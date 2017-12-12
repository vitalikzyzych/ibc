<?php

/** Set Content Width */
if ( ! isset( $content_width ) ) {
	$content_width = 660;
}

/** WP Actions */
add_action( 'wp_enqueue_scripts', 'pirouette_register_assets'  );
add_action( 'after_setup_theme', 'pirouette_menu_setup' );
add_action( 'after_setup_theme', 'pirouette_theme_support' );
add_action( 'after_setup_theme', 'pirouette_theme_localization'  );
add_action( 'widgets_init', 'pirouette_sidebars' );
add_action( 'init', 'pirouette_add_excerpts_to_pages' );

/** WP Filters */
add_filter( 'excerpt_length', 'pirouette_excerpt_length', 999 );
add_filter( 'the_content_more_link', 'pirouette_modify_read_more_link' );

/** Curly Actions */
add_action( 'pirouette_after_post_content', 'pirouette_get_sharing'  );
add_action( 'pirouette_toolbar', 'pirouette_get_toolbar_text'  );
add_action( 'pirouette_toolbar', array( 'PirouetteWPML', 'get_wpml_selector' )  );

function pirouette_add_excerpts_to_pages() {
	add_post_type_support( 'page', 'excerpt' );
}

function pirouette_modify_read_more_link() {
	return "<a href='" . get_the_permalink() . "' title='" . get_the_title() . "' class='btn btn-sm btn-link more-link'>" . esc_html__( 'Read More', 'pirouette' ) . "</a>";
}


function pirouette_get_toolbar_text(){

	if( function_exists('icl_get_languages') && ! filter_var( esc_attr( get_theme_mod( 'wpml' ) ), FILTER_VALIDATE_BOOLEAN ) )
		return;

	$toolbar_text = esc_attr( get_theme_mod( 'toolbar' ) );

	if( ! empty( $toolbar_text ) )
		echo do_shortcode( $toolbar_text );
}

function pirouette_get_sharing(){

    if( ! PirouetteHelpers::has_sharing() )
      return;

    get_template_part( 'template-parts/sharing' );

  }

function pirouette_theme_localization(){
	  load_theme_textdomain( 'pirouette', get_template_directory() . '/languages');
}

function pirouette_excerpt_length() {

		$length	= esc_attr( get_theme_mod( 'excerpt' ) );

		if ( ! $length )
			$length = 60;

		return $length;

	}

function pirouette_register_assets(){


  wp_enqueue_script(
    'pirouette-scripts',
    get_template_directory_uri() . '/assets/front/js/scripts-min.js',
    array( 'jquery', 'jquery-masonry' ),
    is_user_logged_in() ? rand() : null,
    true
  );

	wp_localize_script( 'pirouette-scripts', 'pirouette_theme_data', array(
		'menu' => array(
			'sticky' => filter_var( esc_attr( get_theme_mod( 'navigation_sticky' ) ), FILTER_VALIDATE_BOOLEAN )
		)
	));

	if ( is_singular() )
		wp_enqueue_script( "comment-reply" );

  wp_enqueue_style(
    'pirouette-style',
    get_stylesheet_uri(),
    null,
    filter_var( true, FILTER_VALIDATE_BOOLEAN ) ? rand() : null,
    'all'
  );

}

function pirouette_menu_setup(){
  register_nav_menu( 'main_navigation',  esc_html__( 'Main Navigation', 'pirouette' ) );
	register_nav_menu( 'footer_navigation',  esc_html__( 'Footer Navigation', 'pirouette' ) );
}



