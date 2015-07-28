<?php

/*************************************
* Add/Edit page in admin area.
*************************************/

$wpcb_public = WPCB_Public::get_instance();
$wpcb = WPCB_Admin::get_instance();

if(isset($_GET['id'])){
    global $wpdb;
    $id = $_GET['id'];
}

if(isset($_GET['step'])){
    $step = $_GET['step'];
};

?>    
<div class="wrap wpcb_main">
    
    <h2><?php if(isset($id)) { echo __('Customize' , 'wp-conversion-boxes') . " : <em>".$wpcb->get_box_name($id)."</em> <input type='text' value='[wpcb id=\"".$id."\"]' disabled />";} else{ _e('Add New WP Conversion Box' , 'wp-conversion-boxes') ; } ?></h2>
    
    <?php 
    if(isset($id)){
    ?>
    
        <h2 class="nav-tab-wrapper">
            <a href="<?php echo admin_url( 'admin.php?page=' . $wpcb->wpcb_edit_slug )."&step=1&id=".$id; ?>" class="nav-tab <?php if((isset($step) && $step == 1) || !isset($step)) echo "nav-tab-active"; ?>"><?php _e('Step 1: Select Box Template' , 'wp-conversion-boxes'); ?></a>
            <a href="<?php echo admin_url( 'admin.php?page=' . $wpcb->wpcb_edit_slug )."&step=2&id=".$id; ?>" class="nav-tab <?php if(isset($step) && $step == 2) echo "nav-tab-active"; ?>"><?php _e('Step 2: Customize Box' , 'wp-conversion-boxes'); ?></a>
            <a href="<?php echo admin_url( 'admin.php?page=' . $wpcb->wpcb_edit_slug )."&step=3&id=".$id; ?>" class="nav-tab <?php if(isset($step) && $step == 3) echo "nav-tab-active"; ?>"><?php _e('Step 3: Box Settings' , 'wp-conversion-boxes'); ?></a>
        </h2>
    
    <?php    
    }  
    ?>
    
    <?php if($step != 2) : ?>
    
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
    
    <?php elseif($step == 2 && $id != ''): ?>
        
        <div class="wpcb_customizer_wrap">    

            <?php $wpcb->wpcb_edit_page_content($step, $id); ?>

        </div>
    
    <?php 
        else: 
            $wpcb->wpcb_edit_page_content(null,null); 
        endif; 
    ?>
    
</div>