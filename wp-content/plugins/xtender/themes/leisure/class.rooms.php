<?php

/**
* Hotel Rooms
*/
class XtenderLeisureRooms {

	public function __construct(){

		/** Register Hotel Rooms */
		add_action( 'init', array( $this, 'rooms_init' ) );

		/** Register Taxonomies */
		add_action( 'init', array( $this, 'room_taxonomies' ), 0 );

		/** Taxonomies Meta Fields */
		add_action( 'amenity_add_form_fields', array( $this, 'amenities_meta' ), 10, 2 );

		/** Taxonomy Custom Fields  */
		add_action( 'amenity_edit_form_fields', array( $this, 'taxonomy_custom_fields' ), 10, 2 );

		/** Taxonomy Custom Fields Save Action */
		add_action( 'edited_amenity', array( $this, 'save_taxonomy_custom_fields' ), 10, 2 );
		add_action( 'create_amenity', array( $this, 'save_taxonomy_custom_fields' ), 10, 2 );

		/** Widget Areas */
		add_action( 'widgets_init', array( $this, 'widgets' ) );

		/** Filter Navigation */
		add_filter('nav_menu_css_class', array( $this, 'menu_class' ) );

	}



	/** Filter Navigation */
	function menu_class( $classes ){
	     switch ( get_post_type() ){
	     	case 'room':
	     		$classes = array_filter( $classes, array( $this, "remove_parent_classes" ) );
	     		break;
	     }
		return $classes;
	}
	function remove_parent_classes( $class ){
		return $class === 'current_page_parent' ? '' : $class;
	}



	/* Adds a box to the main column on the Post and Page edit screens */
	function ninja_forms_add_custom_box() {
		add_meta_box(
			'ninja_forms_selector',
			__( 'Append A Ninja Form', 'ninja-forms'),
			'ninja_forms_inner_custom_box',
			'room',
			'side',
			'low'
		);
	}




	/** Taxonomy Custom Fields Save Callback */
	function save_taxonomy_custom_fields( $term_id ) {
	    if ( isset( $_POST['term_meta'] ) ) {
	        $t_id = $term_id;
	        $term_meta = get_option( "taxonomy_term_$t_id" );
	        $cat_keys = array_keys( $_POST['term_meta'] );
	            foreach ( $cat_keys as $key ){
	            if ( isset( $_POST['term_meta'][$key] ) ){
	                $term_meta[$key] = $_POST['term_meta'][$key];
	            }
	        }
	        //save the option array
	        update_option( "taxonomy_term_$t_id", $term_meta );
	    }
	}




	/** Taxonomy Custom Fields */
	function taxonomy_custom_fields($tag) {
	    $t_id = $tag->term_id;
	    $term_meta = get_option( "taxonomy_term_$t_id" );
	    $icon = esc_attr( $term_meta['icon'] );
	?>

	<tr class="form-field">
		<th scope="row" valign="top">
			<label for="icon"><?php _e( 'Amenity Icon', 'xtender' ); ?></label>
		</th>
		<td>
			<input type="text" name="term_meta[icon]" id="term_meta[icon]" size="25" value="<?php echo $icon ? $icon : ''; ?>"><br />
			<span class="description"><?php _e('The list of available icons can be found in the documentation.', 'xtender'); ?></span>
		</td>
	</tr>

	<?php
	}



	/** Taxonomies Meta Fields */
	function amenities_meta() {
		?>
		<div class="form-field">
			<label for="term_meta[icon]"><?php _e( 'Amenity Icon', 'xtender' ); ?></label>
			<input type="text" name="term_meta[icon]" id="term_meta[icon]" value="">
			<p class="description"><?php _e( 'Enter icon number. The list of available icons can be found in the documentation.','xtender' ); ?></p>
		</div>
	<?php
	}



