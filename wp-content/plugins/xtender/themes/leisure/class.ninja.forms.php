<?php

class XtenderLeisureNinjaForms {

	public $_ninja_localization;

	function __construct() {

		/** Add Styles */
		add_action( 'wp_enqueue_scripts', array ($this, 'load_scripts' ), 100 );

		/** Construct Ninja Forms Extension for Visual Composer */
		if ( defined( 'WPB_VC_VERSION' ) ){

			add_action( 'vc_before_init', array( $this, 'ninja_vc' ) );
			add_action( 'vc_before_init', array( $this, 'ninja_button_vc' ) );
			add_shortcode( 'curly_ninja', array( $this, 'ninja' ) );
			add_shortcode( 'curly_ninja_modal', array( $this, 'ninja_button' ) );

		}

		/** Form Title */
		add_filter( 'ninja_forms_form_title', array( $this, 'form_title' ), 10, 2 );

		add_action( 'init', array( $this, 'localize' ) );

	}



	function localize(){

		global $wp_locale;

		$months = array_values( $wp_locale->month );
		foreach( $months as $key => $month ){
			$months[$key] = "$month";
		}

		$months_short = array_values( $wp_locale->month_abbrev );
		foreach( $months_short as $key => $month ){
			$months_short[$key] = "$month";
		}

		$days = array_values( $wp_locale->weekday );
		foreach( $days as $key => $day ){
			$days[$key] = "$day";
		}

		$days_short = array_values( $wp_locale->weekday_abbrev );
		foreach( $days_short as $key => $day ){
			$days_short[$key] = "$day";
		}

		$days_min = array_values( $wp_locale->weekday_initial );
		foreach( $days_min as $key => $day ){
			$days_min[$key] = "$day";
		}

		$this->_ninja_localization = array(
			'months' => implode( ',', $months ),
			'months_short' => implode( ',', $months_short ),
			'days' => implode( ',', $days ),
			'days_short' => implode( ',', $days_short ),
			'days_min' => implode( ',', $days_min )
		);

	}



	/** Ninja Localization */
	function localize_datepicker( $datepicker_args ){

		global $wp_locale;

		$datepicker_args['monthNames'] = array_values( $wp_locale->month );
		$datepicker_args['monthNamesShort'] = array_values( $wp_locale->month_abbrev );
		$datepicker_args['dayNames'] = array_values( $wp_locale->weekday );
		$datepicker_args['dayNamesShort'] = array_values( $wp_locale->weekday_abbrev );
		$datepicker_args['dayNamesMin'] = array_values( $wp_locale->weekday_initial );

		return $datepicker_args;
	}

	/** Fix Form Titles */
	function form_title( $form_title, $form_id ) {
		return str_replace('h2', 'h3', $form_title);
	}

