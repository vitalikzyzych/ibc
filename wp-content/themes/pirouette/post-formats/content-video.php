<article id="post-<?php the_ID(); ?>" <?php post_class('ct-post entry'); ?>>

	<div class="ct-post__excerpt">
		<?php the_content(); ?>
	</div>

	<header>
		<?php get_template_part( 'template-parts/title', 'loop' ); ?>
		<?php get_template_part( 'template-parts/meta', 'loop' ); ?>
	</header>

</article>
