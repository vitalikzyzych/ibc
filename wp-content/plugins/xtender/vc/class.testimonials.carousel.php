<?php

	class xtenderVCTestimonialsCarousel{

		function __construct(){

			/** Construct Services Carousel */
			add_action( 'vc_before_init', array( $this, 'testimonials_carousel_vc' ) );
			add_shortcode( 'curly_testimonials_carousel', array( $this, 'testimonials_carousel' ) );
			add_shortcode( 'curly_testimonial', array( $this, 'testimonial' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'load_assets' ), 20 );

		}

		function load_assets(){

			global $post;

			if( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'curly_testimonials_carousel') ) {
				wp_enqueue_script( 'owl-carousel');
				wp_enqueue_style( 'owl-carousel');
				wp_enqueue_script( 'xtender-owl-carousel', XTENDER_URL . 'assets/front/js/xtender-owl-carousel-min.js', array( 'jquery', 'owl-carousel', 'imagesloaded' ), rand(), true );
			}

		}

		public function testimonials_carousel( $atts, $content = null ) {

			$atts = shortcode_atts( array(
				'title' => '',
				'subtitle'		=> '',
				'items'	=> 3,
				'items_table'	=> 2,
				'items_mobile'	=> 1,
				'dots'	=> false,
				'hover'	=> false,
				'loop'	=> false,
				'next'	=> '',
				'prev'	=> '',
				'css'	=> '',
				'autoplay_speed'	=> 2000
			), $atts, 'curly_testimonials_carousel' );

			extract( $atts );

			$el_class = $css;
			if ( ! empty( $el_class ) ) {
				$el_class = " " . str_replace( ".", "", $el_class );
				$el_class = substr( $el_class , 0, strpos( $el_class, '{' ) );
			}

			if ( isset( $_SESSION['curly_testimonials'] ) ) {
				$_SESSION['curly_testimonials'] = $_SESSION['curly_testimonials'] + 1;
			} else {
				$_SESSION['curly_testimonials'] = 0;
			}

			$html = '';

			if ( ! empty( $title ) ) {
				$html .= '<h2 class="text-center">';
				$html .= ! empty( $subtitle ) ? "<small>{$subtitle}</small>" : '';
				$html .= $atts['title'];
				$html .= '</h2>';
			}

			$hover	= wp_validate_boolean( $hover );
			$loop		= wp_validate_boolean( $loop );
			$dots		= wp_validate_boolean( $dots );

			if ( ! empty( $next ) && ! empty( $prev ) ) {
				$nav = true;
				$nav_text = array( $prev, $next );
			} else {
				$nav = false;
				$nav_text = array();
			}
			if ( ! empty( $autoplay_speed ) ) {
				$autoplay = true;
			} else {
				$autoplay = false;
				$autoplay_speed = 0;
			}

			if ( $nav || $dots ) {
				$el_class .= ' owl-with-navigation';
			}

			$dataParams = array(
				'items' => intval( $items ),
				'nav' => $nav,
				'navText' => $nav_text,
				'loop' => $loop,
				'autoplay' => $autoplay,
				'autoplayTimeout' => $autoplay_speed,
				'autoplayHoverPause' => $hover,
				'responsive' => array(
					0 => array(
						'items' => intval( $items_mobile ),
						'dots'	=> $dots,
						'nav'		=> $nav
					),
					767 => array(
						'items' => intval( $items_table ),
						'dots'	=> $dots,
					),
					992 => array(
						'items' => intval( $items ),
						'dots'	=> $dots,
					),
				)
			);

			$html .= "<div class='testimonials-carousel $el_class' data-owl='".htmlspecialchars(json_encode($dataParams), ENT_QUOTES, 'UTF-8')."'>".do_shortcode( $content )."</div>";

			return $html;
		}

		/** Testimonial Shortcode */
		public function testimonial( $atts, $content = null ) {

			$atts = shortcode_atts( array(
				'name' => '',
				'date'	=> '',
				'testimonial'	=> '',
				'link'	=> '#',
				'text_link'	=> '',
				'new' => ''
			), $atts, 'curly_testimonial' );

			extract( $atts );

			$link = esc_url_raw( $link );
			$target = wp_validate_boolean( $new ) ? '_blank' : '_self';

			ob_start();

			$template = xtender_get_template_part( 'vc/testimonials-carousel', 'testimonial' );

			if( isset( $template ) && ! empty( $template ) && $template !== false ){
				include( $template );
			}

			return ob_get_clean();

		}


		public function testimonials_carousel_vc() {

			/** Carousel Container */
			vc_map( array(
			   "name" => __("Testimonials Carousel", 'xtender'),
			   "base" => "curly_testimonials_carousel",
			   "as_parent" => array('only' => 'curly_testimonial'),
			   "content_element" => true,
			   'is_container' => true,
			   "show_settings_on_create" => false,
			   "admin_enqueue_css" => array( XTENDER_URL . 'assets/admin/css/vc-icon.css' ),
			   "icon" => "curly_icon",
			   "class" => "",
			   "category" => 'xtender',
			   "params" => array(
			   	  array(
			   	     "type" => "textfield",
			   	     "heading" => __("Widget Title", 'xtender'),
			   	     'edit_field_class' => 'vc_col-sm-6',
			   	     "param_name" => "title",
			   	     "value" => null,
			   	     "description" => __("Enter widget title", 'xtender')
			   	  ),
			   	  array(
			   	     "type" => "textfield",
			   	     "heading" => __("Widget Subtitle", 'xtender'),
			   	     'edit_field_class' => 'vc_col-sm-6 vc_column',
			   	     "param_name" => "subtitle",
			   	     "value" => null,
			   	     "description" => __("Enter widget subtitle", 'xtender')
			   	  ),
			      array(
			         "type" => "textfield",
			         "heading" => __("Desktop Testimonials", 'xtender'),
			         'edit_field_class' => 'vc_col-sm-4 vc_column',
			         "param_name" => "items",
			         "value" => 3,
			         "description" => __("Testimonials on a computer", 'xtender')
			      ),
			      array(
			         "type" => "textfield",
			         "heading" => __("Tablet Testimonials", 'xtender'),
			         'edit_field_class' => 'vc_col-sm-4 vc_column',
			         "param_name" => "items_tablet",
			         "value" => 2,
			         "description" => __("Testimonials on a tablet", 'xtender')
			      ),
			      array(
			         "type" => "textfield",
			         "heading" => __("Mobile Testimonials", 'xtender'),
			         'edit_field_class' => 'vc_col-sm-4 vc_column',
			         "param_name" => "items_mobile",
			         "value" => 1,
			         "description" => __("Testimonials on a mobile", 'xtender')
			      ),
			      array(
			         "type" => "textfield",
			         "heading" => __("Autoplay Speed", 'xtender'),
			         "param_name" => "autoplay_speed",
			         "value" => 2000,
			         "description" => __("Choose the carousel autoplay speed in milliseconds. Leave blank to disable the autoplay", 'xtender')
			      ),
			      array(
			         "type" => "checkbox",
			         "heading" => __("Pause on hover", 'xtender'),
			         "param_name" => "hover",
			         'value' => array( __( 'Yes, pause carousel on hover', 'xtender' ) => 'yes' )
			      ),
			      array(
			         "type" => "checkbox",
			         "heading" => __("Loop", 'xtender'),
			         'edit_field_class' => 'vc_col-sm-6 vc_column',
			         "param_name" => "loop",
			         'value' => array( __( 'Yes, play the carousel in a loop', 'xtender' ) => 'yes' )
			      ),
			      array(
			         "type" => "checkbox",
			         "heading" => __("Navigation", 'xtender'),
			         'edit_field_class' => 'vc_col-sm-6 vc_column',
			         "param_name" => "dots",
			         'value' => array( __( 'Yes, enable dots navigation', 'xtender' ) => 'yes' )
			      ),
			      array(
			         "type" => "textfield",
			         "heading" => __("Next Button Text", 'xtender'),
			         'edit_field_class' => 'vc_col-sm-6 vc_column',
			         "param_name" => "next",
			         "value" => '',
			         "description" => __("Leave blank to disable links", 'xtender')
			      ),
			      array(
			         "type" => "textfield",
			         "heading" => __("Previous Button Text", 'xtender'),
			         'edit_field_class' => 'vc_col-sm-6 vc_column',
			         "param_name" => "prev",
			         "value" => '',
			         "description" => __("Leave blank to disable links", 'xtender')
			      ),
			      array(
			      	 'type' => 'css_editor',
			      	 'heading' => __( 'Css', 'js_composer' ),
			      	 'param_name' => 'css'
			      )
			   ),
			   "js_view" => 'VcColumnView'
			) );

			/** Carousel Item */
			vc_map( array(
			    "name" => __("Testimonial" , 'xtender'),
			    "base" => "curly_testimonial",
			    "content_element" => true,
			    "icon" => "curly_icon",
			    "as_child" => array('only' => 'curly_testimonials_carousel'),
			    "params" => array(
			        array(
			            "type" => "textfield",
			            "heading" => __("Person Name", 'xtender'),
			            "holder" => "div",
			            "value" => "Curly Green",
			            "param_name" => "name"
			        ),
			        array(
			            "type" => "textarea",
			            "heading" => __("Testimonial", 'xtender'),
			            "value" => "",
			            "param_name" => "testimonial"
			        ),
			        array(
			            "type" => "textfield",
			            "heading" => __("Date", 'xtender'),
			            "value" => "",
			            "param_name" => "date"
			        ),
			        array(
			            "type" => "textfield",
			            "heading" => __("Text Link", 'xtender'),
			            "value" => "",
			            "param_name" => "text_link"
			        ),
			        array(
			            "type" => "textfield",
			            "heading" => __("Link", 'xtender'),
			            "value" => "#",
			            "param_name" => "link"
			        ),
			        array(
			            "type" => "checkbox",
			            "heading" => __("Open link in new window", 'xtender'),
			            "param_name" => "new"
			        )
			    )
			) );
		}

}

/** Check if Visual Composer is Activated */
if ( defined( 'WPB_VC_VERSION' ) ) {

	/** Extend Classes */
	if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
	    class WPBakeryShortCode_Curly_Testimonials_Carousel extends WPBakeryShortCodesContainer {}
	}
	if ( class_exists( 'WPBakeryShortCode' ) ) {
	    class WPBakeryShortCode_Curly_Testimonial extends WPBakeryShortCode {}
	}

}

$xtender_services_carousel =	new xtenderVCTestimonialsCarousel();

?>
