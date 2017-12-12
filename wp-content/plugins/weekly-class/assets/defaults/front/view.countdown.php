<?php
add_filter( 'wcs_view_css', 'wcs_view_css_countdown', 10, 3 );

function wcs_view_css_countdown( $css, $data, $schedule_id ){

  if(  ! isset( $data['view'] ) || intval( $data['view'] ) !== 10 )
    return $css;

  /** Basic */
  $color_special = isset( $data['color_special'] ) && ! empty( $data['color_special'] ) ? $data['color_special'] : '#BF392B';
  $color_bg      = isset( $data['color_countdown_bg'] ) && ! empty( $data['color_countdown_bg'] ) ? $data['color_countdown_bg'] : '#16a085';
  $color_bg_opac = isset( $data['countdown_overlay'] ) && ! empty( $data['countdown_overlay'] ) ? intval( $data['countdown_overlay'] ) : 10;
  $color_bg_opac = $color_bg_opac / 100;

  $css .= "
    .wcs-timetable--$schedule_id .wcs-timetable--countdown .wcs-class__titles,
    .wcs-timetable--$schedule_id .wcs-timetable--countdown .wcs-class__countdown{
      background-color:  ".CurlyWeeklyClassShortcodes::hex2rgb( $color_bg, $color_bg_opac ).";

    }
    .wcs-timetable--$schedule_id .wcs-timetable--countdown .wcs-class__countdown-time::before{
      background-color: ".CurlyWeeklyClassShortcodes::darken( $color_bg ).";
    }
    .wcs-timetable--$schedule_id .wcs-timetable--countdown .wcs-class__action::before{
      background-color: ".CurlyWeeklyClassShortcodes::hex2rgb( $color_bg, $color_bg_opac ).";
    }
  ";

  return $css;
}
?>