	/** Load Scripts */
	function load_scripts() {

		wp_enqueue_style( 'curly-ninja', XTENDER_URL . 'assets/front/css/ninja.css', null, rand(), 'all');

		$color_text				= new LeisureColor( get_theme_mod('text_color', '#667279') );
		$color_link 			= new LeisureColor( get_theme_mod('link_color', '#363D40') );
		$color_primary 		= new LeisureColor( get_theme_mod('primary_color', '#C0392B' ) );
		$color_bg 				= new LeisureColor( get_theme_mod('background_color', '#ffffff') );

		$svg_date = '<svg width="36px" height="22px" viewBox="0 0 36 22" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns">
		    <defs></defs>
		    <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage">
		        <path d="M18,4.71428571 L18,19 C18,19.3869067 17.8629821,19.7217248 17.5889423,20.0044643 C17.3149025,20.2872038 16.9903865,20.4285714 16.6153846,20.4285714 L1.38461538,20.4285714 C1.00961351,20.4285714 0.685097524,20.2872038 0.411057692,20.0044643 C0.137017861,19.7217248 0,19.3869067 0,19 L0,4.71428571 C0,4.32737902 0.137017861,3.99256094 0.411057692,3.70982143 C0.685097524,3.42708192 1.00961351,3.28571429 1.38461538,3.28571429 L2.76923077,3.28571429 L2.76923077,2.21428571 C2.76923077,1.72321183 2.93870023,1.30282913 3.27764423,0.953125 C3.61658823,0.603420871 4.02403608,0.428571429 4.5,0.428571429 L5.19230769,0.428571429 C5.66827161,0.428571429 6.07571946,0.603420871 6.41466346,0.953125 C6.75360746,1.30282913 6.92307692,1.72321183 6.92307692,2.21428571 L6.92307692,3.28571429 L11.0769231,3.28571429 L11.0769231,2.21428571 C11.0769231,1.72321183 11.2463925,1.30282913 11.5853365,0.953125 C11.9242805,0.603420871 12.3317284,0.428571429 12.8076923,0.428571429 L13.5,0.428571429 C13.9759639,0.428571429 14.3834118,0.603420871 14.7223558,0.953125 C15.0612998,1.30282913 15.2307692,1.72321183 15.2307692,2.21428571 L15.2307692,3.28571429 L16.6153846,3.28571429 C16.9903865,3.28571429 17.3149025,3.42708192 17.5889423,3.70982143 C17.8629821,3.99256094 18,4.32737902 18,4.71428571 Z M1.38461538,19 L4.5,19 L4.5,15.7857143 L1.38461538,15.7857143 L1.38461538,19 Z M5.19230769,19 L8.65384615,19 L8.65384615,15.7857143 L5.19230769,15.7857143 L5.19230769,19 Z M1.38461538,15.0714286 L4.5,15.0714286 L4.5,11.5 L1.38461538,11.5 L1.38461538,15.0714286 Z M5.19230769,15.0714286 L8.65384615,15.0714286 L8.65384615,11.5 L5.19230769,11.5 L5.19230769,15.0714286 Z M1.38461538,10.7857143 L4.5,10.7857143 L4.5,7.57142857 L1.38461538,7.57142857 L1.38461538,10.7857143 Z M9.34615385,19 L12.8076923,19 L12.8076923,15.7857143 L9.34615385,15.7857143 L9.34615385,19 Z M5.19230769,10.7857143 L8.65384615,10.7857143 L8.65384615,7.57142857 L5.19230769,7.57142857 L5.19230769,10.7857143 Z M13.5,19 L16.6153846,19 L16.6153846,15.7857143 L13.5,15.7857143 L13.5,19 Z M9.34615385,15.0714286 L12.8076923,15.0714286 L12.8076923,11.5 L9.34615385,11.5 L9.34615385,15.0714286 Z M5.53846154,5.42857143 L5.53846154,2.21428571 C5.53846154,2.11755904 5.50420707,2.03385452 5.43569712,1.96316964 C5.36718716,1.89248477 5.28605816,1.85714286 5.19230769,1.85714286 L4.5,1.85714286 C4.40624953,1.85714286 4.32512053,1.89248477 4.25661058,1.96316964 C4.18810062,2.03385452 4.15384615,2.11755904 4.15384615,2.21428571 L4.15384615,5.42857143 C4.15384615,5.5252981 4.18810062,5.60900262 4.25661058,5.6796875 C4.32512053,5.75037238 4.40624953,5.78571429 4.5,5.78571429 L5.19230769,5.78571429 C5.28605816,5.78571429 5.36718716,5.75037238 5.43569712,5.6796875 C5.50420707,5.60900262 5.53846154,5.5252981 5.53846154,5.42857143 Z M13.5,15.0714286 L16.6153846,15.0714286 L16.6153846,11.5 L13.5,11.5 L13.5,15.0714286 Z M9.34615385,10.7857143 L12.8076923,10.7857143 L12.8076923,7.57142857 L9.34615385,7.57142857 L9.34615385,10.7857143 Z M13.5,10.7857143 L16.6153846,10.7857143 L16.6153846,7.57142857 L13.5,7.57142857 L13.5,10.7857143 Z M13.8461538,5.42857143 L13.8461538,2.21428571 C13.8461538,2.11755904 13.8118994,2.03385452 13.7433894,1.96316964 C13.6748795,1.89248477 13.5937505,1.85714286 13.5,1.85714286 L12.8076923,1.85714286 C12.7139418,1.85714286 12.6328128,1.89248477 12.5643029,1.96316964 C12.4957929,2.03385452 12.4615385,2.11755904 12.4615385,2.21428571 L12.4615385,5.42857143 C12.4615385,5.5252981 12.4957929,5.60900262 12.5643029,5.6796875 C12.6328128,5.75037238 12.7139418,5.78571429 12.8076923,5.78571429 L13.5,5.78571429 C13.5937505,5.78571429 13.6748795,5.75037238 13.7433894,5.6796875 C13.8118994,5.60900262 13.8461538,5.5252981 13.8461538,5.42857143 Z" id="Type-something" fill="'.$color_text.'" sketch:type="MSShapeGroup"></path>
		    </g>
		</svg>';

		$svg_date_dark = '<svg width="36px" height="22px" viewBox="0 0 36 22" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns">
		    <defs></defs>
		    <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage">
		        <path d="M18,4.71428571 L18,19 C18,19.3869067 17.8629821,19.7217248 17.5889423,20.0044643 C17.3149025,20.2872038 16.9903865,20.4285714 16.6153846,20.4285714 L1.38461538,20.4285714 C1.00961351,20.4285714 0.685097524,20.2872038 0.411057692,20.0044643 C0.137017861,19.7217248 0,19.3869067 0,19 L0,4.71428571 C0,4.32737902 0.137017861,3.99256094 0.411057692,3.70982143 C0.685097524,3.42708192 1.00961351,3.28571429 1.38461538,3.28571429 L2.76923077,3.28571429 L2.76923077,2.21428571 C2.76923077,1.72321183 2.93870023,1.30282913 3.27764423,0.953125 C3.61658823,0.603420871 4.02403608,0.428571429 4.5,0.428571429 L5.19230769,0.428571429 C5.66827161,0.428571429 6.07571946,0.603420871 6.41466346,0.953125 C6.75360746,1.30282913 6.92307692,1.72321183 6.92307692,2.21428571 L6.92307692,3.28571429 L11.0769231,3.28571429 L11.0769231,2.21428571 C11.0769231,1.72321183 11.2463925,1.30282913 11.5853365,0.953125 C11.9242805,0.603420871 12.3317284,0.428571429 12.8076923,0.428571429 L13.5,0.428571429 C13.9759639,0.428571429 14.3834118,0.603420871 14.7223558,0.953125 C15.0612998,1.30282913 15.2307692,1.72321183 15.2307692,2.21428571 L15.2307692,3.28571429 L16.6153846,3.28571429 C16.9903865,3.28571429 17.3149025,3.42708192 17.5889423,3.70982143 C17.8629821,3.99256094 18,4.32737902 18,4.71428571 Z M1.38461538,19 L4.5,19 L4.5,15.7857143 L1.38461538,15.7857143 L1.38461538,19 Z M5.19230769,19 L8.65384615,19 L8.65384615,15.7857143 L5.19230769,15.7857143 L5.19230769,19 Z M1.38461538,15.0714286 L4.5,15.0714286 L4.5,11.5 L1.38461538,11.5 L1.38461538,15.0714286 Z M5.19230769,15.0714286 L8.65384615,15.0714286 L8.65384615,11.5 L5.19230769,11.5 L5.19230769,15.0714286 Z M1.38461538,10.7857143 L4.5,10.7857143 L4.5,7.57142857 L1.38461538,7.57142857 L1.38461538,10.7857143 Z M9.34615385,19 L12.8076923,19 L12.8076923,15.7857143 L9.34615385,15.7857143 L9.34615385,19 Z M5.19230769,10.7857143 L8.65384615,10.7857143 L8.65384615,7.57142857 L5.19230769,7.57142857 L5.19230769,10.7857143 Z M13.5,19 L16.6153846,19 L16.6153846,15.7857143 L13.5,15.7857143 L13.5,19 Z M9.34615385,15.0714286 L12.8076923,15.0714286 L12.8076923,11.5 L9.34615385,11.5 L9.34615385,15.0714286 Z M5.53846154,5.42857143 L5.53846154,2.21428571 C5.53846154,2.11755904 5.50420707,2.03385452 5.43569712,1.96316964 C5.36718716,1.89248477 5.28605816,1.85714286 5.19230769,1.85714286 L4.5,1.85714286 C4.40624953,1.85714286 4.32512053,1.89248477 4.25661058,1.96316964 C4.18810062,2.03385452 4.15384615,2.11755904 4.15384615,2.21428571 L4.15384615,5.42857143 C4.15384615,5.5252981 4.18810062,5.60900262 4.25661058,5.6796875 C4.32512053,5.75037238 4.40624953,5.78571429 4.5,5.78571429 L5.19230769,5.78571429 C5.28605816,5.78571429 5.36718716,5.75037238 5.43569712,5.6796875 C5.50420707,5.60900262 5.53846154,5.5252981 5.53846154,5.42857143 Z M13.5,15.0714286 L16.6153846,15.0714286 L16.6153846,11.5 L13.5,11.5 L13.5,15.0714286 Z M9.34615385,10.7857143 L12.8076923,10.7857143 L12.8076923,7.57142857 L9.34615385,7.57142857 L9.34615385,10.7857143 Z M13.5,10.7857143 L16.6153846,10.7857143 L16.6153846,7.57142857 L13.5,7.57142857 L13.5,10.7857143 Z M13.8461538,5.42857143 L13.8461538,2.21428571 C13.8461538,2.11755904 13.8118994,2.03385452 13.7433894,1.96316964 C13.6748795,1.89248477 13.5937505,1.85714286 13.5,1.85714286 L12.8076923,1.85714286 C12.7139418,1.85714286 12.6328128,1.89248477 12.5643029,1.96316964 C12.4957929,2.03385452 12.4615385,2.11755904 12.4615385,2.21428571 L12.4615385,5.42857143 C12.4615385,5.5252981 12.4957929,5.60900262 12.5643029,5.6796875 C12.6328128,5.75037238 12.7139418,5.78571429 12.8076923,5.78571429 L13.5,5.78571429 C13.5937505,5.78571429 13.6748795,5.75037238 13.7433894,5.6796875 C13.8118994,5.60900262 13.8461538,5.5252981 13.8461538,5.42857143 Z" id="Type-something" fill="#ffffff" sketch:type="MSShapeGroup"></path>
		    </g>
		</svg>';

		$svg_dropdown_dark = '<?xml version="1.0" encoding="UTF-8" standalone="no"?>
					<svg width="40px" height="15px" viewBox="0 0 40 15" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns">
					    <defs></defs>
					    <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage">
					        <path d="M20,15 L0,15 L10,0 L20,15 Z" id="Triangle-1" fill="#ffffff" sketch:type="MSShapeGroup" transform="translate(10.000000, 7.500000) rotate(-180.000000) translate(-10.000000, -7.500000) "></path>
					    </g>
					</svg>';

		$css = "";

		wp_add_inline_style( 'curly-ninja', apply_filters( 'minify_css', htmlspecialchars_decode( $css ) ) );
	}

