<?php

/*************************************
* Add/Edit page in admin area.
*************************************/

$wpcb_license = WPCB_Licensing::get_instance();

if(!$wpcb_license->is_license_valid()){
    $wpcb_license->license_activation_form();
}
else{
    $wpcb_license->license_renew_notice();
    $wpcb =  WPCB_Admin::get_instance();
    $wpcb_public = WPCB_Public::get_instance();
    if(isset($_GET['id'])){
    global $wpdb;
    $id = $_GET['id'];
    $wpcb_tbl_name = $wpcb_public->get_boxes_table_name();
    $wpcb_test_with = $wpdb->get_row($wpdb->prepare("SELECT test_enabled,test_with from $wpcb_tbl_name WHERE id = %d", array($id)));
    if($wpcb_test_with->test_enabled == 1 && $wpcb_test_with->test_with == 0){
        $wpcb_show_shortcode = '';
    }
    else{
        $wpcb_show_shortcode = "<input type='text' value='[wpcb id=\"".$id."\"]' disabled />";
    }
}

if(isset($_GET['step'])){
    $step = $_GET['step'];
};

?>    
<div class="wrap wpcb-wrapper">
    
    <h2><?php if(isset($id)) { echo __('Customize' , 'wp-conversion-boxes-pro') . " : <em>".$wpcb->get_box_name($id)."</em> <input type='text' value='[wpcb id=\"".$id."\"]' disabled />";} else{ _e('Add New WP Conversion Box' , 'wp-conversion-boxes-pro') ; } ?></h2>
    
    <?php 
    if(isset($id)){
    ?>
    
        <h2 class="nav-tab-wrapper">
            <a href="<?php echo admin_url( 'admin.php?page=' . $wpcb->wpcb_edit_slug )."&step=1&id=".$id; ?>" class="nav-tab <?php if((isset($step) && $step == 1) || !isset($step)) echo "nav-tab-active"; ?>"><?php _e('Step 1: Select Box Template' , 'wp-conversion-boxes-pro'); ?></a>
            <a href="<?php echo admin_url( 'admin.php?page=' . $wpcb->wpcb_edit_slug )."&step=2&id=".$id; ?>" class="nav-tab <?php if(isset($step) && $step == 2) echo "nav-tab-active"; ?>"><?php _e('Step 2: Customize Box' , 'wp-conversion-boxes-pro'); ?></a>
            <a href="<?php echo admin_url( 'admin.php?page=' . $wpcb->wpcb_edit_slug )."&step=3&id=".$id; ?>" class="nav-tab <?php if(isset($step) && $step == 3) echo "nav-tab-active"; ?>"><?php _e('Step 3: Box Settings' , 'wp-conversion-boxes-pro'); ?></a>
        </h2>
    
    <?php    
    }  
    ?>
    
    
    <div id="poststuff" class="metabox-holder has-right-sidebar">    

        <div class="inner-sidebar" id="side-info-column">
            <?php $wpcb->wpcb_sidebar(); ?>
        </div>

        <div id="post-body-content">
                <?php 
                    if(!isset($id)){
                        $wpcb->wpcb_edit_page_content(null,null); 
                    }  
                    else{
                        $wpcb->wpcb_edit_page_content($step, $id); 
                    }
                ?>
        </div>
        
    </div>
    
</div>

<?php } ?>