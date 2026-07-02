<?php
/**
 * Fallback template for a single event.
 *
 * This template exists so page builders such as Elementor can edit event
 * pages: it calls the_content() within the loop, which is what Elementor needs
 * to locate the content area. Themes may override it by providing their own
 * single-event.php or a simple-events/single-event.php file.
 *
 * @package SimpleEvents
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<div id="primary" class="content-area simple-events-single">
	<main id="main" class="site-main" role="main">

		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'simple-events-single-event' ); ?>>

				<header class="entry-header">
					<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
				</header>

				<?php if ( has_post_thumbnail() ) : ?>
					<div class="simple-events-single-thumbnail">
						<?php the_post_thumbnail( 'large' ); ?>
					</div>
				<?php endif; ?>

				<div class="entry-content">
					<?php
					// IMPORTANT: the_content() must be called here so Elementor
					// (and other builders) can render/edit this event.
					the_content();

					wp_link_pages(
						array(
							'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'simple-events' ),
							'after'  => '</div>',
						)
					);
					?>
				</div>

				<?php
				$event_terms = get_the_term_list( get_the_ID(), Simple_Events_CPT::TAXONOMY, '', ', ' );
				if ( $event_terms && ! is_wp_error( $event_terms ) ) :
					?>
					<footer class="entry-footer">
						<span class="simple-events-single-categories">
							<?php
							/* translators: %s: list of event categories. */
							printf( esc_html__( 'Categories: %s', 'simple-events' ), wp_kses_post( $event_terms ) );
							?>
						</span>
					</footer>
				<?php endif; ?>

			</article>

			<?php
			// Comments, if the theme/site enables them for events.
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}
			?>

		<?php endwhile; ?>

	</main>
</div>

<?php
get_sidebar();
get_footer();
