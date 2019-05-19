<?php
/**
 * Function definitions for the "New Member" custom page (with entry form)
 */

global $PLUGIN_DIR;
global $new_member_form_template, $new_member_form_title;
global $new_member_confirmation_template, $new_member_confirmation_title;

$new_member_form_template = $PLUGIN_DIR . 'templates/new-member-form.php';
$new_member_form_title = 'New Member';
$new_member_confirmation_template = $PLUGIN_DIR . 'templates/new-member-confirmation.php';
$new_member_confirmation_title = 'Submission accepted';

// Define a function to create a custom page
function add_new_member_form_page()
{
		global $new_member_form_template, $new_member_form_title; 
		// Create the page if it doesn't already exist
		$page = get_page_by_title($new_member_form_title); 
		if (!isset($page->ID)) {
				$page_title = $new_member_form_title;
				$page_arr = array(
						'post_type' 	 => 'page',
						'post_title'   => $page_title,
						'post_status'  => 'publish',
						'post_name'    => 'new-member'
				);
	
				$page_id = wp_insert_post($page_arr);
				$template = $new_member_form_template;
				update_post_meta($page_id, '_wp_page_template', $template);
		}
}

function add_new_member_confirmation_page()
{
		global $new_member_confirmation_template, $new_member_confirmation_title;
		// Create the page if it doesn't already exist
		$page = get_page_by_title($new_member_confirmation_title); 
		if (!isset($page->ID)) {
				$page_title = $new_member_confirmation_title;
				$page_arr = array(
						'post_type' 	 => 'page',
						'post_title'   => $page_title,
						'post_status'  => 'publish',
						'post_name'    => 'new-member-confirmation'
				);
	
				$page_id = wp_insert_post($page_arr);
				$template = $new_member_confirmation_template;
				update_post_meta($page_id, '_wp_page_template', $template);
		}
}
 
// Include a template for the new member page from the plugin
function include_new_member_template($template)
{
		global $new_member_form_template, $new_member_form_title;
		if (is_page($new_member_form_title))
				return $new_member_form_template;
		return $template;
}

// Include a template for a message upon successful submission of the form
function include_submit_confirmation_template($template)
{
		global $new_member_confirmation_template, $new_member_confirmation_title;
		if (is_page($new_member_confirmation_title))
				return $new_member_confirmation_template;
		return $template;
}
		
// Remove the page from the database
function remove_new_member_pages()
{
		global $new_member_form_title, $new_member_confirmation_title;
		$form_page = get_page_by_title($new_member_form_title);
		$confirmation_page = get_page_by_title($new_member_confirmation_title);
		wp_delete_post($form_page->ID, true);
		wp_delete_post($confirmation_page->ID, true);
}
