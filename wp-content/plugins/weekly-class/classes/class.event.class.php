<?php

class CurlyWeeklyClassEvent {

	private $_id;
	private $_title;
	private $_timestamp;
	private $_repeat;
	private $_duration;
	private $_cancel;
	private $_end;
	private $_status;
	private $_post_meta;
	private $_out;
	private $_defaults;
	private $_terms;
	private $_excerpt;
	private $_post;
	private $_thumnbail;
	private $_ending;
	private $_multiday;

	public function __construct( $post = false, $start = false, $end = false ){

		$this->_post = $post;
		$this->_id = $post !== false ? $post->ID : get_the_id();
		$this->_status = 0;
		$this->_post_meta = get_post_meta( $this->_id );
		$this->_out = array();
		$this->_timestamp = isset( $this->_post_meta['_wcs_timestamp'][0] ) ? intval( $this->_post_meta['_wcs_timestamp'][0] ) : false;
		$this->_duration 	= isset( $this->_post_meta['_wcs_duration'][0] ) ? intval( $this->_post_meta['_wcs_duration'][0] ) : 0;
		$this->_status 		= isset( $this->_post_meta['_wcs_status'][0] ) ? intval( $this->_post_meta['_wcs_status'][0] ) : 0;

		$this->_repeat 		= isset( $this->_post_meta['_wcs_interval'][0] ) && intval( $this->_post_meta['_wcs_interval'][0] ) >= 1 ? true : false;
		$this->_ending 		= isset( $this->_post_meta['_wcs_ending'][0] ) ? $this->_post_meta['_wcs_ending'][0] : false;
		$this->_multiday 	= isset( $this->_post_meta['_wcs_multiday'][0] ) ? wp_validate_boolean( $this->_post_meta['_wcs_multiday'][0] ) : false;

		if( wp_validate_boolean( $this->_multiday ) && $this->_ending !== false ){
			$this->_duration = ( $this->_ending - $this->_timestamp ) / 60;
		}

		$this->_end 			= $this->_timestamp + $this->_duration * MINUTE_IN_SECONDS;

		if( $start !== false && $end !== false ){

			$master_count = round( abs( ( $start - $end ) / DAY_IN_SECONDS ) ) <= 31 ? 10000 : 0;

			/** Repeatable Event */
			if( $this->_repeat && ! wp_validate_boolean( $this->_multiday ) ){

				$repeat_interval = intval( $this->_post_meta['_wcs_interval'][0] );

				$last_repeat = isset( $this->_post_meta['_wcs_repeat_until'][0] ) && ! empty( $this->_post_meta['_wcs_repeat_until'][0] ) ? strtotime( $this->_post_meta['_wcs_repeat_until'][0] ) + DAY_IN_SECONDS : $end;
				$end = $last_repeat >= $end ? $end : $last_repeat;

				$last = isset( $this->_post_meta['_wcs_timestamp_last'][0] ) ? maybe_unserialize( $this->_post_meta['_wcs_timestamp_last'][0] ) : false;

				if( $last !== false ){
					foreach( $last as $key => $ts ){
						if( $ts <= $end && $ts >= $start ){
							$this->_out[] = $ts;
						}
					}
				}

				/** Weekly Repeat */
				if( $repeat_interval === 1 ){
					$count = $master_count !== 0 ? $master_count : 5;
					while( $this->_timestamp <= $end && $count > 0 ){
						if( $this->_timestamp >= $start ){
							$this->_out[] = $this->_timestamp;
						}
						$this->_timestamp += WEEK_IN_SECONDS;
						$count--;
					}

				}

				/** Repeat Every Two Weeks */
				if( $repeat_interval === 3 ){
					$count = $master_count !== 0 ? $master_count : ceil( ( $start - $end ) / DAY_IN_SECONDS ) > 31 ? 4 : 1000;
					while( $this->_timestamp <= $end && $count > 0 ){
						if( $this->_timestamp >= $start ){
							$this->_out[] = $this->_timestamp;
						}
						$this->_timestamp += 2 * WEEK_IN_SECONDS;
						$count--;
					}

				}

				/** Repeat Daily */
				if( $repeat_interval === 2){
					$count = $master_count !== 0 ? $master_count : 35;
					$allowed = isset( $this->_post_meta['_wcs_repeat_days'][0] ) ? maybe_unserialize($this->_post_meta['_wcs_repeat_days'][0]) : array( 0,1,2,3,4,5,6 );

					while( $this->_timestamp <= $end  && $count > 0 ){
						if( $this->_timestamp >= $start ){
							if( in_array( date('N', $this->_timestamp) == 7 ? 0 : date('N', $this->_timestamp), $allowed ) ){
								$this->_out[] = $this->_timestamp;
							}
						}
						$this->_timestamp += DAY_IN_SECONDS;
						$count--;
					}

				}

				/** Monthly Repeat */
				if( $repeat_interval === 4 ){
					$count = $master_count !== 0 ? $master_count : 3;
					while( $this->_timestamp <= $end  && $count > 0 ){
						if( $this->_timestamp >= $start ){
							$this->_out[] = $this->_timestamp;
						}
						$this->_timestamp = strtotime( '+1 months', $this->_timestamp );
						$count--;
					}

				}

				/** Yearly Repeat */
				if( $repeat_interval === 5 ){
					$count = $master_count !== 0 ? $master_count : 2;
					while( $this->_timestamp <= $end && $count > 0 ){
						if( $this->_timestamp >= $start ){
							$this->_out[] = $this->_timestamp;
						}
						$this->_timestamp = strtotime( '+1 years', $this->_timestamp );
						$count--;
					}

				}


			}

			/** Non Repetitive Event or Multi Day Event */
			else {

				/** Check for Multi Day */
				if( wp_validate_boolean( $this->_multiday ) && $this->_ending !== false ){

					if( $this->_timestamp <= $end ){

						if( $this->_timestamp >= $start ){
							$this->_out[] = $this->_timestamp;
						}

						elseif( $this->_timestamp >= $start - $this->_duration * MINUTE_IN_SECONDS ){
							$this->_out[] = $this->_timestamp;
						}

					}

				}

				else {

					if( $this->_timestamp >= $start && $this->_timestamp <= $end ){
						$this->_out[] = $this->_timestamp;
					}

				}

			}

		}

		/** Single Event */
		else{
			$this->_out[] = $this->_timestamp;
		}

	}

