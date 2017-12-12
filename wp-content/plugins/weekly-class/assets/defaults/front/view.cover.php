<?php
add_filter( 'wcs_view_css', 'wcs_view_css_cover', 10, 3 );

function wcs_view_css_cover( $css, $data, $schedule_id ){

  if(  ! isset( $data['view'] ) || intval( $data['view'] ) !== 11)
    return $css;

  /** Basic */
  $color_special = isset( $data['color_special'] ) && ! empty( $data['color_special'] ) ? $data['color_special'] : '#BF392B';
  $color_bg      = isset( $data['color_cover_bg'] ) && ! empty( $data['color_cover_bg'] ) ? $data['color_cover_bg'] : '#ffffff';
  $color_bg_opac = isset( $data['cover_overlay'] ) && ! empty( $data['cover_overlay'] ) ? intval( $data['cover_overlay'] ) : 10;
  $color_bg_opac = $color_bg_opac / 100;

  $css .= "
    .wcs-timetable--$schedule_id .wcs-timetable--cover.wcs-timetable--cover-overlay-image .wcs-class__image::after{
      background-color: {$color_bg};
      opacity: {$color_bg_opac}
    }
    .wcs-timetable--$schedule_id .wcs-timetable--cover.wcs-timetable--cover-overlay-image:hover .wcs-class__image::after{
      opacity: " . ( $color_bg_opac + 0.05 ) . ";
    }
    .wcs-timetable--$schedule_id .wcs-timetable--cover.wcs-timetable--cover-overlay-text .wcs-class__content::before{
      background-color: {$color_bg};
      opacity: {$color_bg_opac}
    }
    .wcs-timetable--$schedule_id .wcs-timetable--cover.wcs-timetable--cover-overlay-text:hover .wcs-class__content::before{
      opacity: " . ( $color_bg_opac + 0.05 ) . ";
    }
  ";

  return $css;
}
?>
