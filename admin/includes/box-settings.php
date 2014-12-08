<?php

// Step 3

    $wpcb_the_row = $wpdb->get_row($wpdb->prepare("SELECT `box_name`,`box_type`,`box_settings` from $wpcb_tbl_name WHERE id = %s",array($id)));
    $box_name = stripslashes(esc_attr($wpcb_the_row->box_name));
    $box_type = $wpcb_the_row->box_type;
    $box_settings = unserialize($wpcb_the_row->box_settings);
    
    $upgrade_message = $this->upgrade_to_pro();
    
    $box_fade_in  = ((isset($box_settings['box_fade_in']) && $box_settings['box_fade_in'] == 1) ? 'checked' : '');
    $box_fade_in_time = (isset($box_settings['box_fade_in_time']) ? $box_settings['box_fade_in_time'] : '');
    $box_make_sticky = ((isset($box_settings['box_make_sticky']) && $box_settings['box_make_sticky'] == 1) ? 'checked' : '');
    
    echo (isset($_GET['success']) && $_GET['success'] == 1) ? "<div class='updated'><p>".__("Box customizations updated successfully! Save the final box settings below and you'll have the box ready to use.","wp-conversion-boxes") ."<a href='' style='float:right;' onclick='jQuery(this).parent().parent().fadeOut(300).hide();return false;'>". __('Close','wp-conversion-boxes') ."</a></p></div>" : "";
    
