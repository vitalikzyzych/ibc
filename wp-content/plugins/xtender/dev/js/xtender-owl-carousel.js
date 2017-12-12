(function ($) {
    "use strict";

    $(function() {

      if( $('.services-carousel').length > 0 ){
        $('.services-carousel').each(function(){
          var container = $(this);
          imagesLoaded( container, function(){
            var options = container.data('owl');
            options.margin = 20;
            container.owlCarousel(options);
          });
        });
      }

      if( $('.testimonials-carousel').length > 0 ){
        $('.testimonials-carousel').each(function(){
          var container = $(this);
          imagesLoaded( container, function(){
            var options = container.data('owl');
            options.margin = 30;
            container.owlCarousel(options);
          });
        });
      }

    });

})(jQuery);
