<?php

class WeeklyClass {

	public function __construct(){

		add_filter( 'wcs_minify_css', array( $this, 'minify_css' ), 1 );

		add_filter( 'wcs_dateformat_php_to_moment', array( $this, 'filter_dateformat_php_to_moment' ) );

		add_filter( 'wcs_filter_migration_tdata', array( $this, 'filter_migration_tdata' ) );

		add_action( 'wp_ajax_wcs_get_events_json', array( $this, 'get_events_json' ) );
		add_action( 'wp_ajax_nopriv_wcs_get_events_json', array( $this, 'get_events_json' ) );

		add_action( 'wp_ajax_wcs_get_taxonomy_json', array( $this, 'get_taxonomy_json' ) );
		add_action( 'wp_ajax_nopriv_wcs_get_taxonomy_json', array( $this, 'get_taxonomy_json' ) );

		add_action( 'wp_ajax_wcs_get_class_json', array( $this, 'get_class_json' ) );
		add_action( 'wp_ajax_nopriv_wcs_get_class_json', array( $this, 'get_class_json' ) );

		add_filter( 'wcs_start', array( $this, 'default_start_date' ), 10, 2 );
		add_filter( 'wcs_stop', array( $this, 'default_stop_date' ), 10, 2 );

		add_filter( 'wcs_excerpt', array( $this, 'excerpt' ) );

		add_filter( 'wcs_all_days', array( $this, 'all_days' ) );

	}

	public static function all_days( $days ){
		$settings = wcs_get_settings();
		$days = intval( $settings['wcs_all_days'] ) * 365;
		return $days;
	}

	function excerpt( $text ){
		$limit = apply_filters( 'wcs_excerpt_limit', 55 );
		$text  = wp_kses_post( $text );
		if ( str_word_count( $text, 0) > $limit ) {
      $words = str_word_count($text, 2);
      $pos = array_keys($words);
      $text = substr( $text, 0, $pos[$limit] ) . apply_filters( 'wcs_excerpt_suffix', '...' );
    }
    return $text;
	}

	function default_start_date( $date, $atts ){
		if( isset( $atts['show_past_events'] ) && filter_var( $atts['show_past_events'], FILTER_VALIDATE_BOOLEAN ) ){
			if( isset( $atts['starting_date'] ) && ! empty( $atts['starting_date'] ) ){
				$date = strtotime( $atts['starting_date'] );
			}
		}
		return $date;
	}
	function default_stop_date( $date, $atts ){

		if( isset( $atts['show_past_events'] ) && wp_validate_boolean( $atts['show_past_events'] ) ){
			if( isset( $atts['starting_date'] ) && ! empty( $atts['starting_date'] ) && intval( $atts['days'] ) !== 0 ){
				$date = current_time( 'timestamp' );
			}
		}

		return $date;
	}

	function get_taxonomy_json(){

		if( ! isset( $_REQUEST['action'] ) || $_REQUEST['action'] !== 'wcs_get_taxonomy_json' || ! isset( $_REQUEST['id'] ) )
			wp_die();

		$out = array(
			'id' => intval( $_REQUEST['id'] ),
			'content' => get_term( intval( $_REQUEST['id'] ) )->description
		);

		wp_send_json( $out );

	}

