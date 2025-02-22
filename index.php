<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme and one of the
 * two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * For example, it puts together the home page when no home.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package BookChoix WordPress theme
 */

get_header();

$settings = acmthemes_settings();

?>
<?php do_action( 'before_content_wrap' ); ?>

	<div id="content-wrap" class="main-content-wrap blog-wrap blog-content-wrap container clr">

		<?php do_action( 'before_primary' ); ?>

		<div id="primary" class="<?php echo acmthemes_blog_content_area_class(); ?> clr">

			<?php do_action( 'before_content' ); ?>

			<div id="content" class="site-content clr">

				<?php do_action( 'before_content_inner' ); ?>

				<?php
				// Check if posts exist
				if ( have_posts() ) :

					// Elementor `archive` location
					if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'archive' ) ) {

						// Add Support For EDD Archive Pages
						if ( is_post_type_archive( 'download' ) || is_tax( array( 'download_category', 'download_tag' ) ) ) {

							do_action( 'before_archive_download' ); ?>

							<div class="acmthemes-row <?php echo esc_attr( acmthemes_edd_loop_classes() ); ?>">
								<?php
								// Archive Post Count for clearing float
								$acmthemes_count = 0;
								while ( have_posts() ) : the_post();
									$acmthemes_count++;
									get_template_part( 'partials/edd/archive' );
									if ( acmthemes_edd_entry_columns() == $acmthemes_count ) {
										$acmthemes_count=0;
									}
								endwhile; ?>
							</div>

							<?php
							do_action( 'after_archive_download' );

						}  else { ?>

							<?php
							// Get post layout
							$blog_col_layout = ( ! empty( $settings['blog_col_layout'] ) ) ? intval( $settings['blog_col_layout'] ) : 1;
							if ( $blog_col_layout > 1 || isset( $_GET['blog_col'] ) && $_GET['blog_col'] == 'multi' ) {
								get_template_part( 'partials/entry/columns' );
							}
							else {
								get_template_part( 'partials/entry/default' );
							} ?>

						<?php
						// Display post pagination
						acmthemes_blog_pagination();
						}
					} ?>

				<?php
				// No posts found
				else : ?>

					<?php
					// Display no post found notice
					get_template_part( 'partials/none' ); ?>

				<?php endif; ?>

				<?php do_action( 'after_content_inner' ); ?>

			</div><!-- #content -->

			<?php do_action( 'after_content' ); ?>

		</div><!-- #primary -->

		<?php get_sidebar(); ?>

		<?php do_action( 'after_primary' ); ?>

	</div><!-- #content-wrap -->

	<?php do_action( 'after_content_wrap' ); ?>

<?php get_footer(); ?>
