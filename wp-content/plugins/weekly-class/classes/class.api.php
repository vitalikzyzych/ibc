<?php

class WCS_API_CORE {


	public function __construct(){

		add_action( 'plugins_loaded', array( __CLASS__, 'plugins_loaded' ) );


	}

	static function plugins_loaded() {

		global $pagenow;

		if ( $pagenow === 'edit.php' && isset( $_REQUEST['page'] ) && $_REQUEST['page'] === 'wcs_schedule' && isset( $_GET['download'] ) && $_GET['download'] === 'json' ) {

			$count = intval( get_option( '__wcs_schedule_count', 0 ) );

			$data = array();

			if( intval( $count ) > 0 ) {

				while( $count > 0 ) {

					$schedule = get_option( "__wcs_schedule_$count" );

					if( ! empty( $schedule ) ) {

						$data[$count] = maybe_unserialize( $schedule );
					}

					$count--;

				}

			}

			include( plugin_dir_path( __FILE__ ) . '../api/api.export.php' );

			exit();

		} elseif( $pagenow === 'edit.php' && isset( $_REQUEST['page'] ) && $_REQUEST['page'] === 'wcs_schedule' && isset( $_GET['upload'] ) && $_GET['upload'] === 'json' && isset( $_FILES['json_file'] ) ){

			$contents = json_decode( file_get_contents( $_FILES['json_file']['tmp_name'] )  );
			$count = intval( get_option( '__wcs_schedule_count', 0 ) );

			foreach( $contents as $key => $data ){

				$data = (array)$data;

				$id  = isset( $data['id'] ) ? $data['id'] : false;

				if( ! $id ){

					$sch = get_option( "__wcs_schedule_{$key}" );

					if( $sch !== false ){

						$id = $count + 1;

					} else {

						$id = $key;

					}

				} else {

					$sch = get_option( "__wcs_schedule_{$id}" );

					if( $sch !== false ){

						$id = $count + 1;

					}

				}

				update_option( "__wcs_schedule_$id", maybe_serialize( $data ) );
				update_option( '__wcs_schedule_count', $id );

			}

		}

		if( WCS_WOO ){
			include( plugin_dir_path( __FILE__ ) . '../api/api.woo.php' );
		}

    }

}

new WCS_API_CORE();

?>
