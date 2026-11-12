<?php
/**
 * Title: Book details panel
 * Slug: readerly/book-details
 * Categories: readerly-book-blog
 * Keywords: book, details, cover, rating, review, panel
 * Description: Cover, title, author, series, pages, rating and reading status for the book under review.
 *
 * @package Readerly
 */

?>
<!-- wp:group {"tagName":"aside","className":"readerly-book-panel is-style-card","layout":{"type":"default"}} -->
<aside class="wp-block-group readerly-book-panel is-style-card"><?php echo readerly_cover_block( 'salt-orchard', __( 'Cover of The Salt Orchard by Mira Hollis', 'readerly' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside the helper. ?>

<!-- wp:group {"style":{"spacing":{"blockGap":"0.25rem"}},"layout":{"type":"default"}} -->
<div class="wp-block-group"><!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e( 'The Salt Orchard', 'readerly' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"readerly-book-author"} -->
<p class="readerly-book-author"><?php esc_html_e( 'by Mira Hollis', 'readerly' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:group {"className":"readerly-details","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|20","left":"var:preset|spacing|30"},"margin":{"top":"var:preset|spacing|30"}}},"fontSize":"small","layout":{"type":"grid","columnCount":2,"minimumColumnWidth":null}} -->
<div class="wp-block-group readerly-details has-small-font-size" style="margin-top:var(--wp--preset--spacing--30)"><!-- wp:paragraph -->
<p><?php esc_html_e( 'Series', 'readerly' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><?php esc_html_e( 'The Tidewater Books, Book 2 of 3', 'readerly' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><?php esc_html_e( 'Pages', 'readerly' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><?php echo esc_html( number_format_i18n( 384 ) ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><?php esc_html_e( 'Published', 'readerly' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><?php esc_html_e( '2026, Harrow & Lane', 'readerly' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><?php esc_html_e( 'My rating', 'readerly' ); ?></p>
<!-- /wp:paragraph -->

<?php echo readerly_rating_block( 4.5 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside the helper. ?>

<!-- wp:paragraph -->
<p><?php esc_html_e( 'Status', 'readerly' ); ?></p>
<!-- /wp:paragraph -->

<?php echo readerly_status_block( 'read' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside the helper. ?></div>
<!-- /wp:group --></div>
<!-- /wp:group --></aside>
<!-- /wp:group -->
