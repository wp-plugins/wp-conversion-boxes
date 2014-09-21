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
    
    echo (isset($_GET['success']) && $_GET['success'] == 1) ? "<div class='updated'><p>Box customizations updated successfully! Save the final box settings below and you'll have the box ready to use.<a href='' style='float:right;' onclick='jQuery(this).parent().parent().fadeOut(300).hide();return false;'>Close</a></p></div>" : "";
    
?>
    <div class="postbox">
        <h3>Box Settings</h3>
        <div class="inside">
            <table class="form-table">
                <tbody>
                    <tr>
                        <th scope="row"><label for="">Box Name</label></th>
                        <td>
                            <input type="text" name="box_name" id="box_name" value="<?= $box_name; ?>"/>
                            <p class="wpcb_help_block">Change the name of this conversion box.</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="">Box Fade In/Out Effect</label></th>
                        <td>
                            <label for="box_fade_in"><input type="checkbox" name="box_fade_in" id="box_fade_in" <?= $box_fade_in; ?>/> Enable Flash Effect. </label>
                            <label for="box_fade_in_time"> Fade In/Out duration: <input type="text" name="box_fade_in_time" id="box_fade_in_time" value="<?= $box_fade_in_time; ?>" /> seconds.</label>
                            <p class="wpcb_help_block">Give a cool fade in/out (flash) effect to the box so that it grabs user's attention.</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="">Make Box Sticky</label></th>
                        <td>
                            <label for="make_sticky"><input type="checkbox" name="make_sticky" id="make_sticky"  <?= $box_make_sticky; ?> />Make Sticky</label>
                            <p class="wpcb_help_block">Make the box stick to top when user scrolls past the box.</p>
                        </td>
                    </tr>                        
                </tbody>
            </table>            
        </div>
    </div>

    
    <?php if(isset($box_type) && ($box_type == 1 || $box_type == 2)){ ?>
        
        <div id="postbox" class="opaque5">
            <div class='postbox'>
                <h3>Optin Form Settings<?= $upgrade_message; ?></h3>
                <div class='inside'>
                    <p>Following are the messages that the visitor sees after submitting the optin form.</p>
                    <table class="form-table">
                        <tbody>
                            <tr>
                                <th scope="row"><label for="">Processing Texts</label></th>
                                <td>
                                    <label>Processing Headline: <input type="text" value="Processing... Please Wait!" class="wpcb_fullwidth" disabled></label><br />
                                    <label>When taking too long: <input type="text" value="It's taking longer than usual. Please hang on for a few moments..." class="wpcb_fullwidth" disabled></label>
                                </td>    
                            </tr>
                            <tr>
                                <th scope="row"><label for="">Success Texts</label></th>
                                <td>
                                    <label>Success Headline: <input type="text" value="Success!" class="wpcb_fullwidth" disabled></label><br />
                                    <label>Success Description: <input type="text" value="Thanks for subscribing!" class="wpcb_fullwidth" disabled></label>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="">Error Texts</label></th>
                                <td>
                                    <label>Error Headline: <input type="text" value="Error!" class="wpcb_fullwidth" disabled></label><br />
                                    <label>Error Description: <input type="text" value="There was an error submitting your info." class="wpcb_fullwidth" disabled></label>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    
    <?php } ?>
    
    <div class="wpcb_nav_buttons_step_3">
        <input type="submit" box_id="<?php echo $id; ?>" value="Save and Publish" class="button button-primary" name="update-box-settings" id="update-box-settings"/>
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
            <h1>Conversion Box Created Successfully!</h1>
            <span class="fa fa-close wpcb_publish_close" style="position: absolute; top: 20px; right: 20px; cursor: pointer;" onclick="jQuery(this).parent().trigger('close');"></span>
        </div>
        <div id="wpcb_after_finish_body">
            <p>Now you can place this box on your blog using the following placement options:</p>
            <table class="form-table">
                <tbody>
                    <tr>
                        <th scope="row" style="width: 200px;"><label for="">Global Placement :</label></th>
                        <td>
                            <ul>
                                <li><label><input type="radio" name="wpcb_gloabal_placement" id="wpcb_gloabal_placement" value="1" <?= $checked_1 ?>>Place this box under all Posts and Pages (Sitewide).</label></li>
                                <li><label><input type="radio" name="wpcb_gloabal_placement" id="wpcb_gloabal_placement" value="2" <?= $checked_2 ?>>Place this box under all Posts.</label></li>
                                <li><label><input type="radio" name="wpcb_gloabal_placement" id="wpcb_gloabal_placement" value="3" <?= $checked_3 ?>>Place this box under all Pages.</label></li>
                                <li><label><input type="radio" name="wpcb_gloabal_placement" id="wpcb_gloabal_placement" value="0" <?= $checked_0 ?>>Custom. (Keep this selected this you want to do custom placement using options below.)</label></li>
                            </ul>
                            <p class="description">Place this box globally under all posts and pages. You can change this anytime in future by coming back to this page or using the <b>Sitewide Settings</b> on the <a target="_blank" href="<?php echo admin_url( 'admin.php?page=' . $this->wpcb_settings_slug ); ?>">Global Settings</a> page.</p>
                        </td>    
                    </tr>
                    <tr>
                        <th scope="row" style="width: 200px;"><label for="">Shortcode :</label></th>
                        <td>
                            <p><input type='text' value='[wpcb id="<?= $id; ?>"]' /></p>
                            <p class="description">You can place this box almost anywhere on your blog using this shortcode. If you want to use this box inside your theme you can do so by pasting the following code there: <code>&lt;?php echo do_shortcode('[wpcb id="<?= $id; ?>"]'); ?&gt;</code></p>
                        </td>    
                    </tr>                    
                    <tr class="opaque5">
                        <th scope="row" style="width: 200px;"><label for="">Category Wise Placement :<?= $upgrade_message; ?></label></th>
                        <td>
                            <?php $this->checklist_of_categories(); //Box ID to tick the checked box. ?> 
                            <p class="description">Place this box globally under the posts of above selected categories. You can also change this anytime using <b>Conversion Boxes For Categories</b> section on the <b>Global Settings</b> page.</p>
                            <p class="description"><b>NOTE:</b> Selecting categories above will override Global Placement settings for the selected categories.'</p>
                        </td>    
                    </tr>
                    <tr class="opaque5">
                        <th scope="row" style="width: 200px;"><label for="">Post/Page Specific Placement :<?= $upgrade_message; ?></label></th>
                        <td>
                            <p><input type="text" placeholder="42,122,221" class="wpcb_fullwidth" disabled/></p>
                            <p class="description">Place this box under above entered comma-separated list of post ID's (e.g. 42,122,221). You can also change this anytime directly from any post's edit page using the <b>WP Conversion Boxes Pro</b> meta box. From the dropdown list of conversion boxes select this box and hit the Save/Update button.</p>
                            <p class="description"><b>NOTE:</b> Entering post ID's above will override Global and Category Specific Placement settings for those post ID's.</p>
                        </td>    
                    </tr>
                    <tr class="opaque5">
                        <th scope="row" style="width: 200px;"><label for="">Sidebar Placement :<?= $upgrade_message; ?></label></th>
                        <td>
                            <p class="description">You can also place this box in your sidebars using our widget on the <b>Widgets</b> page.</p>
                        </td>    
                    </tr>
                </tbody>
            </table>
        </div>
        <div id="wpcb_after_finish_foot">
            <a class="button button-primary" style="float: left;" href="<?php echo admin_url( 'admin.php?page=' . $this->wpcb_edit_slug ); ?>">Create Another Box</a>
            <button class="button button-primary wpcb_publish_now" data-boxid="<?= $id ?>">Publish Now!</button>
            <button class="button button-primary wpcb_publish_close" onclick="jQuery(this).parent().trigger('close');window.location.href='<?php echo admin_url( 'admin.php?page=' . $this->wpcb_main_slug ); ?>'">Later</button>
        </div>
    </div>