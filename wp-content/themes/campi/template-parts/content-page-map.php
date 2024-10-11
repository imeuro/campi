<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package campi
 */

if ( is_page('luoghi') || is_page('locations') ) {
	$opened = ' class="opened"';
} else {
	$opened = '';
}

?>


<section id="campi-map" data-map="<?php echo MAPBOX_KEY ?>"></section>

<section id="luoghi-container" data-id="<?php the_ID(); ?>"<?php echo $opened?>>

	<div id="close-container">
		<svg width="50" height="50">
			<use xlink:href="<?php echo get_template_directory_uri() . "/assets/campi-sprite.svg#ico-close"?>"></use>
		</svg>
	</div>

	<header class="entry-header">
		<h1 class="entry-title">LUOGHI</h1>
		<?php 
			the_archive_description( '<div class="archive-description">', '</div>' );
		 ?>
	</header><!-- .entry-header -->


	<div id="location-list">
		<?php
		// pare che manchi l'archivio di tutti i termini di una taxonomy O_o
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
						<?php echo $location['street_name']; ?> 
						<?php if ( array_key_exists('street_number', $location) ) : echo ', '.$location['street_number']; endif; ?> - 
						<?php echo $location['city']; ?> 
						<?php if ( array_key_exists('state_short', $location) ) : echo ' ('.$location['state_short'].') '; endif; ?> 
						<?php echo $location['country']; ?>
					</p>
					

					<div id="related-arts-carousel-<?php echo $terms[$i]->term_id; ?>" class="related-arts-carousel">
						<?php the_opere_carousel( $terms[$i]->term_id ); ?>
					</div>
					<?php 
					/*
						//print_r($terms[$i]);
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
										//print_r($post);
										$count++;
										if ( has_post_thumbnail($post) ) {
									        echo '<li class="CSScarouselItem">'.get_the_post_thumbnail( $post->ID, $size = 'thumbnail', $attr = '' ) .'</li>';	
									    } else {
											// No post thumbnail, try attachments instead.
											$images = get_posts(
												array(
													'post_type'      => 'attachment',
													'post_mime_type' => 'image',
													'post_parent'    => $post->ID,
													'posts_per_page' => 1, 
												)
											);

											if ( $images ) {
												//print_r($images);
											    $image = wp_get_attachment_image_src( $images[0]->ID, 'thumbnail' );
											    echo '<li class="CSScarouselItem"><a href="'.get_permalink($post->ID).'" title="'.$post->post_title.'"><img src="' . $image[0].'" width="' . $image[1].'" height="' . $image[2].'" alt="'.$post->name.'" /></a></li>';
											} else {
												echo '<li class="CSScarouselItem"><a href="'.get_permalink($post->ID).'" title="'.$post->post_title.'"><img src="https://placehold.co/80x112/f8f8f8/0003?text=No image" width="80" height="112" alt="'.$post->name.'" /></a></li>';
											}
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
						wp_reset_postdata();
					}
					*/
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
