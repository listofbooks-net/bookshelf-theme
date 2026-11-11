<?php
/**
 * Block markup for the book cards that several patterns share.
 *
 * Each function returns serialized core blocks, so the cards stay ordinary
 * blocks the site owner edits by hand once a pattern is inserted.
 *
 * @package Bookshelf
 */

if ( ! function_exists( 'bookshelf_image_uri' ) ) :
	/**
	 * URL of an image bundled with the theme.
	 *
	 * @param string $file Path below assets/images/.
	 * @return string
	 */
	function bookshelf_image_uri( $file ) {
		return get_theme_file_uri( 'assets/images/' . $file );
	}
endif;

if ( ! function_exists( 'bookshelf_cover_block' ) ) :
	/**
	 * Image block with the Book cover style.
	 *
	 * @param string $cover Cover file name in assets/images/covers/, without extension.
	 * @param string $alt   Alternative text; empty when a linked title follows.
	 * @return string
	 */
	function bookshelf_cover_block( $cover, $alt = '' ) {
		return '<!-- wp:image {"sizeSlug":"full","linkDestination":"none","className":"is-style-book-cover"} -->
<figure class="wp-block-image size-full is-style-book-cover"><img src="' . esc_url( bookshelf_image_uri( 'covers/' . $cover . '.svg' ) ) . '" alt="' . esc_attr( $alt ) . '"/></figure>
<!-- /wp:image -->';
	}
endif;

if ( ! function_exists( 'bookshelf_paragraph_block' ) ) :
	/**
	 * Paragraph block with an optional class. The HTML must already be escaped.
	 *
	 * @param string $html      Escaped inner HTML.
	 * @param string $class_name Optional class names.
	 * @return string
	 */
	function bookshelf_paragraph_block( $html, $class_name = '' ) {
		if ( '' === $class_name ) {
			return "<!-- wp:paragraph -->\n<p>" . $html . "</p>\n<!-- /wp:paragraph -->";
		}
		return '<!-- wp:paragraph {"className":"' . esc_attr( $class_name ) . '"} -->' . "\n" . '<p class="' . esc_attr( $class_name ) . '">' . $html . "</p>\n<!-- /wp:paragraph -->";
	}
endif;

if ( ! function_exists( 'bookshelf_heading_block' ) ) :
	/**
	 * Level 3 heading block.
	 *
	 * @param string $html Escaped inner HTML.
	 * @return string
	 */
	function bookshelf_heading_block( $html ) {
		return "<!-- wp:heading {\"level\":3} -->\n<h3 class=\"wp-block-heading\">" . $html . "</h3>\n<!-- /wp:heading -->";
	}
endif;

if ( ! function_exists( 'bookshelf_rating_block' ) ) :
	/**
	 * Rating paragraph: stars in the accent colour, then the score as text.
	 *
	 * @param float $rating Rating out of five, halves allowed.
	 * @return string
	 */
	function bookshelf_rating_block( $rating ) {
		$full  = (int) floor( $rating );
		$stars = str_repeat( '★', $full ) . ( $rating > $full ? '½' : '' ) . str_repeat( '☆', 5 - (int) ceil( $rating ) );
		/* translators: %s: rating, for example 4.5. */
		$score = sprintf( __( '%s / 5', 'bookshelf' ), number_format_i18n( $rating, floor( $rating ) === (float) $rating ? 0 : 1 ) );
		return bookshelf_paragraph_block(
			'<mark style="background-color:rgba(0, 0, 0, 0)" class="has-inline-color has-accent-color">' . $stars . '</mark> ' . esc_html( $score ),
			'is-style-rating'
		);
	}
endif;

if ( ! function_exists( 'bookshelf_status_block' ) ) :
	/**
	 * Status badge paragraph. The text names the status, so colour is never the only cue.
	 *
	 * @param string $status One of reading, read, want, dnf.
	 * @return string
	 */
	function bookshelf_status_block( $status ) {
		$labels = array(
			'reading' => __( 'Reading', 'bookshelf' ),
			'read'    => __( 'Read', 'bookshelf' ),
			'want'    => __( 'Want to read', 'bookshelf' ),
			'dnf'     => __( 'Did not finish', 'bookshelf' ),
		);
		return bookshelf_paragraph_block( esc_html( $labels[ $status ] ), 'is-style-status-' . $status );
	}
endif;

