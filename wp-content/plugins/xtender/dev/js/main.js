(function ($) {
    "use strict";

    $("document").ready(function(){

    });

    /** Nice Self Scroll */
    $('a.smooth-scroll, .smooth-scroll > a').on('click', function (e) {

  		e.preventDefault();
  		var target = $(this).attr('href');
      $('html, body').animate({
          scrollTop: $(target).offset().top - $('.ct-header__wrapper--stuck').outerHeight()
      }, 700);
  	});
    $('.wpb_single_image.smooth-scroll a').on('click', function (e) {
  		e.preventDefault();
  		var target = $(this).attr('href');
      $('html, body').animate({
          scrollTop: $(target).offset().top - $('.ct-header__wrapper--stuck').outerHeight()
      }, 700);
  	});

    $('.wpb_single_image[data-img-hover]').each(function(){

      var img = $('img', this).attr('src');
      var img_hover = $(this).data('img-hover');

      $('img', this).hover(function(){
        $(this).attr( 'src', img_hover );
        $(this).attr( 'srcset', $(this).attr('srcset').replace( img, img_hover ) );
      }, function(){console.log(this);
        $(this).attr( 'src', img );
        $(this).attr( 'srcset', $(this).attr('srcset').replace( img_hover, img ) );
      });
    });

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

  $('body').on('section-reached', function(){

   var section         = $('body').sectionScroll.activeSection;
   var section_title   = $(section).attr('data-section-title');
   var section_color   = $(section).attr('data-section-color');
   var section_list    = $('.section-bullets');

   if(typeof section_color === 'undefined' ){
     $(section_list).attr('style', '');
   } else{
     $(section_list).css('color', section_color);
   }

   if(typeof section_title === 'undefined' ){

     if( $('.section-title', section_list ).length > 0 ){
       $('.section-title', section_list ).addClass('is_hidden');
     }

   } else {

     if( $('.section-title', section_list ).length === 0 ){

       $( section_list ).prepend( '<li class="section-title"><div><span>' + section_title  + '<span></div></li>');

     } else {
       $('.section-title').removeClass('is_hidden');
       $('.section-title > div > span', section_list ).text( section_title );

     }

   }

  });

  $(function() {

    if( ! isMobile.any() && $('.parallax-image[data-stellar-ratio]').length > 0 ){
  		$.stellar({
  			horizontalScrolling: false,
  			parallaxBackgrounds: false,
  			hideDistantElements: false
  		});
  	}

    if( $('.scrollable-section').length > 0 ){
      $('body').sectionScroll({
        titles: false
      });
    }


    $('.xtd-carousel-mini > .owl-carousel').each(function(){

        var container = $(this);

        imagesLoaded( container, function(){

          container.owlCarousel({
            items				  : 1,
            nav					  : false,
            navText				: ['',''],
            dots				  : container.data('dots'),
            loop 				  : container.data('loop'),
            dotsSpeed     : 700,
            autoplay 			: container.data('autoplay'),
            autoplayTimeout		: container.data('timeout'),
            autoplayHoverPause	: container.data('hover'),
            autoHeight    : true
          });

        });

    });

      $('.xtd-carousel-filmstrip').each(function(){

        var filmstrip = $(this);

        imagesLoaded( filmstrip, function(){
          filmstrip.bxSlider({
            slideSelector : '.wpb_single_image',
            ticker: true,
            minSlides: 4,
            speed: $(filmstrip).data('speed'),
            onSliderLoad: function(){
              $('body').trigger('bxSlider-ready')
            }
          });
        });

      });


    $('.xtd-gmap').each(function(){
      var map = $(this);

      $(this).height(map.data('height')).gmap3({
        center:[map.data('latitude-center'), map.data('longitude-center')],
        zoom: map.data('zoom'),
        disableDefaultUI: true,
				draggable: false,
				mapTypeId: google.maps.MapTypeId[map.data('type')],
				mapTypeControl: false,
				mapTypeControlOptions: {},
				navigationControl: false,
				scrollwheel: false,
				streetViewControl: false,
				styles: map.data('theme').length >= 1 ? map.data('theme') : ''
      })
      .marker([
        {position:[map.data('latitude'), map.data('longitude')]}
      ])
      .overlay({
        position: [map.data('latitude'), map.data('longitude')],
        content: function(){

          var $image = map.data('image-src');
          var $title = map.data('title');
          var $description = map.data('description');
          var $html = '';

          $html = '<div class="xtd-gmap-info">';
            $html += typeof $image !== 'undefined' && $image.length > 0 ? '<img src="' + $image + '">' : '';
            $html += typeof $title !== 'undefined' && $title.length > 0 ? '<div>' + $title + '</div>' : '';
            $html += typeof $description !== 'undefined' && $description.length > 0 ? '<p>' + $description + '</p>' : '';
          $html += '</div>';

          return $html;
        },
        x:-100,
        y:-70
      });

    });

  });


  $("document").ready(function(){
    if( jQuery().magnificPopup ) {
      $('.xtd-modal--inline > .vc_btn3[href^=#], a.xtd-modal--inline[href^=#]').magnificPopup({
          type: 'inline',
          closeBtnInside:true
      });
    }
  });



})(jQuery);
