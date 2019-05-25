<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Member_Bios
 * @subpackage Member_Bios/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Member_Bios
 * @subpackage Member_Bios/includes
 * @author     Mitch Negus <mitchell.negus.57@gmail.com>
 */

global $PLUGIN_DIR;
global $new_member_form_title, $new_member_confirmation_title;

$new_member_form_title = 'New Member';
$new_member_confirmation_title = 'Submission accepted';

class Member_Bios_Deactivator {

	/**
	 * Deactivate the plugin, removing the new members page and confirmation.
	 *
	 * Upon deactivation, this class handles the removal of the new membes page
	 * and submission confirmation page from the database. (Pages were added to
	 * the database upon activation.)
	 *
	 * @since    1.0.0
	 */

	public static function deactivate() {
		Member_Bios_Deactivator::remove_new_member_form_page();
		Member_Bios_Deactivator::remove_new_member_confirmation_page();
	}

	public static function remove_new_member_form_page(){
		global $new_member_form_title;
		$form_page = get_page_by_title( $new_member_form_title );
		wp_delete_post( $form_page->ID, true );
	}

	public static function remove_new_member_confirmation_page(){
		global $new_member_confirmation_title;
		$confirmation_page = get_page_by_title( $new_member_confirmation_title );
		wp_delete_post( $confirmation_page->ID, true );
	}

}
