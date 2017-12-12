<?php

$CurlyWeeklyClass = new CurlyWeeklyClass();

/**
 * Weekly Class
 */
class CurlyWeeklyClass {

	public function __construct(){

		/** Register Weekly Classes */
		add_action( 'init', array( $this, 'classes_init' ) );

		/** Register Taxonomies */
		add_action( 'init', array( $this, 'classes_taxonomies' ), 0 );

		/** Load Assets */
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_assets' ) );

		add_action( 'admin_head', array( $this, 'remove_default_category_description' ) );

		add_filter( 'wcs-room_edit_form_fields', array( $this, 'cat_description' ), 10, 2 );
		add_filter( 'wcs-type_edit_form_fields', array( $this, 'cat_description' ), 10, 2 );
		add_filter( 'wcs-instructor_edit_form_fields', array( $this, 'cat_description' ), 10, 2 );

		add_action( 'wcs-type_add_form_fields',  array( $this, 'cat_color' ), 10, 2 );
		add_action( 'wcs-type_edit_form_fields', array( $this, 'cat_color_meta'), 30, 2 );

		add_action( 'edited_wcs-type', array( $this, 'save_cat_color' ), 10, 2 );
		add_action( 'create_wcs-type', array( $this, 'save_cat_color' ), 10, 2 );

		remove_filter( 'pre_term_description', 'wp_filter_kses' );
		remove_filter( 'term_description', 'wp_kses_data' );

		add_filter( 'manage_edit-class_columns', array( $this, 'date_columns' ) );
		add_action( 'manage_class_posts_custom_column', array( $this, 'date_column_content' ), 10, 2 );
		add_filter( 'manage_edit-class_sortable_columns', array( $this, 'sortable_date_column' ) );
		add_action( 'pre_get_posts', array( $this, 'timestamp_orderby' ) );

		add_filter( 'manage_edit-class_columns', array( $this, 'day_columns' ) );
		add_action( 'manage_class_posts_custom_column', array( $this, 'day_column_content' ), 10, 2 );


		add_filter( 'post_row_actions', array( $this, 'action_row' ), 10, 2);

		add_action( 'admin_action_wcs_duplicate', array( $this, 'duplicate_class' ) );
		add_action( 'admin_notices', array( $this, 'admin_notice' ) );

		add_filter( 'wcs_register_templates_filters', array( $this, 'register_default_filter_templates' ) );

	}

