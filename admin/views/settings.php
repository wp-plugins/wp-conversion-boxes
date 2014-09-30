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
        case 1: include_once(plugin_dir_path(dirname(__FILE__)).'mailers/getresponse-api.php');
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
                        $showresponse_1 = "<div class='wpcb_success' style='margin: 0px 10px 10px 10px;'><p>Connected to API successfully.<a href='' style='float:right;' onclick='jQuery(this).parent().parent().fadeOut(300).hide();'>Close</a></p></div>";
                    }
                    catch (Exception $e){
                        echo $e->getMessage();
                        $showresponse_1 = "<div id='wpcb_error' style='margin: 0px 10px 10px 10px;'><p>Invalid API Key. Please try again.<a href='' style='float:right;' onclick='jQuery(this).parent().parent().fadeOut(300).hide();'>Close</a></p></div>";
                    }
                    echo "</ul>";
                break;
        
        // MailChimp
        case 2: include_once(plugin_dir_path(dirname(__FILE__)).'mailers/mailchimp-api.php');
                $mailchimp = new MCAPI($api_key);       
                $allmclists = $mailchimp->lists();
                if($allmclists['total'] == 0){
                    $showresponse_2 = "<div id='wpcb_error' style='margin: 0px 10px 10px 10px;'><p>No lists found. Please create a list and try again later. <a href='' style='float:right;' onclick='jQuery(this).parent().parent().fadeOut(300).hide();'>Close</a></p></div>";
                } 
                else{
                    foreach($allmclists['data'] as $mclist){
                        $mailchimp_lists[$mclist['id']] = $mclist['name'];
                    }
                    $mailchimp_lists_serialized = serialize($mailchimp_lists);
                    update_option('wpcb_mailchimp_api_key',$api_key);
                    update_option('wpcb_mailchimp_lists',$mailchimp_lists_serialized);
                    $showresponse_2 = "<div class='wpcb_success' style='margin: 0px 10px 10px 10px;'><p>Connected to API successfully.<a href='' style='float:right;' onclick='jQuery(this).parent().parent().fadeOut(300).hide();'>Close</a></p></div>";
                }
                break;
                
        // Aweber                
        case 3: include_once(plugin_dir_path(dirname(__FILE__)).'mailers/aweber_api/aweber_api.php');
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
                        $showresponse_3 = "<div class='wpcb_success' style='margin: 0px 10px 10px 10px;'><p>Connected to Aweber successfully.<a href='' style='float:right;' onclick='jQuery(this).parent().parent().fadeOut(300).hide();'>Close</a></p></div>";
                }
                catch (Exception $ex) {
                        $showresponse_3 = "<div id='wpcb_error' style='margin: 0px 10px 10px 10px;'><p>Invalid authorization Key. Please try again.<a href='' style='float:right;' onclick='jQuery(this).parent().parent().fadeOut(300).hide();'>Close</a></p></div>";
                }
                break;                
    }
}
else if(isset($_POST['mailer']) and isset($_POST['disconnect'])){
    $mailer_id = $_POST['mailer'];
    switch($mailer_id){
        //GetResponse
        case 1: delete_option('wpcb_getresponse_api_key');
                delete_option('wpcb_getresponse_campaigns');
                break;
        //MailChimp    
        case 2: delete_option('wpcb_mailchimp_api_key');
                delete_option('wpcb_mailchimp_lists');
                break;    
        //Aweber
        case 3: delete_option('wpcb_aweber_api_key');
                delete_option('wpcb_aweber_lists');
                break;
    }
}
        
                
?>

