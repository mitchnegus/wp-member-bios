<?php
/**
 * Functions for handling submitted data
 */

// Define a function to accept user input
function submit_new_member_form()
{
		$posted_nonce = $_POST['new_member_form_nonce'];
		$nonce_action = 'add_new_member_form_nonce';
		if(isset($posted_nonce) && wp_verify_nonce($posted_nonce, $nonce_action)) {
				// Sanitize input
				$name = sanitize_text_field($_POST['name']);
				$email = sanitize_email($_POST['email']);
				$subject = sanitize_text_field($_POST['subject']);
				$grad_date = sanitize_text_field($_POST['grad_date']);
				$interests = sanitize_text_field($_POST['interests']);
				$bio = sanitize_text_field($_POST['bio']);
				// Set the array to insert for the new member's database entry
				$post_arr = array(
						'post_type'    => 'members',
						'post_title'   => $name,
						'post_content' => $bio,
						'post_name'    => 'test-new-member'
				);
				// Insert a new post for the new member with accompanying metadata
				$post_id = wp_insert_post($post_arr);
				update_post_meta($post_id, 'subject', $subject);
				update_post_meta($post_id, 'grad_date', $grad_date);
				update_post_meta($post_id, 'interests', $interests);
		} else {
				wp_die('Invalid nonce specified', 'Error', array('response'=> 403));
		}
}
