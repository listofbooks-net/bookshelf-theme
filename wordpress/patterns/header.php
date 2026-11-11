<?php
/**
 * Title: Header
 * Slug: bookshelf/header
 * Categories: header
 * Block Types: core/template-part/header
 * Inserter: no
 *
 * @package Bookshelf
 */

?>
<!-- wp:group {"className":"bookshelf-header","layout":{"type":"constrained"}} -->
<div class="wp-block-group bookshelf-header"><!-- wp:group {"align":"wide","style":{"spacing":{"padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|30"},"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
<div class="wp-block-group alignwide" style="padding-top:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--30)"><!-- wp:group {"style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group"><!-- wp:site-title {"level":0} /-->

<!-- wp:site-tagline /--></div>
<!-- /wp:group -->

<!-- wp:search <?php
echo serialize_block_attributes( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON-encoded block attributes.
	array(
		'label'          => __( 'Search', 'bookshelf' ),
		'showLabel'      => false,
		'placeholder'    => __( 'Search reviews', 'bookshelf' ),
		'buttonText'     => __( 'Search', 'bookshelf' ),
		'buttonPosition' => 'button-inside',
		'buttonUseIcon'  => true,
	)
);
?> /--></div>
<!-- /wp:group -->

<!-- wp:group {"align":"wide","style":{"spacing":{"padding":{"bottom":"var:preset|spacing|30"}}},"layout":{"type":"default"}} -->
<div class="wp-block-group alignwide" style="padding-bottom:var(--wp--preset--spacing--30)"><!-- wp:navigation {"overlayBackgroundColor":"base","overlayTextColor":"contrast","overlayMenu":"mobile","hasIcon":false,"layout":{"type":"flex","flexWrap":"wrap"},"style":{"spacing":{"blockGap":"var:preset|spacing|40"}}} /--></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
