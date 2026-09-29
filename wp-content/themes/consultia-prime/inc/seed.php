<?php
/**
 * Seeding the four pages and their two menus.
 *
 * The reference is four static pages. This creates the same four as
 * editable pages, filled from the patterns in inc/patterns.php, and the
 * two menus the header and footer navigate with.
 *
 * It is idempotent and safe to re-run. A page is rewritten only if this
 * function wrote it before — the `_consultia_prime_seeded` marker records
 * that — so a page an editor has since changed by hand is left alone
 * unless $force is true. Menus are rebuilt from scratch each run, since
 * navigation is site chrome rather than content.
 *
 * It is a function, not a hook. Nothing here runs on a page load; call it
 * deliberately, from wp-cli, a REST route or an execute-php harness.
 *
 * @package Consultia_Prime
 */

defined( 'ABSPATH' ) || exit;

/**
 * Create or update the four landing pages.
 *
 * Block comments are HTML comments, and kses strips comments on save for
 * a user without unfiltered_html. kses is lifted for the insert and
 * restored immediately after, so the sanitisation WordPress applies to
 * everything else still runs.
 *
 * @param bool $force Rewrite pages even if they carry edits.
 * @return array Slug => post ID.
 */
function consultia_prime_seed_pages( $force = false ) {
	$ids = array();

	foreach ( consultia_prime_pages() as $slug => $page ) {
		list( $title, $content ) = $page;

		$existing = get_page_by_path( $slug );
		$is_seeded = $existing instanceof WP_Post
			&& '1' === get_post_meta( $existing->ID, '_consultia_prime_seeded', true );

		if ( $existing instanceof WP_Post && $is_seeded && ! $force ) {
			$ids[ $slug ] = $existing->ID;
			continue;
		}

		kses_remove_filters();

		$post_id = wp_insert_post(
			array(
				'ID'           => $existing instanceof WP_Post ? $existing->ID : 0,
				'post_title'   => $title,
				'post_name'    => $slug,
				'post_content' => $content,
				'post_status'  => 'publish',
				'post_type'    => 'page',
				'post_author'  => consultia_prime_author_id(),
				'menu_order'   => array_search( $slug, array_keys( consultia_prime_pages() ), true ),
			),
			true
		);

		kses_init_filters();

		if ( is_wp_error( $post_id ) ) {
			continue;
		}

		update_post_meta( $post_id, '_consultia_prime_seeded', '1' );
		$ids[ $slug ] = $post_id;
	}

	return $ids;
}

/**
 * The user the seeded pages are authored by.
 *
 * @return int User ID.
 */
function consultia_prime_author_id() {
	$admins = get_users(
		array(
			'role'   => 'administrator',
			'number' => 1,
			'fields' => array( 'ID' ),
		)
	);

	return empty( $admins ) ? 1 : (int) $admins[0]->ID;
}

/**
 * Build the two menus the header and footer use.
 *
 * Rebuilt on every run: a menu is navigation, not content, and the
 * reference fixes exactly which links exist and in what order.
 *
 * @param array $ids Slug => post ID, as returned by the seeder.
 * @return array Location => menu ID.
 */
function consultia_prime_seed_menus( $ids ) {
	$locations = array();

	// Primary: the four pages, in the reference's order.
	$primary = consultia_prime_replace_menu( __( 'Primary', 'consultia-prime' ) );

	foreach ( array( 'home', 'about', 'services', 'contact' ) as $slug ) {
		if ( ! isset( $ids[ $slug ] ) ) {
			continue;
		}

		consultia_prime_add_menu_item(
			$primary,
			array(
				'menu-item-title'     => get_the_title( $ids[ $slug ] ),
				'menu-item-object'    => 'page',
				'menu-item-object-id' => $ids[ $slug ],
				'menu-item-type'      => 'post_type',
				'menu-item-status'    => 'publish',
			)
		);
	}

	$locations['primary'] = $primary;

	// Footer: the services list, which points into the services page.
	$footer = consultia_prime_replace_menu( __( 'Footer Services', 'consultia-prime' ) );

	$services_url = isset( $ids['services'] ) ? get_permalink( $ids['services'] ) : home_url( '/services/' );

	$links = array(
		__( 'Organizational improvement', 'consultia-prime' ) => $services_url . '#services',
		__( 'Business analysis', 'consultia-prime' ) => $services_url . '#services',
		__( 'Capital markets', 'consultia-prime' ) => $services_url . '#markets',
	);

	foreach ( $links as $label => $url ) {
		consultia_prime_add_menu_item(
			$footer,
			array(
				'menu-item-title'  => $label,
				'menu-item-url'    => $url,
				'menu-item-type'   => 'custom',
				'menu-item-status' => 'publish',
			)
		);
	}

	$locations['footer-services'] = $footer;

	set_theme_mod( 'nav_menu_locations', $locations );

	return $locations;
}

/**
 * Find or create a menu by name, and empty it.
 *
 * @param string $name Menu name.
 * @return int Menu term ID.
 */
function consultia_prime_replace_menu( $name ) {
	$menu = wp_get_nav_menu_object( $name );

	if ( $menu ) {
		foreach ( wp_get_nav_menu_items( $menu->term_id ) as $item ) {
			wp_delete_post( $item->ID, true );
		}

		return (int) $menu->term_id;
	}

	$created = wp_create_nav_menu( $name );

	return is_wp_error( $created ) ? 0 : (int) $created;
}

/**
 * Add one item to a menu, if that menu exists.
 *
 * wp_update_nav_menu_item() takes a menu term ID of 0 to mean "unattached",
 * so a failure to create the menu has to be caught here. Silently writing
 * unattached items would leave orphan rows in the posts table that no menu
 * lists, and the location would render empty with no sign of why.
 *
 * @param int    $menu Menu term ID.
 * @param array  $args Menu item arguments.
 * @return int Item ID, or 0 on failure.
 */
function consultia_prime_add_menu_item( $menu, $args ) {
	if ( $menu < 1 ) {
		return 0;
	}

	$item = wp_update_nav_menu_item( $menu, 0, $args );

	return is_wp_error( $item ) ? 0 : (int) $item;
}

/**
 * Seed everything, and put the home page on the front.
 *
 * @param bool $force Rewrite pages even if they carry edits.
 * @return array Summary of what was written.
 */
function consultia_prime_seed( $force = false ) {
	$ids = consultia_prime_seed_pages( $force );

	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $ids['home'] );

	$menus = consultia_prime_seed_menus( $ids );

	return array(
		'pages' => $ids,
		'menus' => $menus,
		'front' => $ids['home'],
	);
}
