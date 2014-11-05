<?php

/**
 * Fired during plugin activation
 *
 * @link       http://jakebowles.com/simple-woo-affiliate-tracking
 * @since      1.0.0
 *
 * @package    Simple_Woo_Affiliate_Tracking
 * @subpackage Simple_Woo_Affiliate_Tracking/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Simple_Woo_Affiliate_Tracking
 * @subpackage Simple_Woo_Affiliate_Tracking/includes
 * @author     Jake Bowles jake@jakebowles.com
 */
class Simple_Woo_Affiliate_Tracking_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

		} else{
			deactivate_plugins( plugin_basename( __FILE__ ) );
			wp_die( __( 'Simple Woo Affiliate Tracking requires WooCommerce to function!', 'simple-affiliate-tracker' ) );

		}


	}

}