	/** Visual Composer Ninja Extension */
	public function ninja_vc() {

		$all_forms = Ninja_Forms()->form()->get_forms();
		$all_fields = array();

		$forms[__('Choose Booking Form','xtender')] = '';

		foreach ( $all_forms as $key => $form ) {
				$form_title = $form->get_setting( 'title' );
				$form_title = ! empty( $form_title ) ? $form_title : $form->get_id();
				$forms[ $form_title ] = $form->get_id();
				$all_fields[ $form->get_id() ] = Ninja_Forms()->form( $form->get_id() )->get_fields();
		}

		$fields_array = array(
			array(
				"type" => "dropdown",
				"heading" => __("Ninja Forms", 'xtender'),
				"param_name" => "form",
				'std' => '',
				"value" => $forms,
				"description" => __( 'After selecting your booking form, please check which fields should be used in the pre-fill form', 'xtender' )
			),
			array(
				"type" => "dropdown",
				"group" => __( 'Design options', 'js_composer' ),
				"heading" => __("Form Orientation", 'xtender'),
				"param_name" => "form_style",
				'edit_field_class' => 'vc_col-sm-4 vc_column',
				"value" => array(
					__( 'Vertical Form', 'xtender' ) => 'vertical',
					__( 'Horizontal Form', 'xtender' ) => 'horizontal',
				)
			),
			array(
				"type" => "dropdown",
				"group" => __( 'Design options', 'js_composer' ),
				"heading" => __("Form Fields Size", 'xtender'),
				"param_name" => "fields_size",
				'edit_field_class' => 'vc_col-sm-4',
				'std'	=> '',
				"value" => array(
					__( 'Normal', 'xtender' ) => '',
					__( 'Small', 'xtender' ) => 'sm',
					__( 'Large', 'xtender' ) => 'lg',
				)
			),
			array(
				"type" => "dropdown",
				"group" => __( 'Design options', 'js_composer' ),
				"heading" => __("Color Theme", 'xtender'),
				"param_name" => "theme",
				'edit_field_class' => 'vc_col-sm-4 vc_column',
				"value" => array(
					__( 'Normal', 'xtender' ) => 'light',
					__( 'Dark', 'xtender' ) => 'dark',
				)
			),
			array(
				'type' => 'css_editor',
				'heading' => __( 'Css', 'js_composer' ),
				'param_name' => 'css',
				'group' => __( 'Design options', 'js_composer' )
			)
		);

		if ( ! empty( $all_fields ) ) {

			$forms = array();

			foreach ( $all_fields as $key => $field ){

				$fields = array();

				foreach ( $field as $field_model ) {
					if ( in_array( $field_model->get_setting( 'type' ), array( 'text', 'textbox', 'email', 'phone', 'firstname', 'lastname', 'country', 'zip', 'city', 'address', 'state', 'listselect', 'date' ) ) ) {
						$fields[ $field_model->get_setting( 'label' ) ] = $field_model->get_id();
					}
				}

				array_push( $fields_array , array(
					"type" => "checkbox",
					"heading" => esc_html__( 'Which fields to add?', 'js_composer' ),
					'edit_field_class' => 'vc_col-xs-12 curly_inputs_group',
					"param_name" => "form_fields_" . $key,
					"value" => $fields,
					'dependency' => array(
						'element' => 'form',
						'value' => "$key"
					)
				) );

			}
		}


		/** Booking Forms */
		vc_map( array(
			"name" => __("Booking Form", 'xtender'),
			"base" => "curly_ninja",
			"show_settings_on_create" => true,
			"admin_enqueue_css" => array( XTENDER_URL . 'assets/admin/css/vc-icon.css' ),
			"icon" => "curly_icon",
			"class" => "",
			"category" => __('Curly Themes Extension', 'xtender'),
			"params" => $fields_array
		) );
	}

