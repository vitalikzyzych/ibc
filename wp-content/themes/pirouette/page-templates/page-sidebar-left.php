<?php // Template Name: Left Sidebar Page ?>
<?php get_header(); ?>

<div class="row">
  <div class="col-md-4 col-lg-3">
    <?php get_template_part( 'template-parts/global/sidebar', 'page' ); ?>
  </div>
  <div class="col-md-7 offset-md-1 col-lg-8">
    <?php get_template_part( 'post-formats/content', 'page' ); ?>
  </div>
</div>

<?php get_footer(); ?>
