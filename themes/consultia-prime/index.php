<?php
/**
 * Fallback archive template.
 *
 * Used for the blog index, category and tag archives, search results, and any
 * request that does not match a more specific template. Renders real content
 * rather than an empty shell.
 *
 * @package Consultia_Prime
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main id="main">
	<div class="wrap page-main">
		<?php if ( is_search() ) : ?>
			<h1><?php printf( esc_html__( 'Results for: %s', 'consultia-prime' ), esc_html( get_search_query() ) ); ?></h1>
		<?php elseif ( is_archive() ) : ?>
			<h1><?php the_archive_title(); ?></h1>
		<?php else : ?>
			<h1><?php esc_html_e( 'Writing', 'consultia-prime' ); ?></h1>
		<?php endif; ?>

		<?php if ( have_posts() ) : ?>
			<ul class="post-list">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<li>
						<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
					</li>
					<?php
				endwhile;
				?>
			</ul>

			<?php
			the_posts_pagination(
				array(
					'mid_size'  => 2,
					'prev_text' => __( 'Previous', 'consultia-prime' ),
					'next_text' => __( 'Next', 'consultia-prime' ),
				)
			);
			?>
		<?php else : ?>
			<p class="lede"><?php esc_html_e( 'Nothing published here yet.', 'consultia-prime' ); ?></p>
		<?php endif; ?>
	</div>
</main>

<?php
get_footer();