?>
    <div class="postbox">
        <h3><?php _e('Box Settings','wp-conversion-boxes'); ?></h3>
        <div class="inside">
            <table class="form-table">
                <tbody>
                    <tr>
                        <th scope="row"><label for=""><?php _e('Box Name','wp-conversion-boxes'); ?></label></th>
                        <td>
                            <input type="text" name="box_name" id="box_name" value="<?php echo $box_name; ?>"/>
                            <p class="wpcb_help_block"><?php _e('Change the name of this conversion box.','wp-conversion-boxes'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for=""><?php _e('Box Fade In/Out Effect','wp-conversion-boxes'); ?></label></th>
                        <td>
                            <label for="box_fade_in"><input type="checkbox" name="box_fade_in" id="box_fade_in" <?php echo $box_fade_in; ?>/> <?php _e('Enable Flash Effect. ','wp-conversion-boxes'); ?></label>
                            <label for="box_fade_in_time"> <?php _e('Fade In/Out duration:','wp-conversion-boxes'); ?> <input type="text" name="box_fade_in_time" id="box_fade_in_time" value="<?php echo $box_fade_in_time; ?>" /> <?php _e('seconds.','wp-conversion-boxes'); ?></label>
                            <p class="wpcb_help_block"><?php _e("Give a cool fade in/out (flash) effect to the box so that it grabs user's attention.",'wp-conversion-boxes'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for=""><?php _e('Make Box Sticky','wp-conversion-boxes'); ?></label></th>
                        <td>
                            <label for="make_sticky"><input type="checkbox" name="make_sticky" id="make_sticky"  <?php echo $box_make_sticky; ?> /><?php _e('Make Sticky','wp-conversion-boxes'); ?></label>
                            <p class="wpcb_help_block"><?php _e('Make the box stick to top when user scrolls past the box.','wp-conversion-boxes'); ?></p>
                        </td>
                    </tr>                        
                </tbody>
            </table>            
        </div>
    </div>

    
    <?php if(isset($box_type) && ($box_type == 1 || $box_type == 2)){ ?>
        
        <div id="postbox" class="opaque5">
            <div class='postbox'>
                <h3><?php _e('Optin Form Settings','wp-conversion-boxes'); echo $upgrade_message; ?></h3>
                <div class='inside'>
                    <p><?php _e('Following are the messages that the visitor sees after submitting the optin form.','wp-conversion-boxes'); ?></p>
                    <table class="form-table">
                        <tbody>
                            <tr>
                                <th scope="row"><label for=""><?php _e('Processing Texts','wp-conversion-boxes'); ?></label></th>
                                <td>
                                    <label><?php _e('Processing Headline:','wp-conversion-boxes'); ?> <input type="text" value="Processing... Please Wait!" class="wpcb_fullwidth" disabled></label><br />
                                    <label><?php _e('When taking too long:','wp-conversion-boxes'); ?> <input type="text" value="It's taking longer than usual. Please hang on for a few moments..." class="wpcb_fullwidth" disabled></label>
                                </td>    
                            </tr>
                            <tr>
                                <th scope="row"><label for=""><?php _e('Success Texts','wp-conversion-boxes'); ?></label></th>
                                <td>
                                    <label><?php _e('Success Headline:','wp-conversion-boxes'); ?> <input type="text" value="Success!" class="wpcb_fullwidth" disabled></label><br />
                                    <label><?php _e('Success Description:','wp-conversion-boxes'); ?> <input type="text" value="Thanks for subscribing!" class="wpcb_fullwidth" disabled></label>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label for=""><?php _e('Error Texts','wp-conversion-boxes'); ?></label></th>
                                <td>
                                    <label><?php _e('Error Headline:','wp-conversion-boxes'); ?> <input type="text" value="Error!" class="wpcb_fullwidth" disabled></label><br />
                                    <label><?php _e('Error Description:','wp-conversion-boxes'); ?> <input type="text" value="There was an error submitting your info." class="wpcb_fullwidth" disabled></label>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    
    <?php } ?>
    
    <div class="wpcb_nav_buttons_step_3">
        <input type="submit" box_id="<?php echo $id; ?>" value="<?php _e('Save and Publish!','wp-conversion-boxes'); ?>" class="button button-primary" name="update-box-settings" id="update-box-settings"/>
    </div>


<?php

    $checked_1 = ($id == get_option('wpcb_default_box')) ? "checked" : "1";
    $checked_2 = ($id == get_option('wpcb_all_posts')) ? "checked" : "2";
    $checked_3 = ($id == get_option('wpcb_all_pages')) ? "checked" : "3";

    if($checked_1 == $checked_2 || $checked_1 == $checked_3 || $checked_2 == $checked_3){
        $checked_0 = "checked";
    }
    else{
        $checked_0 = "";
    }
    
?>

    <div class="wpcb_after_finish">
        <div id="wpcb_after_finish_head">
            <h1><?php _e('Conversion Box Created Successfully!','wp-conversion-boxes'); ?></h1>
            <span class="fa fa-close wpcb_publish_close" style="position: absolute; top: 20px; right: 20px; cursor: pointer;" onclick="jQuery(this).parent().trigger('close');"></span>
        </div>
        <div id="wpcb_after_finish_body">
            <p><?php _e('Now you can place this box on your blog using the following placement options:','wp-conversion-boxes'); ?></p>
            <table class="form-table">
                <tbody>
                    <tr>
                        <th scope="row" style="width: 200px;"><label for=""><?php _e('Global Placement :','wp-conversion-boxes'); ?></label></th>
                        <td>
                            <ul>
                                <li><label><input type="radio" name="wpcb_gloabal_placement" id="wpcb_gloabal_placement" value="1" <?php echo $checked_1 ?>><?php _e('Place this box under all Posts and Pages (Sitewide).','wp-conversion-boxes'); ?></label></li>
                                <li><label><input type="radio" name="wpcb_gloabal_placement" id="wpcb_gloabal_placement" value="2" <?php echo $checked_2 ?>><?php _e('Place this box under all Posts.','wp-conversion-boxes'); ?></label></li>
                                <li><label><input type="radio" name="wpcb_gloabal_placement" id="wpcb_gloabal_placement" value="3" <?php echo $checked_3 ?>><?php _e('Place this box under all Pages.','wp-conversion-boxes'); ?></label></li>
                                <li><label><input type="radio" name="wpcb_gloabal_placement" id="wpcb_gloabal_placement" value="0" <?php echo $checked_0 ?>><?php _e('Custom. (Keep this selected if you want to do custom placement using options below.)','wp-conversion-boxes'); ?></label></li>
                            </ul>
                            <p class="description"><?php echo sprintf( __("Place this box globally under all posts and pages. You can change this anytime in future by coming back to this page or using the <b>Sitewide Settings</b> on the <a target='_blank' href='%s'>Global Settings</a> page.",'wp-conversion-boxes') , admin_url( 'admin.php?page=' . $this->wpcb_settings_slug )); ?></p>
                        </td>    
                    </tr>
                    <tr>
                        <th scope="row" style="width: 200px;"><label for=""><?php _e('Shortcode :','wp-conversion-boxes'); ?></label></th>
                        <td>
                            <p><input type='text' value='[wpcb id="<?php echo $id; ?>"]' /></p>
                            <p class="description"><?php echo sprintf( __("You can place this box almost anywhere on your blog using this shortcode. If you want to use this box inside your theme you can do so by pasting the following code there: <code>&lt;?php echo do_shortcode('[wpcb id=\"%d\"]'); ?&gt;</code>",'wp-conversion-boxes') , $id); ?></p>
                        </td>    
                    </tr>                    
                    <tr class="opaque5">
                        <th scope="row" style="width: 200px;"><label for=""><?php _e('Category Wise Placement :','wp-conversion-boxes'); echo $upgrade_message;  ?></label></th>
                        <td>
                            <?php $this->checklist_of_categories(); //Box ID to tick the checked box. ?> 
                            <p class="description"><?php _e("Place this box globally under the posts of above selected categories. You can also change this anytime using <b>Conversion Boxes For Categories</b> section on the <b>Global Settings</b> page.",'wp-conversion-boxes'); ?></p>
                            <p class="description"><?php _e("<b>NOTE:</b> Selecting categories above will override Global Placement settings for the selected categories.",'wp-conversion-boxes'); ?></p>
                        </td>    
                    </tr>
                    <tr class="opaque5">
                        <th scope="row" style="width: 200px;"><label for=""><?php _e('Post/Page Specific Placement :','wp-conversion-boxes'); echo $upgrade_message; ?></label></th>
                        <td>
                            <p><input type="text" placeholder="42,122,221" class="wpcb_fullwidth" disabled/></p>
                            <p class="description"><?php _e("Place this box under above entered comma-separated list of post ID's (e.g. 42,122,221). You can also change this anytime directly from any post's edit page using the <b>WP Conversion Boxes Pro</b> meta box. From the dropdown list of conversion boxes select this box and hit the Save/Update button.",'wp-conversion-boxes'); ?></p>
                            <p class="description"><?php _e("<b>NOTE:</b> Entering post ID's above will override Global and Category Specific Placement settings for those post ID's.",'wp-conversion-boxes'); ?></p>
                        </td>    
                    </tr>
                    <tr class="opaque5">
                        <th scope="row" style="width: 200px;"><label for=""><?php _e('Sidebar Placement :','wp-conversion-boxes'); echo $upgrade_message; ?></label></th>
                        <td>
                            <p class="description"><?php _e("You can also place this box in your sidebars using our widget on the <b>Widgets</b> page.",'wp-conversion-boxes'); ?></p>
                        </td>    
                    </tr>
                </tbody>
            </table>
        </div>
        <div id="wpcb_after_finish_foot">
            <a class="button button-primary" style="float: left;" href="<?php echo admin_url( 'admin.php?page=' . $this->wpcb_edit_slug ); ?>"><?php _e('Create Another Box','wp-conversion-boxes'); ?></a>
            <button class="button button-primary wpcb_publish_now" data-boxid="<?php echo $id ?>"><?php _e('Publish Now!','wp-conversion-boxes'); ?></button>
            <button class="button button-primary wpcb_publish_close" onclick="jQuery(this).parent().trigger('close');window.location.href='<?php echo admin_url( 'admin.php?page=' . $this->wpcb_main_slug ); ?>'"><?php _e('Later','wp-conversion-boxes'); ?></button>
        </div>
    </div>