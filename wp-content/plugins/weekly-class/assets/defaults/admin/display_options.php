<?php
add_filter( 'wcs_builder_display_options', 'wcs_default_display_options' );
function wcs_default_display_options( $display_options ){
  global $wp_locale;
  $options = array(
     array(
      'id'	=> 'show_title',
      'title' => __( 'Show Schedule Title', 'WeeklyClass' ),
      'desc'	=> __( 'Checking this option will make your title visible', 'WeeklyClass'),
      'type'  => 'switch',
      'default' => false
    ),
     array(
      'id'	=> 'show_ending',
      'title' => __('Show Ending Time', 'WeeklyClass' ),
      'type'  => 'switch',
      'default' => false
    ),
    array(
      'id' => 'show_duration',
      'title' => __( 'Show Duration', 'WeeklyClass' ),
      'type'  => 'switch',
      'default' => false
    ),
    array(
      'id'	=> 'show_description',
      'title' => __( 'Show Description', 'WeeklyClass' ),
      'type'  => 'switch',
      'default' => false
    ),
    array(
      'id'	=> 'show_excerpt',
      'title' => __( 'Show Excerpt', 'WeeklyClass' ),
      'type'  => 'switch',
      'default' => false
    ),
    array(
      'id'	=> 'show_more',
      'title' => __( 'Show More Button', 'WeeklyClass' ),
      'type'  => 'switch',
      'default' => true
    ),
    array(
      'id'	=> 'show_time_format',
      'title' => __( 'Show 12-hour Clock', 'WeeklyClass' ),
      'type'  => 'switch',
      'default' => false
    ),
    array(
      'id'	=> 'label_dateformat',
      'title' => __( 'Date Format', 'WeeklyClass' ),
      'desc'	=> __( 'Available date & time formats available here: <a href="http://docs.curlythemes.com/weekly-class-schedule/#moment-js-date-format" target="_blank">Events Schedule Date Formatting</a>', 'WeeklySchedule' ),
      'placeholder' => 'MM/DD',
      'type'  => 'text',
      'default' => ''
    ),
    array(
      'id'	=> 'show_past_events',
      'title' => __( 'Show Past Events', 'WeeklyClass' ),
      'type'  => 'switch',
      'default' => false
    ),
    array(
      'id'	=> 'reverse_order',
      'title' => __( 'Reverse Order', 'WeeklyClass' ),
      'type'  => 'switch',
      'default' => false
    ),
    array(
      'id'	=> 'starting_date',
      'title' => __( 'Past Events Starting Date', 'WeeklyClass' ),
      'type'  => 'date',
      'default' => '',
      'options' => array(
        'offset' => get_option('gmt_offset'),
        'firstDay' => intval( get_option( 'start_of_week', 0 ) ),
        'monthNames' => array_values( $wp_locale->month ),
        'monthNamesShort' => array_values( $wp_locale->month_abbrev ),
        'dayNames' => array_values( $wp_locale->weekday ),
        'dayNamesShort' => array_values( $wp_locale->weekday_abbrev ),
        'dayNamesMin' => array_values( $wp_locale->weekday_initial )
      )
    )
  );
  $taxonomies = WeeklyClass::get_object_taxonomies( 'class', 'objects' );
  foreach( $taxonomies as $tax => $taxonomy ){
    $options[] = array(
      'id' => 'show_'.str_replace('-','_',$tax),
      'title' => sprintf( __( 'Show %s', 'WeeklyClass' ), $taxonomy->label ),
      'type' 	=> 'switch',
      'default' => false
    );
  }
  return array_merge($options, $display_options);
}

?>