	function get_class_json(){

		if( ! isset( $_REQUEST['action'] ) || $_REQUEST['action'] !== 'wcs_get_class_json' || ! isset( $_REQUEST['id'] ) )
			wp_die();

		$post = get_post( intval( $_REQUEST['id'] ) );

		$image = wp_get_attachment_image_src( esc_attr( get_post_meta( intval( $_REQUEST['id'] ), '_wcs_image_id', true )  ), 'large' );
		$image_path = esc_attr( get_post_meta( intval( $_REQUEST['id'] ), '_wcs_image', true ) );

		if( $image === false && $image_path !== false ){
			$image = $image_path;
		} elseif( is_array( $image ) ){
			$image = $image[0];
		}

		$id = intval( $_REQUEST['id'] );

		$out = array(
			'id' => $id,
			'content' => apply_filters( 'the_content', $post->post_content ),
			'image' => $image
		);

		$map = array();
		$lat = get_post_meta( $id, WCS_PREFIX . '_latitude', true );
		$lon = get_post_meta( $id, WCS_PREFIX . '_longitude', true );

		if( ! empty( $lat ) && ! empty( $lon ) ){
			$map['latitude'] = $lat;
			$map['longitude'] = $lon;

			$type 	= get_post_meta( $id, WCS_PREFIX . '_map_type', true );
			$theme 	= get_post_meta( $id, WCS_PREFIX . '_map_theme', true );
			$zoom 	= get_post_meta( $id, WCS_PREFIX . '_map_zoom', true );

			$map['type'] 	= ! empty( $type ) ? $type : 'roadmap';
			$map['theme'] = ! empty( $theme ) ? $theme : 'default';
			$map['zoom']	= ! empty( $zoom ) ? $zoom : 15;
		}

		if( ! empty( $map ) ){
			$out['map'] = $map;
		}

		wp_send_json( $out );

	}


	public static function get_event_json( $id ){

		$post = get_post( $id );
		setup_postdata( $post );
		$feed = new CurlyWeeklyClassEvent( $post );
		$feed = $feed->render();
		wp_reset_postdata();

		return isset( $feed[0] ) ? $feed[0] : array();

	}


	public static function get_events_json( $return = false, $args = array() ){

		if( ! $return ){

			if( ! isset( $_REQUEST['action'] ) || $_REQUEST['action'] !== 'wcs_get_events_json' || ! isset( $_REQUEST['start'] ) || empty( $_REQUEST['start'] ) || ! isset( $_REQUEST['end'] ) || empty( $_REQUEST['end'] ) )
				wp_die();

			$start = strtotime( esc_attr( $_REQUEST['start'] ) );
			$end 	 = strtotime( esc_attr( $_REQUEST['end'] ) . ' 23:59:59' );
			$filters = isset( $_REQUEST['content'] ) ? $_REQUEST['content'] : array();

		} else {

			$start = strtotime( $args['start'] );
			$end 	 = strtotime( $args['end'] . ' 23:59:59' );
			$filters = $args['content'];

		}

		$query_tax = array( 'relation' => 'AND' );

		foreach( $filters as $key => $filter ){
			if( ! empty( $filter ) ){
				array_push(
					$query_tax,
					array(
						'taxonomy' => str_replace( '_', '-', $key ),
						'field'    => 'id',
						'terms'    => $filter,
					)
				);
			}
		}

		$query_tax = count( $query_tax ) === 1 ? array() : $query_tax;

		$hash = hash( 'crc32', maybe_serialize( $query_tax ) );

		$classes = false; //is_user_logged_in() ? false : get_transient( 'wcs_query_' . $hash );

		if( ! $classes ){

			$classes = new WP_Query(
				array(
					'post_status' => array( 'publish' ),
					'posts_per_page' => -1,
					'post_type' => 'class',
					'meta_key'  => '_wcs_timestamp',
					'orderby'   => 'meta_value_num',
					'order'     => 'ASC',
					'tax_query' => $query_tax,
				)
			);

			if( ! is_user_logged_in() ){
				set_transient( 'wcs_query_' . $hash, $classes, 5 * MINUTE_IN_SECONDS );
			}

		}

		$classes_array = false; //is_user_logged_in() ? false : get_transient( 'wcs_events_' . hash( 'md5', $hash . $start . $end ) ) ;

		if( ! $classes_array ){

			$classes_array = array();

			if ( $classes->have_posts() ) : while ( $classes->have_posts() ) : $classes->the_post();
				$class = new CurlyWeeklyClassEvent( $classes->post, $start, $end );
				$classes_array = array_merge( $classes_array, $class->render() );

			endwhile; endif;

			wp_reset_query();

			usort( $classes_array, function( $a, $b ){
				return $a['timestamp']>$b['timestamp'];
			});

			if( ! is_user_logged_in() ){
				//set_transient( 'wcs_events_' . hash( 'md5', $hash . $start . $end ), $classes_array, 5 * MINUTE_IN_SECONDS );
			}

		}

		if( ! $return ){
			wp_send_json( $classes_array );
		} else {
			return $classes_array;
		}

	}

