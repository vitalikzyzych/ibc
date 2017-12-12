<?php

	class xtenderVCServicesList{

		function __construct(){

			/** Construct Services Carousel */
      add_action( 'vc_before_init', array( $this, 'services_list_vc' ) );
			add_shortcode( 'curly_services_list', array( $this, 'services_list' ) );
			add_shortcode( 'curly_services_list_item', array( $this, 'services_list_item' ) );

		}

    public function services_list( $atts, $content = null ){

			$atts = shortcode_atts( array(
				'css' 	=> '',
				'el_class' => ''
			), $atts, 'curly_services_list' );

			extract( $atts );

			$el_class = $css;
			if ( ! empty( $el_class ) ) {
				$el_class = " " . str_replace( ".", "", $el_class );
				$el_class = substr( $el_class , 0, strpos( $el_class, '{' ) );
			}

			$html = '<ul class="list-services '.$el_class.'">';
			$html .= do_shortcode( $content );
			$html .= '</ul>';

			return $html;

		}

		/** Isotope Shortcode */
		public function services_list_item( $atts, $content = null ){

			$atts = shortcode_atts( array(
				'link' 	=> '#',
				'title' => '',
				'description'	=> ''
			), $atts, 'curly_services_list_item' );

			extract( $atts );

			ob_start();

			$template = xtender_get_template_part( 'vc/services-list', 'service' );

			if( isset( $template ) && ! empty( $template ) && $template !== false ){
				include( $template );
			}

			return ob_get_clean();
		}


    public function services_list_vc() {

			/** Services Container */
			vc_map( array(
			   "name" => __("Services List", 'xtender'),
			   "base" => "curly_services_list",
			   "as_parent" => array('only' => 'curly_services_list_item'),
			   "content_element" => true,
			   'is_container' => true,
			   "show_settings_on_create" => false,
			   "admin_enqueue_css" => array( XTENDER_URL . 'assets/admin/css/vc-icon.css' ),
			   "icon" => "curly_icon",
			   "params" => array(
			   		array(
			   			'type' => 'textfield',
			   			'heading' => __( 'Extra class name', 'js_composer' ),
			   			'param_name' => 'el_class',
			   			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'js_composer' ),
			   		),
			   		array(
			   			'type' => 'css_editor',
			   			'heading' => __( 'Css', 'js_composer' ),
			   			'param_name' => 'css'
			   		)
			   ),
			   "category" => 'xtender',
			   "js_view" => 'VcColumnView'
			) );

			/** Services List Item */
			vc_map( array(
			    "name" => __("Services List Item", 'xtender'),
			    "base" => "curly_services_list_item",
			    "content_element" => true,
			    "icon" => "curly_icon",
			    "as_child" => array('only' => 'curly_services_list'),
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
			            "type" => "textfield",
			            "heading" => __("Link", 'xtender'),
			            "value" => "#",
			            "param_name" => "link"
			        )
			    )
			) );
		}

}

/** Check if Visual Composer is Activated */
if ( defined( 'WPB_VC_VERSION' ) ) {

	/** Extend Classes */
	if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
	    class WPBakeryShortCode_Curly_Services_List extends WPBakeryShortCodesContainer {}
	}
	if ( class_exists( 'WPBakeryShortCode' ) ) {
	    class WPBakeryShortCode_Curly_Services_List_Item extends WPBakeryShortCode {}
	}

}

$xtender_services_list =	new xtenderVCServicesList();

?>
