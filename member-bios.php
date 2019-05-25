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
 * @package 
 * @version 1.0
 *
 * member-bios - WordPress Plugin for updating member bios from member 
 * uploaded member forms.
 * Copyright (C) 2019 Mitch Negus
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 */

// Don't allow user to access this file directly
if (!defined('ABSPATH')) {
  die();
}

// Plugin directory must be set global to use in activation hook
global $PLUGIN_DIR, $PLUGIN_URL;
$PLUGIN_DIR = plugin_dir_path(__FILE__);
$PLUGIN_URL = plugin_dir_url(__FILE__);

// Set the maximum image size to be allowed in an upload
global $max_headshot_size;
$max_headshot_size = set_plugin_max_headshot_size();

// Plugin specific files to include
require($PLUGIN_DIR . '/includes/define-members-post-type.php');
require($PLUGIN_DIR . '/includes/define-new-members-form.php');
require($PLUGIN_DIR . '/includes/submit-new-members.php');

// These files need to be included as dependencies when on the front end.
require_once(ABSPATH . 'wp-admin/includes/image.php');
require_once(ABSPATH . 'wp-admin/includes/file.php');
require_once(ABSPATH . 'wp-admin/includes/media.php');
 
// Include CSS stylesheet
function enqueue_resources()
{
   wp_register_style('member-bios', plugins_url('style.css', __FILE__));
	 wp_enqueue_style('member-bios');
}

// Set the maximum image size for user uploaded headshots
function set_plugin_max_headshot_size()
{
		$max_wordpress_upload_size = wp_max_upload_size();
		if ($max_wordpress_upload_size > 1e6) {
    		$plugin_max_headshot_size = 1e6;   // (Approx. 1 MB)
    } else {
        $plugin_max_headshot_size = $max_wordpress_upload_size;
    }
		return $plugin_max_headshot_size;
}

require $PLUGIN_DIR . '/goal_framework/member-bios/member-bios.php';

// Add theme support for thumbnails if not already included
add_theme_support('post-thumbnails');
set_post_thumbnail_size(200, 200);
// Add new member submission page on activation
register_activation_hook(__FILE__, 'activate_member_bios');
register_deactivation_hook(__FILE__, 'deactivate_member_bios');
// Include the CSS stylesheet for the plugin
add_action('init', 'enqueue_resources'); 
// Hook up our custom post to theme setup
add_action('init', 'register_member_post_type');
add_action('init', 'register_positions_taxonomy');
// Add custom fields to the custom post; save them on post
add_action('admin_init', 'add_admin_fields');
add_action('save_post', 'save_member_details');
// Update the columns on the browse members page
add_action('manage_members_posts_custom_column', 'add_member_columns', 10, 2);
add_filter('manage_members_posts_columns', 'set_member_columns');
// Use a custom template for the member pages
add_filter('single_template', 'use_custom_member_single_template');
add_filter('archive_template', 'use_custom_member_archive_template');
// Use a custom template for the new member and submission sucess pages
add_filter('template_include', 'include_new_member_template');
add_filter('template_include', 'include_submit_confirmation_template');
// Format the "Members" page properly
add_action('pre_get_posts', 'show_all_members');
add_action('pre_get_posts', 'alpha_order_classes');
// Accept user input
add_action('admin_post_submit_member', 'submit_new_member_form');
add_action('admin_post_nopriv_submit_member', 'submit_new_member_form');
