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

function create_image_carousel( $img_ids ) {

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