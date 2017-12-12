<?php


class WC_Settings_Tab_WCS_Bookings {
    public static function init() {
        add_filter( 'woocommerce_settings_tabs_array', __CLASS__ . '::add_settings_tab', 50 );
    }
    public static function add_settings_tab( $settings_tabs ) {
        $settings_tabs['settings_tab_wcs'] = __( 'Event Schedule Bookings', 'WeeklyClass' );
        return $settings_tabs;
    }
}
WC_Settings_Tab_WCS_Bookings::init();

function wcs_api_woo_register_product_type() {

	class WC_Product_Wcs_ticket extends WC_Product_Simple {

		public function __construct( $product ) {

			$this->product_type = 'wcs_ticket';

			parent::__construct( $product );
		}

    function get_type(){
      return 'wcs_ticket';
    }

	}

}
if( WCS_WOO ){
	add_action( 'init', 'wcs_api_woo_register_product_type' );
}



class WCS_API_WOOCOMMERCE {

	function __construct(){

		add_filter( 'woocommerce_add_to_cart_redirect', array( $this, 'add_to_cart_redirect' ) );
		add_filter( 'woocommerce_add_to_cart_validation', array( $this, 'validate_add_cart_item' ), 10, 5 );
		add_filter( 'woocommerce_update_cart_validation', array( $this, 'validate_update_cart_item' ), 10, 5 );
		add_filter( 'woocommerce_add_cart_item_data', array( $this, 'add_item_data' ), 1, 10 );
		add_filter( 'woocommerce_get_cart_item_from_session', array( $this, 'get_cart_items_from_session' ), 1, 3 );
		add_filter( 'woocommerce_cart_item_name', array( $this, 'add_custom_session' ), 1, 3 );
		add_action( 'woocommerce_add_order_item_meta', array( $this, 'add_values_to_order_item_meta' ),1, 2 );
		add_filter( 'woocommerce_hidden_order_itemmeta', array( $this, 'hidden_order_itemmeta' ), 10, 1);

		add_action( 'woocommerce_settings_tabs_settings_tab_wcs', array( $this, 'settings_tab' ) );
		add_action( 'woocommerce_update_options_settings_tab_wcs', array( $this, 'update_settings' ) );
		add_filter( 'product_type_selector', array( $this, 'add_wcs_ticket_product_type' ) );
		add_filter( 'woocommerce_product_data_tabs', array( $this, 'hide_data_panels' ) );
		add_action( 'admin_footer', array( $this, 'custom_admin_footer_js' ) );

		add_action( 'add_meta_boxes', array( $this, 'meta_box') );

	}



	function add_to_cart_redirect( $url ) {

    global $woocommerce;

    $cart_contents = $woocommerce->cart->cart_contents;
    $booking = false;

    if( is_array( $cart_contents ) ){

      foreach( $cart_contents as $key => $item ){

        if( isset( $item['_wcs_options'] ) && ! empty( $item['_wcs_options'] ) && is_array( $item['_wcs_options'] ) ){
          $booking = true;
        }

      }

      if( $booking ){
          $url = intval( WC_Admin_Settings::get_option( 'wc_settings_tab_wcs_redirect', 0 ) ) === 0 ? wc_get_cart_url() : wc_get_checkout_url();
      }

    }


		return $url;

	}


