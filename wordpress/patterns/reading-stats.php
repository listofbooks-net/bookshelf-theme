<?php
/**
 * Title: Reading log introduction and stats
 * Slug: bookshelf/reading-stats
 * Categories: bookshelf-reading-log
 * Keywords: reading, log, stats, year, journal
 * Viewport Width: 1280
 * Description: A heading for your reading log and four numbers for the year. Update the numbers by hand.
 *
 * @package Bookshelf
 */

$bookshelf_stats = array(
	array( number_format_i18n( 10 ), __( 'Books read', 'bookshelf' ) ),
	array( number_format_i18n( 3304 ), __( 'Pages read', 'bookshelf' ) ),
	array( number_format_i18n( 4.2, 1 ), __( 'Average rating', 'bookshelf' ) ),
	array( number_format_i18n( 2 ), __( 'Did not finish', 'bookshelf' ) ),
);
?>
<!-- wp:group {"tagName":"section","align":"full","className":"bookshelf-section is-tight","layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull bookshelf-section is-tight"><!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained","contentSize":"52rem","justifyContent":"left"}} -->
<div class="wp-block-group alignwide"><!-- wp:paragraph {"className":"is-style-eyebrow"} -->
<p class="is-style-eyebrow"><?php esc_html_e( 'Reading journal', 'bookshelf' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":1,"style":{"typography":{"fontWeight":"400"}}} -->
<h1 class="wp-block-heading" style="font-weight:400"><?php esc_html_e( 'What I’m reading this year', 'bookshelf' ); ?></h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"textColor":"muted","fontSize":"large","style":{"typography":{"lineHeight":"1.55"}}} -->
<p class="has-muted-color has-text-color has-large-font-size" style="line-height:1.55"><?php esc_html_e( 'Every book I start goes in here, including the ones I abandon. Ratings are out of five; half stars are allowed when a book refuses to settle.', 'bookshelf' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"align":"wide","className":"bookshelf-stats","style":{"spacing":{"margin":{"top":"var:preset|spacing|50"},"blockGap":"var:preset|spacing|40"}},"layout":{"type":"grid","minimumColumnWidth":"9rem"}} -->
<div class="wp-block-group alignwide bookshelf-stats" style="margin-top:var(--wp--preset--spacing--50)">
<?php foreach ( $bookshelf_stats as $bookshelf_stat ) : ?>
<!-- wp:group {"className":"bookshelf-stat","style":{"spacing":{"blockGap":"0.25rem"}},"layout":{"type":"default"}} -->
<div class="wp-block-group bookshelf-stat"><!-- wp:paragraph {"className":"bookshelf-stat-number"} -->
<p class="bookshelf-stat-number"><?php echo esc_html( $bookshelf_stat[0] ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"bookshelf-stat-label"} -->
<p class="bookshelf-stat-label"><?php echo esc_html( $bookshelf_stat[1] ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<?php endforeach; ?>
</div>
<!-- /wp:group --></section>
<!-- /wp:group -->
