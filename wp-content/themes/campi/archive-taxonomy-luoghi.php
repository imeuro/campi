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


			<section id="campi-map" data-map="<?php echo MAPBOX_KEY ?>"></section>


			<section id="page-content" data-id="<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="entry-header">
					<?php 
						the_title( '<h1 class="entry-title">', '</h1>' );
						the_archive_description( '<div class="archive-description">', '</div>' );
					 ?>
				</header><!-- .entry-header -->


				<div id="tax-list">
					<?php
					//pare che manchi l'archivio di tutti i termini di una taxonomy O_o
					// una cosa del genere....
					$terms = get_terms(array( 
						'taxonomy' => 'luoghi',
						'parent'   => 0
					));
					
					echo '<ul>';
					foreach ($terms as $term) {
						echo '<li><a href="'.get_term_link($term).'">'.$term->name.'</a></li>';
					}
					echo '</ul>';
					?>
				</div>

			</section>


			

			<?php
		else :

			get_template_part( 'template-parts/content', 'none' );

		endif;
		?>

	</main><!-- #main -->

<?php
get_footer();
