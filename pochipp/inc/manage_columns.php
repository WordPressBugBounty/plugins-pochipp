<?php
namespace POCHIPP;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * manage_posts_columns
 */
add_filter( 'manage_posts_columns', '\POCHIPP\add_custom_post_columns' );
function add_custom_post_columns( $columns ) {
	global $post_type;

	if ( \POCHIPP::POST_TYPE_SLUG === $post_type ) {

		$columns['pimg']        = '商品画像';
		$columns['pid']         = 'ID';
		$columns['searched_at'] = '検索元';
		$columns['used_at']     = '使用ページ';

	}

	if ( \POCHIPP::POST_TYPE_SLUG === $post_type && \POCHIPP::get_setting( 'auto_update' ) ) {
		$columns['link_alive'] = 'リンク切れ';
	}

	return $columns;
}

/**
 * manage_posts_custom_column
 */
add_action( 'manage_posts_custom_column', '\POCHIPP\output_custom_post_columns', 10, 2 );
function output_custom_post_columns( $column_name, $post_id ) {
	global $post_type;

	if ( \POCHIPP::POST_TYPE_SLUG !== $post_type ) return;

	$pchpp_metas = get_post_meta( $post_id, \POCHIPP::META_SLUG, true );
	$pchpp_metas = json_decode( $pchpp_metas, true ) ?: [];

	if ( 'searched_at' === $column_name ) {

		$searched_at = $pchpp_metas['searched_at'] ?? '';

		if ( 'amazon' === $searched_at ) {
			echo 'Amazon';
		} elseif ( 'rakuten' === $searched_at ) {
			echo '楽天市場';
		} elseif ( 'yahoo' === $searched_at ) {
			echo 'Yahoo';
		} else {
			echo '-';
		}
	} elseif ( 'pid' === $column_name ) {

		echo esc_html( $post_id );

	} elseif ( 'pimg' === $column_name ) {

		$image_id    = $pchpp_metas['image_id'] ?? '';
		$image_url   = $pchpp_metas['image_url'] ?? '';
		$searched_at = $pchpp_metas['searched_at'] ?? '';

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo \POCHIPP::get_item_image( $image_id, $image_url, $searched_at );

	} elseif ( 'used_at' === $column_name ) {
		$args                          = [
			'post_type'      => [ 'post', 'page' ],
			'no_found_rows'  => true,
			'posts_per_page' => -1,
		];
		$pattern_block                 = "/wp:pochipp\/linkbox.+\"pid\":$post_id/";
		$pattern_shortcode             = "/pochipp id=\"$post_id\"/";
		$pattern_inline_shortcode      = "/pochipp_btn id=\"$post_id\"/";
		$pattern_inline_link_shortcode = "/pochipp_link id=\"$post_id\"/";
		$pattern_inline_img_shortcode  = "/pochipp_img id=\"$post_id\"/";

		$used_count = get_post_meta( $post_id, 'used_count', true ) ?: 0;

		$count     = 0;
		$the_query = new \WP_Query( $args );
		foreach ( $the_query->posts as $post_data ) {
			$the_id      = $post_data->ID;
			$title       = $post_data->post_title;
			$the_content = $post_data->post_content;
			if (
				! preg_match( $pattern_block, $the_content )
				&& ! preg_match( $pattern_shortcode, $the_content )
				&& ! preg_match( $pattern_inline_shortcode, $the_content )
				&& ! preg_match( $pattern_inline_link_shortcode, $the_content )
				&& ! preg_match( $pattern_inline_img_shortcode, $the_content )
			) {
				continue;
			}

			$ttl_width = mb_strwidth( $title, 'UTF-8' );
			if ( 30 < $ttl_width ) {
				$title = mb_strimwidth( $title, 0, 30 ) . '...';
			} elseif ( 0 === $ttl_width ) {
				$title = '(タイトルなし)';
			}

			$edit_link = admin_url( 'post.php?post=' . $the_id . '&action=edit' );
			echo '<a href="' . esc_url( $edit_link ) . '" class="pchpp-usepage" data-title="' . esc_attr( $title ) . '">' .
				esc_html( $the_id )
			. '</a>';

			$count++;
		}
		wp_reset_postdata();

		// 使用回数、変わってた時だけ更新
		if ( (int) $used_count !== $count ) {
			update_post_meta( $post_id, 'used_count', $count );
		}
	} elseif ( 'link_alive' === $column_name ) {
		$link_broken = $pchpp_metas['link_broken'] ?? null;
		if ( is_null( $link_broken ) ) {
			echo '
				<a href="javascript:void(0)">
					<div class="link_alive__tooltip">
						リンク切れチェックは以下の方法で検索した場合のみ利用可能です。
						<br />
						・Amazon PA-API
						<br />
						・楽天API
					</div>
					<div class="link_alive__content">
						<span>チェックなし</span>
						<img src="' . esc_url( POCHIPP_URL ) . 'assets/img/hatena.svg" alt="" width="18" height="18">
					</div>
				</a>
			';
		} elseif ( $link_broken ) {
			echo '
				<div class="link_alive__content">
					<img src="' . esc_url( POCHIPP_URL ) . 'assets/img/attension.svg" alt="" width="18" height="18">
					<span>要チェック</span>
				</div>
			';
		} else {
			echo '
				<div class="link_alive__content">
					<img src="' . esc_url( POCHIPP_URL ) . 'assets/img/check.svg" alt="" width="18" height="18">
					<span>問題なし</span>
				</div>
			';
		}
	}
}
