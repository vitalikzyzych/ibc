<?php
class PirouetteWPML {

	function __construct(){

	}

	public static function get_wpml_selector(){

    if( ! function_exists('icl_get_languages') || filter_var( esc_attr( get_theme_mod( 'wpml' ) ), FILTER_VALIDATE_BOOLEAN ) )
      return;


	    $languages = icl_get_languages('skip_missing=0&order=custom');

	    if( ! empty( $languages ) ){

		    $html = '<div class="wpml-switcher">';

	        foreach( $languages as $language ){
	        	if( $language['active'] ) $html .= '<span class="active">'; else $html .= '<span>';
	            if( ! $language['active'] ) $html .= '<a href="'.$language['url'].'">';
	            $html .= $language['code'];
	            if( ! $language['active'] ) $html .= '</a>';
	            $html .= '</span>';
	        }

	        $html .= '</div>';
	    }

	    echo ( isset( $html ) ) ? $html : null;
	}

}

if( has_action('icl_language_selector') ) {
	new PirouetteWPML();
}

?>
