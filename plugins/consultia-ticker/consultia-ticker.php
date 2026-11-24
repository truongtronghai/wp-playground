<?php
/**
 * Plugin Name:       Consultia Ticker
 * Description:       Reusable decorative markets ticker for the Consultia theme. Embed it on any page or post with the [consultia_ticker] shortcode; editors manage the market-name list under Settings → Consultia Ticker.
 * Version:           1.0.0
 * Requires at least: 6.0
 * Requires PHP:      8.1
 * Author:            Consultia
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       consultia-ticker
 *
 * @package Consultia_Ticker
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'CONSULTIA_TICKER_OPTION' ) ) {
	define( 'CONSULTIA_TICKER_OPTION', 'consultia_ticker_items' );
}

require plugin_dir_path( __FILE__ ) . 'includes/settings.php';
require plugin_dir_path( __FILE__ ) . 'includes/shortcode.php';

/**
 * Register the [consultia_ticker] shortcode.
 */
function consultia_ticker_register_shortcode() {
	add_shortcode( 'consultia_ticker', 'consultia_ticker_shortcode' );
}
add_action( 'init', 'consultia_ticker_register_shortcode' );

/**
 * Seed the ticker option with the default market names on activation.
 *
 * Uses add_option() so an existing (previously edited) value is never
 * overwritten by re-activating the plugin.
 */
register_activation_hook( __FILE__, 'consultia_ticker_activation' );