	/** Register Taxonomies */
	function room_taxonomies() {

		/** Add Room Types */
		$labels = array(
			'name'              => _x( 'Room Types', 'taxonomy general name', 'xtender' ),
			'singular_name'     => _x( 'Room Type', 'taxonomy singular name', 'xtender' ),
			'search_items'      => __( 'Search Room Types', 'xtender' ),
			'all_items'         => __( 'All Room Types', 'xtender' ),
			'parent_item'       => __( 'Parent Type', 'xtender' ),
			'parent_item_colon' => __( 'Parent Type:', 'xtender' ),
			'edit_item'         => __( 'Edit Type', 'xtender' ),
			'update_item'       => __( 'Update Type', 'xtender' ),
			'add_new_item'      => __( 'Add New Room Type', 'xtender' ),
			'new_item_name'     => __( 'New Room Type', 'xtender' ),
			'menu_name'         => __( 'Room Types', 'xtender' ),
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'type' ),
		);

		register_taxonomy( 'type', array( 'room' ), $args );

		/** Add Amenities */
		$labels = array(
			'name'                       => _x( 'Room Amenities', 'taxonomy general name', 'xtender' ),
			'singular_name'              => _x( 'Amenity', 'taxonomy singular name', 'xtender' ),
			'search_items'               => __( 'Search  Room Amenities', 'xtender' ),
			'popular_items'              => __( 'Popular Room Amenities', 'xtender' ),
			'all_items'                  => __( 'All Amenities', 'xtender' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => __( 'Edit Amenity', 'xtender' ),
			'update_item'                => __( 'Update Amenity', 'xtender' ),
			'add_new_item'               => __( 'Add New Amenity', 'xtender' ),
			'new_item_name'              => __( 'New Amenity Name', 'xtender' ),
			'separate_items_with_commas' => __( 'Separate amenities with commas', 'xtender' ),
			'add_or_remove_items'        => __( 'Add or remove amenities', 'xtender' ),
			'choose_from_most_used'      => __( 'Choose from the most used amenities', 'xtender' ),
			'not_found'                  => __( 'No amenities found.', 'xtender' ),
			'menu_name'                  => __( 'Room Amenities', 'xtender' ),
		);

		$args = array(
			'hierarchical'          => false,
			'labels'                => $labels,
			'show_ui'               => true,
			'show_admin_column'     => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'amenity' ),
		);

		register_taxonomy( 'amenity', 'room', $args );
	}



	/** Widget Areas */
	function widgets(){
		if ( function_exists( 'register_sidebar' ) )
			register_sidebar( array(
			'name'			 => __('Rooms Widgets Area', 'xtender'),
			'id'			 => 'sidebar_rooms',
			'before_widget'	 => '<aside id="%1$s" class="sidebar-widget %2$s animated">',
			'after_widget' 	 => '</aside>',
			'before_title'	 => '<h4 class="widget-title">',
			'after_title'	 => '</h4>',
		));
	}




	/** Register Hotel Rooms */
	function rooms_init(){
		$labels = array(
			'name'               => _x( 'Rooms', 'post type general name', 'xtender' ),
			'singular_name'      => _x( 'Room', 'post type singular name', 'xtender' ),
			'menu_name'          => _x( 'Rooms', 'admin menu', 'xtender' ),
			'name_admin_bar'     => _x( 'Room', 'add new on admin bar', 'xtender' ),
			'add_new'            => _x( 'Add New', 'room', 'xtender' ),
			'add_new_item'       => __( 'Add New Room', 'xtender' ),
			'new_item'           => __( 'New Room', 'xtender' ),
			'edit_item'          => __( 'Edit Room', 'xtender' ),
			'view_item'          => __( 'View Room', 'xtender' ),
			'all_items'          => __( 'All Rooms', 'xtender' ),
			'search_items'       => __( 'Search Rooms', 'xtender' ),
			'parent_item_colon'  => __( 'Parent Rooms:', 'xtender' ),
			'not_found'          => __( 'No rooms found.', 'xtender' ),
			'not_found_in_trash' => __( 'No rooms found in Trash.', 'xtender' )
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'_builtin' 			 => false,
			'rewrite'            => array( 'slug' => get_theme_mod( 'rooms_slug', 'rooms' ) ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'menu_icon'   		 => 'dashicons-star-filled',
			'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
		);

		register_post_type( 'room', $args );
	}
}

