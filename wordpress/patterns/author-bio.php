<?php
/**
 * Title: Author bio
 * Slug: bookshelf/author-bio
 * Categories: bookshelf-book-blog
 * Keywords: author, bio, reviewer, byline
 * Block Types: core/post-content
 * Post Types: wp_template
 * Description: The post author's name and biographical info, taken from their profile.
 *
 * @package Bookshelf
 */

?>
<!-- wp:group {"className":"bookshelf-bio","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50"},"margin":{"top":"var:preset|spacing|60"},"blockGap":"var:preset|spacing|20"}},"layout":{"type":"default"}} -->
<div class="wp-block-group bookshelf-bio" style="margin-top:var(--wp--preset--spacing--60);padding-top:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50)"><!-- wp:paragraph {"className":"is-style-eyebrow"} -->
<p class="is-style-eyebrow"><?php esc_html_e( 'Reviewed by', 'bookshelf' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:post-author-name {"isLink":true,"style":{"typography":{"fontWeight":"500","lineHeight":"1.15"}},"fontSize":"large","fontFamily":"heading"} /-->

<!-- wp:post-author-biography /--></div>
<!-- /wp:group -->
