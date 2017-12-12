  <?php if( PirouetteHelpers::get_sidebar( 'sidebar_blog' ) ) : ?>
    </div>

    <?php if( is_single() ) : ?>
      <div class="col-md-4 offset-md-1 col-lg-3">
    <?php else : ?>
      <div class="col-md-4 col-lg-3">
    <?php endif; ?>
        <?php get_template_part( 'template-parts/global/sidebar' ); ?>
  <?php endif; ?>
  
  </div>
</div>
