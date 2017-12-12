<?php

add_filter( 'weekly_class_settings', 'wcs_default_settings' );

function wcs_default_settings( $settings ){

  $settings['wcs_single'] = array(
    'value' => false,
    'callback' => 'wp_validate_boolean'
  );

  $settings['wcs_lazy_load'] = array(
    'value' => false,
    'callback' => 'wp_validate_boolean'
  );

  $settings['wcs_classes_archive'] = array(
    'value' => false,
    'callback' => 'wp_validate_boolean'
  );

  $settings['wcs_all_days'] = array(
    'value' => 5,
    'callback' => 'intval'
  );

  $settings['wcs_get_footer'] = array(
    'value' => false,
    'callback' => 'wp_validate_boolean'
  );

  $settings['wcs_single_template'] = array(
    'value' => 'page'
  );

  $settings['wcs_slug'] = array(
    'value' => 'class'
  );

  $settings['wcs_single_box'] = array(
    'value' => 'left'
  );

  $settings['wcs_single_color'] = array(
    'value' => '#BD322C'
  );

  $settings['wcs_dateformat'] = array(
    'value' => 'F j @ H:i'
  );

  $settings['wcs_single_ending'] = array(
    'value' => true,
    'callback' => 'wp_validate_boolean'
  );

  $settings['wcs_single_duration'] = array(
    'value' => true,
    'callback' => 'wp_validate_boolean'
  );

  $settings['wcs_single_location'] = array(
    'value' => true,
    'callback' => 'wp_validate_boolean'
  );

  $settings['wcs_single_instructor'] = array(
    'value' => true,
    'callback' => 'wp_validate_boolean'
  );

  $settings['wcs_single_map'] = array(
    'value' => true,
    'callback' => 'wp_validate_boolean'
  );

  $settings['wcs_single_map_theme'] = array(
    'value' => 'light'
  );

  $settings['wcs_single_map_zoom'] = array(
    'value' => 15,
    'callback' => 'intval'
  );

  $settings['wcs_single_map_type'] = array(
    'value' => 'roadmap'
  );

  $settings['wcs_api_key'] = array(
    'value' => ''
  );

  $settings['wcs_time_format'] = array(
    'value' => false,
    'callback' => 'wp_validate_boolean'
  );

  foreach( $settings as $key => $s ){
    $settings[$key]['value'] = isset( $s['callback'] ) ? $s['callback']( $s['value'] ) : esc_attr( $s['value'] );
  }

  return $settings;
}

?>
