<?php
/*
Plugin Name: xtender
Plugin URI: xtender
Description: This plugins adds more flavour to your Curly Theme.
Version: 1.4.1
Author: Curly Themes
Author URI: http://demo.curlythemes.com
Text Domain: xtender
*/

define( 'XTENDER_PATH', plugin_dir_path( __FILE__ ) );
define( 'XTENDER_URL', plugin_dir_url( __FILE__ ) );
define( 'XTENDER_PREFIX', 'xtender' );
define( 'XTENDER_THEMPREFIX', str_replace( '-', '_', str_replace('/wp-content/themes/', '', str_replace( get_site_url(), '', get_template_directory_uri() ) ) ) );

function xtender_get_template_part( $slug, $name = '' ) {

    $template = '';

    if ( $name ) {
        $template = locate_template( array( "{$slug}-{$name}.php", apply_filters( 'xtender_template_path', 'xtender_templates/' ) . "{$slug}-{$name}.php" ) );
    }

    if ( ! $template && $name && file_exists( XTENDER_PATH . "/templates/{$slug}-{$name}.php" ) ) {
        $template = XTENDER_PATH . "/templates/{$slug}-{$name}.php";
    }

    if ( ! $template ) {
        $template = locate_template( array( "{$slug}.php", apply_filters( 'xtender_template_path', 'xtender_templates/' ) . "{$slug}.php" ) );
    }

    if ( ! $template && file_exists( XTENDER_PATH . "/templates/{$slug}.php" ) ) {
	    $template = XTENDER_PATH . "/templates/{$slug}.php";
    }

    $template = apply_filters( 'xtender_get_template_part', $template, $slug, $name );

    if ( $template ) {
        return $template;
    }
}

if( ! defined( 'XTENDER_THEMPREFIX' ) || XTENDER_THEMPREFIX !== 'leisure' ) require XTENDER_PATH . 'class.widgets.php';

require XTENDER_PATH . 'class.sidebars.php';
require XTENDER_PATH . 'class.ips.php';
require XTENDER_PATH . 'class.tinymce.php';

require XTENDER_PATH . 'class.widget.recent.php';
require XTENDER_PATH . 'class.widget.search.php';

require XTENDER_PATH . '/shortcodes/class.icons.php';
require XTENDER_PATH . '/shortcodes/class.buttons.php';
require XTENDER_PATH . '/shortcodes/class.dropcap.php';
require XTENDER_PATH . '/shortcodes/class.spacer.php';
require XTENDER_PATH . '/shortcodes/class.lead.php';

add_filter( 'minify_css', 'xtender_minify_css', 1 );

require XTENDER_PATH . 'class.sharing.php';
require XTENDER_PATH . 'class.google.php';
require XTENDER_PATH . 'class.custom.code.php';

/** Visual Composer Extensions */
require_once( XTENDER_PATH . '/vc/class.vc.extend.php');
require_once( XTENDER_PATH . '/vc/class.carousel.filmstrip.php');
require_once( XTENDER_PATH . '/vc/class.carousel.mini.php');
require_once( XTENDER_PATH . '/vc/class.gmap3.php');
require_once( XTENDER_PATH . '/vc/class.modal.php');
require_once( XTENDER_PATH . '/vc/class.timeline.php');
require_once( XTENDER_PATH . '/vc/class.shape.php');
require_once( XTENDER_PATH . '/vc/class.banner.person.php');
require_once( XTENDER_PATH . '/vc/class.pricing.list.php');
require_once( XTENDER_PATH . '/vc/class.card.php');
require_once( XTENDER_PATH . '/vc/class.services.carousel.php');
require_once( XTENDER_PATH . '/vc/class.services.list.php');
require_once( XTENDER_PATH . '/vc/class.testimonials.carousel.php');
require_once( XTENDER_PATH . '/vc/class.isotope.php');

add_action( 'init', 'xtender_init_components' );
add_action( 'wp_enqueue_scripts', 'xtender_register_assets' );
add_action( 'admin_enqueue_scripts', 'xtender_register_admin_assets' );
add_filter(	'upload_mimes', 'xtender_custom_mime_types' );
add_filter( 'widget_text', 'do_shortcode' );
add_filter( 'widget_title', 'do_shortcode');
add_filter( 'widget_text', 'curly_shortcode_sanitizer' );
add_filter( 'the_content', 'curly_shortcode_sanitizer' );