	function admin_notice(){

		if( ! isset( $_GET['wcs_notice'] ) )
			return;

		if( $_GET['wcs_notice'] !== 'success' )
			return;

		$class   = 'notice notice-success is-dismissible';
		$message = __( 'Your Class has been duplicated!', 'WeeklyClass' );

		printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message );

	}



	function duplicate_class(){

		global $wpdb;

		if ( ! ( isset( $_GET['post'] ) || isset( $_POST['post'] )  || ( isset($_REQUEST['action'] ) && 'wcs_duplicate' == $_REQUEST['action'] ) ) ) {
			wp_die('No post to duplicate has been supplied!');
		}

		/*
		 * get the original post id
		 */
		$post_id = ( isset( $_GET['post'] ) ? $_GET['post'] : $_POST['post'] );
		/*
		 * and all the original post data then
		 */
		$post = get_post( $post_id );

		/*
		 * if you don't want current user to be the new post author,
		 * then change next couple of lines to this: $new_post_author = $post->post_author;
		 */
		$current_user = wp_get_current_user();
		$new_post_author = $current_user->ID;

		/*
		 * if post data exists, create the post duplicate
		 */
		if ( isset( $post ) && $post != null ) {

			/*
			 * new post data array
			 */
			$args = array(
				'comment_status' => $post->comment_status,
				'ping_status'    => $post->ping_status,
				'post_author'    => $new_post_author,
				'post_content'   => $post->post_content,
				'post_excerpt'   => $post->post_excerpt,
				'post_name'      => $post->post_name,
				'post_parent'    => $post->post_parent,
				'post_password'  => $post->post_password,
				'post_status'    => $post->post_status,
				'post_title'     => $post->post_title,
				'post_type'      => $post->post_type,
				'to_ping'        => $post->to_ping,
				'menu_order'     => $post->menu_order
			);

			/*
			 * insert the post by wp_insert_post() function
			 */
			$new_post_id = wp_insert_post( $args );

			/*
			 * get all current post terms ad set them to the new post draft
			 */
			$taxonomies = get_object_taxonomies($post->post_type); // returns array of taxonomy names for post type, ex array("category", "post_tag");
			foreach ($taxonomies as $taxonomy) {
				$post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
				wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
			}

			/*
			 * duplicate all post meta just in two SQL queries
			 */
			$post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id");
			if (count($post_meta_infos)!=0) {
				$sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
				foreach ($post_meta_infos as $meta_info) {
					$meta_key = $meta_info->meta_key;
					$meta_value = addslashes($meta_info->meta_value);
					$sql_query_sel[]= "SELECT $new_post_id, '$meta_key', '$meta_value'";
				}
				$sql_query.= implode(" UNION ALL ", $sql_query_sel);
				$wpdb->query($sql_query);
			}


			/*
			 * finally, redirect to the edit post screen for the new draft
			 */
			wp_redirect( admin_url( 'edit.php?post_type=' . $post->post_type ). '&wcs_notice=success' );
			exit;
		} else {
			wp_die('Post creation failed, could not find original post: ' . $post_id);
		}

	}


	function register_default_filter_templates( $array ){

		$array['filters'] = array(
			'checkbox' 	=> wcs_get_template_part( 'filters/filter' ),
			'switch' 		=> wcs_get_template_part( 'filters/filter', 'switch' ),
			'radio' 		=> wcs_get_template_part( 'filters/filter', 'radio' ),
			//'select2' 		=> wcs_get_template_part( 'filters/filter', 'select2' )
		);

		$array['modals'] = array(
			'wrapper' 		=> wcs_get_template_part( 'modal/modal', 'wrapper' ),
			'normal' 		=> wcs_get_template_part( 'modal/modal' ),
			'large' 		=> wcs_get_template_part( 'modal/modal', 'large' ),
			'taxonomy' 	=> wcs_get_template_part( 'modal/modal', 'taxonomy' )
		);

		$array['misc'] = array(
			'more' => wcs_get_template_part( 'misc/button', 'more' ),
			'loader' => wcs_get_template_part( 'misc/loader' )
		);

		return $array;

	}

	function action_row( $actions, $post ){
		if ( current_user_can('edit_posts') && $post->post_type === "class" ){
	        $action = array( 'duplicate_class' => '<a href="admin.php?action=wcs_duplicate&amp;post='.$post->ID.'">' . __( 'Duplicate Class', 'WeeklyClass' ) . '</a>' );
	        $actions = array_slice( $actions, 0, 2, true ) + $action + array_slice( $actions, 2, NULL, true );
	    }
	    return $actions;
	}


	function timestamp_orderby( $query ) {

	    if( ! is_admin() )
	    	return $query;

	    $orderby = $query->get( 'orderby');

	    if( 'class_timestamp' == $orderby ) {
        $query->set('meta_key','_wcs_timestamp');
        $query->set('orderby','meta_value_num');
	    }
	}


	function sortable_date_column( $columns ) {
    $columns['class_date'] = 'class_timestamp';
		return $columns;
	}


	function date_columns($columns) {

	    $date = array( 'class_date' => __( 'Starting','WeeklyClass' ) );

	    $columns = array_slice( $columns, 0, 2, true ) + $date + array_slice( $columns, 2, NULL, true );

	    return $columns;

	}

	function day_columns($columns) {

	    $date = array( 'class_day' => __( 'Day of the Week','WeeklyClass' ) );

	    $columns = array_slice( $columns, 0, 3, true ) + $date + array_slice( $columns, 3, NULL, true );

	    return $columns;

	}

	function day_column_content( $column_name, $post_id ) {

	    if ( 'class_day' !== $column_name )
	        return;

		$timestamp = get_post_meta( $post_id, '_wcs_timestamp', true );

		if( ! $timestamp || empty( $timestamp ) )
			return;

    $date = date_i18n( 'l', $timestamp );

    echo $date;

	}

	function date_column_content( $column_name, $post_id ) {

	    if ( 'class_date' !== $column_name )
	        return;

		$timestamp = get_post_meta( $post_id, '_wcs_timestamp', true );

		if( ! $timestamp || empty( $timestamp ) )
			return;

    $date = date_i18n( esc_attr( get_option('date_format') ), $timestamp ) . '<br>' . date_i18n( esc_attr( get_option('time_format') ), $timestamp );

    echo $date;

	}


	function admin_assets($hook) {

		if( $hook === 'edit-tags.php' ) {

			wp_enqueue_script( 'wcs-taxonomies', plugins_url() . '/weekly-class/assets/admin/js/min/taxonomies-min.js', array( 'jquery', 'wp-color-picker' ), null );

			wp_enqueue_style( 'wp-color-picker' );

		}

		if( $hook === 'edit.php' ) {

			wp_enqueue_style( 'wcs-admin-columns', plugins_url() . '/weekly-class/assets/admin/css/columns.css', null, false, false );

		}

	}


	function save_cat_color( $term_id ){

		if ( isset( $_POST['term_meta'] ) ) {

			$t_id = $term_id;
			$term_meta = get_option( "taxonomy_$t_id" );

			$cat_keys = array_keys( $_POST['term_meta'] );

			foreach ( $cat_keys as $key ) {
				if ( isset ( $_POST['term_meta'][$key] ) ) {
					$term_meta[$key] = $_POST['term_meta'][$key];
				}
			}

			// Save the option array.
			update_option( "taxonomy_$t_id", $term_meta );
		}

	}


	function cat_color(){

	?>

		<div class="form-field">
			<label for="term_meta[color]"><?php _e( 'Color', 'WeeklyClass' ); ?></label>
			<input type="text" name="term_meta[color]" id="term_meta[color]" value="" class="wp-color-picker-field">
			<p class="description"><?php _e( 'Choose your type color','WeeklyClass' ); ?></p>
		</div>

	<?php
	}

	function cat_color_meta( $term ){

		$t_id = $term->term_id;

		$term_meta = get_option( "taxonomy_$t_id" ); ?>

		<table class="form-table">
			<tr class="form-field">
				<th scope="row" valign="top"><label for="term_meta[color]"><?php _e( 'Color', 'WeeklyClass' ); ?></label></th>
				<td>
					<input type="text" name="term_meta[color]" id="term_meta[color]" value="<?php echo esc_attr( $term_meta['color'] ) ? esc_attr( $term_meta['color'] ) : ''; ?>" class="wp-color-picker-field">
					<p class="description"><?php _e( 'Choose your type color','WeeklyClass' ); ?></p>
				</td>
			</tr>
		</table>

		<?php

	}

	function cat_description($tag){
    ?>
        <table class="form-table">
            <tr class="form-field">
                <th scope="row" valign="top"><label for="description"><?php _ex('Description', 'Taxonomy Description'); ?></label></th>
                <td>
                <?php
                    $settings = array('wpautop' => true, 'media_buttons' => true, 'quicktags' => true, 'textarea_rows' => '15', 'textarea_name' => 'description' );
                    wp_editor(wp_kses_post($tag->description , ENT_QUOTES, 'UTF-8'), 'cat_description', $settings);
                ?>
                <br />
                <p class="description"><?php _e('The description is not prominent by default; however, some themes may show it.'); ?></p>
                </td>
            </tr>
        </table>
    <?php
	}

	function remove_default_category_description(){

	    global $current_screen;

	    if ( isset( $current_screen->id ) && in_array( $current_screen->id, array( 'edit-wcs-room', 'edit-wcs-type', 'edit-wcs-instructor' ) ) && in_array( $current_screen->taxonomy, array( 'wcs-room', 'wcs-type', 'wcs-instructor' ) ) ){
		    wp_enqueue_media();

	    ?>
	        <script type="text/javascript">
	        jQuery(function($) {
	            jQuery('textarea#description').closest('tr.form-field').remove();
	        });
	        </script>
	    <?php
	    }
	}


	public static function object_to_list( $object, $tag ){

		$list = '';

		if( ! empty( $object ) ){
			foreach( $object as $key => $value ){

				$separator = filter_var( preg_match( '/#/', $value->name ), FILTER_VALIDATE_BOOLEAN ) ? '&nbsp;' : ',';
				$list .= ! empty( $value->description ) && filter_var( $value->description, FILTER_VALIDATE_URL ) ? "<a href='" . esc_url_raw( $value->description ) . "'>{$value->name}</a>$separator " : ( empty( $value->description ) ? "{$value->name}$separator " : "<a href='#' class='wcs-modal-call' data-wcs-id='{$value->term_id}' data-wcs-method='1' data-wcs-type='wcs-instructor'>{$value->name}</a>$separator " );

			}
			return substr( trim($list), 0, -1 );

		} else {

			return;

		}

	}


	/** Register Taxonomies */
	function classes_taxonomies() {

		$settings = wcs_get_settings();

		/** Add Class Types */
		$labels = array(
			'name'              => _x( 'Class Types', 'taxonomy general name', 'WeeklyClass' ),
			'singular_name'     => _x( 'Class Type', 'taxonomy singular name', 'WeeklyClass' ),
			'search_items'      => __( 'Search Class Types', 'WeeklyClass' ),
			'all_items'         => __( 'All Class Types', 'WeeklyClass' ),
			'parent_item'       => __( 'Parent Class', 'WeeklyClass' ),
			'parent_item_colon' => __( 'Parent Class:', 'WeeklyClass' ),
			'edit_item'         => __( 'Edit Class', 'WeeklyClass' ),
			'update_item'       => __( 'Update Class', 'WeeklyClass' ),
			'add_new_item'      => __( 'Add New Class Type', 'WeeklyClass' ),
			'new_item_name'     => __( 'New Class Type', 'WeeklyClass' ),
			'menu_name'         => __( 'Class Types', 'WeeklyClass' )
		);

		$labels = apply_filters( 'wcs_tax_labels', $labels, 'wcs-type' );

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => false,
			'wcs_icon'          => 'ti-folder',
      'wcs_labels'        => array(
        'all'   => __( 'All', 'WeeklyClass' )
      )
		);

		if( wp_validate_boolean( $settings['wcs_classes_archive'] ) ) unset( $args['query_var'] );

		register_taxonomy( 'wcs-type', array( 'class' ), $args );


		/** Add Rooms */
		$labels = array(
			'name'              => _x( 'Locations', 'taxonomy general name', 'WeeklyClass' ),
			'singular_name'     => _x( 'location', 'taxonomy singular name', 'WeeklyClass' ),
			'search_items'      => __( 'Search Locations', 'WeeklyClass' ),
			'all_items'         => __( 'All Locations', 'WeeklyClass' ),
			'parent_item'       => __( 'Location', 'WeeklyClass' ),
			'parent_item_colon' => __( 'Location:', 'WeeklyClass' ),
			'edit_item'         => __( 'Edit Location', 'WeeklyClass' ),
			'update_item'       => __( 'Update Location', 'WeeklyClass' ),
			'add_new_item'      => __( 'Add New Location', 'WeeklyClass' ),
			'new_item_name'     => __( 'New Location', 'WeeklyClass' ),
			'separate_items_with_commas' => __( 'Separate locations with commas', 'WeeklyClass' ),
			'add_or_remove_items'        => __( 'Add or remove locations', 'WeeklyClass' ),
			'choose_from_most_used'      => __( 'Choose from the most used locations', 'WeeklyClass' ),
			'not_found'                  => __( 'No locations found.', 'WeeklyClass' ),
			'menu_name'         => __( 'Locations', 'WeeklyClass' ),
		);

		$labels = apply_filters( 'wcs_tax_labels', $labels, 'wcs-room' );

		$args = array(
			'hierarchical'      => false,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => false,
			'wcs_icon'          => 'ti-user',
      'wcs_labels'        => array(
        'all'   => __( 'All', 'WeeklyClass' )
      )
		);

		if( wp_validate_boolean( $settings['wcs_classes_archive'] ) ) unset( $args['query_var'] );

		register_taxonomy( 'wcs-room', array( 'class' ), $args );

		/** Add Instructors */
		$labels = array(
			'name'                       => _x( 'Instructors', 'taxonomy general name', 'WeeklyClass' ),
			'singular_name'              => _x( 'Instructor', 'taxonomy singular name', 'WeeklyClass' ),
			'search_items'               => __( 'Search  Instructors', 'WeeklyClass' ),
			'popular_items'              => __( 'Popular Instructors', 'WeeklyClass' ),
			'all_items'                  => __( 'All Instructors', 'WeeklyClass' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => __( 'Edit Instructor', 'WeeklyClass' ),
			'update_item'                => __( 'Update Instructor', 'WeeklyClass' ),
			'add_new_item'               => __( 'Add New Instructor', 'WeeklyClass' ),
			'new_item_name'              => __( 'New Instructor Name', 'WeeklyClass' ),
			'separate_items_with_commas' => __( 'Separate instructors with commas', 'WeeklyClass' ),
			'add_or_remove_items'        => __( 'Add or remove instructors', 'WeeklyClass' ),
			'choose_from_most_used'      => __( 'Choose from the most used instructors', 'WeeklyClass' ),
			'not_found'                  => __( 'No instructors found.', 'WeeklyClass' ),
			'menu_name'                  => __( 'Instructors', 'WeeklyClass' )
		);

		$labels = apply_filters( 'wcs_tax_labels', $labels, 'wcs-instructor' );

		$args = array(
			'hierarchical'          => false,
			'labels'                => $labels,
			'show_ui'               => true,
			'show_admin_column'     => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => false,
			'wcs_icon'          => 'ti-location',
      'wcs_labels'        => array(
        'all'   => __( 'All', 'WeeklyClass' )
      )
		);

		if( wp_validate_boolean( $settings['wcs_classes_archive'] ) ) unset( $args['query_var'] );

		register_taxonomy( 'wcs-instructor', 'class', $args );


	}



	/** Register Dance Classes */
	function classes_init(){

		$settings = wcs_get_settings();

		$labels = array(
			'name'               => _x( 'Classes', 'post type general name', 'WeeklyClass' ),
			'singular_name'      => _x( 'Class', 'post type singular name', 'WeeklyClass' ),
			'menu_name'          => _x( 'Classes', 'admin menu', 'WeeklyClass' ),
			'name_admin_bar'     => _x( 'Class', 'add new on admin bar', 'WeeklyClass' ),
			'add_new'            => _x( 'Add New', 'class', 'WeeklyClass' ),
			'add_new_item'       => __( 'Add New Class', 'WeeklyClass' ),
			'new_item'           => __( 'New Class', 'WeeklyClass' ),
			'edit_item'          => __( 'Edit Class', 'WeeklyClass' ),
			'view_item'          => __( 'View Class', 'WeeklyClass' ),
			'all_items'          => __( 'All Classes', 'WeeklyClass' ),
			'search_items'       => __( 'Search Classes', 'WeeklyClass' ),
			'parent_item_colon'  => __( 'Parent Classes:', 'WeeklyClass' ),
			'not_found'          => __( 'No classes found.', 'WeeklyClass' ),
			'not_found_in_trash' => __( 'No classes found in Trash.', 'WeeklyClass' )
		);

		$class_slug = esc_attr( get_option( 'wcs_slug', 'class' ) );

		$args = array(
			'labels'             => apply_filters( 'wcs_post_type_labels', $labels ),
			'public'             => true,
			'publicly_queryable' => filter_var( esc_attr( get_option( 'wcs_single', true ) ), FILTER_VALIDATE_BOOLEAN ),
			'exclude_from_search' => true,
			'show_ui'            => true,
			'show_in_nav_menus'  => false,
			'show_in_menu'       => true,
			'query_var'          => false,
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false,
			'rewrite'			=> array(
				'slug' 	=> ! empty( $class_slug ) ? $class_slug : 'class'
			),
			//'menu_position'      => null,
			'menu_icon'      => 'dashicons-calendar-alt',
			'supports'           => array( 'title', 'editor', 'excerpt' )
		);

		if( wp_validate_boolean( $settings['wcs_classes_archive'] ) ) unset( $args['exclude_from_search'] );

		register_post_type( 'class', $args );
	}

}

?>
