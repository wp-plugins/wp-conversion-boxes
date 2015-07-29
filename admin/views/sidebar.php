<?php

// Sidebar
?>
<div class="postbox" style="display: block;">
    <h3 style="cursor: pointer;"><span>Upgrade to Pro</span></h3>
    <div class="inside">
        <p>Upgrade to WP Conversion Boxes Pro for higher converting email optin and call-to-action boxes.</p>
        <p> Watch the demo video below to take a look inside...</p>
        <a class="wpcb_launch_popup" onclick="jQuery('.wpcb_promo_popup').lightbox_me({centered: true});">Unlock now...</a>
    </div>
</div>

<?php
if((isset($_GET['step']) && $_GET['step'] == 2) && (isset($_GET['step']) &&  $_GET['page'] == 'wp-conversion-boxes/edit')){

}
else{?>

<div class="postbox" style="display: block;">
    <h3 style="cursor: pointer;"><span>External Links</span></h3>
    <div class="inside">
        <b>Need Help?</b>
        <p>If you face any issue using the plugin, let us know on the Support Forum and we'll get it fixed asap.</p>
        <p><a class="button button-primary" href="https://wordpress.org/support/plugin/wp-conversion-boxes#postform" target="_blank">Open Thread</a></p>
        <hr />
        <b>Like The Plugin?</b>
        <p>If you like the plugin, please leave your valuable rating on WordPress.org!</p>
        <a href="https://wordpress.org/support/view/plugin-reviews/wp-conversion-boxes?rate=5#postform" target="_blank"><img src="<?php echo plugins_url('assets/imgs/rating.png', dirname(__FILE__ )); ?>" /></a>
        <br /><a href="https://wordpress.org/support/view/plugin-reviews/wp-conversion-boxes?rate=5#postform" target="_blank">Leave rating now</a>
    </div>
</div>
<?php } ?>

