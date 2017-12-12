<?php

class PirouetteVC {

	function __construct(){

		/** We safely integrate with VC with this hook */
		add_action( 'init', array( 'PirouetteVC', 'integrateWithVC' ) );

		/** Set as Theme */
		add_action( 'vc_before_init', array( 'PirouetteVC', 'set_as_theme' ) );

	}

	/** VC Integration */
	public static function integrateWithVC() {

        if ( ! defined( 'WPB_VC_VERSION' ) ) {
            return;

        } else {

        }
    }

	/** Set as Theme */
	public static function set_as_theme() {
		vc_set_as_theme( true );
	}

}

/** Initialize the Class */
$pirouette_vc_extend = new PirouetteVC();

?>
