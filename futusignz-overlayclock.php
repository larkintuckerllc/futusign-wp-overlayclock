<?php
/**
 * The plugin bootstrap file
 *
 * @link             https://bitbucket.org/futusign/futusign-wp-overlayclock
 * @since            0.1.0
 * @package          futusign_overlayclock
 * @wordpress-plugin
 * Plugin Name:      futusign Overlay Clock
 * Plugin URI:       https://www.futusign.com
 * Description:      Add futusign Overlay Clock feature
 * Version:          0.4.0
 * Author:           John Tucker
 * Author URI:       https://github.com/larkintuckerllc
 * License:          Custom
 * License URI:      https://www.futusign.com/license
 * Text Domain:      futusign-overlayclock
 * Domain Path:      /languages
 */
if ( ! defined( 'WPINC' ) ) {
	die;
}
//Use version 3.1 of the update checker.
require 'plugin-update-checker/plugin-update-checker.php';
$MyUpdateChecker = new PluginUpdateChecker_3_1 (
	'http://futusign-wordpress.s3-website-us-east-1.amazonaws.com/futusignz-overlayclock.json',
	__FILE__
);
function activate_futusign_overlayclock() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-futusign-overlayclock-activator.php';
	Futusign_OverlayClock_Activator::activate();
}
function deactivate_futusign_overlayclock() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-futusign-overlayclock-deactivator.php';
	Futusign_OverlayClock_Deactivator::deactivate();
}
register_activation_hook( __FILE__, 'activate_futusign_overlayclock' );
register_deactivation_hook( __FILE__, 'deactivate_futusign_overlayclock' );
require_once plugin_dir_path( __FILE__ ) . 'includes/class-futusign-overlayclock.php';
/**
 * Begins execution of the plugin.
 *
 * @since    0.1.0
 */
function run_futusign_overlayclock() {
	$plugin = new Futusign_OverlayClock();
	$plugin->run();
}
run_futusign_overlayclock();
