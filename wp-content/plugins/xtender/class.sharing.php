<?php

class XtenderSharing{

  function __construct(){
    add_action( 'init', array( $this, 'init' ) );

  }

  function init(){
    add_action( 'xtender_sharing', array( $this, 'render_sharing' ) );
    add_filter( XTENDER_THEMPREFIX . '_theme_mods', array( $this, 'mods_sharing'), 10, 1 );
  }

  function mods_sharing( $options ){

    $options[] = array(
    	'label'    	=> esc_html__('Social Sharing','xtender'),
    	'type'		=> 'section',
    	'id'   		=> 'section_sharing',
    	'panel'		=> 'panel_dev',
    	'desc'		=> esc_html__( 'Set up your sharing options in this section', 'xtender' )
    );
    $options[] = array(
    	'label'    	=> esc_html__( 'Sharing on single posts', 'xtender' ),
    	'type'		=> 'checkbox',
    	'section'	=> 'section_sharing',
    	'id' 		=> 'sharing_posts',
    	'default'	=> true,
    	'desc'		=> esc_html__( 'Check this in order to place sharing links at the bottom of each single blog post.', 'xtender'),
      'transport' => 'refresh'
    );
    $options[] = array(
    	'label'    	=> esc_html__( 'Sharing on pages', 'xtender' ),
    	'type'		=> 'checkbox',
    	'section'	=> 'section_sharing',
    	'id' 		=> 'sharing_pages',
    	'default'	=> false,
    	'desc'		=> esc_html__( 'Check this in order to place sharing links at the bottom of each page.', 'xtender'),
      'transport' => 'refresh'
    );
    $options[] = array(
    	'label'    	=> esc_html__( 'Sharing Tagline', 'xtender' ),
    	'type'		=> 'text',
    	'section'   => 'section_sharing',
    	'id' 		=> 'sharing',
    	'default'	=> esc_html__( 'Did you like this? Share it!', 'xtender' ),
    	'active_cb' => array( 'XtenderSharing', 'ccb_has_sharing' ),
      'transport' => 'refresh'
    );

  return $options;

  }


  public static function ccb_has_sharing( $control  ){
    return wp_validate_boolean( $control->manager->get_setting('sharing_posts')->value() ) || wp_validate_boolean( $control->manager->get_setting('sharing_pages')->value() ) ? true : false;
  }

  function render_sharing(){

    $sharing = self::get_sharing(); if( is_array( $sharing ) ) : ?>

    <div class="ct-social-box">
      <?php if( ! empty( $sharing['title'] ) ) : ?><h4 class="h3"><?php echo esc_attr( $sharing['title'] ); ?></h4><?php endif; ?>
      <div class="ct-social-box__icons">
        <a class="ct-social-box__link ct-social-box__link--popup" rel="nofollow" href="<?php echo esc_url_raw( $sharing['fb'] ); ?>" title="<?php esc_html__( 'Facebook', 'xtender' ); ?>">
          <i class="fa fa-boxed fa-facebook"></i>
        </a>
        <a class="ct-social-box__link ct-social-box__link--popup" rel="nofollow" href="<?php echo esc_url_raw( $sharing['tw'] ); ?>" title="<?php esc_html__( 'Twitter', 'xtender' ); ?>">
          <i class="fa fa-boxed fa-twitter"></i>
        </a>
        <a class="ct-social-box__link ct-social-box__link--popup" rel="nofollow" href="<?php echo esc_url_raw( $sharing['li'] ); ?>" title="<?php esc_html__( 'Linkedin', 'xtender' ); ?>">
          <i class="fa fa-boxed fa-linkedin"></i>
        </a>
        <a class="ct-social-box__link ct-social-box__link--popup" rel="nofollow" href="<?php echo esc_url_raw( $sharing['gp'] ); ?>" title="<?php esc_html__( 'GooglePlus', 'xtender' ); ?>">
          <i class="fa fa-boxed fa-google-plus"></i>
        </a>
        <a class="ct-social-box__link" rel="nofollow" href="<?php echo esc_url_raw( $sharing['em'] ); ?>" title="<?php esc_html__( 'Email', 'xtender' ); ?>">
          <i class="fa fa-boxed fa-envelope"></i>
        </a>
      </div>
    </div>

  <?php endif;

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

  public static function get_sharing(){

    if( ! is_singular() )
      return;

    if( ! self::has_sharing() )
      return false;

    $sharing = wp_cache_get( 'sharing_array', 'CurlyThemes' );

    if( ! $sharing ){

        $link  = urlencode( get_the_permalink() );

        $title_tw = esc_attr( get_theme_mod( 'sharing_twitter' ) );
        $title_tw = empty( $title_tw ) ? urlencode( get_the_title() ) : urlencode( $title_tw );

        $title_li = esc_attr( get_theme_mod( 'sharing_linkedin' ) );
        $title_li = empty( $title_li ) ? urlencode( get_the_title() ) : urlencode( $title_li );

        $title_em = esc_attr( get_theme_mod( 'sharing_email' ) );
        $title_em = empty( $title_em ) ? get_the_title() : $title_em;

        $fb = esc_url( add_query_arg(
          array(
            'u' => $link
          ),
          'https://www.facebook.com/sharer.php'
        ) );
        $tw = esc_url( add_query_arg( 'status', "$link &nbsp; $title_tw", 'https://twitter.com/home' ) );
        $gp = esc_url( add_query_arg(
          array(
            'url'	=> $link,
          ),
          'https://plus.google.com/share'
        ) );
        $li = esc_url( add_query_arg(
          array(
            'mini' 	=> 'true',
            'url'	=> $link,
            'title'	=> $title_li
          ),
          'https://linkedin.com/shareArticle'
        ) );
        $em = esc_url( add_query_arg(
          array(
            'subject' => $title_em,
            'body'	=> $link
          ),
          'mailto:'
        ) );


        $sharing = array(
          'title' => esc_attr( get_theme_mod( 'sharing', __( 'Did you like this? Share it!', 'xtender' ) ) ),
          'fb' => $fb,
          'tw' => $tw,
          'li' => $li,
          'gp' => $gp,
          'em' => $em
        );

      wp_cache_set( 'sharing_array', $sharing, 'CurlyThemes' );

    }

    return $sharing;

  }
}

new XtenderSharing();

?>
