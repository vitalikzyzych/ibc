<?php get_header(); ?>

  <?php get_template_part( 'template-parts/global/wrapper-before-blog' ); ?>

      <?php if ( have_posts() ) : ?>

        <div class="ct-posts">

          <?php while ( have_posts() ) : the_post(); ?>

          <?php get_template_part( 'post-formats/content', get_post_format() ); ?>

          <?php endwhile; ?>

        </div>

        <?php get_template_part( 'template-parts/pagination' , 'posts' ); ?>

      <?php else : get_template_part( 'post-formats/content' , 'missing' ); endif; ?>

  <?php get_template_part( 'template-parts/global/wrapper-after-blog' ); ?>

<?php get_footer(); ?>
