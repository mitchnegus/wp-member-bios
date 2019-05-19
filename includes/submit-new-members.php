<?php
/**
 * Functions for handling submitted data
 */

// Define a function to accept user input
function submit_new_member_form()
{
		// Check submission validity
		check_nonce();
		check_email();
		$upload_present = check_upload_present();
		if ($upload_present) 
				check_upload_validity();
		// Create a new post for the member
		$inputs = sanitize_input_fields();
		$post_id = create_new_post($inputs);
	  if ($upload_present) {	
				// Upload the image to the media library and assign to the post
				$attachment_id = media_handle_upload('photo', $post_id);
		} else {
				// Set the default headshot as the post image
		}
		// Set the image as the post thumbnail
		set_post_thumbnail($post_id, $attachment_id);
		wp_redirect(home_url().'/new-member-confirmation');
		notify_admin_on_submission($inputs);
}

// Check that a nonce was provided and is valid, otherwise kill execution
function check_nonce()
{
		$nonce = $_POST['new_member_form_nonce'];
		$nonce_action = 'add_new_member_nonce';
		if (!isset($nonce) || !wp_verify_nonce($nonce, $nonce_action))
				wp_die('Invalid nonce specified', 'Error', array('response'=> 403));
}

// Check the user-provided email
function check_email()
{
		// Check that the email is of the correct format
		$email = $_POST['email'];
		if (!is_email($email))
				wp_die('Invalid email provided. Please go back and use a different one.', 'Error', array('response'=> 403));
		// Check that the email is from the correct organization domain (not spam)
		$org = get_option('organization_name');
		$org_domain = get_option('organization_domain');
		$submitted_domain = substr($email, -strlen($org_domain));
		if ($submitted_domain != $org_domain)
				wp_die('Invalid email provided. Please go back and make sure to use your ' . esc_html($org) . ' email address.', 'Error', array('response'=> 403));
}

// Check whether the user has uploaded an image
function check_upload_present()
{
		$photo = $_FILES['photo'];
		if (empty($_FILES) || !isset($photo) || $photo['error'] == 4) {
				return false;
		} else {
				return true;
		}
}
// Check that the upload is valid, otherwise stop.
function check_upload_validity()
{
		if (!check_file_meets_specs()) {
				wp_die('The image you uploaded does not meet the specifications. Please go back and upload a new image.', 'Error', array('response'=> 403));
		}
}

// Check that the file was uploaded and meets specifications
function check_file_meets_specs()
{
		global $max_headshot_size;
		$allowed_image_types = array('image/jpeg', 'image/png');
		if (!isset($_FILES['photo']['size']) || $_FILES['photo']['size'] == 0)
				return false;
		if (!in_array($_FILES['photo']['type'], $allowed_image_types))
				return false;
		if ($_FILES['photo']['size'] > $max_headshot_size)
		 		return false;	
		return true;
}

// Sanitize all text input fields
function sanitize_input_fields()
{
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

// Create a new post based on the user inputs
function create_new_post($inputs)
{
		// Assign data for the post
		$post_arr = array(
				'post_type'    => 'members',
				'post_title'   => $inputs['name'],
				'post_content' => $inputs['bio'],
				'post_name'    => 'test-new-member'
		);
		// Insert a post for the new member with accompanying metadata
		$post_id = wp_insert_post($post_arr);
		update_post_meta($post_id, 'subject', $inputs['subject']);
		update_post_meta($post_id, 'grad_date', $inputs['grad_date']);
		update_post_meta($post_id, 'interests', $inputs['interests']);
		return $post_id;
}

// Notify the site administrator when someone submits the form
function notify_admin_on_submission($inputs)
{
		$recipient = get_option('admin_email');
		$subject = 'SPG new member: ' . $inputs['name'];
		$message = $inputs['name'] . ' has submitted a new member request.';
		wp_mail($recipient, $subject, $message);
}
