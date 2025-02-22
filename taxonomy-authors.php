<?php
/**
 * Page to display Authors content
*/
//get options framework settings
$settings = acmthemes_settings();

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

					$author_taxonamy = $wp_query->get_queried_object();
					$term_id = $author_taxonamy->term_id;
					$author_name = $author_taxonamy->name;
					$image_id = get_term_meta( $term_id, 'author_picture', true );
					$author_picture = wp_get_attachment_image_src( $image_id, 'thumbnail' );
					$author_bio = get_term_meta( $term_id, 'bks_author_bio', true );
					$author_title = get_term_meta( $term_id, 'bks_author_bio', true );

					?>
					<div class="author-profile">
						<div class="author-picture">
							<?php
							if( !empty($author_picture[0]) ) {
								echo '<img alt="'. $author_name .'" src="' . $author_picture[0] . '">';
							}
							else {
								echo '<span class="icon-user"></span>';
							}
							  ?>
						</div>
						<div class="author-name"><?php echo esc_html($author_name); ?>
						<div class="author-title"><?php echo term_description(); ?></div>
					</div>						
						<div class="author-description">
							<?php echo wp_kses_post($author_bio); ?>
						</div>
					</div>

					<?php woocommerce_product_loop_start(); ?>

					<?php
					/** 
					 * enable custom wp_Query in case the website doesn't read taxonomy author page 
					 * @since 1.6.0
					 * **/
					if( isset($settings['authors_books_wp_query']) && 1 == $settings['authors_books_wp_query'] ) {
						$args = array(
							'post_type' => 'product',
							'tax_query' => array(
								array(
									'taxonomy' => 'authors',
									'field' => 'slug',
									'terms' => $author_taxonamy->slug,
								),
							),
							'posts_per_page' =>	-1,
						);
						$query = new WP_Query( $args );					  
						
						if ( $query->have_posts() ) :
						
							while ( $query->have_posts() ) :
								$query->the_post();
								wc_get_template( 'content-product.php' );
							endwhile; 	
						
						endif;
						
						wp_reset_postdata();

					} else {

					while ( have_posts() ) : the_post();

					wc_get_template( 'content-product.php' );

					endwhile;

					}

				// No posts found
				else : 
					// Display no post found notice
					get_template_part( 'partials/none' ); 
					
				endif; ?>

				<?php do_action( 'after_content_inner' ); ?>

				<?php woocommerce_product_loop_end(); ?>

			</div><!-- #content -->

			<?php do_action( 'after_content' ); ?>

		</div><!-- #primary -->

		<?php do_action( 'after_primary' ); ?>

	</div><!-- #content-wrap -->

	<?php do_action( 'after_content_wrap' ); ?>

<?php get_footer(); ?>
