<?php

	class xtenderVCPricingListRow{

		function __construct(){

			/** Construct Services Carousel */
			add_action( 'vc_before_init', array( $this, 'vc_pricing_list_row' ) );
			add_shortcode( 'xtender_pricing_list_row', array( $this, 'pricing_list_row' ) );

		}



		public function pricing_list_row( $atts, $content = null ){

			$atts = shortcode_atts( array(
				'title'	=> null,
        'price'	=> null,
        'description'	=> null,
				'css' => null,
				'el_css' => null
			), $atts, 'xtender_pricing_list_row' );

			extract( $atts );

			$el_css = ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), 'xtender_pricing_list_row', $atts );

      if( ! is_null( $title ) && ! empty( $title ) ){
        $title  = "<div class='xtd-pricing-list-row__title color--h1'>{$title}";
        $title .= ! is_null( $price ) && ! empty( $price ) ? "<div class='xtd-pricing-list-row__spacer'></div><div class='xtd-pricing-list-row__price color-primary'>{$price}</div>" : '';
        $title .= '</div>';
      }

      $description = ! is_null( $description ) && ! empty( $description ) ? "<div class='xtd-pricing-list-row__description'>{$description}</div>" : '';

			$html = "<div class='xtd-pricing-list-row {$el_css}'>{$title}{$description}</div>";

			return $html;

		}


		/** Visual Composer Services Carousel */
		public function vc_pricing_list_row() {

			/** Carousel Container */
			vc_map( array(
			   "name" => __("Pricing List Row", 'xtender'),
			   "base" => "xtender_pricing_list_row",
			   "show_settings_on_create" => true,
			   "category" => __('xtender', 'xtender'),
			   "params" => array(
					array(
 							"type" => "textfield",
 							"heading" => __("Title", 'xtender'),
 							"param_name" => "title",
              "holder" => "div",
							'edit_field_class' => 'vc_col-sm-6',
 							"value" => null
 					),
          array(
 							"type" => "textfield",
 							"heading" => __("Price", 'xtender'),
 							"param_name" => "price",
							'edit_field_class' => 'vc_col-sm-6',
 							"value" => null
 					),
          array(
 							"type" => "textfield",
 							"heading" => __("Description", 'xtender'),
 							"param_name" => "description",
							'edit_field_class' => 'vc_col-sm-12',
 							"value" => null
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

$xtender_pricing_list_row =	new xtenderVCPricingListRow();

?>
