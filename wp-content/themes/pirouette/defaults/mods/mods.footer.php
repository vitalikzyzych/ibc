<?php

add_filter( 'pirouette_theme_mods', 'pirouette_mods_footer', 10, 1 );

function pirouette_mods_footer( $options ){

  $options[] = array(
  	'label'    	=> esc_html__( 'Footer', 'pirouette' ),
  	'type'		=> 'section',
  	'id'   		=> 'section_footer',
  	'priority'	=> 130
  );

  $options[] = array(
  	'label'    	=> esc_html__( 'Footer Logo', 'pirouette' ),
  	'type'		=> 'image',
  	'section'   => 'section_footer',
  	'id' 		=> 'footer_logo',
  	'desc'		=> esc_html__( 'This image will be used as the logo in the footer', 'pirouette' ),
    'transport' => 'refresh'
  );

  $options[] = array(
  	'label'    	=> esc_html__( 'Footer Retina Logo @2x', 'pirouette' ),
  	'type'		=> 'image',
  	'section'   => 'section_footer',
  	'id' 		=> 'footer_logo_retina',
  	'desc'		=> esc_html__( 'This image will be used as the retina logo in the footer', 'pirouette' ),
    'active_cb' => 'pirouette_ccb_has_footer_logo',
    'transport' => 'refresh'
  );

  $options[] = array(
  	'label'   => esc_html__( 'Back to top label', 'pirouette' ),
  	'type'		=> 'text',
  	'section' => 'section_footer',
  	'id' 		  => 'footer_top',
    'default' => esc_html__( 'Back to top of the page', 'pirouette' ),
    'desc'		=> esc_html__( 'Leave empty to disable this button.', 'pirouette' ),
    'active_cb' => 'pirouette_ccb_has_footer_menu'
  );

  return $options;

}

function pirouette_ccb_has_footer_logo( $control  ){
  $value = $control->manager->get_setting('footer_logo')->value();
  return empty( $value ) ? false : true;
}

function pirouette_ccb_has_footer_menu( $control  ){
  return intval( $control->manager->get_setting('nav_menu_locations[footer_navigation]')->value() ) === 0 ? false : true;
}

?>
