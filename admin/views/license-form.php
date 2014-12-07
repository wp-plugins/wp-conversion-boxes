
<div class="wrap wpcb-wrapper">
    <h2><?php _e('Enter License Key to Activate WP Conversion Boxes Pro','wp-conversion-boxes-pro'); ?></h2>
    <?php if(get_option('wpcb_license_validity_remaining') == 0 && get_option('wpcb_license_key') != '') { ?> <div class="error"><p><?php echo sprintf( __('Your license has been expired. You can renew your license by going to <a href="%s" target="_blank">this link</a>.','wp-conversion-boxes-pro') , 'http://wpconversionboxes.com/checkout/?edd_license_key='. get_option('wpcb_license_key') ); ?></p></div> <?php } ?>
    <table class="form-table">
            <tbody>
                    <tr valign="top">	
                            <th scope="row" valign="top">
                                    <?php _e('License Key','wp-conversion-boxes-pro'); ?>
                            </th>
                            <td>
                                    <input id="wpcb_license_key" name="wpcb_license_key" type="text" placeholder="<?php _e('Enter your license key here...','wp-conversion-boxes-pro'); ?>" class="regular-text" value="<?php esc_attr_e( get_option('wpcb_license_key') ); ?>" />
                                    <p class="description"><?php _e('Enter the license key you received here and click the Activate button.','wp-conversion-boxes-pro'); ?></p>
                            </td>
                    </tr>
            </tbody>
    </table>
    <input type="submit" id="wpcb_activate_license" class="button button-primary" value="<?php _e('Activate','wp-conversion-boxes-pro'); ?>" />
    <p><?php _e('To get a new license, visit our website :', 'wp-conversion-boxes-pro'); ?> <a target="_blank" href="http://wpconversionboxes.com">http://wpconversionboxes.com</a>.</p> 
</div>