<?php

// Sidebar
if((isset($_GET['step']) && $_GET['step'] == 2) && (isset($_GET['step']) &&  $_GET['page'] == 'wp-conversion-boxes-pro/edit')){

}
else{?>

<div class="postbox" style="display: block;">
    <h3 style="cursor: pointer;"><span>Like The Plugin?</span></h3>
    <div class="inside">
        <p>If you like the plugin, please leave your valuable rating on WordPress.org to help us spread the word out!</p>
        <a href="https://wordpress.org/support/view/plugin-reviews/wp-conversion-boxes?rate=5#postform" target="_blank"><img src="<?php echo plugins_url('assets/imgs/rating.png', dirname(__FILE__ )); ?>" /></a>
        <br /><a href="https://wordpress.org/support/view/plugin-reviews/wp-conversion-boxes?rate=5#postform" target="_blank">Leave rating now</a>
    </div>
</div>
<?php } ?>
<div class="postbox" style="display: block;">
    <h3 style="cursor: pointer;"><span>Need Help?</span></h3>
    <div class="inside">
        <p>Please visit our Documentation page for all the basic help of setting up and using the plugin.</p>
        <a class="button button-primary" href='http://wpconversionboxes.com/docs/'>Documentation</a>
        <p>If you need help with anything else, do not hesitate to submit a ticket on the Support page and we’ll get back to you with the solution asap.</p>
        <a class="button button-primary" href='http://wpconversionboxes.com/account/support/'>Open Support Ticket</a>
    </div>
</div>