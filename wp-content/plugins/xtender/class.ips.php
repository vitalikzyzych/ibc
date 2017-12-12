<?php

new XtenderIPS();

class XtenderIPS {

	public $_options;
	public $_filters;
	public $_prefix;

	public function __construct(){

		$this->_prefix = defined( 'XTENDER_THEMPREFIX' ) && XTENDER_THEMPREFIX === 'leisure' ? 'leisure' : XTENDER_PREFIX;

		/** Set Default Array */
		add_action( 'admin_init', array( $this, 'options' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );

		add_action( 'add_meta_boxes', array( $this, 'ips' ) );

		add_action( 'save_post', array( $this, 'save_ips' ), 10, 2 );

		add_action( 'init', array( $this, 'filters_init' ), 11 );

	}

	function filters_init(){

		global $wp_customize;

		if( ! is_admin() && is_null( $wp_customize ) ){

      $filters = array();
			$this->_filters = apply_filters( 'xtender_ips_filters', $filters );

			foreach( $this->_filters as $filter ){
				add_filter( "theme_mod_$filter", array( $this, 'filter_mods' ), 1, 1);
			}

		}

	}

	public static function is_blog(){

    $is_blog = wp_cache_get( 'is_blog', 'xtender' );

    if( ! $is_blog ){

      global  $post;

      $post_type = get_post_type( $post );

      if ( ( ( is_archive() ) || ( is_author() ) || ( is_category() ) || (is_home() ) || ( is_single() ) || ( is_tag() ) ) && ( $post_type == 'post' ) ) {

        $is_blog = 1;

      } else {

        $is_blog = 0;

      }

      wp_cache_set( 'is_blog', $is_blog, 'xtender' );

    }

    return filter_var( $is_blog, FILTER_VALIDATE_BOOLEAN );

  }

	public static function get_the_id(){

    $id = wp_cache_get( 'id', 'xtender' );

    if( ! $id ){

      if( is_singular() ){

        global $post;

        $id = $post->ID;

      } elseif( self::is_blog() && get_option( 'show_on_front' ) === 'page' ){

        $id = get_option( 'page_for_post' );

      } else{

        $id = get_queried_object_id();

      }

      wp_cache_set( 'id', $id, 'xtender' );

    }

    return $id;

  }


	function filter_mods( $mod, $id = false ){

		$id = self::get_the_id();

		$filter_name = current_filter();
		$filter_name = str_replace( 'theme_mod_', '', $filter_name );

		$id = ! $id ? get_queried_object_id() : $id;

		$default = get_post_meta( $id, "_" . XTENDER_PREFIX . "_$filter_name", true );

		return ! empty( $default ) ? $default : $mod;

	}

	/** Set Default Array */
	function options(){

    $options = array();
    $options = apply_filters( 'xtender_ips_array', $options );
		$this->_options = $options;

	}

	/*	Enqueue Admin Scripts
    ================================================= */
	function enqueue( $hook ) {
	 	if ( in_array( get_current_screen()->id, get_post_types()) ) {

		 	wp_enqueue_script('thickbox');
		 	wp_enqueue_style('thickbox');

	 		wp_enqueue_style(
	 			'curly-meta-boxes-css',
	 			XTENDER_URL . 'assets/admin/css/meta-boxes.css',
	 			null,
	 			filter_var( WP_DEBUG, FILTER_VALIDATE_BOOLEAN ) ? rand() : null,
	 			'all'
	 		);

	 		wp_enqueue_style( 'wp-color-picker' );
	 		wp_enqueue_media();

	 		wp_enqueue_script('wp-color-picker');
	 		wp_enqueue_script(
		    	'curly-color-picker',
		    	XTENDER_URL . 'assets/admin/js/wp-color-picker-alpha.js' ,
		    	array( 'wp-color-picker' ),
		    	rand(),
		    	true
		    );
	 		wp_enqueue_script(
	 			'curly-ips',
	 			XTENDER_URL . 'assets/admin/js/ips-min.js',
	 			array( 'jquery', 'curly-color-picker' ),
				filter_var( WP_DEBUG, FILTER_VALIDATE_BOOLEAN ) ? rand() : null
	 		);

	 		// Get Current Color Scheme
	 		global $_wp_admin_css_colors;
	 		$admin_colors = $_wp_admin_css_colors;
	 		$color_scheme = $admin_colors[get_user_option('admin_color')]->colors;

	 		$color_scheme = '
	 			#individual-page-settings .form-control .slider.ui-slider .ui-slider-handle{
	 				background: '.$color_scheme[3].';
	 			}
	 			#individual-page-settings-wrapper > ul > li.ui-state-active > a{
	 				border-left: 5px solid '.$color_scheme[3].';
	 				border-top-color: '.$color_scheme[3].';
	 				padding-left: 15px;
	 			}';

	 		wp_add_inline_style('curly-meta-boxes-css', $color_scheme);
	 	}
	}

	/*	Individual Page Settings
    ================================================= */
	function ips(){
		$post_types = get_post_types();

		foreach ($post_types as $post_type) {
			add_meta_box(
				'individual-page-settings',
				'Individual Page Settings',
				array( $this, 'ips_cb' ),
				$post_type,
				'normal',
				'high'
			);
		}
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
		$options = $this->_options;

		if( $options ){

			foreach( $options as $option ){

				if( $option['type'] !== 'tab' ){

					$values[] = $this->_prefix . '_' . $option['id'];

					if( isset( $option['theme_mod'] ) ){

						$defaults[ $this->_prefix . '_' . $option['id'] ] = $option['theme_mod'];

					}

					elseif( isset( $option['default'] ) ) {

						$defaults[ $this->_prefix . '_' . $option['id'] ] = $option['default'];

					}

					else{

						$defaults[ $this->_prefix. '_' . $option['id'] ] = null;

					}

				}

			}

		}

		/** Update Post Meta or Delete Empty Post Meta */
		if( $values ){

			$underscore = $this->_prefix === 'leisure' ? '' : '_';

			foreach ( $values as $value ) {

				if( isset( $_POST[$value] ) && ! empty( $_POST[$value] ) ) {

					if( array_key_exists( $value, $defaults ) ){

						if ( $_POST[$value] == $defaults[ $value ] ) {

							delete_post_meta( $post_id, $underscore . $value );

						}

						else {

							update_post_meta( $post_id, $underscore . $value, wp_kses_post( $_POST[$value] ) );

						}

					}

					elseif( ! empty( $_POST[$value] ) ) {

						update_post_meta( $post_id, $underscore . $value, wp_kses_post( $_POST[$value] , null ) );

					}

					else {

						delete_post_meta( $post_id, $underscore . $value );

					}

				}

				else {

					delete_post_meta( $post_id, "_$value" );

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

	function set_value( $values, $value, $value_null = null ) {
		if( $this->_prefix === 'leisure' ){
			if( isset( $values[ $this->_prefix . '_' . $value ] ) ){
				if( ! empty( $values[ $this->_prefix . '_' . $value ][0] ) ){
					return $values[ $this->_prefix . '_' . $value ][0];
				} else {
					return $value_null;
				}
			} else {
				return $value_null;
			}
		} else {
			if( isset( $values[ '_' . $this->_prefix . '_' . $value ] ) ){
				if( ! empty( $values[ '_' . $this->_prefix . '_' . $value ][0] ) ){
					return $values[ '_' . $this->_prefix . '_' . $value ][0];
				} else {
					return $value_null;
				}
			} else {
				return $value_null;
			}
		}
	}

	/*	Individual Page Settings
    ================================================= */
	function ips_cb( $post ){

		/** Set value */


		/** Get Values */
		$values	= get_post_custom( $post->ID );

		/** Load Options Array */
		$options = $this->_options;

		if( empty( $options ) ){
			echo '<div id="individual-page-settings-wrapper"><p style="padding: 10px 20px;max-width: 500px">' . sprintf( __( 'Your theme does not declare support Individual Page Settings. Please use a theme crafted by %s, or any other compatible theme to enjoy this features.', 'xtender' ), '<a href="https://themeforest.net/user/curlythemes/portfolio?ref=CurlyThemes" target="_blank">Curly Themes</a>' ) . '</p></div>';
			return;
		}

		wp_nonce_field( 'my_meta_box_nonce', 'meta_box_nonce' );
		?>

    <div id="individual-page-settings-wrapper" class="xtender-tabs">
		<nav class="xtender-tabs__nav">
			<?php
				foreach( $options as $key => $option ) {
					if( $option['type'] === 'tab' ) {

						$tabs[$key] = $option['id'];

						$template 	= isset( $options[$key]['template'] ) ? $options[$key]['template'] : array();
						$show 		= isset( $options[$key]['show'] ) ? $options[$key]['show'] : '';

						if( $this->check_template( $post->ID,  $template ) && $this->check_show( $post->ID,  $show ) ) {

						?>

						<a href="#tab-<?php echo $option['id'] ?>" class="xtender-tab" data-tabs-group="tab_group"><?php echo $option['name'] ?></a>

			<?php 		}
					}
				}
			?>
		</nav>
		<ul class="xtender-tabs__data">
			<?php

				foreach( $tabs as $key => $tab ) {
					$template 	= isset( $options[$key]['template'] ) ? $options[$key]['template'] : array();
					$show 		= isset( $options[$key]['show'] ) ? $options[$key]['show'] : '';
					if( $this->check_template( $post->ID,  $template ) && $this->check_show( $post->ID,  $show ) ) { ?>

				<li id="tab-<?php echo $tab ?>">
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
											${ 'object_' . $id } = new XtenderMetaOption(
												$this->_prefix . '_' . $id,
												$name,
												$this->set_value( $values, $id, $default ),
												$desc,
												$choices
											);
											${ 'object_' . $id }->input();
										} break;

										case 'textarea' : {
											${ 'object_' . $id } = new XtenderMetaOption(
												$this->_prefix . '_' . $id,
												$name,
												$this->set_value( $values, $id, $default ),
												$desc,
												$choices
											);
											${ 'object_' . $id }->textarea();
										} break;

										case 'checkbox' : {
											${ 'object_' . $id } = new XtenderMetaOption(
												$this->_prefix . '_' . $id,
												$name,
												$this->set_value( $values, $id, $default ),
												$desc,
												$choices
											);
											${ 'object_' . $id }->checkbox();
										} break;
										case 'editor' : {
											${ 'object_' . $id } = new XtenderMetaOption(
												$this->_prefix . '_' . $id,
												$name,
												$this->set_value( $values, $id, $default ),
												$desc,
												$choices
											);
											${ 'object_' . $id }->editor();
										} break;
										case 'radio' : {
											${ 'object_' . $id } = new XtenderMetaOption(
												$this->_prefix . '_' . $id,
												$name,
												$this->set_value( $values, $id, $default ),
												$desc,
												$choices
											);
											${ 'object_' . $id }->radio();
										} break;
										case 'select' : {
											${ 'object_' . $id } = new XtenderMetaOption(
												$this->_prefix . '_' . $id,
												$name,
												$this->set_value( $values, $id, $default ),
												$desc,
												$choices
											);
											${ 'object_' . $id }->select();
										} break;
										case 'color' : {
											${ 'object_' . $id } = new XtenderMetaOption(
												$this->_prefix . '_' . $id,
												$name,
												$this->set_value( $values, $id, $default ),
												$desc,
												$choices
											);
											${ 'object_' . $id }->color();
										} break;
										case 'image' : {
											${ 'object_' . $id } = new XtenderMetaOption(
												$this->_prefix . '_' . $id,
												$name,
												array( $this->set_value( $values, $id . '_id', null ), $this->set_value( $values, $id, $default ) ),
												$desc,
												array(
													'upload_title' 	=> __( 'Upload Image', 'xtender' ),
													'upload_button' => __( 'Insert Image', 'xtender' ),
													'upload_link' 	=> __( 'Upload Image', 'xtender' ),
													'clear_link' 	=> __( 'Clear Image', 'xtender' )
												)
											);
											${ 'object_' . $id }->image();
										} break;
										case 'video' : {
											${ 'object_' . $id } = new XtenderMetaOption(
												$this->_prefix . '_' . $id,
												$name,
												$this->set_value( $values, $id, $default ),
												$desc,
												array(
													'upload_title' 	=> __( 'Upload Video', 'xtender' ),
													'upload_button' => __( 'Insert Video', 'xtender' ),
													'upload_link' 	=> __( 'Upload Video', 'xtender' ),
													'clear_link' 	=> __( 'Clear Video', 'xtender' )
												)
											);
											${ 'object_' . $id }->video();
										} break;
										case 'slider' : {
											${ 'object_' . $id } = new XtenderMetaOption(
												$this->_prefix . '_' . $id,
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

class XtenderMetaOption {

	private $_id;
	private $_label;
	private $_default;
	private $_description;
	private $_array;
	private $_class;


	public function __construct( $id = null, $label = null, $default = null, $description = null, $array = null, $class = null ) {

		$this->_id = $id;
		$this->_label = $label;
		$this->_default = $default;
		$this->_description = $description;
		$this->_array = $array;
		$this->_class = $class;

	}

	public function checkbox( $html = null ) {
		$html .= '<div class="form-control checkbox '.$this->_class.'" id="wrapper_'.$this->_id.'">';
			$html .= '<label for="'.$this->_id.'" class="name">'.$this->_label.'</label>';
				$html .= '<label class="description"><input type="checkbox" name="'.$this->_id.'" id="'.$this->_id.'" '.checked( filter_var( $this->_default, FILTER_VALIDATE_BOOLEAN ), true , false ).' />';
				$html .= ( $this->_description ) ? $this->_description : null;
				$html .= '</label>';
		$html .= '</div>';

		echo $html;
	}

	public function select( $html = null ) {
		$html .= '<div class="form-control select '.$this->_class.'" id="wrapper_'.$this->_id.'">';
			$html .= '<label for="'.$this->_id.'" class="name">'.$this->_label.'</label>';
			$html .= ( $this->_description ) ? '<span class="description">'.$this->_description.'</span>' : null;
			$html .= '<div>';
				$html .= '<select class="select" name="'.$this->_id.'" id="'.$this->_id.'">';

					foreach ( $this->_array as $key => $value ) {
						$html .= '<option value="'.$key.'" '.selected( $this->_default, $key, false ).'>';
						$html .= $value;
						$html .= '</option>';
					}

				$html .= '</select>';
			$html .= '</div>';
		$html .= '</div>';

		echo $html;
	}

	public function color( $html = null ) {
		$html .= '<div class="form-control color '.$this->_class.'" id="wrapper_'.$this->_id.'">';
			$html .= '<label for="'.$this->_id.'" class="name">'.$this->_label.'</label>';
			$html .= ( $this->_description ) ? '<span class="description">'.$this->_description.'</span>' : null;
				$html .= '<input class="color-picker" data-alpha="true" type="text" name="'.$this->_id.'" id="'.$this->_id.'" value="'.$this->_default.'" />';
		$html .= '</div>';

		echo $html;
	}

	public function image( $html = null ) {

		$clear_style = ( $this->_default ) ? 'style="display:inline-block"' : 'style="display:none"';

		$html .= '<div class="form-control image-field '.$this->_class.'" id="wrapper_'.$this->_id.'">';
			$html .= '<label for="'.$this->_id.'" class="name">'.$this->_label.'</label>';
			$html .= ( $this->_description ) ? '<span class="description">'.$this->_description.'</span>' : null;
			$html .= '<div>';
				$html .= '<input type="hidden" name="'.$this->_id.'_id" id="'.$this->_id.'_id" value="'.$this->_default[0].'">';
				$html .= '<input class="text-field" type="text" name="'.$this->_id.'" id="'.$this->_id.'" value="'.$this->_default[1].'" />';
				$html .= '<a href="#" class="image-upload-button button button-primary button-large" data-upload-title="'.$this->_array['upload_title'].'" data-upload-button="'.$this->_array['upload_button'].'">'.$this->_array['upload_link'].'</a>';
				$html .= '<a href="#" class="image-clear-button button button-large" '.$clear_style.'>'.$this->_array['clear_link'].'</a>';
				$html .= ( isset( $this->_default[1] ) ) ? '<img src="'.$this->_default[1].'" class="image-preview" />' : null;
			$html .= '</div>';
		$html .= '</div>';

		echo $html;
	}

	public function video( $html = null ) {

		$clear_style = ( $this->_default ) ? 'style="display:inline-block"' : 'style="display:none"';

		$html .= '<div class="form-control image-field '.$this->_class.'" id="wrapper_'.$this->_id.'">';
			$html .= '<label for="'.$this->_id.'" class="name">'.$this->_label.'</label>';
			$html .= '<div>';
				$html .= '<input class="text-field" type="text" name="'.$this->_id.'" id="'.$this->_id.'" value="'.$this->_default.'" />';
				$html .= '<a href="#" class="video-upload-button button button-primary button-large" data-upload-title="'.$this->_array['upload_title'].'" data-upload-button="'.$this->_array['upload_button'].'">'.$this->_array['upload_link'].'</a>';
				$html .= '<a href="#" class="video-clear-button button button-large" '.$clear_style.'>'.$this->_array['clear_link'].'</a>';
				$html .= ( $this->_description ) ? '<span class="description">'.$this->_description.'</span>' : null;
			$html .= '</div>';
		$html .= '</div>';

		echo $html;
	}

	public function slider( $html = null ) {
		$html .= '<div class="form-control slider-field" id="wrapper_'.$this->_id.'">';
			$html .= '<label for="'.$this->_id.'" class="name">'.$this->_label.'</label>';
			$html .= '<div style="position: relative;">';
				$html .= '<input type="hidden" name="'.$this->_id.'" id="'.$this->_id.'" value="'.$this->_default.'" />';
				$html .= '<div class="slider" id="'.$this->_id.'_slider"></div>';
				$html .= '<div class="slider_value">'.(( $this->_default ) ? $this->_default.$this->_array['suf'] :  null ).'</div>';
				$html .= ( $this->_description ) ? '<span class="description">'.$this->_description.'</span>' : null;
				$html .= '<script type="text/javascript">';
					$html .= 'jQuery(function() {
									jQuery( "#'.$this->_id.'_slider" ).slider({
										value: '.$this->_default.' ,
										step: '.$this->_array['step'].' ,
										min: '.$this->_array['min'].' ,
										max: '.$this->_array['max'].' ,
										slide: function( event, ui ) {
											jQuery(this).siblings(".slider_value").text( ui.value + "'.$this->_array['suf'].'" );
											jQuery(this).siblings("input[type=hidden]").val(ui.value);
										}
									});
								});';
				$html .= '</script>';
			$html .= '</div>';
		$html .= '</div>';

		echo $html;
	}

	public function editor( $html = null ) {

		ob_start(); wp_editor( $this->_default , $this->_id, array('textarea_rows' => 10 , 'teeny' => true) );

		$html .= '<div class="form-control" id="wrapper_'.$this->_id.'">';
			$html .= '<label for="'.$this->_id.'" class="name">'.$this->_label.'</label>';
			$html .= '<div>';
				$html .= ob_get_clean();
			$html .= '</div>';
		$html .= '</div>';

		echo $html;
	}

	public function input( $html = null ) {
		$html .= '<div class="form-control text-field" id="wrapper_'.$this->_id.'">';
			$html .= '<label for="'.$this->_id.'" class="name">'.$this->_label.'</label>';
			$html .= $this->_description ? '<span class="description">'.$this->_description.'</span>' : null;
			$html .= '<div>';
				$html .= '<input class="text-field" type="text" name="'.$this->_id.'" id="'.$this->_id.'" value="'.$this->_default.'" />';
			$html .= '</div>';
		$html .= '</div>';

		echo $html;
	}

	public function textarea( $html = null ) {
		$html .= '<div class="form-control textarea-field" id="wrapper_'.$this->_id.'">';
			$html .= '<label for="'.$this->_id.'" class="name">'.$this->_label.'</label>';
			$html .= $this->_description ? '<span class="description">'.$this->_description.'</span>' : null;
			$html .= '<div>';
				$html .= '<textarea class="text-field" type="text" name="'.$this->_id.'" id="'.$this->_id.'">'.$this->_default.'</textarea>';
			$html .= '</div>';
		$html .= '</div>';

		echo $html;
	}

	public function radio( $html = null ) {
		$selected = $this->_default ? $this->_default : key( $this->_array );
		$html .= '<div class="form-control radio" id="wrapper_'.$this->_id.'">';
			$html .= '<label for="'.$this->_id.'" class="name">'.$this->_label.'</label>';
			$html .= ( $this->_description ) ? '<span class="description">'.$this->_description.'</span>' : null;
			$html .= '<div>';

				foreach ($this->_array as $key => $value) {
					$html .= '<label class="'.$this->_id.'_'.$key.'">';
						$html .= '<input type="radio" name="'.$this->_id.'" id="'.$key.'" value="'.$key.'" '.checked( $selected, $key, false ).' />';
						$html .= $value;
					$html .= '</label>';
				}

			$html .= '</div>';
		$html .= '</div>';

		echo $html;
	}

}
?>
