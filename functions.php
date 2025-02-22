<?php
/**
 * Theme functions and definitions.
 *
 * @package BookChoix WordPress theme
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Core Constants
define( 'ACMTHEMES_THEME_DIR', get_template_directory() );
define( 'ACMTHEMES_THEME_URI', get_template_directory_uri() );

/*
**  acmthemes main class
*/
final class ACMTHEMES_THEME_CLASS {

	/**
	 * Main Theme Class Constructor
	 *
	 * @since   1.0.0
	 */
	public function __construct() {

		// Define constants
		add_action( 'after_setup_theme', array( 'ACMTHEMES_THEME_CLASS', 'constants' ), 0 );

		// Load all core theme function files
		add_action( 'after_setup_theme', array( 'ACMTHEMES_THEME_CLASS', 'include_functions' ), 1 );

		// Load configuration classes
		add_action( 'after_setup_theme', array( 'ACMTHEMES_THEME_CLASS', 'configs' ), 4 );

		// Load framework classes
		add_action( 'after_setup_theme', array( 'ACMTHEMES_THEME_CLASS', 'classes' ), 5 );

		// Setup theme
		add_action( 'after_setup_theme', array( 'ACMTHEMES_THEME_CLASS', 'theme_setup' ), 10 );

		//add mobile menu in top of header
		add_action('before_outer_wrap', array( 'ACMTHEMES_THEME_CLASS', 'bookchoix_add_mobile_menu'), 5);

		// register sidebar widget areas
		add_action( 'widgets_init', array( 'ACMTHEMES_THEME_CLASS', 'register_sidebars' ) );

		/** Admin only actions **/
		if ( is_admin() ) {

			// Load scripts in the WP admin
			add_action( 'admin_enqueue_scripts', array( 'ACMTHEMES_THEME_CLASS', 'admin_scripts' ) );

		/** Non Admin actions **/
		} else {

			// Load theme CSS
			add_action( 'wp_enqueue_scripts', array( 'ACMTHEMES_THEME_CLASS', 'theme_css' ) );

			// Load theme js
			add_action( 'wp_enqueue_scripts', array( 'ACMTHEMES_THEME_CLASS', 'theme_js' ) );

			// Add a pingback url auto-discovery header for singularly identifiable articles
			add_action( 'wp_head', array( 'ACMTHEMES_THEME_CLASS', 'pingback_header' ), 1 );

			// Add meta viewport tag to header
			add_action( 'wp_head', array( 'ACMTHEMES_THEME_CLASS', 'meta_viewport' ), 1 );

			// Add an X-UA-Compatible header
			add_filter( 'wp_headers', array( 'ACMTHEMES_THEME_CLASS', 'x_ua_compatible_headers' ) );

			// Alter the search posts per page
			add_action( 'pre_get_posts', array( 'ACMTHEMES_THEME_CLASS', 'search_posts_per_page' ) );

			// Add a responsive wrapper to the WordPress oembed output
			add_filter( 'embed_oembed_html', array( 'ACMTHEMES_THEME_CLASS', 'add_responsive_wrap_to_oembeds' ), 99, 4 );

			// Adds classes the post class
			add_filter( 'post_class', array( 'ACMTHEMES_THEME_CLASS', 'post_class' ) );

			// Add schema markup to the authors post link
			add_filter( 'the_author_posts_link', array( 'ACMTHEMES_THEME_CLASS', 'the_author_posts_link' ) );

			// Add support for Elementor Pro locations
			add_action( 'elementor/theme/register_locations', array( 'ACMTHEMES_THEME_CLASS', 'register_elementor_locations' ) );

			// Remove the default lightbox script for the beaver builder plugin
			add_filter( 'fl_builder_override_lightbox', array( 'ACMTHEMES_THEME_CLASS', 'remove_bb_lightbox' ) );

		}

	}

