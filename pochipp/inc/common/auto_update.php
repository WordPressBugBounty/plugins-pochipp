<?php
namespace POCHIPP;

if ( ! defined( 'ABSPATH' ) ) exit;

function update_item_data( $pid ) {
	$metadata = get_post_meta( $pid, \POCHIPP::META_SLUG, true );
	$metadata = json_decode( $metadata, true ) ?: [];

	if ( ! \POCHIPP::should_periodic_update( $metadata ) ) {
		return null;
	}

	$itemcode = \POCHIPP::get_itemcode_from_metadata( $metadata );

	$datas = \POCHIPP::get_item_data( $metadata['searched_at'], $itemcode );

	if ( isset( $datas['error'] ) ) {
		$add_data = [];
		if ( $datas['error']['code'] === 'InvalidParameterValue' || $datas['error']['code'] === 'no_item' || $datas['error']['code'] === 404 ) {
			$add_data['link_broken'] = true;
		}

		$add_data['last_searched'] = wp_date( 'Y/m/d H:i' );
		update_post_meta( $pid, \POCHIPP::META_SLUG, json_encode( array_merge( $metadata, $add_data ), JSON_UNESCAPED_UNICODE ) );

		return [
			'error' => $datas['error'],
		];
	}

	$add_data     = [
		'link_broken'   => false,
		'last_searched' => wp_date( 'Y/m/d H:i' ),
	];
	$new_metadata = array_merge( $metadata, array_merge( $datas[0], $add_data ) );
	$updated      = update_post_meta( $pid, \POCHIPP::META_SLUG, json_encode( $new_metadata, JSON_UNESCAPED_UNICODE ) );

	return [
		'updated' => $updated,
	];
}
