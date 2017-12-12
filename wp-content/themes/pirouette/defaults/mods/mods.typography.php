<?php

add_filter( 'pirouette_theme_mods', 'pirouette_mods_typography', 20, 1 );

function pirouette_mods_typography( $options ){

  $options[] = array(
  	'label'    	=> esc_html__( 'Typography', 'pirouette' ),
  	'type'		=> 'panel',
  	'id'   		=> 'panel_typo',
  	'priority'	=> 30
  );
  $options[] = array(
  	'label'    	=> esc_html__('Global Typography','pirouette'),
  	'type'		=> 'section',
  	'id'   		=> 'typo_global',
  	'panel'		=> 'panel_typo'
  );
  $options[] = array(
  	'label'       => esc_html__( 'Main Font Family', 'pirouette' ),
  	'type'		    => 'typo',
  	'id'   		    => 'font',
  	'section'	    => 'typo_global',
  	'default'	    => array( 'Lato', '600', 16 ),
    'input_attr'   => array( 'min' => 10, 'max' => 24, 'step' => 1, 'suffix' => 'px' ),
  	'desc'		    => esc_html__( 'This is the general font of your site. It is used for general text, headings, buttons and other text elements.', 'pirouette' ),
  	'transport'	  => 'refresh'
  );
  $options[] = array(
  	'label'     => esc_html__( 'Font Subset', 'pirouette' ),
  	'type'		=> 'select',
  	'id'   		=> 'font_subset',
  	'section'	=> 'typo_global',
  	'default'	=> 0,
  	'choices'	=> array(
  		0 => 'No Subset - Standard Latin',
  		1 => 'Cyrillic Extended (cyrillic-ext)',
  		2 => 'Greek Extended (greek-ext)',
  		3 => 'Greek (greek)',
  		4 => 'Vietnamese (vietnamese)' ,
  		5 => 'Latin Extended (latin-ext)' ,
  		6 => 'Cyrillic (cyrillic)'),
  	'desc'		=> esc_html__( 'Make sure the fonts you use on the website support these special characters.', 'pirouette' )
  );
  $options[] = array(
  	'label'    	=> esc_html__( 'Web Font Loader', 'pirouette' ),
  	'type'		=> 'checkbox',
  	'section'	=> 'typo_global',
  	'id' 		=> 'font_loader',
  	'desc'		=> esc_html__( 'Check this to activate Web Font Loader. This will increase your page speed, but the fonts will be loaded after the other elements.', 'pirouette')
  );
  $options[] = array(
  	'label'    	=> esc_html__('Main Menu','pirouette'),
  	'type'		=> 'section',
  	'id'   		=> 'menu_section',
  	'panel'		=> 'panel_typo'
  );
  $options[] = array(
  	'label'       => esc_html__( 'Main Menu Font', 'pirouette' ),
  	'type'		    => 'typo',
  	'id'   		    => 'font_menu',
  	'section'	    => 'menu_section',
  	'default'	    => array( 'Lato', '700', 12, 'normal', 10 ),
    'input_attr'   => array( 'min' => 10, 'max' => 18, 'step' => 1, 'suffix' => 'px' ),
  	'desc'		    => esc_html__( 'This is the general font of your navigation.', 'pirouette' ),
  	'transport'	  => 'refresh'
  );
  $options[] = array(
  	'label'    	=> esc_html__('H1 Heading','pirouette'),
  	'type'		=> 'section',
  	'id'   		=> 'h1_section',
  	'panel'		=> 'panel_typo'
  );
  $options[] = array(
  	'label'       => esc_html__( 'H1 Headings Font', 'pirouette' ),
  	'type'		    => 'typo',
  	'id'   		    => 'font_h1',
  	'section'	    => 'h1_section',
  	'default'	    => array( 'Lato', '700', 66, 'normal', -5 ),
    'input_attr'   => array( 'min' => 30, 'max' => 72, 'step' => 1, 'suffix' => 'px' ),
  	'desc'		    => esc_html__( 'This is the general font of your H1 headings.', 'pirouette' ),
  	'transport'	  => 'refresh'
  );

  $options[] = array(
  	'label'    	=> esc_html__('H2 Heading','pirouette'),
  	'type'		=> 'section',
  	'id'   		=> 'h2_section',
  	'panel'		=> 'panel_typo'
  );
  $options[] = array(
  	'label'       => esc_html__( 'H2 Headings Font', 'pirouette' ),
  	'type'		    => 'typo',
  	'id'   		    => 'font_h2',
  	'section'	    => 'h2_section',
  	'default'	    => array( 'Lato', '300', 43, 'normal', -4 ),
    'input_attr'   => array( 'min' => 20, 'max' => 62, 'step' => 1, 'suffix' => 'px' ),
  	'desc'		    => esc_html__( 'This is the general font of your H2 headings.', 'pirouette' ),
  	'transport'	  => 'refresh'
  );

  $options[] = array(
  	'label'    	=> esc_html__('H3 Heading','pirouette'),
  	'type'		=> 'section',
  	'id'   		=> 'h3_section',
  	'panel'		=> 'panel_typo'
  );
  $options[] = array(
  	'label'       => esc_html__( 'H3 Headings Font', 'pirouette' ),
  	'type'		    => 'typo',
  	'id'   		    => 'font_h3',
  	'section'	    => 'h3_section',
  	'default'	    => array( 'Lato', '300', 28, 'uppercase', 0 ),
    'input_attr'   => array( 'min' => 18, 'max' => 42, 'step' => 1, 'suffix' => 'px' ),
  	'desc'		    => esc_html__( 'This is the general font of your H3 headings.', 'pirouette' ),
  	'transport'	  => 'refresh'
  );
  $options[] = array(
  	'label'    	=> esc_html__('H4 Heading','pirouette'),
  	'type'		=> 'section',
  	'id'   		=> 'h4_section',
  	'panel'		=> 'panel_typo'
  );
  $options[] = array(
  	'label'       => esc_html__( 'H4 Headings Font', 'pirouette' ),
  	'type'		    => 'typo',
  	'id'   		    => 'font_h4',
  	'section'	    => 'h4_section',
  	'default'	    => array( 'Lato', 'regular', 14, 'normal', 0 ),
    'input_attr'   => array( 'min' => 12, 'max' => 24, 'step' => 1, 'suffix' => 'px' ),
  	'desc'		    => esc_html__( 'This is the general font of your H4 headings.', 'pirouette' ),
  	'transport'	  => 'refresh'
  );
  $options[] = array(
  	'label'    	=> esc_html__('H5 Heading','pirouette'),
  	'type'		=> 'section',
  	'id'   		=> 'h5_section',
  	'panel'		=> 'panel_typo'
  );
  $options[] = array(
  	'label'       => esc_html__( 'H5 Headings Font', 'pirouette' ),
  	'type'		    => 'typo',
  	'id'   		    => 'font_h5',
  	'section'	    => 'h5_section',
  	'default'	    => array( 'Lato', 'regular', 18, 'uppercase', 10 ),
    'input_attr'   => array( 'min' => 12, 'max' => 24, 'step' => 1, 'suffix' => 'px' ),
  	'desc'		    => esc_html__( 'This is the general font of your H5 headings.', 'pirouette' ),
  	'transport'	  => 'refresh'
  );
  $options[] = array(
  	'label'    	=> esc_html__('H6 Heading','pirouette'),
  	'type'		=> 'section',
  	'id'   		=> 'h6_section',
  	'panel'		=> 'panel_typo'
  );
  $options[] = array(
  	'label'       => esc_html__( 'H6 Headings Font', 'pirouette' ),
  	'type'		    => 'typo',
  	'id'   		    => 'font_h6',
  	'section'	    => 'h6_section',
  	'default'	    => array( 'Lato', 'regular', 16, 'uppercase', 0 ),
    'input_attr'   => array( 'min' => 10, 'max' => 18, 'step' => 1, 'suffix' => 'px' ),
  	'desc'		    => esc_html__( 'This is the general font of your H6 headings.', 'pirouette' ),
  	'transport'	  => 'refresh'
  );
  $options[] = array(
  	'label'    	=> esc_html__('Blockquote','pirouette'),
  	'type'		=> 'section',
  	'id'   		=> 'blockquote_section',
  	'panel'		=> 'panel_typo'
  );
  $options[] = array(
  	'label'       => esc_html__( 'Blockquote Font', 'pirouette' ),
  	'type'		    => 'typo',
  	'id'   		    => 'font_blockquote',
  	'section'	    => 'blockquote_section',
  	'default'	    => array( 'Old Standard TT', 'italic', 24, 'normal', 0 ),
    'input_attr'  => array( 'min' => 14, 'max' => 32, 'step' => 1, 'suffix' => 'px' ),
  	'desc'		    => esc_html__( 'This is the general font of your blockquotes.', 'pirouette' ),
  	'transport'	  => 'refresh'
  );

  return $options;

}

?>
