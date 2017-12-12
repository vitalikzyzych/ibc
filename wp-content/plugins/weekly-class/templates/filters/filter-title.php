<?php

$html .= ! empty( $filter_title ) ? "<span class='wcs-filters__title'>{$filter_title}</span>" : '';

if( $filter_template === 'radio' &&  isset( $default_all[$tax] ) && ! empty( $default_all[$tax] ) ){

  $value = '';
  $title = $default_all[$tax];
  $id = null;

  include( wcs_get_template_part( 'filters/filter', $filter_template ) );
}

?>
