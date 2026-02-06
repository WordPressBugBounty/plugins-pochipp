<?php
/**
 * Amazon商品検索フォーム
 */
$has_creators_api = \POCHIPP::get_setting( 'amazon_creators_client_id' ) && \POCHIPP::get_setting( 'amazon_creators_client_secret' );
$has_paapi        = \POCHIPP::get_setting( 'amazon_access_key' ) && \POCHIPP::get_setting( 'amazon_secret_key' );

$can_use_amazon_search = apply_filters(
	'pochipp_can_use_amazon_search',
	$has_creators_api || $has_paapi
);

if ( ! $can_use_amazon_search ) {
	$pochipp_setting_url = admin_url( 'edit.php?post_type=pochipps&page=pochipp_settings&tab=amazon' );
	?>
	<p><a href="<?php echo esc_url( $pochipp_setting_url ); ?>">ポチップ設定ページ</a>から、Amazon APIの設定を行ってください。</p>
	<p>もしくは、<a href="https://pochipp.com/pochipp-assist/">Pochipp Assist</a>をご利用ください。</p>
	<?php
	return;
}

echo $common_parts; // phpcs:ignore
