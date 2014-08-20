<?php   

/*************************************
* Main admin page. Lists all boxes.
*************************************/

$wpcb =  WPCB_Admin::get_instance();
?>

<div class="wrap">
    <h2><?php echo esc_html( get_admin_page_title() ); ?> <a href='<?php echo admin_url( 'admin.php?page=' . $this->wpcb_edit_slug ); ?>' class="add-new-h2">Add New</a></h2>
    
    <div id="poststuff" class="metabox-holder has-right-sidebar">
    
        <div class="inner-sidebar" id="side-info-column">
            <?php $wpcb->wpcb_sidebar(); ?>
        </div>

        <div id="post-body-content">
            <?php $wpcb->wpcb_show_boxes_list(); ?>
        </div>
    
    </div> 

</div>