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
