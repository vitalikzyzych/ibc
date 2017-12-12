<?php

add_filter( 'xtender_ips_array', 'pirouette_ips_header', 10, 1 );

function pirouette_ips_header( $options ){

  $revsliders = PirouetteHelpers::get_sliders_array( esc_html__( 'Inherit Global Slider', 'pirouette' ), esc_html__( 'Disable Header Slider', 'pirouette' ) );

  $options[] = array(
  	'id'	=> 'header_image_tab',
  	'type'	=> 'tab',
  	'name'	=> esc_html__( 'Header Image', 'pirouette' )
  );

  $options[] = array(
  	'id'	=> 'header_image',
  	'type'	=> 'image',
  	'tab'	=> 'header_image_tab',
  	'name'	=> esc_html__( 'Header Image', 'pirouette' )
  );
  $options[] = array(
  	'id'	=> 'header_height',
  	'type'	=> 'slider',
  	'tab'	=> 'header_image_tab',
  	'name'	=> esc_html__( 'Header Height', 'pirouette' ),
  	'desc'	=> esc_html__( 'Set header height in vh (virtual height) units. For example, 30 vh will make your header stretch on 30% of the screen.', 'pirouette' ),
  	'atts'	=> array(
  		'step' => 10,
  		'min' => 0,
  		'max' => 1080,
  		'suf' => 'px'
  	),
  	'default' => esc_attr( get_theme_mod( 'header_height', 540 ) )
  );

  $options[] = array(
  	'id'	=> 'header_image_repeat',
  	'type'	=> 'radio',
  	'tab'	=> 'header_image_tab',
  	'name'	=> esc_html__( 'Image Repeat', 'pirouette' ),
  	'choices' 	=> array(
      'inherit'	=> esc_html__( 'Inherit', 'pirouette' ),
  		'repeat'	=> esc_html__( 'Tile', 'pirouette' ),
  		'no-repeat'	=> esc_html__( 'No Repeat', 'pirouette' ),
  		'repeat-y'	=> esc_html__( 'Tile Vertically', 'pirouette' ),
  		'repeat-x'	=> esc_html__( 'Tile Horizontally', 'pirouette' )
  	),
    'default' => 'inherit'
  );
  $options[] = array(
  	'id'	=> 'header_image_alignment',
  	'type'	=> 'radio',
  	'tab'	=> 'header_image_tab',
  	'name'	=> esc_html__( 'Image Alignment', 'pirouette' ),
  	'choices' 	=> array(
      'inherit'	=> esc_html__( 'Inherit', 'pirouette' ),
  		'left'	=> esc_html__( 'Left', 'pirouette' ),
  		'center' => esc_html__( 'Center', 'pirouette' ),
  		'right'	=> esc_html__( 'Right', 'pirouette' )
  	),
    'default' => 'inherit'
  );
  $options[] = array(
  	'id'	=> 'header_image_position',
  	'type'	=> 'radio',
  	'tab'	=> 'header_image_tab',
  	'name'	=> esc_html__( 'Image Position', 'pirouette' ),
  	'choices' 	=> array(
      'inherit'	=> esc_html__( 'Inherit', 'pirouette' ),
  		'top'	=> esc_html__( 'Top', 'pirouette' ),
  		'center' => esc_html__( 'Center', 'pirouette' ),
  		'bottom'	=> esc_html__( 'Bottom', 'pirouette' )
  	),
      'default' => 'inherit'
  );
  $options[] = array(
  	'id'	=> 'header_image_att',
  	'type'	=> 'radio',
  	'tab'	=> 'header_image_tab',
  	'name'	=> esc_html__( 'Image Attachment', 'pirouette' ),
  	'choices' 	=> array(
      'inherit'	=> esc_html__( 'Inherit', 'pirouette' ),
      'scroll'	=> esc_html__( 'Scroll', 'pirouette' ),
      'fixed'	=> esc_html__( 'Fixed', 'pirouette' )
  	),
      'default' => 'inherit'
  );
  $options[] = array(
  	'id'	=> 'header_image_size',
  	'type'	=> 'radio',
  	'tab'	=> 'header_image_tab',
  	'name'	=> esc_html__( 'Image Size', 'pirouette' ),
  	'choices' 	=> array(
          'inherit'	=> esc_html__( 'Inherit', 'pirouette' ),
  		'auto'	=> esc_html__( 'Auto', 'pirouette' ),
  		'cover'	=> esc_html__( 'Cover', 'pirouette' )

  	),
      'default' => 'inherit'
  );


  if( ! empty( $revsliders ) ){
    $options[] = array(
    	'id'	=> 'header_slider_tab',
    	'type'	=> 'tab',
    	'name'	=> esc_html__( 'Header Slider', 'pirouette' )
    );
  	$options[] = array(
  		'id'	=> 'header_slider',
  		'type'	=> 'select',
  		'tab'	=> 'header_slider_tab',
  		'name'	=> esc_html__( 'Header Slider', 'pirouette' ),
  		'choices' 	=> $revsliders
  	);
  }


  return $options;

}

?>
