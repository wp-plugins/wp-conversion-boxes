<?php

// Step 3

    $wpcb_the_row = $wpdb->get_row($wpdb->prepare("SELECT `box_settings` from $wpcb_tbl_name WHERE id = $id",array('%s')));
    $box_settings = unserialize($wpcb_the_row->box_settings);
    
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
                        <th scope="row"><label for="">Box Fade In/Out Effect</label></th>
                        <td>
                            <label for="box_fade_in"><input type="checkbox" name="box_fade_in" id="box_fade_in" <?= $box_fade_in; ?>/> Enable Fade-in Effect. </label>
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
    
    
    <div class="wpcb_nav_buttons_step_3">
        <input type="submit" box_id="<?php echo $id; ?>" value="Update" class="button button-primary" name="update-box-settings" id="update-box-settings"/>
    </div>