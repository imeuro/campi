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

		<?php if ( have_posts() ) : 
		?>


			<section id="campi-map" data-map="<?php echo MAPBOX_KEY ?>"></section>


			<section id="archive-content" data-id="<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="entry-header">
					<?php 
						the_title( '<h1 class="entry-title">', '</h1>' );
						the_archive_description( '<div class="archive-description">', '</div>' );
					 ?>
				</header><!-- .entry-header -->


				<div id="location-list">
					<?php
					//pare che manchi l'archivio di tutti i termini di una taxonomy O_o
					// una cosa del genere....
					$terms = get_terms(array( 
						'taxonomy' => 'luoghi',
						'parent'   => 0
					));

					echo '<ul>';
					for ($i=0; $i < count($terms) ; $i++) { 
						$location = get_field('map_location', 'luoghi_'.$terms[$i]->term_id);?>
						<li class="location-item" data-location="<?php echo $terms[$i]->term_id; ?>" data-lat="<?php echo $location['lat']; ?>" data-lng="<?php echo $location['lng']; ?>">
							<input type="checkbox" name="panel" id="panel-<?php echo $terms[$i]->term_id; ?>" class="hidden">
							<label for="panel-<?php echo $terms[$i]->term_id; ?>" class="location-header">
								<h2 class="location-name"><?php echo $terms[$i]->name; ?></h2>
								<p class="location-data"><?php echo $location['city']; ?></p>
							</label>
							<div class="location-expand">
								<p>
									<?php echo $location['street_name']; ?>, <?php echo $location['street_number']; ?> - <?php echo $location['city']; ?> (<?php echo $location['state_short']; ?>) <?php echo $location['country']; ?>
								</p>
								<?php 
									// foreach post in location...
									$args = array(
									'post_type' => 'post',
									'tax_query' => array(
										array(
										'taxonomy' => 'luoghi',
										'field' => 'term_id',
										'terms' => $terms[$i]->term_id
										))
									);
									$carousel_query = new WP_Query( $args );
									if($carousel_query->have_posts() ) {
									$count = 0;
									?>

										<div id="related-arts-carousel-<?php echo $terms[$i]->term_id; ?>" class="related-arts-carousel">
											<ul id="related-<?php echo $terms[$i]->term_id; ?>" class="CSScarousel flex" data-passo="1">

											<?php
												while ( $carousel_query->have_posts() ) {
													$carousel_query->the_post();
													$count++;
													if ( has_post_thumbnail() ) {
												        echo '<li class="CSScarouselItem">'.get_the_post_thumbnail( $post->ID, $size = 'thumbnail', $attr = '' ) .'</li>';	
												    } else {
														// No post thumbnail, try attachments instead.
														$images = get_posts(
															array(
																'post_type'      => 'attachment',
																'post_mime_type' => 'image',
																'post_parent'    => $post->ID,
																'posts_per_page' => 1, /* Save memory, only need one */
															)
														);

														if ( $images ) {
														    $image = wp_get_attachment_image_src( $images[0]->ID, 'thumbnail' );
														    echo '<li class="CSScarouselItem"><a href="'.get_permalink($post->ID).'"><img src="' . $image[0].'" width="' . $image[1].'" height="' . $image[2].'" /></a></li>';
														}
												    }

													for ($k=$count; $k < 10; $k++) { // TEMP FILLER!!
														$rnd = random_int(10, 25);
														echo '<li class="CSScarouselItem"><img src="https://picsum.photos/80/112?random='.$rnd.'" /></li>';
													}
												}
											?>

											</ul>
											<a class="CSScarouselPrev CSScarouselControl CSScarouselDisabled" data-target="#related-<?php echo $terms[$i]->term_id; ?>">
												<svg width="21" height="18">
													<use xlink:href="<?php echo get_template_directory_uri() . "/assets/campi-sprite.svg?cb=69632.88982883877#ico-triangle-carousel"?>"></use>
												</svg>
											</a>
											<a class="CSScarouselNext CSScarouselControl" data-target="#related-<?php echo $terms[$i]->term_id; ?>">
												<svg width="21" height="18">
													<use xlink:href="<?php echo get_template_directory_uri() . "/assets/campi-sprite.svg?cb=69632.88982883877#ico-triangle-carousel"?>"></use>
												</svg>

											</a>
										</div>

									<?php
								}
								wp_reset_postdata();
								?>


							</div>
						</li>
					<?php 
					// echo '<pre>';
					// print_r($term);
					// print_r($location);
					// echo '</pre>';
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
