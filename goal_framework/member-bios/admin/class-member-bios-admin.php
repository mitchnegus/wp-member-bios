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
 * (inlcuding enqueuing the admin-specific stylesheet and JavaScript). An
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
	 * @param      string    $member_bios       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $member_bios, $version ) {

		$this->member_bios = $member_bios;
		$this->version = $version;
		// All functions prefixed with 'display_' come from `partials`
		require_once plugin_dir_path( __FILE__ ) . 'partials/member-bios-admin-display.php';
	}

	/**
	 * Register the stylesheets for the admin area.
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
	 * @since    1.0.0
	 */
	public function add_settings_page() {
		
		add_options_page(
				'Member Bios Settings',
				'Member Bios',
				'manage_options',
				'member-bios',
				[$this, 'add_settings_options']
		);
	}

	/**
	 * Add settings (associated with sections) that are available to a an admin.
	 *
	 * @since    1.0.0
	 */
	public function add_settings() {

		$this->register_settings();
		$this->add_email_notification_settings();
		$this->add_spam_filtering_settings();

	}

	/**
	 * Display all registered menu items on the settings page.
	 *
	 * @since    1.0.0
	 */
	private function add_settings_options() {
		
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		$option_group = 'member-bios-option-group';
		$page_slug = 'member-bios';
		display_settings( $option_group, $page_slug );

	}

	/**
	 * Regsister new settings for the plugin's settings page
	 *
	 * @since    1.0.0
	 */
	private function register_settings() {

		$option_group = 'member-bios-option-group';
		register_setting( $option_group, 'organization_name' );
		register_setting( $option_group, 'organization_domain' );
		register_setting( $option_group, 'notification_email' );
		
	}

	/**
	 * Add a section with fields for email notification settings
	 *
	 * @since    1.0.0
	 */
	private function add_email_notification_settings() {

		add_settings_section(
				'email-notifications',
				'Email Notifications',
				'display_email_notification_section',
				'member-bios'
		);

		add_settings_field(
				'notification_email',
				'Notification Email',
				[$this, 'collect_email_preference'],
				'member-bios',
				'email-notifications',
				array('label_for' => 'notification_email')
		);
		
	}
	
	/**
	 * Add a section with fields for spam filtering settings
	 *
	 * @since    1.0.0
	 */
	private function add_spam_filtering_settings() {

		add_settings_section(
				'email-spam-filter',
				'Email-based Spam Filtering',
				'display_spam_filter_section',
				'member-bios'
		);

		add_settings_field(
				'organization_name',
				'Organization Name',
				[$this, 'collect_organization_name'],
				'member-bios',
				'email-spam-filter',
				array('label_for' => 'organization_name')
		);
		add_settings_field(
				'organization_domain',
				'Organization Email Domain (e.g. berkeley.edu)',
				[$this, 'collect_organization_domain'],
				'member-bios',
				'email-spam-filter',
				array('label_for' => 'organization_domain')
		);

	}

	/**
	 * Collect the administrator's email notification preference.
	 *
	 * @since 1.0.0
	 */
	private function collect_email_preference( $args ) {

		$option_name = $args['label_for'];
		$option_default = get_option('notification_email');
		display_checkbox( $option_name, $option_default );

	}

	/**
	 * Collect the organization name.
	 *
	 * @since 1.0.0
	 */
	private function collect_organization_name( $args ) {

		$option_name = $args['label_for'];
		$option_default = get_option('organization_name');
		display_text_input( $option_name, $option_default );

	}

	/**
	 * Collect the organization domain.
	 *
	 * @since 1.0.0
	 */
	private function collect_organization_domain( $args ) {

		$option_name = $args['label_for'];
		$option_default = get_option('organization_domain');
		display_text_input( $option_name, $option_default );

	}

}
