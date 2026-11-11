<?php
/**
 * Title: Author introduction
 * Slug: bookshelf/author-hero
 * Categories: bookshelf-author-site, banner
 * Keywords: author, hero, portrait, about, introduction
 * Viewport Width: 1280
 * Description: Portrait beside your name, a one-line introduction and two buttons.
 *
 * @package Bookshelf
 */

?>
<!-- wp:group {"tagName":"section","align":"full","anchor":"about","className":"bookshelf-section bookshelf-author-hero","layout":{"type":"constrained"}} -->
<section id="about" class="wp-block-group alignfull bookshelf-section bookshelf-author-hero"><!-- wp:columns {"verticalAlignment":"center","align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|50","left":"var:preset|spacing|70"}}}} -->
<div class="wp-block-columns alignwide are-vertically-aligned-center"><!-- wp:column {"verticalAlignment":"center","width":"41.66%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:41.66%"><!-- wp:image {"sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full"><img src="<?php echo esc_url( bookshelf_image_uri( 'portrait-isobel.svg' ) ); ?>" alt="<?php echo esc_attr__( 'Portrait of the author', 'bookshelf' ); ?>"/></figure>
<!-- /wp:image --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","width":"58.34%","style":{"spacing":{"blockGap":"var:preset|spacing|40"}}} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:58.34%"><!-- wp:paragraph {"className":"is-style-eyebrow"} -->
<p class="is-style-eyebrow"><?php esc_html_e( 'Novelist', 'bookshelf' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":1} -->
<h1 class="wp-block-heading"><?php esc_html_e( 'Isobel Thorne', 'bookshelf' ); ?></h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"fontSize":"large","style":{"typography":{"lineHeight":"1.55"}}} -->
<p class="has-large-font-size" style="line-height:1.55"><?php echo wp_kses( __( 'I write novels about small towns, old trades and the people who keep them going. My fourth book, <cite>The Bell Foundry</cite>, is out now.', 'bookshelf' ), array( 'cite' => array() ) ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"textColor":"muted"} -->
<p class="has-muted-color has-text-color"><?php esc_html_e( 'I live on the edge of a fen with a lurcher called Pip and far too many notebooks. Before writing full time I restored church clocks, which explains a great deal.', 'bookshelf' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="#new-release"><?php esc_html_e( 'Discover the new novel', 'bookshelf' ); ?></a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="#newsletter"><?php esc_html_e( 'Join my newsletter', 'bookshelf' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></section>
<!-- /wp:group -->
