<?php

/*************************************
* Global Settings Page
*************************************/

$wpcb = WPCB_Admin::get_instance();
$upgrade_message = $wpcb->upgrade_to_pro();

if(isset($_GET['step'])){
    $step = $_GET['step'];
}

if(isset($_POST['mailer']) and isset($_POST['apikey']) and isset($_POST['connect'])){
    $mailer_id = $_POST['mailer'];
    $api_key = $_POST['apikey'];
    
    switch($mailer_id){

        //GetResponse
        case 1: if (!class_exists('jsonRPCClient')) {
                    include_once(plugin_dir_path(dirname(__FILE__)).'mailers/getresponse-api.php');
                }
                    $getresponse = new jsonRPCClient('http://api2.getresponse.com');
                    try{
                        $name = array();
                        $allgrcampaigns = $getresponse->get_campaigns($api_key);
                        foreach($allgrcampaigns as $grcampaign){
                            $campaign_name = $grcampaign['name'];
                            $result = $getresponse->get_campaigns($api_key, array ('name' => array ( 'EQUALS' => $campaign_name )));
                            $res = array_keys($result);
                            $campaign_id = array_pop($res);
                            $getresponse_campaigns[$campaign_id] = $campaign_name;
                        }
                        $getresponse_campaigns = serialize($getresponse_campaigns);
                        update_option('wpcb_getresponse_api_key',$api_key);
                        update_option('wpcb_getresponse_campaigns',$getresponse_campaigns);
                        $showresponse_1 = "<div class='wpcb_success' style='margin: 0px 10px 10px 10px;'><p>". __('Connected to API successfully.', 'wp-conversion-boxes') ."<a href='' style='float:right;' onclick='jQuery(this).parent().parent().fadeOut(300).hide();'>Close</a></p></div>";
                    }
                    catch (Exception $e){
                        echo $e->getMessage();
                        $showresponse_1 = "<div id='wpcb_error' style='margin: 0px 10px 10px 10px;'><p>". __('Invalid API Key. Please try again.', 'wp-conversion-boxes') ."<a href='' style='float:right;' onclick='jQuery(this).parent().parent().fadeOut(300).hide();'>Close</a></p></div>";
                    }
                    echo "</ul>";
                break;
        
        // MailChimp
        case 2: if (!class_exists('MCAPI')) {
                    include_once(plugin_dir_path(dirname(__FILE__)).'mailers/mailchimp-api.php');
                }
                $mailchimp = new MCAPI($api_key);       
                $allmclists = $mailchimp->lists();
                if($allmclists['total'] == 0){
                    $showresponse_2 = "<div id='wpcb_error' style='margin: 0px 10px 10px 10px;'><p>". __('No lists found. Please create a list and try again later.', 'wp-conversion-boxes') ." <a href='' style='float:right;' onclick='jQuery(this).parent().parent().fadeOut(300).hide();'>Close</a></p></div>";
                } 
                else{
                    foreach($allmclists['data'] as $mclist){
                        $mailchimp_lists[$mclist['id']] = $mclist['name'];
                    }
                    $mailchimp_lists_serialized = serialize($mailchimp_lists);
                    update_option('wpcb_mailchimp_api_key',$api_key);
                    update_option('wpcb_mailchimp_lists',$mailchimp_lists_serialized);
                    $showresponse_2 = "<div class='wpcb_success' style='margin: 0px 10px 10px 10px;'><p>". __('Connected to API successfully.', 'wp-conversion-boxes') ."<a href='' style='float:right;' onclick='jQuery(this).parent().parent().fadeOut(300).hide();'>Close</a></p></div>";
                }
                break;
                
        // Aweber                
        case 3: if (!class_exists('AWeberAPI')) {
                    include_once(plugin_dir_path(dirname(__FILE__)).'mailers/aweber_api/aweber_api.php');
                }
                try {
                        $aweber_data = AWeberAPI::getDataFromAweberID($api_key);
                        $aweber = new AWeberAPI($aweber_data[0], $aweber_data[1]);
                        $account = $aweber->getAccount($aweber_data[2], $aweber_data[3]);
                        $allaweberlists = $account->lists;
                        foreach($allaweberlists as $aweberlist){
                            $aweber_lists[$aweberlist->id] = $aweberlist->name;
                        }
                        $aweber_data_serialized = serialize($aweber_data);
                        $aweber_lists_serialized = serialize($aweber_lists);
                        update_option('wpcb_aweber_api_key',$aweber_data_serialized);
                        update_option('wpcb_aweber_lists',$aweber_lists_serialized);
                        $showresponse_3 = "<div class='wpcb_success' style='margin: 0px 10px 10px 10px;'><p>". __('Connected to Aweber successfully.', 'wp-conversion-boxes') ."<a href='' style='float:right;' onclick='jQuery(this).parent().parent().fadeOut(300).hide();'>Close</a></p></div>";
                }
                catch (Exception $ex) {
                        $showresponse_3 = "<div id='wpcb_error' style='margin: 0px 10px 10px 10px;'><p>". __('Invalid authorization Key. Please try again.', 'wp-conversion-boxes') ."<a href='' style='float:right;' onclick='jQuery(this).parent().parent().fadeOut(300).hide();'>Close</a></p></div>";
                }
                break;
                
        // Feedburner
        case 11:if($api_key == ''){
                    echo "<div class='error'><p>". __('Please enter your Feedburner URI.', 'wp-conversion-boxes') ."<a href='' style='float:right;' onclick='jQuery(this).parent().parent().fadeOut(300).hide();'>Close</a></p></div>";
                    break;
                }
                $feedburner_uri = $api_key;
                update_option('wpcb_feedburner_uri',$feedburner_uri);
                echo "<div class='updated'><p>". __('Connected to Feedburner successfully.', 'wp-conversion-boxes') ."<a href='' style='float:right;' onclick='jQuery(this).parent().parent().fadeOut(300).hide();'>Close</a></p></div>";
                break;
    }
}
else if(isset($_POST['mailer']) and isset($_POST['disconnect'])){
    $mailer_id = $_POST['mailer'];
    switch($mailer_id){
        //GetResponse
        case 1: delete_option('wpcb_getresponse_api_key');
                delete_option('wpcb_getresponse_campaigns');
                echo "<div class='updated'><p>". __('GetResponse disconnected successfully.', 'wp-conversion-boxes') ."</p></div>";
                break;
        //MailChimp    
        case 2: delete_option('wpcb_mailchimp_api_key');
                delete_option('wpcb_mailchimp_lists');
                echo "<div class='updated'><p>". __('MailChimp disconnected successfully.', 'wp-conversion-boxes') ."</p></div>";
                break;    
        //Aweber
        case 3: delete_option('wpcb_aweber_api_key');
                delete_option('wpcb_aweber_lists');
                echo "<div class='updated'><p>". __('Aweber disconnected successfully.', 'wp-conversion-boxes') ."</p></div>";
                break;
        //Feedburner
        case 11: delete_option('wpcb_feedburner_uri');
                echo "<div class='updated'><p>". __('Feedburner disconnected successfully.', 'wp-conversion-boxes') ."</p></div>";
    }
}
                
