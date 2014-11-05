<?php
 
class Simple_Woo_Affiliate_Tracking_Admin {
 	
 	/**
 	 * The current version of the plugin.
 	 * @var string
 	 */
    protected $version;
 	
 	/**
 	 * The order to modify in the backend.
 	 * @var string
 	 */
 	protected $order;

 	/**
 	 * An array of any size.
 	 * @var array
 	 */
 	protected $array;

 	/**
 	 * The column to combine.
 	 * @var integer
 	 */
 	protected $col;

 	/**
 	 * Initialize the class and set the properties.
 	 * @param string $version The version of this plugin.
 	 */
    public function __construct( $version ) {
        $this->version = $version;
    }
 
 	/**
 	 * Include the admin panel UI file.
 	 */
	public function render_admin_panel() {
		require_once plugin_dir_path( __FILE__ ) . 'partials/simple-woo-affiliate-tracking.php';
	}

	/**
	 * Rendering for the refid field on the back panel.
	 * @param  string $order The order to modify.
	 */
	public function render_refid_field( $order ) {
		echo '<p><strong>'.__('Ref ID').':</strong> ' . get_post_meta( $order->id, 'refid', true ) . '</p>';
	}

	 /**
     * Add the submenu page to the Tools menu for administrative functions.
     */
    public function swat_setup_menu() {

        add_submenu_page(
        			'tools.php',
        			'Simple WooCommerce Affiliate Tracker',
        			'Simple WooCommerce Affiliate Tracker',
        			'manage_options',
        			'simple-woocommerce-affiliate-tracker',
        			array( $this, 'render_admin_panel' )
        );

    }

    /**
     * Export the data to a csv file
     */
	public function swat_export_csv() {

		if ( ! is_super_admin() ) {
			return;
		}
	 
	 	
		$filename = 'affiliate-sales-' . date('Y-m-d') . '.csv';
	 
		$header_row = array(
			0 => 'Refid',
			1 => 'Sales'
		);
	 
		$data_rows = array();
	 	
		global $wpdb;
		$sql = $wpdb->prepare( "SELECT * FROM $wpdb->postmeta WHERE meta_key =  %s" , 'refid' );
		$sales = $wpdb->get_results( $sql );

		foreach ( $sales as $s ) {
			$row = array();
			$row[0] = $s->meta_value;
			$row[1] = '1'; 
			$data_rows[] = $row;
		}

	 	$data_rows = $this->combine_duplicates( $data_rows, 0);

		$fh = @fopen( 'php://output', 'w' );
	 
		fprintf( $fh, chr(0xEF) . chr(0xBB) . chr(0xBF) );
	 
		header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
		header( 'Content-Description: File Transfer' );
		header( 'Content-type: text/csv' );
		header( "Content-Disposition: attachment; filename={$filename}" );
		header( 'Expires: 0' );
		header( 'Pragma: public' );
	 
		fputcsv( $fh, $header_row );
	 
		foreach ( $data_rows as $data_row ) {
			fputcsv( $fh, $data_row );
		}
	 
		fclose( $fh );
		die();
	
	}

	/**
	 * Combine duplicate columns by adding their values.
	 * @param  array $array The array to be searched.
	 * @param  integer $col The column to combine.
	 * @return array        The combined array.
	 */
	private function combine_duplicates( $array, $col ) {
		$index = array();

	    foreach ( $array as $row ) {
	        $key = $row[ $col];
	        if ( !isset( $index[ $key ] ) ) {
	            $index[ $key ] = $row;
	        } else {
	            for ( $i = 0; $i < count($row); ++$i ) {
	                if ( $i != $col ) {
	                    $index[ $key ][ $i ] += $row[ $i ];
	                }
	            }
	        }
	    }

	    return array_values( $index );
	}

}