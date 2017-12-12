<?php
$img = ! is_null( $image ) && ! empty( $image  ) ? wp_get_attachment_image( $image, 'large' ) : '';
$person_name = esc_attr( $person_name );
$person_title = ! is_null( $person_title ) && ! empty( $person_title  ) ? "<small>" . esc_attr( $person_title ) . "</small>" : '';
$person_position = ! is_null( $person_position ) && ! empty( $person_position  ) ? "<span>" . esc_attr( $person_position ) . "</span>" : '';

$html = "<div class='xtd-person {$el_css}'>$img<div class='xtd-person__info'><div class='xtd-person__title'>{$person_title}{$person_name}{$person_position}</div><div class='xtd-person__content'>" . do_shortcode( $content ) . "</div></div></div>";


?>
