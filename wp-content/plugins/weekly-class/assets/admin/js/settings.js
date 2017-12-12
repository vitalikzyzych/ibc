if (typeof(document.getElementById("wcs-settings__app")) != 'undefined' && document.getElementById("wcs-settings__app") != null){

  ELEMENT.locale(window.EventsSchedule.locale_element_ui);

  var WcsSettings = new Vue({
    el: '#wcs-settings__app',
    data: function() {
      return {
        visible: false,
        form: {},
        loading: true,
        tabs: 'general'
      }
    },
    mounted: function(){
      var vm = this;
      vm.$http.get(
        window.EventsSchedule.rest_route + 'weekly-class/v1/settings/',
        {
          //emulateHTTP: true
          headers: {
            'X-WP-Nonce': window.EventsSchedule.nonce
          }
        }
      ).then(vm.successCallback, vm.errorCallback);
    },
    methods: {
      successCallback: function(response){
        var vm = this;
        vm.loading = false;
        vm.form = response.body;
        console.log(response);
      },
      errorCallback: function(response){
        var vm = this;
        vm.loading = false;
        console.log(response);
      },
      saveSettings: function(){
        var vm = this;
        vm.loading = true;
        vm.$http.post(
          window.EventsSchedule.rest_route + 'weekly-class/v1/settings/',
          vm.form,
          {
            emulateJSON: true,
            headers: {
              'X-WP-Nonce': window.EventsSchedule.nonce
            }
          }
        ).then(vm.successCallbackUpdate, vm.errorCallbackUpdate);
      },
      successCallbackUpdate: function(response){
        var vm = this;
        vm.loading = false;
        this.$notify({
          title: 'Success',
          message: 'Settings saved succesfully.',
          type: 'success',
          offset: 40,
          duration: 2000
        });
        console.log(response);
      },
      errorCallbackUpdate: function(response){
        var vm = this;
        vm.loading = false;
        this.$notify({
          title: 'Error',
          message: 'Settings could not be saved. Please try again!',
          type: 'error',
          offset: 40,
          duration: 2000
        });
        console.log(response);
      },
    }
	});
};

/*
(function($) {
	"use strict";


	$('.wcs-options--single-allow').dependsOn({

	    '#wcs_single': {
	        checked: true
	    }

	});

	$('.wcs-options--single').dependsOn({

	    '#wcs_single': {
	        checked: true
	    },
	    'input[name="wcs_single_box"]': {
	        values: ['left', 'center', 'right']
	    }

	});

	$('.wcs-options--map').dependsOn({

	    '#wcs_single': {
	        checked: true
	    },
	    '#wcs_map': {
	        checked: true
	    }

	});

	$(function() {

		$('.wp-color-picker-field').wpColorPicker();

	});


})(jQuery);
*/
