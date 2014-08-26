<?php

// Step 2
    
    $wpcb_the_row = $wpdb->get_row($wpdb->prepare("SELECT `box_type`,`box_template`,`box_customizations`  from $wpcb_tbl_name WHERE id = $id",array('%s','%d')));
    $box_type = $wpcb_the_row->box_type;
    $box_template = $wpcb_the_row->box_template;
    $box_customizations = unserialize($wpcb_the_row->box_customizations);
    
    if($id and $box_type != null and $box_template != null){
        // Editing is on
?>

    <?php 
            // Will show two meta boxes : Template design and settings
            $this->include_the_template_and_settings($box_type, $box_template, $box_customizations, $id); 
        
    ?>
    
    <div class="wpcb_nav_buttons_step_2">
        <input type="submit" box_id="<?php echo $id; ?>" value="Update" class="button button-primary" name="update-box-customizations" id="update-box-customizations"/>
        <button box_id="<?php echo $id; ?>" class="button button-primary" id="restore-to-default">Reset</button>
    </div>
<?php

    }
    else{
        echo "<p>No box template selected yet. Please select a box template first to customize it.</p>";
    }
?>