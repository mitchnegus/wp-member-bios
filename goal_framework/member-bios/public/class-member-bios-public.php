<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Member_Bios
 * @subpackage Member_Bios/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 * Defines the plugin name, version, and hooks for managing the public front
 * end (including enqueuing the admin-specific stylesheet and JavaScript). An
 * instance of this class should be passed to the run() function defined
 * in Member_Bios_Loader as all of the hooks are actually defined in that
 * particular class. The Member_Bios_Loader will then create the
 * relationship between the defined hooks and the functions defined in this
 * class.
 *
 * @package    Member_Bios
 * @subpackage Member_Bios/public
 * @author     Mitch Negus <mitchell.negus.57@gmail.com>
 */
class Member_Bios_Public {

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

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style(
			$this->member_bios,
			plugin_dir_url( __FILE__ ) . 'css/member-bios-public.css',
			array(),
			$this->version,
			'all'
	 	);

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script(
			$this->member_bios,
			plugin_dir_url( __FILE__ ) . 'js/member-bios-public.js',
			array( 'jquery' ),
			$this->version,
			false
		);

	}

	/**
	 * Register the custom post type for a member.
	 *
	 * Each group member has an individual post that stores their information
	 * (name, expected graduation date, subject, interests, bio, etc.). This 
	 * post is also accessed for display on the general members page, where 
	 * all members of the organization are listed.
	 *
	 * @since    1.0.0
	 */
	public function register_member_post_type() {

		$labels = array(
			'name' 					=> __('Members'),
			'singular_name' => __('Member'),
			'add_new_item' 	=> __('Add New Member'),
			'edit_item' 		=> __('Edit Member')
		);

		$args = array(
			'labels' 			=> $labels,
			'public'			=> true,
			'has_archive' => true,
			'rewrite' 		=> array('slug' => 'members'),
			'supports' 		=> array('title', 'editor', 'thumbnail'),
			'menu_icon' 	=> 'dashicons-groups'
		);

		register_post_type('members', $args);
		flush_rewrite_rules();

	}

	/**
	 * Register the positions taxonomy for a member post.
	 *
	 * Each group member can hold many different positions in the group.
	 * This taxonomy allows positions like general member, executive, co-founder
	 * or alumni to be included for any given member.
	 *
	 * @since    1.0.0
	 */
	public function register_positions_taxonomy() {

  	$labels = array(
				'name' 					=> __('Positions'),
				'singular_name' => __('Position'),
				'add_new_item' 	=> __('Add New Position')
		);

		$args = array(
				'labels' 	=> $labels,
				'rewrite'	=> array('slug' => 'positions')
		);

		register_taxonomy('positions', array('members'), $args);

	}

}
