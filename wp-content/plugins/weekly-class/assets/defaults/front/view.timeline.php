<?php
add_filter( 'wcs_view_css', 'wcs_view_css_timeline', 10, 3 );

function wcs_view_css_timeline( $css, $data, $schedule_id ){

  if( ! isset( $data['view'] ) ||  intval( $data['view'] ) !== 8 )
    return $css;

  /** Basic */
  $color_special = isset( $data['color_special'] ) && ! empty( $data['color_special'] ) ? $data['color_special'] : '#BF392B';
  $color_timeline_item_bg = isset( $data['color_timeline_item_bg'] ) && ! empty( $data['color_timeline_item_bg'] ) ? $data['color_timeline_item_bg'] : '#ffffff';
  $css .= "
    .wcs-timetable--$schedule_id .wcs-timetable__timeline .wcs-day__title,
    .wcs-timetable--$schedule_id .wcs-timetable__timeline::before{
      background-color: $color_special;
      color: " . CurlyWeeklyClassShortcodes::contrast( $color_special, 1, 0.75 ) . ";
    }
    .wcs-timetable--$schedule_id .wcs-timetable__timeline .wcs-day{
      background-color: $color_timeline_item_bg;
    }
  ";

  return $css;
}
?>
