<?php

class XtenderShortcodeIconsHotel {

	function __construct() {
		if ( ! shortcode_exists( 'hotel-icon' ) ) {
			add_shortcode( 'hotel-icon', array( 'XtenderShortcodeIconsHotel', 'icon_shortcode' ) );
			add_shortcode( 'curly_hotel_icon', array( 'XtenderShortcodeIconsHotel', 'icon_shortcode' ) );
		} else {
			add_shortcode( 'curly_hotel_icon', array( 'XtenderShortcodeIconsHotel', 'icon_shortcode' ) );
		}

		add_action( 'wp_enqueue_scripts', array( $this, 'load_assets' ) );

  }

	function load_assets(){
		wp_enqueue_style(
			'hotel-icons',
			 XTENDER_URL . 'assets/front/css/hotel-icons.css',
			array( 'leisure-style' ), rand(), 'all'
		);
	}

  public static function icon_shortcode( $atts, $content = null ) {

		if ( isset( $atts['icon'] ) ) {

			$css 	= $atts['icon'];
			$data 	= '';

			/** Set icon size */
			if ( isset( $atts['size'] ) ) {
				switch ( strtolower( $atts['size'] ) ) {
					case '2x' : $css .= ' hi-2x'; break;
					case '3x' : $css .= ' hi-3x'; break;
					case '4x' : $css .= ' hi-4x'; break;
					case '5x' : $css .= ' hi-5x'; break;
					case 'lg' : $css .= ' hi-lg'; break;
				}
			}

			/** Tooltip */
			if( isset( $atts['tooltip'] ) ){
				$data  = "data-toggle='tooltip'";
				$data .= " title='{$atts['tooltip']}'";
			}

			/** Class */
			if( isset( $atts['class'] ) ){
				$css .= " {$atts['class']}";
			}

			/** Style */
			if ( isset( $atts['boxed'] ) && $atts['boxed'] == 'yes' ) {
				$css .= ' hi-boxed';
			}

			return "<span class='hotel-icon-$css' $data></span>";
		}

	}

}

$xtender_shortcodes_icons_hotel = new XtenderShortcodeIconsHotel();

?>
