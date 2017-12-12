<?php
add_filter( 'wcs_view_css', 'wcs_view_css_compact_list', 10, 3 );

function wcs_view_css_compact_list( $css, $data, $schedule_id ){

  if(  ! isset( $data['view'] ) || intval( $data['view'] ) !== 1 )
    return $css;

  /** Basic */
  $color_special = isset( $data['color_special'] ) && ! empty( $data['color_special'] ) ? $data['color_special'] : '#BF392B';
  $css .= "
    .wcs-timetable--$schedule_id .wcs-timetable--compact .wcs-timetable__classes::before,
    .wcs-timetable--$schedule_id .wcs-timetable--compact .wcs-class__title{
      color: $color_special;
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
      $key  = $key === 0 ? 7 : $key;
      $css .= ! empty( $day_color ) ? "
        .wcs-timetable--$schedule_id .wcs-timetable--compact .wcs-day--$key .wcs-timetable__classes::before{
          border-left-color: $day_color;
        }
      " : '';
    }
  }

  /** Terms */
  foreach( $data['terms_colors'] as $term_id => $term_color ){
    $css .= ".wcs-timetable--$schedule_id .wcs-timetable--compact .wcs-class--term-id-$term_id .wcs-class__title{
      color:{$term_color};
    }";
    $css .= ".wcs-timetable--$schedule_id .wcs-timetable--compact .wcs-class--term-id-$term_id:not(.wcs-class--canceled) .wcs-class__title::before{
      content: '';
      display: inline-block;
      margin-bottom: 2px;
      width: 8px;
      height: 8px;
      margin-right: 3px;
      border-radius: 100%;
      background-color: {$term_color};
    }";
  }

  return $css;
}
?>