	function validate_add_cart_item( $passed, $product_id, $quantity, $variation_id = '', $variations= '' ) {

		$event_id = isset( $_REQUEST['event'] ) ? $_REQUEST['event'] : false;
		$timestamp = isset( $_REQUEST['timestamp'] ) ? $_REQUEST['timestamp'] : false;

		if( ! $event_id || ! $timestamp )
			return $passed;

		global $woocommerce;

		$total = 0;

		$contents = $woocommerce->cart->cart_contents;

		foreach( $contents as $key => $item ){

			if( $item["product_id"] === $product_id && $item['_wcs_options']['event'] === $event_id && $item['_wcs_options']['timestamp'] === $timestamp ){
				$total += $item["quantity"];
			}

		}

		$capacity = esc_attr( get_post_meta( $event_id, WCS_PREFIX . "_woo_capacity" , true ) );

		if( empty( $capacity ) )
			return $passed;


		$ticket_woo = get_post_meta( $event_id, WCS_PREFIX . "_woo_product" , true );

		if( empty( $ticket_woo ) )
			return $passed;

		$history = get_post_meta( $event_id, '_' . WCS_PREFIX . "_woo_history" , true );

		if( empty( $capacity ) || intval( $capacity ) === 0 )
			$passed = false;

		if( ! empty( $history ) ){

			$history = maybe_unserialize( $history );

			if( isset( $history[$timestamp] ) ){

				$orders = $history[$timestamp];

				foreach( $orders as $ord ){

          $order =  wc_get_order( $ord );

          if( ! is_wp_error( $order ) && method_exists( $order, 'get_items' ) && ! empty( $order ) && ! is_null( $order ) && $order !== false ) :

					if( ! in_array( $order->get_status(), array( 'cancelled', 'refunded', 'rejected', 'failed' ) ) ){

						$items = $order->get_items();

						foreach( $items as $item ){

							if( intval( $item->get_product_id() ) === intval( $ticket_woo ) && $item->meta_exists('_wcs_event') && intval( $item->get_meta('_wcs_event', true ) ) === $event_id && $item->meta_exists('_wcs_timestamp') && intval( $item->get_meta('_wcs_timestamp', true ) ) === intval( $timestamp ) ){
								$total += $item['qty'];
							}

						}

					}

        endif;

				}

			}

		}

		if( ( intval( $capacity ) - $total - $quantity ) < 0 ){
			$passed = false;
		}

		if( ! $passed )
			wc_add_notice( esc_attr( WC_Admin_Settings::get_option( 'wc_settings_tab_wcs_label_error_capacity', __( 'Sorry there are no more tickets available for this event.', 'WeeklyTabs' ) ) ), 'error' );

	    return $passed;

	}



	function validate_update_cart_item( $passed, $cart_item_key,  $values,  $quantity  ) {

		global $woocommerce;

		$total = 0;

		$cart = $woocommerce->cart->cart_contents;
		$cart_item = $cart[$cart_item_key];

		$product_id = $cart_item["product_id"];
		$event_id = $cart_item['_wcs_options']['event'];
		$timestamp = $cart_item['_wcs_options']['timestamp'];

		if( ! isset( $event_id ) || ! isset( $timestamp ) )
			return $passed;

		$capacity = esc_attr( get_post_meta( $event_id, WCS_PREFIX . "_woo_capacity" , true ) );

		if( empty( $capacity ) )
			return $passed;

		$ticket_woo = get_post_meta( $event_id, WCS_PREFIX . "_woo_product" , true );

		if( empty( $ticket_woo ) )
			return $passed;

		$history = get_post_meta( $event_id, '_' . WCS_PREFIX . "_woo_history" , true );

		if( empty( $capacity ) || intval( $capacity ) === 0 )
			$passed = false;

		if( ! empty( $history ) ){

			$history = maybe_unserialize( $history );

			if( isset( $history[$timestamp] ) ){

				$orders = $history[$timestamp];

				foreach( $orders as $ord ){

					$order =  wc_get_order( $ord );

          if( ! is_wp_error( $order ) && method_exists( $order, 'get_items' ) && ! empty( $order ) && ! is_null( $order ) && $order !== false ) :

					if( ! in_array( $order->get_status(), array( 'cancelled', 'refunded', 'rejected', 'failed' ) ) ){

						$items = $order->get_items();

						foreach( $items as $item ){

              if( intval( $item->get_product_id() ) === intval( $ticket_woo ) && $item->meta_exists('_wcs_event') && intval( $item->get_meta('_wcs_event', true ) ) === $event_id && $item->meta_exists('_wcs_timestamp') && intval( $item->get_meta('_wcs_timestamp', true ) ) === intval( $timestamp ) ){
								$total += $item['qty'];
							}

						}

					}

        endif;

				}

			}

		}

		if( ( intval( $capacity ) - $total - $quantity ) < 0 ){
			$passed = false;
		}

		if( ! $passed )
			wc_add_notice( esc_attr( WC_Admin_Settings::get_option( 'wc_settings_tab_wcs_label_error_capacity', __( 'Sorry there are no more tickets available for this event.', 'WeeklyTabs' ) ) ), 'error' );

	    return $passed;

	}



