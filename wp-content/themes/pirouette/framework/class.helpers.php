<?php


/**
* Curly Helpers
*/
class PirouetteHelpers {

  function __construct(){

		add_filter( 'vc_inner_shortcode', array( $this, 'shortcode_sanitizer') );

    add_filter( 'pirouette_menu_name' , array( $this, 'menu_name' ), 1 );

    add_filter( 'body_class' , array( $this, 'body_class' ) );

    add_action( 'admin_enqueue_scripts', array( $this, 'load_admin_assets' ) );

  	add_filter( 'embed_handler_html', array( $this, 'custom_instagram_settings' ) );
  	add_filter( 'embed_oembed_html', array( $this, 'custom_instagram_settings' ) );

    add_filter( 'wp_generate_tag_cloud_data', array( $this, 'tag_cloud_data' ), 10, 1 );

    /** CSS Minify Filter */
		add_filter( 'pirouette_minify_css', array( $this, 'minify_css' ), 1 );

  }

  public static function minify_css( $string ) {
		$dev 	= $string;
		$string = preg_replace('!/\*.*?\*/!s','', $string);
		$string = preg_replace('/\n\s*\n/',"\n", $string);

		// space
		$string = preg_replace('/[\n\r \t]/',' ', $string);
		$string = preg_replace('/ +/',' ', $string);
		$string = preg_replace('/ ?([,:;{}]) ?/','$1',$string);

		// trailing;
		$string = preg_replace('/;}/','}',$string);

		return $string;
	}

  function tag_cloud_data( $tags_data ) {

      foreach ( $tags_data as $key => $tag ) {

          // get tag count
          $count = $tag [ 'real_count' ];

          // adjust the class based on the size
          if ( $count > 20 ) {
              $tags_data [ $key ] [ 'class' ] .= ' tag x-large';
          } elseif ( $count > 15 ) {
              $tags_data [ $key ] [ 'class' ] .= ' tag large';
          } elseif ( $count > 7 ) {
              $tags_data [ $key ] [ 'class' ] .= ' tag medium';
          } elseif ( $count > 1 ) {
              $tags_data [ $key ] [ 'class' ] .= ' tag small';
          } else {
              $tags_data [ $key ] [ 'class' ] .= ' tag x-small ';
          }
      }

      // return adjusted data
      return $tags_data;
  }

  function custom_instagram_settings( $code ){

    if( strpos( $code, 'youtu.be' ) !== false || strpos( $code, 'youtube.com' ) !== false ){
        $code = str_replace( '?feature=oembed', '?feature=oembed&modestbranding=1&showinfo=0&rel=0', $code );
    }

    if( strpos( $code, 'youtu.be' ) !== false || strpos( $code, 'youtube.com' ) !== false || strpos( $code, 'vimeo.com' ) !== false ){
      $code = str_replace( '<iframe', '<iframe class="embed-responsive-item"', $code );
      $code =  "<div class='embed-responsive embed-responsive-16by9'>$code</div>";
    }

    $code = str_replace( array(
      'frameborder="0"',
      "frameborder='0'",
      'mozallowfullscreen',
      'webkitallowfullscreen'
    ), '', $code );

    return $code;

  }

  function load_admin_assets(){

    wp_enqueue_style(
			'pirouette-admin',
			get_template_directory_uri() . '/assets/admin/css/admin.css',
			null,
			rand(),
			'all'
		);

  }


