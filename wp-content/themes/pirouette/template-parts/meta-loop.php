<div class="ct-post__entry-meta">
	<?php

	ob_start();
	comments_number(  esc_html__( 'No Comments', 'pirouette' ), esc_html__( '1 Comment', 'pirouette' ), esc_html__( '% Comments', 'pirouette' ) );
	$pirouette_comments = ob_get_clean();

	if( comments_open() ){

		echo sprintf( '<span><i class="ti-calendar color-primary"></i> <em>%1$s</em></span><span><i class="ti-comments color-primary"></i> <em>%2$s</em></span>', '<a href="'.get_the_permalink().'">'.get_the_date().'</a>', $pirouette_comments );
	}

	else {

		echo sprintf( '<span><i class="ti-calendar color-primary"></i> <em>%1$s</em></span>', '<a href="'.get_the_permalink().'">'.get_the_date().'</a>' );

	}


	 ?>
</div>
