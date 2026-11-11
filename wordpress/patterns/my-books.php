<?php
/**
 * Title: My books grid
 * Slug: bookshelf/my-books
 * Categories: bookshelf-author-site
 * Keywords: books, bibliography, novels, grid, buy
 * Viewport Width: 1280
 * Description: Your books four across. Each card keeps its Buy and Learn more buttons at the foot, aligned across the row.
 *
 * @package Bookshelf
 */

$bookshelf_books = array(
	array(
		'cover'   => 'bell-foundry',
		/* translators: book card eyebrow: year and kind of book. */
		'eyebrow' => __( '2026 · Novel', 'bookshelf' ),
		'title'   => __( 'The Bell Foundry', 'bookshelf' ),
		'excerpt' => __( 'A young caster is given the last commission of a closing foundry: a bell for a wedding no one in town wants.', 'bookshelf' ),
	),
	array(
		'cover'   => 'map-of-quiet-places',
		'eyebrow' => __( '2023 · Novel', 'bookshelf' ),
		'title'   => __( 'A Map of Quiet Places', 'bookshelf' ),
		'excerpt' => __( 'A sound recordist sets out to find the quietest place left in England, and finds her estranged father there instead.', 'bookshelf' ),
	),
	array(
		'cover'   => 'saltmarsh-hymns',
		'eyebrow' => __( '2021 · Novel', 'bookshelf' ),
		'title'   => __( 'Saltmarsh Hymns', 'bookshelf' ),
		'excerpt' => __( 'A choir of fishermen’s widows, a flooded chapel and one long, disastrous rehearsal season.', 'bookshelf' ),
	),
	array(
		'cover'   => 'clockmakers-apprentice',
		'eyebrow' => __( '2019 · Debut novel', 'bookshelf' ),
		'title'   => __( 'The Clockmaker’s Apprentice', 'bookshelf' ),
		'excerpt' => __( 'A girl, a church clock and the summer it stopped.', 'bookshelf' ),
	),
);
?>
<!-- wp:group {"tagName":"section","align":"full","anchor":"books","className":"bookshelf-section","layout":{"type":"constrained"}} -->
<section id="books" class="wp-block-group alignfull bookshelf-section"><!-- wp:group {"align":"wide","className":"bookshelf-section-head","layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
<div class="wp-block-group alignwide bookshelf-section-head"><!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e( 'My books', 'bookshelf' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php esc_html_e( 'Four novels, all standalone', 'bookshelf' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|60 var:preset|spacing|50"}},"layout":{"type":"grid","columnCount":4,"minimumColumnWidth":"9rem"}} -->
<div class="wp-block-group alignwide">
<?php
foreach ( $bookshelf_books as $bookshelf_book ) {
	echo bookshelf_author_card( $bookshelf_book ) . "\n\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- block markup built from escaped parts.
}
?>
</div>
<!-- /wp:group --></section>
<!-- /wp:group -->
