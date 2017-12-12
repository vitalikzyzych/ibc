<?php

class WeeklyClassScheduleWPRestApi {

  private $_version;
  private $_namespace;

  public function __construct(){

    $this->_version = '1';
    $this->_namespace = "weekly-class/v{$this->_version}";

    add_action( 'rest_api_init', array( $this, 'register_api_routes' ) );

  }

  function register_api_routes(){

    register_rest_route( $this->_namespace, '/settings', array(
      array(
          'methods'  => WP_REST_Server::READABLE,
          'callback' => array( $this, 'get_settings' ),
          'permission_callback' => array( $this, 'get_private_data_permissions_check' )
      ),
      array(
          'methods'  => WP_REST_Server::CREATABLE,
          'callback' => array( $this, 'update_settings' ),
          'permission_callback' => array( $this, 'get_private_data_permissions_check' )
      )
    ));

  }

  public function get_settings(){
    return rest_ensure_response( wcs_get_settings() );
  }

  public function update_settings( $request ){
    $params = $request->get_body_params();
    $settings = wcs_get_settings();
    foreach( $params as $key => $param ){
      if( $key === 'wcs_slug' && empty( $param ) ){
        $param = 'class';
      }
      update_option( $key, esc_attr( $param ) );
    }
    return rest_ensure_response( 'Options updated.' );
  }

  function get_private_data_permissions_check() {
    if ( ! current_user_can('edit_posts') ) {
      return new WP_Error( 'rest_forbidden', esc_html__( 'OMG you can not view private data.', 'my-text-domain' ), array( 'status' => 401 ) );
    }

    return true;
  }



}

new WeeklyClassScheduleWPRestApi();
