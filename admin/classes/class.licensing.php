<?php

/*************************************
* Licensing Related Stuff
*************************************/


class WPCB_Licensing {
    
        /************************************
	 * Instance of this class.
	 ************************************/
	protected static $instance = null;
        
        /*************************************
	 * Initialize functions
	 *************************************/
        private function __construct() {
                
            add_action( 'wp_ajax_activate_and_save_license', array( $this, 'activate_and_save_license') );
            add_action( 'wp_ajax_deactivate_and_delete_license', array( $this, 'deactivate_and_delete_license') );
            add_action( 'wp_ajax_dont_show_renew_msg', array( $this, 'dont_show_renew_msg') );
            add_action( 'admin_init', array( $this, 'wpcb_plugin_updater') );
            
            add_action( 'init', array( $this, 'check_actual_license_validity') );
        }
        
        /************************************
	 * Return an instance of this class.
	 ************************************/
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}
        
        /***************************************
	 * Activate and Save License.
         * 
         * Be respectful and do not modify this.
         * It took countless hors of hard labour 
         * to build this plugin. Thanks.
	 ***************************************/
        
	public function activate_and_save_license() {
                $license_key = (isset($_POST['wpcb_license_key'])) ? $_POST['wpcb_license_key'] : "";

                $api_params = array( 
			'edd_action'=> 'activate_license', 
			'license' 	=> $license_key, 
			'item_name' => urlencode( WPCB_PRODUCT_NAME ),
			'url'       => home_url()
		);
		
		$response = wp_remote_get( add_query_arg( $api_params, WPCB_WEBSITE_URL ), array( 'timeout' => 15, 'sslverify' => false ) );

		if ( is_wp_error( $response ) ){
                    echo 'response_error';
                    die();
                }
                
		$license_data = json_decode( wp_remote_retrieve_body( $response ) );
                
		// $license_data->license will be either "valid" or "invalid"
                if($license_data->license == 'valid'){
                    update_option( 'wpcb_license_key', $license_key );
                    update_option( 'wpcb_license_status', $license_data->license );
                    
                    if($license_data->expires){
                        $validtill = strtotime($license_data->expires);
                    }
                    else{
                        $validtill = time();
                    }
                    $today = time();
                    $difference = $validtill - $today;
                    if ($difference < 0) { $difference = 0; }
                    $validity_remaining = floor($difference/60/60/24);
                    update_option( 'wpcb_license_validity_remaining', $validity_remaining );
                    
                    echo 1; // License activated and saved
                }
                else{
                    echo 0; // License was not activated and saved
                }
                die();
        }
        
        /***************************************
	 * Deactivate and Delete License.
         * 
         * Be respectful and do not modify this.
         * It took countless hors of hard labour 
         * to build this plugin. Thanks.
	 ***************************************/
        
	public function deactivate_and_delete_license() {
                $license = trim( get_option( 'wpcb_license_key' ) );
		$api_params = array( 
			'edd_action'=> 'deactivate_license', 
			'license' 	=> $license, 
			'item_name' => urlencode( WPCB_PRODUCT_NAME ),
			'url'       => home_url()
		);

		$response = wp_remote_get( add_query_arg( $api_params, WPCB_WEBSITE_URL ), array( 'timeout' => 15, 'sslverify' => false ) );
                
		if ( is_wp_error( $response ) ){
                    delete_option( 'wpcb_license_key' );
                    delete_option( 'wpcb_license_status' );
                    delete_option( 'wpcb_license_validity_remaining');
                    echo 'response_error';
                    die();
                }

		$license_data = json_decode( wp_remote_retrieve_body( $response ) );
		
		// $license_data->license will be either "deactivated" or "failed"
		if( $license_data->license == 'deactivated' ){
                    delete_option( 'wpcb_license_key' );
                    delete_option( 'wpcb_license_status' );
                    delete_option( 'wpcb_license_validity_remaining');
                    echo 1; // License deactivation and deletion successful
                }
                else if($license_data->license == 'failed'){
                    delete_option( 'wpcb_license_key' );
                    delete_option( 'wpcb_license_status' );
                    delete_option( 'wpcb_license_validity_remaining');
                    echo 0; // License deactivation and deletion failed
                }
                die();
        }
        
        /***************************************
	 * Quickly check if license is valid
         ***************************************/
        
        public function is_license_valid() {
            if(get_option('wpcb_license_status') == 'valid'){
                return true;
            }
            else{
                return false;
            }
        }
        
        /***************************************
	 * Check license validity from server
         ***************************************/
        
        public function check_actual_license_validity() {
            
            if(!get_transient('wpcb_license_validity_checked')){
                
                global $wp_version;

                $license = trim( get_option( 'wpcb_license_key' ) );

                $api_params = array( 
                        'edd_action'=> 'check_license',
                        'license'   => $license,
                        'item_name' => urlencode( WPCB_PRODUCT_NAME ),
                        'url'       => home_url()
                );

                $response = wp_remote_get( add_query_arg( $api_params, WPCB_WEBSITE_URL ), array( 'timeout' => 15, 'sslverify' => false ) );

                if ( is_wp_error( $response ) )
                        return false;

                $license_data = json_decode( wp_remote_retrieve_body( $response ) );

                // If valid next day also
                
                if( $license_data->license == 'valid' ) {
                    
                    update_option( 'wpcb_license_status', $license_data->license );
                    if($license_data->expires){
                        $validtill = strtotime($license_data->expires);
                    }
                    else{
                        $validtill = time();
                    }
                    $today = time();
                    $difference = $validtill - $today;
                    if ($difference < 0) { $difference = 0; }
                    $validity_remaining = floor($difference/60/60/24);
                    update_option( 'wpcb_license_validity_remaining', $validity_remaining );
                } 
                // If validity is over or some error
                else {
                    if($license_data->expires){
                        $validtill = strtotime($license_data->expires);
                    }
                    else{
                        $validtill = time();
                    }
                    $today = time();
                    $difference = $validtill - $today;
                    if ($difference < 0) { $difference = 0; }
                    $validity_remaining = floor($difference/60/60/24);
                    update_option( 'wpcb_license_validity_remaining', $validity_remaining );
                    update_option( 'wpcb_license_status', 0 );
                }
                
                set_transient('wpcb_license_validity_checked','1', 24 * HOUR_IN_SECONDS);
                
            }
            
        }
        
        /***************************************
	 * License Activation Form
	 ***************************************/
        
        public function license_activation_form() {
                include_once( dirname(dirname(__FILE__)) . '/views/license-form.php' );
        }
        
        /***************************************
	 * Plugin Updater Function
         * 
         * Checks for latest updates and lets
         * the user download only if license is
         * valid.
	 ***************************************/
        
        public function wpcb_plugin_updater() {
            
            if( !class_exists( 'EDD_SL_Plugin_Updater' ) ) {
                include_once( dirname(__FILE__) . '/class.updater.php' );
            }
            
            $license_key = trim( get_option( 'wpcb_license_key' ) );

            // setup the updater
            $edd_updater = new EDD_SL_Plugin_Updater( WPCB_WEBSITE_URL, dirname(dirname(dirname(__FILE__))) . '/wp-conversion-boxes-pro.php', array( 
                            'version' 	=> WPCB_CURRENT_VERSION, 		// current version number
                            'license' 	=> $license_key, 	// license key (used get_option above to retrieve from DB)
                            'item_name' => WPCB_PRODUCT_NAME, 	// name of this plugin
                            'author' 	=> 'Ram Shengale',       // author of this plugin
                            'url'           => home_url()
                    )
            );

        }
        
        /***************************************
	 * License renew notice
	 ***************************************/
        
        public function license_renew_notice(){
            if(get_option('wpcb_license_validity_remaining') < 7){
                if(get_option('wpcb_license_validity_msg') != 2)
                    echo "<div class='error'><p>". sprintf( __('WARNING: Your license of WP Conversion Boxes Pro will expire in %d days','wp-conversion-boxes-pro') , get_option('wpcb_license_validity_remaining')) .". <a target='_blank' href='http://wpconversionboxes.com/checkout/?edd_license_key=". esc_attr__( get_option('wpcb_license_key') )."'>". __('Renew now?','wp-conversion-boxes-pro'). "</a><button data-dontshow='2' class='wpcb_dont_show_renew button'>". __('Don\'t Show This','wp-conversion-boxes-pro') ."</button></p></div>";   
            }
            else if(get_option('wpcb_license_validity_remaining') < 30){
                if(get_option('wpcb_license_validity_msg') != 1)
                    echo "<div class='error'><p>". sprintf( __('WARNING: Your license of WP Conversion Boxes Pro will expire in %d days','wp-conversion-boxes-pro') , get_option('wpcb_license_validity_remaining')) .". <a target='_blank' href='http://wpconversionboxes.com/checkout/?edd_license_key=". esc_attr__( get_option('wpcb_license_key') )."'>". __('Renew now?','wp-conversion-boxes-pro'). "</a><button data-dontshow='1' class='wpcb_dont_show_renew button'>". __('Don\'t Show This','wp-conversion-boxes-pro') ."</button></p></div>";
            }
        }
        
        /***************************************
	 * Don't show renew message
	 ***************************************/
        
        public function dont_show_renew_msg(){
            $dontshow = $_POST['dont_show'];
            update_option('wpcb_license_validity_msg',$dontshow);
            die;
        }
        
        /***************************************
	 * Remove HTTP/HTTPS
	 ***************************************/
        
        public function remove_http($url) {
            $disallowed = array('http://', 'https://');
            foreach($disallowed as $d) {
               if(strpos($url, $d) === 0) {
                  return str_replace($d, '', $url);
               }
            }
            return $url;
         }
}