?>

<div class="wrap wpcb_main">
    
    <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
    
    <h2 class="nav-tab-wrapper">
        <a href="<?php echo admin_url( 'admin.php?page=' . $wpcb->wpcb_settings_slug )."&step=1"; ?>" class="nav-tab <?php if($step == 1 || !isset($step)) echo "nav-tab-active"; ?>"><?php _e('General Settings', 'wp-conversion-boxes'); ?></a>
        <a href="<?php echo admin_url( 'admin.php?page=' . $wpcb->wpcb_settings_slug )."&step=2"; ?>" class="nav-tab <?php if($step == 2) echo "nav-tab-active"; ?>"><?php _e('Email Service Integration', 'wp-conversion-boxes'); ?></a>
        <a href="<?php echo admin_url( 'admin.php?page=' . $wpcb->wpcb_settings_slug )."&step=3"; ?>" class="nav-tab <?php if($step == 3) echo "nav-tab-active"; ?>"><?php _e('Upload Custom Template', 'wp-conversion-boxes'); ?></a>
        <a href="<?php echo admin_url( 'admin.php?page=' . $wpcb->wpcb_settings_slug )."&step=4"; ?>" class="nav-tab <?php if($step == 4) echo "nav-tab-active"; ?>"><?php _e('Export/Import Boxes', 'wp-conversion-boxes'); ?></a>
    </h2>
    
    <div id="poststuff" class="metabox-holder has-right-sidebar">    

        <div class="inner-sidebar" id="side-info-column">
            <?php $wpcb->wpcb_sidebar(); ?>
        </div>
        
        <?php if(!isset($step) || $step == 1) : ?>

        <div id="post-body-content">
            <div class='postbox'>
                <h3><?php _e('Sitewide Settings', 'wp-conversion-boxes'); ?></h3>
                <div class='inside'>
                    <table class="form-table">
                        <tbody>
                            <tr>
                                <th scope="row"><label for=""><?php _e('Default Conversion Box', 'wp-conversion-boxes'); ?></label></th>
                                <td>
                                    <?php $wpcb->wpcb_box_list(get_option('wpcb_default_box'),'default','wpcb_boxes_list_default'); ?>
                                    <p class="wpcb_help_block"><?php _e("Assign a default conversion box to all pages and posts. This box will be used when no other box has been set for any post/page.", 'wp-conversion-boxes'); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label for=""><?php _e('Conversion Box for All Posts', 'wp-conversion-boxes'); ?></label></th>
                                <td>
                                    <?php $wpcb->wpcb_box_list(get_option('wpcb_all_posts'),'','wpcb_boxes_list_posts'); ?>
                                    <p class="wpcb_help_block"><?php _e("Select a conversion box that'll be shown under all Blog Posts. This will override the default conversion box.", 'wp-conversion-boxes'); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label for=""><?php _e('Conversion Box for All Pages', 'wp-conversion-boxes'); ?></label></th>
                                <td>
                                    <?php $wpcb->wpcb_box_list(get_option('wpcb_all_pages'),'','wpcb_boxes_list_pages'); ?>
                                    <p class="wpcb_help_block"><?php _e("Select a conversion box that'll be shown under all Pages of your site. This will override the default conversion box.", 'wp-conversion-boxes'); ?></p>
                                </td>
                            </tr>
                            </tbody>
			</table>

                </div>
            </div>
            
            <div class="postbox opaque6">
                <h3><?php _e('Conversion Boxes for Categories', 'wp-conversion-boxes'); echo $upgrade_message; ?></h3>
                <div class='inside'>
                        <table class="form-table">
                            <tbody>
                                <tr>
                                    <th scope="row"><label for=""><?php _e('Select Conversion Box', 'wp-conversion-boxes'); ?></label></th>
                                    <td>
                                        <p class="wpcb_help_block"><?php _e("Select a conversion box for the posts of specific categories. This will override the Default Conversion Box and also Conversion Box for All Posts.", 'wp-conversion-boxes'); ?></p>
                                    </td>
                                </tr>
                            </tbody>
			</table>
                        <div>
                            <?php $wpcb->wpcb_category_wise_box_list(); ?>
                        </div>
                </div>
            </div>
            
            <div class="postbox">
                <h3><?php _e('Help Us Spread The Word', 'wp-conversion-boxes'); ?></h3>
                <div class='inside'>
                        <table class="form-table">
                            <tbody>
                                <tr>
                                    <th scope="row"><label for=""><?php _e('Show Link to WP Conversion Boxes Under Conversion Boxes', 'wp-conversion-boxes'); ?></label></th>
                                    <td>
                                        <p><label><input type='checkbox' value='1' id='enable_credit_link' name='enable_credit_link' <?php echo (get_option('wpcb_enable_credit_link') == 1) ? 'checked' : '' ?>/> <?php _e('Enable Credit Link', 'wp-conversion-boxes'); ?></label></p>
                                        <p class="wpcb_help_block"><?php _e("Place a small link to WP Conversion Boxes below your conversion boxes. This will help us spread the word out about the plugin and along with that, give you the fuzzy feeling of helping us out.", 'wp-conversion-boxes'); ?></p>
                                    </td>
                                </tr>
                            </tbody>
			</table>
                </div>
            </div>
            
            <input type="submit" value="<?php _e('Update', 'wp-conversion-boxes'); ?>" class="button button-primary" name="update-global-settings" id="update-global-settings"/>
            
        </div>
        
        <?php elseif($step == 2 || !isset($step)) : ?>
        
        <div id="post-body-content">
            <div class='postbox'>
                <h3><?php _e('Integrate Your Email Service', 'wp-conversion-boxes'); ?></h3>
                <div class='inside'>
                    <table class="form-table">
                        <tbody>
                            <tr>
                                <th scope="row" style="max-width: 200px;"><label for=""><?php _e('Select Your Email Service Provider', 'wp-conversion-boxes'); ?></label></th>
                                <td class="wpcb_mailers_td">
                                    <ul class="wpcb_mailers_list">
                                        
                                        <!-- Feedburner = 11 -->
                                        
                                        <li class="wpcb_mailer_li">
                                            <table>
                                                <tr>
                                                    <td><img style="width: 200px;" src="<?php echo ADMIN_ASSETS_URL.'imgs/feedburner-logo.png'; ?>" /></td>
                                                    <?php if(get_option('wpcb_feedburner_uri') == '') : ?>
                                                        <td><button class="button-primary wpcb_mailer" data-mailer-id="11"><?php _e('Integrate','wp-conversion-boxes'); ?></button></td>
                                                        <td><a class="button-primary" target="_blank" href="http://wpconversionboxes.com/email-services/feedburner/"><?php _e('Create Account For Free','wp-conversion-boxes'); ?></a></td>
                                                    <?php else : ?>
                                                        <td>
                                                            <form action="" method="post">
                                                                <input type="submit" class="button-primary" name="disconnect" value="<?php _e('Disconnect','wp-conversion-boxes'); ?>">
                                                                <input type="hidden" name="mailer" value="11">
                                                            </form>
                                                        </td>
                                                    <?php endif; ?>
                                                </tr>
                                            </table>
                                            <div id="wpcb_mailer_11" class="wpcb_mailers_option">
                                                <form method="post" action="">
                                                    <p><?php _e("Feedburner URI:", 'wp-conversion-boxes'); ?></p>
                                                    <p><input type="text" name="apikey" value="" class="wpcb_fullwidth"/><br /></p>
                                                    <div style="margin: 10px 0px 10px 0px;">
                                                        <input type="submit" class="button-primary" name="connect" value="<?php _e('Connect to Feedburner','wp-conversion-boxes'); ?>">
                                                        <button class="button-primary wpcb_mailer_cancel"><?php _e('Cancel','wp-conversion-boxes'); ?></button>
                                                        <input type="hidden" name="mailer" value="11">
                                                    </div>
                                                </form>
                                                <p class="wpcb_help_block"><?php _e("Enter your Feedburner URI here (http://feeds.feedburner.com/THIS-PART-IS-URI) and click on Connect to Feedburner. After you integrate here, you can then select it as a List/Campaign on the Step 2: Customize Box page for your conversion boxes for adding leads to this Feedburner account.", 'wp-conversion-boxes'); ?></p>
                                            </div>
                                        </li>
                                        
                                        
                                        <!-- GetResponse -->
                                        
                                        
                                        <li class="wpcb_mailer_li">
                                            <table>
                                                <tr>
                                                    <td width="200"><img src="<?php echo ADMIN_ASSETS_URL.'/imgs/getresponse-logo.png'; ?>" /></td>
                                                    <?php if(get_option('wpcb_getresponse_api_key') == '') : ?>
                                                        <td><button class="button-primary wpcb_mailer" data-mailer-id="1"><?php _e('Integrate', 'wp-conversion-boxes'); ?></button></td>
                                                        <td><a class="button-primary" target="_blank" href="http://wpconversionboxes.com/email-services/getresponse/"><?php _e('Create Account For Free', 'wp-conversion-boxes'); ?></a></td>
                                                    <?php else : ?>
                                                        <td>
                                                            <form action="" method="post">
                                                                <input type="submit" class="button-primary" name="disconnect" value="<?php _e('Disconnect', 'wp-conversion-boxes'); ?>">
                                                                <input type="hidden" name="mailer" value="1">
                                                            </form>
                                                        </td>
                                                    <?php endif; ?>
                                                </tr>
                                            </table>
                                            <?php echo (isset($showresponse_1)) ? $showresponse_1 : ''; ?>

                                                <div id="wpcb_mailer_1" class="wpcb_mailers_option">
                                                    <p><?php _e('GetResponse API Key:', 'wp-conversion-boxes'); ?></p>
                                                    <form method="post" action="">
                                                        <input type="text" name="apikey" value="" class="wpcb_fullwidth"/><br />
                                                        <div style="margin: 10px 0px 10px 0px;">
                                                            <input type="submit" class="button-primary" name="connect" value="<?php _e('Connect to GetResponse', 'wp-conversion-boxes'); ?>">
                                                            <button class="button-primary wpcb_mailer_cancel"><?php _e('Cancel', 'wp-conversion-boxes'); ?></button>
                                                            <input type="hidden" name="mailer" value="1">
                                                        </div>
                                                    </form>
                                                    <p class="wpcb_help_block"><?php _e("This API key is used to add email leads to your GetResponse campaigns. You can find your API key from <a target='_blank' href='https://app.getresponse.com/account.html#api'>this page</a>.", 'wp-conversion-boxes'); ?></p>
                                                </div>

                                        </li>
                                        
                                        
                                        <!-- MailChimp -->
                                        
                                        
                                        <li class="wpcb_mailer_li">
                                            <table>
                                                <tr>
                                                    <td  style="width: 200px;"><img src="<?php echo ADMIN_ASSETS_URL.'/imgs/mailchimp-logo.png'; ?>" /></td>
                                                    <?php if(get_option('wpcb_mailchimp_api_key') == '') : ?>
                                                        <td><button class="button-primary wpcb_mailer" data-mailer-id="2"><?php _e('Integrate', 'wp-conversion-boxes'); ?></button></td>
                                                        <td><a class="button-primary" target="_blank" href="http://wpconversionboxes.com/email-services/mailchimp/"><?php _e('Create Account For Free', 'wp-conversion-boxes'); ?></a></td>
                                                    <?php else : ?>
                                                        <td>
                                                            <form action="" method="post">
                                                                <input type="submit" class="button-primary" name="disconnect" value="<?php _e('Disconnect', 'wp-conversion-boxes'); ?>">
                                                                <input type="hidden" name="mailer" value="2">
                                                            </form>
                                                        </td>
                                                    <?php endif; ?>
                                                </tr>
                                            </table>
                                            <?php echo (isset($showresponse_2)) ? $showresponse_2 : ''; ?>
                                            <div id="wpcb_mailer_2" class="wpcb_mailers_option">
                                                <p><?php _e('MailChimp API Key:', 'wp-conversion-boxes'); ?></p>
                                                <form method="post" action="">
                                                    <input type="text" name="apikey" value="" class="wpcb_fullwidth"/><br />
                                                    <div style="margin: 10px 0px 10px 0px;">
                                                        <input type="submit" class="button-primary" name="connect" value="<?php _e('Connect to MailChimp', 'wp-conversion-boxes'); ?>">
                                                        <button class="button-primary wpcb_mailer_cancel"><?php _e('Cancel', 'wp-conversion-boxes'); ?></button>
                                                        <input type="hidden" name="mailer" value="2">
                                                    </div>
                                                </form>
                                                <p class="wpcb_help_block"><?php _e("This API key is used to add email leads to your MailChimp lists. You can find your API key from <a target='_blank' href='https://admin.mailchimp.com/account/api/'>this page</a>. Go to the given link and just click on Create a Key to generate new API key.", 'wp-conversion-boxes'); ?></p>
                                            </div>
                                        </li>
                                        
                                        <!-- Aweber -->
                                        
                                        <li class="wpcb_mailer_li">
                                            <table>
                                                <tr>
                                                    <td><img style="width: 200px;" src="<?php echo ADMIN_ASSETS_URL.'/imgs/aweber-logo.gif'; ?>" /></td>
                                                    <?php if(get_option('wpcb_aweber_api_key') == '') : ?>
                                                        <td><button class="button-primary wpcb_mailer" data-mailer-id="3"><?php _e('Integrate', 'wp-conversion-boxes'); ?></button></td>
                                                        <td><a class="button-primary" target="_blank" href="http://wpconversionboxes.com/email-services/aweber/"><?php _e('Create Account For $1', 'wp-conversion-boxes'); ?></a></td>
                                                    <?php else : ?>
                                                        <td>
                                                            <form action="" method="post">
                                                                <input type="submit" class="button-primary" name="disconnect" value="<?php _e('Disconnect', 'wp-conversion-boxes'); ?>">
                                                                <input type="hidden" name="mailer" value="3">
                                                            </form>
                                                        </td>
                                                    <?php endif; ?>
                                                </tr>
                                            </table>
                                            <?php echo (isset($showresponse_3)) ? $showresponse_3 : ''; ?>
                                                <div id="wpcb_mailer_3" class="wpcb_mailers_option">
                                                    <p><?php _e('Aweber Authorization Code:', 'wp-conversion-boxes'); ?></p>
                                                    <form method="post" action="">
                                                        <input type="text" name="apikey" value="" class="wpcb_fullwidth"/><br />
                                                        <div style="margin: 10px 0px 10px 0px;">
                                                            <input type="submit" class="button-primary" name="connect" value="<?php _e('Connect to Aweber', 'wp-conversion-boxes'); ?>">
                                                            <button class="button-primary wpcb_mailer_cancel"><?php _e('Cancel', 'wp-conversion-boxes'); ?></button>
                                                            <input type="hidden" name="mailer" value="3">
                                                        </div>
                                                    </form>
                                                    <p class="wpcb_help_block"><?php _e("You need to authorize this plugin to be able to add leads to your Aweber lists. To get the authorization code, log into <a target='_blank' href='https://auth.aweber.com/1.0/oauth/authorize_app/459aa11b'>this page</a> with your Aweber acoount.", 'wp-conversion-boxes'); ?></p>
                                                </div>
                                        </li>
                                    </ul>
                                    <p class="wpcb_help_block"><?php _e("Click on <b>Integrate</b> button to integrate your email service provider. If you don't have an account, click on <b>Create Account</b> button to create a new account. If at any point you want to disconnect your email service provider click on <b>Disconnect</b> button to do so.", 'wp-conversion-boxes'); ?></p>
                                </td>    
                            </tr>
                            <tr>
                                <th scope="row"><label for=""><?php _e('Integrate WordPress Plugin <em>(New)</em>', 'wp-conversion-boxes'); ?></label><p class="wpcb_help_block" style="font-weight: normal;"><?php _e("Integrate your email marketing WordPress plugin with WP Conversion Boxes.", 'wp-conversion-boxes'); ?></p></th>
                                <td class="wpcb_mailers_td">
                                    <ul class="wpcb_mailers_list">
                                    <!-- MailPoet -->
                                        
                                        <li class="wpcb_mailer_li">
                                            <table>
                                                <tr>
                                                    <td><img style="width: 200px;" src="<?php echo ADMIN_ASSETS_URL.'imgs/mailpoet-logo.png'; ?>" /></td>
                                                    <td><a class="button-primary" target="_blank" href="http://wpconversionboxes.com/email-services/mailpoet/"><?php _e('Download WordPress Plugin','wp-conversion-boxes'); ?></a></td>
                                                    <td>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" style="padding-top: 0px;"><p class="wpcb_help_block"><?php _e("When you install MailPoet (Wysija) plugin, WP Conversion Boxes will automagically pull your mailing lists on <b>Customize Box</b> page. No setup required!", 'wp-conversion-boxes'); ?></p></td>
                                                </tr>
                                            </table>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <?php elseif($step == 3) : ?>
        
        <div id="post-body-content">
                <div class='postbox opaque6'>
                    <h3><?php _e('Upload Custom Template', 'wp-conversion-boxes'); echo $upgrade_message ?></h3>
                    <div class='inside'>
                        <table class="form-table">
                            <tbody>
                                <tr>
                                    <th scope="row"><label for=""><?php _e('Upload Custom Template', 'wp-conversion-boxes'); ?></label></th>
                                    <td>
                                        <form action="" method="post" enctype="multipart/form-data">
                                        <p><select disabled><option><?php _e('Email Optin', 'wp-conversion-boxes'); ?></option></select></p>
                                        <p class="wpcb_help_block"><?php _e("Please select template type of the zip file.", 'wp-conversion-boxes'); ?></p><br />
                                        <p><label><?php _e('Select zip file:', 'wp-conversion-boxes'); ?> <input type="file" name="wpcb_template_zip" id="wpcb_template_zip" disabled></label></p>
                                        <p class="wpcb_help_block"><?php _e("Upload your custom .zip template file here. This .zip file will be extracted to the <code>/templates/</code> directory of the plugin. Your template will be available to use on Select Box Template page. To know how make custom box templates, <a target='_blank'>click here</a>", 'wp-conversion-boxes'); ?></p><br />
                                        <p><input type="submit" name="submit" value="Upload" class="button button-primary" disabled /></p>
                                        </form>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
        </div>
        
        <?php elseif($step == 4) : 
            $wpcb->import_boxes_from_xml();    
        ?>
        
        <div id="post-body-content">
                <div class='postbox'>
                    <h3><?php _e('Export/Import Boxes', 'wp-conversion-boxes'); ?></h3>
                    <div class='inside'>
                        <table class="form-table">
                            <tbody>
                                <tr>
                                    <th scope="row"><label for=""><?php _e('Export All Boxes', 'wp-conversion-boxes'); ?></label></th>
                                    <td>
                                        <form action="" method="post" enctype="multipart/form-data">
                                            <p><input type="submit" name="export-boxes" value="Export Boxes" class="button button-primary" /></p>
                                        </form>
                                        <p class="wpcb_help_block"><?php _e("Click on the <b>Export Boxes</b> button to export all your conversion boxes to an XML file. This XML file will contain all your boxes and their customizations. You can then import the boxes on other sites using FREE or Pro version of the plugin. Please note that only box customizations and settings get exported and not templates.", 'wp-conversion-boxes'); ?></p><br />    
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for=""><?php _e('Import Boxes', 'wp-conversion-boxes'); ?></label></th>
                                    <td>
                                        <form action="" method="post" enctype="multipart/form-data">
                                            <p><label><?php _e('Select XML file:', 'wp-conversion-boxes'); ?> <input type="file" name="wpcb_import_xml" id="wpcb_import_xml"></label></p>
                                            <p class="wpcb_help_block"><?php _e("Select your previously exported XML file and click on Import button to import your boxes and their customiztons.", 'wp-conversion-boxes'); ?></p><br />
                                            <p><input type="submit" name="import-boxes" value="Import" class="button button-primary" /></p>
                                        </form>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
        </div>
        
        <?php endif; ?>
        
    </div>
    
</div>