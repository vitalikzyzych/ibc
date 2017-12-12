<?php

class PirouetteSizingCSS{

  private $_layout;
	private $_layout_size;
  private $_layout_mode;
	private $_layout_grid;
	private $_header_slider;
	private $_header_height;
	private $_header_margin;

	public function __construct(){

		/** Render Typography */
		add_action( 'wp_enqueue_scripts', array( $this, 'render_sizing' ) );

	}

	/** Render Typography */
	function render_sizing(){

		$this->_nav_margin		= array( esc_attr( get_theme_mod( 'navigation_margin_top', 40 ) ) / 16, esc_attr( get_theme_mod( 'navigation_margin_bot', 60 ) ) / 16 );
    $this->_layout			  = esc_attr( get_theme_mod( 'layout' ) );
		$this->_layout_size 	= esc_attr( get_theme_mod( 'layout_box_width', 1366 ) );
    $this->_layout_mode		= esc_attr( get_theme_mod( 'layout_fixed' ) );
		$this->_layout_grid 	= esc_attr( get_theme_mod( 'layout_grid_width', 1366 ) );
		//$this->_header_slider = esc_attr( get_theme_mod( 'header_slider' ) );
		$this->_header_height = esc_attr( get_theme_mod( 'header_height', 540 ) );
    $header_heading_top   = intval( esc_attr( get_theme_mod( 'header_heading_top', 50 ) ) ) / 16;
    $header_heading_bot   = intval( esc_attr( get_theme_mod( 'header_heading_bot', 50 ) ) ) / 16;

		$css = '';
    $min_width = 1366;

    if( filter_var( $this->_layout, FILTER_VALIDATE_BOOLEAN ) ){

      $css .= "
        .ct-layout--boxed .ct-site{
          max-width: {$this->_layout_size}px;
        }
      ";

    }

    if( ! filter_var( $this->_layout_mode, FILTER_VALIDATE_BOOLEAN ) ){

      $css .= "
        .ct-layout--fixed .container-fluid{
          max-width: {$this->_layout_grid}px;
        }
      ";

      $min_width = $this->_layout_grid;

    }

    $min_width = $min_width <= 1366 ? $min_width + 1 : $min_width;

    $css .= "
      @media(min-width: {$min_width}px){
        .bullets-container,
        #ct-header__hero-navigator{
          display: block;
  			}
      }

		";

		$css .= "
			.ct-header{
				padding: {$this->_nav_margin[0]}rem 0 {$this->_nav_margin[1]}rem;
			}
		";

    if( ! PirouetteHelpers::header_slider() && PirouetteHelpers::header_image() ){
      $css .= "
      @media(min-width: 768px){
        .ct-header__hero{
          min-height: " . $this->_header_height / 1.5 ."px;
        }
      }
      @media(min-width: 992px){
        .ct-header__hero{
          min-height: " . $this->_header_height / 1.2 ."px;
        }
      }
      @media(min-width: 1200px){
        .ct-header__hero{
          min-height: {$this->_header_height}px;
        }
      }

  		";
    }

    $css .= "
      .ct-header__main-heading .container-fluid{
        flex-basis: {$this->_layout_grid}px;
        -webkit-flex-basis: {$this->_layout_grid}px;
        -moz-flex-basis: {$this->_layout_grid}px;
      }
      .ct-header__main-heading-title{
        padding-top: {$header_heading_top}rem;
        padding-bottom: {$header_heading_bot}rem;
      }
    ";


		wp_add_inline_style( 'pirouette-style', apply_filters( 'pirouette_minify_css', htmlspecialchars_decode( $css ) ) );

	}

}
$pirouette_renders_sizing = new PirouetteSizingCSS();
?>
