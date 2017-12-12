<?php

add_filter( 'wcs_locale_element_ui', 'wcs_default_locale_element_ui' );

function wcs_default_locale_element_ui( $out ){
  $out['colorpicker'] = array(
    'confirm' => esc_html__( 'OK', 'WeeklyClass'),
    'clear' => esc_html__( 'Clear', 'WeeklyClass')
  );
  $out['datepicker'] = array(
    'now' => esc_html__( 'Now', 'WeeklyClass'),
    'today' => esc_html__( 'Today', 'WeeklyClass'),
    'cancel' => esc_html__( 'Cancel', 'WeeklyClass'),
    'clear' => esc_html__( 'Clear', 'WeeklyClass'),
    'confirm' => esc_html__( 'OK', 'WeeklyClass'),
    'selectDate' => esc_html__( 'Select date', 'WeeklyClass'),
    'selectTime' => esc_html__( 'Select time', 'WeeklyClass'),
    'startDate' => esc_html__( 'Start Date', 'WeeklyClass'),
    'startTime' => esc_html__( 'Start Time', 'WeeklyClass'),
    'endDate' => esc_html__( 'End Date', 'WeeklyClass'),
    'endTime' => esc_html__( 'End Time', 'WeeklyClass'),
    'year' => '',
    'month1' => esc_html__( 'Jan', 'WeeklyClass'),
    'month2' => esc_html__( 'Feb', 'WeeklyClass'),
    'month3' => esc_html__( 'Mar', 'WeeklyClass'),
    'month4' => esc_html__( 'Apr', 'WeeklyClass'),
    'month5' => esc_html__( 'May', 'WeeklyClass'),
    'month6' => esc_html__( 'Jun', 'WeeklyClass'),
    'month7' => esc_html__( 'Jul', 'WeeklyClass'),
    'month8' => esc_html__( 'Aug', 'WeeklyClass'),
    'month9' => esc_html__( 'Sep', 'WeeklyClass'),
    'month10' => esc_html__( 'Oct', 'WeeklyClass'),
    'month11' => esc_html__( 'Nov', 'WeeklyClass'),
    'month12' => esc_html__( 'Dec', 'WeeklyClass'),
    'weeks' =>  array(
      'sun' => esc_html__( 'Sun', 'WeeklyClass'),
      'mon' => esc_html__( 'Mon', 'WeeklyClass'),
      'tue' => esc_html__( 'Tue', 'WeeklyClass'),
      'wed' => esc_html__( 'Wed', 'WeeklyClass'),
      'thu' => esc_html__( 'Thu', 'WeeklyClass'),
      'fri' => esc_html__( 'Fri', 'WeeklyClass'),
      'sat' => esc_html__( 'Sat', 'WeeklyClass')
    ),
    'months' => array(
      'jan' => esc_html__( 'Jan', 'WeeklyClass'),
      'feb' => esc_html__( 'Feb', 'WeeklyClass'),
      'mar' => esc_html__( 'Mar', 'WeeklyClass'),
      'apr' => esc_html__( 'Apr', 'WeeklyClass'),
      'may' => esc_html__( 'May', 'WeeklyClass'),
      'jun' => esc_html__( 'Jun', 'WeeklyClass'),
      'jul' => esc_html__( 'Jul', 'WeeklyClass'),
      'aug' => esc_html__( 'Aug', 'WeeklyClass'),
      'sep' => esc_html__( 'Sep', 'WeeklyClass'),
      'oct' => esc_html__( 'Oct', 'WeeklyClass'),
      'nov' => esc_html__( 'Nov', 'WeeklyClass'),
      'dec' => esc_html__( 'Dec', 'WeeklyClass')
    )
  );
  $out['select'] = array(
    'loading' => esc_html__( 'Loading', 'WeeklyClass'),
    'noMatch' => esc_html__( 'No matching data', 'WeeklyClass'),
    'noData' => esc_html__( 'No data', 'WeeklyClass'),
    'placeholder' => esc_html__( 'Select', 'WeeklyClass')
  );
  $out['cascader'] = array(
    'noMatch' => esc_html__( 'No matching data', 'WeeklyClass'),
    'loading' => esc_html__( 'Loading', 'WeeklyClass'),
    'placeholder' => esc_html__( 'Select', 'WeeklyClass')
  );
  $out['pagination'] = array(
   'goto' => esc_html__( 'Go to', 'WeeklyClass'),
   'pagesize' => esc_html__( '/page', 'WeeklyClass'),
   'total' => esc_html__( 'Total {total}', 'WeeklyClass'),
   'pageClassifier' => ''
 );
 $out['messagebox'] = array(
    'title' => esc_html__( 'Message', 'WeeklyClass'),
    'confirm' => esc_html__( 'OK', 'WeeklyClass'),
    'cancel' => esc_html__( 'Cancel', 'WeeklyClass'),
    'error' => esc_html__( 'Illegal input', 'WeeklyClass')
  );
  $out['upload'] = array(
    'deleteTip' => esc_html__( 'press delete to remove', 'WeeklyClass'),
    'delete' => esc_html__( 'Delete', 'WeeklyClass'),
    'preview' => esc_html__( 'Preview', 'WeeklyClass'),
    'continue' => esc_html__( 'Continue', 'WeeklyClass')
  );
  $out['table'] = array(
    'emptyText' => esc_html__( 'No Data', 'WeeklyClass'),
    'confirmFilter' => esc_html__( 'Confirm', 'WeeklyClass'),
    'resetFilter' => esc_html__( 'Reset', 'WeeklyClass'),
    'clearFilter' => esc_html__( 'All', 'WeeklyClass'),
    'sumText' => esc_html__( 'Sum', 'WeeklyClass')
  );
  $out['tree'] = array(
   'emptyText' => esc_html__( 'No Data', 'WeeklyClass')
 );
 $out['transfer'] = array(
    'noMatch' => esc_html__( 'No matching data', 'WeeklyClass'),
    'noData' => esc_html__( 'No data', 'WeeklyClass'),
    'titles' => [ esc_html__( 'List 1', 'WeeklyClass'), esc_html__('List 2', 'WeeklyClass')],
    'filterPlaceholder' => esc_html__( 'Enter keyword', 'WeeklyClass'),
    'noCheckedFormat' => esc_html__( '{total} items', 'WeeklyClass'),
    'hasCheckedFormat' => esc_html__( '{checked}/{total} checked', 'WeeklyClass')
  );
  return $out;
}
?>
