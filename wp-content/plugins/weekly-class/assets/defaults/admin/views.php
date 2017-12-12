<?php


add_filter( 'wcs_views_3_options', 'wcs_default_options_weekly_schedule' );
function wcs_default_options_weekly_schedule( $labels ){
  return array(
    array(
      'id'	=> 'show_starting_hours',
      'title' => __( 'Group by Starting Hour', 'WeeklyClass' ),
      'type'  => 'switch',
      'default' => false
    ),
    array(
      'id'	=> 'show_navigation',
      'title' => __( 'Show Navigation', 'WeeklyClass' ),
      'type'  => 'switch',
      'default' => false
    )
  );
}

add_filter( 'wcs_views_3_labels', 'wcs_default_labels_weekly_schedule' );
function wcs_default_labels_weekly_schedule( $labels ){
  return array(
    array(
      'title' => __( 'Next Week Label', 'WeeklyClass' ),
      'id'	=> 'label_weekly_schedule_next',
      'placeholder' => __( 'Next', 'WeeklyClass' ),
    ),
    array(
      'title' => __( 'Previous Week Label', 'WeeklyClass' ),
      'id'	=> 'label_weekly_schedule_prev',
      'placeholder' => __( 'Prev', 'WeeklyClass' ),
    )
  );
}

add_filter( 'wcs_views_6_labels', 'wcs_default_labels_carousel' );
function wcs_default_labels_carousel( $labels ){
  return array(
    array(
      'title' => __( 'Next Label', 'WeeklyClass' ),
      'id'	=> 'label_carousel_next',
      'placeholder' => __( 'Next', 'WeeklyClass' ),
    ),
    array(
      'title' => __( 'Previous Label', 'WeeklyClass' ),
      'id'	=> 'label_carousel_prev',
      'placeholder' => __( 'Prev', 'WeeklyClass' ),
    )
  );
}

add_filter( 'wcs_views_6_colors', 'wcs_default_colors_carousel' );
function wcs_default_colors_carousel( $labels ){
  return array(
    array(
      'id' => 'color_carousel_item_bg',
      'title'   => __( 'Carousel Bg Color:', 'WeeklyClass' ),
      'default'   => '#ffffff'
    ),
    array(
      'id' => 'color_carousel_item_nav',
      'title'   => __( 'Carousel Nav Color:', 'WeeklyClass' )
    )
  );
}

add_filter( 'wcs_views_6_options', 'wcs_default_options_carousel' );
function wcs_default_options_carousel( $labels ){
  return array(
    array(
      'id'    => 'carousel_nav',
      'title' => __( 'Show Navigation Arrows', 'WeeklyClass' ),
      'type'  => 'switch',
      'desc'  => __( 'Checking this option will enable arrows navigation', 'WeeklyClass'),
      'value' => true
    ),
    array(
      'id'    => 'carousel_dots',
      'title' => __( 'Show Dots Arrows', 'WeeklyClass' ),
      'type'  => 'switch',
      'desc'  => __( 'Checking this option will enable dots navigation', 'WeeklyClass'),
      'value' => true
    ),
    array(
      'id'    => 'carousel_autoplay',
      'title' => __( 'Enable Autoplay', 'WeeklyClass' ),
      'type'  => 'switch',
      'desc'  => __( 'Checking this option will autoplay the carousel', 'WeeklyClass'),
      'value' => false
    ),
    array(
      'id'    => 'carousel_loop',
      'title' => __( 'Enable Loop Play', 'WeeklyClass' ),
      'type'  => 'switch',
      'desc'  => __( 'Checking this option will loop the carousel', 'WeeklyClass'),
      'value' => false
    ),
    array(
      'id'    => 'carousel_autoplay_speed',
      'title' => __( 'Autoplay Speed:', 'WeeklyClass' ),
      'type'  => 'slider',
      'value' => 5000,
      'atts'  => array(
        'min' => 500,
        'max' => 10000,
        'step' => 100,
        'suf' => 'ms'
      )
    ),
    array(
      'id'    => 'carousel_items_xl',
      'title' => __( 'X-Large devices Items:', 'WeeklyClass' ),
      'type'  => 'slider',
      'value' => 6,
      'atts'  => array(
        'min' => 1,
        'max' => 12,
        'step' => 1,
        'suf' => array(
          'one' => __( ' item', 'WeeklyClass' ),
          'many' => __( ' items', 'WeeklyClass' )
        )
      )
    ),
    array(
      'id'    => 'carousel_items_lg',
      'title' => __( 'Large devices Items:', 'WeeklyClass' ),
      'type'  => 'slider',
      'value' => 4,
      'atts'  => array(
        'min' => 1,
        'max' => 8,
        'step' => 1
      )
    ),
    array(
      'id'    => 'carousel_items_md',
      'title' => __( 'Medium devices Items:', 'WeeklyClass' ),
      'type'  => 'slider',
      'value' => 3,
      'atts'  => array(
        'min' => 1,
        'max' => 6,
        'step' => 1
      )
    ),
    array(
      'id'    => 'carousel_items_xs',
      'title' => __( 'Small devices Items:', 'WeeklyClass' ),
      'type'  => 'slider',
      'value' => 1,
      'atts'  => array(
        'min' => 1,
        'max' => 4,
        'step' => 1
      )
    ),
    array(
      'id'    => 'carousel_items_spacing',
      'title' => __( 'Distance Between Items:', 'WeeklyClass' ),
      'type'  => 'slider',
      'value' => 10,
      'atts'  => array(
        'min' => 0,
        'max' => 200,
        'step' => 1
      )
    ),
    array(
      'id'    => 'carousel_padding',
      'title' => __( 'Stage Padding:', 'WeeklyClass' ),
      'type'  => 'slider',
      'value' => 0,
      'atts'  => array(
        'min' => 0,
        'max' => 200,
        'step' => 1
      )
    )
  );
}


