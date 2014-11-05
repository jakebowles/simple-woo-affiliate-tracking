<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       jakebowles.com/simple-woo-affiliate-tracking
 *
 * @package    Simple_Woo_Affiliate_Tracking
 * @subpackage Simple_Woo_Affiliate_Tracking/includes
 */
/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    Simple_Woo_Affiliate_Tracking
 * @subpackage Simple_Woo_Affiliate_Tracking/includes
 * @author     Jake Bowles jake@jakebowles.com
 */
 
class Simple_Woo_Affiliate_Tracking_Public {
    
    /**
     * The version of this plugin.
     *
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    protected $version;
 	
    /**
     * The order id to work with.
     *
     * @access   private
     * @var      string    $order_id    The order id to work with.
     */
 	protected $order_id;

    /**
     * The field to work with in the checkout
     * 
     * @var     array      $fields      The array of fields in the checkout.
     */
 	protected $fields;

    /**
     * Initialize the class and set its properties.
     *
     * @var      string    $version    The version of this plugin.
     */
    public function __construct( $version ) {
        $this->version = $version;
    }
    
    /**
     * Register the scripts for the public facing site.
     */
    public function enqueue_scripts() {
     		wp_enqueue_script(
			'simple-woo-affiliate-tracking-public',
			plugin_dir_url( __FILE__ ) . 'js/swat_cookies.js',
			array( 'jquery' )
		);
    }

    /**
     * Over ride the fields at the checkout
     * @param  array    $fields     The array of fields in the checkout.
     * @return array    $fields     The array of fields in the checkout.
     */
    public function swat_override_checkout_fields( $fields ) {

    	$fields['order']['refid'] = [
        'label'         => __('refid', 'woocommerce'),
	    'placeholder'   => _x('refid', 'placeholder', 'woocommerce'),
	    'required'      => false,
	    'class'         => array('hidden'),
	    'clear'         => true
    	];

    	return $fields;

    }

    /**
     * Update the meta for the order id
     * @param  string    $order_id  The order id to update.
     */
    public function swat_checkout_field_update_order_meta( $order_id ) {

    	if ( ! empty( $_POST['refid'] ) ) {
	        update_post_meta( $order_id, 'refid', sanitize_text_field( $_POST['refid'] ) );
	    }

    }

    /**
     * Initialize the cookie state
     */
    public function swat_cookie_init() {

    	if ( isset($_GET['refid']) ) {
    		$this->swat_set_cookie( $_GET['refid'] );
    	}

    }

    /**
     * Get the current id for the cookie refid.
     * @return string     The value of the refid cookie.
     */
    private function swat_get_current_cookie_id()
    {
    	return $_COOKIE['refid'];
    }

    /**
     * Set the cookie in the browser.
     * @param  string     $id           The refid to check.
     */
    private function swat_set_cookie( $id ) {

        if ( isset( $_COOKIE['refid'] ) ) {

            if ( $_COOKIE['refid'] != $id ) {
                $this->swat_eat_cookie();
                setcookie( 'refid', $id, time() + (86400 * 7) , COOKIEPATH, COOKIE_DOMAIN );
            }
        
        } else {
            setcookie('refid', $id, time() + (86400 * 7), COOKIEPATH, COOKIE_DOMAIN );
        }

    }

    /**
     * Destroy the cookie
     */
    private function swat_eat_cookie() {
    	unset( $_COOKIE['refid'] );
    }

}