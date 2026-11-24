<?php
/**
 * Single post template.
 *
 * @package Consultia_Prime
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main id="main">
	<div class="wrap page-main">
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<article <?php post_class(); ?>>
				<h1><?php the_title(); ?></h1>

				<time class="mono" datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>

				<div class="entry-content">
					<?php
					the_content(
						sprintf(
							/* translators: %s: post title, for screen readers. */
							__( 'Continue reading %s', 'consultia-prime' ),
							'<span class="screen-reader-text">' . esc_html( get_the_title() ) . '</span>'
						)
					);

					wp_link_pages(
						array(
							'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'consultia-prime' ),
							'after'  => '</div>',
						)
					);
					?>
				</div>
			</article>
			<?php

			the_post_navigation(
				array(
					'prev_text' => '<span class="mono">' . esc_html__( 'Previous', 'consultia-prime' ) . '</span> %title',
					'next_text' => '<span class="mono">' . esc_html__( 'Next', 'consultia-prime' ) . '</span> %title',
				)
			);
		endwhile;
		?>
	</div>
</main>

<?php
get_footer();
