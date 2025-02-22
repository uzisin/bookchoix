<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package BookChoix WordPress theme
 */

$settings = acmthemes_settings();

// Return if full width or full screen
$hide_sidebar = acmthemes_is_fullscreen_page();
if ( $hide_sidebar && ! is_shop() ) {
	return;
}

if ( ! is_active_sidebar( 'sidebar' ) )
	return;

if( is_page() && !empty( $settings['page_layout'] ) && $settings['page_layout'] == 'fullwidth' ) {
	return;
}

$sidebar_class = 'sidebar-right';

$is_woo_active = acmthemes_is_woocommerce_activated();

if( $is_woo_active ) {

	if( is_shop() || is_product() ) {

		if( !empty( $settings['shop_layout'] ) && $settings['shop_layout'] == 'fullwidth' ) {
			return;
		}

		if( !empty( $settings['shop_layout'] ) && $settings['shop_layout'] == 'left' ) {
			$sidebar_class = 'sidebar-left';
		}

		elseif( !empty( $settings['shop_layout'] ) && $settings['shop_layout'] == 'right' ) {
			$sidebar_class = 'sidebar-right';
		}

		else {
			$sidebar_class = 'sidebar-left';
		}

	}

	if( is_product_category() && !empty( $settings['shop_archive_layout'] ) && $settings['shop_archive_layout'] == 'fullwidth' ) {
		return;
	}

}

if( is_home() || is_singular('post') ) {

	$post_id      = acmthemes_post_id();

	//show/hide sidebar for invidual post
	$indv_post_layout = get_post_meta( $post_id, 'this_post_layout', true );
	if( !empty( $indv_post_layout ) && $indv_post_layout == 'fullwidth' ) {
		return;
	}

	//sidebar position for invidual post
	if( !empty( $indv_post_layout ) && $indv_post_layout == 'left' ) {
		$sidebar_class = 'sidebar-left';
	}
	elseif( !empty( $indv_post_layout ) && $indv_post_layout == 'right' ) {
		$sidebar_class = 'sidebar-right';
	}
	//blogwide sidebar position
	elseif( !empty( $settings['blog_layout'] ) && $settings['blog_layout'] == 'fullwidth' ) {
		return;
	}
	elseif( !empty( $settings['blog_layout'] ) && $settings['blog_layout'] == 'left' ) {
		$sidebar_class = 'sidebar-left';
	}
	elseif( !empty( $settings['blog_layout'] ) && $settings['blog_layout'] == 'right' ) {
		$sidebar_class = 'sidebar-right';
	}
	else {
		$sidebar_class = 'sidebar-left';
	}

}

?>
<?php do_action( 'before_sidebar' ); ?>

<aside id="sidebar" class="widget-area <?php echo wp_kses_post( $sidebar_class ); ?>" <?php acmthemes_schema_markup( 'sidebar' ); ?>>

	<?php do_action( 'before_sidebar_inner' ); ?>

	<div id="sidebar-inner" class="clr">

		<?php
		if ( $sidebar = acmthemes_get_sidebar() ) {
			dynamic_sidebar( $sidebar );
		} ?>

	</div><!-- #sidebar-inner -->

	<?php do_action( 'after_sidebar_inner' ); ?>

</aside><!-- #right-sidebar -->

<?php do_action( 'after_sidebar' ); ?>
