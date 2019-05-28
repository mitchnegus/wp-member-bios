<?php
/**
 * The template file for a member page.
 *
 * This template file type is used for displaying member pages. Each member
 * page has an image of the user on the left and the member's information on
 * the right. This information includes the member's name, subject area,
 * expected graduation date, bio, and interess.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 */

get_header();
?>

<div id="primary" class="content-area">
	<main id="main" class="site-main">
	
		<div id="profile-block">
			<div id="member-image">
				
				<?php
				// Collect the post information
				$post = get_post();
				$positions = get_the_terms($post->ID, 'positions');
				$custom = get_post_custom();
				$first_subheader = $custom['first_subheader'][0];
				$second_subheader = $custom['second_subheader'][0];
				$delimiter = get_option( 'wmb_subheader_delimiter' ) . ' ';
				$info = $first_subheader . $delimiter . $second_subheader;
				$tags = $custom['tags'][0];
	
				// Display the thumbnail
				if (has_post_thumbnail()) {
					the_post_thumbnail();
				} else {
					?>

						<img src="<?php echo WMB_URL . 'img/headshot_template.png';  ?>" style="width: 200px; height: 200px;" />

					<?php
				}
				?>

			</div>
			<div id="member-info">
				<div id="member-header">
					<?php the_title('<h1>', '</h1>'); ?>
					<h2 id="member-position"><?php echo esc_html( $positions[0]->name ); ?></h2>
				</div>
				<h3>
					<?php echo esc_html( $info ); ?>
				</h3>

				<?php
				while (have_posts()) :
				 	the_post();
					the_content();
				endwhile;
				?>

				<p><b>Interests:</b> <?php echo esc_html( $tags ); ?></p>
			</div>
		</div>
	
	</main><!-- #main -->
</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
