<?php
        $posttype = get_post_type();
        
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

        $final_output = ob_get_contents();  // get buffer content
        ob_end_clean();
        