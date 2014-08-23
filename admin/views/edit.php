<?php

/*************************************
* Add/Edit page in admin area.
*************************************/


$version = WPCB_Public::VERSION;  
$wpcb_public = WPCB_Public::get_instance();
$wpcb = WPCB_Admin::get_instance();

if(isset($_GET['id'])){
    global $wpdb;
    $id = $_GET['id'];
    //$box_customizations = $wpcb_the_row->box_customizations;
    //$box_settings = $wpcb_the_row->box_settings;
    
    
}

if(isset($_GET['step'])){
    $step = $_GET['step'];
};

?>    
<div class="wrap wpcb_main">
    
    <h2><?php if($id != null) echo "Customize WP Conversion Box : <em>".$wpcb->get_box_name($id)."</em> <input type='text' value='[wpcb id=\"".$id."\"]' disabled />"; else echo "Add New WP Conversion Box"; ?></h2>
    
    <?php 
    if($id){
    ?>
    
        <h2 class="nav-tab-wrapper">
            <a href="<?php echo admin_url( 'admin.php?page=' . $wpcb->wpcb_edit_slug )."&step=1&id=".$id; ?>" class="nav-tab <?php if((isset($step) && $step == 1) || !isset($step)) echo "nav-tab-active"; ?>">Select Box Template</a>
            <a href="<?php echo admin_url( 'admin.php?page=' . $wpcb->wpcb_edit_slug )."&step=2&id=".$id; ?>" class="nav-tab <?php if(isset($step) && $step == 2) echo "nav-tab-active"; ?>">Customize Box</a>
            <a href="<?php echo admin_url( 'admin.php?page=' . $wpcb->wpcb_edit_slug )."&step=3&id=".$id; ?>" class="nav-tab <?php if(isset($step) && $step == 3) echo "nav-tab-active"; ?>">Box Settings</a>
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
                    if(!$id){
                        $wpcb->wpcb_edit_page_content(null,null); 
                    }  
                    else{
                        $wpcb->wpcb_edit_page_content($step, $id); 
                    }
                ?>
        </div>
        
    </div>
    
</div>