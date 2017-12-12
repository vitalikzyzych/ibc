<?php

class XtenderShortcodeLead {

	function __construct() {
    add_shortcode( 'lead', array( 'XtenderShortcodeLead', 'lead_shortcode' ) );
  }

	public static function lead_shortcode( $atts, $content = null ){

		return '<p class="lead">'.$content.'</p>';
	}

}

$xtender_shortcodeselead = new XtenderShortcodeLead();

?>