add_filter( 'wcs_views_7_labels', 'wcs_default_labels_grid' );
function wcs_default_labels_grid( $labels ){
  $taxonomies = WeeklyClass::get_object_taxonomies( 'class', 'objects' );
  $default_all = array();
  foreach( $taxonomies as $key => $tax ){
    $default_all[str_replace('-', '_', $key)] = array(
      'title' => sprintf( __( 'All %s Label', 'WeeklyClass' ), $tax->label ),
      'id'	=> 'label_grid_all_' . str_replace('-', '_', $key),
      'placeholder' => __( 'All', 'WeeklyClass' ),
      'default' => isset( $tax->wcs_labels['all'] ) ? $tax->wcs_labels['all'] : __( 'All', 'WeeklyClass' )
    );
  }
  return array_merge( $default_all, array(
    array(
      'title' => __( 'Any Days Label', 'WeeklyClass' ),
      'id'	=> 'label_grid_all_day_of_week',
      'placeholder' => __( 'Any', 'WeeklyClass' ),
      'default' => __( 'Any', 'WeeklyClass' )
    ),
    array(
      'title' => __( 'Any Time Label', 'WeeklyClass' ),
      'id'	=> 'label_grid_all_time_of_day',
      'placeholder' => __( 'Any', 'WeeklyClass' ),
      'default' => __( 'Any', 'WeeklyClass' )
    ),
  ) );
}

add_filter( 'wcs_views_7_colors', 'wcs_default_colors_grid' );
function wcs_default_colors_grid( $labels ){
  return array(
    array(
      'id' => 'color_grid_item_bg',
      'title'   => __( 'Grid Item Bg Color:', 'WeeklyClass' ),
      'default'   => '#ffffff'
    )
  );
}

add_filter( 'wcs_views_7_options', 'wcs_default_options_grid' );
function wcs_default_options_grid( $labels ){
  return array(
    array(
      'id'    => 'grid_items_lg',
      'title' => __( 'Large devices Items:', 'WeeklyClass' ),
      'type'  => 'slider',
      'value' => 4,
      'atts'  => array(
        'min' => 1,
        'max' => 8,
        'step' => 1
      )
    ),
    array(
      'id'    => 'grid_items_md',
      'title' => __( 'Medium devices Items:', 'WeeklyClass' ),
      'type'  => 'slider',
      'value' => 3,
      'atts'  => array(
        'min' => 1,
        'max' => 6,
        'step' => 1
      )
    ),
    array(
      'id'    => 'grid_items_xs',
      'title' => __( 'Small devices Items:', 'WeeklyClass' ),
      'type'  => 'slider',
      'value' => 1,
      'atts'  => array(
        'min' => 1,
        'max' => 4,
        'step' => 1
      )
    )
  );
}

add_filter( 'wcs_views_8_colors', 'wcs_default_colors_timetable' );
function wcs_default_colors_timetable( $labels ){
  return array(
    array(
      'id' => 'color_timeline_item_bg',
      'title'   => __( 'Timeline Item Bg Color:', 'WeeklyClass' ),
      'default'   => '#ffffff'
    )
  );
}

