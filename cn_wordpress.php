<?php
/**
 * The WordPress Plugin Boilerplate.
 *
 * A foundation off of which to build well-documented WordPress plugins that
 * also follow WordPress Coding Standards and PHP best practices.
 *
 * @package   cn_wordpress
 * @author    Jesse Greathouse <jesse.greathouse@gmail.com>
 * @license   MIT
 * @link      http://cookingnutritious.com
 * @copyright 2014 Jesse Greathouse @cookingnutritious.com
 *
 * @wordpress-plugin
 * Plugin Name:       cn_wordpress
 * Plugin URI:        cookingnutritious.com
 * Description:       plugin for using the cookingnutritious api in wordpress
 * Version:           1.0.0
 * Author:            Jesse Greathouse
 * Author URI:        https://www.linkedin.com/pub/jesse-greathouse/14/812/990
 * Text Domain:       cn_wordpress-locale
 * License:           MIT
 * License URI:       http://opensource.org/licenses/MIT
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/cookingnutritious/cn_wordpress-plugin
 * WordPress-Plugin-Boilerplate: v2.6.1
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

/*
 * @TODO:
 *
 * - replace `class-cn_wordpress.php` with the name of the plugin's class file
 *
 */
require_once( plugin_dir_path( __FILE__ ) . 'public/class-cn_wordpress.php' );

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 *
 * @TODO:
 *
 * - replace cn_wordpress with the name of the class defined in
 *   `class-cn_wordpress.php`
 */
register_activation_hook( __FILE__, array( 'cn_wordpress', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'cn_wordpress', 'deactivate' ) );

/*
 * @TODO:
 *
 * - replace cn_wordpress with the name of the class defined in
 *   `class-cn_wordpress.php`
 */
add_action( 'plugins_loaded', array( 'cn_wordpress', 'get_instance' ) );

/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/

/*
 * @TODO:
 *
 * - replace `class-cn_wordpress-admin.php` with the name of the plugin's admin file
 * - replace cn_wordpress_Admin with the name of the class defined in
 *   `class-cn_wordpress-admin.php`
 *
 * If you want to include Ajax within the dashboard, change the following
 * conditional to:
 *
 * if ( is_admin() ) {
 *   ...
 * }
 *
 * The code below is intended to to give the lightest footprint possible.
 */
if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {

	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-cn_wordpress-admin.php' );
	add_action( 'plugins_loaded', array( 'cn_wordpress_Admin', 'get_instance' ) );

}
