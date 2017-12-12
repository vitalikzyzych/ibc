(function($) {
	"use strict";

	$(function() {
		var $date_default = new Date( $( 'input[name="wcs-timestamp"]' ).val() + 'T' + ( $('select[name="wcs-hour"]').val() >= 10 ? $('select[name="wcs-hour"]').val() : '0' + $('select[name="wcs-hour"]').val() ) + ':' + ( parseInt( $('select[name="wcs-minutes"]').val() ) >= 10 ? $('select[name="wcs-minutes"]').val() : '0' + $('select[name="wcs-minutes"]').val() ) + ':00Z' );

		var $offset = $date_default.getTimezoneOffset();
			$date_default.setMinutes($date_default.getMinutes() + $offset);
		var $months = $( "#wcs-datepicker" ).data( 'wcs-months' );
		var $days = $( "#wcs-datepicker" ).data( 'wcs-days' );
		var $days_short = $( "#wcs-datepicker" ).data( 'wcs-days-short' );
		var $days_min = $( "#wcs-datepicker" ).data( 'wcs-days-min' );
		var $first_day = $( "#wcs-datepicker" ).data( 'wcs-firstday' );

		$( "#wcs-datepicker" ).datepicker({
			dateFormat: "yy-mm-dd",
			altField: 'input[name="wcs-date"]',
			changeMonth: true,
		    changeYear: true,
		    defaultDate: $date_default,
		    firstDay : $first_day,
				beforeShow: function(input, inst) {
		       $('#ui-datepicker-div').addClass('wcs-datepicker-pop');
		   },
		   onClose : function(input, inst) {
		       $('#ui-datepicker-div').removeClass('wcs-datepicker-pop');
		   },
		    monthNames: $months.split(','),
	        monthNamesShort: $months.split(','),
	        dayNames: $days.split(','),
	        dayNamesShort: $days_short.split(','),
	        dayNamesMin: $days_min.split(',')
		});

		$( "#wcs-datepicker-ending" ).datepicker({
			dateFormat: "yy-mm-dd",
			changeMonth: true,
		    changeYear: true,
		    beforeShow: function(input, inst) {
		       $('#ui-datepicker-div').addClass('wcs-datepicker-pop');
		   },
		   onClose : function(input, inst) {
		       $('#ui-datepicker-div').removeClass('wcs-datepicker-pop');
		   },
		});

		$('body').on('click', '#wcs-until a', function(e){
			e.preventDefault();
			$(this).next('input').val('');
		});

		$( ".repeat_until" ).datepicker({
			dateFormat: "yy-mm-dd",
			changeMonth: true,
		    changeYear: true,
		    beforeShow: function(input, inst) {
		       $('#ui-datepicker-div').addClass('wcs-datepicker-pop');
		   },
		   onClose : function(input, inst) {
		       $('#ui-datepicker-div').removeClass('wcs-datepicker-pop');
		   },
		});

		function call_datepickers( $item ){

			$( $item ).datepicker({
				changeMonth: true,
			    changeYear: true,
				defaultDate: $date_default,
			    firstDay : $first_day,
			    monthNames: $months.split(','),
		        monthNamesShort: $months.split(','),
		        dayNames: $days.split(','),
		        dayNamesShort: $days_short.split(','),
		        dayNamesMin: $days_min.split(','),
		        dateFormat: "yy-mm-dd",
		        beforeShow: function() {

			        if( ! $('#ui-datepicker-div').hasClass('wcs-datepicker-pop') ){
				       $('#ui-datepicker-div').addClass('wcs-datepicker-pop');
			        }
			    },
				onClose : function(){
					if( $('#ui-datepicker-div').hasClass('wcs-datepicker-pop') ){
				       $('#ui-datepicker-div').removeClass('wcs-datepicker-pop');
			        }
				}

			});

		}

		var canceled_data = $( "#wcs-canceled-item" ).html();


		function add_repeat(){

			var template_canceled_data = Handlebars.compile( canceled_data );

			$('#wcs-canceled__holder').append( template_canceled_data() );

			call_datepickers( "input[name='wcs-canceled-days[]']" );

		}

		$( '#add-canceled' ).on( 'click', add_repeat );

		$( 'body' ).on( 'click', '.wcs-delete-canceled', function(e){
			e.preventDefault();

			var $confirm = confirm("Are you sure?");

			if( $confirm ){
				$(this).parents('.wcs-canceled__item').remove();
			}

		});


		call_datepickers( "input[name='wcs-canceled-days[]']" );


		var $duration = $( "#wcs-duration" );

		$( '.slider', $duration ).slider({
			value: $duration.data( 'wcs-value' ),
			step: 5,
			min: 5,
			max: 1440,
			slide: function( event, ui ) {

				var $time = ' ';
				var $minutes = ui.value;

				if( Math.floor( ui.value / 60 ) === 1 ){
					$time = Math.floor( ui.value / 60 ) + ' ' + $duration.data( 'wcs-units-hour' );
				}

				if( Math.floor( ui.value / 60 ) >= 2 &&  Math.floor( ui.value / 60 ) <= 24  ){
					$time = Math.floor( ui.value / 60 ) + ' ' + $duration.data( 'wcs-units-hours' );
				}

				$minutes -= Math.floor( ui.value / 60 ) * 60;

				if( $minutes > 0 ){
					$minutes = $minutes + ' ' + $duration.data( 'wcs-units-minutes' );
				} else{
					$minutes = '';
				}

				$(this).siblings(".slider_value").children('strong').text( $time );
				$(this).siblings(".slider_value").children('em').text( $minutes );

				$(this).siblings("input[type=hidden]").val(ui.value);

			}
		});


		$('#wrapper__wcs_action_page').dependsOn({

		    'input[name*=_wcs_action_call]': {
		        values: ['0']
		    }

		});

		$('#wrapper__wcs_repeat_days').dependsOn({

			'select[name*=wcs-interval]': {
					values: ['2', 2]
			}

		});

		$('#wrapper__wcs_action_custom').dependsOn({

		    'input[name*=_wcs_action_call]': {
		        values: ['1']
		    }

		});

		$('#wrapper__wcs_action_email').dependsOn({

		    'input[name*=_wcs_action_call]': {
		        values: ['2']
		    }

		});

		$('#wcs-canceled').dependsOn({

		    'select[name*=wcs-status]': {
		        values: ['2']
		    }

		});

		$('#wcs-until').dependsOn({

		    'select[name*=wcs-interval]': {
		        values: ['1', '2', '3', '4', '5', 1, 2, 3, 4, 5]
		    }

		});

		$('#wcs-duration-container').dependsOn({

		    'input[name*=wcs_multiday]': {
		        checked: false
		    }

		});

		$('.wcs-admin-metabox-time--ending').dependsOn({

		    'input[name*=wcs_multiday]': {
		        checked: true
		    }

		});
/*
		$('#wcs-repeat-container').dependsOn({

		    'input[name*=wcs_multiday]': {
		        checked: false
		    }

		});*/

	});


})(jQuery);
