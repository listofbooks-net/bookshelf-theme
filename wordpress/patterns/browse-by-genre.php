<?php
/**
 * Title: Browse by genre
 * Slug: readerly/browse-by-genre
 * Categories: readerly-book-blog
 * Keywords: categories, genres, contents, browse
 * Viewport Width: 1280
 * Description: Your categories as a two-column contents list with dotted leaders and review counts.
 *
 * @package Readerly
 */

?>
<!-- wp:group {"tagName":"section","align":"full","className":"readerly-section","layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull readerly-section"><!-- wp:group {"align":"wide","className":"readerly-section-head","layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
<div class="wp-block-group alignwide readerly-section-head"><!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e( 'Browse by genre', 'readerly' ); ?></h2>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:categories {"showPostCounts":true,"align":"wide","className":"is-style-contents"} /--></section>
<!-- /wp:group -->