	function add_item_data( $cart_item_data, $product_id ) {

		if( ! isset( $_REQUEST['event'] ) && ! isset( $_REQUEST['timestamp'] ) )
			return $cart_item_data;

		$types = $locations = $instructors = '';

		$event_id = esc_attr( $_REQUEST['event'] );
		$timestamp = esc_attr( $_REQUEST['timestamp'] );

		$label_date 		= esc_attr( WC_Admin_Settings::get_option( 'wc_settings_tab_wcs_label_date', __( 'Date', 'WeeklyClass') ) );
		$label_types 		= esc_attr( WC_Admin_Settings::get_option( 'wc_settings_tab_wcs_label_type', __( 'Type', 'WeeklyClass') ) );
		$label_instructors 	= esc_attr( WC_Admin_Settings::get_option( 'wc_settings_tab_wcs_label_instructors', __( 'Instructor', 'WeeklyClass') ) );
		$label_locations 	= esc_attr( WC_Admin_Settings::get_option( 'wc_settings_tab_wcs_label_locations', __( 'Location', 'WeeklyClass') ) );

		if( ! empty( $label_type ) ){

			$instructors_array = get_the_terms( $event_id, 'wcs-instructor' );

			if( is_array( $instructors_array ) ){

				$count = 0;

				foreach( $instructors_array as $instructor ){
					$types .= $count >= 1 ? ", $instructor->name" : $instructor->name;
					$count++;
				}

			}
		}

		if( ! empty( $label_instructors ) ){

			$instructors_array = get_the_terms( $event_id, 'wcs-instructor' );

			if( is_array( $instructors_array ) ){

				$count = 0;

				foreach( $instructors_array as $instructor ){
					$instructors .= $count >= 1 ? ", $instructor->name" : $instructor->name;
					$count++;
				}

			}
		}

		if( ! empty( $label_locations ) ){

			$locations_array = get_the_terms( $event_id, 'wcs-room' );

			if( is_array( $locations_array ) ){

				$count = 0;

				foreach( $locations_array as $instructor ){
					$locations .= $count >= 1 ? ", $instructor->name" : $instructor->name;
					$count++;
				}

			}
		}

		$title = get_the_title( $event_id );

		$date = ! empty( $label_date ) ? date_i18n( 'l, F j, Y @ H:i', $timestamp ) : '';


	    global $woocommerce;

	    $new_value = array();
	    $new_value['_wcs_options'] = array(
	    	'event' => $event_id,
	    	'timestamp' => $timestamp,
	    	'date'	=> $date,
	    	'type' => $types,
	    	'instructor' => $instructors,
	    	'location' => $locations,
	    	'title' => $title,
	    	'label_date' => $label_date,
	    	'label_types' => $label_types,
	    	'label_instructors' => $label_instructors,
	    	'label_locations' => $label_locations,
	    );

	    if( empty( $cart_item_data ) ) {

	        return $new_value;

	    } else {

	        return array_merge( $cart_item_data, $new_value );

	    }
	}


	function get_cart_items_from_session($item,$values,$key) {

	    if ( array_key_exists( '_wcs_options', $values ) ) {
	        $item['_wcs_options'] = $values['_wcs_options'];
	    }

	    return $item;
	}


	function add_custom_session( $product_name, $values, $cart_item_key ) {

		$timestamp 	= isset( $values['_wcs_options'] ) ? $values['_wcs_options']['timestamp'] : '';
		$title 		= isset( $values['_wcs_options'] ) ? ' - ' . $values['_wcs_options']['title'] : '';
		$types 		= ! empty( $values['_wcs_options']['types'] ) ? "<strong>{$values['_wcs_options']['label_types']}:</strong> {$values['_wcs_options']['types']} " : '';
		$date 		= ! empty( $values['_wcs_options']['date'] ) ? "<strong>{$values['_wcs_options']['label_date']}:</strong> {$values['_wcs_options']['date']} " : '';
		$instructors 	= ! empty( $values['_wcs_options']['instructor'] ) ? "<strong>{$values['_wcs_options']['label_instructors']}:</strong> {$values['_wcs_options']['instructor']} " : '';
		$locations 		= ! empty( $values['_wcs_options']['location'] ) ? "<strong>{$values['_wcs_options']['label_locations']}:</strong> {$values['_wcs_options']['location']} " : '';

		$description = ! empty( $types ) || ! empty( $date ) || ! empty( $locations ) || ! empty( $instructors ) ? "<br><small class='wcs-woo-description'>$types $date $locations $instructors</small>" : '';

    $return_string = $product_name . $title . $description;

    return $return_string;

	}

