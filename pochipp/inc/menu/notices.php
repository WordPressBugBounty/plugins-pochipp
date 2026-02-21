<?php
namespace POCHIPP;

if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'admin_notices', '\POCHIPP\show_rakuten_api_replace_notice' );
function show_rakuten_api_replace_notice() {
	$post_type  = \POCHIPP::get_sanitized_data( $_GET, 'post_type', 'text', '' );
	$page       = \POCHIPP::get_sanitized_data( $_GET, 'page', 'text', '' );
	$rakuten_app_id = trim( (string) \POCHIPP::get_setting( 'rakuten_app_id' ) );
	$access_key = trim( (string) \POCHIPP::get_setting( 'rakuten_access_key' ) );

	if ( 'pochipps' !== $post_type || 'pochipp_settings' !== $page || ! $rakuten_app_id || $access_key ) {
		return;
	}
	?>
	<div class="notice notice-warning">
		<p>Pochipp: お使いの楽天APIは現在非推奨です。2026年5月13日までに<a href="https://pochipp.com/6102/" target="_blank" rel="noopener noreferrer">こちらのページ</a>に従って移行手続きをお願いします。</p>
	</div>
	<?php
}
