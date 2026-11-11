<?php
/**
 * Title: Events list
 * Slug: bookshelf/events
 * Categories: bookshelf-author-site
 * Keywords: events, readings, signings, workshops, dates
 * Viewport Width: 1280
 * Description: Upcoming readings, signings and workshops, each with a date, a place and a button.
 *
 * @package Bookshelf
 */

$bookshelf_events = array(
	array( '19', __( 'Nov', 'bookshelf' ), __( 'In conversation: The Bell Foundry', 'bookshelf' ), __( 'Wren & Ash Books, York · 7 pm · Free, booking advised', 'bookshelf' ), __( 'Book a place', 'bookshelf' ) ),
	array( '3', __( 'Dec', 'bookshelf' ), __( 'Reading and signing', 'bookshelf' ), __( 'Harbour Street Library, Whitby · 6:30 pm · Free', 'bookshelf' ), __( 'Details', 'bookshelf' ) ),
	array( '22', __( 'Jan', 'bookshelf' ), __( 'Writing small towns: a workshop', 'bookshelf' ), __( 'Online · 10 am to 1 pm GMT · 12 places', 'bookshelf' ), __( 'Join the waitlist', 'bookshelf' ) ),
);
?>
<!-- wp:group {"tagName":"section","align":"full","anchor":"events","className":"bookshelf-section","layout":{"type":"constrained"}} -->
<section id="events" class="wp-block-group alignfull bookshelf-section"><!-- wp:group {"align":"wide","className":"bookshelf-section-head","layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
<div class="wp-block-group alignwide bookshelf-section-head"><!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e( 'Events', 'bookshelf' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php esc_html_e( 'Readings, signings and workshops', 'bookshelf' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"align":"wide","className":"bookshelf-events","layout":{"type":"default"}} -->
<div class="wp-block-group alignwide bookshelf-events">
<?php foreach ( $bookshelf_events as $bookshelf_event ) : ?>
<!-- wp:group {"className":"bookshelf-event","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|30","left":"var:preset|spacing|40"}}},"layout":{"type":"flex","flexWrap":"wrap","verticalAlignment":"center"}} -->
<div class="wp-block-group bookshelf-event"><!-- wp:group {"className":"bookshelf-event-date","style":{"border":{"width":"1px","color":"var:preset|color|contrast","radius":"6px"},"spacing":{"padding":{"top":"var:preset|spacing|20","bottom":"var:preset|spacing|20"},"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group bookshelf-event-date has-border-color has-contrast-border-color" style="border-color:var(--wp--preset--color--contrast);border-width:1px;border-radius:6px;padding-top:var(--wp--preset--spacing--20);padding-bottom:var(--wp--preset--spacing--20)"><!-- wp:paragraph {"className":"bookshelf-event-day"} -->
<p class="bookshelf-event-day"><?php echo esc_html( $bookshelf_event[0] ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"bookshelf-event-month"} -->
<p class="bookshelf-event-month"><?php echo esc_html( $bookshelf_event[1] ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"bookshelf-event-body","style":{"spacing":{"blockGap":"0.25rem"}},"layout":{"type":"default"}} -->
<div class="wp-block-group bookshelf-event-body"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading"><?php echo esc_html( $bookshelf_event[2] ); ?></h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"textColor":"muted","fontSize":"small"} -->
<p class="has-muted-color has-text-color has-small-font-size"><?php echo esc_html( $bookshelf_event[3] ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="#"><?php echo esc_html( $bookshelf_event[4] ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->

<?php endforeach; ?>
</div>
<!-- /wp:group --></section>
<!-- /wp:group -->