	function add_values_to_order_item_meta( $item_id, $values ) {

	    global $woocommerce, $wpdb;

	    $order_id  = $wpdb->get_var( $wpdb->prepare( "SELECT order_id FROM {$wpdb->prefix}woocommerce_order_items WHERE order_item_id = %d ", $item_id ) );

		if( isset( $values['_wcs_options'] ) && ! empty( $values['_wcs_options']['label_date'] ) ){
			wc_add_order_item_meta($item_id, $values['_wcs_options']['label_date'], $values['_wcs_options']['date'] );
		}

		if( isset( $values['_wcs_options'] ) && ! empty( $values['_wcs_options']['label_types'] ) ){
			wc_add_order_item_meta($item_id, $values['_wcs_options']['label_types'], $values['_wcs_options']['type'] );
		}

		if( isset( $values['_wcs_options'] ) && ! empty( $values['_wcs_options']['label_instructors'] ) ){
			wc_add_order_item_meta($item_id, $values['_wcs_options']['label_instructors'], $values['_wcs_options']['instructor'] );
		}

		if( isset( $values['_wcs_options'] ) && ! empty( $values['_wcs_options']['label_locations'] ) ){
			wc_add_order_item_meta($item_id, $values['_wcs_options']['label_locations'], $values['_wcs_options']['location'] );
		}

		if( isset( $values['_wcs_options'] ) ){

		    wc_add_order_item_meta( $item_id, __( 'Event', 'WeeklyClass'), '#' . $values['_wcs_options']['event'] . ' - ' . $values['_wcs_options']['title'] );
		    wc_add_order_item_meta( $item_id,'_wcs_event', $values['_wcs_options']['event'] );
		    wc_add_order_item_meta( $item_id,'_wcs_timestamp', $values['_wcs_options']['timestamp'] );

		    $history = get_post_meta( $values['_wcs_options']['event'], '_' . WCS_PREFIX . "_woo_history" , true );
		    $history = maybe_unserialize( $history );

		    if( ! empty( $history ) ){

			    if( isset( $history[$values['_wcs_options']['timestamp']] ) ){

				    $history[$values['_wcs_options']['timestamp']][] = $order_id;

			    } else {

				    $history[$values['_wcs_options']['timestamp']] = array( $order_id );

			    }

		    } else {
			    $history = array();
			    $history[$values['_wcs_options']['timestamp']] = array( $order_id );

		    }

		    update_post_meta( $values['_wcs_options']['event'], '_' . WCS_PREFIX . "_woo_history", $history );

	    }

	}



	function hidden_order_itemmeta( $arr ) {

	    $arr[] = '_wcs_event';
	    $arr[] = '_wcs_timestamp';
	    return $arr;

	}

	function settings_tab() {
	    woocommerce_admin_fields( self::get_settings() );
	}

	function update_settings() {
	    woocommerce_update_options( self::get_settings() );
	}