<div class="wpcb_promo_popup">
    <div id="wpcb_promo_popup_head">
        <h1>Upgrade to WP Conversion Boxes Pro!</h1>
        <span class="fa fa-close wpcb_publish_close" style="position: absolute; top: 20px; right: 20px; cursor: pointer;" onclick="jQuery(this).parent().trigger('close');"></span>
    </div>
    <div id="wpcb_promo_popup_body">
        <p>Upgrade to WP Conversion Boxes Pro for more power over the design, tracking and testing of your email optin and call-to-action boxes.</p><p>Watch the demo video below to take a look inside the WP Conversion Boxes Pro:</p>
        
        <center><iframe style="border: 5px solid #ddd;" width="320" height="180" src="https://www.youtube.com/embed/y45eY2BwFYI" frameborder="0" allowfullscreen></iframe></center>
        
        <p>Here's all the great features you get with WP Conversion Boxes Pro:</p>
        
        <h3>More Template Designs</h3>
        <ul>
                <li>45+ extra template designs. New templates being added regularly.</li>
                <li>Design your own templates with our template engine. Complete documentation provided.</li>
                <li>Or, ask us to design a template for you for, starting just $49!</li>
        </ul>
        <h3>A/B Testing</h3>
        <ul>
                <li>Split test between your boxes to see which box is converting better.</li>
                <li>Show the each box in an A/B test exactly 50% of the time for perfect results.</li>
                <li>Get detailed statistics of each A/B test to find which box converts most.</li>
        </ul>
        <h3>Advance Tracking and Statistics</h3>
        <ul>
                <li>Detailed box statistics for each conversion box:
        <ul>
                <li>Unique Visitors</li>
                <li>Pageviews</li>
                <li>Box Views</li>
                <li>Conversion</li>
                <li>Conversion Rate (%)</li>
        </ul>
        </li>
                <li>View the stats on an easy to understand graph with advance filtering options.</li>
                <li>Also view all the top performing posts and pages for each box.</li>
                <li><h3>Track Stats With Google Analytics Event Tracking</h3>
        <p>Super charge your conversion tracking by enabling Google Analytics' Event Tracking for your conversion boxes. Enabling this feature disables the plugin's in-built conversion tracking system and uses Google Analytics’ code to track the conversions as events. Best for high traffic volume blogs.</p></li>
        </ul>
        <h3>Settings and Box Placement</h3>
        <p class="p1"><i>Box Settings:</i></p>

        <ul class="ol1">
                <li class="li1">Everything included in free version, plus...</li>
                <li class="li1">Box Slide-in : Option to slide in the box from either right, left, top or bottom direction according to your settings grabbing the user's attention.</li>
                <li class="li1">Complete control over optin form messages. Write your own personalised Processing, Success and Error messages that'll show up after user opts in.</li>
        </ul>
        <p class="p1"><i>Box Placement:</i></p>

        <ul class="ol1">
                <li class="li1">Option to set default conversion boxes under and above all your posts/pages.</li>
                <li class="li1">Option to override above option and set different conversion boxes for posts and pages.</li>
                <li class="li1">Shortcode for each conversion box. Place the conversion box anywhere on your blog using shortcode.
        <ul class="ul2">
                <li class="li1">To place the box in the content of post/page, use
        <pre>[wpcb id=“#”]</pre>
        </li>
                <li class="li3">To add the box inside your theme, use
        <pre>&lt;?php echo do_shortcode('[wpcb id=“#”]'); ?&gt;</pre>
        </li>
        </ul>
        </li>
                <li class="li1">Widgets to place the boxes directly to your sidebar. Just select one from the list of boxes and A/B tests.</li>
                <li class="li1">Option under the edit page of posts, pages or any other custom post type to select a box or A/B test for that post/page.</li>
                <li class="li1">Option to set a default conversion box or A/B test for posts of specific categories.</li>
                <li class="li1">Place more than one conversion box on a single page/post and track stats for all of them!</li>
        </ul>
        <p class="p1"><i>Email Integration:</i></p>

        <ul class="ol1">
                <li class="li1">Integration with all major email service providers - <b>Feedburner, GetResponse, Aweber, MailChimp, Constant Contact, Campaign Monitor, Mad Mimi, Pardot (Salesforce), Infusionsoft and iContact</b>. New mailers added regularly.</li>
                <li class="li1">Integration with all major email marketing WordPress Plugins - MailPoet. New email marketing plugins added regularly.</li>
                <li class="li1">Can't find your email service provider above? No worries. The plugin also comes with Custom HTML Forms for your Lists. If your email service provider provides a HTML sign-up form for your lists, the plugin will automagically extract important information from it and create a Custom List for you which you can use with your boxes!</li>
        </ul>
        <h3>Other Salient Features</h3>
        <ul>
                <li>Full documentation to help you create your own templates.</li>
                <li>Template uploader to upload your custom templates.</li>
                <li>Option to add redirect link after successful email optin.</li>
        </ul>
        
        <h3>WP Conversion Boxes Pro Training Material</h3>
        <ul>
            <li>Training material where we'll walk you through our stupidly simple Conversion Booster Strategies to help you boost your conversion rate, all while having the same traffic. Same Traffic. More Conversions!</li>
            <li>Training material also includes useful tips on boosting your conversion rate on your blog using CTAs/Email Optin boxes. All written by experts in Conversion Optimization.</li>
        </ul>
        
        
        <h3>Support and Upgrades</h3>
        <ul>
                <li>Free 1 year access to our support ticketing system.</li>
                <li>Free 1 year automatic upgrades to every new version of the plugin.</li>
        </ul>
        <p>And a lot more... <a href='http://wpconversionboxes.com/?utm_source=sidebar&utm_medium=link&utm_campaign=WPCB' target="_blank">Visit site for more info &gt;&gt;</a></p>
        
    </div>
    <div id="wpcb_promo_popup_foot">
        <a class="button button-primary" target="_blank" href="http://wpconversionboxes.com/?utm_source=sidebar&utm_medium=link&utm_campaign=WPCB">Unlock and Upgrade Now!</a>
        <a class="button button-primary" target="_blank" href="http://wpconversionboxes.com/?utm_source=sidebar&utm_medium=link&utm_campaign=WPCB">Visit Site</a>
    </div>
</div>