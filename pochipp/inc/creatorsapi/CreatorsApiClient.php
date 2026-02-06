<?php
namespace POCHIPP;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Amazon Creators API クライアント
 */
class CreatorsApiClient {

	private $client_id;
	private $client_secret;
	private $partner_tag;

	// トークンキャッシュ用のtransientキー
	const TOKEN_TRANSIENT_KEY = 'pochipp_creators_api_token';

	// APIエンドポイント
	const AUTH_ENDPOINT = 'https://creatorsapi.auth.us-west-2.amazoncognito.com/oauth2/token';
	const API_BASE_URL  = 'https://creatorsapi.amazon/catalog/v1';

	/**
	 * コンストラクタ
	 */
	public function __construct( $client_id, $client_secret, $partner_tag ) {
		$this->client_id     = $client_id;
		$this->client_secret = $client_secret;
		$this->partner_tag   = $partner_tag;
	}

	/**
	 * アクセストークン取得（キャッシュ対応）
	 *
	 * @return string|array トークン文字列、またはエラー配列
	 */
	public function get_access_token() {
		// キャッシュチェック
		$cached_token = get_transient( self::TOKEN_TRANSIENT_KEY );
		if ( $cached_token ) {
			return $cached_token;
		}

		// 新規トークン取得
		$credentials = base64_encode( $this->client_id . ':' . $this->client_secret );

		$response = wp_remote_post( self::AUTH_ENDPOINT, [
			'timeout' => 10,
			'headers' => [
				'Authorization' => 'Basic ' . $credentials,
				'Content-Type'  => 'application/x-www-form-urlencoded',
			],
			'body' => 'grant_type=client_credentials&scope=creatorsapi/default',
		] );

		if ( is_wp_error( $response ) ) {
			return [
				'error' => [
					'code'    => 'token_request_failed',
					'message' => $response->get_error_message(),
				],
			];
		}

		$body = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( isset( $body['error'] ) ) {
			return [
				'error' => [
					'code'    => $body['error'],
					'message' => $body['error_description'] ?? 'トークン取得に失敗しました。',
				],
			];
		}

		if ( empty( $body['access_token'] ) ) {
			return [
				'error' => [
					'code'    => 'no_token',
					'message' => 'アクセストークンが返されませんでした。',
				],
			];
		}

		// トークンをキャッシュ（有効期限の80%程度でリフレッシュ）
		$expires_in     = isset( $body['expires_in'] ) ? intval( $body['expires_in'] ) : 3600;
		$cache_duration = intval( $expires_in * 0.8 );
		set_transient( self::TOKEN_TRANSIENT_KEY, $body['access_token'], $cache_duration );

		return $body['access_token'];
	}

	/**
	 * 商品検索（キーワード）
	 *
	 * @param string $keywords 検索キーワード
	 * @param string $search_index 検索インデックス（デフォルト: All）
	 * @return array レスポンスデータまたはエラー配列
	 */
	public function search_items( $keywords, $search_index = 'All' ) {
		$token = $this->get_access_token();

		if ( is_array( $token ) && isset( $token['error'] ) ) {
			return $token;
		}

		$request_body = [
			'marketplace' => 'www.amazon.co.jp',
			'partnerTag'  => $this->partner_tag,
			'keywords'    => $keywords,
			'resources'   => [
				'images.primary.small',
				'images.primary.large',
				'itemInfo.byLineInfo',
				'itemInfo.title',
				'itemInfo.classifications',
				'itemInfo.productInfo',
				'offersV2.listings.price',
				'parentASIN',
			],
		];

		// search_index が 'All' 以外の場合のみ追加
		if ( 'All' !== $search_index ) {
			$request_body['searchIndex'] = $search_index;
		}

		$response = wp_remote_post( self::API_BASE_URL . '/searchItems', [
			'timeout' => 10,
			'headers' => [
				'Authorization' => 'Bearer ' . $token . ', Version 2.3',
				'Content-Type'  => 'application/json',
				'x-marketplace' => 'www.amazon.co.jp',
			],
			'body' => json_encode( $request_body ),
		] );

		return $this->handle_response( $response, 'searchItems' );
	}

	/**
	 * 商品取得（ASIN指定）
	 *
	 * @param string|array $item_ids ASINまたはASINの配列
	 * @return array レスポンスデータまたはエラー配列
	 */
	public function get_items( $item_ids ) {
		$token = $this->get_access_token();

		if ( is_array( $token ) && isset( $token['error'] ) ) {
			return $token;
		}

		// ASINの配列化
		if ( ! is_array( $item_ids ) ) {
			$item_ids = [ $item_ids ];
		}

		$request_body = [
			'itemIds'     => $item_ids,
			'itemIdType'  => 'ASIN',
			'marketplace' => 'www.amazon.co.jp',
			'partnerTag'  => $this->partner_tag,
			'resources'   => [
				'images.primary.small',
				'images.primary.large',
				'itemInfo.byLineInfo',
				'itemInfo.title',
				'itemInfo.classifications',
				'itemInfo.productInfo',
				'offersV2.listings.price',
				'parentASIN',
			],
		];

		$response = wp_remote_post( self::API_BASE_URL . '/getItems', [
			'timeout' => 10,
			'headers' => [
				'Authorization' => 'Bearer ' . $token . ', Version 2.3',
				'Content-Type'  => 'application/json',
				'x-marketplace' => 'www.amazon.co.jp',
			],
			'body' => json_encode( $request_body ),
		] );

		return $this->handle_response( $response, 'getItems' );
	}

	/**
	 * APIレスポンスを処理
	 *
	 * @param array|WP_Error $response wp_remote_postのレスポンス
	 * @param string $operation 操作タイプ ('searchItems' or 'getItems')
	 * @return array レスポンスデータまたはエラー配列
	 */
	private function handle_response( $response, $operation ) {
		if ( is_wp_error( $response ) ) {
			return [
				'error' => [
					'code'    => 'api_request_failed',
					'message' => $response->get_error_message(),
				],
			];
		}

		$body = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( ! $body || ! is_array( $body ) ) {
			return [
				'error' => [
					'code'    => 'decode_error',
					'message' => 'APIから正しいデータが返ってきませんでした。',
				],
			];
		}

		// エラーレスポンスの処理
		if ( isset( $body['errors'] ) && ! empty( $body['errors'] ) ) {
			$error = $body['errors'][0];
			return [
				'error' => [
					'code'    => $error['code'] ?? 'unknown',
					'message' => \POCHIPP\get_creators_api_error_text(
						$error['code'] ?? '',
						$error['message'] ?? ''
					),
				],
			];
		}

		// 結果データの取得
		$result_key = 'searchItems' === $operation ? 'searchResult' : 'itemsResult';
		if ( empty( $body[ $result_key ] ) ) {
			return [
				'error' => [
					'code'    => 'no_result',
					'message' => '商品データが見つかりませんでした。',
				],
			];
		}

		return $body[ $result_key ];
	}

	/**
	 * トークンキャッシュをクリア
	 */
	public static function clear_token_cache() {
		delete_transient( self::TOKEN_TRANSIENT_KEY );
	}
}
