<?php
/**
 * Title: Review list
 * Slug: bookshelf/post-list
 * Categories: bookshelf-book-blog
 * Keywords: archive, list, posts, reviews, query
 * Block Types: core/query
 * Post Types: wp_template
 * Description: Posts of the current archive or search as a list, cover beside text, with pagination.
 *
 * @package Bookshelf
 */

?>
<!-- wp:query {"queryId":20,"query":{"perPage":10,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":true},"align":"wide","className":"bookshelf-list"} -->
<div class="wp-block-query alignwide bookshelf-list"><!-- wp:post-template -->
<!-- wp:group {"tagName":"article","className":"bookshelf-book-card","layout":{"type":"default"}} -->
<article class="wp-block-group bookshelf-book-card"><!-- wp:post-featured-image {"isLink":true,"aspectRatio":"2/3","className":"is-style-book-cover"} /-->

<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"default"}} -->
<div class="wp-block-group"><!-- wp:post-terms {"term":"category","className":"is-style-eyebrow"} /-->

<!-- wp:post-title {"isLink":true} /-->

<!-- wp:post-excerpt {"excerptLength":40} /-->

<!-- wp:post-date /--></div>
<!-- /wp:group --></article>
<!-- /wp:group -->
<!-- /wp:post-template -->

<!-- wp:query-pagination {"paginationArrow":"arrow","layout":{"type":"flex","justifyContent":"center"}} -->
<!-- wp:query-pagination-previous /-->

<!-- wp:query-pagination-numbers /-->

<!-- wp:query-pagination-next /-->
<!-- /wp:query-pagination -->

<!-- wp:query-no-results -->
<!-- wp:paragraph -->
<p><?php esc_html_e( 'Nothing here yet. Try a different search, or browse the latest reviews from the front page.', 'bookshelf' ); ?></p>
<!-- /wp:paragraph -->
<!-- /wp:query-no-results --></div>
<!-- /wp:query -->
