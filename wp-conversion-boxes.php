<?php
/**
 * @package   WP_Conversion_Boxes
 * @author    Ram Shengale <me@ramshengale.in>
 * @license   GPL-2.0+
 * @copyright 2014 Ram Shengale
 *
 * @wordpress-plugin
 * Plugin Name:       WP Conversion Boxes - Email Optin & CTA Boxes
 * Plugin URI:        http://wpconversionboxes.com
 * Description:       Same Traffic. More Conversions. Replace your CTAs and email/optin subscriber boxes with WP Conversion Boxes and skyrocket your conversion rate.
 * Version:           2.6.1
 * Author:            Ram Shengale
 * Author URI:        http://ramshengale.in
 * Text Domain:       wp-conversion-boxes
 * Domain Path:       /lang/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

// Localization
function wpcb_init(){
    load_plugin_textdomain('wp-conversion-boxes', false, dirname(plugin_basename(__FILE__)) . "lang/");
}
add_action('init', 'wpcb_init');

/**********************************************
* Define most used URLS and Paths
**********************************************/

define( 'WPCB_WEBSITE_URL', 'http://wpconversionboxes.com' );

define('ADMIN_ASSETS_URL', plugins_url('admin/assets/', __FILE__ ));
define('PUBLIC_ASSETS_URL', plugins_url('public/assets/', __FILE__ ));

define('TEMPLATE_DIR_PATH', plugin_dir_path(__FILE__).'templates/');
define('TEMPLATE_DIR_URL', plugins_url( 'templates/',__FILE__)); 

define('WPCB_TEMPLATE_DIR_PATH',plugin_dir_path(__FILE__).'templates/');

/***********************************************
 * Load wpcb-public.php file.
 **********************************************/

require_once( plugin_dir_path( __FILE__ ) . 'public/class.tracker.php' );
require_once( plugin_dir_path( __FILE__ ) . 'public/class.public.php' );


/***********************************************
* Plugin activate and deactivate hook.
***********************************************/

register_activation_hook( __FILE__, array( 'WPCB_Public', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'WPCB_Public', 'deactivate' ) );


/***********************************************
 * Get an instance of the classes when needed.
 ***********************************************/

add_action( 'plugins_loaded', array( 'WPCB_Tracker', 'get_instance' ) );
add_action( 'plugins_loaded', array( 'WPCB_Public', 'get_instance' ) );

/**********************************************
 * Load wpcb-admin.php file for Admin area.
 **********************************************/

if ( is_admin() ) {
	require_once( plugin_dir_path( __FILE__ ) . 'admin/class.admin.php' );
	add_action( 'plugins_loaded', array( 'WPCB_Admin', 'get_instance' ) );

}