function pirouette_sidebars(){
	if ( function_exists( 'register_sidebar' ) )
		register_sidebar( array(
		'name'			 		 => esc_html__( 'Blog Widgets Area', 'pirouette' ),
		'description' 	 => esc_html__( 'This sidebar is visible on the right side of the screen, on all blog pages of your website.', 'pirouette' ),
		'id'			 	 		 => 'sidebar_blog',
		'before_widget'	 => '<aside id="%1$s" class="sidebar-widget %2$s animated">',
		'after_widget' 	 => '</aside>',
		'before_title'	 => '<h4 class="widget-title color-primary">',
		'after_title'	 	 => '</h4>',
	) );

	if ( function_exists( 'register_sidebar' ) )
		register_sidebar( array(
		'name'			 		 => esc_html__( 'Pages Widget Area' , 'pirouette' ),
		'description' 	 => esc_html__( 'This sidebar is visible on the left or right side of the screen, on pages which use a page template with sidebar.', 'pirouette' ),
		'id'			 			 => 'sidebar_page',
		'before_widget'	 => '<aside id="%1$s" class="sidebar-widget %2$s">',
		'after_widget' 	 => '</aside>',
		'before_title'	 => '<h4 class="widget-title color-primary">',
		'after_title'	 	 => '</h4>',
	));

	if ( function_exists( 'register_sidebar' ) )
		register_sidebar( array(
		'name'			 		 => esc_html__( 'Footer Widget Area' , 'pirouette' ),
		'description' 	 => esc_html__( 'This sidebar is visible on all pages of your website, in the footer area.', 'pirouette' ),
		'id'			 			 => 'footer_widget_area',
		'before_widget'	 => '<aside id="%1$s" class="col-sm-3 sidebar-widget %2$s">',
		'after_widget' 	 => '</aside>',
		'before_title'	 => '<h5 class="widget-title color-primary">',
		'after_title'	 	 => '</h5>',
	));

}


function pirouette_theme_support(){
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails', array( 'post', 'page' ) );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'custom-background' );
	add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ) );
	add_theme_support( 'post-formats', array( 'aside', 'gallery', 'chat', 'link', 'image', 'quote', 'status', 'video', 'audio') );
	add_theme_support( 'custom-header', array(
		'random-default'		=> true,
		'width'             => 1920,
		'height'            => 1080,
		'flex-height'       => true,
		'flex-width'        => true,
		'header-text'       => true,
		'uploads'           => true
	) );
}

/*	Theme Update */
$pirouette_tf_user = esc_attr( get_theme_mod( 'tf_user' ) );
$pirouette_tf_api  = esc_attr( get_theme_mod( 'tf_api' ) );

if ( ! empty( $pirouette_tf_user ) && ! empty( $pirouette_tf_api ) ) {

	require_once( trailingslashit( get_template_directory() ) . 'framework/theme-update/envato-wp-theme-updater.php' );

	if( class_exists( 'Envato_WP_Theme_Updater' ) )
		Envato_WP_Theme_Updater::init( $pirouette_tf_user, $pirouette_tf_api, 'Curly Themes' );

}

/* Framework */
require_once( trailingslashit( get_template_directory() ) . 'framework/class.helpers.php' );
require_once( trailingslashit( get_template_directory() ) . 'framework/class.customizer.php' );
require_once( trailingslashit( get_template_directory() ) . 'framework/class.typography.php' );
require_once( trailingslashit( get_template_directory() ) . 'framework/class.color.php' );
require_once( trailingslashit( get_template_directory() ) . 'framework/class.heading.php' );
require_once( trailingslashit( get_template_directory() ) . 'framework/class.comments.php' );
require_once( trailingslashit( get_template_directory() ) . 'framework/class.vc.extend.php' );
require_once( trailingslashit( get_template_directory() ) . 'framework/class.woo.php' );
require_once( trailingslashit( get_template_directory() ) . 'framework/class.wpml.php' );
require_once( trailingslashit( get_template_directory() ) . 'framework/class.plugins.php' );

/* Defaults */
require_once( trailingslashit( get_template_directory() ) . 'defaults/mods.php' );
require_once( trailingslashit( get_template_directory() ) . 'defaults/ips.php' );
require_once( trailingslashit( get_template_directory() ) . 'defaults/renders.php' );


?>
