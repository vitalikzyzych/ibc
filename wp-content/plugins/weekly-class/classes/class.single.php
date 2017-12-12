<?php


$wcs_single = new CurlyWeeklySingle();

class CurlyWeeklySingle{

	function __construct(){

		add_filter( 'single_template', array( $this, 'get_custom_post_type_template' ), 1, 1 );
		add_filter( 'the_content', array( $this, 'filter_content' ), 1, 1 );
		add_action( 'wp_enqueue_scripts', array( $this, 'assets' ) );

		add_shortcode( 'wcs_event_title', array( $this, 'event_title' ) );
		add_shortcode( 'wcs_event_image', array( $this, 'event_image' ) );
		add_shortcode( 'wcs_event_date', array( $this, 'event_date' ) );
		add_shortcode( 'wcs_event_terms', array( $this, 'event_terms' ) );
		add_shortcode( 'wcs_event_button', array( $this, 'event_button' ) );
		add_shortcode( 'wcs_event_duration', array( $this, 'event_duration' ) );
		add_shortcode( 'wcs_event_map', array( $this, 'event_map' ) );

		add_filter( 'sanitize_buffer', array( $this, 'sanitize_output' ) );

	}

	function sanitize_output( $buffer ) {

    $search = array(
        '/\>[^\S ]+/s',     // strip whitespaces after tags, except space
        '/[^\S ]+\</s',     // strip whitespaces before tags, except space
        '/(\s)+/s',         // shorten multiple whitespace sequences
        '/<!--(.|\s)*?-->/' // Remove HTML comments
    );

    $replace = array(
        '>',
        '<',
        '\\1',
        ''
    );

    $buffer = preg_replace($search, $replace, $buffer);

    return $buffer;
}

	function assets(){

		$settings = wcs_get_settings();

		$key = $settings['wcs_api_key'];
		$key = ! empty( $key ) ? $key : 'AIzaSyArPwtdP09w4OeKGuRDjZlGkUshNh180z8';
		$maps_url = add_query_arg( array( 'key' => $key ), '//maps.google.com/maps/api/js');

		wp_register_script(
			'wcs-google-map',
			$maps_url,
			array( 'jquery' ),
			null
		);
		wp_register_script(
			'wcs-gmaps',
			plugins_url() . '/weekly-class/assets/libs/gmaps/gmap3.min.js',
			array( 'jquery' ),
			'7.1'
		);

		wp_register_script(
			'wcs-single',
			plugins_url() . '/weekly-class/assets/front/js/min/single-min.js',
			array( 'jquery', 'wcs-google-map', 'wcs-gmaps' ),
			null,
			true
		);

		wp_localize_script( 'wcs-single', 'wcs_maps_url', $maps_url );

		$special_color = $settings['wcs_single_color'];
		$css = ".wcs-single__action .wcs-btn--action{ color: " . CurlyWeeklyClassShortcodes::contrast( $special_color, 1, 0.75 )  . "; background-color: $special_color }";

		wp_add_inline_style( 'wcs-timetable', apply_filters( 'wcs_minify_css', htmlspecialchars_decode( $css ) ) );

	}

	public static function get_event_meta( $key, $id = false ){

		$id = ! $id ? get_the_id() : $id;

		if( empty( $id ) ) return;

		$meta = wp_cache_get( 'wcs_single_event_meta' );

		if( ! $meta ){
			$meta = get_post_meta( $id );
			wp_cache_add( 'wcs_single_event_meta', $meta );
		}

		$meta = isset( $meta[$key][0] ) ? esc_attr( $meta[$key][0] ) : '';

		return $meta;

	}

	public static function get_map_array(){
		$settings = wcs_get_settings();
		$map = '';
		$lat = self::get_event_meta( '_wcs_latitude' );
		$lon = self::get_event_meta( '_wcs_longitude' );

		if( ! empty( $lat ) && ! empty( $lon ) ){

			$map = array();

			$map['latitude'] = $lat;
			$map['longitude'] = $lon;

			$type 	= $settings['wcs_single_map_type'];
			$theme 	= $settings['wcs_single_map_theme'];
			$zoom 	= $settings['wcs_single_map_zoom'];

			$map['type'] 	= ! empty( $type ) ? $type : 'roadmap';
			$map['theme'] = ! empty( $theme ) ? $theme : 'default';
			$map['zoom']	= ! empty( $zoom ) ? $zoom : 15;
		}

		return $map;
	}


