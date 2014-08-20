<?php   

/*************************************
* A/B Tests listing page.
*************************************/

$wpcb =  WPCB_Admin::get_instance();
$upgrade_message = $wpcb->upgrade_to_pro();

?>

<div class="wrap">
    <div class="error"><p>A/B Testing feature is not available in free version of the plugin. <?= $upgrade_message; ?></p></div>
    <h2><?php echo esc_html( get_admin_page_title() ); ?> <span class="add-new-h2" style="cursor: pointer;">Add New</span></h2> 
    <div id="poststuff" class="metabox-holder has-right-sidebar">
    
        <div class="inner-sidebar" id="side-info-column">
            <?php $wpcb->wpcb_sidebar(); ?>
        </div>

        <div id="post-body-content" class="opaque6">
            No A/B tests found. Please <b style="cursor: pointer;">click here</b> to create a new A/B Test.
        </div>
    
    </div> 

</div>