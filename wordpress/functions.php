<?php
/**
 * Bookshelf functions and definitions.
 *
 * @package Bookshelf
 */

require_once get_template_directory() . '/inc/book-cards.php';

if ( ! function_exists( 'bookshelf_setup' ) ) :
	/**
	 * Loads the stylesheet into the editor so blocks look the same there as on the site.
	 */
	function bookshelf_setup() {
		add_editor_style( 'style.css' );
	}
endif;
add_action( 'after_setup_theme', 'bookshelf_setup' );

if ( ! function_exists( 'bookshelf_enqueue_styles' ) ) :
	/**
	 * Enqueues the theme stylesheet on the front end.
	 */
	function bookshelf_enqueue_styles() {
		wp_enqueue_style(
			'bookshelf-style',
			get_stylesheet_uri(),
			array(),
			wp_get_theme()->get( 'Version' )
		);
	}
endif;
add_action( 'wp_enqueue_scripts', 'bookshelf_enqueue_styles' );

if ( ! function_exists( 'bookshelf_register_pattern_categories' ) ) :
	/**
	 * Registers one pattern category for each way to use the theme.
	 */
	function bookshelf_register_pattern_categories() {
		register_block_pattern_category(
			'bookshelf-book-blog',
			array(
				'label'       => __( 'Bookshelf: Book blog', 'bookshelf' ),
				'description' => __( 'Sections for a book review blog.', 'bookshelf' ),
			)
		);
		register_block_pattern_category(
			'bookshelf-author-site',
			array(
				'label'       => __( 'Bookshelf: Author site', 'bookshelf' ),
				'description' => __( 'Sections and pages for an author website.', 'bookshelf' ),
			)
		);
		register_block_pattern_category(
			'bookshelf-reading-log',
			array(
				'label'       => __( 'Bookshelf: Reading log', 'bookshelf' ),
				'description' => __( 'Shelves, book cards and reading statistics.', 'bookshelf' ),
			)
		);
	}
endif;
add_action( 'init', 'bookshelf_register_pattern_categories' );