  function body_class( $classes ) {

    global $post;

    $layout       = esc_attr( get_theme_mod( 'layout' ) );
    $layout_mode  = esc_attr( get_theme_mod( 'layout_fixed', false ) );
    $navigation_alignment = esc_attr( get_theme_mod( 'navigation_alignment', 'right' ) );
    $header_img   = PirouetteHelpers::header_image();

    $classes[] = filter_var( $layout, FILTER_VALIDATE_BOOLEAN ) ? 'ct-layout--boxed' : 'ct-layout--full';
    $classes[] = filter_var( $layout_mode, FILTER_VALIDATE_BOOLEAN ) ? 'ct-layout--fluid' : 'ct-layout--fixed';
    $classes[] = PirouetteHelpers::header_slider() ? 'ct-layout--with-slider' : 'ct-layout--without-slider';
    $classes[] = $header_img  ? 'ct-hero--with-image' : 'ct-hero--without-image';
    $classes[] = 'ct-menu--align-' . $navigation_alignment;

    if( ! PirouetteHelpers::header_slider() ){

      if( $header_img ){

        $classes[] = 'ct-hero-image--' . esc_attr( get_theme_mod( 'header_image_repeat', 'no-repeat' ) );
        $classes[] = 'ct-hero-image--' . esc_attr( get_theme_mod( 'header_image_alignment', 'center' ) ) . '-' . esc_attr( get_theme_mod( 'header_image_position', 'top' ) );
        $classes[] = 'ct-hero-image--' . esc_attr( get_theme_mod( 'header_image_att', 'fixed' ) );
        $classes[] = 'ct-hero-image--' . esc_attr( get_theme_mod( 'header_image_size', 'cover' ) );

      }

      $classes[] = 'ct-hero--' . esc_attr( get_theme_mod( 'heading_alignment', 'left' ) );
      $classes[] = 'ct-hero--' . esc_attr( get_theme_mod( 'heading_position', 'middle' ) );
      $classes[] = 'ct-hero--text-' . esc_attr( get_theme_mod( 'heading_alignment_text', 'left' ) );

    }

    if( self::is_blog() ){
      $classes[] = self::get_sidebar( 'sidebar_blog' ) ? 'ct-blog--with-sidebar' : 'ct-blog--without-sidebar';
    }

    $classes[] = is_singular() && isset( $post->post_content ) && has_shortcode( $post->post_content, 'vc_row' ) ? 'ct-content-with-vc' : 'ct-content-without-vc';

    return $classes;

  }


  public static function get_sidebar( $default ) {

			if( is_null( $default ) || empty( $default ) )
				return false;

      $sidebar = wp_cache_get( $default, 'CurlyTheme' );

      if( ! $sidebar ){

        if( class_exists( 'XtenderSidebars' ) ){
          $sidebar = is_singular() ? esc_attr( get_post_meta( get_the_id(), 'xtender_dynamic_sidebar', true ) ) : false;
          $sidebar = ! $sidebar || empty( $sidebar ) ? false : $sidebar;
        }

  			$sidebar = ! $sidebar ? $default : $sidebar;
  			$sidebar = is_active_sidebar( $sidebar ) ? $sidebar : false;

        wp_cache_set( $default, $sidebar, 'CurlyTheme' );

      }

			return $sidebar;

		}



  public function menu_name( $location ) {

    if( empty( $location ) )
      return false;

    $locations = get_nav_menu_locations();

    if( ! isset( $locations[$location] ) )
      return false;

    $menu_obj = get_term( $locations[$location], 'nav_menu' );

    return isset( $menu_obj->name ) ? esc_attr( $menu_obj->name ) : esc_html__( 'Menu', 'pirouette' );

  }

  public static function get_the_id(){

    $id = wp_cache_get( 'id', 'CurlyTheme' );

    if( ! $id ){

      if( is_singular() ){

        global $post;

        $id = $post->ID;

      }

      elseif( get_option( 'show_on_front' ) === 'page' && self::is_blog() ){

        $id = get_option( 'page_for_posts' );

      }

      else{

        $id = get_queried_object_id();

      }

      wp_cache_set( 'id', $id, 'CurlyTheme' );

    }

    return $id;

  }


  public static function header_image(){

    $header_img = wp_cache_get( 'header_image', 'CurlyTheme' );


    if( ! $header_img ){

      if( ! self::header_slider() ){
        $header_img = esc_url_raw( get_post_meta( self::get_the_id(), '_xtender_header_image', true ) );
        $header_img = empty( $header_img ) ? get_header_image() : $header_img;
        $header_img = empty( $header_img ) ? false : esc_url_raw( $header_img );
      }

      else{
        $header_img = false;
      }

      wp_cache_set( 'header_image', $header_img, 'CurlyTheme' );

    }

    return $header_img;



  }


  public static function get_first_url() {
		if ( ! preg_match( '/<a\s[^>]*?href=[\'"](.+?)[\'"]/is', get_the_content(), $matches ) )
			return false;

		return esc_url_raw( $matches[1] );
	}


