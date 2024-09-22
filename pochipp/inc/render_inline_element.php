<?php
namespace POCHIPP;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * インラインボタン出力用
 */
function get_inline_element( $pid, $type, $shop, $content, $pdata = [] ) {

	$keywords             = $pdata['keywords'] ?? '';
	$asin                 = $pdata['asin'] ?? '';
	$searched_at          = $pdata['searched_at'] ?? '';
	$image_url            = $pdata['image_url'] ?? '';
	$image_id             = $pdata['image_id'] ?? '';
	$custom_btn_url       = $pdata['custom_btn_url'] ?? '';
	$custom_btn_text      = $pdata['custom_btn_text'] ?? '';
	$custom_btn_url_2     = $pdata['custom_btn_url_2'] ?? '';
	$custom_btn_text_2    = $pdata['custom_btn_text_2'] ?? '';
	$is_all_search_result = $pdata['is_all_search_result'] ?? '';
	$cvKey                = $pdata['cvkey'] ?? '';

	// もしも用aid
	$aid = \POCHIPP::get_setting( "moshimo_{$shop}_aid" );

	$main_url = '';
	// AmazonボタンURL
	if ( 'amazon' === $shop ) {
		$amazon_affi_url        = $pdata['amazon_affi_url'] ?? '';
		$show_amazon_detail_url = $asin && ! $is_all_search_result;
		$amazon_url             = $show_amazon_detail_url ? 'https://www.amazon.co.jp/dp/' . $asin : \POCHIPP::get_amazon_searched_url( $keywords );
		$main_url               = \POCHIPP::get_amazon_affi_url( $amazon_affi_url, $amazon_url, $aid );
	}

	// 楽天ボタンURL
	if ( 'rakuten' === $shop ) {
		$rakuten_detail_url      = $pdata['rakuten_detail_url'] ?? '';
		$show_rakuten_detail_url = $rakuten_detail_url && ! $is_all_search_result;
		$rakuten_url             = $show_rakuten_detail_url ? $rakuten_detail_url : \POCHIPP::get_rakuten_searched_url( $keywords );
		$main_url                = \POCHIPP::get_rakuten_affi_url( $rakuten_url, $aid );
	}

	// YahooボタンURL
	if ( 'yahoo' === $shop ) {
		$yahoo_detail_url      = $pdata['yahoo_detail_url'] ?? '';
		$show_yahoo_detail_url = $yahoo_detail_url && ! $is_all_search_result;
		$yahoo_url             = $show_yahoo_detail_url ? $yahoo_detail_url : \POCHIPP::get_yahoo_searched_url( $keywords );
		$main_url              = \POCHIPP::get_yahoo_affi_url( $yahoo_url, $aid );
	}

	// メルカリボタンURL
	if ( 'mercari' === $shop ) {
		$mercari_url = \POCHIPP::get_mercari_searched_url( $keywords );
		$main_url    = \POCHIPP::get_mercari_affi_url( $mercari_url );
	}

	// 商品画像
	$item_image = \POCHIPP::get_item_image( $image_id, $image_url, $searched_at );

	$is_blank = \POCHIPP::get_setting( 'show_amazon_normal_link' );
	if ( $is_blank ) {
		$rel_target = 'rel="nofollow noopener" target="_blank"';
	} else {
		$rel_target = 'rel="nofollow"';
	}

	// 追加属性
	$ex_props = '';
	if ( $cvKey ) {
		$ex_props .= ' data-cvkey="' . esc_attr( $cvKey ) . '"';
	}

	ob_start();
	\POCHIPP\render_pochipp_element([
		'type'              => $type,
		'main_url'          => $main_url,
		'item_image'        => $item_image,
		'aid'               => $aid,
		'custom_btn_url'    => $custom_btn_url,
		'custom_btn_text'   => $custom_btn_text,
		'custom_btn_url_2'  => $custom_btn_url_2,
		'custom_btn_text_2' => $custom_btn_text_2,
		'rel_target'        => $rel_target,
		// 追加プロパティ
		'content'           => $content,
		'shop'              => $shop,
		'ex_props'          => $ex_props,
	]);
	return ob_get_clean();
}


