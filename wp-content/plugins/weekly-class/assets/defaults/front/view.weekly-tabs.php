<?php
add_filter( 'wcs_view_css', 'wcs_view_css_weekly_tabs', 10, 3 );
add_filter( 'wcs_stop', 'wcs_default_stop_date_weekly_tabs' , 20, 2 );
add_filter( 'wcs_start', 'wcs_default_start_date_weekly_tabs' , 20, 2 );

function wcs_default_start_date_weekly_tabs( $date, $data ){

  if( ! isset( $data['view'] ) ||  intval( $data['view'] ) !== 4 )
    return $date;

  $date = current_time('timestamp');

  if( ! isset( $data['show_past_events'] ) || ! filter_var( $data['show_past_events'], FILTER_VALIDATE_BOOLEAN ) ){
    $date = current_time('timestamp') + 1 * DAY_IN_SECONDS;
  }
  return $date;
}

function wcs_default_stop_date_weekly_tabs( $date, $data ){

  if( ! isset( $data['view'] ) ||  intval( $data['view'] ) !== 4 )
    return $date;

  $date = current_time('timestamp') + 6 * DAY_IN_SECONDS;

  if( ! isset( $data['show_past_events'] ) || ! filter_var( $data['show_past_events'], FILTER_VALIDATE_BOOLEAN ) ){
    $date = current_time('timestamp') + 7 * DAY_IN_SECONDS;
  }
  return $date;
}


function wcs_view_css_weekly_tabs( $css, $data, $schedule_id ){

  if( ! isset( $data['view'] ) ||  intval( $data['view'] ) !== 4 )
    return $css;

  /** Basic */
  $color_special = isset( $data['color_special'] ) && ! empty( $data['color_special'] ) ? $data['color_special'] : '#BF392B';
  $css .= "
    .wcs-timetable--$schedule_id .wcs-timetable__tabs-nav .active{
      background-color: $color_special;
      color: " . CurlyWeeklyClassShortcodes::contrast( $color_special, 1, 0.75 ) . ";
    }
    .wcs-timetable--$schedule_id .wcs-timetable__tabs-nav .active::after{
      border-top-color: $color_special;
    }
  ";

  /** Weekdays */
  $weekdays = array();
  for( $i= 1; $i <= 7; $i++){
    if( isset( $data['color_days_0' . $i] ) && ! empty( $data['color_days_0' . $i] ) ){
      $weekdays[$i] = $data['color_days_0' . $i];
    }
  }
  if( ! empty( $weekdays ) ){
    foreach( $weekdays as $key => $day_color ){
      $key  = $key === 0 ? 7 : $key;
      $css .= ! empty( $day_color ) ? "
        .wcs-timetable--$schedule_id .wcs-timetable--tabs .wcs-timetable__tabs-nav-item--$key.active{
          background-color: $day_color;
          color: " . CurlyWeeklyClassShortcodes::contrast( $day_color, 1, 0.75 ) . ";
        }
        .wcs-timetable--$schedule_id .wcs-timetable--tabs .wcs-timetable__tabs-nav-item--$key.active::after{
          border-top-color: $day_color;
        }
      " : '';
    }
  }

  /** Terms */
  foreach( $data['terms_colors'] as $term_id => $term_color ){
    $css .= ".wcs-timetable--$schedule_id .wcs-timetable--tabs .wcs-class--term-id-$term_id{
      background-color: {$term_color};
      color: " . CurlyWeeklyClassShortcodes::contrast( $term_color, 1, 0.75 ) . "
    }";
  }

  return $css;
}
?>
