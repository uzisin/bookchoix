<?php
/**
 * Page to display Publishers content
*/

get_header(); ?>

	<?php do_action( 'before_content_wrap' ); ?>

	<div id="content-wrap" class="main-content-wrap author-archive-content-wrap container clr">

		<?php do_action( 'before_primary' ); ?>

		<div id="primary" class="page-full-width clr">

			<?php do_action( 'before_content' ); ?>

			<div id="content" class="site-content clr">

				<?php do_action( 'before_content_inner' ); ?>

				<?php

				// Check if posts exist
				if ( have_posts() ) :

					$publisher_taxonamy = $wp_query->get_queried_object();
					$term_id = $publisher_taxonamy->term_id;
					$publisher_name = $publisher_taxonamy->name;
					$image_id = get_term_meta( $term_id, 'publisher_picture', true );
					$author_picture = wp_get_attachment_image_src( $image_id, 'thumbnail' );
					$author_bio = get_term_meta( $term_id, 'bks_publisher_bio', true );
					?>

					<div class="author-profile">
						<div class="author-picture">
							<?php
							if( !empty($author_picture[0]) ) {
								echo '<img alt="'. $publisher_name .'" src="' . $author_picture[0] . '">';
							}
							else {
								echo '<span class="icon-user"></span>';
							}
							  ?>
						</div>
						<div class="author-name"><?php echo esc_html($publisher_name); ?></div>
						<div class="author-description">
							<?php echo wp_kses_post($author_bio); ?>
						</div>
					</div>

					<?php woocommerce_product_loop_start(); ?>

					<?php

          while ( have_posts() ) : the_post();

          wc_get_template( 'content-product.php' );

          endwhile;

				// No posts found
				else : ?>

					<?php
					// Display no post found notice
					get_template_part( 'partials/none' ); ?>

				<?php endif; ?>

				<?php do_action( 'after_content_inner' ); ?>

				<?php woocommerce_product_loop_end(); ?>

			</div><!-- #content -->

			<?php do_action( 'after_content' ); ?>

		</div><!-- #primary -->

		<?php do_action( 'after_primary' ); ?>

	</div><!-- #content-wrap -->

	<?php do_action( 'after_content_wrap' ); ?>

<?php get_footer(); ?>
