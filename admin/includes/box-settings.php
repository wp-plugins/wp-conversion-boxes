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
    
    $wpcb_popup_type_radio = (isset($box_settings['wpcb_popup_type_radio'])) ? $box_settings['wpcb_popup_type_radio'] : 0;
    $wpcb_popup_option_val = (isset($box_settings['wpcb_popup_option_val'])) ? $box_settings['wpcb_popup_option_val'] : 0;
    $wpcb_popup_frequency = (isset($box_settings['wpcb_popup_frequency'])) ? $box_settings['wpcb_popup_frequency'] : 10;
    
    switch($wpcb_popup_type_radio){
        case '1' :  $wpcb_popup_duration = $wpcb_popup_option_val;
                    $wpcb_popup_scroll_percentage = 0;
                    break;
        case '3' :  $wpcb_popup_duration = 0;
                    $wpcb_popup_scroll_percentage = $wpcb_popup_option_val;
                    break;
        default :   $wpcb_popup_duration = 0;
                    $wpcb_popup_scroll_percentage = 0;
                    break;
    }
    
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
                    <?php if($box_type != 5 && $box_type != 6) : ?>
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
                    <?php endif; ?>
                    
                    <?php if($box_type == 6) : ?>
                    <tr>
                        <th scope="row"><label for=""><?php _e('How to Show this Smart Popup?','wp-conversion-boxes'); ?></label></th>
                        <td>
                            <div class='wpcb_popup_type'>
                                <input type="radio" class="wpcb_popup_type_radio" id="wpcb_popup_1" name="wpcb_popup_type" value="1" <?php echo "checked"; ?>>
                                    <label for="wpcb_popup_1" id="wpcb_popup_1_label"><?php _e('Timed Popup','wp-conversion-boxes'); ?></label>
                                 <input type="radio" class="wpcb_popup_type_radio" id="wpcb_popup_2" name="wpcb_popup_type" value="2" <?php if($wpcb_popup_type_radio == '2') echo "checked"; ?>>
                                    <label for="wpcb_popup_2" id="wpcb_popup_2_label"><?php _e('Exit Popup','wp-conversion-boxes'); ?></label>  
                                 <input type="radio" class="wpcb_popup_type_radio" id="wpcb_popup_3" name="wpcb_popup_type" value="3" <?php if($wpcb_popup_type_radio == '3') echo "checked";?>>
                                    <label for="wpcb_popup_3" id="wpcb_popup_3_label"><?php _e('Scroll-triggered Popup','wp-conversion-boxes'); ?></label>
                            </div>    
                                
                            <div class="wpcb_popup_div wpcb_popup_type_1">
                                <label for="wpcb_popup_duration"> <?php _e('Trigger popup after ','wp-conversion-boxes'); ?> <input style="width: 50px;" type="number" name="wpcb_popup_duration" id="wpcb_popup_duration" value="<?php echo $wpcb_popup_duration; ?>" /> <?php _e('seconds of the user visit.','wp-conversion-boxes'); ?></label>
                                <p class="wpcb_help_block"><?php _e("Enter duration in seconds (eg. <b>10</b> seconds) after which the popup should be triggered and shown to the visitor.",'wp-conversion-boxes'); ?></p>
                            </div>
                            <div class="wpcb_popup_div wpcb_popup_type_2">
                                <p class="wpcb_help_block"><?php _e("Popup will be triggered when someone tries to leave the website.",'wp-conversion-boxes'); ?></p>
                            </div>
                            <div class="wpcb_popup_div wpcb_popup_type_3">
                                <label for="wpcb_popup_scroll_percentage"> <?php _e('Trigger popup after user scrolls ','wp-conversion-boxes'); ?> <input style="width: 50px;" type="number" name="wpcb_popup_scroll_percentage" id="wpcb_popup_scroll_percentage" value="<?php echo $wpcb_popup_scroll_percentage; ?>" /> <?php _e('% of the web page.','wp-conversion-boxes'); ?></label>
                                <p class="wpcb_help_block"><?php _e("Enter value in percentage (0-100).",'wp-conversion-boxes'); ?></p>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for=""><?php _e('Smart Popup Frequency','wp-conversion-boxes'); ?></label></th>
                        <td>
                            <label for="wpcb_popup_frequency"><input style="width: 50px;" type="number" name="wpcb_popup_frequency" id="wpcb_popup_frequency" value="<?php echo $wpcb_popup_frequency; ?>" /> <?php _e('days.','wp-conversion-boxes'); ?></label>
                            <p class="wpcb_help_block"><?php _e("Enter number of days (0-100) after which the popup will be shown again to the user after the first visit. Keep value 0 to show popup on every page view. Default value : 10 days.",'wp-conversion-boxes'); ?></p>
                        </td>    
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>            
        </div>
    </div>

    
    <?php if(isset($box_type) && ($box_type == 1 || $box_type == 2 || $box_type == 5 || $box_type == 6)){ ?>
        
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
            <h1><?php ($box_type != 6) ? _e('Conversion Box Created Successfully!','wp-conversion-boxes') : _e('Smart Popup Created Successfully!','wp-conversion-boxes'); ?></h1>
            <span class="fa fa-close wpcb_publish_close" style="position: absolute; top: 20px; right: 20px; cursor: pointer;" onclick="jQuery(this).parent().trigger('close');"></span>
        </div>
        <div id="wpcb_after_finish_body">
            
            <?php if($box_type != 5) : ?>
            
                <p><?php _e('Now you can place this box on your blog using the following placement options:','wp-conversion-boxes'); ?></p>
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row" style="width: 200px;"><label for=""><?php _e('Global Placement :','wp-conversion-boxes'); ?></label></th>
                            <td>
                                <ul>
                                    <li><label><input type="radio" name="wpcb_gloabal_placement" id="wpcb_gloabal_placement" value="1" <?php echo $checked_1 ?>><?php ($box_type != 6) ? _e('Place this box under all Posts and Pages (Sitewide).','wp-conversion-boxes') : _e('Show this Smart Popup on all Posts and Pages (Sitewide).','wp-conversion-boxes'); ?></label></li>
                                    <li><label><input type="radio" name="wpcb_gloabal_placement" id="wpcb_gloabal_placement" value="2" <?php echo $checked_2 ?>><?php ($box_type != 6) ? _e('Place this box under all Posts.','wp-conversion-boxes') : _e('Show this Smart Popup on all Posts.','wp-conversion-boxes'); ?></label></li>
                                    <li><label><input type="radio" name="wpcb_gloabal_placement" id="wpcb_gloabal_placement" value="3" <?php echo $checked_3 ?>><?php ($box_type != 6) ? _e('Place this box under all Pages.','wp-conversion-boxes') : _e('Show this Smart Popup on all Pages.','wp-conversion-boxes'); ?></label></li>
                                    <li><label><input type="radio" name="wpcb_gloabal_placement" id="wpcb_gloabal_placement" value="0" <?php echo $checked_0 ?>><?php _e('Custom. (Keep this selected if you want to do custom placement using options below.)','wp-conversion-boxes'); ?></label></li>
                                </ul>
                                <p class="description">
                                    <?php echo ($box_type != 6) ? sprintf( __("Place this box globally under all posts and pages. You can change this anytime in future by coming back to this page or using the <b>Sitewide Settings</b> on the <a target='_blank' href='%s'>Global Settings</a> page.",'wp-conversion-boxes') , admin_url( 'admin.php?page=' . $this->wpcb_settings_slug ))
                                            : sprintf( __("Show this Smart Popup globally on all posts and pages. You can change this anytime in future by coming back to this page or using the <b>Sitewide Settings</b> on the <a target='_blank' href='%s'>Global Settings</a> page.",'wp-conversion-boxes') , admin_url( 'admin.php?page=' . $this->wpcb_settings_slug ) ) ; ?>
                            </td>    
                        </tr>
                        <tr>
                            <th scope="row" style="width: 200px;"><label for=""><?php _e('Shortcode :','wp-conversion-boxes'); ?></label></th>
                            <td>
                                <p><input type='text' value='[wpcb id="<?php echo $id; ?>"]' /></p>
                                <p class="description"><?php echo ($box_type != 6) ? sprintf( __("You can place this box almost anywhere on your blog using this shortcode. If you want to use this box inside your theme you can do so by pasting the following code there: <code>&lt;?php echo do_shortcode('[wpcb id=\"%d\"]'); ?&gt;</code>",'wp-conversion-boxes') , $id)
                                    : sprintf( __("Show this popup on any page/post by using this shortcode. To put it inside theme template use this code: <code>&lt;?php echo do_shortcode('[wpcb id=\"%d\"]'); ?&gt;</code>",'wp-conversion-boxes') , $id); ?></p>
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
                        <?php if($box_type != 6) : ?>
                        <tr class="opaque5">
                            <th scope="row" style="width: 200px;"><label for=""><?php _e('Sidebar Placement :','wp-conversion-boxes'); echo $upgrade_message; ?></label></th>
                            <td>
                                <p class="description"><?php _e("You can also place this box in your sidebars using our widget on the <b>Widgets</b> page.",'wp-conversion-boxes'); ?></p>
                            </td>    
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            
            <?php else : ?>
            
                <p><?php _e('Now you can place this 2-Step Optin Link on your blog using a shortcode. Below you can customize how your Optin Link should look like. Put the shortcode in your content where you want to show this Optin Link. Clicking this link will show the email optin box in popup that you customized in Step 2.','wp-conversion-boxes'); ?></p>
                <h3>Shortcode Generator: </h3>
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row" style="width: 200px;"><label for=""><?php _e('Optin Link Text','wp-conversion-boxes'); ?></label></th>
                            <td>
                                <p><input type='text' value='Click Here' id="two_step_toptin_link_text" /></p>
                            </td>    
                        </tr>
                        <tr>
                            <th scope="row" style="width: 200px;"><label for=""><?php _e('Optin Link Style','wp-conversion-boxes'); ?></label></th>
                            <td>
                                <p>
                                    <select id="two-step-optin-link-style">
                                        <option value="none">No Style</option>
                                        <option value="image">Use Image</option>
                                        <option value="blue_flat">Blue Flat</option>
                                        <option value="blue_gradient">Blue Gradient</option>
                                        <option value="blue_3d">Blue 3D</option>
                                        <option value="black_flat">Black Flat</option>
                                        <option value="black_gradient">Black Gradient</option>
                                        <option value="green_flat">Green Flat</option>
                                        <option value="green_gradient">Green Gradient</option>
                                        <option value="red_flat">Red Flat</option>
                                        <option value="red_gradient">Red Gradient</option>
                                        <option value="voilet_flat">Voilet Flat</option>
                                        <option value="voilet_gradient">Voilet Gradient</option>
                                        <option value="orange_flat">Orange Flat</option>
                                        <option value="orange_gradient">Orange Gradient</option>
                                        <option value="yellow_flat">Yellow Flat</option>
                                        <option value="yellow_gradient">Yellow Gradient</option>
                                    </select>
                                </p>
                                <p id="two_step_img">
                                    <input id="two_step_image_url" type="text" name="image_url" placeholder="http://" value="" /> 
                                    <input id="two_step_wpcb_img_upload" align="right" class="button" type="button" value="<?php _e('Upload Image','wp-conversion-boxes'); ?>" />
                                </p>
                            </td>    
                        </tr>
                        <tr>
                            <th scope="row" style="width: 200px;"><label for=""><?php _e('Optin Link Preview :','wp-conversion-boxes'); ?></label></th>
                            <td>
                                <a id="wpcb_two_step_optin_link">Click Here</a>
                            </td>    
                        </tr>
                        <tr>
                            <th scope="row" style="width: 200px;"><label for=""><?php _e('Generated Shortcode :','wp-conversion-boxes'); ?></label></th>
                            <td>
                                <p>
                                    <code class="wpcb_shortcode_code">[wpcb id="<?php echo $id; ?>"<span id="wpcb_shortcode_text"> text="Click Here"</span><span id="wpcb_shortcode_style"> style="none"</span><span id="wpcb_shortcode_img_url"></span>]</code>
                                    <input id="wpcb_shortcode_input" style="width: 100%;" />
                                </p>
                                <p class="description"><?php echo sprintf( __("You can place this 2-Step Optin Link almost anywhere on your blog using this shortcode.",'wp-conversion-boxes') , $id); ?></p>
                            </td>    
                        </tr>
                    </tbody>
                </table>
                
            <?php endif; ?>
            
        </div>
        <div id="wpcb_after_finish_foot">
            <a class="button button-primary" style="float: left;" href="<?php echo admin_url( 'admin.php?page=' . $this->wpcb_edit_slug ); ?>"><?php _e('Create Another Box','wp-conversion-boxes'); ?></a>
            <?php if($box_type != 5) : ?>
                <button class="button button-primary wpcb_publish_now" data-boxid="<?php echo $id ?>"><?php _e('Publish Now!','wp-conversion-boxes'); ?></button>
                <button class="button button-primary wpcb_publish_close" onclick="jQuery(this).parent().trigger('close');window.location.href='<?php echo admin_url( 'admin.php?page=' . $this->wpcb_main_slug ); ?>'"><?php _e('Later','wp-conversion-boxes'); ?></button>
            <?php else: ?>
                <button class="button button-primary wpcb_publish_close" onclick="jQuery(this).parent().trigger('close');window.location.href='<?php echo admin_url( 'admin.php?page=' . $this->wpcb_main_slug ); ?>'"><?php _e('Done','wp-conversion-boxes'); ?></button>
            <?php endif; ?>
        </div>
    </div>