	/** Visual Composer Form Button Extentions */
	public function ninja_button_vc() {

		$all_forms = Ninja_Forms()->form()->get_forms();

		$forms[ __('Choose Booking Form','xtender') ] = '';

		foreach ( $all_forms as $key => $form ) {
				$form_title = $form->get_setting( 'title' );
				$form_title = ! empty ( $form_title ) ? $form_title : $form->get_id();
				$forms[$form_title] = $form->get_id();
		}

		/** Ninja Forms */
		vc_map( array(
			"name" => __("Ninja Form Modal", 'xtender'),
			"base" => "curly_ninja_modal",
			"show_settings_on_create" => true,
			"admin_enqueue_css" => array( XTENDER_URL . 'assets/admin/css/vc-icon.css' ),
			"icon" => "curly_icon",
			"class" => "",
			"category" => __('Curly Themes Extension', 'xtender'),
			"params" => array(
				array(
					"type" => "dropdown",
					"heading" => __("Ninja Forms", 'xtender'),
					"param_name" => "id",
					"value" => $forms
				),
				array(
					"type" => "dropdown",
					"heading" => __("Button Align", 'xtender'),
					"param_name" => "align",
					'edit_field_class' => 'vc_col-sm-6 vc_column',
					"value" => array(
						__('Left', 'xtender') => 'text-left',
						__('Center', 'xtender') => 'text-center',
						__('Right', 'xtender') => 'text-right'
					)
				),
				array(
					"type" => "textfield",
					'edit_field_class' => 'vc_col-sm-6 vc_column',
					"heading" => __("Button Text", 'xtender'),
					"param_name" => "button_text"
				)
			)
		) );
	}

