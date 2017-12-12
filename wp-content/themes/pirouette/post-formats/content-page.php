<article id="post-<?php the_ID(); ?>" <?php post_class( 'ct-page__entry-content' ) ?>>

	<!-- The Content -->
	<?php while ( have_posts() ) : the_post(); ?>
		<?php the_content() ?>
	<?php endwhile; ?>

	<!-- Sharing -->
	<?php get_template_part( 'template-parts/sharing' ); ?>

	<!-- Comments -->
	<?php get_template_part( 'template-parts/comments' ); ?>

</article>
