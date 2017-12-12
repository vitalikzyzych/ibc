<?php

	class xtenderVCGmap{

		function __construct(){

			/** Construct Services Carousel */
			add_action( 'vc_before_init', array( $this, 'vc_gmap' ) );
			add_shortcode( 'xtender_gmap', array( $this, 'gmap' ) );

			add_action( 'wp_ajax_xtd_get_map_key', array( $this, 'ajax_get_map_key' ) );
			add_action( 'wp_ajax_nopriv_xtd_get_map_key', array( $this, 'ajax_get_map_key' ) );

			add_action( 'wp_enqueue_scripts', array( $this, 'register_assets' ) );

		}


		function register_assets(){

			if( ! wp_script_is( 'curly-google-maps', 'registered' ) ){
				$google_maps_api_key = esc_attr( get_theme_mod( 'maps_api_key', 'AIzaSyArPwtdP09w4OeKGuRDjZlGkUshNh180z8' ) );
				$google_maps_api_key = ! empty( $google_maps_api_key ) ? $google_maps_api_key : 'AIzaSyArPwtdP09w4OeKGuRDjZlGkUshNh180z8';
				wp_register_script(
					'curly-google-maps',
					add_query_arg( array( 'key' => $google_maps_api_key ), 'https://maps.googleapis.com/maps/api/js' ),
					array( 'jquery' ),
					null,
					true
				);
			}



			wp_register_script(
				'curly-gmaps3',
				XTENDER_URL . 'dev/libs/gmaps3/gmap3.min.js',
				array( 'jquery', 'curly-google-maps' ),
				filter_var( WP_DEBUG, FILTER_VALIDATE_BOOLEAN ) ? rand() : null
			);

		}

		function ajax_get_map_key(){

			$google_maps_api_key = esc_attr( get_theme_mod( 'maps_api_key', 'AIzaSyArPwtdP09w4OeKGuRDjZlGkUshNh180z8' ) );
			$google_maps_api_key = ! empty( $google_maps_api_key ) ? $google_maps_api_key : 'AIzaSyArPwtdP09w4OeKGuRDjZlGkUshNh180z8';

			//echo $google_maps_api_key;

			wp_die();

		}



		public function gmap( $atts, $content = null ){

			if( ! wp_script_is( 'curly-gmaps3' ) && ! wp_script_is( 'wcs-gmaps' ) )
				wp_enqueue_script( 'curly-gmaps3' );

			$atts = shortcode_atts( array(
				'latitude'	=> null,
				'longitude'	=> null,
				'latitude_center'	=> null,
				'longitude_center'	=> null,
				'type'	=> 'ROADMAP',
				'zoom'	=> 6,
				'theme'	=> '',
				'height'	=> 500,
				'image'	=> null,
				'title'	=> null,
				'description' => null,
				'css' => '',
				'el_css' => null
			), $atts, 'xtender_gmap' );

			extract( $atts );

			if( empty( $latitude ) || empty( $longitude ) || is_null( $latitude ) || is_null( $longitude ) )
				return;

			preg_match('/\d+/', $height, $height);

			$latitude_center 	= is_null( $latitude_center ) || empty( $latitude_center ) 	? $latitude 	: $latitude_center;
			$longitude_center = is_null( $longitude_center ) || empty( $longitude_center ) 	? $longitude 	: $longitude_center;

			$height = intval( $height[0] ) >= 400 ? $height[0] : 400;

			if( ! is_null( $theme ) && ! empty( $theme ) ){

				switch( true ){

					case intval( $theme ) === 1 :
						$theme = '[{"featureType":"all","elementType":"labels.text.fill","stylers":[{"saturation":36},{"color":"#000000"},{"lightness":40}]},{"featureType":"all","elementType":"labels.text.stroke","stylers":[{"visibility":"on"},{"color":"#000000"},{"lightness":16}]},{"featureType":"all","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#000000"},{"lightness":20}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#000000"},{"lightness":17},{"weight":1.2}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":20}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":21}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#000000"},{"lightness":17}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#000000"},{"lightness":29},{"weight":0.2}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":18}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":16}]},{"featureType":"transit","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":19}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":17}]}]';
						break;

					case intval( $theme ) === 2 :
						$theme = '[{"featureType":"all","elementType":"all","stylers":[{"hue":"#ff0000"},{"saturation":-100},{"lightness":-30}]},{"featureType":"all","elementType":"labels.text.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"all","elementType":"labels.text.stroke","stylers":[{"color":"#353535"}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#656565"}]},{"featureType":"poi","elementType":"geometry.fill","stylers":[{"color":"#505050"}]},{"featureType":"poi","elementType":"geometry.stroke","stylers":[{"color":"#808080"}]},{"featureType":"road","elementType":"geometry","stylers":[{"color":"#454545"}]},{"featureType":"transit","elementType":"labels","stylers":[{"hue":"#000000"},{"saturation":100},{"lightness":-40},{"invert_lightness":true},{"gamma":1.5}]}]';
						break;

					case intval( $theme ) === 3 :
						$theme = '[{"featureType":"administrative","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"visibility":"simplified"},{"color":"#fcfcfc"}]},{"featureType":"poi","elementType":"geometry","stylers":[{"visibility":"simplified"},{"color":"#fcfcfc"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"visibility":"simplified"},{"color":"#dddddd"}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"visibility":"simplified"},{"color":"#dddddd"}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"visibility":"simplified"},{"color":"#eeeeee"}]},{"featureType":"water","elementType":"geometry","stylers":[{"visibility":"simplified"},{"color":"#dddddd"}]}]';
						break;

					case intval( $theme ) === 4 :
						$theme = '[{"featureType":"water","elementType":"geometry.fill","stylers":[{"color":"#d3d3d3"}]},{"featureType":"transit","stylers":[{"color":"#808080"},{"visibility":"off"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"visibility":"on"},{"color":"#b3b3b3"}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"road.local","elementType":"geometry.fill","stylers":[{"visibility":"on"},{"color":"#ffffff"},{"weight":1.8}]},{"featureType":"road.local","elementType":"geometry.stroke","stylers":[{"color":"#d7d7d7"}]},{"featureType":"poi","elementType":"geometry.fill","stylers":[{"visibility":"on"},{"color":"#ebebeb"}]},{"featureType":"administrative","elementType":"geometry","stylers":[{"color":"#a7a7a7"}]},{"featureType":"road.arterial","elementType":"geometry.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"road.arterial","elementType":"geometry.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"landscape","elementType":"geometry.fill","stylers":[{"visibility":"on"},{"color":"#efefef"}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"color":"#696969"}]},{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"visibility":"on"},{"color":"#737373"}]},{"featureType":"poi","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"poi","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"road.arterial","elementType":"geometry.stroke","stylers":[{"color":"#d6d6d6"}]},{"featureType":"road","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{},{"featureType":"poi","elementType":"geometry.fill","stylers":[{"color":"#dadada"}]}]';
						break;

					case intval( $theme ) === 5 :
						$theme = '[{"featureType":"water","elementType":"geometry","stylers":[{"color":"#e9e9e9"},{"lightness":17}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#f5f5f5"},{"lightness":20}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#ffffff"},{"lightness":17}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#ffffff"},{"lightness":29},{"weight":0.2}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#ffffff"},{"lightness":18}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#ffffff"},{"lightness":16}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#f5f5f5"},{"lightness":21}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#dedede"},{"lightness":21}]},{"elementType":"labels.text.stroke","stylers":[{"visibility":"on"},{"color":"#ffffff"},{"lightness":16}]},{"elementType":"labels.text.fill","stylers":[{"saturation":36},{"color":"#333333"},{"lightness":40}]},{"elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"geometry","stylers":[{"color":"#f2f2f2"},{"lightness":19}]},{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#fefefe"},{"lightness":20}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#fefefe"},{"lightness":17},{"weight":1.2}]}]';
						break;

					case intval( $theme ) === 6 :
							$theme = '[ { "featureType": "administrative", "elementType": "geometry", "stylers": [ { "saturation": "2" }, { "visibility": "simplified" } ] }, { "featureType": "administrative", "elementType": "labels", "stylers": [ { "saturation": "-40" }, { "lightness": "-29" }, { "visibility": "on" }, { "weight": "0.89" }, { "gamma": "1" } ] }, { "featureType": "administrative", "elementType": "labels.text.fill", "stylers": [ { "color": "#727272" }, { "lightness": "0" } ] }, { "featureType": "landscape", "elementType": "all", "stylers": [ { "color": "#d8d8d8" }, { "saturation": "0" } ] }, { "featureType": "landscape", "elementType": "geometry.fill", "stylers": [ { "saturation": "-1" }, { "lightness": "-12" } ] }, { "featureType": "landscape.natural", "elementType": "labels.text", "stylers": [ { "lightness": "-31" } ] }, { "featureType": "landscape.natural", "elementType": "labels.text.fill", "stylers": [ { "lightness": "-75" } ] }, { "featureType": "landscape.natural", "elementType": "labels.text.stroke", "stylers": [ { "lightness": "65" } ] }, { "featureType": "landscape.natural.landcover", "elementType": "geometry", "stylers": [ { "lightness": "-15" }, { "gamma": "1" } ] }, { "featureType": "landscape.natural.landcover", "elementType": "geometry.fill", "stylers": [ { "lightness": "0" }, { "gamma": "1" } ] }, { "featureType": "poi", "elementType": "all", "stylers": [ { "visibility": "off" }, { "lightness": "0" } ] }, { "featureType": "road", "elementType": "all", "stylers": [ { "saturation": -100 }, { "lightness": "24" } ] }, { "featureType": "road", "elementType": "geometry", "stylers": [ { "visibility": "on" }, { "saturation": "0" }, { "lightness": "-30" } ] }, { "featureType": "road", "elementType": "geometry.stroke", "stylers": [ { "lightness": "-14" }, { "visibility": "on" } ] }, { "featureType": "road", "elementType": "labels", "stylers": [ { "lightness": "-55" }, { "gamma": "1" }, { "weight": "1.39" } ] }, { "featureType": "road", "elementType": "labels.text", "stylers": [ { "lightness": "0" } ] }, { "featureType": "road", "elementType": "labels.text.fill", "stylers": [ { "lightness": "36" } ] }, { "featureType": "road", "elementType": "labels.text.stroke", "stylers": [ { "lightness": "46" }, { "weight": "0.01" } ] }, { "featureType": "road.highway", "elementType": "all", "stylers": [ { "visibility": "simplified" }, { "lightness": "3" }, { "saturation": "-86" } ] }, { "featureType": "road.highway", "elementType": "labels.icon", "stylers": [ { "lightness": "-13" }, { "weight": "1.23" }, { "invert_lightness": true }, { "visibility": "simplified" }, { "hue": "#ff0000" } ] }, { "featureType": "road.arterial", "elementType": "labels.icon", "stylers": [ { "visibility": "off" } ] }, { "featureType": "transit", "elementType": "all", "stylers": [ { "visibility": "off" } ] }, { "featureType": "water", "elementType": "all", "stylers": [ { "color": "#adadad" }, { "visibility": "on" } ] } ]';
							break;
				}

			}

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

			$el_css .= ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), 'xtender_carousel_mini', $atts );

			$html = "<div class='xtd-gmap {$el_css}' $wrapper_attributes></div>";

			return $html;

		}


		/** Visual Composer Services Carousel */
		public function vc_gmap() {

			/** Carousel Container */
			vc_map( array(
			   "name" => __("Google Map", 'xtender'),
			   "base" => "xtender_gmap",
			   "show_settings_on_create" => true,
			  // "admin_enqueue_css" => array( XTENDER_URL . 'assets/admin/css/vc-icon.css' ),
			   "class" => "",
			   "category" => __('xtender', 'xtender'),
			   "params" => array(
					 array(
							"type" => "textfield",
							"heading" => __("Map Pin Latitude", 'xtender'),
							"param_name" => "latitude",
							"value" => null,
							"description" => __("Enter the map latitude coordinates in decimal format. (ie: 51.5085300)", 'xtender')
					 ),
					 array(
							"type" => "textfield",
							"heading" => __("Map Pin Longitude", 'xtender'),
							"param_name" => "longitude",
							"value" => null,
							"description" => __("Enter the map longitude coordinates in decimal format. (ie: -0.1257400)", 'xtender')
					 ),
			   	  array(
			   	     "type" => "textfield",
			   	     "heading" => __("Map Center Latitude", 'xtender'),
			   	     "param_name" => "latitude_center",
							 'edit_field_class' => 'vc_col-sm-6',
			   	     "value" => null,
			   	     "description" => __("Leave Empty to inherit pin latitude", 'xtender')
			   	  ),
						array(
			   	     "type" => "textfield",
			   	     "heading" => __("Map Center Longitude", 'xtender'),
			   	     "param_name" => "longitude_center",
							 'edit_field_class' => 'vc_col-sm-6',
			   	     "value" => null,
			   	     "description" => __("Leave empty to inherit pin longitude", 'xtender')
			   	  ),
						array(
			   	     "type" => "dropdown",
			   	     "heading" => __("Map Type", 'xtender'),
			   	     "param_name" => "type",
							 'edit_field_class' => 'vc_col-sm-6',
			   	     "value" => array(
								__( 'Roadmap', 'xtender' ) => 'ROADMAP',
								__( 'Hybrid', 'xtender' ) => 'HYBRID',
								__( 'Satellite', 'xtender' ) => 'SATELLITE',
								__( 'Terrain', 'xtender' ) => 'TERRAIN'
							 ),
			   	     "description" => __("Choose your map type", 'xtender')
			   	  ),
						array(
			   	     "type" => "dropdown",
			   	     "heading" => __("Map Zoom", 'xtender'),
			   	     "param_name" => "zoom",
							 'edit_field_class' => 'vc_col-sm-3',
			   	     "value" => range(1,16),
							 'std'	=> 6,
			   	     "description" => __("Choose your map zoom", 'xtender')
			   	  ),
						array(
			   	     "type" => "dropdown",
			   	     "heading" => __("Map Theme", 'xtender'),
			   	     "param_name" => "theme",
							 'edit_field_class' => 'vc_col-sm-3',
			   	     "value" => array(
								__( 'Default', 'xtender' ) => '',
								__( 'Shades of Grey', 'xtender' ) => '1',
								__( 'Minimal Dark', 'xtender' ) => '2',
								__( 'Simple & Light', 'xtender' ) => '3',
								__( 'Light Gray', 'xtender' ) => '4',
								__( 'Ultra Light with Labels', 'xtender' ) => '5',
								__( 'Simple Gray', 'xtender' ) => '6'
							 ),
			   	     "description" => __("Choose your map theme", 'xtender')
			   	  ),
			   	  array(
			   	     "type" => "textfield",
			   	     "heading" => __("Map Height", 'xtender'),
			   	     "param_name" => "height",
			   	     "value" => 500,
			   	     "description" => __("Enter you map height in pixels.", 'xtender')
			   	  ),
			   	  array(
			   	     "type" => "attach_image",
			   	     "heading" => __("Map Image", 'xtender'),
			   	     "param_name" => "image",
			   	     "description" => __("Choose your map image", 'xtender')
			   	  ),
						array(
			   	     "type" => "textfield",
			   	     "heading" => __("Map Title", 'xtender'),
			   	     "param_name" => "title",
			   	     "description" => __("Enter you map title", 'xtender')
			   	  ),
						array(
			   	     "type" => "textarea",
			   	     "heading" => __("Map Description", 'xtender'),
			   	     "param_name" => "description",
			   	     "description" => __("Enter you map description", 'xtender')
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

$xtender_gmap3 =	new xtenderVCGmap();

?>