	function get_terms(){

		$taxonomies = WeeklyClass::get_object_taxonomies( 'class', 'objects' );

		$data = array();

		foreach( $taxonomies as $tax => $taxonomy ){

			$terms = get_the_terms( $this->_id, $tax );
			if( is_array( $terms ) ) {
				foreach( $terms as $key => $term ){
					$data_array = array(
						'slug' => $term->slug,
						'id' => $term->term_id,
						'url' =>  ! empty( $term->description ) && filter_var( $term->description, FILTER_VALIDATE_URL ) ? esc_url_raw( $term->description ) : null,
						'desc' => ! empty( $term->description ) && ! filter_var( $term->description, FILTER_VALIDATE_URL ) ? true : false,
						'name' => $term->name
					);
					if( isset( $data[ str_replace('-','_',$tax) ] ) ){
						$data[str_replace('-','_',$tax)][] = $data_array;
					}
					else{
						$data[str_replace('-','_',$tax)] = array( $data_array );
					}
				}
			}

		}
		return $data;
	}

	function get_duration(){
		$hours = floor( $this->_duration / 60 );
		$minutes = $this->_duration - floor( $this->_duration / 60 ) * 60;
		$duration  = $hours > 0 ? sprintf( _n( '%sh', '%sh', $hours, 'WeeklyClass' ), $hours ) : '';
		$duration .= $minutes > 0 && $hours > 0 ? ' ' : '';
		$duration .= $minutes > 0 ? sprintf( __( '%d\'', 'WeeklyClass' ), $minutes ) : '';
		return $duration;
	}

	function get_thumbnail(){
		if( empty( $this->_thumbnail ) ){
			$image = isset( $this->_post_meta['_wcs_image_id'] ) ? wp_get_attachment_image_src( esc_attr( $this->_post_meta['_wcs_image_id'][0] ), 'large' ) : false;
			$image_path = isset( $this->_post_meta['_wcs_image'] ) ? esc_url( $this->_post_meta['_wcs_image'][0] ) : false;

			if( $image === false && $image_path !== false ){
				$image = $image_path;
			}
			$this->_thumbnail = $image;
		}
		return $this->_thumbnail;
	}

