<?php

	class xtenderVCShape{

		function __construct(){

			/** Construct Services Carousel */
			add_action( 'vc_before_init', array( $this, 'vc_shape' ) );
			add_shortcode( 'xtender_shape', array( $this, 'shape' ) );

		}



		public function shape( $atts, $content = null ){

			$atts = shortcode_atts( array(
				'shape'	=> 'square',
				'depth'	=> '0',
				'position' => false,
				'width' => '',
				'height' => '',
				'top' => '',
				'right' => '',
				'bottom' => '',
				'left' => '',
				'opacity' => '',
				'css' => null,
				'el_css' => null
			), $atts, 'xtender_gmap' );

			extract( $atts );

			$styles = array();

			$el_css .= filter_var( $position, FILTER_VALIDATE_BOOLEAN ) ? ' xtd-shape--position-absolute' : '';
			$el_css .= " xtd-shape--z-index-{$depth}";

			if( $width !== '' ){
				$styles[] = 'width: ' . esc_attr( $width );
			}
			if( $height !== '' ){
				$styles[] = 'height: ' . esc_attr( $height );
			}
			if( $top !== '' ){
				$styles[] = 'top: ' . esc_attr( $top );
			}
			if( $right !== '' ){
				$styles[] = 'right: ' . esc_attr( $right );
			}
			if( $bottom !== '' ){
				$styles[] = 'bottom: ' . esc_attr( $bottom );
			}
			if( $left !== '' ){
				$styles[] = 'left: ' . esc_attr( $left );
			}
			if( $opacity !== '' ){
				$styles[] = 'opacity: ' . (float)esc_attr( $opacity );
			}

			$styles = ' style="' . implode( '; ', $styles ) . '" ';

			$el_css .= ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), 'xtender_carousel_mini', $atts );

			$html = "<div class='xtd-shape {$el_css}' $styles></div>";

			return $html;

		}


		/** Visual Composer Services Carousel */
		public function vc_shape() {

			/** Carousel Container */
			vc_map( array(
			   "name" => __("Shape", 'xtender'),
			   "base" => "xtender_shape",
			   "show_settings_on_create" => true,
			   "category" => __('xtender', 'xtender'),
			   "params" => array(
					 array(
 						 "type" => "dropdown",
 						 "heading" => __("Shape Form", 'xtender'),
 						 "param_name" => "shape",
 						 'edit_field_class' => 'vc_col-sm-6',
 						 "value" => array(
		 						__( 'Square', 'xtender' ) => 'square',
		 						__( 'Round', 'xtender' ) => 'round',
	 					 ),
 						 "description" => __("Choose your shape form", 'xtender')
 					),
					array(
						"type" => "dropdown",
						"heading" => __("Depth", 'xtender'),
						"param_name" => "depth",
						'edit_field_class' => 'vc_col-sm-6',
						'std' => '0',
						"value" => array(
							 __( '-1', 'xtender' ) => '-1',
							 __( '0', 'xtender' ) => '0',
							 __( '1', 'xtender' ) => '1',
							 __( '2', 'xtender' ) => '2',
							 __( '3', 'xtender' ) => '3',
						),
						"description" => __("Choose your shape depth", 'xtender')
					),
					array(
 							"type" => "textfield",
 							"heading" => __("Width", 'xtender'),
 							"param_name" => "width",
							'edit_field_class' => 'vc_col-sm-6',
 							"value" => null
 					),
					array(
 							"type" => "textfield",
 							"heading" => __("Height", 'xtender'),
 							"param_name" => "height",
							'edit_field_class' => 'vc_col-sm-6',
 							"value" => null
 					),
					array(
 						 "type" => "checkbox",
 						 "heading" => __("Position Absolute ?", 'xtender'),
 						 'edit_field_class' => 'vc_col-sm-12 vc_column',
 						 "param_name" => "position"
 					),
					array(
 							"type" => "textfield",
 							"heading" => __("Top", 'xtender'),
 							"param_name" => "top",
							'edit_field_class' => 'vc_col-sm-3',
 							"value" => null,
							'dependency' => array(
								'element' => 'position',
								'value' => array( 'yes', true, 'true' )
							)
 					),
					array(
 							"type" => "textfield",
 							"heading" => __("Right", 'xtender'),
 							"param_name" => "right",
							'edit_field_class' => 'vc_col-sm-3',
 							"value" => null,
							'dependency' => array(
								'element' => 'position',
								'value' => array( 'yes', true, 'true' )
							)
 					),
					array(
 							"type" => "textfield",
 							"heading" => __("Bottom", 'xtender'),
 							"param_name" => "bottom",
							'edit_field_class' => 'vc_col-sm-3',
 							"value" => null,
							'dependency' => array(
								'element' => 'position',
								'value' => array( 'yes', true, 'true' )
							)
 					),
					array(
 							"type" => "textfield",
 							"heading" => __("Left", 'xtender'),
 							"param_name" => "left",
							'edit_field_class' => 'vc_col-sm-3',
 							"value" => null,
							'dependency' => array(
								'element' => 'position',
								'value' => array( 'yes', true, 'true' )
							)
 					),
					array(
 							"type" => "textfield",
 							"heading" => __("Opacity", 'xtender'),
 							"param_name" => "opacity",
							'edit_field_class' => 'vc_col-sm-6',
 							"value" => null,
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
				)
			) );

		}


	}

$xtender_shape =	new xtenderVCShape();

?>
