<?php
/**
 * Title: Shelf
 * Slug: readerly/shelf
 * Categories: readerly-reading-log
 * Keywords: shelf, books, read, covers, reading log
 * Viewport Width: 1280
 * Description: A row of covers standing on a shelf, each with a title, author, rating and status. Duplicate a book to add one; delete one to remove it.
 *
 * @package Readerly
 */

$readerly_shelves = readerly_sample_shelves();
echo readerly_shelf_section( __( 'Read this year', 'readerly' ), __( 'Most recently finished first', 'readerly' ), $readerly_shelves['read'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- block markup built from escaped parts.
