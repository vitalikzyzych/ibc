<article id="post-<?php the_ID(); ?>" <?php post_class('ct-post entry'); ?>>

	<?php the_content(); ?>

	<header>
		<?php get_template_part( 'template-parts/meta', 'loop' ); ?>
	</header>

</article>
