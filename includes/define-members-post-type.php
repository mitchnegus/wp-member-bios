<?php
/**
 * Function definitions for the "Members" custom post type
 */

// Save the custom information to the database
function save_member_details()
{
		global $post;
		// Only save meta data for members posts
		if ($post->post_type == 'members') {
				// Sanitize user input
				$subject = sanitize_text_field($_POST['subject']);
				$grad_date = sanitize_text_field($_POST['grad_date']);
				$interests = sanitize_text_field($_POST['interests']);
				// Update the metadata for the post
				update_post_meta($post->ID, 'subject', $subject);
				update_post_meta($post->ID, 'grad_date', $grad_date);
				update_post_meta($post->ID, 'interests', $interests);
		}
}

// Add columns to the "Members" admin page
function add_member_columns($column, $post_id)
{
		$custom = get_post_custom();
		switch ($column) {
				case 'subject':
					echo $custom['subject'][0];
					break;
				case 'grad_date':
					echo $custom['grad_date'][0];
					break;
		}
}

function set_member_columns($columns)
{
		$columns = array(
				'cb' 				=> '<input type="checkbox" />',
				'title' 		=> __('Member'),
				'subject' 	=> __('Subject'),
				'grad_date' => __('Graduation Date')
		);
		return $columns;
}	

function use_custom_member_single_template($single_template)
{
		global $post, $PLUGIN_DIR;
		/* Check for single template by post type */
		if ($post->post_type == 'members') {
			$single_template = $PLUGIN_DIR . 'templates/single-members.php';
	 	}
		return $single_template;
}

function use_custom_member_archive_template($archive_template)
{
		global $post, $PLUGIN_DIR;
		/* Check for single template by post type */
		if (is_post_type_archive('members')) {
			  $archive_template = $PLUGIN_DIR . 'templates/archive-members.php';
		}
		return $archive_template;
}

// Show all members on the archive page
function show_all_members($query)
{
	  if (!is_admin() && $query->is_main_query()) {
				if (is_post_type_archive('members')) {
						$query->set('posts_per_page', -1);
				}
		}
}

// Alphabetize members on the archive page
function alpha_order_classes($query)
{
		if ($query->is_main_query()) {
				if (is_post_type_archive('members')) {
				        $query->set('orderby', 'name');
								$query->set('order', 'ASC');
				}
		}
}