	/**
	 * Define Constants
	 *
	 * @since   1.0.0
	 */
	public static function constants() {

		$version = self::theme_version();

		// Theme version
		define( 'ACMTHEMES_THEME_VERSION', $version );

		// Javascript and CSS Paths
		define( 'ACMTHEMES_JS_DIR_URI', ACMTHEMES_THEME_URI .'/assets/js/' );
		define( 'ACMTHEMES_CSS_DIR_URI', ACMTHEMES_THEME_URI .'/assets/css/' );

		// Include Paths
		define( 'ACMTHEMES_INC_DIR', ACMTHEMES_THEME_DIR .'/inc/' );
		define( 'ACMTHEMES_INC_DIR_URI', ACMTHEMES_THEME_URI .'/inc/' );

		// Check if plugins are active
		define( 'ACMTHEMES_ELEMENTOR_ACTIVE', class_exists( 'Elementor\Plugin' ) );
		define( 'ACMTHEMES_BEAVER_BUILDER_ACTIVE', class_exists( 'FLBuilder' ) );
		define( 'ACMTHEMES_WOOCOMMERCE_ACTIVE', class_exists( 'WooCommerce' ) );
		define( 'ACMTHEMES_EDD_ACTIVE', class_exists( 'Easy_Digital_Downloads' ) );
		define( 'ACMTHEMES_LIFTERLMS_ACTIVE', class_exists( 'LifterLMS' ) );
		define( 'ACMTHEMES_ALNP_ACTIVE', class_exists( 'Auto_Load_Next_Post' ) );
		define( 'ACMTHEMES_LEARNDASH_ACTIVE', class_exists( 'SFWD_LMS' ) );
	}

	/**
	 * Load all core theme function files
	 *
	 * @since 1.0.0
	 */
	public static function include_functions() {
		$dir = ACMTHEMES_INC_DIR;
		require_once ( $dir .'helpers.php' );
		require_once ( $dir .'header-content.php' );
		require_once ( $dir .'walker/init.php' );
		require_once ( $dir .'walker/menu-walker.php' );
	}

	/**
	 * Configs for 3rd party plugins.
	 *
	 * @since 1.0.0
	 */
	public static function configs() {

		$dir = ACMTHEMES_INC_DIR;

		//load theme configurations file
		require_once ( $dir .'theme-config/theme-config.php' );

		// WooCommerce
		if ( ACMTHEMES_WOOCOMMERCE_ACTIVE ) {
			require_once ( $dir .'woocommerce/woocommerce-config.php' );
		}
	}

	/**
	 * Returns current theme version
	 *
	 * @since   1.0.0
	 */
	public static function theme_version() {

		// Get theme data
		$theme = wp_get_theme();

		// Return theme version
		return $theme->get( 'Version' );

	}

	/**
	 * Load theme classes
	 *
	 * @since   1.0.0
	 */
	public static function classes() {

		// Admin only classes
		if ( is_admin() ) {

			// Recommend plugins
			require_once( ACMTHEMES_INC_DIR .'plugins/class-tgm-plugin-activation.php' );
			require_once( ACMTHEMES_INC_DIR .'plugins/tgm-plugin-activation.php' );

		}

		// Front-end classes
		else {

			// Breadcrumbs class
			require_once( ACMTHEMES_INC_DIR .'breadcrumbs.php' );

		}

	}

