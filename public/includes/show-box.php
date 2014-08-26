<?php
        $posttype = get_post_type();
        
        $wpcb_default_box = get_option('wpcb_default_box');
        $wpcb_all_posts = get_option('wpcb_all_posts');
        $wpcb_all_pages = get_option('wpcb_all_pages');
            
        function show_the_box($id){
            
            $wpcb_public = WPCB_Public::get_instance();
            $wpcb_tracking = WPCB_Tracker::get_instance();            
            
            global $wpdb;

            $wpcb_tbl_name = $wpcb_public->get_boxes_table_name();
            $wpcb_the_row = $wpdb->get_row($wpdb->prepare("SELECT * from $wpcb_tbl_name WHERE id = %d", array($id)));

            if ($wpcb_the_row) {
                $box_type = $wpcb_the_row->box_type;
                $box_template = $wpcb_the_row->box_template;
                $box_customizations = $wpcb_the_row->box_customizations;
                $box_settings = $wpcb_the_row->box_settings;
            } else {

            }
            
            $box_id = $id;
            
            if($box_customizations != null AND $box_customizations != 'defaults'){
                $wpcb_default_fields = unserialize($box_customizations);
                $box_customizations = $wpcb_public->sanitise_array($box_customizations);
                $wpcb_default_fields['defaults'] = 'custom';
            }
            else{
                $wpcb_default_fields['defaults'] = 'defaults';
            }
            
            if(isset($box_settings)){
            
                $wpcb_box_settings = unserialize($box_settings);

                if($wpcb_box_settings['box_fade_in'] == '1'){
                    $wpcb_default_fields['box_fade_in'] = "wpcb_fade_in";
                }

                $wpcb_default_fields['box_fade_in_time'] = $wpcb_box_settings['box_fade_in_time'];

                if($wpcb_box_settings['box_make_sticky'] == '1'){
                    $wpcb_default_fields['box_make_sticky'] = "box_make_sticky";
                }

            }
            
            ob_start();

            switch($box_type){
                case 1 :    include(plugin_dir_path(dirname(dirname(__FILE__))).'templates/'.$wpcb_public->get_template_directory(1).'/'.$box_template.'/template.php');
                            break;
                case 2 :    include(plugin_dir_path(dirname(dirname(__FILE__))).'templates/'.$wpcb_public->get_template_directory(2).'/'.$box_template.'/template.php');
                            break;
                case 3 :    include(plugin_dir_path(dirname(dirname(__FILE__))).'templates/'.$wpcb_public->get_template_directory(3).'/'.$box_template.'/template.php');
                            break;
                case 4 :    include(plugin_dir_path(dirname(dirname(__FILE__))).'templates/'.$wpcb_public->get_template_directory(4).'/'.$box_template.'/template.php');
                            break;                                     
            }
            
            $wpcb_tracking->log_new_visit($box_id);

        }
        
        if($wpcb_all_posts == $wpcb_all_pages && $wpcb_all_posts != 0){
            show_the_box($wpcb_all_posts);
        }else if($posttype == 'post' && $wpcb_all_posts != 0){
            show_the_box($wpcb_all_posts);
        }
        elseif($posttype == 'page' && $wpcb_all_pages != 0){
            show_the_box($wpcb_all_pages);
        }
        elseif($wpcb_default_box != 0){
            show_the_box($wpcb_default_box);
        }
        else {
            ob_start();
        }

        $final_output = ob_get_contents();  // get buffer content
        ob_end_clean();
        