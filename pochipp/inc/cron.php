<?php
namespace POCHIPP;

if ( ! defined( 'ABSPATH' ) ) exit;

const AUTO_UPDATE_LAST_ID_OPTION = 'pochipp_auto_update_last_id';
const AUTO_UPDATE_LOCK_TRANSIENT = 'pochipp_auto_update_lock';

add_action( 'pochipp_auto_update_cron', '\POCHIPP\run_auto_update_cron' );
add_action( 'init', '\POCHIPP\schedule_auto_update' );

function schedule_auto_update() {
	if ( ! wp_next_scheduled( 'pochipp_auto_update_cron' ) ) {
		wp_schedule_event( time(), 'hourly', 'pochipp_auto_update_cron' );
	}
}

function clear_auto_update_schedule() {
	wp_clear_scheduled_hook( 'pochipp_auto_update_cron' );
}

function run_auto_update_cron() {
	if ( ! \POCHIPP::get_setting( 'auto_update' ) ) return;

	if ( get_transient( AUTO_UPDATE_LOCK_TRANSIENT ) ) return;
	set_transient( AUTO_UPDATE_LOCK_TRANSIENT, 1, 2 * MINUTE_IN_SECONDS );

	$scan_size   = 50;

	$last_id = (int) get_option( AUTO_UPDATE_LAST_ID_OPTION, 0 );
	$ids     = get_update_target_ids_after( $last_id, $scan_size );

	if ( empty( $ids ) ) {
		update_option( AUTO_UPDATE_LAST_ID_OPTION, 0 );
		return;
	}

	$updated_count = 0;
	$last_processed_id = 0;
	foreach ( $ids as $pid ) {
		$update_result = update_item_data( $pid );
		if ( null === $update_result ) {
			continue;
		}

		$updated_count++;
		$last_processed_id = $pid;
		usleep( 2000000 );
	}

	update_option( AUTO_UPDATE_LAST_ID_OPTION, (int) $last_processed_id );
}

function get_update_target_ids_after( $last_id, $limit ) {
	$last_id = (int) $last_id;
	$limit   = (int) $limit;

	$filter = function( $where ) use ( $last_id ) {
		if ( $last_id <= 0 ) return $where;

		global $wpdb;
		return $where . $wpdb->prepare( " AND {$wpdb->posts}.ID > %d", $last_id );
	};

	add_filter( 'posts_where', $filter );

	$query = new \WP_Query( [
		'post_type'      => \POCHIPP::POST_TYPE_SLUG,
		'post_status'    => 'publish',
		'posts_per_page' => $limit,
		'fields'         => 'ids',
		'orderby'        => 'ID',
		'order'          => 'ASC',
		'no_found_rows'  => true,
	] );

	remove_filter( 'posts_where', $filter );

	return $query->posts;
}
