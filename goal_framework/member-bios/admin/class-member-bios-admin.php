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
		$this->members_custom_post_type = 'members';
		$this->settings_page_slug = 'member-bios-settings';
		$this->option_group = 'member-bios-option-group';
		// Define settings to be added to the database
		$this->plugin_settings = array(
			'notification_email'  => 'wmb_notification_email',
			'organization_name'   => 'wmb_organization_name',
			'organization_domain' => 'wmb_organization_domain'
		);
		// Define member provided meta fields
		$this->member_meta_info = array(
			'subject'   => 'Field of Study',
			'grad_date' => 'Graduation Date',
			'interests' => 'Policy Interests'
		);
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

		foreach ( $this->member_meta_info as $meta_key => $meta_title ) {
			$meta_args = array(
				'label_for'   => $meta_key,
				'label_title' => $meta_title
			);
			add_meta_box(
				$meta_key . '-meta',
				$meta_title,
				[$this, 'present_metabox_text_input'],
				$this->members_custom_post_type,
				'normal',
				'low',
				$meta_args
			);
		}

	}

	/**
	 * Save member details to the database after an admin decides to update.
	 *
	 * (Executed by loader class)
	 *
	 * @since    1.0.0
	 */
	public function save_member_details( $post_id ) {

			// Only save meta data for members posts
			if ( get_post_type( $post_id ) == $this->members_custom_post_type ) {

				foreach ( $this->member_meta_info as $meta_key => $meta_title ) {
					// Sanitize user input and update the post metadata
					$meta_value = sanitize_text_field($_POST[ $meta_key ]);
					update_post_meta( $post_id, $meta_key, $meta_value );
				}

			}
	}

	/**
	 * Fill columns in the admin area with custom post information.
	 *
	 * In the admin area, an administrator can see a list of all members
	 * currently listed on the site (as well as drafts for approval). 
	 * This function populates columns in that list with relevant information
	 * about each member.
	 * (Executed by loader class)
	 *
	 * @since    1.0.0
	 */
	public function fill_member_columns() {

		$column1 = 'subject';
		$column2 = 'grad_date';
		$custom = get_post_custom();
		switch ( $column ) {
			case $column1:
				echo $custom[ $column1 ][0];
				break;
			case $column2:
				echo $custom[ $column2 ][0];
				break;
		}

	}

	/**
	 * Show columns on the list of all members in the admin area.
	 *
	 * (Executed by loader class)
	 *
	 * @since    1.0.0
	 */
	public function set_member_columns() {

		$extra_column1 = 'subject';
		$extra_column2 = 'grad_date';
		$columns = array(
				'cb' 				    => '<input type="checkbox" />',
				'title' 		    => __( 'Member' ),
				$extra_column1 	=> __( $this->member_meta_info[ $extra_column1 ] ),
				$extra_column2 	=> __( $this->member_meta_info[ $extra_column2 ] ),
		);
		return $columns;

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

		foreach ( $this->plugin_settings as $setting ) {
			register_setting( $this->option_group, $setting );
		}	

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

		$notification_email_id = $this->plugin_settings['notification_email'];
		$notification_email_label = 'Notification Email';
		add_settings_field(
				$notification_email_id,
				$notification_email_label,
				[$this, 'present_checkbox_setting'],
				$this->settings_page_slug,
				$section_id,	
				array( 'label_for' => $notification_email_id )
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

		$organization_name_id = $this->plugin_settings['organization_name'];
		$organization_name_label = 'Organization Name';
		add_settings_field(
				$organization_name_id,
				$organization_name_label,
				[$this, 'present_text_input_setting'],
				$this->settings_page_slug,
				$section_id,
				array( 'label_for' => $organization_name_id )
		);
		$organization_domain_id = $this->plugin_settings['organization_domain'];
		$organization_domain_label = 'Organization Email Domain (e.g. berkeley.edu)';
		add_settings_field(
				$organization_domain_id,
				$organization_domain_label,
				[$this, 'present_text_input_setting'],
				$this->settings_page_slug,
				$section_id,
				array( 'label_for' => $organization_domain_id )
		);

	}

	/**
	 * Present a checkbox to collect an administrator preference.
	 *
	 * @since 1.0.0
	 */
	public function present_checkbox_setting( $args ) {

		$option_name = $args['label_for'];
		$option_default = get_option( $option_name );
		display_checkbox( $option_name, $option_default );

	}

	/**
	 * Present a text input for a setting in the admin area.
	 *
	 * @since 1.0.0
	 */
	public function present_text_input_setting( $args ) {

		$option_name = $args['label_for'];
		$option_default = get_option( $option_name );
		display_text_input( $option_name, $option_default, $required = false );

	}

	/**
	 * Present a text input in an admin area metabox for managing member info.
	 *
	 * @since 1.0.0
	 */
	public function present_metabox_text_input( $post, $metabox ) {

		$info = $this->get_metabox_info( $post, $metabox );
		display_label( $info['name'], $info['title'] );
		display_text_input( $info['name'], $info['value'], $required = true );

	}
	
	/**
	 * Present a text area in an admin area metabox for managing member info.
	 *
	 * @since 1.0.0
	 */
	public function present_metabox_text_area( $post, $metabox ) {
		
		$info = $this->get_metabox_info( $post, $metabox );
		display_label( $info['name'], $info['title'] );
		display_text_area( $info['name'], $info['value'], $required = true );

	}

	/**
	 * Get the metabox name and title, along with the corresponding post value.
	 *
	 * @since 1.0.0
	 */
	private function get_metabox_info( $post, $metabox ) {

		$name = $metabox['args']['label_for'];
		$title = $metabox['args']['label_title'];
		$custom = get_post_custom( $post->ID );
		$value = $custom[ $name ][0];
		$info = array(
			'name'  => $name,
			'title' => $title,
			'value' => $value
		);
		return $info;

	}

}
