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
				$member_subheader = '';
				// Only set the subheader if the admin has included the first subheader
				if ( get_option( 'wmb_first_subheader' ) ) {
					$member_first_subheader = $custom['first_subheader'][0];
					$member_subheader .= $member_first_subheader;
					// Only set the second subheader if the second subheader is set
					if ( get_option( 'wmb_second_subheader' ) ) {
						$member_second_subheader = $custom['second_subheader'][0];
						$delimiter = get_option( 'wmb_subheader_delimiter' ) . ' ';
						// Set the delimiter to an empty string if it is unset
						if ( ! $delimiter ) {
							$delimiter = '';
						}
						$member_subheader .= $delimiter . $member_second_subheader;
					}
				}
				$tags_title = get_option( 'wmb_tags' );
				if ( $tags_title ) {
					$tags_title .= ':';
					$member_tags = $custom['tags'][0];
				} else {
					$member_tags = '';
				}
	
				// Display the thumbnail
				if ( has_post_thumbnail() ) {
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
					<?php the_title( '<h1 id="member-name">', '</h1>' ); ?>
					<h2 id="member-position"><?php echo esc_html( $positions[0]->name ); ?></h2>
				</div>
				<h3>
					<?php echo esc_html( $member_subheader ); ?>
				</h3>

				<?php
				while ( have_posts() ) :
				 	the_post();
					the_content();
				endwhile;

				if ( ! empty( $member_tags ) ) {
					?>

					<p><b><?php echo esc_html( $tags_title ); ?></b>
					<?php echo esc_html( $member_tags ); ?>
					</p>
		
					<?php
				}
				?>

			</div>
		</div>
	
	</main><!-- #main -->
</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
