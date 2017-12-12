<?php

class PirouetteHeading{

	public static function check() {

		$heading = wp_cache_get( 'heading', 'CurlyTheme' );

		if( ! $heading ){

			$heading_temp = self::get_heading();

			$heading = filter_var( esc_attr( get_post_meta( PirouetteHelpers::get_the_id(), '_xtender_heading', true ) ), FILTER_VALIDATE_BOOLEAN ) ? false : true;
			$heading = ! PirouetteHelpers::header_slider() ? $heading : false;
			$heading = is_single() && PirouetteHelpers::is_blog() && $heading_temp === get_the_title() ? false : $heading;

			$heading = $heading && ! empty( $heading_temp ) ? true : false;

			wp_cache_set( 'heading', $heading, 'CurlyTheme' );

		}

		return filter_var( $heading, FILTER_VALIDATE_BOOLEAN );

	}

	public static function get_heading(){

		$heading = wp_cache_get( 'heading_text', 'CurlyTheme' );

		if( ! $heading ){

			global $post;

			$heading_custom = is_singular() ? esc_attr( get_post_meta( $post->ID, '_xtender_header_title', true ) ) : false;

			switch( true ){

				case is_singular() && ! empty( $heading_custom ) : {

					$heading = $heading_custom;

				} break;

				case function_exists('is_woocommerce') && is_woocommerce() : {

					$heading = woocommerce_page_title( false );

					if ( apply_filters( 'woocommerce_show_page_title', true ) ) {



					}

				} break;

				case is_singular() : {

					if( get_post_type() === 'post' ){

						$heading = get_the_title( get_option( 'page_for_posts' ) );

					} else {

						$heading = get_the_title();

					}

				} break;

				case is_home() : {

					$blog_page = esc_attr( get_option( 'page_for_posts' ) );
					$custom_title = get_post_meta( $blog_page, '_xtender_header_title', true );

					$heading = ! empty( $blog_page ) ? get_the_title( $blog_page ) : esc_html__( 'Blog', 'pirouette' );
					$heading = ! empty( $custom_title ) ? $custom_title : $heading;

				} break;

				case is_category() || is_tax() : {

					$heading = single_cat_title('' , false);

				} break;

				case is_archive() : {

					$heading = get_the_archive_title();

				} break;

				case is_search() : {

					$heading = esc_html__( 'Search Results' , 'pirouette' );

				} break;

				case is_404() : {

					$heading = esc_html__('Page could not be found. 404 Error' , 'pirouette');

				} break;

				default : $heading = get_the_title();

			}

			$heading = apply_filters( 'pirouette_page_title', $heading );

			wp_cache_set( 'heading_text', $heading, 'CurlyTheme' );

		}

		return $heading;

	}


	public static function the_heading( $before = null, $after = null ){

		if ( filter_var( self::check(), FILTER_VALIDATE_BOOLEAN ) ){

			global $post;

			$html = self::get_heading();

			$subtitle = isset( $post ) && is_singular()  ? esc_attr( get_post_meta( $post->ID, '_xtender_header_subtitle', true ) ) : null;
			$subtitle = ! is_null( $subtitle ) && ! empty( $subtitle ) ? '<small>' . $subtitle . '</small>' : null;

			$excerpt = isset( $post ) && is_singular() ? wp_kses_post( get_post_meta( $post->ID, '_xtender_header_excerpt', true ) ) : null;
			$excerpt = apply_filters( 'pirouette_page_excerpt', $excerpt );
			$excerpt = ! is_null( $excerpt ) && ! empty( $excerpt ) ? '<div class="ct-header__main-heading-excerpt">' . apply_filters( 'the_content', $excerpt ) . '</div>' : null;

			if( is_singular() && ! is_page() ){
				$before = '<div class="h1">';
				$after 	= '</div>';
			}

			echo wp_kses_post( $subtitle.$before.$html.$after.$excerpt );

		}


	}

}

?>
