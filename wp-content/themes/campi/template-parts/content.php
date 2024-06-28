<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package campi
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php
		if ( is_singular() ) {
			$data_opera = '';
			if ( 'post' === get_post_type() ) {

				$autori = wp_get_post_terms( $post->ID, 'autori', array( 'fields' => 'names' ) );
				$sep = (count($autori) > 1) ? ', ' : '';
				foreach ($autori as $key => $autore) {
					echo "<h2>$autore Campi</h2>";
				}

				$data_start = get_field('anno_inizio',$post->ID);
				$data_end = get_field('anno_fine',$post->ID);
				$sep1 = ($data_start || $data_end) ? ', ' : '';
				$sep2 = ($data_start && $data_end) ? '-' : '';
				$data_opera = $sep1.$data_start.$sep2.$data_end;

			}
			the_title( '<h1 class="entry-title"><em>', '</em>'.$data_opera.'</h1>' );

			$materiale = get_field('materiale_dimensioni',$post->ID);
			echo "<span>$materiale</span>";		


		} else {
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		}
		?>

	</header><!-- .entry-header -->

	<?php campi_post_thumbnail(); ?>

	<div class="entry-content">
		<?php
		the_content(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'campi' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				wp_kses_post( get_the_title() )
			)
		);

		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'campi' ),
				'after'  => '</div>',
			)
		);
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php campi_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
