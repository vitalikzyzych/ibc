<?php

class WeeklyClassCrons {

  public function __construct(){

    add_action( 'wcs_daily_class_update', array( $this, 'daily_update' ) );

  }

  public static function plugin_activation(){

		$timestamp = new DateTime();
		$timestamp->add(new DateInterval('PT1H'));
		$timestamp->setTime( intval( $timestamp->format('H') ), 0, 0 );

		wp_schedule_event( $timestamp->getTimestamp(), 'hourly', 'wcs_daily_class_update' );

	}


	public static function plugin_deactivation(){
		wp_clear_scheduled_hook( 'wcs_daily_class_update' );
	}

  public static function daily_update(){

		$now 	= current_time( 'timestamp' );

		$classes = new WP_Query(
			array(
				'post_status' => array( 'publish' ),
				'posts_per_page' => -1,
				'post_type' => 'class',
				'meta_key'  => '_wcs_timestamp',
				'orderby'   => 'meta_value_num',
				'order'     => 'ASC',
				'meta_query' => array(
					'relation' => 'AND',
					array(
						'key'     => '_wcs_timestamp',
						'value'   =>  $now,
						'compare' => '<',
					),
					array(
						'key'     => '_wcs_interval',
						'value'   =>  1,
						'compare' => '>=',
					)
				)
			)
		);

		if ( $classes->have_posts() ) : while ( $classes->have_posts() ) : $classes->the_post();

			$id = get_the_id();

			$timestamp  = get_post_meta( $id, '_wcs_timestamp', true );
      $duration   = get_post_meta( $id, '_wcs_duration', true );
      $repeat     = intval( get_post_meta( $id, '_wcs_interval', true ) );

      $multi_day = wp_validate_boolean( get_post_meta( $id, '_wcs_multiday', true ) );

      $end = $timestamp + $duration * MINUTE_IN_SECONDS;

      if( ! wp_validate_boolean( $multi_day ) ){

        if( $end <= $now ){

          $until = get_post_meta( $id, '_wcs_repeat_until', true );

          /** Repeat Weekly */
          if( $repeat === 1 ){

            $factor = ceil( ( $now - $timestamp ) / WEEK_IN_SECONDS );
      			$increase = intval( $timestamp ) + $factor * WEEK_IN_SECONDS;

          }

          /** Repeat Daily */
          elseif( $repeat === 2 ){

            $repeat_days = maybe_unserialize( get_post_meta( $id, '_wcs_repeat_days', true ) );

            if( empty( $repeat_days ) ){

              $until = true;

            } else{

              $factor = ceil( ( $now - $timestamp ) / DAY_IN_SECONDS );
        			$increase = intval( $timestamp ) + $factor * DAY_IN_SECONDS;

              while( ! in_array( date('N', $increase) == 7 ? 0 : date('N', $increase), $repeat_days ) ){
                $increase += DAY_IN_SECONDS;
              }

            }

          }

          /** Repeat Every Two Weeks */
          elseif( $repeat === 3 ){

            $factor = ceil( ( $now - $timestamp ) / WEEK_IN_SECONDS / 2 );
      			$increase = intval( $timestamp ) + $factor * 2 * WEEK_IN_SECONDS;

          }

          /** Repeat Monthly */
          elseif( $repeat === 4 ){

            $factor = ceil( ( $now - $timestamp ) / MONTH_IN_SECONDS );
      			$increase = strtotime( "+{$factor} months", intval( $timestamp ) );

          }

          /** Repeat Yearly */
          elseif( $repeat === 5 ){

            $factor = ceil( ( $now - $timestamp ) / YEAR_IN_SECONDS );
      			$increase = strtotime( "+{$factor} years", intval( $timestamp ) );

          }

          if( $until !== true && $until !== false && ! empty( $until ) ){

            $until = strtotime( $until );

            if( $increase > intval( $until ) ){
              $until = true;
            }

          }

          if( $until !== true ){
    				update_post_meta( $id, '_wcs_timestamp', $increase );
    			}

          WeeklyClassCrons::update_event_history( $id, $timestamp, $repeat );

        }
    }

		endwhile; endif;

	}

  public static function update_event_history( $id, $timestamp, $repeat ){

    $now 	= current_time( 'timestamp' );

    $last = maybe_unserialize( get_post_meta( $id, '_wcs_timestamp_last', true ) );

    if( ! $last || empty( $last ) ){
      $last = array();
    }

    if( ! in_array( $timestamp, $last ) ){
      $last[] = $timestamp;
    }

    switch ( $repeat ) {

      case 5 : {

        for( $i = 1;  $i < ceil( ( $now - $timestamp ) / YEAR_IN_SECONDS ); $i++ ){

          $increase = intval( $timestamp ) + $i * YEAR_IN_SECONDS;

          if( ! in_array( $increase, $last ) ){
            $last[] = $increase;
          }

        }

      } break;

      case 4 : {

        for( $i = 1;  $i < ceil( ( $now - $timestamp ) / MONTH_IN_SECONDS ); $i++ ){

          $increase = intval( $timestamp ) + $i * MONTH_IN_SECONDS;

          if( ! in_array( $increase, $last ) ){
            $last[] = $increase;
          }

        }

      } break;

      case 3 : {

        for( $i = 1;  $i < ceil( ( $now - $timestamp ) / WEEK_IN_SECONDS / 2 ); $i++ ){

          $increase = intval( $timestamp ) + $i * 2 * WEEK_IN_SECONDS;

          if( ! in_array( $increase, $last ) ){
            $last[] = $increase;
          }

        }

      } break;

      case 2 : {

        $repeat_days = maybe_unserialize( get_post_meta( $id, '_wcs_repeat_days', true ) );

        if( ! empty( $repeat_days ) ){

          $factor = ceil( ( $now - $timestamp ) / DAY_IN_SECONDS );

          for( $i = 1;  $i < ceil( ( $now - $timestamp ) / DAY_IN_SECONDS ); $i++ ){

            $increase = intval( $timestamp ) + $i * DAY_IN_SECONDS;

            if( ! in_array( $increase, $last ) && in_array( date('N', $increase), $repeat_days ) ){
              $last[] = $increase;
            }

          }

        }

      } break;

      default : {

        for( $i = 1;  $i < ceil( ( $now - $timestamp ) / WEEK_IN_SECONDS ); $i++ ){

          $increase = intval( $timestamp ) + $i * WEEK_IN_SECONDS;

          if( ! in_array( $increase, $last ) ){
            $last[] = $increase;
          }

        }

      } break;

    }

    $max_history_count = intval( apply_filters( 'wcs_history_count', 35 ) );
    $max_history_count = $max_history_count > 366 ? 366 : $max_history_count;
    $max_history_count = $max_history_count <= 0 ? 1 : $max_history_count;

    if( count( $last ) > $max_history_count ){
      unset( $last[0] );
    }

    update_post_meta( $id, '_wcs_timestamp_last', $last );

  }

}

new WeeklyClassCrons();

?>
