<?php

new WCSClassSettings();
/**
* Individual Page Settings
*/
class WCSClassSettings {

	public $_options;

	public function __construct(){

		//add_action( 'admin_init', array( $this, 'options' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );

		add_action( 'add_meta_boxes', array( $this, 'ips' ) );

		add_action( 'save_post', array( $this, 'save_ips' ), 10, 2 );

	}

	function options(){



		//$this->_options = ;

	}



	function enqueue( $hook ) {

		global $post_type;

		if( $post_type !== 'class' )
			return;

	 	wp_enqueue_script('thickbox');
	 	wp_enqueue_style('thickbox');
 		wp_enqueue_style(
 			'wcs-meta-boxes-css',
 			plugins_url() . '/weekly-class/assets/admin/css/meta-boxes.css',
 			null,
 			rand(),
 			'all'
 		);
 		wp_enqueue_style( 'wp-color-picker' );
 		wp_enqueue_media();
 		wp_enqueue_script(
	 		'wcs-hash',
 			plugins_url() . '/weekly-class/assets/libs/hashtabber/hashTabber.js',
 			array(),
 			false,
 			true
 		);
 		wp_enqueue_script('wp-color-picker');
 		wp_enqueue_script(
 			'wcs-ips',
 			plugins_url() . '/weekly-class/assets/admin/js/min/ips-min.js',
 			array( 'jquery', 'wcs-hash' ),
 			false,
 			true
 		);

 		// Get Current Color Scheme
 		global $_wp_admin_css_colors;
 		$admin_colors = $_wp_admin_css_colors;
 		$color_scheme = $admin_colors[get_user_option('admin_color')]->colors;

 		$color_scheme = '
 			#individual-page-settings .wcs-form-control .slider.ui-slider .ui-slider-handle{
 				background: '.$color_scheme[3].';
 			}
 			#individual-page-settings-wrapper > ul > li.ui-state-active > a{
 				border-left: 5px solid '.$color_scheme[3].';
 				border-top-color: '.$color_scheme[3].';
 				padding-left: 15px;
 			}';

 		wp_add_inline_style('curly-meta-boxes-css', $color_scheme);

	}

	/*	Individual Page Settings
    ================================================= */
	function ips(){

		add_meta_box(
			'wcs-settings',
			__( 'Class Settings', 'WeeklyClass' ),
			array( $this, 'ips_cb' ),
			'class',
			'normal',
			'high'
		);

	}

	/*	Save Individual Page Settings
    ================================================= */
    function save_ips( $post_id, $post ){

		// Bail if we're doing an auto save
		if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

		// if our nonce isn't there, or we can't verify it, bail
		if( ! isset( $_POST['meta_box_nonce'] ) || ! wp_verify_nonce( $_POST['meta_box_nonce'], 'my_meta_box_nonce' ) ) return;

		/* Get the post type object. */
		$post_type = get_post_type_object( $post->post_type );

		/* Check if the current user has permission to edit the post. */
		if ( ! current_user_can( $post_type->cap->edit_post, $post_id ) )
			return $post_id;

		$defaults = array();
		$options = apply_filters( 'wcs_ips_options', array() );

		if( $options ){
			foreach( $options as $option ){
				if( $option['type'] !== 'tab' ){
					$values[] = WCS_PREFIX . '_' . $option['id'];
					if( isset( $option['theme_mod'] ) ){
						$defaults[ WCS_PREFIX . '_' . $option['id'] ] = $option['theme_mod'];
					} elseif( isset( $option['default'] ) ) {
						$defaults[ WCS_PREFIX . '_' . $option['id'] ] = $option['default'];
					} else{
						$defaults[ WCS_PREFIX . '_' . $option['id'] ] = null;
					}
				}
			}
		}

		/** Update Post Meta or Delete Empty Post Meta */
		if( $values ){
			foreach ( $values as $value ) {
				if( isset( $_POST[$value] ) && ! empty( $_POST[$value] ) ) {
					if( array_key_exists( $value, $defaults ) ){
						if ( $_POST[$value] == $defaults[ $value ] ) {
							delete_post_meta( $post_id, $value );
						} else {
							update_post_meta( $post_id, $value, wp_kses_post( $_POST[$value] ) );
						}
					} elseif( ! empty( $_POST[$value] ) ) {
						update_post_meta( $post_id, $value, wp_kses_post( $_POST[$value] , null ) );
					} else {
						delete_post_meta( $post_id, $value );
					}
				} else {
					delete_post_meta( $post_id, $value );
				}
			}
		}

	}

	/** Check For Page Template */
	function check_template( $post_id, $array ) {

		if( ! is_array( $array ) ) return;
		if( ! $post_id ) return;

		if( ! empty( $array )){
			if ( in_array( get_post_meta( $post_id, '_wp_page_template', TRUE ), $array ) ) {
				return true;
			} else {
				return false;
			}
		} else {
			return true;
		}

	}

	/** Check For Post Type  */
	function check_show( $post_id, $type ) {

		if( ! isset( $type ) ) return;
		if( ! $post_id ) return;

		if( ! empty( $type )){
			if ( get_post_type( $post_id  ) === $type ) {
				return true;
			} else {
				return false;
			}
		} else {
			return true;
		}

	}

	/** Set value */
	function set_value( $values, $value, $value_null = null ) {
		if( isset( $values[ WCS_PREFIX . '_' . $value ] ) ){
			if( ! empty( $values[ WCS_PREFIX . '_' . $value ][0] ) ){
				return $values[ WCS_PREFIX . '_' . $value ][0];
			} else {
				return $value_null;
			}
		} else {
			return $value_null;
		}
	}

	/*	Individual Page Settings
    ================================================= */
	function ips_cb( $post ){

		/** Get Values */
		$values	= get_post_custom( $post->ID );

		/** Load Options Array */
		$options = apply_filters( 'wcs_ips_options', array() );

		wp_nonce_field( 'my_meta_box_nonce', 'meta_box_nonce' );
		?>

    <div id="class-settings-wrapper">
		<ul class="hashTabber-nav" data-hashtabber-id="wcs-tabs">
			<?php
				foreach( $options as $key => $option ) {
					if( $option['type'] === 'tab' ) {

						$tabs[$key] = $option['id'];

						$template 	= isset( $options[$key]['template'] ) ? $options[$key]['template'] : array();
						$show 		= isset( $options[$key]['show'] ) ? $options[$key]['show'] : '';

						if( $this->check_template( $post->ID,  $template ) && $this->check_show( $post->ID,  $show ) ) {

						?>

						<li><a href="#<?php echo $option['id'] ?>"><?php echo $option['name'] ?></a></li>

			<?php 		}
					}
				}
			?>
		</ul>
		<ul class="hashTabber-data" data-hashtabber-id="wcs-tabs">
			<?php

				foreach( $tabs as $key => $tab ) {
					$template 	= isset( $options[$key]['template'] ) ? $options[$key]['template'] : array();
					$show 		= isset( $options[$key]['show'] ) ? $options[$key]['show'] : '';
					if( $this->check_template( $post->ID,  $template ) && $this->check_show( $post->ID,  $show ) ) { ?>

				<li id="<?php echo $tab ?>">
					<div>
						<?php

							foreach( $options as $option ){

								if( $option['type'] !== 'tab' && $option['tab'] === $tab ){

									$id 	 	= $option['id'];
									$default 	= isset( $option['default'] ) ? $option['default'] : null;
									$desc 		= isset( $option['desc'] ) ? $option['desc'] : null;
									$choices	= isset( $option['choices'] ) ? $option['choices'] : null;
									$name		= isset( $option['name'] ) ? $option['name'] : null;
									$atts		= isset( $option['atts'] ) ? $option['atts'] : null;

									switch( $option['type'] ){
										case 'text' : {
											${ 'object_' . $id } = new WCSMetaOption(
												WCS_PREFIX . '_' . $id,
												$name,
												$this->set_value( $values, $id, $default ),
												$desc,
												$choices
											);
											${ 'object_' . $id }->input();
										} break;
										case 'checkbox' : {
											${ 'object_' . $id } = new WCSMetaOption(
												WCS_PREFIX . '_' . $id,
												$name,
												$this->set_value( $values, $id, $default ),
												$desc,
												$choices
											);
											${ 'object_' . $id }->checkbox();
										} break;
										case 'editor' : {
											${ 'object_' . $id } = new WCSMetaOption(
												WCS_PREFIX . '_' . $id,
												$name,
												$this->set_value( $values, $id, $default ),
												$desc,
												$choices
											);
											${ 'object_' . $id }->editor();
										} break;
										case 'radio' : {
											${ 'object_' . $id } = new WCSMetaOption(
												WCS_PREFIX . '_' . $id,
												$name,
												$this->set_value( $values, $id, $default ),
												$desc,
												$choices
											);
											${ 'object_' . $id }->radio();
										} break;
										case 'select' : {
											${ 'object_' . $id } = new WCSMetaOption(
												WCS_PREFIX . '_' . $id,
												$name,
												$this->set_value( $values, $id, $default ),
												$desc,
												$choices
											);
											${ 'object_' . $id }->select();
										} break;
										case 'color' : {
											${ 'object_' . $id } = new WCSMetaOption(
												WCS_PREFIX . '_' . $id,
												$name,
												$this->set_value( $values, $id, $default ),
												$desc,
												$choices
											);
											${ 'object_' . $id }->color();
										} break;
										case 'image' : {
											${ 'object_' . $id } = new WCSMetaOption(
												WCS_PREFIX . '_' . $id,
												$name,
												array( $this->set_value( $values, $id . '_id', null ), $this->set_value( $values, $id, $default ) ),
												$desc,
												array(
													'upload_title' 	=> __( 'Upload Image', 'WeeklyClass' ),
													'upload_button' => __( 'Insert Image', 'WeeklyClass' ),
													'upload_link' 	=> __( 'Upload Image', 'WeeklyClass' ),
													'clear_link' 	=> __( 'Clear Image', 'WeeklyClass' )
												)
											);
											${ 'object_' . $id }->image();
										} break;
										case 'video' : {
											${ 'object_' . $id } = new WCSMetaOption(
												WCS_PREFIX . '_' . $id,
												$name,
												$this->set_value( $values, $id, $default ),
												$desc,
												array(
													'upload_title' 	=> __( 'Upload Video', 'WeeklyClass' ),
													'upload_button' => __( 'Insert Video', 'WeeklyClass' ),
													'upload_link' 	=> __( 'Upload Video', 'WeeklyClass' ),
													'clear_link' 	=> __( 'Clear Video', 'WeeklyClass' )
												)
											);
											${ 'object_' . $id }->video();
										} break;
										case 'slider' : {
											${ 'object_' . $id } = new WCSMetaOption(
												WCS_PREFIX . '_' . $id,
												$name,
												$this->set_value( $values, $id, $default ),
												$desc,
												$atts
											);
											${ 'object_' . $id }->slider();
										} break;
									}

								}
							}
						?>
					</div>
				</li>
			<?php
				}
			}
			?>
		</ul>
    </div>
    <?php
	}
}
?>
