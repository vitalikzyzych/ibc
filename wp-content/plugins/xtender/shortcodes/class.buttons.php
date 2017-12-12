<?php

class XtenderShortcodeButtons {

	function __construct() {
    add_shortcode( 'button', array( 'XtenderShortcodeButtons', 'button_shortcode' ) );
  }

  public static function button_shortcode( $atts, $content = null ) {

		$atts = shortcode_atts( array(
			'style' => 'primary',
			'size' 	=> null,
			'outline' => null,
			'display'	=> null,
			'link' 		=> null,
			'target' 	=> null
		), $atts, 'button' );

		extract( $atts );

		$suffix = ! is_null( $outline ) ? '-outline' : '';

		$classes = array();
		$classes[] = 'btn-' . strtolower( $style ) . $suffix;

		switch( $size ){
			case 'small' : $classes[] = 'btn-sm'; break;
			case 'large' : $classes[] = 'btn-lg'; break;
		}

		if( ! is_null( $display ) )
			$classes[] = 'btn-block';

		$content 	= esc_attr( $content );
		$link 		= esc_url_raw( $link );
		$link			= empty( $link ) ? '#' : $link;
		$target		= "_$target";

		if( strpos( $link, '#' ) === 0 )
			$classes[] = 'smooth-scroll';

		$classes 	= implode( ' ', $classes );

		return "<a class='btn $classes' href='$link' role='button'>$content</a>";

	}

}

$xtender_shortcodes_buttons = new XtenderShortcodeButtons();

?>
