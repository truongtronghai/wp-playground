<?php
/**
 * Consultia Prime theme setup.
 *
 * @package Consultia_Prime
 */

defined( 'ABSPATH' ) || exit;

define( 'CONSULTIA_PRIME_VERSION', '1.0.0' );

require get_template_directory() . '/inc/patterns.php';
require get_template_directory() . '/inc/seed.php';

/**
 * Set up theme supports and navigation.
 */
function consultia_prime_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' )
	);

	register_nav_menus(
		array(
			'primary'         => __( 'Primary Menu', 'consultia-prime' ),
			'footer-services' => __( 'Footer Menu: Services', 'consultia-prime' ),
		)
	);

	// A Custom Logo replaces the wordmark in the header plate. The reference
	// points at an image that is not in the repository, so the plate holds the
	// site name and is sized to sit where the image would have; uploading a
	// logo swaps it without a change to any template.
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 38,
			'width'       => 150,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	// The front page is blocks styled by the theme's own class names (.hero,
	// .service-grid, .markets), and none of that lives in core. Both halves
	// matter: add_editor_style() alone only reaches the classic editor, while
	// the block editor canvas reads the same files through
	// get_block_editor_theme_styles(), which core gates behind this support
	// flag. Without it the sections open unstyled in the editor.
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/fonts.css' );
	add_editor_style( 'assets/styles.css' );
}
add_action( 'after_setup_theme', 'consultia_prime_setup' );

/**
 * Enqueue the theme stylesheets.
 *
 * The theme is the single source of styling: the consultia-ticker plugin ships
 * no CSS of its own and relies on the rules in assets/styles.css.
 *
 * The stylesheet loads after `wp-block-library` on purpose: the front page is
 * built from blocks (see inc/patterns.php), and the theme's rules must win over
 * core's defaults for the same element.
 *
 * Fonts are self-hosted (assets/fonts/*.woff2) rather than loaded from a Google
 * Fonts <link>, so no third-party request is made on page view. The five files
 * are the exact latin cuts the reference's own <link> to fonts.googleapis.com
 * returns, byte-for-byte, so the faces rendered are the faces it asks for.
 */
function consultia_prime_scripts() {
	$base = get_template_directory_uri();

	wp_enqueue_style(
		'consultia-prime-fonts',
		$base . '/assets/fonts.css',
		array(),
		consultia_prime_asset_version( 'assets/fonts.css' )
	);

	wp_enqueue_style(
		'consultia-prime-styles',
		$base . '/assets/styles.css',
		array( 'consultia-prime-fonts', 'wp-block-library' ),
		consultia_prime_asset_version( 'assets/styles.css' )
	);

	// The nav toggle is progressive enhancement, so it is not deferred to
	// render-blocking: it is tiny and only matters below 980px.
	wp_enqueue_script(
		'consultia-prime-nav',
		$base . '/assets/nav.js',
		array(),
		consultia_prime_asset_version( 'assets/nav.js' ),
		true
	);
}
add_action( 'wp_enqueue_scripts', 'consultia_prime_scripts' );

/**
 * Render the consultia-ticker plugin surface.
 *
 * The plugin emits .ticker / .ticker-track and no CSS of its own; the theme
 * owns every rule that styles them. Returns an empty string when the plugin is
 * inactive, so the front page never breaks without it.
 *
 * @return string
 */
function consultia_prime_ticker() {
	if ( ! shortcode_exists( 'consultia_ticker' ) ) {
		return '';
	}

	return do_shortcode( '[consultia_ticker]' );
}

/**
 * Place the ticker directly below the hero, on the front page only.
 *
 * The front page is editor-managed, but the ticker stays part of the layout:
 * the theme decides where it goes. It is appended after the first block whose
 * class list contains `hero`, which is the hero section in the theme's pattern
 * set. Filtering render_block leaves the stored page content untouched, so a
 * re-save in the editor can never duplicate or drop the ticker.
 *
 * @param string $block_content Rendered block HTML.
 * @param array  $block         Parsed block.
 * @return string
 */
function consultia_prime_inject_ticker( $block_content, $block ) {
	static $placed = false;

	if ( $placed || is_admin() || ! is_front_page() || ! in_the_loop() || ! is_main_query() ) {
		return $block_content;
	}

	if ( empty( $block['attrs']['className'] ) ) {
		return $block_content;
	}

	if ( ! in_array( 'hero', preg_split( '/\s+/', trim( $block['attrs']['className'] ) ), true ) ) {
		return $block_content;
	}

	$placed = true;

	return $block_content . consultia_prime_ticker();
}
add_filter( 'render_block', 'consultia_prime_inject_ticker', 10, 2 );

/**
 * Build a cache-busting version for a theme asset.
 *
 * Uses the file modification time when the file is readable so editing a token
 * is visible without hand-bumping a version string, and falls back to the theme
 * version otherwise.
 *
 * @param string $relative_path Path relative to the theme root.
 * @return string
 */
function consultia_prime_asset_version( $relative_path ) {
	$path = get_template_directory() . '/' . ltrim( $relative_path, '/' );

	if ( is_readable( $path ) ) {
		$mtime = filemtime( $path );

		if ( $mtime ) {
			return (string) $mtime;
		}
	}

	return CONSULTIA_PRIME_VERSION;
}

/**
 * Render the primary navigation.
 *
 * Falls back to a page list when no menu is assigned, so the header is never
 * empty on a fresh install.
 */
function consultia_prime_primary_nav() {
	if ( has_nav_menu( 'primary' ) ) {
		wp_nav_menu(
			array(
				'theme_location' => 'primary',
				'container'      => false,
				'menu_class'     => 'nav-menu',
				'fallback_cb'    => false,
				'depth'          => 1,
				'item_spacing'   => 'discard',
			)
		);

		return;
	}

	// No menu assigned: surface the published pages so the nav is usable.
	$pages = get_pages(
		array(
			'sort_column' => 'menu_order,post_title',
			'number'      => 4,
		)
	);

	if ( empty( $pages ) ) {
		return;
	}

	echo '<ul class="nav-menu">';
	foreach ( $pages as $page ) {
		printf(
			'<li><a href="%1$s">%2$s</a></li>',
			esc_url( get_permalink( $page->ID ) ),
			esc_html( $page->post_title )
		);
	}
	echo '</ul>';
}

/**
 * Render one footer navigation column.
 *
 * The footer holds two columns and the reference's are different lists, so
 * each is its own menu location. A location with no menu assigned renders
 * nothing rather than a fallback list: a stray column of page links in the
 * footer would be worse than an absent column.
 *
 * @param string $location Registered menu location.
 */
function consultia_prime_footer_nav( $location ) {
	if ( ! has_nav_menu( $location ) ) {
		return;
	}

	wp_nav_menu(
		array(
			'theme_location' => $location,
			'container'      => false,
			'menu_class'     => 'footer-menu',
			'fallback_cb'    => false,
			'depth'          => 1,
			'item_spacing'   => 'discard',
		)
	);
}

/**
 * The mark in the header: a Custom Logo when one is set, the wordmark otherwise.
 *
 * The reference holds an <img> inside the plate. There is no logo file in the
 * repository, so the plate carries the site name and assets/styles.css sizes it
 * to occupy the space the image was given. Uploading a Custom Logo in wp-admin
 * replaces it with no change here.
 *
 * @return string Plate markup.
 */
function consultia_prime_logo_plate() {
	if ( has_custom_logo() ) {
		return get_custom_logo();
	}

	return '<span class="logo-plate">' . esc_html( get_bloginfo( 'name' ) ) . '</span>';
}