  public static function header_slider(){

    if( ! function_exists( 'putRevSlider' ) ){
      return false;
    }

    $slider = wp_cache_get( 'slider', 'CurlyTheme' );

    if( ! $slider ){

      $id = self::get_the_id();

      $header_slider  = esc_attr( get_post_meta( $id, '_xtender_header_slider', true ) );
      $header_bg  = esc_attr( get_post_meta( $id, '_xtender_header_bg', true ) );
      $header_img  = esc_attr( get_post_meta( $id, '_xtender_header_image', true ) );

      $slider = ! empty( $header_slider ) && $header_slider !== 'disable-header-slider' ? $header_slider : false;

      if( ! $slider && $header_slider !== 'disable-header-slider' ){

        $slider = esc_attr( get_theme_mod( 'header_slider' ) );
        $slider = is_null( $slider ) || empty( $slider ) || $slider === 0 ? false : $slider;

      }

      wp_cache_set( 'slider', $slider, 'CurlyTheme' );

    }

    return $slider;

  }

  public static function has_sharing(){

    $sharing = wp_cache_get( 'sharing', 'CurlyTheme' );

    if( ! $sharing ){

      switch( true ){

        case is_page() : {

          $sharing = filter_var( esc_attr( get_theme_mod( 'sharing_pages' ) ), FILTER_VALIDATE_BOOLEAN ) ? true : false;

        } break;

        case is_singular() : {

          $sharing = filter_var( esc_attr( get_theme_mod( 'sharing_posts', true ) ), FILTER_VALIDATE_BOOLEAN ) ? true : false;
        } break;

        default : $sharing = false;

      }

      wp_cache_set( 'sharing', $sharing, 'CurlyTheme' );

    }

    return $sharing;

  }

	public static function has_background_image(){

		$bg_image = wp_cache_get( 'background_image', 'CurlyTheme' );

		if( ! $bg_image ){

			$bg_image = esc_url_raw( get_theme_mod( 'background_image' ) );
			$bg_image = empty( $bg_image ) ? false : $bg_image;

			wp_cache_set( 'background_image', $bg_image, 'CurlyTheme' );

		}

		return $bg_image;


	}

	public static function webfonts_method(){

		$loader = wp_cache_get( 'font_loader', 'CurlyTheme' );

		if( ! $loader ){

			$loader = esc_attr( get_theme_mod( 'font_loader' ) );
			$loader = filter_var( $loader, FILTER_VALIDATE_BOOLEAN );

			wp_cache_set( 'font_loader', $loader, 'CurlyTheme' );

		}

		return $loader;

	}

  public static function get_sliders_array( $default = null, $default_2 = null ){

    $default = is_null( $default ) ? esc_html__( 'Inherit Global Slider', 'pirouette' ) : $default;

    $revsliders = wp_cache_get( 'sliders', 'CurlyTheme' );


    if( ! $revsliders ){

      if( class_exists( 'RevSlider' ) ){
        $slider = new RevSlider();
        $arrSliders = $slider->getArrSliders();
        $revsliders[0] = $default;
        if( ! is_null( $default_2 ) )
          $revsliders[strtolower( sanitize_file_name( $default_2 ) )] = $default_2;

        if( $arrSliders ) {
          foreach( $arrSliders as $value ) {
            $revsliders[$value->getAlias()] = $value->getTitle();
          }
        }
      }

      else {
        $revsliders = array(0);
      }
      wp_cache_set( 'sliders', $revsliders, 'CurlyTheme' );
    }

    return $revsliders;
  }


  public static function is_blog(){

    $is_blog = wp_cache_get( 'is_blog', 'CurlyTheme' );

    if( ! $is_blog ){

      global  $post;

      $post_type = get_post_type( $post );

      if ( ( ( is_archive() ) || ( is_author() ) || ( is_category() ) || (is_home() ) || ( is_single() ) || ( is_tag() ) ) && ( $post_type == 'post' ) ) {

        $is_blog = 1;

      } else {

        $is_blog = 0;

      }

      wp_cache_set( 'is_blog', $is_blog, 'CurlyTheme' );

    }

    return filter_var( $is_blog, FILTER_VALIDATE_BOOLEAN );

  }

}

$pirouette_helpers = new PirouetteHelpers();

?>