new XtenderLeisureRooms();

class CurlyPageGallery {

	public function __construct(){

		add_action( 'add_meta_boxes', array( $this, 'meta_box') );
		add_action( 'save_post', array( $this, 'save_meta_box_data' ) );

		/** Load Assets */
		add_action( 'admin_enqueue_scripts', array( $this, 'load_assets' ) );

	}

	/** Add Meta Box */
	function meta_box(){
		$screens = array( 'room' );

		foreach ( $screens as $screen ) {
			add_meta_box(
				'curly_gallery_metabox',
				__( 'Page Gallery', 'xtender' ),
				array( $this, 'meta_box_callback'),
				$screen,
				'side'
			);
		}

	}

	/** Add Meta Box Callback */
	public function meta_box_callback( $post ) {

		wp_nonce_field( 'curly_gallery_metabox', 'gallery_meta_box_nonce' );

		$ids = '';
		echo '<div class="inside-container">';
		$galleries = substr( get_post_meta( $post->ID, '_leisure_gallery', true ), 0, -1 );

		echo '<p>'.__('Choose the images that you want to display as an image gallery:','xtender').'</p>';
		echo '<div class="images" id="gallery-images">';
			if( $galleries ){
				$galleries = explode( ',', $galleries );
				foreach( $galleries as $key => $gallery ){
					$ids .= $gallery . ',';
					echo wp_get_attachment_image( $gallery, 'thumbnail' );
				}
			}
		echo '</div>';

		echo "<input type='hidden' id='curly_galleries' name='curly_galleries' value='$ids'>";

		echo '</div>';

		echo '<div class="actions"><a href="#" id="gallery-upload-button" class="button button-primary">'.__( 'Select Images', 'xtender' ).'</a> ';
		echo '<a href="#" id="gallery-clear-button" class="delete">'.__( 'Clear Gallery', 'xtender' ).'</a></div>';

	}

	/** Save Meta Box */
	public function save_meta_box_data( $post_id ) {

		if ( ! isset( $_POST['gallery_meta_box_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['gallery_meta_box_nonce'], 'curly_gallery_metabox' ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return;
			}

		} else {

			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
		}

		if ( ! isset( $_POST['curly_galleries'] ) ) {
			return;
		}

		$data = sanitize_text_field( maybe_serialize( $_POST['curly_galleries'] ) );
		update_post_meta( $post_id, '_leisure_gallery', $data );
	}

	/** Load Assets */
	function load_assets(){

		wp_enqueue_media();

		wp_enqueue_script(
			'curly-gallery',
			XTENDER_URL . 'assets/admin/js/media-uploader-min.js'
		);

		wp_enqueue_style(
			'curly-gallery',
			XTENDER_URL . 'assets/admin/css/gallery.css',
			null,
			false,
			'all'
		);
	}

}

new CurlyPageGallery();

/**
* Curly Room Ratings
*/
class XtenderRoomRatings {

	public $_rating_1;
	public $_rating_2;
	public $_rating_3;
	public $_rating_4;
	public $_defaults;

