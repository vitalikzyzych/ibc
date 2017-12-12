<?php

class PirouetteRendersGeneral{

  private $_header_image;

  function __construct(){

    add_action( 'wp_enqueue_scripts', array( $this, 'render_general' ) );

  }

  function render_general(){

    $this->_header_image = PirouetteHelpers::header_image();

    $css = '';

    if( ! PirouetteHelpers::header_slider() && $this->_header_image ){

      $css .= "
        .ct-header__hero::before{
          background-image: url({$this->_header_image});
        }
      ";

    }

		wp_add_inline_style( 'pirouette-style', apply_filters( 'pirouette_minify_css', htmlspecialchars_decode( $css ) ) );

  }

}

$pirouette_renders_general= new PirouetteRendersGeneral();

?>
