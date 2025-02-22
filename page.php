<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that other
 * 'pages' on your WordPress site will use a different template.
 *
 * @package BookChoix WordPress theme
 */

get_header();

$settings = acmthemes_settings();
?>

	<?php do_action( 'before_content_wrap' ); ?>

	<div id="content-wrap" class="page-content container clr">

		<?php do_action( 'before_primary' ); ?>

		<div id="primary" class="<?php echo acmthemes_page_content_area_class(); ?> clr">

			<?php do_action( 'before_content' ); ?>

			<div id="content" class="site-content clr">

				<?php do_action( 'before_content_inner' ); ?>

				<?php
				// Elementor `single` location
				if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'single' ) ) {

					// Start loop
					while ( have_posts() ) : the_post();

						get_template_part( 'partials/page/layout' );

					endwhile;

				} ?>

				<?php do_action( 'after_content_inner' ); ?>

			</div><!-- #content -->

			<?php do_action( 'after_content' ); ?>

		</div><!-- #primary -->

		<?php do_action( 'after_primary' ); ?>

	</div><!-- #content-wrap -->

	<?php do_action( 'after_content_wrap' ); ?>

<?php get_footer(); ?>
