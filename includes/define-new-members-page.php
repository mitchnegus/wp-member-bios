<?php
/**
 * Function definitions for the "New Member" custom page (with entry form)
 */

global $PLUGIN_DIR, $new_member_page_template, $new_member_page_title;
$new_member_page_template = $PLUGIN_DIR . 'templates/new-member-form.php';
$new_member_page_title = 'New Member';

// Define a function to create a custom page
function add_new_member_page()
{
		global $new_member_page_template, $new_member_page_title; 
		// Create the page if it doesn't already exist
		$page = get_page_by_title($new_member_page_title); 
		if (!isset($page->ID) || $page->post_type != 'members') {
				$page_title = $new_member_page_title;
				$page_arr = array(
						'post_type' 	 => 'page',
						'post_title'   => $page_title,
						'post_status'  => 'publish',
						'post_name'    => 'new-member'
				);
	
				$page_id = wp_insert_post($page_arr);
				$template = $new_member_page_template;
				if (!empty($template)) {
						update_post_meta($page_id, '_wp_page_template', $template);
				}
		}
}
 
// Include a template for the new member page from the plugin
function include_new_member_template($template)
{
		global $new_member_page_template, $new_member_page_title;
		if (is_page($new_member_page_title)) {
				return $new_member_page_template;
		}
		return $template;
}

// Remove the page from the database
function remove_new_member_page()
{
		global $new_member_page_title;
		$page = get_page_by_title($new_member_page_title);
		wp_delete_post($page->ID);
}
