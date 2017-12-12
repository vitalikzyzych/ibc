<?php


class WCS_Builder {

	function __construct(){

		add_action( 'admin_menu', array( $this, 'schedule_page' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'load_assets' ) );

		add_filter( 'wcs_filter_builder_tdata', array( $this, 'filter_builder_tdata' ) );
		add_action( 'wp_ajax_wcs_update_schedule', array( $this, 'update_schedule' ) );
		add_filter( 'wcs_builder_posts', array( $this, 'builder_posts' ) );


		add_filter( 'wcs_sntz_label_modal_dateformat', array( $this, 'sanitize_date' ) );
		add_filter( 'wcs_sntz_content', array( $this, 'sanitize_content' ) );

		require_once( WCS_PATH . '/assets/defaults/admin/views.php');
	  require_once( WCS_PATH . '/assets/defaults/admin/conditions.php');
	  require_once( WCS_PATH . '/assets/defaults/admin/labels.php');
	  require_once( WCS_PATH . '/assets/defaults/admin/colors.php');
	  require_once( WCS_PATH . '/assets/defaults/admin/display_options.php');
	  require_once( WCS_PATH . '/assets/defaults/admin/modal_options.php');
	  require_once( WCS_PATH . '/assets/defaults/admin/filters_options.php');

		require_once( WCS_PATH . '/assets/defaults/admin/array.locale-element-ui.php');

	}

	function sanitize_content( $content ){

		if( is_array( $content ) ){

			foreach( $content as $tax => $terms ){

				if( is_array( $terms ) && ! empty( $terms ) ){

					foreach( $terms as $key => $term ){

						$obj = get_term_by( 'term_taxonomy_id', intval( $term ), $tax );

						if( $obj === false ){
							unset( $content[$tax][$key] );
						}

					}

				}

			}

		}

		return $content;

	}

	function builder_posts( $posts ){

		$classes = false;//get_transient( 'wcs_admin_posts' );

		if( ! $classes ){

			$classes = new WP_Query(
				array(
					'post_status' => array( 'publish' ),
					'posts_per_page' => -1,
					'post_type' => 'class',
					'meta_key'  => '_wcs_timestamp',
					'orderby'   => 'post_title',
					'order'     => 'ASC'
				)
			);

			set_transient( 'wcs_admin_posts', $classes, WEEK_IN_SECONDS );

		}

		return $classes;

	}

	public static function hex2rgb( $colour, $opacity = 1, $array = false ) {

		if ( ! is_array( $colour ) && strpos( $colour, '#') !== false ) {
		    $colour = substr( $colour, 1 );
		}
		if ( strlen( $colour ) == 6 ) {
		        list( $r, $g, $b ) = array( $colour[0] . $colour[1], $colour[2] . $colour[3], $colour[4] . $colour[5] );
		} elseif ( strlen( $colour ) == 3 ) {
		        list( $r, $g, $b ) = array( $colour[0] . $colour[0], $colour[1] . $colour[1], $colour[2] . $colour[2] );
		} else {
		        return false;
		}
		$r = hexdec( $r );
		$g = hexdec( $g );
		$b = hexdec( $b );

		if( $array === true )
			return array( 'r' => $r, 'g' => $g, 'b' => $b );

		return "rgba( $r, $g, $b, $opacity)";

	}

	public static function contrast( $color, $opacity1 = 1, $opacity2 = 1 ) {
		return (abs(self::brightness('#ffffff') - self::brightness(self::darken($color))) > abs(self::brightness('#000000') - self::brightness(self::darken($color)))) ? self::hex2rgb('#ffffff', $opacity1) : self::hex2rgb('#000000', $opacity2);
	}

	public static function brightness( $hexStr ) {
		$hexStr = preg_replace("/[^0-9A-Fa-f]/", '', $hexStr);
		$rgbArray = array();
		if (strlen($hexStr) == 6) {
			$colorVal = hexdec($hexStr);
			$rgbArray['red'] = 0xFF & ($colorVal >> 0x10);
			$rgbArray['green'] = 0xFF & ($colorVal >> 0x8);
			$rgbArray['blue'] = 0xFF & $colorVal;
		} elseif (strlen($hexStr) == 3) {
			$rgbArray['red'] = hexdec(str_repeat(substr($hexStr, 0, 1), 2));
			$rgbArray['green'] = hexdec(str_repeat(substr($hexStr, 1, 1), 2));
			$rgbArray['blue'] = hexdec(str_repeat(substr($hexStr, 2, 1), 2));
		} else {
			return false;
		}
		return (($rgbArray['red']*299) + ($rgbArray['green']*587) + ($rgbArray['blue']*114))/1000;
	}
	public static function darken( $color, $dif=20 ){
	    $color = str_replace('#', '', $color);
	    if (strlen($color) != 6){ return '000000'; }
	    $rgb = '';
	    for ($x=0;$x<3;$x++){
	        $c = hexdec(substr($color,(2*$x),2)) - $dif;
	        $c = ($c < 0) ? 0 : dechex($c);
	        $rgb .= (strlen($c) < 2) ? '0'.$c : $c;
	    }
	    return '#'.$rgb;
	}

