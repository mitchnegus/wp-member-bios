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

global $PLUGIN_DIR;
global $new_member_form_template, $new_member_confirmation_template;
global $new_member_form_title, $new_member_confirmation_title;

$new_member_form_template = $PLUGIN_DIR . 'goal_framework/member-bios/public/templates/new-member-form.php';
$new_member_confirmation_template = $PLUGIN_DIR . 'goal_framework/member-bios/public/templates/new-member-confirmation.php';
$new_member_form_title = 'New Member';
$new_member_confirmation_title = 'Submission accepted';

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and hooks for managing the public front
 * end (including enqueuing the public-facing stylesheet and JavaScript). An
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
	 * (Executed by loader class)
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
	 * (Executed by loader class)
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
	 * (Executed by loader class)
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
	 * (Executed by loader class)
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

	/**
	 * Accept user input from the new member form page.
	 *
	 * After a member submits information through the new member page, process
	 * that information and create a new post from that information. The new post
	 * is saved as a draft, and can be found under the 'Members' section in the
	 * admin area. This function includes checks on the submission's validity, 
	 * returning an error upon submission of invalid information. If the user does
	 * not choose to include a photo, the user's thumbnail is left unset (the
	 * displays of user information handle cases of users without thumbnails,
	 * using a substitute template image instead).  
	 * (Executed by loader class)
	 *
	 * @since    1.0.0
	 */
	public function submit_new_member_form() {

		// Check submission validity
		$this->check_nonce();
		$this->check_email();
		$upload_present = $this->check_upload_present();
		if ( $upload_present ) {
			$this->check_upload_validity();
		}

		// Create a new post for the member
		$inputs = $this->sanitize_input_fields();
		$post_id = $this->create_new_post( $inputs );
	  if ( $upload_present ) {	
				// Upload the image to the media library and assign to the post
				$attachment_id = media_handle_upload( 'photo', $post_id );
				set_post_thumbnail( $post_id, $attachment_id );
		}

		// Send the user to the confirmation page and notify the admin (if desired) 
		wp_redirect( home_url() . '/new-member-confirmation' );
		if ( get_option( 'notification_email' ) == 'checked' ) {
			$this->notify_admin_on_submission( $inputs );
		}

	}

	/**
	 * Include a template for the new member page from the plugin.
	 *
	 * Loads the template for the new member form page out of the available 
	 * templates. The template has text input boxes for entering the new
	 * member's name, bio, field of study, expected graduation date, and policy
	 * interests.
	 * (Executed by loader class)
	 *
	 * @since    1.0.0
	 */
	public function include_new_member_template( $template ) {

		global $new_member_form_template, $new_member_form_title;

		if ( is_page( $new_member_form_title ) ) {
			return $new_member_form_template;
		}
		return $template;

	}

	/**
	 * Include a template for the submission confirmation page.
	 * 
	 * After a user successfully submits a new member request form, this template
	 * will be used to display a confirmation page.
	 * (Executed by loader class)
	 *
	 * @since    1.0.0
	 */
	public function include_submit_confirmation_template( $template ) {

		global $new_member_confirmation_template, $new_member_confirmation_title;

		if ( is_page( $new_member_confirmation_title ) ) {
			return $new_member_confirmation_template;
		}
		return $template;

	}

	/**
	 * Check that the email provided is a valid user email address.
	 *
	 * User submitted emails are checked for validity. The validation is a two-
	 * step process: first emails are checked by Wordpress to ensure a valid
	 * email format, and then they are checked to ensure that they match the
	 * admin-specified organization domain.
	 *
	 * @since    1.0.0
	 */
	private function check_email() {

		// Check that the email is of the correct format
		$email = $_POST['email'];
		if ( ! is_email( $email ) ) {
			wp_die(
				'Invalid email provided. Please go back and use a different one.',
				'Error',
				array( 'response'=> 403 )
			);
		}

		// Check that the email is from the correct organization domain (not spam)
		$org = get_option( 'organization_name' );
		$org_domain = get_option( 'organization_domain' );
		$submitted_domain = substr( $email, -strlen( $org_domain ) );
		if ( $submitted_domain != $org_domain ) {
			wp_die(
				'Invalid email provided. Please go back and make sure to use your ' . esc_html($org) . ' email address.',
				'Error',
				array( 'response'=> 403 )
			);
		}

	}

	/**
	 * Check whether or not the user uploaded an image with their submission.
	 *
	 * @since    1.0.0
	 */
	private function check_upload_present() {
		$photo = $_FILES['photo'];
		if ( empty( $_FILES ) || ! isset( $photo ) || $photo['error'] == 4 ) {
			return false;
		} else {
			return true;
		}

	}

	/**
	 * Check that the image upload is valid, otherwise stop.
	 *
	 * @since    1.0.0
	 */
	private function check_upload_validity() {
		if (!check_file_meets_specs()) {
			wp_die(
				'The image you uploaded does not meet the specifications. Please go back and upload a new image.',
			 	'Error',
			 	array( 'response'=> 403 )
			);
		}

	}
	
	/**
	 * Check that the file was uploaded and meets specifications
	 *
	 * @since    1.0.0
	 */
function check_file_meets_specs() {

		$allowed_image_types = array('image/jpeg', 'image/png');
		$file_size = $_FILES['photo']['size'];
		$file_type = $_FILES['photo']['type'];
		if ( ! isset( $file_size ) || $file_size == 0 ) {
				return false;
		}
		if ( $file_size > get_option( 'wmb_max_headshot_size' )*1e6 ) {
		 		return false;	
		}
		if ( ! in_array( $file_type, $allowed_image_types ) ) {
				return false;
		}
		return true;

	}

	/**
	 * Sanitize all input fields from the user's POST request
	 *
	 * @since    1.0.0
	 */
	private function sanitize_input_fields() {

		$sanitized_inputs = array(
				'name' 			=> sanitize_text_field( $_POST['name'] ),
				'email' 		=> sanitize_email( $_POST['email'] ),
				'subject' 	=> sanitize_text_field( $_POST['subject'] ),
				'grad_date' => sanitize_text_field( $_POST['grad_date'] ),
				'interests' => sanitize_text_field( $_POST['interests'] ),
				'bio'				=> sanitize_text_field( $_POST['bio'] )
		);
		return $sanitized_inputs;

	}

	/**
	 * Create a new post based on the user's inputs.
	 * 
	 * @since    1.0.0
	 */
	private function create_new_post( $inputs ) {

		// Assign data for the post
		$post_arr = array(
				'post_type'    => 'members',
				'post_title'   => $inputs['name'],
				'post_content' => $inputs['bio'],
				'post_name'    => 'test-new-member'
		);

		// Insert a post for the new member with accompanying metadata
		$post_id = wp_insert_post( $post_arr );
		update_post_meta( $post_id, 'subject', $inputs['subject'] );
		update_post_meta( $post_id, 'grad_date', $inputs['grad_date'] );
		update_post_meta( $post_id, 'interests', $inputs['interests'] );
		return $post_id;
	}
	
	/**
	 * Notify the site administrator when someone submits the form (if desired).
	 * 
	 * @since    1.0.0
	 */
	private function notify_admin_on_submission( $inputs ) {

		$recipient = get_option( 'admin_email' );
		$subject = 'SPG new member: ' . $inputs['name'];
		$message = $inputs['name'] . ' has submitted a new member request.';
		wp_mail( $recipient, $subject, $message );
	}

}

