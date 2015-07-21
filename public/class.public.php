<?php

/*************************************
* WP COnversion Boxes Public Class
*************************************/

class WPCB_Public {

        const WPCB_AUTHOR_NAME = 'Ram Shengale';
        
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
        protected $template_directory_5 = '2-step-optin-link';
        protected $template_directory_6 = 'smart-popup';
        
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
                add_filter( 'the_content', array( $this, 'show_box') );
                add_shortcode( 'wpcb' , array( $this, 'run_shortcode') );
        
                add_action( 'wp_ajax_add_new_contact', array( $this, 'add_new_contact') );
                add_action( 'wp_ajax_nopriv_add_new_contact', array( $this, 'add_new_contact') );
                
                /***************************************
		 * Init functions
		 ***************************************/
                add_action( 'init' , array ($this, 'update_table_structure') );

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
                case 5: return $this->template_directory_5;
                        break;
                case 6: return $this->template_directory_6;
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
                $wpdb->query("CREATE TABLE IF NOT EXISTS $wpcb_tbl_1 (
                    id INT(9) NOT NULL AUTO_INCREMENT,
                    box_status int(1) DEFAULT 1,
                    box_name VARCHAR(80) NOT NULL,
                    box_type int(9),
                    box_template VARCHAR(120),
                    box_customizations longtext,
                    box_settings VARCHAR(350),
                    UNIQUE KEY id (id)
                ) DEFAULT CHARSET=utf8;","%s");
                
                
                $wpcb_tbl_2 = $wpdb->prefix.'wpcb_tracking';
                $wpdb->query("CREATE TABLE IF NOT EXISTS $wpcb_tbl_2 (
                    id INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                    ip VARCHAR( 16 ) NOT NULL ,
                    host VARCHAR( 100 ) NOT NULL ,
                    visitdate DATETIME NOT NULL ,
                    visitedpage VARCHAR(255) NOT NULL ,
                    referring VARCHAR( 255 ) NOT NULL ,
                    visittype ENUM('visit', 'boxview', 'click', 'optin'),
                    box_id INT(9) NOT NULL DEFAULT 0 
                ) DEFAULT CHARSET=utf8;","%s");
                
