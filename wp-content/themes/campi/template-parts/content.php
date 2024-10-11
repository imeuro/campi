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
				$dati_principali = get_field('dati_principali',$post->ID);
				get_field('dati_scheda',$post->ID) ? $dati_scheda = get_field('dati_scheda',$post->ID) : $dati_scheda = [];
				// echo '<pre>';
				// print_r($dati_scheda);
				// echo '</pre>';

				if ($dati_principali) {

					$sep1 = ($dati_principali['anno_inizio'] || $dati_principali['anno_fine']) ? ', ' : '';
					$sep2 = ($dati_principali['anno_inizio'] && $dati_principali['anno_fine']) ? '-' : '';
					$data_opera = $sep1.$dati_principali['anno_inizio'].$sep2.$dati_principali['anno_fine'];
				
					the_title( '<h1 class="entry-title"><em>', '</em>'.$data_opera.'</h1>' );
					echo "<span>".$dati_principali['materiale_dimensioni']."</span>";
					echo "<span>".$dati_principali['iscrizione']."</span>";

				}

				// autore scheda
				if ( array_key_exists('autore_scheda_opera', $dati_scheda) && !empty($dati_scheda['autore_scheda_opera']) ) :
					echo '<small class="scheda-author">di '.$dati_scheda['autore_scheda_opera'].'</small>';
				endif; 
			}


		} else {
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		}
		?>



	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php if (( array_key_exists('opere_collegate', $dati_scheda) && $dati_scheda['opere_collegate'] == 'true') ) : ?>
		<div id="related-arts-carousel" class="related-arts-carousel">
			<h4 class="section-heading smol">Opere collegate</h4>
				<?php 
				$scheda_terms = wp_get_post_terms( $post->ID, 'luoghi' );
				//print_r($scheda_terms);
				$scheda_term = $scheda_terms[0]->term_id; // poi ne parliamo del [0]...

				the_opere_carousel( $scheda_term );
				?>
		</div>
		<?php endif; ?>


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

		?>
	</div><!-- .entry-content -->

	<?php
		$commissione = get_field('commissione',$post->ID);
		$restauro = get_field('restauro',$post->ID);
		$bibliografia_specifica = get_field('bibliografia_specifica',$post->ID);
		
		if ($commissione) {
			echo '<section id="commissione" class="entry-content additional">';
			echo '<h2 class="section-heading">Commissione</h2>';
			echo $commissione;
			echo '</section>';
		}
		if ($restauro) {
			echo '<section id="restauro" class="entry-content additional">';
			echo '<h2 class="section-heading">Restauro</h2>';
			echo $restauro;
			echo '</section>';
		}
		if ($bibliografia_specifica) {
			echo '<section id="bibliografia_specifica" class="entry-content additional">';
			echo '<h2 class="section-heading">Bibliografia specifica</h2>';
			echo $bibliografia_specifica;
			echo '</section>';
		}


	?>
	<footer class="entry-footer">
		<?php campi_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
