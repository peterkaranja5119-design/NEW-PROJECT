<?php
/**
 * Fallback template for a single AOSARS event.
 *
 * This template exists so page builders such as Elementor can edit event pages.
 * The critical line is the_content() inside the loop: Elementor locates the
 * content area by detecting that the_content() runs while the template renders.
 * Some themes never call the_content() for custom post types, which triggers
 * Elementor's "you must call the the_content function in the current template"
 * notice — this template resolves that.
 *
 * Themes may override it by providing their own single-aosars_event.php or a
 * aosars-events/single-aosars_event.php file. Disable it entirely with:
 *   add_filter( 'aosev_use_single_template', '__return_false' );
 *
 * @package AOSARS_Events
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

get_header();
?>

<div id="primary" class="content-area aosev-single">
	<main id="main" class="site-main" role="main">

		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'aosev-single-event' ); ?>>

				<header class="entry-header">
					<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
				</header>

				<div class="entry-content">
					<?php
					// REQUIRED for Elementor / page builders — do not remove.
					// The plugin's the_content filter appends the event app here
					// (unless the page is built with Elementor).
					the_content();
					?>
				</div>

			</article>
			<?php
		endwhile;
		?>

	</main>
</div>

<?php
get_footer();
