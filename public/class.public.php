<?php

/*************************************
* WP COnversion Boxes Public Class
*************************************/

class WPCB_Public {

	const VERSION = '1.0.3';
        const WPCB_AUTHOR_NAME = 'Ram Shengale';
        const WPCB_WEBSITE_URL = 'http://wpconversionboxes.com';
        
	/*********************************
	 * Default slugs
	 *********************************/
	protected $wpcb_main_slug = 'wp-conversion-boxes';
        protected $wpcb_edit_slug = 'wp-conversion-boxes/edit';
        protected $wpcb_ab_tests_slug = 'wp-conversion-boxes/ab-tests';
        protected $wpcb_edit_ab_test_slug = 'wp-conversion-boxes/edit-ab-tests';
	protected $wpcb_stats_slug = 'wp-conversion-boxes/statistics';
        protected $wpcb_settings_slug = 'wp-conversion-boxes/settings';
        
        protected $template_directory_1 = 'email-optin';
        protected $template_directory_2 = 'video-email-optin';
        protected $template_directory_3 = 'call-to-action';
        protected $template_directory_4 = 'video-call-to-action';
        
        protected $main_table = 'wp_conversion_boxes';
        protected $tracking_table = 'wpcb_tracking';

        /*********************************
	 * Instance of this class.
	 *********************************/
	protected static $instance = null;
                
	/*********************************
	 * Initialize the plugin by setting 
         * localization and loading public 
         * scripts and styles.
	 *********************************/
	private function __construct() {
                
		/*********************************
                 * Activate plugin when new blog 
                 * is added
                 *********************************/
		add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

                
		/*********************************
                 * Load public-facing style sheet,
                 * JavaScript and shortcode.
                 *********************************/
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
                
                add_action( 'wp_footer', array( $this, 'in_footer' ) );
                add_filter( 'the_content', array( $this, 'show_box'));
                add_shortcode( 'wpcb' , array( $this, 'run_shortcode') );

	}
        
	/***************************************
	 * Return the variable values
	 ***************************************/
        public function get_website_url() {
                return $this->WPCB_WEBSITE_URL;
        }
        
	public function get_wpcb_main_slug() {
		return $this->wpcb_main_slug;
	}
        
        public function get_wpcb_edit_slug() {
		return $this->wpcb_edit_slug;
	}
        
        public function get_wpcb_ab_tests_slug(){
                return $this->wpcb_ab_tests_slug;
        }
        public function get_wpcb_edit_ab_test_slug(){
                return $this->wpcb_edit_ab_test_slug;
        }
        public function get_wpcb_stats_slug() {
		return $this->wpcb_stats_slug;
	}
        
        public function get_wpcb_settings_slug() {
		return $this->wpcb_settings_slug;
	}
        
        public function get_template_directory($dir) {
            switch ($dir){
                case 1: return $this->template_directory_1;
                        break;
                case 2: return $this->template_directory_2;
                        break;
                case 3: return $this->template_directory_3;
                        break;
                case 4: return $this->template_directory_4;
                        break;                    
            }
            
        }
        public function get_boxes_table_name(){
            global $wpdb;
            return $wpdb->prefix.$this->main_table;
        }
        public function get_tracking_table_name(){
            global $wpdb;
            return $wpdb->prefix.$this->tracking_table;
        }

	/***************************************
	 * Return an instance of this class.
	 ***************************************/
	public static function get_instance() {
                if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}
        

