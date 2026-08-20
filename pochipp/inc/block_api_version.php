<?php
namespace POCHIPP;

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! defined( 'POCHIPP_BLOCK_API_VERSION' ) ) {
	define( 'POCHIPP_BLOCK_API_VERSION', 3 );
}

function get_block_api_version() {
	$api_version = (int) POCHIPP_BLOCK_API_VERSION;
	$api_version = (int) apply_filters( 'pochipp_block_api_version', $api_version );

	return 3 === $api_version ? 3 : 2;
}

add_filter( 'block_type_metadata', function( $metadata ) {
	if ( empty( $metadata['name'] ) || 0 !== strpos( $metadata['name'], 'pochipp/' ) ) {
		return $metadata;
	}

	$metadata['apiVersion'] = get_block_api_version();
	return $metadata;
} );
