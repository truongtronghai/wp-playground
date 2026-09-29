<?php
/**
 * Site footer.
 *
 * The structure is the reference's: a .foot-top band holding the brand
 * plate and two navigation columns, then a .foot-bottom line.
 *
 * The brand plate holds the site name rather than a logo file. The
 * reference points at assets/consultia-logo.png, which is not in the
 * repository; assets/styles.css sizes the plate to sit where the image
 * would have. Drop a Custom Logo into wp-admin and it can replace the
 * text without any change here.
 *
 * Both navigation columns are menus, so they are edited in Appearance
 * → Menus rather than in a template. "Site" renders the primary menu,
 * which keeps it in step with the header by construction. "Services" is
 * its own location because the reference's second column is a different
 * list, not a copy of the first.
 *
 * @package Consultia_Prime
 */

defined( 'ABSPATH' ) || exit;

?>
<footer class="site-footer">
	<div class="wrap">
		<div class="foot-top">
			<div class="foot-brand">
				<?php echo consultia_prime_logo_plate(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- returns escaped markup. ?>
				<p><?php esc_html_e( 'Solid commitment. Reliable experience. Professional services and capital-markets advisory for growing companies.', 'consultia-prime' ); ?></p>
			</div>

			<div class="foot-nav">
				<div>
					<h4><?php esc_html_e( 'Site', 'consultia-prime' ); ?></h4>
					<?php consultia_prime_footer_nav( 'primary' ); ?>
				</div>

				<div>
					<h4><?php esc_html_e( 'Services', 'consultia-prime' ); ?></h4>
					<?php
					consultia_prime_footer_nav( 'footer-services', 'footer-services' );
					?>
				</div>
			</div>
		</div>

		<div class="foot-bottom">
			<span class="mono">
				<?php
				printf(
					/* translators: 1: current year, 2: site name. */
					esc_html__( '© %1$s %2$s', 'consultia-prime' ),
					esc_html( gmdate( 'Y' ) ),
					esc_html( get_bloginfo( 'name' ) )
				);
				?>
			</span>
			<span class="mono"><?php esc_html_e( 'Nasdaq · NYSE · OTC Markets', 'consultia-prime' ); ?></span>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
