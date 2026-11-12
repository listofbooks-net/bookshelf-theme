<?php
/**
 * Title: Newsletter
 * Slug: readerly/newsletter
 * Categories: readerly-book-blog, readerly-author-site, call-to-action
 * Keywords: newsletter, subscribe, email, signup
 * Viewport Width: 1280
 * Description: A band inviting readers to your newsletter. Point the button at your newsletter provider's signup page.
 *
 * @package Readerly
 */

?>
<!-- wp:group {"tagName":"section","align":"full","anchor":"newsletter","className":"readerly-section readerly-band","layout":{"type":"constrained"}} -->
<section id="newsletter" class="wp-block-group alignfull readerly-section readerly-band"><!-- wp:columns {"verticalAlignment":"bottom","align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|40","left":"var:preset|spacing|60"}}}} -->
<div class="wp-block-columns alignwide are-vertically-aligned-bottom"><!-- wp:column {"verticalAlignment":"bottom","style":{"spacing":{"blockGap":"var:preset|spacing|30"}}} -->
<div class="wp-block-column is-vertically-aligned-bottom"><!-- wp:paragraph {"className":"is-style-eyebrow"} -->
<p class="is-style-eyebrow"><?php esc_html_e( 'The Sunday letter', 'readerly' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e( 'One email a month: what I read, what I loved, and what I gave up on.', 'readerly' ); ?></h2>
<!-- /wp:heading --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"bottom","style":{"spacing":{"blockGap":"var:preset|spacing|30"}}} -->
<div class="wp-block-column is-vertically-aligned-bottom"><!-- wp:paragraph -->
<p><?php esc_html_e( 'New reviews, the month’s reading log and one book I can’t stop recommending.', 'readerly' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="#"><?php esc_html_e( 'Subscribe', 'readerly' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->

<!-- wp:paragraph {"textColor":"muted","fontSize":"small"} -->
<p class="has-muted-color has-text-color has-small-font-size"><?php esc_html_e( 'No spam, ever. Unsubscribe with one click.', 'readerly' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></section>
<!-- /wp:group -->
