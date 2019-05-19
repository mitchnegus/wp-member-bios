<?php
/**
 * Template Name: 
 * The template file for a new member page.
 *
 * This template file type is used for displaying a new member page. The page
 * requests information from a potential member pertaining to their academic
 * information (field, graduation date) and their interests, as well as provides
 * the opportunity for the member to provide a short bio and a profile photo.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 */

global $max_headshot_size;

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">
		
		<h1><?php the_title(); ?></h1>
		<img src="https://sciencepolicy.berkeley.edu/wp-content/uploads/2018/01/headshot_template-e1516922994678.png" style="display: block; width: 25%; margin: 0 auto;" alt="headshot template" />
		<br>
		<p>If you’re an SPG member but not already on the site, please fill out the following form. If you’d like, you may also include:</p>
		<ul>
			<li>a short paragraph (2 sentences max) describing yourself</li>
			<li>a photo (square images work best)</li>
		</ul>

		<form id="new-member-form" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" enctype="multipart/form-data" method="post">
			<input type="hidden" name="action" value="submit_member" />
			<?php wp_nonce_field('add_new_member_nonce', 'new_member_form_nonce'); ?>
			<div style="display: flex; width: 70%;">
				<div class="new-member-form-element" style="flex-grow: 3;">
					<label for="name" class="new-member-form-label">
						Name 
						<span class="new-member-form-label-required">(required)</span>
					</label>
					<input type="text" id="name" name="name" class="full-width" required/>
				</div>
				<div class="new-member-form-element" style="flex-grow: 2;" >
					<label for="email" class="new-member-form-label">
						<?php echo esc_html(get_option('organization_name')); ?> email
						<span class="new-member-form-label-required">(required)</span>
					</label>
					<input type="text" id="email" name="email" class="full-width" required/>
				</div>
			</div>
			<div class="new-member-form-element" style="width: 70%;">
				<label for="subject" class="new-member-form-label">
					Field of study
					<span class="new-member-form-label-required">(required)</span>
				</label>
				<input type="text" id="subject" name="subject" class="full-width" required/>
			</div>
			<div class="new-member-form-element" style="width: 70%;">
				<label for="grad_date" class="new-member-form-label">
					Expected graduation date (e.g. Spring 2020)
					<span class="new-member-form-label-required">(required)</span>
				</label>
				<input type="text" id="grad_date" name="grad_date" class="full-width" required/>
			</div>
			<div class="new-member-form-element" class="full-width">
				<label for="interests" class="new-member-form-label">
					Policy interests
					<span class="new-member-form-label-required">(required)</span>
				</label>
				<input type="text" id="interests" name="interests" class="full-width" required/>
			</div>
			<div class="new-member-form-element" class="full-width">
				<label for="bio" class="new-member-form-label">
					Bio
				</label>
				<textarea id="bio" name="bio" style="width: 100%;" rows="10"></textarea>
			</div>
			<div class="new-member-form-element">
				<label for="photo" class="new-member-form-label">
				Photo (<?php echo floor($max_headshot_size/1e6); ?>MB maximum file size)
				</label>
				<br>
				<input type="file" id="photo" name="photo" value="" />
			</div>
			<div class="new-member-form-element">
				<input type="submit" value="Submit" />
			</div>
		</form>
		
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
