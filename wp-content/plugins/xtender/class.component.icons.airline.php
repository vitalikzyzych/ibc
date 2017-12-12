<?php

class XtenderAriline {

	function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'register_assets' ) );
  }

	function register_assets(){

		if( ! defined( 'XTENDER_THEMPREFIX' ) ) return;
		if( XTENDER_THEMPREFIX !== 'private_airline_wp' ) return;

		wp_enqueue_style(
			'xtender-icons-airline',
			XTENDER_URL . 'assets/front/css/xtd_airline_icons.css',
			null,
			filter_var( WP_DEBUG, FILTER_VALIDATE_BOOLEAN ) ? rand() : null,
			'all'
		);

	}

}

$xtender_dental = new XtenderAriline();

?>
