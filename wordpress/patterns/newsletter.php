<?php
/**
 * Title: Newsletter
 * Slug: bookshelf/newsletter
 * Categories: bookshelf-book-blog, bookshelf-author-site, call-to-action
 * Keywords: newsletter, subscribe, email, signup
 * Viewport Width: 1280
 * Description: A band inviting readers to your newsletter. Point the button at your newsletter provider's signup page.
 *
 * @package Bookshelf
 */

?>
<!-- wp:group {"tagName":"section","align":"full","className":"bookshelf-section bookshelf-band","layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull bookshelf-section bookshelf-band"><!-- wp:columns {"verticalAlignment":"bottom","align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|40","left":"var:preset|spacing|60"}}}} -->
<div class="wp-block-columns alignwide are-vertically-aligned-bottom"><!-- wp:column {"verticalAlignment":"bottom","style":{"spacing":{"blockGap":"var:preset|spacing|30"}}} -->
<div class="wp-block-column is-vertically-aligned-bottom"><!-- wp:paragraph {"className":"is-style-eyebrow"} -->
<p class="is-style-eyebrow"><?php esc_html_e( 'The Sunday letter', 'bookshelf' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e( 'One email a month: what I read, what I loved, and what I gave up on.', 'bookshelf' ); ?></h2>
<!-- /wp:heading --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"bottom","style":{"spacing":{"blockGap":"var:preset|spacing|30"}}} -->
<div class="wp-block-column is-vertically-aligned-bottom"><!-- wp:paragraph -->
<p><?php esc_html_e( 'New reviews, the month’s reading log and one book I can’t stop recommending.', 'bookshelf' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="#"><?php esc_html_e( 'Subscribe', 'bookshelf' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->

<!-- wp:paragraph {"textColor":"muted","fontSize":"small"} -->
<p class="has-muted-color has-text-color has-small-font-size"><?php esc_html_e( 'No spam, ever. Unsubscribe with one click.', 'bookshelf' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></section>
<!-- /wp:group -->
