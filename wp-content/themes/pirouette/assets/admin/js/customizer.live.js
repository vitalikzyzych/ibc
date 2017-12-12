
(function ($, api) {
    "use strict";

    function add_custom_css( $tag, $css ){

      if( $('style.' + $tag ).length > 0 ){
        $('style.' + $tag ).html( $css );
      } else {
        $('head').append("<style medial='all' class='" + $tag + "' type='text/css'>" + $css + "</style>");
      }

    }

    $('body').on('change_layout_width', function( event, $grid_width ){

      var $css = '';

      $css += '.ct-layout--boxed .ct-site{max-width: ' + $grid_width + 'px}';

      if( $css.length > 0 ){
        add_custom_css( 'change_layout_width', $css );
      }

    });

    $('body').on('change_grid_width', function( event, $grid_width ){

      var $css = '';

      $css += '.ct-layout--fixed .container-fluid{max-width: ' + $grid_width + 'px}';

      if( $css.length > 0 ){
        add_custom_css( 'change_grid_width', $css );
      }

    });

    $('body').on('change_header_height', function( event, $header_height ){

      var $css = '';

      $css += '.ct-layout--without-slider.ct-hero--with-image .ct-header__hero, .ct-layout--without-slider.ct-hero--with-image .ct-header__main-heading{min-height: ' + $header_height + 'px}';

      if( $css.length > 0 ){
        add_custom_css( 'change_header_height', $css );
      }

    });

    /*
    $('body').on('change_colors_navigation', function( event, $color_text, $color_active, $color_bg ){

      var $css = '';

      if( $color_text.length > 0 ){
        $css += '.ct-header__logo-nav a{color: ' + $color_text + '}';
      }
      if( $color_active.length > 0 ){
        $css += '.ct-header__logo-nav .current_page_item > a{color: ' + $color_active + '}';
      }


      if( $('style.change_colors_navigation').length > 0 ){
        $('style.change_colors_navigation').html( $css );
      } else {
        $('head').append("<style medial='all' class='change_colors_navigation' type='text/css'>" + $css + "</style>");
      }

    });

		*/

    wp.customize( 'header_heading_top', function( value ) {
			value.bind( function( newval ) {
        $('.ct-header__main-heading-title').css('padding-top', ( newval / 16 ) + 'rem');
			});
		});

    wp.customize( 'header_heading_bot', function( value ) {
			value.bind( function( newval ) {
        $('.ct-header__main-heading-title').css('padding-bottom', ( newval / 16 ) + 'rem');
			});
		});

    wp.customize( 'heading_alignment', function( value ) {
			value.bind( function( newval ) {
        $('body').removeClass( 'ct-hero--left ct-hero--center ct-hero--right' ).addClass( 'ct-hero--' + newval );
			});
		});

    wp.customize( 'heading_alignment_text', function( value ) {
			value.bind( function( newval ) {
        $('body').removeClass( 'ct-hero--text-left ct-hero--text-center ct-hero--text-right' ).addClass( 'ct-hero--text-' + newval );
			});
		});

    wp.customize( 'heading_position', function( value ) {
			value.bind( function( newval ) {
        $('body').removeClass( 'ct-hero--bottom ct-hero--top ct-hero--middle' ).addClass( 'ct-hero--' + newval );
			});
		});

    wp.customize( 'header_image_repeat', function( value ) {
			value.bind( function( newval ) {
        $('body').removeClass( 'ct-hero-image--no-repeat ct-hero-image--repeat ct-hero-image--repeat-x ct-hero-image--repeat-y' ).addClass( 'ct-hero-image--' + newval );
			});
		});

    wp.customize( 'header_image_att', function( value ) {
			value.bind( function( newval ) {
        $('body').removeClass( 'ct-hero-image--scroll ct-hero-image--fixed' ).addClass( 'ct-hero-image--' + newval );
			});
		});

    wp.customize( 'header_image_size', function( value ) {
			value.bind( function( newval ) {
        $('body').removeClass( 'ct-hero-image--cover ct-hero-image--auto' ).addClass( 'ct-hero-image--' + newval );
			});
		});

    wp.customize( 'header_image_alignment', function( value ) {
			value.bind( function( newval ) {
        $('body').removeClass( 'ct-hero-image--left-top ct-hero-image--center-top ct-hero-image--right-top ct-hero-image--left-bottom ct-hero-image--center-bottom ct-hero-image--right-bottom ct-hero-image--left-center ct-hero-image--center-center ct-hero-image--right-center' ).addClass( 'ct-hero-image--' + newval + '-' + wp.customize.instance( 'header_image_position' ).get() );
			});
		});

    wp.customize( 'header_image_position', function( value ) {
			value.bind( function( newval ) {
        $('body').removeClass( 'ct-hero-image--left-top ct-hero-image--center-top ct-hero-image--right-top ct-hero-image--left-bottom ct-hero-image--center-bottom ct-hero-image--right-bottom ct-hero-image--left-center ct-hero-image--center-center ct-hero-image--right-center' ).addClass( 'ct-hero-image--' + wp.customize.instance( 'header_image_alignment' ).get() + '-' + newval );
			});
		});

    wp.customize( 'layout', function( value ) {
			value.bind( function( newval ) {

        if( newval === true ){

          $('body').removeClass( 'ct-layout--boxed ct-layout--full' ).addClass( 'ct-layout--boxed' );

        } else {

          $('body').removeClass( 'ct-layout--boxed ct-layout--full' ).addClass( 'ct-layout--full' );

        }

			});
		});

    wp.customize( 'layout_fixed', function( value ) {
			value.bind( function( newval ) {

        if( newval === true ){

          $('body').removeClass( 'ct-layout--fixed ct-layout--fluid' ).addClass( 'ct-layout--fluid' );

        } else {

          $('body').removeClass( 'ct-layout--fixed ct-layout--fluid' ).addClass( 'ct-layout--fixed' );

        }

			});
		});

    wp.customize( 'layout_box_width', function( value ) {
			value.bind( function( newval ) {
        $('body').trigger('change_layout_width', [ newval ] );
			});
		});

    wp.customize( 'layout_grid_width', function( value ) {
			value.bind( function( newval ) {
        $('body').trigger('change_grid_width', [ newval ] );
			});
		});

    wp.customize( 'header_height', function( value ) {
			value.bind( function( newval ) {
        $('body').trigger('change_header_height', [ newval ] );
			});
		});

    /*
    wp.customize( 'color_navigation', function( value ) {
			value.bind( function( newval ) {
        $('body').trigger('change_colors_navigation', [ newval, wp.customize.instance( 'color_navigation_active' ).get(), wp.customize.instance( 'color_navigation_bg' ).get() ] );
			});
		});

    wp.customize( 'color_navigation_active', function( value ) {
			value.bind( function( newval ) {
        $('body').trigger('change_colors_navigation', [ wp.customize.instance( 'color_navigation' ).get(), newval, wp.customize.instance( 'color_navigation_bg' ).get() ] );
			});
		});
    */

	})(jQuery, wp.customize);