	function sanitize_date( $date ){
		return esc_attr( $date );
	}

	function update_schedule(){
		if( ! isset( $_REQUEST['wp_nonce'] ) ) wp_send_json_error();
		if( ! isset( $_REQUEST['id'] ) || ! wp_verify_nonce( $_REQUEST['wp_nonce'], 'wcs-update-schedule-nonce' ) ) wp_send_json_error();

		if( intval( $_REQUEST['id'] ) === -1 ){
			$count = get_option( '__wcs_schedule_count', 0 );
			$count = intval($count);
			$count++;
			update_option( '__wcs_schedule_count', $count );
		} else {
			$count = intval( $_REQUEST['id'] );
		}
		foreach( $_REQUEST as $key => $value ){
			$_REQUEST[$key] = apply_filters( 'wcs_sntz_' . $key, $value === 'undefined' ? '' : $value );
		}
		$_REQUEST['last_edit_date'] = time();

		update_option( '__wcs_schedule_' . $count, $_REQUEST );

		delete_transient( 'wcs_tdata_' . $count );

		wp_send_json_success( array( 'id'	=> $count ) );
	}

	function filter_builder_tdata( $atts ){

		$atts = apply_filters( 'wcs_filter_migration_tdata', $atts );

		if( is_array( $atts ) ){

			foreach ( $atts as $key => $value ) {
				if( strpos( $key, 'show_' ) === 0 ){
					$atts[$key] = filter_var( $value, FILTER_VALIDATE_BOOLEAN );
				}
				if( strpos( $key, 'dateformat' ) !== false  ){
					$atts[$key] = stripslashes( $value );
				}
				if( $key === 'content' ){

					$value = json_decode(json_encode($value), true);

					if( is_array( $value ) ){
						foreach( $value as $value_key => $term ){
							$value[$value_key] = is_array( $term ) ? array_values( $term ) : $term;
						}
					}
					$atts[$key] = $value;
				}
			}
		}

		return $atts;
	}

	function load_assets(){

		$screen = get_current_screen();

		if( $screen->base !== 'class_page_wcs_schedule' ) return;

		if( ! wp_script_is( 'vue-js', 'registered' ) ) {
			wp_register_script(
				'vue-js',
				WP_DEBUG ? plugins_url() . '/weekly-class/assets/libs/vue/vue.js' : plugins_url() . '/weekly-class/assets/libs/vue/vue.min.js',
				array( 'jquery' ),
				rand(),
				true
			);
		}

		if( ! wp_script_is( 'vue-resource', 'registered' ) ) {
			wp_register_script(
				'vue-resource',
				plugins_url() . '/weekly-class/assets/libs/vue/vue-resource.min.js',
				array( 'jquery', 'vue-js' ),
				null,
				true
			);
		}

		if( ! wp_script_is( 'clipboard', 'registered' ) ) {
			wp_register_script(
				'clipboard',
				plugins_url() . '/weekly-class/assets/libs/clipboard/clipboard.min.js',
				array( 'jquery' ),
				rand(),
				true
			);
		}

		if( ! wp_script_is( 'wcs-fs-core', 'registered' ) ) {
			wp_register_script( 'wcs-fs-core', plugins_url() . '/weekly-class/assets/libs/formstone/js/core.js', array( 'jquery' ), null );
		}

		if( ! wp_script_is( 'wcs-fs-tooltip', 'registered' ) ) {
			wp_register_script( 'wcs-fs-tooltip', plugins_url() . '/weekly-class/assets/libs/formstone/js/tooltip.js', array( 'jquery', 'wcs-fs-core' ), null );
		}

		if( ! wp_script_is( 'wcs-ladda-spin', 'registered' ) ) {
			wp_register_script(
				'wcs-ladda-spin',
				plugins_url() . '/weekly-class/assets/libs/ladda/js/spin.min.js',
				array( 'jquery' ),
				null,
				true
			);
		}

		if( ! wp_script_is( 'wcs-ladda', 'registered' ) ) {
			wp_register_script(
				'wcs-ladda',
				plugins_url() . '/weekly-class/assets/libs/ladda/js/ladda.min.js',
				array( 'jquery', 'wcs-ladda-spin' ),
				null,
				true
			);
		}

		if( ! wp_script_is( 'wcs-ladda-jquery', 'registered' ) ) {
			wp_register_script(
				'wcs-ladda-jquery',
				plugins_url() . '/weekly-class/assets/libs/ladda/js/ladda.jquery.min.js',
				array( 'jquery', 'wcs-ladda-spin', 'wcs-ladda'  ),
				null,
				true
			);
		}

		wp_register_script(
			'wcs-builder',
			plugins_url() . '/weekly-class/assets/admin/js/min/builder-min.js',
			array( 'jquery', 'clipboard', 'jquery-ui-slider', 'jquery-ui-datepicker', 'wp-color-picker', 'wcs-fs-tooltip', 'wcs-ladda-jquery', 'vue-js', 'vue-resource' ),
			is_user_logged_in() ? rand() : WCS_VERSION
		);

		wp_register_style(
			'wcs-ladda',
			plugins_url() . '/weekly-class/assets/libs/ladda/css/ladda-themeless.min.css',
			null,
			false,
			'all'
		);
		wp_register_style( 'wcs-fs-tooltip', plugins_url() . '/weekly-class/assets/libs/formstone/css/tooltip.css' );
		wp_register_style( 'wcs-themify', plugins_url() . '/weekly-class/assets/libs/themify/themify-icons.css' );
		wp_register_style( 'wcs-builder', plugins_url() . '/weekly-class/assets/admin/css/builder.css', array( 'wp-color-picker', 'wcs-themify', 'wcs-fs-tooltip', 'wcs-ladda' ), is_user_logged_in() ? rand() : WCS_VERSION );

	}


