<?php
/**
 * 404 template.
 *
 * @package Consultia_Prime
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main id="main">
	<div class="wrap page-main">
		<span class="eyebrow"><?php esc_html_e( '404', 'consultia-prime' ); ?></span>

		<h1><?php esc_html_e( 'That page is not here.', 'consultia-prime' ); ?></h1>

		<p class="lede"><?php esc_html_e( 'The address may have changed, or the page may never have existed. The links below are the ones that do exist.', 'consultia-prime' ); ?></p>

		<div class="hero__actions">
			<a class="btn btn--primary" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back to the front page', 'consultia-prime' ); ?></a>
			<?php if ( current_user_can( 'edit_posts' ) ) : ?>
				<a class="btn btn--ghost" href="<?php echo esc_url( admin_url( 'post-new.php' ) ); ?>"><?php esc_html_e( 'Write a page', 'consultia-prime' ); ?></a>
			<?php endif; ?>
		</div>
	</div>
</main>

<?php
get_footer();
