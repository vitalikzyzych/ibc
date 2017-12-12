<?php

$pirouette_title 			     = esc_attr( get_bloginfo( 'name' ) );
$pirouette_desc 			     = esc_attr( get_bloginfo( 'description' ) );
$pirouette_logo_url 			 = esc_url_raw( get_theme_mod( 'logo' ) );
$pirouette_logo_retina_url = esc_url_raw( get_theme_mod( 'logo_retina' ) );

?>
<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" id="ct-logo">
  <?php if( ! empty( $pirouette_logo_url ) ) : ?>
    <img id="ct-logo-image" src='<?php echo esc_url( $pirouette_logo_url ) ?>' <?php if( ! empty( $pirouette_logo_retina_url ) ) echo "srcset='" . esc_url( $pirouette_logo_retina_url ). " 2x'" ?> alt='<?php echo esc_attr( $pirouette_title ) ?>'>
  <?php else : ?>
    <span><?php echo esc_attr( $pirouette_title ) ?></span>
    <small><?php echo esc_attr( $pirouette_desc ) ?></small>
  <?php endif; ?>
</a>
