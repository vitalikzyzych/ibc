<?php

class PirouetteCustomizer {

	public function __construct() {

		/** Register Customizer */
		add_action( 'customize_register', array( &$this, 'customizer' ) );

		/** Add Scripts */
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'load_assets' ) );

		/** Slider Control */
		require_once( trailingslashit( get_template_directory() ) . 'framework/class.customizer.color.php'  );
		require_once( trailingslashit( get_template_directory() ) . 'framework/class.customizer.slider.php' );
		require_once( trailingslashit( get_template_directory() ) . 'framework/class.customizer.typo.php'  );

		require_once( trailingslashit( get_template_directory() ) . 'framework/class.slimdown.php' );

		if( class_exists( 'PirouetteSlimdown' ) ){
			PirouetteSlimdown::add_rule ('/\[br\]/', '<br>');
		}

		/** Live Customizer */
		add_action( 'customize_preview_init', array( $this, 'live_preview' ) );

	}

	/** Live Customizer */
	function live_preview(){
		wp_enqueue_script(
		  'pirouette-live-customizer',
		  get_template_directory_uri().'/assets/admin/js/min/customizer.live-min.js',
		  array( 'jquery','customize-preview' ),
		  filter_var( WP_DEBUG, FILTER_VALIDATE_BOOLEAN ) ? rand() : null,
		  true
		);
	}

	/** Add Scripts */
	public function load_assets() {
		wp_enqueue_style(
			'pirouette-customizer',
			get_template_directory_uri() . '/assets/admin/css/customizer.css',
			null,
			filter_var( WP_DEBUG, FILTER_VALIDATE_BOOLEAN ) ? rand() : null,
			'all'
		);

		wp_register_script(
			'pirouette-customizer',
			get_template_directory_uri() . '/assets/admin/js/min/customizer.callbacks-min.js',
			array( 'jquery', 'customize-controls' ),
			filter_var( WP_DEBUG, FILTER_VALIDATE_BOOLEAN ) ? rand() : null,
			true
		);

    wp_localize_script( 'pirouette-customizer', 'pirouette_font_list', json_decode( get_option( 'pirouette_google_font_list' ) ) );

		wp_enqueue_script( 'pirouette-customizer' );


	}

	public function customizer( $wp_customize ) {
		$this->construct_customizer( $wp_customize );
	}

	public function construct_customizer( $wp_customize ) {

		$options = array();
		$options = apply_filters( 'pirouette_theme_mods', $options );

		PirouetteSlimdown::add_rule ('/-----/', '\1<br>\2');

		// Add Each Section
		foreach ( $options as $option ) {

			/** Build the Settings Array */
			$settings = array();
			$settings_args = array();

			/** Set Setting Type */
			$settings_args['type'] = 'theme_mod';

			/** Set title */
			if( isset( $option[ 'label' ] ) ){
				$settings[ 'title' ] = $option[ 'label' ];
				$settings[ 'label' ] = $option[ 'label' ];
			}

			/** Set panel */
			if( isset( $option[ 'panel' ] ) ){
				$settings[ 'panel' ] = $option[ 'panel' ];
			}

			/** Set section */
			if( isset( $option[ 'section' ] ) ){
				$settings[ 'section' ] = $option[ 'section' ];
			}

			/** Set type */
			if( isset( $option[ 'type'] ) ){
				$settings[ 'type' ] = $option[ 'type' ];
			}

			/** Set settings */
			if( isset( $option[ 'id' ] ) ){
				$settings[ 'settings' ] = $option[ 'id' ];
				$settings[ 'id' ] = $option[ 'id' ];
			}

			/** Set default */
			if( isset( $option[ 'default' ] ) ) {
				$settings_args[ 'default' ] = $option[ 'default' ];
			}

			/** Set choices */
			if( isset( $option[ 'choices' ] ) ) {
				$settings[ 'choices' ] = $option[ 'choices' ];
			}

			/** Set transport */
			if( isset( $option[ 'transport' ] ) ) {
				$settings_args[ 'transport' ] = $option[ 'transport' ];
			} else{
				$settings_args[ 'transport' ] = 'postMessage';
			}

			/** Set callback */
			if( isset( $option[ 'cb' ] ) ) {
				$settings_args[ 'sanitize_callback' ] = $option[ 'cb' ];
			} else{
				switch( $option['type'] ){
					case 'image' :
						$settings_args[ 'sanitize_callback' ] = 'esc_url_raw';
						break;
					case 'color' :
						$settings_args[ 'sanitize_callback' ] = 'pirouette_sanitize_color_field';
						$settings_args[ 'sanitize_callback_js' ] = 'maybe_hash_hex_color';
						break;
					case 'text' :
						$settings_args[ 'sanitize_callback' ] = 'wp_kses_post';
						break;
					case 'textarea' :
						$settings_args[ 'sanitize_callback' ] = 'wp_kses_post';
						break;
					case 'select' :
						$settings_args[ 'sanitize_callback' ] = 'pirouette_sanitize_dropdown_field';
						break;
					case 'range' :
						$settings_args[ 'sanitize_callback' ] = 'is_numeric';
						break;
					case 'number' :
						$settings_args[ 'sanitize_callback' ] = 'is_numeric';
						break;
					case 'checkbox' :
						$settings_args[ 'sanitize_callback' ] = 'pirouette_sanitize_checkbox_field';
						break;
					default :
						$settings_args[ 'sanitize_callback' ] = 'esc_html';
				}
			}

			/** Set callback_js */
			if( isset( $option[ 'cb_js' ] ) ) {
				$settings_args[ 'sanitize_callback_js' ] = $option[ 'cb_js' ];
			}

			/** Set active_callback */
			if( isset( $option[ 'active_cb' ] ) ) {
				$settings[ 'active_callback' ] = $option[ 'active_cb' ];
			}

			/** Set priority */
			if( isset( $option[ 'priority' ] ) ) {
				$settings[ 'priority' ] = $option[ 'priority' ];
			} else{
				$settings[ 'priority' ] = 10;
			}

			/** Set description */
			if( isset( $option[ 'desc' ] ) ) {
				$settings[ 'description' ] = PirouetteSlimdown::render( $option[ 'desc' ] );
			}

			/** Set input_attrs */
			if( isset( $option['input_attr'] ) ) {
				$settings[ 'input_attrs' ] = array(
					'min' 		=> $option['input_attr']['min'],
					'max' 		=> $option['input_attr']['max'],
					'step' 		=> $option['input_attr']['step'],
					'prefix' 	=> isset( $option['input_attr']['prefix'] ) ? $option['input_attr']['prefix'].' ' : null,
					'suffix' 	=> isset( $option['input_attr']['suffix'] ) ? ' '.$option['input_attr']['suffix'] : null,
					'default'	=> $option[ 'default' ]
				);
			}


			/** Add Control Settings */
			if ( ! in_array( $option['type'], array( 'remove', 'section', 'panel', 'update_section' ) ) ) {
				$wp_customize->add_setting( $option['id'], array(
					'default' 				=> isset( $settings_args['default'] ) ? $settings_args['default'] : null,
					'sanitize_callback' 	=> $settings_args['sanitize_callback'],
					'type'					=> $settings_args['type'],
					'transport'				=> $settings_args['transport'],
					'sanitize_js_callback'	=> isset( $settings_args['sanitize_js_callback'] ) ? $settings_args['sanitize_js_callback'] : null
				 ) );
			}

			/** Remove Default Settings */
			if ( $option['type'] === 'remove' ) {
				$wp_customize->remove_control( $option['id'] );
			}

			/** Update Section */
			elseif ( $option['type'] === 'update_section' ) {

				if ( isset( $option['title'] ) ) {
					$wp_customize->get_section( $option['id'] )->title = $option['title'];
				}

				if ( isset( $option['panel'] ) ) {
					$wp_customize->get_section( $option['id'] )->panel = $option['panel'];
				}

				if ( isset( $option['priority'] ) ) {
					$wp_customize->get_section( $option['id'] )->priority = $option['priority'];
				}
			}

			/** Update Default Settings */
			elseif ( $option['type'] === 'update' ) {

				if ( isset( $option['transport'] ) ) {
					$wp_customize->get_setting( $option['id'] )->transport = $option['transport'];
				}

				if ( isset( $option['default'] ) ) {
					$wp_customize->get_setting( $option['id'] )->default = $option['default'];
				}
			}

			elseif ( $option['type'] === 'panel' ) {
				$wp_customize->add_panel( $option['id'], $settings );
			}

			// Generate Sections
			elseif ( $option['type'] === 'section' ) {
				$wp_customize->add_section( $option['id'] , $settings );

			}

			/** Generate Sliders */
			elseif( $option['type'] === 'slider' ) {
				$wp_customize->add_control(
					new Pirouette_Slider_Control( $wp_customize, $option['id'], $settings )
				);
			}

			/** Generate Typos */
			elseif( $option['type'] === 'typo' ) {
				$wp_customize->add_control(
					new Pirouette_Typo_Control( $wp_customize, $option['id'], $settings )
				);
			}

			/** Generate Sidebars */
			elseif( $option['type'] === 'sidebar' ) {
				$wp_customize->add_control(
					new Pirouette_Sidebar_Control( $wp_customize, $option['id'], $settings )
				);
			}

			// Generate Text & Checkbox Fields
			elseif ( in_array( $option['type'], array( 'text', 'checkbox', 'number', 'range', 'textarea' ) ) ) {
				$wp_customize->add_control(
					new WP_Customize_Control( $wp_customize, $option['id'], $settings )
				);
			}

			// Generate Select & Radio Fields
			elseif ( in_array( $option['type'], array( 'radio', 'select' ) ) ) {
				$wp_customize->add_control(
					new WP_Customize_Control( $wp_customize, $option['id'], $settings )
				);
			}

			// Generate Color Fields
			elseif ( $option['type'] === 'coloor' ) {
				$wp_customize->add_control(
					new Pirouette_Color_Control( $wp_customize, $option['id'], $settings )
				);
			}

			// Generate Upload Fields
			elseif ( $option['type'] === 'upload' ) {
				$wp_customize->add_control(
					new WP_Customize_Upload_Control( $wp_customize, $option['id'], $settings )
				);
			}

			// Generate Image Fields
			elseif ( $option['type'] === 'image' ) {
				$wp_customize->add_control(
					new WP_Customize_Image_Control( $wp_customize, $option['id'], $settings )
				);
			}

		}
	}
}

/** Sanitize Color */
function pirouette_sanitize_color_field( $color ) {
	if( $color === 'transparent' ){
		return esc_html( $color );
	}

	elseif( substr( trim( $color ), 0, 4 ) === 'rgb(' ){
		return esc_html( $color );
	}

	elseif( substr( trim( $color ), 0, 4 ) === 'rgba' ){
		return esc_html( $color );
	}

	elseif( strlen( trim( $color ) ) === 6 ){
		return esc_html( $color );
	}

	elseif( substr( trim( $color ), 0, 1 ) === '#' ){
		return esc_html( $color );
	}

	else {
		return esc_html( $color );
	}
}

/** Sanitize Code */
function pirouette_sanitize_code_field( $input ) {
	return $input;
}

/** Sanitize Dropdown */
function pirouette_sanitize_dropdown_field( $input ) {
	if( is_numeric( $input ) ) {
        return intval( $input );
    } elseif( is_string( $input ) ){
	    return esc_html( $input );
    }
}

/** Sanitize Checkbox */
function pirouette_sanitize_checkbox_field( $input ) {
	return is_bool( $input ) ? $input : null;
}

$pirouette_customizer = new PirouetteCustomizer();

?>
