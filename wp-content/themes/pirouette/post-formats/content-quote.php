<aside id="post-<?php the_ID(); ?>" <?php post_class('ct-post entry'); ?>>

	<div class="ct-post__content">
		<?php the_content(); ?>
	</div>

	<header>
		<?php get_template_part( 'template-parts/meta', 'loop' ); ?>
	</header>

</aside>
