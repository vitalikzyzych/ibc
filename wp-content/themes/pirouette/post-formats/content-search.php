<article id="post-<?php the_ID(); ?>" <?php post_class( 'ct-post entry' ); ?>>
	<header>
		<?php get_template_part( 'template-parts/featured', 'image' ); ?>
		<?php get_template_part( 'template-parts/title', 'loop' ); ?>
		<?php get_template_part( 'template-parts/meta', 'loop' ); ?>
	</header>

	<div class="ct-post__excerpt">
		<?php the_excerpt(); ?>
	</div>

	<?php get_template_part( 'template-parts/button', 'read-more'); ?>

</article>