	public static function get_settings() {
	    $settings = array(
	        'section_title' => array(
	            'name'     => __( 'Events Schedule Bookings', 'WeeklyClass' ),
	            'type'     => 'title',
	            'desc'     => '',
	            'id'       => 'wc_settings_tab_wcs_section_title'
	        ),
	        'label_date' => array(
	            'name' => __( 'Event Date Label', 'WeeklyClass' ),
	            'type' => 'text',
	            'desc' => __( 'Leave this field empty and the event date on the Cart, Checkout & Thank you page will be hidden.', 'WeeklyClass' ),
	            'default' => __( 'Date', 'WeeklyClass' ),
	            'id'   => 'wc_settings_tab_wcs_label_date'
	        ),
	        'label_type' => array(
	            'name' => __( 'Event Types Label', 'WeeklyClass' ),
	            'type' => 'text',
	            'default' => __( 'Type', 'WeeklyClass' ),
	            'desc' => __( 'Leave this field empty and the event types on the Cart, Checkout & Thank you page will be hidden.', 'WeeklyClass' ),
	            'id'   => 'wc_settings_tab_wcs_label_types'
	        ),
	        'label_instructors' => array(
	            'name' => __( 'Event Instructors Label', 'WeeklyClass' ),
	            'type' => 'text',
	            'default' => __( 'Instructor', 'WeeklyClass' ),
	            'desc' => __( 'Leave this field empty and the event instructors on the Cart, Checkout & Thank you page will be hidden.', 'WeeklyClass' ),
	            'id'   => 'wc_settings_tab_wcs_label_instructors'
	        ),
	        'label_locations' => array(
	            'name' => __( 'Event Locations Label', 'WeeklyClass' ),
	            'type' => 'text',
	            'default' => __( 'Location', 'WeeklyClass' ),
	            'desc' => __( 'Leave this field empty and the event locations on the Cart, Checkout & Thank you page will be hidden.', 'WeeklyClass' ),
	            'id'   => 'wc_settings_tab_wcs_label_locations'
	        ),
	        'redirect' => array(
	            'name' => __( 'Redirect after "Add Ticket to Cart"', 'WeeklyClass' ),
	            'type' => 'select',
	            'default' => 0,
	            'desc' => __( 'Choose where you want your visitor to be redirected after adding a ticket to the cart', 'WeeklyClass' ),
	            'options' => array(
		            0 => __( 'Cart Page', 'WeeklyClass' ),
		            1 => __( 'Checkout Page', 'WeeklyClass' )
	            ),
	            'id'   => 'wc_settings_tab_wcs_redirect'
	        ),
	        'label_error_capacity' => array(
	            'name' => __( 'Capacity Error Message', 'WeeklyClass' ),
	            'type' => 'textarea',
	            'default' => __( 'Sorry there are no more tickets available for this event.', 'WeeklyClass' ),
	            'id'   => 'wc_settings_tab_wcs_label_error_capacity'
	        ),
	        'section_end' => array(
	             'type' => 'sectionend',
	             'id' => 'wc_settings_tab_wcs_section_end'
	        )
	    );
	    return apply_filters( 'wc_settings_tab_demo_settings', $settings );
	}


	function add_wcs_ticket_product_type( $types ){

		$types[ 'wcs_ticket' ] = __( 'Schedule Ticket' );

		return $types;

	}

	function hide_data_panels( $tabs) {

		$tabs['attribute']['class'][] = 'hide_if_wcs_ticket hide_if_variable_wcs_ticket';
		$tabs['variations']['class'][] = 'hide_if_wcs_ticket hide_if_variable_wcs_ticket';
		$tabs['inventory']['class'][] = 'hide_if_wcs_ticket hide_if_variable_wcs_ticket';
		$tabs['shipping']['class'][] = 'hide_if_wcs_ticket hide_if_variable_wcs_ticket';

		return $tabs;

	}

	function custom_admin_footer_js() {

		if ( 'product' != get_post_type() ) : return; endif;

		?><script type='text/javascript'>
			jQuery( document ).ready( function() {
        jQuery( '.general_options' ).addClass( 'show_if_wcs_ticket' ).show();
        jQuery( '.type_box label[for="_virtual"]' ).addClass( 'show_if_wcs_ticket' ).show();
        jQuery( '.type_box label[for="_downloadable"]' ).addClass( 'show_if_wcs_ticket' ).show();

        jQuery( '.options_group.pricing' ).addClass( 'show_if_wcs_ticket' ).show();
				//jQuery( '.options_group.show_if_downloadable' ).addClass( 'hide_if_wcs_ticket' ).hide();
				jQuery( '.options_group.reviews' ).addClass( 'hide_if_wcs_ticket' ).hide();
			});
		</script><?php
	}


	/** Add Meta Box */
	function meta_box(){

		$screens = array( 'class' );

		foreach ( $screens as $screen ) {

			add_meta_box(
				'wcs_bookings',
				'<span class="dashicons dashicons-calendar-alt"></span> &nbsp;' . __( 'Bookings', 'WeeklyClass' ),
				array( $this, 'meta_box_callback_bookings'),
				$screen,
				'normal',
				'high'
			);

		}

	}

