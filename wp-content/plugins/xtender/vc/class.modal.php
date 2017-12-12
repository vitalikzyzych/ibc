<?php

	class xtenderVCModal{

		function __construct(){

			/** Construct Services Carousel */
			add_action( 'vc_before_init', array( $this, 'vc_modal' ) );
			add_shortcode( 'xtender_modal', array( $this, 'modal' ) );

		}




		public function modal( $atts, $content = null ){

			$atts = shortcode_atts( array(
				'cookie_count'	=> 0,
				'show_on_load'	=> null,
				'el_css' => null
			), $atts, 'xtender_gmap' );

			extract( $atts );

			$wrapper_attributes = array();
			$wrapper_attributes[] = "data-latitude='$latitude'";
			$wrapper_attributes[] = "data-longitude='$longitude'";
			$wrapper_attributes[] = "data-latitude-center='$latitude_center'";
			$wrapper_attributes[] = "data-longitude-center='$longitude_center'";
			$wrapper_attributes[] = "data-type='$type'";
			$wrapper_attributes[] = "data-zoom='$zoom'";
			$wrapper_attributes[] = "data-theme='$theme'";
			$wrapper_attributes[] = "data-height='$height'";

			if( ! is_null( $image ) && ! empty( $image ) ){
				$image = wp_get_attachment_image_src( $image, 'medium' );
				$wrapper_attributes[] = "data-image-src='{$image[0]}'";
			}

			if( ! is_null( $title ) && ! empty( $title ) )
				$wrapper_attributes[] = "data-title='$title'";

				if( ! is_null( $description ) && ! empty( $description ) ){
					$description = wpautop( $description );
					$wrapper_attributes[] = "data-description='$description'";
				}

			$wrapper_attributes   = implode(' ', $wrapper_attributes );

			$html = "<div class='xtd-modal {$el_css}' $wrapper_attributes></div>";

			return $html;

		}


		/** Visual Composer Services Carousel */
		public function vc_modal() {

			/** Carousel Container */
			vc_map( array(
			   "name" => __("Modal", 'xtender'),
			   "base" => "xtender_modal",
			   "show_settings_on_create" => false,
				 "is_container" => true,
			  // "admin_enqueue_css" => array( XTENDER_URL . 'assets/admin/css/vc-icon.css' ),
			   "params" => array(
					 array(
							"type" => "checkbox",
							"heading" => __("Show on page load", 'xtender'),
							"param_name" => "show_on_load",
							"value" => null,
					 ),
			   )
			) );

		}


	}

	$xtender_modal =	new xtenderVCModal();

	function xtender_modal_before_init(){

		/** Extend Classes */
		if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
		    class WPBakeryShortCode_Xtender_Modal extends WPBakeryShortCodesContainer {}
		}

	}

	add_action( 'vc_before_init', 'xtender_modal_before_init' );

?>