add_filter( 'wcs_views_9_colors', 'wcs_default_colors_monthly' );
function wcs_default_colors_monthly( $labels ){
  return array(
    array(
      'id' => 'color_monthly_event_bg',
      'title'   => __( 'Event Color:', 'WeeklyClass' ),
      'default'   => '#16a085'
    ),
    array(
      'id' => 'color_monthly_event_past',
      'title'   => __( 'Past Event Color:', 'WeeklyClass' ),
      'default'   => '#e2e2e2'
    ),
    array(
      'id' => 'color_monthly_today',
      'title'   => __( 'Today Color:', 'WeeklyClass' ),
      'default'   => '#FBF9E3'
    )
  );
}

add_filter( 'wcs_views_9_options', 'wcs_default_options_monthly' );
function wcs_default_options_monthly( $labels ){
  return array(
    array(
      'id'    => 'calendar_limit',
      'title' => __( 'Show More Link', 'WeeklyClass' ),
      'type'  => 'switch',
      'desc'  => __( 'Checking this option will add the more link', 'WeeklyClass'),
      'value' => true
    ),
    array(
      'id'    => 'calendar_sticky',
      'title' => __( 'Sticky Days Header', 'WeeklyClass' ),
      'type'  => 'switch',
      'desc'  => __( 'Checking this option will make your table days sticky', 'WeeklyClass'),
      'value' => true
    ),
    array(
      'id'    => 'calendar_weekends',
      'title' => __( 'Show Weekends', 'WeeklyClass' ),
      'type'  => 'switch',
      'desc'  => __( 'Checking this option will show weekends', 'WeeklyClass'),
      'value' => true
    )
  );
}


add_filter( 'wcs_views_10_colors', 'wcs_default_colors_countdown' );
function wcs_default_colors_countdown( $labels ){
  return array(
    array(
      'id' => 'color_countdown_bg',
      'title'   => __( 'Background Color:', 'WeeklyClass' ),
      'default'   => '#16a085'
    )
  );
}

add_filter( 'wcs_views_10_labels', 'wcs_default_labels_countdown' );
function wcs_default_labels_countdown( $labels ){
  return array(
    array(
      'title' => __( 'Years Label', 'WeeklyClass' ),
      'id'	=> 'label_countdown_years',
      'placeholder' => __( 'Year,Years', 'WeeklyClass' ),
      'default' => __( 'Year,Years', 'WeeklyClass' ),
    ),
    array(
      'title' => __( 'Months Label', 'WeeklyClass' ),
      'id'	=> 'label_countdown_months',
      'placeholder' => __( 'Month,Months', 'WeeklyClass' ),
      'default' => __( 'Month,Months', 'WeeklyClass' ),
    ),
    array(
      'title' => __( 'Days Label', 'WeeklyClass' ),
      'id'	=> 'label_countdown_days',
      'placeholder' => __( 'Day,Days', 'WeeklyClass' ),
      'default' => __( 'Day,Days', 'WeeklyClass' ),
    ),
    array(
      'title' => __( 'Hours Label', 'WeeklyClass' ),
      'id'	=> 'label_countdown_hours',
      'placeholder' => __( 'Hour,Hours', 'WeeklyClass' ),
      'default' => __( 'Hour,Hours', 'WeeklyClass' ),
    ),
    array(
      'title' => __( 'Minutes Label', 'WeeklyClass' ),
      'id'	=> 'label_countdown_minutes',
      'placeholder' => __( 'Minute,Minutes', 'WeeklyClass' ),
      'default' => __( 'Minute,Minutes', 'WeeklyClass' ),
    ),
    array(
      'title' => __( 'Seconds Label', 'WeeklyClass' ),
      'id'	=> 'label_countdown_seconds',
      'placeholder' => __( 'Second,Seconds', 'WeeklyClass' ),
      'default' => __( 'Second,Seconds', 'WeeklyClass' ),
    )
  );
}

