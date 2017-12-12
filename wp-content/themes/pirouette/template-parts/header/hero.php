<?php

$pirouette_hero           = PirouetteHelpers::header_slider();
$pirouette_hero_image     = PirouetteHelpers::header_image();
$pirouette_hero_navigator = wp_kses_post( get_post_meta( get_the_id(), '_xtender_header_scroller', true ) );
$pirouette_hero_sec_title = esc_attr( get_post_meta( get_the_id(), '_xtender_sec_nav_title', true ) );
$pirouette_hero_sec_color = esc_attr( get_post_meta( get_the_id(), '_xtender_color_sec_nav', true ) );
$pirouette_hero_sec_nav   = ! empty( $pirouette_hero_sec_title ) ? true : false;
$pirouette_hero_data      = array();

if( $pirouette_hero_sec_nav ){
  $pirouette_hero_data[] = "data-section-title='$pirouette_hero_sec_title'";

  if( ! empty( $pirouette_hero_sec_color ) ){
    $pirouette_hero_data[] = "data-section-color='$pirouette_hero_sec_color'";
  }

}


if( PirouetteHeading::check() || $pirouette_hero_image || $pirouette_hero ) : ?>

  <?php if( $pirouette_hero_sec_nav ) : ?>
    <div class="scrollable-section" <?php echo implode( ' ', $pirouette_hero_data ); ?>>
  <?php endif; ?>

    <div id="ct-header__hero" class="ct-header__hero" data-slider="<?php echo ( $pirouette_hero ) ? 'true' : 'false' ?>">
      <?php
        if( ! empty( $pirouette_hero_navigator ) ) : ?><div id="ct-header__hero-navigator" class="ct-header__hero-navigator"><a href="#"><?php echo wp_kses_post( $pirouette_hero_navigator ) ?></a></div><?php endif;
        if( $pirouette_hero && $pirouette_hero !== 'disable-header-slider' ) echo do_shortcode( "[rev_slider alias='$pirouette_hero']" );?>
    </div>

<?php if( $pirouette_hero_sec_nav ) : ?>
  </div>
<?php endif; endif; ?>
