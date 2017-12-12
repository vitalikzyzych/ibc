<?php

	class xtenderVCCurlyPerson{

		function __construct(){

			/** Construct Services Carousel */
			add_action( 'vc_before_init', array( $this, 'person_vc' ) );
			add_shortcode( 'curly_person', array( $this, 'person' ) );

		}

		public function person( $atts, $content = null ){

	    	$atts = shortcode_atts( array(
    			'size' 	=> 'person-mini',
    			'image' => '',
    			'name'	=> '',
    			'title'	=> '',
    			'phone'	=> '',
    			'email'	=> '',
    			'description'	=> ''
    		), $atts, 'curly_person' );

				extract( $atts );

	    	$size  = $size === 'person-large' ? $size : 'person-mini';
	    	$image = ! empty( $image ) ? wp_get_attachment_image( $image, 'large' ) : '';

	    	$html  = '<div class="'.$size.'">';
	    	$html .= $image;
	    	$html .= ! empty( $name ) ? "<strong>{$name}</strong>" : '';
	    	$html .= $title;
	    	$html .= ! empty( $phone ) ? "<br>{$phone}" : '';
	    	$html .= ! empty( $email ) ? "<br>{$email}" : '';
	    	$html .= ! empty( $description ) ? "<br>{$description}" : '';
	    	$html .= '</div>';

	    	return $html;

	    }


		public function person_vc() {

	    	/** Isotope Item */
	    	vc_map(
	    		array(
		    	    "name" => __("Person", 'xtender'),
		    	    "base" => "curly_person",
		    	    "content_element" => true,
		    	    "icon" => "curly_icon",
		    	    "class" => '',
		    	    "category" => 'xtender',
		    	    "params" => array(
		    	        array(
		    	            "type" => "textfield",
		    	            "heading" => __("Person Name", 'xtender'),
		    	            "holder" => "div",
		    	            "param_name" => "name"
		    	        ),
		    	        array(
		    	            "type" => "attach_image",
		    	            "heading" => __("Image", 'xtender'),
		    	            "param_name" => "image"
		    	        ),
		    	        array(
		    	            "type" => "textfield",
		    	            "heading" => __("Title", 'xtender'),
		    	            'edit_field_class' => 'vc_col-sm-4 vc_column',
		    	            "param_name" => "title"
		    	        ),
		    	        array(
		    	            "type" => "textfield",
		    	            "heading" => __("E-mail", 'xtender'),
		    	            'edit_field_class' => 'vc_col-sm-4 vc_column',
		    	            "param_name" => "email"
		    	        ),
		    	        array(
		    	            "type" => "textfield",
		    	            "heading" => __("Phone", 'xtender'),
		    	            'edit_field_class' => 'vc_col-sm-4 vc_column',
		    	            "param_name" => "phone"
		    	        ),
		    	        array(
		    	            "type" => "textarea",
		    	            "heading" => __("Extra Description", 'xtender'),
		    	            'edit_field_class' => 'vc_col-sm-8 vc_column',
		    	            "param_name" => "description"
		    	        ),
		    	        array(
		    	            "type" => "dropdown",
		    	            "heading" => __("Size", 'xtender'),
		    	            'edit_field_class' => 'vc_col-sm-4 vc_column',
		    	            "param_name" => "size",
		    	            'value' => array(
		    	            	__('Large', 'xtender') => '',
		    	            	__('Small', 'xtender') => 'small',
		    	            )
		    	        )
	    	    	)
	    		)
	    	);
	    }

}

$xtender_curly_person =	new xtenderVCCurlyPerson();

?>