	function get_thumbnail_image(){
		$image = $this->get_thumbnail();
		return is_array( $image ) ? $image[0] : $image;
	}

	function get_thumbnail_size(){
		$image = $this->get_thumbnail();
		return is_array( $image ) ? array( $image[1], $image[2] ) : false;
	}

	public function get_terms_list( $tax ){
		$term_list = '';
		if( isset( $this->_terms[str_replace('-','_', $tax)] ) && is_array( $this->_terms[str_replace('-','_', $tax)] ) ) :
			foreach( $this->_terms[str_replace('-','_', $tax)] as $key => $term ){
				$term_list = isset( $term['name'] ) && ! empty( $term['name'] ) ? $term_list . $term['name'] . ', ' : $term_list;
			}
			$term_list = substr(trim( $term_list ), 0 , -1);
		endif;
		return $term_list;
	}

	public function prepare_buttons( $timestamp, $timestamp_ending ){

		$buttons = array();
		$id = $this->_id;
		$title = $this->_title;

		$btn_label		= isset( $this->_post_meta['_wcs_action_label'] ) ? esc_attr( $this->_post_meta['_wcs_action_label'][0] ) : '';
		$gmt_offset 	= get_option( 'gmt_offset' ) * HOUR_IN_SECONDS;

		/** Main Button */
		if( ! empty( $btn_label ) && $timestamp_ending >= current_time('timestamp') ){
			$buttons['main'] = array(
				'custom_url' 	=> isset( $this->_post_meta['_wcs_action_custom'] ) ? html_entity_decode( esc_url_raw( $this->_post_meta['_wcs_action_custom'][0] ) ) : false,
				'permalink'  	=> isset( $this->_post_meta['_wcs_action_page'] ) ? get_permalink( esc_attr( $this->_post_meta['_wcs_action_page'][0] ) ) : false,
				'label'				=> $btn_label,
				'email'				=> isset( $this->_post_meta['_wcs_action_email'] ) ? esc_url( add_query_arg(
	        array(
	          'subject' => apply_filters( 'wcs_actions_email_default_subject', $title . ' - ' . date_i18n( get_option('date_format') . ' @ ' . get_option('time_format'), $timestamp ), $title, $timestamp )
	        ),
	        'mailto:' . sanitize_email( esc_attr( $this->_post_meta['_wcs_action_email'][0] ) )
	      ) ) : false,
				'method'			=> isset( $this->_post_meta['_wcs_action_call'] ) ? intval( esc_attr( $this->_post_meta['_wcs_action_call'][0] ) ) : false,
				'target'			=> isset( $this->_post_meta['_wcs_action_target'] ) ? filter_var( esc_attr( $this->_post_meta['_wcs_action_target'][0] ), FILTER_VALIDATE_BOOLEAN ) : false,
				'ical'				=> add_query_arg( array(
				    'start' 	=> $timestamp + $gmt_offset * -1,
				    'end' 		=> $timestamp_ending + $gmt_offset * -1,
				    'subject' => urlencode( $title ),
				    'desc'		=> urlencode( strip_tags( $this->_excerpt ) ),
				    'url'			=> urlencode( site_url('/') ),
				    'location'  => urlencode( get_bloginfo( 'name' ) . ' @ ' . $this->get_terms_list( 'wcs-room' ) ),
				    'name'		=> urlencode( sanitize_file_name( "$title $timestamp" ) )
				), site_url( '/?feed=wcs_ical' ) )
			);
		}


		/** Woo Button */
		if( WCS_WOO && $this->exists( $timestamp ) && $timestamp_ending >= current_time('timestamp') ){

			$woo_booking_capacity = isset( $this->_post_meta[ WCS_PREFIX . '_woo_capacity' ] ) ? $this->_post_meta[ WCS_PREFIX . '_woo_capacity' ][0] : '';
			$woo_booking_label 		= isset( $this->_post_meta[ WCS_PREFIX . '_woo_label' ] ) ? $this->_post_meta[ WCS_PREFIX . '_woo_label' ][0] : '';
			$woo_booking_product 	= isset( $this->_post_meta[ WCS_PREFIX . '_woo_product' ] ) ? $this->_post_meta[ WCS_PREFIX . '_woo_product' ][0] : '';
			$woo_booking_history 	= isset( $this->_post_meta[ '_' . WCS_PREFIX . '_woo_history' ] ) ? $this->_post_meta[ '_' . WCS_PREFIX . '_woo_history' ][0] : '';

			if( ! empty( $woo_booking_label ) && ! empty( $woo_booking_capacity ) && ! empty( $woo_booking_product ) && intval( $woo_booking_capacity ) > 0 ){

				$product = wc_get_product( $woo_booking_product );

				if( is_object( $product ) && $product->is_purchasable() ){
					$total = 0;

					/** Check for previous orders */
					if( ! empty( $woo_booking_history ) ){
						$history = maybe_unserialize( $woo_booking_history );
						if( isset( $history[$timestamp] ) ){
							$orders = $history[$timestamp];
							foreach( $orders as $ord ){

								$order =  wc_get_order( $ord );
                if( ! is_wp_error( $order ) && method_exists( $order, 'get_items' ) && ! empty( $order ) && ! is_null( $order ) && $order !== false ) :

								if( ! in_array( $order->get_status(), array( 'cancelled', 'refunded', 'rejected', 'failed' ) ) ){
									$items = $order->get_items();
									foreach( $items as $item ){
										if( intval( $item->get_product_id() ) === intval( $woo_booking_product ) && $item->meta_exists('_wcs_event') && intval( $item->get_meta('_wcs_event', true ) ) === intval( $id ) && $item->meta_exists('_wcs_timestamp') && intval( $item->get_meta('_wcs_timestamp', true ) ) === intval( $timestamp ) ){
											$total += $item['qty'];
										}
									}
								}
								endif;
							}
						}
					}

					/** Check for no capacity */
					if( ( intval( $woo_booking_capacity ) - $total ) <= 0 ){
						$woo_booking_label_sold = isset( $this->_post_meta[ WCS_PREFIX . '_woo_label_sold'] ) ? $this->_post_meta[ WCS_PREFIX . '_woo_label_sold'][0] : '';
						if( ! empty( $woo_booking_label_sold ) ){
							$woo_booking_label_sold_link = isset($this->_post_meta[ WCS_PREFIX . '_woo_label_sold_link']) ? $this->_post_meta[ WCS_PREFIX . '_woo_label_sold_link'][0] : '';
							if( ! empty( $woo_booking_label_sold_link ) ){
								$buttons['woo'] = array(
									'href' => $woo_booking_label_sold_link,
									'classes' => 'wcs-btn wcs-btn--sold-out wcs-btn--action',
									'label'	=> $woo_booking_label_sold,
									'status' => true
								);
							} else {
								$buttons['woo'] = array(
									'classes' => 'wcs-btn wcs-btn--disabled wcs-btn--sold-out',
									'label'	=> $woo_booking_label_sold,
									'status' => false
								);
							}
						}

					}
					/** If capacity is allowed */
					else {

						global $woocommerce;

						$direction = esc_attr( WC_Admin_Settings::get_option( 'wc_settings_tab_wcs_redirect', 0 ) );

						$buttons['woo'] = array(
							'href' => add_query_arg( array(
								'add-to-cart' => $woo_booking_product,
								'event' => $id,
								'timestamp' => $timestamp
							), intval( $direction ) === 0 ? wc_get_cart_url() : wc_get_checkout_url() ),
							'classes' => 'wcs-btn wcs-btn--action',
							'label'	=> $woo_booking_label,
							'status' => true
						);
					}

				}

			}

		}

		return $buttons;

	}

