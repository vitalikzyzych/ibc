<?php

	class xtenderVCIsotope{

		function __construct(){

			add_action( 'vc_before_init', array( $this, 'isotope_vc' ) );
			add_shortcode( 'curly_isotope', array( $this, 'isotope' ) );
			add_shortcode( 'curly_isotope_item', array( $this, 'isotope_item' ) );

		}

		function load_assets(){

			global $post;

			if( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'curly_isotope') ) {
				wp_enqueue_script( 'isotope' );
				wp_enqueue_style( 'isotope' );
				wp_enqueue_script( 'xtender-isotope', XTENDER_URL . 'assets/front/js/xtender-owl-carousel-min.js', array( 'jquery', 'isotope', 'imagesloaded' ), rand(), true );
			}

		}

		public function isotope( $atts, $content = null ){

			if( ! isset( $_SESSION['curly-isotope'] ) ){
				$_SESSION['curly-isotope'] = 1;
			} else {
				$_SESSION['curly-isotope'] = $_SESSION['curly-isotope'] + 1;
			}

			$filters = ( isset( $atts['filters'] ) ) ? explode(',', $atts['filters'] ) : null;

			$html = '<ul class="list-inline isotope-filter clearfix" id="isotope-filter-'.$_SESSION['curly-isotope'].'">';
			$html .= ( isset( $atts['filters_before'] ) ) ? '<li><strong>'.$atts['filters_before'].'</strong></li>' : null;
			$html .= ( isset( $atts['filters_all'] ) ) ? '<li class="selected" data-filter="*"><a href="#">'.$atts['filters_all'].'</a></li>' : null;
			if ( is_array( $filters ) ) {
				foreach ( $filters as $key => $value) {
					$html .= '<li><a href="#" data-filter=".'.strtolower( sanitize_file_name( trim( $value ) ) ).'">'.ucwords( $value ).'</a></li>';
				}
			}
			$html .= '</ul><br>';
			$html .= '<div id="isotope-'.$_SESSION['curly-isotope'].'" class="isotope" data-isotope-options="{ "layoutMode": "horizontal", "itemSelector": ".item" }">';
			$html .= do_shortcode( $content );
			$html .= '</div>';

			$html .= "<script type='text/javascript'>( function( $ ) { $( document ).ready(function() {
				var container = $('#isotope-".$_SESSION['curly-isotope']."')
				container.imagesLoaded(function(){
					container.isotope();
					$('#isotope-filter-".$_SESSION['curly-isotope']." a').on('click', function (e) {
						e.preventDefault();
						var filterValue = $(this).attr('data-filter');
						$('#isotope-".$_SESSION['curly-isotope']."').isotope({ layoutMode: 'fitRows', filter: filterValue });
						$(this).parent().siblings().removeClass('selected');
						$(this).parent().addClass('selected');
					});
				});
			}); } )( jQuery );</script>";

			return $html;

		}

		function normalize_filters(&$item1, $key){
			$item1 = strtolower( sanitize_file_name( $item1 ) );
		}

		/** Isotope Shortcode */
		public function isotope_item( $atts, $content = null ){

			$atts = shortcode_atts( array(
				'filters' => '',
				'title'	=> '',
				'image'	=> '',
				'link'	=> '#',
				'description'	=> "Nihil hic munitissimus habendi senatus locus, nihil horum?",
				'new'	=> ''
			), $atts, 'curly_istotope_item' );

			extract( $atts );

			$filters = ! empty( $filters ) ? explode(',', $atts['filters'] ) : '';

			if( is_array( $filters ) ){
				array_walk( $filters, array( $this, 'normalize_filters' ) );
			}

			$link = esc_url_raw( $link );
			$target = wp_validate_boolean( $new ) ? '_blank' : '_self';
			$image = ! empty( $image ) ? wp_get_attachment_image( $atts['image'] , 'large')  : '';

			ob_start();

			$template = xtender_get_template_part( 'vc/isotope', 'item' );

			if( isset( $template ) && ! empty( $template ) && $template !== false ){
				include( $template );
			}

			return ob_get_clean();

		}


		public function isotope_vc() {

			/** Isotope Container */
			vc_map( array(
			   "name" => __("Isotope Grid", 'xtender'),
			   "base" => "curly_isotope",
			   "as_parent" => array('only' => 'curly_isotope_item'),
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
			         "heading" => __("Filters Text", 'xtender'),
			         "param_name" => "filters_before",
			         "value" => 'Filter Services',
			         "description" => __("Enter the text before the filters", 'xtender')
			      ),
			      array(
			         "type" => "textfield",
			         "heading" => __("All Items Text", 'xtender'),
			         "param_name" => "filters_all",
			         "value" => 'All',
			         "description" => __("Enter the text for all items", 'xtender')
			      ),
			      array(
			         "type" => "textfield",
			         "heading" => __("Filter Tags", 'xtender'),
			         "param_name" => "filters",
			         "description" => __("Enter a comma separated list for filters (ie: sky, water sports, nightlife)", 'xtender')
			      )
			   ),
			   "js_view" => 'VcColumnView'
			) );

			/** Isotope Item */
			vc_map( array(
			    "name" => __("Isotope Grid Item", 'xtender'),
			    "base" => "curly_isotope_item",
			    "content_element" => true,
			    "icon" => "curly_icon",
			    "as_child" => array('only' => 'curly_isotope'),
			    "params" => array(
			        array(
			            "type" => "textfield",
			            "heading" => __("Title", 'xtender'),
			            "holder" => "div",
			            "value" => "Some title here",
			            "param_name" => "title"
			        ),
			        array(
			            "type" => "textarea",
			            "heading" => __("Description", 'xtender'),
			            "value" => "Nihil hic munitissimus habendi senatus locus, nihil horum?",
			            "param_name" => "description"
			        ),
			        array(
			            "type" => "attach_image",
			            "heading" => __("Image", 'xtender'),
			            "param_name" => "image"
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
			        ),
			        array(
			            "type" => "textfield",
			            "heading" => __("Filter Tags", 'xtender'),
			            "param_name" => "filters",
			            "description" => __("Enter a comma separated list for filters (ie: sky, water sports, nightlife)", 'xtender')
			        )
			    )
			) );
		}

}

/** Check if Visual Composer is Activated */
if ( defined( 'WPB_VC_VERSION' ) ) {

	/** Extend Classes */
	if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
	    class WPBakeryShortCode_Curly_Isotope extends WPBakeryShortCodesContainer {}
	}
	if ( class_exists( 'WPBakeryShortCode' ) ) {
	    class WPBakeryShortCode_Curly_Isotope_Item extends WPBakeryShortCode {}
	}

}

$xtender_services_list =	new xtenderVCIsotope();

?>
