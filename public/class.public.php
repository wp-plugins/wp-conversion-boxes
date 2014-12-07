<?php

/*************************************
* WP COnversion Boxes Public Class
*************************************/

class WPCB_Public {

        const WPCB_AUTHOR_NAME = 'Ram Shengale';
        
	/*********************************
	 * Default slugs
	 *********************************/
	protected $wpcb_main_slug = 'wp-conversion-boxes-pro';
        protected $wpcb_edit_slug = 'wp-conversion-boxes-pro/edit';
        protected $wpcb_settings_slug = 'wp-conversion-boxes-pro/settings';
        
        protected $template_directory_1 = 'email-optin';
        protected $template_directory_2 = 'video-email-optin';
        protected $template_directory_3 = 'call-to-action';
        protected $template_directory_4 = 'video-call-to-action';
        
        protected $template_directory_type_name_1 = 'Email Optin';
        protected $template_directory_type_name_2 = 'Video Email Optin';
        protected $template_directory_type_name_3 = 'Call To Action';
        protected $template_directory_type_name_4 = 'Video Call To Action';
        
        protected $main_table = 'wp_conversion_boxes_pro';
        protected $tracking_table = 'wpcbp_tracking';

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
                add_filter( 'the_content', array( $this, 'show_box') , 20);
                add_shortcode( 'wpcb' , array( $this, 'run_shortcode') );
                
                add_action( 'wp_ajax_add_new_contact', array( $this, 'add_new_contact') );
                add_action( 'wp_ajax_nopriv_add_new_contact', array( $this, 'add_new_contact') );
                
                /***************************************
		 * Register widget
		 ***************************************/
                add_action( 'widgets_init', array( $this, 'register_wpcb_widget') );
                
