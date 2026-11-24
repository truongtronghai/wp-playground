<?php
/**
 * Ticker settings screen and activation default.
 *
 * Registers the Consultia Ticker settings page (Settings → Consultia Ticker)
 * using the WordPress Settings API, plus the activation-time default seeding
 * of the ticker option.
 *
 * @package Consultia_Ticker
 */

defined( 'ABSPATH' ) || exit;

/**
 * Seed the ticker option with the default market names on plugin activation.
 *
 * Only sets the option when it does not already exist, so editors' saved
 * values survive reactivation.
 *
 * @return void
 */
function consultia_ticker_activation() {
	if ( false === get_option( CONSULTIA_TICKER_OPTION, false ) ) {
		add_option(
			CONSULTIA_TICKER_OPTION,
			array( 'Nasdaq', 'New York Stock Exchange', 'OTC Markets' )
		);
	}
}

/**
 * Register the ticker iteration as a settings group and section.
 */
function consultia_ticker_register_settings() {
	register_setting(
		'consultia_ticker_group',
		CONSULTIA_TICKER_OPTION,
		array(
			'type'              => 'array',
			'description'       => __( 'Ordered list of market names shown in the ticker.', 'consultia-ticker' ),
			'sanitize_callback' => 'consultia_ticker_sanitize_items',
			'default'           => array(),
		)
	);

	add_settings_section(
		'consultia_ticker_section',
		__( 'Ticker items', 'consultia-ticker' ),
		'consultia_ticker_section_intro',
		'consultia-ticker'
	);

	add_settings_field(
		'consultia_ticker_items_field',
		__( 'Market names', 'consultia-ticker' ),
		'consultia_ticker_items_field',
		'consultia-ticker',
		'consultia_ticker_section'
	);
}
add_action( 'admin_init', 'consultia_ticker_register_settings' );

/**
 * Add the settings page under Settings.
 */
function consultia_ticker_add_options_page() {
	add_options_page(
		__( 'Consultia Ticker', 'consultia-ticker' ),
		__( 'Consultia Ticker', 'consultia-ticker' ),
		'manage_options',
		'consultia-ticker',
		'consultia_ticker_render_settings_page'
	);
}
add_action( 'admin_menu', 'consultia_ticker_add_options_page' );

/**
 * Render the intro text for the ticker settings section.
 */
function consultia_ticker_section_intro() {
	echo '<p>' . esc_html__( 'One market name per line. Changes apply to every page that uses the [consultia_ticker] shortcode.', 'consultia-ticker' ) . '</p>';
}

/**
 * Render the market-names textarea from the saved option.
 */
function consultia_ticker_items_field() {
	$items = get_option( CONSULTIA_TICKER_OPTION, array() );

	if ( ! is_array( $items ) ) {
		$items = array();
	}

	$field_name = CONSULTIA_TICKER_OPTION . '[]';

	printf(
		'<textarea name="%1$s" id="consultia-ticker-items" rows="8" class="large-text" aria-describedby="consultia-ticker-help">%2$s</textarea>',
		esc_attr( $field_name ),
		esc_textarea( implode( "\n", array_map( 'sanitize_text_field', $items ) ) )
	);

	echo '<p class="description" id="consultia-ticker-help">' . esc_html__( 'Leave the list empty to hide the ticker entirely.', 'consultia-ticker' ) . '</p>';
}

/**
 * Sanitize the ticker item list on save.
 *
 * Drops empty items and non-scalar entries, and passes every value through
 * sanitize_text_field (which trims and strips tags/line breaks).
 *
 * @param mixed $items Raw value submitted for CONSULTIA_TICKER_OPTION.
 * @return array<int, string>
 */
function consultia_ticker_sanitize_items( $items ) {
	if ( ! is_array( $items ) ) {
		return array();
	}

	$clean = array();

	foreach ( $items as $item ) {
		if ( is_array( $item ) ) {
			continue;
		}

		$item = sanitize_text_field( (string) $item );

		if ( '' !== $item ) {
			$clean[] = $item;
		}
	}

	return $clean;
}

/**
 * Render the Consultia Ticker settings page.
 */
function consultia_ticker_render_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Consultia Ticker', 'consultia-ticker' ); ?></h1>
		<form action="options.php" method="post">
			<?php
			settings_fields( 'consultia_ticker_group' );
			do_settings_sections( 'consultia-ticker' );
			submit_button();
			?>
		</form>
	</div>
	<?php
}