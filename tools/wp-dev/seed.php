<?php
/**
 * Loads Bookshelf demo content into a local WordPress: review posts, pages built
 * from the theme's patterns, a primary menu and a few comments.
 *
 * Run through wp-cli: wp eval-file seed.php. Every item is looked up by slug and
 * rewritten from the current patterns, so rerunning converges to the same site.
 */

if ( ! defined( 'WP_CLI' ) ) {
	exit( 1 );
}

function bookshelf_seed_pattern( $slug ) {
	$pattern = WP_Block_Patterns_Registry::get_instance()->get_registered( $slug );
	if ( ! $pattern ) {
		WP_CLI::error( "Pattern $slug is not registered." );
	}
	$blocks = resolve_pattern_blocks( parse_blocks( $pattern['content'] ) );
	return serialize_blocks( $blocks );
}

function bookshelf_seed_cover( $cover ) {
	$existing = get_posts(
		array(
			'post_type'   => 'attachment',
			'post_status' => 'inherit',
			'meta_key'    => '_bookshelf_demo_cover',
			'meta_value'  => $cover,
			'fields'      => 'ids',
		)
	);
	if ( $existing ) {
		return $existing[0];
	}
	$uploads = wp_upload_dir();
	$target  = $uploads['path'] . "/$cover.svg";
	if ( ! copy( get_template_directory() . "/assets/images/covers/$cover.svg", $target ) ) {
		WP_CLI::error( "Could not copy cover $cover.svg to $target." );
	}
	$id = wp_insert_attachment(
		array(
			'post_title'     => $cover,
			'post_mime_type' => 'image/svg+xml',
			'post_status'    => 'inherit',
		),
		$target
	);
	update_post_meta( $id, '_bookshelf_demo_cover', $cover );
	wp_update_attachment_metadata(
		$id,
		array(
			'width'  => 400,
			'height' => 600,
			'file'   => _wp_relative_upload_path( $target ),
			'sizes'  => array(),
		)
	);
	return $id;
}

function bookshelf_seed_post( $args ) {
	$existing = get_page_by_path( $args['post_name'], OBJECT, $args['post_type'] );
	$args     = array_merge( array( 'post_status' => 'publish' ), $args );
	if ( $existing ) {
		$args['ID'] = $existing->ID;
		return wp_update_post( wp_slash( $args ), true );
	}
	return wp_insert_post( wp_slash( $args ), true );
}

function bookshelf_seed_term( $name, $taxonomy ) {
	$term = term_exists( $name, $taxonomy );
	if ( ! $term ) {
		$term = wp_insert_term( $name, $taxonomy );
	}
	if ( is_wp_error( $term ) ) {
		WP_CLI::error( $term );
	}
	return (int) $term['term_id'];
}

update_option( 'blogname', 'Marginalia' );
update_option( 'blogdescription', 'Book notes & reviews by Nora Vale' );
update_option( 'show_on_front', 'posts' );
update_option( 'posts_per_page', 8 );

$admin = get_user_by( 'login', 'admin' );
if ( ! $admin ) {
	WP_CLI::error( 'User "admin" not found. Install WordPress through wp-dev.sh.' );
}
wp_update_user(
	array(
		'ID'           => $admin->ID,
		'display_name' => 'Nora Vale',
		'first_name'   => 'Nora',
		'last_name'    => 'Vale',
		'description'  => 'Nora is a former school librarian who reads about sixty books a year and writes about the ones that stay with her. She lives in a small flat above a bakery, which is either very good or very bad for reading.',
	)
);

wp_update_term( 1, 'category', array( 'name' => 'Literary fiction', 'slug' => 'literary-fiction', 'description' => 'Novels where not much happens and everything changes. Family sagas, quiet grief, small towns and big inner lives.' ) );

$reviews = array(
	array( 'the-salt-orchard', 'The Salt Orchard', 'salt-orchard', 'Literary fiction', array( 'Tidewater series', 'Historical' ), '2026-11-04', 'A grieving fruit scientist inherits a failing orchard on a salt-burned coast, along with a ledger that seems to predict every bad harvest. Hollis writes weather the way other novelists write villains.' ),
	array( 'a-house-of-paper-moths', 'A House of Paper Moths', 'paper-moths', 'Fantasy', array(), '2026-10-28', 'A bookbinder’s apprentice discovers that the moths eating the library’s oldest folios are rewriting them. Strange, tender and very funny.' ),
	array( 'fieldwork', 'Fieldwork', 'fieldwork', 'Essays', array( 'Five stars' ), '2026-10-21', 'Twelve essays on walking the same three miles of farmland for a year. The best nature writing I’ve read in a decade, and the least preachy.' ),
	array( 'the-glass-cartographer', 'The Glass Cartographer', 'glass-cartographer', 'Science fiction', array(), '2026-10-14', 'A mapmaker on a generation ship charts corridors that keep moving. Big ideas, small kindnesses, and an ending that earns its hope.' ),
	array( 'northern-ledger', 'Northern Ledger', 'northern-ledger', 'Mystery', array(), '2026-10-07', 'A tax inspector, a frozen harbour town and a set of accounts that don’t add up. A slow-burn mystery with a wonderfully dry narrator.' ),
	array( 'winter-pears', 'Winter Pears', 'winter-pears', 'Poetry', array(), '2026-09-30', 'Short, plain-spoken poems about a mother’s last winter. I read it in one sitting and then again, slowly, with a pencil.' ),
	array( 'under-the-tamarind', 'Under the Tamarind', 'under-the-tamarind', 'Historical', array(), '2026-09-23', 'Three generations of a Lagos family across one tree’s lifetime. Sprawling, generous and full of food you can almost taste.' ),
	array( 'the-quiet-arithmetic', 'The Quiet Arithmetic', 'quiet-arithmetic', 'Literary fiction', array(), '2026-09-16', 'A retired maths teacher counts her way through a year of small losses. Beautifully written, if a little too restrained for me.' ),
	array( 'letters-to-the-lighthouse', 'Letters to the Lighthouse', 'lighthouse-letters', 'Literary fiction', array( 'Epistolary' ), '2026-09-09', 'A novel in letters between a lighthouse keeper and the widow who writes to him by mistake. Gentle, witty, and sad in all the right places.' ),
	array( 'the-tide-ledger', 'The Tide Ledger', 'tide-ledger', 'Literary fiction', array( 'Tidewater series' ), '2026-03-18', 'The first Tidewater book: an estuary town, a drowned archive and a clerk who keeps finding her own name in it.' ),
);

