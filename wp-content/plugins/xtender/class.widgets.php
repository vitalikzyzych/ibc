<?php

class CurlyWidgets{

  function __construct(){

    add_action( 'in_widget_form', array( $this, 'footer_widget_columns_form' ), 5, 3 );
    add_filter( 'widget_update_callback', array( $this, 'footer_widget_columns_cb' ), 5, 2 );
		add_filter( 'widget_display_callback', array( $this, 'footer_widget_columns_cb_display' ), 5, 3 );

  }


  function footer_widget_columns_form($widget_instance, $return, $instance) {

      $current_widgets = get_option( 'sidebars_widgets' );
      $current_sidebar_footer = isset( $current_widgets["footer_widget_area"] ) ? array_search( "{$widget_instance->id}", $current_widgets["footer_widget_area"], true ) : false;

			if( $current_sidebar_footer === false  )
				return;

			$default             = isset( $instance['curly-size'] ) ? $instance['curly-size'] : '1/4';
			$default_offset      = isset( $instance['curly-offset'] ) ? $instance['curly-offset'] : '1';
      $default_text        = isset( $instance['curly-text'] ) ? $instance['curly-text'] : '0';
      $default_visibility  = isset( $instance['curly-visibility'] ) ? $instance['curly-visibility'] : '0';

      $default_xl             = isset( $instance['curly-size-xl'] ) ? $instance['curly-size-xl'] : '1';
			$default_offset_xl      = isset( $instance['curly-offset-xl'] ) ? $instance['curly-offset-xl'] : '1';
      $default_text_xl        = isset( $instance['curly-text-xl'] ) ? $instance['curly-text-xl'] : '0';
      $default_visibility_xl  = isset( $instance['curly-visibility-xl'] ) ? $instance['curly-visibility-xl'] : '0';

      $default_lg             = isset( $instance['curly-size-lg'] ) ? $instance['curly-size-lg'] : '1';
			$default_offset_lg      = isset( $instance['curly-offset-lg'] ) ? $instance['curly-offset-lg'] : '1';
      $default_text_lg        = isset( $instance['curly-text-lg'] ) ? $instance['curly-text-lg'] : '0';
      $default_visibility_lg  = isset( $instance['curly-visibility-lg'] ) ? $instance['curly-visibility-lg'] : '0';

      $default_sm             = isset( $instance['curly-size-sm'] ) ? $instance['curly-size-sm'] : '1';
			$default_offset_sm      = isset( $instance['curly-offset-sm'] ) ? $instance['curly-offset-sm'] : '1';
      $default_text_sm        = isset( $instance['curly-text-sm'] ) ? $instance['curly-text-sm'] : '0';
      $default_visibility_sm  = isset( $instance['curly-visibility-sm'] ) ? $instance['curly-visibility-sm'] : '0';

      $default_xs             = isset( $instance['curly-size-xs'] ) ? $instance['curly-size-xs'] : '1/1';
			$default_offset_xs      = isset( $instance['curly-offset-xs'] ) ? $instance['curly-offset-xs'] : '0';
      $default_text_xs        = isset( $instance['curly-text-xs'] ) ? $instance['curly-text-xs'] : '0';
      $default_visibility_xs  = isset( $instance['curly-visibility-xs'] ) ? $instance['curly-visibility-xs'] : '0';

			?>
      <p><strong><?php _e( 'Widget Layout', 'xtender' ) ?></strong></p>

      <div class="xtender-accordion">


        <a href="#" class="xtender-toggler" data-swap-target="#tab_content_<?php echo $widget_instance->id ?>_xl" data-swap-group="group_<?php echo $widget_instance->id ?>"><?php _e( 'Extra Large Devices', 'xtender' ); ?></a>
        <div id="tab_content_<?php echo $widget_instance->id ?>_xl">
          <p>
    				<label for="<?php echo $widget_instance->get_field_id('curly-size-xl'); ?>"><?php _e( 'Widget Grid Size:', 'xtender' ) ?></label>
    				<select class="widefat" id="<?php echo $widget_instance->get_field_id('curly-size-xl'); ?>" name="<?php echo $widget_instance->get_field_name('curly-size-xl'); ?>">
              <option value="1" <?php selected( '1', $default_xl, true ) ?>><?php _e( 'Inherit from smaller', 'xtender' ) ?></option>
    					<option value="1/12" <?php selected( '1/12', $default_xl, true ) ?>><?php _e( '1 column - 1/12', 'xtender' ) ?></option>
    					<option value="1/6" <?php selected( '1/6', $default_xl, true ) ?>><?php _e( '2 columns - 1/6', 'xtender' ) ?></option>
    					<option value="1/4" <?php selected( '1/4', $default_xl, true ) ?>><?php _e( '3 columns - 1/4', 'xtender' ) ?></option>
    					<option value="1/3" <?php selected( '1/3', $default_xl, true ) ?>><?php _e( '4 columns - 1/3', 'xtender' ) ?></option>
    					<option value="5/12" <?php selected( '5/12', $default_xl, true ) ?>><?php _e( '5 columns - 5/12', 'xtender' ) ?></option>
    					<option value="1/2" <?php selected( '1/2', $default_xl, true ) ?>><?php _e( '6 columns - 1/2', 'xtender' ) ?></option>
    					<option value="2/3" <?php selected( '2/3', $default_xl, true ) ?>><?php _e( '8 columns - 2/3', 'xtender' ) ?></option>
    					<option value="3/4" <?php selected( '3/4', $default_xl, true ) ?>><?php _e( '9 columns - 3/4', 'xtender' ) ?></option>
    					<option value="5/6" <?php selected( '5/6', $default_xl, true ) ?>><?php _e( '10 columnns - 5/6', 'xtender' ) ?></option>
    					<option value="11/12" <?php selected( '11/12', $default_xl, true ) ?>><?php _e( '11 columns - 11/12', 'xtender' ) ?></option>
    					<option value="1/1" <?php selected( '1/1', $default_xl, true ) ?>><?php _e( '12 columns - 1/1', 'xtender' ) ?></option>

    				</select>
    			</p>
          <p>
    				<label for="<?php echo $widget_instance->get_field_id('curly-offset-xl'); ?>"><?php _e( 'Widget Grid Offset:', 'xtender' ) ?></label>
    				<select class="widefat" id="<?php echo $widget_instance->get_field_id('curly-offset-xl'); ?>" name="<?php echo $widget_instance->get_field_name('curly-offset-xl'); ?>">
              <option value="1" <?php selected( '1', $default_offset_xl, true ) ?>><?php _e( 'Inherit from smaller', 'xtender' ) ?></option>
              <option value="0" <?php selected( '0', $default_offset_xl, true ) ?>><?php _e( 'No Offset', 'xtender' ) ?></option>
    					<option value="1/12" <?php selected( '1/12', $default_offset_xl, true ) ?>><?php _e( '1 column - 1/12', 'xtender' ) ?></option>
    					<option value="1/6" <?php selected( '1/6', $default_offset_xl, true ) ?>><?php _e( '2 columns - 1/6', 'xtender' ) ?></option>
    					<option value="1/4" <?php selected( '1/4', $default_offset_xl, true ) ?>><?php _e( '3 columns - 1/4', 'xtender' ) ?></option>
    					<option value="1/3" <?php selected( '1/3', $default_offset_xl, true ) ?>><?php _e( '4 columns - 1/3', 'xtender' ) ?></option>
    					<option value="5/12" <?php selected( '5/12', $default_offset_xl, true ) ?>><?php _e( '5 columns - 5/12', 'xtender' ) ?></option>
    					<option value="1/2" <?php selected( '1/2', $default_offset_xl, true ) ?>><?php _e( '6 columns - 1/2', 'xtender' ) ?></option>
    					<option value="2/3" <?php selected( '2/3', $default_offset_xl, true ) ?>><?php _e( '8 columns - 2/3', 'xtender' ) ?></option>
    					<option value="3/4" <?php selected( '3/4', $default_offset_xl, true ) ?>><?php _e( '9 columns - 3/4', 'xtender' ) ?></option>
    					<option value="5/6" <?php selected( '5/6', $default_offset_xl, true ) ?>><?php _e( '10 columnns - 5/6', 'xtender' ) ?></option>
    					<option value="11/12" <?php selected( '11/12', $default_offset_xl, true ) ?>><?php _e( '11 columns - 11/12', 'xtender' ) ?></option>
    					<option value="1/1" <?php selected( '1/1', $default_offset_xl, true ) ?>><?php _e( '12 columns - 1/1', 'xtender' ) ?></option>
    				</select>
    			</p>
          <p>
    				<label for="<?php echo $widget_instance->get_field_id('curly-text-xl'); ?>"><?php _e( 'Widget Text Align:', 'xtender' ) ?></label>
    				<select class="widefat" id="<?php echo $widget_instance->get_field_id('curly-text-xl'); ?>" name="<?php echo $widget_instance->get_field_name('curly-text-xl'); ?>">
    					<option value="0" <?php selected( '0', $default_text_xl, true ) ?>><?php _e( 'Inherit from smaller', 'xtender' ) ?></option>
              <option value="1" <?php selected( '1', $default_text_xl, true ) ?>><?php _e( 'Left', 'xtender' ) ?></option>
              <option value="2" <?php selected( '2', $default_text_xl, true ) ?>><?php _e( 'Center', 'xtender' ) ?></option>
              <option value="3" <?php selected( '3', $default_text_xl, true ) ?>><?php _e( 'Right', 'xtender' ) ?></option>
    				</select>
    			</p>
          <p>
    				<label for="<?php echo $widget_instance->get_field_id('curly-visibility-xl'); ?>"><?php _e( 'Widget Visibility:', 'xtender' ) ?></label>
    				<select class="widefat" id="<?php echo $widget_instance->get_field_id('curly-visibility-xl'); ?>" name="<?php echo $widget_instance->get_field_name('curly-visibility-xl'); ?>">
    					<option value="0" <?php selected( '0', $default_visibility_xl, true ) ?>></option>
              <option value="1" <?php selected( '1', $default_visibility_xl, true ) ?>><?php _e( 'Hidden Upper', 'xtender' ) ?></option>
              <option value="2" <?php selected( '2', $default_visibility_xl, true ) ?>><?php _e( 'Hidden Down', 'xtender' ) ?></option>
    				</select>
    			</p>
        </div>


        <a href="#" class="xtender-toggler" data-swap-target="#tab_content_<?php echo $widget_instance->id ?>_lg" data-swap-group="group_<?php echo $widget_instance->id ?>"><?php _e( 'Large Devices', 'xtender' ); ?></a>
        <div id="tab_content_<?php echo $widget_instance->id ?>_lg">
            <p>
      				<label for="<?php echo $widget_instance->get_field_id('curly-size-lg'); ?>"><?php _e( 'Widget Grid Size:', 'xtender' ) ?></label>
      				<select class="widefat" id="<?php echo $widget_instance->get_field_id('curly-size-lg'); ?>" name="<?php echo $widget_instance->get_field_name('curly-size-lg'); ?>">
                <option value="1" <?php selected( '1', $default_lg, true ) ?>><?php _e( 'Inherit from smaller', 'xtender' ) ?></option>
      					<option value="1/12" <?php selected( '1/12', $default_lg, true ) ?>><?php _e( '1 column - 1/12', 'xtender' ) ?></option>
      					<option value="1/6" <?php selected( '1/6', $default_lg, true ) ?>><?php _e( '2 columns - 1/6', 'xtender' ) ?></option>
      					<option value="1/4" <?php selected( '1/4', $default_lg, true ) ?>><?php _e( '3 columns - 1/4', 'xtender' ) ?></option>
      					<option value="1/3" <?php selected( '1/3', $default_lg, true ) ?>><?php _e( '4 columns - 1/3', 'xtender' ) ?></option>
      					<option value="5/12" <?php selected( '5/12', $default_lg, true ) ?>><?php _e( '5 columns - 5/12', 'xtender' ) ?></option>
      					<option value="1/2" <?php selected( '1/2', $default_lg, true ) ?>><?php _e( '6 columns - 1/2', 'xtender' ) ?></option>
      					<option value="2/3" <?php selected( '2/3', $default_lg, true ) ?>><?php _e( '8 columns - 2/3', 'xtender' ) ?></option>
      					<option value="3/4" <?php selected( '3/4', $default_lg, true ) ?>><?php _e( '9 columns - 3/4', 'xtender' ) ?></option>
      					<option value="5/6" <?php selected( '5/6', $default_lg, true ) ?>><?php _e( '10 columnns - 5/6', 'xtender' ) ?></option>
      					<option value="11/12" <?php selected( '11/12', $default_lg, true ) ?>><?php _e( '11 columns - 11/12', 'xtender' ) ?></option>
      					<option value="1/1" <?php selected( '1/1', $default_lg, true ) ?>><?php _e( '12 columns - 1/1', 'xtender' ) ?></option>

      				</select>
      			</p>
            <p>
      				<label for="<?php echo $widget_instance->get_field_id('curly-offset-lg'); ?>"><?php _e( 'Widget Grid Offset:', 'xtender' ) ?></label>
      				<select class="widefat" id="<?php echo $widget_instance->get_field_id('curly-offset-lg'); ?>" name="<?php echo $widget_instance->get_field_name('curly-offset-lg'); ?>">
                <option value="1" <?php selected( '1', $default_offset_lg, true ) ?>><?php _e( 'Inherit from smaller', 'xtender' ) ?></option>
                <option value="0" <?php selected( '0', $default_offset_lg, true ) ?>><?php _e( 'No Offset', 'xtender' ) ?></option>
      					<option value="1/12" <?php selected( '1/12', $default_offset_lg, true ) ?>><?php _e( '1 column - 1/12', 'xtender' ) ?></option>
      					<option value="1/6" <?php selected( '1/6', $default_offset_lg, true ) ?>><?php _e( '2 columns - 1/6', 'xtender' ) ?></option>
      					<option value="1/4" <?php selected( '1/4', $default_offset_lg, true ) ?>><?php _e( '3 columns - 1/4', 'xtender' ) ?></option>
      					<option value="1/3" <?php selected( '1/3', $default_offset_lg, true ) ?>><?php _e( '4 columns - 1/3', 'xtender' ) ?></option>
      					<option value="5/12" <?php selected( '5/12', $default_offset_lg, true ) ?>><?php _e( '5 columns - 5/12', 'xtender' ) ?></option>
      					<option value="1/2" <?php selected( '1/2', $default_offset, true ) ?>><?php _e( '6 columns - 1/2', 'xtender' ) ?></option>
      					<option value="2/3" <?php selected( '2/3', $default_offset_lg, true ) ?>><?php _e( '8 columns - 2/3', 'xtender' ) ?></option>
      					<option value="3/4" <?php selected( '3/4', $default_offset_lg, true ) ?>><?php _e( '9 columns - 3/4', 'xtender' ) ?></option>
      					<option value="5/6" <?php selected( '5/6', $default_offset_lg, true ) ?>><?php _e( '10 columnns - 5/6', 'xtender' ) ?></option>
      					<option value="11/12" <?php selected( '11/12', $default_offset_lg, true ) ?>><?php _e( '11 columns - 11/12', 'xtender' ) ?></option>
      					<option value="1/1" <?php selected( '1/1', $default_offset_lg, true ) ?>><?php _e( '12 columns - 1/1', 'xtender' ) ?></option>
      				</select>
      			</p>
            <p>
      				<label for="<?php echo $widget_instance->get_field_id('curly-text-lg'); ?>"><?php _e( 'Widget Text Align:', 'xtender' ) ?></label>
      				<select class="widefat" id="<?php echo $widget_instance->get_field_id('curly-text-lg'); ?>" name="<?php echo $widget_instance->get_field_name('curly-text-lg'); ?>">
      					<option value="0" <?php selected( '0', $default_text_lg, true ) ?>><?php _e( 'Inherit from smaller', 'xtender' ) ?></option>
                <option value="1" <?php selected( '1', $default_text_lg, true ) ?>><?php _e( 'Left', 'xtender' ) ?></option>
                <option value="2" <?php selected( '2', $default_text_lg, true ) ?>><?php _e( 'Center', 'xtender' ) ?></option>
                <option value="3" <?php selected( '3', $default_text_lg, true ) ?>><?php _e( 'Right', 'xtender' ) ?></option>
      				</select>
      			</p>
            <p>
      				<label for="<?php echo $widget_instance->get_field_id('curly-visibility-lg'); ?>"><?php _e( 'Widget Visibility:', 'xtender' ) ?></label>
      				<select class="widefat" id="<?php echo $widget_instance->get_field_id('curly-visibility-lg'); ?>" name="<?php echo $widget_instance->get_field_name('curly-visibility-lg'); ?>">
      					<option value="0" <?php selected( '0', $default_visibility_lg, true ) ?>></option>
                <option value="1" <?php selected( '1', $default_visibility_lg, true ) ?>><?php _e( 'Hidden Upper', 'xtender' ) ?></option>
                <option value="2" <?php selected( '2', $default_visibility_lg, true ) ?>><?php _e( 'Hidden Down', 'xtender' ) ?></option>
      				</select>
      			</p>
          </div>




          <a href="#" class="xtender-toggler" data-swap-target="#tab_content_<?php echo $widget_instance->id ?>_md" data-swap-group="group_<?php echo $widget_instance->id ?>"><?php _e( 'Medium Devices', 'xtender' ); ?></a>
          <div id="tab_content_<?php echo $widget_instance->id ?>_md">
            <p>
      				<label for="<?php echo $widget_instance->get_field_id('curly-size'); ?>"><?php _e( 'Widget Grid Size:', 'xtender' ) ?></label>
      				<select class="widefat" id="<?php echo $widget_instance->get_field_id('curly-size'); ?>" name="<?php echo $widget_instance->get_field_name('curly-size'); ?>">
                <option value="1" <?php selected( '1', $default, true ) ?>><?php _e( 'Inherit from smaller', 'xtender' ) ?></option>
      					<option value="1/12" <?php selected( '1/12', $default, true ) ?>><?php _e( '1 column - 1/12', 'xtender' ) ?></option>
      					<option value="1/6" <?php selected( '1/6', $default, true ) ?>><?php _e( '2 columns - 1/6', 'xtender' ) ?></option>
      					<option value="1/4" <?php selected( '1/4', $default, true ) ?>><?php _e( '3 columns - 1/4', 'xtender' ) ?></option>
      					<option value="1/3" <?php selected( '1/3', $default, true ) ?>><?php _e( '4 columns - 1/3', 'xtender' ) ?></option>
      					<option value="5/12" <?php selected( '5/12', $default, true ) ?>><?php _e( '5 columns - 5/12', 'xtender' ) ?></option>
      					<option value="1/2" <?php selected( '1/2', $default, true ) ?>><?php _e( '6 columns - 1/2', 'xtender' ) ?></option>
      					<option value="2/3" <?php selected( '2/3', $default, true ) ?>><?php _e( '8 columns - 2/3', 'xtender' ) ?></option>
      					<option value="3/4" <?php selected( '3/4', $default, true ) ?>><?php _e( '9 columns - 3/4', 'xtender' ) ?></option>
      					<option value="5/6" <?php selected( '5/6', $default, true ) ?>><?php _e( '10 columnns - 5/6', 'xtender' ) ?></option>
      					<option value="11/12" <?php selected( '11/12', $default, true ) ?>><?php _e( '11 columns - 11/12', 'xtender' ) ?></option>
      					<option value="1/1" <?php selected( '1/1', $default, true ) ?>><?php _e( '12 columns - 1/1', 'xtender' ) ?></option>

      				</select>
      			</p>
            <p>
      				<label for="<?php echo $widget_instance->get_field_id('curly-offset'); ?>"><?php _e( 'Widget Grid Offset:', 'xtender' ) ?></label>
      				<select class="widefat" id="<?php echo $widget_instance->get_field_id('curly-offset'); ?>" name="<?php echo $widget_instance->get_field_name('curly-offset'); ?>">
                <option value="1" <?php selected( '1', $default_offset, true ) ?>><?php _e( 'Inherit from smaller', 'xtender' ) ?></option>
                <option value="0" <?php selected( '0', $default_offset, true ) ?>><?php _e( 'No Offset', 'xtender' ) ?></option>
      					<option value="1/12" <?php selected( '1/12', $default_offset, true ) ?>><?php _e( '1 column - 1/12', 'xtender' ) ?></option>
      					<option value="1/6" <?php selected( '1/6', $default_offset, true ) ?>><?php _e( '2 columns - 1/6', 'xtender' ) ?></option>
      					<option value="1/4" <?php selected( '1/4', $default_offset, true ) ?>><?php _e( '3 columns - 1/4', 'xtender' ) ?></option>
      					<option value="1/3" <?php selected( '1/3', $default_offset, true ) ?>><?php _e( '4 columns - 1/3', 'xtender' ) ?></option>
      					<option value="5/12" <?php selected( '5/12', $default_offset, true ) ?>><?php _e( '5 columns - 5/12', 'xtender' ) ?></option>
      					<option value="1/2" <?php selected( '1/2', $default_offset, true ) ?>><?php _e( '6 columns - 1/2', 'xtender' ) ?></option>
      					<option value="2/3" <?php selected( '2/3', $default_offset, true ) ?>><?php _e( '8 columns - 2/3', 'xtender' ) ?></option>
      					<option value="3/4" <?php selected( '3/4', $default_offset, true ) ?>><?php _e( '9 columns - 3/4', 'xtender' ) ?></option>
      					<option value="5/6" <?php selected( '5/6', $default_offset, true ) ?>><?php _e( '10 columnns - 5/6', 'xtender' ) ?></option>
      					<option value="11/12" <?php selected( '11/12', $default_offset, true ) ?>><?php _e( '11 columns - 11/12', 'xtender' ) ?></option>
      					<option value="1/1" <?php selected( '1/1', $default_offset, true ) ?>><?php _e( '12 columns - 1/1', 'xtender' ) ?></option>
      				</select>
      			</p>
            <p>
      				<label for="<?php echo $widget_instance->get_field_id('curly-text'); ?>"><?php _e( 'Widget Text Align:', 'xtender' ) ?></label>
      				<select class="widefat" id="<?php echo $widget_instance->get_field_id('curly-text'); ?>" name="<?php echo $widget_instance->get_field_name('curly-text'); ?>">
      					<option value="0" <?php selected( '0', $default_text, true ) ?>><?php _e( 'Inherit from smaller', 'xtender' ) ?></option>
                <option value="1" <?php selected( '1', $default_text, true ) ?>><?php _e( 'Left', 'xtender' ) ?></option>
                <option value="2" <?php selected( '2', $default_text, true ) ?>><?php _e( 'Center', 'xtender' ) ?></option>
                <option value="3" <?php selected( '3', $default_text, true ) ?>><?php _e( 'Right', 'xtender' ) ?></option>
      				</select>
      			</p>
            <p>
      				<label for="<?php echo $widget_instance->get_field_id('curly-visibility'); ?>"><?php _e( 'Widget Visibility:', 'xtender' ) ?></label>
      				<select class="widefat" id="<?php echo $widget_instance->get_field_id('curly-visibility'); ?>" name="<?php echo $widget_instance->get_field_name('curly-visibility'); ?>">
      					<option value="0" <?php selected( '0', $default_visibility, true ) ?>></option>
                <option value="1" <?php selected( '1', $default_visibility, true ) ?>><?php _e( 'Hidden Upper', 'xtender' ) ?></option>
                <option value="2" <?php selected( '2', $default_visibility, true ) ?>><?php _e( 'Hidden Down', 'xtender' ) ?></option>
      				</select>
      			</p>
          </div>



          <a href="#" class="xtender-toggler" data-swap-target="#tab_content_<?php echo $widget_instance->id ?>_sm" data-swap-group="group_<?php echo $widget_instance->id ?>"><?php _e( 'Small Devices', 'xtender' ); ?></a>
          <div id="tab_content_<?php echo $widget_instance->id ?>_sm">
            <p>
      				<label for="<?php echo $widget_instance->get_field_id('curly-size-sm'); ?>"><?php _e( 'Widget Grid Size:', 'xtender' ) ?></label>
      				<select class="widefat" id="<?php echo $widget_instance->get_field_id('curly-size-sm'); ?>" name="<?php echo $widget_instance->get_field_name('curly-size-sm'); ?>">
                <option value="1" <?php selected( '1', $default_sm, true ) ?>><?php _e( 'Inherit from smaller', 'xtender' ) ?></option>
      					<option value="1/12" <?php selected( '1/12', $default_sm, true ) ?>><?php _e( '1 column - 1/12', 'xtender' ) ?></option>
      					<option value="1/6" <?php selected( '1/6', $default_sm, true ) ?>><?php _e( '2 columns - 1/6', 'xtender' ) ?></option>
      					<option value="1/4" <?php selected( '1/4', $default_sm, true ) ?>><?php _e( '3 columns - 1/4', 'xtender' ) ?></option>
      					<option value="1/3" <?php selected( '1/3', $default_sm, true ) ?>><?php _e( '4 columns - 1/3', 'xtender' ) ?></option>
      					<option value="5/12" <?php selected( '5/12', $default_sm, true ) ?>><?php _e( '5 columns - 5/12', 'xtender' ) ?></option>
      					<option value="1/2" <?php selected( '1/2', $default_sm, true ) ?>><?php _e( '6 columns - 1/2', 'xtender' ) ?></option>
      					<option value="2/3" <?php selected( '2/3', $default_sm, true ) ?>><?php _e( '8 columns - 2/3', 'xtender' ) ?></option>
      					<option value="3/4" <?php selected( '3/4', $default_sm, true ) ?>><?php _e( '9 columns - 3/4', 'xtender' ) ?></option>
      					<option value="5/6" <?php selected( '5/6', $default_sm, true ) ?>><?php _e( '10 columnns - 5/6', 'xtender' ) ?></option>
      					<option value="11/12" <?php selected( '11/12', $default_sm, true ) ?>><?php _e( '11 columns - 11/12', 'xtender' ) ?></option>
      					<option value="1/1" <?php selected( '1/1', $default_sm, true ) ?>><?php _e( '12 columns - 1/1', 'xtender' ) ?></option>

      				</select>
      			</p>
            <p>
      				<label for="<?php echo $widget_instance->get_field_id('curly-offset-sm'); ?>"><?php _e( 'Widget Grid Offset:', 'xtender' ) ?></label>
      				<select class="widefat" id="<?php echo $widget_instance->get_field_id('curly-offset-sm'); ?>" name="<?php echo $widget_instance->get_field_name('curly-offset-sm'); ?>">
                <option value="1" <?php selected( '1', $default_offset_sm, true ) ?>><?php _e( 'Inherit from smaller', 'xtender' ) ?></option>
                <option value="0" <?php selected( '0', $default_offset_sm, true ) ?>><?php _e( 'No Offset', 'xtender' ) ?></option>
      					<option value="1/12" <?php selected( '1/12', $default_offset_sm, true ) ?>><?php _e( '1 column - 1/12', 'xtender' ) ?></option>
      					<option value="1/6" <?php selected( '1/6', $default_offset_sm, true ) ?>><?php _e( '2 columns - 1/6', 'xtender' ) ?></option>
      					<option value="1/4" <?php selected( '1/4', $default_offset_sm, true ) ?>><?php _e( '3 columns - 1/4', 'xtender' ) ?></option>
      					<option value="1/3" <?php selected( '1/3', $default_offset_sm, true ) ?>><?php _e( '4 columns - 1/3', 'xtender' ) ?></option>
      					<option value="5/12" <?php selected( '5/12', $default_offset_sm, true ) ?>><?php _e( '5 columns - 5/12', 'xtender' ) ?></option>
      					<option value="1/2" <?php selected( '1/2', $default_offset_sm, true ) ?>><?php _e( '6 columns - 1/2', 'xtender' ) ?></option>
      					<option value="2/3" <?php selected( '2/3', $default_offset_sm, true ) ?>><?php _e( '8 columns - 2/3', 'xtender' ) ?></option>
      					<option value="3/4" <?php selected( '3/4', $default_offset_sm, true ) ?>><?php _e( '9 columns - 3/4', 'xtender' ) ?></option>
      					<option value="5/6" <?php selected( '5/6', $default_offset_sm, true ) ?>><?php _e( '10 columnns - 5/6', 'xtender' ) ?></option>
      					<option value="11/12" <?php selected( '11/12', $default_offset_sm, true ) ?>><?php _e( '11 columns - 11/12', 'xtender' ) ?></option>
      					<option value="1/1" <?php selected( '1/1', $default_offset_sm, true ) ?>><?php _e( '12 columns - 1/1', 'xtender' ) ?></option>
      				</select>
      			</p>
            <p>
      				<label for="<?php echo $widget_instance->get_field_id('curly-text-sm'); ?>"><?php _e( 'Widget Text Align:', 'xtender' ) ?></label>
      				<select class="widefat" id="<?php echo $widget_instance->get_field_id('curly-text-sm'); ?>" name="<?php echo $widget_instance->get_field_name('curly-text-sm'); ?>">
      					<option value="0" <?php selected( '0', $default_text_sm, true ) ?>><?php _e( 'Inherit from smaller', 'xtender' ) ?></option>
                <option value="1" <?php selected( '1', $default_text_sm, true ) ?>><?php _e( 'Left', 'xtender' ) ?></option>
                <option value="2" <?php selected( '2', $default_text_sm, true ) ?>><?php _e( 'Center', 'xtender' ) ?></option>
                <option value="3" <?php selected( '3', $default_text_sm, true ) ?>><?php _e( 'Right', 'xtender' ) ?></option>
      				</select>
      			</p>
            <p>
      				<label for="<?php echo $widget_instance->get_field_id('curly-visibility-sm'); ?>"><?php _e( 'Widget Visibility:', 'xtender' ) ?></label>
      				<select class="widefat" id="<?php echo $widget_instance->get_field_id('curly-visibility-sm'); ?>" name="<?php echo $widget_instance->get_field_name('curly-visibility-sm'); ?>">
      					<option value="0" <?php selected( '0', $default_visibility_sm, true ) ?>></option>
                <option value="1" <?php selected( '1', $default_visibility_sm, true ) ?>><?php _e( 'Hidden Upper', 'xtender' ) ?></option>
                <option value="2" <?php selected( '2', $default_visibility_sm, true ) ?>><?php _e( 'Hidden Down', 'xtender' ) ?></option>
      				</select>
      			</p>
          </div>




          <a href="#" class="xtender-toggler" data-swap-target="#tab_content_<?php echo $widget_instance->id ?>_xs" data-swap-group="group_<?php echo $widget_instance->id ?>"><?php _e( 'Extra Small Devices', 'xtender' ); ?></a>
          <div id="tab_content_<?php echo $widget_instance->id ?>_xs">
            <p>
      				<label for="<?php echo $widget_instance->get_field_id('curly-size-xs'); ?>"><?php _e( 'Widget Grid Size:', 'xtender' ) ?></label>
      				<select class="widefat" id="<?php echo $widget_instance->get_field_id('curly-size-xs'); ?>" name="<?php echo $widget_instance->get_field_name('curly-size-xs'); ?>">
      					<option value="1/12" <?php selected( '1/12', $default_xs, true ) ?>><?php _e( '1 column - 1/12', 'xtender' ) ?></option>
      					<option value="1/6"  <?php selected( '1/6', $default_xs, true ) ?>><?php _e( '2 columns - 1/6', 'xtender' ) ?></option>
      					<option value="1/4"  <?php selected( '1/4', $default_xs, true ) ?>><?php _e( '3 columns - 1/4', 'xtender' ) ?></option>
      					<option value="1/3"  <?php selected( '1/3', $default_xs, true ) ?>><?php _e( '4 columns - 1/3', 'xtender' ) ?></option>
      					<option value="5/12" <?php selected( '5/12', $default_xs, true ) ?>><?php _e( '5 columns - 5/12', 'xtender' ) ?></option>
      					<option value="1/2"  <?php selected( '1/2', $default_xs, true ) ?>><?php _e( '6 columns - 1/2', 'xtender' ) ?></option>
      					<option value="2/3"  <?php selected( '2/3', $default_xs, true ) ?>><?php _e( '8 columns - 2/3', 'xtender' ) ?></option>
      					<option value="3/4"  <?php selected( '3/4', $default_xs, true ) ?>><?php _e( '9 columns - 3/4', 'xtender' ) ?></option>
      					<option value="5/6"  <?php selected( '5/6', $default_xs, true ) ?>><?php _e( '10 columnns - 5/6', 'xtender' ) ?></option>
      					<option value="11/12" <?php selected( '11/12', $default_xs, true ) ?>><?php _e( '11 columns - 11/12', 'xtender' ) ?></option>
      					<option value="1/1"  <?php selected( '1/1', $default_xs, true ) ?>><?php _e( '12 columns - 1/1', 'xtender' ) ?></option>

      				</select>
      			</p>
            <p>
      				<label for="<?php echo $widget_instance->get_field_id('curly-offset-xs'); ?>"><?php _e( 'Widget Grid Offset:', 'xtender' ) ?></label>
      				<select class="widefat" id="<?php echo $widget_instance->get_field_id('curly-offset-xs'); ?>" name="<?php echo $widget_instance->get_field_name('curly-offset-xs'); ?>">
                <option value="0" <?php selected( '0', $default_offset_xs, true ) ?>><?php _e( 'No Offset', 'xtender' ) ?></option>
      					<option value="1/12" <?php selected( '1/12', $default_offset_xs, true ) ?>><?php _e( '1 column - 1/12', 'xtender' ) ?></option>
      					<option value="1/6" <?php selected( '1/6', $default_offset_xs, true ) ?>><?php _e( '2 columns - 1/6', 'xtender' ) ?></option>
      					<option value="1/4" <?php selected( '1/4', $default_offset_xs, true ) ?>><?php _e( '3 columns - 1/4', 'xtender' ) ?></option>
      					<option value="1/3" <?php selected( '1/3', $default_offset_xs, true ) ?>><?php _e( '4 columns - 1/3', 'xtender' ) ?></option>
      					<option value="5/12" <?php selected( '5/12', $default_offset_xs, true ) ?>><?php _e( '5 columns - 5/12', 'xtender' ) ?></option>
      					<option value="1/2" <?php selected( '1/2', $default_offset_xs, true ) ?>><?php _e( '6 columns - 1/2', 'xtender' ) ?></option>
      					<option value="2/3" <?php selected( '2/3', $default_offset_xs, true ) ?>><?php _e( '8 columns - 2/3', 'xtender' ) ?></option>
      					<option value="3/4" <?php selected( '3/4', $default_offset_xs, true ) ?>><?php _e( '9 columns - 3/4', 'xtender' ) ?></option>
      					<option value="5/6" <?php selected( '5/6', $default_offset_xs, true ) ?>><?php _e( '10 columnns - 5/6', 'xtender' ) ?></option>
      					<option value="11/12" <?php selected( '11/12', $default_offset_xs, true ) ?>><?php _e( '11 columns - 11/12', 'xtender' ) ?></option>
      					<option value="1/1" <?php selected( '1/1', $default_offset_xs, true ) ?>><?php _e( '12 columns - 1/1', 'xtender' ) ?></option>
      				</select>
      			</p>
            <p>
      				<label for="<?php echo $widget_instance->get_field_id('curly-text-xs'); ?>"><?php _e( 'Widget Text Align:', 'xtender' ) ?></label>
      				<select class="widefat" id="<?php echo $widget_instance->get_field_id('curly-text-xs'); ?>" name="<?php echo $widget_instance->get_field_name('curly-text-xs'); ?>">
      					<option value="0" <?php selected( '0', $default_text_xs, true ) ?>><?php _e( 'Default', 'xtender' ) ?></option>
                <option value="1" <?php selected( '1', $default_text_xs, true ) ?>><?php _e( 'Left', 'xtender' ) ?></option>
                <option value="2" <?php selected( '2', $default_text_xs, true ) ?>><?php _e( 'Center', 'xtender' ) ?></option>
                <option value="3" <?php selected( '3', $default_text_xs, true ) ?>><?php _e( 'Right', 'xtender' ) ?></option>
      				</select>
      			</p>
            <p>
      				<label for="<?php echo $widget_instance->get_field_id('curly-visibility-xs'); ?>"><?php _e( 'Widget Visibility:', 'xtender' ) ?></label>
      				<select class="widefat" id="<?php echo $widget_instance->get_field_id('curly-visibility-xs'); ?>" name="<?php echo $widget_instance->get_field_name('curly-visibility-xs'); ?>">
                <option value="0" <?php selected( '0', $default_visibility_xs, true ) ?>></option>
                <option value="1" <?php selected( '1', $default_visibility_xs, true ) ?>><?php _e( 'Hidden Upper', 'xtender' ) ?></option>
                <option value="2" <?php selected( '2', $default_visibility_xs, true ) ?>><?php _e( 'Hidden Down', 'xtender' ) ?></option>
      				</select>
      			</p>
          </div>

      </div>
      <p>
				<label for="<?php echo $widget_instance->get_field_id('curly-classes'); ?>"><?php _e( 'CSS Classes:', 'xtender' ) ?></label>
				<input type="text" class="widefat" id="<?php echo $widget_instance->get_field_id('curly-classes'); ?>" name="<?php echo $widget_instance->get_field_name('curly-classes'); ?>" value="<?php if( isset( $instance['curly-classes'] ) ) echo $instance['curly-classes']; ?>">
			</p>

			<?php

		}

