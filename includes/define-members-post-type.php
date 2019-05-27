<?php
/**
 * Function definitions for the "Members" custom post type
 */

function use_custom_member_single_template($single_template)
{
		global $post, $PLUGIN_DIR;
		/* Check for single template by post type */
		if ($post->post_type == 'members') {
			$single_template = $PLUGIN_DIR . 'goal_framework/member-bios/public/templates/single-members.php';
	 	}
		return $single_template;
}

function use_custom_member_archive_template($archive_template)
{
		global $post, $PLUGIN_DIR;
		/* Check for single template by post type */
		if (is_post_type_archive('members')) {
			  $archive_template = $PLUGIN_DIR . 'goal_framework/member-bios/public/templates/archive-members.php';
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
