<?php

class XtenderLeisureOpenGraph {

  public function __construct(){

    /** Open Graph */
		add_action('wp_head', array($this, 'open_graph'), 5);

  }

  /*	Open Graph
	================================================= */
	function open_graph() {
		echo '<meta property="og:title" content="'.wp_title( ' - ', false, 'right' ).get_bloginfo('name').'" />';
		if ( is_single() ) {
			switch ( get_post_format() ) {
				case 'video' : $type = 'video'; break;
				case 'audio' : $type = 'audio'; break;
				case 'image' : $type = 'image'; break;
				default : $type = 'article';
			}
			echo '<meta property="og:type" content="'.$type.'" />';

		} else{
			echo '<meta property="og:type" content="article" />';
		}
		$image = ( has_post_thumbnail() ) ? wp_get_attachment_image_src( get_post_thumbnail_id( ), 'single-post-thumbnail' ) : get_theme_mod( 'logo' );
		if ( is_array( $image ) || $image ) {
			echo '<meta property="og:image" content="'.( is_array( $image ) ? $image[0] : $image ).'" />';
		}
		echo '<meta property="og:url" content="'.get_permalink().'" />';
	}

}

new XtenderLeisureOpenGraph();

?>
