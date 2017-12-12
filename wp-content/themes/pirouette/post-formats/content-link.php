<article id="post-<?php the_ID(); ?>" <?php post_class('ct-post entry animated'); ?>>
	<header>
		<?php the_title( '<h2 class="ct-post__title"><span class="ti-link"></span><a href="' . PirouetteHelpers::get_first_url() . '" title="'.get_the_title().'">', '</a></h2>' ) ?>
		<?php get_template_part( 'template-parts/meta', 'loop' ); ?>
	</header>
</article>
