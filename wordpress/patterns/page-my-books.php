<?php
/**
 * Title: My books page
 * Slug: readerly/page-my-books
 * Categories: readerly-author-site, page
 * Keywords: books, bibliography, page
 * Block Types: core/post-content
 * Post Types: page
 * Viewport Width: 1280
 * Description: Your newest book followed by all your books. Use it with the Landing page template.
 *
 * @package Readerly
 */

?>
<!-- wp:group {"tagName":"section","align":"full","className":"readerly-section is-tight","layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull readerly-section is-tight"><!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained","contentSize":"52rem","justifyContent":"left"}} -->
<div class="wp-block-group alignwide"><!-- wp:paragraph {"className":"is-style-eyebrow"} -->
<p class="is-style-eyebrow"><?php esc_html_e( 'Bibliography', 'readerly' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":1,"style":{"typography":{"fontWeight":"400"}}} -->
<h1 class="wp-block-heading" style="font-weight:400"><?php esc_html_e( 'My books', 'readerly' ); ?></h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"textColor":"muted","fontSize":"large","style":{"typography":{"lineHeight":"1.55"}}} -->
<p class="has-muted-color has-text-color has-large-font-size" style="line-height:1.55"><?php esc_html_e( 'Every novel so far, newest first, with where to find each one.', 'readerly' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->

<!-- wp:pattern {"slug":"readerly/new-release"} /-->

<!-- wp:pattern {"slug":"readerly/my-books"} /-->
