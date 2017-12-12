<?php
add_filter( 'wcs_view_css', 'wcs_view_css_large_list', 10, 3 );

function wcs_view_css_large_list( $css, $data, $schedule_id ){

  if(  ! isset( $data['view'] ) || intval( $data['view'] ) !== 2 )
    return $css;

  /** Basic */
  $color_special = isset( $data['color_special'] ) && ! empty( $data['color_special'] ) ? $data['color_special'] : '#BF392B';
  $css .= "
    .wcs-timetable--$schedule_id .wcs-timetable--large .wcs-timetable__heading .wcs-table__td,
    .wcs-timetable--$schedule_id .wcs-timetable--large + .wcs-more{
      background-color: $color_special;
      color: " . CurlyWeeklyClassShortcodes::contrast( $color_special, 1, 0.75 ) . ";
    }
  ";

  /** Weekdays */
  $weekdays = array();
  for( $i= 1; $i <= 7; $i++){
    if( isset( $atts['color_days_0' . $i] ) && ! empty( $atts['color_days_0' . $i] ) ){
      $weekdays[$i] = $atts['color_days_0' . $i];
    }
  }
  if( ! empty( $weekdays ) ){
    foreach( $weekdays as $key => $day_color ){
      $css .= ! empty( $day_color ) ? "
        .wcs-timetable--$schedule_id .wcs-timetable--large .wcs-class--day-$key{
          color: $day_color;
        }
      " : '';
    }
  }

  /** Terms */
  foreach( $data['terms_colors'] as $term_id => $term_color ){
    $css .= ".wcs-timetable--$schedule_id .wcs-timetable--large .wcs-class--term-id-$term_id{
      color:{$term_color};
    }";
  }

  return $css;
}
?>
