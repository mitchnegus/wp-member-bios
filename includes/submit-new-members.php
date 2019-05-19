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
				// Upload the image to the 'uploads' directory
			  $photo = $_FILES['photo'];
				$overrides = array('test_form' => false);
				$img_upload = wp_handle_upload($photo, $overrides);
				$img_path = $img_upload['file'];
				// Check file type, size, etc.
				// Specify the attachment attributes
				$attachment = array(
					'guid' 						=> $img_upload['url'],
					'post_mime_type'  => $img_upload['type'],
					'post_title'			=> sanitize_file_name($img_path), 
					'post_content'    => '',
					'post_status'			=> 'inherit'
				);
				// Set the metadata for the image attachment
				$attach_id = wp_insert_attachment($attachment, $img_path, $post_id);
				$attach_data = wp_generate_attachment_metadata($attach_id, $img_path); 
				wp_update_attachment_metadata($attach_id, $attach_data);
				// Assign the image to the post
				set_post_thumbnail($post_id, $attach_id);
		} else {
				// Set the post image to the template headshot image
		}
}

function check_nonce()
{
		// Check that a nonce was provided and is valid, otherwise kill execution
		$nonce = $_POST['new_member_form_nonce'];
		$nonce_action = 'add_new_member_nonce';
		if (!isset($nonce) || !wp_verify_nonce($nonce, $nonce_action)) {
				wp_die('Invalid nonce specified', 'Error', array('response'=> 403));
		}
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
