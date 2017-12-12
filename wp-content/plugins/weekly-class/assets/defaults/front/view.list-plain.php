<?php
add_filter( 'wcs_view_css', 'wcs_view_css_plain_list', 10, 3 );

function wcs_view_css_plain_list( $css, $data, $schedule_id ){

  if( ! isset( $data['view'] ) ||  intval( $data['view'] ) !== 0 )
    return $css;

  /** Basic */
  $color_special = isset( $data['color_special'] ) && ! empty( $data['color_special'] ) ? $data['color_special'] : '#BF392B';
  $css .= "
    .wcs-timetable--$schedule_id .wcs-timetable--list .wcs-class__time{
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
  if( ! empty( $weekdays ) ){
    foreach( $weekdays as $key => $day_color ){
      $css .= ! empty( $day_color ) ? "
        .wcs-timetable--$schedule_id .wcs-timetable--list .wcs-class--day-$key .wcs-class__title,
        .wcs-timetable--$schedule_id .wcs-timetable--list .wcs-class--day-$key::before,
        .wcs-timetable--$schedule_id .wcs-timetable--list .wcs-class--day-$key .wcs-class__time{
          color: $day_color;
        }
        .wcs-timetable--$schedule_id .wcs-timetable--list .wcs-class--day-$key{
          border-left: 0.6vh solid $day_color;
        }
      " : '';
    }
  }

  /** Terms */
  foreach( $data['terms_colors'] as $term => $term_color ){
      $css .= ".wcs-timetable--$schedule_id .wcs-timetable--list .wcs-class--term-id-$term .wcs-class__title,
      .wcs-timetable--$schedule_id .wcs-timetable--list .wcs-class--term-id-$term::before,
      .wcs-timetable--$schedule_id .wcs-timetable--list .wcs-class--term-id-$term .wcs-class__time{
        color:{$term_color};
      }";

      $css .= ".wcs-timetable--$schedule_id .wcs-timetable--list .wcs-class--term-id-$term{
        border-left: 0.6vh solid {$term_color};
      }";
  }

  return $css;
}
?>
