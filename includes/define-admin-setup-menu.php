<?php
/**
 * Function definitions for the "Members" custom post type
 */

// Define a function to load a setup menu page for the plugin
function add_setup_menu_page()
{
		add_options_page(
				'Member Bios Settings',
				'Member Bios',
				'manage_options',
				'member-bios',
				'add_setup_options'
		);
}

// Define a function to load specific menu items on the menu page
function add_setup_options()
{
		if (!current_user_can('manage_options'))
				wp_die( __('You do not have sufficient permissions to access this page.'));
		?>
		<div class="wrap">
			<h1><?php esc_html(get_admin_page_title()); ?></h1>
			<form action="options.php" method="post">
					<?php 
					// Output security fields
					settings_fields('member-bios-option-group');
					// Output settings sections that have been defined for the group
					do_settings_sections('member-bios');
					// Create a submit button
					submit_button();
					?>
			</form>
		</div>
		<?php
}

function register_member_bios_settings()
{
		// Register new settings for the plugin's settings page
		register_setting('member-bios-option-group', 'organization_name');
		register_setting('member-bios-option-group', 'notification_email');
		// Register a new settings sections on the plugin's settings page
		add_settings_section(
				'email-notifications',
				'Email Notifications',
				'define_email_notification_section',
				'member-bios'
		);
		add_settings_section(
				'email-spam-filter',
				'Email-based Spam Filtering',
				'define_spam_filter_section',
				'member-bios'
		);
		// Register settings fields for email notifications
		add_settings_field(
				'notification_email',
				'Notification Email',
				'collect_notification_email_setting',
				'member-bios',
				'email-notifications',
				array('label_for' => 'notification_email')
		);
		// Register settings fields for email based spam filtering
		add_settings_field(
				'organization_name',
				'Organization Name',
				'collect_organization_name_setting',
				'member-bios',
				'email-spam-filter',
				array('label_for' => 'organization_name')
		);
		add_settings_field(
				'organization_domain',
				'Organization Email Domain (e.g. berkeley.edu)',
				'collect_organization_domain_setting',
				'member-bios',
				'email-spam-filter',
				array('label_for' => 'organization_domain')
		);
}

function define_email_notification_section($args)
{
		?>
		<p id="<?php echo esc_attr($args['id']); ?>">
				To receive an email notification whenever someone submits a new member form, fill out the following info:
		</p>
		<?php
}

function define_spam_filter_section($args)
{
		?>
		<p id="<?php echo esc_attr($args['id']); ?>">
				Users must provide an organizational email as a simple precaution against spam submissions. A user will be prompted for the email for the named organization, and the email provided will be checked for the proper domain.
		</p>
		<?php
}

function collect_notification_email_setting($args)
{
		// Get the value of the setting already registered
		$options = get_option('notification_email');
		// Output the field
		$opt_name = esc_attr($args['label_for']);
		?>
		<input id="<?php echo $opt_name; ?>" name="<?php echo $opt_name; ?>" type="text"/>
		<?php
}

function collect_organization_name_setting($args)
{
		// Get the value of the setting already registered
		$options = get_option('organization_name');
		// Output the field
		$opt_name = esc_attr($args['label_for']);
		?>
		<input id="<?php echo $opt_name; ?>" name="<?php echo $opt_name; ?>" type="text"/>
		<?php
}

function collect_organization_domain_setting($args)
{
		// Get the value of the setting already registered
		$options = get_option('organization_domain');
		// Output the field
		$opt_name = esc_attr($args['label_for']);
		?>
		<input id="<?php echo $opt_name; ?>" name="<?php echo $opt_name; ?>" type="text"/>
		<?php
}
