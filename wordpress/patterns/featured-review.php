<?php
/**
 * Title: Featured review
 * Slug: readerly/featured-review
 * Categories: readerly-book-blog, featured
 * Keywords: sticky, featured, review, post, hero
 * Viewport Width: 1280
 * Description: Your sticky post in a large card: cover, category, title, excerpt and a link. Mark a post sticky to feature it.
 *
 * @package Readerly
 */

?>
<!-- wp:group {"tagName":"section","align":"full","anchor":"featured-review","className":"readerly-section is-tight readerly-first-page-only","layout":{"type":"constrained"}} -->
<section id="featured-review" class="wp-block-group alignfull readerly-section is-tight readerly-first-page-only"><!-- wp:query {"queryId":10,"query":{"perPage":1,"pages":1,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"only","inherit":false},"align":"wide"} -->
<div class="wp-block-query alignwide"><!-- wp:post-template -->
<!-- wp:group {"tagName":"article","className":"readerly-feature is-style-card","layout":{"type":"default"}} -->
<article class="wp-block-group readerly-feature is-style-card"><!-- wp:columns {"verticalAlignment":"center","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|50","left":"var:preset|spacing|60"}}}} -->
<div class="wp-block-columns are-vertically-aligned-center"><!-- wp:column {"verticalAlignment":"center","width":"18rem"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:18rem"><!-- wp:post-featured-image {"isLink":true,"aspectRatio":"2/3","className":"is-style-book-cover"} /--></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","style":{"spacing":{"blockGap":"var:preset|spacing|30"}}} -->
<div class="wp-block-column is-vertically-aligned-center"><!-- wp:post-terms <?php
echo serialize_block_attributes( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON-encoded block attributes.
	array(
		'term'      => 'category',
		'prefix'    => __( 'Featured review · ', 'readerly' ),
		'className' => 'is-style-eyebrow',
	)
);
?> /-->

<!-- wp:post-title {"level":2,"isLink":true} /-->

<!-- wp:post-excerpt {"excerptLength":45,"fontSize":"large","style":{"typography":{"lineHeight":"1.55"}}} /-->

<!-- wp:group {"className":"readerly-entry-meta","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|20","left":"var:preset|spacing|30"}}},"layout":{"type":"flex","flexWrap":"wrap"}} -->
<div class="wp-block-group readerly-entry-meta"><!-- wp:post-date /-->

<!-- wp:post-time-to-read /--></div>
<!-- /wp:group -->

<!-- wp:read-more <?php
echo serialize_block_attributes( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON-encoded block attributes.
	array(
		'content'   => __( 'Read the review', 'readerly' ),
		'className' => 'wp-element-button',
	)
);
?> /--></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></article>
<!-- /wp:group -->
<!-- /wp:post-template --></div>
<!-- /wp:query --></section>
<!-- /wp:group -->
