<?php
/**
 * The template for displaying the footer.
 *
 * @package BookChoix WordPress theme
 */ ?>

        </main><!-- #main -->

        <?php do_action( 'after_main' ); ?>

        <?php do_action( 'before_footer' ); ?>

        <?php
        // Elementor `footer` location
        if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'footer' ) ) { ?>

            <?php do_action( 'footer' ); ?>

        <?php } ?>

        <?php do_action( 'after_footer' ); ?>

    </div><!-- #wrap -->

    <?php do_action( 'after_wrap' ); ?>

</div><!-- #outer-wrap -->

<?php do_action( 'after_outer_wrap' ); ?>

<?php
  //scroll top button
  get_template_part( 'partials/scroll-top' ); ?>

<?php
// Search overlay style
if ( 'overlay' == acmthemes_menu_search_style() ) {
    get_template_part( 'partials/header/search-overlay' );
}

/**
* do_action on footer for custom functions above wp footer
*/
$arg1 = $arg2 = "";
do_action( 'above_wp_footer', $arg1, $arg2 );

wp_footer(); ?>
</body>
</html>
