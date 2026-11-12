<?php
/**
 * Title: Book card, compact
 * Slug: readerly/book-card-compact
 * Categories: readerly-reading-log
 * Keywords: book, card, compact, status, reading log
 * Description: One book in a row: small cover, title, author and a status badge.
 *
 * @package Readerly
 */

echo readerly_compact_card( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- block markup built from escaped parts.
	array(
		'cover'  => 'atlas-of-lost-rivers',
		'title'  => __( 'Atlas of Lost Rivers', 'readerly' ),
		'author' => __( 'Samir Haddad-Lowe', 'readerly' ),
		'status' => 'want',
	)
);
