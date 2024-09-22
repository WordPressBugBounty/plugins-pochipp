<?php
namespace Pochipp;

defined( 'ABSPATH' ) || exit;

add_action( 'admin_notices', function() {
	$post_type = \POCHIPP::get_sanitized_data( $_GET, 'post_type', 'text' );
	if ( \POCHIPP::POST_TYPE_SLUG !== $post_type ) return;
	?>
	<!-- 必要な際にここにバナーを追加 -->
	<!-- <div class="pchpp-notice"></div> -->
	<?php
} );
