<?php


class WCS_Settings {

	function __construct(){

		add_action( 'admin_menu', array( $this, 'settings_page' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'load_assets' ) );

	}


	function load_assets(){

		$screen = get_current_screen();

		if( $screen->base !== 'class_page_wcs_settings' )
			return;

			wp_register_style( 'element-ui', plugins_url() . '/weekly-class/assets/libs/element-ui/index.css', null, '1.4.8' );
			wp_register_style( 'wcs-settings', plugins_url() . '/weekly-class/assets/admin/css/settings.css', array( 'element-ui'  ), rand() );

      if( ! wp_script_is( 'vue-js', 'registered' ) ) wp_register_script( 'vue-js', plugins_url() . '/weekly-class/assets/libs/vue/vue.min.js', array( 'jquery' ), null, true );
      if( ! wp_script_is( 'vue-resource', 'registered' ) ) wp_register_script( 'vue-resource', plugins_url() . '/weekly-class/assets/libs/vue/vue-resource.min.js', array( 'vue-js' ), null, true );
      if( ! wp_script_is( 'moment-js', 'registered' ) ) wp_register_script( 'moment-js', plugins_url() . '/weekly-class/moment/moment.js', array( 'vue-js' ), null, true );
      if( ! wp_script_is( 'element-ui', 'registered' ) ) wp_register_script( 'element-ui', plugins_url() . '/weekly-class/assets/libs/element-ui/index.js', array( 'vue-js' ), null, true );

			wp_register_script( 'wcs-settings', plugins_url() . '/weekly-class/assets/admin/js/min/settings-min.js', array( 'jquery', 'wp-color-picker', 'vue-resource', 'element-ui' ), rand() );
      wp_localize_script( 'wcs-settings', 'EventsSchedule', array(
				'rest_route' => esc_url_raw( get_rest_url() ),
				'nonce' => wp_create_nonce( 'wp_rest' ),
				'locale_element_ui' => array( 'el' => apply_filters( 'wcs_locale_element_ui', array() ) )
			) );

	}


	function settings_page(){
		add_submenu_page( 'edit.php?post_type=class', __( 'Events Schedule Settings', 'WeeklyClass' ), __( 'Settings', 'WeeklyClass' ), 'manage_options', 'wcs_settings', array( $this, 'settings_page_hook' ) );
	}

