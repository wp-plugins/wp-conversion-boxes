<?php

// Sidebar
if((isset($_GET['step']) && $_GET['step'] == 2) && (isset($_GET['step']) &&  $_GET['page'] == 'wp-conversion-boxes/edit')){

}
else{?>

<div class="postbox" style="display: block;">
    <h3 style="cursor: pointer;"><span>Got 30 Seconds?</span></h3>
    <div class="inside">
        If you like the plugin, please leave your valuable rating on WordPress.org!
        <a href="https://wordpress.org/support/view/plugin-reviews/wp-conversion-boxes?rate=5#postform" target="_blank"><img src="<?php echo plugins_url('assets/imgs/rating.png', dirname(__FILE__ )); ?>" /></a>
        <br /><a href="https://wordpress.org/support/view/plugin-reviews/wp-conversion-boxes?rate=5#postform" target="_blank">Leave rating now</a>
    </div>
</div>
<?php } ?>
<div class="postbox" style="display: block;">
    <h3 style="cursor: pointer;"><span>Upgrade to Pro</span></h3>
    <div class="inside">
        <div class=" wpcb_more_less">
            <div class="wpcb_more_block">
                <p>Upgrade to WP Conversion Boxes Pro to use the full potential of your conversion boxes.</p>
                <p>With the pro version of the plugin, you get all the following features:</p>
                <strong>More Template Designs</strong>
                <ul>
                    <li>30+ extra template designs.</li>
                    <li>Design your own templates with our template engine.</li>
                    <li>Buy from our collection of premium template designs, starting just $5.</li>
                    <li>Or, ask us to design a template for you for, starting just $49!</li>
                </ul>
                <strong>A/B Testing</strong>
                <ul>
                    <li>Split test between your boxes to see which box is converting better.</li>
                    <li>Get detailed statistics of each A/B test to find which box converts most. </li>
                </ul>
                <strong>Advance Statistics</strong>
                <ul>
                    <li>Track every minor detail of each box.</li>
                    <li>View the stats on an easy to understand graph with advance filtering options.</li>
                    <li>Also view all the top performing posts and pages.</li>
                </ul>
                <strong>Advance Box Placement</strong>
                <ul>
                    <li>Place conversion boxes to posts of selected categories for targeted box copies.</li>
                    <li>Option to place boxes under specific posts/pages from below the edit page.</li>
                    <li>Place conversion boxes in sidebar using widgets.</li>
                    <li>Place more than one conversion box on a single page.</li>
                </ul>
                <strong>Other Salient Features</strong>
                <ul>
                    <li>All major email service providers included.</li>
                    <li>Full documentation to help you create your own templates.</li>
                    <li>Template uploader to upload your custom templates.</li>
                    <li>Option to add redirect link after successful email optin.</li>
                </ul>
                <strong>Support and Upgrades</strong>
                <ul>
                    <li>Free life-time priority support.</li>
                    <li>Free upgrades to every new version of the plugin.</li>
                </ul>
                <p>And a lot more... <a href='<?= $this->wpcb_website_url ?>' target="_blank">Visit site for more info &gt;&gt;</a></p>
            </div>
        </div>
        <a href="#" class="wpcb_adjust wpcb_load_more_stats">More...</a>
    </div>
</div>