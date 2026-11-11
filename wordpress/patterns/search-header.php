<?php
/**
 * Title: Search header
 * Slug: bookshelf/search-header
 * Inserter: no
 *
 * @package Bookshelf
 */

?>
<!-- wp:group {"align":"wide","className":"bookshelf-archive-header","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|50"},"blockGap":"var:preset|spacing|40"}},"layout":{"type":"default"}} -->
<div class="wp-block-group alignwide bookshelf-archive-header" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--50)"><!-- wp:query-title {"type":"search"} /-->

<!-- wp:search {"label":"<?php echo esc_attr__( 'Search', 'bookshelf' ); ?>","showLabel":false,"placeholder":"<?php echo esc_attr__( 'Search by title, author or genre', 'bookshelf' ); ?>","width":32,"widthUnit":"rem","buttonText":"<?php echo esc_attr__( 'Search', 'bookshelf' ); ?>","buttonPosition":"button-inside","buttonUseIcon":true} /--></div>
<!-- /wp:group -->
