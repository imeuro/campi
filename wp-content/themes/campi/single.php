<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package campi
 */

get_header();
?>
	<div class="container flex">
		<aside id="post-navigation">
			<?php
			$luogo = wp_get_post_terms( $post->ID, 'luoghi', array( 'fields' => 'names' ) );
			print_r( $luogo ); 
			?>

		</aside>

		<main id="primary" class="site-main">

			<?php
			while ( have_posts() ) :
				the_post();

				get_template_part( 'template-parts/content', get_post_type() );

				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;

			endwhile; // End of the loop.
			?>

		</main><!-- #main -->
	</div>

<?php
get_footer();
