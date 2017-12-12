<?php

class PirouetteTypographyCSS{

	private $_font;
	private $_font_h1;
	private $_font_h2;
	private $_font_h3;
	private $_font_h4;
	private $_font_h5;
	private $_font_h6;
	private $_font_menu;
	private $_font_blockquote;

	public function __construct(){

		/** Render Typography */
		add_action( 'wp_enqueue_scripts', array( $this, 'render_typography' ) );

	}


	/** Render Typography */
	function render_typography(){

		$this->_font 		= apply_filters( 'pirouette_pre_fonts_array', 'font', 'Lato', 'regular', 16 );
		$this->_font_h1 = apply_filters( 'pirouette_pre_fonts_array', 'font_h1', 'Lato', '700', 66, 'normal', -5 );
		$this->_font_h2 = apply_filters( 'pirouette_pre_fonts_array', 'font_h2', 'Lato', '300', 43, 'normal', -4 );
		$this->_font_h3 = apply_filters( 'pirouette_pre_fonts_array', 'font_h3', 'Lato', '300', 28, 'normal', 0 );
		$this->_font_h4 = apply_filters( 'pirouette_pre_fonts_array', 'font_h4', 'Lato', 'regular', 14, 'normal', 0 );
		$this->_font_h5 = apply_filters( 'pirouette_pre_fonts_array', 'font_h5', 'Lato', 'regular', 18, 'uppercase', 10 );
		$this->_font_h6	= apply_filters( 'pirouette_pre_fonts_array', 'font_h6', 'Lato', 'regular', 16, 'normal', 0 );
		$this->_font_menu					= apply_filters( 'pirouette_pre_fonts_array', 'font_menu', 'Lato', '700', 12, 'uppercase', 10 );
		$this->_font_blockquote		= apply_filters( 'pirouette_pre_fonts_array', 'font_blockquote', 'Old Standard TT', 'italic', 24, 'normal' );

		$fonts = array();
		$fonts = apply_filters( 'pirouette_fonts_array', $fonts, $this->_font );
		$fonts = apply_filters( 'pirouette_fonts_array', $fonts, $this->_font_h1 );
		$fonts = apply_filters( 'pirouette_fonts_array', $fonts, $this->_font_h2 );
		$fonts = apply_filters( 'pirouette_fonts_array', $fonts, $this->_font_h3 );
		$fonts = apply_filters( 'pirouette_fonts_array', $fonts, $this->_font_h4 );
		$fonts = apply_filters( 'pirouette_fonts_array', $fonts, $this->_font_h5 );
		$fonts = apply_filters( 'pirouette_fonts_array', $fonts, $this->_font_h6 );
		$fonts = apply_filters( 'pirouette_fonts_array', $fonts, $this->_font_menu );
		$fonts = apply_filters( 'pirouette_fonts_array', $fonts, $this->_font_blockquote );

		if( PirouetteHelpers::webfonts_method() ){

			$fonts = PirouetteLoadFonts::web_fonts( $fonts );

		} else{

			$fonts = PirouetteLoadFonts::fonts( $fonts );
		}

		if( PirouetteHelpers::webfonts_method() ){

			wp_localize_script( 'pirouette-scripts', 'pirouette_fonts', $fonts );

		} else {

			wp_enqueue_style( 'pirouette-google-fonts', $fonts, array( 'pirouette-style' ), null, 'all' );

		}

		$font_body   = new PirouetteFont( $this->_font );
		$font_h1     = new PirouetteFont( $this->_font_h1 );
		$font_h2     = new PirouetteFont( $this->_font_h2 );
		$font_h3     = new PirouetteFont( $this->_font_h3 );
		$font_h4     = new PirouetteFont( $this->_font_h4 );
		$font_h5     = new PirouetteFont( $this->_font_h5 );
		$font_h6     = new PirouetteFont( $this->_font_h6 );
		$font_menu   = new PirouetteFont( $this->_font_menu );
		$font_quote  = new PirouetteFont( $this->_font_blockquote );

		$css = "
			body{
				$font_body->_family
				$font_body->_style
				$font_body->_variant
				$font_body->_rem
			}
			h1,
			.h1{
				$font_h1->_family
				$font_h1->_style
				$font_h1->_variant
				font-size: " . ( $font_h1->_size * 0.75 / 16 ) ."rem;
				$font_h1->_spacing
			}
			@media(min-width: 768px){
				h1,
				.h1{
					$font_h1->_rem
				}
			}
			h2,
			.h2{
				$font_h2->_family
				$font_h2->_style
				$font_h2->_variant
				$font_h2->_rem
				$font_h2->_spacing
			}
			h3,
			.h3{
				$font_h3->_family
				$font_h3->_style
				$font_h3->_variant
				$font_h3->_rem
				$font_h3->_spacing
			}
			h4,
			.h4{
				$font_h4->_family
				$font_h4->_style
				$font_h4->_variant
				$font_h4->_rem
				$font_h4->_spacing
			}
			h5,
			.h5{
				$font_h5->_family
				$font_h5->_style
				$font_h5->_variant
				$font_h5->_rem
				$font_h5->_spacing
			}
			h6,
			.h6{
				$font_h6->_family
				$font_h6->_style
				$font_h6->_variant
				$font_h6->_rem
			}
			blockquote,
			blockquote p,
			.pullquote{
				$font_quote->_family
				$font_quote->_style
				$font_quote->_variant
				$font_quote->_rem
			}
			blockquote cite{
				$font_body->_family
			}
			.ct-main-navigation{
				$font_menu->_family
				$font_menu->_style
				$font_menu->_variant
				$font_menu->_rem
				$font_menu->_spacing
			}
			h1 small, h2 small, h3 small{
				$font_body->_family
				$font_body->_rem
			}
		";


		$css .= "
			.sub-menu a,
			.children a{
				$font_body->_family
				$font_body->_style
			}
			#footer .widget-title{
				$font_menu->_family
				$font_menu->_style
				$font_menu->_variant
			}
		";

		$css .= "
			table thead th{
				$font_h5->_family
				$font_h5->_style
				$font_h5->_variant
			}

			.btn,
			.wcs-more.wcs-btn--action{
				$font_menu->_family
				$font_menu->_style
				$font_menu->_variant
				".( $font_menu->_variant === 'text-transform: uppercase;' ? 'letter-spacing: 1px;' : '' )."
			}

			.ct-header__main-heading small,
			.special-title em:first-child,
			.wcs-timetable--carousel .wcs-class__timestamp .date-day{
				$font_quote->_family
				$font_quote->_style
			}
		";

		/** Components */
		$css .= "
			.ct-vc-text-separator{
				$font_h1->_family
				$font_h1->_style
			}
			.wcs-timetable--week .wcs-class__title,
			.wcs-timetable--agenda .wcs-class__title{
				$font_h3->_family
			}
			.xtd-gmap-info{
				$font_body->_family
				$font_body->_style
				$font_body->_variant
				$font_body->_rem
			}
		";

		wp_add_inline_style( 'pirouette-style', apply_filters( 'pirouette_minify_css', htmlspecialchars_decode( $css ) ) );

	}

}

$pirouette_renders_typography = new PirouetteTypographyCSS();

?>