	function filter_migration_tdata( $atts ){
		if( ! is_array( $atts ) || isset( $atts['last_edit_date'] ) ) return $atts;

		$atts['days'] = isset( $atts['days'] ) ? intval( $atts['days'] ) : 0;
		if( ! isset( $atts['content'] ) ){
			$atts['content'] = array();
		}
		if( ! isset( $atts['filters'] ) ){
			$atts['filters'] = array();
		}

		$taxonomies = WeeklyClass::get_object_taxonomies( 'class', 'objects' );

		foreach( $taxonomies as $tax => $taxonomy ){
			$tax_name = str_replace('-', '_', $tax);
			if( ! in_array( $tax_name, array( 'wcs_type', 'wcs_room', 'wcs_instructor' ) ) ){
				if( ! isset( $atts['content'][$tax_name] ) ){
					$atts['content'][$tax_name] = isset( $atts[$tax_name] ) && ! empty( $atts[$tax_name] ) ? $atts[$tax_name] : array();
				}
				if( ! isset( $atts['filters'][$tax_name] ) ){
					$atts['filters'][$tax_name] = isset( $atts['filters_'.$tax_name] ) && ! empty( $atts['filters_'.$tax_name] ) ? $atts['filters_'.$tax_name] : array();
				}
			}
		}

		$atts['content']['wcs_type'] = isset( $atts['types_ids'] ) && ! empty( $atts['types_ids'] ) ? explode(',', $atts['types_ids'] ) : array();
		$atts['content']['wcs_room'] = isset( $atts['locations_ids'] ) && ! empty( $atts['locations_ids'] ) ? explode(',', $atts['locations_ids'] ) : array();
		$atts['content']['wcs_instructor'] = isset( $atts['instructors_ids'] ) && ! empty( $atts['instructors_ids'] ) ? explode(',', $atts['instructors_ids'] ) : array();

		$atts['filters']['wcs_type'] = isset( $atts['filters_types'] ) && ! empty( $atts['filters_types'] ) ? explode(',', $atts['filters_types'] ) : array();
		$atts['filters']['wcs_room'] = isset( $atts['filters_locations'] ) && ! empty( $atts['filters_locations'] ) ? explode(',', $atts['filters_locations'] ) : array();
		$atts['filters']['wcs_instructor'] = isset( $atts['filters_instructors'] ) && ! empty( $atts['filters_instructors'] ) ? explode(',', $atts['filters_instructors'] ) : array();

		$atts['label_wcs_type'] = isset( $atts['label_types'] ) ? $atts['label_types'] : '';
		$atts['label_wcs_room'] = isset( $atts['label_locations'] ) ? $atts['label_locations'] : '';
		$atts['label_wcs_instructor'] = isset( $atts['label_instructors'] ) ? $atts['label_instructors'] : '';

		$atts['label_filter_wcs_type'] = isset( $atts['label_filter_type'] ) ? $atts['label_filter_type'] : '';
		$atts['label_filter_wcs_room'] = isset( $atts['label_filter_location'] ) ? $atts['label_filter_location'] : '';
		$atts['label_filter_wcs_instructor'] = isset( $atts['label_filter_instructor'] ) ? $atts['label_filter_instructor'] : '';

		$atts['show_wcs_type'] = isset( $atts['show_types'] ) ? filter_var( $atts['show_types'], FILTER_VALIDATE_BOOLEAN ) : false;
		$atts['show_wcs_room'] = isset( $atts['show_locations'] ) ? filter_var( $atts['show_locations'], FILTER_VALIDATE_BOOLEAN ) : false;
		$atts['show_wcs_instructor'] = isset( $atts['show_instructors'] ) ? filter_var( $atts['show_instructors'], FILTER_VALIDATE_BOOLEAN ) : false;

		$atts['show_filter_wcs_type'] = isset( $atts['show_filter_type'] ) ? filter_var( $atts['show_filter_type'], FILTER_VALIDATE_BOOLEAN ) : false;
		$atts['show_filter_wcs_room'] = isset( $atts['show_filter_locations'] ) ? filter_var( $atts['show_filter_locations'], FILTER_VALIDATE_BOOLEAN ) : false;
		$atts['show_filter_wcs_instructor'] = isset( $atts['show_filter_instructors'] ) ? filter_var( $atts['show_filter_instructors'], FILTER_VALIDATE_BOOLEAN ) : false;
		$atts['show_filter_wcs_time_of_day'] = isset( $atts['show_filter_time'] ) ? filter_var( $atts['show_filter_time'], FILTER_VALIDATE_BOOLEAN ) : false;
		$atts['show_filter_wcs_day_of_week'] = isset( $atts['show_filter_day'] ) ? filter_var( $atts['show_filter_day'], FILTER_VALIDATE_BOOLEAN ) : false;

		$atts['show_modal_wcs_type'] = isset( $atts['show_modal_types'] ) ? filter_var( $atts['show_modal_types'], FILTER_VALIDATE_BOOLEAN ) : false;
		$atts['show_modal_wcs_room'] = isset( $atts['show_modal_locations'] ) ? filter_var( $atts['show_modal_locations'], FILTER_VALIDATE_BOOLEAN ) : false;
		$atts['show_modal_wcs_instructor'] = isset( $atts['show_modal_instructors'] ) ? filter_var( $atts['show_modal_instructors'], FILTER_VALIDATE_BOOLEAN ) : false;

		$atts['label_grid_all_wcs_type'] = isset( $atts['label_grid_all_terms'] ) ? $atts['label_grid_all_terms'] : '';
		$atts['label_grid_all_wcs_room'] = isset( $atts['label_grid_all_locations'] ) ? $atts['label_grid_all_locations'] : '';
		$atts['label_grid_all_wcs_instructor'] = isset( $atts['label_grid_all_instructors'] ) ? $atts['label_grid_all_instructors'] : '';
		$atts['label_grid_all_day_of_week'] = isset( $atts['label_grid_all_days'] ) ? $atts['label_grid_all_days'] : '';
		$atts['label_grid_all_time_of_day'] = isset( $atts['label_grid_all_times'] ) ? $atts['label_grid_all_times'] : '';

		$atts['label_dateformat'] = isset( $atts['label_dateformat'] ) ? apply_filters( 'wcs_dateformat_php_to_moment' , $atts['label_dateformat'] ) : '';
		$atts['label_modal_dateformat'] = isset( $atts['label_modal_dateformat'] ) ? apply_filters( 'wcs_dateformat_php_to_moment' , $atts['label_modal_dateformat'] ) : '';

		return $atts;

	}

