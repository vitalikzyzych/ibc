<?php

new CurlyWeeklyClassVC();

/**
* Weekly Classe
*/
class CurlyWeeklyClassVC {

	public $_classes;

	public function __construct(){

		/** Load Assets */
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_assets' ) );

		add_action( 'vc_before_init', array( $this, 'timetable_vc' ) );
		add_shortcode( 'vc-wcs-schedule', array( $this, 'schedule' ) );



	}

	function schedule( $atts = null ){

		extract( shortcode_atts( array(
	    	'id' => null
		), $atts, 'vc-wcs-timetable' ) );

		return do_shortcode("[wcs-schedule id='$id']");

	}



	/** Admin Assets */
	function admin_assets(){

		wp_enqueue_style(
			'wcs-admin-vc',
			plugins_url() . '/weekly-class/assets/admin/css/vc.css',
			null,
			false,
			'all'
		);

	}

	/** Visual Composer Timetable */
    public function timetable_vc() {

	    $count = intval( get_option( '__wcs_schedule_count', 0 ) );
	    $schedules = array();

			$views = apply_filters( 'wcs_views', array() );

	    if( intval( $count ) !== 0 ){
		    while( $count > 0 ) :

					$data = get_option( "__wcs_schedule_$count" );
					$data = maybe_unserialize( $data );

					if( $data !== false && ! empty( $data ) && isset( $data['title'] ) && isset( $data['view'] ) ) :
		    		$schedules[" {$count} - {$data['title']} ({$views[$data['view']]['title']})"] = $count;
		    	endif;

					$count--;

				endwhile;

	    } else {
		    $schedules = array( __( 'No Schedules Created', 'WeeklyClass' ) );
	    }

		vc_map(
			array(
	    	    "name" => __("Weekly Class Schedule", "WeeklyClass"),
	    	    "base" => "vc-wcs-schedule",
	    	    "content_element" => true,
	    	    "class" => '',
	    	    "params" => array(

	    	        array(
	    	            "type" => "dropdown",
	    	            "heading" => __("Choose Schedule", "WeeklyClass"),
	    	            "param_name" => "id",
	    	            'value' => $schedules
	    	        )
		    	)
			)
		);
	}

}


?>
