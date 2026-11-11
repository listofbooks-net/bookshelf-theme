<?php
/**
 * Switches the site's global styles to a Bookshelf style variation.
 * Usage: wp eval-file set-style.php <paper|lamplight>
 */

$choice = $args[0] ?? 'paper';
$config = array(
	'version'                     => WP_Theme_JSON::LATEST_SCHEMA,
	'isGlobalStylesUserThemeJSON' => true,
);
if ( 'paper' !== $choice ) {
	$match = null;
	foreach ( WP_Theme_JSON_Resolver::get_style_variations() as $variation ) {
		if ( strtolower( $variation['title'] ) === $choice ) {
			$match = $variation;
		}
	}
	if ( ! $match ) {
		WP_CLI::error( "No style variation named $choice." );
	}
	$config['settings'] = $match['settings'] ?? array();
	$config['styles']   = $match['styles'] ?? array();
}

$admins = get_users( array( 'role' => 'administrator', 'number' => 1 ) );
if ( ! $admins ) {
	WP_CLI::error( 'No administrator user found.' );
}
wp_set_current_user( $admins[0]->ID );
$post_id = WP_Theme_JSON_Resolver::get_user_global_styles_post_id();
$result  = wp_update_post(
	array(
		'ID'           => $post_id,
		'post_content' => wp_slash( wp_json_encode( $config ) ),
	),
	true
);
if ( is_wp_error( $result ) ) {
	WP_CLI::error( $result );
}
WP_Theme_JSON_Resolver::clean_cached_data();
WP_CLI::success( "Global styles set to $choice." );