	public function __construct(){

		/** Save Ratings */
		add_action( 'comment_post', array( $this, 'save_comment_meta_data' ) );

		/** Rating Fields */
		add_filter( 'curly_rating_form_fields', array( $this, 'fields' ), 10, 1);

		/** Rating Score */
		add_filter( 'curly_rating_score', array( $this, 'rating_score' ), 10, 1 );

		/** Default Ratings */
		$this->_defaults = array(
			__( 'Comfort', 'xtender' ),
			__( 'Position', 'xtender' ),
			__( 'Price', 'xtender' ),
			__( 'Quality', 'xtender' )
		);
		$this->_rating_1 = get_theme_mod( 'rating_1', $this->_defaults[0] );
		if( ! empty( $this->_rating_1 ) ){
			$this->_rating_1 = array(
				'name'	=> get_theme_mod( 'rating_1', $this->_rating_1 ),
				'slug'	=> 1,
				'html'	=> $this->rating_field( $this->_rating_1, 1 )
			);
		}
		$this->_rating_2 = get_theme_mod( 'rating_2', $this->_defaults[1] );
		if( ! empty( $this->_rating_2 ) ){
			$this->_rating_2 = array(
				'name'	=> get_theme_mod( 'rating_2', $this->_rating_2 ),
				'slug'	=> 2,
				'html'	=> $this->rating_field( $this->_rating_2, 2 )
			);
		}

		$this->_rating_3 = get_theme_mod( 'rating_3', $this->_defaults[2] );
		if( ! empty( $this->_rating_3 ) ){
			$this->_rating_3 = array(
				'name'	=> get_theme_mod( 'rating_3', $this->_rating_3 ),
				'slug'	=> 3,
				'html'	=> $this->rating_field( $this->_rating_3, 3 )
			);
		}

		$this->_rating_4 = get_theme_mod( 'rating_4', $this->_defaults[3] );
		if( ! empty( $this->_rating_4 ) ){
			$this->_rating_4 = array(
				'name'	=> get_theme_mod( 'rating_4', $this->_rating_4 ),
				'slug'	=> 4,
				'html'	=> $this->rating_field( $this->_rating_4, 4 )
			);
		}

		/** Rating Slug */
		add_filter( 'curly_rating', array( $this, 'rating_slug' ), 10, 1 );

	}



	/** Rating Score */
	function rating_score( $score ){

	}



	/** Rating Field */
	function rating_field( $name, $slug ){
		$html = "
			<div class='comment-form-rating-$slug stars-rating'>
				<label for='rating_$slug' class='sr-only'>$name</label>
				<em>$name:</em>
				<label class='stars-1'>
					<input name='_rating_$slug' type='radio' value='1'>
					<span></span>
				</label>
				<label class='stars-2'>
					<input name='_rating_$slug' type='radio' value='2'>
					<span></span>
				</label>
				<label class='stars-3'>
					<input name='_rating_$slug' type='radio' value='3'>
					<span></span>
				</label>
				<label class='stars-4'>
					<input name='_rating_$slug' type='radio' value='4'>
					<span></span>
				</label>
				<label class='stars-5'>
					<input name='_rating_$slug' type='radio' value='5' checked>
					<span></span>
				</label>
			</div>
		";

		return $html;
	}


	/** Generate Ratings */
	function comments( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;

		$rating_total  = 0;
		$rating_count = get_comment_count();

		$rating_1 = intval( get_comment_meta( get_comment_id(), '_rating_1', true ) );
		$rating_2 = intval( get_comment_meta( get_comment_id(), '_rating_2', true ) );
		$rating_3 = intval( get_comment_meta( get_comment_id(), '_rating_3', true ) );
		$rating_4 = intval( get_comment_meta( get_comment_id(), '_rating_4', true ) );

		$rating_total = $rating_1 + $rating_2 + $rating_3 + $rating_4;

		?>

		<li id="li-review-<?php comment_ID(); ?>">
			<article id="review-<?php comment_ID(); ?>" class="review">
				<?php if( $rating_total > 0 ) : ?>
				<aside class="ratings row">
					<?php if( $rating_1 > 0 ) : ?>
						<div class="rating col-sm-3">
							<?php echo get_theme_mod( 'rating_1', __( 'Comfort', 'xtender' ) ) ?>:
							<div class="stars-rating rating-<?php echo $rating_1 ?>"></div>
						</div>
					<?php endif; ?>
					<?php if( $rating_2 > 0 ) : ?>
						<div class="rating col-sm-3">
							<?php echo get_theme_mod( 'rating_2', __( 'Position', 'xtender' ) ) ?>:
							<div class="stars-rating rating-<?php echo $rating_2 ?>"></div>
						</div>
					<?php endif; ?>
					<?php if( $rating_3 > 0 ) : ?>
						<div class="rating col-sm-3">
							<?php echo get_theme_mod( 'rating_3', __( 'Price', 'xtender' ) ) ?>:
							<div class="stars-rating rating-<?php echo $rating_3 ?>"></div>
						</div>
					<?php endif; ?>
					<?php if( $rating_4 > 0 ) : ?>
						<div class="rating col-sm-3">
							<?php echo get_theme_mod( 'rating_4', __( 'Quality', 'xtender' ) ) ?>:
							<div class="stars-rating rating-<?php echo $rating_4 ?>"></div>
						</div>
					<?php endif; ?>
				</aside>
				<?php endif; ?>

				<section class="review-content review">
					<?php

						comment_text();
						printf( '<h6><cite class="fn">%1$s </cite></h6>',
									get_comment_author_link()
							);
						?>
					<?php if ( '0' == $comment->comment_approved ) : ?>
						<span class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'xtender' ); ?></span>
					<?php endif; ?>
				</section>
			</article>
		<?php
	}




