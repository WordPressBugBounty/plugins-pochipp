<?php
namespace POCHIPP;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Creators APIのエラーメッセージを日本語化
 *
 * @param string $code エラーコード
 * @param string $description エラー説明
 * @return string 日本語化されたエラーメッセージ
 */
function get_creators_api_error_text( $code, $description = '' ) {
	switch ( $code ) {
		case 'InvalidClient':
			$message = 'クライアントIDまたはクライアントシークレットが無効です。';
			break;
		case 'InvalidToken':
		case 'ExpiredToken':
			$message = 'アクセストークンが無効または期限切れです。';
			// トークンキャッシュをクリア
			CreatorsApiClient::clear_token_cache();
			break;
		case 'InvalidPartnerTag':
			$message = 'パートナータグ（トラッキングID）が無効です。';
			break;
		case 'ItemNotFound':
		case 'NoResults':
			$message = '指定された商品が見つかりませんでした。';
			break;
		case 'TooManyRequests':
			$message = 'リクエスト回数が多すぎます。しばらく時間を空けてください。';
			break;
		case 'ServiceUnavailable':
			$message = 'サービスが一時的に利用できません。';
			break;
		case 'InvalidParameterValue':
		case 'MissingParameter':
			$message = 'キーワードを入力してください。';
			break;
		default:
			$message = $description ?: '不明なエラーが発生しました。';
			break;
	}
	return $message;
}

/**
 * Creators APIが利用可能かチェック
 *
 * @return bool
 */
function is_creators_api_available() {
	$client_id     = \POCHIPP::get_setting( 'amazon_creators_client_id' );
	$client_secret = \POCHIPP::get_setting( 'amazon_creators_client_secret' );
	$tracking_id   = \POCHIPP::get_setting( 'amazon_traccking_id' );

	return ! empty( $client_id ) && ! empty( $client_secret ) && ! empty( $tracking_id );
}

/**
 * PA-APIが利用可能かチェック
 *
 * @return bool
 */
function is_paapi_available() {
	$access_key  = \POCHIPP::get_setting( 'amazon_access_key' );
	$secret_key  = \POCHIPP::get_setting( 'amazon_secret_key' );
	$tracking_id = \POCHIPP::get_setting( 'amazon_traccking_id' );

	return ! empty( $access_key ) && ! empty( $secret_key ) && ! empty( $tracking_id );
}

/**
 * CreatorsApiClientインスタンスを取得
 *
 * @return CreatorsApiClient|null
 */
function get_creators_api_client() {
	static $client = null;

	if ( null === $client && is_creators_api_available() ) {
		$client = new CreatorsApiClient(
			\POCHIPP::get_setting( 'amazon_creators_client_id' ),
			\POCHIPP::get_setting( 'amazon_creators_client_secret' ),
			\POCHIPP::get_setting( 'amazon_traccking_id' )
		);
	}

	return $client;
}
