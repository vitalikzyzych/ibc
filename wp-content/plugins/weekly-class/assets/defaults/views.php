<?php

add_filter( 'wcs_views','wcs_register_views' );

function wcs_register_views( $views ){
  $views[] = array(
    'value' => 0,
    'title' => __( 'Plain List', 'WeeklyClass' ),
    'icon'  => 'ti-layout-list-post',
    'slug'  => 'list-plain'
  );
  $views[] = array(
    'value' => 1,
    'title' => __( 'Compact List', 'WeeklyClass' ),
    'icon'  => 'ti-view-list',
    'slug'  => 'list-compact'
  );
  $views[] = array(
    'value' => 2,
    'title' => __( 'Large List', 'WeeklyClass' ),
    'icon'  => 'ti-view-list-alt',
    'slug'  => 'list-large'
  );
  $views[] = array(
    'value' => 3,
    'title' => __( 'Weekly Schedule', 'WeeklyClass' ),
    'icon'  => 'ti-calendar',
    'slug'  => 'weekly-schedule',
    'mixins' => 'wcs_timetable_weekly_mixins'
  );
  $views[] = array(
    'value' => 4,
    'title' => __( 'Weekly Tabs', 'WeeklyClass' ),
    'icon'  => 'ti-layout-tab',
    'slug'  => 'weekly-tabs',
    'mixins' => 'wcs_timetable_weekly_tabs_mixins'
  );
  $views[] = array(
    'value' => 5,
    'title' => __( 'Daily Agenda', 'WeeklyClass' ),
    'icon'  => 'ti-layout-list-thumb-alt',
    'slug' => 'daily-agenda'
  );
  $views[] = array(
    'value' => 6,
    'title' => __( 'Events Carousel', 'WeeklyClass' ),
    'slug'  => 'carousel',
    'icon'  => 'ti-layout-column3',
    'filters' => false,
    'mixins' => 'wcs_carousel_mixin',
    'deps' => 'wcs-owl'
  );
  $views[] = array(
    'value' => 7,
    'title' => __( 'Masonry Grid', 'WeeklyClass' ),
    'icon'  => 'ti-layout-grid4-alt',
    'slug'  => 'grid',
    'filters_template' => 'grid',
    'mixins' => 'wcs_timetable_isotope_mixins',
    'deps' => 'wcs-isotope'
  );
  $views[] = array(
    'value' => 8,
    'title' => __( 'Timeline', 'WeeklyClass' ),
    'icon'  => 'ti-split-h',
    'slug'  => 'timeline',
    'filters' => false,
    'mixins' => 'wcs_timetable_timeline_mixins',
    'deps' => 'wcs-isotope'
  );
  $views[] = array(
    'value' => 9,
    'title' => __( 'Monthly Schedule (beta)', 'WeeklyClass' ),
    'icon'  => 'ti-calendar',
    'slug'  => 'monthly-schedule',
    'mixins' => 'wcs_timetable_monthly_mixins',
    'deps' => 'wcs-fullcalendar'
  );
  $views[] = array(
    'value' => 10,
    'title' => __( 'Countdown', 'WeeklyClass' ),
    'icon'  => 'ti-timer',
    'slug'  => 'countdown',
    'single' => true,
    'filters' => false,
    'mixins' => 'wcs_timetable_countdown'
  );
  $views[] = array(
    'value' => 11,
    'title' => __( 'Cover', 'WeeklyClass' ),
    'icon'  => 'ti-bookmark-alt',
    'slug'  => 'cover',
    'single' => true,
    'filters' => false,
    'mixins' => 'wcs_timetable_cover'
  );
  $views[] = array(
    'value' => 12,
    'title' => __( 'Monthly Calendar', 'WeeklyClass' ),
    'icon'  => 'ti-layout-slider',
    'slug'  => 'monthly-calendar',
    'mixins' => 'wcs_mixins_monthly_calendar',
    'filters' => true,
  );

  /*
  $views[] = array(
    'value' => 12,
    'title' => __( 'Slider', 'WeeklyClass' ),
    'icon'  => 'ti-layout-slider',
    'slug'  => 'slider',
  );*/
  return $views;
}

?>
