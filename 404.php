<?php
/**
 * The template for displaying 404 error page.
 *
 */

get_header();

$settings = acmthemes_settings();

?>
<div id="page-full-width" class="clr">

  <div class="content404">

    <div class="not-found-image">
    <?php if( isset( $settings['error_image'] ) && !empty( $settings['error_image']['id'] ) ){
      echo wp_get_attachment_image( $settings['error_image']['id'], 'full');
    }else {
      echo '<img src="' . get_template_directory_uri() . '/assets/img/404-not-found.png" alt="Page Not Found" />';
    }
     ?>
   </div>

   <div class="error404-content">

       <div class="error404 not-found">
           <h1 class="error-title">
               <?php
               if(isset($settings['error404-title']) && !empty($settings['error404-title'])) {
                   echo wp_kses_post( do_shortcode ( $settings['error404-title'] ) );
               }
               else {
                   esc_html_e ( 'Oops! Page Not Found!', 'bookchoix' );
               }
               ?>
           </h1>
           <div class="error-description">
               <p>
                   <?php
                       if(isset($settings['error404-desc']) && !empty($settings['error404-desc'])) {
                           echo wp_kses_post( do_shortcode( $settings['error404-desc'] ));
                       }
                       else {
                           esc_html_e ( "We're sorry, but the web address you've entered is no longer available.", 'bookchoix' );
                       }
                       ?>
               </p>
               <a class="btn-home btn btn-primary" href="<?php echo esc_url( home_url('/') ); ?>"><?php esc_html_e('Back to Home', 'bookchoix'); ?></a>
         </div>
       </div>

   </div> <!-- error404-content -->

 </div>

</div>

<?php get_footer();
