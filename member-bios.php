<?php

/**
 * Wordpress Member Bios
 *
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @package           Member_Bios
 * @link              http://example.com
 * @since             1.0.0
 * @author						Mitch Negus
 * @copyright					2019 Mitch Negus
 * @license						GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name:       WordPress Member Bios
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

global $PLUGIN_DIR, $PLUGIN_URL;
$PLUGIN_DIR = plugin_dir_path(__FILE__);
$PLUGIN_URL = plugin_dir_url(__FILE__);
 
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-member-bios-activator.php
 */
function activate_member_bios() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-member-bios-activator.php';
	Member_Bios_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-member-bios-deactivator.php
 */
function deactivate_member_bios() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-member-bios-deactivator.php';
	Member_Bios_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_member_bios' );
register_deactivation_hook( __FILE__, 'deactivate_member_bios' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-member-bios.php';

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
