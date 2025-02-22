<?php
/**
 * Header template.
 *
 * @package BookChoix WordPress theme
 */ ?>
<!DOCTYPE html>
<html class="<?php echo esc_attr( acmthemes_html_classes() ); ?>" <?php language_attributes(); ?><?php acmthemes_schema_markup( 'html' ); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">

	<?php
	//get options framework settings
	$settings = acmthemes_settings();

	// insert favicon
	if ( isset( $settings['site_favicon']['url'] ) && !empty( $settings['site_favicon']['url'] )) {
	?>
		<link rel="shortcut icon" href="<?php echo esc_url( $settings['site_favicon']['url'] ); ?>" type="image/x-icon" />
		<?php
	}
	
	?>

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
  <?php wp_body_open(); ?>

	<?php do_action( 'before_outer_wrap' ); ?>

	<div id="outer-wrap" class="<?php echo acmthemes_wrap_class(); ?>">

		<?php do_action( 'before_wrap' ); ?>

		<div id="wrap" class="clr">

		<?php do_action( 'before_top_bar' ); ?>

		<?php
		if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'header' ) ) { ?>
			
			<?php do_action( 'top_bar' ); ?>

			<?php do_action( 'header' ); ?>

			<?php do_action( 'before_main' ); ?>

		<?php } ?>

			<main id="main" class="site-main clr"<?php acmthemes_schema_markup( 'main' ); ?>>

				<?php do_action( 'page_header' );
