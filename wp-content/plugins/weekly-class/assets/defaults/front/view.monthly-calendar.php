<?php
add_filter( 'wcs_view_css', 'wcs_view_css_monthly_calendar', 10, 3 );
add_filter( 'wcs_stop', 'wcs_default_stop_date_monthly_calendar' , 20, 2 );
add_filter( 'wcs_start', 'wcs_default_start_date_monthly_calendar' , 20, 2 );
add_filter( 'wcs_schedule_limit', 'wcs_default_limit_monthly_calendar', 20, 2 );

function wcs_default_limit_monthly_calendar( $limit, $data ){
  if( ! isset( $data['view'] ) ||  intval( $data['view'] ) !== 12 ) return $limit;
  return 0;
}

function wcs_default_start_date_monthly_calendar( $date, $data ){

  if( ! isset( $data['view'] ) || intval( $data['view'] ) !== 12 )
    return $date;

  $date = strtotime( date('Y-m-01', current_time('timestamp') ) );
  return $date;
}

function wcs_default_stop_date_monthly_calendar( $date, $data ){

  if( ! isset( $data['view'] ) ||  intval( $data['view'] ) !== 12 )
    return $date;

  $date = strtotime( date("Y-m-t", current_time('timestamp')) );
  return $date;
}

function wcs_view_css_monthly_calendar( $css, $data, $schedule_id ){

  if( ! isset( $data['view'] ) ||  intval( $data['view'] ) !== 12 )
    return $css;

  /** Basic */
  $color_special  = isset( $data['color_special'] ) ? $data['color_special'] : '#BF392B';
  $color_today    = isset( $data['color_mth_selected'] ) && ! empty( $data['color_mth_selected'] ) ? $data['color_mth_selected'] : '#BD322C';

  $css .= "
    .wcs-timetable--$schedule_id .wcs-timetable--monthly-calendar .wcs-date--selected span{
      color: " . CurlyWeeklyClassShortcodes::contrast( $color_today, 0.95, 0.75 ) . "
    }
    .wcs-timetable--$schedule_id .wcs-timetable--monthly-calendar .wcs-date.wcs-date--selected span::after{
      background-color: $color_today;
    }
    .wcs-timetable--$schedule_id .wcs-timetable--monthly-calendar .wcs-date span::after{
      background-color: $color_special;
    }
    .wcs-timetable--$schedule_id .wcs-timetable--monthly-calendar .wcs-date--with-events:not(.wcs-date--selected):not(.wcs-date--past-month):not(.wcs-date--future-month):hover,
    .wcs-timetable--$schedule_id .wcs-timetable--monthly-calendar .wcs-class__time-duration span:first-child{
      color: $color_special;
    }
    .wcs-timetable--$schedule_id .wcs-timetable--monthly-calendar.wcs-timetable--inside-agenda:not(.wcs-timetable--highligh-round) .wcs-date--selected::after{
      border-top: 1em solid $color_today;
    }
    .wcs-timetable--$schedule_id .wcs-timetable--monthly-calendar .wcs-calendar-nav i,
    .wcs-timetable--$schedule_id .wcs-timetable--monthly-calendar .wcs-day-agenda__title{
      color: $color_special;
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