	function schedule_page(){
		add_submenu_page( 'edit.php?post_type=class', __( 'Schedule Builder', 'WeeklyClass' ), __( 'Schedule Builder', 'WeeklyClass' ), 'manage_options', 'wcs_schedule', array( $this, 'schedule_page_hook' ) );
	}

	function schedule_page_hook(){

		wp_enqueue_style( 'wcs-builder' );
 		wp_enqueue_script( 'wcs-builder' );

    $id = isset( $_REQUEST['schedule_id'] ) ? $_REQUEST['schedule_id'] : -1;

    if( $id !== -1 ){
	    $data = get_option( "__wcs_schedule_$id" );
	    $data = empty( $data ) ? array() : $data;
			$data = maybe_unserialize( $data );
    }

    $views = apply_filters( 'wcs_builder_views', array() );

		if( isset( $_REQUEST['action'] ) && intval( $_REQUEST['action'] ) === 2 ){
			$id = isset( $_REQUEST['schedule_id'] ) ? $_REQUEST['schedule_id'] : 0;
			delete_option( "__wcs_schedule_$id" );
			$notification = __( 'The Schedule has been deleted!', 'WeeklyClass' );
		}

		if( isset( $_REQUEST['action'] ) && intval( $_REQUEST['action'] ) === 3 ){
			$id 	= isset( $_REQUEST['schedule_id'] ) ? $_REQUEST['schedule_id'] : 0;
			$new_id = intval( get_option( '__wcs_schedule_count', 0 ) ) + 1;
			update_option( "__wcs_schedule_$new_id", get_option( "__wcs_schedule_$id" ) );
			update_option( '__wcs_schedule_count', $new_id );
			$notification = __( 'The Schedule has been duplicated!', 'WeeklyClass' );
		}

		?>

		<?php if( isset( $_REQUEST['action'] ) && intval( $_REQUEST['action'] ) === 1 ) : ?>
		<?php

			global $_wp_admin_css_colors;
			$admin_colors = $_wp_admin_css_colors;
			$color_scheme = $admin_colors[ get_user_option('admin_color') ]->colors;

			$taxonomies = WeeklyClass::get_object_taxonomies( 'class', 'objects' );
			$builder_taxonomies = array();

			foreach( $taxonomies as $tax => $taxonomy ){
				$terms_array = array();
				$terms = get_terms( $tax, array( 'hide_empty' => false ) );
				foreach( $terms as $key => $term ){
					$terms_array[] = $term;
				}
				$builder_taxonomies[str_replace('-','_',$tax)] = array(
					'label' => $taxonomy->label,
					'terms' => $terms_array
				);
			}
		?>
		<script type="text/javascript">
			/* <![CDATA[ */
			var wcs_builder = <?php echo json_encode( array(
				'page_title' => array( __( 'Add New Schedule', 'WeeklyClass' ), __( 'Edit Schedule', 'WeeklyClass' ) ),
				'title_placeholder' => __( 'Enter title here', 'WeeklyClass' ),
				'sections' => array(
					'style' => array(
						'title' 	=> __( 'Choose Style', 'WeeklyClass' ),
						'desc' 		=> sprintf( __( 'Choose how you would like your schedule to look like. You have %d available styles.', 'WeeklyClass' ), count( $views ) ),
						'options' => apply_filters( 'wcs_builder_views', array() )
					),
					'contents' => array(
						'title' 	=> __( 'Choose Contents', 'WeeklyClass' ),
						'desc' 		=> __( 'Choose what content should be included in your schedule. If you do not allow filters this default content will be limited and cannot be changed. If you allow filters, then this default content can be changed by filters.', 'WeeklyClass' ),
					),
					'filters' => array(
						'title' 	=> __( 'Available Filters', 'WeeklyClass' ),
						'desc' 		=> __( 'Choose what filters should be included in your schedule. If you allow filters, then this filters will be avaialble.', 'WeeklyClass' ),
					),
					'labels'	=> array(
						'title'		=> __( 'Labels', 'WeeklyClass' ),
						'desc' 		=> __( 'For each label enter your desired value', 'WeeklyClass' ),
						'options' => apply_filters( 'wcs_builder_labels', array() )
					),
					'design' => array(
						'title' 	=> __( 'Design Options', 'WeeklyClass' ),
						'desc'		=> __( 'For each label enter your desired value', 'WeeklyClass' ),
						'options'	=> apply_filters( 'wcs_builder_colors', array() )
					),
					'save' => array(
						'title' => __( 'How many days to show?', 'WeeklyClass' ),
						'select' => array(
							0 	=> __( 'Show all Days', 'WeeklyClass' ),
							1 	=> __( '1 Day', 'WeeklyClass' ),
							2 	=> __( '2 Days', 'WeeklyClass' ),
							3 	=> __( '3 Days', 'WeeklyClass' ),
							4 	=> __( '4 Days', 'WeeklyClass' ),
							5 	=> __( '5 Days', 'WeeklyClass' ),
							6 	=> __( '6 Days', 'WeeklyClass' ),
							7 	=> __( '1 Week', 'WeeklyClass' ),
							14 	=> __( '2 Weeks', 'WeeklyClass' ),
							21 	=> __( '3 Weeks', 'WeeklyClass' ),
							28 	=> __( '4 Weeks', 'WeeklyClass' )
						),
					),
					'limit' => array(
						'title' => __( 'How many events to show?', 'WeeklyClass' ),
						'value' => 0,
			      'atts'  => array(
			        'min' => 0,
			        'max' => 30,
			        'step' => 1,
			      ),
						'desc'		=> __( 'Leave 0 to show all', 'WeeklyClass' ),
					),
					'display' => array(
						'title' => __( 'Display Options', 'WeeklyClass' ),
						'options' => apply_filters( 'wcs_builder_display_options', array() )
					),
					'modal' => array(
						'title' => __( 'Modal Options', 'WeeklyClass' ),
						'options' => apply_filters( 'wcs_builder_modal_options', array() )
					),
					'filters_options' => array(
						'title' => __( 'Filters Options', 'WeeklyClass' ),
						'options' => apply_filters( 'wcs_builder_filters_options', array() )
					),
				),
				'delete_href' => admin_url( "edit.php?post_type=class&page=wcs_schedule&action=2&schedule_id=$id" ),
				'delete_confirm'	=> __( 'Are you sure you want to delete this?', 'WeeklyClass' ),
				'delete_label'		=> __( 'Move to Trash', 'WeeklyClass' ),
				'save_label'			=> __( 'Save', 'WeeklyClass' ),
				'saving_label'		=> __( 'Saving', 'WeeklyClass' ),
				'notifications'		=> array(
					'updated' => __( 'The Schedule has been updated!', 'WeeklyClass' ),
					'created' => __( 'The Schedule has been created!', 'WeeklyClass' ),
					'error'		=> __( 'Something went wrong. Please try again.', 'WeeklyClass' )
				)
			)); ?>;

			var wcs_taxonomies = <?php echo json_encode( $builder_taxonomies ); ?>;
			var wcs_conditions = <?php echo json_encode( apply_filters( 'wcs_builder_conditions', array() ) ); ?>;
			var wcs_posts 		 = <?php echo json_encode( apply_filters( 'wcs_builder_posts', array() ) ); ?>;
			var wcs_timetable_data = <?php $tdata = apply_filters( 'wcs_filter_builder_tdata', maybe_unserialize( get_option( "__wcs_schedule_" . $id ) ) ); echo json_encode( $tdata ? array_merge( $tdata, array( 'id' => $id ) ) : array( 'id' => $id ) ) ?>;
			var wcs_builder_nonce = '<?php echo wp_create_nonce( 'wcs-update-schedule-nonce' ); ?>';
			/* ]]> */
		</script>

		<!-- Switch Template -->
		<script type="text/x-template" id="wcs_templates_switch">
			<label class="wcs-switcher" :data-title="description">
				<input type="checkbox" :id="'switch_' + name" :name="name" :checked="filter_var(value)" v-on:change="updateModelValue">
				<span class="wcs-switcher__switch"><span class="wcs-switcher__handler"></span></span>
				{{title}}
			</label>
		</script>


		<!-- Text Template -->
		<script type="text/x-template" id="wcs_templates_text">
			<div class="wcs-input-text">
				<label>{{title}}</label>
				<input type="text" :name="name" :placeholder="placeholder" :value="value" v-on:input.lazy="updateModelValue">
				<p class="wcs-builder__description" v-html="description"></p>
			</div>
		</script>

		<!-- Slider Template -->
		<script type="text/x-template" id="wcs_templates_slider">
			<div class="wcs-slider">
				<label>{{title}} <span class="slider_value">{{prefix}}{{value}}{{suffix}}</span></label>
				<input type="hidden" :name="name" :value="value" v-on:input="updateModelValue">
				<div class="wcs_slider"></div>
				<p v-if="description" class="wcs-builder__description" v-html="description"></p>
			</div>
		</script>

		<!-- Datepicker Template -->
		<script type="text/x-template" id="wcs_templates_datepicker">
			<div class="wcs-datepicker">
				<label>{{title}}:</label>
				<input v-show="value.length > 0" type="text" readonly="readonly" :value="value">
				<a v-show="value.length > 0" href="#" v-on:click.prevent="clearValue()"> <i class="ti-close"></i> <?php _e( 'Clear Date', 'WeeklyClass' ) ?></a>
				<div class="wcs_datepicker"></div>
				<p v-if="description" class="wcs-builder__description" v-html="description"></p>
			</div>
		</script>

		<!-- Group Template -->
		<script type="text/x-template" id="wcs_templates_group">
			<div><p v-if="title">{{title}}</p>
				<div class="wcs-labels">
					<label v-for="option in options" class="wcs-labels__label--with-icon" :class="value == option.value ? 'selected' : ''">
						<input type="radio" :name="name" :value="option.value" :checked="option.value == value" v-on:change="updateModelValue">
						<i class="ti-size-xxl" :class="option.icon"></i> {{option.name}}
					</label>
				</div>
			</div>
		</script>


		<!-- Select Template -->
		<script type="text/x-template" id="wcs_templates_select">
			<div><label v-if="title">{{title}}</label>
				<select :name="name" v-on:change="updateModelValue">
					<option v-for="(option, key) in options" :value="key" :selected="key == value">{{option}}</option>
				</select>
				<p v-if="description" class="wcs-builder__description" v-html="description"></p>
			</div>
		</script>

		<!-- Color Template -->
		<script type="text/x-template" id="wcs_templates_color">
			<div class="wcs-builder__color-field">
				<label class="wcs-builder__info-label">{{title}}</label>
				<input type="text" :name="name" :value="value" class="wp-color-picker-field" :data-default-color="color" v-on:change="updateModelValue" v-on:input="updateModelValue">
			</div>
		</script>

		<style type="text/css" scoped>
			.wcs-labels__label--with-icon.selected::before{
				background-color: <?php echo $color_scheme[3] ?> !important;
			}
			.wcs-labels__label--with-icon.selected{
				color: <?php echo self::contrast( $color_scheme[3], 1, 1 ) ?>
			}
			.clear-all,
			.wcs-switcher input:checked + span{
				color: <?php echo $color_scheme[3] ?>;
			}
			.wcs-builder__tabs-data .selected{
				color: <?php echo $color_scheme[2] ?>;
			}
			.ui-state-active{
				background-color: <?php echo $color_scheme[3] ?> !important;
				color: <?php echo self::contrast( $color_scheme[3], 1, 1 ) ?> !important;
			}
			.wcs-builder__tabs-data .selected{
				background-color: <?php echo self::hex2rgb( $color_scheme[2], 0.125 ) ?> !important;
			}
		</style>

		<div class="wrap wcs-builder wcs-builder--edit" id="wcs-builder-app" v-cloak>
			<div v-show="notification.show" class="wcs-notice" :class="notification.success ? 'wcs-notice--updated' : 'wcs-notice--error'"><p>{{notification.message}}</p></div>
			<h1 class="wcs-builder__title">{{form.id == -1 ? builder.page_title[0] : builder.page_title[1]}}</h1>
			<form class="wcs-builder__container" v-on:submit.prevent="updateSchedule">
				<div class="wcs-builder__content">
					<input type="text" :placeholder="builder.title_placeholder" v-model="form['title']" name="title" size="30" :value="form['title']" id="title" spellcheck="true" autocomplete="off">
					<p v-show="form.id != -1" class="wcs-builder__shortcode clipboard-text" :data-clipboard-text="'[wcs-schedule id=' + form.id + ']'" data-clipboard-success="<?php _e('Copied to clipboard', 'WeeklyClass') ?>" data-title="<?php _e('Click to copy', 'WeeklyClass') ?>">[wcs-schedule id={{form.id}}]</p>
					<h2>{{builder.sections.style.title}}</h2>
					<p class="wcs-builder__description">{{builder.sections.style.desc}}</p>
					<div class="wcs-labels">
						<label v-for="style in builder.sections.style.options" class="wcs-labels__label--with-icon" :class="form['view'] == style.value ? 'selected' : ''">
							<input type="radio" :id="'view-' + style.value" :value="style.value" v-model="form['view']" v-on:change="changeView(style)" :checked="style.value == form['view']">
							<i class="ti-size-xxl" :class="style.icon"></i> <span>{{style.title}}</span>
						</label>
					</div><!-- #views -->
					<h2>{{builder.sections.contents.title}}</h2>
					<div v-show="isVisible('section_contents')">
						<ol class="wcs-builder__tabs-nav">
							<li v-for="(taxonomy, tax) in taxonomies" v-on:click="tabs['content'] = tax" :class="tabs['content'] === tax ? 'active' : ''"><a>{{taxonomy.label}}</a></li>
						</ol>
						<ol class="wcs-builder__tabs-data">
							<li v-for="(taxonomy, tax) in taxonomies" v-show="tabs['content'] === tax" class="active">
								<div class="wcs-labels__columns">
									<template v-for="(term, index) in taxonomy.terms">
										<label :class="isTaxSelected(term, tax)">
											<input class="radio-boxes" :id="'content-' + tax + '-' + index" type="checkbox" v-model="form['content'][tax]" :value="term.term_id" v-bind:value="term.term_id">{{term.name}}</label>
									</template>
								</div><!-- .wcs-labels__columns -->
								<div class="clear-all" v-on:click="form['content'][tax] = []" v-show="form['content'][tax].length >= 2">
									<label><?php _e( 'Clear all', 'WeeklyClass' ) ?></label>
								</div>
							</li>
						</ol>
						<p class="wcs-builder__description">{{builder.sections.contents.desc}}</p>
					</div>
					<div v-show="isVisible('section_single')">
						<select v-model="form.single">
							<template v-for="(option, index) in posts.posts">
								<option v-if="index === 0" value=""><?php _e( 'Choose Post', 'WeeklyClass' ) ?></option>
								<option :value="option.ID">{{option.post_title}}</option>
							</template>
						</select>
					</div>
					<!-- Filters -->
					<div v-show="isVisible('section_filters')">
						<h2>{{builder.sections.filters.title}}</h2>
						<ol class="wcs-builder__tabs-nav">
							<li v-for="(taxonomy, tax) in taxonomies" v-on:click="tabs['filters'] = tax" :class="tabs['filters'] === tax ? 'active' : ''"><a>{{taxonomy.label}}</a></li>
						</ol>
						<ol class="wcs-builder__tabs-data">
							<li v-for="(taxonomy, tax) in taxonomies" v-show="tabs['filters'] === tax" class="active">
								<div class="wcs-labels__columns">
									<template v-for="(term, index) in taxonomy.terms">
										<label :class="form['filters'][tax].indexOf(term.slug) >= 0 ? 'selected' : ''"><input class="radio-boxes" type="checkbox" v-model="form['filters'][tax]" :value="term.slug">{{term.name}}</label>
									</template>
								</div><!-- .wcs-labels__columns -->
								<div class="clear-all" v-on:click="form['filters'][tax] = []" v-show="form['filters'][tax].length >= 2">
									<label><?php _e( 'Clear all', 'WeeklyClass' ) ?></label>
								</div>
							</li>
						</ol>
						<p class="wcs-builder__description">{{builder.sections.filters.desc}}</p>
					</div><!-- #wcs_options__filters -->
					<!-- Labels -->
					<h2>{{builder.sections.labels.title}}</h2>
					<p class="wcs-builder__description">{{builder.sections.labels.desc}}</p>
					<div class="wcs-cols">
						<div v-for="(taxonomy, tax) in taxonomies" v-show="isVisible( 'label_' + tax )" class="wcs-cols__col-2">
							<label>{{taxonomy.label}}<?php  _e( ' Label', 'WeeklyClass' ) ?></label>
							<input type="text" v-model="form['label_'+tax]" placeholder="<?php _e( 'Enter the type label here', 'WeeklyClass' ) ?>">
						</div>
						<div v-for="(taxonomy, tax) in taxonomies" v-show="isVisible( 'label_filter_' + tax ) && isVisible('section_filters')" class="wcs-cols__col-2">
							<label>{{taxonomy.label}}<?php  _e( ' Filter Label', 'WeeklyClass' ) ?></label>
							<input type="text" v-model="form['label_filter_'+tax]" placeholder="<?php _e( 'Enter the type label here', 'WeeklyClass' ) ?>">
						</div>
						<div v-for="label in builder.sections.labels.options" v-show="isVisible(label.id)" class="wcs-cols__col-2">
							<label>{{label.title}}</label>
							<input type="text" v-model="form[label.id]" :value="form[label.id]" :placeholder="label.placeholder">
						</div>
					</div><!-- .wcs-cols -->
					<!-- Colors -->
					<h2>{{builder.sections.design.title}}</h2>
					<p class="wcs-builder__description">{{builder.sections.design.desc}}</p>
					<ol class="wcs-builder__tabs-nav">
						<li v-for="(colors, key) in builder.sections.design.options" v-on:click="tabs['design'] = colors.id" :class="tabs['design'] === colors.id ? 'active' : ''" v-if="isVisible('tab_colors_' + colors.id) && colors.colors.length > 0"><a>{{colors.label}}</a></li>
					</ol>
					<ol class="wcs-builder__tabs-data">
						<li v-for="(colors, key) in builder.sections.design.options" v-show="tabs['design'] === colors.id" class="active">
							<control-color v-if="isVisible('tab_colors_' + colors.id)" v-for="(color, key) in colors.colors" v-show="isVisible(color.id)" v-model="form[color.id]" :name="color.id" :title="color.title" :description="color.desc" :color="color.default"></control-color>
						</li>
					</ol>
				</div><!-- .wcs-builder__content -->
				<div class="wcs-builder__side">
					<!-- Save -->
					<div class="wcs-builder__box">
						<h4 v-show="isVisible('days')">{{builder.sections.save.title}}</h4>
						<select name="days" v-model="form['days']" v-show="isVisible('days')">
							<option v-for="(label, days) in builder.sections.save.select" v-bind:value="days">{{label}}</option>
						</select>
						<control-slider v-show="isVisible('limit')" v-model="form['limit']" :value="form['limit']" :name="'limit_events'" :title="builder.sections.limit.title" :description="builder.sections.limit.desc" :attributes="builder.sections.limit.atts"></control-slider>
						<a v-show="form.id != -1" :href="builder.delete_href" onclick="return confirm(builder.delete_confirm)" id="trash">{{builder.delete_label}}</a>
						<button name="publish" id="save" class="button button-primary button-large" data-spinner-color="#ffffff" data-style="expand-right" data-size="xs" :data-save="builder.save_label" :data-saving="builder.saving_label"><span class="ladda-label">{{builder.save_label}}</span></button>
					</div><!-- .wcs-builder__box -->
					<!-- Default Options -->
					<h2>{{builder.sections.display.title}}</h2>
					<template v-for="(option, key) in builder.sections.display.options">
						<control-switch v-if="option.type == 'switch'" v-show="isVisible(option.id)" v-model="form[option.id]" :value="form[option.id]" :name="option.id" :title="option.title" :description="option.desc"></control-switch>
						<control-text v-if="option.type == 'text'" v-show="isVisible(option.id)" v-model="form[option.id]" :value="form[option.id]" :name="option.id" :title="option.title" :description="option.desc" :placeholder="option.placeholder"></control-text>
						<control-datepicker v-if="option.type == 'date'" v-show="isVisible(option.id)" v-model="form[option.id]" :value="form[option.id]" :name="option.id" :title="option.title" :description="option.desc" :placeholder="option.placeholder" :options="option.options"></control-datepicker>
					</template>
					<!-- Generated Options for each view -->
					<div v-for="view in builder.sections.style.options" v-if="view.options" v-show="isVisible('section_side_' + view.value) && view.options.length > 0">
						<h2>{{view.title}} <?php _e( 'Options', 'WeeklyClass' ) ?></h2>
						<template v-for="(option, key) in view.options">
							<control-switch v-if="option.type == 'switch'" v-show="isVisible(option.id)" v-model="form[option.id]" :value="form[option.id]" :name="option.id" :title="option.title" :description="option.desc"></control-switch>
							<control-text v-if="option.type == 'text'" v-show="isVisible(option.id)" v-model="form[option.id]" :value="form[option.id]" :name="option.id" :title="option.title" :description="option.desc" :placeholder="option.placeholder"></control-text>
							<control-group v-if="option.type == 'group'" v-show="isVisible(option.id)" v-model="form[option.id]" :value="form[option.id]" :name="option.id" :title="option.title" :description="option.desc" :options="option.options"></control-group>
							<control-slider v-if="option.type == 'slider'" v-show="isVisible(option.id)" v-model="form[option.id]" :value="form[option.id]" :name="option.id" :title="option.title" :description="option.desc" :attributes="option.atts"></control-slider>
							<control-select v-if="option.type == 'select'" v-show="isVisible(option.id)" v-model="form[option.id]" :value="form[option.id]" :name="option.id" :title="option.title" :description="option.desc" :options="option.options"></control-select>
						</template>
					</div>
					<!-- Filters Options -->
					<div v-show="isVisible('section_filters')">
						<h2>{{builder.sections.filters_options.title}}</h2>
						<template v-for="(option, key) in builder.sections.filters_options.options">
							<control-switch v-if="option.type == 'switch'" v-show="isVisible(option.id)" v-model="form[option.id]" :value="form[option.id]" :name="option.id" :title="option.title" :description="option.desc"></control-switch>
							<control-text v-if="option.type == 'text'" v-show="isVisible(option.id)" v-model="form[option.id]" :value="form[option.id]" :name="option.id" :title="option.title" :description="option.desc" :placeholder="option.placeholder"></control-text>
							<control-group v-if="option.type == 'group'" v-show="isVisible(option.id)" v-model="form[option.id]" :value="form[option.id]" :name="option.id" :title="option.title" :description="option.desc" :options="option.options"></control-group>
						</template>
					</div>
					<!-- Modal Options -->
					<div v-show="isVisible('section_modal')">
						<h2>{{builder.sections.modal.title}}</h2>
						<template v-for="(option, key) in builder.sections.modal.options">
							<control-switch v-if="option.type == 'switch'" v-show="isVisible(option.id)" v-model="form[option.id]" :value="form[option.id]" :name="option.id" :title="option.title" :description="option.desc"></control-switch>
							<control-text v-if="option.type == 'text'" v-show="isVisible(option.id)" v-model="form[option.id]" :value="form[option.id]" :name="option.id" :title="option.title" :description="option.desc" :placeholder="option.placeholder"></control-text>
							<control-group v-if="option.type == 'group'" v-show="isVisible(option.id)" v-model="form[option.id]" :value="form[option.id]" :name="option.id" :title="option.title" :description="option.desc" :options="option.options"></control-group>
						</template>
					</div>
				</div><!-- .wcs-builder__side -->
			</form><!-- .wcs-builder__container -->
		</div><!-- .wcs-builder -->
		<?php else : ?>
			<div class="wrap wcs-builder wcs-builder--list">
				<h1><?php _e( 'Schedule Builder', 'WeeklyClass' ) ?> <a href="<?php echo admin_url( 'edit.php?post_type=class&page=wcs_schedule&action=1' ) ?>" class="page-title-action">Add New</a></h1>
				<?php if( isset( $notification ) ) : ?>
					<div class="updated notice"><p><?php echo $notification; ?></p></div>
				<?php endif;  ?>
				<br>
				<table class="wp-list-table wcs-table-admin widefat fixed hover striped posts">
					<thead>
						<tr>
							<th scope="col" class="manage-column"><?php _e( 'Title', 'WeeklyClass' ) ?></th>
							<th scope="col" class="manage-column"><?php _e( 'View', 'WeeklyClass' ) ?></th>
							<th scope="col" class="manage-column"><?php _e( 'Days', 'WeeklyClass' ) ?></th>
							<th scope="col" class="manage-column"><?php _e( 'Shortcode', 'WeeklyClass' ) ?></th>
							<th scope="col" class="manage-column" style="text-align: center"><?php _e( 'Actions', 'WeeklyClass' ) ?></th>
						</tr>
					</thead>
					<tbody id="the-list">
						<?php $count = intval( get_option( '__wcs_schedule_count', 0 ) );  $count_real = $count; if( intval( $count ) === 0 ) : ?>
							<tr class="no-items"><td class="colspanchange" colspan="4"><?php _e( 'No classes found.', 'WeeklyClass') ?></td></tr>
						<?php else : while( $count > 0 ) : $data = get_option( "__wcs_schedule_$count" ); if( ! empty( $data ) ) : $data = maybe_unserialize( $data ); ?>
							<tr>
								<td>
									<a class="link-post" href="<?php echo admin_url( "edit.php?post_type=class&page=wcs_schedule&action=1&schedule_id=$count" ) ?>">
										<?php echo empty( $data['title'] ) ? _e( '(no title)', 'WeeklyClass' ) : $data['title']; ?>
									</a>
								</td>
								<td><?php echo $views[ $data['view'] ]['title'];?></td>
								<td><?php $days = $data['days']; echo empty( $days ) ? __( 'Show All', 'WeeklyClass' ) : $days . __( ' Days', 'WeeklyClass') ?></td>
								<td>[wcs-schedule id=<?php echo $count?>]</td>
								<td style="text-align: center">
									<a href="<?php echo admin_url( "edit.php?post_type=class&page=wcs_schedule&action=2&schedule_id=$count" ) ?>" class="quick-action ti-trash" onclick="return confirm('<?php _e( 'Are you sure you want to delete this?', 'WeeklyClass' ) ?>')" data-title="<?php _e( 'Delete Schedule', 'WeeklyClass' ) ?>"></a>&nbsp;&nbsp;
									<a href="<?php echo admin_url( "edit.php?post_type=class&page=wcs_schedule&action=3&schedule_id=$count" ) ?>" class="quick-action ti-layers" onclick="return confirm('<?php _e( 'Are you sure you want to duplicate this?', 'WeeklyClass' ) ?>')" data-title="<?php _e( 'Duplicate Schedule', 'WeeklyClass' ) ?>"></a>&nbsp;&nbsp;
									<a href="<?php echo admin_url( "edit.php?post_type=class&page=wcs_schedule&action=1&schedule_id=$count" ) ?>" class="quick-action ti-pencil-alt" data-title="<?php _e( 'Edit Schedule', 'WeeklyClass' ) ?>"></a>
																</td>
							</tr>

						<?php endif; $count--; endwhile; endif; ?>
					</tbody>
					<tfoot>
						<tr>
							<td scope="col" class="manage-column"><?php _e( 'Title', 'WeeklyClass' ) ?></td>
							<td scope="col" class="manage-column"><?php _e( 'View', 'WeeklyClass' ) ?></td>
							<th scope="col" class="manage-column"><?php _e( 'Days', 'WeeklyClass' ) ?></th>
							<td scope="col" class="manage-column"><?php _e( 'Shortcode', 'WeeklyClass' ) ?></td>
							<td scope="col" class="manage-column" style="text-align: center"><?php _e( 'Actions', 'WeeklyClass' ) ?></td>
						</tr>
					</tfoot>
				</table>
				<br>
				<a href="#" class="wcs-import__show"><label for="wcs-import__checkbox"><?php _e( 'Backup Options', 'WeekyClass' ) ?></label></a><br>
				<?php
					$url_download = add_query_arg( array(
					    'post_type' => 'class',
					    'page' 		=> 'wcs_schedule',
					    'download' 	=> 'json'
					), admin_url( '/edit.php' ) );

					$url_upload = add_query_arg( array(
					    'post_type' => 'class',
					    'page' 		=> 'wcs_schedule',
					    'upload' 	=> 'json'
					), admin_url( '/edit.php' ) );
				?>
				<input type="checkbox" hidden id='wcs-import__checkbox'>
				<form action="<?php echo $url_upload ?>" method="post" enctype="multipart/form-data" class="wcs-import">
					<input type="file" name='json_file'>
					<input type="submit" class="button button-primary" value="Import Backup">
				</form>
				<?php if( count( $count_real ) > 0 ) : ?><a href="<?php echo $url_download ?>" class="button" target='_blank'><?php _e( 'Download Backup', 'WeeklyClass' ) ?></a><?php endif; ?>

			</div>
		<?php endif; ?>


		<?php

	}


}

new WCS_Builder();


?>
