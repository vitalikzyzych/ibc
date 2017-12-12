<?php

$pirouette_logo_url 			 = esc_url_raw( get_theme_mod( 'footer_logo' ) );
$pirouette_logo_retina_url = esc_url_raw( get_theme_mod( 'footer_logo_retina' ) );
$pirouette_logo_retina_url = ! empty( $pirouette_logo_retina_url ) ? "srcset='$pirouette_logo_retina_url 2x'" : '';


if( ! empty( $pirouette_logo_url ) ) : ?>

  <div class="ct-footer__logo">
    <a href="<?php echo is_front_page() ? '#top' : site_url('/'); ?>" rel="home" id="ct-footer-logo">
      <img src='<?php echo esc_url( $pirouette_logo_url ); ?>' <?php echo ! empty( $pirouette_logo_retina_url ) ? $pirouette_logo_retina_url : '' ?> alt='<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>'>
    </a>
  </div>

<?php endif; ?>
