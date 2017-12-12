(function ($) {
    "use strict";

    /** Define Mobile Enviroment */
    var isMobile = {
        Android: function() {
            return navigator.userAgent.match(/Android/i);
        },
        BlackBerry: function() {
            return navigator.userAgent.match(/BlackBerry/i);
        },
        iOS: function() {
            return navigator.userAgent.match(/iPhone|iPad|iPod/i);
        },
        Opera: function() {
            return navigator.userAgent.match(/Opera Mini/i);
        },
        Windows: function() {
            return navigator.userAgent.match(/IEMobile/i);
        },
        any: function() {
            return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
        }
    };

    $('body').on('click', 'a.ct-social-box__link--popup', function(e){
      window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=no,height=600,width=600');
      return false;
    });

    if( $('#ct-header__hero-navigator').length > 0 ){

      var waypoint = new Waypoint({
        element: $("#ct-header__hero"),
        handler: function(direction) {
          if( direction === 'down' ) {
            $('#ct-header__hero-navigator').addClass('is_stuck');
          } else {
            $('#ct-header__hero-navigator').removeClass('is_stuck');
          }
        },
        offset: 'bottom-in-view'
      });

    }

    var waypoint_navigator = new Waypoint({
      element: $(".ct-footer"),
      handler: function(direction) {

        if( direction === 'down' ) {
          $('.bullets-container').removeClass('fadeIn').addClass('fadeOut');
        } else {
          $('.bullets-container').removeClass('fadeOut').addClass('fadeIn');
        }


      },
      offset: function(){
        return screen.height - parseInt( $(".ct-footer").outerHeight() );
      }
    });


    if ( ! isMobile.any() && pirouette_theme_data.menu.sticky === false ) {

      var offset = $('.ct-header__toolbar').outerHeight();
      var sticky =  new Waypoint.Sticky({
				element: $('.ct-header__wrapper'),
				offset: -offset,
				wrapper: '<div class="ct-header__logo-nav--sticky" />',
				stuckClass: 'ct-header__wrapper--stuck'
			});

      var hero_elem = $('#ct-header__hero').length > 0 ? $("#ct-header__hero") : $(".ct-content");
      var sticky_navigator = new Waypoint({
        element: hero_elem,
        handler: function(direction) {
          if( direction === 'down' ) {
            $('.ct-header__wrapper').addClass('ct-header__wrapper--shadow');
          } else {
            $('.ct-header__wrapper').removeClass('ct-header__wrapper--shadow');
          }
        },
        offset: $('.ct-header__logo-nav').outerHeight()
      });

    }


  $('body').on( 'click', 'a.ct-lightbox', function(e){

    e.preventDefault();

    $.magnificPopup.open({
      items:
        {
          src: $(this).attr('href'),
          type: 'image'
        }
    });

  });

	$( document ).ready(function() {

    $('a.ct-lightbox').magnificPopup({
      type: 'image',
      closeBtnInside: false
    });

    $('a.ct-lightbox-video').magnificPopup({
      type: 'iframe',
      closeBtnInside: false
    });

    $('.gallery-item a[href*=jpg], .gallery-item a[href*=jpeg], .gallery-item a[href*=png], .gallery-item a[href*=gif], .gallery-item a[href*=gif]').magnificPopup({
      type: 'image',
      closeBtnInside: false
    });

    $('a.xtd-ninja-modal-btn').magnificPopup({
      type: 'inline',
  		preloader: false,
    });





    if( jQuery().masonry && $('.ct-posts').length > 0 ){

      var $grid = $('.ct-posts').masonry({
        itemSelector: '.ct-post',
        percentPosition: true,
        resizable: true,
        masonry: {
          columnWidth: '.ct-post'
        }
      });

      $grid.imagesLoaded().progress( function() {
        $grid.masonry('layout');

      });

      $('.ct-posts .ct-post').each(function(){
        $(this).bind("DOMSubtreeModified", function() {
          $('iframe', this).on('load', function(){
            $grid.delay(2000).masonry('layout');
          });
        });
        $('iframe', this).on('load', function(){
          $grid.delay(2000).masonry('layout');
        });

      });

      if(typeof window.twttr !== 'undefined' ){

        twttr.events.bind(
        'loaded',
        function (event) {
          event.widgets.forEach(function (widget) {
            $grid.masonry('layout');
          });
        });

      }


    }

    $('.ct-gallery--carousel').each(function(){

        var container = $(this);

        imagesLoaded( container, function(){

          container.owlCarousel({
            items				  : 1,
            nav					  : false,
            navText				: ['',''],
            dots				  : true,
            loop 				  : true,
            dotsSpeed     : 700,
            autoplay 			: true,
            autoplayTimeout		: 3000,
            autoplayHoverPause	: true,
            autoHeight    : false,
            onInitialized  : function(){
              $grid.masonry('layout');
            }
          });

        });

    });








    $('.responsive-image', '.wcs-timetable--carousel').imagefill();

     if( ! isMobile.any() && $('.parallax-image').length > 0 ){
	  		$.stellar({
	  			horizontalScrolling: false,
	  			parallaxBackgrounds: false,
	  			hideDistantElements: false
	  		});
	  	}

	    /** Sticky */
	    if ( ! isMobile.any() ) {

	    }

  	});



})(jQuery);