                /***************************************
		 * Init functions
		 ***************************************/
                add_action( 'wp' , array ($this, 'update_table_structure') );

	}
        
	/***************************************
	 * Return the variable values
	 ***************************************/
        
	public function get_wpcb_main_slug() {
		return $this->wpcb_main_slug;
	}
        
        public function get_wpcb_edit_slug() {
		return $this->wpcb_edit_slug;
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
        
        public function get_template_directory_type_name($dir) {
            switch ($dir){
                case 1: return $this->template_directory_type_name_1;
                        break;
                case 2: return $this->template_directory_type_name_2;
                        break;
                case 3: return $this->template_directory_type_name_3;
                        break;
                case 4: return $this->template_directory_type_name_4;
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
                
                $wpcb_tbl_1 = $wpdb->prefix.'wp_conversion_boxes_pro';
                $wpdb->query("CREATE TABLE IF NOT EXISTS $wpcb_tbl_1 (
                    id INT(9) NOT NULL AUTO_INCREMENT,
                    box_status int(1) DEFAULT 1,
                    box_name VARCHAR(120) NOT NULL,
                    box_type int(9),
                    box_template VARCHAR(140),
                    is_custom_template int(9) DEFAULT 0,
                    box_customizations longtext,
                    box_settings longtext,
                    test_enabled INT(4) NOT NULL DEFAULT 0,
                    test_with INT(11) NOT NULL DEFAULT 0,
                    UNIQUE KEY id (id)
                ) DEFAULT CHARSET=utf8;");
                
                $wpcb_tbl_2 = $wpdb->prefix.'wpcbp_tracking';
                $wpdb->query("CREATE TABLE IF NOT EXISTS $wpcb_tbl_2 (
                    id INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                    ip VARCHAR( 16 ) NOT NULL ,
                    host VARCHAR( 100 ) NOT NULL ,
                    visitdate DATETIME NOT NULL ,
                    visitedpage VARCHAR(255) NOT NULL ,
                    referring VARCHAR( 255 ) NOT NULL ,
                    visittype ENUM('visit', 'boxview', 'click', 'optin'),
                    box_id INT(9) NOT NULL DEFAULT 0 
                ) DEFAULT CHARSET=utf8;",'');
                
                add_option('wpcb_default_box', 0 , '', 'yes' );
                add_option('wpcb_all_posts', 0 , '', 'yes' );
                add_option('wpcb_all_pages', 0 , '', 'yes' );
                add_option('wpcb_enable_credit_link', 0 , '', 'yes' );
                add_option('wpcb_ga_tracking', 0 , '', 'yes' );
                add_option('wpcb_license_key', '' , '', 'yes' );
                add_option('wpcb_license_status', 0 , '', 'yes' );
                add_option('wpcb_license_validity_remaining', 0 , '', 'yes' );
                add_option('wpcb_license_validity_msg', 0 , '', 'yes' );
                
                $upload_dir = wp_upload_dir();
                $wpcb_custom_template_dirs = array('/wpcb-custom-templates','/wpcb-custom-templates/call-to-action','/wpcb-custom-templates/email-optin','/wpcb-custom-templates/video-call-to-action','/wpcb-custom-templates/video-email-optin');
                foreach($wpcb_custom_template_dirs as $wpcb_custom_template_dir){
                    if (!is_dir( $upload_dir['basedir'].$wpcb_custom_template_dir )) {
                        wp_mkdir_p( $upload_dir['basedir'].$wpcb_custom_template_dir );
                    }
                }
	}

        /***************************************
	 * Update Table Structure After Update
         * 
         * Current Version : 1
	 ***************************************/
        
        public function update_table_structure() {
                global $wpdb;
                $wpcb_tbl = $wpdb->prefix.'wp_conversion_boxes_pro';
                $all_columns = $wpdb->get_row("SELECT * FROM $wpcb_tbl");
                
                if(!isset($all_columns->box_status)){
                    $wpdb->query("ALTER TABLE $wpcb_tbl ADD box_status INT(1) NOT NULL DEFAULT 1");
                }                
        }
        
        
	/***************************************
	 * Fired for each blog when the plugin 
         * is deactivated.
	 ***************************************/
	private static function single_deactivate() {
		
	}


	/***************************************
	 * Register and enqueue public-facing 
         * style sheet.
	 ***************************************/
	public function enqueue_styles() {
		wp_enqueue_style( $this->wpcb_main_slug . '-plugin-styles', PUBLIC_ASSETS_URL .'css/public.css', array() );
	}

        
	/***************************************
	 * Register and enqueues public-facing 
         * JavaScript files.
	 ***************************************/
	public function enqueue_scripts() {
		// wp_enqueue_script( $this->wpcb_main_slug . '-plugin-script', PUBLIC_ASSETS_URL .'js/public.js', array( 'jquery' ) );
	}
        
        /***************************************
         * Show box in front-end
         ***************************************/
        
        public function show_the_box($id){
            
            $wpcb_tracking = WPCB_Tracker::get_instance();            
            
            $wpcb_ab_test_rotate = get_option('wpcb_ab_test_rotate_'.$id);
        
            if($wpcb_ab_test_rotate != false && $wpcb_ab_test_rotate != 0){
                $old_id = $id;
                $id = $wpcb_ab_test_rotate;
            }

            global $wpdb;

            $wpcb_tbl_name = $this->get_boxes_table_name();
            
            $wpcb_the_row = $wpdb->get_row($wpdb->prepare("SELECT * from $wpcb_tbl_name WHERE id = %d", array($id)));

            if (isset($wpcb_the_row)) {
                $box_name = (isset($wpcb_the_row->box_name)) ? $wpcb_the_row->box_name : "";
                $box_status = $wpcb_the_row->box_status;
                $box_type = $wpcb_the_row->box_type;
                $box_template = $wpcb_the_row->box_template;
                $is_custom_template = $wpcb_the_row->is_custom_template;
                $box_customizations = $wpcb_the_row->box_customizations;
                $box_settings = $wpcb_the_row->box_settings;
                $test_enabled = (isset($wpcb_the_row->test_enabled)) ? $wpcb_the_row->test_enabled : '';
                $test_with = (isset($wpcb_the_row->test_with)) ? $wpcb_the_row->test_with : '';
            }
            else {
                if(isset($old_id)){
                    update_option('wpcb_ab_test_rotate_'.$old_id, 0 );
                }
                return;
            }
            
            if($box_status != 1){
                ob_start();
                return;
            }

            if($test_enabled == 1 && $test_with != 0){
                update_option('wpcb_ab_test_rotate_'.$id, $test_with );
            }
            else if($test_enabled == 1 && $test_with == 0){
                update_option('wpcb_ab_test_rotate_'.$old_id, 0 );
            }
            else{
                update_option('wpcb_ab_test_rotate_'.$id, 0 );
            }
            
            $box_id = $id;
            
            if($box_customizations != null AND $box_customizations != 'defaults'){
                $wpcb_default_fields = unserialize($box_customizations);
                $wpcb_default_fields = $this->sanitise_array($wpcb_default_fields);
                $wpcb_default_fields['defaults'] = 'custom';
            }
            else{
                $wpcb_default_fields['defaults'] = 'defaults';
            }
            
            if(isset($box_settings)){
            
                $box_settings = unserialize($box_settings);

                $wpcb_box_settings = $this->sanitise_array($box_settings);
                
                if($wpcb_box_settings['box_fade_in'] == '1'){
                    $wpcb_settings_data['box_fade_in'] = "wpcb_fade_in";
                }
                else{
                    $wpcb_settings_data['box_fade_in'] = "wpcb_nothing";
                }

                $wpcb_settings_data['box_fade_in_time'] = $wpcb_box_settings['box_fade_in_time'];

                if($wpcb_box_settings['box_make_sticky'] == '1'){
                    $wpcb_settings_data['box_make_sticky'] = "box_make_sticky";
                }
                else{
                    $wpcb_settings_data['box_make_sticky'] = "wpcb_nothing";
                }
                
                if($wpcb_box_settings['box_slide_in'] == '1'){
                    wp_enqueue_script('jquery-effects-slide','','jquery-effects-core');
                    $box_slide_in_js = "<div class='wpcb_box_slide_in' data-from='".$wpcb_box_settings['box_slide_in_from']."' data-speed='".$wpcb_box_settings['box_slide_in_speed']."'></div>";
                }
                else{
                    $box_slide_in_js = "";
                }   
            }
            else{
                $wpcb_settings_data['box_fade_in'] = "wpcb_nothing";
                $wpcb_settings_data['box_fade_in_time'] = "0";
                $wpcb_settings_data['box_make_sticky'] = "wpcb_nothing";
                $box_slide_in_js = "";
            }
            
            $box_processing_head = (isset($wpcb_box_settings['box_processing_head'])) ? esc_attr($wpcb_box_settings['box_processing_head']) : esc_attr__('Processing... Please Wait!','wp-conversion-boxes-pro');
            $box_taking_too_long = (isset($wpcb_box_settings['box_taking_too_long'])) ? esc_attr($wpcb_box_settings['box_taking_too_long']) : esc_attr__('It\'s taking longer than usual. Please hang on for a few moments...','wp-conversion-boxes-pro');
            $box_success_head = (isset($wpcb_box_settings['box_success_head'])) ? esc_attr($wpcb_box_settings['box_success_head']) : esc_attr__('Success!','wp-conversion-boxes-pro');
            $box_success_desc = (isset($wpcb_box_settings['box_success_desc'])) ? esc_attr($wpcb_box_settings['box_success_desc']) : esc_attr__('Thanks for subscribing!','wp-conversion-boxes-pro');
            $box_error_head = (isset($wpcb_box_settings['box_error_head'])) ? esc_attr($wpcb_box_settings['box_error_head']) : esc_attr__('Error!','wp-conversion-boxes-pro');
            $box_error_desc = (isset($wpcb_box_settings['box_error_desc'])) ? esc_attr($wpcb_box_settings['box_error_desc']) : esc_attr__('There was an error submitting your info.','wp-conversion-boxes-pro');
            $box_after_optin_messages = "<div class='wpcb-after-optin-messages' data-boxid='$box_id' data-box-processing-head='$box_processing_head' data-box-taking-too-long='$box_taking_too_long' data-box-success-head='$box_success_head' data-box-success-desc='$box_success_desc' data-box-error-head='$box_error_head' data-box-error-desc='$box_error_desc'></div>";
            
            ob_start();
            
            // Run actions before box is diplayed
            do_action('wpcb_before_box', $box_id );
            
            echo '<div class="'. $wpcb_settings_data['box_make_sticky'].'_offset"></div>';
            
            if($is_custom_template == 0){
                switch($box_type){
                        case 1 :    include(WPCB_TEMPLATE_DIR_PATH.$this->get_template_directory(1).'/'.$box_template.'/template.php');
                                    break;
                        case 2 :    include(WPCB_TEMPLATE_DIR_PATH.$this->get_template_directory(2).'/'.$box_template.'/template.php');
                                    break;
                        case 3 :    include(WPCB_TEMPLATE_DIR_PATH.$this->get_template_directory(3).'/'.$box_template.'/template.php');
                                    break;
                        case 4 :    include(WPCB_TEMPLATE_DIR_PATH.$this->get_template_directory(4).'/'.$box_template.'/template.php');
                                    break;                                     
                }
            }
            else{
                switch($box_type){
                        case 1 :    include(WPCB_CUSTOM_TEMPLATE_DIR_PATH.$this->get_template_directory(1).'/'.$box_template.'/template.php');
                                    break;
                        case 2 :    include(WPCB_CUSTOM_TEMPLATE_DIR_PATH.$this->get_template_directory(2).'/'.$box_template.'/template.php');
                                    break;
                        case 3 :    include(WPCB_CUSTOM_TEMPLATE_DIR_PATH.$this->get_template_directory(3).'/'.$box_template.'/template.php');
                                    break;
                        case 4 :    include(WPCB_CUSTOM_TEMPLATE_DIR_PATH.$this->get_template_directory(4).'/'.$box_template.'/template.php');
                                    break;                                     
                }
            }
            
            if(get_option('wpcb_enable_credit_link') == 1){
                $credit_link = "<span class='wpcb_credit_link'>".__("Made using <a target='_blank' href='http://wpconversionboxes.com'>WP Conversion Boxes Pro</a>",'wp-conversion-boxes-pro')."</span>";
                $credit_link = apply_filters('wpcb_credit_link' , $credit_link );
                echo  $credit_link ;
            }
            
            if($box_type != 3 && $box_type != 4)
                echo $box_after_optin_messages;
            
            // Echo JS if Slide in activated
            echo $box_slide_in_js;
            
            if (isset($wpcb_the_row) && get_option('wpcb_ga_tracking') == 0 ) {
                $wpcb_tracking->log_new_visit($box_id);
            }
            else{
                echo "<script type='text/javascript'>ga('send', 'event', '".$box_name."' , 'Pageviews' , '".$_SERVER['REQUEST_URI']."');</script>";
            }
            
            // Run actions after box has been diplayed
            do_action('wpcb_after_box' , $box_id );

        }
        
        /******************************
         * Main shortcode [wpcb]
         ******************************/

        public function run_shortcode($atts) {
            extract(shortcode_atts(array(
                'id' => 0
            ), $atts));
            $this->show_the_box($id);
            $final_output = ob_get_clean();
            $final_output = apply_filters('wpcb_box_content' , $final_output);
            return $final_output;
        }
        
        /******************************
         * This function handles the 
         * output of box according to
         * settings.
         ******************************/

        public function show_box($content){
            $posttype = get_post_type();
            $postid = get_the_ID();
            
            $categories = get_the_category();
            $cat_id = (isset($categories[0]->cat_ID)) ? $categories[0]->cat_ID : "";
            
            $wpcb_meta_selected_box_id = get_post_meta( $postid, 'wpcb_meta_selected_box_id', true );
            $wpcb_box_id_for_cat = get_option('wpcb_box_for_cat_'.$cat_id);
            
            
            // Return if 'Don't Show Box' selected.
            if($wpcb_meta_selected_box_id == '-1'){
                return $content;
            }
            
            if(isset($wpcb_meta_selected_box_id) && $wpcb_meta_selected_box_id != 0){
                $this->show_the_box($wpcb_meta_selected_box_id);
            }
            else{
                
                if(isset($wpcb_box_id_for_cat) && $wpcb_box_id_for_cat != 0 ){
                    $this->show_the_box($wpcb_box_id_for_cat);
                }
                
                else{
                    $wpcb_default_box = get_option('wpcb_default_box');
                    $wpcb_all_posts = get_option('wpcb_all_posts');
                    $wpcb_all_pages = get_option('wpcb_all_pages');

                    if($wpcb_all_posts == $wpcb_all_pages && $wpcb_all_posts != 0){
                        $this->show_the_box($wpcb_all_posts);
                    }else if($posttype == 'post' && $wpcb_all_posts != 0){
                        $this->show_the_box($wpcb_all_posts);
                    }
                    else if($posttype == 'page' && $wpcb_all_pages != 0){
                        $this->show_the_box($wpcb_all_pages);
                    }
                    else if($wpcb_default_box != 0){
                        $this->show_the_box($wpcb_default_box);
                    }
                    else {
                        ob_start();
                    }
                }
            }
            
            $final_output = ob_get_contents();  // get buffer content
            $final_output = apply_filters('wpcb_box_content' , $final_output);
            ob_end_clean();
            if(isset($final_output)){ 
                return $content.$final_output; 
            }
            else{ 
                return $content; 
            }
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
                            if($k != 'content_text'){
                                $data[$k] = esc_attr(stripslashes(trim($v)));
                            }
                            else{
                                $data[$k] = stripslashes(trim($v));
                            }
                    }
                    if (is_array($v)) {
                            $data[$k] = sanitise_array($v);
                    }
            }

            return $data;
        }
        
        /***************************************
         * Register widget
         ***************************************/
        
        public function register_wpcb_widget() {
            include_once('class.widget.php');
            register_widget( 'WPCB_Widget' );
        }
        
        /****************************************
         * Add new contact to respective campaign
         * or list
         * 
         * GetResponse : 1
         * MailChimp : 2
         * Aweber : 3
         * MadMimi : 4
         * Constant Contact : 5
         * Campaign Monitor : 6
         * InfusionSoft : 7
         * iContact : 8
         * MailPoet : 9
         * Pardot : 10
         ***************************************/
        
        function add_new_contact(){
            $name = isset($_POST['name']) ? $_POST['name'] : "";
            $email = isset($_POST['email']) ? $_POST['email'] : "";
            $mailer_id = isset($_POST['mailer_id']) ? $_POST['mailer_id'] : "";
            $campaign_id = isset($_POST['campaign_id']) ? $_POST['campaign_id'] : "";
            $tracker_id = isset($_POST['tracker_id']) ? $_POST['tracker_id'] : "";
            
            switch($mailer_id){
                // GetResponse
                case 1: $getresponse_api_key = get_option('wpcb_getresponse_api_key');
                        include_once(MAILERS_DIR_PATH.'getresponse-api.php');
                        $getresponse = new jsonRPCClient('http://api2.getresponse.com');
                        try{
                            $result_contact = $getresponse->add_contact($getresponse_api_key, array ('campaign' => $campaign_id,'name' => $name,'email' => $email,'cycle_day' => 0));
                            global $wpdb;
                            $wpcb_tbl_name = $this->get_tracking_table_name();
                            $wpdb->update($wpcb_tbl_name, array('visittype' => 'optin'), array('id' => $tracker_id), array('%s'), array('%d'));
                            echo 1;
                        }
                        catch (Exception $e){
                            echo $e;
                        }
                        die();
                        break;
                // MailChimp
                case 2: $mailchimp_api_key = get_option('wpcb_mailchimp_api_key');
                        include_once(MAILERS_DIR_PATH.'mailchimp-api.php');
                        $mailchimp = new MCAPI($mailchimp_api_key);
                        $merge_vars = array('FNAME' => $name, 'LNAME' => '');
                        $retval = $mailchimp->listSubscribe($campaign_id, $email, $merge_vars);
                        if($mailchimp->errorCode){
                            echo 0;
                        }
                        else {
                            global $wpdb;
                            $wpcb_tbl_name = $this->get_tracking_table_name();
                            $wpdb->update($wpcb_tbl_name, array('visittype' => 'optin'), array('id' => $tracker_id), array('%s'), array('%d'));
                            echo 1;
                        }
                        die();
                        break;
                // Aweber
                case 3: include_once(MAILERS_DIR_PATH.'aweber_api/aweber_api.php');
                        try {
                            $aweber_api_key = get_option('wpcb_aweber_api_key');
                            $aweber_data = unserialize($aweber_api_key);
                            $aweber = new AWeberAPI($aweber_data[0], $aweber_data[1]);
                            $account = $aweber->getAccount($aweber_data[2], $aweber_data[3]);
                            $account_id = $account->id;
                            $listURL = "/accounts/".$account_id."/lists/".$campaign_id;
                            $list = $account->loadFromUrl($listURL);
                            $params = array(
                                'email' => $email,
                                'name' => $name
                            );
                            $subscribers = $list->subscribers;
                            $new_subscriber = $subscribers->create($params);
                            global $wpdb;
                            $wpcb_tbl_name = $this->get_tracking_table_name();
                            $wpdb->update($wpcb_tbl_name, array('visittype' => 'optin'), array('id' => $tracker_id), array('%s'), array('%d'));
                            echo 1;
                        }
                        catch (Exception $exc)
                        {
                            echo 0;
                        }
                        die();
                        break;
                // MadMimi
                case 4 :$madmimi_api_key = get_option('wpcb_madmimi_api_key');
                        $madmimi_data = unserialize($madmimi_api_key );
                        include_once(MAILERS_DIR_PATH.'madmimi/MadMimi.class.php');
                        $madmimi = new MadMimi($madmimi_data['username'], $madmimi_data['api_key']);
                        $info = array(
                            'email' => $email,
                            'firstName' => $name,
                            'add_list' => $campaign_id
                        );
                        $madmimi->AddUser($info);
                        global $wpdb;
                        $wpcb_tbl_name = $this->get_tracking_table_name();
                        $wpdb->update($wpcb_tbl_name, array('visittype' => 'optin'), array('id' => $tracker_id), array('%s'), array('%d'));
                        echo 1;
                        die();
                        break;
                // Constant Contact
                case 5 :$constant_contact_access_token = get_option('wpcb_constant_contact_api_key');
                        $cc_response = wp_remote_get('https://api.constantcontact.com/v2/contacts?api_key=wcp88fmhmyxmegwbpvcpz36x&access_token=' . $constant_contact_access_token . '&email=' . $email);
                        $cc_contact = json_decode(wp_remote_retrieve_body($cc_response));
                        if (!empty($cc_contact->results)) {
                            echo 0;
                        } else {
                            $args = $body = array();
                            $body['email_addresses'] = array();
                            $body['email_addresses'][0]['id'] = $campaign_id;
                            $body['email_addresses'][0]['status'] = 'ACTIVE';
                            $body['email_addresses'][0]['email_address'] = $email;
                            $body['lists'] = array();
                            $body['lists'][0]['id'] = $campaign_id;
                            $body['first_name'] = $name;

                            $args['body'] = json_encode($body);

                            $args['headers']['Content-Type'] = 'application/json';
                            $args['headers']['Content-Length'] = strlen(json_encode($body));
                            $create = wp_remote_post('https://api.constantcontact.com/v2/contacts?api_key=wcp88fmhmyxmegwbpvcpz36x&access_token=' . $constant_contact_access_token , $args);
                            global $wpdb;
                            $wpcb_tbl_name = $this->get_tracking_table_name();
                            $wpdb->update($wpcb_tbl_name, array('visittype' => 'optin'), array('id' => $tracker_id), array('%s'), array('%d'));
                            echo 1;
                        }
                        die();
                        break;
                // Campaign Monitor
                case 6 :$campaign_monitor_api_key = get_option('wpcb_campaign_monitor_api_key');
                        include_once(MAILERS_DIR_PATH. 'campaign-monitor/csrest_subscribers.php');
                        $campaign_monitor = new CS_Rest_Subscribers($campaign_id, $campaign_monitor_api_key);
                        $result = $campaign_monitor->add(array('EmailAddress' => $email, 'Name' => $name, 'Resubscribe' => true, 'CustomFields' => array(array('Key' => 'WP Conversion Boxes Pro', 'Value' => true))));
                        if ($result->was_successful()) {
                            global $wpdb;
                            $wpcb_tbl_name = $this->get_tracking_table_name();
                            $wpdb->update($wpcb_tbl_name, array('visittype' => 'optin'), array('id' => $tracker_id), array('%s'), array('%d'));
                            echo 1;
                        } else {
                            echo 0;
                        }
                        die();
                        break;
                // Infusion Soft (not checked)
                case 7 :$infusionsoft_api = get_option('wpcb_infusionsoft_api_key');
                        $infusionsoft_api_data = unserialize($infusionsoft_api);
                        $infusionsoft_subdomain = $infusionsoft_api_data['subdomain'];
                        $infusionsoft_api_key = $infusionsoft_api_data['api_key'];
                        include_once(MAILERS_DIR_PATH. 'infusionsoft/isdk.php');
                        try {
                            $infusionsoft = new iSDK();
                            $infusionsoft->cfgCon($infusionsoft_subdomain, $infusionsoft_api_key, 'throw');
                        } catch (iSDKException $e) {
                            echo 0;
                            die();
                        }
                        try {
                            $contact_id = $infusionsoft->addCon(array('FirstName' => $name, 'Email' => $email));
                            $group_add = $infusionsoft->grpAssign($contact_id, $campaign_id); //$campaign_id = group_id
                        } catch (iSDKException $e) {
                            echo 0;
                            die();
                        }
                        global $wpdb;
                        $wpcb_tbl_name = $this->get_tracking_table_name();
                        $wpdb->update($wpcb_tbl_name, array('visittype' => 'optin'), array('id' => $tracker_id), array('%s'), array('%d'));
                        echo 1;
                        die();
                        break;
                // iContact
                case 8 :$icontact_api = get_option('wpcb_icontact_api_key');
                        $icontact_api_data = unserialize($icontact_api);
                        $icontact_username = $icontact_api_data['username'];
                        $icontact_app_password = $icontact_api_data['app_password'];
                        $icontact_app_id = $icontact_api_data['api_key'];
                        include_once(MAILERS_DIR_PATH. 'icontact/iContactApi.php');

                            try {
                                iContactApi::getInstance()->setConfig(array(
                                    'appId' => $icontact_app_id,
                                    'apiPassword' => $icontact_app_password,
                                    'apiUsername' => $icontact_username
                                ));
                                $icontact = iContactApi::getInstance();
                                $contact = $icontact->addContact($email, 'normal', null, $name);
                                
                                // Subscribe the contact to the list.
                                $subscribe = $icontact->subscribeContactToList($contact->contactId, $campaign_id);
                                global $wpdb;
                                $wpcb_tbl_name = $this->get_tracking_table_name();
                                $wpdb->update($wpcb_tbl_name, array('visittype' => 'optin'), array('id' => $tracker_id), array('%s'), array('%d'));
                                echo 1;
                            } catch (Exception $e) {
                                echo 0;
                            }

                        die();
                        break;
                // MailPoet
                case 9 :$mailpoet_data = array(
                            'user' => array('email' => $email, 'firstname' => $name),
                            'user_list' => array('list_ids' => array($campaign_id))
                        );

                        // Add subscriber to MailPoet.
                        $userHelper = WYSIJA::get('user', 'helper');
                        $userHelper->addSubscriber($mailpoet_data);
                        global $wpdb;
                        $wpcb_tbl_name = $this->get_tracking_table_name();
                        $wpdb->update($wpcb_tbl_name, array('visittype' => 'optin'), array('id' => $tracker_id), array('%s'), array('%d'));
                        $this->data['success'] = true;
                        echo 1;
                        die();
                        break;
                // Pardot
                case 10:$pardot_api_data_serialized = get_option('wpcb_pardot_api_key');
                        $pardot_api_data = unserialize($pardot_api_data_serialized);
                        
                        include_once(MAILERS_DIR_PATH. 'pardot/pardot-api-class.php');
                        
                        $pardot = new Pardot_OM_API($pardot_api_data);
                        $pardot->authenticate($pardot_api_data);

                        $url = 'https://pi.pardot.com/api/prospect/version/3/do/create/email/' . $email . '?campaign_id=' . $campaign_id . '&first_name=' . $name . '&api_key=' . $pardot->api_key . '&user_key=' . $pardot_api_data['user_key'];
                        $contact = wp_remote_post($url);
                        $xml_resp = new SimpleXMLElement(wp_remote_retrieve_body($contact));
                        $response = json_decode(json_encode($xml_resp));

                        if (isset($response->err))
                            echo 0;
                        else{
                            global $wpdb;
                            $wpcb_tbl_name = $this->get_tracking_table_name();
                            $wpdb->update($wpcb_tbl_name, array('visittype' => 'optin'), array('id' => $tracker_id), array('%s'), array('%d'));
                            echo 1;
                        }
                        die();                        
                        break;
                //Custom List        
                case 99:$all_custom_lists_serialized = get_option('wpcb_custom_lists');
                        $all_custom_lists = unserialize($all_custom_lists_serialized);
                        
                        $list_data = $all_custom_lists[$campaign_id];
                        
                        $url = $list_data['action_url'];
                        
                        $post_data = array( 
                            $list_data['names_name_tag'] => $name, 
                            $list_data['emails_name_tag'] => $email
                        );
                        
                        if($list_data['other_fields'] != ''){
                            $post_data = array_merge($post_data, $list_data['other_fields']);
                        }
                        
                        $response = wp_remote_post( $url, array(
                                'method' => 'POST',
                                'timeout' => 45,
                                'redirection' => 5,
                                'httpversion' => '1.0',
                                'blocking' => true,
                                'headers' => array(),
                                'body' => $post_data,
                                'cookies' => array()
                            )
                        );
                        
                        echo 1;
                        die();
                        break;
            }
            die();
        }
}

/******************************************
 * Returns the current custom template url
 ******************************************/

function get_current_custom_template_url( $the_file , $current_file_dir ){
    $current_file_dir = wp_normalize_path($current_file_dir);
    $base = basename(dirname(dirname($current_file_dir)));
    $base .= "/".basename(dirname($current_file_dir))."/";
    $the_file = wp_normalize_path($the_file);
    return WPCB_CUSTOM_TEMPLATE_DIR_URL.$base.$the_file;
}