if ( ! function_exists( 'bookshelf_progress_block' ) ) :
	/**
	 * Reading progress: a decorative bar and a text label carrying the numbers.
	 *
	 * @param int $page  Current page.
	 * @param int $pages Total pages.
	 * @return string
	 */
	function bookshelf_progress_block( $page, $pages ) {
		$percent  = (int) round( 100 * $page / $pages );
		$gradient = 'linear-gradient(90deg,var(--wp--preset--color--accent-2) ' . $percent . '%,var(--wp--preset--color--line) ' . $percent . '%)';
		/* translators: 1: current page, 2: total pages, 3: percentage read. */
		$label = sprintf( __( 'Page %1$s of %2$s · %3$s%%', 'bookshelf' ), number_format_i18n( $page ), number_format_i18n( $pages ), number_format_i18n( $percent ) );
		return '<!-- wp:group {"className":"bookshelf-progress","style":{"color":{"gradient":"' . $gradient . '"}},"layout":{"type":"default"}} -->
<div class="wp-block-group bookshelf-progress has-background" style="background:' . $gradient . '"></div>
<!-- /wp:group -->

' . bookshelf_paragraph_block( esc_html( $label ), 'bookshelf-progress-label' );
	}
endif;

if ( ! function_exists( 'bookshelf_compact_card' ) ) :
	/**
	 * Compact book card: small cover, title, author, then a status, rating or progress line.
	 *
	 * @param array $book {
	 *     @type string $cover    Cover file name.
	 *     @type string $title    Book title.
	 *     @type string $author   Book author.
	 *     @type string $status   Optional status: reading, read, want or dnf.
	 *     @type float  $rating   Optional rating out of five.
	 *     @type int[]  $progress Optional [current page, total pages].
	 * }
	 * @return string
	 */
	function bookshelf_compact_card( $book ) {
		$body = array(
			bookshelf_heading_block( esc_html( $book['title'] ) ),
			bookshelf_paragraph_block( esc_html( $book['author'] ), 'bookshelf-book-author' ),
		);
		if ( isset( $book['rating'] ) ) {
			$body[] = bookshelf_rating_block( $book['rating'] );
		}
		if ( isset( $book['status'] ) ) {
			$body[] = bookshelf_status_block( $book['status'] );
		}
		if ( isset( $book['progress'] ) ) {
			$body[] = bookshelf_progress_block( $book['progress'][0], $book['progress'][1] );
		}
		/* translators: %s: book title. */
		$alt = sprintf( __( 'Cover of %s', 'bookshelf' ), $book['title'] );

		return '<!-- wp:group {"className":"bookshelf-book-card is-compact","layout":{"type":"default"}} -->
<div class="wp-block-group bookshelf-book-card is-compact">' . bookshelf_cover_block( $book['cover'], $alt ) . '

<!-- wp:group {"style":{"spacing":{"blockGap":"0.35rem"}},"layout":{"type":"default"}} -->
<div class="wp-block-group">' . implode( "\n\n", $body ) . '</div>
<!-- /wp:group --></div>
<!-- /wp:group -->';
	}
endif;

if ( ! function_exists( 'bookshelf_full_card' ) ) :
	/**
	 * Full book card for grids: cover, genre, title, author, rating, excerpt and date.
	 *
	 * @param array $book Keys: cover, genre, title, author, rating, excerpt, and optional date.
	 * @return string
	 */
	function bookshelf_full_card( $book ) {
		/* translators: %s: book author. */
		$byline = sprintf( __( 'by %s', 'bookshelf' ), $book['author'] );
		$blocks = array(
			bookshelf_cover_block( $book['cover'] ),
			bookshelf_paragraph_block( esc_html( $book['genre'] ), 'is-style-eyebrow' ),
			bookshelf_heading_block( '<a href="#">' . esc_html( $book['title'] ) . '</a>' ),
			bookshelf_paragraph_block( esc_html( $byline ), 'bookshelf-book-author' ),
			bookshelf_rating_block( $book['rating'] ),
			bookshelf_paragraph_block( esc_html( $book['excerpt'] ), 'bookshelf-excerpt' ),
		);
		if ( isset( $book['date'] ) ) {
			$blocks[] = bookshelf_paragraph_block( esc_html( $book['date'] ), 'bookshelf-book-author' );
		}
		return '<!-- wp:group {"className":"bookshelf-book-card","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"default"}} -->
<div class="wp-block-group bookshelf-book-card">' . implode( "\n\n", $blocks ) . '</div>
<!-- /wp:group -->';
	}
endif;

if ( ! function_exists( 'bookshelf_author_card' ) ) :
	/**
	 * Author's own book card. Buy and Learn more stay pinned to the card's foot.
	 *
	 * @param array $book Keys: cover, eyebrow, title, excerpt.
	 * @return string
	 */
	function bookshelf_author_card( $book ) {
		return '<!-- wp:group {"className":"bookshelf-book-card","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"default"}} -->
<div class="wp-block-group bookshelf-book-card">' . bookshelf_cover_block( $book['cover'] ) . '

' . bookshelf_paragraph_block( esc_html( $book['eyebrow'] ), 'is-style-eyebrow' ) . '

' . bookshelf_heading_block( '<a href="#">' . esc_html( $book['title'] ) . '</a>' ) . '

' . bookshelf_paragraph_block( esc_html( $book['excerpt'] ), 'bookshelf-excerpt' ) . '

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="#">' . esc_html__( 'Buy', 'bookshelf' ) . '</a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="#">' . esc_html__( 'Learn more', 'bookshelf' ) . '</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->';
	}
