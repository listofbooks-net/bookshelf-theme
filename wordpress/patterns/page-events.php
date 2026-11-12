<?php
/**
 * Title: Events page
 * Slug: readerly/page-events
 * Categories: readerly-author-site, page
 * Keywords: events, readings, signings, page
 * Block Types: core/post-content
 * Post Types: page
 * Viewport Width: 1280
 * Description: Upcoming events and a newsletter signup. Use it with the Landing page template.
 *
 * @package Readerly
 */

?>
<!-- wp:group {"tagName":"section","align":"full","className":"readerly-section is-tight","layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull readerly-section is-tight"><!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained","contentSize":"52rem","justifyContent":"left"}} -->
<div class="wp-block-group alignwide"><!-- wp:paragraph {"className":"is-style-eyebrow"} -->
<p class="is-style-eyebrow"><?php esc_html_e( 'Events', 'readerly' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":1,"style":{"typography":{"fontWeight":"400"}}} -->
<h1 class="wp-block-heading" style="font-weight:400"><?php esc_html_e( 'Come and say hello', 'readerly' ); ?></h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"textColor":"muted","fontSize":"large","style":{"typography":{"lineHeight":"1.55"}}} -->
<p class="has-muted-color has-text-color has-large-font-size" style="line-height:1.55"><?php esc_html_e( 'Readings, signings and workshops, in bookshops and libraries and sometimes online.', 'readerly' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->

<!-- wp:pattern {"slug":"readerly/events"} /-->

<!-- wp:pattern {"slug":"readerly/newsletter"} /-->
