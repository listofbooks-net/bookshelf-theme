<?php
/**
 * Title: Reading log page
 * Slug: readerly/page-reading-log
 * Categories: readerly-reading-log, page
 * Keywords: reading log, shelves, page, journal
 * Block Types: core/post-content
 * Post Types: page
 * Viewport Width: 1280
 * Description: A full reading log: yearly stats, what you are reading now, and shelves for read, want to read and did not finish. Use it with the Landing page template.
 *
 * @package Readerly
 */

$readerly_shelves = readerly_sample_shelves();
?>
<!-- wp:pattern {"slug":"readerly/reading-stats"} /-->

<!-- wp:pattern {"slug":"readerly/currently-reading"} /-->

<?php
// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped -- block markup built from escaped parts.
echo readerly_shelf_section( __( 'Read this year', 'readerly' ), __( 'Most recently finished first', 'readerly' ), $readerly_shelves['read'] ) . "\n\n";
echo readerly_shelf_section( __( 'Want to read', 'readerly' ), __( 'Next up, in no particular order', 'readerly' ), $readerly_shelves['want'] ) . "\n\n";
echo readerly_shelf_section( __( 'Did not finish', 'readerly' ), __( 'Not for me, or not yet', 'readerly' ), $readerly_shelves['dnf'] ) . "\n";
// phpcs:enable
