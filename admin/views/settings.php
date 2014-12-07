<?php

/*************************************
* Global Settings Page
*************************************/

$wpcb_license = WPCB_Licensing::get_instance();

if(!$wpcb_license->is_license_valid()){
    $wpcb_license->license_activation_form();
}
else{

$wpcb_license->license_renew_notice();

$wpcb = WPCB_Admin::get_instance();
    
if(isset($_GET['step'])){
    $step = $_GET['step'];
}

/************************
* GetResponse : 1
* MailChimp : 2
* Aweber : 3
* MadMimi : 4
* Constant Contact : 5
* Campaign Monitor : 6
* Infusion Soft : 7
* iContact : 8
* MailPoet : 9
* Pardot : 10
*************************/

if(isset($_POST['mailer']) and isset($_POST['apikey']) and isset($_POST['connect'])){
    $mailer_id = $_POST['mailer'];
    $api_key = $_POST['apikey'];
    
    switch($mailer_id){

        //GetResponse
        case 1: include_once( MAILERS_DIR_PATH . 'getresponse-api.php');
                    $getresponse = new jsonRPCClient('http://api2.getresponse.com');
                    try{
                        $name = array();
                        $allgrcampaigns = $getresponse->get_campaigns($api_key);
                        foreach($allgrcampaigns as $grcampaign){
                            $campaign_name = $grcampaign['name'];
                            $upload_result = $getresponse->get_campaigns($api_key, array ('name' => array ( 'EQUALS' => $campaign_name )));
                            $res = array_keys($upload_result);
                            $campaign_id = array_pop($res);
                            $getresponse_campaigns[$campaign_id] = $campaign_name;
                        }
                        $getresponse_campaigns = serialize($getresponse_campaigns);
                        update_option('wpcb_getresponse_api_key',$api_key);
                        update_option('wpcb_getresponse_campaigns',$getresponse_campaigns);
                        echo "<div class='updated'><p>". __('Connected to API successfully.', 'wp-conversion-boxes-pro') ."<a href='' style='float:right;' onclick='jQuery(this).parent().parent().fadeOut(300).hide();'>Close</a></p></div>";
                    }
                    catch (Exception $e){
                        echo $e->getMessage();
                        echo "<div class='error'><p>". __('Invalid API Key. Please try again.', 'wp-conversion-boxes-pro') ."<a href='' style='float:right;' onclick='jQuery(this).parent().parent().fadeOut(300).hide();'>Close</a></p></div>";
                    }
                    echo "</ul>";
                break;
        
        // MailChimp
        case 2: include_once( MAILERS_DIR_PATH .'mailchimp-api.php');
                $mailchimp = new MCAPI($api_key);       
                $allmclists = $mailchimp->lists();
                if($allmclists['total'] == 0){
                    $showresponse_2 = "<div id='wpcb_error' style='margin: 0px 10px 10px 10px;'><p>". __('No lists found. Please create a list and try again later. ', 'wp-conversion-boxes-pro') ."<a href='' style='float:right;' onclick='jQuery(this).parent().parent().fadeOut(300).hide();'>Close</a></p></div>";
                }
                else{
                    foreach($allmclists['data'] as $mclist){
                        $mailchimp_lists[$mclist['id']] = $mclist['name'];
                    }
                    $mailchimp_lists_serialized = serialize($mailchimp_lists);
                    update_option('wpcb_mailchimp_api_key',$api_key);
                    update_option('wpcb_mailchimp_lists',$mailchimp_lists_serialized);
                    echo "<div class='updated'><p>". __('Connected to API successfully.', 'wp-conversion-boxes-pro') ."<a href='' style='float:right;' onclick='jQuery(this).parent().parent().fadeOut(300).hide();'>Close</a></p></div>";
                }
                break;
                
        // Aweber                
        case 3: include_once(MAILERS_DIR_PATH . 'aweber_api/aweber_api.php');
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
                        echo "<div class='updated'><p>". __('Connected to Aweber successfully.', 'wp-conversion-boxes-pro') ."<a href='' style='float:right;' onclick='jQuery(this).parent().parent().fadeOut(300).hide();'>Close</a></p></div>";
                }
                catch (Exception $ex) {
                        echo "<div class='error'><p>". __('Invalid authorization Key. Please try again.', 'wp-conversion-boxes-pro') ."<a href='' style='float:right;' onclick='jQuery(this).parent().parent().fadeOut(300).hide();'>Close</a></p></div>";
                }
                break;             
                
        // MadMimi
        case 4: if($_POST['madmimi_login'] != ''){
            
                    $madmimi_username = $_POST['madmimi_login'];
                    include_once( MAILERS_DIR_PATH . 'madmimi/MadMimi.class.php');
                    $madmimi = new MadMimi($madmimi_username, $api_key);
                    // If XML is not returned, we need to send an error message.
                    libxml_use_internal_errors(true);
                    $lists = simplexml_load_string($madmimi->Lists());
                    if (!$lists) {
                        echo "<div class='error'><p>". __('Invalid API Key. Please try again.', 'wp-conversion-boxes-pro') ."<a href='' style='float:right;' onclick='jQuery(this).parent().parent().fadeOut(300).hide();'>Close</a></p></div>";
                    } else {
                        $madmimi_data['api_key'] = $api_key;
                        $madmimi_data['username'] = $madmimi_username;

                        foreach($lists->list as $list){
                            $madmimi_lists[(string)$list['id']] = (string)$list['name'];
                        }
                        
                        $madmimi_data_serialized = serialize($madmimi_data);
                        $madmimi_lists_serialized = serialize($madmimi_lists);
                        update_option('wpcb_madmimi_api_key',$madmimi_data_serialized);
                        update_option('wpcb_madmimi_lists',$madmimi_lists_serialized);                        
                        echo "<div class='updated'><p>". __('Connected to MadMimi successfully.', 'wp-conversion-boxes-pro') ."<a href='' style='float:right;' onclick='jQuery(this).parent().parent().fadeOut(300).hide();'>Close</a></p></div>";
                    }
                }
                else{
                    echo "<div class='error'><p>". __('Please enter your MadMimi username/email id.', 'wp-conversion-boxes-pro') ."<a href='' style='float:right;' onclick='jQuery(this).parent().parent().fadeOut(300).hide();'>Close</a></p></div>";
                }
            
                
                break;
                
        // Constant Contact                
        case 5: 
                $constant_contact = wp_remote_get('https://api.constantcontact.com/v2/lists?api_key=wcp88fmhmyxmegwbpvcpz36x&access_token=' . $api_key);
                $all_constant_contact_lists = json_decode(wp_remote_retrieve_body($constant_contact));
                if((array) $all_constant_contact_lists[0]->error_key){
                    echo "<div class='error'><p>". __('Invalid access token. Please try again.', 'wp-conversion-boxes-pro') ."<a href='' style='float:right;' onclick='jQuery(this).parent().parent().fadeOut(300).hide();'>Close</a></p></div>";
                }
                else{
                    foreach ((array) $all_constant_contact_lists as $list){
                        $constant_contact_lists[$list->id] = $list->name;
                    }
                    $constant_contact_lists_serialized = serialize($constant_contact_lists);
                    update_option('wpcb_constant_contact_api_key',$api_key); // This is access token
                    update_option('wpcb_constant_contact_lists',$constant_contact_lists_serialized);  
                    echo "<div class='updated'><p>". __('Connected to Constant Contact successfully.', 'wp-conversion-boxes-pro') ."<a href='' style='float:right;' onclick='jQuery(this).parent().parent().fadeOut(300).hide();'>Close</a></p></div>";
                }
                
                
                break;
                
        // Campaign Monitor
        case 6: include_once( MAILERS_DIR_PATH . 'campaign-monitor/csrest_general.php');

                $campaign_monitor = new CS_Rest_General(array('api_key' => $api_key));
                $campaign_monitor_clients = $campaign_monitor->get_clients();

                if (!$campaign_monitor_clients->was_successful()) {
                    echo "<div class='error'><p>". __('Invalid API Key. Please try again.', 'wp-conversion-boxes-pro') ."<a href='' style='float:right;' onclick='jQuery(this).parent().parent().fadeOut(300).hide();'>Close</a></p></div>";
                } 
                else {
                    foreach ((array) $campaign_monitor_clients->response as $client){
                        //$client->ClientID = $client->Name ;
                        if(isset($_POST['cm_client_name']) && $_POST['cm_client_name'] == $client->Name){
                            $client_name = $client->Name;
                            $client_id = $client->ClientID;
                            break;
                        }
                    }
                    
                    if($client_name != '' && $client_id != ''){
                        include_once( MAILERS_DIR_PATH . 'campaign-monitor/csrest_clients.php');
                        $client = new CS_Rest_Clients($client_id, $api_key);
                        $lists = $client->get_lists();
                        foreach ((array) $lists->response as $list){
                            $campaign_monitor_lists[$list->ListID] = $list->Name;
                        }
                        $campaign_monitor_lists_serialized = serialize($campaign_monitor_lists);
                        update_option('wpcb_campaign_monitor_api_key',$api_key); 
                        update_option('wpcb_campaign_monitor_lists',$campaign_monitor_lists_serialized);  
                         echo "<div class='updated'><p>". __('Connected to Campaign Monitor successfully.', 'wp-conversion-boxes-pro') ."<a href='' style='float:right;' onclick='jQuery(this).parent().parent().fadeOut(300).hide();'>Close</a></p></div>";
                    }
                    else{
                        echo "<div class='error'><p>". __('Please enter a valid Client Name.', 'wp-conversion-boxes-pro') ."<a href='' style='float:right;' onclick='jQuery(this).parent().parent().fadeOut(300).hide();'>Close</a></p></div>";
                    }                    
                }
                break;
        // Infusionsoft (Not Tested)
        case 7: if(isset($_POST['subdomain_name']) && $_POST['subdomain_name'] != ''){
                    $subdomain_name = $_POST['subdomain_name'];
                    include_once( MAILERS_DIR_PATH . 'infusionsoft/isdk.php');
                    try {
                        $infusionsoft = new iSDK();
                        $infusionsoft->cfgCon($subdomain_name, $api_key, 'throw');
                    } catch (iSDKException $e) {
                        echo "<div class='error'><p>".sprintf( __('Sorry, but Infusionsoft was unable to grant access to your account data. Infusionsoft gave this response: <em>%s</em>. Please try entering your information again.', 'wp-conversion-boxes-pro' ), $e->getMessage())."</p></div>";
                        break;
                    }
                    $page = 0;
                    $all_res = array();
                    while (true) {
                        $res = $infusionsoft->dsQuery('ContactGroup', 1000, $page, array('Id' => '%'), array('Id', 'GroupName'));
                        $all_res = array_merge($all_res, $res);
                        if (count($res) < 1000)
                            break;

                        $page++;
                    }
                    foreach ((array) $all_res as $group){
                        $infusionsoft_lists[$group['Id']] = $group['GroupName'];
                    }
                    $infusionsoft_api_data = array('subdomain' => $subdomain_name, 'api_key' => $api_key);
                    $infusionsoft_api_data_serialized = serialize($infusionsoft_api_data);
                    $infusionsoft_lists_serialized = serialize($infusionsoft_lists);
                    update_option('wpcb_infusionsoft_api_key',$infusionsoft_api_data_serialized); 
                    update_option('wpcb_infusionsoft_lists',$infusionsoft_lists_serialized);  
                    echo "<div class='updated'><p>". __('Connected to Infusionsoft successfully.', 'wp-conversion-boxes-pro') ."<a href='' style='float:right;' onclick='jQuery(this).parent().parent().fadeOut(300).hide();'>Close</a></p></div>";
                }
                else{
                    echo "<div class='error'><p>". __('Please enter a valid sub-doamin name.', 'wp-conversion-boxes-pro') ."<a href='' style='float:right;' onclick='jQuery(this).parent().parent().fadeOut(300).hide();'>Close</a></p></div>";
                }
                break;
        // iContact
        case 8 :if($_POST['app_password'] != '' && $_POST['icontact_username'] != ''){
                    $username = $_POST['icontact_username'];
                    $app_password = $_POST['app_password'];
                    
                    include_once( MAILERS_DIR_PATH . 'icontact/iContactApi.php');
                    try {
                        iContactApi::getInstance()->setConfig(array(
                            'appId' => $api_key,
                            'apiPassword' => $app_password,
                            'apiUsername' => $username
                        ));
                        $icontact = iContactApi::getInstance();
                        $all_icontact_lists = $icontact->getLists();
                    } catch (Exception $e) {
                        $errors = $icontact->getErrors();
                        echo "<div class='error'><p>".sprintf( __('Sorry, but iContact was unable to grant access to your account data. iContact gave this response: <em>%s</em>. Please try entering your information again.', 'wp-conversion-boxes-pro'), $errors[0])."</p></div>";
                        break;
                    }
                    foreach ($all_icontact_lists as $list){
                        $icontact_lists[ $list->listId ] = $list->name ;
                    }
                    $icontact_api_data = array(
                        'api_key' => $api_key,
                        'app_password' => $app_password,
                        'username' => $username
                    );
                    $icontact_api_data_serialized = serialize($icontact_api_data);
                    $icontact_lists_serialized = serialize($icontact_lists);
                    update_option('wpcb_icontact_api_key',$icontact_api_data_serialized);
                    update_option('wpcb_icontact_lists',$icontact_lists_serialized);
                    echo "<div class='updated'><p>". __('Connected to iContact successfully.', 'wp-conversion-boxes-pro') ."<a href='' style='float:right;' onclick='jQuery(this).parent().parent().fadeOut(300).hide();'>Close</a></p></div>";
                }
                else{
                    echo "<div class='error'><p>". __('Please enter iContact Username/Application Password.', 'wp-conversion-boxes-pro') ."<a href='' style='float:right;' onclick='jQuery(this).parent().parent().fadeOut(300).hide();'>Close</a></p></div>";
                }    
                break;
        
        // MailPoet
        case 9: 
                break;
                
        // Pardot
        case 10:if($_POST['username'] == ''){
                    echo "<div class='error'><p>". __('Please enter Pardot Username.', 'wp-conversion-boxes-pro') ."<a href='' style='float:right;' onclick='jQuery(this).parent().parent().fadeOut(300).hide();'>Close</a></p></div>";
                }
                else if($_POST['password'] == ''){
                    echo "<div class='error'><p>". __('Please enter Pardot Password.', 'wp-conversion-boxes-pro') ."<a href='' style='float:right;' onclick='jQuery(this).parent().parent().fadeOut(300).hide();'>Close</a></p></div>";
                }
                else{
                    $username = $_POST['username'];
                    $password = $_POST['password'];
                    
                    include_once( MAILERS_DIR_PATH . 'pardot/pardot-api-class.php');
                    $pardot_api_data = array('email' => $username, 'password' => $password, 'user_key' => $api_key);
                    // Attempt to connect to the Pardot API to retrieve lists.
                    $pardot = new Pardot_OM_API( $pardot_api_data );
                    $all_pardot_lists = $pardot->get_campaigns();

                    // If there is an error, output and return early.
                    if ($pardot->error) {
                        echo "<div class='error'><p>". __('Unable to connect to Pardot. Please enter valid Username, Password and User Key.', 'wp-conversion-boxes-pro') ."<a href='' style='float:right;' onclick='jQuery(this).parent().parent().fadeOut(300).hide();'>Close</a></p></div>";
                        break;
                    }

                    foreach ($all_pardot_lists as $list_id => $list){
                        $pardot_lists[$id] = $list->name;
                    }
                    
                    $pardot_api_data_serialized = serialize($pardot_api_data);
                    $pardot_lists_serialized = serialize($pardot_lists);
                    update_option('wpcb_pardot_api_key',$pardot_api_data_serialized);
                    update_option('wpcb_pardot_lists',$pardot_lists_serialized);
                    echo "<div class='updated'><p>". __('Connected to Pardot successfully.', 'wp-conversion-boxes-pro') ."<a href='' style='float:right;' onclick='jQuery(this).parent().parent().fadeOut(300).hide();'>Close</a></p></div>";
                    
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
                echo "<div class='updated'><p>". __('GetResponse disconnected successfully.', 'wp-conversion-boxes-pro') ."</p></div>";
                break;
        //MailChimp    
        case 2: delete_option('wpcb_mailchimp_api_key');
                delete_option('wpcb_mailchimp_lists');
                echo "<div class='updated'><p>". __('MailChimp disconnected successfully.', 'wp-conversion-boxes-pro') ."</p></div>";
                break;    
        //Aweber
        case 3: delete_option('wpcb_aweber_api_key');
                delete_option('wpcb_aweber_lists');
                echo "<div class='updated'><p>". __('Aweber disconnected successfully.', 'wp-conversion-boxes-pro') ."</p></div>";
                break;
        //Mad Mimi
        case 4: delete_option('wpcb_madmimi_api_key');
                delete_option('wpcb_madmimi_lists');
                echo "<div class='updated'><p>". __('Mad Mimi disconnected successfully.', 'wp-conversion-boxes-pro') ."</p></div>";
                break;                    
        //Constant Contact
        case 5: delete_option('wpcb_constant_contact_api_key');
                delete_option('wpcb_constant_contact_lists');
                echo "<div class='updated'><p>". __('Constant Contact disconnected successfully.', 'wp-conversion-boxes-pro') ."</p></div>";
                break;                    
        //Campaign Monitor
        case 6: delete_option('wpcb_campaign_monitor_api_key');
                delete_option('wpcb_campaign_monitor_lists');
                echo "<div class='updated'><p>". __('Campaign Monitor disconnected successfully.', 'wp-conversion-boxes-pro') ."</p></div>";
                break;                    
        //Infusionsoft
        case 7: delete_option('wpcb_infusionsoft_api_key');
                delete_option('wpcb_infusionsoft_lists');
                echo "<div class='updated'><p>". __('Infusionsoft disconnected successfully.', 'wp-conversion-boxes-pro') ."</p></div>";
                break;                    
        //iContact
        case 8: delete_option('wpcb_icontact_api_key');
                delete_option('wpcb_icontact_lists');
                echo "<div class='updated'><p>". __('iContact disconnected successfully.', 'wp-conversion-boxes-pro') ."</p></div>";
                break;                             
        //Pardot
        case 10:delete_option('wpcb_pardot_api_key');
                delete_option('wpcb_pardot_lists');
                echo "<div class='updated'><p>". __('Pardot disconnected successfully.', 'wp-conversion-boxes-pro') ."</p></div>";
                break;
    }
}

// Upload and extract zip if uploaded
if(isset($_FILES['wpcb_template_zip'])){
    $wpcb->upload_custom_template($_FILES['wpcb_template_zip']);
}

?>

<div class="wrap wpcb-wrapper">
    
    <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
    
    <h2 class="nav-tab-wrapper">
        <a href="<?php echo admin_url( 'admin.php?page=' . $wpcb->wpcb_settings_slug )."&step=1"; ?>" class="nav-tab <?php if($step == 1 || !isset($step)) echo "nav-tab-active"; ?>"><?php _e('General Settings', 'wp-conversion-boxes-pro'); ?></a>
        <a href="<?php echo admin_url( 'admin.php?page=' . $wpcb->wpcb_settings_slug )."&step=2"; ?>" class="nav-tab <?php if($step == 2) echo "nav-tab-active"; ?>"><?php _e('Email Service Integration', 'wp-conversion-boxes-pro'); ?></a>
        <a href="<?php echo admin_url( 'admin.php?page=' . $wpcb->wpcb_settings_slug )."&step=3"; ?>" class="nav-tab <?php if($step == 3) echo "nav-tab-active"; ?>"><?php _e('Upload Custom Template', 'wp-conversion-boxes-pro'); ?></a>
        <a href="<?php echo admin_url( 'admin.php?page=' . $wpcb->wpcb_settings_slug )."&step=4"; ?>" class="nav-tab <?php if($step == 4) echo "nav-tab-active"; ?>"><?php _e('Export/Import', 'wp-conversion-boxes-pro'); ?></a>
        <a href="<?php echo admin_url( 'admin.php?page=' . $wpcb->wpcb_settings_slug )."&step=5"; ?>" class="nav-tab <?php if($step == 5) echo "nav-tab-active"; ?>"><?php _e('License', 'wp-conversion-boxes-pro'); ?></a>
    </h2>
    
    <div id="poststuff" class="metabox-holder has-right-sidebar">    

        <div class="inner-sidebar" id="side-info-column">
            <?php $wpcb->wpcb_sidebar(); ?>
        </div>
        
        <?php if(!isset($step) || $step == 1) : ?>

        <div id="post-body-content">
            
            <div class='postbox'>
                <h3><?php _e("Google Analytics' Event Tracking", 'wp-conversion-boxes-pro'); ?></h3>
                <div class='inside'>
                    <table class="form-table">
                        <tbody>
                            <tr>
                                <th scope="row"><label for=""><?php _e('Enable Event Tracking', 'wp-conversion-boxes-pro');; ?></label></th>
                                <td>
                                    <p><label><input type="checkbox" name="wpcb_ga_tracking" id="wpcb_ga_tracking" <?php echo (get_option('wpcb_ga_tracking') != 0) ? "checked" : "" ?> /> <?php _e('Enable', 'wp-conversion-boxes-pro'); ?></label></p>
                                    <p class="wpcb_help_block"><?php _e("Super charge your conversion tracking by enabling <b>Google Analytics' Event Tracking</b> for your conversion boxes. <b>NOTE:</b> Enabling this will disable plugin's conversion tracking and you'll have to check the conversion stats on Google Analytics website. Also you must have latest Google Analytics code installed on your site.", 'wp-conversion-boxes-pro'); ?> <a href="http://wpconversionboxes.com/docs/google-analytics-event-tracking-in-wp-conversion-boxes-pro/" target="_blank"><?php _e('Learn More','wp-conversion-boxes-pro') ?></a></p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class='postbox'>
                <h3><?php _e('Sitewide Settings', 'wp-conversion-boxes-pro'); ?></h3>
                <div class='inside'>
                    <table class="form-table">
                        <tbody>
                            <tr>
                                <th scope="row"><label for=""><?php _e('Default Conversion Box', 'wp-conversion-boxes-pro'); ?></label></th>
                                <td>
                                    <?php $wpcb->wpcb_box_list(get_option('wpcb_default_box'),'default','wpcb_boxes_list_default'); ?>
                                    <p class="wpcb_help_block"><?php _e("Assign a default conversion box to all pages and posts. This box will be used when no other box has been set for any post/page.", 'wp-conversion-boxes-pro'); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label for=""><?php _e('Conversion Box for All Posts', 'wp-conversion-boxes-pro'); ?></label></th>
                                <td>
                                    <?php $wpcb->wpcb_box_list(get_option('wpcb_all_posts'),'','wpcb_boxes_list_posts'); ?>
                                    <p class="wpcb_help_block"><?php _e("Select a conversion box that'll be shown under all Blog Posts. This will override the default conversion box.", 'wp-conversion-boxes-pro'); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label for=""><?php _e('Conversion Box for All Pages', 'wp-conversion-boxes-pro'); ?></label></th>
                                <td>
                                    <?php $wpcb->wpcb_box_list(get_option('wpcb_all_pages'),'','wpcb_boxes_list_pages'); ?>
                                    <p class="wpcb_help_block"><?php _e("Select a conversion box that'll be shown under all Pages of your site. This will override the default conversion box.", 'wp-conversion-boxes-pro'); ?></p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="postbox">
                <h3><?php _e('Conversion Boxes for Categories', 'wp-conversion-boxes-pro'); ?></h3>
                <div class='inside'>
                        <table class="form-table">
                            <tbody>
                                <tr>
                                    <th scope="row"><label for=""><?php _e('Select Conversion Box', 'wp-conversion-boxes-pro'); ?></label></th>
                                    <td>
                                        <p class="wpcb_help_block"><?php _e("Select a conversion box for the posts of specific categories. This will override the Default Conversion Box and also Conversion Box for All Posts.", 'wp-conversion-boxes-pro'); ?></p>
                                    </td>
                                </tr>
                            </tbody>
			</table>
                        <div >
                            <?php $wpcb->wpcb_category_wise_box_list(); ?>
                        </div>
                </div>
            </div>
            
            <div class="postbox">
                <h3><?php _e('Help Us Spread The Word', 'wp-conversion-boxes-pro'); ?></h3>
                <div class='inside'>
                        <table class="form-table">
                            <tbody>
                                <tr>
                                    <th scope="row"><label for=""><?php _e('Show Link to WP Conversion Boxes Under Conversion Boxes', 'wp-conversion-boxes-pro'); ?></label></th>
                                    <td>
                                        <p><label><input type='checkbox' value='1' id='enable_credit_link' name='enable_credit_link' <?php echo (get_option('wpcb_enable_credit_link') == 1) ? 'checked' : '' ?>/> <?php _e('Enable Credit Link', 'wp-conversion-boxes-pro'); ?></label></p>
                                        <p class="wpcb_help_block"><?php _e("Place a small link to WP Conversion Boxes below your conversion boxes. This will help us spread the word out about the plugin and along with that, give you the fuzzy feeling of helping us out.", 'wp-conversion-boxes-pro'); ?></p>
                                    </td>
                                </tr>
                            </tbody>
			</table>
                </div>
            </div>
            
            <input type="submit" value="<?php _e('Update', 'wp-conversion-boxes-pro'); ?>" class="button button-primary" name="update-global-settings" id="update-global-settings"/>
            
        </div>
        
        <?php elseif($step == 2) : ?>
        
        <div id="post-body-content">
            <div class='postbox'>
                <h3><?php _e('Integrate Your Email Marketing Tool', 'wp-conversion-boxes-pro'); ?></h3>
                <div class='inside'>
                    <table class="form-table">
                        <tbody>
                            <tr>
                                <th scope="row" style="max-width: 200px;"><label for=""><?php _e('Integrate Your Email Service Provider', 'wp-conversion-boxes-pro'); ?></label><p class="wpcb_help_block" style="font-weight: normal;"><?php _e("Click on <b>Integrate</b> button to integrate your email service provider. If you don't have an account, click on <b>Create Account</b> button to create a new account. If at any point you want to disconnect your email service provider click on <b>Disconnect</b> button to do so.", 'wp-conversion-boxes-pro'); ?></p></th>
                                <td class="wpcb_mailers_td">
                                    <ul class="wpcb_mailers_list">
                                        
                                        
                                        <!-- GetResponse -->
                                        
                                        
                                        <li class="wpcb_mailer_li">
                                            <table>
                                                <tr>
                                                    <td width="200"><img src="<?php echo ADMIN_ASSETS_URL.'imgs/getresponse-logo.png'; ?>" /></td>
                                                    <?php if(get_option('wpcb_getresponse_api_key') == '') : ?>
                                                        <td><button class="button-primary wpcb_mailer" data-mailer-id="1"><?php _e('Integrate','wp-conversion-boxes-pro'); ?></button></td>
                                                        <td><a class="button-primary" target="_blank" href="http://wpconversionboxes.com/email-services/getresponse/"><?php _e('Create Account For Free','wp-conversion-boxes-pro'); ?></a></td>
                                                    <?php else : ?>
                                                        <td>
                                                            <form action="" method="post">
                                                                <input type="submit" class="button-primary" name="disconnect" value="<?php _e('Disconnect','wp-conversion-boxes-pro'); ?>">
                                                                <input type="hidden" name="mailer" value="1">
                                                            </form>
                                                        </td>
                                                    <?php endif; ?>
                                                </tr>
                                            </table>
                                            <div id="wpcb_mailer_1" class="wpcb_mailers_option">
                                                <p><?php _e("GetResponse API Key:", 'wp-conversion-boxes-pro'); ?></p>
                                                <form method="post" action="">
                                                    <input type="text" name="apikey" value="" class="wpcb_fullwidth"/><br />
                                                    <div style="margin: 10px 0px 10px 0px;">
                                                        <input type="submit" class="button-primary" name="connect" value="<?php _e('Connect to GetResponse','wp-conversion-boxes-pro'); ?>">
                                                        <button class="button-primary wpcb_mailer_cancel"><?php _e('Cancel','wp-conversion-boxes-pro'); ?></button>
                                                        <input type="hidden" name="mailer" value="1">
                                                    </div>
                                                </form>
                                                <p class="wpcb_help_block"><?php _e("This API key is used to add email leads to your GetResponse campaigns. You can find your API key from <a target='_blank' href='https://app.getresponse.com/account.html#api'>this page</a>.", 'wp-conversion-boxes-pro'); ?></p>
                                            </div>

                                        </li>
                                        
                                        
                                        <!-- MailChimp -->
                                        
                                        
                                        <li class="wpcb_mailer_li">
                                            <table>
                                                <tr>
                                                    <td  style="width: 200px;"><img src="<?php echo ADMIN_ASSETS_URL.'imgs/mailchimp-logo.png'; ?>" /></td>
                                                    <?php if(get_option('wpcb_mailchimp_api_key') == '') : ?>
                                                        <td><button class="button-primary wpcb_mailer" data-mailer-id="2"><?php _e('Integrate','wp-conversion-boxes-pro'); ?></button></td>
                                                        <td><a class="button-primary" target="_blank" href="http://wpconversionboxes.com/email-services/mailchimp/"><?php _e('Create Account For Free','wp-conversion-boxes-pro'); ?></a></td>
                                                    <?php else : ?>
                                                        <td>
                                                            <form action="" method="post">
                                                                <input type="submit" class="button-primary" name="disconnect" value="<?php _e('Disconnect','wp-conversion-boxes-pro'); ?>">
                                                                <input type="hidden" name="mailer" value="2">
                                                            </form>
                                                        </td>
                                                    <?php endif; ?>
                                                </tr>
                                            </table>
                                            <div id="wpcb_mailer_2" class="wpcb_mailers_option">
                                                <p><?php _e("MailChimp API Key:", 'wp-conversion-boxes-pro'); ?></p>
                                                <form method="post" action="">
                                                    <input type="text" name="apikey" value="" class="wpcb_fullwidth"/><br />
                                                    <div style="margin: 10px 0px 10px 0px;">
                                                        <input type="submit" class="button-primary" name="connect" value="<?php _e('Connect to MailChimp','wp-conversion-boxes-pro'); ?>">
                                                        <button class="button-primary wpcb_mailer_cancel"><?php _e('Cancel','wp-conversion-boxes-pro'); ?></button>
                                                        <input type="hidden" name="mailer" value="2">
                                                    </div>
                                                </form>
                                                <p class="wpcb_help_block"><?php _e("This API key is used to add email leads to your MailChimp lists. You can find your API key from <a target='_blank' href='https://admin.mailchimp.com/account/api/'>this page</a>. Go to the given link and just click on Create a Key to generate new API key.", 'wp-conversion-boxes-pro'); ?></p>
                                            </div>
                                        </li>
                                        
                                        <!-- Aweber -->
                                        
                                        <li class="wpcb_mailer_li">
                                            <table>
                                                <tr>
                                                    <td><img style="width: 200px;" src="<?php echo ADMIN_ASSETS_URL.'imgs/aweber-logo.gif'; ?>" /></td>
                                                    <?php if(get_option('wpcb_aweber_api_key') == '') : ?>
                                                        <td><button class="button-primary wpcb_mailer" data-mailer-id="3"><?php _e('Integrate','wp-conversion-boxes-pro'); ?></button></td>
                                                        <td><a class="button-primary" target="_blank" href="http://wpconversionboxes.com/email-services/aweber/"><?php _e('Create Account For $1','wp-conversion-boxes-pro'); ?></a></td>
                                                    <?php else : ?>
                                                        <td>
                                                            <form action="" method="post">
                                                                <input type="submit" class="button-primary" name="disconnect" value="<?php _e('Disconnect','wp-conversion-boxes-pro'); ?>">
                                                                <input type="hidden" name="mailer" value="3">
                                                            </form>
                                                        </td>
                                                    <?php endif; ?>
                                                </tr>
                                            </table>
                                            <div id="wpcb_mailer_3" class="wpcb_mailers_option">
                                                <p><?php _e("Aweber Authorization Code:", 'wp-conversion-boxes-pro'); ?></p>
                                                <form method="post" action="">
                                                    <input type="text" name="apikey" value="" class="wpcb_fullwidth"/><br />
                                                    <div style="margin: 10px 0px 10px 0px;">
                                                        <input type="submit" class="button-primary" name="connect" value="<?php _e('Connect to Aweber','wp-conversion-boxes-pro'); ?>">
                                                        <button class="button-primary wpcb_mailer_cancel"><?php _e('Cancel','wp-conversion-boxes-pro'); ?></button>
                                                        <input type="hidden" name="mailer" value="3">
                                                    </div>
                                                </form>
                                                <p class="wpcb_help_block"><?php _e("You need to authorize this plugin to be able to add leads to your Aweber lists. To get the authorization code, log into <a target='_blank' href='https://auth.aweber.com/1.0/oauth/authorize_app/459aa11b'>this page</a> with your Aweber acoount.", 'wp-conversion-boxes-pro'); ?></p>
                                            </div>
                                        </li>
                                        
                                        <!-- MadMimi -->
                                        
                                        <li class="wpcb_mailer_li">
                                            <table>
                                                <tr>
                                                    <td><img style="width: 200px;" src="<?php echo ADMIN_ASSETS_URL.'imgs/madmimi-logo.png'; ?>" /></td>
                                                    <?php if(get_option('wpcb_madmimi_api_key') == '') : ?>
                                                        <td><button class="button-primary wpcb_mailer" data-mailer-id="4"><?php _e('Integrate','wp-conversion-boxes-pro'); ?></button></td>
                                                        <td><a class="button-primary" target="_blank" href="http://wpconversionboxes.com/email-services/madmimi/"><?php _e('Create Account For Free','wp-conversion-boxes-pro'); ?></a></td>
                                                    <?php else : ?>
                                                        <td>
                                                            <form action="" method="post">
                                                                <input type="submit" class="button-primary" name="disconnect" value="<?php _e('Disconnect','wp-conversion-boxes-pro'); ?>">
                                                                <input type="hidden" name="mailer" value="4">
                                                            </form>
                                                        </td>
                                                    <?php endif; ?>
                                                </tr>
                                            </table>
                                            <div id="wpcb_mailer_4" class="wpcb_mailers_option">
                                                <form method="post" action="">
                                                    <p><?php _e("MadMimi API Key:", 'wp-conversion-boxes-pro'); ?></p>
                                                    <input type="text" name="apikey" value="" class="wpcb_fullwidth"/><br />
                                                    <p><?php _e("MadMimi username or email", 'wp-conversion-boxes-pro'); ?></p>
                                                    <input type="text" name="madmimi_login" value="" class="wpcb_fullwidth"/>
                                                    <div style="margin: 10px 0px 10px 0px;">
                                                        <input type="submit" class="button-primary" name="connect" value="<?php _e('Connect to MadMimi','wp-conversion-boxes-pro'); ?>">
                                                        <button class="button-primary wpcb_mailer_cancel"><?php _e('Cancel','wp-conversion-boxes-pro'); ?></button>
                                                        <input type="hidden" name="mailer" value="4">
                                                    </div>
                                                </form>
                                                <p class="wpcb_help_block"><?php _e("You need to authorize this plugin to be able to add leads to your Mad Mimi lists. Please enter your MadMimi API key (<a href='http://help.madmimi.com/where-can-i-find-my-api-key/' target='_blank'>get it here</a>) and MadMimi account username and click on Connect to MadMimi", 'wp-conversion-boxes-pro'); ?></p>
                                            </div>
                                        </li>
                                        
                                        <!-- Constant Contact = 5 -->
                                        
                                        <li class="wpcb_mailer_li">
                                            <table>
                                                <tr>
                                                    <td><img style="width: 200px;" src="<?php echo ADMIN_ASSETS_URL.'imgs/constant-contact-logo.png'; ?>" /></td>
                                                    <?php if(get_option('wpcb_constant_contact_api_key') == '') : ?>
                                                        <td><button class="button-primary wpcb_mailer" data-mailer-id="5"><?php _e('Integrate','wp-conversion-boxes-pro'); ?></button></td>
                                                        <td><a class="button-primary" target="_blank" href="http://wpconversionboxes.com/email-services/constant-contact/"><?php _e('Create Account For Free','wp-conversion-boxes-pro'); ?></a></td>
                                                    <?php else : ?>
                                                        <td>
                                                            <form action="" method="post">
                                                                <input type="submit" class="button-primary" name="disconnect" value="<?php _e('Disconnect','wp-conversion-boxes-pro'); ?>">
                                                                <input type="hidden" name="mailer" value="5">
                                                            </form>
                                                        </td>
                                                    <?php endif; ?>
                                                </tr>
                                            </table>
                                            <div id="wpcb_mailer_5" class="wpcb_mailers_option">
                                                <p><?php _e("Constant Contact Access Token:", 'wp-conversion-boxes-pro'); ?></p>
                                                <form method="post" action="">
                                                    <input type="text" name="apikey" value="" class="wpcb_fullwidth"/><br />
                                                    <div style="margin: 10px 0px 10px 0px;">
                                                        <input type="submit" class="button-primary" name="connect" value="<?php _e('Connect to Constant Contact','wp-conversion-boxes-pro'); ?>">
                                                        <button class="button-primary wpcb_mailer_cancel"><?php _e('Cancel','wp-conversion-boxes-pro'); ?></button>
                                                        <input type="hidden" name="mailer" value="5">
                                                    </div>
                                                </form>
                                                <p class="wpcb_help_block"><?php _e("You need to authorize this plugin to be able to add leads to your Constant Contact lists. To get the access token go to <a href='https://oauth2.constantcontact.com/oauth2/callback.htm?client_id=wcp88fmhmyxmegwbpvcpz36x' target='_blank'>this page</a> and Allow Access to WP Conversion Boxes Pro.", 'wp-conversion-boxes-pro'); ?></p>
                                            </div>
                                        </li>
                                        
                                        <!-- Campaign Monitor = 6 -->
                                        
                                        <li class="wpcb_mailer_li">
                                            <table>
                                                <tr>
                                                    <td><img style="width: 200px;" src="<?php echo ADMIN_ASSETS_URL.'imgs/campaign-monitor-logo.png'; ?>" /></td>
                                                    <?php if(get_option('wpcb_campaign_monitor_api_key') == '') : ?>
                                                        <td><button class="button-primary wpcb_mailer" data-mailer-id="6"><?php _e('Integrate','wp-conversion-boxes-pro'); ?></button></td>
                                                        <td><a class="button-primary" target="_blank" href="http://wpconversionboxes.com/email-services/campaign-monitor/"><?php _e('Create Account For Free','wp-conversion-boxes-pro'); ?></a></td>
                                                    <?php else : ?>
                                                        <td>
                                                            <form action="" method="post">
                                                                <input type="submit" class="button-primary" name="disconnect" value="<?php _e('Disconnect','wp-conversion-boxes-pro'); ?>">
                                                                <input type="hidden" name="mailer" value="6">
                                                            </form>
                                                        </td>
                                                    <?php endif; ?>
                                                </tr>
                                            </table>
                                            <div id="wpcb_mailer_6" class="wpcb_mailers_option">
                                                <form method="post" action="">
                                                    <p><?php _e("Client Name:", 'wp-conversion-boxes-pro'); ?></p>
                                                    <p><input type="text" name="cm_client_name" value="" class="wpcb_fullwidth"/><br /></p>
                                                    <p><?php _e("Campaign Monitor API Key:", 'wp-conversion-boxes-pro'); ?></p>
                                                    <p><input type="text" name="apikey" value="" class="wpcb_fullwidth"/><br /></p>
                                                    <div style="margin: 10px 0px 10px 0px;">
                                                        <input type="submit" class="button-primary" name="connect" value="<?php _e('Connect to Campaign Monitor','wp-conversion-boxes-pro'); ?>">
                                                        <button class="button-primary wpcb_mailer_cancel"><?php _e('Cancel','wp-conversion-boxes-pro'); ?></button>
                                                        <input type="hidden" name="mailer" value="6">
                                                    </div>
                                                </form>
                                                <p class="wpcb_help_block"><?php _e("You need to authorize this plugin to be able to add leads to your Campaign Monitor lists. Enter the Client Name above along with API Key (You can get the Client Name from Clients tab and the API key is present in Account Settings tab.)", 'wp-conversion-boxes-pro'); ?></p>
                                            </div>
                                        </li>
                                        
                                        <!-- InfusionSoft = 7 -->
                                        
                                        <li class="wpcb_mailer_li">
                                            <table>
                                                <tr>
                                                    <td><img style="width: 200px;" src="<?php echo ADMIN_ASSETS_URL.'imgs/infusionsoft-logo.png'; ?>" /></td>
                                                    <?php if(get_option('wpcb_infusionsoft_api_key') == '') : ?>
                                                        <td><button class="button-primary wpcb_mailer" data-mailer-id="7"><?php _e('Integrate','wp-conversion-boxes-pro'); ?></button></td>
                                                        <td><a class="button-primary" target="_blank" href="http://wpconversionboxes.com/email-services/infusionsoft/"><?php _e('Create a New Account','wp-conversion-boxes-pro'); ?></a></td>
                                                    <?php else : ?>
                                                        <td>
                                                            <form action="" method="post">
                                                                <input type="submit" class="button-primary" name="disconnect" value="<?php _e('Disconnect','wp-conversion-boxes-pro'); ?>">
                                                                <input type="hidden" name="mailer" value="7">
                                                            </form>
                                                        </td>
                                                    <?php endif; ?>
                                                </tr>
                                            </table>
                                            <div id="wpcb_mailer_7" class="wpcb_mailers_option">
                                                <form method="post" action="">
                                                    <p><?php _e("Infusionsoft Sub-domain Name:", 'wp-conversion-boxes-pro'); ?></p>
                                                    <p><input type="text" name="subdomain_name" value="" class="wpcb_fullwidth"/><br /></p>
                                                    <p><?php _e("Infusionsoft API Key:", 'wp-conversion-boxes-pro'); ?></p>
                                                    <p><input type="text" name="apikey" value="" class="wpcb_fullwidth"/><br /></p>
                                                    <div style="margin: 10px 0px 10px 0px;">
                                                        <input type="submit" class="button-primary" name="connect" value="<?php _e('Connect to Infusionsoft','wp-conversion-boxes-pro'); ?>">
                                                        <button class="button-primary wpcb_mailer_cancel"><?php _e('Cancel','wp-conversion-boxes-pro'); ?></button>
                                                        <input type="hidden" name="mailer" value="7">
                                                    </div>
                                                </form>
                                                <p class="wpcb_help_block"><?php _e("You need to authorize this plugin to be able to add leads to your Infusion Soft lists. Enter your Infusionsoft subdomain and API key above (know how to get it <a href='http://ug.infusionsoft.com/article/AA-00442/0/Infusionsoft-API-Key.html' target='_blank'>here</a>) and click on Connect to Infusionsoft", 'wp-conversion-boxes-pro'); ?></p>
                                            </div>
                                        </li>
                                        
                                        <!-- iContact = 8 -->
                                        
                                        <li class="wpcb_mailer_li">
                                            <table>
                                                <tr>
                                                    <td><img style="width: 200px;" src="<?php echo ADMIN_ASSETS_URL.'imgs/icontact-logo.png'; ?>" /></td>
                                                    <?php if(get_option('wpcb_icontact_api_key') == '') : ?>
                                                        <td><button class="button-primary wpcb_mailer" data-mailer-id="8"><?php _e('Integrate','wp-conversion-boxes-pro'); ?></button></td>
                                                        <td><a class="button-primary" target="_blank" href="http://wpconversionboxes.com/email-services/icontact/"><?php _e('Create Account For Free','wp-conversion-boxes-pro'); ?></a></td>
                                                    <?php else : ?>
                                                        <td>
                                                            <form action="" method="post">
                                                                <input type="submit" class="button-primary" name="disconnect" value="<?php _e('Disconnect','wp-conversion-boxes-pro'); ?>">
                                                                <input type="hidden" name="mailer" value="8">
                                                            </form>
                                                        </td>
                                                    <?php endif; ?>
                                                </tr>
                                            </table>
                                            <div id="wpcb_mailer_8" class="wpcb_mailers_option">
                                                <form method="post" action="">
                                                    <p><?php _e("iContact Username:", 'wp-conversion-boxes-pro'); ?></p>
                                                    <p><input type="text" name="icontact_username" value="" class="wpcb_fullwidth"/><br /></p>
                                                    <p><?php _e("iContact Application ID:", 'wp-conversion-boxes-pro'); ?></p>
                                                    <p><input type="text" name="apikey" value="" class="wpcb_fullwidth"/><br /></p>
                                                    <p><?php _e("iContact Application Password (This is not your account password.):", 'wp-conversion-boxes-pro'); ?></p>
                                                    <p><input type="password" name="app_password" value="" class="wpcb_fullwidth"/><br /></p>
                                                    <div style="margin: 10px 0px 10px 0px;">
                                                        <input type="submit" class="button-primary" name="connect" value="<?php _e('Connect to iContact','wp-conversion-boxes-pro'); ?>">
                                                        <button class="button-primary wpcb_mailer_cancel"><?php _e('Cancel','wp-conversion-boxes-pro'); ?></button>
                                                        <input type="hidden" name="mailer" value="8">
                                                    </div>
                                                </form>
                                                <p class="wpcb_help_block"><?php _e("You need to create a new iContact App to be able to add leads to your iContact lists. First go to <a href='https://app.icontact.com/icp/core/registerapp/' target='_blank'>this link</a> to create an app by entering a name and description. On the next page select Show Information for <b>API 2.0</b> and click <b>enable this AppId for your account.</b> On next page create a password, copy Application ID and hit save.", 'wp-conversion-boxes-pro'); ?></p>
                                            </div>
                                        </li>
                                        
                                        <!-- Pardot -->
                                        
                                        <li class="wpcb_mailer_li">
                                            <table>
                                                <tr>
                                                    <td><img style="width: 200px;" src="<?php echo ADMIN_ASSETS_URL.'imgs/pardot-logo.png'; ?>" /></td>
                                                    <?php if(get_option('wpcb_pardot_api_key') == '') : ?>
                                                        <td><button class="button-primary wpcb_mailer" data-mailer-id="10"><?php _e('Integrate','wp-conversion-boxes-pro'); ?></button></td>
                                                        <td><a class="button-primary" target="_blank" href="http://wpconversionboxes.com/email-services/pardot/"><?php _e('Create a New Account','wp-conversion-boxes-pro'); ?></a></td>
                                                    <?php else : ?>
                                                        <td>
                                                            <form action="" method="post">
                                                                <input type="submit" class="button-primary" name="disconnect" value="<?php _e('Disconnect','wp-conversion-boxes-pro'); ?>">
                                                                <input type="hidden" name="mailer" value="10">
                                                            </form>
                                                        </td>
                                                    <?php endif; ?>
                                                </tr>
                                            </table>
                                            <div id="wpcb_mailer_10" class="wpcb_mailers_option">
                                                <form method="post" action="">
                                                    <p><?php _e("Pardot Username:", 'wp-conversion-boxes-pro'); ?></p>
                                                    <p><input type="text" name="username" value="" class="wpcb_fullwidth"/><br /></p>
                                                    <p><?php _e("Pardot Password:", 'wp-conversion-boxes-pro'); ?></p>
                                                    <p><input type="password" name="password" value="" class="wpcb_fullwidth"/><br /></p>
                                                    <p><?php _e("Pardot User Key", 'wp-conversion-boxes-pro'); ?></p>
                                                    <p><input type="text" name="apikey" value="" class="wpcb_fullwidth"/><br /></p>
                                                    <div style="margin: 10px 0px 10px 0px;">
                                                        <input type="submit" class="button-primary" name="connect" value="<?php _e('Connect to Pardot','wp-conversion-boxes-pro'); ?>">
                                                        <button class="button-primary wpcb_mailer_cancel"><?php _e('Cancel','wp-conversion-boxes-pro'); ?></button>
                                                        <input type="hidden" name="mailer" value="10">
                                                    </div>
                                                </form>
                                                <p class="wpcb_help_block"><?php _e("You need to authorize this plugin to be able to add leads to your Pardot lists. Enter your account username, password and user key above and click on Connect to Pardot", 'wp-conversion-boxes-pro'); ?></p>
                                            </div>
                                        </li>
                                        
                                        
                                    </ul>
                                </td>    
                            </tr>
                            <tr>
                                <th scope="row"><label for=""><?php _e('Integrate WordPress Plugin', 'wp-conversion-boxes-pro'); ?></label><p class="wpcb_help_block" style="font-weight: normal;"><?php _e("Integrate your email marketing WordPress plugin with WP Conversion Boxes Pro. Most of the plugins are auto-integrated and fetch your lists directly on the <b>Customize Box</b> page.", 'wp-conversion-boxes-pro'); ?></p></th>
                                <td class="wpcb_mailers_td">
                                    <ul class="wpcb_mailers_list">
                                    <!-- MailPoet -->
                                        
                                        <li class="wpcb_mailer_li">
                                            <table>
                                                <tr>
                                                    <td><img style="width: 200px;" src="<?php echo ADMIN_ASSETS_URL.'imgs/mailpoet-logo.png'; ?>" /></td>
                                                    <td><a class="button-primary" target="_blank" href="http://wpconversionboxes.com/email-services/mailpoet/"><?php _e('Download WordPress Plugin','wp-conversion-boxes-pro'); ?></a></td>
                                                    <td>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" style="padding-top: 0px;"><p class="wpcb_help_block"><?php _e("When you install MailPoet (Wysija) plugin, this plugin will automagically pull your mailing lists on Customize Box page. No setup required!", 'wp-conversion-boxes-pro'); ?></p></td>
                                                </tr>
                                            </table>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label for=""><?php _e('Custom HTML Form For Lists', 'wp-conversion-boxes-pro'); ?></label><p class="wpcb_help_block" style="font-weight: normal;"><?php _e("You can connect to any other email provider that offers an HTML form for subscribing to your list. Click on Add New Custom List and paste the contents of the HTML code given by your provider and click on Process Code button. We'll extract all the important details from the form and create a custom list for you. Make sure you don't have any <code>&lt;script&gt;</code> or <code>&lt;iframe&gt;</code> tags in the code as it'll not be processed.", 'wp-conversion-boxes-pro'); ?></p></th>
                                <td class="wpcb_mailers_td" style="vertical-align: top;">
                                    <ul class="wpcb_mailers_list">
                                        <li class="wpcb_mailer_li">
                                            <table>
                                                <tr>
                                                    <td><img style="width: 200px;" src="<?php echo ADMIN_ASSETS_URL.'imgs/custom-logo.png'; ?>" /></td>
                                                    <td><button class="button-primary wpcb_mailer" data-mailer-id="99"><?php _e('Add New Custom List','wp-conversion-boxes-pro'); ?></button></td>
                                                </tr>
                                            </table>
                                            <div id="wpcb_mailer_99" class="wpcb_mailers_option">
                                                    <p><?php _e("Custom List Name:", 'wp-conversion-boxes-pro'); ?></p>
                                                    <p><input type="text" id="custom_list_name" name="custom_list_name" value="" class="wpcb_fullwidth"/></p>
                                                    <p class="wpcb_help_block"><?php _e("This will show up in the list of Lists/Campaigns under Optin Form Settings on Customize Box page.", 'wp-conversion-boxes-pro'); ?></p><br />
                                                    <p><?php _e("Custom List Form HTML Code:", 'wp-conversion-boxes-pro'); ?></p>
                                                    <p><textarea id="custom_list_form_html" style="height: 100px;" name="custom_list_form_html" value="" class="wpcb_fullwidth"/></textarea></p>
                                                    <p class="wpcb_help_block"><?php _e("Enter your form HTML code here.", 'wp-conversion-boxes-pro'); ?></p><br />
                                                    <input type="submit" value="<?php _e('Process Code and Save','wp-conversion-boxes-pro'); ?>" id="wpcb_save_custom_list" class="button button-primary" >
                                                    <button class="button-primary wpcb_mailer_cancel"><?php _e('Cancel','wp-conversion-boxes-pro'); ?></button>
                                            </div>
                                            <table class="wp-list-table widefat fixed posts" style="clear: none;">
                                                <thead>
                                                    <tr>
                                                        <th style="padding: 10px 10px;"><?php _e('Custom HTML Form Lists','wp-conversion-boxes-pro'); ?></th>
                                                        <th style="text-align: center; padding: 10px 10px;"><?php _e('Delete List','wp-conversion-boxes-pro'); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody id="the-list">
                                                    <?php $wpcb->get_all_custom_email_lists(); ?>
                                                </tbody>
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
        
        <?php 
        
        elseif($step == 3) : 
        
            if(isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['template'])){
                
                $dirPath = WPCB_CUSTOM_TEMPLATE_DIR_PATH.$_GET['template'];
                
                if($wpcb->wpcb_delete_dir($dirPath)){
                    echo "<div class='updated'><p>Custom template deleted successfully.</p></div>";
                }
                else{
                    echo "<div class='error'><p>ERROR: There was an error deleting the custom template.</p></div>";
                }
            }
            
        ?>
        <div id="post-body-content">
                <div class='postbox'>
                    <h3><?php _e('Upload Custom Template', 'wp-conversion-boxes-pro'); ?></h3>
                    <div class='inside'>
                        <table class="form-table">
                            <tbody>
                                <tr>
                                    <th scope="row"><label for=""><?php _e('Upload Custom Template', 'wp-conversion-boxes-pro'); ?></label></th>
                                    <td>
                                        <form action="" method="post" enctype="multipart/form-data">
                                            <p><?php echo $wpcb->get_list_of_template_types(); ?></p>
                                            <p class="wpcb_help_block"><?php _e("Please select template type of the zip file.", 'wp-conversion-boxes-pro'); ?></p><br />
                                            <p><label><?php _e('Select zip file:','wp-conversion-boxes-pro'); ?> <input type="file" name="wpcb_template_zip" id="wpcb_template_zip"></label></p>
                                            <p class="wpcb_help_block"><?php _e("Upload your custom .zip template file here. This .zip file will be extracted to the <code>wp-content/uploads/wpcb-custom-templates/</code> directory of your site. Your template will be available to use on Select Box Template page. To know how make custom box templates, <a href='http://wpconversionboxes.com/docs/how-to-create-my-own-custom-templates/' target='_blank'>click here</a>", 'wp-conversion-boxes-pro'); ?></p><br />
                                            <p><input type="submit" name="submit" value="<?php _e('Upload','wp-conversion-boxes-pro'); ?>" class="button button-primary" /></p>
                                        </form>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class='postbox'>
                    <h3><?php _e('Your Custom Templates', 'wp-conversion-boxes-pro'); ?></h3>
                    <div class='inside'>
                        <p><?php _e("Following is the list of your uploaded custom templates. These custom templates are available to use on <b>Select Box Template</b> page:",'wp-conversion-boxes-pro'); ?></p>
                        <?php $wpcb->show_list_of_custom_templates(); ?>
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
        
        <?php elseif($step == 5) : ?>
        
        <div id="post-body-content">
                <div class='postbox'>
                    <h3><?php _e('License', 'wp-conversion-boxes-pro'); ?></h3>
                    <div class='inside'>
                        <table class="form-table">
                            <tbody>
                                <tr>
                                    <th scope="row"><label for=""><?php _e('License Key and Status', 'wp-conversion-boxes-pro'); ?></label></th>
                                    <td>
                                        <p><code><?php echo get_option('wpcb_license_key'); ?></code></p>
                                        <p><?php _e('Status: ', 'wp-conversion-boxes-pro'); ?><span class="wpcb_active_label"><?php _e('active', 'wp-conversion-boxes-pro'); ?></span></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for=""><?php _e('License Validity', 'wp-conversion-boxes-pro'); ?></label></th>
                                    <td>
                                        <p><?php echo sprintf( __("License valid for next <b>%d</b> days" , 'wp-conversion-boxes-pro') , get_option('wpcb_license_validity_remaining') ); ?></p>
                                        <?php if(get_option('wpcb_license_validity_remaining') < 30) { ?> <p><a href="http://wpconversionboxes.com/checkout/?edd_license_key=<?php esc_attr_e( get_option('wpcb_license_key') ); ?>">Renew License Now</a></p> <?php } ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for=""><?php _e('Deactivate and Delete', 'wp-conversion-boxes-pro'); ?></label></th>
                                    <td>
                                        <p><a id="wpcb_deactivate_license"><?php _e('Deactivate and Delete License','wp-conversion-boxes-pro') ?></a></p>
                                        <p class="wpcb_help_block"><?php _e('Clicking on the above link will deactivate and delete the license. You may however activate the license key again later if validity is not over or activate the plugin using other license key.','wp-conversion-boxes-pro') ?></p>
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



<?php } ?>