<?php
/**
 * Block markup for the book cards that several patterns share.
 *
 * Each function returns serialized core blocks, so the cards stay ordinary
 * blocks the site owner edits by hand once a pattern is inserted.
 *
 * @package Readerly
 */

if ( ! function_exists( 'readerly_image_uri' ) ) :
	/**
	 * URL of an image bundled with the theme.
	 *
	 * @param string $file Path below assets/images/.
	 * @return string
	 */
	function readerly_image_uri( $file ) {
		return get_theme_file_uri( 'assets/images/' . $file );
	}
endif;

if ( ! function_exists( 'readerly_cover_block' ) ) :
	/**
	 * Image block with the Book cover style.
	 *
	 * @param string $cover Cover file name in assets/images/covers/, without extension.
	 * @param string $alt   Alternative text; empty when a linked title follows.
	 * @return string
	 */
	function readerly_cover_block( $cover, $alt = '' ) {
		return '<!-- wp:image {"sizeSlug":"full","linkDestination":"none","className":"is-style-book-cover"} -->
<figure class="wp-block-image size-full is-style-book-cover"><img src="' . esc_url( readerly_image_uri( 'covers/' . $cover . '.svg' ) ) . '" alt="' . esc_attr( $alt ) . '"/></figure>
<!-- /wp:image -->';
	}
endif;

if ( ! function_exists( 'readerly_paragraph_block' ) ) :
	/**
	 * Paragraph block with an optional class. The HTML must already be escaped.
	 *
	 * @param string $html      Escaped inner HTML.
	 * @param string $class_name Optional class names.
	 * @return string
	 */
	function readerly_paragraph_block( $html, $class_name = '' ) {
		if ( '' === $class_name ) {
			return "<!-- wp:paragraph -->\n<p>" . $html . "</p>\n<!-- /wp:paragraph -->";
		}
		return '<!-- wp:paragraph {"className":"' . esc_attr( $class_name ) . '"} -->' . "\n" . '<p class="' . esc_attr( $class_name ) . '">' . $html . "</p>\n<!-- /wp:paragraph -->";
	}
endif;

if ( ! function_exists( 'readerly_heading_block' ) ) :
	/**
	 * Level 3 heading block.
	 *
	 * @param string $html Escaped inner HTML.
	 * @return string
	 */
	function readerly_heading_block( $html ) {
		return "<!-- wp:heading {\"level\":3} -->\n<h3 class=\"wp-block-heading\">" . $html . "</h3>\n<!-- /wp:heading -->";
	}
endif;

if ( ! function_exists( 'readerly_rating_block' ) ) :
	/**
	 * Rating paragraph: stars in the accent colour, then the score as text.
	 *
	 * @param float $rating Rating out of five, halves allowed.
	 * @return string
	 */
	function readerly_rating_block( $rating ) {
		$full  = (int) floor( $rating );
		$stars = str_repeat( '★', $full ) . ( $rating > $full ? '½' : '' ) . str_repeat( '☆', 5 - (int) ceil( $rating ) );
		/* translators: %s: rating, for example 4.5. */
		$score = sprintf( __( '%s / 5', 'readerly' ), number_format_i18n( $rating, floor( $rating ) === (float) $rating ? 0 : 1 ) );
		return readerly_paragraph_block(
			'<mark style="background-color:rgba(0, 0, 0, 0)" class="has-inline-color has-accent-color">' . $stars . '</mark> ' . esc_html( $score ),
			'is-style-rating'
		);
	}
endif;

if ( ! function_exists( 'readerly_status_block' ) ) :
	/**
	 * Status badge paragraph. The text names the status, so colour is never the only cue.
	 *
	 * @param string $status One of reading, read, want, dnf.
	 * @return string
	 */
	function readerly_status_block( $status ) {
		$labels = array(
			'reading' => __( 'Reading', 'readerly' ),
			'read'    => __( 'Read', 'readerly' ),
			'want'    => __( 'Want to read', 'readerly' ),
			'dnf'     => __( 'Did not finish', 'readerly' ),
		);
		return readerly_paragraph_block( esc_html( $labels[ $status ] ), 'is-style-status-' . $status );
	}
endif;

