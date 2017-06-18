<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://bitbucket.org/futusign/futusign-wp-overlayclock
 * @since      0.1.0
 *
 * @package    futusign_overlayclock
 * @subpackage futusign_overlayclock/admin
 */
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * The admin-specific functionality of the plugin.
 *
 * @package    futusign_overlayclock
 * @subpackage futusign_overlayclock/admin
 * @author     John Tucker <john@larkintuckerllc.com>
 */
class Futusign_OverlayClock_Admin {
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    0.1.0
	 */
	public function __construct() {
	}
	/**
	 * Define settings
	 *
	 * @since    0.1.0
	 */
	public function admin_init() {
		register_setting(
			'futusign_overlayclock_option_group',
			'futusign_overlayclock_option_name',
			array( $this, 'sanitize_callback')
		);
		add_settings_section(
			'futusign_overlayclock_style_section',
			'Style',
			array ( $this, 'style_section_callback'),
			'futusign_overlayclock_settings_page');
		add_settings_field(
			'size',
			'size',
			array ( $this, 'size' ),
			'futusign_overlayclock_settings_page',
			'futusign_overlayclock_style_section'
		);
	}
	/**
	 * Sanitize inputs
	 *
	 * @since   0.1.0
	 * @param    array      $input     input
	 * @return   array      sanitized input
	 */
	public function sanitize_callback($input) {
		$newinput = array();
		$newinput['size'] = trim($input['size']);
		return $newinput;
	}
	/**
	 * Reload section copy
	 *
	 * @since    0.1.0
	 */
	public function style_section_callback() {
		?>
		<p>Blah...</p>
		<?php
	}
	/**
	 * size Input
	 *
	 * @since    0.1.0
	 */
	public function size() {
		$options = get_option('futusign_overlayclock_option_name');
		$size = array_key_exists('size',$options) ? $options['size'] : '10';
		echo "<input type='number' min='10' id='size' name='futusign_overlayclock_option_name[size]'  value='{$size}' /> <b>px</b>";
	}
	/**
	 * Add admin menus
	 *
	 * @since    0.1.0
	 */
	public function admin_menu() {
		add_options_page(
			'futusign Overlay Clock',
			'futusign Overlay Clock',
			'manage_options',
			'futusign_overlayclock_options',
			array( $this, 'options_page' )
		);
	}
	/**
	 * Display settings page
	 *
	 * @since    0.1.0
	 */
	public function options_page() {
		?>
		<div class="wrap">
			<h1>futusign Overlay Clock Settings</h1>
			<form action="options.php" method="post">
				<?php settings_fields('futusign_overlayclock_option_group'); ?>
				<?php do_settings_sections('futusign_overlayclock_settings_page'); ?>
				<input
					name="Submit"
					type="submit"
					value="<?php esc_attr_e('Save Changes', 'futusign_overlayclock'); ?>"
					class="button button-primary"
				/>
			</form>
		</div>
		<?php
	}
}
