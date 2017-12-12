<?php
add_filter( 'wcs_view_css', 'wcs_view_css_masonry_grid', 10, 3 );

function wcs_view_css_masonry_grid( $css, $data, $schedule_id ){

  if(  ! isset( $data['view'] ) || intval( $data['view'] ) !== 7 )
    return $css;

  /** Basic */
  $color_special = isset( $data['color_special'] ) && ! empty( $data['color_special'] ) ? $data['color_special'] : '#BF392B';
  $color_grid_item_bg = isset( $data['color_grid_item_bg'] ) && ! empty( $data['color_grid_item_bg'] ) ? $data['color_grid_item_bg'] : '#ffffff';
  $grid_items_lg = isset( $data['grid_items_lg'] ) ? $data['grid_items_lg'] : 4;
  $grid_items_md = isset( $data['grid_items_md'] ) ? $data['grid_items_md'] : 3;
  $grid_items_xs = isset( $data['grid_items_xs'] ) ? $data['grid_items_xs'] : 1;
  $gutter_lg = ( 2 * ( $grid_items_lg - 1 ) ) / $grid_items_lg;
  $gutter_md = ( 2 * ( $grid_items_md - 1 ) ) / $grid_items_md;
  $gutter_xs = ( 2 * ( $grid_items_xs - 1 ) ) / $grid_items_xs;
  $item_width_lg = ( 100 / $grid_items_lg ) - $gutter_lg;
  $item_width_md = ( 100 / $grid_items_md ) - $gutter_md;
  $item_width_xs = ( 100 / $grid_items_xs ) - $gutter_xs;
  $item_width_lg_active = ( 2 * $item_width_lg ) + 2 > 100 ? 100 : ( 2 * $item_width_lg ) + 2;
  $item_width_md_active = ( 2 * $item_width_md ) + 2 > 100 ? 100 : ( 2 * $item_width_md ) + 2;
  $item_width_xs_active = ( 2 * $item_width_xs ) + 2 > 100 ? 100 : ( 2 * $item_width_xs ) + 2;
  $css .= "
    .wcs-timetable--$schedule_id .wcs-timetable__grid .ti-time,
    .wcs-timetable--$schedule_id .wcs-timetable__grid .wcs-class__title{
      color: $color_special;
    }
    .wcs-timetable--$schedule_id .wcs-timetable__grid .wcs-class{
      background-color: $color_grid_item_bg;
    }
    .wcs-class__minimize{
      background-color: " . CurlyWeeklyClassShortcodes::hex2rgb( $color_grid_item_bg, 0.85 ) . ";
    }
    .wcs-timetable--$schedule_id .wcs-isotope-item,
    .wcs-timetable--$schedule_id .wcs-class{
      width: {$item_width_xs}%;
    }
    .wcs-timetable--$schedule_id .wcs-class--active{
      width: {$item_width_xs_active}%;
    }
    @media (min-width: 600px) {
      .wcs-timetable--$schedule_id .wcs-isotope-item,
      .wcs-timetable--$schedule_id .wcs-class{
        width: {$item_width_md}%;
      }
      .wcs-timetable--$schedule_id .wcs-class--active{
        width: {$item_width_md_active}%;
      }
    }
    @media (min-width: 1000px) {
      .wcs-timetable--$schedule_id .wcs-isotope-item,
      .wcs-timetable--$schedule_id .wcs-class{
        width: {$item_width_lg}%;
      }
      .wcs-timetable--$schedule_id .wcs-class--active{
        width: {$item_width_lg_active}%;
      }
    }
  ";

  return $css;
}
?>
