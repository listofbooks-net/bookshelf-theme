<?php
/**
 * Title: Book card, compact
 * Slug: bookshelf/book-card-compact
 * Categories: bookshelf-reading-log
 * Keywords: book, card, compact, status, reading log
 * Description: One book in a row: small cover, title, author and a status badge.
 *
 * @package Bookshelf
 */

echo bookshelf_compact_card( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- block markup built from escaped parts.
	array(
		'cover'  => 'atlas-of-lost-rivers',
		'title'  => __( 'Atlas of Lost Rivers', 'bookshelf' ),
		'author' => __( 'Samir Haddad-Lowe', 'bookshelf' ),
		'status' => 'want',
	)
);
