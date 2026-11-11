<?php
/**
 * Title: Browse by genre
 * Slug: bookshelf/browse-by-genre
 * Categories: bookshelf-book-blog
 * Keywords: categories, genres, contents, browse
 * Viewport Width: 1280
 * Description: Your categories as a two-column contents list with dotted leaders and review counts.
 *
 * @package Bookshelf
 */

?>
<!-- wp:group {"tagName":"section","align":"full","className":"bookshelf-section","layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull bookshelf-section"><!-- wp:group {"align":"wide","className":"bookshelf-section-head","layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
<div class="wp-block-group alignwide bookshelf-section-head"><!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e( 'Browse by genre', 'bookshelf' ); ?></h2>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:categories {"showPostCounts":true,"align":"wide","className":"is-style-contents"} /--></section>
<!-- /wp:group -->
