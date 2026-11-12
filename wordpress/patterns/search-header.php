<?php
/**
 * Title: Search header
 * Slug: readerly/search-header
 * Inserter: no
 *
 * @package Readerly
 */

?>
<!-- wp:group {"align":"wide","className":"readerly-archive-header","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|50"},"blockGap":"var:preset|spacing|40"}},"layout":{"type":"default"}} -->
<div class="wp-block-group alignwide readerly-archive-header" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--50)"><!-- wp:query-title {"type":"search"} /-->

<!-- wp:search <?php
echo serialize_block_attributes( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON-encoded block attributes.
	array(
		'label'          => __( 'Search', 'readerly' ),
		'showLabel'      => false,
		'placeholder'    => __( 'Search by title, author or genre', 'readerly' ),
		'width'          => 32,
		'widthUnit'      => 'rem',
		'buttonText'     => __( 'Search', 'readerly' ),
		'buttonPosition' => 'button-inside',
		'buttonUseIcon'  => true,
	)
);
?> /--></div>
<!-- /wp:group -->
