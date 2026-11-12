<?php
/**
 * Title: Praise quotes
 * Slug: readerly/praise
 * Categories: readerly-author-site, testimonials
 * Keywords: praise, quotes, reviews, blurbs, testimonials
 * Viewport Width: 1280
 * Description: Three short quotes about your books, each with its source.
 *
 * @package Readerly
 */

$readerly_quotes = array(
	array( __( '“A novel that rings long after you close it. Thorne writes about craft with the authority of someone who has done it.”', 'readerly' ), __( 'The Northern Review', 'readerly' ), __( 'on The Bell Foundry', 'readerly' ) ),
	array( __( '“Funny, exact and unexpectedly moving. I wanted to move to her fen and never leave.”', 'readerly' ), __( 'Mira Hollis', 'readerly' ), __( 'author of The Salt Orchard', 'readerly' ) ),
	array( __( '“Thorne is the quiet master of the small-town novel. Every sentence has been weighed.”', 'readerly' ), __( 'Lamplight Quarterly', 'readerly' ), __( 'on A Map of Quiet Places', 'readerly' ) ),
);
?>
<!-- wp:group {"tagName":"section","align":"full","className":"readerly-section readerly-praise","layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull readerly-section readerly-praise"><!-- wp:group {"align":"wide","className":"readerly-section-head","layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
<div class="wp-block-group alignwide readerly-section-head"><!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e( 'Kind words', 'readerly' ); ?></h2>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|50"}},"layout":{"type":"grid","columnCount":3,"minimumColumnWidth":"16rem"}} -->
<div class="wp-block-group alignwide">
<?php foreach ( $readerly_quotes as $readerly_quote ) : ?>
<!-- wp:quote -->
<blockquote class="wp-block-quote"><!-- wp:paragraph -->
<p><?php echo esc_html( $readerly_quote[0] ); ?></p>
<!-- /wp:paragraph --><cite><strong><?php echo esc_html( $readerly_quote[1] ); ?></strong> <?php echo esc_html( $readerly_quote[2] ); ?></cite></blockquote>
<!-- /wp:quote -->

<?php endforeach; ?>
</div>
<!-- /wp:group --></section>
<!-- /wp:group -->
