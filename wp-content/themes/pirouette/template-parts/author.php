<?php $pirouette_author_desc = get_the_author_meta("description"); if( filter_var( esc_attr( get_theme_mod( 'author', true ) ) , FILTER_VALIDATE_BOOLEAN ) && ! empty( $pirouette_author_desc ) ) : ?>
<div class="ct-about-author">
	<div class="ct-about-author__avatar">
		<?php echo get_avatar( get_the_author_meta('email'), '72' ); ?>
	</div>
	<div class="ct-about-author__title-meta">
		<h4><?php the_author(); ?></h4>
	    <?php echo esc_attr( $pirouette_author_desc ); ?>
	</div>
</div>
<?php endif; ?>
