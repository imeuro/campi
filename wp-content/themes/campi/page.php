<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package campi
 */

get_header();
?>

	<main id="primary" class="site-main">

		<?php
		while ( have_posts() ) :
			the_post();

			if ($post->post_name == 'mappa' || $post->post_name == 'map' || $post->post_name == 'about' || $post->post_name == 'about-us') { // tutte le pagine che avranno la mappa come bg
 				get_template_part( 'template-parts/content', 'page-map' );
			} else {
				get_template_part( 'template-parts/content', 'page' );
			}

			

		endwhile; // End of the loop.
		?>

	</main><!-- #main -->

<?php
get_footer();
