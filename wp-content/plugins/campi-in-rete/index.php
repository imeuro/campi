<?php
/**
 * @package Campi_in_rete
 * @version 0.0.1
 */
/*
Plugin Name: Campi in Rete
Description: First setup and utilities for Campi in Rete project.
Author: Mauro Fioravanzi
Version: 0.0.1
Author URI: https://meuro.dev/
*/

if( ! defined( 'ABSPATH') ) { exit; }

include('inc/custom-post-types-fields-taxonomies.php');
include('inc/_api.php');



function get_post_block_galleries_images( $post ) {
	$post_blocks = parse_blocks( $post->post_content );

	$post_blocks_array = $post_blocks[0]['innerBlocks'];

	foreach ( $post_blocks_array as $post_block ) {
		$id_array[] = $post_block['attrs']['id'];
	}
	return $id_array;
}

function the_HP_carousel( $img_ids ) {
	$out = '<div class="imageslider">';
	$i = 0;
	foreach($img_ids as $imgid) {
		$i++;
		$imgData = wp_get_attachment_image_src( $imgid, 'full', '', array( 'class' => 'img-responsive' ) );
		//print_r($imgData);
		$out .= '<div class="box" data-index="'.$i.'"><img src="'.$imgData[0].'" width="'.$imgData[1].'" height="'.$imgData[2].'" loading="lazy" /></div>';
	}
	$out .= '</div>';
	echo $out;
}



function the_opere_carousel( $term_id ) {
	// foreach post in location...
		$args = array(
		'post_type' => 'post',
		'tax_query' => array(
			array(
			'taxonomy' => 'luoghi',
			'field' => 'term_id',
			'terms' => $term_id
			))
		);
		$carousel_query = new WP_Query( $args );
		if($carousel_query->have_posts() ) {
		$count = 0;
		?>

			
		<ul id="related-<?php echo $term_id; ?>" class="CSScarousel flex" data-passo="1">
		<?php
			while ( $carousel_query->have_posts() ) {
				// ...get an image and create a slide
				global $post;
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
							'posts_per_page' => 1, // Save memory, only need one
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
		<a class="CSScarouselPrev CSScarouselControl CSScarouselDisabled" data-target="#related-<?php echo $term_id; ?>">
			<svg width="21" height="18">
				<use xlink:href="<?php echo get_template_directory_uri() . "/assets/campi-sprite.svg?cb=69632.88982883877#ico-triangle-carousel"?>"></use>
			</svg>
		</a>
		<a class="CSScarouselNext CSScarouselControl" data-target="#related-<?php echo $term_id; ?>">
			<svg width="21" height="18">
				<use xlink:href="<?php echo get_template_directory_uri() . "/assets/campi-sprite.svg?cb=69632.88982883877#ico-triangle-carousel"?>"></use>
			</svg>
		</a>

		<?php
		wp_reset_postdata();
	}

}