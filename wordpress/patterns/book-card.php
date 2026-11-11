<?php
/**
 * Title: Book card
 * Slug: bookshelf/book-card
 * Categories: bookshelf-reading-log, bookshelf-book-blog
 * Keywords: book, card, cover, rating
 * Description: One book: cover, genre, title, author, rating and a short note. Put several in a grid group.
 *
 * @package Bookshelf
 */

echo bookshelf_full_card( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- block markup built from escaped parts.
	array(
		'cover'   => 'paper-moths',
		'genre'   => __( 'Fantasy', 'bookshelf' ),
		'title'   => __( 'A House of Paper Moths', 'bookshelf' ),
		'author'  => __( 'Celia Marr', 'bookshelf' ),
		'rating'  => 4.5,
		'excerpt' => __( 'A bookbinder’s apprentice discovers that the moths eating the library’s oldest folios are rewriting them. Strange, tender and very funny.', 'bookshelf' ),
	)
);
