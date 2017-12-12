(function($) {
	"use strict";
/*
  var wcs_calendars = [];
	var loading = $('<div class="wcs-calendar-loading"><div class="wcs-spinner"><div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect5"></div></div></div>');

  $(document).ready(function() {

      $('.wcs-timetable__monthly-schedule').each(function(){

        var parent = $(this);
        var id = $(this).data('wcs-id');
        var unique_id = $(this).data('wcs-unique');

        wcs_calendars[unique_id] = $(this).fullCalendar({
          header: {
    				left: 'prev,next today',
    				center: 'title',
    				right: 'month,agendaWeek,agendaDay,listWeek'
    			},
          eventSources: [
            {
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'wcs_get_events_json',
                    id: id,
                }
            }
          ],
          eventRender: function(event, element) {

            element.attr({
              'data-wcs-id': event.id,
              'data-wcs-timestamp': event.timestamp
            });

            var modal = true;

            if( event.start.diff( moment() ) <= 0 ){
              modal = false;
              element.addClass('wcs-class--past-event');
            }

            if( ! event.visible ){
              modal = false;
              element.addClass('wcs-class--canceled');
            }

            if( modal ){
							element.addClass('wcs-class wcs-modal-call');
              element.wcs_modal_box();
            }


          },
          timeFormat: parent.data('wcs-time-format') === 12 ? 'h(:mm)t'  : 'H(:mm)',
          firstDay: wcs_locale_calendar.firstDay,
          monthNames: wcs_locale_calendar.monthNames,
          monthNamesShort: wcs_locale_calendar.monthNamesShort,
          dayNames: wcs_locale_calendar.dayNames,
          dayNamesShort: wcs_locale_calendar.dayNamesShort,
          eventLimit: parent.data('wcs-calendar-limit') === 1 ? true : false,
          allDaySlot: false,
					height: parent.data('wcs-calendar-sticky') === 1 ? false : 'auto',
					weekends: parent.data('wcs-calendar-weekends') === 1 ? true : false,
          loading: function(boolean){

						if( boolean ){
							$(loading).appendTo(parent.parents('.wcs-timetable__container') );
						}
						else {
							$('.wcs-calendar-loading', parent.parents('.wcs-timetable__container') ).remove();
						}

          }
        });

      });

  });
*/
})(jQuery);
