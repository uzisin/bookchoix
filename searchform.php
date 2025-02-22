<?php
/**
 * The template for displaying search forms.
 *
 * @package BookChoix WordPress theme
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Post type
$post_type = 'product'; ?>

<form method="get" class="searchform" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<input type="text" class="field" name="s" id="s" placeholder="<?php esc_html_e( 'Search', 'bookchoix' ); ?>">
	<?php if ( '2' != $post_type ) { ?>
		<input type="hidden" name="post_type" value="<?php echo esc_attr( $post_type ); ?>">
	<?php } ?>
	<button type="submit" name="submit-search" class="button-search"><span class="icon-search fa fa-search"></span></button>
</form>
