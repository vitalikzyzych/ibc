<?php

class Curly_Extended_Menu extends Walker_Nav_Menu {
	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 )
	    {
	        $classes     = empty ( $item->classes ) ? array () : (array) $item->classes;

	        $class_names = join( ' ' , apply_filters( 'nav_menu_css_class' , array_filter( $classes ), $item ) );

	        ! empty ( $class_names ) and $class_names = ' class="'. esc_attr( $class_names ) . '"';

			$data_background = ( get_post_meta( $item->ID, '_menu_item_background', true ) ) ? 'data-background='.esc_attr( get_post_meta( $item->ID, '_menu_item_background', true ) ) : null;

	        $output .= "<li id='menu-item-$item->ID' $class_names $data_background>";

	        $attributes  = '';

	        ! empty( $item->attr_title ) and $attributes .= ' title="'  . esc_attr( $item->attr_title ) .'"';
	        ! empty( $item->target ) and $attributes .= ' target="' . esc_attr( $item->target     ) .'"';
	        ! empty( $item->xfn ) and $attributes .= ' rel="'    . esc_attr( $item->xfn        ) .'"';
	        ! empty( $item->url ) and $attributes .= ' href="'   . esc_attr( $item->url        ) .'"';

	        // insert description for top level elements only
	        // you may change this
	        $description = ( ! empty ( $item->description ) and 0 == $depth )
	            ? '<small class="nav_desc">' . esc_attr( $item->description ) . '</small>' : '';

	        $title = apply_filters( 'the_title', $item->title, $item->ID );

	        $item_output = $args->before
	            . "<a $attributes>"
	            . $args->link_before
	            . $title
	            . $description
	            . '</a> '
	            . $args->link_after
	            . $args->after;

	        // Since $output is called by reference we don't need to return anything.
	        $output .= apply_filters(
	            'walker_nav_menu_start_el'
	        ,   $item_output
	        ,   $item
	        ,   $depth
	        ,   $args
	        );
	    }
}

class Curly_Menu_Item_Custom_Fields {

	public static function load() {
		add_filter( 'wp_edit_nav_menu_walker', array( __CLASS__, '_filter_walker' ), 99 );
	}

	/**
	* Replace default menu editor walker with ours
	*
	* We don't actually replace the default walker. We're still using it and
	* only injecting some HTMLs.
	*
	* @since   0.1.0
	* @access  private
	* @wp_hook filter wp_edit_nav_menu_walker
	* @param   string $walker Walker class name
	* @return  string Walker class name
	*/
	public static function _filter_walker( $walker ) {

		$walker = 'Curly_Menu_Item_Custom_Fields_Walker';

		include( XTENDER_PATH . '/themes/leisure/class.leisure.walker.php' );

		return $walker;
	}
}
add_action( 'wp_loaded', array( 'Curly_Menu_Item_Custom_Fields', 'load' ), 9 );







class Curly_Menu_Item_Custom_Fields_Create {

	/**
	 * Initialize plugin
	 */
	public static function init() {
		add_action( 'menu_item_custom_fields', array( __CLASS__ , '_fields' ), 10, 3 );
		add_action( 'wp_update_nav_menu_item', array( __CLASS__, '_save' ), 10, 3 );
		add_filter( 'manage_nav-menus_columns', array( __CLASS__, '_columns' ), 99 );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'admin_enqueue' ) );
	}

	public static function admin_enqueue(){
		wp_enqueue_media();

		wp_localize_script( 'xtender-leisure-nav', 'data_object', array( 'button' => __( 'Remove Image', 'xtender' ) ) );
		wp_enqueue_script( 'xtender-leisure-nav', XTENDER_URL . '/assets/admin/js/nav-min.js');
		wp_enqueue_style( 'xtender-leisure-nav', XTENDER_URL . '/assets/admin/css/leisure-navigation.css');
	}


	/**
	 * Save custom field value
	 *
	 * @wp_hook action wp_update_nav_menu_item
	 *
	 * @param int   $menu_id         Nav menu ID
	 * @param int   $menu_item_db_id Menu item ID
	 * @param array $menu_item_args  Menu item data
	 */
	public static function _save( $menu_id, $menu_item_db_id, $menu_item_args ) {
		//check_admin_referer( 'update-nav_menu', 'update-nav-menu-nonce' );

		// Sanitize
		if ( ! empty( $_POST['_menu_item_background'][ $menu_item_db_id ] ) ) {
			$value = $_POST['_menu_item_background'][ $menu_item_db_id ];
		}
		else {
			$value = '';
		}

		// Update
		if ( ! empty( $value ) ) {
			update_post_meta( $menu_item_db_id, '_menu_item_background', $value );
		}
		else {
			delete_post_meta( $menu_item_db_id, '_menu_item_background' );
		}
	}


	/**
	 * Print field
	 *
	 * @param object $item  Menu item data object.
	 * @param int    $depth  Depth of menu item. Used for padding.
	 * @param array  $args  Menu item args.
	 * @param int    $id    Nav menu ID.
	 *
	 * @return string Form fields
	 */
	public static function _fields( $item, $depth, $args = array(), $id = 0 ) {

		$id = $item->ID;
		$image = esc_attr( get_post_meta( $item->ID, '_menu_item_background', true ) );

		?>
			<p class="field-custom description description-wide">
				<label for="bg-image-<?php echo esc_attr( $item->ID ) ?>">
					<?php _e( 'Background Image:', 'xtender' ) ?>
				</label>
				<a href="#" class="btn-upload-image" style="display: <?php echo ( ! $image ? 'block' : 'none' ) ?>;"><?php _e( 'Choose Image', 'xtender' ) ?></a>
				<?php printf( '<input type="hidden" value="%1$s" name="_menu_item_background[%2$d]" class="widefat code edit-menu-item-custom-field" id="bg-image-%2$d">', $image, $id ) ?>
				<?php echo ( $image ) ? '<img src="'.$image.'" alt="" class="image-preview">' : null;  ?>
				<a href="#" class="image-remove" style="display: <?php echo ( $image ? 'block' : 'none' ) ?>;"><?php _e( 'Remove Image', 'xtender' ); ?></a>
			</p>
		<?php
	}


	/**
	 * Add our field to the screen options toggle
	 *
	 * To make this work, the field wrapper must have the class 'field-custom'
	 *
	 * @param array $columns Menu item columns
	 * @return array
	 */
	public static function _columns( $columns ) {
		$columns['custom'] = __( 'Background Image', 'xtender' );

		return $columns;
	}
}
Curly_Menu_Item_Custom_Fields_Create::init();
