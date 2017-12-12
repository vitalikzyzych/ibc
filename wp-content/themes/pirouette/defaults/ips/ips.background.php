<?php

add_filter( 'xtender_ips_array', 'pirouette_ips_background', 20, 1 );

function pirouette_ips_background( $options ){

  $options[] = array(
  	'id'	=> 'bg_tab',
  	'type'	=> 'tab',
  	'name'	=> esc_html__( 'Background', 'pirouette' )
  );
  $options[] = array(
  	'id'	=> 'color_bg',
  	'type'	=> 'color',
  	'tab'	=> 'bg_tab',
  	'name'	=> esc_html__( 'Background Color', 'pirouette' )
  );
  $options[] = array(
  	'id'	=> 'background_image',
  	'type'	=> 'image',
  	'tab'	=> 'bg_tab',
  	'name'	=> esc_html__( 'Background Image', 'pirouette' )
  );
  $options[] = array(
  	'id'	=> 'background_position_x',
  	'type'	=> 'radio',
  	'tab'	=> 'bg_tab',
  	'name'	=> esc_html__( 'Background Position', 'pirouette' ),
  	'choices' 	=> array(
          'inherit'	=> esc_html__( 'Inherit', 'pirouette' ),
  		'left'	=> esc_html__( 'Left', 'pirouette' ),
  		'center' => esc_html__( 'Center', 'pirouette' ),
  		'right'	=> esc_html__( 'Right', 'pirouette' )
  	),
      'default' => 'inherit'
  );
  $options[] = array(
  	'id'	=> 'background_repeat',
  	'type'	=> 'radio',
  	'tab'	=> 'bg_tab',
  	'name'	=> esc_html__( 'Background Repeat', 'pirouette' ),
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
  	'id'	=> 'background_size',
  	'type'	=> 'radio',
  	'tab'	=> 'bg_tab',
  	'name'	=> esc_html__( 'Background Size', 'pirouette' ),
  	'choices' 	=> array(
          'inherit'	=> esc_html__( 'Inherit', 'pirouette' ),
  		'auto'	=> esc_html__( 'Auto', 'pirouette' ),
  		'cover'	=> esc_html__( 'Cover', 'pirouette' )

  	),
      'default' => 'inherit'
  );
  $options[] = array(
  	'id'	=> 'background_attachment',
  	'type'	=> 'radio',
  	'tab'	=> 'bg_tab',
  	'name'	=> esc_html__( 'Background Attachment', 'pirouette' ),
  	'choices' 	=> array(
          'inherit'	=> esc_html__( 'Inherit', 'pirouette' ),
          'scroll'	=> esc_html__( 'Scroll', 'pirouette' ),
          'fixed'	=> esc_html__( 'Fixed', 'pirouette' )
  	),
      'default' => 'inherit'
  );

  return $options;

}

?>
