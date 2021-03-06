<?php
/**
 * The common functionality of the plugin.
 *
 * @link       https://bitbucket.org/futusign/futusign-wp-overlayclock
 * @since      0.1.0
 *
 * @package    futusign_overlayclock
 * @subpackage futusign_overlayclock/common
 */
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * The common functionality of the plugin.
 *
 * @package    futusign_overlayclock
 * @subpackage futusign_overlayclock/common
 * @author     John Tucker <john@larkintuckerllc.com>
 */
class Futusign_OverlayClock_Common {
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    0.1.0
	 */
	public function __construct() {
	}
	/**
   * Add rewrite rules
   *
   * @since    0.4.0
   */
   public function add_rewrite_rules() {
	   add_rewrite_rule( '^fs-oc-endpoint/?', 'index.php?futusign_oc_endpoint=1', 'top' );
	 }
}
