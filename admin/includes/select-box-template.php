<?php 

// Step 1 - Select Box Template

    $wpcb_the_row = $wpdb->get_row($wpdb->prepare("SELECT `box_type`,`box_template` from $wpcb_tbl_name WHERE id = %d",array($id)));
    $box_type = $wpcb_the_row->box_type;
    $box_template = $wpcb_the_row->box_template;

?>

    <p>Select the type of WP Conversion Box that you want to make:</p>    
    
    <div class='wpcb_box_type'>
        <input type="radio" class="wpcb_box_type_radio" id="wpcb_box_1" name="wpcb_box_type" value="1" <?php if($box_type == '1' || $box_type == null) echo "checked";?>>
           <label for="wpcb_box_1" id="wpcb_box_1_label">Email Optin Box (Beta)</label>
        <input type="radio" class="wpcb_box_type_radio" id="wpcb_box_2" name="wpcb_box_type" value="2" <?php if($box_type == '2') echo "checked"; ?>>
           <label for="wpcb_box_2" id="wpcb_box_2_label">Video Email Optin Box (Beta)</label>
        <input type="radio" class="wpcb_box_type_radio" id="wpcb_box_3" name="wpcb_box_type" value="3" <?php if($box_type == '3') echo "checked"; ?>>
           <label for="wpcb_box_3" id="wpcb_box_3_label">Call-to-action Box</label> 
        <input type="radio" class="wpcb_box_type_radio" id="wpcb_box_4" name="wpcb_box_type" value="4" <?php if($box_type == '4') echo "checked"; ?>>
           <label for="wpcb_box_4" id="wpcb_box_4_label">Video Call-to-action Box</label>    
    </div>

    <div class="wpcb_box_div wpcb_box_type_1">
        <div class="postbox">
            <h3>Email Optin Box</h3>
            <div class="inside minheight150">
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row"><label for="">Select a template</label></th>
                            <td>
                                <?php $this->wpcb_template_list($box_template, 1); ?>
                                <p class="wpcb_help_block">Select a template from above and hit Update.</p>
                            </td>
                        </tr>                        
                    </tbody>
                </table>
                <div class='wpcb_template_preview_1 wpcb_preview_image_center'></div>
            </div>
        </div>
    </div>
    <div class="wpcb_box_div wpcb_box_type_2">
        <div class="postbox">
            <h3>Video Email Optin Box</h3>
            <div class="inside minheight150">
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row"><label for="">Select a template</label></th>
                            <td>
                                <?php $this->wpcb_template_list($box_template, 2); ?>
                                <p class="wpcb_help_block">Select a template from above and hit Update.</p>
                            </td>
                        </tr>                        
                    </tbody>
                </table>
                <div class='wpcb_template_preview_2 wpcb_preview_image_center'></div>
            </div>
        </div>
    </div>
    <div class="wpcb_box_div wpcb_box_type_3">
        <div class="postbox">
            <h3>Call-to-action Box</h3>
            <div class="inside minheight150">
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row"><label for="">Select a template</label></th>
                            <td>
                                <?php $this->wpcb_template_list($box_template, 3); ?>
                                <p class="wpcb_help_block">Select a template from above and hit Update.</p>
                            </td>
                        </tr>                        
                    </tbody>
                </table>
                <div class='wpcb_template_preview_3 wpcb_preview_image_center'></div>
            </div>
        </div>
    </div>
    <div class="wpcb_box_div wpcb_box_type_4">
        <div class="postbox">
            <h3>Video Call-to-action Box</h3>
            <div class="inside minheight150">
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row"><label for="">Select a template</label></th>
                            <td>
                                <?php $this->wpcb_template_list($box_template, 4); ?>
                                <p class="wpcb_help_block">Select a template from above and hit Update.</p>
                            </td>
                        </tr>                        
                    </tbody>
                </table>
                <div class='wpcb_template_preview_4 wpcb_preview_image_center'></div>
            </div>
        </div>
    </div>
<div class="wpcb_nav_buttons_step_1">
    <input type="submit" box_id="<?php echo $id; ?>" value="Update" class="button button-primary" name="update-box-template" id="update-box-template"/>
</div>