function xtender_minify_css( $string ) {
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

function xtender_init_components(){

  if( defined( 'XTENDER_COMP_DENTAL' ) && filter_var( XTENDER_COMP_DENTAL, FILTER_VALIDATE_BOOLEAN ) ){
		require XTENDER_PATH . 'class.component.icons.dental.php';
	}

  if( defined( 'XTENDER_COMP_AIRLINE' ) && wp_validate_boolean( XTENDER_COMP_AIRLINE ) ){
		require XTENDER_PATH . 'class.component.icons.airline.php';
	}



}

if( defined( 'XTENDER_THEMPREFIX' ) && XTENDER_THEMPREFIX === 'leisure' ){

  require XTENDER_PATH . '/themes/leisure/class.shortcode.icons.hotel.php';
  require XTENDER_PATH . '/themes/leisure/class.rooms.php';
  require XTENDER_PATH . '/themes/leisure/class.open-graph.php';
  require XTENDER_PATH . '/themes/leisure/class.navigation.php';
  require XTENDER_PATH . '/themes/leisure/class.ninja.forms.php';
  require XTENDER_PATH . '/themes/leisure/class.vc.person.php';

}

function curly_shortcode_sanitizer( $content ) {

		$array = array(
	        '<p>['    	=> '[',
	        ']</p>'   	=> ']',
	        ']<br />' 	=> ']',
	        ']<br>' 	=> ']',
	        ']&#10;' 	=> ']',
	        '&#10;[' 	=> '['
	    );

	    return strtr( $content, $array );

	}

function xtender_register_admin_assets(){

	wp_enqueue_style(
		'xtender-admin-style',
		XTENDER_URL . 'assets/admin/css/admin.css',
		null,
		filter_var( WP_DEBUG, FILTER_VALIDATE_BOOLEAN ) ? rand() : null,
		'all'
	);

	wp_enqueue_script(
		'curly-admin-scripts',
		XTENDER_URL . 'assets/admin/js/scripts-min.js',
		array( 'jquery' ),
		wp_validate_boolean( WP_DEBUG ) ? rand() : null
	);

	wp_localize_script( 'curly-admin-scripts', 'curly_locale', array( 'confirm_dialog' => __( 'Are you sure?', 'xtender' ) ) );

}

function xtender_register_assets(){

  if( ! wp_script_is( 'isotope', 'registered' ) ){
    wp_register_script(
  		'isotope',
  		XTENDER_URL . 'assets/vendor/isotope/isotope.pkgd.min.js',
  		array( 'jquery', 'imagesloaded' ),
  		filter_var( WP_DEBUG, FILTER_VALIDATE_BOOLEAN ) ? rand() : rand(),
  		true
  	);
  }

  if( ! wp_script_is( 'owl-carousel', 'registered' ) ){
    wp_register_script(
  		'owl-carousel',
  		XTENDER_URL . 'assets/vendor/owl-carousel/owl.carousel.min.js',
  		array( 'jquery' ),
  		filter_var( WP_DEBUG, FILTER_VALIDATE_BOOLEAN ) ? rand() : rand(),
  		true
  	);
  }

  if( ! wp_style_is( 'owl-carousel', 'registered' ) ){
    wp_register_style(
  		'owl-carousel',
  		XTENDER_URL . 'assets/vendor/owl-carousel/owl.carousel.min.css',
  		null,
  		filter_var( WP_DEBUG, FILTER_VALIDATE_BOOLEAN ) ? rand() : rand()
  	);
  }

	wp_enqueue_script(
		'xtender-scripts',
		XTENDER_URL . 'assets/front/js/scripts-min.js',
		array( 'jquery' ),
		filter_var( WP_DEBUG, FILTER_VALIDATE_BOOLEAN ) ? rand() : rand(),
		true
	);


	$xtender_data = array(
		'ajax_url' => admin_url( 'admin-ajax.php' )
	);

	wp_localize_script( 'xtender-scripts', 'xtender_data', $xtender_data );

	wp_enqueue_style(
		'xtender-style',
		XTENDER_URL . 'assets/front/css/xtender.css',
		null,
		filter_var( WP_DEBUG, FILTER_VALIDATE_BOOLEAN ) || is_user_logged_in() ? rand() : '1.3.1',
		'all'
	);

}

function xtender_custom_mime_types( $mimes ){

		$mimes['mp4'] = 'video/mp4';
		$mimes['webm'] = 'video/webm';
		$mimes['ogg'] = 'video/ogg';
		$mimes['ogv'] = 'video/ogv';
		$mimes['svg'] = 'image/svg+xml';

		return $mimes;

}

function my_mce4_options( $init ) {



  $default_colours = '"000000", "Black",
                      "993300", "Burnt orange",
                      "333300", "Dark olive",
                      "003300", "Dark green",
                      "003366", "Dark azure",
                      "000080", "Navy Blue",
                      "333399", "Indigo",
                      "333333", "Very dark gray",
                      "800000", "Maroon",
                      "FF6600", "Orange",
                      "808000", "Olive",
                      "008000", "Green",
                      "008080", "Teal",
                      "0000FF", "Blue",
                      "666699", "Grayish blue",
                      "808080", "Gray",
                      "FF0000", "Red",
                      "FF9900", "Amber",
                      "99CC00", "Yellow green",
                      "339966", "Sea green",
                      "33CCCC", "Turquoise",
                      "3366FF", "Royal blue",
                      "800080", "Purple",
                      "999999", "Medium gray",
                      "FF00FF", "Magenta",
                      "FFCC00", "Gold",
                      "FFFF00", "Yellow",
                      "00FF00", "Lime",
                      "00FFFF", "Aqua",
                      "00CCFF", "Sky blue",
                      "993366", "Red violet",
                      "FFFFFF", "White",
                      "FF99CC", "Pink",
                      "FFCC99", "Peach",
                      "FFFF99", "Light yellow",
                      "CCFFCC", "Pale green",
                      "CCFFFF", "Pale cyan",
                      "99CCFF", "Light sky blue",
                      "CC99FF", "Plum"';

  $custom_colours =  '"E14D43", "Color 1 Name",
                      "D83131", "Color 2 Name",
                      "ED1C24", "Color 3 Name",
                      "F99B1C", "Color 4 Name",
                      "50B848", "Color 5 Name",
                      "00A859", "Color 6 Name",
                      "00AAE7", "Color 7 Name",
                      "282828", "Color 8 Name"';

  // build colour grid default+custom colors
  $init['textcolor_map'] = '['.$default_colours.','.$custom_colours.']';

  // enable 6th row for custom colours in grid
  $init['textcolor_rows'] = 6;

  return $init;
}
//add_filter('tiny_mce_before_init', 'my_mce4_options');

?>