    function footer_widget_columns_cb( $instance, $new_instance ){

			if( isset( $new_instance['curly-classes'] ) ) $instance['curly-classes'] = $new_instance['curly-classes'];

      if( isset( $new_instance['curly-size'] ) ) $instance['curly-size'] = $new_instance['curly-size'];
			if( isset( $new_instance['curly-offset'] ) ) $instance['curly-offset'] = $new_instance['curly-offset'];
      if( isset( $new_instance['curly-text'] ) ) $instance['curly-text'] = $new_instance['curly-text'];
      if( isset( $new_instance['curly-visibility'] ) ) $instance['curly-visibility'] = $new_instance['curly-visibility'];

      if( isset( $new_instance['curly-size-xl'] ) ) $instance['curly-size-xl'] = $new_instance['curly-size-xl'];
			if( isset( $new_instance['curly-offset-xl'] ) ) $instance['curly-offset-xl'] = $new_instance['curly-offset-xl'];
      if( isset( $new_instance['curly-text-xl'] ) ) $instance['curly-text-xl'] = $new_instance['curly-text-xl'];
      if( isset( $new_instance['curly-visibility-xl'] ) ) $instance['curly-visibility-xl'] = $new_instance['curly-visibility-xl'];

      if( isset( $new_instance['curly-size-lg'] ) ) $instance['curly-size-lg'] = $new_instance['curly-size-lg'];
			if( isset( $new_instance['curly-offset-lg'] ) ) $instance['curly-offset-lg'] = $new_instance['curly-offset-lg'];
      if( isset( $new_instance['curly-text-lg'] ) ) $instance['curly-text-lg'] = $new_instance['curly-text-lg'];
      if( isset( $new_instance['curly-visibility-lg'] ) ) $instance['curly-visibility-lg'] = $new_instance['curly-visibility-lg'];

      if( isset( $new_instance['curly-size-sm'] ) ) $instance['curly-size-sm'] = $new_instance['curly-size-sm'];
			if( isset( $new_instance['curly-offset-sm'] ) ) $instance['curly-offset-sm'] = $new_instance['curly-offset-sm'];
      if( isset( $new_instance['curly-text-sm'] ) ) $instance['curly-text-sm'] = $new_instance['curly-text-sm'];
      if( isset( $new_instance['curly-visibility-sm'] ) ) $instance['curly-visibility-sm'] = $new_instance['curly-visibility-sm'];

      if( isset( $new_instance['curly-size-xs'] ) ) $instance['curly-size-xs'] = $new_instance['curly-size-xs'];
			if( isset( $new_instance['curly-offset-xs'] ) ) $instance['curly-offset-xs'] = $new_instance['curly-offset-xs'];
      if( isset( $new_instance['curly-text-xs'] ) ) $instance['curly-text-xs'] = $new_instance['curly-text-xs'];
      if( isset( $new_instance['curly-visibility-xs'] ) ) $instance['curly-visibility-xs'] = $new_instance['curly-visibility-xs'];

		  return $instance;

		}

