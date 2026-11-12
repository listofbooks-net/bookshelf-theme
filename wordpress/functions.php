<?php
/**
 * Readerly functions and definitions.
 *
 * @package Readerly
 */

require_once get_template_directory() . '/inc/book-cards.php';

if ( ! function_exists( 'readerly_setup' ) ) :
	/**
	 * Loads the stylesheet into the editor so blocks look the same there as on the site.
	 */
	function readerly_setup() {
		add_editor_style( 'style.css' );
	}
endif;
add_action( 'after_setup_theme', 'readerly_setup' );

if ( ! function_exists( 'readerly_enqueue_styles' ) ) :
	/**
	 * Enqueues the theme stylesheet on the front end.
	 */
	function readerly_enqueue_styles() {
		wp_enqueue_style(
			'readerly-style',
			get_parent_theme_file_uri( 'style.css' ),
			array(),
			wp_get_theme()->get( 'Version' )
		);
	}
endif;
add_action( 'wp_enqueue_scripts', 'readerly_enqueue_styles' );

if ( ! function_exists( 'readerly_register_pattern_categories' ) ) :
	/**
	 * Registers one pattern category for each way to use the theme.
	 */
	function readerly_register_pattern_categories() {
		register_block_pattern_category(
			'readerly-book-blog',
			array(
				'label'       => __( 'Readerly: Book blog', 'readerly' ),
				'description' => __( 'Sections for a book review blog.', 'readerly' ),
			)
		);
		register_block_pattern_category(
			'readerly-author-site',
			array(
				'label'       => __( 'Readerly: Author site', 'readerly' ),
				'description' => __( 'Sections and pages for an author website.', 'readerly' ),
			)
		);
		register_block_pattern_category(
			'readerly-reading-log',
			array(
				'label'       => __( 'Readerly: Reading log', 'readerly' ),
				'description' => __( 'Shelves, book cards and reading statistics.', 'readerly' ),
			)
		);
	}
endif;
add_action( 'init', 'readerly_register_pattern_categories' );

if ( ! function_exists( 'readerly_exclude_featured_post' ) ) :
	/**
	 * Keeps the featured review out of the blog's post grid.
	 *
	 * The featured card shows the newest sticky post. The blog index would list
	 * that post again, so it is left out of the main query, and any other sticky
	 * posts are listed by date like the rest. Once the owner deletes the card
	 * from the Blog Home template, the post is listed normally again.
	 *
	 * @param WP_Query $query The query being set up.
	 */
	function readerly_exclude_featured_post( $query ) {
		if ( is_admin() || ! $query->is_main_query() || ! $query->is_home() ) {
			return;
		}
		$home = get_block_template( get_stylesheet() . '//home', 'wp_template' );
		if ( ! $home || ( false === strpos( $home->content, 'readerly/featured-review' ) && false === strpos( $home->content, 'readerly-feature' ) ) ) {
			return;
		}
		$sticky = get_option( 'sticky_posts' );
		if ( empty( $sticky ) ) {
			return;
		}
		$featured = get_posts(
			array(
				'post__in'            => $sticky,
				'posts_per_page'      => 1,
				'orderby'             => 'date',
				'order'               => 'DESC',
				'fields'              => 'ids',
				'ignore_sticky_posts' => true,
				'no_found_rows'       => true,
			)
		);
		if ( empty( $featured ) ) {
			return;
		}
		$query->set( 'post__not_in', array_merge( (array) $query->get( 'post__not_in' ), $featured ) );
		$query->set( 'ignore_sticky_posts', true );
	}
endif;
add_action( 'pre_get_posts', 'readerly_exclude_featured_post' );

if ( ! function_exists( 'readerly_hide_first_page_only_blocks' ) ) :
	/**
	 * Hides groups that carry the readerly-first-page-only class on pages 2 and up
	 * of a paginated list, so the featured card is not repeated.
	 *
	 * @param string $content The rendered block.
	 * @param array  $block   The parsed block.
	 * @return string The block, or an empty string on later pages.
	 */
	function readerly_hide_first_page_only_blocks( $content, $block ) {
		$class_name = isset( $block['attrs']['className'] ) ? (string) $block['attrs']['className'] : '';
		if ( is_paged() && false !== strpos( $class_name, 'readerly-first-page-only' ) ) {
			return '';
		}
		return $content;
	}
endif;
add_filter( 'render_block_core/group', 'readerly_hide_first_page_only_blocks', 10, 2 );
