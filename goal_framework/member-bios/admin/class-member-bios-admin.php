<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Member_Bios
 * @subpackage Member_Bios/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and hooks for managing the admin area
 * (including enqueuing the admin-specific stylesheet and JavaScript). An
 * instance of this class should be passed to the run() function defined
 * in Member_Bios_Loader as all of the hooks are actually defined in that
 * particular class. The Member_Bios_Loader will then create the
 * relationship between the defined hooks and the functions defined in this
 * class.
 *
 * @package    Member_Bios
 * @subpackage Member_Bios/admin
 * @author     Mitch Negus <mitchell.negus.57@gmail.com>
 */
class Member_Bios_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $member_bios    The ID of this plugin.
	 */
	private $member_bios;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $member_bios       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $member_bios, $version ) {

		$this->member_bios = $member_bios;
		$this->version = $version;
		$this->settings_page_slug = 'member-bios-settings';
		$this->option_group = 'member-bios-option-group';
		// All functions prefixed with 'display_' come from `partials`
		require_once plugin_dir_path( __FILE__ ) . 'partials/member-bios-admin-display.php';
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * (Executed by loader class)
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		$stylesheet = plugin_dir_url( __FILE__ ) . 'css/member-bios-admin.css';
		wp_enqueue_style( 
			$this->member_bios, 
			$stylesheet,
			array(),
			$this->version, 'all'
	 	);

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * (Executed by loader class)
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		$script = plugin_dir_url( __FILE__ ) . 'js/member-bios-admin.js';
		wp_enqueue_script(
		 	$this->member_bios,
			$script,
			array( 'jquery' ),
			$this->version,
			false
		);

	}

	/**
	 *  Include setup menu page for the plugin in the admin area.
	 *
	 * (Executed by loader class)
	 *
	 * @since    1.0.0
	 */
	public function add_settings_page() {
		
		$page_title = 'Member Bios Settings';
		$menu_title = 'Member Bios';
		add_options_page(
				$page_title,
				$menu_title,
				'manage_options',
				$this->settings_page_slug,
				[$this, 'add_settings_options']
		);
	}

	/**
	 * Add settings (associated with sections) that are available to a an admin.
	 *
	 * (Executed by loader class)
	 *
	 * @since    1.0.0
	 */
	public function add_settings() {

		$this->register_settings();
		$this->add_email_notification_settings();
		$this->add_spam_filtering_settings();

	}

	/**
	 * Add fields to the admin area corresponding to custom post metadata.
	 *
	 * Submitted new member information other than the new member's name, image
	 * and bio (e.g. field of study, expected graduation date, and policy
	 * interests) are stored as post metadata. Input boxes for that metadata in
	 * the admin area are defined here.
	 * (Executed by loader class)
	 *
	 * @since    1.0.0
	 */
	public function add_admin_fields() {

		add_meta_box(
				'subject-meta',
				'Subject',
				[$this, 'get_subject'],
				'members',
				'normal',
				'low'
		);
		add_meta_box(
				'grad_date-meta',
				'Graduation',
				[$this, 'get_grad_date'],
				'members',
				'normal',
				'low'
		);
		add_meta_box(
				'interests-meta',
				'Interests',
				[$this, 'get_interests'],
				'members',
				'normal',
				'low'
		);

	}

	/**
	 * Display all registered menu items on the settings page.
	 *
	 * @since    1.0.0
	 */
	public function add_settings_options() {
		
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		display_settings( $this->option_group, $this->settings_page_slug );

	}

	/**
	 * Regsister new settings for the plugin's settings page
	 *
	 * @since    1.0.0
	 */
	private function register_settings() {

		register_setting( $this->option_group, 'organization_name' );
		register_setting( $this->option_group, 'organization_domain' );
		register_setting( $this->option_group, 'notification_email' );
		
	}

	/**
	 * Add a section with fields for email notification settings
	 *
	 * @since    1.0.0
	 */
	private function add_email_notification_settings() {

		$section_id = 'email-notifications';
		$section_label = 'Email Notifications';
		add_settings_section(
				$section_id,
				$section_label,
				'display_email_notification_section',
				$this->settings_page_slug
		);

		$notification_email_id = 'notification_email';
		$notification_email_label = 'Notification Email';
		add_settings_field(
				$notification_email_id,
				$notification_email_label,
				[$this, 'collect_checkbox_preference'],
				$this->settings_page_slug,
				$section_id,	
				array('label_for' => $notification_email_id)
		);
		
	}
	
	/**
	 * Add a section with fields for spam filtering settings
	 *
	 * @since    1.0.0
	 */
	private function add_spam_filtering_settings() {

		$section_id = 'spam-filter';
		$section_label = 'Email-based Spam Filtering';
		add_settings_section(
				$section_id,
				$section_label,
				'display_spam_filter_section',
				$this->settings_page_slug
		);

		$organization_name_id = 'organization_name';
		$organization_name_label = 'Organization Name';
		add_settings_field(
				$organization_name_id,
				$organization_name_label,
				[$this, 'collect_text_input'],
				$this->settings_page_slug,
				$section_id,
				array('label_for' => $organization_name_id)
		);
		$organization_domain_id = 'organization_domain';
		$organization_domain_label = 'Organization Email Domain (e.g. berkeley.edu)';
		add_settings_field(
				$organization_domain_id,
				$organization_domain_label,
				[$this, 'collect_text_input'],
				$this->settings_page_slug,
				$section_id,
				array('label_for' => $organization_domain_id)
		);

	}

	/**
	 * Collect the administrator's email notification preference.
	 *
	 * @since 1.0.0
	 */
	public function collect_checkbox_preference( $args ) {

		$option_name = $args['label_for'];
		$option_default = get_option( $option_name );
		display_checkbox( $option_name, $option_default );

	}

	/**
	 * Collect the text input from a formatted input box.
	 *
	 * @since 1.0.0
	 */
	public function collect_text_input( $args ) {

		$option_name = $args['label_for'];
		$option_default = get_option( $option_name );
		display_text_input( $option_name, $option_default, $required=false );

	}

	// Function for collecting custom data on the admin page (member's discipline)
	public function get_subject() {

		global $post;

		$custom = get_post_custom($post->ID);
		$subject = $custom['subject'][0];
		?>
		<label for="subject">Subject area:</label>
		<?php
		display_text_input( 'subject', $subject, $required=true );

	}

	// Function for collecting custom data on the admin page (graduation date)
	public function get_grad_date()
	{
			global $post;

			$custom = get_post_custom($post->ID);
			$grad_date = $custom['grad_date'][0];
			?>
			<label for="grad_date">Expected Graduation Date:</label>
			<?php
			display_text_input( 'grad_date', $grad_date, $required=true );

	}
	
	// Function for collecting custom data on the admin page (member's interests)
	public function get_interests()
	{
			global $post;
			$custom = get_post_custom($post->ID);
			$interests = $custom['interests'][0];
			?>
			<label for="interests">Member interests:</label>
			<br>
			<textarea id="interests" name="interests" cols="50" rows="2"  required><?php
			echo esc_textarea($interests);
			?></textarea>
			<?php
	}
}
