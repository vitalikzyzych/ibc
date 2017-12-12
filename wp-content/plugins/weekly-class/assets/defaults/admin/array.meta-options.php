<?php

add_filter( 'wcs_ips_options', 'wcs_default_ips_options', 10, 1 );

function wcs_default_ips_options( $options ){

	$pages_array = get_pages();
	$pages = array();

	if( is_array( $pages_array ) ){

		foreach( $pages_array as $page ){

			$pages[] = array( 'name' => $page->post_title, 'value' => $page->ID );

		}

	} else {

		$pages[0] = __( 'No Pages', 'WeeklyClass' );

	}

	/** Class Image */
	$options[] = array(
		'id'	=> 'class_image_tab',
		'type'	=> 'tab',
		'name'	=> __( 'Class Image', 'WeeklyClass' )
	);
	$options[] = array(
		'id'	=> 'image',
		'type'	=> 'image',
		'tab'	=> 'class_image_tab',
		'name'	=> __( 'Class Image', 'WeeklyClass' ),
		'desc'	=> __( 'Choose your class image', 'WeeklyClass' )
	);


	/** Action */
	$options[] = array(
		'id'	=> 'action_tab',
		'type'	=> 'tab',
		'name'	=> __( 'Action Button', 'WeeklyClass' )
	);
	$options[] = array(
		'id'	=> 'action_label',
		'type'	=> 'text',
		'tab'	=> 'action_tab',
		'name'	=> __( 'Button Label', 'WeeklyClass' ),
		'desc'	=> sprintf( "<strong>%s:</strong> %s", __( 'Note', 'WeeklyClass' ), __( 'Leaving this field blank will hide the action button for this class.', 'WeeklyClass' ) )
	);
	$options[] = array(
		'id'	=> 'action_call',
		'type'	=> 'radio',
		'tab'	=> 'action_tab',
		'choices' => array(
			'0' => __( 'Go to page', 'WeeklyClass' ),
			'1' => __( 'Go to custom URL', 'WeeklyClass' ),
			'2' => __( 'Email', 'WeeklyClass' ),
			'3' => __( 'Download iCal Event', 'WeeklyClass' )
		),
		'name'	=> __( 'Button Action', 'WeeklyClass' )
	);
	$options[] = array(
		'id'	=> 'action_page',
		'type'	=> 'select',
		'tab'	=> 'action_tab',
		'choices' => $pages,
		'name'	=> __( 'Go to Page', 'WeeklyClass' )
	);
	$options[] = array(
		'id'	=> 'action_custom',
		'type'	=> 'text',
		'tab'	=> 'action_tab',
		'name'	=> __( 'Custom URL', 'WeeklyClass' ),
		'desc'	=> __( 'Enter your custom URL in this field', 'WeeklyClass' )
	);
	$options[] = array(
		'id'	=> 'action_email',
		'type'	=> 'text',
		'tab'	=> 'action_tab',
		'name'	=> __( 'Email', 'WeeklyClass' ),
		'desc'	=> __( 'Enter your email in this field', 'WeeklyClass' )
	);
	$options[] = array(
		'id'	=> 'action_target',
		'type'	=> 'radio',
		'tab'	=> 'action_tab',
		'choices' => array(
			'0' => __( 'Same Window', 'WeeklyClass' ),
			'1' => __( 'New Window', 'WeeklyClass' )
		),
		'name'	=> __( 'Button Target', 'WeeklyClass' )
	);

	/** Contact Tab */
	$options[] = array(
		'id'	=> 'contact_tab',
		'type'	=> 'tab',
		'name'	=> __( 'Map Details', 'WeeklyClass' )
	);
	$options[] = array(
		'id'	=> 'latitude',
		'type'	=> 'text',
		'tab'	=> 'contact_tab',
		'name'	=> __( 'Map Latitude', 'WeeklyClass' ),
		'desc'	=> __( 'Latitude Coordinates (Please use decimal coordinates. ie. 51.508056)', 'WeeklyClass' )
	);
	$options[] = array(
		'id'	=> 'longitude',
		'type'	=> 'text',
		'tab'	=> 'contact_tab',
		'name'	=> __( 'Map Longitude', 'WeeklyClass' ),
		'desc'	=> __( 'Longitude Coordinates (Please use decimal coordinates. ie. -0.128056)', 'WeeklyClass' )
	);
	$options[] = array(
		'id'	=> 'map_type',
		'type'	=> 'radio',
		'tab'	=> 'contact_tab',
		'name'	=> __( 'Map Type', 'WeeklyClass' ),
		'choices' 	=> array(
			'roadmap'	=> __( 'Roadmap', 'WeeklyClass' ),
			'satellite'	=> __( 'Satellite', 'WeeklyClass' ),
			'hybrid'	=> __( 'Hybrid', 'WeeklyClass' ),
			'terrain'	=> __( 'Terrain', 'WeeklyClass' )
		),
		'default' => 'roadmap'
	);
	$options[] = array(
		'id'	=> 'map_theme',
		'type'	=> 'radio',
		'tab'	=> 'contact_tab',
		'name'	=> __( 'Map Color Theme', 'WeeklyClass' ),
		'choices' 	=> array(
			'default'	=> __( 'Default', 'WeeklyClass' ),
			'light'		=> __( 'Light', 'WeeklyClass' ),
			'dark'		=> __( 'Dark', 'WeeklyClass' )
		),
		'default' => 'default'
	);
	$options[] = array(
		'id'	=> 'map_zoom',
		'type'	=> 'slider',
		'tab'	=> 'contact_tab',
		'name'	=> __( 'Map Zoom', 'WeeklyClass' ),
		'atts'	=> array( 'step' => 1, 'min' => 1, 'max' => 18, 'suf' => null ),
		'desc'	=> __( 'Choose the map zoom level. Default level is 15', 'WeeklyClass' ),
		'default' => 12
	);


	if ( WCS_WOO ) {

		$products = array();

		$loop = new WP_Query(
			array(
				'post_type' => 'product',
				'posts_per_page' => -1
			)
		);
		if ( $loop->have_posts() ) {

			$products = array( array( 'name' => __( 'Select product to be used as ticket', 'WeeklyClass'), 'value' => '' ) );

			while ( $loop->have_posts() ) : $loop->the_post();

				$product_type = get_the_terms( get_the_id(), 'product_type' );
				$product 			= wc_get_product( get_the_id() );

				if( method_exists( $product, 'get_type' ) && $product->get_type() === 'wcs_ticket' && ! $product->is_visible() ){
					$products[] = array( 'name' => '#' . get_the_id() . ' - '. get_the_title(), 'value' => get_the_id() );
				}

			endwhile;

		}
		wp_reset_postdata();


		if( isset( $products ) && is_array( $products ) && ! empty( $products ) ){

			$options[] = array(
				'id'	=> 'woo_tab',
				'type'	=> 'tab',
				'name'	=> __( 'WooCommerce Tickets', 'WeeklyClass' )
			);
			$options[] = array(
				'id'	=> 'woo_capacity',
				'type'	=> 'text',
				'tab'	=> 'woo_tab',
				'name'	=> __( 'Capacity', 'WeeklyClass' ),
				'desc'	=> sprintf( "<strong>%s:</strong> %s", __( 'Note', 'WeeklyClass' ), __( 'Enter the maximum capacity for this event. Leaving this blank will hide the purchase button.', 'WeeklyClass' ) )
			);
			$options[] = array(
				'id'	=> 'woo_label',
				'type'	=> 'text',
				'tab'	=> 'woo_tab',
				'name'	=> __( 'Button Label', 'WeeklyClass' ),
				'desc'	=> sprintf( "<strong>%s:</strong> %s", __( 'Note', 'WeeklyClass' ), __( 'Leaving this field blank will hide the purchase button for this event.', 'WeeklyClass' ) )
			);
			$options[] = array(
				'id'	=> 'woo_label_sold',
				'type'	=> 'text',
				'tab'	=> 'woo_tab',
				'name'	=> __( 'Sold Out Label', 'WeeklyClass' ),
				'desc'	=> sprintf( "<strong>%s:</strong> %s", __( 'Note', 'WeeklyClass' ), __( 'Leaving this field blank will hide the sold out button for this event.', 'WeeklyClass' ) )
			);
			$options[] = array(
				'id'	=> 'woo_label_sold_link',
				'type'	=> 'text',
				'tab'	=> 'woo_tab',
				'name'	=> __( 'Sold Out Link', 'WeeklyClass' ),
				'desc'	=> sprintf( "<strong>%s:</strong> %s", __( 'Note', 'WeeklyClass' ), __( 'Leaving this field blank will make the sold out button not clickable for this event.', 'WeeklyClass' ) )
			);

			$options[] = array(
				'id'	=> 'woo_product',
				'type'	=> 'select',
				'tab'	=> 'woo_tab',
				'choices' => $products,
				'name'	=> __( 'WooCommerce Event Ticket', 'WeeklyClass' ),
				'desc'	=> sprintf( "<strong>%s:</strong> %s", __( 'Note', 'WeeklyClass' ), __( 'All tickets must be of type "Schedule Ticket" and have visibility set to hidden.', 'WeeklyClass' ) )
			);

		}
	}

	return $options;
}

?>