add_filter( 'wcs_views_10_options', 'wcs_default_options_countdown' );
function wcs_default_options_countdown( $labels ){
  return array(
    array(
      'id'    => 'countdown_starting',
      'title' => __( 'Show Starting Date', 'WeeklyClass' ),
      'type'  => 'switch',
      'desc'  => __( 'Checking this option will show the starting date', 'WeeklyClass'),
      'value' => true
    ),
    array(
      'id'    => 'countdown_vertical',
      'title' => __( 'Vertical Countdown', 'WeeklyClass' ),
      'type'  => 'switch',
      'desc'  => __( 'Checking this option will make the countdown vertical', 'WeeklyClass'),
      'value' => false
    ),
    array(
      'id'    => 'countdown_image',
      'title' => __( 'Show Image', 'WeeklyClass' ),
      'type'  => 'switch',
      'desc'  => __( 'Checking this option will show the class/event image', 'WeeklyClass'),
      'value' => false
    ),
    array(
      'id'    => 'countdown_image_position',
      'title' => __( 'Image Position', 'WeeklyClass' ),
      'type'  => 'select',
      'value' => 4,
      'options' => array(
        0 => __( 'Top Left', 'WeeklyClass' ),
        1 => __( 'Top Center', 'WeeklyClass' ),
        2 => __( 'Top Right', 'WeeklyClass' ),
        3 => __( 'Middle Left', 'WeeklyClass' ),
        4 => __( 'Middle Center', 'WeeklyClass' ),
        5 => __( 'Middle Right', 'WeeklyClass' ),
        6 => __( 'Bottom Left', 'WeeklyClass' ),
        7 => __( 'Bottom Center', 'WeeklyClass' ),
        8 => __( 'Bottom Right', 'WeeklyClass' ),
      )
    ),
    array(
      'id'    => 'countdown_overlay',
      'title' => __( 'Overlay Opacity:', 'WeeklyClass' ),
      'type'  => 'slider',
      'value' => 10,
      'atts'  => array(
        'min' => 0,
        'max' => 100,
        'step' => 1,
        'suf' => '%'
      )
    )
  );
}

add_filter( 'wcs_views_11_colors', 'wcs_default_colors_cover' );
function wcs_default_colors_cover( $labels ){
  return array(
    array(
      'id' => 'color_cover_bg',
      'title'   => __( 'Overlay Color:', 'WeeklyClass' ),
      'default'   => '#ffffff'
    ),
  );
}


add_filter( 'wcs_views_11_options', 'wcs_default_options_cover' );
function wcs_default_options_cover( $labels ){
  return array(
    array(
      'id'    => 'cover_aspect',
      'title' => __( 'Cover Aspect Ratio', 'WeeklyClass' ),
      'type'  => 'select',
      'value' => 0,
      'options' => array(
        0 => __( '16:9', 'WeeklyClass' ),
        1 => __( '16:9 (vertical)', 'WeeklyClass' ),
        2 => __( '4:3', 'WeeklyClass' ),
        3 => __( '4:3 (vertical)', 'WeeklyClass' ),
        4 => __( '1:1', 'WeeklyClass' )
      )
    ),
    array(
      'id'    => 'cover_text_position',
      'title' => __( 'Text Position', 'WeeklyClass' ),
      'type'  => 'select',
      'value' => 6,
      'options' => array(
        0 => __( 'Top Left', 'WeeklyClass' ),
        1 => __( 'Top Center', 'WeeklyClass' ),
        2 => __( 'Top Right', 'WeeklyClass' ),
        3 => __( 'Middle Left', 'WeeklyClass' ),
        4 => __( 'Middle Center', 'WeeklyClass' ),
        5 => __( 'Middle Right', 'WeeklyClass' ),
        6 => __( 'Bottom Left', 'WeeklyClass' ),
        7 => __( 'Bottom Center', 'WeeklyClass' ),
        8 => __( 'Bottom Right', 'WeeklyClass' ),
      )
    ),
    array(
      'id'    => 'cover_text_align',
      'title' => __( 'Text Align', 'WeeklyClass' ),
      'type'  => 'select',
      'value' => 0,
      'options' => array(
        0 => __( 'Left', 'WeeklyClass' ),
        1 => __( 'Center', 'WeeklyClass' ),
        2 => __( 'Right', 'WeeklyClass' )
      )
    ),
    array(
      'id'    => 'cover_text_size',
      'title' => __( 'Text Size', 'WeeklyClass' ),
      'type'  => 'select',
      'value' => 1,
      'options' => array(
        0 => __( 'Small', 'WeeklyClass' ),
        1 => __( 'Normal', 'WeeklyClass' ),
        2 => __( 'Large', 'WeeklyClass' )
      )
    ),
    array(
      'id'    => 'cover_overlay_type',
      'title' => __( 'Color Overlay Layer', 'WeeklyClass' ),
      'type'  => 'select',
      'value' => 0,
      'options' => array(
        0 => __( 'Image', 'WeeklyClass' ),
        1 => __( 'Text', 'WeeklyClass' )
      )
    ),
    array(
      'id'    => 'cover_overlay',
      'title' => __( 'Overlay Opacity:', 'WeeklyClass' ),
      'type'  => 'slider',
      'value' => 10,
      'atts'  => array(
        'min' => 0,
        'max' => 100,
        'step' => 1,
        'suf' => '%'
      )
    )
  );
}



