<?php
/**
 * Title: Currently reading
 * Slug: bookshelf/currently-reading
 * Categories: bookshelf-book-blog, bookshelf-reading-log
 * Keywords: reading, progress, now, books, log
 * Viewport Width: 1280
 * Description: A band showing the books you are reading now, with a progress line for each. Edit the covers, titles and pages by hand.
 *
 * @package Bookshelf
 */

$bookshelf_books = array(
	array(
		'cover'    => 'moth-season',
		'title'    => __( 'Moth Season', 'bookshelf' ),
		'author'   => __( 'Pell Ordway', 'bookshelf' ),
		'status'   => 'reading',
		'progress' => array( 142, 368 ),
	),
	array(
		'cover'    => 'ninefold-bridge',
		'title'    => __( 'Ninefold Bridge', 'bookshelf' ),
		'author'   => __( 'Ada Merriweather', 'bookshelf' ),
		'status'   => 'reading',
		'progress' => array( 210, 290 ),
	),
);
?>
<!-- wp:group {"tagName":"section","align":"full","className":"bookshelf-section bookshelf-band","layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull bookshelf-section bookshelf-band"><!-- wp:group {"align":"wide","className":"bookshelf-section-head","layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
<div class="wp-block-group alignwide bookshelf-section-head"><!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e( 'Currently reading', 'bookshelf' ); ?></h2>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|40 var:preset|spacing|50"}},"layout":{"type":"grid","minimumColumnWidth":"19rem"}} -->
<div class="wp-block-group alignwide">
<?php
foreach ( $bookshelf_books as $bookshelf_book ) {
	echo bookshelf_compact_card( $bookshelf_book ) . "\n\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- block markup built from escaped parts.
}
?>
</div>
<!-- /wp:group --></section>
<!-- /wp:group -->
