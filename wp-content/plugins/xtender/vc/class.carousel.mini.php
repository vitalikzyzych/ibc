<?php

	class xtenderVCCarouselMini{

		function __construct(){

			/** Construct Services Carousel */
			add_action( 'vc_before_init', array( $this, 'vc_carousel_mini' ) );
			add_shortcode( 'xtender_carousel_mini', array( $this, 'carousel' ) );

			/** Register Assests */
			add_action( 'wp_enqueue_scripts', array( $this, 'register_assets' ) );
		}



		/** Assets Hook */
		function register_assets(){

		}


		public function carousel( $atts, $content = null ){

			$atts = shortcode_atts( array(
				'hover'	=> null,
				'loop'	=> null,
				'dots'	=> null,
				'autoplay_speed'	=> 2000,
				'css' => '',
				'el_css' => null
			), $atts, 'xtender_carousel_mini' );

			extract( $atts );

			$html = null;

			$hover	= filter_var( $atts['hover'], FILTER_VALIDATE_BOOLEAN );
			$hover	= $hover ? 'true' : 'false';

			$loop		= filter_var( $atts['loop'], FILTER_VALIDATE_BOOLEAN );
			$loop   = $loop ? 'true' : 'false';

			$dots		= filter_var( $atts['dots'], FILTER_VALIDATE_BOOLEAN );
			$dots   = ! $dots ? 'true' : 'false';

      $nav = 'false';
      $nav_text = array();
      $nav_text = wp_json_encode( $nav_text );

			if ( $atts['autoplay_speed'] ) {
				$autoplay = 'true';
				$autoplay_speed = $atts['autoplay_speed'];
			} else {
				$autoplay = 'false';
				$autoplay_speed = 0;
			}

			$el_css .= ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), 'xtender_carousel_mini', $atts );

			$html .= "<div class='xtd-carousel-mini__container {$el_css}'><div class='xtd-carousel-mini'><div class='owl-carousel' data-autoplay='$autoplay' data-timeout='$autoplay_speed' data-hover='$hover' data-loop='$loop' data-dots='$dots'>". do_shortcode( $content ) ."</div></div></div>";

			return $html;

		}


		/** Visual Composer Services Carousel */
		public function vc_carousel_mini() {

			/** Carousel Container */
      vc_map( array(
			   "name" => __("Mini Carousel", 'xtender'),
			   "base" => "xtender_carousel_mini",
			   "show_settings_on_create" => true,
				 "as_parent" => array( 'only' => 'vc_single_image' ),
			  // "admin_enqueue_css" => array( XTENDER_URL . 'assets/admin/css/vc-icon.css' ),
			   "class" => "",
			   "category" => __('xtender', 'xtender'),
			   "params" => array(
			      array(
			         "type" => "textfield",
			         "heading" => __("Timeout", 'xtender'),
			         "param_name" => "autoplay_speed",
			         "value" => 2000,
			         "description" => __("Choose the carousel timeout in milliseconds. Leave blank to disable the autoplay", 'xtender')
			      ),
			      array(
			         "type" => "checkbox",
			         "heading" => __("Pause on hover", 'xtender'),
			         "param_name" => "hover",
			         'value' => array( __( 'Yes, pause carousel on hover', 'xtender' ) => 'yes' )
			      ),
			      array(
			         "type" => "checkbox",
			         "heading" => __("Loop", 'xtender'),
			         'edit_field_class' => 'vc_col-sm-6 vc_column',
			         "param_name" => "loop",
			         'value' => array( __( 'Yes, play the carousel in a loop', 'xtender' ) => 'yes' )
			      ),
						array(
			         "type" => "checkbox",
			         "heading" => __("Navigation", 'xtender'),
			         'edit_field_class' => 'vc_col-sm-6 vc_column',
			         "param_name" => "dots",
			         'value' => array( __( 'Disable the dots navigation', 'xtender' ) => 'yes' )
			      ),
						array(
			         "type" => "css_editor",
			         "heading" => __("Style", 'xtender'),
			         'edit_field_class' => 'vc_col-sm-12 vc_column',
							 'group' => __( 'Design options', 'xtender' ),
			         "param_name" => "css"
			      ),
						array(
 							"type" => "textfield",
 							"heading" => __("CSS Classes", 'xtender'),
 							"param_name" => "el_css",
 							"value" => null
 					 )
			   ),
			   "js_view" => 'VcColumnView'
			) );


		}


	}

$xtender_carousel_mini =	new xtenderVCCarouselMini();

function xtender_carousel_mini_before_init(){

	/** Extend Classes */
	if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
	    class WPBakeryShortCode_Xtender_Carousel_Mini extends WPBakeryShortCodesContainer {}
	}

}

add_action( 'vc_before_init', 'xtender_carousel_mini_before_init' );

?>
