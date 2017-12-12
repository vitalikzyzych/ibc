<?php

class XtenderGoogle{

  function __construct(){
    add_action( 'init', array( $this, 'init' ) );
  }

  function init(){
    add_filter( XTENDER_THEMPREFIX . '_theme_mods', array( $this, 'mods_google'), 10, 1 );
    add_action( 'wp_footer', array( $this, 'google_analytics' ), 1 );
    add_action( 'wp_footer', array( $this, 'google_tag_manager' ), 1 );
    add_action( 'wp_head', array( $this, 'google_verification' ), 9999999 );
  }

  function mods_google( $options ){

    $options[] = array(
    	'label'    	=> esc_html__('Google Services','xtender'),
    	'type'		=> 'section',
    	'id'   		=> 'section_google',
    	'panel'		=> 'panel_dev'
    );
    $options[] = array(
    	'label'    	=> esc_html__( 'Google Tag Manager Container', 'xtender' ),
    	'type'		=> 'text',
    	'section'   => 'section_google',
    	'id' 		=> 'google_tag'
    );
    $options[] = array(
    	'label'    	=> esc_html__( 'Analytics ID', 'xtender' ),
    	'type'		=> 'text',
    	'section'   => 'section_google',
    	'id' 		=> 'analytics'
    );
    $options[] = array(
    	'label'    	=> esc_html__( 'Webmaster Tools Verification', 'xtender' ),
    	'type'		=> 'text',
    	'section'   => 'section_google',
    	'id' 		=> 'webmaster'
    );
    $options[] = array(
    	'label'    	=> esc_html__( 'Google Maps Api Key', 'xtender' ),
    	'type'		=> 'text',
    	'section'   => 'section_google',
    	'id' 		=> 'maps_api_key'
    );

    return $options;

  }

  public static function google_analytics(){

    $analytics = esc_attr( get_theme_mod( 'analytics' ) );
		if( $analytics) {
			echo "<script>
			  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

			  ga('create', '".$analytics."', 'auto');
			  ga('send', 'pageview');

			</script>";
		}

  }

  public static function google_tag_manager(){

    $tag = esc_attr( get_theme_mod( 'google_tag' ) );

    if( ! empty( $tag ) ){

      ?>

      <!-- Google Tag Manager -->
      <noscript><iframe src="//www.googletagmanager.com/ns.html?id=<?php echo esc_attr( $tag ); ?>"
      height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
      <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
      new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
      j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
      '//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
      })(window,document,'script','dataLayer','<?php echo esc_attr( $tag ); ?>');</script>
      <!-- End Google Tag Manager -->

      <?php
    }

  }

  public static function google_verification() {

		$verification = esc_attr( get_theme_mod( 'webmaster' ) );

		if( $verification ) {
			echo '<meta name="google-site-verification" content="'.$verification.'">';
		}

	}

}

new XtenderGoogle();

?>
