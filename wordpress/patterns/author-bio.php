<?php
/**
 * Title: Author bio
 * Slug: readerly/author-bio
 * Categories: readerly-book-blog
 * Keywords: author, bio, reviewer, byline
 * Inserter: no
 * Description: The post author's name and biographical info, taken from their profile.
 *
 * @package Readerly
 */

?>
<!-- wp:group {"className":"readerly-bio","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50"},"margin":{"top":"var:preset|spacing|60"},"blockGap":"var:preset|spacing|20"}},"layout":{"type":"default"}} -->
<div class="wp-block-group readerly-bio" style="margin-top:var(--wp--preset--spacing--60);padding-top:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50)"><!-- wp:paragraph {"className":"is-style-eyebrow"} -->
<p class="is-style-eyebrow"><?php esc_html_e( 'Reviewed by', 'readerly' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:post-author-name {"isLink":true,"style":{"typography":{"fontWeight":"500","lineHeight":"1.15"}},"fontSize":"large","fontFamily":"heading"} /-->

<!-- wp:post-author-biography /--></div>
<!-- /wp:group -->
