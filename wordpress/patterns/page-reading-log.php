<?php
/**
 * Title: Reading log page
 * Slug: bookshelf/page-reading-log
 * Categories: bookshelf-reading-log, page
 * Keywords: reading log, shelves, page, journal
 * Block Types: core/post-content
 * Post Types: page
 * Viewport Width: 1280
 * Description: A full reading log: yearly stats, what you are reading now, and shelves for read, want to read and did not finish. Use it with the Landing page template.
 *
 * @package Bookshelf
 */

$bookshelf_shelves = bookshelf_sample_shelves();
?>
<!-- wp:pattern {"slug":"bookshelf/reading-stats"} /-->

<!-- wp:pattern {"slug":"bookshelf/currently-reading"} /-->

<?php
// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped -- block markup built from escaped parts.
echo bookshelf_shelf_section( __( 'Read this year', 'bookshelf' ), __( 'Most recently finished first', 'bookshelf' ), $bookshelf_shelves['read'] ) . "\n\n";
echo bookshelf_shelf_section( __( 'Want to read', 'bookshelf' ), __( 'Next up, in no particular order', 'bookshelf' ), $bookshelf_shelves['want'] ) . "\n\n";
echo bookshelf_shelf_section( __( 'Did not finish', 'bookshelf' ), __( 'Not for me, or not yet', 'bookshelf' ), $bookshelf_shelves['dnf'] ) . "\n";
// phpcs:enable
