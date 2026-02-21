<?php
/**
 * 楽天商品検索フォーム
 */
$rakuten_app_id = \POCHIPP::get_setting( 'rakuten_app_id' );

if ( ! $rakuten_app_id ) {
	$pochipp_setting_url = admin_url( 'edit.php?post_type=pochipps&page=pochipp_settings&tab=rakuten' );
	?>
	<p><a href="<?php echo esc_url( $pochipp_setting_url ); ?>" target="_blank">ポチップ設定ページ</a>から、楽天APIの「アプリID」の設定を行ってください。</p>
	<?php
	return;
}

echo $common_parts; // phpcs:ignore