	function event_title( $atts ){

		$atts = shortcode_atts( array(
			'id' => false,
		), $atts, 'wcs_event_title' );

		extract( $atts );

		return $id ? get_the_title( $id ) : get_the_title();

	}

	function event_map(){

		$template = wcs_get_template_part( 'single/map' );

		$settings = wcs_get_settings();
		$map = self::get_map_array();

		ob_start();
		if( wp_validate_boolean( $settings['wcs_single_map'] ) && ! empty( $map['longitude'] ) && ! empty( $map['latitude'] ) && $template ) include( $template );
		return apply_filters( 'sanitize_buffer', ob_get_clean() );

	}


	function event_terms( $atts ){

		$atts = shortcode_atts( array(
			'id' => '',
			'term' => '',
		), $atts, 'wcs_event_terms' );

		extract( $atts );

		$rooms_list = '';

		$id = ! empty( $id ) ? $id : get_the_id();

		if( empty( $id ) || empty( $term ) ) return '';

		$locations_temp = get_the_terms( $id, $term );
		$locations = array( null );

		if( is_array( $locations_temp ) ){
			foreach( $locations_temp as $location ){
				$separator = filter_var( preg_match( '/#/', $location->name ), FILTER_VALIDATE_BOOLEAN ) ? '&nbsp;' : ',';
				$rooms_list .= ! empty( $location->description ) && filter_var( $location->description, FILTER_VALIDATE_URL ) ? "<a href='" . esc_url_raw( $location->description ) . "'>{$location->name}</a>$separator " : ( empty( $location->description ) ? "{$location->name}$separator " : "<a href='#' class='wcs-modal-call' data-wcs-id='{$location->term_id}' data-wcs-method='1' data-wcs-type='wcs-room'>{$location->name}</a>$separator "  );
			}
		}
		$rooms_list = substr( trim( $rooms_list ), 0, -1 );

		return $rooms_list;

	}

	function event_duration( $atts ){

		$duration = self::get_duration();

		$hours = floor( $duration / 60 );
		$minutes = $duration - floor( $duration / 60 ) * 60;

		$duration  = $hours > 0 ? sprintf( _n( '%sh', '%sh', $hours, 'WeeklyClass' ), $hours ) : '';
		$duration .= $minutes > 0 && $hours > 0 ? ' ' : '';
		$duration .= $minutes > 0 ? sprintf( __( '%d\'', 'WeeklyClass' ), $minutes ) : '';

		return $duration;

	}

	function event_button( $atts ){

		$template = wcs_get_template_part( 'single/buttons' );

		$atts = shortcode_atts( array(
			'btn' => 'action',
		), $atts, 'wcs_event_title' );

		extract( $atts );

		global $post;

		$timestamp = self::get_timestamp_starting();
		$button = $button_woo = '';

		if( $btn === 'woo' ){
			$button = $this->prepare_button_woo( $post->ID, $timestamp );
		} else {

			$title = esc_attr( $post->post_title );
			$btn_label = self::get_event_meta( '_wcs_action_label' );
			$button = array();

			if( ! empty( $btn_label )  ){
				$gmt_offset 			=  get_option( 'gmt_offset' ) * HOUR_IN_SECONDS;
				$email = self::get_event_meta( '_wcs_action_email' );
				$action_call = self::get_event_meta( '_wcs_action_call' );
				$button = array(
					'custom_url' 	=> esc_url_raw( self::get_event_meta( '_wcs_action_custom' ) ),
					'permalink'  	=> get_permalink( self::get_event_meta( '_wcs_action_page' ) ),
					'label'				=> $btn_label,
					'email'				=> ! empty( $email ) ? esc_url( add_query_arg(
						array(
							'subject' => $title . ' - ' . date_i18n( 'F j, Y', $timestamp + $gmt_offset )
						),
						'mailto:' . sanitize_email( self::get_event_meta('_wcs_action_email' ) )
					) ) : false,
					'method'			=> ! empty( $action_call ) ? intval( self::get_event_meta( '_wcs_action_call' ) ) : false,
					'target'			=> wp_validate_boolean( self::get_event_meta( '_wcs_action_target' ) ),
					'ical'				=> add_query_arg( array(
							'start' 	=> $timestamp + $gmt_offset * -1,
							'end' 		=> $timestamp + self::get_duration() + $gmt_offset * -1,
							'subject' => urlencode( $title ),
							'desc'		=> urlencode( strip_tags( $post->_excerpt ) ),
							'url'			=> urlencode( site_url('/') ),
							'location'  => urlencode( get_bloginfo( 'name' ) ),
							'name'		=> urlencode( sanitize_file_name( "$title $timestamp" ) )
					), site_url( '/?feed=wcs_ical' ) )
				);

			}

			if( ! empty( $button ) ){
				$target = $button['target'] ? '_blank' : '_self';
				$href = array( $button['permalink'], $button['custom_url'], $button['email'], $button['ical'] );
				$button = "<a class='wcs-btn--action wcs-btn' href='{$href[$button['method']]}' target='{$target}'>{$button['label']}</a>";
			} else{
				$button = '';
			}
		}

		ob_start();
		if( ! empty( $button ) && $template ) include( $template );
		return apply_filters( 'sanitize_buffer', ob_get_clean() );

	}

