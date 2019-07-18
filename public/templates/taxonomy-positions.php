<?php
/**
 * The template for displaying archive pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 */

get_header(); 
?>

<div id="primary" class="content-area">
	<main id="main" class="site-main">

		<div class="clearfix">

			<?php
			if ( have_posts() ) :
				global $post
				?>

				<header class="entry-header">
					<h1 class="entry-title">

						<?php
						/* Set title based on page type */
						if ( is_tax( 'positions', 'member' ) ) {
							echo esc_html( 'Members' );
						} elseif ( is_tax( 'positions', 'alumni' ) ) {
							echo esc_html( 'Alumni' );
						}
						?> 

					</h1>
				</header><!-- .page-header -->
	
				<?php
				/* Start the Loop */
				while ( have_posts() ) :
					the_post();
					$name = get_the_title();
					$slug = get_post_field( 'post_name', get_post() );
					$positions = wp_get_post_terms( $post->ID, 'positions' );
					$position = $positions[0]->name
					?>

					<a href="<?php echo esc_url( get_page_link() ) ?>">
						<div class="mtile-container">

							<?php
		 					if ( has_post_thumbnail() ) {
								the_post_thumbnail();
							} else {
								?>

								<img src="<?php echo WMB_URL . 'img/headshot_template.png';  ?>" style="width: 200px; height: 200px;" />

								<?php
							}
							?>

							<div class="mtile-overlay">
								<div class="mtile-text mtile-text1">
									<?php echo esc_html( $name ); ?>
								</div>
								<div class="mtile-text mtile-text2">
									<?php echo esc_html( $position ); ?>
								</div>
							</div>
						</div>
					</a>

				<?php
				endwhile;

			endif;
			?>

		</div>

		<?php
		if ( is_tax( 'positions', 'member' ) ) {
			?>

			<a href="<?php echo home_url() . '/positions/alumni' ?>">
				<div class="alumni-button">
					<p class="alumni-button-text">SPG Alumni</p>
				</div>
			</a>

			<div class="contact-block">
				Don't see yourself? New to the group? 
				<a href="<?php echo home_url() . '/new-member' ?>">
					Click here and send us your info to include on the site!
				</a>
			</div>
			
			<?php
	}
	?>

	</main><!-- #main -->
</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
