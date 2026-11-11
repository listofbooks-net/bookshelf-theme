<?php
/**
 * Title: Post meta
 * Slug: bookshelf/post-meta
 * Inserter: no
 *
 * @package Bookshelf
 */

?>
<!-- wp:group {"className":"bookshelf-entry-meta","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|20","left":"var:preset|spacing|30"}}},"layout":{"type":"flex","flexWrap":"wrap"}} -->
<div class="wp-block-group bookshelf-entry-meta"><!-- wp:group {"style":{"spacing":{"blockGap":"0.3em"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group"><!-- wp:paragraph -->
<p><?php echo esc_html_x( 'By', 'post author prefix', 'bookshelf' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:post-author-name {"isLink":true} /--></div>
<!-- /wp:group -->

<!-- wp:post-date /-->

<!-- wp:post-time-to-read /--></div>
<!-- /wp:group -->
