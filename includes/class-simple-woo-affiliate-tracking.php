<?php


/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the dashboard.
 *
 * @link       jakebowles.com/simple-woo-affiliate-tracking
 *
 * @package    Simple_Woo_Affiliate_Tracking
 * @subpackage Simple_Woo_Affiliate_Tracking/includes
 */
/**
 * The core plugin class.
 *
 * This is used to define internationalization, dashboard-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @package    Simple_Woo_Affiliate_Tracking
 * @subpackage Simple_Woo_Affiliate_Tracking/includes
 * @author     Jake Bowles jake@jakebowles.com
 */

class Simple_Woo_Affiliate_Tracking {

	/**
	* The loader that's responsible for maintaining and registering all hooks that power
	* the plugin.
	*
	* @access   protected
	* @var      Simple_Woo_Affiliate_Tracking_Loader    $loader    Maintains and registers all hooks for the plugin.
	*/
	protected $loader;


	/**
	 * The unique identifier of this plugin.
	 *
	 * @access   protected
	 * @var      string    $plugin_slug    The string used to uniquely identify this plugin.
	 */
	protected $plugin_slug;

	/**
	 * The current version of the plugin.
	 *
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the Dashboard and
	 * the public-facing side of the site.
	 *
	 */
	public function __construct() {

		$this->plugin_slug = 'simple-woo-affiliate-tracking';
		$this->version = '0.1.0';

		$this->load_dependencies();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}



	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Plugin_Name_Loader. Orchestrates the hooks of the plugin.
	 * - Plugin_Name_i18n. Defines internationalization functionality.
	 * - Plugin_Name_Admin. Defines all hooks for the dashboard.
	 * - Plugin_Name_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for defining all actions that occur in the Dashboard.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-simple-woo-affiliate-tracking-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-simple-woo-affiliate-tracking-public.php';

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( __FILE__ ) . 'class-simple-woo-affiliate-tracking-loader.php';

		$this->loader = new Simple_Woo_Affiliate_Tracking_loader();

	}

	/**
	 * Register all of the hooks related to the dashboard functionality
	 * of the plugin.
	 *
	 * @access   private
	 */
	private function define_admin_hooks() {

		$admin = new Simple_Woo_Affiliate_Tracking_Admin( $this->get_version() );
		$this->loader->add_action( 'woocommerce_admin_order_data_after_billing_address' , $admin, 'render_refid_field');
		$this->loader->add_action( 'admin_post_swat_export_csv', $admin, 'swat_export_csv');
		$this->loader->add_action( 'admin_menu', $admin, 'swat_setup_menu');
		
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @access   private
	 */
	private function define_public_hooks() {

		$public = new Simple_Woo_Affiliate_Tracking_Public( $this->get_version() );
		$this->loader->add_action( 'wp_enqueue_scripts', $public, 'enqueue_scripts' );
		$this->loader->add_action( 'init', $public, 'swat_cookie_init' );
		$this->loader->add_filter( 'woocommerce_checkout_fields', $public, 'swat_override_checkout_fields');
		$this->loader->add_action( 'woocommerce_checkout_update_order_meta', $public, 'swat_checkout_field_update_order_meta');

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}