	public function ninja_button( $atts, $content = null ){

		$atts = shortcode_atts( array(
			'id' 	=> '',
			'align' => null,
			'button_text' => 'Open Form'
		), $atts, 'curly_ninja_modal' );

		extract( $atts );

		if( empty( $id ) ) return;

		$form_id 	= $id;
		$form 		= Ninja_Forms()->form( $form_id )->get();
		$form_settings = $form->get_setting( 'ninja_forms_settings' );
		$html	= $display = null;

		if ( ! $form ) {
			return sprintf( '<p class="text-center"><strong>'.__( 'Form #%s is not existing. To create a form, please go to Forms > Add New in your WP Dashboard.', 'xtender' ).'</strong></p>', $form_id );
		}

		$form_title = $form->get_setting( 'title' );
		$form_title = $form_title ? $form_title : '';

		$html .= '<div class="'.$atts['align'].'"><input type="button" class="btn btn-primary modal-button" name="'.$atts['button_text'].'" value="'.$atts['button_text'].'" data-target="#modal-'.$form_id.'"></div>';
		$html .= '<div class="modal fade" id="modal-'.$form_id.'" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="'.$form_title.'">';
			$html .= '<div class="modal-dialog">';
				$html .= '<div class="modal-content">';
		    		$html .= '<div class="modal-header">';
		    			$html .= '<button type="button" class="close" data-dismiss="modal" data-toggle="tooltip" title="'.__( 'Close', 'xtender' ).'"><span>&times;</span><span class="sr-only">'.__( 'Close', 'xtender' ).'</span></button>';
			    		$html .= ! empty( $form_title ) ? '<h2 id="'.$form_title.'">'.$form_title.'</small></h2>' : '';
		    		$html .= '</div>';
		    		$html .= '<div class="modal-body">';
						ob_start();	Ninja_Forms()->display( $atts['id'] );
						$html .= ob_get_clean();
		    		$html .= '</div>';
				$html .= '</div>';
			$html .= '</div>';
		$html .= '</div>';

		return $html;
	}


