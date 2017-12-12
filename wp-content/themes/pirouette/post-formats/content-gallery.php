<aside id="post-<?php the_ID(); ?>" <?php post_class('ct-post entry animated'); ?>>
	<?php $pirouette_post_gallery = get_post_gallery( $post->ID, false ); if ( isset( $pirouette_post_gallery['ids'] ) && ! empty( $pirouette_post_gallery['ids'] ) ) : $pirouette_gallery_ids = explode( ',', $pirouette_post_gallery['ids'] ); ?>

		<div class="ct-gallery--carousel owl-carousel">

			<?php $i = 8; foreach ( $pirouette_gallery_ids as $id ) : if( $i > 0 ) : $i--; ?>

				<div class="item"><a href="'<?php the_permalink(); ?>"><?php echo wp_get_attachment_image( $id, 'large' ) ?></a></div>

			<?php endif; endforeach; ?>

		</div>

	<?php elseif( isset( $pirouette_post_gallery['src'] ) && ! empty( $pirouette_post_gallery['src'] ) ) : ?>

		<header>
			<?php get_template_part( 'template-parts/title', 'loop' ); ?>
		</header>

		<div class="ct-gallery--grid">

			<?php $i = 12; foreach ( $pirouette_post_gallery['src'] as $image ) : if( $i > 0 ) : $i--; ?>

				<div class="item"><a href="'<?php the_permalink(); ?>"><img src="<?php echo esc_url( $image ); ?>"></a></div>

			<?php endif; endforeach; ?>

		</div>

	<?php endif; ?>

	<?php get_template_part( 'template-parts/meta', 'loop' ); ?>

</aside>
