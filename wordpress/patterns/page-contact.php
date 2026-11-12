<?php
/**
 * Title: Contact and newsletter page
 * Slug: readerly/page-contact
 * Categories: readerly-book-blog, readerly-author-site, page
 * Keywords: contact, email, newsletter, subscribe, page
 * Block Types: core/post-content
 * Post Types: page
 * Description: How to reach you, for readers, publishers and events, followed by a newsletter signup.
 *
 * @package Readerly
 */

?>
<!-- wp:paragraph {"fontSize":"large","style":{"typography":{"lineHeight":"1.55"}}} -->
<p class="has-large-font-size" style="line-height:1.55"><?php esc_html_e( 'I read every message, though it can take me a week or two to reply.', 'readerly' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading"><?php esc_html_e( 'Readers', 'readerly' ); ?></h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php
printf(
	/* translators: %s: email address, as a link. */
	esc_html__( 'Recommendations and disagreements are equally welcome: %s', 'readerly' ),
	'<a href="mailto:hello@example.com">hello@example.com</a>'
);
?></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading"><?php esc_html_e( 'Publishers and publicists', 'readerly' ); ?></h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php
printf(
	/* translators: %s: email address, as a link. */
	esc_html__( 'Please read the review policy on the about page first, then write to %s', 'readerly' ),
	'<a href="mailto:books@example.com">books@example.com</a>'
);
?></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading"><?php esc_html_e( 'Events and festivals', 'readerly' ); ?></h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php esc_html_e( 'For readings, panels and school visits, include the date, the place and the audience.', 'readerly' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:pattern {"slug":"readerly/newsletter"} /-->
