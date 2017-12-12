<?php
add_filter( 'wcs_view_css', 'wcs_view_css_carousel', 10, 3 );

function wcs_view_css_carousel( $css, $data, $schedule_id ){

  if(  ! isset( $data['view'] ) || intval( $data['view'] ) !== 6 )
    return $css;

  /** Basic */
  $color_special = isset( $data['color_special'] ) && ! empty( $data['color_special'] ) ? $data['color_special'] : '#BF392B';
  $color_carousel_item_bg = isset( $data['color_carousel_item_bg'] ) && ! empty( $data['color_carousel_item_bg'] ) ? $data['color_carousel_item_bg'] : '#ffffff';
  $color_carousel_nav = isset( $data['color_carousel_item_bg'] ) && ! empty( $data['color_carousel_item_nav'] ) ? $data['color_carousel_item_nav'] : null;
  $carousel_padding = isset( $data['carousel_padding'] ) && ! empty( $data['carousel_padding'] ) ? $data['carousel_padding'] : 0;
  $css .= "
    .wcs-timetable--$schedule_id .wcs-timetable__carousel .ti-time,
    .wcs-timetable--$schedule_id .wcs-timetable__carousel .wcs-class__title,
    .wcs-timetable--$schedule_id .wcs-timetable__carousel .owl-dots .active,
    .wcs-timetable--$schedule_id .wcs-timetable__carousel .owl-nav .owl-prev:hover,
    .wcs-timetable--$schedule_id .wcs-timetable__carousel .owl-nav .owl-next:hover{
      color: $color_special;
    }
    .wcs-timetable--$schedule_id .wcs-timetable__carousel .wcs-class{
      background-color: $color_carousel_item_bg;
    }
    .wcs-timetable--$schedule_id .wcs-timetable__carousel .owl-nav .owl-prev{
      left: {$carousel_padding}px;
    }
    .wcs-timetable--$schedule_id .wcs-timetable__carousel .owl-nav .owl-next{
      right: {$carousel_padding}px;
    }
  ";
  if( ! is_null( $color_carousel_nav ) ){
    $css .= "
      .wcs-timetable--$schedule_id .wcs-timetable__carousel .owl-dots,
      .wcs-timetable--$schedule_id .wcs-timetable__carousel .owl-nav{
        color: $color_carousel_nav;
      }
    ";
  }

  return $css;
}
?>
