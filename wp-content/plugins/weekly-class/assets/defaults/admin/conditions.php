<?php
add_filter( 'wcs_builder_conditions', 'wcs_default_conditions' );
function wcs_default_conditions( $conditions ){
  $conditions['days'] = array(
    'relation' => 'AND',
    'conditions' => array(
      array( 'view', 'not_in_array', array( 3, 4, 9, 10, 11, 12 ) )
    )
  );
  $conditions['section_filters'] = array(
    'relation' => 'AND',
    'conditions' => array(
      array( 'view', 'not_in_array', array( 6, 8, 9, 10, 11 ) )
    )
  );
  $conditions['section_contents'] = array(
    'relation' => 'AND',
    'conditions' => array(
      array( 'view', 'not_in_array', array( 10, 11 ) )
    )
  );
  $conditions['section_single'] = array(
    'relation' => 'AND',
    'conditions' => array(
      array( 'view', 'in_array', array( 10, 11 ) )
    )
  );
  $conditions['label_toggle'] = array(
    'relation' => 'AND',
    'conditions' => array(
      array( 'view', 'not_in_array', array( 6, 8 ) )
    )
  );
  $conditions['show_duration'] = array(
    'relation' => 'AND',
    'conditions' => array(
      array( 'view', 'not_in_array', array( 6, 8, 10 ) )
    )
  );
  $conditions['show_time_format'] = array(
    'relation' => 'AND',
    'conditions' => array(
      array( 'view', 'not_in_array', array( 10 ) )
    )
  );
  $conditions['show_past_events'] = array(
    'relation' => 'AND',
    'conditions' => array(
      array( 'view', 'not_in_array', array( 10, 11 ) )
    )
  );
  $conditions['label_dateformat'] = array(
    'relation' => 'AND',
    'conditions' => array(
      array( 'view', 'in_array', array( 1, 2, 4, 6, 7, 8, 10, 11 ) )
    )
  );
  $conditions['starting_date'] = array(
    'relation' => 'AND',
    'conditions' => array(
      array( 'show_past_events', 'is_true'),
      array( 'view', 'not_in_array', array( 3, 4, 9, 10, 11, 12 ) )
    )
  );
  $conditions['show_ending'] = array(
    'relation' => 'AND',
    'conditions' => array(
      array( 'view', 'not_in_array', array( 10 ) )
    )
  );
  $conditions['section_modal'] = array(
    'relation' => 'AND',
    'conditions' => array(
      array( 'show_description', 'is_true')
    )
  );
  $conditions['show_modal'] = array(
    'relation' => 'AND',
    'conditions' => array(
      array( 'modal', '!=', 2 )
    )
  );
  $conditions['show_modal_ending'] = array(
    'relation' => 'AND',
    'conditions' => array(
      array( 'modal', '!=', 2 )
    )
  );
  $conditions['show_modal_duration'] = array(
    'relation' => 'AND',
    'conditions' => array(
      array( 'modal', '!=', 2 )
    )
  );
  $conditions['label_modal_dateformat'] = array(
    'relation' => 'AND',
    'conditions' => array(
      array( 'modal', '!=', 2 )
    )
  );
  $conditions['modal_wcs_type'] = array(
    'relation' => 'AND',
    'conditions' => array(
      array( 'modal', '!=', 2 )
    )
  );
  $conditions['modal_wcs_room'] = array(
    'relation' => 'AND',
    'conditions' => array(
      array( 'modal', '!=', 2 )
    )
  );
  $conditions['modal_wcs_instructor'] = array(
    'relation' => 'AND',
    'conditions' => array(
      array( 'modal', '!=', 2 )
    )
  );
  $conditions['label_filter_day'] = array(
    'relation' => 'AND',
    'conditions' => array(
      array( 'show_filter_day_of_week', 'is_true' ),
      array( 'view', 'not_in_array', array( 10 ) )
    )
  );
  $conditions['label_filter_time'] = array(
    'relation' => 'AND',
    'conditions' => array(
      array( 'show_filter_time_of_day', 'is_true' ),
      array( 'view', 'not_in_array', array( 10 ) )
    )
  );
  $conditions['label_filter_wcs_room'] = array(
    'relation' => 'AND',
    'conditions' => array(
      array( 'show_filter_wcs_room', 'is_true' ),
      array( 'view', 'not_in_array', array( 10 ) )
    )
  );
  $conditions['label_filter_wcs_type'] = array(
    'relation' => 'AND',
    'conditions' => array(
      array( 'show_filter_wcs_type', 'is_true' ),
      array( 'view', 'not_in_array', array( 10 ) )
    )
  );
  $conditions['label_filter_wcs_instructor'] = array(
    'relation' => 'AND',
    'conditions' => array(
      array( 'show_filter_wcs_instructor', 'is_true' ),
      array( 'view', 'not_in_array', array( 10 ) )
    )
  );
  $conditions['label_toggle'] = array(
    'relation' => 'OR',
    'conditions' => array(
      array( 'show_filter_wcs_type', 'is_true' ),
      array( 'show_filter_wcs_instructor', 'is_true' ),
      array( 'show_filter_wcs_room', 'is_true' ),
      array( 'show_filter_time_of_day', 'is_true' ),
      array( 'show_filter_day_of_week', 'is_true' ),
    )
  );
  $conditions['label_info'] = array(
    'relation' => 'AND',
    'conditions' => array(
      array( 'view', 'in_array', array( 0, 10, 11 ) )
    )
  );
  $conditions['label_wcs_type'] = array(
    'relation' => 'AND',
    'conditions' => array(
      array( 'show_wcs_type', 'is_true' )
    )
  );
  $conditions['label_wcs_room'] = array(
    'relation' => 'AND',
    'conditions' => array(
      array( 'show_wcs_room', 'is_true' )
    )
  );
  $conditions['label_wcs_instructor'] = array(
    'relation' => 'AND',
    'conditions' => array(
      array( 'show_wcs_instructor', 'is_true' )
    )
  );
  $conditions['zero'] = array(
    'relation' => 'AND',
    'conditions' => array(
      array( 'view', 'not_in_array', array( 10, 11 ) )
    )
  );
  $conditions['label_more'] = array(
    'relation' => 'AND',
    'conditions' => array(
      array( 'days', '!=', 0, true ),
      array( 'show_more', 'is_true' ),
      array( 'view', 'not_in_array', array( 10, 11 ) )
    )
  );
  $conditions['show_more'] = array(
    'relation' => 'AND',
    'conditions' => array(
      array( 'days', '!=', 0, true ),
      array( 'view', 'not_in_array', array( 10, 11, 12 ) )
    )
  );
  $conditions['show_title'] = array(
    'relation' => 'AND',
    'conditions' => array(
      array( 'view', 'not_in_array', array( 12 ) )
    )
  );
  $conditions['filters_position'] = array(
    'relation' => 'OR',
    'conditions' => array(
      array( 'show_filter_wcs_type', 'is_true' ),
      array( 'show_filter_wcs_instructor', 'is_true' ),
      array( 'show_filter_wcs_room', 'is_true' ),
      array( 'show_filter_time_of_day', 'is_true' ),
      array( 'show_filter_day_of_week', 'is_true' )
    )
  );
  $conditions['filters_style'] = array(
    'relation' => 'OR',
    'conditions' => array(
      array( 'show_filter_wcs_type', 'is_true' ),
      array( 'show_filter_wcs_instructor', 'is_true' ),
      array( 'show_filter_wcs_room', 'is_true' ),
      array( 'show_filter_time_of_day', 'is_true' ),
      array( 'show_filter_day_of_week', 'is_true' )
    )
  );
  $conditions['show_filters_opened'] = array(
    'relation' => 'OR',
    'conditions' => array(
      array( 'show_filter_wcs_type', 'is_true' ),
      array( 'show_filter_wcs_instructor', 'is_true' ),
      array( 'show_filter_wcs_room', 'is_true' ),
      array( 'show_filter_time_of_day', 'is_true' ),
      array( 'show_filter_day_of_week', 'is_true' )
    )
  );
  $conditions['limit'] = array(
    'relation' => 'AND',
    'conditions' => array(
      array( 'days', 'in_array', array( 0, '0' ) ),
      array( 'view', 'not_in_array', array( 3, 4, 9, 10, 11, 12 ) )
    )
  );
  $conditions['label_weekly_schedule_next'] = array(
    'relation' => 'AND',
    'conditions' => array(
      array( 'view', 'in_array', array( 3 ) ),
      array( 'show_navigation', 'is_true' )
    )
  );
  $conditions['label_weekly_schedule_prev'] = array(
    'relation' => 'AND',
    'conditions' => array(
      array( 'view', 'in_array', array( 3, '3' ) ),
      array( 'show_navigation', 'is_true' )
    )
  );
  $conditions['filters_select2_multiple'] = array(
    'relation' => 'AND',
    'conditions' => array(
      array( 'filters_select2', 'is_true' ),
    )
  );

  $conditions['mth_cal_date_format'] = array(
    'relation' => 'AND',
    'conditions' => array(
      array( 'mth_cal_agenda_position', '!=', 3 )
    )
  );
  $conditions['countdown_overlay'] = array(
    'relation' => 'AND',
    'conditions' => array(
      array( 'countdown_image', 'is_true' ),
      array( 'view', 'in_array', array( 10, '10' ) ),
    )
  );
  $conditions['countdown_image_position'] = array(
    'relation' => 'AND',
    'conditions' => array(
      array( 'countdown_image', 'is_true' ),
      array( 'view', 'in_array', array( 10, '10' ) ),
    )
  );
  $conditions['reverse_order'] = array(
    'relation' => 'AND',
    'conditions' => array(
      array( 'view', 'in_array', array( 0, 1, 2, 5, 6 ) ),
    )
  );
  return $conditions;
}
?>
