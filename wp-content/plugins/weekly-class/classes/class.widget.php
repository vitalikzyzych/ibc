<?php

class WeeklyClassWidget extends WP_Widget {

  function __construct() {

		// Instantiate the parent object
		parent::__construct(
      'wcs-events-schedule',
      __( 'Events Schedule', 'WeeklyClass' ),
      array(
				'classname' => 'wcs-events-schedule',
				'description' => __('This widget displays the a events schedule', 'WeeklyClass'),
        'idbase' => 'wcs-events-schedule'
      )
    );
	}

	function widget( $args, $instance ) {

		extract( $args );

    echo isset( $before_widget ) && ! empty( $before_widget ) ? $before_widget : '';

    $title_temp = apply_filters('widget_title', $instance['title'] );

    if( ! empty( $title_temp ) ) echo $before_title . apply_filters('widget_title', $instance['title'] ) . $after_title;

    $id = isset( $instance['id'] ) ? $instance['id'] : false;

    if( $id !== false ) echo do_shortcode("[wcs_timetable id=$id]");

    echo $after_widget;

	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
    $instance['title'] = strip_tags( $new_instance['title'] );
    $instance['id'] = strip_tags( $new_instance['id'] );
    return $instance;
	}

	function form( $instance ) {

    $defaults = array(
       'title' => null,
       'id'   => null,
   );

   $instance = wp_parse_args( (array) $instance, $defaults);

   $count = intval( get_option( '__wcs_schedule_count', 0 ) );
   $schedules = array();

   $views = apply_filters( 'wcs_views', array() );

   if( intval( $count ) !== 0 ){
     while( $count > 0 ) :

       $data = get_option( "__wcs_schedule_$count" );
       $data = maybe_unserialize( $data );

       if( $data !== false && ! empty( $data ) && isset( $data['title'] ) && isset( $data['view'] ) ) :
         $schedules[" {$count} - {$data['title']} ({$views[$data['view']]['title']})"] = $count;
       endif;

       $count--;

     endwhile;

   } else {
     $schedules = array( __( 'No Schedules Created', 'WeeklyClass' ) );
   }

   ?>
   <div class="widget-content">
      <p>
          <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Widget Title:', 'SIMPLEWEATHER'); ?></label>
          <input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" />
      </p>
      <p>
          <label for="<?php echo $this->get_field_id( 'id' ); ?>"><?php _e('Schedule:', 'SIMPLEWEATHER'); ?></label>
          <select class="widefat" id="<?php echo $this->get_field_id( 'id' ); ?>" name="<?php echo $this->get_field_name( 'id' ); ?>">
            <option <?php selected( $instance['id'], null ); ?> value=""><?php _e( 'Choose Schedule', 'WeeklyClass' ) ?></option>
            <?php foreach( $schedules as $schedule => $val ) : ?>
              <option <?php selected( $instance['id'], $val ); ?> value="<?php echo $val ?>"><?php echo $schedule ?></option>
            <?php endforeach; ?>
          </select>
      </p>
    </div>
   <?php

	}

}

function wcs_events_register_widgets() {
	register_widget( 'WeeklyClassWidget' );
}

add_action( 'widgets_init', 'wcs_events_register_widgets' );

?>
