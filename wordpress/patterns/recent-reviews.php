<?php
/**
 * Title: Recent reviews grid
 * Slug: bookshelf/recent-reviews
 * Categories: bookshelf-book-blog, query
 * Keywords: grid, recent, posts, reviews, covers
 * Viewport Width: 1280
 * Post Types: wp_template
 * Description: The blog's posts, four across, each with its cover, category, title, excerpt and date. It follows the Reading settings and paginates with /page/N/, so use it in the Blog Home template.
 *
 * @package Bookshelf
 */

?>
<!-- wp:group {"tagName":"section","align":"full","anchor":"reviews","className":"bookshelf-section bookshelf-recent","layout":{"type":"constrained"}} -->
<section id="reviews" class="wp-block-group alignfull bookshelf-section bookshelf-recent"><!-- wp:group {"align":"wide","className":"bookshelf-section-head","layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
<div class="wp-block-group alignwide bookshelf-section-head"><!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e( 'Recent reviews', 'bookshelf' ); ?></h2>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:query {"queryId":11,"query":{"perPage":8,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":true},"align":"wide"} -->
<div class="wp-block-query alignwide"><!-- wp:post-template {"className":"bookshelf-book-grid","style":{"spacing":{"blockGap":"var:preset|spacing|50"}},"layout":{"type":"grid","columnCount":4,"minimumColumnWidth":"9rem"}} -->
<!-- wp:group {"tagName":"article","className":"bookshelf-book-card","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"default"}} -->
<article class="wp-block-group bookshelf-book-card"><!-- wp:post-featured-image {"isLink":true,"aspectRatio":"2/3","className":"is-style-book-cover"} /-->

<!-- wp:post-terms {"term":"category","className":"is-style-eyebrow"} /-->

<!-- wp:post-title {"level":3,"isLink":true} /-->

<!-- wp:post-excerpt {"excerptLength":24} /-->

<!-- wp:post-date /--></article>
<!-- /wp:group -->
<!-- /wp:post-template -->

<!-- wp:query-pagination {"paginationArrow":"arrow","layout":{"type":"flex","justifyContent":"space-between"}} -->
<!-- wp:query-pagination-previous <?php
echo serialize_block_attributes( array( 'label' => __( 'Newer reviews', 'bookshelf' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON-encoded block attributes.
?> /-->

<!-- wp:query-pagination-next <?php
echo serialize_block_attributes( array( 'label' => __( 'Older reviews', 'bookshelf' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON-encoded block attributes.
?> /-->
<!-- /wp:query-pagination -->

<!-- wp:query-no-results -->
<!-- wp:paragraph -->
<p><?php esc_html_e( 'No reviews yet. Publish your first post and it will appear here.', 'bookshelf' ); ?></p>
<!-- /wp:paragraph -->
<!-- /wp:query-no-results --></div>
<!-- /wp:query --></section>
<!-- /wp:group -->
