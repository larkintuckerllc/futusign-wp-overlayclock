<?php
/**
 * futusign oc endpoint
 *
 * @link       https://github.com/larkintuckerllc
 * @since      0.4.0
 *
 * @package    futusign_overlaylock
 * @subpackage futusign_overlay/public/partials
 */
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * futusign endpoint
 *
 * @since    0.4.0
 */
function futusign_oc_endpoint() {
  // SETTINGS
  $options = get_option( 'futusign_overlayclock_option_name' );
  $size = $options !== false && array_key_exists( 'size', $options ) ? $options['size'] : '10';
  $theme = $options !== false && array_key_exists( 'theme', $options ) ? $options['theme'] : 'dark';
  // OUTPUT
  header( 'Content-Type: application/json' );
  header( 'Cache-Control: no-cache, no-store, must-revalidate');
  echo '{';
  echo '"size":';
  echo json_encode( $size );
  echo ', "theme":';
  echo json_encode( $theme );
  echo '}';
}