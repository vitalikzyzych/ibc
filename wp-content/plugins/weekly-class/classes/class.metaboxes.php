<?php

new CurlyWeeklyClassMetaBoxes();

/**
* Weekly Classes Meta Boxes
*/
class CurlyWeeklyClassMetaBoxes {

	public function __construct(){

		/** Add Meta Boxes */
		add_action( 'add_meta_boxes', array( $this, 'meta_box') );
		add_action( 'save_post', array( $this, 'save_meta_box_data' ), 10, 3 );

		/** Load Assets */
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_assets' ) );

	}


	/** Add Meta Box */
	function meta_box(){

		$screens = array( 'class' );

		foreach ( $screens as $screen ) {
			add_meta_box(
				'wcs_date_time_metabox',
				'<span class="dashicons dashicons-calendar-alt"></span> &nbsp;' . __( 'Schedule Class', 'WeeklyClass' ),
				array( $this, 'meta_box_callback'),
				$screen,
				'side',
				'high'
			);
		}

	}


	/** Add Meta Box Callback */
	public function meta_box_callback( $post ) {

		wp_nonce_field( 'wcs_schedule_metabox', 'wcs_schedule_box_nonce' );

		$timestamp  = get_post_meta( $post->ID, '_wcs_timestamp', true );
		$status 	= get_post_meta( $post->ID, '_wcs_status', true );
		$canceled 	= maybe_unserialize( get_post_meta( $post->ID, '_wcs_canceled', true ) );

		$class_start = new DateTime();

		if( ! empty( $timestamp ) ){
			$class_start->setTimestamp( $timestamp );
		} else {
			$class_start->setTimestamp( current_time( 'timestamp', 0 ) );
			$class_start->add( new DateInterval('P7D') );
		}

		$hour = $class_start->format('H');
		$minutes = $class_start->format('i');
		$minutes = 5 * round( $minutes / 5 );
		$interval = get_post_meta( $post->ID, '_wcs_interval', true );
		$duration = get_post_meta( $post->ID, '_wcs_duration', true );
		$duration = empty( $duration ) ? 60 : $duration;

		$repeat_u = get_post_meta( $post->ID, '_wcs_repeat_until', true );
		$repeat_u = empty( $repeat_u ) ? '' : $repeat_u;

		$repeat_days = get_post_meta( $post->ID, '_wcs_repeat_days', true );
		$repeat_days = $repeat_days && ! empty( $repeat_days ) ? $repeat_days : array( 0,1,2,3,4,5,6 );

		$multi_day = wp_validate_boolean( get_post_meta( $post->ID, '_wcs_multiday', true ) );
		$ending = get_post_meta( $post->ID, '_wcs_ending', true );

		$start_of_week = intval( get_option( 'start_of_week', 0 ) );

		$ending = empty( $ending ) ? intval( $class_start->getTimestamp() ) + $duration * 60 : $ending;
		$class_ending = new DateTime();
		$class_ending->setTimestamp( $ending );
		$hour_ending = $class_ending->format('H');
		$minutes_ending = $class_ending->format('i');
		$minutes_ending = 5 * round( $minutes_ending / 5 );

		global $wp_locale;

		$weekdays_ini = array_values($wp_locale->weekday_initial);

		?>
		<div class="inside-container" id="wcs-schedule">
			<div class="wcs-admin-metabox-time">
				<input type="hidden" name="wcs-timestamp" value="<?php echo $class_start->format('Y-m-d'); ?>">
				<input type="hidden" name="wcs-date" value="<?php echo $class_start->format('Y-m-d'); ?>">

				<p><strong><?php _e( 'Starting Date & Time:', 'WeeklyClass' ) ?></strong></p>
			<input type="text"  value="<?php echo $class_start->format('Y-m-d'); ?>" readonly id="wcs-datepicker" data-wcs-offset='<?php echo get_option('gmt_offset') ?>' data-wcs-timestamp="<?php echo intval( $class_start->getTimestamp() ) * 1000; ?>" data-wcs-timezone="<?php echo get_option('gmt_offset'); ?>" data-wcs-months="<?php echo implode( ',', array_values( $wp_locale->month ) ); ?>" data-wcs-months-short="<?php echo implode( ',', array_values( $wp_locale->month_abbrev ) ); ?>" data-wcs-days="<?php echo implode( ',', array_values( $wp_locale->weekday ) ); ?>" data-wcs-days-short="<?php echo implode( ',', array_values( $wp_locale->weekday_abbrev ) ); ?>" data-wcs-days-min="<?php echo implode( ',', array_values( $wp_locale->weekday_initial ) ); ?>" data-wcs-firstday="<?php echo $start_of_week ?>"></input>

			<select name="wcs-hour">
				<?php for( $i = 0; $i < 24; $i++ ) : ?>
					<option value="<?php echo $i ?>" <?php selected( intval( $hour ), $i, true ) ?>><?php echo $i <= 9 ? '0' . $i : $i ?></option>
				<?php endfor; ?>
			</select>
			<select name="wcs-minutes">
				<?php for( $i = 0; $i < 12; $i++ ) : ?>
					<option value="<?php echo $i * 5 ?>" <?php selected( intval( $minutes ), $i * 5, true ) ?>><?php echo $i <= 1 ? '0' . $i * 5 : $i * 5; ?></option>
				<?php endfor; ?>
			</select>
			</div>
			<p>
				<label><input type="checkbox" name="wcs_multiday" <?php checked( $multi_day, true ) ?>>
				<strong><?php _e( 'This is a multi-day event', 'WeeklyClass' ) ?></strong></label>
			</p>
			<div class="wcs-admin-metabox-time wcs-admin-metabox-time--ending"  style="display: none;">
				<p style="font-size: 90%; opacity: .5"><?php esc_html_e( 'Displayed only for Plain List, Events Carousel and Masonry Grid schedule styles.', 'WeeklyClass' ) ?></p>
				<p><strong><?php _e( 'Ending Date & Time:', 'WeeklyClass' ) ?></strong></p>
				<input type="text" name="wcs-ending" value="<?php echo $class_ending->format('Y-m-d'); ?>" readonly id="wcs-datepicker-ending" data-wcs-offset='<?php echo get_option('gmt_offset') ?>' data-wcs-timestamp="<?php echo intval( $class_ending->getTimestamp() ) * 1000; ?>" data-wcs-timezone="<?php echo get_option('gmt_offset'); ?>" data-wcs-months="<?php echo implode( ',', array_values( $wp_locale->month ) ); ?>" data-wcs-months-short="<?php echo implode( ',', array_values( $wp_locale->month_abbrev ) ); ?>" data-wcs-days="<?php echo implode( ',', array_values( $wp_locale->weekday ) ); ?>" data-wcs-days-short="<?php echo implode( ',', array_values( $wp_locale->weekday_abbrev ) ); ?>" data-wcs-days-min="<?php echo implode( ',', array_values( $wp_locale->weekday_initial ) ); ?>" data-wcs-firstday="<?php echo $start_of_week ?>"></input>
				<select name="wcs-hour-ending">
					<?php for( $i = 0; $i < 24; $i++ ) : ?>
						<option value="<?php echo $i ?>" <?php selected( intval( $hour_ending ), $i, true ) ?>><?php echo $i <= 9 ? '0' . $i : $i ?></option>
					<?php endfor; ?>
				</select>
				<select name="wcs-minutes-ending">
					<?php for( $i = 0; $i < 12; $i++ ) : ?>
						<option value="<?php echo $i * 5 ?>" <?php selected( intval( $minutes_ending ), $i * 5, true ) ?>><?php echo $i <= 1 ? '0' . $i * 5 : $i * 5; ?></option>
					<?php endfor; ?>
				</select>
			</div>
			<div id="wcs-duration-container">
				<p><strong><?php _e( 'Duration:', 'WeeklyClass' ) ?></strong></p>
				<?php

					$hours = floor( $duration / 60 );
					$minutes = $duration - floor( $duration / 60 ) * 60;

				?>
				<div id="wcs-duration" data-wcs-value="<?php echo $duration ?>" data-wcs-units-hour="<?php _e( 'hour', 'WeeklyClass' ) ?>" data-wcs-units-hours="<?php _e( 'hours', 'WeeklyClass' ) ?>" data-wcs-units-minutes="<?php _e( 'minutes', 'WeeklyClass' ) ?>">
					<input type="hidden" name="wcs-duration" value="<?php echo $duration ?>">
					<div class="slider"></div>
					<div class="slider_value">
						<strong><?php if( $hours > 0 ) printf( _n( '%s hour', '%s hours', $hours, 'WeeklyClass' ), $hours ) ?></strong>
						<em><?php if( $minutes > 0 ) printf( __( '%d minutes', 'WeeklyClass' ), $minutes ) ?></em>
					</div>
				</div>
			</div>
			<div id="wcs-repeat-container">
				<p><strong><?php esc_html_e( 'Repeat', 'WeeklyClass' ) ?></strong>
				<select name="wcs-interval">
					<option value="0" <?php selected( '0', $interval, true ) ?>><?php _e( 'No Repeat', 'WeeklyClass' )?></option>
					<option value="2" <?php selected( '2', $interval, true ) ?>><?php _e( 'Repeat Daily', 'WeeklyClass' ); ?></option>
					<option value="1" <?php selected( '1', $interval, true ) ?>><?php _e( 'Repeat Weekly', 'WeeklyClass' ); ?></option>
					<option value="3" <?php selected( '3', $interval, true ) ?>><?php _e( 'Repeat Every Two Weeks', 'WeeklyClass' ); ?></option>
					<option value="4" <?php selected( '4', $interval, true ) ?>><?php _e( 'Repeat Monthly', 'WeeklyClass' ); ?></option>
					<option value="5" <?php selected( '5', $interval, true ) ?>><?php _e( 'Repeat Yearly', 'WeeklyClass' ); ?></option>
				</select></p>
				<table class="widefat fixed striped posts" id="wrapper__wcs_repeat_days">
					<thead>
						<tr>
							<?php for( $i = $start_of_week; $i <= $start_of_week + 6; $i++ ) : $key = $i <= 6 ? $i : $i - 7; ?>
								<th><label for="wcs_rep_day_<?php echo $key ?>"><?php echo $weekdays_ini[$key]; ?></label></th>
							<?php endfor; ?>
						</tr>
					</thead>
					<tbody>
						<tr>
							<?php for( $i = $start_of_week; $i <= $start_of_week + 6; $i++ ) : $key = $i <= 6 ? $i : $i - 7; ?>
								<td><input <?php if( in_array( $key, $repeat_days ) ) echo 'checked' ?> type="checkbox" name="wcs-repeat-days[]" value="<?php echo $key ?>" id="wcs_rep_day_<?php echo $key ?>"></td>
							<?php endfor; ?>
						</tr>
					</tbody>
				</table>
				<small><?php printf( "<strong>%s:</strong> %s", __( 'Note', 'WeeklyClass' ), __( 'If you choose to repeat a class, the next one will be added when previous has started.', 'WeeklyClass' )); ?></small>
				<p id="wcs-until">
					<strong><?php _e( 'Last Repeat Date:', 'WeeklyClass' ) ?></strong>
					<a href="#"><?php _e( 'Clear', 'WeeklyClass' ); ?></a>
					<input type="date" class="repeat_until" name="wcs-repeat-until" readonly value="<?php echo $repeat_u ?>">
					<small><?php printf( "<strong>%s:</strong> %s", __( 'Note', 'WeeklyClass' ), __( 'Leave empty for infinite loop.', 'WeeklyClass' )); ?></small>
				</p>
			</div>
			<p>
				<strong><?php _e( 'Status:', 'WeeklyClass' ) ?></strong>
				<select name="wcs-status">
					<option value="0" <?php selected( '0', $status, true ) ?>><?php _e( 'Live', 'WeeklyClass' ) ?></option>
					<option value="1" <?php selected( '1', $status, true ) ?>><?php _e( 'Canceled', 'WeeklyClass' ) ?></option>
					<option value="2" <?php selected( '2', $status, true ) ?>><?php _e( 'Canceled Dates', 'WeeklyClass' ) ?></option>
				</select>
			</p>
			<div id="wcs-canceled">
				<p><strong><?php _e( 'When to Cancel?', 'WeeklyClass' ) ?></strong></p>
				<div id="wcs-canceled__holder">
					<?php if( is_array( $canceled ) ) : foreach( $canceled as $not ) : ?>
					<div class="wcs-canceled__item">
						<input type="text" name="wcs-canceled-days[]" readonly placeholder="<?php _e( 'Select date', 'WeeklyClass') ?>" value="<?php echo $not ?>">
						<a href="#" class="wcs-delete-canceled"><?php _e( 'Delete', 'WeeklyClass' ) ?></a>
					</div>
					<?php endforeach; endif; ?>
				</div>
				<input id="add-canceled" type="button" class="button" value="<?php _e( 'Add Date', 'WeeklyClass' ) ?>">
			</div>
			<script id="wcs-canceled-item" type="text/x-handlebars-template">
				<div class="wcs-canceled__item">
					<input type="text" name="wcs-canceled-days[]" readonly placeholder="<?php _e( 'Select date', 'WeeklyClass') ?>">
					<a href="#" class="wcs-canceled-repeat"><?php _e( 'Delete', 'WeeklyClass' ) ?></a>
				</div>
			</script>

		</div>

		<?php

	}



