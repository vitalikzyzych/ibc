<?php

class XtenderCptCollection {

  public function __construct(){

    add_action( 'init', array( $this, 'register_xtender_collection' ) );

  }

  function register_xtender_collection() {

    $labels = array(
  		'name'               => _x( 'Collections', 'post type general name', 'your-plugin-textdomain' ),
  		'singular_name'      => _x( 'Collection', 'post type singular name', 'your-plugin-textdomain' ),
  		'menu_name'          => _x( 'Collections', 'admin menu', 'your-plugin-textdomain' ),
  		'name_admin_bar'     => _x( 'Collection', 'add new on admin bar', 'your-plugin-textdomain' ),
  		'add_new'            => _x( 'Add New', 'book', 'your-plugin-textdomain' ),
  		'add_new_item'       => __( 'Add New Collection', 'your-plugin-textdomain' ),
  		'new_item'           => __( 'New Collection', 'your-plugin-textdomain' ),
  		'edit_item'          => __( 'Edit Collection', 'your-plugin-textdomain' ),
  		'view_item'          => __( 'View Collection', 'your-plugin-textdomain' ),
  		'all_items'          => __( 'All Collections', 'your-plugin-textdomain' ),
  		'search_items'       => __( 'Search Collections', 'your-plugin-textdomain' ),
  		'parent_item_colon'  => __( 'Parent Collections:', 'your-plugin-textdomain' ),
  		'not_found'          => __( 'No collections found.', 'your-plugin-textdomain' ),
  		'not_found_in_trash' => __( 'No collections found in Trash.', 'your-plugin-textdomain' )
  	);

  	$args = array(
  		'labels'             => $labels,
      'description'        => __( 'Description.', 'your-plugin-textdomain' ),
  		'public'             => true,
  		'publicly_queryable' => true,
  		'show_ui'            => true,
  		'show_in_menu'       => true,
  		'query_var'          => true,
  		'rewrite'            => array( 'slug' => 'collection' ),
  		'capability_type'    => 'post',
  		'has_archive'        => true,
  		'hierarchical'       => false,
  		'menu_position'      => null,
  		'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt' )
  	);

    register_post_type( 'xtender_collection', $args );

  }

}

if( defined( 'XTENDER_THEMPREFIX' ) && XTENDER_THEMPREFIX === 'art_gallery_wp' ) new XtenderCptCollection();

?>
