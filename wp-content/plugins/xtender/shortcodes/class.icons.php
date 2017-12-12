<?php

class XtenderShortcodeIcons {

	function __construct() {
    add_shortcode( 'icon', array( 'XtenderShortcodeIcons', 'icon_shortcode' ) );
  }

  public static function icon_shortcode( $atts, $content = null ) {

		$css = $style = array();
		$before = $after ='';

		if( ! isset( $atts['icon']  ) )
			return;

		$icon = $atts['icon'];

		if( preg_match( '(ti-|fa-)', $icon ) !== 1  ){
			$icon = "fa-{$icon}";
		}

		/** Set icon size */
		if ( isset( $atts['size'] ) ) {
			switch ( strtolower( $atts['size'] ) ) {
				case '2x' : $icon .= ' fa-2x'; break;
				case '3x' : $icon .= ' fa-3x'; break;
				case '4x' : $icon .= ' fa-4x'; break;
				case '5x' : $icon .= ' fa-5x'; break;
				case 'lg' : $icon .= ' fa-lg'; break;
			}
		}

		/** Display */
		if ( isset( $atts['display'] ) ) {
			switch ( strtolower( $atts['display'] ) ) {
				case 'inline' : $icon .= ' fa-inline'; break;
				case 'block'  : $icon .= ' fa-block'; break;
			}
		}

		if ( isset( $atts['align'] ) ) {
			switch ( strtolower( $atts['align'] ) ) {
				case 'left' : $icon .= ' fa-text-left'; break;
				case 'right'  : $icon .= ' fa-text-right'; break;
			}
		}

		/** Icon color */
		if ( isset( $atts['color'] ) )
			array_push( $style, 'color: '.$atts['color'] );

		/** Border */
		if ( isset( $atts['bordered'] ) )
			array_push( $css, 'fa-border' );

    if( isset( $atts['border'] ) )
      array_push( $style, 'border: '.$atts['border'] );

		if ( isset( $atts['border_color'] ) || isset( $atts['border_style'] ) || isset( $atts['border_size'] ) ) {
			array_push( $css, 'fa-bordered' );
			if ( isset( $atts['border_color'] )  ) {
				array_push( $style, 'border-color: '.$atts['border_color'] );
			}
			if ( isset( $atts['border_style'] )  ) {
				array_push( $style, 'border-style: '.$atts['border_style'] );
			}
			if ( isset( $atts['border_size'] )  ) {
				array_push( $style, 'border-width: '.$atts['border_size'] );
			}
		}

		/** Style */
		if ( isset( $atts['boxed'] ) && filter_var( $atts['boxed'], FILTER_VALIDATE_BOOLEAN ) ) {
			array_push( $css, 'fa-boxed' );
		}

		/** Background */
		if ( isset( $atts['background'] ) ) {
			array_push( $css, 'fa-boxed' );
			array_push( $style, 'background-color: '.$atts['background'] );
		}

		if( isset( $atts['link'] ) ){
			$nice_scroll = strpos( $atts['link'], "http" !== false ) ? '' : ' class="nice-scroll" ';
			$target = ( isset( $atts['new_window'] ) && filter_var( $atts['new_window'], FILTER_VALIDATE_BOOLEAN ) ? '_target' : '_self' );
			$before = "<a href='{$atts['link']}' target='$target' $nice_scroll>";
			$after  = "</a>";

		}

		if( ! is_null( $content ) && ! empty( $content ) ){

			$block_size = isset( $atts['size'] ) ? 'icon-bullet--' . strtolower( $atts['size'] ) : '';

			return "<div class='icon-bullet $block_size'><i class='fa fa-fw $icon ".implode( ' ', $css )."' style='".implode( '; ', $style )."'></i>" . do_shortcode( $content ) . "</div>";

		} else {

			return "$before<i class='fa fa-fw $icon ".implode( ' ', $css )."' style='".implode( '; ', $style )."'></i>$after";

		}

	}

}

$xtender_shortcodes_icons = new XtenderShortcodeIcons();

?>
