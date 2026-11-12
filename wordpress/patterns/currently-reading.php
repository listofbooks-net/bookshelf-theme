<?php
/**
 * Title: Currently reading
 * Slug: readerly/currently-reading
 * Categories: readerly-book-blog, readerly-reading-log
 * Keywords: reading, progress, now, books, log
 * Viewport Width: 1280
 * Description: A band showing the books you are reading now, with a progress line for each. Edit the covers, titles and pages by hand.
 *
 * @package Readerly
 */

$readerly_books = array(
	array(
		'cover'    => 'moth-season',
		'title'    => __( 'Moth Season', 'readerly' ),
		'author'   => __( 'Pell Ordway', 'readerly' ),
		'status'   => 'reading',
		'progress' => array( 142, 368 ),
	),
	array(
		'cover'    => 'ninefold-bridge',
		'title'    => __( 'Ninefold Bridge', 'readerly' ),
		'author'   => __( 'Ada Merriweather', 'readerly' ),
		'status'   => 'reading',
		'progress' => array( 210, 290 ),
	),
);
?>
<!-- wp:group {"tagName":"section","align":"full","className":"readerly-section readerly-band","layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull readerly-section readerly-band"><!-- wp:group {"align":"wide","className":"readerly-section-head","layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
<div class="wp-block-group alignwide readerly-section-head"><!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e( 'Currently reading', 'readerly' ); ?></h2>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|40","left":"var:preset|spacing|50"}}},"layout":{"type":"grid","minimumColumnWidth":"19rem"}} -->
<div class="wp-block-group alignwide">
<?php
foreach ( $readerly_books as $readerly_book ) {
	echo readerly_compact_card( $readerly_book ) . "\n\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- block markup built from escaped parts.
}
?>
</div>
<!-- /wp:group --></section>
<!-- /wp:group -->
