<?php
if ( ! class_exists( 'XtenderSidebars' ) ) {

	class XtenderSidebars {

		public $_prefix = 'xtender';
		public $_url;
		public $_folder;

		public function __construct( $url = null, $folder = 'admin' ) {

			$this->_prefix = defined( 'XTENDER_THEMPREFIX' ) && XTENDER_THEMPREFIX === 'leisure' ? 'white' : $this->_prefix;

			$this->_url = XTENDER_URL;
			$this->_folder = $folder;

			add_action( 'admin_menu', array( $this, 'add_submenu_page' ) );
			add_action( 'add_meta_boxes', array( $this, 'meta_box' ) );
			add_action( 'save_post', array( $this, 'save_meta_box_data' ) );
			add_action( 'current_screen', array( $this, 'add_sidebar' ) );
			add_action( 'widgets_init', array( $this, 'create_sidebars' ) );

			add_shortcode( 'dynamic-sidebar', array( $this, 'sidebar_shortcode' ) );

		}

		function add_sidebar(){

			$current_screen = get_current_screen();

			if( $current_screen->id !== 'appearance_page_sidebars' )
				return;

			if( ! isset( $_REQUEST['action'] ) || empty( $_REQUEST['action'] ) )
				return;

			if( ! in_array( $_REQUEST['action'], array( 'add_sidebar', 'delete_sidebar' ) ) )
				return;

			if( ! isset( $_REQUEST['sidebar_nonce'] ) && ! wp_verify_nonce( $_REQUEST['sidebar_nonce'], 'add_sidebar' ) ){
				return;
			}

			$sidebar = $out = false;
			$sidebars = $this->get_sidebars();

			if( esc_attr( $_REQUEST['action'] ) === 'delete_sidebar' ){

				$id = isset( $_REQUEST['id'] ) ? esc_attr( $_REQUEST['id'] ) : false;

				if( ! $id ){
					$out = new WP_Error( 'broke', __( "Something went wrong! Please try again.", "xtender" ) );
				}

				elseif( isset( $sidebars[$id] ) ){

					unset( $sidebars[$id] );
					$sidebars = json_encode( $sidebars );
					update_option( $this->_prefix . '_sidebars_list' , $sidebars );

					$out = __( 'Your sidebar has been succesfully deleted.', 'xtender' );

				}

			}

			elseif( ! is_wp_error( $out ) ){

				$sidebar 				= isset( $_POST['sidebar_name'] ) ? esc_attr( $_POST['sidebar_name'] ) : false;
				$sidebars_count	= $this->get_sidebars_count() + 1;

				if( ! empty( $sidebar )  ){

					if ( ! $sidebars ) {

						$sidebars = array( $sidebars_count => $sidebar );
						$sidebars = json_encode( $sidebars );

						update_option( $this->_prefix . '_sidebars_list' , $sidebars );
						update_option( $this->_prefix . '_sidebars_list_count' , $sidebars_count );

						$out = __( 'Your sidebar has been succesfully created.', 'xtender' );

					} else {

						if ( ! in_array( $sidebar , $sidebars ) ) {

							$sidebars[$sidebars_count] = $sidebar;
							$sidebars = json_encode($sidebars);

							update_option( $this->_prefix . '_sidebars_list' , $sidebars );
							update_option( $this->_prefix . '_sidebars_list_count' , $sidebars_count );

							$out = __( 'Your sidebar has been succesfully created.','xtender' );

						} else {

							$out = new WP_Error( 'broke', __( "You already have a sidebar with that name. Please provide a valid name for your sidebar.", "xtender" ) );

						}
					}

				}

				else {
					$out = new WP_Error( 'broke', __( "Sidebar name cannot be empty. Please provide a valid name for your sidebar.", "xtender" ) );
				}

			}

			$type = is_wp_error( $out ) ? 'error' : 'updated';
			$out 	= is_wp_error( $out ) ? $out->get_error_message() : $out;


			if( $out )
				add_settings_error( 'sidebar_notice', 'sidebar_notice', $out, $type );

		}


		function add_submenu_page(){
		     add_theme_page( __( 'Sidebars', 'xtender' ), __( 'Sidebars', 'xtender' ), 'edit_theme_options', 'sidebars', array( $this, 'add_submenu_page_cb' ) );
		}

		function add_submenu_page_cb( $html = null ) {

			$sidebars = $this->get_sidebars();

			?>
			<div id="sidebars-wrapper" class="wrap">
				<h1><?php _e('Add Sidebar', 'xtender') ?></h1>
				<?php settings_errors(); ?>
				<form method="post" id="add-sidebar" action="<?php echo admin_url('themes.php?page=sidebars') ?>">
					<?php wp_nonce_field( 'add_sidebar', 'sidebar_nonce'); ?>
					<input type='hidden' name='action' value='add_sidebar'>
					<input type="text" name="sidebar_name" id="add-sidebar-field" placeholder="<?php _e('Enter new sidebar name','xtender'); ?>"><br>
					<input type="submit" id="add-sidebar-button" value="<?php _e('Add Sidebar','xtender') ?>" class="button button-primary button-large">
				</form>
				<h3><?php _e('Sidebar List','xtender') ?></h3>
				<ul id="sidebar-list">
					<?php if ( $sidebars ) : foreach ( $sidebars as $id => $name ) : ?>
						<li><span><?php echo $name ?></span><code>[dynamic-sidebar id="<?php echo $id ?>"]</code><a href="<?php echo admin_url( 'themes.php?page=sidebars&action=delete_sidebar&id='. $id . '&sidebar_nonce='. wp_create_nonce( 'add_nonce' ) ) ?>" class="confirm-dialog"><?php _e('Remove','xtender') ?></a></li>
					<?php endforeach; else : ?>
						<li id="no-sidebar"><?php _e('You currently have no sidebars created. <br>Use the form above to create your first sidebar.','xtender') ?></li>
					<?php endif; ?>
				</ul>
			</div>
			<?php

		}

		function get_sidebars() {
			$sidebars = get_option( $this->_prefix . '_sidebars_list' );
			$sidebars = json_decode( $sidebars , true);

			return $sidebars;
		}

		function get_sidebars_count() {
			$count = get_option( $this->_prefix . '_sidebars_list_count', 0 );

			return $count;
		}

		function create_sidebars() {
			$sidebars = $this->get_sidebars();
			if ( $sidebars ) {
				foreach ( $sidebars as $id => $name ) {
					if( ! empty( $name ) && ! empty( $id ) ){
						register_sidebar( array(
					    'name'         => $name,
					    'id'           => 'dynamic-sidebar-'.$id,
					    'before_widget'	 => '<aside id="%1$s" class="sidebar-widget %2$s animated">',
							'after_widget' 	 => '</aside>',
							'before_title'	 => '<h4 class="widget-title">',
							'after_title'	 => '</h4>',
						) );
					}
				}
			}

		}

		public static function sidebar( $default, $return = false ) {

			if( is_null( $default ) || empty( $default ) )
				return;

			$sidebar = is_singular() ? get_post_meta( get_the_id(), 'xtender_dynamic_sidebar', true ) : false;
			$sidebar = ! $sidebar ? $default : $sidebar;
			$sidebar = is_active_sidebar( $sidebar ) ? $sidebar : false;

			if( ! $sidebar )
				return;

			if( $return ){
				dynamic_sidebar( $sidebar );
			}	else {
				return $sidebar;
			}

		}

		function sidebar_shortcode( $atts ) {

			ob_start();
			dynamic_sidebar( 'sidebar_'.$atts['id'] );
			$sidebar = ob_get_contents();
			ob_end_clean();

			return $sidebar;
		}

		public function meta_box() {
			$screens = array( 'post', 'page' );

				foreach ( $screens as $screen ) {
					add_meta_box('sidebar_metabox', __( 'Sidebar', 'xtender' ), array($this, 'meta_box_callback'), $screen, 'side');
				}

		}

		public function meta_box_callback( $post ) {

			wp_nonce_field( 'sidebar_meta_box', 'sidebar_meta_box_nonce' );

			$default_sidebar = get_post_meta( $post->ID, $this->_prefix . '_dynamic_sidebar', true );

			global $wp_registered_sidebars;

			echo '<p><strong><label>'.__('Choose Sidebar:','xtender').'</label></strong></p>';
			echo '<select name="sidebar" id="sidebar">';
			echo '<option value="">'.__('Choose Sidebar','xtender').'</option>';
			foreach ( $wp_registered_sidebars as $value ) {
				echo '<option value="'.$value['id'].'" '.selected($default_sidebar, $value['id']).'>'.$value['name'].'</option>';
			}
			echo '</select>';
			echo '<p>'.__('Choose a custom sidebar for this page','xtender').'</p>';
		}

		public function save_meta_box_data( $post_id ) {

			if ( ! isset( $_POST['sidebar_meta_box_nonce'] ) ) {
				return;
			}

			if ( ! wp_verify_nonce( $_POST['sidebar_meta_box_nonce'], 'sidebar_meta_box' ) ) {
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

			if ( ! isset( $_POST['sidebar'] ) ) {
				return;
			}


			$data = sanitize_text_field( $_POST['sidebar'] );
			update_post_meta( $post_id, $this->_prefix . '_dynamic_sidebar', $data );
		}
	}

	$sidebars = new XtenderSidebars();

}
?>
