<?php

$wpcb_public = WPCB_Public::get_instance();

// Step 2
    
    $wpcb_the_row = $wpdb->get_row($wpdb->prepare("SELECT `box_type`,`box_template`,`box_customizations`  from $wpcb_tbl_name WHERE id = %d",array($id)));
    $box_type = $wpcb_the_row->box_type;
    $box_template = $wpcb_the_row->box_template;
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
        // Will show two meta boxes : Template design and customizations
        $this->include_the_template_and_settings($box_type, $box_template, $box_customizations, $id); 
    }
    else{
        echo "<p>". __('No box template selected yet. Please select a box template first to customize it.','wp-conversion-boxes'). "</p>";
    }
?>