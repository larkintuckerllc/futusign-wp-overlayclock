<?php
/**
 * The file that defines the core plugin class
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
 * The core plugin class.
 *
 * @since      0.1.0
 * @package    futusign_overlayclock
 * @subpackage futusign_overlayclock/includes
 * @author     John Tucker <john@larkintuckerllc.com>
 */
class Futusign_OverlayClock {
	/**
	 * Static function to determine if dependant plugin(s) are active
	 *
	 * @since    0.1.0
	 * @var      string    $plugin    Indicates which plugin(s) to check for.
	 */
	public static function is_plugin_active( $plugin ) {
		if ( 'futusign' == $plugin ) {
			return class_exists( 'Futusign' );
		} elseif ( 'futusignz-overlay' == $plugin ) {
			return class_exists( 'Futusign_Overlay');
		} elseif ( 'all' == $plugin ) {
			return class_exists( 'acf' ) && class_exists( 'Futusign' ) && class_exists( 'Futusign_Overlay' );
		}
		return false;
	}
	/**
	 * Static function to determine if dependant plugin(s) are installed
	 *
	 * @since    0.1.0
	 * @var      string    $plugin    Indicates which plugin(s) to check for.
	 */
	public static function is_plugin_installed( $plugin ) {
		if ( ! function_exists( 'get_plugins' ) ) {
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}
		$paths = false;
		if ( 'futusign' == $plugin ) {
			$paths = array( 'futusign/futusign.php' );
		} elseif ( 'futusignz-overlay' == $plugin ) {
			$paths = array( 'futusignz-overlay/futusignz-overlay.php' );
		}
		if ( $paths ) {
			$plugins = get_plugins();
			if ( is_array( $plugins ) && count( $plugins ) > 0 ) {
				foreach ( $paths as $path ) {
					if ( isset( $plugins[$path] ) && ! empty( $plugins[$path] ) ) {
						return $path;
					}
				}
			}
		}
		return false;
	}
	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    0.1.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;
	/**
	 * The current version of the plugin.
	 *
	 * @since    0.1.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;
	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    0.1.0
	 * @access   protected
	 * @var      Futusign_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;
	/**
	 * Define the core functionality of the plugin.
	 *
	 * @since    0.1.0
	 */
	public function __construct() {
		$this->plugin_name = 'futusign-overlayclock';
		$this->version = '0.4.5';
		$this->load_dependencies();
		$this->set_locale();
		if (Futusign_OverlayClock::is_plugin_active('all')) {
			$this->define_common_hooks();
			if ( is_admin() ) {
				$this->define_admin_hooks();
			} else {
				$this->define_public_hooks();
			}
		} else {
			$this->define_inactive_hooks();
		}
	}
	/**
	 * Load the required dependencies for this plugin.
	 *
	 * @since    0.1.0
	 * @access   private
	 */
	private function load_dependencies() {
		require_once plugin_dir_path( __FILE__ ) . 'class-futusign-overlayclock-loader.php';
		require_once plugin_dir_path( __FILE__ ) . 'class-futusign-overlayclock-i18n.php';
		if (Futusign_OverlayClock::is_plugin_active('all')) {
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'common/class-futusign-overlayclock-common.php';
			if ( is_admin() ) {
				require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-futusign-overlayclock-admin.php';
			} else {
				require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-futusign-overlayclock-public.php';
			}
		} else {
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'inactive/class-futusign-overlayclock-inactive.php';
		}
		$this->loader = new Futusign_OverlayClock_Loader();
	}
	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * @since    0.1.0
	 * @access   private
	 */
	private function set_locale() {
		$plugin_i18n = new Futusign_OverlayClock_i18n();
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}
	/**
	 * Register all of the inactive hooks of the plugin.
	 *
	 * @since    0.1.0
	 * @access   private
	 */
	private function define_inactive_hooks() {
		$plugin_inactive = new Futusign_OverlayClock_Inactive();
		$this->loader->add_action('admin_notices', $plugin_inactive, 'missing_plugins_notice' );
	}
	/**
	 * Register all of the common hooks of the plugin.
	 *
	 * @since    0.1.0
	 * @access   private
	 */
	private function define_common_hooks() {
		$plugin_common = new Futusign_OverlayClock_Common();
		$this->loader->add_action('init', $plugin_common, 'add_rewrite_rules');
		// UPDATE DB CHECK
		$this->loader->add_action('init', $this, 'update_db_check');
	}
	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    0.1.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		$plugin_admin = new Futusign_OverlayClock_Admin();
		$this->loader->add_action('admin_init', $plugin_admin, 'admin_init');
		$this->loader->add_action('admin_menu', $plugin_admin, 'admin_menu');
	}
	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    0.1.0
	 * @access   private
	 */
	private function define_public_hooks() {
		$plugin_public = new Futusign_OverlayClock_Public();
		$this->loader->add_action('single_template', $plugin_public, 'single_template');
		$this->loader->add_action('query_vars', $plugin_public, 'query_vars');
		$this->loader->add_action('parse_request', $plugin_public, 'parse_request');
	}
	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    0.1.0
	 */
	public function run() {
		$this->loader->run();
	}
	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     0.1.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}
	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     0.1.0
	 * @return    Plugin_Name_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}
	/**
	 * On update, update database
	 *
	 * @since    0.4.1
	 */
	public function update_db_check() {
		$current_version = $this->version;
		if ( get_site_option( 'futusign_oc_db_version' ) !== $current_version ) {
			flush_rewrite_rules();
			update_option( 'futusign_oc_db_version', $current_version );
		}
	}
}
