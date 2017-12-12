<?php if( has_post_thumbnail() ) : ?>

	<aside id="post-<?php the_ID(); ?>" <?php post_class('ct-post entry animated'); ?>>

		<?php get_template_part( 'template-parts/featured', 'image-2' ); ?>
		<header>
			<?php get_template_part( 'template-parts/meta', 'loop' ); ?>
		</header>

	</aside>

<?php else : get_template_part( 'post-formats/content' ); endif; ?>
