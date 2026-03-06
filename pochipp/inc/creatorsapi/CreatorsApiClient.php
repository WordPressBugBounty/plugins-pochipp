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
	const AUTH_ENDPOINT_V33 = 'https://api.amazon.com/auth/o2/token';
	const AUTH_ENDPOINT_V23 = 'https://creatorsapi.auth.us-west-2.amazoncognito.com/oauth2/token';
	const API_BASE_URL      = 'https://creatorsapi.amazon/catalog/v1';

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
		$attempts = [];
		$token    = $this->request_access_token_v33();

		if ( isset( $token['error'] ) ) {
			$attempts['v3.3'] = $token['error'];
			$token            = $this->request_access_token_v23();
			$token            = $token;
		}

		if ( isset( $token['error'] ) ) {
			$attempts['v2.3'] = $token['error'];
			return [
				'error' => [
					'code'     => 'creators_api_auth_failed',
					'message'  => \POCHIPP\get_creators_api_error_text( 'creators_api_auth_failed' ),
					'attempts' => $attempts,
				],
			];
		}

		$this->cache_access_token( $token );

		if ( 'v2.3' === $token['auth_flow'] ) {
			return $token['access_token'] . ', Version 2.3';
		}
		return $token['access_token'];
	}

	/**
	 * 商品検索（キーワード）
	 *
	 * @param string $keywords 検索キーワード
	 * @param string $search_index 検索インデックス（デフォルト: All）
	 * @return array レスポンスデータまたはエラー配列
	 */
	public function search_items( $keywords, $search_index = 'All' ) {
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

		return $this->request_api( '/searchItems', $request_body, 'searchItems' );
	}

	/**
	 * 商品取得（ASIN指定）
	 *
	 * @param string|array $item_ids ASINまたはASINの配列
	 * @return array レスポンスデータまたはエラー配列
	 */
	public function get_items( $item_ids ) {
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

		return $this->request_api( '/getItems', $request_body, 'getItems' );
	}

	/**
	 * APIを実行
	 *
	 * @param string $path APIパス
	 * @param array $request_body リクエストボディ
	 * @param string $operation 操作タイプ
	 * @param bool $retry_on_token_error トークンエラー時に再試行するか
	 * @return array レスポンスデータまたはエラー配列
	 */
	private function request_api( $path, $request_body, $operation, $retry_on_token_error = true ) {
		$token = $this->get_access_token();

		if ( is_array( $token ) && isset( $token['error'] ) ) {
			return $token;
		}

		$response = wp_remote_post(
			self::API_BASE_URL . $path,
			[
				'timeout' => 10,
				'headers' => [
					'Authorization' => 'Bearer ' . $token,
					'Content-Type'  => 'application/json',
					'x-marketplace' => 'www.amazon.co.jp',
				],
				'body' => wp_json_encode( $request_body ),
			]
		);

		$result = $this->handle_response( $response, $operation );

		if ( $retry_on_token_error && isset( $result['error']['code'] ) && $this->is_token_error_code( $result['error']['code'] ) ) {
			self::clear_token_cache();
			return $this->request_api( $path, $request_body, $operation, false );
		}

		return $result;
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

		$status_code = (int) wp_remote_retrieve_response_code( $response );
		$body = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( ! $body || ! is_array( $body ) ) {
			if ( 400 <= $status_code ) {
				return [
					'error' => [
						'code'    => 'http_' . $status_code,
						'message' => 'Creators APIからエラーレスポンスが返されました。',
					],
				];
			}
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

		if ( 400 <= $status_code || isset( $body['error'] ) || isset( $body['message'] ) || ! empty( $body['fieldList'][0]['message'] ) ) {
			$error_code = $body['code'] ?? $body['error'] ?? 'http_' . $status_code;
			return [
				'error' => [
					'code'    => $error_code,
					'message' => \POCHIPP\get_creators_api_error_text(
						$error_code,
						$this->extract_error_message( $body )
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
	 * v3.3形式でアクセストークンを取得
	 *
	 * @return array 正規化済みトークン、またはエラー配列
	 */
	private function request_access_token_v33() {
		$response = wp_remote_post(
			self::AUTH_ENDPOINT_V33,
			[
				'timeout' => 10,
				'headers' => [
					'Content-Type' => 'application/json',
				],
				'body' => wp_json_encode(
					[
						'grant_type'    => 'client_credentials',
						'client_id'     => $this->client_id,
						'client_secret' => $this->client_secret,
						'scope'         => 'creatorsapi::default',
					]
				),
			]
		);

		return $this->normalize_token_response( $response, 'v3.3' );
	}

	/**
	 * v2.3形式でアクセストークンを取得
	 *
	 * @return array 正規化済みトークン、またはエラー配列
	 */
	private function request_access_token_v23() {
		$credentials = base64_encode( $this->client_id . ':' . $this->client_secret );

		$response = wp_remote_post(
			self::AUTH_ENDPOINT_V23,
			[
				'timeout' => 10,
				'headers' => [
					'Authorization' => 'Basic ' . $credentials,
					'Content-Type'  => 'application/x-www-form-urlencoded',
				],
				'body' => 'grant_type=client_credentials&scope=creatorsapi/default',
			]
		);

		return $this->normalize_token_response( $response, 'v2.3' );
	}

	/**
	 * トークンレスポンスを正規化
	 *
	 * @param array|\WP_Error $response wp_remote_postのレスポンス
	 * @param string $auth_flow 認証フロー名
	 * @return array 正規化済みトークン、またはエラー配列
	 */
	private function normalize_token_response( $response, $auth_flow ) {
		if ( is_wp_error( $response ) ) {
			return [
				'error' => [
					'code'      => 'token_request_failed',
					'message'   => $response->get_error_message(),
					'auth_flow' => $auth_flow,
				],
			];
		}

		$status_code = (int) wp_remote_retrieve_response_code( $response );
		$body        = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( ! is_array( $body ) ) {
			return [
				'error' => [
					'code'      => 400 <= $status_code ? 'http_' . $status_code : 'decode_error',
					'message'   => 'トークン取得レスポンスを解析できませんでした。',
					'auth_flow' => $auth_flow,
				],
			];
		}

		if ( 400 <= $status_code || isset( $body['error'] ) || isset( $body['message'] ) || ! empty( $body['fieldList'][0]['message'] ) ) {
			$error_code = $body['error'] ?? $body['code'] ?? 'http_' . $status_code;
			return [
				'error' => [
					'code'      => $error_code,
					'message'   => \POCHIPP\get_creators_api_error_text(
						$error_code,
						$this->extract_error_message( $body )
					),
					'auth_flow' => $auth_flow,
				],
			];
		}

		if ( empty( $body['access_token'] ) ) {
			return [
				'error' => [
					'code'      => 'no_token',
					'message'   => 'アクセストークンが返されませんでした。',
					'auth_flow' => $auth_flow,
				],
			];
		}

		return [
			'access_token' => $body['access_token'],
			'auth_flow'    => $auth_flow,
			'expires_in'   => isset( $body['expires_in'] ) ? (int) $body['expires_in'] : 3600,
		];
	}

	/**
	 * トークンをキャッシュ
	 *
	 * @param array $token_data 正規化済みトークン
	 */
	private function cache_access_token( $token_data ) {
		$expires_in     = ! empty( $token_data['expires_in'] ) ? (int) $token_data['expires_in'] : 3600;
		$cache_duration = max( 1, (int) ( $expires_in * 0.8 ) );

		set_transient(
			self::TOKEN_TRANSIENT_KEY,
			[
				'access_token' => $token_data['access_token'],
				'auth_flow'    => $token_data['auth_flow'],
				'expires_in'   => $expires_in,
			],
			$cache_duration
		);
	}

	/**
	 * エラーメッセージを抽出
	 *
	 * @param array $body レスポンスボディ
	 * @return string
	 */
	private function extract_error_message( $body ) {
		if ( ! empty( $body['error_description'] ) ) {
			return $body['error_description'];
		}

		if ( ! empty( $body['message'] ) ) {
			return $body['message'];
		}

		if ( ! empty( $body['fieldList'][0]['message'] ) ) {
			return $body['fieldList'][0]['message'];
		}

		return '';
	}

	/**
	 * トークンエラーかどうかを判定
	 *
	 * @param string $code エラーコード
	 * @return bool
	 */
	private function is_token_error_code( $code ) {
		$normalized_code = strtolower( preg_replace( '/[^a-z]/', '', (string) $code ) );

		return in_array( $normalized_code, [ 'invalidtoken', 'expiredtoken' ], true );
	}

	/**
	 * トークンキャッシュをクリア
	 */
	public static function clear_token_cache() {
		delete_transient( self::TOKEN_TRANSIENT_KEY );
	}
}
