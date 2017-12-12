<article id="post-<?php the_ID(); ?>" <?php post_class('ct-single entry single'); ?>>
	<header>
		<h1 class="ct-single__post-title"><?php the_title() ?></h1>
		<?php get_template_part( 'template-parts/meta', 'single' ); ?>
	</header>

	<div class="ct-single__entry-content">

		<!-- Content -->
		<?php the_content() ?>

		<!-- Link Pages -->
		<?php get_template_part( 'template-parts/pagination', 'links'); ?>

		<!-- Post Navigation -->
		<?php if( get_theme_mod( 'post_navigation', false ) === false ) the_post_navigation(); ?>

	</div>

	<!-- Sharing -->
	<?php get_template_part( 'template-parts/sharing' ); ?>

	<!-- Author -->
	<?php get_template_part( 'template-parts/author' ); ?>

	<!-- Comments -->
	<?php get_template_part( 'template-parts/comments' ); ?>

</article>
