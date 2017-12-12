<?php

add_filter( 'pirouette_theme_mods', 'pirouette_mods_identity', 10, 1 );

function pirouette_mods_identity( $options ){

  $options[] = array(
		'label'     => esc_html__( 'Logo', 'pirouette' ),
		'type'		=> 'image',
		'id'   		=> 'logo',
		'section'	=> 'title_tagline',
		'desc' => esc_html__('The logo will appear in your header.','pirouette')
	);
	$options[] = array(
		'label'     => esc_html__( 'Retina Logo @2x', 'pirouette' ),
		'type'		=> 'image',
		'id'   		=> 'logo_retina',
		'section'	=> 'title_tagline',
		'desc' => esc_html__('Your retina logo must be exactly 2X times bigger than your logo.','pirouette'),
    'active_cb'	=> 'pirouette_ccb_retina_logo'
	);

  return $options;

}

function pirouette_ccb_retina_logo( $control ){
  $value = $control->manager->get_setting('logo')->value();
  return empty( $value ) ? false : true;
}


?>