	public function meta_box_callback_bookings( $post ){

		//delete_post_meta($post->ID, '_' . WCS_PREFIX . "_woo_history" );
    //$post_id = function_exists('icl_object_id') ? icl_object_id( $post->ID ) : $post->ID;

		$history = get_post_meta( $post->ID, '_' . WCS_PREFIX . "_woo_history" , true );
		$history = maybe_unserialize( $history );

		$associated_product = get_post_meta( $post->ID, WCS_PREFIX . '_woo_product' , true );
		$capacity = get_post_meta( $post->ID, WCS_PREFIX . '_woo_capacity' , true );

		if( ! empty( $history ) && ! empty( $associated_product ) ){

			krsort( $history, SORT_NUMERIC );



			?>

			<table class="wp-list-table widefat fixed striped posts">

				<thead>
					<tr>
						<th><?php _e( 'Event Date & Time', 'WeeklyClass' ); ?></th>
						<th><?php _e( 'Order', 'WeeklyClass'); ?></th>
						<th><?php _e( 'Client', 'WeekylClass'); ?></th>
						<th><?php _e( 'Tickets', 'WeeklyClass'); ?></th>
						<th><?php _e( 'Order Status', 'WeeklyClass'); ?></th>
					</tr>
				</thead>
				<tbody>

					<?php

						$temp = '';

						foreach( $history as $timestamp => $orders ) :

							rsort( $orders );

							$total = $orders_count = 0;

							foreach( $orders as $ord ) :

								$order =  wc_get_order( $ord );

                if( ! is_wp_error( $order ) && method_exists( $order, 'get_items' ) && ! empty( $order ) && ! is_null( $order ) && $order !== false ) :

								$items = $order->get_items();
								$qty = 0;
								$order_date = $order->get_date_created();

								foreach( $items as $item ) :

									if( intval( $item->get_product_id() ) === intval( $associated_product ) && ! in_array( $order->get_status(), array( 'cancelled', 'refunded', 'rejected', 'failed' ) ) ) :
                    if( $item->meta_exists('_wcs_event') && intval( $item->get_meta('_wcs_event', true ) ) === intval( $post->ID ) ) :
                      if( $item->meta_exists('_wcs_timestamp') && intval( $item->get_meta('_wcs_timestamp', true ) ) === intval( $timestamp ) ) :

										$qty += intval( $item->get_quantity() );
										$total += $qty;
										$orders_count++;

										?>

											<tr>
												<?php if( $temp !== $timestamp ) : $temp = $timestamp; ?><td rowspan="<?php echo count( $orders ) + 1 ?>"><?php echo date_i18n( get_option( 'date_format', 'Y-m-d' ) . ' @ ' . get_option( 'time_format', 'H:i' ), $timestamp ) ?></td><?php endif; ?>
												<td><a href="<?php echo admin_url( "/post.php?post=$ord&action=edit" ) ?>">#<?php echo $ord ?></a> - <small><?php echo date_i18n( get_option( 'date_format', 'Y-m-d' ) . ' @ ' . get_option( 'time_format', 'H:i' ), strtotime($order_date) ) ?></small></td>
												<td>
                          <?php

                          $user = $order->get_user();

                          if( $user ){
                              echo "<a href='" . admin_url( "/user-edit.php?user_id={$user->ID}" ) . "'>{$order->get_formatted_billing_full_name()} ({$user->user_login})</a>";
                          } else {
                            echo $order->get_formatted_billing_full_name();
                          }
                          ?>
                        </td>
												<td><?php echo $qty; ?></td>
												<td><?php echo $order->get_status(); ?></td>
											</tr>

										<?php


									endif; endif; endif;

								endforeach;

              endif;

						endforeach;

						if( $total !== 0 ) :

							?>

							<tr>
								<td colspan="2"><?php echo sprintf( __( '%s bookings', 'WeeklyClass' ), $orders_count ); ?></td>
								<td colspan="2"><strong><?php echo "$total / $capacity"; ?></strong></td>
							</tr>

						<?php

						endif;

					endforeach; ?>

				</tbody>

			</table>

			<?php

		} else {

			 _e( 'There are no bookings for this event.', 'WeeklyClass' );

		}

	}


}

new WCS_API_WOOCOMMERCE();

?>
