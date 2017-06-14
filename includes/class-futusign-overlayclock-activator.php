<?php

/**
 * Fired during plugin activation
 *
 * @link       https://bitbucket.org/futusign/futusign-wp-overlayclock
 * @since      0.1.0
 *
 * @package    futusign_overlayclock
 * @subpackage futusign_overlayclock/includes
 */
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * Fired during plugin activation.
 *
 * @since      0.1.0
 * @package    futusign_overlayclock
 * @subpackage futusign_overlayclock/includes
 * @author     John Tucker <john@larkintuckerllc.com>
 */
class Futusign_OverlayClock_Activator {
	/**
	 * Fired during plugin activation.
	 *
	 * @since    0.1.0
	 */
	public static function activate() {
		wp_insert_post(
			array(
				'post_type' => 'futusign_ov_widget',
				'post_status' => 'publish',
				'post_title' => 'Clock',
			)
		);
	}
}
