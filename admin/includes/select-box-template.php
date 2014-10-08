<?php 

// Step 1 - Select Box Template

    $wpcb_the_row = $wpdb->get_row($wpdb->prepare("SELECT `box_type`,`box_template` from $wpcb_tbl_name WHERE id = %d",array($id)));
    $box_type = $wpcb_the_row->box_type;
    $box_template = $wpcb_the_row->box_template;

?>

    <p><?php _e('Select the type of WP Conversion Box that you want to make:','wp-conversion-boxes'); ?></p>
    
    <div class='wpcb_box_type'>
        <input type="radio" class="wpcb_box_type_radio" id="wpcb_box_1" name="wpcb_box_type" value="1" <?php if($box_type == '1' || $box_type == null) echo "checked";?>>
           <label for="wpcb_box_1" id="wpcb_box_1_label"><?php _e('Email Optin Box','wp-conversion-boxes'); ?></label>
        <input type="radio" class="wpcb_box_type_radio" id="wpcb_box_2" name="wpcb_box_type" value="2" <?php if($box_type == '2') echo "checked"; ?>>
           <label for="wpcb_box_2" id="wpcb_box_2_label"><?php _e('Video Email Optin Box','wp-conversion-boxes'); ?></label>
        <input type="radio" class="wpcb_box_type_radio" id="wpcb_box_3" name="wpcb_box_type" value="3" <?php if($box_type == '3') echo "checked"; ?>>
           <label for="wpcb_box_3" id="wpcb_box_3_label"><?php _e('Call-to-action Box','wp-conversion-boxes'); ?></label> 
        <input type="radio" class="wpcb_box_type_radio" id="wpcb_box_4" name="wpcb_box_type" value="4" <?php if($box_type == '4') echo "checked"; ?>>
           <label for="wpcb_box_4" id="wpcb_box_4_label"><?php _e('Video Call-to-action Box','wp-conversion-boxes'); ?></label>    
    </div>

    <div class="wpcb_box_div wpcb_box_type_1">
        <div class="postbox">
            <h3><?php _e('Email Optin Box','wp-conversion-boxes'); ?></h3>
            <div class="inside minheight150">
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row"><label for=""><?php _e('Select a template','wp-conversion-boxes'); ?></label></th>
                            <td>
                                <?php $this->wpcb_template_list($box_template, 1); ?>
                                <p class="wpcb_help_block"><?php _e('Select a template from above and hit Update.','wp-conversion-boxes'); ?></p>
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
            <h3><?php _e('Video Email Optin Box','wp-conversion-boxes'); ?></h3>
            <div class="inside minheight150">
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row"><label for=""><?php _e('Select a template','wp-conversion-boxes'); ?></label></th>
                            <td>
                                <?php $this->wpcb_template_list($box_template, 2); ?>
                                <p class="wpcb_help_block"><?php _e('Select a template from above and hit Update.','wp-conversion-boxes'); ?></p>
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
            <h3><?php _e('Call-to-action Box','wp-conversion-boxes'); ?></h3>
            <div class="inside minheight150">
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row"><label for=""><?php _e('Select a template','wp-conversion-boxes'); ?></label></th>
                            <td>
                                <?php $this->wpcb_template_list($box_template, 3); ?>
                                <p class="wpcb_help_block"><?php _e('Select a template from above and hit Update.','wp-conversion-boxes'); ?></p>
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
            <h3><?php _e('Video Call-to-action Box','wp-conversion-boxes'); ?></h3>
            <div class="inside minheight150">
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row"><label for=""><?php _e('Select a template','wp-conversion-boxes'); ?></label></th>
                            <td>
                                <?php $this->wpcb_template_list($box_template, 4); ?>
                                <p class="wpcb_help_block"><?php _e('Select a template from above and hit Update.','wp-conversion-boxes'); ?></p>
                            </td>
                        </tr>                        
                    </tbody>
                </table>
                <div class='wpcb_template_preview_4 wpcb_preview_image_center'></div>
            </div>
        </div>
    </div>
<div class="wpcb_nav_buttons_step_1">
    <p><input type="submit" box_id="<?php echo $id; ?>" value="<?php _e('Save and Next','wp-conversion-boxes'); ?>" class="button button-primary" name="update-box-template" id="update-box-template"/></p>
</div>
<p class="description"><?php _e("<b>NOTE: </b>If you come back later and change the selected template for this box, don't forget to Reset the box customizations by clicking the Reset button at the bottom of Customize Box page or else your design will look <del>screwed</del> broken.",'wp-conversion-boxes'); ?></p>
<p class="description"><?php _e("<strong>NOTE 2: Need a custom box template?</strong> Send me your custom box template design and I'll make a template out of it for just $49. <a target='_blank' href='http://wpconversionboxes.com/order-custom-template/'>Hire me now</a>",'wp-conversion-boxes'); ?></p>