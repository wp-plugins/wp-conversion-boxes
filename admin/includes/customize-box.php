<?php

$wpcb_public = WPCB_Public::get_instance();

// Step 2
    
    $wpcb_the_row = $wpdb->get_row($wpdb->prepare("SELECT box_type,box_template,box_customizations,is_custom_template  from $wpcb_tbl_name WHERE id = %d",array($id)));
    $box_type = $wpcb_the_row->box_type;
    $box_template = $wpcb_the_row->box_template;
    $is_custom_template = $wpcb_the_row->is_custom_template;
    if($wpcb_the_row->box_customizations != null && $wpcb_the_row->box_customizations != 'defaults'){
        $box_customizations = unserialize($wpcb_the_row->box_customizations);
        $box_customizations = $wpcb_public->sanitise_array($box_customizations);
        $box_customizations['defaults'] = 'custom';
    }    
    else{
        $box_customizations['defaults'] = 'defaults';
    }
    
    if($id and $box_type != null and $box_template != null){
        // Editing is on
?>

    <?php   
            echo (isset($_GET['success']) && $_GET['success'] == 1) ? "<div class='updated'><p>". __('Box template selected successfully! Use the options given below to customize and craft the box according to your needs.','wp-conversion-boxes-pro'). "<a href='' style='float:right;' onclick='jQuery(this).parent().parent().fadeOut(300).hide();return false;'>". __('Close','wp-conversion-boxes-pro'). "</a></p></div>" : "";
            // Will show two meta boxes : Template design and settings
            $this->include_the_template_and_settings($box_type, $box_template, $box_customizations, $id , $is_custom_template); 
    ?>
    
    

    <div class="wpcb_nav_buttons_step_2">
        <input type="submit" box_id="<?php echo $id; ?>" value="<?php _e('Save and Next','wp-conversion-boxes-pro'); ?>" class="button button-primary" name="update-box-customizations" id="update-box-customizations"/>
        <button box_id="<?php echo $id; ?>" class="button button-primary" id="restore-to-default"><?php _e('Reset','wp-conversion-boxes-pro'); ?></button>
    </div>
<?php

    }
    else{
        echo "<p>". __('No box template selected yet. Please select a box template first to customize it.','wp-conversion-boxes-pro'). "</p>";
    }
?>