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
	<?php
		while ( have_posts() ) :
			the_post();
	?>
		<aside id="post-navigation">

			<?php get_template_part( 'template-parts/aside', 'info-opera' ); ?>

		</aside>

		<main id="primary" class="site-main">

			<?php get_template_part( 'template-parts/content', get_post_type() ); ?>

		</main><!-- #main -->

	<?php
		endwhile; // End of the loop.
	?>
	</div>

<?php
get_footer();
