<?php
add_filter( 'wcs_builder_modal_options', 'wcs_default_modal_options' );
function wcs_default_modal_options( $modal_options ){
  $options = array(
    array(
      'id'	=> 'modal',
      'type'  => 'group',
      'default' => 0,
      'options' => array(
        array(
          'name' => __( 'Small', 'WeeklyClass' ),
          'value' => 0,
          'icon' => 'ti-layout-cta-btn-right'
        ),
        array(
          'name' => __( 'Large', 'WeeklyClass' ),
          'value' => 1,
          'icon' => 'ti-layout-media-center'
        ),
        array(
          'name' => __( 'Disabled', 'WeeklyClass' ),
          'value' => 2,
          'icon' => 'ti-layout-placeholder'
        )
      )
    ),
    array(
      'id'	=> 'show_modal',
      'title' => __( 'Enabled Light Modal Window', 'WeeklyClass' ),
      'type'  => 'switch',
      'default' => false
    ),
    array(
      'id'	=> 'show_modal_duration',
      'title' => __( 'Show Duration', 'WeeklyClass' ),
      'type'  => 'switch',
      'default' => true
    ),
    array(
      'id'	=> 'show_modal_ending',
      'title' => __( 'Show Starting & Ending Time', 'WeeklyClass' ),
      'type'  => 'switch',
      'default' => true
    ),
    array(
      'id'	=> 'label_modal_dateformat',
      'title' => __( 'Date Format', 'WeeklyClass' ),
      'desc'	=> __( 'Available date & time formats available here: <a href="http://docs.curlythemes.com/weekly-class-schedule/#moment-js-date-format" target="_blank">Events Schedule Date Formatting</a>', 'WeeklySchedule' ),
      'placeholder' => 'MMMM DD @ HH:mm',
      'type'  => 'text',
      'default' => ''
    )
  );
  $taxonomies = WeeklyClass::get_object_taxonomies( 'class', 'objects' );
  foreach( $taxonomies as $tax => $taxonomy ){
    $options[] = array(
      'id'	=> 'modal_'.str_replace('-','_',$tax),
      'title' => sprintf( __( 'Show %s', 'WeeklyClass' ), $taxonomy->label ),
      'type' 	=> 'switch',
      'default' => true
    );
  }
  return array_merge($options, $modal_options);
}

?>
