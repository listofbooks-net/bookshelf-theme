<?php
/**
 * Title: Blog introduction
 * Slug: bookshelf/blog-intro
 * Categories: bookshelf-book-blog, banner
 * Keywords: intro, hero, welcome, heading
 * Viewport Width: 1280
 * Description: A short welcome with a large headline and a link to the latest review.
 *
 * @package Bookshelf
 */

?>
<!-- wp:group {"tagName":"section","align":"full","className":"bookshelf-section is-tight bookshelf-intro","layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull bookshelf-section is-tight bookshelf-intro"><!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained","contentSize":"52rem","justifyContent":"left"}} -->
<div class="wp-block-group alignwide"><!-- wp:paragraph {"className":"is-style-eyebrow"} -->
<p class="is-style-eyebrow"><?php esc_html_e( 'Book notes and reviews', 'bookshelf' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":1,"style":{"typography":{"fontWeight":"400"}}} -->
<h1 class="wp-block-heading" style="font-weight:400"><?php echo wp_kses( __( 'Honest reviews of the books that kept me up <em>past midnight</em>, and a few that didn&rsquo;t.', 'bookshelf' ), array( 'em' => array() ) ); ?></h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"textColor":"muted","fontSize":"large","style":{"typography":{"lineHeight":"1.55"}}} -->
<p class="has-muted-color has-text-color has-large-font-size" style="line-height:1.55"><?php esc_html_e( 'Slow, careful reviews of literary fiction, quiet fantasy, essays and poetry, and notes on the ones that stay with me.', 'bookshelf' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="#latest-review"><?php esc_html_e( 'Read the latest review', 'bookshelf' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->
