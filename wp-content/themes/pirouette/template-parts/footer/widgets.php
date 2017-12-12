<?php if( is_active_sidebar( 'footer_widget_area' ) ) : ?>
  <div id="absolute-footer">
    <div class="row">
      <?php dynamic_sidebar( 'footer_widget_area' ); ?>
    </div>
  </div><!-- #absolute-footer -->
<?php endif; ?>
