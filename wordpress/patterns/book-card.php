<?php
/**
 * Title: Book card
 * Slug: readerly/book-card
 * Categories: readerly-reading-log, readerly-book-blog
 * Keywords: book, card, cover, rating
 * Description: One book: cover, genre, title, author, rating and a short note. Put several in a grid group.
 *
 * @package Readerly
 */

echo readerly_full_card( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- block markup built from escaped parts.
	array(
		'cover'   => 'paper-moths',
		'genre'   => __( 'Fantasy', 'readerly' ),
		'title'   => __( 'A House of Paper Moths', 'readerly' ),
		'author'  => __( 'Celia Marr', 'readerly' ),
		'rating'  => 4.5,
		'excerpt' => __( 'A bookbinder’s apprentice discovers that the moths eating the library’s oldest folios are rewriting them. Strange, tender and very funny.', 'readerly' ),
	)
);