<div class="wrap wpcb_main">
    
    <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
    
    <h2 class="nav-tab-wrapper">
        <a href="<?php echo admin_url( 'admin.php?page=' . $wpcb->wpcb_settings_slug )."&step=1"; ?>" class="nav-tab <?php if($step == 1 || !isset($step)) echo "nav-tab-active"; ?>">General Settings</a>
        <a href="<?php echo admin_url( 'admin.php?page=' . $wpcb->wpcb_settings_slug )."&step=2"; ?>" class="nav-tab <?php if($step == 2) echo "nav-tab-active"; ?>">Email Service Integration</a>
        <a href="<?php echo admin_url( 'admin.php?page=' . $wpcb->wpcb_settings_slug )."&step=3"; ?>" class="nav-tab <?php if($step == 3) echo "nav-tab-active"; ?>">Upload Custom Template</a>
    </h2>
    
    <div id="poststuff" class="metabox-holder has-right-sidebar">    

        <div class="inner-sidebar" id="side-info-column">
            <?php $wpcb->wpcb_sidebar(); ?>
        </div>
        
        <?php if(!isset($step) || $step == 1) : ?>

        <div id="post-body-content">
            <div class='postbox'>
                <h3>Sitewide Settings</h3>
                <div class='inside'>
                    <table class="form-table">
                        <tbody>
                            <tr>
                                <th scope="row"><label for=""> Default Conversion Box</label></th>
                                <td>
                                    <?php $wpcb->wpcb_box_list(get_option('wpcb_default_box'),'default','wpcb_boxes_list_default'); ?>
                                    <p class="wpcb_help_block">Assign a default conversion box to all pages and posts. This box will be used when no other box has been set for any post/page.</p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label for=""> Conversion Box for All Posts</label></th>
                                <td>
                                    <?php $wpcb->wpcb_box_list(get_option('wpcb_all_posts'),'','wpcb_boxes_list_posts'); ?>
                                    <p class="wpcb_help_block">Select a conversion box that'll be shown under all Blog Posts. This will override the default conversion box.</p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label for=""> Conversion Box for All Pages</label></th>
                                <td>
                                    <?php $wpcb->wpcb_box_list(get_option('wpcb_all_pages'),'','wpcb_boxes_list_pages'); ?>
                                        <p class="wpcb_help_block">Select a conversion box that'll be shown under all Pages of your site. This will override the default conversion box.</p>
                                </td>
                            </tr>
                            </tbody>
			</table>

                </div>
            </div>
            
            <div class="postbox opaque6">
                <h3>Conversion Boxes for Categories<?= $upgrade_message; ?></h3>
                <div class='inside'>
                        <table class="form-table">
                            <tbody>
                                <tr>
                                    <th scope="row"><label for="">Select Conversion Box</label></th>
                                    <td>
                                        <p class="wpcb_help_block">Select a conversion box for the posts of specific categories. This will override the Default Conversion Box and also Conversion Box for All Posts.</p>
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
                <h3>Help Us Spread The Word</h3>
                <div class='inside'>
                        <table class="form-table">
                            <tbody>
                                <tr>
                                    <th scope="row"><label for="">Show Link to WP Conversion Boxes Under Conversion Boxes</label></th>
                                    <td>
                                        <p><label><input type='checkbox' value='1' id='enable_credit_link' name='enable_credit_link' <?= (get_option('wpcb_enable_credit_link') == 1) ? 'checked' : '' ?>/> Enable Credit Link</label></p>
                                        <p class="wpcb_help_block">Place a small link to WP Conversion Boxes below your conversion boxes. This will help us spread the word out about the plugin and along with that, give you the fuzzy feeling of helping us out.</p>
                                    </td>
                                </tr>
                            </tbody>
			</table>
                </div>
            </div>
            
            <input type="submit" value="Update" class="button button-primary" name="update-global-settings" id="update-global-settings"/>
            
        </div>
        
        <?php elseif($step == 2 || !isset($step)) : ?>
        
        <div id="post-body-content">
            <div class='postbox'>
                <h3>Integrate Your Email Service</h3>
                <div class='inside'>
                    <table class="form-table">
                        <tbody>
                            <tr>
                                <th scope="row" style="max-width: 200px;"><label for="">Select Your Email Service Provider</label></th>
                                <td class="wpcb_mailers_td">
                                    <ul class="wpcb_mailers_list">
                                        
                                        
                                        <!-- GetResponse -->
                                        
                                        
                                        <li class="wpcb_mailer_li">
                                            <table>
                                                <tr>
                                                    <td width="200"><img src="<?php echo ADMIN_ASSETS_URL.'/imgs/getresponse-logo.png'; ?>" /></td>
                                                    <?php if(get_option('wpcb_getresponse_api_key') == '') : ?>
                                                        <td><button class="button-primary wpcb_mailer" data-mailer-id="1">Integrate</button></td>
                                                        <td><a class="button-primary" target="_blank" href="http://wpconversionboxes.com/email-services/getresponse/">Create Account For Free</a></td>
                                                    <?php else : ?>
                                                        <td>
                                                            <form action="" method="post">
                                                                <input type="submit" class="button-primary" name="disconnect" value="Disconnect">
                                                                <input type="hidden" name="mailer" value="1">
                                                            </form>
                                                        </td>
                                                    <?php endif; ?>
                                                </tr>
                                            </table>
                                            <?= (isset($showresponse_1)) ? $showresponse_1 : ''; ?>

                                                <div id="wpcb_mailer_1" class="wpcb_mailers_option">
                                                    <p>GetResponse API Key:</p>
                                                    <form method="post" action="">
                                                        <input type="text" name="apikey" value="" class="wpcb_fullwidth"/><br />
                                                        <div style="margin: 10px 0px 10px 0px;">
                                                            <input type="submit" class="button-primary" name="connect" value="Connect to GetResponse">
                                                            <button class="button-primary wpcb_mailer_cancel">Cancel</button>
                                                            <input type="hidden" name="mailer" value="1">
                                                        </div>
                                                    </form>
                                                    <p class="wpcb_help_block">This API key is used to add email leads to your GetResponse campaigns. You can find your API key from <a target="_blank" href="https://app.getresponse.com/account.html#api">this page</a>.</p>
                                                </div>

                                        </li>
                                        
                                        
                                        <!-- MailChimp -->
                                        
                                        
                                        <li class="wpcb_mailer_li">
                                            <table>
                                                <tr>
                                                    <td  style="width: 200px;"><img src="<?php echo ADMIN_ASSETS_URL.'/imgs/mailchimp-logo.png'; ?>" /></td>
                                                    <?php if(get_option('wpcb_mailchimp_api_key') == '') : ?>
                                                        <td><button class="button-primary wpcb_mailer" data-mailer-id="2">Integrate</button></td>
                                                        <td><a class="button-primary" target="_blank" href="http://wpconversionboxes.com/email-services/mailchimp/">Create Account For Free</a></td>
                                                    <?php else : ?>
                                                        <td>
                                                            <form action="" method="post">
                                                                <input type="submit" class="button-primary" name="disconnect" value="Disconnect">
                                                                <input type="hidden" name="mailer" value="2">
                                                            </form>
                                                        </td>
                                                    <?php endif; ?>
                                                </tr>
                                            </table>
                                            <?= (isset($showresponse_2)) ? $showresponse_2 : ''; ?>
                                            <div id="wpcb_mailer_2" class="wpcb_mailers_option">
                                                <p>MailChimp API Key:</p>
                                                <form method="post" action="">
                                                    <input type="text" name="apikey" value="" class="wpcb_fullwidth"/><br />
                                                    <div style="margin: 10px 0px 10px 0px;">
                                                        <input type="submit" class="button-primary" name="connect" value="Connect to MailChimp">
                                                        <button class="button-primary wpcb_mailer_cancel">Cancel</button>
                                                        <input type="hidden" name="mailer" value="2">
                                                    </div>
                                                </form>
                                                <p class="wpcb_help_block">This API key is used to add email leads to your MailChimp lists. You can find your API key from <a target="_blank" href="https://admin.mailchimp.com/account/api/">this page</a>. Go to the given link and just click on Create a Key to generate new API key.</p>
                                            </div>
                                        </li>
                                        
                                        <!-- Aweber -->
                                        
                                        <li class="wpcb_mailer_li">
                                            <table>
                                                <tr>
                                                    <td><img style="width: 200px;" src="<?php echo ADMIN_ASSETS_URL.'/imgs/aweber-logo.gif'; ?>" /></td>
                                                    <?php if(get_option('wpcb_aweber_api_key') == '') : ?>
                                                        <td><button class="button-primary wpcb_mailer" data-mailer-id="3">Integrate</button></td>
                                                        <td><a class="button-primary" target="_blank" href="http://wpconversionboxes.com/email-services/aweber/">Create Account For $1</a></td>
                                                    <?php else : ?>
                                                        <td>
                                                            <form action="" method="post">
                                                                <input type="submit" class="button-primary" name="disconnect" value="Disconnect">
                                                                <input type="hidden" name="mailer" value="3">
                                                            </form>
                                                        </td>
                                                    <?php endif; ?>
                                                </tr>
                                            </table>
                                            <?= (isset($showresponse_3)) ? $showresponse_3 : ''; ?>
                                                <div id="wpcb_mailer_3" class="wpcb_mailers_option">
                                                    <p>Aweber Authorization Code:</p>
                                                    <form method="post" action="">
                                                        <input type="text" name="apikey" value="" class="wpcb_fullwidth"/><br />
                                                        <div style="margin: 10px 0px 10px 0px;">
                                                            <input type="submit" class="button-primary" name="connect" value="Connect to Aweber">
                                                            <button class="button-primary wpcb_mailer_cancel">Cancel</button>
                                                            <input type="hidden" name="mailer" value="3">
                                                        </div>
                                                    </form>
                                                    <p class="wpcb_help_block">You need to authorize this plugin to be able to add leads to your Aweber lists. To get the authorization code, log into <a target="_blank" href="https://auth.aweber.com/1.0/oauth/authorize_app/459aa11b">this page</a> with your Aweber acoount.</p>
                                                </div>
                                        </li>
                                    </ul>
                                    <p class="wpcb_help_block">Click on <b>Integrate</b> button to integrate your email service provider. If you don't have an account, click on <b>Create Account</b> button to create a new account. If at any point you want to disconnect your email service provider click on <b>Disconnect</b> button to do so.</p>
                                </td>    
                            </tr>
                            <tr>
                                <th scope="row"><label for=""></label></th>
                                <td>

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
                    <h3>Upload Custom Template<?= $upgrade_message ?></h3>
                    <div class='inside'>
                        <table class="form-table">
                            <tbody>
                                <tr>
                                    <th scope="row"><label for=""> Upload Custom Template</label></th>
                                    <td>
                                        <form action="" method="post" enctype="multipart/form-data">
                                        <p><select disabled><option>Email Optin</option></select></p>
                                        <p class="wpcb_help_block">Please select template type of the zip file.</p><br />
                                        <p><label>Select zip file: <input type="file" name="wpcb_template_zip" id="wpcb_template_zip" disabled></label></p>
                                        <p class="wpcb_help_block">Upload your custom .zip template file here. This .zip file will be extracted to the <code>/templates/</code> directory of the plugin. Your template will be available to use on Select Box Template page. To know how make custom box templates, <a target="_blank">click here</a></p><br />
                                        <p><input type="submit" name="submit" value="Upload" class="button button-primary" disabled /></p>
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



<?= get_option('wpcb_mailchimp_api_key')."<br />";  ?>
<?= get_option('wpcb_mailchimp_lists'); ?>