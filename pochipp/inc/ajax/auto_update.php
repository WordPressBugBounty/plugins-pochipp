<?php
namespace POCHIPP;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * 価格の自動更新
 */
add_action( 'wp_ajax_auto_update', '\POCHIPP\auto_update' );
function auto_update() {

	if ( ! current_user_can( 'edit_posts' ) ) {
		wp_die( json_encode( [
			'error' => 'forbidden',
		] ) );
	}

	if ( ! \POCHIPP\check_ajax_nonce() ) {
		wp_die( json_encode( [
			'error' => 'nonce error',
		] ) );
	};

	$pidStr = \POCHIPP::get_sanitized_data( $_POST, 'pids', 'text', '' );
	$pids   = explode( ',', $pidStr );

	$resuts = [];
	foreach ( $pids as $pid ) {
		$update_result = \POCHIPP\update_item_data( $pid );
		if ( null !== $update_result ) {
			$resuts[ $pid ] = $update_result;
		}
	}

	wp_die( json_encode( [
		'result' => json_encode( $resuts, JSON_UNESCAPED_UNICODE ),
	] ) );
}
