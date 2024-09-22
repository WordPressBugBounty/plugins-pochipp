<?php
namespace POCHIPP;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * 価格の自動更新
 */
add_action( 'wp_ajax_auto_update', '\POCHIPP\auto_update' );
add_action( 'wp_ajax_nopriv_auto_update', '\POCHIPP\auto_update' );
function auto_update() {

	if ( ! \POCHIPP\check_ajax_nonce() ) {
		wp_die( json_encode( [
			'error' => 'nonce error',
		] ) );
	};

	$pidStr = \POCHIPP::get_sanitized_data( $_POST, 'pids', 'text', '' );
	$pids   = explode( ',', $pidStr );

	$resuts = [];
	foreach ( $pids as $pid ) {
		$metadata = get_post_meta( $pid, \POCHIPP::META_SLUG, true );
		$metadata = json_decode( $metadata, true ) ?: [];
		$itemcode = \POCHIPP::get_itemcode_from_metadata( $metadata );

		// 商品データ取得
		$datas = \POCHIPP::get_item_data( $metadata['searched_at'], $itemcode );

		// 何かエラーがあれば
		if ( isset( $datas['error'] ) ) {
			$resuts[ $pid ] = [
				'error' => $datas['error'],
			];

			// Amazon/楽天で商品データ取得失敗
			$add_data = [];
			if ( $datas['error']['code'] === 'InvalidParameterValue' || $datas['error']['code'] === 'no_item' || $datas['error']['code'] === 404 ) {
				$add_data['link_broken'] = true;
			}

			$add_data['last_searched'] = wp_date( 'Y/m/d H:i' );
			update_post_meta( $pid, \POCHIPP::META_SLUG, json_encode( array_merge( $metadata, $add_data ), JSON_UNESCAPED_UNICODE ) );
			continue;
		};

		// 更新
		$add_data     = [
			'link_broken'   => false,
			'last_searched' => wp_date( 'Y/m/d H:i' ),
		];
		$new_metadata = array_merge( $metadata, array_merge( $datas[0], $add_data ) );
		$updated      = update_post_meta( $pid, \POCHIPP::META_SLUG, json_encode( $new_metadata, JSON_UNESCAPED_UNICODE ) );

		$resuts[ $pid ] = [
			'updated' => $updated,
		];

	}

	wp_die( json_encode( [
		'result' => json_encode( $resuts, JSON_UNESCAPED_UNICODE ),
	] ) );
}
