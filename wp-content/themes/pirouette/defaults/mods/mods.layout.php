<?php

add_filter( 'pirouette_theme_mods', 'pirouette_mods_layout', 10, 1 );

function pirouette_mods_layout( $options ){

  $options[] = array(
		'label'    	=> esc_html__( 'Navigation Layout', 'pirouette' ),
		'type'		=> 'section',
		'panel'   => 'nav_menus',
		'id' 		=> 'nav'
	);
  $options[] = array(
  	'label'    	=> esc_html__( 'Site Layout', 'pirouette' ),
  	'type'		  => 'section',
  	'id'   		  => 'section_layout',
  	'priority'	=> 20
  );
  $options[] = array(
  	'label'   => esc_html__( 'Disable Full Width Layout', 'pirouette' ),
  	'type'		=> 'checkbox',
  	'section' => 'section_layout',
  	'id' 		  => 'layout',
  	'desc'		=> esc_html__( 'Checking this box will make your website layout boxed.', 'pirouette' )
  );
  $options[] = array(
  	'label'     => esc_html__( 'Boxed Layout Width', 'pirouette' ),
  	'type'		=> 'slider',
  	'id'   		=> 'layout_box_width',
  	'section'	=> 'section_layout',
  	'default'	=> 1366,
  	'desc'		=> esc_html__( 'Choose your exact box width', 'pirouette' ),
  	'input_attr'=> array( 'min' => 640, 'max' => 1920, 'step' => 1, 'suffix' => 'px' ),
  	'active_cb'	=> 'pirouette_ccb_is_boxed'
  );
  $options[] = array(
    'label'    	=> esc_html__( 'Disable Fixed Grid', 'pirouette' ),
    'type'		=> 'checkbox',
    'section'   => 'section_layout',
    'id' 		=> 'layout_fixed',
    'desc'		=> esc_html__( 'Checking this box will make your website fluid and fill as much horizontal screen space as possible.', 'pirouette' )
  );
  $options[] = array(
  	'label'     => esc_html__( 'Grid Width', 'pirouette' ),
  	'type'		=> 'slider',
  	'id'   		=> 'layout_grid_width',
  	'section'	=> 'section_layout',
  	'default'	=> 1366,
  	'desc'		=> esc_html__( 'Choose your exact content area width', 'pirouette' ),
  	'input_attr'=> array( 'min' => 640, 'max' => 1920, 'step' => 1, 'suffix' => 'px' ),
  	'active_cb'	=> 'pirouette_ccb_is_fixed'
  );

  /*
  $options[] = array(
  	'label'    	=> esc_html__( 'Header Height', 'pirouette' ),
  	'type'		=> 'slider',
  	'section'   => 'section_layout',
  	'id' 		=> 'header_height',
  	'input_attr'=> array( 'min' => get_theme_mod( 'navigation_height', 100 ) / 10, 'max' => 100, 'step' => 0.5, 'suffix' => 'vh' ),
      'desc'		=> esc_html__( 'Set header height in vh (virtual height) units. For example, 30 vh will make your header stretch on 30% of the screen.', 'pirouette' ),
  	'default'	=> 50,

  );
  */

  return $options;

}

function pirouette_ccb_is_boxed( $control ){
  return filter_var( $control->manager->get_setting('layout')->value(), FILTER_VALIDATE_BOOLEAN ) ? true : false;
}

function pirouette_ccb_is_fixed( $control ){
  return filter_var( $control->manager->get_setting('layout_fixed')->value(), FILTER_VALIDATE_BOOLEAN ) ? false : true;
}

?>