$review_content = bookshelf_seed_pattern( 'bookshelf/review-post' );
foreach ( $reviews as $review ) {
	list( $slug, $title, $cover, $category, $tags, $date, $excerpt ) = $review;
	$id = bookshelf_seed_post(
		array(
			'post_type'     => 'post',
			'post_name'     => $slug,
			'post_title'    => $title,
			'post_excerpt'  => $excerpt,
			'post_content'  => $review_content,
			'post_date'     => "$date 09:00:00",
			'post_author'   => $admin->ID,
			'post_category' => array( bookshelf_seed_term( $category, 'category' ) ),
		)
	);
	if ( is_wp_error( $id ) ) {
		WP_CLI::error( $id );
	}
	wp_set_post_tags( $id, $tags );
	set_post_thumbnail( $id, bookshelf_seed_cover( $cover ) );
}

$orchard = get_page_by_path( 'the-salt-orchard', OBJECT, 'post' );
update_option( 'sticky_posts', array( $orchard->ID ) );
if ( ! get_comments( array( 'post_id' => $orchard->ID, 'count' => true ) ) ) {
	$first = wp_insert_comment( array( 'comment_post_ID' => $orchard->ID, 'comment_author' => 'Hannah M.', 'comment_author_email' => 'hannah@example.com', 'comment_content' => '“Writes weather the way other novelists write villains” is exactly it. The storm chapter had me checking my own windows. I agree about the ending, though; it felt like a different book for twenty pages.', 'comment_date' => '2026-11-04 19:12:00', 'comment_approved' => 1 ) );
	wp_insert_comment( array( 'comment_post_ID' => $orchard->ID, 'comment_parent' => $first, 'user_id' => $admin->ID, 'comment_author' => 'Nora Vale', 'comment_author_email' => 'admin@example.com', 'comment_content' => 'A different book for twenty pages, yes! I keep wondering whether an editor asked for more resolution.', 'comment_date' => '2026-11-04 21:40:00', 'comment_approved' => 1 ) );
	wp_insert_comment( array( 'comment_post_ID' => $orchard->ID, 'comment_author' => 'Owen Brierley', 'comment_author_email' => 'owen@example.com', 'comment_content' => 'Does it really work without the first book? I bounced off The Tide Ledger halfway through.', 'comment_date' => '2026-11-05 08:03:00', 'comment_approved' => 1 ) );
}

$pages = array(
	array( 'author-home', 'Author home', 'bookshelf/page-author-home', 'page-landing' ),
	array( 'reading-log', 'Reading log', 'bookshelf/page-reading-log', 'page-landing' ),
	array( 'about', 'About', 'bookshelf/page-about', '' ),
	array( 'contact', 'Contact', 'bookshelf/page-contact', '' ),
);
$page_ids = array();
foreach ( $pages as $page ) {
	list( $slug, $title, $pattern, $template ) = $page;
	$id = bookshelf_seed_post(
		array(
			'post_type'      => 'page',
			'post_name'      => $slug,
			'post_title'     => $title,
			'post_content'   => bookshelf_seed_pattern( $pattern ),
			'post_author'    => $admin->ID,
			'comment_status' => 'closed',
		)
	);
	if ( is_wp_error( $id ) ) {
		WP_CLI::error( $id );
	}
	update_post_meta( $id, '_wp_page_template', $template );
	$page_ids[ $slug ] = $id;
}

$sample = get_page_by_path( 'sample-page', OBJECT, 'page' );
if ( $sample ) {
	wp_delete_post( $sample->ID, true );
}
$hello = get_page_by_path( 'hello-world', OBJECT, 'post' );
if ( $hello ) {
	wp_delete_post( $hello->ID, true );
}

$link = function ( $label, $url, $page_id = 0 ) {
	$attrs = array( 'label' => $label, 'url' => $url );
	if ( $page_id ) {
		$attrs += array( 'type' => 'page', 'id' => $page_id, 'kind' => 'post-type' );
	}
	return '<!-- wp:navigation-link ' . wp_json_encode( $attrs, JSON_UNESCAPED_SLASHES ) . ' /-->';
};
$menu = implode(
	"\n",
	array(
		'<!-- wp:home-link {"label":"Reviews"} /-->',
		$link( 'Reading log', get_permalink( $page_ids['reading-log'] ), $page_ids['reading-log'] ),
		$link( 'Author home', get_permalink( $page_ids['author-home'] ), $page_ids['author-home'] ),
		$link( 'About', get_permalink( $page_ids['about'] ), $page_ids['about'] ),
	)
);
$navigation = bookshelf_seed_post(
	array(
		'post_type'    => 'wp_navigation',
		'post_name'    => 'primary',
		'post_title'   => 'Primary',
		'post_content' => $menu,
	)
);
if ( is_wp_error( $navigation ) ) {
	WP_CLI::error( $navigation );
}

WP_CLI::success( sprintf( 'Seeded %d reviews, %d pages and the primary menu.', count( $reviews ), count( $pages ) ) );
