<?php if( has_post_thumbnail() ) : ?>
	<?php if ( ! is_single() ) : ?><a href="<?php the_permalink(); ?>" class="ct-post__featured-link"><?php endif; ?>
	<?php the_post_thumbnail( 'large', array('class' => 'featured-image img-responsive') ); ?>
	<?php if ( ! is_single() ) : ?></a><?php endif; ?>
<?php endif; ?>