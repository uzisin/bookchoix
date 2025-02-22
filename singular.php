 <?php
/**
 * The template for displaying all single posts and attachments
 *
 * @package BookChoix WordPress theme
 */

get_header();

//get theme settings
$settings = acmthemes_settings();

//get post id
$post_id = acmthemes_post_id();

//get post format
$get_post_format = get_post_format( $post_id );

//get date format
$blog_date_format = get_option('date_format');

//get sidebar setting
$show_sidebar = false;
if( isset( $settings['show_blog_sidebar'] ) && $settings['show_blog_sidebar'] == 1 ) {
	$show_sidebar = true;
}

?>

	<?php do_action( 'before_content_wrap' ); ?>

	<div id="content-wrap" class="main-content-wrap blog-wrap blog-single-wrap container clr">

		<?php do_action( 'before_primary' ); ?>

		<div id="primary" class="<?php echo acmthemes_singular_content_area_class(); ?> clr">

			<?php do_action( 'before_content' ); ?>

			<div id="content" class="site-content clr">

				<?php do_action( 'before_content_inner' ); ?>

        <?php while ( have_posts() ) : the_post(); ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

              <header class="entry-header">

                <span class="post-categories">
        					<?php
        					$categories = get_the_category();
        					if ( ! empty( $categories ) ) {
        						 echo '<a href="' . esc_url( get_category_link( $categories[0]->term_id ) ) . '">' . esc_html( $categories[0]->name ) . '</a>';
        					}
        					?>
        				</span>

                <?php
                  if( $get_post_format != 'quote' ) :
                      if ( is_single() ) :
                        the_title( '<h1 class="entry-title">', '</h1>' );
                      else :
                        the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
                      endif;
                  endif;
                      ?>

                    <div class="post-meta">
                        <span class="author"><?php echo esc_html__('By', 'bookchoix'); ?> <?php esc_url( the_author_posts_link() ); ?></span>
        								<?php if( ! empty( get_the_date() ) ) { ?> <?php echo esc_html__('on', 'bookchoix'); ?> <span class="post-date"><?php echo get_the_date(); ?></span> <?php } ?>
                        <?php if( has_tag() ){ ?>
                        <span class="separator">|</span>
                        <span class="tags"><i class="fa fa-tag"></i> <?php the_tags( '', ', ' ); ?></span>
                        <?php } ?>
                    </div>

                </header><!-- .entry-header -->

                <?php if( has_post_thumbnail() ) : ?>
                <div class="featured-media">
                  <div class="featured-image">
                <?php

                  if( $show_sidebar )
                    the_post_thumbnail('bookchoix_blog_thumb', array('class' => 'img-responsive post-thumbnail'));
                  else
                    the_post_thumbnail('full', array('class' => 'img-responsive post-full-image post-thumbnail'));

                ?>
                  </div>
                </div>
              <?php endif; ?>

                <div class="entry-content clr">
                    <div class="post-content">
                      <?php
                         the_content();
                          wp_link_pages( array(
                                  'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'bookchoix' ) . '</span>',
                                  'after'       => '</div>',
                                  'link_before' => '<span>',
                                  'link_after'  => '</span>',
                                  'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'bookchoix' ) . ' </span>%',
                                  'separator'   => '<span class="screen-reader-text">, </span>',
                          ) );
                        ?>
                    </div>
                </div><!-- .entry-content -->

                <?php if(!is_attachment()) { ?>
                <footer class="entry-footer clr">

                  <div class="adjac-posts clr">
                    <div class="prev-post-link">
                      <?php acmthemes_prev_post(true); //if true post title will be shown ?>
                    </div>
                    <div class="next-post-link">
                      <?php acmthemes_next_post(true); //if true post title will be shown ?>
                    </div>
                  </div>
                </footer>
              <?php } ?>


            </article><!-- #post-## -->

            <?php if(!empty($settings['show_author_bio']) && !empty(get_the_author_meta('description'))) {
                $author_id = get_the_author_meta('ID');
                $author_name = get_the_author_meta('first_name');
                if( !empty($author_name) ) {
                    $author_dis_name = get_the_author_meta('first_name') . " " . get_the_author_meta('last_name');
                }
                else {
                    $author_dis_name = get_the_author_meta('display_name') ;
                }
                ?>
            <div class="author-bio">
              <div class="acm-row clr">
                  <div class="acm-col-sm-2">
                      <?php echo get_avatar( $author_id, 100, '', $author_dis_name ); ?>
                  </div>
                  <div class="acm-col-sm-10">
                      <h3 class="author-name"><a href="<?php echo esc_url(get_author_posts_url($author_id)); ?>"><?php echo esc_html($author_dis_name); ?></a></h3>
                      <div class="author-info">
                          <?php echo get_the_author_meta('description'); ?>
                      </div>
                  </div>
              </div>
            </div>
            <?php } ?>

            <?php get_template_part( 'partials/single/related-posts' ); ?>

            <?php
                    // If comments are open or we have at least one comment, load up the comment template
                    if ( comments_open() || '0' != get_comments_number() )
                            comments_template();
            ?>
            <?php endwhile; // end of the loop. ?>

				<?php do_action( 'after_content_inner' ); ?>

			</div><!-- #content -->

			<?php do_action( 'after_content' ); ?>

		</div><!-- #primary -->

    <?php get_sidebar(); ?>

		<?php do_action( 'after_primary' ); ?>

	</div><!-- #content-wrap -->

	<?php do_action( 'after_content_wrap' ); ?>

<?php get_footer(); ?>
