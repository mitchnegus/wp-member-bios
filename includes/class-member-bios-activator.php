<?php

/**
 * Fired during plugin activation
 *
 * @link
 * @since      1.0.0
 *
 * @package    Member_Bios
 * @subpackage Member_Bios/includes
 */

global $new_member_form_template, $new_member_confirmation_template;
global $new_member_form_title, $new_member_confirmation_title;

$new_member_form_template = WMB_PATH . 'public/templates/new-member-form.php';
$new_member_confirmation_template = WMB_PATH . 'public/templates/new-member-confirmation.php';
$new_member_form_title = 'New Member';
$new_member_confirmation_title = 'Submission accepted';

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Member_Bios
 * @subpackage Member_Bios/includes
 * @author     Mitch Negus <mitchell.negus.57@gmail.com>
 */
class Member_Bios_Activator {

	/**
	 * Activate the plugin, specifically creating the new member request page.
	 *
	 * The Member Bios plugin accepts user submissions with profile information.
	 * This information is provided on a special template form page, and this
	 * is linked into the theme upon plugin activation. There is also an
	 * accompanying confirmation page that is also loaded into the theme upon
	 * activation.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		Member_Bios_Activator::add_new_member_form_page();
		Member_Bios_Activator::add_new_member_confirmation_page();

	}

	/**
	 * Add a page with a new member form using the given template and title.
	 *
	 * @since    1.0.0
	 */
	public static function add_new_member_form_page() {

		global $new_member_form_template, $new_member_form_title; 

		// Create the page if it doesn't already exist
		$page = get_page_by_title( $new_member_form_title ); 

		if ( ! isset( $page->ID ) ) {
				$page_title = $new_member_form_title;
				$page_arr = array(
						'post_type'   => 'page',
						'post_title'  => $page_title,
						'post_status' => 'publish',
						'post_name'   => 'new-member'
				);
				$page_id = wp_insert_post( $page_arr );
				$template = $new_member_form_template;
				update_post_meta( $page_id, '_wp_page_template', $template );

		}

	}

	/**
	 * Add a submission confirmation page using the given template and title.
	 *
	 * @since    1.0.0
	 */
	public static function add_new_member_confirmation_page() {

		global $new_member_confirmation_template, $new_member_confirmation_title;

		// Create the page if it doesn't already exist
		$page = get_page_by_title( $new_member_confirmation_title ); 

		if ( ! isset ( $page->ID ) ) {
				$page_title = $new_member_confirmation_title;
				$page_arr = array(
						'post_type'   => 'page',
						'post_title'  => $page_title,
						'post_status' => 'publish',
						'post_name'   => 'new-member-confirmation'
				);
				$page_id = wp_insert_post( $page_arr );
				$template = $new_member_confirmation_template;
				update_post_meta( $page_id, '_wp_page_template', $template );

		}

	}

}
