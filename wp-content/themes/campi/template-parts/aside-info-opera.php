
<ul class="aside-location flex">
	<li class="aside-location-ico">
		<svg width="15" height="24">
			<use xlink:href="<?php echo get_template_directory_uri() . "/assets/campi-sprite.svg?cb=69632.88982883877#ico-arrow-dot"?>"></use>
		</svg>
	</li>		

	<?php
		$luoghi = wp_get_post_terms( $post->ID, 'luoghi' );
		// echo '<pre>';
		// print_r( $luoghi );
		// echo '</pre>------';
		foreach ($luoghi as $key => $luogo) {
			echo '<li class="aside-location-name level_'.$key.'">';
			if ($key == 0) {
				echo '<a href="'. home_url('/mappa/#'.$luogo->term_id).'">'.$luogo->name.'</a>';
			} else {
				echo $luogo->name;
			}
			echo '</li>';
		}
	?>
</ul>


<ul class="aside-navi flex">
	<?php
		$autori = wp_get_post_terms( $post->ID, 'autori', array( 'fields' => 'all' ) );
		$sep = (count($autori) > 1) ? ', ' : '';
		foreach ($autori as $key => $autore) {
			echo '<li class="aside-link"><a href="'.get_term_link($autore->term_id).'">'.$autore->name.' Campi</a></li>';
		}

		$commissione = get_field('commissione',$post->ID);
		$restauro = get_field('restauro',$post->ID);
		$bibliografia_specifica = get_field('bibliografia_specifica',$post->ID);

		if($commissione) {
			echo '<li class="aside-link aside-anchor" data-section="commissione"><a href="#commissione">Commissione</a></li>';
		}
		if($restauro) {
			echo '<li class="aside-link aside-anchor" data-section="restauro"><a href="#restauro">Restauro</a></li>';
		}
		if($bibliografia_specifica) {
			echo '<li class="aside-link aside-anchor" data-section="bibliografia_specifica"><a href="#bibliografia_specifica">Bibliografia specifica</a></li>';
		}
	?>
</ul>