<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link
 * @since      1.0.0
 *
 * @package    Member-Bios
 * @subpackage Member-Bios/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Member-Bios
 * @subpackage Member-Bios/includes
 * @author     Mitch Negus <mitchell.negus.57@gmail.com>
 */
class Member_Bios {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Member_Bios_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $member_bios    The string used to uniquely identify this plugin.
	 */
	protected $member_bios;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		// Set plugin overhead details
		if ( defined( 'MEMBER_BIOS_VERSION' ) ) {
			$this->version = MEMBER_BIOS_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->member_bios = 'member-bios';
		// Create an array of options that are added to the database by the plugin
		// 		-> Keys are the in-code reference names
		// 		-> Values are the option names in the database
		$this->plugin_options = array(
			'first_subheader'     => 'wmb_first_subheader',
			'second_subheader'    => 'wmb_second_subheader',
			'subheader_delimiter' => 'wmb_subheader_delimiter',
			'tags'                => 'wmb_tags',
		 	'max_headshot_size'   => 'wmb_max_headshot_size',
			'notification_email'  => 'wmb_notification_email',
			'spam_filtering'      => 'wmb_spam_filtering',
			'organization_name'   => 'wmb_organization_name',
			'organization_domain' => 'wmb_organization_domain'
		);
		// Create an array of meta keys that are assigned to a custom member posts
		$this->member_meta = array(
			'first_subheader',
			'second_subheader',
			'tags'
		);

		// Load plugin dependencies and set actions and filters for hooks
		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Member_Bios_Loader. Orchestrates the hooks of the plugin.
	 * - Member_Bios_i18n. Defines internationalization functionality.
	 * - Member_Bios_Admin. Defines all hooks for the admin area.
	 * - Member_Bios_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-member-bios-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-member-bios-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-member-bios-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-member-bios-public.php';

		$this->loader = new Member_Bios_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Member_Bios_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Member_Bios_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Member_Bios_Admin( 
			$this->get_member_bios(),
			$this->get_version(),
			$this->get_plugin_options(),
			$this->get_member_meta()
	 	);

		// Set admin area styles and JavaScript
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		// Add admin area settings page
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_settings_page' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'add_settings' );
		// Provide admin area controls for member custom posts
		$this->loader->add_action( 'admin_init', $plugin_admin, 'add_admin_fields');
		$this->loader->add_action( 'save_post', $plugin_admin, 'save_member_details');
		// Update the columns on the browse members page
		$this->loader->add_action('manage_members_posts_custom_column', $plugin_admin, 'fill_member_columns', 10, 2);
		$this->loader->add_filter('manage_members_posts_columns', $plugin_admin, 'set_member_columns');

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Member_Bios_Public( 
			$this->get_member_bios(),
			$this->get_version(),
			$this->get_plugin_options(),
			$this->get_member_meta()
	 	);

		// Set public-facing styles and JavaScript
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		// Hook up our custom post to theme setup
		$this->loader->add_action( 'init', $plugin_public, 'register_member_post_type' );
		$this->loader->add_action( 'init', $plugin_public, 'register_positions_taxonomy');
		// Accept user input
		$this->loader->add_action( 'admin_post_submit_member', $plugin_public, 'submit_new_member_form' );
		$this->loader->add_action( 'admin_nopriv_post_submit_member', $plugin_public, 'submit_new_member_form' );
		// Use a custom template for the new member and submission success pages
		$this->loader->add_filter( 'template_include', $plugin_public, 'include_new_member_template' );
		$this->loader->add_filter( 'template_include', $plugin_public, 'include_submit_confirmation_template' );
		// Use custom templates for the member pages
		$this->loader->add_filter( 'single_template', $plugin_public, 'use_custom_member_single_template' );
		$this->loader->add_filter( 'archive_template', $plugin_public, 'use_custom_member_archive_template' );
		// Format the 'Members' page properly
		$this->loader->add_action( 'pre_get_posts', $plugin_public, 'show_all_members' );
		$this->loader->add_action( 'pre_get_posts', $plugin_public, 'alpha_order_members' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {

		// Add theme support for thumbnails if not already included
		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 200, 200 );
		$this->set_plugin_max_headshot_size();
		// Run the loader (with hooks for actions and filters)
		$this->loader->run();

	}

	/**
	 * Set the maximum headshot size that is allowed to be uploaded (in MB).
	 *
	 * Defines the maximum allowable headshot file size that can be uploaded
	 * by a new member. If the default Wordpress value is greater than 2 MB
	 * then the maximum size will be limited to 2 MB. This value can be
	 * overridden on the settings page. The value returned is in megabytes (MB).
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_plugin_max_headshot_size() {

		$option = 'wmb_max_headshot_size';
		// Set the maximum image size for user uploaded headshots
		$current_max_headshot_megabytes = get_option( $option );	
		$current_max_headshot_size = $current_max_headshot_megabytes*1e6;
		$max_wordpress_upload_size = wp_max_upload_size();
		$fallback_max_size = 2e6;

		if ( $current_max_headshot_megabytes == '' ) {
			// Current size not set; use the smaller of the fallback or Wordpress max
			if ( $max_wordpress_upload_size > $fallback_max_size ) {
	  		$plugin_max_headshot_size = $fallback_max_size;   // (Approx. 3 MB)
	    } else {
	       $plugin_max_headshot_size = $max_wordpress_upload_size;
	    }
		} elseif ( $current_max_headshot_size > $max_wordpress_upload_size ) {
			// Current size is set large; use the Wordpress max
			$plugin_max_headshot_size = $max_wordpress_upload_size;
		} else {
			$plugin_max_headshot_size = $current_max_headshot_size;
		}
		$plugin_max_headshot_megabytes = $plugin_max_headshot_size/1e6;
		update_option( $option, $plugin_max_headshot_megabytes );

	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_member_bios() {
		return $this->member_bios;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Member_Bios_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Retrieve the options that are added to the database by the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    An array of options that are set by the plugin.
	 */
	public function get_plugin_options() {
		return $this->plugin_options;
	}

	/**
	 * Retrieve the custom member post meta keys.
	 *
	 * @since     1.0.0
	 * @return    string    An array of custom post meta keys used by the plugin.
	 */
	public function get_member_meta() {
		return $this->member_meta;
	}

}
