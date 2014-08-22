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
        protected $wpcb_ab_tests_screen_hook_suffix = null;
        protected $wpcb_edit_ab_test_screen_hook_suffix = null;
        protected $wpcb_stats_screen_hook_suffix = null;
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
                $this->wpcb_website_url = $wpcb_public::WPCB_WEBSITE_URL;
		$this->wpcb_main_slug = $wpcb_public->get_wpcb_main_slug();
                $this->wpcb_edit_slug = $wpcb_public->get_wpcb_edit_slug();
                $this->wpcb_ab_tests_slug = $wpcb_public->get_wpcb_ab_tests_slug();
                $this->wpcb_edit_ab_test_slug = $wpcb_public->get_wpcb_edit_ab_test_slug();
                $this->wpcb_stats_slug = $wpcb_public->get_wpcb_stats_slug();
                $this->wpcb_settings_slug = $wpcb_public->get_wpcb_settings_slug();
                $this->template_directory_1 = $wpcb_public->get_template_directory(1);
                $this->template_directory_2 = $wpcb_public->get_template_directory(2);
                $this->template_directory_3 = $wpcb_public->get_template_directory(3);
                $this->template_directory_4 = $wpcb_public->get_template_directory(4);
                $this->wpcb_boxes_table = $wpcb_public->get_boxes_table_name();
            
                
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
		if ( $this->wpcb_main_screen_hook_suffix == $wpcb_screen->id || $this->wpcb_edit_screen_hook_suffix == $wpcb_screen->id || $this->wpcb_stats_screen_hook_suffix == $wpcb_screen->id || $this->wpcb_settings_screen_hook_suffix == $wpcb_screen->id ) {
			wp_enqueue_style( $this->wpcb_main_slug .'-admin-styles', plugins_url( 'assets/css/admin.css', __FILE__ ), array(), WPCB_Public::VERSION );
            wp_enqueue_style( 'wp-color-picker');
                }       

	}
        

	/***************************************
	 * Register and enqueue admin-specific 
         * JavaScript.
         * TODO
	 ***************************************/
        public function enqueue_admin_scripts() {

		if ( ! isset( $this->wpcb_main_screen_hook_suffix ) || ! isset( $this->wpcb_edit_screen_hook_suffix ) ) {
			return;
		}

		$wpcb_screen = get_current_screen();
		if ( $this->wpcb_main_screen_hook_suffix == $wpcb_screen->id || $this->wpcb_edit_screen_hook_suffix == $wpcb_screen->id || $this->wpcb_stats_screen_hook_suffix == $wpcb_screen->id || $this->wpcb_settings_screen_hook_suffix == $wpcb_screen->id ) {
                        wp_enqueue_script('wp-color-picker');
                        wp_enqueue_media();
                        wp_enqueue_script( $this->wpcb_main_slug . '-admin-script', plugins_url( 'assets/js/admin.js', __FILE__ ), array( 'jquery' , 'wp-color-picker'), WPCB_Public::VERSION );
                        wp_enqueue_script( $this->wpcb_main_slug . "-real-time-box-customizer-js",  plugins_url('assets/js/realtimeboxcustomizer.js',__FILE__));
                        wp_enqueue_script( $this->wpcb_main_slug . "-flot-js",  plugins_url('assets/js/jquery.flot.min.js',__FILE__));
                        wp_enqueue_script( $this->wpcb_main_slug . "-flot-selection-js",  plugins_url('assets/js/jquery.flot.selection.min.js',__FILE__));
                        wp_enqueue_script( $this->wpcb_main_slug . "-flot-timer-js",  plugins_url('assets/js/jquery.flot.time.min.js',__FILE__));                        
		}

	}

        
	/**************************************
	 * Register admin menu and sub menus
	 **************************************/
	public function wpcb_add_admin_menu() {

		$this->wpcb_main_screen_hook_suffix = add_menu_page(
			__( 'WP Conversion Boxes', $this->wpcb_main_slug ),
			__( 'WP Conversion Boxes', $this->wpcb_main_slug ),
			'manage_options',
			$this->wpcb_main_slug,
			array( $this, 'wpcb_display_main_page' ),
                        //plugins_url('assets/imgs/icon.png', __FILE__ ),
                        '',
                        85
		);
                add_submenu_page(
                        $this->wpcb_main_slug, 
                        __( 'WP Conversion Boxes', $this->wpcb_main_slug ), 
                        __( 'WP Conversion Boxes', $this->wpcb_main_slug ),
                        'manage_options',
                        $this->wpcb_main_slug
                );
                $this->wpcb_edit_screen_hook_suffix = add_submenu_page(
                        $this->wpcb_main_slug, 
                        __( 'Add New Box', $this->wpcb_main_slug ), 
                        __( 'Add New Box', $this->wpcb_main_slug ),
                        'manage_options',
                        $this->wpcb_edit_slug,
                        array( $this, 'wpcb_display_edit_page' )
                );
                $this->wpcb_ab_tests_screen_hook_suffix = add_submenu_page(
                        $this->wpcb_main_slug, 
                        __( 'A/B Tests', $this->wpcb_main_slug ), 
                        __( 'A/B Tests', $this->wpcb_main_slug ),
                        'manage_options',
                        $this->wpcb_ab_tests_slug,
                        array( $this, 'wpcb_display_ab_tests_page' )
                );
                $this->wpcb_edit_ab_test_screen_hook_suffix = add_submenu_page(
                        $this->wpcb_main_slug, 
                        __( 'Add New A/B Test', $this->wpcb_main_slug ), 
                        __( 'Add New A/B Test', $this->wpcb_main_slug ),
                        'manage_options',
                        $this->wpcb_edit_ab_test_slug,
                        array( $this, 'wpcb_display_edit_ab_test_page' )
                );
                $this->wpcb_stats_screen_hook_suffix = add_submenu_page(
                        $this->wpcb_main_slug, 
                        __( 'Statistics', $this->wpcb_main_slug ), 
                        __( 'Statistics', $this->wpcb_main_slug ),
                        'manage_options',
                        $this->wpcb_stats_slug,
                        array( $this, 'wpcb_display_stats_page' )
                );
                $this->wpcb_settings_screen_hook_suffix = add_submenu_page(
                        $this->wpcb_main_slug, 
                        __( 'Global Settings', $this->wpcb_main_slug ), 
                        __( 'Global Settings', $this->wpcb_main_slug ),
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
        public function wpcb_display_ab_tests_page(){
                include_once( 'views/ab-tests.php' );
        }
        public function wpcb_display_edit_ab_test_page(){
                include_once( 'views/edit-ab-test.php' );
        }
        public function wpcb_display_stats_page() {
		include_once( 'views/statistics.php' );
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
				'settings' => '<a href="' . admin_url( 'admin.php?page=' . $this->wpcb_edit_slug ) . '">' . __( 'Add New Box', $this->wpcb_main_slug ) . '</a>'
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
                echo "<p>Enter a name for your new Coversion Box:</p>"
                             . "<input type='text' name='wpcb_box_name' id='wpcb_box_name' class='regular-text'><br /><br />"
                             . "<input type='submit' name='wpcb_create_box' id='wpcb_create_box' value='Create Box and Proceed' class='button button-primary'>";
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
                
                // All the code that'll save the box data to db
                die(); // this is required to return a proper result
        } 
        
        // Handle AJAX call to update box using ID  
        
        public function update_box_template() {
                global $wpdb;

                $wpcb_data = array(
                    'box_type' => $_POST['box_type'],
                    'box_template' => $_POST['box_template']
                );

                $wpcb_if_done = $wpdb->update($this->wpcb_boxes_table, $wpcb_data, array('id' => $_POST['box_id']), array('%d','%s'), array('%d'));
                if($wpcb_if_done === FALSE)
                    echo 0;
                else
                    echo 1;
                
                // All the code that'll save the box data to db
                die(); // this is required to return a proper result
        } 
        
        //Update box design customizations
        
        public function update_box_customizations() {
            
                global $wpdb;

                $all_customizations = $_POST['all_customizations'];
                
                $box_customizations = serialize($all_customizations);

                $wpcb_if_done = $wpdb->update($this->wpcb_boxes_table, array('box_customizations' => $box_customizations), array('id' => $_POST['box_id']), array('%s'), array('%d'));
                
                if($wpcb_if_done === FALSE)
                    echo 0;
                else
                    echo 1;
                
                // All the code that'll save the box data to db
                die(); // this is required to return a proper result
                
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
                
                // All the code that'll save the box data to db
                die(); // this is required to return a proper result
        }


        // Update box settings
        
        public function update_box_settings() {
                global $wpdb;
                
                $box_name = $_POST['box_name'];
                $box_settings = array(
                    'box_fade_in' => $_POST['box_fade_in'],
                    'box_fade_in_time' => $_POST['box_fade_in_time'],
                    'box_make_sticky' => $_POST['box_make_sticky']    
                );
                
                $box_id = $_POST['box_id'];
                
                $box_settings = serialize($box_settings);

                $wpcb_if_done = $wpdb->update($this->wpcb_boxes_table, array('box_name' => $box_name, 'box_settings' => $box_settings), array('id' => $box_id), array('%s'), array('%d'));
                
                if($wpcb_if_done === FALSE)
                    echo 0;
                else
                    echo 1;
                
                // All the code that'll save the box data to db
                die(); // this is required to return a proper result
        }
        
        // Handles AJAX call to delete a particular box.
        
        public function delete_it(){
                global $wpdb;
                $wpcb_id = $_POST['wpcb_id'];
                if($wpdb->delete( $this->wpcb_boxes_table, array( 'id' => $wpcb_id ) )){
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
            if(update_option('wpcb_default_box', $wpcb_boxes_list_default) || update_option('wpcb_all_posts', $wpcb_boxes_list_posts) || update_option('wpcb_all_pages', $wpcb_boxes_list_pages))
                echo 1;
            else
                echo 1;
        }

        /***************************************
         * Show list of all the boxes
         ***************************************/
        
        public function wpcb_show_boxes_list() {
                global $wpdb;
                $wpcb_tbl_name = $this->wpcb_boxes_table;
                $results = $wpdb->get_results("SELECT id,box_name FROM $wpcb_tbl_name ORDER BY id DESC");
                $result_count = count($results);
                if($result_count != 0){
                    $count = 1;
                    ?>
                    <table class="wp-list-table widefat fixed posts" style="clear: none;">
                        <thead>
                            <tr>
                                <th>WP Conversion Boxes Name</th>
                                <th>Shortcode</th>
                            </tr>
                        </thead>

                        <tfoot>
                            <tr>
                                <th>WP Conversion Boxes Name</th>
                                <th>Shortcode</th>
                            </tr>
                        </tfoot>

                        <tbody id="the-list">
                        <?php
                        foreach ($results as $result){
                            $id = $result->id;
                            $name = $result->box_name;
                            $wpcb_list = ++$count % 2 == 0 ? "<tr class='alternate wpcb-list-item-".$id."'>": "<tr class='wpcb-list-item-".$id."'>";
                            $wpcb_list .= "<td>".$name."<div class='row-actions'>";
                            $wpcb_list .= "<a href='".admin_url( 'admin.php?page=' . $this->wpcb_edit_slug .'&step=1&id=' . $id )."' >Change Template</a> | ";
                            $wpcb_list .= "<a href='".admin_url( 'admin.php?page=' . $this->wpcb_edit_slug .'&step=2&id=' . $id )."' >Customize</a> | ";
                            $wpcb_list .= "<a href='".admin_url( 'admin.php?page=' . $this->wpcb_edit_slug .'&step=3&id=' . $id )."' >Settings</a> | ";
                            $wpcb_list .= "<a class='wpcb_delete' style='color: #a00;' href='' wpcb_id='".$id."'>Delete</a>";
                            $wpcb_list .= "</div></td>";
                            $wpcb_list .= "<td><input type='text' value='[wpcb id=\"".$id."\"]' ></td>";
                            $wpcb_list .= "</tr>";
                            echo $wpcb_list;
                        }
                        ?>
                        </tbody>
                    </table>
                    <?php
                }
                else{
                    echo "No boxes found. Please <a href='".admin_url( 'admin.php?page=' . $this->wpcb_edit_slug)."'>click here</a> to create a new WP Conversion Box.";
                }
        }
        
        /***************************************
         * Show list of all boxes in DB
         ***************************************/
        
        public function wpcb_box_list($selected_box,$list_type,$list_id){
            global $wpdb;
            $wpcb_tbl_name = $this->wpcb_boxes_table;
            $boxes_list = $wpdb->get_results("SELECT id,box_name FROM $wpcb_tbl_name ORDER BY id ASC","ARRAY_A");
            
            if($list_type == 'default') $first_option = 'None';
            else $first_option = 'Use Default';
            
            echo "<select name='".$list_id."' id='".$list_id."'>";
            echo "<option value='0'>".$first_option."</option>";
            foreach($boxes_list as $box){
                if($selected_box == $box['id']){
                    $is_selected = "selected";
                }

                else{
                    $is_selected = "";
                }
                echo "<option value='".$box['id']."' ".$is_selected.">".$box['box_name']."</option>";
            }
            echo "</select>";
        }
        
        /***************************************
         * Show list of all categories and 
         * respective boxes from DB
         ***************************************/
        
        public function wpcb_category_wise_box_list(){

            $category_ids = get_all_category_ids();
            foreach ($category_ids as $cat_id){
                $categories_list[$cat_id] = get_cat_name($cat_id);
            }
            echo "<table class='widefat'><thead><tr><th>Category Name</th><th>Conversion Box</th><th>A/B Test</th><th>Select A/B Test</th><tr></thead><tbody>";
            foreach ($category_ids as $cat_id){
                $list_id = "wpcb_boxes_list_cat_".$cat_id;
                echo "<tr>";
                echo "<th>".get_cat_name($cat_id)."</th>";
                echo "<td><select disabled><option>Use Default</option></select>";
                //$this->wpcb_box_list('','',$list_id);
                echo "</td>";
                echo "<td><label><input type='checkbox' disabled> Enable</label></td><td><select disabled><option>None</option></select></td>";
                echo "</tr>";
            }
            echo "</tbody></table>";
        }
        
        /***************************************
         * Show list of all templates
         ***************************************/
        
        public function wpcb_template_list($selected_template,$template_type){
                switch($template_type){
                    case 1 :    $wpcb_template_main_dir = plugin_dir_path(dirname(__FILE__)).'templates/'.$this->template_directory_1.'/';
                                $wpcb_template_dir_url = plugins_url( 'templates/'.$this->template_directory_1, dirname(__FILE__ )).'/';
                                                break;
                    case 2 :    $wpcb_template_main_dir = plugin_dir_path(dirname(__FILE__)).'templates/'.$this->template_directory_2.'/';
                                $wpcb_template_dir_url = plugins_url( 'templates/'.$this->template_directory_2, dirname(__FILE__ )).'/';
                                                break;
                    case 3 :    $wpcb_template_main_dir = plugin_dir_path(dirname(__FILE__)).'templates/'.$this->template_directory_3.'/';
                                $wpcb_template_dir_url = plugins_url( 'templates/'.$this->template_directory_3, dirname(__FILE__ )).'/';
                                                break;
                    case 4 :    $wpcb_template_main_dir = plugin_dir_path(dirname(__FILE__)).'templates/'.$this->template_directory_4.'/';
                                $wpcb_template_dir_url = plugins_url( 'templates/'.$this->template_directory_4, dirname(__FILE__ )).'/';
                                                break;                                     
                }
                
                $wpcb_templates = scandir($wpcb_template_main_dir);
                
                $cnt = 0;
                
                echo "<div class='wpcb_template_selector'><select class='wpcb_template_dropdown' name='wpcb_template_dropdown_".$template_type."' id='wpcb_template_dropdown_".$template_type."'><option value='0'>Select template</option>";
                
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
            $wpcb_the_row = $wpdb->get_row($wpdb->prepare("SELECT `box_name` from $wpcb_tbl_name WHERE id = $box_id",array('%s','%d')));
            return $wpcb_the_row->box_name;
        }




        /***************************************
         * Include the selected template - If 
         * you make changes to this, also do so
         * with the same in WPCB_Public class.
         ***************************************/        
        
        public function include_the_template_and_settings($box_type, $box_template, $box_customizations, $box_id){
            echo "<div class='postbox'><h3>Box Preview</h3><div class='inside minheight150'>";
            
            $wpcb_default_fields = $box_customizations;
            
            switch($box_type){
                    case 1 :    include_once(plugin_dir_path(dirname(__FILE__)).'templates/'.$this->template_directory_1.'/'.$box_template.'/template.php');
                                break;
                    case 2 :    include_once(plugin_dir_path(dirname(__FILE__)).'templates/'.$this->template_directory_2.'/'.$box_template.'/template.php');
                                break;
                    case 3 :    include_once(plugin_dir_path(dirname(__FILE__)).'templates/'.$this->template_directory_3.'/'.$box_template.'/template.php');
                                break;
                    case 4 :    include_once(plugin_dir_path(dirname(__FILE__)).'templates/'.$this->template_directory_4.'/'.$box_template.'/template.php');
                                break;                                     
            }
            
            echo "</div></div>";
            echo "<div class='postbox'><h3>Default Customizations</h3><div class='inside minheight150'>";
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
        
        public function get_mailer_campaigns_list($selected_campaign) {
            $getresponse = get_option('wpcb_getresponse_campaigns');
            $mailchimp = get_option('wpcb_mailchimp_lists');
            $aweber = get_option('wpcb_aweber_lists');
            
            $getresponse_campaigns = unserialize($getresponse);
            $mailchimp_lists = unserialize($mailchimp);
            $aweber_lists = unserialize($aweber);
            
            if($getresponse_campaigns != '' or $mailchimp_lists != '' or $aweber_lists != ''){

                //GetResponse
                
                echo '<strong>Your GetResponse Campaigns</strong><ul>';
                if($getresponse_campaigns != ''){
                    foreach($getresponse_campaigns as $gr_id => $gr_name){
                        if($gr_id == $selected_campaign){
                            echo '<li><label><input type="radio" class="input_campaign_name" name="input_campaign_name" data-mailer-id="1" value="'.$gr_id.'" checked>'.$gr_name.'</label></li>';
                        }
                        else{
                            echo '<li><label><input type="radio" class="input_campaign_name" name="input_campaign_name" data-mailer-id="1" value="'.$gr_id.'" >'.$gr_name.'</label></li>';
                        }
                    }                    
                }
                else{
                    echo "No campaigns found";
                }
                echo "</ul>";
                
                //MailChimp
                
                echo '<strong>Your MailChimp Lists</strong><ul>';
                if($mailchimp_lists != ''){
                    foreach($mailchimp_lists as $mc_id => $mc_name){
                        if($mc_id == $selected_campaign){
                            echo '<li><label><input type="radio" class="input_campaign_name" name="input_campaign_name" data-mailer-id="2" value="'.$mc_id.'" checked>'.$mc_name.'</label></li>';
                        }
                        else{
                            echo '<li><label><input type="radio" class="input_campaign_name" name="input_campaign_name" data-mailer-id="2" value="'.$mc_id.'" >'.$mc_name.'</label></li>';
                        }
                    }
                }
                else{
                    echo "No lists found";
                }
                echo "</ul>";
                
                //Aweber
                
                echo '<strong>Your Aweber Lists</strong><ul>';
                if($aweber_lists != ''){
                    foreach($aweber_lists as $aweber_id => $aweber_name){
                        if($aweber_id == $selected_campaign){
                            echo '<li><label><input type="radio" class="input_campaign_name" name="input_campaign_name" data-mailer-id="3" value="'.$aweber_id.'" checked>'.$aweber_name.'</label></li>';
                        }
                        else{
                            echo '<li><label><input type="radio" class="input_campaign_name" name="input_campaign_name" data-mailer-id="3" value="'.$aweber_id.'" >'.$aweber_name.'</label></li>';
                        }
                    }                    
                }
                else{
                    echo "No lists found";
                }
                echo "</ul>";
                
            }
            else{
                echo "<p>No campaigns/lists found. Please integrate your email service provider first to select a campaign/list for this conversion box. <a href='".admin_url( 'admin.php?page=' . $this->wpcb_settings_slug )."&step=2'>Click here to integrate now.</a></p>";
            }
    }


        /***************************************
         * Upgrade To WP Conversion Boxes Pro
         ***************************************/        
        
        public function upgrade_to_pro(){
            include_once('includes/upgrade-message.php');
            return $upgrade_message;
        }
}