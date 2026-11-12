<?php
/**
 * Title: Events list
 * Slug: readerly/events
 * Categories: readerly-author-site
 * Keywords: events, readings, signings, workshops, dates
 * Viewport Width: 1280
 * Description: Upcoming readings, signings and workshops, each with a date, a place and a button.
 *
 * @package Readerly
 */

$readerly_events = array(
	array( '19', _x( 'Nov', 'abbreviated month name', 'readerly' ), __( 'In conversation: The Bell Foundry', 'readerly' ), __( 'Wren & Ash Books, York · 7 pm · Free, booking advised', 'readerly' ), __( 'Book a place', 'readerly' ) ),
	array( '3', _x( 'Dec', 'abbreviated month name', 'readerly' ), __( 'Reading and signing', 'readerly' ), __( 'Harbour Street Library, Whitby · 6:30 pm · Free', 'readerly' ), __( 'Details', 'readerly' ) ),
	array( '22', _x( 'Jan', 'abbreviated month name', 'readerly' ), __( 'Writing small towns: a workshop', 'readerly' ), __( 'Online · 10 am to 1 pm GMT · 12 places', 'readerly' ), __( 'Join the waitlist', 'readerly' ) ),
);
?>
<!-- wp:group {"tagName":"section","align":"full","anchor":"events","className":"readerly-section","layout":{"type":"constrained"}} -->
<section id="events" class="wp-block-group alignfull readerly-section"><!-- wp:group {"align":"wide","className":"readerly-section-head","layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
<div class="wp-block-group alignwide readerly-section-head"><!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e( 'Events', 'readerly' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php esc_html_e( 'Readings, signings and workshops', 'readerly' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"align":"wide","className":"readerly-events","layout":{"type":"default"}} -->
<div class="wp-block-group alignwide readerly-events">
<?php foreach ( $readerly_events as $readerly_event ) : ?>
<!-- wp:group {"className":"readerly-event","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|30","left":"var:preset|spacing|40"}}},"layout":{"type":"flex","flexWrap":"wrap","verticalAlignment":"center"}} -->
<div class="wp-block-group readerly-event"><!-- wp:group {"className":"readerly-event-date","style":{"border":{"width":"1px","color":"var:preset|color|contrast","radius":"6px"},"spacing":{"padding":{"top":"var:preset|spacing|20","bottom":"var:preset|spacing|20"},"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group readerly-event-date has-border-color has-contrast-border-color" style="border-color:var(--wp--preset--color--contrast);border-width:1px;border-radius:6px;padding-top:var(--wp--preset--spacing--20);padding-bottom:var(--wp--preset--spacing--20)"><!-- wp:paragraph {"className":"readerly-event-day"} -->
<p class="readerly-event-day"><?php echo esc_html( $readerly_event[0] ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"readerly-event-month"} -->
<p class="readerly-event-month"><?php echo esc_html( $readerly_event[1] ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"readerly-event-body","style":{"spacing":{"blockGap":"0.25rem"}},"layout":{"type":"default"}} -->
<div class="wp-block-group readerly-event-body"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading"><?php echo esc_html( $readerly_event[2] ); ?></h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"textColor":"muted","fontSize":"small"} -->
<p class="has-muted-color has-text-color has-small-font-size"><?php echo esc_html( $readerly_event[3] ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="#"><?php echo esc_html( $readerly_event[4] ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->

<?php endforeach; ?>
</div>
<!-- /wp:group --></section>
<!-- /wp:group -->