	/** Rating Form Fields */
	function fields( $fields ){

		if( ! empty( $this->_rating_1 ) ){
			$fields[ $this->_rating_1['name'] ] = $this->_rating_1['html'];
		}

		if( ! empty( $this->_rating_2 ) ){
			$fields[ $this->_rating_2['name'] ] = $this->_rating_2['html'];
		}

		if( ! empty( $this->_rating_3 ) ){
			$fields[ $this->_rating_3['name'] ] = $this->_rating_3['html'];
		}

		if( ! empty( $this->_rating_4 ) ){
			$fields[ $this->_rating_4['name'] ] = $this->_rating_4['html'];
		}

		$fields['author'] = '</div></div><div class="form-group">' .
		      '<div class="comment-form-author col-lg-6" '.( get_option( 'require_name_email' ) ? "data-required" : null ).'>'.
		      '<label for="author" class="sr-only">'.__( 'Name', 'xtender' ).'</label> ' .
		      '<input class="form-control" id="author" name="author" type="text" placeholder="'.__( 'Name', 'xtender' ).'"></div>';

		$fields['email'] = '<div class="comment-form-email col-lg-6" '.( get_option( 'require_name_email' ) ? "data-required" : null ).'><label for="email" class="sr-only">'.__( 'Email', 'xtender' ).'</label> ' .
		      '<input class="form-control" id="email" name="email" type="text" placeholder="'.__( 'Email', 'xtender' ).'"></div></div>';

		return $fields;
	}




	/** Save Ratings */
	function save_comment_meta_data( $comment_id ) {

		if ( isset( $_POST[ '_rating_1' ] )  && $_POST[ '_rating_1' ] != '' ){
			add_comment_meta( $comment_id, '_rating_1', intval( wp_filter_nohtml_kses( $_POST[ '_rating_1' ] ) ) );
		}

		if ( isset( $_POST[ '_rating_2' ] )  && $_POST[ '_rating_2' ] != '' ){
			add_comment_meta( $comment_id, '_rating_2', intval( wp_filter_nohtml_kses( $_POST[ '_rating_2' ] ) ) );
		}

		if ( isset( $_POST[ '_rating_3' ] )  && $_POST[ '_rating_3' ] != '' ){
			add_comment_meta( $comment_id, '_rating_3', intval( wp_filter_nohtml_kses( $_POST[ '_rating_3' ] ) ) );
		}

		if ( isset( $_POST[ '_rating_4' ] )  && $_POST[ '_rating_4' ] != '' ){
			add_comment_meta( $comment_id, '_rating_4', intval( wp_filter_nohtml_kses( $_POST[ '_rating_4' ] ) ) );
		}

	}





}

$xtender_ratings = new XtenderRoomRatings();

?>