	/**
	 * Theme Setup
	 *
	 * @since   1.0.0
	 */
	public static function theme_setup() {

		// Load text domain
		load_theme_textdomain( 'bookchoix', ACMTHEMES_THEME_DIR .'/languages' );

		// Get globals
		global $content_width;

		// Set content width based on theme's default design
		if ( ! isset( $content_width ) ) {
			$content_width = 1200;
		}

		// Register navigation menus
		register_nav_menus( array(
			'topbar_menu'     => esc_html__( 'Top Bar Navigation', 'bookchoix' ),
			'main_menu'       => esc_html__( 'Main Navigation', 'bookchoix' ),
			'mobile_menu'     => esc_html__( 'Mobile Navigation', 'bookchoix' )
		) );

		// Enable support for Post Formats
		add_theme_support( 'post-formats', array( 'video', 'gallery', 'audio', 'quote', 'link' ) );

		// Enable support for <title> tag
		add_theme_support( 'title-tag' );

		// Add default posts and comments RSS feed links to head
		add_theme_support( 'automatic-feed-links' );

		// Enable support for Post Thumbnails on posts and pages
		add_theme_support( 'post-thumbnails' );

		/**
		 * Enable support for header image
		 */
		add_theme_support( 'custom-header', apply_filters( 'custom_header_args', array(
			'width'              => 2000,
			'height'             => 1200,
			'flex-height'        => true,
			'video'              => true,
		) ) );

		/**
		 * Enable support for site logo
		 */
		add_theme_support( 'custom-logo', apply_filters( 'custom_logo_args', array(
			'height'      => 45,
			'width'       => 164,
			'flex-height' => true,
			'flex-width'  => true,
		) ) );

		/*
		 * Switch default core markup for search form, comment form, comments, galleries, captions and widgets
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'widgets',
			'script',
			'style'
		) );

		// Declare WooCommerce support.
		add_theme_support( 'woocommerce' );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );

		// Add editor style
		add_editor_style( 'assets/css/editor-style.min.css' );

		// Declare support for selective refreshing of widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		add_filter( 'woocommerce_get_image_size_thumbnail', function( $size ) {
			return array(
			'width' => 257,
			'height' => 284,
			'crop' => 0,
			);
		} );

		//default product thumbnail sizes
		$catalog_width = '257';
		$catalog_height = '284';

		//woo product thumbnail size
		$catalog_size = apply_filters( 'bookchoix_catalog_thumbnail_size', array( $catalog_width, $catalog_height ) );
		add_image_size('bookchoix_catalog_thumb', $catalog_size[0], $catalog_size[1], true);

		//default single product sizes
		$single_width = '550';
		$single_height = '680';

		//woo single product image size
		$single_product_size = apply_filters( 'bookchoix_single_product_size', array( $single_width, $single_height ) );
		add_image_size('bookchoix_single_product', $single_product_size[0], $single_product_size[1], true);


		//default product thumbnail sizes
		$blog_thumb_width = '1200';
		$blog_thumb_height = '764';

		//blog thumnail size
		$blog_thumb_size = apply_filters( 'bookchoix_blog_thumb_size', array( $blog_thumb_width, $blog_thumb_height ) );
		add_image_size('bookchoix_blog_thumb', $blog_thumb_size[0], $blog_thumb_size[1], true);

		//other image sizes
		add_image_size('bookchoix_square_thumb', 250, 250, true);
		add_image_size('bookchoix_multi_blog_thumb', 585, 350, true);
		add_image_size('bookchoix_woo_cat_thumb', 700, 700, true);

	}

	/**
	 * Adds the meta tag to the site header
	 *
	 * @since 1.1.0
	 */
	public static function pingback_header() {

		if ( is_singular() && pings_open() ) {
			printf( '<link rel="pingback" href="%s">' . "\n", esc_url( get_bloginfo( 'pingback_url' ) ) );
		}

	}

	/**
	 * Adds the meta tag to the site header
	 *
	 * @since 1.0.0
	 */
	public static function meta_viewport() {

		// Meta viewport
		$viewport = '<meta name="viewport" content="width=device-width, initial-scale=1">';

		// Apply filters for child theme tweaking
		echo apply_filters( 'meta_viewport', $viewport );

	}

	/**
	 * Load scripts in the WP admin
	 *
	 * @since 1.0.0
	 */
	public static function admin_scripts() {
		global $pagenow;
		if ( 'nav-menus.php' == $pagenow ) {
			wp_enqueue_style( 'acmthemes-menus', ACMTHEMES_INC_DIR_URI .'walker/assets/menus.css' );
		}
	}

	/**
	 * Load mobile menu in header
	 *
	 * @since 1.0.0
	 */
	public static function bookchoix_add_mobile_menu(){

	      $menu_args = array(
	        'container'         => 'nav',
	        'container_id'      => 'acm-mobile-menu',
	        'menu_id'           => 'acm-mmenu',
	        'menu_class'        => 'acm-mmenu',
					'echo'            => true,
	        'theme_location'    => 'mobile_menu',
	        'fallback_cb'       => false
	      );

				wp_nav_menu($menu_args);

	  }

	/**
	 * Load front-end scripts
	 *
	 * @since   1.0.0
	 */
	public static function theme_css() {

		// Define dir
		$dir = ACMTHEMES_CSS_DIR_URI;
		$theme_version = ACMTHEMES_THEME_VERSION;

		// Load preloader styles
		wp_enqueue_style( 'bookchoix-preloader', $dir .'preloader.css', false, $theme_version );

		wp_enqueue_style( 'google-font-jost', '//fonts.googleapis.com/css2?family=Jost:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&display=swap', false );

		// Register the lightbox style
		wp_enqueue_style( 'magnific-popup', $dir .'third/magnific-popup.min.css', false, '1.0.0' );

		// Register the slick style
		wp_enqueue_style( 'slick', $dir .'third/slick.min.css', false, '1.6.0' );

		// Main Style.css File
		wp_enqueue_style( 'bookchoix-style', $dir .'style.css', false, $theme_version );

		if( is_rtl() ) {
			// RTL CSS File
			wp_enqueue_style( 'bookchoix-rtl', $dir .'rtl.css', false, $theme_version );
		}

		//mmenu
		wp_register_style('jquery-mmenu', $dir . 'third/jquery.mmenu.all.min.css', array(), false, 'screen');
		wp_enqueue_style('jquery-mmenu');

	}

	/**
	 * Returns all js needed for the front-end
	 *
	 * @since 1.0.0
	 */
	public static function theme_js() {

		// Get js directory uri
		$dir = ACMTHEMES_JS_DIR_URI;

		// Get current theme version
		$theme_version = ACMTHEMES_THEME_VERSION;

		// Get localized array
		$localize_array = self::localize_array();

		// Comment reply
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		// Add images loaded
		wp_enqueue_script( 'imagesloaded' );

		//mmenu
		wp_register_script('jquery-mmenu', $dir . 'third/jquery.mmenu.min.all.js', array('jquery'), false, true);
		wp_enqueue_script('jquery-mmenu');

		// WooCommerce scripts
		if ( ACMTHEMES_WOOCOMMERCE_ACTIVE ) {
			wp_enqueue_script( 'woo-scripts', $dir .'third/woo/woo-scripts.min.js', array( 'jquery' ), $theme_version, true );
		}

		// Load the lightbox scripts
		wp_enqueue_script( 'magnific-popup', $dir .'third/magnific-popup.min.js', array( 'jquery' ), $theme_version, true );
		wp_enqueue_script( 'lightbox', $dir .'third/lightbox.min.js', array( 'jquery' ), $theme_version, true );

		// Load minified js
		wp_enqueue_script( 'bookchoix-main-scripts', $dir .'main.min.js', array( 'jquery' ), $theme_version, true );

		// Load quick scripts js
		wp_enqueue_script( 'bookchoix-quick-scripts', $dir .'quick.js', array( 'jquery' ), $theme_version, true );

		// Localize array
		wp_localize_script( 'bookchoix-main-scripts', 'acmthemesLocalize', $localize_array );

	}

	/**
	 * Functions.js localize array
	 *
	 * @since 1.0.0
	 */
	public static function localize_array() {

		// Create array
		$array = array(
			'isRTL'                 => is_rtl(),
			'menuSearchStyle'       => acmthemes_menu_search_style(),
			'verticalHeaderTarget'  => 'icon',
			'customSelects'         => '.woocommerce-ordering .orderby, #dropdown_product_cat,
			    .widget_categories select, .widget_archive select, .single-product .variations_form .variations select, .textwidget select,
					.wp-block-categories-dropdown select',
		);

		// WooCart
		if ( ACMTHEMES_WOOCOMMERCE_ACTIVE ) {
			$array['wooCartStyle'] 	= acmthemes_menu_cart_style();
		}

		// Apply filters and return array
		return apply_filters( 'localize_array', $array );
	}

	/**
	 * Add headers for IE to override IE's Compatibility View Settings
	 *
	 * @since 1.0.0
	 */
	public static function x_ua_compatible_headers( $headers ) {
		$headers['X-UA-Compatible'] = 'IE=edge';
		return $headers;
	}

	/**
	 * Registers sidebars
	 *
	 * @since   1.0.0
	 */
	public static function register_sidebars() {

		//get theme settings
		$settings = acmthemes_settings();

		$heading = 'h3';
		$heading = apply_filters( 'sidebar_heading', $heading );

		// Default Sidebar
		register_sidebar( array(
			'name'			=> esc_html__( 'Default Sidebar', 'bookchoix' ),
			'id'			=> 'sidebar',
			'description'	=> esc_html__( 'Widgets in this area will be displayed in the left or right sidebar area if you choose the Left or Right Sidebar layout.', 'bookchoix' ),
			'before_widget'	=> '<div id="%1$s" class="sidebar-box %2$s clr">',
			'after_widget'	=> '</div>',
			'before_title'	=> '<'. $heading .' class="widget-title">',
			'after_title'	=> '</'. $heading .'>',
		) );

		// Left Sidebar
		register_sidebar( array(
			'name'			=> esc_html__( 'Shop Sidebar', 'bookchoix' ),
			'id'			=> 'shop_sidebar',
			'description'	=> esc_html__( 'Widgets in this area are used in the shop pages.', 'bookchoix' ),
			'before_widget'	=> '<div id="%1$s" class="sidebar-box %2$s clr">',
			'after_widget'	=> '</div>',
			'before_title'	=> '<'. $heading .' class="widget-title">',
			'after_title'	=> '</'. $heading .'>',
		) );

		// Search Results Sidebar
		if ( isset( $settings[ 'product_search_custom_sidebar' ] ) && $settings[ 'product_search_custom_sidebar' ] == true ) {
			register_sidebar( array(
				'name'			=> esc_html__( 'Product Search Results Sidebar', 'bookchoix' ),
				'id'			=> 'search_sidebar',
				'description'	=> esc_html__( 'Widgets in this area are used in the products search result page.', 'bookchoix' ),
				'before_widget'	=> '<div id="%1$s" class="sidebar-box %2$s clr">',
				'after_widget'	=> '</div>',
				'before_title'	=> '<'. $heading .' class="widget-title">',
				'after_title'	=> '</'. $heading .'>',
			) );
		}

		// Footer 1
		register_sidebar( array(
			'name'			=> esc_html__( 'Footer 1', 'bookchoix' ),
			'id'			=> 'footer-one',
			'description'	=> esc_html__( 'Widgets in this area are used in the first footer region.', 'bookchoix' ),
			'before_widget'	=> '<div id="%1$s" class="footer-widget %2$s single-line clr">',
			'after_widget'	=> '</div>',
			'before_title'	=> '<div class="bookchoix-heading single-line"><'. $heading .' class="widget-title bookchoix-title title-white style-left">',
			'after_title'	=> '</'. $heading .'></div>',
		) );

		// Footer 2
		register_sidebar( array(
			'name'			=> esc_html__( 'Footer 2', 'bookchoix' ),
			'id'			=> 'footer-two',
			'description'	=> esc_html__( 'Widgets in this area are used in the second footer region.', 'bookchoix' ),
			'before_widget'	=> '<div id="%1$s" class="footer-widget %2$s single-line clr">',
			'after_widget'	=> '</div>',
			'before_title'	=> '<div class="bookchoix-heading single-line"><'. $heading .' class="widget-title bookchoix-title title-white style-left">',
			'after_title'	=> '</'. $heading .'></div>',
		) );

		// Footer 3
		register_sidebar( array(
			'name'			=> esc_html__( 'Footer 3', 'bookchoix' ),
			'id'			=> 'footer-three',
			'description'	=> esc_html__( 'Widgets in this area are used in the third footer region.', 'bookchoix' ),
			'before_widget'	=> '<div id="%1$s" class="footer-widget %2$s single-line clr">',
			'after_widget'	=> '</div>',
			'before_title'	=> '<div class="bookchoix-heading single-line"><'. $heading .' class="widget-title bookchoix-title title-white style-left">',
			'after_title'	=> '</'. $heading .'></div>',
		) );

		// Footer 4
		register_sidebar( array(
			'name'			=> esc_html__( 'Footer 4', 'bookchoix' ),
			'id'			=> 'footer-four',
			'description'	=> esc_html__( 'Widgets in this area are used in the fourth footer region.', 'bookchoix' ),
			'before_widget'	=> '<div id="%1$s" class="footer-widget %2$s clr">',
			'after_widget'	=> '</div>',
			'before_title'	=> '<div class="bookchoix-heading single-line"><'. $heading .' class="widget-title bookchoix-title title-white style-left">',
			'after_title'	=> '</'. $heading .'></div>',
		) );

		// Custom Sidebar
		register_sidebar( array(
			'name'			=> esc_html__( 'Custom Sidebar 1', 'bookchoix' ),
			'id'			=> 'custom_sidebar_1',
			'description'	=> esc_html__( 'Custom sidebar Widgets.', 'bookchoix' ),
			'before_widget'	=> '<div id="%1$s" class="custom_widget sidebar-box %2$s clr">',
			'after_widget'	=> '</div>',
			'before_title'	=> '<'. $heading .' class="widget-title">',
			'after_title'	=> '</'. $heading .'>',
		) );

		// Custom Sidebar
		register_sidebar( array(
			'name'			=> esc_html__( 'Custom Sidebar 2', 'bookchoix' ),
			'id'			=> 'custom_sidebar_2',
			'description'	=> esc_html__( 'Custom sidebar Widgets.', 'bookchoix' ),
			'before_widget'	=> '<div id="%1$s" class="custom_widget sidebar-box %2$s clr">',
			'after_widget'	=> '</div>',
			'before_title'	=> '<'. $heading .' class="widget-title">',
			'after_title'	=> '</'. $heading .'>',
		) );

		// Custom Sidebar
		register_sidebar( array(
			'name'			=> esc_html__( 'Custom Sidebar 3', 'bookchoix' ),
			'id'			=> 'custom_sidebar_3',
			'description'	=> esc_html__( 'Custom sidebar Widgets.', 'bookchoix' ),
			'before_widget'	=> '<div id="%1$s" class="custom_widget sidebar-box %2$s clr">',
			'after_widget'	=> '</div>',
			'before_title'	=> '<'. $heading .' class="widget-title">',
			'after_title'	=> '</'. $heading .'>',
		) );

		// Custom Sidebar
		register_sidebar( array(
			'name'			=> esc_html__( 'Custom Sidebar 4', 'bookchoix' ),
			'id'			=> 'custom_sidebar_4',
			'description'	=> esc_html__( 'Custom sidebar Widgets.', 'bookchoix' ),
			'before_widget'	=> '<div id="%1$s" class="custom_widget sidebar-box %2$s clr">',
			'after_widget'	=> '</div>',
			'before_title'	=> '<'. $heading .' class="widget-title">',
			'after_title'	=> '</'. $heading .'>',
		) );

		// Offcanvas Sidebar
		register_sidebar( array(
			'name'			=> esc_html__( 'Off canvas sidebar', 'bookchoix' ),
			'id'			=> 'off_canvas',
			'description'	=> esc_html__( 'Off canvas shop sidebar.', 'bookchoix' ),
			'before_widget'	=> '<div id="%1$s" class="offcanvas_widget sidebar-box %2$s clr">',
			'after_widget'	=> '</div>',
			'before_title'	=> '<'. $heading .' class="widget-title">',
			'after_title'	=> '</'. $heading .'>',
		) );

	}


	/**
	 * Alter the search posts per page
	 *
	 * @since 1.3.7
	 */
	public static function search_posts_per_page( $query ) {

		$settings = (function_exists('acmthemes_settings')) ? acmthemes_settings() : "";

		$posts_per_page = ( isset($settings['posts_per_page_on_search'])
			&& !empty($settings['posts_per_page_on_search']) ) ? $settings['posts_per_page_on_search'] : '10';

		if ( $query->is_main_query() && is_search() ) {
			$query->set( 'posts_per_page', $posts_per_page );
		}
	}

	/**
	 * Alters the default oembed output.
	 * Adds special classes for responsive oembeds via CSS.
	 *
	 * @since 1.0.0
	 */
	public static function add_responsive_wrap_to_oembeds( $cache, $url, $attr, $post_ID ) {

		// Supported video embeds
		$hosts = apply_filters( 'oembed_responsive_hosts', array(
			'vimeo.com',
			'youtube.com',
			'blip.tv',
			'money.cnn.com',
			'dailymotion.com',
			'flickr.com',
			'hulu.com',
			'kickstarter.com',
			'vine.co',
			'soundcloud.com',
			'#http://((m|www)\.)?youtube\.com/watch.*#i',
	        '#https://((m|www)\.)?youtube\.com/watch.*#i',
	        '#http://((m|www)\.)?youtube\.com/playlist.*#i',
	        '#https://((m|www)\.)?youtube\.com/playlist.*#i',
	        '#http://youtu\.be/.*#i',
	        '#https://youtu\.be/.*#i',
	        '#https?://(.+\.)?vimeo\.com/.*#i',
	        '#https?://(www\.)?dailymotion\.com/.*#i',
	        '#https?://dai\.ly/*#i',
	        '#https?://(www\.)?hulu\.com/watch/.*#i',
	        '#https?://wordpress\.tv/.*#i',
	        '#https?://(www\.)?funnyordie\.com/videos/.*#i',
	        '#https?://vine\.co/v/.*#i',
	        '#https?://(www\.)?collegehumor\.com/video/.*#i',
	        '#https?://(www\.|embed\.)?ted\.com/talks/.*#i'
		) );

		// Supports responsive
		$supports_responsive = false;

		// Check if responsive wrap should be added
		foreach( $hosts as $host ) {
			if ( strpos( $url, $host ) !== false ) {
				$supports_responsive = true;
				break; // no need to loop further
			}
		}

		// Output code
		if ( $supports_responsive ) {
			return '<p class="responsive-video-wrap clr">' . $cache . '</p>';
		} else {
			return '<div class="acmthemes-oembed-wrap clr">' . $cache . '</div>';
		}

	}

	/**
	 * Adds extra classes to the post_class() output
	 *
	 * @since 1.0.0
	 */
	public static function post_class( $classes ) {

		// Get post
		global $post;

		// Add entry class
		$classes[] = 'entry';

		// Add has media class
		if ( has_post_thumbnail()
			|| get_post_meta( $post->ID, 'post_oembed', true )
			|| get_post_meta( $post->ID, 'post_self_hosted_media', true )
			|| get_post_meta( $post->ID, 'post_video_embed', true )
		) {
			$classes[] = 'has-media';
		}

		// Return classes
		return $classes;

	}


	/**
	 * Add schema markup to the authors post link
	 *
	 * @since 1.0.0
	 */
	public static function the_author_posts_link( $link ) {

		// Add schema markup
		$schema = acmthemes_get_schema_markup( 'author_link' );
		if ( $schema ) {
			$link = str_replace( 'rel="author"', 'rel="author" '. $schema, $link );
		}

		// Return link
		return $link;

	}

	/**
	 * Add support for Elementor Pro locations
	 *
	 * @since 1.0.0
	 */
	public static function register_elementor_locations( $elementor_theme_manager ) {
		$elementor_theme_manager->register_all_core_location();
	}

	/**
	 * Add schema markup to the authors post link
	 *
	 * @since 1.0.0
	 */
	public static function remove_bb_lightbox() {
		return true;
	}

}
new ACMTHEMES_THEME_CLASS;