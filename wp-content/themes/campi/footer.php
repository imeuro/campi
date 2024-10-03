<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package campi
 */

?>

	<?php if (!is_front_page() && !is_single()) : 
		$query_auth = get_terms( array(
			'taxonomy' => 'autori',
			'hide_empty' => false
		) );
	?>
	<footer id="footer" class="site-footer flex">
		<?php if ( ! empty( $query_auth ) ) { ?>
			<h2 class="auth-menu CSScarousel">
			<?php foreach( $query_auth as $auth ) : 
				//print_r($auth);
				echo '<a class="campi-autori CSScarouselItem" href="'.get_term_link( $auth ).'">'.$auth->name.'</a>';
			endforeach; ?>
			</h2>
		<?php } ?>

		<div class="site-tools">

			<span class="search-box">
				<svg width="20" height="30">
					<use xlink:href="<?php echo get_template_directory_uri() . '/assets/campi-sprite.svg#ico-search'; ?>"></use>
				</svg>
				<?php get_search_form() ?>
			</span>

		</div><!-- .site-info -->
	</footer><!-- #colophon -->
	<?php endif; ?>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
