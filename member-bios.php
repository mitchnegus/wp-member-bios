<?php

/**
 * Member Bios
 *
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @package           Member_Bios
 * @link
 * @since             1.0.0
 * @author						Mitch Negus
 * @copyright					2019 Mitch Negus
 * @license						GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name:       Member Bios
 * Plugin URI:        https://github.com/mitchnegus/wp-member-bios
 * Description:       A plugin for managing a group's member pages, including automatic submission of an image and short bio.
 * Version:           1.0.0
 * Author:            Mitch Negus
 * Author URI:        https://www.mitchnegus.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       member-bios
 * Domain Path:       /languages
 */
namespace Member_Bios;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Current plugin version.
 * Start(ed) at version 1.0.0 and use SemVer - https://semver.org
 */
define( 'MEMBER_BIOS_VERSION', '1.0.0' );
 
// These files need to be included as dependencies when on the front end.
require_once ABSPATH . 'wp-admin/includes/image.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';

// Define some constants for use in the plugin
if ( ! defined( 'WMB_PATH' ) ) {
	define( 'WMB_PATH', plugin_dir_path( __FILE__ ) );
}
if ( ! defined( 'WMB_URL' ) ) {
	define( 'WMB_URL', plugin_dir_url( __FILE__ ) );
}

//Define some global variables used by various classes
global $new_member_form_template, $new_member_confirmation_template;
global $new_member_form_title, $new_member_confirmation_title;

$new_member_form_template = WMB_PATH . 'public/templates/new-member-form.php';
$new_member_confirmation_template = WMB_PATH . 'public/templates/new-member-confirmation.php';
$new_member_form_title = 'New Member';
$new_member_confirmation_title = 'Submission accepted';

 
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-member-bios-activator.php
 */
function activate_member_bios() {
	require_once WMB_PATH . 'includes/class-member-bios-activator.php';
	Member_Bios_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-member-bios-deactivator.php
 */
function deactivate_member_bios() {
	require_once WMB_PATH . 'includes/class-member-bios-deactivator.php';
	Member_Bios_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_member_bios' );
register_deactivation_hook( __FILE__, 'deactivate_member_bios' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require WMB_PATH . 'includes/class-member-bios.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_member_bios() {

	$plugin = new Member_Bios();
	$plugin->run();

}
run_member_bios();
