<?php
/**
 * Title: Book details panel
 * Slug: bookshelf/book-details
 * Categories: bookshelf-book-blog
 * Keywords: book, details, cover, rating, review, panel
 * Description: Cover, title, author, series, pages, rating and reading status for the book under review.
 *
 * @package Bookshelf
 */

?>
<!-- wp:group {"tagName":"aside","className":"bookshelf-book-panel is-style-card","layout":{"type":"default"}} -->
<aside class="wp-block-group bookshelf-book-panel is-style-card"><?php echo bookshelf_cover_block( 'salt-orchard', __( 'Cover of The Salt Orchard by Mira Hollis', 'bookshelf' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside the helper. ?>

<!-- wp:group {"style":{"spacing":{"blockGap":"0.25rem"}},"layout":{"type":"default"}} -->
<div class="wp-block-group"><!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e( 'The Salt Orchard', 'bookshelf' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"bookshelf-book-author"} -->
<p class="bookshelf-book-author"><?php esc_html_e( 'by Mira Hollis', 'bookshelf' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:group {"className":"bookshelf-details","style":{"spacing":{"blockGap":"var:preset|spacing|20 var:preset|spacing|30","margin":{"top":"var:preset|spacing|30"}}},"fontSize":"small","layout":{"type":"grid","columnCount":2,"minimumColumnWidth":null}} -->
<div class="wp-block-group bookshelf-details has-small-font-size" style="margin-top:var(--wp--preset--spacing--30)"><!-- wp:paragraph -->
<p><?php esc_html_e( 'Series', 'bookshelf' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><?php esc_html_e( 'The Tidewater Books, Book 2 of 3', 'bookshelf' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><?php esc_html_e( 'Pages', 'bookshelf' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><?php echo esc_html( number_format_i18n( 384 ) ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><?php esc_html_e( 'Published', 'bookshelf' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><?php esc_html_e( '2026, Harrow & Lane', 'bookshelf' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><?php esc_html_e( 'My rating', 'bookshelf' ); ?></p>
<!-- /wp:paragraph -->

<?php echo bookshelf_rating_block( 4.5 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside the helper. ?>

<!-- wp:paragraph -->
<p><?php esc_html_e( 'Status', 'bookshelf' ); ?></p>
<!-- /wp:paragraph -->

<?php echo bookshelf_status_block( 'read' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside the helper. ?></div>
<!-- /wp:group --></div>
<!-- /wp:group --></aside>
<!-- /wp:group -->
