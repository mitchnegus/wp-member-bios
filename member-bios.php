<?php
/**
 * Plugin Name: Member Bios
 * Version: 1.0
 * Plugin URI: 
 * Description: Automatically updating member bio pages.
 * Author: Mitch Negus
 * Author URI: www.mitchnegus.com
 * Text Domain: member-bios
 * Domain Path: /languages/
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 *
 */

// Don't allow user to access this file directly
if (!defined('ABSPATH')) {
  die();
}

// Plugin directory must be set global to use in activation hook
global $PLUGIN_DIR, $PLUGIN_URL;
$PLUGIN_DIR = plugin_dir_path(__FILE__);
$PLUGIN_URL = plugin_dir_url(__FILE__);

// Plugin specific files to include
require($PLUGIN_DIR . '/includes/define-members-post-type.php');

require $PLUGIN_DIR . '/goal_framework/member-bios/member-bios.php';

// Add new member submission page on activation
register_activation_hook(__FILE__, 'activate_member_bios');
register_deactivation_hook(__FILE__, 'deactivate_member_bios');

// Use a custom template for the member pages
add_filter('single_template', 'use_custom_member_single_template');
add_filter('archive_template', 'use_custom_member_archive_template');
// Format the "Members" page properly
add_action('pre_get_posts', 'show_all_members');
add_action('pre_get_posts', 'alpha_order_classes');