	public function jquery_dateformat( $format ){

		$formatted = str_replace('YY', 'Y', $format );

		switch ( $format ) {

			case 'DD/MM/YYYY': return strtolower( $formatted ); break;
			case 'DD-MM-YYYY': return strtolower( $formatted ); break;
			case 'MM/DD/YYYY': return strtolower( $formatted ); break;
			case 'MM/DD/YYYY': return strtolower( $formatted ); break;
			case 'YYYY-MM-DD': return strtolower( $formatted ); break;
			case 'YYYY/MM/DD': return strtolower( $formatted ); break;
			case 'YYYY/MM/DD': return strtolower( $formatted ); break;
			case 'dddd, MMMM D YYYY': {

				$formatted = str_replace( 'D', 'd', $formatted );
				$formatted = str_replace( 'dddd', 'DD', $formatted );
				$formatted = str_replace( 'MMMM', 'MM', $formatted );

				return strtolower( $formatted );

			} break;

			default: return $format; break;
		}


	}

	/** Ninja Shortcode */
	public function ninja( $atts, $content = null ){

		$form_id 	= isset( $atts['form'] ) ? $atts['form'] : null;
		$html	= $display = $el_class = null;

		$el_class = isset( $atts['css'] ) ? $atts['css'] : null;
		if ( $el_class != '' ) {
			$el_class = " " . str_replace( ".", "", $el_class );
			$el_class = substr( $el_class , 0, strpos( $el_class, '{' ) );
		}

		global $ninja_forms_processing;

		$form_row = Ninja_Forms()->form( $form_id )->get();
		$form_data = $form_row;

		if ( ! $form_row ) {
			return sprintf( '<p class="text-center"><strong>'.__( 'Form #%s is not existing. To create a form, please go to Forms > Add New in your WP Dashboard.', 'xtender' ).'</strong></p>', $form_id );
		}

		$ajax = true;

		/** Look for Form ID */
		if ( isset( $form_id ) && ! isset( $display ) ) {
			$fields 	= 'form_fields_' . $form_id;
			$fields 	=  isset( $atts[$fields] )  ? explode( ',', $atts[$fields] ) : null;
			$form 		=  Ninja_Forms()->form( $form_id )->get();
			$form_fields = Ninja_Forms()->form( $form_id )->get_fields();
			$form_settings = get_option( 'ninja_forms_settings' );

			/** Check for Form Fields Size */
			if ( isset( $atts['fields_size'] ) ) {
				switch ( $atts['fields_size'] ) {
					case 'sm' : {
						$input_size 	= 'input-sm';
						$button_size 	= 'btn-sm';
					} break;
					case 'lg' : {
						$input_size 	= 'input-lg';
						$button_size 	= 'btn-lg';
					} break;
					default	: {
						$input_size = $button_size = null;
					}
				}
			} else {
				$input_size = $button_size = null;
			}

			/** Generate Form */
			if ( $fields ) {
				foreach ( $form_fields  as $field ) {

					if ( in_array( strval( $field->get_id() ), $fields ) && in_array( $field->get_setting( 'type' ), array( 'text', 'textbox', 'email', 'phone', 'firstname', 'lastname', 'country', 'zip', 'city', 'address', 'state', 'listselect', 'date' ) ) ) {
						$active_fields[] = $field;
					}
				}
			}

			/** Form Theme */
			$form_class  	 = ( isset( $atts['form_style'] ) && $atts['form_style'] == 'horizontal' ) ? 'row' : null;
			$form_class 	.= ( isset( $atts['theme'] ) && $atts['theme'] == 'dark' ) ? ' dark' : ' light';
			$form_class		.= $el_class;
			$button_size 	.= ( isset( $atts['form_style'] ) && $atts['form_style'] == 'horizontal' ) ? ' btn-block' : ' btn-block';

			$array_textfield = array( 'text', 'textbox', 'email', 'phone', 'firstname', 'lastname', 'country', 'zip', 'city', 'address', 'state' );

			$html .= '<form role="form" class="booking-form '.$form_class.'" action="/" id="prefill-form-'.$form_id.'" data-locale-months="'.$this->_ninja_localization['months'].'" data-locale-months-short="'.$this->_ninja_localization['months_short'].'" data-locale-days="'.$this->_ninja_localization['days'].'" data-locale-days-short="'.$this->_ninja_localization['days_short'].'" data-locale-days-min="'.$this->_ninja_localization['days_min'].'" data-locale-first-day="'.get_option( 'start_of_week' ).'">';
			if ( isset( $active_fields ) ) {
				foreach ( $active_fields  as $key => $field ) {

					if ( isset( $atts['form_style'] ) && $atts['form_style'] == 'horizontal' ) {
						$fields = count( $active_fields );
						$fields = floor( ( 10 / $fields ) );
						$remaining = 10 % count( $active_fields );
						$size = ( $key < $remaining ) ? 'col-md-'. ( $fields + 1 ) : 'col-md-'. $fields;
					} else {
						$size = 'form-group';
					}

					if (in_array( $field->get_setting( 'type' ), $array_textfield) ) {

						$html .= '<div class="'.$size.'">';
						$html .= '<label class="sr-only" for="prefill-'.$form_id.'-'.$field->get_id().'">'.$field->get_setting( 'label' ).'</label>';
						$html .= '<input type="text" class="form-control '.$input_size.' prefill-text"  data-target="#nf-field-'.$field->get_id().'" id="prefill-'.$form_id.'-'.$field->get_id().'" placeholder="'.$field->get_setting( 'label' ).'" value="">';
						$html .= '</div>';
					}

					if ( $field->get_setting( 'type' ) == 'date' ) {

						if( ! wp_script_is( 'jquery-ui-datepicker' ) )
							wp_enqueue_script( 'jquery-ui-datepicker' );

						$html .= '<div class="'.$size.'">';
						$html .= '<label class="sr-only" for="prefill-'.$form_id.'-'.$field->get_id().'">'.$field->get_setting( 'label' ).'</label>';
						$html .= '<input type="text" class="form-control prefill-datepicker '.$input_size.'" data-target="#nf-field-'.$field->get_id().'" data-provide="date-picker" data-date-autoclose="true" data-date-format="'. $field->get_setting( 'date_format' ) .'" data-jquery-format="'. $this->jquery_dateformat( $field->get_setting( 'date_format' ) ) .'" id="prefill-'.$form_id.'-'.$field->get_id().'" placeholder="'.$field->get_setting( 'label' ).'" value="" data-id="date_'.$field->get_id().'">';
						$html .= '</div>';
					}

					if ( $field->get_setting( 'type' ) == 'listselect' ) {
						$html .= '<div class="'.$size.'">';
						$html .= '<label class="sr-only" for="prefill-'.$form_id.'-'.$field->get_id().'">'.$field->get_setting( 'label' ).'</label>';
						$html .= '<select class="form-control '.$input_size.' prefill-text"  data-target="#nf-field-'.$field->get_id().'" id="prefill-'.$form_id.'-'.$field->get_id().'">';
						$options = $field->get_setting( 'options' );

							foreach ( $options as $option ) {
								$value = $option['value'] ? $option['value'] : $option['label'];
								$html .= '<option value="'.$value.'">'.$option['label'].'</option>';
							}

						$html .= '</select>';
						$html .= '</div>';
					}

				}
			}

			/** Define Submit Button */
			foreach ( $form_fields as $field) {
				if ( $field->get_setting( 'type' ) == 'submit' ) {
					$submit_label = $field->get_setting( 'label' );
				}
			}
			$submit_label = isset( $submit_label ) ? $submit_label : __( 'Submit', 'xtender' );
			$submit_class = isset( $atts['form_style'] ) && $atts['form_style'] == 'horizontal' ? 'col-md-2' : 'form-group';

			$html .= '<div class="'.$submit_class.'">';
			$html .= '<input type="button" class="btn btn-primary modal-button '.$button_size.'" name="'.$submit_label.'" value="'.$submit_label.'"  data-target="#prefill-modal-'.$form_id.'">';
			$html .= '</div>';

			$html .= '</form>';
			$html .= '<div class="modal fade" id="prefill-modal-'.$form_id.'" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="'.$form->get_setting( 'form_title' ).'">';
	    		$html .= '<div class="modal-dialog">';
		    		$html .= '<div class="modal-content">';
			    		$html .= '<div class="modal-header">';
			    			$html .= '<button type="button" class="close" data-dismiss="modal" data-toggle="tooltip" title="'.__( 'Close', 'xtender' ).'"><span>&times;</span><span class="sr-only">'.__( 'Close', 'xtender' ).'</span></button>';
				    		$html .= '<h2 id="'.$form_id.'">'.$form->get_setting( 'title' ).'</h2>';
			    		$html .= '</div>';
			    		$html .= '<div class="modal-body">';
							ob_start();	Ninja_Forms()->display( $atts['form'] );
							$html .= ob_get_clean();
			    		$html .= '</div>';
		    		$html .= '</div>';
	    		$html .= '</div>';
			$html .= '</div>';

		}

		return wpb_js_remove_wpautop($html);
	}

}

/** Check if Ninja Forms is active before initialization */
if ( class_exists( 'Ninja_Forms' ) && method_exists( 'Ninja_Forms', 'get_setting' ) ) {

	if( version_compare( get_option( 'ninja_forms_version', '0.0.0' ), '3', '<' ) || get_option( 'ninja_forms_load_deprecated', FALSE ) ) {

	} else {

		new XtenderLeisureNinjaForms();

	}

}

?>
