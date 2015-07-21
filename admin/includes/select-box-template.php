<?php 

// Step 1 - Select Box Template

    $wpcb_the_row = $wpdb->get_row($wpdb->prepare("SELECT `box_type`,`box_template` from $wpcb_tbl_name WHERE id = %d",array($id)));
    $box_type = $wpcb_the_row->box_type;
    $box_template = $wpcb_the_row->box_template;
?>
    
    <p><?php _e('Select the type of WP Conversion Box that you want to make:','wp-conversion-boxes'); ?></p>
    
    <div class='wpcb_box_type'>
        <input type="radio" class="wpcb_box_type_radio" id="wpcb_box_6" name="wpcb_box_type" value="6" <?php if($box_type == '6' || $box_type == null) echo "checked"; ?>>
           <label for="wpcb_box_6" id="wpcb_box_6_label"><?php _e('Smart Popup Box','wp-conversion-boxes'); ?></label>
        <input type="radio" class="wpcb_box_type_radio" id="wpcb_box_5" name="wpcb_box_type" value="5" <?php if($box_type == '5') echo "checked"; ?>>
           <label for="wpcb_box_5" id="wpcb_box_5_label"><?php _e('2-Step Optin Link','wp-conversion-boxes'); ?></label>
        <input type="radio" class="wpcb_box_type_radio" id="wpcb_box_1" name="wpcb_box_type" value="1" <?php if($box_type == '1') echo "checked";?>>
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
    <div class="wpcb_box_div wpcb_box_type_5">
        <div class="postbox">
            <h3><?php _e('2-Step Optin Link','wp-conversion-boxes'); ?> <i class="fa fa-question-circle" id="two-step-toggle"></i></h3>
            <div id="two-step-optin-info">
                <h3>2-Step Optin Link (Link-To-Lightbox Email Optin Box)</h3>
                <span class="fa fa-close wpcb_publish_close" style="position: absolute; top: 20px; right: 20px; cursor: pointer;" onclick="jQuery(this).parent().trigger('close');"></span>
                <p>2-Step Optin Link box type is a 2-step email optin form. Here's how it works:</p>
                <ol>
                    <li>You create a 2-Step Optin Link box like any other conversion box and get a shortcode for it at the end.</li>
                    <li>You paste this shortcode anywhere in your content or on your landing page. The shortcode will display a link with your desired call-to-action.</li>
                    <li>When a user clicks this link, it'll open the email optin box you created, in a lightbox popup!</li>
                </ol>
                <p>Cool, isn't it? We're not shoving an email optin box directly in the visitor's face, but hiding it behind a beautiful looking link. Research have shown that 2-step optin links can drastically boost your conversion by upto 600%! <a href='http://wpconversionboxes.com/2-step-optin/' target="_blank">Learn more here.</a></p>
            </div>
            <div class="inside minheight150">
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row"><label for=""><?php _e('Select a template','wp-conversion-boxes'); ?></label></th>
                            <td>
                                <?php $this->wpcb_template_list($box_template, 5); ?>
                                <p class="wpcb_help_block"><?php _e('Select a template from above and hit Update.','wp-conversion-boxes'); ?></p>
                            </td>
                        </tr>                        
                    </tbody>
                </table>
                <div class='wpcb_template_preview_5 wpcb_preview_image_center'></div>
            </div>
        </div>
    </div>
    <div class="wpcb_box_div wpcb_box_type_6">
        <div class="postbox">
            <h3><?php _e('Smart Popup Box','wp-conversion-boxes'); ?> <i class="fa fa-question-circle" id="wpcb-popup-toggle"></i></h3>
            <div id="wpcb-popup-info">
                <h3>Smart Popup Box</h3>
                <span class="fa fa-close wpcb_publish_close" style="position: absolute; top: 20px; right: 20px; cursor: pointer;" onclick="jQuery(this).parent().trigger('close');"></span>
                <p>Create three different types of Smart Popups using this box type.</p>
                <ol>
                    <li><b>Timed Popup</b> - Trigger the popup after a certain time period.</li>
                    <li><b>Scroll-triggered Popup</b> - Trigger the popup after a user scrolls past a certain point on the page.</li>
                    <li><b>Exit Popup</b> - Convert the users abandoning your site by triggering the popup when a visitor tries to leave your site.</li>
                </ol>
                <p>Select a box template below for the popup and hit Update. On <b>Step 2 - Customize Box</b> page cuustomize the popup and then on the <b>Step 3 - Box Settings</b> page select how you want to show this popup by selecting one of the above options.</p>
            </div>
            <div class="inside minheight150">
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row"><label for=""><?php _e('Select a template','wp-conversion-boxes'); ?></label></th>
                            <td>
                                <?php $this->wpcb_template_list($box_template, 6); ?>
                                <p class="wpcb_help_block"><?php _e('Select a template from above and hit Update.','wp-conversion-boxes'); ?></p>
                            </td>
                        </tr>                        
                    </tbody>
                </table>
                <div class='wpcb_template_preview_6 wpcb_preview_image_center'></div>
            </div>
        </div>
    </div>
<div class="wpcb_nav_buttons_step_1">
    <p><input type="submit" wpcb_has_template="<?php echo isset($box_template) ? $box_template : '';  ?>" box_id="<?php echo $id; ?>" value="<?php _e('Save and Next','wp-conversion-boxes'); ?>" class="button button-primary" name="update-box-template" id="update-box-template"/></p>
</div>