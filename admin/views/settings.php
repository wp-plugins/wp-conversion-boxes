<?php

/*************************************
* Settings Page
*************************************/

$wpcb = WPCB_Admin::get_instance();
$upgrade_message = $wpcb->upgrade_to_pro();
                
?>

<div class="wrap wpcb_main">
    
    <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
    
    <div id="poststuff" class="metabox-holder has-right-sidebar">    

        <div class="inner-sidebar" id="side-info-column">
            <?php $wpcb->wpcb_sidebar(); ?>
        </div>

        <div id="post-body-content">
            <div class='postbox'>
                <h3>Default Conversion Box</h3>
                <div class='inside'>
                    <table class="form-table">
                        <tbody>
                            <tr>
                                <th scope="row"><label for=""> Select Conversion Box</label></th>
                                <td>
                                    <?php $wpcb->wpcb_box_list(get_option('wpcb_default_box'),'default','wpcb_boxes_list_default'); ?>
                                    <p class="wpcb_help_block">Assign a default conversion box to all pages and posts. This box will be used when no other box has been set for any post/page.</p>
                                </td>
                            </tr>
                            <tr class="opaque5">
                                <th scope="row"><label for="">A/B Test</label><?= $upgrade_message; ?></th>
                                <td>
                                    <label><input type='checkbox' disabled> Enable</label>
                                    <p class="wpcb_help_block">Use an A/B Test instead of a conversion box. Click Enable and select an A/B Test below.</p>
                                </td>
                            </tr>
                            <tr class="opaque5">
                                <th></th>
                                <td>
                                    <select disabled><option>None</option></select>
                                    <p class="wpcb_help_block">Select a default A/B Test for all pages and posts.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class='postbox'>
                <h3>Conversion Box for All Posts</h3>
                <div class='inside'>

                        <table class="form-table">
                            <tbody>
                                <tr>
                                    <th scope="row"><label for="">Select Conversion Box</label></th>
                                    <td>
                                        <?php $wpcb->wpcb_box_list(get_option('wpcb_all_posts'),'','wpcb_boxes_list_posts'); ?>
                                        <p class="wpcb_help_block">Select a conversion box that'll be shown under all Blog Posts. This will override the default conversion box.</p>
                                    </td>
                                </tr>
                                <tr class="opaque5">
                                    <th scope="row"><label for="">A/B Test</label><?= $upgrade_message; ?></th>
                                    <td>
                                        <label><input type='checkbox' disabled> Enable</label>
                                        <p class="wpcb_help_block">Use an A/B Test instead of a conversion box. Click Enable and select an A/B Test below.</p>
                                    </td>
                                </tr>
                                <tr class="opaque5">
                                    <th></th>
                                    <td>
                                        <select disabled><option>None</option></select>
                                        <p class="wpcb_help_block">Select an A/B Test that'll be shown under all Blog Posts.</p>
                                    </td>
                                </tr>
                            </tbody>
			</table>

                </div>
            </div>
            
            <div class='postbox'>
                <h3>Conversion Box for All Pages</h3>
                <div class='inside'>

                        <table class="form-table">
                            <tbody>
                                <tr>
                                    <th scope="row"><label for=""> Select Conversion Box</label></th>
                                    <td>
                                        <?php $wpcb->wpcb_box_list(get_option('wpcb_all_pages'),'','wpcb_boxes_list_pages'); ?>
                                            <p class="wpcb_help_block">Select a conversion box that'll be shown under all Pages of your site. This will override the default conversion box.</p>
                                    </td>
                                </tr>
                                <tr class="opaque5">
                                    <th scope="row"><label for="">A/B Test</label><?= $upgrade_message; ?></th>
                                    <td>
                                        <label><input type='checkbox' disabled> Enable</label>
                                        <p class="wpcb_help_block">Use an A/B Test instead of a conversion box. Click Enable and select an A/B Test below.</p>
                                    </td>
                                </tr class="opaque5">
                                <tr>
                                    <th></th>
                                    <td>
                                        <select disabled><option>None</option></select>
                                        <p class="wpcb_help_block">Select an A/B Test that'll be shown under all all Pages of your site.</p>
                                    </td>
                                </tr>                                
                            </tbody>
			</table>

                </div>
            </div>
            
            <div class="postbox opaque6">
                <h3>Conversion Boxes for Categories<?= $upgrade_message; ?></h3>
                <div class='inside'>
                        <table class="form-table">
                            <tbody>
                                <tr>
                                    <th scope="row"><label for="">Select Conversion Box</label></th>
                                    <td>
                                        <p class="wpcb_help_block">Select a conversion box for the posts of specific categories. This will override the Default Conversion Box and also Conversion Box for All Posts.</p>
                                    </td>
                                </tr>
                            </tbody>
			</table>
                        <div style="height: 150px; overflow: auto; border: 1px #ccc solid;">
                            <?php $wpcb->wpcb_category_wise_box_list(); ?>
                        </div>
                </div>
            </div>
            
            <input type="submit" value="Update" class="button button-primary" name="update-global-settings" id="update-global-settings"/>
            
        </div>
        
    </div>
    
</div>    