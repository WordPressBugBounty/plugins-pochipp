<?php
namespace POCHIPP;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Register shortcode
 */
add_shortcode( 'pochipp', '\POCHIPP\sc_pochipp' );
function sc_pochipp( $atts ) {

	if ( ! isset( $atts['id'] ) ) return '';

	$args = [ 'pid' => $atts['id'] ];
	if ( isset( $atts['title'] ) ) {
		$args['title'] = $atts['title'];
	}
	if ( isset( $atts['info'] ) ) {
		$args['info'] = $atts['info'];
	}

	return \POCHIPP\cb_pochipp_block( $args, null );
}

/**
 *  インラインボタン
 */
add_shortcode( 'pochipp_btn', '\POCHIPP\sc_pochipp_btn' );
function sc_pochipp_btn( $atts, $content = null ) {
	return \POCHIPP\render_inline_element( 'button', $atts, $content );
}

/**
 *  インラインリンク
 */
add_shortcode( 'pochipp_link', '\POCHIPP\sc_pochipp_link' );
function sc_pochipp_link( $atts, $content = null ) {
	return \POCHIPP\render_inline_element( 'link', $atts, $content );
}

/**
 *  インライン画像
 */
add_shortcode( 'pochipp_img', '\POCHIPP\sc_pochipp_img' );
function sc_pochipp_img( $atts, $content = null ) {
	return \POCHIPP\render_inline_element( 'img', $atts, $content );
}

function render_inline_element( $type, $atts, $content ) {
	if ( ! isset( $atts['id'] ) ) return '';

	$pid   = $atts['id'];
	$shop  = $atts['shop'] ?? '';
	$cvKey = $atts['cvkey'] ?? '';

	// メタデータ取得
	if ( $pid ) {
		$metadata          = get_post_meta( $pid, \POCHIPP::META_SLUG, true );
		$metadata          = json_decode( $metadata, true ) ?: [];
		$metadata['cvkey'] = $cvKey;
	}

	// 商品未選択時
	if ( empty( $metadata ) ) {
		return '';
	}

	// shopの引数が正しいものかチェック
	$allowed_shop_keys = array_merge( \POCHIPP::$shop_list, [ 'custom1', 'custom2' ] );
	if ( ! in_array( $shop, $allowed_shop_keys, true ) ) {
		return '';
	}

	return \POCHIPP\get_inline_element( $pid, $type, $shop, $content, $metadata );
}
