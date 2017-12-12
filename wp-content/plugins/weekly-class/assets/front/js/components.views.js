/** Filters Components */
Vue.component( 'filter-checkbox', {
  template: '#wcs_templates_filter--checkbox',
	props: [ 'filter', 'options', 'value', 'title', 'slug', 'name', 'unique_id', 'level' ],
	mixins: [wcs_filters_mixins]
});

Vue.component('filter-select2', {
  props: ['options', 'value', 'placeholder', 'multiple'],
  template: '#wcs_templates_filter--select2',
  mounted: function () {
    var vm = this;
    if (typeof vm.$el.select2 != 'function'){
      jQuery(document).ready(function(){
        jQuery(vm.$el).select2({ data: vm.options, multiple: vm.multiple, placeholder: vm.placeholder, language: vm.getLanguage() });
        /*
        jQuery(vm.$el)
          // init select2
          .select2({ data: vm.options, multiple: vm.multiple, placeholder: vm.placeholder, language: vm.getLanguage() })
          .val(vm.value)
          .trigger('change')
          // emit event on change.
          .on('change', function () {
            //console.log(jQuery(vm.$el).val());
            vm.$emit('input', jQuery(vm.$el).val() );
          });
          */
      });
    }

  },
  watch: {
    value: function (value) {
      // update value
      console.log(value, this);
      jQuery(this.$el).val(value).trigger('change');
    },
    options: function (options) {
      // update options //jQuery(this.$el).empty().select2({ data: options })
    }
  },
  methods: {
    getLanguage: function(){
      return {
        errorLoading: function () {
          return window.wcs_select2.errorLoading;
        },
        inputTooLong: function (args) {
          var overChars = args.input.length - args.maximum;
          var message = window.wcs_select2.inputTooLong.replace( '%n', overChars );

          return message;
        },
        inputTooShort: function (args) {
          var remainingChars = args.minimum - args.input.length;

          var message = window.wcs_select2.inputTooShort.replace( '%n', remainingChars );

          return message;
        },
        loadingMore: function () {
          return window.wcs_select2.loadingMore;
        },
        maximumSelected: function (args) {
          var message = window.wcs_select2.inputTooShort.replace( '%n', args.maximum );
          return message;
        },
        noResults: function () {
          return window.wcs_select2.noResults;
        },
        searching: function () {
          return window.wcs_select2.searching;
        }
      }
    }
  },
  destroyed: function () {
    jQuery(this.$el).off().select2('destroy')
  }
})


Vue.component( 'filter-switch', {
  template: '#wcs_templates_filter--switch',
	props: [ 'filter', 'options', 'value', 'title', 'slug', 'name', 'unique_id', 'level' ],
	mixins: [wcs_filters_mixins]
});
Vue.component( 'filter-radio', {
  template: '#wcs_templates_filter--radio',
	props: [ 'filter', 'options', 'value', 'title', 'slug', 'name', 'unique_id' ],
	methods: {
		isChecked: function(slug, value){
			var $checked = false;
			if( ( typeof slug !== 'undefined' ? slug.toString() : '' ) === ( value.length > 0 ? value[0].toString() : '' ) ){
				$checked = true;
			}
			return $checked;
		},
		updateRadioModelValue: function( event ){
			this.$refs.input.value = event.target.value;
			this.checked = this.$refs.input.value;
			this.$emit( 'input', this.$refs.input.value );
		}
	},
	mixins: [wcs_filters_mixins]
});


/** Loader Components */
Vue.component( 'wcs-loader', {
  template: '#wcs_templates_misc--loader'
});

Vue.component( 'wcs-modal', {
  template: '<div>sstst</div>'
});

/** Modal Components */
Vue.component( 'modal-normal', {
  template: '#wcs_templates_modal--normal',
	props: [ 'data', 'options', 'classes' ],
	mixins: [wcs_modal_mixins],
  methods: {
    openTaxModal: function( data, options, event ){
			event.preventDefault();
			wcs_vue_modal.openModal( data, options );
		}
  }
});

Vue.component( 'modal-large', {
  template: '#wcs_templates_modal--large',
	props: [ 'data', 'options' , 'classes'],
	mixins: [wcs_modal_mixins],
  methods: {
    openTaxModal: function( data, options, event ){
			event.preventDefault();
			wcs_vue_modal.openModal( data, options );
		}
  }
});

Vue.component( 'modal-taxonomy', {
  template: '#wcs_templates_modal--taxonomy',
	props: [ 'data', 'options', 'content', 'classes' ],
	mixins: [wcs_modal_mixins]
});

/** Misc. Components */
Vue.component( 'button-more', {
  template: '#wcs_templates_misc--button-more',
	props: [ 'color', 'more' ],
	methods: {
		startLoader: function(){
			this.ladda.start();
		},
		stopLoader: function(){
			this.ladda.stop();
		},
		addEvents: function(){
			this.$emit( 'add-events', this );
		}
	},
	mounted: function(){
    this.ladda = Ladda.create( this.$el );
	}
});
Vue.component('taxonomy-list', {
  template : ''+
    '<span><template v-for="(room, index) in event.terms[tax]">' +
      '<template v-if="room.url"><a :href="room.url">{{room.name}}</a></template>' +
      '<template v-else-if="room.desc"><a href="#" class="wcs-modal-call" v-on:click="openModal( room, options, $event )">{{room.name}}</a></template>' +
      '<template v-else>{{room.name}}</template>' +
      '<template v-if="index !== (event.terms[tax].length - 1)">, </template>' +
    '</template></span>' ,
  props    : [ 'tax', 'options', 'event' ],
  methods  : {
    openModal: function( item, options, $event ){
      this.$emit( 'open-modal', item, options, $event );
    }
  }
});