if ( ! function_exists( 'readerly_progress_block' ) ) :
	/**
	 * Reading progress: a decorative bar and a text label carrying the numbers.
	 *
	 * @param int $page  Current page.
	 * @param int $pages Total pages.
	 * @return string
	 */
	function readerly_progress_block( $page, $pages ) {
		$percent  = (int) round( 100 * $page / $pages );
		$gradient = 'linear-gradient(90deg,var(--wp--preset--color--accent-2) ' . $percent . '%,var(--wp--preset--color--line) ' . $percent . '%)';
		/* translators: 1: current page, 2: total pages, 3: percentage read. */
		$label = sprintf( __( 'Page %1$s of %2$s · %3$s%%', 'readerly' ), number_format_i18n( $page ), number_format_i18n( $pages ), number_format_i18n( $percent ) );
		return '<!-- wp:group {"className":"readerly-progress","style":{"color":{"gradient":"' . $gradient . '"}},"layout":{"type":"default"}} -->
<div class="wp-block-group readerly-progress has-background" style="background:' . $gradient . '"></div>
<!-- /wp:group -->

' . readerly_paragraph_block( esc_html( $label ), 'readerly-progress-label' );
	}
endif;

if ( ! function_exists( 'readerly_compact_card' ) ) :
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
	function readerly_compact_card( $book ) {
		$body = array(
			readerly_heading_block( esc_html( $book['title'] ) ),
			readerly_paragraph_block( esc_html( $book['author'] ), 'readerly-book-author' ),
		);
		if ( isset( $book['rating'] ) ) {
			$body[] = readerly_rating_block( $book['rating'] );
		}
		if ( isset( $book['status'] ) ) {
			$body[] = readerly_status_block( $book['status'] );
		}
		if ( isset( $book['progress'] ) ) {
			$body[] = readerly_progress_block( $book['progress'][0], $book['progress'][1] );
		}
		/* translators: %s: book title. */
		$alt = sprintf( __( 'Cover of %s', 'readerly' ), $book['title'] );

		return '<!-- wp:group {"className":"readerly-book-card is-compact","layout":{"type":"default"}} -->
<div class="wp-block-group readerly-book-card is-compact">' . readerly_cover_block( $book['cover'], $alt ) . '

<!-- wp:group {"style":{"spacing":{"blockGap":"0.35rem"}},"layout":{"type":"default"}} -->
<div class="wp-block-group">' . implode( "\n\n", $body ) . '</div>
<!-- /wp:group --></div>
<!-- /wp:group -->';
	}
endif;

if ( ! function_exists( 'readerly_full_card' ) ) :
	/**
	 * Full book card for grids: cover, genre, title, author, rating, excerpt and date.
	 *
	 * @param array $book Keys: cover, genre, title, author, rating, excerpt, and optional date.
	 * @return string
	 */
	function readerly_full_card( $book ) {
		/* translators: %s: book author. */
		$byline = sprintf( __( 'by %s', 'readerly' ), $book['author'] );
		$blocks = array(
			readerly_cover_block( $book['cover'] ),
			readerly_paragraph_block( esc_html( $book['genre'] ), 'is-style-eyebrow' ),
			readerly_heading_block( '<a href="#">' . esc_html( $book['title'] ) . '</a>' ),
			readerly_paragraph_block( esc_html( $byline ), 'readerly-book-author' ),
			readerly_rating_block( $book['rating'] ),
			readerly_paragraph_block( esc_html( $book['excerpt'] ), 'readerly-excerpt' ),
		);
		if ( isset( $book['date'] ) ) {
			$blocks[] = readerly_paragraph_block( esc_html( $book['date'] ), 'readerly-book-author' );
		}
		return '<!-- wp:group {"className":"readerly-book-card","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"default"}} -->
<div class="wp-block-group readerly-book-card">' . implode( "\n\n", $blocks ) . '</div>
<!-- /wp:group -->';
	}
endif;