	function event_image( $atts ){

		$template = wcs_get_template_part( 'single/image' );

		$image = wp_get_attachment_image_src( esc_attr( self::get_event_meta( '_wcs_image_id' ) ), 'large' );
		$image_path = esc_attr( self::get_event_meta( '_wcs_image' ) );

		if( $image === false && $image_path !== false ){
			$image = $image_path;
		} elseif( is_array( $image ) ){
			$image = $image[0];
		}

		ob_start();
		if( $image && $template ) include( $template );
		return apply_filters( 'sanitize_buffer', ob_get_clean() );

	}

	public static function get_duration(){

		$timestamp  = self::get_timestamp_starting();
		$duration  	= self::get_event_meta( '_wcs_duration' );
		$ending 		= self::get_event_meta( '_wcs_ending' );
		$multiday 	= wp_validate_boolean( self::get_event_meta( '_wcs_multiday' ) ) ;

		if( $multiday && $ending !== false ){
			$duration = ( $ending - $timestamp ) / 60;
		}

		return $duration;

	}

	public static function get_timestamp_starting(){

		$timestamp = wp_cache_get( 'wcs_event_timestamp' );

		if( ! $timestamp ){
			$timestamp  = isset( $_REQUEST['wcs_timestamp'] ) ? $_REQUEST['wcs_timestamp'] : self::get_event_meta( '_wcs_timestamp' );
			wp_cache_add( 'wcs_event_timestamp', $timestamp );
		}

		return empty( $timestamp ) || ! $timestamp || is_null( $timestamp ) ? '' : intval( $timestamp );
	}

	function event_date(){

		$settings = wcs_get_settings();
		$template = wcs_get_template_part( 'single/date' );

		$default_wp_date_format = get_option( 'date_format' );
		$default_wp_time_format = get_option('time_format');

		$date_format = empty( $settings['wcs_dateformat'] ) ? $default_wp_date_format : $settings['wcs_dateformat'];
		$time_format = $settings['wcs_time_format'];

		$timestamp  = self::get_timestamp_starting();
		$status  		= self::get_event_meta( '_wcs_status');

		$ending 		= self::get_event_meta( '_wcs_ending' );
		$multiday 	= wp_validate_boolean( self::get_event_meta( '_wcs_multiday' ) ) ;
		$multiday		= $multiday && $ending !== false;

		$duration = self::get_duration();
		$timestamp_ending = $timestamp + $duration * 60 ;

		$date = date_i18n( $date_format,  $timestamp );
		$date_ending = date_i18n( $date_format, $timestamp_ending );

		$starting_time = wp_validate_boolean( $time_format ) ? date_i18n( 'g:i a',  $timestamp ) : date_i18n( 'H:i',  $timestamp );
		$ending_time 	 = wp_validate_boolean( $time_format ) ? date_i18n( 'g:i a',  $timestamp_ending ) : date_i18n( 'H:i',  $timestamp_ending );

		$show_ending = wp_validate_boolean( $settings['wcs_single_ending'] );

		ob_start();
		if( $template ) include( $template );
		return apply_filters( 'sanitize_buffer', ob_get_clean() );

	}



