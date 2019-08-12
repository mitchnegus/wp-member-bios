<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link
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
	 * @param      string    $version           The version of this plugin.
	 * @param      array     $options           An array of the options set and added to the database by the plugin.
	 * @param      array     $member_meta       An array of the meta fields (and corresponding titles) for the custom member post type.
	 */
	public function __construct( $member_bios, $version, $options, $member_meta ) {

		$this->member_bios = $member_bios;
		$this->version = $version;
		$this->members_custom_post_type = 'members';
		$this->settings_page_slug = 'member-bios-settings';
		$this->option_group = 'member-bios-option-group';
		$this->plugin_options = $options;
		$this->member_meta = $member_meta;
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
		$this->add_group_settings();
		$this->add_member_archive_settings();
		$this->add_member_profile_settings();
		$this->add_headshot_settings();
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
			'member_info-meta',
			'Member Info',
			[$this, 'present_member_metabox_text_inputs'],
			$this->members_custom_post_type,
			'normal',
			'low'
		);
		

	}

	/**
	 * Save member details to the database after an admin decides to update.
	 *
	 * (Executed by loader class)
	 *
	 * @since    1.0.0
	 * @param    int        $post_id              The ID of the custom member post for the given member.
	 */
	public function save_member_details( $post_id ) {

			// Only save meta data for members posts
			if ( get_post_type( $post_id ) == $this->members_custom_post_type ) {

				foreach ( $this->member_meta as $meta ) {
					// Sanitize user input and update the post metadata
					$meta_key = $meta['meta_key'];
					$meta_value = sanitize_text_field($_POST[ $meta_key ]);
					// Make sure that a "Quick Edit" is not saving empty info
					if ( ! empty( $meta_value ) ) {
						update_post_meta( $post_id, $meta_key, $meta_value );
					}
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

		$column1 = 'first_subheader';
		$column2 = 'second_subheader';
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
	 * @return   array                            The columns to be displayed in the 'Members' section of the admin area.
	 */
	public function set_member_columns() {

		$first_subheader = get_option( $this->plugin_options['first_subheader'] );
		$second_subheader = get_option( $this->plugin_options['second_subheader'] );
		$columns = array(
			'cb' 				       => '<input type="checkbox" />',
			'title' 		       => __( 'Member' ),
			'first_subheader'  => __( $first_subheader ),
			'second_subheader' => __( $second_subheader )
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
	 * @access   private
	 */
	private function register_settings() {

		foreach ( $this->plugin_options as $option ) {
				register_setting( $this->option_group, $option );
		}	
	}

	/**
	 * Add a section with fields for managing the maximum headshot size.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function add_group_settings() {

		$section_id = 'group-info';
		$section_label = 'Group Information';
		add_settings_section(
			$section_id,
			$section_label,
			'display_group_info_section',
			$this->settings_page_slug
		);

		$group_name_id = $this->plugin_options['group_name'];
		$group_name_label = 'Group Name';
		add_settings_field(
			$group_name_id,
			$group_name_label,
			[$this, 'present_text_input_option'],
			$this->settings_page_slug,
			$section_id,	
			array( 'label_for' => $group_name_id )
		);
		
	}
	
	/**
	 * Add a section with fields for managing the maximum headshot size.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function add_member_archive_settings() {

		$section_id = 'member-archive';
		$section_label = 'Member Archives';
		add_settings_section(
			$section_id,
			$section_label,
			'display_member_archives_section',
			$this->settings_page_slug
		);

		$ranking_position_id = $this->plugin_options['ranking_position'];
		$ranking_position_label = 'Ranking position';
		add_settings_field(
			$ranking_position_id,
			$ranking_position_label,
			[$this, 'present_text_input_option'],
			$this->settings_page_slug,
			$section_id,	
			array( 'label_for' => $ranking_position_id )
		);
		
		$include_alumni_id = $this->plugin_options['include_alumni'];
		$include_alumni_label = 'Include page for alumni archive';
		add_settings_field(
			$include_alumni_id,
			$include_alumni_label,
			[$this, 'present_checkbox_option'],
			$this->settings_page_slug,
			$section_id,	
			array( 'label_for' => $include_alumni_id )
		);
		
	}

	/**
	 * Add a section to control subheaders of the custom member post.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function add_member_profile_settings() {

		$section_id = 'member_profiles';
		$section_label = 'Member Profiles';
		add_settings_section(
			$section_id,
			$section_label,
			'display_member_profile_section',
			$this->settings_page_slug
		);

		$first_subheader_id = $this->plugin_options['first_subheader'];
		$first_subheader_label = 'First subheader prompt';
		add_settings_field(
			$first_subheader_id,
			$first_subheader_label,
			[$this, 'present_text_input_option'],
			$this->settings_page_slug,
			$section_id,	
			array( 'label_for' => $first_subheader_id )
		);
		$second_subheader_id = $this->plugin_options['second_subheader'];
		$second_subheader_label = 'Second subheader prompt';
		add_settings_field(
			$second_subheader_id,
			$second_subheader_label,
			[$this, 'present_text_input_option'],
			$this->settings_page_slug,
			$section_id,	
			array( 'label_for' => $second_subheader_id )
		);
		$subheader_delimiter_id = $this->plugin_options['subheader_delimiter'];
		$subheader_delimiter_label = 'Delimiter separating subheaders';
		add_settings_field(
			$subheader_delimiter_id,
			$subheader_delimiter_label,
			[$this, 'present_text_input_option'],
			$this->settings_page_slug,
			$section_id,	
			array( 'label_for' => $subheader_delimiter_id )
		);
		$tags_id = $this->plugin_options['tags'];
		$tags_label = 'Tag prompt';
		add_settings_field(
			$tags_id,
			$tags_label,
			[$this, 'present_text_input_option'],
			$this->settings_page_slug,
			$section_id,	
			array( 'label_for' => $tags_id )
		);

	}

	/**
	 * Add a section with fields for managing the maximum headshot size.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function add_headshot_settings() {

		$section_id = 'member-headshots';
		$section_label = 'Member Headshots';
		add_settings_section(
			$section_id,
			$section_label,
			'display_headshot_section',
			$this->settings_page_slug
		);

		$max_headshot_size_id = $this->plugin_options['max_headshot_size'];
		$max_headshot_size_label = 'Maximum headshot size (MB)';
		add_settings_field(
			$max_headshot_size_id,
			$max_headshot_size_label,
			[$this, 'present_text_input_option'],
			$this->settings_page_slug,
			$section_id,	
			array( 'label_for' => $max_headshot_size_id )
		);
		
	}

	/**
	 * Add a section with fields for email notification settings
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function add_email_notification_settings() {

		$section_id = 'email-notifications';
		$section_label = 'Email notifications';
		add_settings_section(
			$section_id,
			$section_label,
			'display_email_notification_section',
			$this->settings_page_slug
		);

		$notification_email_id = $this->plugin_options['notification_email'];
		$notification_email_label = 'Enable email notifications';
		add_settings_field(
			$notification_email_id,
			$notification_email_label,
			[$this, 'present_checkbox_option'],
			$this->settings_page_slug,
			$section_id,	
			array( 'label_for' => $notification_email_id )
		);
		
	}
	
	/**
	 * Add a section with fields for spam filtering settings
	 *
	 * @since    1.0.0
	 * @access   private
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

		$spam_filtering_id = $this->plugin_options['spam_filtering'];
		$spam_filtering_label = 'Enable spam filtering';
		add_settings_field(
			$spam_filtering_id,
			$spam_filtering_label,
			[$this, 'present_checkbox_option'],
			$this->settings_page_slug,
			$section_id,	
			array( 'label_for' => $spam_filtering_id )
		);
		$organization_name_id = $this->plugin_options['organization_name'];
		$organization_name_label = 'Organization name';
		add_settings_field(
			$organization_name_id,
			$organization_name_label,
			[$this, 'present_text_input_option'],
			$this->settings_page_slug,
			$section_id,
			array( 'label_for' => $organization_name_id )
		);
		$organization_domain_id = $this->plugin_options['organization_domain'];
		$organization_domain_label = 'Organization email domain (e.g. berkeley.edu)';
		add_settings_field(
			$organization_domain_id,
			$organization_domain_label,
			[$this, 'present_text_input_option'],
			$this->settings_page_slug,
			$section_id,
			array( 'label_for' => $organization_domain_id )
		);

	}

	/**
	 * Present a checkbox to collect an administrator preference.
	 *
	 * @since 1.0.0
	 * @param    array      $args                 Information to include in the checkbox's HTML.
	 */
	public function present_checkbox_option( $args ) {

		$option_name = $args['label_for'];
		$option_default = get_option( $option_name );
		display_checkbox( $option_name, $option_default );

	}

	/**
	 * Present a text input for a setting in the admin area.
	 *
	 * @since 1.0.0
	 * @param    array      $args                 Information to include in the text input's HTML.
	 */
	public function present_text_input_option( $args ) {

		$option_name = $args['label_for'];
		$option_default = get_option( $option_name );
		display_text_input( $option_name, $option_default );

	}

	/**
	 * Present a text input in an admin area metabox for managing member info.
	 *
	 * @since 1.0.0
	 * @param    WP_POST    $post                 The post associated with the current member.
	 */
	public function present_member_metabox_text_inputs( $post ) {

		foreach ( $this->member_meta as $meta ) {
			$meta_key = $meta['meta_key'];
			$required = $meta['required'];
			$custom = get_post_custom( $post->ID );
			$meta_title = get_option( $this->plugin_options[ $meta_key ] );
			$meta_value = $custom[ $meta_key ][0];
			display_label( $meta_key, $meta_title );
			display_text_input( $meta_key, $meta_value, $required );
		}

	}

}
