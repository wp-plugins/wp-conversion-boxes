<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @since 0.0.1
 */

// If uninstall not called from WordPress, then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

$wpcb_upload_dir = wp_upload_dir();

function wpcb_delete_dir($dir) {

    if (!file_exists($dir)) {
        return true;
    }

    if (!is_dir($dir)) {
        return unlink($dir);
    }

    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }

        if (!wpcb_delete_dir($dir . DIRECTORY_SEPARATOR . $item)) {
            return false;
        }

    }

    return rmdir($dir);

}

if (is_multisite()) {
	global $wpdb;
	$blogs = $wpdb->get_results("SELECT blog_id FROM {$wpdb->blogs}", ARRAY_A);
		
	if ($blogs) {
		foreach($blogs as $blog) {
			switch_to_blog($blog['blog_id']);
			$GLOBALS['wpdb']->query("DROP TABLE `".$GLOBALS['wpdb']->prefix."wp_conversion_boxes_pro");
                        $GLOBALS['wpdb']->query("DROP TABLE `".$GLOBALS['wpdb']->prefix."wpcbp_tracking");
                        $GLOBALS['wpdb']->query("DELETE FROM {$GLOBALS['wpdb']->options} WHERE option_name LIKE 'wpcb_%'");
                        $GLOBALS['wpdb']->query("DELETE FROM {$GLOBALS['wpdb']->postmeta} WHERE meta_key LIKE 'wpcb_meta_%'");
                        wp_clear_scheduled_hook( 'check_for_license_validity' );
                        $wpcb_templates_dir = $wpcb_upload_dir['basedir'] . '/wpcb-custom-templates';
                        wpcb_delete_dir($wpcb_templates_dir);
                        
			restore_current_blog();
		}
	}
}
else
{
	$GLOBALS['wpdb']->query("DROP TABLE `".$GLOBALS['wpdb']->prefix."wp_conversion_boxes_pro");
        $GLOBALS['wpdb']->query("DROP TABLE `".$GLOBALS['wpdb']->prefix."wpcbp_tracking");
        $GLOBALS['wpdb']->query("DELETE FROM {$GLOBALS['wpdb']->options} WHERE option_name LIKE 'wpcb_%'");
        $GLOBALS['wpdb']->query("DELETE FROM {$GLOBALS['wpdb']->postmeta} WHERE meta_key LIKE 'wpcb_meta_%'");
        wp_clear_scheduled_hook( 'check_for_license_validity' );
        $wpcb_templates_dir = $wpcb_upload_dir['basedir'] . '/wpcb-custom-templates';
        wpcb_delete_dir($wpcb_templates_dir);
}