	function get_custom_post_type_template( $single_template ) {

	     global $post;

	     if( ! is_singular( 'class' ) )
	     	return $single_template;

	     $template = esc_attr( get_option( 'wcs_single_template', 'page' ) );
	     $template = str_replace( '.php', '', $template );

	     $temp = wcs_get_template_part( $template );

	     if ( $post->post_type === 'class' && ! empty( $temp ) ) {
	        $single_template = $temp;
	     }

	     return $single_template;

	}

	public static function prepare_button_woo( $id, $timestamp, $status = 0 ){

		if( ! WCS_WOO ){
			return;
		}

		if( intval( $status ) !== 0 ){
			return;
		}

		$woo_booking_capacity = get_post_meta( $id, WCS_PREFIX . '_woo_capacity', true );
		$woo_booking_label 		= get_post_meta( $id, WCS_PREFIX . '_woo_label', true );
		$woo_booking_product 	= get_post_meta( $id, WCS_PREFIX . '_woo_product', true );
		$woo_booking_history 	= get_post_meta( $id, '_' . WCS_PREFIX . '_woo_history', true );

		if( empty( $woo_booking_label ) || empty( $woo_booking_capacity ) || empty( $woo_booking_product ) || intval( $woo_booking_capacity ) <= 0 ){
			return;
		}

		$product = wc_get_product( $woo_booking_product );

		if( ! is_object( $product ) ){
			return;
		}

		if( ! $product->is_purchasable() ){
			return;
		}

		$total = 0;

		if( ! empty( $woo_booking_history ) ){

			$history = maybe_unserialize( $woo_booking_history );

			if( isset( $history[$timestamp] ) ){

				$orders = $history[$timestamp];

				foreach( $orders as $ord ){

					$order = wc_get_order( $ord );

					if( ! empty( $order ) && ! is_null( $order ) && $order !== false && ! in_array( $order->get_status(), array( 'cancelled', 'refunded', 'rejected', 'failed' ) ) ){
						$items = $order->get_items();
						foreach( $items as $item ){
							if( intval( $item->get_product_id() ) === intval( $woo_booking_product ) && $item->meta_exists('_wcs_event') && intval( $item->get_meta('_wcs_event', true ) ) === intval( $id ) && $item->meta_exists('_wcs_timestamp') && intval( $item->get_meta('_wcs_timestamp', true ) )  === intval( $timestamp ) ){
								$total += $item['qty'];
							}
						}
					}

				}

			}

		}

		if( ( intval( $woo_booking_capacity ) - $total ) <= 0 ){

			$woo_booking_label_sold = get_post_meta( $id, WCS_PREFIX . '_woo_label_sold', true );

			if( empty( $woo_booking_label_sold ) ){
				return;
			}

			$woo_booking_label_sold_link = get_post_meta( $id, WCS_PREFIX . '_woo_label_sold_link', true );

			if( ! empty( $woo_booking_label_sold_link ) ){

				return "<a href='$woo_booking_label_sold_link' class='wcs-btn wcs-btn--sold-out wcs-btn--action'>$woo_booking_label_sold</a>";

			}

			return "<span class='wcs-btn wcs-btn--disabled wcs-btn--sold-out'>$woo_booking_label_sold</span>";

		} else {

			$direction = esc_attr( WC_Admin_Settings::get_option( 'wc_settings_tab_wcs_redirect', 0 ) );
			$direction = intval( $direction ) === 0 ? 'cart' : 'checkout';

			$url = site_url( "/$direction/?add-to-cart=$woo_booking_product&event=$id&timestamp=$timestamp" );

			$button_woo = "<a href='$url' class='wcs-btn wcs-btn--action'>$woo_booking_label</a>";

			return $button_woo;

		}

	}


	function filter_content( $content ){

		global $post;

		if( ! is_singular( 'class' ) )
			return $content;

		if( ! wp_script_is( 'wcs-single' ) )
			wp_enqueue_script( 'wcs-single' );

		$settings = wcs_get_settings();

		if( $settings['wcs_single_box'] !== 'disabled' ){

			if( ! wp_script_is( 'enqueue', 'wcs-single' ) )
				wp_enqueue_script( 'wcs-single' );

			$template = wcs_get_template_part( 'single/content' );

			ob_start();

			if( $template ) include( $template );

			$content = str_replace( '%%page_content%%', $content, apply_filters( 'sanitize_buffer', ob_get_clean() ) );

		}



		return $content;

	}

}


?>
