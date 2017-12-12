<?php
add_filter( 'wcs_builder_labels', 'wcs_default_labels' );
function wcs_default_labels( $labels ){
  $labels[] = array(
    'title' => __( 'Days of The Week Filter Label', 'WeeklyClass' ),
    'id'	=> 'label_filter_day',
    'placeholder' => __( 'Enter the days of the week filter label here', 'WeeklyClass' )
  );
  $labels[] = array(
    'title' => __( 'Time of the Day Filter Label', 'WeeklyClass' ),
    'id'	=> 'label_filter_time',
    'placeholder' => __( 'Enter the time of the Day filter label here', 'WeeklyClass' )
  );
  $labels[] = array(
    'title' => __( 'More Classes Button Label', 'WeeklyClass' ),
    'id'	=> 'label_more',
    'placeholder' => __( 'Enter the more classes button label here', 'WeeklyClass' ),
    'default' => __( 'More Classes', 'WeeklyClass' )
  );
  $labels[] = array(
    'title' => __( 'Filters Toggle Button Label', 'WeeklyClass' ),
    'id'	=> 'label_toggle',
    'placeholder' => __( 'Enter the filters toggle button label here', 'WeeklyClass' )
  );
  $labels[] = array(
    'title' => __( 'Info Button Label', 'WeeklyClass' ),
    'id'	=> 'label_info',
    'placeholder' => __( 'Enter the info button label here', 'WeeklyClass' ),
    'defaul'  => __( '...', 'WeeklyClass' )
  );
  $labels[] = array(
    'title' => __( 'Nothing to Show Message', 'WeeklyClass' ),
    'id'	=> 'zero',
    'placeholder' => __( 'Enter the no results text here', 'WeeklyClass' ),
    'default' => __( 'Nothing to show.', 'WeeklyClass' )
  );
  return $labels;
}

?>
