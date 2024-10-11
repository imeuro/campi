<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package campi
 */

/* Template Name: Homepage */

get_header();
?>

	<main id="primary" class="site-main">
		<?php
		if ( have_posts() ) :
		?>
			<header>
				<a title="Comincia la visita" href="<?php echo esc_url( home_url( '/mappa/' ) ); ?>">
					<h1>
						<span class="page-subtitle">GIULIO, ANTONIO, VINCENZO, BERNARDINO</span>
						<span class="page-title"><img src="<?php echo get_template_directory_uri() . '/assets/img/CAMPI.svg'; ?>" width="706" height="170" alt="CAMPI" /></span>
					</h1>
					<h2 class="page-description"><?php echo get_the_excerpt(); ?></h2>
				</a>
			</header>

			<ul class="lang-switch">
			<?php 
				$langs = pll_the_languages( array( 'raw' => 1 ));
				foreach ($langs as $lang) :
				?>
					<li>
						<a class="menu-lang" data-text="<?php echo strtoupper($lang['slug']); ?>" href="<?php echo $lang['url']; ?>" title="<?php echo strtoupper($lang['name']); ?>">
							<svg width="30" height="30">
								<use xlink:href="<?php echo get_template_directory_uri() . '/assets/campi-sprite.svg#ico-arrow-right-wh'; ?>"></use>
							</svg>
						</a>
					</li>
				<?php
				endforeach;
			?>
			</ul>

		<?php
			while ( have_posts() ) :
				the_post();

				//the_content();

				if ( has_block( 'gallery' ) ) {
				   	$image_ids = get_post_block_galleries_images( $post );
				   	//print_r( $image_ids );

				   	the_HP_carousel( $image_ids );

				}

			endwhile;
		endif;
		?>

	</main><!-- #main -->

<?php
get_footer();
