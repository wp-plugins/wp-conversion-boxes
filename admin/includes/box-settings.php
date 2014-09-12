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
        <input type="submit" box_id="<?php echo $id; ?>" value="Update" class="button button-primary" name="update-box-settings" id="update-box-settings"/>
    </div>