    function translate_columns_name( $tag ){

      switch( $tag ){

        case '0' : return '0'; break;
        case '1' : return true; break;
        case '1/1'  : return '12'; break;
        case '11/12': return '11'; break;
        case '5/6'  : return '10'; break;
        case '3/4'  : return '9'; break;
        case '2/3'  : return '8'; break;
        case '7/12' : return '7'; break;
        case '1/2'  : return '6'; break;
        case '5/12' : return '5'; break;
        case '1/3'  : return '4'; break;
        case '1/4'  : return '3'; break;
        case '1/6'  : return '2'; break;
        case '1/12' : return '1'; break;
        default : return '3';
      }

    }

    function translate_columns_size( $tag, $size = '' ){

      if( is_bool( $this->translate_columns_name( $tag ) ) )
        return;

      $size = ! empty( $size ) ? "-{$size}" : '';

      return "col{$size}-" . $this->translate_columns_name( $tag );

    }

    function translate_columns_offset( $tag, $size = 'sm' ){

      if( is_bool( $this->translate_columns_name( $tag ) ) )
        return;

      return "offset-$size-" . $this->translate_columns_name( $tag );

    }

    function translate_columns_visibility( $tag, $size = 'sm' ){

      if( $tag === '0' )
        return;

      return $tag === '1' ? "hidden-$size-up" : "hidden-$size-down";

    }

