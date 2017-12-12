<?php

add_filter( 'pirouette_theme_mods', 'pirouette_mods_colors', 10, 1 );

function pirouette_mods_colors( $options ){

  $options[] = array(
  	'label'    	=> esc_html__( 'Colors', 'pirouette' ),
  	'type'		=> 'panel',
  	'id'   		=> 'panel_colors',
  	'priority'	=> 40
  );
  $options[] = array(
  	'label'     => esc_html__( 'Background Color', 'pirouette' ),
  	'type'		=> 'coloor',
  	'id'   		=> 'color_bg',
  	'section'	=> 'colors',
  	'default'	=> '#ffffff',
  	'desc'		=> esc_html__( 'This color is used as background color', 'pirouette' ),
    'transport' => 'refresh'
  );
  $options[] = array(
  	'label'     => esc_html__( 'Text Color', 'pirouette' ),
  	'type'		=> 'coloor',
  	'id'   		=> 'color_text',
  	'section'	=> 'colors',
  	'default'	=> '#666666',
  	'desc'		=> esc_html__( 'This color is used for general text', 'pirouette' ),
    'transport' => 'refresh'
  );
  $options[] = array(
  	'label'     => esc_html__( 'Primary Color', 'pirouette' ),
  	'type'		=> 'coloor',
  	'id'   		=> 'color_primary',
  	'section'	=> 'colors',
  	'default'	=> '#c9ac8c',
  	'desc'		=> esc_html__( 'This color is used for buttons, links and other visual elements', 'pirouette' ),
    'transport' => 'refresh'
  );
  $options[] = array(
  	'label'     => esc_html__( 'Links Color', 'pirouette' ),
  	'type'		=> 'coloor',
  	'id'   		=> 'color_links',
  	'section'	=> 'colors',
  	'default'	=> '#011627',
  	'desc'		=> esc_html__( 'This color is used for text links', 'pirouette' ),
    'transport' => 'refresh'
  );
  $options[] = array(
  	'label'    	=> esc_html__( 'Page Heading', 'pirouette' ),
  	'type'		=> 'section',
  	'id'   		=> 'section_colors_page_heading',
  	'panel'		=> 'panel_colors',
  	'priority'	=> 15
  );
  $options[] = array(
  	'label'     => esc_html__( 'Page Heading Color', 'pirouette' ),
  	'type'		=> 'coloor',
  	'id'   		=> 'color_page_heading',
  	'section'	=> 'section_colors_page_heading',
  	'default'	=> '',
  	'desc'		=> esc_html__( 'This color is used for the page heading', 'pirouette' ),
    'transport' => 'refresh'
  );
  $options[] = array(
  	'label'     => esc_html__( 'Page Subtitle Color', 'pirouette' ),
  	'type'		=> 'coloor',
  	'id'   		=> 'color_page_subtitle',
  	'section'	=> 'section_colors_page_heading',
  	'default'	=> '',
  	'desc'		=> esc_html__( 'This color is used for the page subtitle & excerpt', 'pirouette' ),
    'transport' => 'refresh'
  );
  $options[] = array(
  	'label'     => esc_html__( 'Page Heading Background Color', 'pirouette' ),
  	'type'		=> 'coloor',
  	'id'   		=> 'color_page_heading_bg',
  	'section'	=> 'section_colors_page_heading',
  	'default'	=> '',
  	'desc'		=> esc_html__( 'This color is used for the page heading background', 'pirouette' ),
    'transport' => 'refresh'
  );
  $options[] = array(
  	'label'    	=> esc_html__( 'Headings', 'pirouette' ),
  	'type'		=> 'section',
  	'id'   		=> 'section_colors_headings',
  	'panel'		=> 'panel_colors',
  	'priority'	=> 15
  );
  $options[] = array(
  	'label'     => esc_html__( 'H1 Heading Color', 'pirouette' ),
  	'type'		=> 'coloor',
  	'id'   		=> 'color_h1',
  	'section'	=> 'section_colors_headings',
  	'default'	=> '',
  	'desc'		=> esc_html__( 'This color is used for H1 text. Leave empty to inherit the links colors.', 'pirouette' ),
    'transport' => 'refresh'
  );
  $options[] = array(
  	'label'     => esc_html__( 'H2 Heading Color', 'pirouette' ),
  	'type'		=> 'coloor',
  	'id'   		=> 'color_h2',
  	'section'	=> 'section_colors_headings',
  	'default'	=> '',
  	'desc'		=> esc_html__( 'This color is used for H2 text. Leave empty to inherit the links colors.', 'pirouette' ),
    'transport' => 'refresh'
  );
  $options[] = array(
  	'label'     => esc_html__( 'H3 Heading Color', 'pirouette' ),
  	'type'		=> 'coloor',
  	'id'   		=> 'color_h3',
  	'section'	=> 'section_colors_headings',
  	'default'	=> '',
  	'desc'		=> esc_html__( 'This color is used for H3 text. Leave empty to inherit the links colors.', 'pirouette' ),
    'transport' => 'refresh'
  );
  $options[] = array(
  	'label'     => esc_html__( 'H4 Heading Color', 'pirouette' ),
  	'type'		=> 'coloor',
  	'id'   		=> 'color_h4',
  	'section'	=> 'section_colors_headings',
  	'default'	=> '',
  	'desc'		=> esc_html__( 'This color is used for H4 text. Leave empty to inherit the links colors.', 'pirouette' ),
    'transport' => 'refresh'
  );
  $options[] = array(
  	'label'     => esc_html__( 'H5 Heading Color', 'pirouette' ),
  	'type'		=> 'coloor',
  	'id'   		=> 'color_h5',
  	'section'	=> 'section_colors_headings',
  	'default'	=> '',
  	'desc'		=> esc_html__( 'This color is used for H5 text. Leave empty to inherit the links colors.', 'pirouette' ),
    'transport' => 'refresh'
  );
  $options[] = array(
  	'label'     => esc_html__( 'H6 Heading Color', 'pirouette' ),
  	'type'		=> 'coloor',
  	'id'   		=> 'color_h6',
  	'section'	=> 'section_colors_headings',
  	'default'	=> '',
  	'desc'		=> esc_html__( 'This color is used for H6 text. Leave empty to inherit the links colors.', 'pirouette' ),
    'transport' => 'refresh'
  );
  /*
  $options[] = array(
  	'label'    	=> esc_html__( 'Header', 'pirouette' ),
  	'type'		=> 'section',
  	'id'   		=> 'section_colors_header',
  	'panel'		=> 'panel_colors',
  	'priority'	=> 10
  );
  $options[] = array(
  	'label'     => esc_html__( 'Background Color', 'pirouette' ),
  	'type'		=> 'coloor',
  	'id'   		=> 'color_header_bg',
  	'section'	=> 'section_colors_header',
  	'default'	=> '',
  	'desc'		=> esc_html__( 'This color is used for the header background', 'pirouette' )
  );
  $options[] = array(
  	'label'     => esc_html__( 'Text Color', 'pirouette' ),
  	'type'		=> 'coloor',
  	'id'   		=> 'color_header',
  	'section'	=> 'section_colors_header',
  	'default'	=> '',
  	'desc'		=> esc_html__( 'This color is used for the header text', 'pirouette' )
  );
  */
  $options[] = array(
  	'label'    	=> esc_html__( 'Navigation', 'pirouette' ),
  	'type'		=> 'section',
  	'id'   		=> 'section_colors_navigation',
  	'panel'		=> 'panel_colors',
  	'priority'	=> 10
  );
  $options[] = array(
  	'label'     => esc_html__( 'Text Color', 'pirouette' ),
  	'type'		=> 'coloor',
  	'id'   		=> 'color_navigation',
  	'section'	=> 'section_colors_navigation',
  	'default'	=> '',
  	'desc'		=> esc_html__( 'This color is used for general text in the menu', 'pirouette' ),
    'transport' => 'refresh'
  );
  $options[] = array(
  	'label'     => esc_html__( 'Active Color', 'pirouette' ),
  	'type'		=> 'coloor',
  	'id'   		=> 'color_navigation_active',
  	'section'	=> 'section_colors_navigation',
  	'default'	=> '',
  	'desc'		=> esc_html__( 'This color is used for the active menu element', 'pirouette' ),
    'transport' => 'refresh'
  );
  $options[] = array(
  	'label'     => esc_html__( 'Background Color', 'pirouette' ),
  	'type'		=> 'coloor',
  	'id'   		=> 'color_navigation_bg',
  	'section'	=> 'section_colors_navigation',
  	'default'	=> '',
  	'desc'		=> esc_html__( 'This color is used for menu background', 'pirouette' ),
    'transport' => 'refresh'
  );
  /*
  $options[] = array(
  	'label'     => esc_html__( 'Sticky Text Color', 'pirouette' ),
  	'type'		=> 'coloor',
  	'id'   		=> 'color_navigation_sticky',
  	'section'	=> 'section_colors_navigation',
  	'default'	=> '',
  	'desc'		=> esc_html__( 'This color is used for the header text', 'pirouette' ),
  	//'active_cb'	=> array( 'PirouetteCustomizerCallbacks', 'navigation_is_sticky' )
  );
  $options[] = array(
  	'label'     => esc_html__( 'Sticky Active Color', 'pirouette' ),
  	'type'		=> 'coloor',
  	'id'   		=> 'color_navigation_sticky_active',
  	'section'	=> 'section_colors_navigation',
  	'default'	=> '',
  	'desc'		=> esc_html__( 'This color is used for the active menu element', 'pirouette' ),
  	//'active_cb'	=> array( 'PirouetteCustomizerCallbacks', 'navigation_is_sticky' )
  );
  $options[] = array(
  	'label'     => esc_html__( 'Sticky Background Color', 'pirouette' ),
  	'type'		=> 'coloor',
  	'id'   		=> 'color_navigation_sticky_bg',
  	'section'	=> 'section_colors_navigation',
  	'default'	=> '',
  	'desc'		=> esc_html__( 'This color is used for sticky menu background', 'pirouette' ),
  	//'active_cb'	=> array( 'PirouetteCustomizerCallbacks', 'navigation_is_sticky' )
  );
  */
  $options[] = array(
  	'label'    	=> esc_html__( 'Footer', 'pirouette' ),
  	'type'		=> 'section',
  	'id'   		=> 'section_colors_footer',
  	'panel'		=> 'panel_colors',
  	'priority'	=> 20
  );
  $options[] = array(
  	'label'     => esc_html__( 'Background Color', 'pirouette' ),
  	'type'		=> 'coloor',
  	'id'   		=> 'color_footer_bg',
  	'section'	=> 'section_colors_footer',
  	'default'	=> '#141414',
  	'desc'		=> esc_html__( 'This color is used for footer background', 'pirouette' )
  );
  $options[] = array(
  	'label'     => esc_html__( 'Text Color', 'pirouette' ),
  	'type'		=> 'coloor',
  	'id'   		=> 'color_footer',
  	'section'	=> 'section_colors_footer',
  	'default'	=> '#a7a7a7',
  	'desc'		=> esc_html__( 'This color is used for general text in the footer', 'pirouette' )
  );
  $options[] = array(
  	'label'     => esc_html__( 'Links Color', 'pirouette' ),
  	'type'		=> 'coloor',
  	'id'   		=> 'color_footer_links',
  	'section'	=> 'section_colors_footer',
  	'default'	=> '#ffffff',
  	'desc'		=> esc_html__( 'This color is used for links in the footer', 'pirouette' )
  );
  $options[] = array(
  	'label'     => esc_html__( 'Titles Color', 'pirouette' ),
  	'type'		=> 'coloor',
  	'id'   		=> 'color_footer_titles',
  	'section'	=> 'section_colors_footer',
  	'default'	=> '#ffffff',
  	'desc'		=> esc_html__( 'This color is used for footer titles', 'pirouette' )
  );

  return $options;

}

?>
