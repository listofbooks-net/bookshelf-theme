<?php
/**
 * Title: My books grid
 * Slug: readerly/my-books
 * Categories: readerly-author-site
 * Keywords: books, bibliography, novels, grid, buy
 * Viewport Width: 1280
 * Description: Your books four across. Each card keeps its Buy and Learn more buttons at the foot, aligned across the row.
 *
 * @package Readerly
 */

$readerly_books = array(
	array(
		'cover'   => 'bell-foundry',
		/* translators: book card eyebrow: year and kind of book. */
		'eyebrow' => __( '2026 · Novel', 'readerly' ),
		'title'   => __( 'The Bell Foundry', 'readerly' ),
		'excerpt' => __( 'A young caster is given the last commission of a closing foundry: a bell for a wedding no one in town wants.', 'readerly' ),
	),
	array(
		'cover'   => 'map-of-quiet-places',
		'eyebrow' => __( '2023 · Novel', 'readerly' ),
		'title'   => __( 'A Map of Quiet Places', 'readerly' ),
		'excerpt' => __( 'A sound recordist sets out to find the quietest place left in England, and finds her estranged father there instead.', 'readerly' ),
	),
	array(
		'cover'   => 'saltmarsh-hymns',
		'eyebrow' => __( '2021 · Novel', 'readerly' ),
		'title'   => __( 'Saltmarsh Hymns', 'readerly' ),
		'excerpt' => __( 'A choir of fishermen’s widows, a flooded chapel and one long, disastrous rehearsal season.', 'readerly' ),
	),
	array(
		'cover'   => 'clockmakers-apprentice',
		'eyebrow' => __( '2019 · Debut novel', 'readerly' ),
		'title'   => __( 'The Clockmaker’s Apprentice', 'readerly' ),
		'excerpt' => __( 'A girl, a church clock and the summer it stopped.', 'readerly' ),
	),
);
?>
<!-- wp:group {"tagName":"section","align":"full","anchor":"books","className":"readerly-section","layout":{"type":"constrained"}} -->
<section id="books" class="wp-block-group alignfull readerly-section"><!-- wp:group {"align":"wide","className":"readerly-section-head","layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
<div class="wp-block-group alignwide readerly-section-head"><!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e( 'My books', 'readerly' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php esc_html_e( 'Four novels, all standalone', 'readerly' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"align":"wide","className":"readerly-book-grid","style":{"spacing":{"blockGap":"var:preset|spacing|50"}},"layout":{"type":"grid","columnCount":4,"minimumColumnWidth":"9rem"}} -->
<div class="wp-block-group alignwide readerly-book-grid">
<?php
foreach ( $readerly_books as $readerly_book ) {
	echo readerly_author_card( $readerly_book ) . "\n\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- block markup built from escaped parts.
}
?>
</div>
<!-- /wp:group --></section>
<!-- /wp:group -->
