<?php

add_filter( 'pirouette_theme_mods', 'pirouette_mods_navigation', 10, 1 );

function pirouette_mods_navigation( $options ){

  $options[] = array(
    'label'    	=> esc_html__( 'Navigation Position', 'pirouette' ),
    'type'		=> 'radio',
    'section'   => 'nav',
    'id' 		=> 'navigation_alignment',
    'default'	=> 'right',
    'choices'	=> array(
      'left'	=> esc_html__( 'Left Aligned', 'pirouette' ),
      'right'	=> esc_html__( 'Right Aligned', 'pirouette' )
    ),
    'desc'		=> esc_html__( 'Choose your navigation alignment', 'pirouette' ),
  );
  $options[] = array(
    'label'     => esc_html__( 'Navigation Top Margin', 'pirouette' ),
    'type'		=> 'slider',
    'id'   		=> 'navigation_margin_top',
    'section'	=> 'nav',
    'default'	=> 50,
    'desc'		=> esc_html__( 'Choose your exact menu top margin', 'pirouette' ),
    'input_attr'=> array( 'min' => 0, 'max' => 150, 'step' => 1, 'suffix' => 'px' )
  );
  $options[] = array(
    'label'     => esc_html__( 'Navigation Bottom Margin', 'pirouette' ),
    'type'		=> 'slider',
    'id'   		=> 'navigation_margin_bot',
    'section'	=> 'nav',
    'default'	=> 60,
    'desc'		=> esc_html__( 'Choose your exact menu top margin', 'pirouette' ),
    'input_attr'=> array( 'min' => 0, 'max' => 150, 'step' => 1, 'suffix' => 'px' )
  );
  $options[] = array(
    'label'    	=> esc_html__( 'Disable Sticky Menu', 'pirouette' ),
    'type'		=> 'checkbox',
    'section'   => 'nav',
    'id' 		=> 'navigation_sticky'
  );

  return $options;
}

?>
