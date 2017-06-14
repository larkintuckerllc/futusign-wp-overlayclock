<?php
/**
 * Fired during plugin deactivation
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
 * Fired during plugin deactiviation.
 *
 * @since      0.1.0
 * @package    futusign_overlayclock
 * @subpackage futusign_overlayclock/includes
 * @author     John Tucker <john@larkintuckerllc.com>
 */
class Futusign_OverlayClock_Deactivator {
	/**
	 * Fired during plugin deactivation.
	 *
	 * @since    0.1.0
	 */
	public static function deactivate() {
		$clockIds = array();
		$args = array(
			'post_type' => 'futusign_ov_widget',
			'posts_per_page' => -1,
		);
		$loop = new WP_Query( $args );
		while ( $loop->have_posts() ) {
			$loop->the_post();
		  $id = get_the_ID();
			$title = get_the_title();
			if ($title == 'Clock') {
				$clockIds[] = $id;
			}
		}
		wp_reset_query();
		foreach ($clockIds as $clockId) {
			wp_delete_post($clockId, true);
		}
	}
}
