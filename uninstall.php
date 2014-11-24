<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @since 1.0.0
 */

// If uninstall not called from WordPress, then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}
if (is_multisite()) {
	global $wpdb;
	$blogs = $wpdb->get_results("SELECT blog_id FROM {$wpdb->blogs}", ARRAY_A);
		
	if ($blogs) {
		foreach($blogs as $blog) {
			switch_to_blog($blog['blog_id']);
			$GLOBALS['wpdb']->query("DROP TABLE `".$GLOBALS['wpdb']->prefix."wp_conversion_boxes");
                        $GLOBALS['wpdb']->query("DROP TABLE `".$GLOBALS['wpdb']->prefix."wpcb_tracking");
                        $GLOBALS['wpdb']->query("DELETE FROM {$GLOBALS['wpdb']->options} WHERE option_name LIKE 'wpcb_%'");
			restore_current_blog();
		}
	}
}
else
{
	$GLOBALS['wpdb']->query("DROP TABLE `".$GLOBALS['wpdb']->prefix."wp_conversion_boxes");
        $GLOBALS['wpdb']->query("DROP TABLE `".$GLOBALS['wpdb']->prefix."wpcb_tracking");
        $GLOBALS['wpdb']->query("DELETE FROM {$GLOBALS['wpdb']->options} WHERE option_name LIKE 'wpcb_%'");
}