                add_option('wpcb_default_box', 0 , '', 'yes' );
                add_option('wpcb_all_posts', 0 , '', 'yes' );
                add_option('wpcb_all_pages', 0 , '', 'yes' );
                add_option('wpcb_enable_credit_link', 0 , '', 'yes' );
                add_option('wpcb_database_version', 1 );
	}
        
        /***************************************
	 * Update Table Structure After Update
         * 
         * Current Version : 1
	 ***************************************/
        
        public function update_table_structure() {
                global $wpdb;
                $wpcb_tbl = $wpdb->prefix.'wp_conversion_boxes';
                
                if(get_option('wpcb_database_version') != 1){
                    $all_columns = $wpdb->get_row("SELECT * FROM $wpcb_tbl");
                    if(!isset($all_columns->box_status)){
                        $wpdb->query("ALTER TABLE $wpcb_tbl ADD box_status INT(1) NOT NULL DEFAULT 1");
                        if($done != false)
                            update_option('wpcb_database_version', 1); // From original 0;
                    }
                }
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
         * Show the box
         ***************************************/
        
        public function show_the_box($id,$attributes = null){
            
            $wpcb_tracking = WPCB_Tracker::get_instance();            
            
            global $wpdb;

            $wpcb_tbl_name = $this->get_boxes_table_name();
            $wpcb_the_row = $wpdb->get_row($wpdb->prepare("SELECT * from $wpcb_tbl_name WHERE id = %d", array($id)));

            if (isset($wpcb_the_row)) {
                $box_status = $wpcb_the_row->box_status;
                $box_type = $wpcb_the_row->box_type;
                $box_template = $wpcb_the_row->box_template;
                $box_customizations = $wpcb_the_row->box_customizations;
                $box_settings = $wpcb_the_row->box_settings;
            } else {
                return;
            }
            
            $box_id = $id;
            
            if($box_status != 1){
                ob_start();
                return;
            }
            
            if($box_customizations != null AND $box_customizations != 'defaults'){
                $wpcb_default_fields = unserialize($box_customizations);
                $wpcb_default_fields = $this->sanitise_array($wpcb_default_fields);
                $wpcb_default_fields['defaults'] = 'custom';
            }
            else{
                $wpcb_default_fields['defaults'] = 'defaults';
            }
            
            if(isset($box_settings)){
            
                $wpcb_box_settings = unserialize($box_settings);

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
        
            }
            else{
                $wpcb_settings_data['box_fade_in'] = "wpcb_nothing";
                $wpcb_settings_data['box_fade_in_time'] = "0";
                $wpcb_settings_data['box_make_sticky'] = "wpcb_nothing";
            }
            
            $wpcb_popup_type_radio = (isset($wpcb_box_settings['wpcb_popup_type_radio'])) ? $wpcb_box_settings['wpcb_popup_type_radio'] : 0;
            $wpcb_popup_option_val = (isset($wpcb_box_settings['wpcb_popup_option_val'])) ? $wpcb_box_settings['wpcb_popup_option_val'] : 0;
            $wpcb_popup_frequency = (isset($wpcb_box_settings['wpcb_popup_frequency'])) ? $wpcb_box_settings['wpcb_popup_frequency'] : 10;
            
            ob_start();
            echo '<!--------------------------------------><!-- Conversion Box Made Using : -------><!-- WP Conversion Boxes - -------------><!-- http://wpconversionboxes.com --><!-------------------------------------->';
            echo '<div class="'. $wpcb_settings_data['box_make_sticky'].'_offset"></div>';
            
            switch($box_type){
                case 1 :    include(WPCB_TEMPLATE_DIR_PATH.$this->get_template_directory(1).'/'.$box_template.'/template.php');
                            break;
                case 2 :    include(WPCB_TEMPLATE_DIR_PATH.$this->get_template_directory(2).'/'.$box_template.'/template.php');
                            break;
                case 3 :    include(WPCB_TEMPLATE_DIR_PATH.$this->get_template_directory(3).'/'.$box_template.'/template.php');
                            break;
                case 4 :    include(WPCB_TEMPLATE_DIR_PATH.$this->get_template_directory(4).'/'.$box_template.'/template.php');
                            break;
                case 5 :    if($attributes['style'] != 'image'){
                                $anchor = $attributes['text'];
                                if($attributes['style'] == 'none' || $attributes['style'] == ''){
                                    $attributes['style'] = 'none_none';
                                }
                                $style = explode('_', $attributes['style']);
                            }
                            else{
                                $anchor = '<img src="'.$attributes['image_url'].'" />';
                            }
                            echo '<a id="wpcb_two_step_optin_link_'.$box_id.'" class="wpcb_button_'.$style[1].' wpcb_two_step_optin_link_'.$style[0].'">'.$anchor.'</a>';
                            echo '<style>div.wpcb_template_main.wpcb_template_main_'.$box_id.'{display: none;}</style>';
                            include(WPCB_TEMPLATE_DIR_PATH.$this->get_template_directory(5).'/'.$box_template.'/template.php');
                            break;
                case 6 :    echo '<div class="wpcb_popup_data" id="wpcb_popup_data_'.$box_id.'" data-popup-type="'.$wpcb_popup_type_radio.'" data-popup-val="'.$wpcb_popup_option_val.'" data-popup-frequency="'.$wpcb_popup_frequency.'"></div>';
                            echo '<style>div.wpcb_template_main.wpcb_template_main_'.$box_id.'{display: none;}</style>';
                            include(WPCB_TEMPLATE_DIR_PATH.$this->get_template_directory(5).'/'.$box_template.'/template.php');
                            break;
            }
            
            if(get_option('wpcb_enable_credit_link') == 1){
                echo "<span class='wpcb_credit_link'>". __("Made using <a target='_blank' href='http://wpconversionboxes.com'>WP Conversion Boxes</a>",'wp-conversion-boxes') ."</span>";
            }
            
            echo  '<!------------------------------><!-- Conversion Box Ends Here --><!------------------------------>';
            
            if (isset($wpcb_the_row)) {
                $wpcb_tracking->log_new_visit($box_id);
            }

        }
        
        /******************************
         * Main shortcode [wpcb]
         ******************************/

        public function run_shortcode($atts) {
            $attributes = shortcode_atts(array(
                'id' => 0,
                'text' => 'Click Here',
                'style' => 'none',
                'image_url' => 'http://'
            ), $atts);
            $this->show_the_box($attributes['id'], $attributes);
            return ob_get_clean();
        }

        public function show_box($content){
            $posttype = get_post_type();
        
            $wpcb_default_box = get_option('wpcb_default_box');
            $wpcb_all_posts = get_option('wpcb_all_posts');
            $wpcb_all_pages = get_option('wpcb_all_pages');
            
            $attributes = array(
                'text' => 'Click Here',
                'style' => 'none',
                'image_url' => 'http://'
            );

            if($wpcb_all_posts == $wpcb_all_pages && $wpcb_all_posts != 0){
                $this->show_the_box($wpcb_all_posts, $attributes);
            }else if($posttype == 'post' && $wpcb_all_posts != 0){
                $this->show_the_box($wpcb_all_posts, $attributes);
            }
            else if($posttype == 'page' && $wpcb_all_pages != 0){
                $this->show_the_box($wpcb_all_pages, $attributes);
            }
            else if($wpcb_default_box != 0){
                $this->show_the_box($wpcb_default_box, $attributes);
            }
            else {
                ob_start();
            }

            $final_output = ob_get_contents();  // get buffer content
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
        /****************************************
         * Add new contact to respective campaign
         * or list
         ***************************************/
        
        function add_new_contact(){
            $name = $_POST['name'];
            $email = $_POST['email'];
            $mailer_id = $_POST['mailer_id'];
            $campaign_id = $_POST['campaign_id'];
            $tracker_id = $_POST['tracker_id'];
            
            switch($mailer_id){
                // GetResponse
                case 1: $getresponse_api_key = get_option('wpcb_getresponse_api_key');
                        include_once(plugin_dir_path(dirname(__FILE__)).'admin/mailers/getresponse-api.php');
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
                        break;
                // MailChimp
                case 2: $mailchimp_api_key = get_option('wpcb_mailchimp_api_key');
                        include_once(plugin_dir_path(dirname(__FILE__)).'admin/mailers/mailchimp-api.php');
                        $mailchimp = new MCAPI($mailchimp_api_key);
                        $merge_vars = array('FNAME' => $name, 'LNAME' => '');
                        $retval = $mailchimp->listSubscribe($campaign_id, $email, $merge_vars, 'html', true, true);
                        if($mailchimp->errorCode){
                            echo 0;
                        }
                        else {
                            global $wpdb;
                            $wpcb_tbl_name = $this->get_tracking_table_name();
                            $wpdb->update($wpcb_tbl_name, array('visittype' => 'optin'), array('id' => $tracker_id), array('%s'), array('%d'));
                            echo 1;
                        }
                        break;
                // Aweber
                case 3: include_once(plugin_dir_path(dirname(__FILE__)).'admin/mailers/aweber_api/aweber_api.php');
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
                
                // Feedburner
                case 11:$feedburner_uri = get_option('wpcb_feedburner_uri');
                        global $wpdb;
                        $wpcb_tbl_name = $this->get_tracking_table_name();
                        $wpdb->update($wpcb_tbl_name, array('visittype' => 'optin'), array('id' => $tracker_id), array('%s'), array('%d'));
                        echo 1;
                        break;
            }
            die();
        }

}
