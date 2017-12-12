<?php


	/**
	* Extend Visual Composer
	*/
	class ExtenderVC {

		function __construct(){

			add_action( 'vc_before_init', array( $this, 'construct_row' ) );
			add_action( 'vc_after_init', array( $this, 'update_map_vc_single_image' ) );
			add_action( 'vc_after_init', array( $this, 'update_map_vc_btn' ) );
			add_action( 'vc_after_init', array( $this, 'update_map_vc_tta_tabs' ) );

			add_filter( 'shortcode_atts_vc_row', array( $this, 'filter_row' ), 5, 3 );
			add_filter( 'shortcode_atts_vc_column', array( $this, 'filter_column' ), 5, 3 );
			add_filter( 'shortcode_atts_vc_single_image', array( $this, 'filter_image' ), 5, 3 );
			add_filter( 'shortcode_atts_vc_empty_space', array( $this, 'filter_empty' ), 5, 3 );


		}

		function filter_empty( $atts ){

			if( isset( $atts['el_class'] ) && strpos( $atts['el_class'] , 'xtd-spacer' ) !== false ){
				$atts['height'] = '2rem';
			}

			return $atts;

		}

		function update_map_vc_tta_tabs(){

			$param = WPBMap::getParam( 'vc_tta_tabs', 'style' );
			$default = $param['value'];
			$default[ __('Transparent', 'xtender') ] = 'xtd-vc-tabs-transparent';
			$param['value'] = $default;

			vc_update_shortcode_param( 'vc_tta_tabs', $param );

		}


		function update_map_vc_btn() {

			$param = WPBMap::getParam( 'vc_btn', 'style' );
			$default = $param['value'];
			$default[ __('Theme Primary', 'xtender') ] = 'btn-primary';
			$default[ __('Theme Secondary', 'xtender') ] = 'btn-secondary';
			$default[ __('Theme Link', 'xtender') ] = 'btn-link';
			$default[ __('Theme Primary Outline', 'xtender') ] = 'btn-outline-primary';
			$default[ __('Theme Secondary Outline', 'xtender') ] = 'btn-outline-secondary';

			$param['value'] = $default;
			$param['std'] 	= 'btn-primary';

			vc_update_shortcode_param( 'vc_btn', $param );

		}


		function update_map_vc_single_image() {

			$param = WPBMap::getParam( 'vc_single_image', 'onclick' );
			$param['value'] = array(
				__('None', 'xtender') => '',
				__('Large Image Link', 'xtender') => 'img_link_large',
				__('Large Image Lightbox', 'xtender') => 'link_image',
				__('YouTube / Vimeo Lightbox', 'xtender') => 'video_link',
				__('Custom Link', 'xtender') => 'custom_link',
			);
		  vc_update_shortcode_param( 'vc_single_image', $param );

			$param = WPBMap::getParam( 'vc_single_image', 'link' );
			$param['dependency'] = array(
				'element' => 'onclick',
				'value'	=> array( 'custom_link', 'video_link' )
			);
		  vc_update_shortcode_param( 'vc_single_image', $param );

			$param = WPBMap::getParam( 'vc_single_image', 'style' );
			$default = $param['value'];
			$default[ __('Small White Frame with Shadow', 'xtender') ] = 'img-frame-small';
			$default[ __('Large White Frame with Shadow', 'xtender') ] = 'img-frame-large';
			$default[ __('Normal Shadow', 'xtender') ] = 'xtd-shadow--normal-normal';
			$default[ __('Normal Hard Shadow', 'xtender') ] = 'xtd-shadow--normal-hard';
			$default[ __('Normal Light Shadow', 'xtender') ] = 'xtd-shadow--normal-light';
			$default[ __('Large Shadow', 'xtender') ] = 'xtd-shadow--large-normal';
			$default[ __('Large Hard Shadow', 'xtender') ] = 'xtd-shadow--large-hard';
			$default[ __('Large Light Shadow', 'xtender') ] = 'xtd-shadow--large-light';
			$default[ __('Small Shadow', 'xtender') ] = 'xtd-shadow--small-normal';
			$default[ __('Small Hard Shadow', 'xtender') ] = 'xtd-shadow--small-hard';
			$default[ __('Small Light Shadow', 'xtender') ] = 'xtd-shadow--small-light';
			$default[ __('Glow', 'xtender') ] = 'xtd-glow--normal-normal';
			$default[ __('Hard Glow', 'xtender') ] = 'xtd-glow--normal-hard';
			$default[ __('Light Glow', 'xtender') ] = 'xtd-glow--normal-light';
			$default[ __('Large Glow', 'xtender') ] = 'xtd-glow--large-normal';
			$default[ __('Large Hard Glow', 'xtender') ] = 'xtd-glow--large-hard';
			$default[ __('Large Light Glow', 'xtender') ] = 'xtd-glow--large-light';
			$default[ __('Small Glow', 'xtender') ] = 'xtd-glow--small-normal';
			$default[ __('Small Hard Glow', 'xtender') ] = 'xtd-glow--small-hard';
			$default[ __('Small Light Glow', 'xtender') ] = 'xtd-glow--small-light';

			$param['value'] = $default;

			vc_update_shortcode_param( 'vc_single_image', $param );

		}


    function construct_row(){

			/** Show Content in Modal */
			$attributes = array(
				'type' => 'checkbox',
				'heading' => __("Show this row in modal only", 'xtender'),
				'param_name' => 'xtender_is_modal',
        'edit_field_class' => 'vc_col-sm-12'
			);
			vc_add_param('vc_row', $attributes);

      /** Background Repeat */
			$attributes = array(
				'type' => 'dropdown',
				'heading' => __("Background Repeat", 'xtender'),
				'param_name' => 'curly_bg_repeat',
				'value' => array(
					__('Default', 'xtender') => '',
          __('No Repeat', 'xtender') => 'bg-no-repeat',
					__('Tile Horizontally', 'xtender') 		=> 'bg-repeat-x',
					__('Tile Vertically', 'xtender') 	=> 'bg-repeat-y',
          __('Tile Both', 'xtender') 	=> 'bg-repeat-y'
				),
				'group' => __( 'Design Options', 'xtender' ),
        'edit_field_class' => 'vc_col-sm-6'
			);
			vc_add_param('vc_column', $attributes);
			vc_add_param('vc_row', $attributes);

      /** Background Position */
      $attributes = array(
				'type' => 'dropdown',
				'heading' => __("Background Position", 'xtender'),
				'param_name' => 'curly_bg_position',
				'value' => array(
					__('Default', 'xtender') => '',
          __('Top Left', 'xtender') => 'bg-pos-left-top',
					__('Top Center', 'xtender') 		=> 'bg-pos-center-top',
					__('Top Right', 'xtender') 	=> 'bg-pos-right-top',
          __('Center Left', 'xtender') => 'bg-pos-left-center',
					__('Center Center', 'xtender') 		=> 'bg-pos-center-center',
					__('Center Right', 'xtender') 	=> 'bg-pos-right-center',
          __('Bottom Left', 'xtender') => 'bg-pos-left-bottom',
					__('Bottom Center', 'xtender') 		=> 'bg-pos-center-bottom',
					__('Bottom Right', 'xtender') 	=> 'bg-pos-right-bottom'
				),
				'group' => __( 'Design Options', 'xtender' ),
        'edit_field_class' => 'vc_col-sm-6'
			);
			vc_add_param('vc_column', $attributes);
			vc_add_param('vc_row', $attributes);

			/** Background Repeat */
			$attributes = array(
				'type' => 'checkbox',
				'heading' => __("Parallax Background", 'xtender'),
				'param_name' => 'curly_bg_parallax',
				'value'	=> 'no',
				'group' => __( 'Design Options', 'xtender' ),
        'edit_field_class' => 'vc_col-sm-6'
			);
			vc_add_param('vc_row', $attributes);
			vc_add_param('vc_column', $attributes);

      /** Section Title */
      $attributes = array(
				'type' => 'textfield',
				'heading' => __("Section Title", 'xtender'),
				'param_name' => 'curly_section_title',
        'description' => __( 'Leave this empty to disable the section navigator for this section only' ),
        'edit_field_class' => 'vc_col-sm-8',
        'group' => __( 'Section Navigator', 'xtender' )
			);
			vc_add_param('vc_row', $attributes);

      /** Section Color */
      $attributes = array(
				'type' => 'colorpicker',
				'heading' => __("Navigator Color", 'xtender'),
        'description' => __( 'This color will be used for the section navigator for this section only' ),
				'param_name' => 'curly_section_color',
        'dependency' => array(
					'element'  => 'curly_section_title',
					'not_empty' => true
  			),
        'group' => __( 'Section Navigator', 'xtender' )
			);
			vc_add_param('vc_row', $attributes);

			/** Vertical Spacing */
			$attributes = array(
				'type' => 'dropdown',
				'heading' => __("Vertical Padding", 'xtender'),
				'param_name' => 'curly_padding',
				'value' => array(
					__('Default', 'xtender') => '',
					__('No Padding', 'xtender') 		=> 'content-padding-none',
					__('Small Padding', 'xtender') 	=> 'content-padding-xs',
					__('Medium Padding', 'xtender') => 'content-padding',
					__('Large Padding', 'xtender') 	=> 'content-padding-lg',
					__('XL Padding', 'xtender') 	=> 'content-padding-xl',
					__('XXL Padding', 'xtender')	=> 'content-padding-xxl'
				),
				'group' => __( 'Design Options', 'xtender' )
			);
			vc_add_param('vc_column', $attributes);
			vc_add_param('vc_row', $attributes);

		$attributes = array(
			'type' => 'attach_image',
			'heading' => __("Hover Image", 'xtender'),
			'param_name' => 'curly_hover',
			"description" => __("Choose an image for hover", 'xtender')
		);
		vc_add_param('vc_single_image', $attributes);

			/** Parallax Layer #1 Image */
		$attributes = array(
			'type' => 'checkbox',
			'heading' => __("Enable Parallax", 'xtender'),
			'param_name' => 'curly_layer_parallax',
			'group' => __( 'Parallax', 'xtender' ),
			'value'	=> 'no',
			"description" => __("Choose an image as parallax layer", 'xtender')
		);
		vc_add_param('vc_single_image', $attributes);

		/** Parallax Layer #1 Vertical Value */
		$attributes = array(
			'type' => 'textfield',
			'heading' => __("Layer 1 Vertical", 'xtender'),
			'param_name' => 'curly_layer_1_vertical',
			'edit_field_class' => 'vc_col-sm-4 vc_column',
			'group' => __( 'Parallax', 'xtender' ),
			"description" => __("Parallax layer 1 vertical position (ie: top: 50px)", 'xtender'),
			'dependency' => array(
				'element' => 'curly_layer_parallax',
				'value' => array( 'yes', 'true' )
			)
		);
		vc_add_param('vc_single_image', $attributes);

		/** Parallax Layer #1 Horizontal Value */
		$attributes = array(
			'type' => 'textfield',
			'heading' => __("Layer 1 Horizontal", 'xtender'),
			'param_name' => 'curly_layer_1_horizontal',
			'edit_field_class' => 'vc_col-sm-4 vc_column',
			'group' => __( 'Parallax', 'xtender' ),
			"description" => __("Parallax layer 1 horizontal position (ie: left: 25%)", 'xtender'),
			'dependency' => array(
				'element' => 'curly_layer_parallax',
				'value' => array( 'yes', 'true' )
			)
		);
		vc_add_param('vc_single_image', $attributes);

		/** Parallax Layer #1 Radio */
		$attributes = array(
			'type' => 'textfield',
			'heading' => __("Layer 1 Parallax Ratio", 'xtender'),
			'param_name' => 'curly_layer_1_ratio',
			'edit_field_class' => 'vc_col-sm-4 vc_column',
			'group' => __( 'Parallax', 'xtender' ),
			"description" => __("Enter the parallax ratio (ie. 0.5)", 'xtender'),
			'dependency' => array(
				'element' => 'curly_layer_parallax',
				'value' => array( 'yes', 'true' )
			)
		);
		vc_add_param('vc_single_image', $attributes);

		/** Parallax Layer #1 Radio */
		$attributes = array(
			'type' => 'textfield',
			'heading' => __("Layer 1 Width", 'xtender'),
			'param_name' => 'curly_layer_1_width',
			'edit_field_class' => 'vc_col-sm-4 vc_column',
			'group' => __( 'Parallax', 'xtender' ),
			"description" => __("Leave empty for default", 'xtender'),
			'dependency' => array(
				'element' => 'curly_layer_parallax',
				'value' => array( 'yes', 'true' )
			)
		);
		vc_add_param('vc_single_image', $attributes);

		/** Parallax Layer #1 Radio */
		$attributes = array(
			'type' => 'textfield',
			'heading' => __("Layer 1 Height", 'xtender'),
			'param_name' => 'curly_layer_1_height',
			'edit_field_class' => 'vc_col-sm-4 vc_column',
			'group' => __( 'Parallax', 'xtender' ),
			"description" => __("Leave empty for default", 'xtender'),
			'dependency' => array(
				'element' => 'curly_layer_parallax',
				'value' => array( 'yes', 'true' )
			)
		);
		vc_add_param('vc_single_image', $attributes);

		/** Parallax Layer #1 Radio */
		$attributes = array(
			'type' => 'textfield',
			'heading' => __("Layer 1 z-index", 'xtender'),
			'param_name' => 'curly_layer_1_z',
			'edit_field_class' => 'vc_col-sm-4 vc_column',
			'group' => __( 'Parallax', 'xtender' ),
			"description" => __("Leave empty for default", 'xtender'),
			'dependency' => array(
				'element' => 'curly_layer_parallax',
				'value' => array( 'yes', 'true' )
			)
		);
		vc_add_param('vc_single_image', $attributes);

		/** Maximum Width */
		$attributes = array(
			'type' => 'textfield',
			'heading' => __("Maximum Width", 'xtender'),
			'param_name' => 'curly_max_width',
			'description' => __( 'Choose your maximum width for this element.' ),
			'edit_field_class' => 'vc_col-sm-6 vc_column',
			'group' => __( 'Design Options', 'xtender' ),
		);
		vc_add_param('vc_single_image', $attributes);

		/** Width */
		$attributes = array(
			'type' => 'textfield',
			'heading' => __("Width", 'xtender'),
			'param_name' => 'curly_width',
			'description' => __( 'Choose your width for this element.' ),
			'edit_field_class' => 'vc_col-sm-6 vc_column',
			'group' => __( 'Design Options', 'xtender' ),
		);
		vc_add_param('vc_single_image', $attributes);

		/** Minimum Height */
		$attributes = array(
			'type' => 'textfield',
			'heading' => __("Minimum Height", 'xtender'),
			'param_name' => 'curly_min_height',
			'description' => __( 'Choose your minimum height for this element.' ),
			'edit_field_class' => 'vc_col-sm-6',
			'group' => __( 'Design Options', 'xtender' ),
		);
		vc_add_param('vc_column', $attributes);
		vc_add_param('vc_row', $attributes);


		$attributes = array(
			'type' => 'dropdown',
			'heading' => __("Responsive Visibility", 'xtender'),
			'param_name' => 'curly_visibility',
			'edit_field_class' => 'vc_col-sm-12',
			'group' => __( 'Visibility', 'xtender' ),
			'value' => array(
				__('Visible (No Visibility Restrictions)', 'xtender') => '',
				__('Hidden below 544px', 'xtender') 		=> 'hidden-xs-down',
				__('Hidden below 768px', 'xtender') 		=> 'hidden-sm-down',
				__('Hidden below 992px', 'xtender') 		=> 'hidden-md-down',
				__('Hidden below 1200px', 'xtender') 		=> 'hidden-lg-down',
				__('Hidden above 544px', 'xtender') 		=> 'hidden-sm-up',
				__('Hidden above 768px', 'xtender') 		=> 'hidden-md-up',
				__('Hidden above 992px', 'xtender') 		=> 'hidden-lg-up',
				__('Hidden above 1200px', 'xtender') 		=> 'hidden-xl-up',
			)
		);
		vc_add_param('vc_single_image', $attributes);


    }

		function filter_row( $out, $pairs, $atts ){

			if( isset( $atts['xtender_is_modal'] ) && ! empty( $atts['xtender_is_modal'] ) && filter_var( $atts['xtender_is_modal'], FILTER_VALIDATE_BOOLEAN ) ){
		     $out['el_class'] =  "xtd-modal-content {$out['el_class']}";
	    }
      if( isset( $atts['curly_bg_repeat'] ) && ! empty( $atts['curly_bg_repeat'] ) ){
		     $out['el_class'] =  "{$atts['curly_bg_repeat']} {$out['el_class']}";
	    }
      if( isset( $atts['curly_bg_position'] ) && ! empty( $atts['curly_bg_position'] ) ){
		     $out['el_class'] =  "{$atts['curly_bg_position']} {$out['el_class']}";
	    }
			if( isset( $atts['curly_bg_parallax'] ) && ! empty( $atts['curly_bg_parallax'] ) && filter_var( $atts['curly_bg_parallax'], FILTER_VALIDATE_BOOLEAN ) ){
		     $out['el_class'] =  "xtd-background--fixed {$out['el_class']}";
	    }

			if( strpos( $out['el_class'], 'blackboard' ) !== false ){
				$out['el_class'] .= ' text--inverted';
			}

		  $out['el_class'] =  isset( $atts['curly_padding'] ) ? "{$out['el_class']} {$atts['curly_padding']}" : "{$out['el_class']} content-padding";

	    return  $out;

    }

		function filter_column( $out, $pairs, $atts ){

			if( isset( $atts['curly_bg_repeat'] ) && ! empty( $atts['curly_bg_repeat'] ) ){
		     $out['el_class'] =  "{$atts['curly_bg_repeat']} {$out['el_class']}";
	    }
      if( isset( $atts['curly_bg_position'] ) && ! empty( $atts['curly_bg_position'] ) ){
		     $out['el_class'] =  "{$atts['curly_bg_position']} {$out['el_class']}";
	    }
			if( isset( $atts['curly_bg_parallax'] ) && ! empty( $atts['curly_bg_parallax'] ) && filter_var( $atts['curly_bg_parallax'], FILTER_VALIDATE_BOOLEAN ) ){
		     $out['el_class'] =  "xtd-background--fixed {$out['el_class']}";
	    }

			if( strpos( $out['el_class'], 'blackboard' ) !== false ){
				$out['el_class'] .= ' text--inverted';
			}

		  $out['el_class'] =  isset( $atts['curly_padding'] ) ? "{$out['el_class']} {$atts['curly_padding']}" : $out['el_class'];

	    return  $out;

    }

		function filter_button( $out, $pairs, $atts ){

		  $out['el_class'] =  isset( $atts['style'] ) ? "{$out['el_class']} {$atts['curly_padding']}" : $out['el_class'];

	    return  $out;

    }

		function filter_image( $out, $pairs, $atts ){

			$out['el_class'] = isset( $atts['onclick'] ) && ! empty( $atts['onclick'] ) ? "{$out['el_class']} onclick-{$atts['onclick']}" : $out['el_class'];
			$out['el_class'] = isset( $atts['curly_visibility'] ) && ! empty( $atts['curly_visibility'] ) ? "{$out['el_class']} {$atts['curly_visibility']}" : $out['el_class'];

	    return  $out;

    }


	}

	/** Initialize the Class */
	$xtender_vc = new ExtenderVC();


?>
