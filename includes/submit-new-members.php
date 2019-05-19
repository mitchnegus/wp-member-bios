<?php
/**
 * Functions for handling submitted data
 */

// Define a function to accept user input
function submit_new_member_form()
{
		check_nonce();
		$inputs = sanitize_input_fields();
		$post_id = create_new_post($inputs);
		if (check_file_uploaded()) {
				// Upload the image to the wordpress media library and set post metadata
				wp_handle_upload($_FILES['photo']);
		} else {
				// Set the post image to the template headshot image
		}
}

function check_nonce()
{
		// Check that a nonce was provided and is valid, otherwise kill execution
		$nonce = $_POST['new_member_form_nonce'];
		$nonce_action = 'add_new_member_form_nonce';
		if (!isset($nonce) || !wp_verify_nonce($nonce, $nonce_action)) {
				wp_die('Invalid nonce specified', 'Error', array('response'=> 403));
		}

function check_file_uploaded()
{
		return (!empty($_FILES) && isset($_FILES['photo']));
}

function sanitize_input_fields()
{
		// Sanitize all text input fields
		$sanitized_inputs = array(
				'name' 			=> sanitize_text_field($_POST['name']),
				'email' 		=> sanitize_email($_POST['email']),
				'subject' 	=> sanitize_text_field($_POST['subject']),
				'grad_date' => sanitize_text_field($_POST['grad_date']),
				'interests' => sanitize_text_field($_POST['interests']),
				'bio'				=> sanitize_text_field($_POST['bio'])
		);
		return $sanitized_inputs;
}

function create_new_post($inputs)
{
		// Create a new post based on the user inputs
		$post_arr = array(
				'post_type'    => 'members',
				'post_title'   => $inputs['name'],
				'post_content' => $inputs['bio'],
				'post_name'    => 'test-new-member'
		);
		// Insert a new post for the new member with accompanying metadata
		$post_id = wp_insert_post($post_arr);
		update_post_meta($post_id, 'subject', $inputs['subject']);
		update_post_meta($post_id, 'grad_date', $inputs['grad_date']);
		update_post_meta($post_id, 'interests', $inputs['interests']);
		return $post_id;
}

