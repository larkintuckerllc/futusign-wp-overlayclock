<?php
/**
 * Missing futusignz-overlay plugin partial.
 *
 * @link       https://bitbucket.org/futusign/futusign-wp-overlayclock
 * @since      0.1.0
 *
 * @package    futusign_overlayclock
 * @subpackage inactive/partials
 */
if ( ! defined( 'WPINC' ) ) {
  die;
}
/**
 * Show missing futusignz-overlay
 *
 * @since    0.1.0
 */
function futusign_overlayclock_missing_futusignz_overlay() {
	$is_installed = Futusign_OverlayClock::is_plugin_installed( 'futusignz-overlay' );
	$target = false;
	$action = __('Install', 'futusign_overlayclock');
	if ( current_user_can( 'install_plugins' ) ) {
		if ( $is_installed ) {
			$action = __('Activate', 'futusign_overlayclock');
			$url = wp_nonce_url( self_admin_url( 'plugins.php?action=activate&plugin=' . $is_installed . '&plugin_status=active' ), 'activate-plugin_' . $is_installed );
		} else {
      $target = true;
			$url = 'https://www.futusign.com/downloads/';
		}
	} else {
		$target = true;
		$url = 'https://www.futusign.com/downloads/';
	}
	?>
	<div class="notice error is-dismissible">
		<p><strong>futusign Overlay Clock</strong> <?php esc_html_e('depends on the last version of futusign Overlay to work!', 'futusign_overlayclock' ); ?></p>
		<p><a href="<?php echo esc_url( $url ); ?>" class="button button-primary"<?php if ( $target ) : ?> target="_blank"<?php endif; ?>><?php echo esc_html( $action . ' futusign Overlay' ); ?></a></p>
	</div>
	<?php
}
futusign_overlayclock_missing_futusignz_overlay();
