<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin. This
 * file should primarily consist of HTML with a little bit of PHP.
 *
 * @link
 * @since      1.0.0
 *
 * @package    Member_Bios
 * @subpackage Member_Bios/admin/partials
 */
namespace Member_Bios;

/**
 * Display settings on the admin menu page.
 *
 * @since    1.0.0
 */
function display_settings( $option_group, $page_slug ) {
	?>

	<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		<form action="options.php" method="post">

			<?php 
			// Output security fields and then sections defined for the group
			settings_fields( $option_group );
			do_settings_sections( $page_slug );
			submit_button();
			?>

		</form>
	</div>

	<?php
}

function display_group_info_section( $args ) {
	?>

	<p id="<?php echo esc_attr( $args['id'] ); ?>">
		Set information pertaining to the group as a whole.
	</p>

	<?php
}

function display_member_archives_section( $args ) {
	?>

	<p id="<?php echo esc_attr( $args['id'] ); ?>">
	Set options regarding the display of the full members list. The ranking position is the position (e.g. "Executive") that will be displayed ahead of all other position types on the archive page.
	</p>

	<?php
}

function display_member_profile_section( $args ) {
	?>

	<p id="<?php echo esc_attr( $args['id'] ); ?>">
		Set prompts (on the new member form) for a user regarding which subheader(s) and tags they should provide. Subheaders appear immediately below the member's name on their profile page. Two subheaders may be specified, and the delimiter between the two can also be selected. The tags appear after the member's profile, and could be "Interests", "Skills", "Talents" or something similar.
	</p>

	<?php
}

function display_headshot_section( $args ) {
	?>

	<p id="<?php echo esc_attr( $args['id'] ); ?>">
		Adjust the maximum size allowed for uploaded headshots. If the value given is larger than the maximum value allowed by your Wordpress installation or your provider, that value will be used instead.
	</p>

	<?php
}

function display_email_notification_section( $args ) {
	?>

	<p id="<?php echo esc_attr( $args['id'] ); ?>">
		Indicate whether the site admin should receive an email whenever a new member form is submitted.
	</p>

	<?php
}

function display_spam_filter_section( $args ) {
	?>

	<p id="<?php echo esc_attr( $args['id'] ); ?>">
		Users must provide an organizational email as a simple precaution against spam submissions. A user will be prompted for the email for the named organization, and the email provided will be checked for the proper domain.
	</p>

	<?php
}

function display_checkbox( $name, $default ) {
	?>

	<input id="<?php echo esc_attr( $name ); ?>" name="<?php echo esc_attr( $name ); ?>" type="checkbox" value="checked" <?php echo esc_attr( $default ) ?>/>

	<?php
}


function display_label( $for, $label) {
	?>

	<label for="<?php echo esc_attr( $for ); ?>"><?php echo esc_html( $label ); ?></label>
	<br>

	<?php
}

function display_text_input( $name, $value, $required = false ) {

	if ( $required ) {
		$required = 'required';
	} else {
		$required = '';
	}
	?>

	<input id="<?php echo esc_attr( $name ); ?>" name="<?php echo esc_attr( $name ); ?>" type="text" value="<?php echo esc_attr( $value ) ?>" <?php echo $required; ?>/>
	<br>

	<?php
}
