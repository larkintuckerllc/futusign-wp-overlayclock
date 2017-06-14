<?php
/**
 * The inactive functionality of the plugin.
 *
 * @link       https://bitbucket.org/futusign/futusign-wp-overlayclock
 * @since      0.1.0
 *
 * @package    futusign_overlayclock
 * @subpackage futusign_overlayclock/inactive
 */
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * The inactive functionality of the plugin.
 *
 * @package    futusign_overlayclock
 * @subpackage futusign_overlayclock/inactive
 * @author     John Tucker <john@larkintuckerllc.com>
 */
class Futusign_OverlayClock_Inactive {
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    0.1.0
	 */
	public function __construct() {
	}
	/**
	 * Display missing plugin dependency notices.
	 *
	 * @since    0.3.0
	 */
	public function missing_plugins_notice() {
		if ( ! Futusign_OverlayClock::is_plugin_active( 'futusign' ) ) {
			include plugin_dir_path( __FILE__ ) . 'partials/futusign-overlayclock-missing-futusign.php';
		}
		if ( ! Futusign_OverlayClock::is_plugin_active( 'futusignz-overlay' ) ) {
			include plugin_dir_path( __FILE__ ) . 'partials/futusign-overlayclock-missing-futusignz-overlay.php';
		}
	}
}
