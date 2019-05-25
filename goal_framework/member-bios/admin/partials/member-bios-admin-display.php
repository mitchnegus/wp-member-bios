<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Member_Bios
 * @subpackage Member_Bios/admin/partials
 */

?>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<?php

/**
 * Display settings on the admin menu page.
 *
 * @since    1.0.0
 */
private function display_settings( $option_group, $page_slug ) {
	?>

	<div class="wrap">
		<h1><?php esc_html( get_admin_page_title() ); ?></h1>
		<form action="options.php" method="post">

			<?php 
			// Output security fields and then sections defined for the group
			settings_fields( $option_group );
			do_settings_sections( $page_slug );
			submit_button();
			?>

		</form>
	</div>

	<?php
}
