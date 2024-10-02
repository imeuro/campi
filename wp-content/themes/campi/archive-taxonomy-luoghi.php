<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package campi
 */

/* Template Name: Home Luoghi */

get_header();
?>

	<main id="primary" class="site-main">

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<?php
				the_archive_title( '<h1 class="page-title">', '</h1>' );
				the_archive_description( '<div class="archive-description">', '</div>' );
				?>
			</header><!-- .page-header -->

			<section id="map">
			</section>

			<section id="tax-list">
				<?php
				//pare che manchi l'archivio di tutti i termini di una taxonomy O_o
				// una cosa del genere....
				$terms = get_terms('luoghi');
				echo '<ul>';
				foreach ($terms as $term) {
					echo '<li><a href="'.get_term_link($term).'">'.$term->name.'</a></li>';
				}
				echo '</ul>';
				?>
			</section>

			<?php
		else :

			get_template_part( 'template-parts/content', 'none' );

		endif;
		?>

	</main><!-- #main -->

<?php
get_footer();