	function settings_page_hook(){

		wp_enqueue_style( 'wcs-settings' );
		wp_enqueue_script( 'wcs-settings' );

  	$templates 		= get_page_templates();
  	$templates		= array_merge( array( __('Default Page', 'WeeklyClass' ) => 'page', __('Single Page', 'WeeklyClass' ) => 'single' ), $templates );

		?>

		<div class="wrap wcs-settings">

			<div id="wcs-settings__app" v-cloak>
				<h1><?php _e( 'Events Schedule Settings', 'WeeklyClass' ) ?></h1>
				<el-form v-if="form" ref="form" :model="form" label-position="left" label-width="180px" class="wcs-settings-form" v-loading="loading">
					<el-tabs v-model="tabs">

						<el-tab-pane label="<?php esc_html_e( 'General', 'WeeklyClass' ) ?>" name="general">
							<el-form-item label="<?php _e( 'Event Single Page', 'WeeklyClass' ) ?>">
                <el-switch
                  v-model="form.wcs_single"
                  on-text="<?php esc_html_e( 'Yes', 'WeeklyClass' ) ?>"
                  off-text="<?php esc_html_e( 'No', 'WeeklyClass' ) ?>">
                </el-switch>
                <small><?php _e( 'Allow events to have a public single page', 'WeekylClass' ) ?>. <?php _e( 'Checking this field will allow visitors to view each event also as a separate page', 'WeeklyClass' ) ?></small>
              </el-form-item>

						</el-tab-pane>

            <el-tab-pane label="<?php esc_html_e( 'Event Page', 'WeeklyClass' ) ?>" name="single" v-if="form.wcs_single">

							<el-form-item label="<?php _e( 'Event Page Template', 'WeeklyClass' ) ?>">
                <el-select v-model="form.wcs_single_template" filterable placeholder="<?php esc_html_e( 'Select Template', 'WeeklyClass' ) ?>">
									<?php foreach( $templates as $key => $template ) : ?>
										<el-option label="<?php echo $key ?>" value="<?php echo $template ?>"></el-option>
									<?php endforeach; ?>
								</el-select>
							</el-form-item>

							<el-form-item label="<?php _e( 'Event Page Slug', 'WeeklyClass' ) ?>">
                <el-input v-model="form.wcs_slug" placeholder="Enter your event page slug here"></el-input>
                <small><?php _e( 'Default: class. This field cannot be empty', 'WeeklyClass' ) ?></small>
              </el-form-item>

							<el-form-item label="<?php _e( 'Events Box Position', 'WeeklyClass' ) ?>">
								<el-radio-group v-model="form.wcs_single_box">
								  <el-radio-button label="disabled"><?php esc_html_e( 'Disabled', 'WeeklyClass' ) ?></el-radio-button>
								  <el-radio-button label="left"><?php esc_html_e( 'Left', 'WeeklyClass' ) ?></el-radio-button>
								  <el-radio-button label="center"><?php esc_html_e( 'Center', 'WeeklyClass' ) ?></el-radio-button>
								  <el-radio-button label="right"><?php esc_html_e( 'Right', 'WeeklyClass' ) ?></el-radio-button>
								</el-radio-group>
							</el-form-item>

							<el-form-item label="<?php _e( 'Special Color', 'WeeklyClass' ) ?>">
								<el-color-picker v-model="form.wcs_single_color"></el-color-picker>
								<small><?php _e( 'This color will be used in the titles and on the background of the buttons', 'WeeklyClass' ) ?></small>
							</el-form-item>

							<el-form-item label="<?php _e( 'Date Format', 'WeeklyClass' ) ?>">
                <el-input v-model="form.wcs_dateformat" placeholder="Enter your date format here"></el-input>
                <small><?php _e( 'Default: F j @ H:i. Available date &amp; time formats available here: <a href="https://codex.wordpress.org/Formatting_Date_and_Time" target="_blank">WordPress Date Formatting</a>', 'WeeklyClass' ) ?></small>
              </el-form-item>

							<el-form-item label="<?php _e( '12h Time Format', 'WeeklyClass' ) ?>">
                <el-switch
                  v-model="form.wcs_time_format"
                  on-text="<?php esc_html_e( 'Yes', 'WeeklyClass' ) ?>"
                  off-text="<?php esc_html_e( 'No', 'WeeklyClass' ) ?>">
                </el-switch>
            	</el-form-item>

							<el-form-item v-if="form.wcs_single_box !== 'disabled'" label="<?php _e( 'Show Ending Time', 'WeeklyClass' ) ?>">
                <el-switch
                  v-model="form.wcs_single_ending"
                  on-text="<?php esc_html_e( 'Yes', 'WeeklyClass' ) ?>"
                  off-text="<?php esc_html_e( 'No', 'WeeklyClass' ) ?>">
                </el-switch>
            	</el-form-item>

							<el-form-item v-if="form.wcs_single_box !== 'disabled'" label="<?php _e( 'Show Duration', 'WeeklyClass' ) ?>">
                <el-switch
                  v-model="form.wcs_single_duration"
                  on-text="<?php esc_html_e( 'Yes', 'WeeklyClass' ) ?>"
                  off-text="<?php esc_html_e( 'No', 'WeeklyClass' ) ?>">
                </el-switch>
            	</el-form-item>

							<el-form-item v-if="form.wcs_single_box !== 'disabled'" label="<?php _e( 'Show Location', 'WeeklyClass' ) ?>">
                <el-switch
                  v-model="form.wcs_single_location"
                  on-text="<?php esc_html_e( 'Yes', 'WeeklyClass' ) ?>"
                  off-text="<?php esc_html_e( 'No', 'WeeklyClass' ) ?>">
                </el-switch>
            	</el-form-item>

							<el-form-item v-if="form.wcs_single_box !== 'disabled'" label="<?php _e( 'Show Instructor', 'WeeklyClass' ) ?>">
                <el-switch
                  v-model="form.wcs_single_instructor"
                  on-text="<?php esc_html_e( 'Yes', 'WeeklyClass' ) ?>"
                  off-text="<?php esc_html_e( 'No', 'WeeklyClass' ) ?>">
                </el-switch>
            	</el-form-item>

							<el-form-item v-if="form.wcs_single_box !== 'disabled'" label="<?php _e( 'Show Map', 'WeeklyClass' ) ?>">
                <el-switch
                  v-model="form.wcs_single_map"
                  on-text="<?php esc_html_e( 'Yes', 'WeeklyClass' ) ?>"
                  off-text="<?php esc_html_e( 'No', 'WeeklyClass' ) ?>">
                </el-switch>
            	</el-form-item>

						</el-tab-pane>
						<el-tab-pane label="Google Maps" name="google-maps">

							<el-form-item label="<?php _e( 'Google Maps API Key', 'WeeklyClass' ) ?>">
                <el-input v-model="form.wcs_api_key" placeholder="Enter your date Google API key here"></el-input>
                <small><?php _e( 'For optimal performances we recommend using your own Google Maps API Key. You can create one here: <a href="
https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank">Generate API Key</a>', 'WeeklyClass' ) ?></small>
              </el-form-item>

							<el-form-item label="<?php _e( 'Map Theme', 'WeeklyClass' ) ?>">
								<el-radio-group v-model="form.wcs_single_map_theme">
								  <el-radio-button label="light"><?php _e( 'Light Theme', 'WeeklyClass' ) ?></el-radio-button>
								  <el-radio-button label="dark"><?php _e( 'Dark Theme', 'WeeklyClass' ) ?></el-radio-button>
								</el-radio-group>
							</el-form-item>

							<el-form-item label="<?php _e( 'Map Zoom Level', 'WeeklyClass' ) ?>">
                <el-slider
                  v-model="form.wcs_single_map_zoom"
                  :step="1" show-input :min="1" :max="18">
                </el-slider>
							</el-form-item>

							<el-form-item label="<?php _e( 'Map Type', 'WeeklyClass' ) ?>">
								<el-radio-group v-model="form.wcs_single_map_type">
								  <el-radio-button label="roadmap"><?php _e( 'Roadmap', 'WeeklyClass' ) ?></el-radio-button>
								  <el-radio-button label="satellite"><?php _e( 'Satellite', 'WeeklyClass' ) ?></el-radio-button>
								</el-radio-group>
							</el-form-item>
						</el-tab-pane>

						<el-tab-pane label="<?php esc_html_e( 'Advanced', 'WeeklyClass' ) ?>" name="advanced">
							<el-form-item label="<?php _e( 'Enable Lazy Load', 'WeeklyClass' ) ?>">
								<el-switch
									v-model="form.wcs_lazy_load"
									on-text="<?php esc_html_e( 'Yes', 'WeeklyClass' ) ?>"
									off-text="<?php esc_html_e( 'No', 'WeeklyClass' ) ?>">
								</el-switch>
								<small><?php _e( 'Checking this field will load the events after the pages has loaded. This can speed up the loading of your site and also can solve potential incompatibilities with other 3rd party plugins.', 'WeeklyClass' ) ?></small>
							</el-form-item>
							<el-form-item label="<?php _e( 'Insert Javascript Templates before Footer', 'WeeklyClass' ) ?>">
								<el-switch
									v-model="form.wcs_get_footer"
									on-text="<?php esc_html_e( 'Yes', 'WeeklyClass' ) ?>"
									off-text="<?php esc_html_e( 'No', 'WeeklyClass' ) ?>">
								</el-switch>
								<small><?php _e( 'Checking this field will put the js templates before the footer. Putting the js templates before the footer (instead of the footer) is good for fixing some javascript conflicts.', 'WeeklyClass' ) ?></small>
							</el-form-item>
							<el-form-item label="<?php _e( 'Maximum Years', 'WeeklyClass' ) ?>">
								<el-slider v-model="form.wcs_all_days" :min="1" :max="30" show-input show-stops></el-slider>
								<small><?php _e( 'Set the maximum number of years for your schdules. High numbers may result in slowing down your website.', 'WeeklyClass' ) ?></small>
							</el-form-item>
							<el-form-item label="<?php _e( 'Enable Archive', 'WeeklyClass' ) ?>">
								<el-switch
									v-model="form.wcs_classes_archive"
									on-text="<?php esc_html_e( 'Yes', 'WeeklyClass' ) ?>"
									off-text="<?php esc_html_e( 'No', 'WeeklyClass' ) ?>">
								</el-switch>
								<small><?php _e( 'Checking this field will enable your classes/events archive.', 'WeeklyClass' ) ?></small>
							</el-form-item>

						</el-tab-pane>

					</el-tabs>
					<el-form-item>
            <el-button type="primary" :disabled="loading" v-on:click="saveSettings()"><?php esc_html_e( 'Save Settings', 'WeeklyClass' ) ?></el-button>
          </el-form-item>
				</elform>
			</div>

		</div>

		<?php

	}


}

new WCS_Settings();


?>