	/** Save Meta Box */
	public function save_meta_box_data( $post_id, $post, $update ) {

		if ( ! isset( $_POST['wcs_schedule_box_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['wcs_schedule_box_nonce'], 'wcs_schedule_metabox' ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( isset( $_POST['post_type'] ) && 'class' == $_POST['post_type'] ) {

			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return;
			}

		} else {

			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
		}

		if ( ! isset( $_POST['wcs-date'] ) ) {
			return;
		}

		if ( ! isset( $_POST['wcs-hour'] ) ) {
			return;
		}

		if ( ! isset( $_POST['wcs-minutes'] ) ) {
			return;
		}

		if ( ! isset( $_POST['wcs-interval'] ) ) {
			return;
		}

		if ( ! isset( $_POST['wcs-duration'] ) ) {
			return;
		}

		if( $post->post_status === 'draft' ){
			return;
		}

		$post_meta  = get_post_meta( $post_id );

		$date = esc_attr( $_POST['wcs-date'] );
		$h = esc_attr( $_POST['wcs-hour'] );
		$h = $h < 10 ? "0$h" : $h;
		$m = esc_attr( $_POST['wcs-minutes'] );
		$m = $m < 10 ? "0$m" : $m;

		$timestamp  = strtotime("$date $h:$m");
		$interval 	= esc_attr( $_POST['wcs-interval'] );
		$duration 	= esc_attr( $_POST['wcs-duration'] );
		$status 		= isset( $_POST['wcs-status'] ) ? esc_attr( $_POST['wcs-status'] ) : '';
		$repeats		= isset( $_POST['wcs-repeats-day'] ) ? $_POST['wcs-repeats-day'] : false;
		$repeats_h	= isset( $_POST['wcs-repeats-hours'] ) ? $_POST['wcs-repeats-hours'] : false;
		$repeats_m	= isset( $_POST['wcs-repeats-minuts'] ) ? $_POST['wcs-repeats-minutes'] : false;
		$repeat_u		= isset( $_POST['wcs-repeat-until'] ) ? $_POST['wcs-repeat-until'] : '';


		$repeat_days = isset( $_POST['wcs-repeat-days'] ) ? $_POST['wcs-repeat-days'] : '';

		$default_reps = isset( $post_meta['_wcs_repeats'] ) ? '_wcs_repeats' : array();

		$drafts 	= isset( $post_meta['_wcs_drafts'] ) ? maybe_unserialize( $post_meta['_wcs_drafts'][0] ) : array();
		$updates	= array( $post_id );

		$image 		= isset( $_POST['wcs_image'] ) ? esc_url_raw( $_POST['wcs_image'] ) : '';
		$image_id = isset( $_POST['wcs_image_id'] ) ? esc_attr( $_POST['wcs_image_id'] ) : '';

		$action_label 	= isset( $_POST['wcs_action_label'] ) ? $_POST['wcs_action_label'] : '';
		$action_call 		= isset( $_POST['wcs_action_call'] ) ? $_POST['wcs_action_call'] : '';
		$action_page 		= isset( $_POST['wcs_action_page'] ) ? $_POST['wcs_action_page'] : '';
		$action_custom 	= isset( $_POST['wcs_action_custom'] ) ? $_POST['wcs_action_custom'] : '';
		$action_email 	= isset( $_POST['wcs_action_email'] ) ? $_POST['wcs_action_email'] : '';
		$action_new 		= isset( $_POST['wcs_action_target'] ) ? $_POST['wcs_action_target'] : '';


		$multi_day 		= isset( $_POST['wcs_multiday'] ) ? $_POST['wcs_multiday'] : '';

		$ending 		= isset( $_POST['wcs-ending'] ) ? $_POST['wcs-ending'] : '';
		$hour_ending 		= isset( $_POST['wcs-hour-ending'] ) ? $_POST['wcs-hour-ending'] : '';
		$minutes_ending 		= isset( $_POST['wcs-minutes-ending'] ) ? $_POST['wcs-minutes-ending'] : '';

		if( wp_validate_boolean( $multi_day ) && $ending !== '' && $hour_ending !== '' && $minutes_ending !== '' ){
			$h_ending = esc_attr( $hour_ending );
			$h_ending = $h_ending < 10 ? "0$h_ending" : $h_ending;
			$m_ending = esc_attr( $minutes_ending  );
			$m_ending = $m_ending < 10 ? "0$m_ending" : $m_ending;
			$ending = strtotime("{$ending} $h_ending:$m_ending");
		} else {
			$ending = '';
		}

		$week = 7 * 24 * 60 * 60;

		$canceled = isset( $_POST['wcs-canceled-days'] ) ? $_POST['wcs-canceled-days'] : null;

		if( ! isset( $post_meta['_wcs_interval'] ) || $post_meta['_wcs_interval'] !== $interval ){
			update_post_meta( $post_id, '_wcs_interval', $interval );
		}

		if( ! isset( $post_meta['_wcs_timestamp'] ) || $post_meta['_wcs_timestamp'] !== $timestamp ){
			update_post_meta( $post_id, '_wcs_timestamp', $timestamp );
		}

		if( ! isset( $post_meta['_wcs_duration'] ) || $post_meta['_wcs_duration'] !== $duration ){
			update_post_meta( $post_id, '_wcs_duration', $duration );
		}

		if( ! isset( $post_meta['_wcs_status'] ) || $post_meta['_wcs_status'] !== $status ){
			update_post_meta( $post_id, '_wcs_status', $status );
		}

		if( ! isset( $post_meta['_wcs_image'] ) || $post_meta['_wcs_image'] !== $image ){
			update_post_meta( $post_id, '_wcs_image', $image );
		}

		if( ! isset( $post_meta['_wcs_image_id'] ) || $post_meta['_wcs_image_id'] !== $image_id ){
			update_post_meta( $post_id, '_wcs_image_id', $image_id );
		}

		if( ! isset( $post_meta['_wcs_action_label'] ) || $post_meta['_wcs_action_label'] !== $action_label ){
			update_post_meta( $post_id, '_wcs_action_label', $action_label );
		}

		if( ! isset( $post_meta['_wcs_action_call'] ) || $post_meta['_wcs_action_call'] !== $action_call ){
			update_post_meta( $post_id, '_wcs_action_call', $action_call );
		}

		if( ! isset( $post_meta['_wcs_action_page'] ) || $post_meta['_wcs_action_page'] !== $action_page ){
			update_post_meta( $post_id, '_wcs_action_page', $action_page );
		}

		if( ! isset( $post_meta['_wcs_action_custom'] ) || $post_meta['_wcs_action_custom'] !== $action_custom ){
			update_post_meta( $post_id, '_wcs_action_custom', $action_custom );
		}

		if( ! isset( $post_meta['_wcs_action_email'] ) || $post_meta['_wcs_action_email'] !== $action_email ){
			update_post_meta( $post_id, '_wcs_action_email', $action_email );
		}

		if( ! isset( $post_meta['_wcs_action_target'] ) || $post_meta['_wcs_action_target'] !== $action_new ){
			update_post_meta( $post_id, '_wcs_action_target', $action_new );
		}

		if( ! isset( $post_meta['_wcs_canceled'] ) || $post_meta['_wcs_canceled'] !== $canceled ){
			update_post_meta( $post_id, '_wcs_canceled', $canceled );
		}

		if( ! isset( $post_meta['_wcs_repeat_until'] ) || $post_meta['_wcs_repeat_until'] !== $repeat_u ){
			update_post_meta( $post_id, '_wcs_repeat_until', $repeat_u );
		}

		if( ! isset( $post_meta['_wcs_repeat_days'] ) || array_diff( $post_meta['_wcs_repeat_days'], $repeat_days )  ){
			update_post_meta( $post_id, '_wcs_repeat_days', $repeat_days );
		}

		if( ! isset( $post_meta['_wcs_multiday'] ) || $post_meta['_wcs_multiday'] !== $multi_day  ){
			update_post_meta( $post_id, '_wcs_multiday', $multi_day );
		}

		if( ! isset( $post_meta['_wcs_ending'] ) || $post_meta['_wcs_ending'] !== $ending  ){
			update_post_meta( $post_id, '_wcs_ending', $ending );
		}

	}



	/** Load Admin Assets */
	function admin_assets(){

		global $post_type;

		if( $post_type !== 'class' )
			return;

		wp_enqueue_style(
			'wcs-admin',
			plugins_url() . '/weekly-class/assets/admin/css/admin.css',
			null,
			WCS_VERSION,
			'all'
		);

		$screen = get_current_screen();
		if( $screen->base !== 'post' )
			return;

		wp_register_script(
			'wcs-dependson',
			plugins_url() . '/weekly-class/assets/libs/dependson/dependsOn-1.0.2.min.js',
			array( 'jquery'  ),
			null,
			true
		);

		wp_register_script(
			'wcs-handlebars',
			plugins_url() . '/weekly-class/assets/libs/handlebars/handlebars-v4.0.5.js',
			array( 'jquery'  ),
			null,
			true
		);

		wp_enqueue_script( 'jquery-ui-datepicker' );

		wp_enqueue_media();

		wp_enqueue_script(
			'wcs-admin',
			 plugins_url() . '/weekly-class/assets/admin/js/min/admin-min.js',
			 array( 'jquery', 'jquery-ui-datepicker', 'jquery-ui-slider', 'wcs-dependson', 'wcs-handlebars' ),
			 defined( 'WP_DEBUG' ) && WP_DEBUG === true ? rand() : null
		);



		global $_wp_admin_css_colors;
 		$color_scheme = $_wp_admin_css_colors[ get_user_option( 'admin_color' ) ]->colors;

 		$color_scheme = "
			#wcs-datepicker .ui-datepicker-current-day a,
			#ui-datepicker-div.wcs-datepicker-pop .ui-datepicker-current-day a,
			#wcs-datepicker-ending .ui-datepicker-current-day a{
				background-color: {$color_scheme[3]};
			}
			#wcs-datepicker a.ui-state-default,
			#wcs-datepicker-ending a.ui-state-default,
			#ui-datepicker-div.wcs-datepicker-pop a.ui-state-default:not(.ui-state-active){
				color: {$color_scheme[1]};
			}
			#wcs-duration .slider.ui-slider .ui-slider-handle{
 				background: {$color_scheme[3]};
 			}
		";

 		wp_add_inline_style( 'wcs-admin', $color_scheme );

	}




}


?>
