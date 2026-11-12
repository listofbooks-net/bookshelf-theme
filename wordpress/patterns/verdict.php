<?php
/**
 * Title: Verdict box
 * Slug: readerly/verdict
 * Categories: readerly-book-blog
 * Keywords: verdict, summary, rating, pros, cons, review
 * Description: A boxed summary for the end of a review: rating, one-line verdict, and what worked and what didn't.
 *
 * @package Readerly
 */

?>
<!-- wp:group {"tagName":"section","className":"readerly-verdict","style":{"border":{"width":"1px","color":"var:preset|color|contrast","radius":"6px"},"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|50","right":"var:preset|spacing|50"},"blockGap":"var:preset|spacing|40"}},"layout":{"type":"default"}} -->
<section class="wp-block-group readerly-verdict has-border-color has-contrast-border-color" style="border-color:var(--wp--preset--color--contrast);border-width:1px;border-radius:6px;padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)"><!-- wp:group {"style":{"spacing":{"blockGap":{"top":"var:preset|spacing|20","left":"var:preset|spacing|30"}}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
<div class="wp-block-group"><!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e( 'My verdict', 'readerly' ); ?></h2>
<!-- /wp:heading -->

<?php echo readerly_rating_block( 4.5 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside the helper. ?></div>
<!-- /wp:group -->

<!-- wp:paragraph {"className":"readerly-verdict-summary"} -->
<p class="readerly-verdict-summary"><?php esc_html_e( 'Patient, precise and quietly devastating. The best novel about inheritance I’ve read this year, give or take one too-tidy ending.', 'readerly' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:columns {"style":{"spacing":{"blockGap":{"top":"var:preset|spacing|40","left":"var:preset|spacing|40"}}}} -->
<div class="wp-block-columns"><!-- wp:column {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}}} -->
<div class="wp-block-column"><!-- wp:heading {"level":3,"fontSize":"medium"} -->
<h3 class="wp-block-heading has-medium-font-size"><?php esc_html_e( 'What worked', 'readerly' ); ?></h3>
<!-- /wp:heading -->

<!-- wp:list -->
<ul class="wp-block-list"><!-- wp:list-item -->
<li><?php esc_html_e( 'The orchard as a living, seasonal clock', 'readerly' ); ?></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><?php esc_html_e( 'A neighbour who becomes the book’s grumpy heart', 'readerly' ); ?></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><?php esc_html_e( 'Prose that makes weather feel like plot', 'readerly' ); ?></li>
<!-- /wp:list-item --></ul>
<!-- /wp:list --></div>
<!-- /wp:column -->

<!-- wp:column {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}}} -->
<div class="wp-block-column"><!-- wp:heading {"level":3,"fontSize":"medium"} -->
<h3 class="wp-block-heading has-medium-font-size"><?php esc_html_e( 'Less so', 'readerly' ); ?></h3>
<!-- /wp:heading -->

<!-- wp:list -->
<ul class="wp-block-list"><!-- wp:list-item -->
<li><?php esc_html_e( 'A too-tidy answer to the central mystery', 'readerly' ); ?></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><?php esc_html_e( 'A becalmed middle stretch', 'readerly' ); ?></li>
<!-- /wp:list-item --></ul>
<!-- /wp:list --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></section>
<!-- /wp:group -->
