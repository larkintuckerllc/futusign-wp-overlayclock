<?php
/**
 * The public-specific functionality of the plugin.
 *
 * @link       https://bitbucket.org/futusign/futusign-wp-overlayclock
 * @since      0.1.0
 *
 * @package    futusign_overlayclock
 * @subpackage futusign_overlayclock/public
 */
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * The public-specific functionality of the plugin.
 *
 * @package    futusign_overlayclock
 * @subpackage futusign_overlayclock/public
 * @author     John Tucker <john@larkintuckerllc.com>
 */
class Futusign_OverlayClock_Public {
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    0.1.0
	 */
	public function __construct() {
	}
	/**
	 * Return single templates
	 *
	 * @since    0.1.0
	 * @param    string      $single     path to template
	 * @return   string      path to template
	 */
	public function single_template( $single ) {
		global $post;
		if ($post->post_type == 'futusign_ov_widget' && $post->post_title == 'Clock'){
			return plugin_dir_path( __FILE__ ) . 'futusign-overlayclock-template.php';
		}
		return $single;
	}
	/**
	 * Add to query variables
	 *
	 * @since    0.4.0
	 * @param    array      $query_vars     query variables
	 * @return   array      query variables
	 */
	public function query_vars( $query_vars ) {
    $query_vars[] = 'futusign_oc_endpoint';
		return $query_vars;
	}
	/**
	 * Define futusign-monitor endpoint
	 *
	 * @since    0.4.0
	 * @param    array      $query     query
	 */
	public function parse_request( $query ) {
		$query_vars = $query->query_vars;
		if ( array_key_exists( 'futusign_oc_endpoint', $query_vars ) ) {
			require_once plugin_dir_path( __FILE__ ) . 'partials/futusign-oc-endpoint.php';
			futusign_oc_endpoint();
			exit();
		}
		return;
	}
}