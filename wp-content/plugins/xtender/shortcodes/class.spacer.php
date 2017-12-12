<?php

class XtenderShortcodeSpacer {

	function __construct() {
    add_shortcode( 'spacer', array( 'XtenderShortcodeSpacer', 'spacer_shortcode' ) );
  }

	public static function spacer_shortcode( $atts, $content = null ){

		switch( true ){

			case isset( $atts['size'] ) && $atts['size'] === 'xxl' : $size = '-xxl'; break;
			case isset( $atts['size'] ) && $atts['size'] === 'xl' : $size = '-xl'; break;
			case isset( $atts['size'] ) && $atts['size'] === 'lg' : $size = '-lg'; break;
			case isset( $atts['size'] ) && $atts['size'] === 'sm' : $size = '-sm'; break;
			default : $size = '';

		}

		return '<div class="vc_empty_space spacer' . $size . '"><span class="vc_empty_space_inner"></span></div>';
	}

}

$xtender_shortcodesespacer = new XtenderShortcodeSpacer();

?>
