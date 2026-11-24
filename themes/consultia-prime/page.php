<?php
/**
 * Single page template.
 *
 * A thin shell, the same shape as front-page.php. A page owns its own
 * layout: each section is a Group set to render as a <section>, and each
 * carries its own .wrap, exactly as the reference's pages do.
 *
 * The page title is deliberately not printed here. The reference gives
 * every inner page a .page-hero with the h1 inside it, and the seeded
 * pages carry that section, so printing the title as well would put two
 * h1 elements on the page. A page authored later gets its heading from
 * the "Page hero" pattern in the inserter, which is where the reference
 * puts it.
 *
 * @package Consultia_Prime
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main id="main">
	<?php
	while ( have_posts() ) :
		the_post();
		the_content();

		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'consultia-prime' ),
				'after'  => '</div>',
			)
		);
	endwhile;
	?>
</main>

<?php
get_footer();
