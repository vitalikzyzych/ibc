<?php

if ( ! class_exists('WP_EX_PAGE_ON_THE_FLY') ){

    class WP_EX_PAGE_ON_THE_FLY
    {

        public $slug ='';
        public $args = array();

        function __construct ( $args ){
          add_action( 'template_redirect', array( $this, 'xyz_create_fake_query' ) );
        //  add_action('init', array($this, 'init') );
          //add_filter('the_posts', array($this,'fly_page') );

          $this->args = $args;
          $this->slug = $args['slug'];
        }


        public function xyz_create_fake_query(){

          $page_slug = $this->slug;

          if( isset( $_REQUEST['wcs_preview'] ) && $_REQUEST['wcs_preview'] === $page_slug && isset( $_REQUEST['wcs_schedule_id'] ) && ! empty( $_REQUEST['wcs_schedule_id'] ) && is_numeric( $_REQUEST['wcs_schedule_id'] ) ){

              add_filter( 'the_content', array( $this, 'filter_content' ) );
          }

        }


        public function fly_page($posts){
            global $wp, $wp_query;
            $page_slug = $this->slug;

            //check if user is requesting our fake page
            if( $wp->query_vars['page_id'] === $page_slug && isset( $_REQUEST['wcs_schedule_id'] ) && ! empty( $_REQUEST['wcs_schedule_id'] ) && is_numeric( $_REQUEST['wcs_schedule_id'] ) ){

                add_filter( 'the_content', array( $this, 'filter_content' ) );
            }

            return $posts;
        }

        public function filter_content($content){
          return 'asdasd';
        }
    }


}

new WP_EX_PAGE_ON_THE_FLY(array(
        'slug' => 'fake_slug',
        'post_title' => 'Fake Page Title',
        'post content' => 'This is the fake page content'
));

?>
