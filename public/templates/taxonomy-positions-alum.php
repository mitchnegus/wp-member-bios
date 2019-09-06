<?php
/**
 * The template for displaying archive pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 */
namespace Member_Bios;

get_header(); 
$taxonomy = 'positions';

/**
 * Displays tiles for the member in the current post.
 *
 * @since    1.0.0
 * @param    string    $name            The member's name.
 * @param    string    $position        The position held by the member.
 */
function display_member_tile( $name, $position='' ) {
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
				<?php
				if ( $position == '' ) {
					?>

						<div class="mtile-text mtile-text">
							<?php echo esc_html( $name ); ?>
						</div>

					<?php
				} else {
					?>

				<div class="mtile-text mtile-text1">
					<?php echo esc_html( $name ); ?>
				</div>
				<div class="mtile-text mtile-text2">
					<?php echo esc_html( $position ); ?>
				</div>

				<?php
			}
			?>

			</div>
		</div>
	</a>

	<?php
}
?>

<div id="primary" class="content-area">
	<main id="main" class="site-main">

		<div class="clearfix">

			<?php
			if ( have_posts() ) :
				global $post;
				?>

				<header class="entry-header">
					<h1 class="entry-title">Alumni</h1>
				</header><!-- .page-header -->
	
				<?php
				// Start the Loop
				while ( have_posts() ) :
					the_post();
					$post_id = $post->ID;
					$name = get_the_title();
					$args = array( 'fields' => 'names' );
					$positions = wp_get_post_terms( $post_id, $taxonomy, $args );
					$alum_key = array_search( 'Alum', $positions );
					// Display the name, unless the alum has another role
					if ( count( $positions ) <= 1 && $alum_key == 0 ) {
						display_member_tile( $name );
					} else {
						unset( $positions[$alum_key] );
						display_member_tile( $name, array_values( $positions )[0] );
					}
				endwhile;

			endif;
			?>

		</div>

	</main><!-- #main -->
</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
