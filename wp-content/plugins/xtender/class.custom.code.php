<?php

class XtenderCustomCode{

  function __construct(){
    add_action( 'init', array( $this, 'init' ) );
  }

  function init(){
    add_filter( XTENDER_THEMPREFIX . '_theme_mods', array( $this, 'mods_custom_code'), 10, 1 );
    add_action( 'wp_footer', array( $this, 'custom_footer_code' ), 9999999 );
		add_action( 'wp_head', array( $this, 'custom_head_code'), 999999 );
    add_action( 'wp_enqueue_scripts', array( $this, 'custom_css'), 999999 );
  }

  function mods_custom_code( $options ){

    $options[] = array(
  	'label'    	=> esc_html__( 'Custom HTML Code', 'xtender' ),
  	'type'		=> 'section',
  	'id'   		=> 'section_code',
  	'panel'		=> 'panel_dev',
  	'desc'		=> esc_html__( 'Using unsafe code in any of these code fields can lead to huge security vulnerabilities. [br]View our [Online Documentation](http://docs.curlythemes.com/leisure-wordpress) for more.', 'xtender')
  );
  $options[] = array(
  	'label'    	=> esc_html__( 'Show Custom Code Fields', 'xtender' ),
  	'type'		=> 'checkbox',
  	'section'   => 'section_code',
    'desc'  => esc_html__( 'Please make sure you trust the code and that it will not compromise your website.', 'xtender' ),
  	'id' 		=> 'code'
  );
  $options[] = array(
  	'label'     => esc_html__( 'Custom code before </head>', 'xtender' ),
  	'type'		=> 'textarea',
  	'id'   		=> 'code_head',
  	'section'	=> 'section_code',
  	'desc'		=> esc_html__( 'Please use valid HTML5 rules only. [br]Validate code here: [W3C HTML5 Validator](https://validator.w3.org/#validate_by_input)', 'xtender' ),
    'cb'      => array( 'XtenderCustomCode', 'sanitize_code_field'),
  	'active_cb' => array( 'XtenderCustomCode', 'ccb_code')
  );
  $options[] = array(
  	'label'     => esc_html__( 'Custom code before </body>', 'xtender' ),
  	'type'		=> 'textarea',
  	'id'   		=> 'code_body',
  	'section'	=> 'section_code',
  	'desc'		=> esc_html__( 'Please use valid HTML5 rules only. [br]Validate code here: [W3C HTML5 Validator](https://validator.w3.org/#validate_by_input)', 'xtender' ),
    'cb'      => array( 'XtenderCustomCode', 'sanitize_code_field'),
  	'active_cb' => array( 'XtenderCustomCode', 'ccb_code')
  );
  $options[] = array(
  	'label'    	=> esc_html__( 'Custom CSS Code', 'xtender' ),
  	'type'		=> 'section',
  	'id'   		=> 'section_css',
  	'panel'		=> 'panel_dev',
  	'desc'		=> esc_html__( 'Please use valid CSS3 rules only. Validate code here: [W3C CSS3 Validator](https://jigsaw.w3.org/css-validator/#validate_by_input)', 'xtender')
  );
  $options[] = array(
  	'label'     => esc_html__( 'Custom CSS', 'xtender' ),
    'desc'     => esc_html__( 'CSS will be applied sitewide', 'xtender' ),
  	'type'		=> 'textarea',
  	'id'   		=> 'code_css_sitewide',
  	'section'	=> 'section_css'
  );
  $options[] = array(
  	'label'     => esc_html__( 'Small Devices CSS', 'xtender' ),
    'desc'     => esc_html__( 'Devices with display up to 768px wide', 'xtender' ),
  	'type'		=> 'textarea',
  	'id'   		=> 'code_css_sm',
  	'section'	=> 'section_css'
  );
  $options[] = array(
  	'label'     => esc_html__( 'Medium Devices CSS', 'xtender' ),
    'desc'     => esc_html__( 'Devices with display up to 992px wide', 'xtender' ),
  	'type'		=> 'textarea',
  	'id'   		=> 'code_css_md',
  	'section'	=> 'section_css'
  );
  $options[] = array(
  	'label'     => esc_html__( 'Large Devices CSS', 'xtender' ),
    'desc'     => esc_html__( 'Devices with display up to 1200px wide', 'xtender' ),
  	'type'		=> 'textarea',
  	'id'   		=> 'code_css_lg',
  	'section'	=> 'section_css'
  );
  $options[] = array(
  	'label'     => esc_html__( 'Extra Large Devices CSS', 'xtender' ),
    'desc'     => esc_html__( 'Devices with a minimum display of 1200px wide', 'xtender' ),
  	'type'		=> 'textarea',
  	'id'   		=> 'code_css_xl',
  	'section'	=> 'section_css'
  );

    return $options;

  }

  /** Sanitize Code */
  public static function sanitize_code_field( $input ) {
  	return $input;
  }

  public static function ccb_code( $control ){
    return wp_validate_boolean($control->manager->get_setting('code')->value()) ? true : false;
  }

  public static function custom_footer_code(){
    $custom_footer = get_theme_mod( 'code_body' );
		echo filter_var( esc_attr( get_theme_mod( 'code' ) ), FILTER_VALIDATE_BOOLEAN ) && isset( $custom_footer ) && $custom_footer !== false && ! empty( $custom_footer ) ? htmlspecialchars_decode( $custom_footer ) : '';
  }

  public static function custom_head_code() {
    $custom_head = get_theme_mod( 'custom_head' );
		echo filter_var( esc_attr( get_theme_mod( 'code' ) ), FILTER_VALIDATE_BOOLEAN ) && isset( $custom_head ) && $custom_head !== false && ! empty( $custom_head ) ?  htmlspecialchars_decode(  ) : '';
	}

  public static function custom_css(){

    $css = '';

    $custom_css    = esc_attr( get_theme_mod( 'code_css_sitewide' ) );
    $custom_css_sm = esc_attr( get_theme_mod( 'code_css_sm' ) );
    $custom_css_md = esc_attr( get_theme_mod( 'code_css_md' ) );
    $custom_css_lg = esc_attr( get_theme_mod( 'code_css_lg' ) );
    $custom_css_xl = esc_attr( get_theme_mod( 'code_css_xl' ) );

    if( ! empty( $custom_css ) ){
      $css .= $custom_css;
    }
    if( ! empty( $custom_css_sm ) ){
      $css .= "@media(max-width: 767px){ $custom_css_sm }";
    }
    if( ! empty( $custom_css_md ) ){
      $css .= "@media(max-width: 991px){ $custom_css_md }";
    }
    if( ! empty( $custom_css_lg ) ){
      $css .= "@media(max-width: 1199px){ $custom_css_lg }";
    }
    if( ! empty( $custom_css_xl ) ){
      $css .= "@media(min-width: 1200px){ $custom_css_xl }";
    }

    if( ! empty( $css ) )
  		wp_add_inline_style( XTENDER_THEMPREFIX . '-style', apply_filters( 'minify_css', htmlspecialchars_decode( $css ) ) );

  }

}

new XtenderCustomCode();

?>
