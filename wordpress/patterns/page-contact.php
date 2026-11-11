<?php
/**
 * Title: Contact and newsletter page
 * Slug: bookshelf/page-contact
 * Categories: bookshelf-book-blog, bookshelf-author-site, page
 * Keywords: contact, email, newsletter, subscribe, page
 * Block Types: core/post-content
 * Post Types: page
 * Description: How to reach you, for readers, publishers and events, followed by a newsletter signup.
 *
 * @package Bookshelf
 */

?>
<!-- wp:paragraph {"fontSize":"large","style":{"typography":{"lineHeight":"1.55"}}} -->
<p class="has-large-font-size" style="line-height:1.55"><?php esc_html_e( 'I read every message, though it can take me a week or two to reply.', 'bookshelf' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading"><?php esc_html_e( 'Readers', 'bookshelf' ); ?></h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php esc_html_e( 'Recommendations and disagreements are equally welcome:', 'bookshelf' ); ?> <a href="mailto:hello@example.com">hello@example.com</a></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading"><?php esc_html_e( 'Publishers and publicists', 'bookshelf' ); ?></h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php esc_html_e( 'Please read the review policy on the about page first, then write to', 'bookshelf' ); ?> <a href="mailto:books@example.com">books@example.com</a></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading"><?php esc_html_e( 'Events and festivals', 'bookshelf' ); ?></h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php esc_html_e( 'For readings, panels and school visits, include the date, the place and the audience.', 'bookshelf' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:pattern {"slug":"bookshelf/newsletter"} /-->