if ( ! function_exists( 'readerly_author_card' ) ) :
	/**
	 * Author's own book card. Buy and Learn more stay pinned to the card's foot.
	 *
	 * @param array $book Keys: cover, eyebrow, title, excerpt.
	 * @return string
	 */
	function readerly_author_card( $book ) {
		return '<!-- wp:group {"className":"readerly-book-card","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"default"}} -->
<div class="wp-block-group readerly-book-card">' . readerly_cover_block( $book['cover'] ) . '

' . readerly_paragraph_block( esc_html( $book['eyebrow'] ), 'is-style-eyebrow' ) . '

' . readerly_heading_block( '<a href="#">' . esc_html( $book['title'] ) . '</a>' ) . '

' . readerly_paragraph_block( esc_html( $book['excerpt'] ), 'readerly-excerpt' ) . '

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="#">' . esc_html__( 'Buy', 'readerly' ) . '</a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="#">' . esc_html__( 'Learn more', 'readerly' ) . '</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->';
	}
endif;

if ( ! function_exists( 'readerly_shelf_section' ) ) :
	/**
	 * A section holding one shelf of compact book cards.
	 *
	 * @param string $heading Section heading.
	 * @param string $note    Short note beside the heading.
	 * @param array  $books   Books as accepted by readerly_compact_card().
	 * @return string
	 */
	function readerly_shelf_section( $heading, $note, $books ) {
		$cards = array_map( 'readerly_compact_card', $books );
		return '<!-- wp:group {"tagName":"section","align":"full","className":"readerly-section","layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull readerly-section"><!-- wp:group {"align":"wide","className":"readerly-section-head","layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
<div class="wp-block-group alignwide readerly-section-head"><!-- wp:heading -->
<h2 class="wp-block-heading">' . esc_html( $heading ) . '</h2>
<!-- /wp:heading -->

' . readerly_paragraph_block( esc_html( $note ) ) . '</div>
<!-- /wp:group -->

<!-- wp:group {"align":"wide","className":"readerly-shelf","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"top"}} -->
<div class="wp-block-group alignwide readerly-shelf">' . implode( "\n\n", $cards ) . '</div>
<!-- /wp:group --></section>
<!-- /wp:group -->';
	}
endif;

if ( ! function_exists( 'readerly_sample_shelves' ) ) :
	/**
	 * Sample books for the reading log shelves, keyed by shelf.
	 *
	 * @return array
	 */
	function readerly_sample_shelves() {
		$read = array(
			array( 'salt-orchard', __( 'The Salt Orchard', 'readerly' ), __( 'Mira Hollis', 'readerly' ), 4.5 ),
			array( 'paper-moths', __( 'A House of Paper Moths', 'readerly' ), __( 'Celia Marr', 'readerly' ), 4.5 ),
			array( 'fieldwork', __( 'Fieldwork', 'readerly' ), __( 'Dara Quell', 'readerly' ), 5 ),
			array( 'glass-cartographer', __( 'The Glass Cartographer', 'readerly' ), __( 'Ravi Anand-Moss', 'readerly' ), 4 ),
			array( 'northern-ledger', __( 'Northern Ledger', 'readerly' ), __( 'Tomas Arvid', 'readerly' ), 4 ),
			array( 'winter-pears', __( 'Winter Pears', 'readerly' ), __( 'Lotte Brenner', 'readerly' ), 4.5 ),
			array( 'under-the-tamarind', __( 'Under the Tamarind', 'readerly' ), __( 'Yemi Adesanya-Cole', 'readerly' ), 4 ),
			array( 'quiet-arithmetic', __( 'The Quiet Arithmetic', 'readerly' ), __( 'Jun Sato-Bell', 'readerly' ), 3.5 ),
		);
		$want = array(
			array( 'fen-lights', __( 'Fen Lights', 'readerly' ), __( 'Mira Hollis', 'readerly' ) ),
			array( 'bell-foundry', __( 'The Bell Foundry', 'readerly' ), __( 'Isobel Thorne', 'readerly' ) ),
			array( 'brightwater-recipe-box', __( 'The Brightwater Recipe Box', 'readerly' ), __( 'Polly Fenwick', 'readerly' ) ),
			array( 'atlas-of-lost-rivers', __( 'Atlas of Lost Rivers', 'readerly' ), __( 'Samir Haddad-Lowe', 'readerly' ) ),
		);
		$dnf  = array(
			array( 'long-way-to-halden', __( 'The Long Way to Halden', 'readerly' ), __( 'Ivo Petrak', 'readerly' ) ),
			array( 'small-hours', __( 'Small Hours', 'readerly' ), __( 'Wren Atherton', 'readerly' ) ),
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
