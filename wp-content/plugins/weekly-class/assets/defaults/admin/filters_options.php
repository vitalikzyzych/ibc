<?php
add_filter( 'wcs_builder_filters_options', 'wcs_default_filters_options' );
function wcs_default_filters_options( $filters_options ){
  $taxonomies = WeeklyClass::get_object_taxonomies( 'class', 'objects' );

  foreach( $taxonomies as $tax => $taxonomy ){
    $filters_options[] = array(
      'id'	=> 'show_filter_'.str_replace('-','_',$tax),
      'title' => sprintf( __( 'Show %s Filter', 'WeeklyClass' ), $taxonomy->label ),
      'type' 	=> 'switch',
      'default' => false
    );
  }
  $filters_options[] = array(
    'id'	=> 'show_filter_day_of_week',
    'title' => __( 'Show Day of the Week Filter', 'WeeklyClass' ),
    'type'  => 'switch',
    'default' => false
  );
  $filters_options[] = array(
    'id'	=> 'show_filter_time_of_day',
    'title' => __( 'Show Time of the Day Filter', 'WeeklyClass' ),
    'type'  => 'switch',
    'default' => false
  );

  $filters_options[] = array(
    'id'	=> 'filters_position',
    'title' => __( 'Filters Position', 'WeeklyClass' ),
    'type'  => 'group',
    'default' => 1,
    'options' => array(
      array(
        'value' => 0,
        'name' => __( 'Left', 'WeeklyClass' ),
        'icon' => 'ti-align-left'
      ),
      array(
        'value' => 1,
        'name' => __( 'Center', 'WeeklyClass' ),
        'icon' => 'ti-align-center'
      ),
      array(
        'value' => 2,
        'name' => __( 'Right', 'WeeklyClass' ),
        'icon' => 'ti-align-right'
      )
    )
  );
  $filters_options[] = array(
    'id'	=> 'filters_style',
    'title' => __( 'Show Filters as Switches', 'WeeklyClass' ),
    'type'  => 'switch',
    'default' => false
  );
  /*
  $filters_options[] = array(
    'id'	=> 'filters_select2',
    'title' => __( 'Show Filters as Select', 'WeeklyClass' ),
    'type'  => 'switch',
    'default' => false
  );
  $filters_options[] = array(
    'id'	=> 'filters_select2_multiple',
    'title' => __( 'Allow Multiple Select', 'WeeklyClass' ),
    'type'  => 'switch',
    'default' => true
  );
  */
  $filters_options[] = array(
    'id'	=> 'show_filters_opened',
    'title' => __( 'Show Filters Expanded', 'WeeklyClass' ),
    'type'  => 'switch',
    'default' => false
  );

  return $filters_options;

}

?>
