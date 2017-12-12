<article id="post-<?php the_ID(); ?>" <?php post_class('ct-post entry'); ?>>
	<header>
		<?php get_template_part( 'template-parts/featured', 'image' ); ?>
		<?php get_template_part( 'template-parts/title', 'loop' ); ?>
		<?php get_template_part( 'template-parts/meta', 'loop' ); ?>
	</header>

	<?php if( has_excerpt() ) : ?>

		<p class="ct-post__excerpt">
			<?php echo get_the_excerpt(); ?>
		</p>

		<?php get_template_part( 'template-parts/button', 'read-more'); ?>
		<?php get_template_part( 'template-parts/pagination', 'links'); ?>

	<?php else : ?>

		<?php the_content(); ?>

	<?php endif; ?>

</article>
