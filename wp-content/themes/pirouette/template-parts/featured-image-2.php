<?php if( has_post_thumbnail() ) : ?>
	<a href="<?php echo wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) ); ?>" class="ct-post__featured-link ct-lightbox">
	<?php the_post_thumbnail( 'large', array('class' => 'featured-image img-responsive') ); ?>
	</a>
<?php endif; ?>
