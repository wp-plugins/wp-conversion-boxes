<?php
/*************************************
* WP Conversion Boxes Admin Class
*************************************/


class WPCB_Admin {
    
        /************************************
	 * Instance of this class.
	 ************************************/
	protected static $instance = null;

        /*************************************
	 * Slug of the plugin screen.
	 ************************************/
	protected $wpcb_main_screen_hook_suffix = null;
        protected $wpcb_edit_screen_hook_suffix = null;
        protected $wpcb_settings_screen_hook_suffix = null;
        
        /*************************************
	 * Initialize the plugin by loading admin 
         * scripts & styles and adding a settings 
         * page and menu.
	 *************************************/
	private function __construct() {
            
                /**************************************
		 * Call variables from public plugin class.
		 **************************************/
		$wpcb_public = WPCB_Public::get_instance();
		$this->wpcb_main_slug = $wpcb_public->get_wpcb_main_slug();
                $this->wpcb_edit_slug = $wpcb_public->get_wpcb_edit_slug();
                $this->wpcb_settings_slug = $wpcb_public->get_wpcb_settings_slug();
                $this->template_directory_1 = $wpcb_public->get_template_directory(1);
                $this->template_directory_2 = $wpcb_public->get_template_directory(2);
                $this->template_directory_3 = $wpcb_public->get_template_directory(3);
                $this->template_directory_4 = $wpcb_public->get_template_directory(4);
                $this->wpcb_boxes_table = $wpcb_public->get_boxes_table_name();
                $this->wpcb_tracking_table = $wpcb_public->get_tracking_table_name();
                
                /**************************************		
                * Load admin style sheet and JavaScript.
                ***************************************/
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

                
                /**************************************		
                 * Add the options page and menu item.
                 ***************************************/
		add_action( 'admin_menu', array( $this, 'wpcb_add_admin_menu' ) );

                
                /**************************************		
                 * Add an action link pointing to the
                 * options page.
                 **************************************/
		$wpcb_basename = plugin_basename( plugin_dir_path( realpath( dirname( __FILE__ ) ) ) . $this->wpcb_main_slug . '.php' );
		add_filter( 'plugin_action_links_' . $wpcb_basename, array( $this, 'add_action_links' ) );

                /***************************************
		 * Admin actions and filters.
		 ***************************************/
		
                add_action( 'wp_ajax_create_new_box', array( $this, 'create_new_box') );
                add_action( 'wp_ajax_update_box_template', array( $this, 'update_box_template') );
                add_action( 'wp_ajax_update_box_customizations', array( $this, 'update_box_customizations') );
                add_action( 'wp_ajax_restore_to_default', array( $this, 'restore_to_default') );
                add_action( 'wp_ajax_update_box_settings', array( $this, 'update_box_settings') );
                add_action( 'wp_ajax_update_global_settings', array( $this, 'update_global_settings') );
                add_action( 'wp_ajax_delete_it', array( $this, 'delete_it') );
                add_action( 'wp_ajax_delete_custom_list', array( $this, 'delete_custom_list') );
                add_action( 'wp_ajax_process_and_save_custom_html_form', array( $this, 'process_and_save_custom_html_form') );
                add_action( 'wp_ajax_publish_the_box', array( $this, 'publish_the_box') );
                add_action( 'wp_ajax_disable_box', array( $this, 'disable_box') );
                
                add_action( 'admin_init' , array( $this , 'export_boxes_to_xml' ) );
                
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

	
        /**************************************
	 * Register and enqueue admin-specific 
         * style sheet.
	 *************************************/
        public function enqueue_admin_styles() {

		if ( ! isset( $this->wpcb_main_screen_hook_suffix ) || ! isset( $this->wpcb_edit_screen_hook_suffix ) ) {
			return;
		}

		$wpcb_screen = get_current_screen();
		if ( $this->wpcb_main_screen_hook_suffix == $wpcb_screen->id || $this->wpcb_edit_screen_hook_suffix == $wpcb_screen->id || $this->wpcb_settings_screen_hook_suffix == $wpcb_screen->id ) {
			wp_enqueue_style( $this->wpcb_main_slug .'-admin-styles', ADMIN_ASSETS_URL.'css/admin.css', array() );
                        wp_enqueue_style( 'wp-color-picker');
                        wp_enqueue_style( $this->wpcb_main_slug .'-font-awesome', '//netdna.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css' );
                        wp_enqueue_style( $this->wpcb_main_slug .'-daterangepicker', ADMIN_ASSETS_URL.'css/daterangepicker.css', array() );
                        //wp_enqueue_style( $this->wpcb_main_slug .'-bootstrap', ADMIN_ASSETS_URL.'css/wpcb.bootstrap.min.css', array() );
                        wp_enqueue_style( $this->wpcb_main_slug .'-data-tables', ADMIN_ASSETS_URL.'css/jquery.dataTables.min.css', array() );
                }       

	}
        
	/***************************************
	 * Register and enqueue admin-specific 
         * JavaScript.
	 ***************************************/
        public function enqueue_admin_scripts() {

		if ( ! isset( $this->wpcb_main_screen_hook_suffix ) || ! isset( $this->wpcb_edit_screen_hook_suffix ) ) {
			return;
		}

		$wpcb_screen = get_current_screen();
		if ( $this->wpcb_main_screen_hook_suffix == $wpcb_screen->id || $this->wpcb_edit_screen_hook_suffix == $wpcb_screen->id || $this->wpcb_settings_screen_hook_suffix == $wpcb_screen->id ) {
                        wp_enqueue_script('wp-color-picker');
                        wp_enqueue_media();
                        
                        // Admin JS
                        wp_enqueue_script( $this->wpcb_main_slug . '-admin-script', ADMIN_ASSETS_URL.'js/admin.js', array( 'jquery' , 'wp-color-picker') );
                        $admin_data = array(
                            'activate' => __('Activate','wp-conversion-boxes-pro'),
                            'activatingLicense' => __('Activating License... Please Wait!','wp-conversion-boxes-pro'),
                            'activatedSuccessfully' =>  __('Activated Successfully!','wp-conversion-boxes-pro'),
                            'activatedSuccessfullyMsg' =>  __('License activated successfully. Please wait while we redirect you..','wp-conversion-boxes-pro'),
                            'deactivate' => __('Deactivate and Delete License','wp-conversion-boxes-pro'),
                            'deactivatingLicense' => __('Deactivating License... Please Wait!','wp-conversion-boxes-pro'),
                            'deactivatedSuccessfully' =>  __('Deactivated Successfully!','wp-conversion-boxes-pro'),
                            'deactivatedSuccessfullyMsg' =>  __('License deactivated successfully. Please wait while we redirect you..','wp-conversion-boxes-pro'),
                            'licenseResponseError' =>  __('Invalid reponse from server. Please try again.','wp-conversion-boxes-pro'),
                            'invalidLicense' => __('The license key you entered is invalid/expired. Please try again or contact support if problem persists.','wp-conversion-boxes-pro'),
                            'errorDeletingLicense' => __('There was an error deactivating and deleting the license key. Please try again later.','wp-conversion-boxes-pro'),
                            'choseImage' => __('Choose Image','wp-conversion-boxes-pro'),
                            'creatingBox' => __( 'Creating new box... Please wait!' , 'wp-conversion-boxes-pro' ),
                            'boxCreated' => __( 'Box Created Successfully. Redirecting...' , 'wp-conversion-boxes-pro' ),
                            'createBox' => __( 'Create Box and Proceed' , 'wp-conversion-boxes-pro' ),
                            'errorSavingToDB' => __( 'There was an error saving to database. Please try again later.' , 'wp-conversion-boxes-pro' ),
                            'errorUpdatingDB' => __( 'There was an error updating the database. Please try again later.' , 'wp-conversion-boxes-pro' ),
                            'updatingWait' => __( 'Updating... Please wait!' , 'wp-conversion-boxes-pro' ),
                            'savedRedirecting' => __( 'Saved! Redirecting...' , 'wp-conversion-boxes-pro' ),
                            'saveAndNext' => __( 'Save and Next' , 'wp-conversion-boxes-pro' ),
                            'settingsSaved' => __( 'Settings saved successfully.' , 'wp-conversion-boxes-pro' ),
                            'saveAndPublish' => __( 'Save and Publish!' , 'wp-conversion-boxes-pro' ),
                            'sureDeleteMain' => __( 'Are you sure you want to delete this conversion box? Deleting this conversion box will also delete its A/B test variant and their Conversion Statistics.' , 'wp-conversion-boxes-pro' ),
                            'sureDeleteVariant' => __( 'Are you sure you want to delete this conversion box? Deleting this conversion box will also delete its Conversion Statistics.' , 'wp-conversion-boxes-pro' ),
                            'errorDelete' => __( 'ERROR: Unable to delete the conversion box. Please try again later.' , 'wp-conversion-boxes-pro' ),
                            'flushStats' => __( 'Are you sure you want to flush all the stats for this conversion box?' , 'wp-conversion-boxes-pro' ),
                            'errorFlush' => __( 'ERROR: Unable to flush the stats. Please try again later.' , 'wp-conversion-boxes-pro' ),
                            'updatedSuccessfully' => __( 'Updated successfully.' , 'wp-conversion-boxes-pro' ),
                            'update' => __( 'Update' , 'wp-conversion-boxes-pro' ),
                            'boxPublished' => __( 'Box Published Successfully!' , 'wp-conversion-boxes-pro' ),
                            'later' => __( 'Later' , 'wp-conversion-boxes-pro' ),
                            'errorPublishing' => __( 'Error Publishing The Box!<br /><br />Reload this page and try again.' , 'wp-conversion-boxes-pro' ),
                            'done' => __( 'Done' , 'wp-conversion-boxes-pro' ),
                            'reload' => __( 'Reload' , 'wp-conversion-boxes-pro' ),
                            'errorProcessingCode' => __( 'ERROR: Unable to process the code. Please make sure you have entered proper form HTML code. Or, please contact support if problem persists.' , 'wp-conversion-boxes-pro' ),
                            'pleaseEnterDetails' => __( 'Please enter both Custom List Name and Custom List Form Code.' , 'wp-conversion-boxes-pro' ),
                            'deleteCustomList' => __( 'Are you sure you want to delete this custom list?' , 'wp-conversion-boxes-pro' ),
                            'errorDeletingCustom' => __( 'There was an error deleting the custom list. Please try again later.' , 'wp-conversion-boxes-pro' )
                        );
                        wp_localize_script( $this->wpcb_main_slug . '-admin-script', 'wpcbAdmin', $admin_data);
                        
                        // Real Time Box Customizer JS
                        wp_enqueue_script( $this->wpcb_main_slug . "-real-time-box-customizer-js",  ADMIN_ASSETS_URL.'js/realtimeboxcustomizer.js');
                        $rtbc_data = array(
                            'resetDataConfirmation' => __('Are you sure you want to reset the customizations to default? All design elements and content will be reset to defaults.', 'wp-conversion-boxes-pro'),
                            'resttingBtn' => __('Reseting... Please wait!','wp-conversion-boxes-pro'),
                            'resetError' => __('There was an error. Please try again later or contact support if problem persists.','wp-conversion-boxes-pro'),
                            'updatingBtn' => __('Updating... Please wait!','wp-conversion-boxes-pro'),
                            'updateSaved' => __('Saved! Redirecting...','wp-conversion-boxes-pro'),
                            'saveAndNext' => __('Save and Next','wp-conversion-boxes-pro'),
                            'updateError' => __('There was an error updating the database. Please try again later or contact support if problem persists.','wp-conversion-boxes-pro')
                        );
                        wp_localize_script( $this->wpcb_main_slug . "-real-time-box-customizer-js", 'wpcbRTBC', $rtbc_data);
                       
                        wp_enqueue_script( $this->wpcb_main_slug . "-flot-js",  ADMIN_ASSETS_URL.'js/jquery.flot.min.js');
                        wp_enqueue_script( $this->wpcb_main_slug . "-flot-selection-js",  ADMIN_ASSETS_URL.'js/jquery.flot.selection.min.js');
                        wp_enqueue_script( $this->wpcb_main_slug . "-flot-timer-js",  ADMIN_ASSETS_URL.'js/jquery.flot.time.min.js');
                        wp_enqueue_script( $this->wpcb_main_slug . "-moment-js",  ADMIN_ASSETS_URL.'js/moment.min.js');
                        //wp_enqueue_script( $this->wpcb_main_slug . "-bootstrap-js",  ADMIN_ASSETS_URL.'js/bootstrap.min.js');
                        wp_enqueue_script( $this->wpcb_main_slug . "-daterangepicker-js",  ADMIN_ASSETS_URL.'js/daterangepicker.min.js');
                        wp_enqueue_script( $this->wpcb_main_slug . "-data-tables",  ADMIN_ASSETS_URL.'js/jquery.dataTables.min.js');
                        wp_enqueue_script( $this->wpcb_main_slug . "-dd-slick",  ADMIN_ASSETS_URL.'js/jquery.ddslick.min.js');
                        wp_enqueue_script( $this->wpcb_main_slug . "-lightbox",  ADMIN_ASSETS_URL.'js/lightbox.js');
                        
                }

	}

        
	/**************************************
	 * Register admin menu and sub menus
	 **************************************/
	public function wpcb_add_admin_menu() {
            
                $required_cap = "manage_options";
                
                $required_cap = apply_filters('wpcb_admin_menu_cap',$required_cap);

		$this->wpcb_main_screen_hook_suffix = add_menu_page(
			__( 'WP Conversion Boxes Pro', 'wp-conversion-boxes-pro' ),
			__( 'WP Conversion Boxes Pro', 'wp-conversion-boxes-pro' ),
			$required_cap,
			$this->wpcb_main_slug,
			array( $this, 'wpcb_display_main_page' ),
                        ADMIN_ASSETS_URL.'imgs/icon.ico',
                        85
		);
                add_submenu_page(
                        $this->wpcb_main_slug, 
                        __( 'WP Conversion Boxes Pro', 'wp-conversion-boxes-pro' ), 
                        __( 'WP Conversion Boxes Pro', 'wp-conversion-boxes-pro' ),
                        $required_cap,
                        $this->wpcb_main_slug
                );
                $this->wpcb_edit_screen_hook_suffix = add_submenu_page(
                        $this->wpcb_main_slug, 
                        __( 'Add New Box', 'wp-conversion-boxes-pro' ), 
                        __( 'Add New Box', 'wp-conversion-boxes-pro' ),
                        $required_cap,
                        $this->wpcb_edit_slug,
                        array( $this, 'wpcb_display_edit_page' )
                );
                $this->wpcb_settings_screen_hook_suffix = add_submenu_page(
                        $this->wpcb_main_slug, 
                        __( 'Global Settings', 'wp-conversion-boxes-pro' ), 
                        __( 'Global Settings', 'wp-conversion-boxes-pro' ),
                        $required_cap,
                        $this->wpcb_settings_slug,
                        array( $this, 'wpcb_display_settings_page' )
                );

	}

        
	/**************************************
         * Admin pages
	 **************************************/
        
	public function wpcb_display_main_page() {
		include_once( 'views/main.php' );
	}
        public function wpcb_display_edit_page() {
		include_once( 'views/edit.php' );
	}
        public function wpcb_display_settings_page() {
		include_once( 'views/settings.php' );
	}
        

        /***************************************
	 * Sidebar metabox content
	 ***************************************/
        
        public function wpcb_sidebar() {
            include_once( 'views/sidebar.php' );
        }
        
        
	/***************************************
	 * Add action link to the plugins page.
	 ***************************************/
	
        public function add_action_links( $links ) {

		return array_merge(
			array(
				'settings' => '<a href="' . admin_url( 'admin.php?page=' . $this->wpcb_edit_slug ) . '">' . __( 'Add New Box', 'wp-conversion-boxes-pro' ) . '</a>'
			),
			$links
		);

	}
        
        /***************************************
	 * Edit page content
	 ***************************************/
        
        public function wpcb_edit_page_content($step, $id){
            
            if(!isset($step)){
                do_action('wpcb_before_creating_new_box');
                echo "<p>". __('Enter a name for your new Coversion Box:','wp-conversion-boxes-pro') . "</p>"
                             . "<input type='text' name='wpcb_box_name' id='wpcb_box_name' class='regular-text'><br /><br />"
                             . "<input type='submit' name='wpcb_create_box' id='wpcb_create_box' value='". __('Create Box and Proceed','wp-conversion-boxes-pro') . "' class='button button-primary'>";
            }
            else{
                global $wpdb;
                $wpcb_tbl_name = $this->wpcb_boxes_table;
                switch($step){
                   case 1: include_once( 'includes/select-box-template.php' );
                           break;
                   case 2: include_once( 'includes/customize-box.php' );
                           break;
                   case 3: include_once( 'includes/box-settings.php' );
                           break;  
                   default: include_once( 'includes/select-box-template.php' );
                           break;    
                }   
            }
            
        }
        
        /***************************************
         * Handle AJAX call to save the new box 
         * details to databse
         * 
         * Last Updated : v1.2.1
         ***************************************/      
        
        /*
         * Get available post types
         */
        
        public function get_available_post_types() {
            return get_post_types(array('public' => true));
        }
        
        // Create new box.
        
        public function create_new_box() {

                global $wpdb;
                $wpcb_box_name = strip_tags($_POST['wpcb_box_name']);
                $wpdb->insert($this->wpcb_boxes_table, array('box_name' => $wpcb_box_name), array('%s'));
                
                if($wpdb->insert_id)
                    echo $wpdb->insert_id;
                
                die();
        } 
        
        // Handle AJAX call to update box using ID  
        
        public function update_box_template() {
                global $wpdb;
                $wpcb_data = array(
                    'box_type' => $_POST['box_type'],
                    'box_template' => $_POST['box_template'],
                    'is_custom_template' => $_POST['is_custom_template']
                );                
                $wpcb_if_done = $wpdb->update($this->wpcb_boxes_table, $wpcb_data , array('id' => $_POST['box_id']), array('%d','%s','%d'), array('%d'));
                
                if($wpcb_if_done === FALSE)
                    echo 0;
                else
                    echo 1;
                
                die();
        } 
        
        //Update box design customizations
        
        public function update_box_customizations() {
            
                global $wpdb;

                $all_customizations = $_POST['all_customizations'];
                
                if(isset($all_customizations['custom_css'])){
                    $all_customizations['custom_css'] = strip_tags($all_customizations['custom_css']);
                }
                
                $box_customizations = serialize($all_customizations);

                $wpcb_if_done = $wpdb->update($this->wpcb_boxes_table, array('box_customizations' => $box_customizations), array('id' => $_POST['box_id']), array('%s'), array('%d'));
                
                if($wpcb_if_done === FALSE)
                    echo 0;
                else
                    echo 1;
                
                die();
                
        }
        
        //Restore customizations to default
        
        public function restore_to_default(){
                
                global $wpdb;
                
                $box_customizations = 'defaults';
                
                $wpcb_if_done = $wpdb->update($this->wpcb_boxes_table, array('box_customizations' => $box_customizations), array('id' => $_POST['box_id']), array('%s'), array('%d'));
                
                if($wpcb_if_done === FALSE)
                    echo 0;
                else
                    echo 1;
                
                die();
        }


        // Update box settings
        
        public function update_box_settings() {
                global $wpdb;
                
                $box_name = $_POST['box_name'];
                $box_settings = array(
                    'box_fade_in' => $_POST['box_fade_in'],
                    'box_fade_in_time' => $_POST['box_fade_in_time'],
                    'box_make_sticky' => $_POST['box_make_sticky'],
                    'box_slide_in' => $_POST['box_slide_in'],
                    'box_slide_in_from' => $_POST['box_slide_in_from'],
                    'box_slide_in_speed' => $_POST['box_slide_in_speed'],
                    'box_processing_head' => (isset($_POST['box_processing_head'])) ? $_POST['box_processing_head'] : "",
                    'box_taking_too_long' => (isset($_POST['box_taking_too_long'])) ? $_POST['box_taking_too_long'] : "",
                    'box_success_head' => (isset($_POST['box_success_head'])) ? $_POST['box_success_head'] : "",
                    'box_success_desc' => (isset($_POST['box_success_desc'])) ? $_POST['box_success_desc'] : "",
                    'box_error_head' => (isset($_POST['box_error_head'])) ? $_POST['box_error_head'] : "",
                    'box_error_desc' => (isset($_POST['box_error_desc'])) ? $_POST['box_error_desc'] : ""
                );
                
                $box_id = $_POST['box_id'];
                
                $box_settings = serialize($box_settings);

                $wpcb_if_done = $wpdb->update($this->wpcb_boxes_table, array('box_name' => $box_name, 'box_settings' => $box_settings), array('id' => $box_id), array('%s'), array('%d'));
                
                if($wpcb_if_done === FALSE)
                    echo 0;
                else
                    echo 1;
                
                die();
        }
        
        // Handles AJAX call to delete a particular box or test.
        
        public function delete_it(){
                global $wpdb;
                $wpcb_id = (isset($_POST['wpcb_id'])) ? $_POST['wpcb_id'] : 0;
                $box_data = $wpdb->get_row("SELECT test_enabled,test_with FROM $this->wpcb_boxes_table WHERE id = $wpcb_id", "ARRAY_A");
                if($box_data['test_enabled'] == 1){
                    // Delete both original and variant boxes and stats
                    if($box_data['test_with'] != 0){
                        $wpdb->delete( $this->wpcb_boxes_table, array( 'id' => $wpcb_id ) );
                        $wpdb->delete( $this->wpcb_boxes_table, array( 'id' => $box_data['test_with'] ) );
                        $wpdb->delete( $this->wpcb_tracking_table, array( 'box_id' => $wpcb_id ) );
                        $wpdb->delete( $this->wpcb_tracking_table, array( 'box_id' => $box_data['test_with'] ) );
                    }
                    // Delete variant and its stats and update original to normal
                    else{
                        $wpdb->delete( $this->wpcb_boxes_table, array( 'id' => $wpcb_id ) );
                        $wpdb->update($this->wpcb_boxes_table, array('test_enabled' => 0, 'test_with' => 0), array('test_with' => $wpcb_id), array('%d','%d'), array('%d'));
                        $wpdb->delete( $this->wpcb_tracking_table, array( 'box_id' => $wpcb_id ) );
                    }
                }
                // Delete original box and its stats
                else{
                    $wpdb->delete( $this->wpcb_boxes_table, array( 'id' => $wpcb_id ) );
                    $wpdb->delete( $this->wpcb_tracking_table, array( 'box_id' => $wpcb_id ) );
                }
                
                if(!$wpdb->error){
                    echo $wpcb_id;
                }
                else{ 
                    echo 0;
                }

                die();
        }

        /***************************************
         * Update Global Settings
         ***************************************/
        
        public function update_global_settings(){
            $wpcb_ga_tracking = $_POST['wpcb_ga_tracking'];
            $wpcb_boxes_list_default = $_POST['wpcb_boxes_list_default'];
            $wpcb_boxes_list_posts = $_POST['wpcb_boxes_list_posts'];
            $wpcb_boxes_list_pages = $_POST['wpcb_boxes_list_pages'];
            $wpcb_all_cats_and_box_ids = $_POST['wpcb_all_cats_and_box_ids'];
            $wpcb_all_cats_and_box_ids = explode(',', trim($wpcb_all_cats_and_box_ids,','));
            $enable_credit_link = $_POST['enable_credit_link'];
            
            foreach($wpcb_all_cats_and_box_ids as $wpcb_all_cats_and_box_id){
                $wpcb_all_cats_and_box_id = explode('-', $wpcb_all_cats_and_box_id);
                $option_name = "wpcb_box_for_cat_".$wpcb_all_cats_and_box_id[0]; // Category id name
                $option_value = $wpcb_all_cats_and_box_id[1]; // Box ID
                update_option($option_name,$option_value);
            }

            update_option('wpcb_ga_tracking', $wpcb_ga_tracking);
            update_option('wpcb_default_box', $wpcb_boxes_list_default);
            update_option('wpcb_all_posts', $wpcb_boxes_list_posts);
            update_option('wpcb_all_pages', $wpcb_boxes_list_pages);
            update_option('wpcb_enable_credit_link', $enable_credit_link);
                    
            echo 1;
            die();
        }
        
        /***************************************
         * Publish Box
         ***************************************/
        
        public function publish_the_box(){
            
            $global_placement = $_POST['global_placement'];
            $wpcb_all_cats_and_box_ids = $_POST['wpcb_all_cats_and_box_ids'];
            $wpcb_all_cats_and_box_ids = explode(',', trim($wpcb_all_cats_and_box_ids,','));
            $wpcb_post_ids = (isset($_POST['wpcb_post_ids'])) ? $_POST['wpcb_post_ids'] : '';
            $old_selected_ids = (isset($_POST['old_selected_ids'])) ? $_POST['old_selected_ids'] : '';
            $box_id = (isset($_POST['box_id'])) ? $_POST['box_id'] : '';
            
            
            $wpcb_default_box = get_option('wpcb_default_box');
            $wpcb_all_posts = get_option('wpcb_all_posts');
            $wpcb_all_pages = get_option('wpcb_all_pages');
            
            switch($global_placement){
                case 1: update_option('wpcb_default_box', $box_id);
                        if($wpcb_all_posts == $box_id){
                            update_option('wpcb_all_posts', 0);
                        }
                        if($wpcb_all_pages == $box_id){
                            update_option('wpcb_all_pages', 0);
                        }
                        break;
                case 2: if($wpcb_default_box == $box_id){
                            update_option('wpcb_default_box', 0);
                        }
                        update_option('wpcb_all_posts', $box_id);
                        if($wpcb_all_pages == $box_id){
                            update_option('wpcb_all_pages', 0);
                        }
                        break;                    
                case 3: if($wpcb_default_box == $box_id){
                            update_option('wpcb_default_box', 0);
                        }
                        if($wpcb_all_posts == $box_id){
                            update_option('wpcb_all_posts', 0);
                        }
                        update_option('wpcb_all_pages', $box_id);
                        break;                    
                default:if($wpcb_default_box == $box_id){
                            update_option('wpcb_default_box', 0);
                        }
                        if($wpcb_all_posts == $box_id){
                            update_option('wpcb_all_posts', 0);
                        }
                        if($wpcb_all_pages == $box_id){
                            update_option('wpcb_all_pages', 0);
                        }
                        break;
            }
            
            //Update Post IDs
            if($old_selected_ids != ''){
                $old_ids = explode(',', trim($old_selected_ids,','));
                foreach($old_ids as $old_id){
                    update_post_meta($old_id, 'wpcb_meta_selected_box_id', 0);
                }
            }
            if($wpcb_post_ids != '' && $box_id != ''){
                $wpcb_all_post_ids = explode(',', trim($wpcb_post_ids,','));
                foreach($wpcb_all_post_ids as $wpcb_post_id){
                    //get_post_meta( $wpcb_post_id, 'wpcb_meta_selected_box_id', true );
                    update_post_meta($wpcb_post_id, 'wpcb_meta_selected_box_id', $box_id);
                }
            }
            
            
            // Update Cats
            $all_cat_ids = get_terms('category',array('fields' => 'ids','hide_empty' => false));
            foreach($all_cat_ids as $cat_id){
                if($box_id == get_option('wpcb_box_for_cat_'.$cat_id)){
                    update_option('wpcb_box_for_cat_'.$cat_id , 0 );
                }
            }
            if($wpcb_all_cats_and_box_ids != ''){
                foreach($wpcb_all_cats_and_box_ids as $wpcb_all_cats_and_box_id){
                    $wpcb_all_cats_and_box_id = explode('-', $wpcb_all_cats_and_box_id);
                    $option_name = "wpcb_box_for_cat_".$wpcb_all_cats_and_box_id[0]; // Category id name
                    $option_value = $wpcb_all_cats_and_box_id[1]; // Box ID
                    if($option_value != '')
                        update_option($option_name,$option_value);
                }
            }
            
            echo 1;
            die();
        }
        
        /***************************************
         * Show list of all the boxes on main
         * plugin page with menu and stats
         ***************************************/
        
        public function wpcb_show_boxes_list() {
            include_once('includes/show-boxes-list.php');
        }
        
        /***************************************
         * Duplicate a box
         ***************************************/
        
        public function wpcb_duplicate_box($box_id){
            global $wpdb;
            $wpcb_tbl_name = $this->wpcb_boxes_table;
            $box_data = $wpdb->get_row("SELECT * FROM $wpcb_tbl_name WHERE id = $box_id", "ARRAY_A");
            
            if($box_data != ''){
                $wpdb->insert($this->wpcb_boxes_table, 
                    array(
                        'box_name' => $box_data['box_name']." (". __( 'Duplicate' , 'wp-conversion-boxes-pro' ) . ")",
                        'box_type' => $box_data['box_type'], 
                        'box_template' => $box_data['box_template'],
                        'box_customizations' => $box_data['box_customizations'],
                        'box_settings' => $box_data['box_settings']
                    ), 
                    array(
                        '%s',
                        '%d',
                        '%s',
                        '%s',
                        '%s'
                    )
                );
            }
            if($wpdb->insert_id)
                return true;
            else
                return false;
            
        }
        
        /***************************************
         * Create A/B test from a box id
         ***************************************/
        
        public function wpcb_create_ab_test($box_id){
            global $wpdb;
            $wpcb_tbl_name = $this->wpcb_boxes_table;
            $box_data = $wpdb->get_row("SELECT * FROM $wpcb_tbl_name WHERE id = $box_id", "ARRAY_A");
            
            if($box_data != '' && $box_data['test_with'] == 0){
                $wpdb->insert($this->wpcb_boxes_table, 
                    array(
                        'box_name' => $box_data['box_name']." (". __('Variant B','wp-conversion-boxes-pro') . ")", 
                        'box_type' => $box_data['box_type'], 
                        'box_template' => $box_data['box_template'],
                        'box_customizations' => $box_data['box_customizations'],
                        'box_settings' => $box_data['box_settings'],
                        'test_enabled' => 1
                    ), 
                    array('%s','%d','%s','%s','%s','%d','%d')
                );
            }
            if($wpdb->insert_id){
                $wpdb->update($this->wpcb_boxes_table, array('test_enabled' => 1, 'test_with' => $wpdb->insert_id), array('id' => $box_id), array('%d','%d'), array('%d'));
                return true;
            }
            else{
                return false;
            }
        }
        
        /***************************************
         * Show dropdown list of all boxes in DB
         ***************************************/
        
        public function wpcb_box_list($selected_box,$list_type,$list_id,$list_name = false){
            global $wpdb;
            $wpcb_tbl_name = $this->wpcb_boxes_table;
            $boxes_list = $wpdb->get_results("SELECT id,box_name,test_enabled,test_with FROM $wpcb_tbl_name ORDER BY id ASC","ARRAY_A");
            
            if($list_type == 'default') $first_option = __('None','wp-conversion-boxes-pro');
            else $first_option = __('Use Default','wp-conversion-boxes-pro');
            
            if($list_name == false){
                echo "<select name='".$list_id."' id='".$list_id."'>";
            }
            else {
                echo "<select name='".$list_name."' id='".$list_id."'>";
            }
            
            echo "<option value='0'>".$first_option."</option>";
            
            if($list_type == 'both'){
                if($selected_box == '-1') $is_selected = 'selected';
                else $is_selected = "";
                
                echo "<option value='-1' ".$is_selected.">Don't Show Box</option>";
            }
            
            foreach($boxes_list as $box){
                if($selected_box == $box['id']){
                    $is_selected = "selected";
                }
                else{
                    $is_selected = "";
                }
                if($box['test_enabled'] == 1 && $box['test_with'] == 0){
                    
                }
                else{
                    echo "<option value='".$box['id']."' ".$is_selected.">".stripcslashes($box['box_name'])."</option>";
                }
            }
            echo "</select>";
        }
        
        /***************************************
         * Show list of all categories and 
         * respective boxes from DB
         * 
         * Used in Global Settings page
         ***************************************/
        
        public function wpcb_category_wise_box_list(){
            $categories = get_terms('category','hide_empty=0');
            echo "<table class='widefat'><thead><tr><th>". __('Category Name','wp-conversion-boxes-pro') ."</th><th>". __('Conversion Box','wp-conversion-boxes-pro') ."</th></thead><tbody>";
            foreach ($categories as $cat){
                $list_id = "wpcb_boxes_list_cat_".$cat->term_id;
                $selected = get_option('wpcb_box_for_cat_'.$cat->term_id);
                echo "<tr>";
                echo "<th>".$cat->name."</th>";
                echo "<td>";
                $this->wpcb_box_list($selected , '', $list_id);
                echo "</td>";
                echo "</tr>";
            }
            echo "</tbody></table>";
        }
        
        /****************************************
         * Checklist of categories
         ****************************************/
        
        public function checklist_of_categories($box_id) {
            $selected_cats = array();
            $all_cat_ids = get_terms('category',array('fields' => 'ids','hide_empty' => false));
            foreach($all_cat_ids as $cat_id){
                if($box_id == get_option('wpcb_box_for_cat_'.$cat_id)){
                    array_push($selected_cats, $cat_id);
                }
            }
            
            $categories = get_categories();
            if ( $categories ){
                echo "<div class='wpcb_cat_checklist'>";
                wp_category_checklist( 0, 0, $selected_cats, false, null, false );
                echo "</div>";
            }
        }
        
        /***************************************
         * Show list of all templates
         ***************************************/
        
        public function wpcb_template_list($selected_template,$template_type, $is_custom_template){
                switch($template_type){
                    case 1 :    $wpcb_template_main_dir = WPCB_TEMPLATE_DIR_PATH.$this->template_directory_1.'/';
                                $wpcb_template_dir_url = WPCB_TEMPLATE_DIR_URL.$this->template_directory_1.'/';
                                $wpcb_custom_template_main_dir = WPCB_CUSTOM_TEMPLATE_DIR_PATH.$this->template_directory_1.'/';
                                $wpcb_custom_template_dir_url = WPCB_CUSTOM_TEMPLATE_DIR_URL.$this->template_directory_1.'/';
                                                break;
                    case 2 :    $wpcb_template_main_dir = WPCB_TEMPLATE_DIR_PATH.$this->template_directory_2.'/';
                                $wpcb_template_dir_url = WPCB_TEMPLATE_DIR_URL.$this->template_directory_2.'/';
                                $wpcb_custom_template_main_dir = WPCB_CUSTOM_TEMPLATE_DIR_PATH.$this->template_directory_2.'/';
                                $wpcb_custom_template_dir_url = WPCB_CUSTOM_TEMPLATE_DIR_URL.$this->template_directory_2.'/';
                                                break;
                    case 3 :    $wpcb_template_main_dir = WPCB_TEMPLATE_DIR_PATH.$this->template_directory_3.'/';
                                $wpcb_template_dir_url = WPCB_TEMPLATE_DIR_URL.$this->template_directory_3.'/';
                                $wpcb_custom_template_main_dir = WPCB_CUSTOM_TEMPLATE_DIR_PATH.$this->template_directory_3.'/';
                                $wpcb_custom_template_dir_url = WPCB_CUSTOM_TEMPLATE_DIR_URL.$this->template_directory_3.'/';
                                                break;
                    case 4 :    $wpcb_template_main_dir = WPCB_TEMPLATE_DIR_PATH.$this->template_directory_4.'/';
                                $wpcb_template_dir_url = WPCB_TEMPLATE_DIR_URL.$this->template_directory_4.'/';
                                $wpcb_custom_template_main_dir = WPCB_CUSTOM_TEMPLATE_DIR_PATH.$this->template_directory_4.'/';
                                $wpcb_custom_template_dir_url = WPCB_CUSTOM_TEMPLATE_DIR_URL.$this->template_directory_4.'/';
                                                break;                                     
                }
                
                $wpcb_templates = scandir($wpcb_template_main_dir);
                $wpcb_custom_templates = scandir($wpcb_custom_template_main_dir);
                
                echo "<div class='wpcb_template_selector'><select class='wpcb_template_dropdown' name='wpcb_template_dropdown_".$template_type."' id='wpcb_template_dropdown_".$template_type."'><option value='0'>". __('Select template','wp-conversion-boxes-pro') ."</option>";
                
                foreach ($wpcb_templates as $wpcb_template_dir) {
                    
                    if($wpcb_template_dir === '.' || $wpcb_template_dir === '..') continue;
                    
                    if (is_dir($wpcb_template_main_dir . $wpcb_template_dir)) {
                        
                        if($selected_template == $wpcb_template_dir && $is_custom_template == 0){
                            $is_selected = "selected";
                        }
                        
                        else{
                            $is_selected = "";
                        }
                        
                        $wpcb_template_screenshot_url = $wpcb_template_dir_url.$wpcb_template_dir.'/screenshot.png';
                        
                        echo "<option data-iscustom='0' data-screenshot='".$wpcb_template_screenshot_url."' value='".$wpcb_template_dir."' ".$is_selected.">".$wpcb_template_dir."</option>";
                        
                    }
                    
                }
                
                if($wpcb_custom_templates){
                    echo "<option disabled>─────────────────────────</option>";
                    foreach ($wpcb_custom_templates as $wpcb_custom_template_dir) {

                        if($wpcb_custom_template_dir === '.' || $wpcb_custom_template_dir === '..') continue;

                        if (is_dir($wpcb_custom_template_main_dir . $wpcb_custom_template_dir)) {

                            if($selected_template == $wpcb_custom_template_dir && $is_custom_template == 1){
                                $is_selected = "selected";
                            }

                            else{
                                $is_selected = "";
                            }

                            $wpcb_custom_template_screenshot_url = $wpcb_custom_template_dir_url.$wpcb_custom_template_dir.'/screenshot.png';

                            echo "<option data-iscustom='1' data-screenshot='".$wpcb_custom_template_screenshot_url."' value='".$wpcb_custom_template_dir."' ".$is_selected.">".$wpcb_custom_template_dir."</option>";

                        }

                    }    
                }
                
                echo "</select></div>";
        }
        
        /***************************************
         * Get name of the box
         */
        
        public function get_box_name($box_id){
            global $wpdb;
            $wpcb_tbl_name = $this->wpcb_boxes_table;
            $wpcb_the_row = $wpdb->get_row($wpdb->prepare("SELECT box_name from $wpcb_tbl_name WHERE id = %d",array($box_id)));
            return stripslashes($wpcb_the_row->box_name);
        }

        /***************************************
         * Include the selected template - If 
         * you make changes to this, also do so
         * with the same in WPCB_Public class.
         ***************************************/        
        
        public function include_the_template_and_settings($box_type, $box_template, $box_customizations, $box_id, $is_custom_template){
            echo "<div class='wpcb_stick_this_offset'></div><div class='postbox wpcb_stick_this'><h3>". __('Box Preview','wp-conversion-boxes-pro') ."<span style='float: right;'><label><input type='checkbox' class='wpcb_box_preview_stick' name='wpcb_box_preview_stick' />". __('Stick preview to top','wp-conversion-boxes-pro') ."</label></span></h3><div class='inside minheight150'>";
            
            $wpcb_default_fields = $box_customizations;
            
            if($is_custom_template == 0){
                switch($box_type){
                        case 1 :    include_once(WPCB_TEMPLATE_DIR_PATH.$this->template_directory_1.'/'.$box_template.'/template.php');
                                    break;
                        case 2 :    include_once(WPCB_TEMPLATE_DIR_PATH.$this->template_directory_2.'/'.$box_template.'/template.php');
                                    break;
                        case 3 :    include_once(WPCB_TEMPLATE_DIR_PATH.$this->template_directory_3.'/'.$box_template.'/template.php');
                                    break;
                        case 4 :    include_once(WPCB_TEMPLATE_DIR_PATH.$this->template_directory_4.'/'.$box_template.'/template.php');
                                    break;                                     
                }
            }else{
                switch($box_type){
                        case 1 :    include_once(WPCB_CUSTOM_TEMPLATE_DIR_PATH.$this->template_directory_1.'/'.$box_template.'/template.php');
                                    break;
                        case 2 :    include_once(WPCB_CUSTOM_TEMPLATE_DIR_PATH.$this->template_directory_2.'/'.$box_template.'/template.php');
                                    break;
                        case 3 :    include_once(WPCB_CUSTOM_TEMPLATE_DIR_PATH.$this->template_directory_3.'/'.$box_template.'/template.php');
                                    break;
                        case 4 :    include_once(WPCB_CUSTOM_TEMPLATE_DIR_PATH.$this->template_directory_4.'/'.$box_template.'/template.php');
                                    break;                                     
                }
            }
            
            echo "</div></div>";
            echo "<div class='postbox'><h3>". __('Box Customizations','wp-conversion-boxes-pro') ."</h3><div class='inside minheight150'>";
            include_once('includes/default-customizations-fields.php');
            echo "</div></div>";
        }
        
        /***************************************
         * Get the box container setting fields
         ***************************************/        
        
        public function font_families_dropdown_list($selected_font_family, $font_familiy_area){
            
            // If you edit this, also edit the @import in admin.css
            $font_families = array(
                'Arial','Georgia','Serif','Palatino Linotype','Times New Roman',
                'Comic Sans MS','Impact','Tahoma','Verdana','Courier New',
                'Lucida Grande','Poiret One','Josefin Sans','Lobster','Anton',
                'Shadows Into Light','Gloria Hallelujah','Roboto','Oswald',
                'Raleway','Montserrat','Oxygen','Francois One','Titillium Web',
                'Indie Flower','Fjalla One','Inconsolata','Pacifico','Audiowide',
                'Dancing Script','Coming Soon'
            );
            echo "<select name='font_families_".$font_familiy_area."' id='font_families_".$font_familiy_area."'>";
            foreach($font_families as $font_family){
                
                if($selected_font_family == $font_family){
                    $selected = 'selected';
                }
                else{
                    $selected = '';
                }
                
                echo '<option value="'.$font_family.'" '.$selected.'>'.$font_family.'</option>';
            }
            echo "</select>";
        }
        
        /**************************************
         * Get dropdown list of email service 
         * providers
         **************************************/
        
        public function get_mailer_campaigns_list($mailer_id, $selected_campaign) {
            
            $getresponse = get_option('wpcb_getresponse_campaigns');
            $mailchimp = get_option('wpcb_mailchimp_lists');
            $aweber = get_option('wpcb_aweber_lists');
            $madmimi = get_option('wpcb_madmimi_lists');
            $constant_contact = get_option('wpcb_constant_contact_lists');
            $campaign_monitor = get_option('wpcb_campaign_monitor_lists');
            $infusionsoft = get_option('wpcb_infusionsoft_lists');
            $icontact = get_option('wpcb_icontact_lists');
            $pardot = get_option('wpcb_pardot_lists');
            
            $custom = get_option('wpcb_custom_lists');
            
            $getresponse_campaigns = unserialize($getresponse);
            $mailchimp_lists = unserialize($mailchimp);
            $aweber_lists = unserialize($aweber);
            $madmimi_lists = unserialize($madmimi);
            $constant_contact_lists = unserialize($constant_contact);
            $campaign_monitor_lists = unserialize($campaign_monitor);
            $infusionsoft_lists = unserialize($infusionsoft);
            $icontact_lists = unserialize($icontact);
            if (class_exists('WYSIJA')){
                $modelList = WYSIJA::get('list', 'model');
                $mail_poet_lists = $modelList->get(array('name', 'list_id'), array('is_enabled' => 1));
            }
            $pardot_lists = unserialize($pardot);
            $custom_lists = unserialize($custom);
            
            if($getresponse_campaigns != '' or $mailchimp_lists != '' or $aweber_lists != '' or $madmimi_lists != '' or $constant_contact_lists != '' or $campaign_monitor_lists != '' or $infusionsoft_lists != '' or $icontact_lists != '' or $mail_poet_lists != '' or $pardot_lists != '' or $custom_lists != ''){
                
                //GetResponse
                
                echo '<select id="input_campaign_name" name="input_campaign_name">';
                
                if($getresponse_campaigns != ''){
                    foreach($getresponse_campaigns as $gr_id => $gr_name){
                        if($gr_id == $selected_campaign && $mailer_id == 1){
                            echo '<option data-description="GetResponse" data-imagesrc="'.ADMIN_ASSETS_URL.'imgs/gr-sm.png" value="'.$gr_id.'" selected>'.$gr_name.'</option>';
                        }
                        else{
                            echo '<option data-description="GetResponse" data-imagesrc="'.ADMIN_ASSETS_URL.'imgs/gr-sm.png" value="'.$gr_id.'" >'.$gr_name.'</option>';
                        }
                    }                    
                }
                
                //MailChimp
                
                if($mailchimp_lists != ''){
                    foreach($mailchimp_lists as $mc_id => $mc_name){
                        if($mc_id == $selected_campaign && $mailer_id == 2){
                            echo '<option data-description="MailChimp" data-imagesrc="'.ADMIN_ASSETS_URL.'imgs/mc-sm.png" value="'.$mc_id.'" selected>'.$mc_name.'</option>';
                        }
                        else{
                            echo '<option data-description="MailChimp" data-imagesrc="'.ADMIN_ASSETS_URL.'imgs/mc-sm.png" value="'.$mc_id.'" >'.$mc_name.'</option>';
                        }
                    }
                }

                //Aweber
                
                if($aweber_lists != ''){
                    foreach($aweber_lists as $aweber_id => $aweber_name){
                        if($aweber_id == $selected_campaign && $mailer_id == 3){
                            echo '<option data-description="Aweber" data-imagesrc="'.ADMIN_ASSETS_URL.'imgs/aweber-sm.png" value="'.$aweber_id.'" selected>'.$aweber_name.'</option>';
                        }
                        else{
                            echo '<option data-description="Aweber" data-imagesrc="'.ADMIN_ASSETS_URL.'imgs/aweber-sm.png" value="'.$aweber_id.'" >'.$aweber_name.'</option>';
                        }
                    }                    
                }
                
                //MadMimi
                
                if($madmimi_lists != ''){
                    foreach($madmimi_lists as $madmimi_id => $madmimi_name){
                        if($madmimi_id == $selected_campaign && $mailer_id == 4){
                            echo '<option data-description="Mad Mimi" data-imagesrc="'.ADMIN_ASSETS_URL.'imgs/madmimi-sm.png" value="'.$madmimi_id.'" selected>'.$madmimi_name.'</option>';
                        }
                        else{
                            echo '<option data-description="Mad Mimi" data-imagesrc="'.ADMIN_ASSETS_URL.'imgs/madmimi-sm.png" value="'.$madmimi_id.'" >'.$madmimi_name.'</option>';
                        }
                    }                    
                }
                
                // Constant Contact
                
                if($constant_contact_lists != ''){
                    foreach($constant_contact_lists as $constant_contact_id => $constant_contact_name){
                        if($constant_contact_id == $selected_campaign && $mailer_id == 5){
                            echo '<option data-description="Constant Contact" data-imagesrc="'.ADMIN_ASSETS_URL.'imgs/constant-contact-sm.png" value="'.$constant_contact_id.'" selected>'.$constant_contact_name.'</option>';
                        }
                        else{
                            echo '<option data-description="Constant Contact" data-imagesrc="'.ADMIN_ASSETS_URL.'imgs/constant-contact-sm.png" value="'.$constant_contact_id.'" >'.$constant_contact_name.'</option>';
                        }
                    }                    
                }
                
                // Campaign Monitor
                
                if($campaign_monitor_lists != ''){
                    foreach($campaign_monitor_lists as $campaign_monitor_id => $campaign_monitor_name){
                        if($campaign_monitor_id == $selected_campaign && $mailer_id == 6){
                            echo '<option data-description="Campaign Monitor" data-imagesrc="'.ADMIN_ASSETS_URL.'imgs/campaign-monitor-sm.png" value="'.$campaign_monitor_id.'" selected>'.$campaign_monitor_name.'</option>';
                        }
                        else{
                            echo '<option data-description="Campaign Monitor" data-imagesrc="'.ADMIN_ASSETS_URL.'imgs/campaign-monitor-sm.png" value="'.$campaign_monitor_id.'" >'.$campaign_monitor_name.'</option>';
                        }
                    }                    
                }
                
                //Infusionsoft
                
                if($infusionsoft_lists != ''){
                    foreach($infusionsoft_lists as $infusionsoft_id => $infusionsoft_name){
                        if($infusionsoft_id == $selected_campaign && $mailer_id == 7){
                            echo '<option data-description="Infusionsoft" data-imagesrc="'.ADMIN_ASSETS_URL.'imgs/infusionsoft-sm.png" value="'.$infusionsoft_id.'" selected>'.$infusionsoft_name.'</option>';
                        }
                        else{
                            echo '<option data-description="Infusionsoft" data-imagesrc="'.ADMIN_ASSETS_URL.'imgs/infusionsoft-sm.png" value="'.$infusionsoft_id.'" >'.$infusionsoft_name.'</option>';
                        }
                    }                    
                }
                
                //iContact
                
                if($icontact_lists != ''){
                    foreach($icontact_lists as $icontact_id => $icontact_name){
                        if($icontact_id == $selected_campaign && $mailer_id == 8){
                            echo '<option data-description="iContact" data-imagesrc="'.ADMIN_ASSETS_URL.'imgs/icontact-sm.png" value="'.$icontact_id.'" selected>'.$icontact_name.'</option>';
                        }
                        else{
                            echo '<option data-description="iContact" data-imagesrc="'.ADMIN_ASSETS_URL.'imgs/icontact-sm.png" value="'.$icontact_id.'" >'.$icontact_name.'</option>';
                        }
                    }                    
                }
                
                //MailPoet
                
                if($mail_poet_lists != ''){
                    foreach($mail_poet_lists as $mail_poet_list){
                        if($mail_poet_list['list_id'] == $selected_campaign && $mailer_id == 9){
                            echo '<option data-description="MailPoet" data-imagesrc="'.ADMIN_ASSETS_URL.'imgs/mailpoet-sm.png" value="'.$mail_poet_list['list_id'].'" selected>'.$mail_poet_list['name'].'</option>';
                        }
                        else{
                            echo '<option data-description="MailPoet" data-imagesrc="'.ADMIN_ASSETS_URL.'imgs/mailpoet-sm.png" value="'.$mail_poet_list['list_id'].'" >'.$mail_poet_list['name'].'</option>';
                        }
                    }                    
                }
                
                // Pardot
                
                if($pardot_lists != ''){
                    foreach($pardot_lists as $pardot_id => $pardot_name){
                        if($pardot_id == $selected_campaign && $mailer_id == 10){
                            echo '<option data-description="Pardot" data-imagesrc="'.ADMIN_ASSETS_URL.'imgs/pardot-sm.png" value="'.$pardot_id.'" selected>'.$pardot_name.'</option>';
                        }
                        else{
                            echo '<option data-description="Pardot" data-imagesrc="'.ADMIN_ASSETS_URL.'imgs/pardot-sm.png" value="'.$pardot_id.'" >'.$pardot_name.'</option>';
                        }
                    }                    
                }
                
                // Custom Lists
                
                if($custom_lists != ''){
                    foreach($custom_lists as $custom_lists_id => $custom_list){
                        if($custom_lists_id == $selected_campaign && $mailer_id == 99){
                            echo '<option data-description="Custom List" data-imagesrc="'.ADMIN_ASSETS_URL.'imgs/custom-sm.png" value="'.$custom_lists_id.'" selected>'.$custom_list['custom_list_name'].'</option>';
                        }
                        else{
                            echo '<option data-description="Custom List" data-imagesrc="'.ADMIN_ASSETS_URL.'imgs/custom-sm.png" value="'.$custom_lists_id.'" >'.$custom_list['custom_list_name'].'</option>';
                        }
                    }                    
                }
                
                
                echo "</select>";
            }
            else{
                echo "<p>". __('No campaigns/lists found. Please integrate your email service provider first to select a campaign/list for this conversion box.','wp-conversion-boxes-pro') ." <a href='".admin_url( 'admin.php?page=' . $this->wpcb_settings_slug )."&step=2'>". __('Click here to integrate now.' , 'wp-conversion-boxes-pro' ) ."</a></p>";
            }
        }
        
        /***************************************
         * Process and save custom html form
         ***************************************/
        
        public function process_and_save_custom_html_form(){
            
                $custom_list_form_html = $_POST['custom_list_form_html'];
                $custom_list_name = $_POST['custom_list_name'];
                include_once( MAILERS_DIR_PATH . 'custom/phpQuery/phpQuery.php');

                try {
                    $HTML = phpQuery::newDocumentHTML($custom_list_form_html);
                    $labels = $HTML->find('label');
                    $buttons = $HTML->find('button');
                    $disallowed_atts = array('style', 'class');

                    foreach ((object) $buttons as $i => $button) {
                        $atts = '';
                        for ($m = $button->attributes->length - 1; $m >= 0; --$m) {
                            if (in_array((string) $button->attributes->item($m)->nodeName, $disallowed_atts))
                                pq($button)->removeAttr($button->attributes->item($m)->nodeName);
                        }
                    }

                    // Prepare rest of variables.
                    $inputs = $HTML->find('form')->find(':input')->not(':input[type=reset]');
                    $input_html = '';
                    $input_num = count($inputs);

                    // Build out all input elements into the form.
                    foreach ((object) $inputs as $n => $input) {
                        // Prep variables for checking type of input.
                        $single_input = '';
                        $email_input = false;
                        $name_input = false;
                        $type_hidden = false;
                        $input_name = '';

                        // Avoid looping over body tag.
                        if ('body' == (string) $input->nodeName)
                            continue;

                        for ($m = $input->attributes->length - 1; $m >= 0; --$m) {
                            if (in_array((string) $input->attributes->item($m)->nodeName, $disallowed_atts) || 'data-om-type' == (string) $input->attributes->item($m)->nodeName)
                                continue;
                            if ('type' == (string) $input->attributes->item($m)->nodeName && '\"hidden\"' == (string) $input->attributes->item($m)->nodeValue) {
                                $email_input = $name_input = false;
                                $type_hidden = true;
                            }
                            
                            if ('type' == (string) $input->attributes->item($m)->nodeName && '\"email\"' == (string) $input->attributes->item($m)->nodeValue) {
                                $email_input = true;
                            }
                            
                            if ('name' == (string) $input->attributes->item($m)->nodeName)
                                $input_name = strtolower((string) $input->attributes->item($m)->nodeValue);
                            if ('value' == (string) $input->attributes->item($m)->nodeName)
                                $input_value = strtolower((string) $input->attributes->item($m)->nodeValue);
                            
                        }
                        
                        // Automatically add proper data types based on possible type of input.
                        $this_input_name = trim($input_name,'\"');
                        $this_input_value = trim($input_value,'\"');
                        if($this_input_name != 'submit' && $this_input_value != 'submit'){
                            if(preg_match('#(name)#i',$this_input_name)){
                                $wpcb_name_name = $this_input_name;
                            }
                            elseif(preg_match('#(email)#i',$this_input_name) || $email_input == true){
                                $wpcb_email_name = $this_input_name;
                            }
                            else{
                                $wpcb_other_fields[$this_input_name] = $this_input_value;
                            }
                        }
                    }

                    // Now grab form element.
                    $forms = $HTML->find('form')->empty();
                    

                    // Build out the main <form> element with all attributes.
                    foreach ((object) $forms as $i => $form) {
                        // Avoid looping over body tag.
                        if ('body' == (string) $form->nodeName)
                            continue;

                        for ($k = $form->attributes->length - 1; $k >= 0; --$k) {
                            if (in_array((string) $form->attributes->item($k)->nodeName, $disallowed_atts) || 'id' == (string) $form->attributes->item($k)->nodeName || 'target' == (string) $form->attributes->item($k)->nodeName)
                                continue;

                            if((string) $form->attributes->item($k)->nodeName == 'action'){
                                $action_url = (string) $form->attributes->item($k)->nodeValue;
                                $action_url = trim($action_url,'\"');
                            }
                        }
                    }

                    
                } catch (Exception $e) {
                    echo json_encode(array('error' => $e->getMessage()));
                    die;
                }
                
                if($wpcb_email_name != ''){
                    $custom_html_list_data = array(
                        'custom_list_name' => $custom_list_name,
                        'action_url' => $action_url,
                        'emails_name_tag' => $wpcb_email_name,
                        'names_name_tag' => $wpcb_name_name,
                        'other_fields' => $wpcb_other_fields 
                    );

                    //print_r($custom_html_list_data);

                    $custom_lists = get_option('wpcb_custom_lists');

                    if($custom_lists == ''){
                        $custom_lists_array = array($custom_html_list_data);
                    }
                    else{
                        $custom_lists_array = unserialize($custom_lists);
                        array_push($custom_lists_array, $custom_html_list_data);
                    }
                    $custom_lists = serialize($custom_lists_array);
                    update_option('wpcb_custom_lists',$custom_lists);
                    echo 1;
                }
                else{
                    $custom_html_list_data = array(
                        'custom_list_name' => $custom_list_name,
                        'action_url' => $action_url,
                        'emails_name_tag' => $wpcb_email_name,
                        'names_name_tag' => $wpcb_name_name,
                        'other_fields' => $wpcb_other_fields 
                    );
                    print_r($custom_html_list_data);
                    echo $email_input;
                }
                die();

                // Save the sanitized HTML string.
                //$meta['custom_html'] = trim(esc_html($sanitized_html));
                
        }
        
        /***************************************
         * Get dropdown list of template types
         ***************************************/
        
        public function get_list_of_template_types(){
            echo "<label for='wpcb_template_type'>". __('Template Type', 'wp-conversion-boxes-pro' ) .": </label><select id='wpcb_template_type' name='wpcb_template_type'>";
            echo "<option value='1'>".$this->template_directory_1."</option>";
            echo "<option value='2'>".$this->template_directory_2."</option>";
            echo "<option value='3'>".$this->template_directory_3."</option>";
            echo "<option value='4'>".$this->template_directory_4."</option>";
            echo "</select>";
        }
        
        /***************************************
         * Get all custom email lists
         ***************************************/
        
        public function get_all_custom_email_lists(){
            $all_custom_lists = get_option('wpcb_custom_lists');
            if($all_custom_lists == ''){
                echo "";
            }
            else{
                $custom_lists = unserialize($all_custom_lists);
                foreach($custom_lists as $k => $list){
                   echo "<tr><td style='padding: 10px 10px;'>".$list['custom_list_name']."</td><td style='text-align: center; padding: 10px;'><a style='color: red; cursor: pointer;' id='wpcb_delete_custom_list' data-custom-list-id='".$k."'>". __('Delete', 'wp-conversion-boxes-pro' ) ."</a></td></tr>";
                }
            }
        }
        
        /***************************************
         * Delete custom email lists
         ***************************************/
        
        public function delete_custom_list(){
            $custom_list_id = $_POST['custom_list_id'];
            $all_custom_lists = get_option('wpcb_custom_lists');
            $custom_lists = unserialize($all_custom_lists);
            unset($custom_lists[$custom_list_id]);
            $updated_custom_lists = serialize($custom_lists);
            update_option('wpcb_custom_lists',$updated_custom_lists);
            echo 1;
            die();
        }

        /***************************************
         * Upload and extract uploaded zip
         ***************************************/
        
        public function upload_custom_template($template_zip) {
            $template_name = $template_zip['name'];
            $ext = pathinfo($template_name, PATHINFO_EXTENSION);
            if($ext != 'zip'){
                echo "<div class='error'><p>". __('Invalid file format. Please upload .zip file.', 'wp-conversion-boxes-pro') ."</p></div>";
            }
            else{
                function wpcb_my_upload_dir($upload) {
                    switch($_POST['wpcb_template_type']){
                        case 1: $sub_dir = '/wpcb-custom-templates/email-optin';
                                break;
                        case 2: $sub_dir = '/wpcb-custom-templates/video-email-optin';
                                break;
                        case 3: $sub_dir = '/wpcb-custom-templates/call-to-action';
                                break;
                        case 4: $sub_dir = '/wpcb-custom-templates/video-call-to-action';
                                break;                
                    }
                    $upload['subdir'] = $sub_dir;
                    $upload['path']   = $upload['basedir'] . $upload['subdir'];
                    $upload['url']    = $upload['baseurl'] . $upload['subdir'];
                    return $upload;
                }

                add_filter('upload_dir', 'wpcb_my_upload_dir');
                $upload_result = wp_handle_upload( $template_zip , array('test_form' => FALSE));
                if($upload_result){
                    WP_Filesystem();
                    $unzipfile = unzip_file($upload_result['file'],dirname($upload_result['file']));
                    if($unzipfile){
                        unlink( $upload_result['file'] );
                        echo "<div class='updated'><p>". __('Template uploaded successfully! You can now use it for your conversion boxes from Select Template page.', 'wp-conversion-boxes-pro') ."</p></div>";
                    } 
                    else {
                       echo "<div class='error'><p>". __('ERROR: There was an error unzipping the file.', 'wp-conversion-boxes-pro') ."</p></div>";
                    }
                }
                else{
                    echo "<div class='error'><p>". __('ERROR: Possible file upload attack!', 'wp-conversion-boxes-pro') ."</p></div>";
                }
                remove_filter('upload_dir', 'wpcb_my_upload_dir');

            }
        }
        
        /***************************************
         * Show list of uploaded custom templates
         ***************************************/
        
        public function show_list_of_custom_templates() {
            
            $wpcb_public = WPCB_Public::get_instance();
            
            for($i = 1 ; $i <= 4 ; $i++){
                $wpcb_template_main_dir = WPCB_CUSTOM_TEMPLATE_DIR_PATH."/".$wpcb_public->get_template_directory($i).'/';
                $wpcb_template_dir_url = WPCB_CUSTOM_TEMPLATE_DIR_URL."/".$wpcb_public->get_template_directory($i).'/';
                $wpcb_template_type_name = $wpcb_public->get_template_directory_type_name($i);
                $wpcb_templates = scandir($wpcb_template_main_dir);
                if($wpcb_templates){
                    foreach ($wpcb_templates as $wpcb_template_dir) {

                        if($wpcb_template_dir === '.' || $wpcb_template_dir === '..') continue;

                        if (is_dir($wpcb_template_main_dir . $wpcb_template_dir)) {
                            $wpcb_template_screenshot_url = $wpcb_template_dir_url.$wpcb_template_dir.'/screenshot.png';
                            echo "<div class='wpcb_custom_template'>";
                            echo "<img src='".$wpcb_template_screenshot_url."' />";
                            echo "<p>".$wpcb_template_dir."</p>";
                            echo "<p><b>Type: </b>".$wpcb_template_type_name."<span style='float: right;'><a style='color: #a00; text-decoration: none;' href='". admin_url( 'admin.php?page=' . $this->wpcb_settings_slug )."&step=3&action=delete&template=".$wpcb_public->get_template_directory($i)."/".$wpcb_template_dir."' >".__('Delete','wp-conversion-boxes-pro')."</a></span></p>";
                            echo "</div>";
                        }

                    }
                }
            }
        }
        
        /***************************************
         * Delete custom template
         ***************************************/
        
        public static function wpcb_delete_dir($dir) {
            
            if (!file_exists($dir)) {
                return true;
            }

            if (!is_dir($dir)) {
                return unlink($dir);
            }

            foreach (scandir($dir) as $item) {
                if ($item == '.' || $item == '..') {
                    continue;
                }

                if (!self::wpcb_delete_dir($dir . DIRECTORY_SEPARATOR . $item)) {
                    return false;
                }

            }

            return rmdir($dir);
            
        }
        
        /***************************************
         * Delete custom template
         ***************************************/
        
        public function disable_box(){
            $box_id = (isset($_POST['box_id'])) ? $_POST['box_id'] : 0;
            $box_status = (isset($_POST['box_status'])) ? $_POST['box_status'] : '';
            
            global $wpdb;
            $wpcb_if_done = $wpdb->update($this->wpcb_boxes_table, array('box_status' => $box_status), array('id' => $box_id), array('%d'), array('%d'));
            
            if($wpcb_if_done === FALSE)
                echo 0;
            else
                echo 1;
            
            die();
        }
        
        /***************************************
         * Export boxes to XML if asked for
         ***************************************/
        
        public function export_boxes_to_xml(){
            
            if(isset($_POST['export-boxes'])){

                global $wpdb;
                $wpcb_tbl_name = $this->wpcb_boxes_table;
                $boxes_list = $wpdb->get_results("SELECT * FROM $wpcb_tbl_name ORDER BY id ASC","ARRAY_A");
                
                /* Create a file name */
                $sitename = sanitize_key( get_bloginfo( 'name' ) );
                $filename = $sitename . '-wpcb-pro-' . date( 'Y-m-d' ) . '.xml';

                
                /* Print header */
                header( 'Content-Description: File Transfer' );
                header( 'Content-Disposition: attachment; filename=' . $filename );
                header( 'Content-Type: text/xml; charset=' . get_option( 'blog_charset' ), true );

                /* Print comments */
                echo "<!-- WP Conversion Boxes Pro Export Data -->\n";
                echo '<boxes>';
                foreach ( $boxes_list as $box ) { ?>
                    <item>
                        <box_name><?php echo esc_attr($box['box_name']); ?></box_name>
                        <box_status><?php echo absint($box['box_status']); ?></box_status>
                        <box_type><?php echo absint($box['box_type']); ?></box_type>
                        <box_template><?php echo esc_attr($box['box_template']); ?></box_template>
                        <is_custom_template><?php echo absint($box['is_custom_template']); ?></is_custom_template>
                        <box_customizations><?php echo $box['box_customizations']; ?></box_customizations>
                        <box_settings><?php echo $box['box_settings']; ?></box_settings>
                        <test_enabled><?php echo absint($box['test_enabled']); ?></test_enabled>
                        <test_with><?php echo absint($box['test_with']); ?></test_with>
                    </item>
                <?php }
                echo '</boxes>';
                exit();
            }
            
        }
        
        /***************************************
         * Import boxes from XML
         ***************************************/
        
        public function import_boxes_from_xml(){
            if(isset($_POST['import-boxes'])){
                if(isset($_FILES['wpcb_import_xml']['error']) && $_FILES['wpcb_import_xml']['size'] != 0){
                    $imported_xml = $_FILES['wpcb_import_xml'];
                    $imported_xml_name = $_FILES['wpcb_import_xml']['name'];
                    $ext = pathinfo($imported_xml_name, PATHINFO_EXTENSION);
                    if($ext != 'xml'){
                        echo "<div class='error'><p>". __('Invalid file format. Please upload an XML file.', 'wp-conversion-boxes-pro') ."</p></div>";
                    }
                    else{
                        $boxes = simplexml_load_file($imported_xml['tmp_name']) or die("Error: There was an error parsing your export file.");
                        foreach($boxes as $box){
                            $is_custom_template = (isset($box->is_custom_template)) ? $box->is_custom_template : 0;
                            $test_enabled = (isset($box->test_enabled)) ? $box->test_enabled : 0 ;
                            $test_with = (isset($box->test_with)) ? $box->test_with : 0;
                            
                            global $wpdb;
                            $wpdb->insert($this->wpcb_boxes_table, 
                                array(
                                    'box_name' => $box->box_name,
                                    'box_status' => $box->box_status,
                                    'box_type' => $box->box_type, 
                                    'box_template' => $box->box_template,
                                    'is_custom_template' => $is_custom_template,
                                    'box_customizations' => $box->box_customizations,
                                    'box_settings' => $box->box_settings,
                                    'test_enabled' => $test_enabled,
                                    'test_with' => $test_with
                                ), 
                                array(
                                    '%s',
                                    '%d',
                                    '%d',
                                    '%s',
                                    '%d',
                                    '%s',
                                    '%s',
                                    '%d',
                                    '%d'
                                )
                            );
                        }
                        if($wpdb->insert_id){
                            echo "<div class='updated'><p>".__('Boxes imported successfully!','wp-conversion-boxes')."</p></div>";
                        }
                        else{
                            echo "<div class='error'><p>".__('There was an error importing','wp-conversion-boxes')."</p></div>";
                        }
                    }
                }
                else{
                    echo "<div class='error'><p>". __('No file selected. Please select an XML file before clicking on Import.', 'wp-conversion-boxes-pro') ."</p></div>";
                }
                
            }
        }
        
}