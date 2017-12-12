(function($) {
  	"use strict";

    jQuery.fn.xtenderTabs = jQuery.fn.tabs;

  	$(document).ready( function() {
      $(".xtender-tab").xtenderTabs();
      $(".xtender-toggler").swap();
    });

    $(document).on('widget-updated widget-added', function( $param, $param2 ){
      $(".xtender-toggler", $param2 ).swap();
    });

    $(function() {
        $('.confirm-dialog').click(function(e) {
            e.preventDefault();
            if ( window.confirm(typeof curly_locale.confirm_dialog !== 'undefined' ? curly_locale.confirm_dialog : 'Are you sure?') ) {
                location.href = this.href;
            }
        });
    });

})(jQuery);
