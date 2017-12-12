<?php if ( has_nav_menu( 'footer_navigation' ) ) : ?>
  <nav id="footer-navigation">
    <?php if ( has_nav_menu( 'footer_navigation' ) ) {
      wp_nav_menu(
        array(
          'theme_location' => 'footer_navigation',
          'container'      => false,
          'fallback_cb'    => false,
          'depth'          => 1
        )
      );
    }
    ?>
    <?php get_template_part( 'template-parts/footer/scroll-top' ); ?>

</nav>
<?php endif; ?>
