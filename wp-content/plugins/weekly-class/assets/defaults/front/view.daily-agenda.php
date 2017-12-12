<?php
add_filter( 'wcs_view_css', 'wcs_view_css_agenda', 10, 3 );

function wcs_view_css_agenda( $css, $data, $schedule_id ){

  if( ! isset( $data['view'] ) || intval( $data['view'] ) !== 5 )
    return $css;

  /** Basic */
  $color_special = isset( $data['color_special'] ) && ! empty( $data['color_special'] ) && $data['color_special'] !== 'undefined' ? $data['color_special'] : '#BF392B';
  $css .= "
    .wcs-timetable--$schedule_id .wcs-timetable__agenda-nav .active,
    .wcs-timetable--$schedule_id .wcs-timetable__agenda-data .ti-time{
      color: $color_special;
    }
  ";

  return $css;
}
?>
