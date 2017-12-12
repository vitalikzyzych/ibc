<?php

	class xtenderVCTimeline{

		function __construct(){

			/** Construct Services Carousel */
			add_action( 'vc_before_init', array( $this, 'vc_timeline' ) );
			add_shortcode( 'xtender_timeline', array( $this, 'timeline' ) );
			add_shortcode( 'xtender_timeline_item', array( $this, 'timeline_item' ) );

			/** Register Assests */
			add_action( 'wp_enqueue_scripts', array( $this, 'register_assets' ) );
		}



		/** Assets Hook */
		function register_assets(){

		}


		public static function timeline_item( $atts, $content = null ){
			$atts = shortcode_atts( array(
				'title'	=> null,
				'loop'	=> null,
				'dots'	=> null,
				'autoplay_speed'	=> 2000
			), $atts, 'xtender_title' );

			extract( $atts );

			$html = null;
			$html .= "<div class='xtd-timeline__item' data-title='{$title}'>". do_shortcode( $content ) ."</div>";

			return do_shortcode($html);
		}


		public function timeline( $atts, $content = null ){


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

			$el_css .= ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), 'xtender_carousel_mini', $atts );

			$html .= "<div class='xtd-timeline {$el_css}'>". do_shortcode( $content ) ."</div>";

			return $html;

		}


		/** Visual Composer Services Carousel */
		public function vc_timeline() {

			/** Carousel Container */
      vc_map( array(
			   "name" => __("Timeline", 'xtender'),
			   "base" => "xtender_timeline",
			   "show_settings_on_create" => true,
				 "is_container" => true,
				 "as_parent" => array( 'only' => 'xtender_timeline_item' ),
			   "admin_enqueue_css" => array( XTENDER_URL . 'assets/admin/css/vc-icon.css' ),
			   "class" => "",
			   "category" => __('xtender', 'xtender'),
			   "params" => array(
			      array(
			         "type" => "textfield",
			         "heading" => __("Title", 'xtender'),
			         "param_name" => "title",
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
			      )
			   ),
			   "js_view" => 'VcColumnView'
			) );

			/** Carousel Container */
      vc_map( array(
			   "name" => __("Timeline Item", 'xtender'),
			   "base" => "xtender_timeline_item",
			   "show_settings_on_create" => true,
				 "is_container" => true,
				 'as_child' => array( 'only' => 'xtender_timeline' ),
			   "admin_enqueue_css" => array( XTENDER_URL . 'assets/admin/css/vc-icon.css' ),
			   "class" => "",
			   "category" => __('xtender', 'xtender'),
			   "params" => array(
			      array(
			         "type" => "textfield",
			         "heading" => __("Title", 'xtender'),
			         "param_name" => "title",
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

$xtender_carousel_mini =	new xtenderVCTimeline();

function xtender_timeline_before_init(){

	/** Extend Classes */
	if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
	    class WPBakeryShortCode_Xtender_Timeline extends WPBakeryShortCodesContainer {}
			class WPBakeryShortCode_Xtender_Timeline_Item extends WPBakeryShortCodesContainer {}
	}

}

add_action( 'vc_before_init', 'xtender_timeline_before_init' );

?>