	function filter_dateformat_php_to_moment($format){
    $replacements = array(
        'd' => 'DD',
        'D' => 'ddd',
        'j' => 'D',
        'l' => 'dddd',
        'N' => 'E',
        'S' => 'o',
        'w' => 'e',
        'z' => 'DDD',
        'W' => 'W',
        'F' => 'MMMM',
        'm' => 'MM',
        'M' => 'MMM',
        'n' => 'M',
        't' => '', // no equivalent
        'L' => '', // no equivalent
        'o' => 'YYYY',
        'Y' => 'YYYY',
        'y' => 'YY',
        'a' => 'a',
        'A' => 'A',
        'B' => '', // no equivalent
        'g' => 'h',
        'G' => 'H',
        'h' => 'hh',
        'H' => 'HH',
        'i' => 'mm',
        's' => 'ss',
        'u' => 'SSS',
        'e' => 'zz', // deprecated since version 1.6.0 of moment.js
        'I' => '', // no equivalent
        'O' => '', // no equivalent
        'P' => '', // no equivalent
        'T' => '', // no equivalent
        'Z' => '', // no equivalent
        'c' => '', // no equivalent
        'r' => '', // no equivalent
        'U' => 'X',
    );
    $momentFormat = strtr($format, $replacements);
    return $momentFormat;
	}