endif;

if ( ! function_exists( 'bookshelf_shelf_section' ) ) :
	/**
	 * A section holding one shelf of compact book cards.
	 *
	 * @param string $heading Section heading.
	 * @param string $note    Short note beside the heading.
	 * @param array  $books   Books as accepted by bookshelf_compact_card().
	 * @return string
	 */
	function bookshelf_shelf_section( $heading, $note, $books ) {
		$cards = array_map( 'bookshelf_compact_card', $books );
		return '<!-- wp:group {"tagName":"section","align":"full","className":"bookshelf-section","layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull bookshelf-section"><!-- wp:group {"align":"wide","className":"bookshelf-section-head","layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
<div class="wp-block-group alignwide bookshelf-section-head"><!-- wp:heading -->
<h2 class="wp-block-heading">' . esc_html( $heading ) . '</h2>
<!-- /wp:heading -->

' . bookshelf_paragraph_block( esc_html( $note ) ) . '</div>
<!-- /wp:group -->

<!-- wp:group {"align":"wide","className":"bookshelf-shelf","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"top"}} -->
<div class="wp-block-group alignwide bookshelf-shelf">' . implode( "\n\n", $cards ) . '</div>
<!-- /wp:group --></section>
<!-- /wp:group -->';
	}
endif;

if ( ! function_exists( 'bookshelf_sample_shelves' ) ) :
	/**
	 * Sample books for the reading log shelves, keyed by shelf.
	 *
	 * @return array
	 */
	function bookshelf_sample_shelves() {
		$read = array(
			array( 'salt-orchard', __( 'The Salt Orchard', 'bookshelf' ), __( 'Mira Hollis', 'bookshelf' ), 4.5 ),
			array( 'paper-moths', __( 'A House of Paper Moths', 'bookshelf' ), __( 'Celia Marr', 'bookshelf' ), 4.5 ),
			array( 'fieldwork', __( 'Fieldwork', 'bookshelf' ), __( 'Dara Quell', 'bookshelf' ), 5 ),
			array( 'glass-cartographer', __( 'The Glass Cartographer', 'bookshelf' ), __( 'Ravi Anand-Moss', 'bookshelf' ), 4 ),
			array( 'northern-ledger', __( 'Northern Ledger', 'bookshelf' ), __( 'Tomas Arvid', 'bookshelf' ), 4 ),
			array( 'winter-pears', __( 'Winter Pears', 'bookshelf' ), __( 'Lotte Brenner', 'bookshelf' ), 4.5 ),
			array( 'under-the-tamarind', __( 'Under the Tamarind', 'bookshelf' ), __( 'Yemi Adesanya-Cole', 'bookshelf' ), 4 ),
			array( 'quiet-arithmetic', __( 'The Quiet Arithmetic', 'bookshelf' ), __( 'Jun Sato-Bell', 'bookshelf' ), 3.5 ),
		);
		$want = array(
			array( 'fen-lights', __( 'Fen Lights', 'bookshelf' ), __( 'Mira Hollis', 'bookshelf' ) ),
			array( 'bell-foundry', __( 'The Bell Foundry', 'bookshelf' ), __( 'Isobel Thorne', 'bookshelf' ) ),
			array( 'brightwater-recipe-box', __( 'The Brightwater Recipe Box', 'bookshelf' ), __( 'Polly Fenwick', 'bookshelf' ) ),
			array( 'atlas-of-lost-rivers', __( 'Atlas of Lost Rivers', 'bookshelf' ), __( 'Samir Haddad-Lowe', 'bookshelf' ) ),
		);
		$dnf  = array(
			array( 'long-way-to-halden', __( 'The Long Way to Halden', 'bookshelf' ), __( 'Ivo Petrak', 'bookshelf' ) ),
			array( 'small-hours', __( 'Small Hours', 'bookshelf' ), __( 'Wren Atherton', 'bookshelf' ) ),
		);
		$card = function ( $book, $status ) {
			$card = array(
				'cover'  => $book[0],
				'title'  => $book[1],
				'author' => $book[2],
				'status' => $status,
			);
			if ( isset( $book[3] ) ) {
				$card['rating'] = $book[3];
			}
			return $card;
		};
		return array(
			'read' => array_map( $card, $read, array_fill( 0, count( $read ), 'read' ) ),
			'want' => array_map( $card, $want, array_fill( 0, count( $want ), 'want' ) ),
			'dnf'  => array_map( $card, $dnf, array_fill( 0, count( $dnf ), 'dnf' ) ),
		);
	}
endif;
