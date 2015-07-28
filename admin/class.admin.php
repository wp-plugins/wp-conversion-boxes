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
                $this->template_directory_5 = $wpcb_public->get_template_directory(5);
                $this->template_directory_6 = $wpcb_public->get_template_directory(6);
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
         * TODO
	 *************************************/
        public function enqueue_admin_styles() {

		if ( ! isset( $this->wpcb_main_screen_hook_suffix ) || ! isset( $this->wpcb_edit_screen_hook_suffix ) ) {
			return;
		}

		$wpcb_screen = get_current_screen();
		if ( $this->wpcb_main_screen_hook_suffix == $wpcb_screen->id || $this->wpcb_edit_screen_hook_suffix == $wpcb_screen->id || $this->wpcb_settings_screen_hook_suffix == $wpcb_screen->id ) {
			wp_enqueue_style( $this->wpcb_main_slug .'-admin-styles', ADMIN_ASSETS_URL.'/css/admin.css', array() );
                        wp_enqueue_style( 'wp-color-picker');
                        wp_enqueue_style( $this->wpcb_main_slug .'-font-awesome', '//netdna.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css' );
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
                
                if($this->wpcb_edit_screen_hook_suffix == $wpcb_screen->id){
                    wp_enqueue_script('jquery-ui-accordion');
                }
                
		if ( $this->wpcb_main_screen_hook_suffix == $wpcb_screen->id || $this->wpcb_edit_screen_hook_suffix == $wpcb_screen->id || $this->wpcb_settings_screen_hook_suffix == $wpcb_screen->id ) {
                        wp_enqueue_script('wp-color-picker');
                        wp_enqueue_media();
                        
                        // Admin JS
                        wp_enqueue_script( $this->wpcb_main_slug . '-admin-script', ADMIN_ASSETS_URL.'/js/admin.js', array( 'jquery' , 'wp-color-picker'));
                        $admin_data = array(
                            'choseImage' => __('Choose Image','wp-conversion-boxes'),
                            'creatingBox' => __( 'Creating new box... Please wait!' , 'wp-conversion-boxes' ),
                            'boxCreated' => __( 'Box Created Successfully. Redirecting...' , 'wp-conversion-boxes' ),
                            'createBox' => __( 'Create Box and Proceed' , 'wp-conversion-boxes' ),
                            'errorSavingToDB' => __( 'There was an error saving to database. Please try again later.' , 'wp-conversion-boxes' ),
                            'errorUpdatingDB' => __( 'There was an error updating the database. Please try again later.' , 'wp-conversion-boxes' ),
                            'updatingWait' => __( 'Updating... Please wait!' , 'wp-conversion-boxes' ),
                            'savedRedirecting' => __( 'Saved! Redirecting...' , 'wp-conversion-boxes' ),
                            'saveAndNext' => __( 'Save and Next' , 'wp-conversion-boxes' ),
                            'settingsSaved' => __( 'Settings saved successfully.' , 'wp-conversion-boxes' ),
                            'saveAndPublish' => __( 'Save and Publish!' , 'wp-conversion-boxes' ),
                            'sureDelete' => __( 'Are you sure you want to delete this conversion box?' , 'wp-conversion-boxes' ),
                            'errorDelete' => __( 'ERROR: Unable to delete the conversion box. Please try again later.' , 'wp-conversion-boxes' ),
                            'flushStats' => __( 'Are you sure you want to flush all the stats for this conversion box?' , 'wp-conversion-boxes' ),
                            'errorFlush' => __( 'ERROR: Unable to flush the stats. Please try again later.' , 'wp-conversion-boxes' ),
                            'moreDataPopup' => __( 'Free version of WP Conversion Boxes shows only 7 top performing posts and pages. Upgrade to Pro to view stats for all links.' , 'wp-conversion-boxes' ),
                            'updatedSuccessfully' => __( 'Updated successfully.' , 'wp-conversion-boxes' ),
                            'update' => __( 'Update' , 'wp-conversion-boxes' ),
                            'abTestConfirm' => __( 'A/B tests feature is not available in free version. Please upgrade to Pro to get this feature.' , 'wp-conversion-boxes' ),
                            'boxPublished' => __( 'Box Published Successfully!' , 'wp-conversion-boxes' ),
                            'later' => __( 'Later' , 'wp-conversion-boxes' ),
                            'errorPublishing' => __( 'Error Publishing The Box!<br /><br />Reload this page and try again.' , 'wp-conversion-boxes' ),
                            'done' => __( 'Done' , 'wp-conversion-boxes' ),
                            'reload' => __( 'Reload' , 'wp-conversion-boxes' )
                        );
                        wp_localize_script( $this->wpcb_main_slug . '-admin-script', 'wpcbAdmin', $admin_data);
                        
                        // Real Time Box Customizer JS
                        wp_enqueue_script( $this->wpcb_main_slug . "-real-time-box-customizer-js",  ADMIN_ASSETS_URL.'/js/realtimeboxcustomizer.js');
                        $rtbc_data = array(
                            'resetDataConfirmation' => __('Are you sure you want to reset the customizations to default? All design elements and content will be reset to defaults.', 'wp-conversion-boxes'),
                            'resttingBtn' => __('Reseting... Please wait!','wp-conversion-boxes'),
                            'resetError' => __('There was an error. Please try again later or contact support if problem persists.','wp-conversion-boxes'),
                            'updatingBtn' => __('Updating... Please wait!','wp-conversion-boxes'),
                            'updateSaved' => __('Saved! Redirecting...','wp-conversion-boxes'),
                            'saveAndNext' => __('Save and Next','wp-conversion-boxes'),
                            'updateError' => __('There was an error updating the database. Please try again later or contact support if problem persists.','wp-conversion-boxes')
                        );
                        wp_localize_script( $this->wpcb_main_slug . "-real-time-box-customizer-js", 'wpcbRTBC', $rtbc_data);
                        
                        wp_enqueue_script( $this->wpcb_main_slug . "-flot-js",  ADMIN_ASSETS_URL.'/js/jquery.flot.min.js');
                        wp_enqueue_script( $this->wpcb_main_slug . "-flot-selection-js",  ADMIN_ASSETS_URL.'/js/jquery.flot.selection.min.js');
                        wp_enqueue_script( $this->wpcb_main_slug . "-flot-timer-js",  ADMIN_ASSETS_URL.'/js/jquery.flot.time.min.js');
                        wp_enqueue_script( $this->wpcb_main_slug . "-dd-slick",  ADMIN_ASSETS_URL.'js/jquery.ddslick.min.js');
                        wp_enqueue_script( $this->wpcb_main_slug . "-lightbox",  ADMIN_ASSETS_URL.'js/lightbox.js');
		}

	}

        
	/**************************************
	 * Register admin menu and sub menus
	 **************************************/
	public function wpcb_add_admin_menu() {

		$this->wpcb_main_screen_hook_suffix = add_menu_page(
			__( 'WP Conversion Boxes', 'wp-conversion-boxes' ),
			__( 'WP Conversion Boxes', 'wp-conversion-boxes' ),
			'manage_options',
			$this->wpcb_main_slug,
			array( $this, 'wpcb_display_main_page' ),
                        ADMIN_ASSETS_URL.'/imgs/icon.ico',
                        85
		);
                add_submenu_page(
                        $this->wpcb_main_slug, 
                        __( 'WP Conversion Boxes', 'wp-conversion-boxes' ), 
                        __( 'WP Conversion Boxes', 'wp-conversion-boxes' ),
                        'manage_options',
                        $this->wpcb_main_slug
                );
                $this->wpcb_edit_screen_hook_suffix = add_submenu_page(
                        $this->wpcb_main_slug, 
                        __( 'Add New Box', 'wp-conversion-boxes' ), 
                        __( 'Add New Box', 'wp-conversion-boxes' ),
                        'manage_options',
                        $this->wpcb_edit_slug,
                        array( $this, 'wpcb_display_edit_page' )
                );
                $this->wpcb_settings_screen_hook_suffix = add_submenu_page(
                        $this->wpcb_main_slug, 
                        __( 'Global Settings', 'wp-conversion-boxes' ), 
                        __( 'Global Settings', 'wp-conversion-boxes' ),
                        'manage_options',
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
	 * Add action link to the plugins page.
	 ***************************************/
	public function add_action_links( $links ) {

		return array_merge(
			array(
				'settings' => '<a href="' . admin_url( 'admin.php?page=' . $this->wpcb_edit_slug ) . '">' . __( 'Add New Box', 'wp-conversion-boxes' ) . '</a>'
			),
			$links
		);

	}
        
        /***************************************
	 * Sidebar metabox content
	 ***************************************/
        
        public function wpcb_sidebar() {
            include_once( 'views/sidebar.php' );
        }
        
        
        /***************************************
	 * Edit page content
	 ***************************************/
        
        public function wpcb_edit_page_content($step, $id){
            
            if(!isset($step)){
                echo "<p>". __('Enter a name for your new Coversion Box:','wp-conversion-boxes') . "</p>"
                             . "<input type='text' name='wpcb_box_name' id='wpcb_box_name' class='regular-text'><br /><br />"
                             . "<input type='submit' name='wpcb_create_box' id='wpcb_create_box' value='". __('Create Box and Proceed','wp-conversion-boxes') . "' class='button button-primary'>";
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
        public function create_new_box() {

                global $wpdb;
                $wpcb_box_name = strip_tags(stripslashes($_POST['wpcb_box_name']));
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
                    'box_template' => $_POST['box_template']
                );
                
                if($_POST['delete_customizations'] == '1'){
                    $wpcb_data['box_customizations'] = 'defaults';
                    $wpcb_if_done = $wpdb->update($this->wpcb_boxes_table, $wpcb_data, array('id' => $_POST['box_id']), array('%d','%s','%s'), array('%d'));
                }
                else{
                    $wpcb_if_done = $wpdb->update($this->wpcb_boxes_table, $wpcb_data, array('id' => $_POST['box_id']), array('%d','%s'), array('%d'));    
                }
            
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
                    'wpcb_popup_type_radio' => $_POST['wpcb_popup_type_radio'],
                    'wpcb_popup_option_val' => $_POST['wpcb_popup_option_val'],
                    'wpcb_popup_frequency' => $_POST['wpcb_popup_frequency']
                );
                
                $box_id = $_POST['box_id'];
                
                $box_settings = serialize($box_settings);

                $wpcb_if_done = $wpdb->update($this->wpcb_boxes_table, array('box_name' => $box_name, 'box_settings' => $box_settings), array('id' => $box_id), array('%s'), array('%d'));
                
                if($wpcb_if_done === FALSE)
                    echo 0;
                else
                    echo $box_id;
                
                die();
        }
        
        // Handles AJAX call to delete a particular box.
        
        public function delete_it(){
                global $wpdb;
                $wpcb_id = $_POST['wpcb_id'];
                if($wpdb->delete( $this->wpcb_boxes_table, array( 'id' => $wpcb_id ) )){
                    $wpdb->delete( $this->wpcb_tracking_table, array( 'box_id' => $wpcb_id ) );
                    echo $wpcb_id;
                }
                else 
                    echo 0;
                die();
        }

        /***************************************
         * Update Global Settings
         ***************************************/
        
        public function update_global_settings(){
            $wpcb_boxes_list_default = $_POST['wpcb_boxes_list_default'];
            $wpcb_boxes_list_posts = $_POST['wpcb_boxes_list_posts'];
            $wpcb_boxes_list_pages = $_POST['wpcb_boxes_list_pages'];
            $enable_credit_link = $_POST['enable_credit_link'];
            
        if(update_option('wpcb_default_box', $wpcb_boxes_list_default) || update_option('wpcb_all_posts', $wpcb_boxes_list_posts) || update_option('wpcb_all_pages', $wpcb_boxes_list_pages) || update_option('wpcb_enable_credit_link', $enable_credit_link))
                echo 1;
            else
                echo 1;
        }
        
        /***************************************
         * Publish Box
         ***************************************/
        
        public function publish_the_box(){
            
            $global_placement = $_POST['global_placement'];
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
            
            echo 1;
            die();
        }

        /***************************************
         * Show list of all the boxes
         ***************************************/
        
        public function wpcb_show_boxes_list() {
            include_once('includes/show-boxes-list.php');
        }
        
        /***************************************
         * Duplicate a box
         * 
         * @since 1.2.3
         ***************************************/
        
        public function wpcb_duplicate_box($box_id){
            global $wpdb;
            $wpcb_tbl_name = $this->wpcb_boxes_table;
            $box_data = $wpdb->get_row("SELECT * FROM $wpcb_tbl_name WHERE id = $box_id", "ARRAY_A");
            
            $wpdb->insert($this->wpcb_boxes_table, 
                array(
                    'box_name' => $box_data['box_name']." (". __( 'Duplicate' , 'wp-conversion-boxes' ) . ")",
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
            if($wpdb->insert_id)
                return true;
            else
                return false;
            
        }
        
        /***************************************
         * Show dropdown list of all boxes in DB
         ***************************************/
        
        public function wpcb_box_list($selected_box,$list_type,$list_id){
            global $wpdb;
            $wpcb_tbl_name = $this->wpcb_boxes_table;
            $boxes_list = $wpdb->get_results("SELECT id,box_name,box_type FROM $wpcb_tbl_name ORDER BY id ASC","ARRAY_A");
            
            if($list_type == 'default') $first_option = __('None','wp-conversion-boxes');
            else $first_option = __('Use Default','wp-conversion-boxes');
            
            echo "<select name='".$list_id."' id='".$list_id."'>";
            echo "<option value='0'>".$first_option."</option>";
            foreach($boxes_list as $box){
                if($box['box_type'] != '5'){
                    if($selected_box == $box['id']){
                        $is_selected = "selected";
                    }

                    else{
                        $is_selected = "";
                    }
                    echo "<option value='".$box['id']."' ".$is_selected.">".stripcslashes($box['box_name'])."</option>";   
                }
            }
            echo "</select>";
        }
        
        /***************************************
         * Show list of all categories and 
         * respective boxes from DB
         ***************************************/
        
        public function wpcb_category_wise_box_list(){

            $categories = get_terms('category','orderby=count&hide_empty=0');
            echo "<table class='widefat'><thead><tr><th>". __('Category Name','wp-conversion-boxes') ."</th><th>". __('Conversion Box','wp-conversion-boxes') ."</th></thead><tbody>";
            foreach ($categories as $cat){
                //$list_id = "wpcb_boxes_list_cat_".$cat->id;
                echo "<tr>";
                echo "<th>".$cat->name."</th>";
                echo "<td><select disabled><option>". __('Use Default','wp-conversion-boxes') ."</option></select>";
                echo "</td></tr>";
            }
            echo "</tbody></table>";
        }
        
        /***************************************
         * Show list of all templates
         ***************************************/
        
        public function wpcb_template_list($selected_template,$template_type){
                switch($template_type){
                    case 1 :    $wpcb_template_main_dir = TEMPLATE_DIR_PATH.$this->template_directory_1.'/';
                                $wpcb_template_dir_url = TEMPLATE_DIR_URL.$this->template_directory_1.'/';
                                                break;
                    case 2 :    $wpcb_template_main_dir = TEMPLATE_DIR_PATH.$this->template_directory_2.'/';
                                $wpcb_template_dir_url = TEMPLATE_DIR_URL.$this->template_directory_2.'/';
                                                break;
                    case 3 :    $wpcb_template_main_dir = TEMPLATE_DIR_PATH.$this->template_directory_3.'/';
                                $wpcb_template_dir_url = TEMPLATE_DIR_URL.$this->template_directory_3.'/';
                                                break;
                    case 4 :    $wpcb_template_main_dir = TEMPLATE_DIR_PATH.$this->template_directory_4.'/';
                                $wpcb_template_dir_url = TEMPLATE_DIR_URL.$this->template_directory_4.'/';
                                                break;
                    case 5 :    $wpcb_template_main_dir = TEMPLATE_DIR_PATH.$this->template_directory_5.'/';
                                $wpcb_template_dir_url = TEMPLATE_DIR_URL.$this->template_directory_5.'/';
                                                break;
                    case 6 :    $wpcb_template_main_dir = TEMPLATE_DIR_PATH.$this->template_directory_6.'/';
                                $wpcb_template_dir_url = TEMPLATE_DIR_URL.$this->template_directory_6.'/';
                                                break;
                }
                
                $wpcb_templates = scandir($wpcb_template_main_dir);
                
                $cnt = 0;
                
                echo "<div class='wpcb_template_selector'><select class='wpcb_template_dropdown' name='wpcb_template_dropdown_".$template_type."' id='wpcb_template_dropdown_".$template_type."'><option value='0'>". __('Select template','wp-conversion-boxes') ."</option>";
                
                foreach ($wpcb_templates as $wpcb_template_dir) {
                    
                    if($wpcb_template_dir === '.' || $wpcb_template_dir === '..') continue;
                    
                    if (is_dir($wpcb_template_main_dir . $wpcb_template_dir)) {
                        
                        if($selected_template == $wpcb_template_dir){
                            $is_selected = "selected";
                        }
                        
                        else{
                            $is_selected = "";
                        }
                        
                        $wpcb_template_screenshot_url = $wpcb_template_dir_url.$wpcb_template_dir.'/screenshot.png';
                        
                        echo "<option data-screenshot='".$wpcb_template_screenshot_url."' value='".$wpcb_template_dir."' ".$is_selected.">".$wpcb_template_dir."</option>";
                        
                    }
                    
                    $cnt++;
                }
                echo "</select></div>";
        }
        
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
        
        public function include_the_template_and_settings($box_type, $box_template, $box_customizations, $box_id){
            
            if($box_type == 5 || $box_type == 6){
                echo "<div class='wpcb_box_customizer'><h3>". __('Popup Lightbox Preview','wp-conversion-boxes')."</h3>";
            }
            else{
                echo "<div class='wpcb_box_customizer'><h3>". __('Box Preview','wp-conversion-boxes')."</h3>";
            }
            
            $wpcb_default_fields = $box_customizations;
            $wpcb_upgrade_message = $this->upgrade_to_pro();
            
            switch($box_type){
                    case 1 :    include_once(WPCB_TEMPLATE_DIR_PATH.$this->template_directory_1.'/'.$box_template.'/template.php');
                                break;
                    case 2 :    include_once(WPCB_TEMPLATE_DIR_PATH.$this->template_directory_2.'/'.$box_template.'/template.php');
                                break;
                    case 3 :    include_once(WPCB_TEMPLATE_DIR_PATH.$this->template_directory_3.'/'.$box_template.'/template.php');
                                break;
                    case 4 :    include_once(WPCB_TEMPLATE_DIR_PATH.$this->template_directory_4.'/'.$box_template.'/template.php');
                                break;
                    case 5 :    include_once(WPCB_TEMPLATE_DIR_PATH.$this->template_directory_5.'/'.$box_template.'/template.php');
                                break;
                    case 6 :    include_once(WPCB_TEMPLATE_DIR_PATH.$this->template_directory_6.'/'.$box_template.'/template.php');
                                break;
            }
            
            echo "</div>";
            echo "<div class='wpcb_box_customizer_options'>";
            echo "<div class='wpcb_nav_buttons_step_2'><a href='".admin_url( 'admin.php?page=' . $this->wpcb_edit_slug )."&step=1&id=".$box_id."' class='wpcb_customizer_back_button'></a><input type='submit' box_id='". $box_id ."' value='".__('Save and Next','wp-conversion-boxes'). "' class='button button-primary' name='update-box-customizations' id='update-box-customizations'/>";
            echo "<button box_id='". $box_id ."' class='button' id='restore-to-default'>".__('Reset','wp-conversion-boxes')."</button></div>";
            echo "<div class='wpcb_box_customizer_options_main_wrap'><div class='wpcb_box_customizer_options_main'><h3 class='wpcb_customizer_page_title'>". __('Step 2 : Customize Box','wp-conversion-boxes') ."</h3>";
            
            if($box_type == 5){
                echo "<p style='padding: 0px 15px 20px 15px; background-color: #fff; margin: 0px;'><b>Note:</b> You've selected the box type as <em>'2-Step Optin Link'</em>. This box type has two parts - optin link and email optin box (which appears as popup when you click on the optin link). This page is for customization of the email optin box. Customization of optin link is done during the last Publishing step.</p>";
            }
            
            include_once('includes/default-customizations-fields.php');
            echo "</div></div><div class='wpcb_customizer_footer'><em>WP Conversion Boxes</em></div></div>";
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
        
        public function get_mailer_campaigns_list($mailer_id,$selected_campaign) {
            $getresponse = get_option('wpcb_getresponse_campaigns');
            $mailchimp = get_option('wpcb_mailchimp_lists');
            $aweber = get_option('wpcb_aweber_lists');
            
            $getresponse_campaigns = unserialize($getresponse);
            $mailchimp_lists = unserialize($mailchimp);
            $aweber_lists = unserialize($aweber);
            if (class_exists('WYSIJA')){
                $modelList = WYSIJA::get('list', 'model');
                $mail_poet_lists = $modelList->get(array('name', 'list_id'), array('is_enabled' => 1));
            }
            
            $feedburner_uri = get_option('wpcb_feedburner_uri');
            
            if($feedburner_uri != '' or $getresponse_campaigns != '' or $mailchimp_lists != '' or $aweber_lists != '' or $mail_poet_lists != ''){

                //GetResponse
                
                echo '<select id="input_campaign_name" name="input_campaign_name">';
                
                if($getresponse_campaigns != ''){
                    foreach($getresponse_campaigns as $gr_id => $gr_name){
                        if($gr_id == $selected_campaign && $mailer_id == 1){
                            echo '<option data-description="GetResponse" data-imagesrc="'.ADMIN_ASSETS_URL.'imgs/gr-sm.png" data-mailer-id="1" value="'.$gr_id.'" selected>'.$gr_name.'</option>';
                        }
                        else{
                            echo '<option data-description="GetResponse" data-imagesrc="'.ADMIN_ASSETS_URL.'imgs/gr-sm.png" data-mailer-id="1" value="'.$gr_id.'" >'.$gr_name.'</option>';
                        }
                    }                    
                }
                
                //MailChimp
                
                if($mailchimp_lists != ''){
                    foreach($mailchimp_lists as $mc_id => $mc_name){
                        if($mc_id == $selected_campaign && $mailer_id == 2){
                            echo '<option data-description="MailChimp" data-imagesrc="'.ADMIN_ASSETS_URL.'imgs/mc-sm.png" data-mailer-id="2" value="'.$mc_id.'" selected>'.$mc_name.'</option>';
                        }
                        else{
                            echo '<option data-description="MailChimp" data-imagesrc="'.ADMIN_ASSETS_URL.'imgs/mc-sm.png" data-mailer-id="2" value="'.$mc_id.'" >'.$mc_name.'</option>';
                        }
                    }
                }

                //Aweber
                
                if($aweber_lists != ''){
                    foreach($aweber_lists as $aweber_id => $aweber_name){
                        if($aweber_id == $selected_campaign && $mailer_id == 3){
                            echo '<option data-description="Aweber" data-imagesrc="'.ADMIN_ASSETS_URL.'imgs/aweber-sm.png" data-mailer-id="3" value="'.$aweber_id.'" selected>'.$aweber_name.'</option>';
                        }
                        else{
                            echo '<option data-description="Aweber" data-imagesrc="'.ADMIN_ASSETS_URL.'imgs/aweber-sm.png" data-mailer-id="3" value="'.$aweber_id.'" >'.$aweber_name.'</option>';
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
                
                //Feedburner
                
                if($feedburner_uri != ''){
                    if($feedburner_uri == $selected_campaign && $mailer_id == 11){
                        echo '<option data-description="Feedburner" data-imagesrc="'.ADMIN_ASSETS_URL.'imgs/feedburner-sm.png" value="'.$feedburner_uri.'" selected>'.$feedburner_uri.'</option>';
                    }
                    else{
                        echo '<option data-description="Feedburner" data-imagesrc="'.ADMIN_ASSETS_URL.'imgs/feedburner-sm.png" value="'.$feedburner_uri.'" >'.$feedburner_uri.'</option>';
                    }
                }
                
                echo "</select>";
                
            }
            else{
                echo "<p>". __('No campaigns/lists found. Please integrate your email service provider first to select a campaign/list for this conversion box.','wp-conversion-boxes') ." <a href='".admin_url( 'admin.php?page=' . $this->wpcb_settings_slug )."&step=2'>". __('Click here to integrate now.' , 'wp-conversion-boxes' ) ."</a></p>";
            }
        }

        /***************************************
         * Upgrade To WP Conversion Boxes Pro
         ***************************************/        
        
        public function upgrade_to_pro(){
            include_once('includes/upgrade-message.php');
            return $upgrade_message;
        }
        
        
        /****************************************
         * Checklist of categories
         ****************************************/
        
        public function checklist_of_categories() {
            $selected_cats = array();
            $categories = get_categories();
            if ( $categories ){
                echo "<div class='wpcb_cat_checklist'>";
                wp_category_checklist( 0, 0, $selected_cats, false, null, false );
                echo "</div>";
            }
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
                $filename = $sitename . '-wpcb-' . date( 'Y-m-d' ) . '.xml';

                
                /* Print header */
                header( 'Content-Description: File Transfer' );
                header( 'Content-Disposition: attachment; filename=' . $filename );
                header( 'Content-Type: text/xml; charset=' . get_option( 'blog_charset' ), true );

                /* Print comments */
                echo "<!-- WP Conversion Boxes Export Data -->\n";
                echo '<boxes>';
                foreach ( $boxes_list as $box ) { ?>
                    <item>
                        <box_name><?php echo esc_attr($box['box_name']); ?></box_name>
                        <box_status><?php echo absint($box['box_status']); ?></box_status>
                        <box_type><?php echo absint($box['box_type']); ?></box_type>
                        <box_template><?php echo esc_attr($box['box_template']); ?></box_template>
                        <box_customizations><?php echo $box['box_customizations']; ?></box_customizations>
                        <box_settings><?php echo $box['box_settings']; ?></box_settings>
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
                        echo "<div class='error'><p>". __('Invalid file format. Please upload an XML file.', 'wp-conversion-boxes') ."</p></div>";
                    }
                    else{
                        $boxes = simplexml_load_file($imported_xml['tmp_name']) or die("Error: There was an error parsing your export file.");
                        foreach($boxes as $box){
                            global $wpdb;
                            $wpdb->insert($this->wpcb_boxes_table, 
                                array(
                                    'box_name' => $box->box_name,
                                    'box_status' => $box->box_status,
                                    'box_type' => $box->box_type, 
                                    'box_template' => $box->box_template,
                                    'box_customizations' => $box->box_customizations,
                                    'box_settings' => $box->box_settings
                                ), 
                                array(
                                    '%s',
                                    '%d',
                                    '%d',
                                    '%s',
                                    '%s',
                                    '%s'
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
                    echo "<div class='error'><p>". __('No file selected. Please select an XML file before clicking on Import.', 'wp-conversion-boxes') ."</p></div>";
                }
                
            }
        }
        
}

