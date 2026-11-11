<?php
/**
 * Title: New release
 * Slug: bookshelf/new-release
 * Categories: bookshelf-author-site, featured
 * Keywords: book, release, launch, cover, buy
 * Viewport Width: 1280
 * Description: A band announcing your newest book: cover, blurb, facts, buttons and where to buy it.
 *
 * @package Bookshelf
 */

?>
<!-- wp:group {"tagName":"section","align":"full","anchor":"new-release","className":"bookshelf-section bookshelf-band","layout":{"type":"constrained"}} -->
<section id="new-release" class="wp-block-group alignfull bookshelf-section bookshelf-band"><!-- wp:columns {"verticalAlignment":"center","align":"wide","className":"bookshelf-release","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|50","left":"var:preset|spacing|70"}}}} -->
<div class="wp-block-columns alignwide are-vertically-aligned-center bookshelf-release"><!-- wp:column {"verticalAlignment":"center","width":"41.66%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:41.66%"><?php echo bookshelf_cover_block( 'bell-foundry', __( 'Cover of The Bell Foundry by Isobel Thorne', 'bookshelf' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside the helper. ?></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","width":"58.34%","style":{"spacing":{"blockGap":"var:preset|spacing|30"}}} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:58.34%"><!-- wp:paragraph {"className":"is-style-eyebrow"} -->
<p class="is-style-eyebrow"><?php esc_html_e( 'New release · out now', 'bookshelf' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"fontSize":"xx-large"} -->
<h2 class="wp-block-heading has-xx-large-font-size"><?php esc_html_e( 'The Bell Foundry', 'bookshelf' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"fontSize":"large","style":{"typography":{"lineHeight":"1.55"}}} -->
<p class="has-large-font-size" style="line-height:1.55"><?php esc_html_e( 'In a river town where the last bell foundry is about to close, a young caster is given one final commission: a bell that must ring for a wedding no one in the town wants to happen.', 'bookshelf' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"bookshelf-release-facts"} -->
<p class="bookshelf-release-facts"><?php esc_html_e( 'Novel · 368 pages · Hardback, ebook and audiobook', 'bookshelf' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="#where-to-buy"><?php esc_html_e( 'Buy the book', 'bookshelf' ); ?></a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="#"><?php esc_html_e( 'Read the first chapter', 'bookshelf' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:group {"align":"wide","anchor":"where-to-buy","className":"bookshelf-where-to-buy","style":{"spacing":{"margin":{"top":"var:preset|spacing|60"},"padding":{"top":"var:preset|spacing|40"},"blockGap":"var:preset|spacing|30"}},"layout":{"type":"default"}} -->
<div id="where-to-buy" class="wp-block-group alignwide bookshelf-where-to-buy" style="margin-top:var(--wp--preset--spacing--60);padding-top:var(--wp--preset--spacing--40)"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading"><?php esc_html_e( 'Where to buy', 'bookshelf' ); ?></h3>
<!-- /wp:heading -->

<!-- wp:list -->
<ul class="wp-block-list"><!-- wp:list-item -->
<li><?php esc_html_e( 'Ask at your local independent bookshop. They can order any of my books within a few days.', 'bookshelf' ); ?></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><?php esc_html_e( 'Order signed hardbacks directly from my publisher.', 'bookshelf' ); ?></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><?php esc_html_e( 'Borrow the ebook or audiobook free from your local library.', 'bookshelf' ); ?></li>
<!-- /wp:list-item --></ul>
<!-- /wp:list --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->