	/***************************************
	 * Fired when the plugin is activated.
	 ***************************************/
	public static function activate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide  ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_activate();
				}

				restore_current_blog();

			} else {
				self::single_activate();
			}

		} else {
			self::single_activate();
		}

	}

        
	/***************************************
	 * Fired when the plugin is deactivated.
	 ***************************************/
	public static function deactivate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_deactivate();

				}

				restore_current_blog();

			} else {
				self::single_deactivate();
			}

		} else {
			self::single_deactivate();
		}

	}

        
	/***************************************
	 * Fired when a new site is activated with 
         * a WPMU environment.
	 ****************************************/
	public function activate_new_site( $blog_id ) {

		if ( 1 !== did_action( 'wpmu_new_blog' ) ) {
			return;
		}

		switch_to_blog( $blog_id );
		self::single_activate();
		restore_current_blog();

	}

        
	/***************************************
	 * Get all blog ids of blogs in the current 
         * network that are:
	 * - not archived
	 * - not spam
	 * - not deleted
	 ***************************************/
	private static function get_blog_ids() {

		global $wpdb;

		// get an array of blog ids
		$sql = "SELECT blog_id FROM $wpdb->blogs
			WHERE archived = '0' AND spam = '0'
			AND deleted = '0'";

		return $wpdb->get_col( $sql );

	}

        
	/***************************************
	 * Fired for each blog when the plugin 
         * is activated.
	 ***************************************/
	private static function single_activate() {
                global $wpdb;
                
                $wpcb_tbl_1 = $wpdb->prefix.'wp_conversion_boxes';
                $wpcb_tbl_query_1 = $wpdb->prepare("CREATE TABLE IF NOT EXISTS $wpcb_tbl_1 (
                    id INT(9) NOT NULL AUTO_INCREMENT,
                    box_name VARCHAR(80) NOT NULL,
                    box_type int(9),
                    box_template VARCHAR(40),
                    box_customizations longtext,
                    box_settings VARCHAR(200),
                    UNIQUE KEY id (id)
                ) DEFAULT CHARSET=utf8;","%s");
                
                
                $wpcb_tbl_2 = $wpdb->prefix.'wpcb_tracking';
                $wpcb_tbl_query_2 = $wpdb->prepare("CREATE TABLE IF NOT EXISTS $wpcb_tbl_2 (
                    id INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                    ip VARCHAR( 16 ) NOT NULL ,
                    host VARCHAR( 100 ) NOT NULL ,
                    visitdate DATETIME NOT NULL ,
                    visitedpage VARCHAR(255) NOT NULL ,
                    referring VARCHAR( 255 ) NOT NULL ,
                    visittype ENUM('visit', 'boxview', 'click', 'optin'),
                    box_id INT(9) NOT NULL DEFAULT 0 
                ) DEFAULT CHARSET=utf8;","%s");
                
                $wpdb->query($wpcb_tbl_query_1);
                $wpdb->query($wpcb_tbl_query_2);
                
                add_option('wpcb_default_box', 0 , '', 'yes' );
                add_option('wpcb_all_posts', 0 , '', 'yes' );
                add_option('wpcb_all_pages', 0 , '', 'yes' );
	}

        
	/***************************************
	 * Fired for each blog when the plugin 
         * is deactivated.
	 ***************************************/
	private static function single_deactivate() {
		// Noting so far.
	}


	/***************************************
	 * Register and enqueue public-facing 
         * style sheet.
	 ***************************************/
	public function enqueue_styles() {
		wp_enqueue_style( $this->wpcb_main_slug . '-plugin-styles', plugins_url( 'assets/css/public.css', __FILE__ ), array(), self::VERSION );
	}

        
	/***************************************
	 * Register and enqueues public-facing 
         * JavaScript files.
	 ***************************************/
	public function enqueue_scripts() {
		wp_enqueue_script( $this->wpcb_main_slug . '-plugin-script', plugins_url( 'assets/js/public.js', __FILE__ ), array( 'jquery' ), self::VERSION );
	}
        
        /******************************
         * Main shortcode [wpcb]
         ******************************/

        public function run_shortcode($atts) {
            include_once('includes/run-shortcode.php');
            return ob_get_clean();
        }

        public function show_box($content){
            include_once('includes/show-box.php');
            return $content.$final_output;
            $wpcb_tracking->log_new_visit($id);
        }
        
        public function in_footer(){
            //echo $_COOKIE["wpcb-useradded"];
        }
        
        /***************************************
         * Sanitise array
         ***************************************/
        
        function sanitise_array ($data = array()) {
            if (!is_array($data) || !count($data)) {
                    return array();
            }

            foreach ($data as $k => $v) {
                    if (!is_array($v) && !is_object($v)) {
                            $data[$k] = stripslashes(trim($v));
                    }
                    if (is_array($v)) {
                            $data[$k] = purica_array($v);
                    }
            }

            return $data;
        }


}
