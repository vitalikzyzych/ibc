<?php

	class xtenderVCPerson{

		function __construct(){

			/** Construct Services Carousel */
			add_action( 'vc_before_init', array( $this, 'vc_person' ) );
			add_shortcode( 'xtender_person', array( $this, 'person' ) );

		}



		public function person( $atts, $content = null ){

			$atts = shortcode_atts( array(
				'image'	=> null,
				'person_name'	=> null,
				'person_title'	=> null,
				'person_position'	=> null,
				'link' => null,
				'css' => '',
				'el_css' => null
			), $atts, 'xtender_gmap' );

			extract( $atts );

			$el_css .= ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), 'xtender_carousel_mini', $atts );
			$link = vc_build_link( $link );

			$html = '';

			$template = xtender_get_template_part( 'vc/banner.person' );

			if( isset( $template ) && ! empty( $template ) && $template !== false ){
				include( $template );
			}

			return $html;

		}


		/** Visual Composer Services Carousel */
		public function vc_person() {

			/** Carousel Container */
			vc_map( array(
			   "name" => __("Person", 'xtender'),
			   "base" => "xtender_person",
			   "show_settings_on_create" => true,
			  // "admin_enqueue_css" => array( XTENDER_URL . 'assets/admin/css/vc-icon.css' ),
			   "class" => "",
			   "category" => __('xtender', 'xtender'),
			   "params" => array(
					 array(
							"type" => "attach_image",
							"heading" => __("Person Image", 'xtender'),
							"param_name" => "image",
							"value" => null,
							"description" => __("Choose the person image", 'xtender')
					 ),
					 array(
							"type" => "vc_link",
							"heading" => __("Person Profile Page", 'xtender'),
							"param_name" => "link",
							"value" => null,
							"description" => __("Choose the person profile page", 'xtender')
					 ),
					 array(
							"type" => "textfield",
							"heading" => __("Person Name", 'xtender'),
							"param_name" => "person_name",
							'edit_field_class' => 'vc_col-sm-4',
							"value" => null,
					 ),
					 array(
							"type" => "textfield",
							"heading" => __("Person Title", 'xtender'),
							"param_name" => "person_title",
							'edit_field_class' => 'vc_col-sm-4',
							"value" => null,
					 ),
					 array(
							"type" => "textfield",
							"heading" => __("Person Position", 'xtender'),
							"param_name" => "person_position",
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

$xtender_person =	new xtenderVCPerson();

?>
