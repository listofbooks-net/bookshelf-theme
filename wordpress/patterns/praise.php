<?php
/**
 * Title: Praise quotes
 * Slug: bookshelf/praise
 * Categories: bookshelf-author-site, testimonials
 * Keywords: praise, quotes, reviews, blurbs, testimonials
 * Viewport Width: 1280
 * Description: Three short quotes about your books, each with its source.
 *
 * @package Bookshelf
 */

$bookshelf_quotes = array(
	array( __( '“A novel that rings long after you close it. Thorne writes about craft with the authority of someone who has done it.”', 'bookshelf' ), __( 'The Northern Review', 'bookshelf' ), __( 'on The Bell Foundry', 'bookshelf' ) ),
	array( __( '“Funny, exact and unexpectedly moving. I wanted to move to her fen and never leave.”', 'bookshelf' ), __( 'Mira Hollis', 'bookshelf' ), __( 'author of The Salt Orchard', 'bookshelf' ) ),
	array( __( '“Thorne is the quiet master of the small-town novel. Every sentence has been weighed.”', 'bookshelf' ), __( 'Lamplight Quarterly', 'bookshelf' ), __( 'on A Map of Quiet Places', 'bookshelf' ) ),
);
?>
<!-- wp:group {"tagName":"section","align":"full","className":"bookshelf-section bookshelf-praise","layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull bookshelf-section bookshelf-praise"><!-- wp:group {"align":"wide","className":"bookshelf-section-head","layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
<div class="wp-block-group alignwide bookshelf-section-head"><!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e( 'Kind words', 'bookshelf' ); ?></h2>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|50"}},"layout":{"type":"grid","columnCount":3,"minimumColumnWidth":"16rem"}} -->
<div class="wp-block-group alignwide">
<?php foreach ( $bookshelf_quotes as $bookshelf_quote ) : ?>
<!-- wp:quote -->
<blockquote class="wp-block-quote"><!-- wp:paragraph -->
<p><?php echo esc_html( $bookshelf_quote[0] ); ?></p>
<!-- /wp:paragraph --><cite><strong><?php echo esc_html( $bookshelf_quote[1] ); ?></strong> <?php echo esc_html( $bookshelf_quote[2] ); ?></cite></blockquote>
<!-- /wp:quote -->

<?php endforeach; ?>
</div>
<!-- /wp:group --></section>
<!-- /wp:group -->
