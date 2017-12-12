<?php

	class xtenderVCCarouselFilmstrip{

		function __construct(){

			/** Construct Services Carousel */
			add_action( 'vc_before_init', array( $this, 'vc_carousel_filmstrip' ) );
			add_shortcode( 'xtender_carousel_filmstrip', array( $this, 'services_carousel' ) );

			/** Register Assests */
			add_action( 'wp_enqueue_scripts', array( $this, 'register_assets' ) );
		}



		/** Assets Hook */
		function register_assets(){

		}


		public function services_carousel( $atts, $content = null ){

			$atts = shortcode_atts( array(
				'grayscale'	=> null,
				'speed'	=> 100000,
				'offset' => null,
				'css' => '',
				'el_css' => null
			), $atts, 'xtender_carousel_filmstrip' );

			extract( $atts );

			$html = null;

			$hover	= filter_var( $grayscale, FILTER_VALIDATE_BOOLEAN );
			$hover	= $hover ? '' : ' filtered-image';
			$speed 	= empty( $speed ) ? 100000 : $speed;

			$el_css .= ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), 'xtender_carousel_mini', $atts );

			$html .= "<div class='xtd-carousel-filmstrip__container xtd-carousel-filmstrip--offset-$offset $hover {$el_css}'><div class='xtd-carousel-filmstrip' data-speed='$speed'>".do_shortcode( $content )."</div></div>";

			return $html;

		}


		/** Visual Composer Services Carousel */
		public function vc_carousel_filmstrip() {

			/** Carousel Container */
			vc_map( array(
			   "name" => __("Filmstrip Carousel", 'xtender'),
			   "base" => "xtender_carousel_filmstrip",
			   "as_parent" => array( 'vc_single_image', 'xtender_person' ),
			   'is_container' => true,
			   "show_settings_on_create" => false,
			  // "admin_enqueue_css" => array( XTENDER_URL . 'assets/admin/css/vc-icon.css' ),
			   "class" => "",
			   "category" => __('xtender', 'xtender'),
			   "params" => array(
			      array(
			         "type" => "textfield",
			         "heading" => __("Speed", 'xtender'),
			         "param_name" => "speed",
			         "value" => 100000,
			         "description" => __("Choose the carousel speed in milliseconds. Default is: 100000", 'xtender')
			      ),
						array(
			         "type" => "dropdown",
			         "heading" => __("Offset", 'xtender'),
			         "param_name" => "offset",
							 "value" => array(
								__( 'No offset', 'xtender' ) => '',
								__( 'Top', 'xtender' ) => 'top',
								__( 'Bottom', 'xtender' ) => 'bottom'
							 ),
			         "description" => __("Choose if you wish to offset", 'xtender')
			      ),
			      array(
			         "type" => "checkbox",
			         "heading" => __("Grayscale Images", 'xtender'),
			         "param_name" => "grayscale",
			         'value' => array( __( 'Yes, disable the grayscale effect. Only works in modern browsers.', 'xtender' ) => 'yes' )
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

$xtender_carousel_filmstrip =	new xtenderVCCarouselFilmstrip();

function xtender_carousel_filmstrip_before_init(){

	/** Extend Classes */
	if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
	    class WPBakeryShortCode_Xtender_Carousel_Filmstrip extends WPBakeryShortCodesContainer {}
	}

}

add_action( 'vc_before_init', 'xtender_carousel_filmstrip_before_init' );



?>
