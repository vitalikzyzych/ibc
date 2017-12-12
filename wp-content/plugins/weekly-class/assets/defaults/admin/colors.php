<?php
add_filter( 'wcs_builder_colors', 'wcs_default_colors' );
function wcs_default_colors( $colors ){
  $colors[] = array(
    'label' => __( 'General Coloring', 'WeeklyClass' ),
    'id' 		=> 'general',
    'colors' => array(
      array(
        'id' => 'color_text',
        'title' => __( 'Text Color:', 'WeeklyClass' )
      ),
      array(
        'id' => 'color_special',
        'title' => __( 'Special Color:', 'WeeklyClass' ),
        'default' => '#BF392B'
      )
    )
  );
  $colors[] = array(
    'label' => __( 'Weekdays Coloring', 'WeeklyClass' ),
    'id'		=> 'weekdays',
    'colors' => array(
      array(
        'id' => 'color_days_01',
        'title' => __( 'Monday Color:', 'WeeklyClass' )
      ),
      array(
        'id' => 'color_days_02',
        'title' => __( 'Tuesday Color:', 'WeeklyClass' )
      ),
      array(
        'id' => 'color_days_03',
        'title' => __( 'Wendsday Color:', 'WeeklyClass' )
      ),
      array(
        'id' => 'color_days_04',
        'title' => __( 'Thursday Color:', 'WeeklyClass' )
      ),
      array(
        'id' => 'color_days_05',
        'title' => __( 'Friday Color:', 'WeeklyClass' )
      ),
      array(
        'id' => 'color_days_06',
        'title' => __( 'Saturday Color:', 'WeeklyClass' )
      ),
      array(
        'id' => 'color_days_07',
        'title' => __( 'Sunday Color:', 'WeeklyClass' )
      )
    )
  );
  return $colors;
}

?>
