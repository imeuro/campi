<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package campi
 */

/* Template Name: Home Autori */

get_header();
?>

	<main id="primary" class="site-main">

		<?php get_template_part( 'template-parts/content', '4authors' ); ?>

	</main><!-- #main -->

<?php
get_footer();