    function translate_columns_text( $tag, $size = '' ){

      $size = ! empty( $size ) ? "-{$size}" : '';

      switch ( intval( $tag ) ) {
        case 1:
          return "text{$size}-left";
          break;

        case 2:
          return "text{$size}-center";
          break;

        case 3:
          return "text{$size}-right";
          break;

        default:
          return;
          break;
      }

    }

    function footer_widget_columns_cb_display( $instance, $widget, $args ){

      $allowed = array( 'footer_widget_area' );

			if( ! in_array( $args['id'], $allowed ) )
				 return $instance;

			$size_xl      = isset( $instance['curly-size-xl'] ) ? $instance['curly-size-xl'] : '1';
			$offset_xl    = isset( $instance['curly-offset-xl'] ) ? $instance['curly-offset-xl'] : '1';
      $text_xl      = isset( $instance['curly-text-xl'] ) ? $instance['curly-text-xl'] : '0';
      $visibility_xl      = isset( $instance['curly-visibility-xl'] ) ? $instance['curly-visibility-xl'] : '0';

      $size_lg      = isset( $instance['curly-size-lg'] ) ? $instance['curly-size-lg'] : '1';
			$offset_lg    = isset( $instance['curly-offset-lg'] ) ? $instance['curly-offset-lg'] : '1';
      $text_lg      = isset( $instance['curly-text-lg'] ) ? $instance['curly-text-lg'] : '0';
      $visibility_lg      = isset( $instance['curly-visibility-lg'] ) ? $instance['curly-visibility-lg'] : '0';

      $size         = isset( $instance['curly-size'] ) ? $instance['curly-size'] : '1/4';
			$offset       = isset( $instance['curly-offset'] ) ? $instance['curly-offset'] : '1';
      $text         = isset( $instance['curly-text'] ) ? $instance['curly-text'] : '';
      $visibility   = isset( $instance['curly-visibility'] ) ? $instance['curly-visibility'] : '0';

      $size_sm      = isset( $instance['curly-size-sm'] ) ? $instance['curly-size-sm'] : '1';
			$offset_sm    = isset( $instance['curly-offset-sm'] ) ? $instance['curly-offset-sm'] : '1';
      $text_sm      = isset( $instance['curly-text-sm'] ) ? $instance['curly-text-sm'] : '0';
      $visibility_sm      = isset( $instance['curly-visibility-sm'] ) ? $instance['curly-visibility-sm'] : '0';

      $size_xs      = isset( $instance['curly-size-xs'] ) ? $instance['curly-size-xs'] : '1/1';
			$offset_xs    = isset( $instance['curly-offset-xs'] ) ? $instance['curly-offset-xs'] : '0';
      $text_xs      = isset( $instance['curly-text-xs'] ) ? $instance['curly-text-xs'] : '0';
      $visibility_xs      = isset( $instance['curly-visibility-xs'] ) ? $instance['curly-visibility-xs'] : '0';

		  $widget_classname = $widget->widget_options['classname'];

		  $my_classnames = '';

      $css_classes = array();

      $css_classes[] = $this->translate_columns_size( $size_xs );
      $css_classes[] = $this->translate_columns_size( $size_sm, 'sm' );
      $css_classes[] = $this->translate_columns_size( $size, 'md' );
      $css_classes[] = $this->translate_columns_size( $size_lg, 'lg' );
      $css_classes[] = $this->translate_columns_size( $size_xl, 'xl' );

      $css_classes[] = $this->translate_columns_offset( $offset_xs, 'xs' );
      $css_classes[] = $this->translate_columns_offset( $offset_sm, 'sm' );
      $css_classes[] = $this->translate_columns_offset( $offset, 'md' );
      $css_classes[] = $this->translate_columns_offset( $offset_lg, 'lg' );
      $css_classes[] = $this->translate_columns_offset( $offset_xl, 'xl' );

      $css_classes[] = $this->translate_columns_text( $text_xs );
      $css_classes[] = $this->translate_columns_text( $text_sm, 'sm' );
      $css_classes[] = $this->translate_columns_text( $text, 'md' );
      $css_classes[] = $this->translate_columns_text( $text_lg, 'lg' );
      $css_classes[] = $this->translate_columns_text( $text_xl, 'xl' );


      $css_classes[] = $this->translate_columns_visibility( $visibility_xs, 'xs' );
      $css_classes[] = $this->translate_columns_visibility( $visibility_sm, 'sm' );
      $css_classes[] = $this->translate_columns_visibility( $visibility, 'md' );
      $css_classes[] = $this->translate_columns_visibility( $visibility_lg, 'lg' );
      $css_classes[] = $this->translate_columns_visibility( $visibility_xl, 'xl' );

      $css_classes = array_filter($css_classes, 'strlen');

      $my_classnames .= implode(' ', $css_classes );
      $my_classnames  = str_replace( 'offset-xs-0', '', $my_classnames );
		  $my_classnames .= isset( $instance['curly-classes'] ) ? " {$instance['curly-classes']}" : '';

	    $args['before_widget'] = str_replace( 'col-sm-3', $my_classnames, $args['before_widget'] );

	    $widget->widget($args, $instance);

	    return false;

		}

}

$curly_widgets = new CurlyWidgets();

?>
