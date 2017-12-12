<?php

add_filter( 'pirouette_theme_mods', 'pirouette_mods_header', 10, 1 );

function pirouette_mods_header( $options ){

  $revsliders = PirouetteHelpers::get_sliders_array( esc_html__( '- Select Global Header -', 'pirouette' ) );

  $options[] = array(
  	'label'    	=> esc_html__( 'Header', 'pirouette' ),
  	'type'		=> 'panel',
  	'id'   		=> 'panel_header',
  	'priority'	=> 25
  );

  $options[] = array(
    'label'     => esc_html__( 'Header Height', 'pirouette' ),
    'type'		=> 'slider',
    'id'   		=> 'header_height',
    'section'	=> 'header_image',
    'default'	=> 540,
    'desc'		=> esc_html__( 'Choose your minimum header height', 'pirouette' ),
    'input_attr'  => array( 'min' => 0, 'max' => 1080, 'step' => 10, 'suffix' => 'px' )
  );
  $options[] = array(
  	'label'     => esc_html__( 'Image Repeat', 'pirouette' ),
  	'type'		=> 'radio',
  	'id'   		=> 'header_image_repeat',
  	'section'	=> 'header_image',
  	'default'	=> 'no-repeat',
  	'choices'	=> array(
  		'no-repeat' => esc_html__( 'No Repeat', 'pirouette' ),
  		'repeat' 	=> esc_html__( 'Tile', 'pirouette' ),
  		'repeat-x' 	=> esc_html__( 'Tile Horizontally', 'pirouette' ),
  		'repeat-y' 	=> esc_html__( 'Tile Vertically', 'pirouette' ),
  	),
  	'active_cb'	=> 'pirouette_ccb_has_header_image'
  );

  $options[] = array(
  	'label'     => esc_html__( 'Image Alignment', 'pirouette' ),
  	'type'		=> 'radio',
  	'id'   		=> 'header_image_alignment',
  	'section'	=> 'header_image',
  	'default'	=> 'center',
  	'choices'	=> array(
  		'left' => esc_html__( 'Left', 'pirouette' ),
  		'center' 	=> esc_html__( 'Center', 'pirouette' ),
  		'right' 	=> esc_html__( 'Right', 'pirouette' ),
  	),
  	'active_cb'	=> 'pirouette_ccb_has_header_image'
  );

  $options[] = array(
  	'label'     => esc_html__( 'Image Position', 'pirouette' ),
  	'type'		=> 'radio',
  	'id'   		=> 'header_image_position',
  	'section'	=> 'header_image',
  	'default'	=> 'top',
  	'choices'	=> array(
  		'top' => esc_html__( 'Top', 'pirouette' ),
  		'center' 	=> esc_html__( 'Center', 'pirouette' ),
  		'bottom' 	=> esc_html__( 'Bottom', 'pirouette' ),
  	),
  	'active_cb'	=> 'pirouette_ccb_has_header_image'
  );

  $options[] = array(
  	'label'     => esc_html__( 'Image Attachment', 'pirouette' ),
  	'type'		=> 'radio',
  	'id'   		=> 'header_image_att',
  	'section'	=> 'header_image',
  	'default'	=> 'fixed',
  	'choices'	=> array(
  		'scroll' => esc_html__( 'Scroll', 'pirouette' ),
  		'fixed' => esc_html__( 'Fixed', 'pirouette' )
  	),
  	'active_cb'	=> 'pirouette_ccb_has_header_image'
  );

  $options[] = array(
  	'label'     => esc_html__( 'Image Size', 'pirouette' ),
  	'type'		=> 'radio',
  	'id'   		=> 'header_image_size',
  	'section'	=> 'header_image',
  	'default'	=> 'cover',
  	'choices'	=> array(
  		'auto' => esc_html__( 'Auto', 'pirouette' ),
  		'cover' 	=> esc_html__( 'Cover', 'pirouette' )
  	),
  	'active_cb'	=> 'pirouette_ccb_has_header_image'
  );

  $options[] = array(
  	'label'    	=> esc_html__( 'Header Slider', 'pirouette' ),
  	'type'		=> 'section',
  	'id'   		=> 'header_slider_section',
  	'panel'		=> 'panel_header',
  	'desc' 		=> esc_html__( 'When adding a header slider, you have to keep in mind that the header height will increase to match the height of the slider. Minimum slider height should be 370px; also, please note that this is a global setting.', 'pirouette' ),
  	'priority'	=> 50,
  	'transport' => 'refresh'
  );
  $options[] = array(
  	'label'    	=> esc_html__( 'Header Slider', 'pirouette' ),
  	'type'		=> 'select',
  	'section'   => 'header_slider_section',
  	'id' 		=> 'header_slider',
  	'choices'  	=> $revsliders,
  	'active_cb' => 'pirouette_ccb_has_rev_slider',
    'transport' => 'refresh'
  );

  $options[] = array(
  	'label'    	=> esc_html__( 'Page Heading', 'pirouette' ),
  	'type'		=> 'section',
  	'id'   		=> 'header_layout_section',
  	'panel'		=> 'panel_header',
  	'priority'	=> 50,
  	'transport' => 'refresh'
  );

  $options[] = array(
    'label'     => esc_html__( 'Heading Top Margin', 'pirouette' ),
    'type'		=> 'slider',
    'id'   		=> 'header_heading_top',
    'section'	=> 'header_layout_section',
    'default'	=> 50,
    'desc'		=> esc_html__( 'Choose your heading top margin', 'pirouette' ),
    'input_attr'=> array( 'min' => 20, 'max' => 200, 'step' => 1, 'suffix' => 'px' ),
  );
  $options[] = array(
    'label'     => esc_html__( 'Heading Bottom Margin', 'pirouette' ),
    'type'		=> 'slider',
    'id'   		=> 'header_heading_bot',
    'section'	=> 'header_layout_section',
    'default'	=> 50,
    'desc'		=> esc_html__( 'Choose your heading bottom margin', 'pirouette' ),
    'input_attr'=> array( 'min' => 20, 'max' => 200, 'step' => 1, 'suffix' => 'px' ),
  );

  $options[] = array(
  	'label'     => esc_html__( 'Heading Alignment', 'pirouette' ),
  	'type'		=> 'radio',
  	'id'   		=> 'heading_alignment',
  	'section'	=> 'header_layout_section',
  	'default'	=> 'left',
  	'choices'	=> array(
  		'left'    => esc_html__( 'Left', 'pirouette' ),
      'center'  => esc_html__( 'Center', 'pirouette' ),
  		'right' 	=> esc_html__( 'Right', 'pirouette' )
  	)
  );
  $options[] = array(
  	'label'   => esc_html__( 'Heading Position', 'pirouette' ),
  	'type'		=> 'radio',
  	'id'   		=> 'heading_position',
  	'section'	=> 'header_layout_section',
  	'default'	=> 'middle',
  	'choices'	=> array(
      'top'  => esc_html__( 'Top', 'pirouette' ),
  		'middle'  => esc_html__( 'Middle', 'pirouette' ),
  		'bottom' 	=> esc_html__( 'Bottom', 'pirouette' )
  	)
  );
  $options[] = array(
  	'label'     => esc_html__( 'Heading Text Alignment', 'pirouette' ),
  	'type'		=> 'radio',
  	'id'   		=> 'heading_alignment_text',
  	'section'	=> 'header_layout_section',
  	'default'	=> 'left',
  	'choices'	=> array(
  		'left' => esc_html__( 'Left', 'pirouette' ),
      'center' => esc_html__( 'Center', 'pirouette' ),
  		'right' 	=> esc_html__( 'Right', 'pirouette' )
  	)
  );

  $options[] = array(
  	'label'    	=> esc_html__( 'Toolbar', 'pirouette' ),
  	'type'		=> 'section',
  	'id'   		=> 'header_toolbar_section',
  	'panel'		=> 'panel_header',
  	'priority'	=> 60
  );

  $options[] = array(
  	'label'    	=> esc_html__( 'Toolbar Text', 'pirouette' ),
  	'type'		  => 'text',
  	'section'   => 'header_toolbar_section',
  	'id' 		    => 'toolbar',
    'active_cb' => 'pirouette_ccb_wpml_not',
    'transport' => 'refresh'
  );

  $options[] = array(
  	'label'    	=> esc_html__( 'Disable WPML Language Selector', 'pirouette' ),
  	'type'		=> 'checkbox',
  	'section'	=> 'header_toolbar_section',
  	'id' 		  => 'wpml',
  	'default'	=> false,
  	'desc'		=> esc_html__( 'Check this in order to disable the WPML language selector from the toolbar', 'pirouette'),
  	'active_cb' => 'pirouette_ccb_wpml',
    'transport' => 'refresh'
  );

  return $options;

}

function pirouette_ccb_wpml_not( $control ){
  return ! has_action('icl_language_selector') || filter_var( $control->manager->get_setting('wpml')->value(), FILTER_VALIDATE_BOOLEAN ) ? true : false;
}

function pirouette_ccb_wpml(){
  return has_action('icl_language_selector') ? true : false;
}

function pirouette_ccb_has_header_image( $control  ){
  $value = $control->manager->get_setting('header_image')->value();
  return $value === 'remove-header' || empty( $value ) ? false : true;
}

function pirouette_ccb_has_rev_slider(){
  return ! function_exists( 'putRevSlider' ) ? false : true;
}

?>