add_filter( 'wcs_views_12_options', 'wcs_default_options_monthly_calendar' );
function wcs_default_options_monthly_calendar( $labels ){
  return array(
    array(
      'id'    => 'mth_cal_agenda_position',
      'title' => __( 'Detailed View Position', 'WeeklyClass' ),
      'type'  => 'select',
      'value' => 0,
      'options' => array(
        0 => __( 'Below the calendar', 'WeeklyClass' ),
        1 => __( 'Left of calendar', 'WeeklyClass' ),
        2 => __( 'Right of calendar', 'WeeklyClass' ),
        3 => __( 'Inside the calendar', 'WeeklyClass' ),
      )
    ),
    array(
      'id'    => 'mth_cal_borders',
      'title' => __( 'Border Style', 'WeeklyClass' ),
      'type'  => 'select',
      'value' => 0,
      'options' => array(
        0 => __( 'No borders', 'WeeklyClass' ),
        1 => __( 'Horizontal borders', 'WeeklyClass' ),
        2 => __( 'Vertical borders', 'WeeklyClass' ),
        3 => __( 'All borders', 'WeeklyClass' ),
      )
    ),
    array(
      'id'    => 'mth_cal_day_format',
      'title' => __( 'Day Names Format', 'WeeklyClass' ),
      'type'  => 'select',
      'value' => "dddd",
      'options' => array(
        'dddd' => __( 'Full day name (Monday)', 'WeeklyClass' ),
        'ddd' => __( 'Small day name (Mon)', 'WeeklyClass' ),
        'd' => __( 'Tiny day name (M)', 'WeeklyClass' )
      )
    ),
    array(
      'id'    => 'mth_cal_show_weekends',
      'title' => __( 'Show Weekends', 'WeeklyClass' ),
      'type'  => 'switch',
      'value' => true
    ),
    array(
      'id'    => 'mth_cal_rows',
      'title' => __( 'Alternate Rows', 'WeeklyClass' ),
      'type'  => 'switch',
      'value' => false
    ),
    array(
      'id'    => 'mth_cal_highlight',
      'title' => __( 'Round Day Highlight', 'WeeklyClass' ),
      'type'  => 'switch',
      'value' => false
    ),
    array(
      'id'    => 'mth_cal_date_format',
      'title' => __( 'Date Format', 'WeeklyClass' ),
      'type'  => 'text',
      'value' => 'MMMM DD'
    )
  );
}

add_filter( 'wcs_views_12_colors', 'wcs_default_colors_monthly_calendar' );
function wcs_default_colors_monthly_calendar( $labels ){
  return array(
    array(
      'id' => 'color_mth_selected',
      'title'   => __( 'Selected Day:', 'WeeklyClass' ),
      'default'   => '#BD322C'
    ),
  );
}


add_filter( 'wcs_views_12_labels', 'wcs_default_labels_monthly_calendar' );
function wcs_default_labels_monthly_calendar( $labels ){
  return array(
    array(
      'title' => __( 'Next Label', 'WeeklyClass' ),
      'id'	=> 'label_mth_next',
      'placeholder' => __( 'Next', 'WeeklyClass' ),
      'default' => __( 'Next', 'WeeklyClass' )
    ),
    array(
      'title' => __( 'Previous Label', 'WeeklyClass' ),
      'id'	=> 'label_mth_prev',
      'placeholder' => __( 'Prev', 'WeeklyClass' ),
      'default' => __( 'Prev', 'WeeklyClass' )
    )
  );
}



add_filter( 'wcs_builder_views','wcs_defaults_register_views' );

function wcs_defaults_register_views( $views ){
  $views = array_merge( $views, apply_filters( 'wcs_views', array() ) );
  foreach( $views as $key=> $view ){
    $views[$key] = array(
      'value' => $view['value'],
      'title' => $view['title'],
      'icon' => $view['icon'],
      'slug' => $view['slug'],
      'single'  => isset( $view['single'] ) ? filter_var( $view['single'], FILTER_VALIDATE_BOOLEAN ) : false,
      'colors'  => apply_filters( 'wcs_views_' . $view['value'] . '_colors', isset( $view['colors'] ) ? $view['colors'] : array() ),
      'labels'  => apply_filters( 'wcs_views_' . $view['value'] . '_labels', isset( $view['labels'] ) ? $view['labels'] : array() ),
      'options' => apply_filters( 'wcs_views_' . $view['value'] . '_options', isset( $view['options'] ) ? $view['options'] : array() )

    );
  }
  return $views;
}
?>
