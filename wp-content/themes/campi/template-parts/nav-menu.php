<ul class="nav-menu">
	<li class="menu-first flex">
		<a class="menu-about" href="<?php echo home_url( '/about/' ); ?>" title="ABOUT">ABOUT</a>
			<?php 
			$langs = pll_the_languages( 
				array( 
					// 'display_names_as' 	=> 'slug',
					// 'hide_current'		=> 1,
					// 'echo' 				=> 0,
					'raw' => 1
				)
			);
			// echo "<pre>";
			// print_r( $langs );
			// echo "</pre>";
			$otherlang = (pll_current_language('slug') == 'it') ? 'en' : 'it';
			?>
		<a class="menu-lang" data-text="<?php echo strtoupper($langs[$otherlang]['slug']); ?>" href="<?php echo $langs[$otherlang]['url']; ?>" title="<?php echo strtoupper($langs[$otherlang]['name']); ?>">
			<svg width="30" height="30">
             <use xlink:href="<?php echo get_template_directory_uri() . '/assets/campi-sprite.svg#ico-arrow-right'; ?>"></use>
          </svg>
		</a>
	</li>
	<li><a href="<?php echo home_url( '/about/' ); ?>" title="LUOGHI">LUOGHI</a></li>
	<li><a href="<?php echo home_url( '/opere/' ); ?>" title="OPERE">OPERE</a></li>
	<li><a href="<?php echo home_url( '/biografie/' ); ?>" title="BIOGRAFIE">BIOGRAFIE</a></li>
	<li><a href="<?php echo home_url( '/cronologia/' ); ?>" title="CRONOLOGIA">CRONOLOGIA</a></li>
	<li><a href="<?php echo home_url( '/bibliografia/' ); ?>" title="BIBLIOGRAFIA">BIBLIOGRAFIA</a></li>
</ul>
<a href="<?php echo home_url( '/mappa/' ); ?>" title="MAPPA" class="nav-menu-map">
	<svg width="50" height="50">
		<use xlink:href="<?php echo get_template_directory_uri() . '/assets/campi-sprite.svg?#ico-map'; ?>"></use>
	</svg>
	MAPPA
</a>