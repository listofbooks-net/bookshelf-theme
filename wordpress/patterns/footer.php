<?php
/**
 * Title: Footer
 * Slug: bookshelf/footer
 * Categories: footer
 * Block Types: core/template-part/footer
 * Inserter: no
 *
 * @package Bookshelf
 */

?>
<!-- wp:group {"className":"bookshelf-footer","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|40"},"margin":{"top":"0"}}},"fontSize":"small","layout":{"type":"constrained"}} -->
<div class="wp-block-group bookshelf-footer has-small-font-size" style="margin-top:0;padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--40)"><!-- wp:columns {"align":"wide","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|50","top":"var:preset|spacing|50"}}}} -->
<div class="wp-block-columns alignwide"><!-- wp:column {"width":"50%"} -->
<div class="wp-block-column" style="flex-basis:50%"><!-- wp:site-title {"level":0,"fontSize":"large"} /-->

<!-- wp:site-tagline {"style":{"spacing":{"margin":{"top":"var:preset|spacing|20"}}}} /--></div>
<!-- /wp:column -->

<!-- wp:column {"width":"25%"} -->
<div class="wp-block-column" style="flex-basis:25%"><!-- wp:heading {"style":{"spacing":{"margin":{"bottom":"var:preset|spacing|30"}}}} -->
<h2 class="wp-block-heading" style="margin-bottom:var(--wp--preset--spacing--30)"><?php esc_html_e( 'Browse', 'bookshelf' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:navigation {"overlayMenu":"never","layout":{"type":"flex","orientation":"vertical"},"style":{"spacing":{"blockGap":"0.25rem"}}} /--></div>
<!-- /wp:column -->

<!-- wp:column {"width":"25%"} -->
<div class="wp-block-column" style="flex-basis:25%"><!-- wp:heading {"style":{"spacing":{"margin":{"bottom":"var:preset|spacing|30"}}}} -->
<h2 class="wp-block-heading" style="margin-bottom:var(--wp--preset--spacing--30)"><?php esc_html_e( 'Genres', 'bookshelf' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:categories /--></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:group {"align":"wide","className":"bookshelf-footer-bottom","style":{"spacing":{"margin":{"top":"var:preset|spacing|60"},"padding":{"top":"var:preset|spacing|40"},"blockGap":"var:preset|spacing|20"}},"textColor":"muted","layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
<div class="wp-block-group alignwide bookshelf-footer-bottom has-muted-color has-text-color" style="margin-top:var(--wp--preset--spacing--60);padding-top:var(--wp--preset--spacing--40)"><!-- wp:group {"style":{"spacing":{"blockGap":"0.3em"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group"><!-- wp:paragraph -->
<p>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:site-title {"level":0,"isLink":false,"style":{"typography":{"fontWeight":"400","letterSpacing":"0","lineHeight":"inherit"}},"fontSize":"small","fontFamily":"body"} /--></div>
<!-- /wp:group -->

<!-- wp:paragraph -->
<p><a href="https://www.listofbooks.net/"><?php esc_html_e( 'Theme by List of Books', 'bookshelf' ); ?></a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
