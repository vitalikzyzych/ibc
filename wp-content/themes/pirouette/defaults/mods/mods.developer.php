<?php

add_filter( 'pirouette_theme_mods', 'pirouette_mods_developer', 10, 1 );

function pirouette_mods_developer( $options ){

  $options[] = array(
  	'label'    	=> esc_html__( 'Developer Tools', 'pirouette' ),
  	'type'		=> 'panel',
  	'id'   		=> 'panel_dev',
  	'priority'	=> 140
  );

  $options[] = array(
  	'label'    	=> esc_html__('Automatic Theme Update','pirouette'),
  	'type'		=> 'section',
  	'id'   		=> 'section_update',
  	'panel'		=> 'panel_dev',
  	'desc'		=> esc_html__( 'Type in your Theme Forest Username & API Key in order to get automatic theme updates. To generate an API key you need to login to your Theme Forest account, and go to Settings > API Keys. Enter a label (ex: theme update) and hit the Generate API Key button.', 'pirouette' )
  );
  $options[] = array(
  	'label'    	=> esc_html__( 'Theme Forest Username', 'pirouette' ),
  	'type'		=> 'text',
  	'section'   => 'section_update',
  	'id' 		=> 'tf_user'
  );
  $options[] = array(
  	'label'    	=> esc_html__( 'Theme Forest API Key', 'pirouette' ),
  	'type'		=> 'text',
  	'section'   => 'section_update',
  	'id' 		=> 'tf_api'
  );

  return $options;

}

?>