	public function exists( $timestamp ){

		if( $this->_status === 1 )
			return false;

		if( $this->_status === 2 ){

			$canceled = isset( $this->_post_meta['_wcs_canceled'] ) ? maybe_unserialize( $this->_post_meta['_wcs_canceled'][0] ) : array();
			$canceled = isset( $this->_post_meta['_wcs_canceled'] ) ? unserialize( $this->_post_meta['_wcs_canceled'][0] )  : array();
			if( ! empty( $canceled ) && is_array( $canceled ) ) : foreach( $canceled as $not ){

				if( $timestamp >= strtotime( $not ) && $timestamp < strtotime( $not ) + DAY_IN_SECONDS ){
					return false;
				}

			}

		endif;

		}

		return true;

	}

	public function render(){
		$out = array();
		$meta_options = array();
		$nos = array( '_wcs_image', '_wcs_woo_product', '_wcs_woo_label_sold_link', '_wcs_woo_label_sold', '_wcs_woo_label', '_wcs_woo_capacity', '_wcs_map_zoom', '_wcs_map_theme', '_wcs_map_type', '_wcs_longitude', '_wcs_latitude', '_wcs_action_target', '_wcs_action_email', '_wcs_action_custom', '_wcs_action_page', '_wcs_action_call', '_wcs_action_label', '_wcs_duration', '_wcs_image_id', '_wcs_interval', '_wcs_repeat_until', '_wcs_repeat_days', '_wcs_status', '_wcs_timestamp', '_wcs_timestamp_last', '_wcs_drafts', '_wcs_multiday',  '_wcs_ending' );

		$this->_out = apply_filters( 'wcs_event_render_timestamps', array_unique( array_unique( $this->_out ) ) );

		if( count( $this->_out ) ){

			$this->_title 		= $this->_post->post_title;
			$this->_terms 		= $this->get_terms();
			$this->_excerpt		= ! empty( $this->_post->post_excerpt ) ? wp_kses_post( $this->_post->post_excerpt ) : apply_filters( 'wcs_excerpt', $this->_post->post_content );
			$this->_excerpt 	= apply_filters( 'the_excerpt', $this->_excerpt);

			foreach( $this->_post_meta as $key => $meta ){
				if( isset( $meta[0] ) && strpos( $key, '_wcs' ) === 0 && ! in_array( $key, $nos ) ){
					$meta_options[$key] = $meta[0];
				}
			}

			foreach( $this->_out as $ts ){
					array_push( $out, array(
						'title' => $this->_title,
						'id'		=> $this->_id,
						'thumbnail' => $this->get_thumbnail_image(),
						'thumbnail_size' => $this->get_thumbnail_size(),
						'multiday' => $this->_multiday,
						'ending' => $this->_ending,
						'duration'	=> $this->get_duration(),
						'terms'			=> $this->_terms,
						'period'		=> $this->_duration,
						'excerpt'		=> $this->_excerpt,
						'hash'	=> hash( 'md5', $this->_id . date_i18n( 'c', $ts ) ),
						'visible' => $this->exists( $ts ),
						'timestamp' => $ts,
						'last' => isset( $this->_post_meta['_wcs_repeat_until'][0] ) && ! empty( $this->_post_meta['_wcs_repeat_until'][0] ) ? strtotime( $this->_post_meta['_wcs_repeat_until'][0] ) + DAY_IN_SECONDS : false,
						'start' => date( apply_filters( 'wcs_default_c_time_format', 'c' ), $ts ),
						'end' 	=> date( apply_filters( 'wcs_default_c_time_format', 'c' ), $ts + $this->_duration * MINUTE_IN_SECONDS ),
						'future' => $ts  >= current_time('timestamp') ? true : false,
						'finished' =>  $ts + $this->_duration * MINUTE_IN_SECONDS  < current_time('timestamp') ? true : false,
						'permalink' => add_query_arg( 'wcs_timestamp', $ts, get_permalink( $this->_id ) ),
						'buttons' => $this->prepare_buttons( $ts, $ts + $this->_duration * MINUTE_IN_SECONDS ),
						'meta' => apply_filters( 'wcs_event_meta', $meta_options, $this->_id, $ts )
				 ) );
			}

		}

		return $out;
	}

}

add_filter( 'wcs_default_c_time_format', 'wcs_default_timeformat_corect' );

function wcs_default_timeformat_corect( $format ){
	return 'Y-m-d\TH:i:s+00:00';
}

?>
