<?php
/**
 * Template Name: 
 * The template file for a new member page.
 *
 * This template file type is used for displaying a new member page. The page
 * requests information from a potential member. This information could pertain
 * to their academic information (field, graduation date) and/or their
 * interests. It also provides the opportunity for the member to provide a
 * short bio and a profile photo.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 */
namespace Member_Bios;

global $max_headshot_size;

$given_org = get_option( 'wmb_organization_name' );
$organization = ($given_org == '' ? 'Organization' : $given_org ); 
$first_subheader = get_option( 'wmb_first_subheader' );
$second_subheader = get_option( 'wmb_second_subheader' );
$tags_title = get_option( 'wmb_tags' );

get_header();
?>

<div id="primary" class="content-area">
	<main id="main" class="site-main">
		
		<div class="wp-member-bios">
			<h1><?php the_title(); ?></h1>
			<img src="<?php echo WMB_URL; ?>/img/headshot_template.png" style="display: block; width: 25%; margin: 0 auto;" alt="headshot template" />
			<br>
			<p>If you’re a member but not already on the site, please fill out the following form. If you’d like, you may also include:</p>
			<ul>
				<li>a short paragraph (2 sentences max) describing yourself</li>
				<li>a photo (square images work best)</li>
			</ul>
	
			<form id="new-member-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" enctype="multipart/form-data" method="post">
				<input type="hidden" name="action" value="submit_member" />
				<?php wp_nonce_field( 'add_new_member_nonce', 'new_member_form_nonce' ); ?>
				<div style="display: flex; width: 70%;">
					<div class="new-member-form-element" style="flex-grow: 3;">
						<label for="name" class="new-member-form-label">
							Name 
							<span class="new-member-form-label-required">(required)</span>
						</label>
						<input type="text" id="name" name="name" class="full-width" required/>
					</div>
	
					<?php
					if ( get_option( 'wmb_spam_filtering' ) == 'checked' ) { 	
						?>
	
						<div class="new-member-form-element" style="flex-grow: 2;" >
							<label for="email" class="new-member-form-label">
								<?php echo esc_html( $organization ); ?> email
								<span class="new-member-form-label-required">(required)</span>
							</label>
							<input type="text" id="email" name="email" class="full-width" required/>
						</div>
	
						<?php
					}
					?>
	
				</div>
	
				<?php
				if ( $first_subheader ) {
					?>
	
					<div class="new-member-form-element" style="width: 70%;">
						<label for="first_subheader" class="new-member-form-label">
							<?php echo esc_html( $first_subheader ); ?>
							<span class="new-member-form-label-required">(required)</span>
						</label>
						<input type="text" id="first_subheader" name="first_subheader" class="full-width" required/>
					</div>
	
					<?php
				}
				
				if ( $second_subheader ) {
					?>
		
					<div class="new-member-form-element" style="width: 70%;">
						<label for="second_subheader" class="new-member-form-label">
							<?php echo esc_html( $second_subheader ); ?>
							<span class="new-member-form-label-required">(required)</span>
						</label>
						<input type="text" id="second_subheader" name="second_subheader" class="full-width" required/>
					</div>
	
					<?php
				}
				
				if ( $tags_title ) {
					?>
	
					<div class="new-member-form-element" class="full-width">
						<label for="tags" class="new-member-form-label">
							<?php echo get_option( 'wmb_tags' ); ?>
							<span class="new-member-form-label-required">(required)</span>
						</label>
						<input type="text" id="tags" name="tags" class="full-width" required/>
					</div>
	
					<?php
				}
				?>
	
				<div class="new-member-form-element" class="full-width">
					<label for="bio" class="new-member-form-label">
						Bio
					</label>
					<textarea id="bio" name="bio" style="width: 100%;" rows="10"></textarea>
				</div>
				<div class="new-member-form-element">
					<label for="photo" class="new-member-form-label">
					Photo (<?php echo floor( get_option( 'wmb_max_headshot_size' ) ); ?>MB maximum file size)
					</label>
					<br>
					<input type="file" id="photo" name="photo" value="" />
				</div>
				<div class="new-member-form-element">
					<input type="submit" value="Submit" />
				</div>
			</form>
		</div><!-- .wp-member-bios -->
		
	</main><!-- #main -->
</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
