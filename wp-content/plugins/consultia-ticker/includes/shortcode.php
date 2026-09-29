<?php
/**
 * [consultia_ticker] shortcode renderer.
 *
 * Renders the decorative markets ticker using the theme's `.ticker` and
 * `.ticker-track` classes (the theme owns all styling — this plugin emits no
 * CSS or JS). Market names are repeated 4× server-side, matching the static
 * reference, so the theme's translateX(-50%) keyframe loop is a seamless
 * marquee.
 *
 * @package Consultia_Ticker
 */

defined( 'ABSPATH' ) || exit;

/**
 * Render the [consultia_ticker] shortcode.
 *
 * @param array|string $atts Shortcode attributes (unused; the plugin has a
 *                           single global content source).
 * @return string
 */
function consultia_ticker_shortcode( $atts = array() ) {
	$items = get_option( CONSULTIA_TICKER_OPTION, array() );

	if ( ! is_array( $items ) ) {
		$items = array();
	}

	$items = array_values( array_filter( $items, 'is_string' ) );

	if ( empty( $items ) ) {
		// Empty option: render nothing (no errors, no placeholder).
		return '';
	}

	$track = '';

	// Repeat the full set 4× server-side to feed the seamless marquee loop.
	for ( $repeat = 0; $repeat < 4; $repeat++ ) {
		foreach ( $items as $item ) {
			$track .= '<span>' . esc_html( $item ) . '</span>';
		}
	}

	return '<div class="ticker" aria-hidden="true"><div class="ticker-track">' . $track . '</div></div>';
}