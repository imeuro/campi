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
				// echo '<pre>';
				// print_r($dati_principali);
				// echo '</pre>';

				if ($dati_principali) {

					$sep1 = ($dati_principali['anno_inizio'] || $dati_principali['anno_fine']) ? ', ' : '';
					$sep2 = ($dati_principali['anno_inizio'] && $dati_principali['anno_fine']) ? '-' : '';
					$data_opera = $sep1.$dati_principali['anno_inizio'].$sep2.$dati_principali['anno_fine'];
				
					the_title( '<h1 class="entry-title"><em>', '</em>'.$data_opera.'</h1>' );
					echo "<span>".$dati_principali['materiale_dimensioni']."</span>";

				}	
			}


		} else {
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		}
		?>

	</header><!-- .entry-header -->

	<div class="entry-content">

		<div id="related-arts-carousel">
			<h4 class="section-heading smol">Opere collegate</h4>
			<ul id="related" class="CSScarousel flex" data-passo="1">
				<?php // temp!!
					$rnd = random_int(10, 25);
					for ($i=0; $i < $rnd; $i++) { 
						echo '<li class="CSScarouselItem"><img src="https://picsum.photos/80/112?random='.$i.'" /></li>';
					}
				?>
			</ul>
			<a class="CSScarouselPrev CSScarouselControl CSScarouselDisabled" data-target="#related">
				<svg width="21" height="18">
					<use xlink:href="<?php echo get_template_directory_uri() . "/assets/campi-sprite.svg?cb=69632.88982883877#ico-triangle-carousel"?>"></use>
				</svg>
			</a>
			<a class="CSScarouselNext CSScarouselControl" data-target="#related">
				<svg width="21" height="18">
					<use xlink:href="<?php echo get_template_directory_uri() . "/assets/campi-sprite.svg?cb=69632.88982883877#ico-triangle-carousel"?>"></use>
				</svg>

			</a>
		</div>



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
