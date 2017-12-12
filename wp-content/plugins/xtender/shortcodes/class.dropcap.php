<?php

class XtenderShortcodeDropcap {

	function __construct() {
    add_shortcode( 'dropcap', array( 'XtenderShortcodeDropcap', 'dropcap_shortcode' ) );
  }

  public static function dropcap_shortcode( $atts, $content = null ) {

		$atts = shortcode_atts( array(), $atts, 'button' );

		return "<span class='" . apply_filters( 'xtender_sc_dropcap_classes', 'dropcap' ) . "'>$content</span>";

	}

}

$xtender_shortcodesedropcap = new XtenderShortcodeDropcap();

?>
