<?php
/**
 * The site front page.
 *
 * The page is editor-managed: its sections are blocks authored from the theme
 * patterns in inc/patterns.php (hero, overview, services, closing call).
 * The theme contributes the shell and the ticker, which
 * consultia_prime_inject_ticker() places directly below the hero.
 *
 * The hero carries the only h1, so the page title is never printed here.
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
	endwhile;
	?>
</main>

<?php
get_footer();
