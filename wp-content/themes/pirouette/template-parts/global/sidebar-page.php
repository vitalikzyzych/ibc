<?php $pirouette_sidebar = PirouetteHelpers::get_sidebar( 'sidebar_page' ); if( $pirouette_sidebar ) : ?>
<div class="ct-sidebar content-padding">
  <?php dynamic_sidebar( $pirouette_sidebar ); ?>
</div>
<?php endif; ?>
