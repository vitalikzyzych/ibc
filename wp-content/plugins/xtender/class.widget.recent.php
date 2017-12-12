<?php

$prefix = 'eque_recent_';

add_action( 'widgets_init', 'curly_recent_widget' );


function curly_recent_widget() {
	register_widget( 'xtender_recent_widget' );
}

class xtender_recent_widget extends WP_Widget {

  function __construct() {

    parent::__construct(
      'xtender_recent_widget', __('Recent Posts by xtender', 'xtender'),
      array(
        'classname' => 'xtd_recent_posts',
        'description' => __('A widget that displays the recent posts', 'xtender' )
      )
    );

  }

	function widget( $args, $instance ) {
		extract( $args );

		//Our variables from the widget settings.
		$title = apply_filters('widget_title', $instance['title'] );
		$limit = $instance['limit'];
		$picture = $instance['picture'];
		$exclude = $instance['exclude'];
		$cat = $instance['cat'];

		echo $before_widget;

		if ( $title ) echo $before_title . $title . $after_title;

		$args = array(
			'posts_per_page'  => $limit,
			'exclude'         => $exclude,
			'category'		    => $cat,
			'post_type'       => 'post'
		);

		$posts   = get_posts($args);

		$html 	 = "<div class='xtd-recent-posts type-$picture'>";

			foreach( $posts as $post ){
				setup_postdata( $post );

				$html .= "<div class='xtd-recent-posts__post'>";

				if( $picture != 'none' ){

					if( has_post_thumbnail( $post->ID ) ){

  					$html .= '<a  class="xtd-recent-posts__post__thumbnail" href="'.get_permalink().'">';
  					$html .= get_the_post_thumbnail( $post->ID, $picture );
  					$html .= '</a>';

					}
				}

        $html .= '<div class="xtd-recent-posts__post__content">';
  				$html .= '<h6 class="xtd-recent-posts__post__title"><a href="'.get_permalink($post->ID).'">'.get_the_title($post).'</a></h6>';
          $html .= '<time class="xtd-recent-posts__post__date" datetime="'.get_the_time( 'Y-m-d', $post->ID ).'"><span>'.get_the_date().'</span></time>';
        $html .= '</div>';

				$html .= '</div>';
			}
			$html 	.= '</div>';

		echo $html;

		echo $after_widget;
	}

	//Update the widget

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		//Strip tags from title and name to remove HTML
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['limit'] = strip_tags( $new_instance['limit'] );
		$instance['picture'] = strip_tags( $new_instance['picture'] );
		$instance['exclude'] = strip_tags( $new_instance['exclude'] );
		$instance['cat'] = strip_tags( $new_instance['cat'] );

		return $instance;
	}


	function form( $instance ) {
		$defaults = array(
			'title' => null,
			'limit' => null,
			'picture' => 'thumbnail',
			'exclude' => null,
			'cat' => null
		);

		//Set up some default widget settings.
		$instance = wp_parse_args( (array) $instance, $defaults); ?>
		<div class="widget-content">
            <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Widget Title:', 'xtender'); ?></label>
                <input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" />
            </p>


            <p>
                <label for="<?php echo $this->get_field_id( 'limit' ); ?>"><?php _e('Limit Posts:', 'xtender'); ?></label>
                <input type="text" id="<?php echo $this->get_field_id( 'limit' ); ?>" name="<?php echo $this->get_field_name( 'limit' ); ?>" value="<?php echo $instance['limit']; ?>" class="widefat" />
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( 'picture' ); ?>"><?php _e('Thumbnail:', 'xtender'); ?></label>
                <select class="widefat" id="<?php echo $this->get_field_id( 'picture' ); ?>" name="<?php echo $this->get_field_name( 'picture' ); ?>">
                	<option <?php echo ($instance['picture'] == 'none') ? 'selected="selected"' : null; ?> value="none"><?php _e( 'no thumbnail', 'xtender' ) ?></option>
                    <option <?php echo ($instance['picture'] == 'thumbnail') ? 'selected="selected"' : null; ?> value="thumbnail"><?php _e( 'square thumbnail', 'xtender' ); ?></option>
                </select>

            </p>

            <p>
                <label for="<?php echo $this->get_field_id( 'exclude' ); ?>"><?php _e('Exclude Posts:', 'xtender'); ?></label>
                <input type="text" id="<?php echo $this->get_field_id( 'exclude' ); ?>" name="<?php echo $this->get_field_name( 'exclude' ); ?>" value="<?php echo $instance['exclude']; ?>" class="widefat" />
                <small><?php _e( "Comma separated ID's you want to exclude", 'xtender' ); ?></small>
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( 'cat' ); ?>"><?php _e('Choose Categories:', 'xtender'); ?></label>
                <input type="text" id="<?php echo $this->get_field_id( 'cat' ); ?>" name="<?php echo $this->get_field_name( 'cat' ); ?>" value="<?php echo $instance['cat']; ?>" class="widefat" />
                <small><?php _e( "Comma separated ID's you want to include", 'xtender' ) ?></small>
            </p>

		</div>

	<?php
	}
}
?>
