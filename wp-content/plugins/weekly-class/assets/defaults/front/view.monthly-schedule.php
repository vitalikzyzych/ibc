<?php
add_filter( 'wcs_view_css', 'wcs_view_css_monthly_schedule', 10, 3 );
add_filter( 'wcs_stop', 'wcs_default_stop_date_monthly' , 20, 2 );
add_filter( 'wcs_start', 'wcs_default_start_date_monthly' , 20, 2 );

function wcs_default_start_date_monthly( $date, $data ){

  if( ! isset( $data['view'] ) ||  intval( $data['view'] ) !== 9 )
    return $date;

  if( ! isset( $data['show_past_events'] ) || ! filter_var( $data['show_past_events'], FILTER_VALIDATE_BOOLEAN ) ){
    $date = current_time('timestamp') + 1 * DAY_IN_SECONDS;
  } else{
    $date = strtotime( date('Y-m-01', current_time('timestamp') ) );
  }
  return $date;
}

function wcs_default_stop_date_monthly( $date, $data ){

  if( ! isset( $data['view'] ) ||  intval( $data['view'] ) !== 9 )
    return $date;
  $date = strtotime( date("Y-m-t", current_time('timestamp')) );
  return $date;
}

function wcs_view_css_monthly_schedule( $css, $data, $schedule_id ){

  if( ! isset( $data['view'] ) ||  intval( $data['view'] ) !== 9 )
    return $css;

  /** Basic */
  $color_special  = isset( $data['color_special'] ) ? $data['color_special'] : '#BF392B';
  $color_today    = isset( $data['color_monthly_today'] ) && ! empty( $data['color_monthly_today'] ) ? $data['color_monthly_today'] : '#FBF9E3';
  $color_item     = isset( $data['color_monthly_item'] ) && ! empty( $data['color_monthly_item'] ) ? $data['color_monthly_item'] : '#16a085';
  $color_item_past  = isset( $data['color_monthly_item_past'] ) && ! empty( $data['color_monthly_item_past'] ) ? $data['color_monthly_item'] : '#e2e2e2';

  $css .= "
    .wcs-timetable--$schedule_id .wcs-timetable__monthly-schedule th span{
      color: $color_special;
    }
    .wcs-timetable--$schedule_id .wcs-timetable__monthly-schedule.fc-unthemed .fc-bg td.fc-today{
      background-color: " . CurlyWeeklyClassShortcodes::hex2rgb( $color_today, 1 ) . ";
    }
    .wcs-timetable--$schedule_id .wcs-timetable__monthly-schedule .fc-event:not(.wcs-class--past-event){
      color: " . CurlyWeeklyClassShortcodes::contrast( $color_item, 0.95, 0.75 ) . "
    }
    .wcs-timetable--$schedule_id .wcs-timetable__monthly-schedule .fc-event:not(.wcs-class--past-event)::before{
      background-color: $color_item
    }
    .wcs-timetable--$schedule_id .wcs-timetable__monthly-schedule .fc-event.wcs-class--past-event{
      color: " . CurlyWeeklyClassShortcodes::contrast( $color_item_past, 0.35, 0.35 ) . "
    }
    .wcs-timetable--$schedule_id .wcs-timetable__monthly-schedule .fc-event.wcs-class--past-event::before{
      background-color: $color_item_past
    }
  ";

  /** Weekdays */
  $weekdays = array();
  for( $i= 1; $i <= 7; $i++){
    if( isset( $data['color_days_0' . $i] ) && ! empty( $data['color_days_0' . $i] ) ){
      $weekdays[$i] = $data['color_days_0' . $i];
    }
  }
  foreach( $weekdays as $key => $day_color ){
    switch( $key ){
      case 0 : $day = 'sun'; break;
      case 1 : $day = 'mon'; break;
      case 2 : $day = 'tue'; break;
      case 3 : $day = 'wed'; break;
      case 4 : $day = 'thu'; break;
      case 5 : $day = 'fri'; break;
      case 6 : $day = 'sat'; break;
    }
    $css .= $day_color ? "
      .wcs-timetable--$schedule_id .wcs-timetable__monthly-schedule .fc-bg .fc-day.fc-$day{
        background-color: $day_color;
      }
    " : '';
  }

  return $css;
}
?>
