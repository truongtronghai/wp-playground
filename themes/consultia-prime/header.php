<?php
/**
 * Site header.
 *
 * The structure is the reference's: the mark, then a .nav-links band
 * holding the primary navigation and the single primary action.
 *
 * One deliberate addition: the reference hides the navigation outright
 * below 980px, which leaves a phone visitor with no way to reach the
 * other pages. A toggle is rendered in the same band instead, and
 * assets/nav.js opens and closes it. assets/styles.css gives it
 * display:none above the breakpoint, so on a desktop it is inert and
 * the header matches the reference exactly.
 *
 * @package Consultia_Prime
 */

defined( 'ABSPATH' ) || exit;

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link" href="#main"><?php esc_html_e( 'Skip to content', 'consultia-prime' ); ?></a>

<header class="site-header">
	<div class="wrap">
		<a class="mark" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( sprintf( /* translators: %s: site name. */ __( '%s, home', 'consultia-prime' ), get_bloginfo( 'name' ) ) ); ?>">
			<?php echo consultia_prime_logo_plate(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- returns escaped markup. ?>
		</a>

		<div class="nav-links">
			<button
				class="nav-toggle"
				type="button"
				aria-expanded="false"
				aria-controls="site-nav"
				data-nav-toggle
			><?php esc_html_e( 'Menu', 'consultia-prime' ); ?></button>

			<nav class="site-nav" id="site-nav" aria-label="<?php esc_attr_e( 'Primary', 'consultia-prime' ); ?>">
				<?php consultia_prime_primary_nav(); ?>
			</nav>

			<a class="btn btn--solid" href="<?php echo esc_url( consultia_prime_url( 'contact' ) ); ?>"><?php esc_html_e( 'Start a conversation', 'consultia-prime' ); ?></a>
		</div>
	</div>
</header>
