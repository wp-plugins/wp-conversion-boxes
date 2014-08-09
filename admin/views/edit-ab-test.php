<?php   

/*************************************
* A/B Tests listing page.
*************************************/

$wpcb =  WPCB_Admin::get_instance();
$upgrade_message = $wpcb->upgrade_to_pro();

?>

<div class="wrap">
    <div class="error"><p>A/B Testing feature is not available in free version of the plugin. <?= $upgrade_message; ?></p></div>
    <h2><?php echo esc_html( get_admin_page_title() ); ?></h2> 
    <div id="poststuff" class="metabox-holder has-right-sidebar">
    
        <div class="inner-sidebar" id="side-info-column">
            <?php $wpcb->wpcb_sidebar(); ?>
        </div>

        <div id="post-body-content" class="opaque6">
            <p>Enter a name for your new A/B Test:</p>
            <input type='text' name='wpcb_test_name' id='wpcb_test_name' class='regular-text' disabled="disabled"><br /><br />
            <input type='submit' name='wpcb_create_test' id='wpcb_create_test' value='Create Test and Proceed' class='button button-primary' disabled="disabled">
        </div>
    
    </div> 

</div>