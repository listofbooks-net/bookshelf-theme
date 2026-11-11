<?php
/**
 * Title: Shelf
 * Slug: bookshelf/shelf
 * Categories: bookshelf-reading-log
 * Keywords: shelf, books, read, covers, reading log
 * Viewport Width: 1280
 * Description: A row of covers standing on a shelf, each with a title, author, rating and status. Duplicate a book to add one; delete one to remove it.
 *
 * @package Bookshelf
 */

$bookshelf_shelves = bookshelf_sample_shelves();
echo bookshelf_shelf_section( __( 'Read this year', 'bookshelf' ), __( 'Most recently finished first', 'bookshelf' ), $bookshelf_shelves['read'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- block markup built from escaped parts.