	public static function get_object_taxonomies(){

		$taxonomies = wp_cache_get( 'wcs_taxonomies', 'WeeklyClass' );
		if( ! $taxonomies ){
			$taxonomies = get_object_taxonomies( 'class', 'objects' );
			wp_cache_set( 'wcs_taxonomies', $taxonomies, 'WeeklyClass' );
		}
		return $taxonomies;

	}



	public static function minify_css( $string ) {
		$dev 	= $string;
		$string = preg_replace('!/\*.*?\*/!s','', $string);
		$string = preg_replace('/\n\s*\n/',"\n", $string);

		// space
		$string = preg_replace('/[\n\r \t]/',' ', $string);
		$string = preg_replace('/ +/',' ', $string);
		$string = preg_replace('/ ?([,:;{}]) ?/','$1',$string);

		// trailing;
		$string = preg_replace('/;}/','}',$string);

		return $string;
	}


	public static function get_filters_json( $atts ){

		$out = array(
			'visible'	=> isset( $atts['show_filters_opened'] ) ? filter_var( $atts['show_filters_opened'], FILTER_VALIDATE_BOOLEAN ): false,
			'options' => array(
				'label_toggle' => isset( $atts['label_toggle'] ) ? $atts['label_toggle'] : ''
			),
			'taxonomies' => array()
		);


		/** Filters Order Count */
		$filters_order = 0;

		$taxonomies = WeeklyClass::get_object_taxonomies( 'class', 'objects' );

		$default_all = array();

		/** Taxonomies */
		foreach( $taxonomies as $tax => $taxonomy ){

			$filters_order += 10;

			$tax_name = str_replace( '-', '_', $tax );

			if( ! isset( $default_all[$tax_name] ) ){
				$default_all[$tax_name] = isset( $taxonomy->wcs_labels['all'] ) ? $taxonomy->wcs_labels['all'] : '' ;
			}

			if( isset( $atts[ 'show_filter_' . $tax_name ] ) && filter_var( $atts['show_filter_' . $tax_name ], FILTER_VALIDATE_BOOLEAN ) ){

				$filters = isset( $atts['filters'][$tax_name] ) && ! empty( $atts['filters'][$tax_name] ) && ! empty( $atts['filters'][$tax_name][0] ) ? $atts['filters'][$tax_name] : array();
				$terms = get_terms( $tax, array( 'hide_empty' => false, 'slug' => $filters, 'orderby' => 'name' ) );
				$terms_array = $terms_children = array();
				foreach( $terms as $term ){
					if( intval( $term->parent ) === 0 ){
						$terms_array[$term->term_id] = array(
							'name' => $term->name,
							'slug' => $term->slug,
							'id'	 => $term->term_id,
							'children' => array()
						);
					} else{
						$terms_children[] = array(
							'name' => $term->name,
							'slug' => $term->slug,
							'id'	 => $term->term_id,
							'parent' => $term->parent,
							'children' => array()
						);
					}
				}
				foreach( $terms_children as $child ){
					if( isset( $terms_array[$child['parent']] ) ){
						$terms_array[$child['parent']]['children'][] = $child;
					} else{
						$terms_array[$child['id']] = $child;
					}
				}
				usort($terms_array, function( $a, $b){
					return strcmp( $a["name"], $b["name"] );
				});
				$out['taxonomies'][$tax_name] = array(
					'name' 	=> $tax_name,
					'title' => isset( $atts[ 'label_filter_' . $tax_name ] ) ? $atts[ 'label_filter_' . $tax_name ] : '',
					'order' => apply_filters( 'wcs_filters_order_display', $filters_order, $atts['id'], $tax_name ),
					'label_all' => $default_all[$tax_name],
					'terms'	=> $terms_array
				);

			}


		}

		/** Day of the week */
		if( isset( $atts[ 'show_filter_day_of_week' ] ) && filter_var( $atts['show_filter_day_of_week' ], FILTER_VALIDATE_BOOLEAN ) ){

			$out['taxonomies']['day_of_week'] = array(
				'name' => 'day_of_week',
				'title' => isset( $atts[ 'label_filter_day' ] ) ? $atts[ 'label_filter_day' ] : '',
				'order' => apply_filters( 'wcs_filters_order_display', $filters_order + 10, $atts['id'], 'day_of_week' ),
				'label_all' => isset( $atts['label_grid_all_days_of_week'] ) && ! empty( $atts['label_grid_all_days_of_week'] ) ? $atts['label_grid_all_days_of_week'] : __( 'Any', 'WeeklyClass' ),
				'terms' => array()
			);

			$start = intval( get_option( 'start_of_week', 0 ) );

			$days = array(
				0 => date_i18n( 'l', strtotime( '10/05/1986' ) ),
				1 => date_i18n( 'l', strtotime( '10/06/1986' ) ),
				2 => date_i18n( 'l', strtotime( '10/07/1986' ) ),
				3 => date_i18n( 'l', strtotime( '10/08/1986' ) ),
				4 => date_i18n( 'l', strtotime( '10/09/1986' ) ),
				5 => date_i18n( 'l', strtotime( '10/10/1986' ) ),
				6 => date_i18n( 'l', strtotime( '10/11/1986' ) )
			);

			for( $i = $start; $i <= 6 + $start ; $i++ ){
				$buffer = $i;
				if( $i > 6 )
					$buffer = $i - 7;

				$out['taxonomies']['day_of_week']['terms'][] = array(
					'id' => 'day-' . $buffer,
					'name' => $days[$buffer],
					'slug' => $buffer
				);

			}

		}

		/** Time of the day */
		if( isset( $atts[ 'show_filter_time_of_day' ] ) && filter_var( $atts['show_filter_time_of_day' ], FILTER_VALIDATE_BOOLEAN ) ){

			$out['taxonomies']['time_of_day'] = array(
				'name' => 'time_of_day',
				'title' => isset( $atts['label_filter_time'] ) ? $atts['label_filter_time'] : '',
				'order' => apply_filters( 'wcs_filters_order_display', $filters_order + 20, $atts['id'], 'time_of_day' ),
				'label_all' => isset( $atts['label_grid_all_time_of_day'] ) && ! empty( $atts['label_grid_all_time_of_day'] ) ? $atts['label_grid_all_time_of_day'] : __( 'Any', 'WeeklyClass' ),
				'terms' => array()
			);

				foreach( array(
					array( 'value' => 'morning', 'name' => __( 'Morning', 'WeeklyClass' ) ),
					array( 'value' => 'afternoon', 'name' => __( 'Afternoon', 'WeeklyClass' ) ),
					array( 'value' => 'evening', 'name' => __( 'Evening', 'WeeklyClass' ) ),
				) as $key => $time ){

					extract($time);

					$out['taxonomies']['time_of_day']['terms'][] = array(
						'id' => 'time-' . $key,
						'name' => $name,
						'slug' => $value
					);

				}
		}

		/** Sort filters by order */
		$sort_col = array();
    foreach ( $out['taxonomies'] as $key => $row ) {
        $sort_col[$key] = $row['order'];
    }
    array_multisort( $sort_col, SORT_ASC, $out['taxonomies'] );

		return $out;

	}



}

new WeeklyClass();

?>
