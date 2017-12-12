<?php

	class xtenderVCCard{

		function __construct(){

			/** Construct Services Carousel */
			add_action( 'vc_before_init', array( $this, 'vc_card' ) );
			add_shortcode( 'xtender_card', array( $this, 'card' ) );

		}



		public function card( $atts, $content = null ){

			$atts = shortcode_atts( array(
				'image'	=> null,
				'title'	=> null,
				'subtitle'	=> null,
				'link' => null,
				'more' => null,
				'css' => '',
				'el_css' => null
			), $atts, 'xtender_card' );

			extract( $atts );

			$el_css .= ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), 'xtender_carousel_mini', $atts );
			$link = vc_build_link( $link );
			
			$img    = ! is_null( $image ) && ! empty( $image  ) ? wp_get_attachment_image( $image, 'large' ) : '';
			$title  = esc_attr( $title );
			$subtitle = esc_attr( $subtitle );

			ob_start();

			$template = xtender_get_template_part( 'vc/card' );

			if( isset( $template ) && ! empty( $template ) && $template !== false ){
				include( $template );
			}

			return ob_get_clean();

		}


		/** Visual Composer Services Carousel */
		public function vc_card() {

			/** Carousel Container */
			vc_map( array(
			   "name" => __("Card", 'xtender'),
			   "base" => "xtender_card",
			   "show_settings_on_create" => true,
			  // "admin_enqueue_css" => array( XTENDER_URL . 'assets/admin/css/vc-icon.css' ),
			   "class" => "",
			   "category" => __('xtender', 'xtender'),
			   "params" => array(
					 array(
							"type" => "attach_image",
							"heading" => __("Image", 'xtender'),
							"param_name" => "image",
							"value" => null,
							"description" => __("Choose the image", 'xtender')
					 ),
					 array(
							"type" => "vc_link",
							"heading" => __("Card Link", 'xtender'),
							"param_name" => "link",
							"value" => null,
							"description" => __("Choose the page", 'xtender')
					 ),
					 array(
							"type" => "textfield",
							"heading" => __("Title", 'xtender'),
							"param_name" => "title",
							"holder" => "div",
							'edit_field_class' => 'vc_col-sm-4',
							"value" => null,
					 ),
					 array(
							"type" => "textfield",
							"heading" => __("Subtitle", 'xtender'),
							"param_name" => "subtitle",
							'edit_field_class' => 'vc_col-sm-4',
							"value" => null,
					 ),
					 array(
							"type" => "textarea_html",
							"heading" => __("Person Description", 'xtender'),
							"param_name" => "content",
							"value" => null,
					 ),
					 array(
						 "type" => "textfield",
						 "heading" => __("Read More", 'xtender'),
						 "param_name" => "more",
						 'edit_field_class' => 'vc_col-sm-4',
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

$xtender_card =	new xtenderVCCard();

?>