function render_pochipp_element( $btn_data = [] ) {

	$type              = $btn_data['type'] ?? '';
	$url               = $btn_data['main_url'] ?? '';
	$item_image        = $btn_data['item_image'] ?? '';
	$rel_target        = $btn_data['rel_target'] ?? '';
	$aid               = $btn_data['aid'] ?? '';
	$custom_btn_url    = $btn_data['custom_btn_url'] ?? '';
	$custom_btn_text   = $btn_data['custom_btn_text'] ?? '';
	$custom_btn_url_2  = $btn_data['custom_btn_url_2'] ?? '';
	$custom_btn_text_2 = $btn_data['custom_btn_text_2'] ?? '';

	$content  = $btn_data['content'] ?? '';
	$shop     = $btn_data['shop'] ?? '';
	$ex_props = $btn_data['ex_props'] ?? '';

	$imp_tag   = '';
	$btn_text  = '';
	$add_class = '';
	if ( 'amazon' === $shop ) {
		// impressionタグ
		$imp_tag = \POCHIPP::get_amazon_imptag( $aid );
		// ボタンテキスト
		$btn_text = $content ?? esc_html( \POCHIPP::get_setting( 'amazon_btn_text' ) );
		// 追加クラス
		$add_class = 'pochipp-inline__btnwrap -amazon';
	}
	if ( 'rakuten' === $shop ) {
		// impressionタグ
		$imp_tag = \POCHIPP::get_rakuten_imptag( $aid );
		// ボタンテキスト
		$btn_text = $content ?? esc_html( \POCHIPP::get_setting( 'rakuten_btn_text' ) );
		// 追加クラス
		$add_class = 'pochipp-inline__btnwrap -rakuten';
	}
	if ( 'yahoo' === $shop ) {
		// impressionタグ
		$imp_tag = \POCHIPP::get_yahoo_imptag( $aid );
		// ボタンテキスト
		$yahoo_text = \POCHIPP::get_setting( 'yahoo_btn_text' );
		$btn_text   = $content ?? esc_html( $yahoo_text );
		// 追加クラス
		$add_class = 'pochipp-inline__btnwrap -yahoo';
	}
	if ( 'mercari' === $shop ) {
		// impressionタグ

		// ボタンテキスト
		$mercari_text = \POCHIPP::get_setting( 'mercari_btn_text' );
		$btn_text     = $content ?? esc_html( $mercari_text );
		// 追加クラス
		$add_class = 'pochipp-inline__btnwrap -mercari';
	}
	if ( 'custom1' === $shop ) {
		$url = $custom_btn_url;
		// ボタンテキスト
		$btn_text = $content ?? esc_html( $custom_btn_text );
		// 追加クラス
		$add_class = 'pochipp-inline__btnwrap -custom';
	}
	if ( 'custom2' === $shop ) {
		$url = $custom_btn_url_2;
		// ボタンテキスト
		$btn_text = $content ?? esc_html( $custom_btn_text_2 );
		// 追加クラス
		$add_class = 'pochipp-inline__btnwrap -custom_2';
	}
	$url = esc_url( $url );

	if ( ! $url ) {
		return '';
	}
	if ( $type === 'button' ) {
		echo "
			<span class=\"$add_class\" $ex_props>
				<a href=\"$url\" class=\"pochipp-inline__btn\" $rel_target>
					$btn_text
				</a>
				$imp_tag
			</span>";
	}
	if ( $type === 'link' ) {
		echo "<a href=\"$url\" $ex_props $rel_target>$btn_text</a>$imp_tag";
	}
	if ( $type === 'img' ) {
		echo "<a href=\"$url\" $rel_target>$item_image</a>$imp_tag";
	}
}
