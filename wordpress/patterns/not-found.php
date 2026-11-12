<?php
/**
 * Title: Page not found
 * Slug: readerly/not-found
 * Inserter: no
 *
 * @package Readerly
 */

?>
<!-- wp:group {"tagName":"section","align":"full","className":"readerly-section readerly-not-found","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|60"}}},"layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull readerly-section readerly-not-found" style="padding-top:var(--wp--preset--spacing--80);padding-bottom:var(--wp--preset--spacing--60)"><!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"default"}} -->
<div class="wp-block-group alignwide"><!-- wp:paragraph {"className":"readerly-not-found-code"} -->
<p class="readerly-not-found-code">404</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":1} -->
<h1 class="wp-block-heading"><?php esc_html_e( 'This page has wandered off the shelf', 'readerly' ); ?></h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"textColor":"muted","fontSize":"large"} -->
<p class="has-muted-color has-text-color has-large-font-size"><?php esc_html_e( 'The link may be old, or the page may have been moved or renamed. Try a search, or start again from the front page.', 'readerly' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:search <?php
echo serialize_block_attributes( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON-encoded block attributes.
	array(
		'label'          => __( 'Search', 'readerly' ),
		'showLabel'      => false,
		'placeholder'    => __( 'Search by title, author or genre', 'readerly' ),
		'buttonText'     => __( 'Search', 'readerly' ),
		'buttonPosition' => 'button-inside',
	)
);
?> /-->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back to the front page', 'readerly' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","align":"full","className":"readerly-section","layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull readerly-section"><!-- wp:group {"align":"wide","className":"readerly-section-head","layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
<div class="wp-block-group alignwide readerly-section-head"><!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e( 'Or pick something to read', 'readerly' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php esc_html_e( 'Recent reviews', 'readerly' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:query {"queryId":30,"query":{"perPage":6,"pages":1,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false},"align":"wide"} -->
<div class="wp-block-query alignwide"><!-- wp:post-template {"className":"readerly-shelf","layout":{"type":"flex","flexWrap":"nowrap"}} -->
<!-- wp:group {"className":"readerly-book-card is-compact","layout":{"type":"default"}} -->
<div class="wp-block-group readerly-book-card is-compact"><!-- wp:post-featured-image {"isLink":true,"aspectRatio":"2/3","className":"is-style-book-cover"} /-->

<!-- wp:post-title {"level":3,"isLink":true} /--></div>
<!-- /wp:group -->
<!-- /wp:post-template --></div>
<!-- /wp:query --></section>
<!-- /wp:group -->
