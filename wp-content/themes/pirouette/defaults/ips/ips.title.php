<?php

add_filter( 'xtender_ips_array', 'pirouette_ips_title', 20, 1 );

function pirouette_ips_title( $options ){

  $options[] = array(
  	'id'	=> 'title_tab',
  	'type'	=> 'tab',
  	'name'	=> esc_html__( 'Page Heading', 'pirouette' )
  );

  $options[] = array(
  	'id'	=> 'heading',
  	'type'	=> 'checkbox',
  	'tab'	=> 'title_tab',
  	'name'	=> esc_html__( 'Disable Page Title', 'pirouette' ),
  	'desc'	=> esc_html__( 'Check this to disable the page title.', 'pirouette' )
  );

  $options[] = array(
  	'id'	=> 'header_title',
  	'type'	=> 'text',
  	'tab'	=> 'title_tab',
  	'name'	=> esc_html__( 'Page Title', 'pirouette' ),
  	'desc'	=> esc_html__( 'Leave this empty to use the default title.', 'pirouette' )
  );

  $options[] = array(
  	'id'	=> 'header_subtitle',
  	'type'	=> 'text',
  	'tab'	=> 'title_tab',
  	'name'	=> esc_html__( 'Page Subtitle', 'pirouette' )
  );

  $options[] = array(
  	'id'	=> 'header_excerpt',
  	'type'	=> 'textarea',
  	'tab'	=> 'title_tab',
  	'name'	=> esc_html__( 'Page Description', 'pirouette' )
  );

  $options[] = array(
  	'id'	=> 'heading_alignment',
  	'type'	=> 'radio',
  	'tab'	=> 'title_tab',
  	'name'	=> esc_html__( 'Heading Aligment', 'pirouette' ),
  	'choices' 	=> array(
      'inherit'	=> esc_html__( 'Inherit', 'pirouette' ),
      'left'	  => esc_html__( 'Left', 'pirouette' ),
      'center'	=> esc_html__( 'Center', 'pirouette' ),
      'right'	  => esc_html__( 'Right', 'pirouette' )
  	),
      'default' => 'inherit'
  );

  $options[] = array(
  	'id'	=> 'heading_position',
  	'type'	=> 'radio',
  	'tab'	=> 'title_tab',
  	'name'	=> esc_html__( 'Heading Position', 'pirouette' ),
  	'choices' 	=> array(
      'inherit'	=> esc_html__( 'Inherit', 'pirouette' ),
      'top'	    => esc_html__( 'Top', 'pirouette' ),
      'middle'	=> esc_html__( 'Middle', 'pirouette' ),
      'bottom'	=> esc_html__( 'Bottom', 'pirouette' )
  	),
      'default' => 'inherit'
  );

  $options[] = array(
  	'id'	  => 'heading_alignment_text',
  	'type'	=> 'radio',
  	'tab'	  => 'title_tab',
  	'name'	=> esc_html__( 'Heading Text Aligment', 'pirouette' ),
  	'choices' 	=> array(
      'inherit'	=> esc_html__( 'Inherit', 'pirouette' ),
      'left'	  => esc_html__( 'Left', 'pirouette' ),
      'center'	=> esc_html__( 'Center', 'pirouette' ),
      'right'	  => esc_html__( 'Right', 'pirouette' )
  	),
      'default' => 'inherit'
  );


  $options[] = array(
  	'id'	=> 'color_page_heading',
  	'type'	=> 'color',
  	'tab'	=> 'title_tab',
  	'name'	=> esc_html__( 'Page Heading Color', 'pirouette' )
  );
  $options[] = array(
  	'id'	=> 'color_page_subtitle',
  	'type'	=> 'color',
  	'tab'	=> 'title_tab',
  	'name'	=> esc_html__( 'Page Subtitle Color', 'pirouette' )
  );
  $options[] = array(
  	'id'	=> 'color_page_heading_bg',
  	'type'	=> 'color',
  	'tab'	=> 'title_tab',
  	'name'	=> esc_html__( 'Page Heading Background Color', 'pirouette' )
  );

  $options[] = array(
  	'id'	  => 'header_scroller',
  	'type'	=> 'text',
  	'tab'	  => 'title_tab',
  	'name'	=> esc_html__( 'Scroll Down Text', 'pirouette' ),
  	'desc'	=> esc_html__( 'Leave this empty to disale the scroll down text for this section.', 'pirouette' )
  );
  $options[] = array(
  	'id'	  => 'sec_nav_title',
  	'type'	=> 'text',
  	'tab'	  => 'title_tab',
  	'name'	=> esc_html__( 'Section Title', 'pirouette' ),
  	'desc'	=> esc_html__( 'Leave this empty to disale the section navigator for the hero section.', 'pirouette' )
  );
  $options[] = array(
  	'id'	=> 'color_sec_nav',
  	'type'	=> 'color',
  	'tab'	=> 'title_tab',
  	'name'	=> esc_html__( 'Section Color', 'pirouette' )
  );


  return $options;

}

?>
