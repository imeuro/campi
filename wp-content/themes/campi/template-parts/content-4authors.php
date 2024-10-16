<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package campi
 */

$query_auth = get_terms( array(
	'taxonomy' => 'autori',
	'hide_empty' => false
) );

if ( ! empty( $query_auth ) ) {
?>
<ul id="authors-archive" class="margin-top-header flex">

	<?php foreach( $query_auth as $auth ) : ?>
		<li class="author-archive related-arts-carousel">
			<h2><a class="campi-autori" href="<?php echo get_term_link( $auth ) ?>"><?php echo $auth->name; ?></a></h2>

			<?php the_opere_carousel( 'autori', $auth->term_id, 'withcaptions', 'vertical' ); ?>

		</li>
	<?php endforeach; ?>

</ul>
<?php
}
?>
