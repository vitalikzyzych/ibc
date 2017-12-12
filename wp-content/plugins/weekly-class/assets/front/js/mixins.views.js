/** Vue Mixins */
var wcs_filters_mixins = {
	methods: {
		updateModelValue: function ( event ) {
			this.$emit( 'input', event.target.value );
		}
	}
}



var wcs_timetable_weekly_tabs_mixins = {
	created: function(){
		this.stop = moment( this.start ).utc().add(7, 'days').format('YYYY-MM-DD');
	}
}



var wcs_timetable_mixins = {
	methods: {
		getOption: function( option, default_val ){
			return typeof this.options[option] !== 'undefined' ? this.options[option] : default_val;
		},
		getSelect2Options: function( terms ){
			var vm = this;
			var out = [];
			terms.forEach(function(val, index){
				out.push({ id: val.slug, text: val.name});
			});
			return out;
		},
		isMultiDay: function( ev ){
			if( typeof ev.multiday === 'undefined'  ) return;
			if( typeof ev.ending === 'undefined' ) return;
			return this.filter_var( ev.multiday );
		},
		arrayIntersection: function(arr1, arr2){
			var r = [], o = {}, l = arr2.length, i, v;
			for (i = 0; i < l; i++) {
					o[arr2[i]] = true;
			}
			l = arr1.length;
			for (i = 0; i < l; i++) {
					v = arr1[i];
					if (v in o) {
							r.push(v);
					}
			}
			return r;
		},
		addEvents: function( vm ){
			this.loading = vm;
			this.loading.startLoader();
			this.start 	= moment( this.stop ).utc().add( 1, 'days' ).format('YYYY-MM-DD' );
			this.stop 	= moment( this.start ).utc().add( parseInt(this.options.days) + 1, 'days' ).format('YYYY-MM-DD');
			this.getEvents();
		},
		getEvents: function(){
			this.loading_process = true;
			this.$http.get( ajaxurl, { params: { action: 'wcs_get_events_json', content: typeof this.options.content !== 'undefined' ? this.options.content : [], start: this.start, end: this.stop } } ).then( this.responseSuccess, this.responseError );
		},
		getLimit: function(){
			if( [ 0, 1, 2, 5, 6, 7, 8, 12 ].indexOf( parseInt( this.options.view ) ) === -1 ) return 99999999;
			if( typeof this.options.limit !== 'undefined' && parseInt( this.options.limit ) > 0  && parseInt( this.options.days ) === 0 ) return this.options.limit;
			return 999999999999;
		},
		filter_var: function( $value ){
			return [ '1', 'true', 'on', 'yes', true, 1 ].indexOf( $value ) === -1 ? false : true;
		},
		getFiltersType: function(){
			var $self 	= this;
			var $style 	= 'checkbox';
			if( this.filter_var( $self.options.filters_style )  ){
				$style = 'switch';
			}
			if( parseInt( $self.options.view ) === 7 ){
				$style = 'radio';
			}
			if( typeof $self.options.filters_select2 !== 'undefined' && $self.filter_var( $self.options.filters_select2 ) ){
				$style = 'select2';
			}
			return $style;
		},
		isFiltered: function( val ){
			var $self = this;
			if( ! this.filter_var( $self.options.show_past_events ) ){
				if( ! this.filter_var( val.future ) && this.filter_var( val.finished ) ){
					return true;
				}
			}
			var el_filters = [];
			var filters = $self.filters_active;
			for (var prop in filters ) {
				if ( ! filters.hasOwnProperty(prop) ) continue;
				if( filters[prop].length > 0 ){
					var taxes = [];
					if( typeof val.terms[prop] !== 'undefined' ){
						for(var tax in val.terms[prop] ){
							if ( ! val.terms[prop].hasOwnProperty(tax) ) continue;
							taxes.push( val.terms[prop][tax].slug );
						}
					}
					var meta_array = typeof val.terms[prop] !== 'undefined' ? taxes : [];
					if( prop === 'day_of_week' ){
						meta_array.push( moment( val.start ).utc().day() );
					}
					if( prop === 'time_of_day' ){
						switch ( true ) {
							case moment( val.start ).utc().hour() >= 0 && moment( val.start ).utc().hour() <= 11 :
								meta_array.push( 'morning' );
								break;
							case moment( val.start ).utc().hour() > 11 && moment( val.start ).utc().hour() <= 16 :
								meta_array.push( 'afternoon' );
								break;
							case moment( val.start ).utc().hour() > 16 && moment( val.start ).utc().hour() <= 23 :
								meta_array.push( 'evening' );
								break;
						}
					}
					el_filters.push( $self.arrayIntersection( filters[prop], meta_array ).length > 0 ? true : false );
				} else {
					el_filters.push( true );
				}
			}
			return el_filters.indexOf( false ) >= 0 ? true : false;
		},
		getActiveFilters: function( filters_data_string ){
			var active_filters = {};
			for (var prop in filters_data_string.taxonomies) {
				if ( !filters_data_string.taxonomies.hasOwnProperty(prop) ) continue;
				active_filters[prop] = [];
			}
			active_filters.day_of_week = [];
			active_filters.time_of_day = [];
			return active_filters;
		},
		updateFilterModel: function( $filter, $arguments, $method ){
			if( $method ){
				var $filters = [];
				if($arguments[0].length > 0){
					$filters.push($arguments[0]);
				}
				this.filters_active[$filter] = $filters;
			} else {
				var $self 	 	= this;
				var $filters	= $self.filters_active[ $filter ];
				var $argument = $arguments[0];
				if( $filters instanceof Array ){
					if( $filters.indexOf( $argument ) >= 0 ){
						$filters.splice( $filters.indexOf( $argument ), 1 );
					} else{
						$filters.push( $argument );
					}
				} else{
					$filters = [ $argument ];
				}
			}

			this.$emit( 'input', $filters );
			this.filterEvents();

		},
		updateFilterModelSelect2: function( $filter, $arguments ){

			var $filters	= $arguments[0];

			if( $filters === null ){
				$filters = [];
			}

			if( $filters instanceof Array ){

			} else {
				$filters = $filters === '-1' ? [] : [ $filters ];
			}

			this.filters_active[$filter] = $filters;
			this.$emit( 'input', $filters );
			this.filterEvents();

		},
		filterEvents: function(){
			var $self = this;
			var filtered_events = [];
			$self.events.forEach(function(val, index, array){
				if( $self.isFiltered(val) ){
					filtered_events.push(val.hash);
				}
			});
			this.events_filtered = filtered_events;
		},
		filterEvent: function( val ){
			if( this.isFiltered( val ) ){
				this.events_filtered.push( val.hash );
			}
		},
		responseSuccess: function(response){
			var $self = this;
			var newEvents = [];
			var newHashes = [];

			response.body.forEach(function(val, index, array){
				if( $self.events_hases.indexOf(val.hash) === -1 ){
					if( parseInt( $self.options.days ) === 0 && $self.getLimit() > 0 ){
						if( $self.events.length < $self.getLimit() ){
							$self.events_hases.push(val.hash);
							$self.filterEvent( val );
							$self.events.push(val);
						}
					} else {
						$self.events_hases.push(val.hash);
						$self.filterEvent( val );
						$self.events.push(val);
					}

				}
			});
			this.loading_process = false;
			if( this.loading ){
				this.loading.stopLoader();
			}
		},
		responseError: function(data){
			this.loading_process = false;
			if( this.loading ){
				this.loading.stopLoader();
			}
		},
		openTaxModal: function( data, options, event ){
				event.preventDefault();
				wcs_vue_modal.openModal( data, options );
		},
		openModal: function( data, options, event ){

			var vm = this;
			var $show = typeof data.excerpt !== 'undefined' ? vm.hasModal( data ) : true;

			if( ! $show && parseInt( options.modal ) === 2 ){

				if( event.target.getAttribute('href') === '#' ){
					event.preventDefault();
				}
				if( typeof data.permalink !== 'undefined' && vm.filter_var( window.wcs_settings.hasSingle ) ) window.location = data.permalink;

			}
			if( $show && typeof data.start === 'undefined' ){
				$show = false;
			}

			if( $show && ! this.filter_var( data.visible ) ){
				$show = false;
			}
			if( $show ){
				event.preventDefault();
				wcs_vue_modal.openModal( data, options );
			}
		},
		hasTax: function( tax, event ){
			var out = true;
			if(typeof this.options['show_' + tax] === 'undefined' || ! this.filter_var( this.options['show_' + tax] ) ){
				out = false;
			}
			if( out && ( typeof event.terms[tax] === 'undefined' || event.terms[tax].length <= 0 ) ){
				out = false;
			}
			return out;
		},
		hasModal: function(data){
			var vm = this;
			var options = vm.options;
			var $show = true;
			if( parseInt( options.modal ) === 2 ){
				$show = false;
			}
			if( $show && ! vm.filter_var( options.show_description ) ){
				$show = false;
			}
			if( $show && typeof data.excerpt !== 'undefined' && data.excerpt.length === 0 ){
				$show = false;
			}
			return $show;
		},
		hasLink: function(data){
			var vm = this;
			var options = vm.options;
			var $show = true;
			if( this.hasModal( data, options ) ){
				$show = false;
			}
			if( $show && ! vm.filter_var( wcs_settings.hasSingle ) ){
				$show = false
			}
			if( $show && typeof data.permalink === 'undefined' ){
				$show = false;
			}
			return $show;
		},
		hasMoreButton: function(){
			var $show = true;
			if( typeof this.options.reverse_order !== 'undefined' && this.filter_var( this.options.reverse_order ) ) return false;
			if( ! this.filter_var(this.options.show_more) ){
				$show = false;
			}
			if( ! this.options.days || parseInt( this.options.days ) === 0  ){
				$show = false;
			}
			if( parseInt( this.options.days ) > 7 * 4 ){
				$show = false;
			}
			return $show;
		},
		hasFilters: function(){
			var $self = this;
			var $out = [];
			for( index in $self.filters.taxonomies ){
				$out.push( this.filter_var( this.options['show_filter_' + index] ) );
			};
			return $out.indexOf( true ) >= 0 ? true : false;
		},
		hasToggler: function(){
			var toggler = this.status.toggler;
			if( toggler ){
				toggler = this.hasFilters();
			}
			if( toggler && parseInt(this.options.filters_position) !== 1 ){
				this.filters.visible = true;
				toggler = false;
			}
			if( toggler && parseInt(this.options.view) === 8 ){
				this.filters.visible = true;
				toggler = false;
			}
			return toggler;
		},
		termsList: function( terms ){
			return terms.length > 0 ? true : false;
		},
		event_time: function( time ){
			time = moment( time ).utc();
			return time.format( this.filter_var(this.options.show_time_format) ? 'h' : 'HH' ) + "<span class='wcs-addons--blink'>:</span>" + time.format('mm') + ( this.filter_var(this.options.show_time_format) ? time.format('a') : '' );
		},
		starting_ending: function( event ){
			return this.event_time(event.start) + ( this.filter_var(this.options.show_ending) ? ' - ' + this.event_time(event.end) : '' );
		}
	},
	computed: {
		events_list: function(){
			var $self = this;
			var $events = [];
			var $limit = $self.getLimit();
			$self.filterEvents();
			$self.events.forEach(function(val, index){
				if( $limit > 0 ){
					if( $self.events_filtered.length === 0 || $self.events_filtered.indexOf(val.hash) === -1 ){
						$events.push( val );
						$limit--;
					}
				}
			});
			var order = typeof $self.options.reverse_order !== 'undefined' ? $self.filter_var( $self.options.reverse_order ) : false;
			$events = order ? $events.reverse() : $events;
			return $events.length > 0 ? $events : false;

		},
		events_by_day: function() {
			var $self = this;
			var $events_by_day = {};
			var $limit = $self.getLimit();
			$self.filterEvents();
			var $events = $self.events;
			var order = typeof $self.options.reverse_order !== 'undefined' ? $self.filter_var( $self.options.reverse_order ) : false;
			$events = order ? $events.reverse() : $events;
			$events.forEach(function(val, index){
				if( $self.events_filtered.length === 0 || $self.events_filtered.indexOf(val.hash) === -1 ){
					if( ! $self.isMultiDay(val) ){
						var year 	= moment(val.start).utc().year();
						var month = moment(val.start).utc().month() + 1;
						var day 	= moment(val.start).utc().date();
						var day_name = year + '_' + month + '_' + day;
						if( $limit > 0 ){
							if( day_name in $events_by_day ){
								$events_by_day[day_name].events.push( val );
							}
							else {
								$events_by_day[day_name] = {
									date: moment(val.start).utc(),
									events: [ val ]
								};
							}
							$limit--;
						}
					}
				}
			});
			return $events_by_day;
		},
		all_days: function(){
			var $self = this;
			var $days = {};
			$self.filterEvents();
			$self.events.forEach(function(val, index){
				if( ! $self.isMultiDay( val ) ){
					var year 	= moment(val.start).utc().year();
					var month = moment(val.start).utc().month() + 1;
					var day 	= moment(val.start).utc().date();
					var day_name = year + '_' + month + '_' + day;
					if( ! ( day_name in $days ) ){
						$days[day_name] = {
							date: moment(val.start).utc()
						};
					}
				}
			});
			return $days;
		},
		active_day: function(){
			var $self = this;
			if( ! $self.selected_day ){
				return Object.keys($self.all_days)[0];
			} else{
				return $self.selected_day;
			}
		},
		filters_classes: function(){
			var filters_style = '';
			if(typeof this.options.filters_style !== 'undefined'){
				if( this.filter_var( this.options.filters_style ) ){
					filters_style = 'wcs-filters--switches';
				}
			}
			if( parseInt( this.options.view ) === 7 ){
				filters_style = 'wcs-filters--inline';
			}
			return ' ' + filters_style;
		},
		app_classes: {
			get: function(){
				return this.css_classes.join( ' ' );
			},
			set : function( newVal ){
				this.css_classes.push( newVal );
			}
		}
	},
	filters: {
		bgImage: function( img ){
			return typeof img !== 'undefined' && img.length > 0 ? 'background-image: url("' + img + '")' : '';
		}
	}
}



var wcs_modal_mixins = {
	computed:{
		modal_classes: function(){
			var classes = [];
			classes.push( this.data.image ? 'wcs-modal--with-image' : 'wcs-modal--without-image' );
			return this.classes + " " + classes.join(' ');
		}
	},
	methods: {
		isMultiDay: function( ev ){
			if( typeof ev.multiday === 'undefined'  ) return;
			if( typeof ev.ending === 'undefined' ) return;
			return this.filter_var( ev.multiday );
		},
		filter_var: function( $value ){
			return [ '1', 'true', 'on', 'yes', true, 1 ].indexOf( $value ) === -1 ? false : true;
		},
		closeModal: function( event ){
			var classes = event.target.className.split(' ');
			if( classes.indexOf( 'wcs-modal' ) >= 0 || classes.indexOf( 'wcs-modal__close' ) >= 0 ){
				event.preventDefault();
				wcs_vue_modal.visible = false;
				wcs_vue_modal.loading = true;
			}

		}
	}
}



var wcs_carousel_mixin = {
	mounted: function(){
		var vm = this;
		jQuery('.wcs-class:not(.vue-element)', vm.$el).each(function(){
			jQuery(this).addClass('vue-element');
		});
		jQuery(document).ready(function(){
			jQuery('.wcs-timetable__parent', vm.$el).imagesLoaded( function() {
				vm.$refs.carousel = jQuery('.wcs-timetable__parent', vm.$el).owlCarousel(vm.carousel_data_options).owlCarousel('refresh');
			});
		});
	},
	watch: {
		events_list: function(){
			var $self = this;
			setTimeout(function(){
				jQuery('.wcs-timetable__parent > .wcs-class:not(.vue-element)', $self.$el).each(function(){
					$self.$refs.carousel.owlCarousel( 'add', jQuery(this).addClass('vue-element') ).owlCarousel('update');
				});
				if( typeof $self.$refs.carousel.trigger === 'function' )
					$self.$refs.carousel.trigger('next.owl.carousel').trigger('prev.owl.carousel').owlCarousel('refresh');
			}, 100);
		}
	},
	computed: {
		carousel_data_options: function(){
			var $self = this;
			var $options = {
				margin: parseInt( $self.options.carousel_items_spacing ),
				dots: $self.filter_var( $self.options.carousel_dots ),
				nav: $self.filter_var( $self.options.carousel_nav ),
				loop: $self.events.length === 1 ? false : $self.filter_var( $self.options.carousel_loop ),
				autoplay: $self.filter_var( $self.options.carousel_autoplay ),
				autoplayTimeout: parseInt($self.options.carousel_autoplay_speed),
				autoplayHoverPause: true,
				navText: typeof $self.options.carousel_next !== 'undefined' && typeof $self.options.carousel_prev !== 'undefined' ? [$self.options.carousel_next, $self.options.carousel_prev] : ['',''],
				lazyLoad:true,
				stagePadding: parseInt( $self.options.carousel_padding ),
				responsive:{
							0:{
									items: parseInt( $self.options.carousel_items_xs)
							},
							600:{
									items: parseInt( $self.options.carousel_items_md)
							},
							1200:{
									items: parseInt( $self.options.carousel_items_lg)
							},
							1600:{
									items: parseInt( $self.options.carousel_items_xl)
							}
					},
					/*
					onRefresh: function () {console.log('daaaa');
						jQuery('.wcs-timetable__parent', $self.$el).imagesLoaded( function() {
						  jQuery('.wcs-timetable__parent', $self.$el).find('div.owl-item').height('');
						});
					},
					onRefreshed: function () { console.log('da');
						jQuery('.wcs-timetable__parent', $self.$el).imagesLoaded( function() {
							jQuery('.wcs-timetable__parent', $self.$el).find('div.owl-item').height(jQuery('.wcs-timetable__parent', $self.$el).height());
						});
					}*/

			};
			return $options;
		}
	}
}

if(typeof VueImagesLoaded !== 'undefined' ){
	wcs_carousel_mixin.directives = {
		imagesLoaded: VueImagesLoaded
	}
}




var wcs_timetable_monthly_mixins = {
	mounted: function(){
		var vm = this;
		var calendar = jQuery('.wcs-timetable__monthly-schedule', vm.$el).fullCalendar({
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay,listWeek'
			},
			events: function( start, end, timezone, callback ){
				//callback(vm.events);
				vm.start = start.toISOString();
				vm.stop = end.toISOString();
					vm.$http.get( ajaxurl, { params: { action: 'wcs_get_events_json', content: typeof vm.options.content !== 'undefined' ? vm.options.content : [], start: vm.start, end: vm.stop } } ).then( function(response ){
						var $self = vm;
						response.body.forEach(function(val, index, array){
							if( $self.events_hases.indexOf(val.hash) === -1 ){
								$self.events_hases.push(val.hash);
								$self.filterEvent( val );
								$self.events.push(val);
							}
						});

						if( this.loading ){
							this.loading.stopLoader();
						}
						callback(vm.events);

					});

			},
			eventClick: function(event, $event) {
				vm.openModal( event, vm.options, $event );
			},/*
			eventSources: [
				vm.events_list
			],*/

			timeFormat: parseInt( vm.options.show_time_format ) === 12 ? 'h(:mm)t'  : 'H(:mm)',
			firstDay: wcs_locale.firstDay,
			monthNames: wcs_locale.monthNames,
			monthNamesShort: wcs_locale.monthNamesShort,
			dayNames: wcs_locale.dayNames,
			dayNamesShort: wcs_locale.dayNamesShort,
			eventLimit: vm.filter_var(vm.options.calendar_limit),
			allDaySlot: false,
			height: vm.filter_var(vm.options.calendar_sticky) ? false : 'auto',
			weekends: vm.filter_var(vm.options.calendar_weekends),
			loading: function(boolean){
				if( boolean ){
					jQuery('<div class="wcs-calendar-loading"><div class="wcs-spinner"><div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect5"></div></div></div>').appendTo(vm.$el);
				}
				else {
					jQuery('.wcs-calendar-loading', vm.$el ).remove();
				}
			}
		});
	},
	methods:{
		responseSuccessMonthly: function(){

		},
		errorSuccessMonthly: function(){

		}
	}
}


var wcs_timetable_isotope_mixins = {
	mounted: function(){
		window.lodash = _.noConflict();
		var $self = this;
	},
	updated: function(){

	},
	watch: {
		'filters_active' : {
			handler: function( filters ){
				this.$refs.cpt['filters_active'] = filters;
				this.$refs.cpt.filter('isFilteredVal');

			},
			deep: true
		}
	},
	methods: {
		isZero: function(){
			return typeof this.$refs.cpt !== 'undefined' && typeof this.$refs.cpt.iso !== 'undefined' &&  typeof this.$refs.cpt.iso.filteredItems !== 'undefined' && this.$refs.cpt.iso.filteredItems.length <= 2 ? true : false;
		},
		getLabelAll: function( $tax ){
			if(typeof this.options['label_grid_all_' + $tax] !== 'undefined' && this.options['label_grid_all_' + $tax].length > 0){
				return this.options['label_grid_all_' + $tax];
			} else{
				return this.filters.taxonomies[$tax].label_all;
			}
		},
		layout: function() {
      this.$refs.cpt.layout('masonry');
    },
		isFilteredVal: function(){
			var vm = this;
			if( this.isZero() ){
				jQuery('.wcs-timetable__zero-data', vm.$el).show();
			} else {
				jQuery('.wcs-timetable__zero-data', vm.$el).hide();
			}
		},
		getIsotopeOptions: function(){
			return {
				itemSelector: '.wcs-iso-item',
				percentPosition: true,
				masonry: {
					columnWidth: '.wcs-isotope-item',
					gutter: '.wcs-isotope-gutter'
				},
				getSortData: {
					timestamp: function(item){
						return typeof item !== 'undefined' ? ( typeof item.timestamp !== 'undefined' ? item.timestamp : 0 ) : 0;
					}
	      },
	      sortBy : "timestamp",
				getFilterData: {
			    isFilteredVal: function(itemElem){
						var filters = this.filters_active;
						var $allow = true;
						if( typeof filters !== 'undefined' && filters ){
							for(var key in filters){
								if( [ 'time_of_day', 'day_of_week' ].indexOf(key) >= 0 && filters[key].length > 0 && filters[key][0].length > 0 && typeof itemElem !== 'undefined' ){

									if( key === 'day_of_week' && window.lodash.intersection( filters[key], [ moment( itemElem.start ).utc().day().toString() ] ).length <= 0 ){
										$allow = false;
									}
									else if( key === 'time_of_day' ){
										switch ( true ) {
											case moment( itemElem.start ).utc().hour() >= 0 && moment( itemElem.start ).utc().hour() <= 11 :
												var time = [ 'morning' ];
												break;
											case moment( itemElem.start ).utc().hour() > 11 && moment( itemElem.start ).utc().hour() <= 16 :
												var time = [ 'afternoon' ];
												break;
											case moment( itemElem.start ).utc().hour() > 16 && moment( itemElem.start ).utc().hour() <= 23 :
												var time = [ 'evening' ];
												break;
											}
											var intersection = window.lodash.intersection( filters[key], time );
											if( intersection.length <= 0 ) $allow = false;
									}
								}
								else if ( filters[key].length > 0 && filters[key][0].length > 0 && typeof itemElem !== 'undefined' && window.lodash.intersection( filters[key], window.lodash.map( itemElem.terms[key], function(item){ return item.slug } ) ).length <= 0  ){
									$allow = false;
								}
							}
						}
			      return $allow;
			    }
			  }
			}
		},
		expandIsotopeItem: function( hash, $event ){ console.log();
			this.iso_expanded_items.push( hash );
			$event.target.parentElement.parentElement.className += ' wcs-class--active';
			//jQuery(event.target).parents('.wcs-class').addClass( 'wcs-class--active' );
			this.layout();
		},
		minimizeIsotopeItem: function( hash, $event ){
			this.iso_expanded_items.splice( this.iso_expanded_items.indexOf(hash), 1 );
			$event.target.parentElement.parentElement.className = $event.target.parentElement.parentElement.className.replace( ' wcs-class--active', '' );
			//jQuery(event.target).parents('.wcs-class').removeClass( 'wcs-class--active' );
			this.layout();
		},
		isIsotopeExpanded: function( hash, event){
			return this.iso_expanded_items.indexOf(hash) >= 0 ? true : false;
		},
	},
	filters: {
		eventCSSIsotope: function(css, event){
			var $classes = [];
			if( typeof event.expanded !== 'undefined' ){
				$classes.push( 'wcs-class--active' );
			}
			return css + ' ' + $classes.join(' ');
		}
	}
}

if(typeof VueImagesLoaded !== 'undefined' ){
	wcs_timetable_isotope_mixins.directives = {
		imagesLoaded: VueImagesLoaded
	}
}

var wcs_timetable_timeline_mixins = {
	mounted: function(){
		//window.lodash = _.noConflict();
		var $self = this;
	},
	computed: {
		timeline_events: function(){
			if( this.events.length === 0 ) return [];
			return this.events_by_day ? Object.values(this.events_by_day) : [];
		}
	},
	methods: {
		layout: function() {
      this.$refs.timeline.layout('masonry');
    },
		getIsotopeOptions: function(){
			return {
				itemSelector: '.wcs-iso-item',
				percentPosition: true,
				masonry: {
					columnWidth: '.wcs-isotope-item',
					gutter: '.wcs-isotope-gutter'
				},
			}
		},
	},
	filters: {
		eventCSSIsotope: function(css, event){
			var $classes = [];
			if( typeof event.expanded !== 'undefined' ){
				$classes.push( 'wcs-class--active' );
			}
			return css + ' ' + $classes.join(' ');
		}
	}
}



var wcs_timetable_weekly_mixins = {
	created: function(){
		if( this.filter_var( this.options.show_navigation ) ){
			this.options.show_past_events = true;
		}
		this.stop = moment( this.start ).utc().add(7, 'days').format('YYYY-MM-DD');
		if( this.filter_var( this.options.show_starting_hours ) ) this.app_classes = 'wcs-timetable--grouped-by-hours';
		this.dateRange.start = moment( this.start ).startOf('isoWeek').format('YYYY-MM-DD');
		this.dateRange.stop = moment(this.start ).endOf('isoWeek').format('YYYY-MM-DD');
		this.dateRangeHistory.push( this.dateRange.start + '/' + this.dateRange.stop );
	},
	computed: {
		dateRangeTitle: function(){
			var title = '';
			if( moment( this.dateRange.start ).isSame( this.dateRange.stop, 'month' ) ){
				title = moment(this.dateRange.start).format('MMMM') + ' ' + moment(this.dateRange.start).format('D') + ' - ' + moment(this.dateRange.stop).format('D');
			} else {
				title = moment(this.dateRange.start).format('MMMM D') + ' - ' + moment(this.dateRange.stop).format('MMMM D');
			}
			return title;
		},
		week: function(){
			var $self = this;
			var firstDay = parseInt( wcs_locale.firstDay );
			var week = {};
			for( $i = firstDay; $i <= firstDay + 7; $i++ ){
				var $key = $i <= 6 ? $i : Math.abs( $i - 7 );
				week['day_' + $key] = { 'day_num' : $key, 'events' : [] };
			}
			$self.events.forEach(function(val, index){
				if( $self.inRange( val ) && ( $self.events_filtered.length === 0 || $self.events_filtered.indexOf(val.hash) === -1 ) ){
					if( ! $self.isMultiDay( val ) ){
						week['day_' + moment(val.start).utc().format('e')].events.push( val );
					}
				}
			});
			return week;
		},
		starting_times: function(){
			var vm = this;
			var out = [];
			vm.events.forEach(function(val, index){
				if( out.indexOf( moment( val.start ).utc().format( 'HH:mm' ) ) < 0 ){
					out.push(moment( val.start ).utc().format('HH:mm'));
				}
			});
			out.sort();
			return out;
		},
	},
	filters: {
		eventSlotCSS: function( val, event ){
			return val + ' wcs-class--slots-' + event.period / 10;
		},
		check12format: function( val, timeformat ){

			if( timeformat === true || timeformat === 'true' || timeformat === '1' || timeformat === 1 ){
				var time_array = val.split(':');
				switch ( parseInt( time_array[0] ) ) {
					case 1 : return '1:'+time_array[1]+'am'; break;
					case 2 : return '2:'+time_array[1]+'am'; break;
					case 3 : return '3:'+time_array[1]+'am'; break;
					case 4 : return '4:'+time_array[1]+'am'; break;
					case 5 : return '5:'+time_array[1]+'am'; break;
					case 6 : return '6:'+time_array[1]+'am'; break;
					case 7 : return '7:'+time_array[1]+'am'; break;
					case 8 : return '8:'+time_array[1]+'am'; break;
					case 9 : return '9:'+time_array[1]+'am'; break;
					case 10 : return '10:'+time_array[1]+'am'; break;
					case 11 : return '11:'+time_array[1]+'am'; break;
					case 12 : return '12:'+time_array[1]+'pm'; break;
					case 13 : return '1:'+time_array[1]+'pm'; break;
					case 14 : return '2:'+time_array[1]+'pm'; break;
					case 15 : return '3:'+time_array[1]+'pm'; break;
					case 16 : return '4:'+time_array[1]+'pm'; break;
					case 17 : return '5:'+time_array[1]+'pm'; break;
					case 18 : return '6:'+time_array[1]+'pm'; break;
					case 19 : return '7:'+time_array[1]+'pm'; break;
					case 20 : return '8:'+time_array[1]+'pm'; break;
					case 21 : return '9:'+time_array[1]+'pm'; break;
					case 22 : return '10:'+time_array[1]+'pm'; break;
					case 23 : return '11:'+time_array[1]+'pm'; break;
					case 24 : return '12:'+time_array[1]+'am'; break;
					default: return val;
				}
			} else {
				return val;
			}
		}
	},
	methods: {
		inRange: function( $event ){
			if( ! this.filter_var( this.options.show_navigation ) ) return true;
			return moment( this.dateRange.start ).isBefore( $event.start ) &&  moment( this.dateRange.stop ).add(1, 'days').isAfter( $event.start ) ? true : false;
		},
		navigationGoNext: function(){
			this.dateRange.start = moment( this.dateRange.start ).add(7, 'days').format('YYYY-MM-DD');
			this.dateRange.stop = moment( this.dateRange.start ).add(6, 'days').format('YYYY-MM-DD');
			if( this.dateRangeHistory.indexOf( this.dateRange.start + '/' + this.dateRange.stop ) === -1 ){
				this.dateRangeHistory.push(this.dateRange.start + '/' + this.dateRange.stop );
				this.start = this.dateRange.start;
				this.stop = this.dateRange.stop;
				this.getEvents();
			}
		},
		navigationGoPrev: function(){
			this.dateRange.start = moment( this.dateRange.start ).subtract(7, 'days').format('YYYY-MM-DD');
			this.dateRange.stop = moment( this.dateRange.start ).add(6, 'days').format('YYYY-MM-DD');
			if( this.dateRangeHistory.indexOf( this.dateRange.start + '/' + this.dateRange.stop ) === -1 ){
				this.dateRangeHistory.push(this.dateRange.start + '/' + this.dateRange.stop );
				this.start = this.dateRange.start;
				this.stop = this.dateRange.stop;
				this.getEvents();
			}
		},
		hasHourlyEvents: function(starting){
			var vm = this;
			var week = vm.week;
			var out = false;
			for(var day in week){
				if( typeof vm.week[day].events !== 'undefined' ){
					if( vm.getHourlyEvents( starting, vm.week[day].events ) !== false ){
						out = true; break;
					}
				}
			}
			return out;
		},
		getHourlyEvents: function(starting, events){
			var vm = this;
			var out = [];
			events.forEach(function(eve, inx){
				if( starting === moment(eve.start).utc().format('HH:mm') ) out.push(eve);
			});
			return out.length > 0 ? out : false;
		},
		countWeekEvents: function(){
			var vm = this;
			var count = 0;
			for(var day in vm.week){
				count += vm.week[day].events.length;
			}
			return count;
		},
		day_name: function( day_num ){
			return wcs_locale.dayNames[day_num];
		}
	}
}


var wcs_timetable_countdown = {
	created: function(){
		var vm = this;
		if( vm.countdown.asMilliseconds() > 0 ){
			window.setInterval(function(){
				vm.now = moment().utc();
			}, 1000);
		}
	},
	mounted: function() {
		var classes = [];
		switch(true){
			case this.options.countdown_image_position == 0 : {
				classes.push( 'wcs-timetable--countdown-position-top-left' );
			} break;
			case this.options.countdown_image_position == 1 : {
				classes.push( 'wcs-timetable--countdown-position-top-center' );
			} break;
			case this.options.countdown_image_position == 2 : {
				classes.push( 'wcs-timetable--countdown-position-top-right' );
			} break;
			case this.options.countdown_image_position == 3 : {
				classes.push( 'wcs-timetable--countdown-position-middle-left' );
			} break;
			case this.options.countdown_image_position == 4 : {
				classes.push( 'wcs-timetable--countdown-position-middle-center' );
			} break;
			case this.options.countdown_image_position == 5 : {
				classes.push( 'wcs-timetable--countdown-position-middle-right' );
			} break;
			case this.options.countdown_image_position == 6 : {
				classes.push( 'wcs-timetable--countdown-position-bottom-left' );
			} break;
			case this.options.countdown_image_position == 7 : {
				classes.push( 'wcs-timetable--countdown-position-bottom-center' );
			} break;
			case this.options.countdown_image_position == 8 : {
				classes.push( 'wcs-timetable--countdown-position-bottom-right' );
			} break;
		}
		classes.push( typeof this.single.thumbnail !== 'undefined' && this.single.thumbnail.length > 0 ? 'wcs-timetable--countdown-with-image' : 'wcs-timetable--countdown-without-image' );
		classes.push( ! this.filter_var( this.options.countdown_vertical ) ? 'wcs-timetable--countdown-default' : 'wcs-timetable--countdown-vertical' );
		this.$el.querySelector('.wcs-timetable--countdown').className += ' ' + classes.join( ' ' );
	},
	computed: {
		timestamp: function(){
			return moment( ( parseInt( this.single.timestamp ) + parseInt( wcs_locale.gmtOffset ) * -1 ) * 1000 ).utc();
		},
		countdown: function(){
			return moment.duration( this.timestamp.diff( this.now ) );
		},
		remaining_years: function(){
			var years = this.countdown.years();
			return years <= 0 ? 0 : years;
		},
		remaining_months: function(){
			var months = this.countdown.months();
			if(typeof this.options.label_countdown_years === 'undefined' || this.options.label_countdown_years.length <= 0 ){
				months += this.remaining_years * 365;
			}
			return months <= 0 ? 0 : months;
		},
		remaining_days: function(){
			var days = this.countdown.days();
			if(typeof this.options.label_countdown_months === 'undefined' || this.options.label_countdown_months.length <= 0 ){
				days += this.remaining_months * 30;
			}
			return  days <= 0 ? 0 : days;
		},
		remaining_hours: function(){
			var hours = this.countdown.hours();
			if(typeof this.options.label_countdown_days === 'undefined' || this.options.label_countdown_days.length <= 0 ){
				hours += this.remaining_days * 24;
			}
			return hours <= 0 ? 0 : hours;
		},
		remaining_minutes: function(){
			var minutes = this.countdown.minutes();
			if(typeof this.options.label_countdown_hours === 'undefined' || this.options.label_countdown_hours.length <= 0 ){
				minutes += this.remaining_hours * 60;
			}
			return minutes <= 0 ? 0 : minutes;
		},
		remaining_seconds: function(){
			var seconds = this.countdown.seconds();
			if(typeof this.options.label_countdown_minutes === 'undefined' || this.options.label_countdown_minutes.length <= 0 ){
				seconds += this.remaining_minutes * 60;
			}
			return seconds <= 0 ? 0 : seconds;
		},
	},
	filters: {
		leadingZero: function( number ){
			return parseInt( number ) <= 9 ? '0' + number : number;
		}
	},
	methods: {
		hasCountdownImage: function( cl ){
			if( ! this.filter_var( this.options.countdown_image ) ) return false;
			return true;
			return cl.thumbnail;
		},
		timeLabel: function( label ){
			var $labels = this.options['label_countdown_' + label].split(',');
			if( $labels.length > 1 ){
				if( this['remaining_' + label] == 1 ){
					return $labels[0];
				} else{
					return $labels[1];
				}
			} else{
				return $labels[0];
			}
		}
	}
}

var wcs_timetable_cover = {
	mounted: function() {
		var classes = [];
		switch(true){
			case this.options.cover_text_position == 0 : {
				classes.push( 'wcs-timetable--cover-position-top-left' );
			} break;
			case this.options.cover_text_position == 1 : {
				classes.push( 'wcs-timetable--cover-position-top-center' );
			} break;
			case this.options.cover_text_position == 2 : {
				classes.push( 'wcs-timetable--cover-position-top-right' );
			} break;
			case this.options.cover_text_position == 3 : {
				classes.push( 'wcs-timetable--cover-position-middle-left' );
			} break;
			case this.options.cover_text_position == 4 : {
				classes.push( 'wcs-timetable--cover-position-middle-center' );
			} break;
			case this.options.cover_text_position == 5 : {
				classes.push( 'wcs-timetable--cover-position-middle-right' );
			} break;
			case this.options.cover_text_position == 6 : {
				classes.push( 'wcs-timetable--cover-position-bottom-left' );
			} break;
			case this.options.cover_text_position == 7 : {
				classes.push( 'wcs-timetable--cover-position-bottom-center' );
			} break;
			case this.options.cover_text_position == 8 : {
				classes.push( 'wcs-timetable--cover-position-bottom-right' );
			} break;
		}
		switch(true){
			case this.options.cover_text_align == 0 : {
				classes.push( 'wcs-timetable--cover-align-left' );
			} break;
			case this.options.cover_text_align == 1 : {
				classes.push( 'wcs-timetable--cover-align-center' );
			} break;
			case this.options.cover_text_align == 2 : {
				classes.push( 'wcs-timetable--cover-align-right' );
			} break;
		}
		switch(true){
			case this.options.cover_text_size == 0 : {
				classes.push( 'wcs-timetable--cover-text-size-sm' );
			} break;
			case this.options.cover_text_size == 1 : {
				classes.push( 'wcs-timetable--cover-text-size-md' );
			} break;
			case this.options.cover_text_size == 2 : {
				classes.push( 'wcs-timetable--cover-text-size-lg' );
			} break;
		}
		switch(true){
			case this.options.cover_aspect == 0 : {
				classes.push( 'wcs-timetable--cover-aspect-169' );
			} break;
			case this.options.cover_aspect == 1 : {
				classes.push( 'wcs-timetable--cover-aspect-169v' );
			} break;
			case this.options.cover_aspect == 2 : {
				classes.push( 'wcs-timetable--cover-aspect-43' );
			} break;
			case this.options.cover_aspect == 3 : {
				classes.push( 'wcs-timetable--cover-aspect-43v' );
			} break;
			case this.options.cover_aspect == 4 : {
				classes.push( 'wcs-timetable--cover-aspect-11' );
			} break;
		}
		classes.push( typeof this.single.thumbnail !== 'undefined' && this.single.thumbnail.length > 0 ? 'wcs-timetable--cover-with-image' : 'wcs-timetable--cover-without-image' );
		classes.push( typeof this.options.cover_overlay_type !== 'undefined' && parseInt(this.options.cover_overlay_type) == 0 ? 'wcs-timetable--cover-overlay-image' : 'wcs-timetable--cover-overlay-text' );
		this.$el.querySelector('.wcs-timetable--cover').className += ' ' + classes.join( ' ' );
	},
	methods: {
		hasImage: function(){
			return typeof this.single.thumbnail !== 'undefined' && this.single.thumbnail.length > 0 ? true : false;
		}
	},
	filters: {

	}
}


var wcs_mixins_monthly_calendar = {
	created: function(){

		var vm = this;

		this.loading_history.push( this.start + this.stop );

		this.updateCalendar( this.events );

		if( this.selectedDay === null ){

			var $sel_day = this.today;
			var $days = moment( $sel_day ).utc().endOf('month').diff( moment( $sel_day ).utc(), 'days');
			var ev_today = false;

			for($day = 1; $day <= $days; $day++ ){
				var $day_date = moment($sel_day).utc().add( $day, 'days' ).format('YYYY-MM-DD');
				var events = vm.getDayEvents( $day_date );
				if( events.length >= 0 ){

					events.forEach(function(value, index, array){
						if( moment( value.start ).utc().isAfter( moment().utc() ) ){
							ev_today = true;
						}
					});
				}

				if( ev_today ){
					$sel_day = $day_date;
				}
				if( ev_today ) break;
			}

			this.selectedDay = {
				date: $sel_day,
				events: this.getDayEvents( $sel_day )
			};

		}
	},
	watch: {
		events: function( newEvents ){
			var $self = this;
			this.updateCalendar( newEvents );
		}
	},
	mounted: function (){

	},
	computed: {
		days: function(){
			var days = [];

			var firstDayLocale = parseInt( wcs_locale.firstDay );
					firstDayLocale = firstDayLocale === 0 ? 7 : firstDayLocale;

			var today = this.today;
			var calendarDay = moment( this.calendarDay ? this.calendarDay : today ).format('YYYY-MM-DD');

			var subtract = Math.abs( ( firstDayLocale - 7  -  moment(calendarDay).startOf('month').isoWeekday() ) % 7 );
			var add = Math.abs( 7 - moment(calendarDay).endOf('month').isoWeekday()  );

			var firstDay = moment(calendarDay).startOf('month').subtract( subtract > 6 ? -1 : subtract , 'days');
			var lastDay = moment(calendarDay).endOf('month').add( add, 'days' );


			for (var m = moment(firstDay); m.diff(lastDay, 'days') <= 0; m.add(1, 'days')) {
			  days.push({
					date : m.format('YYYY-MM-DD'),
					past : moment(moment(calendarDay).startOf('month').format('YYYY-MM-DD')).isAfter(m.format('YYYY-MM-DD'), 'day'),
					future : moment(m.format('YYYY-MM-DD')).isAfter(moment(calendarDay).endOf('month').format('YYYY-MM-DD'), 'day'),
					today: m.isSame( today, 'day'),
					events: this.getDayEvents( m.format('YYYY-MM-DD') )
				});
			}

			return days;
		},
		week: function(){
			var $self = this;
			var firstDay = parseInt( wcs_locale.firstDay );
			var week = {};
			for( $i = firstDay; $i <= firstDay + 7; $i++ ){
				var $key = $i <= 6 ? $i : Math.abs( $i - 7 );
				week['day_' + $key] = { 'day_num' : $key, 'events' : [] };
			}
			return week;
		},
		getCurrentMonth: function(){
			return moment( this.calendarDay ? this.calendarDay : this.today ).format('MMMM YYYY');
		},
		getCurrentWeek: function(){
			return moment( this.calendarDay ? this.calendarDay : this.today ).utc().startOf('month').format('W');
		},
		calendarClasses: function(){
			var out = [];
			switch( parseInt( this.options.mth_cal_agenda_position ) ){
				case 1 : out.push('wcs-timetable--side-agenda wcs-timetable--side-agenda-left'); break;
				case 2 : out.push('wcs-timetable--side-agenda wcs-timetable--side-agenda-right'); break;
				case 3 : out.push('wcs-timetable--inside-agenda'); break;
				default : out.push('wcs-timetable--bellow-agenda');
			}
			switch( parseInt( this.options.mth_cal_borders ) ){
				case 1 : out.push('wcs-timetable--horizontal-borders'); break;
				case 2 : out.push('wcs-timetable--vertical-borders'); break;
				case 3 : out.push('wcs-timetable--all-borders'); break;
				default : out.push('wcs-timetable--no-borders');
			}
			if( this.filter_var( this.options.mth_cal_rows ) ) out.push( 'wcs-timetable--alternate' );
			if( this.filter_var( this.options.mth_cal_highlight ) ) out.push( 'wcs-timetable--highligh-round' );
			if( ! this.filter_var(this.options.show_past_events) ) out.push( 'wcs-timetable--past-hidden' );
			if( this.loading_process ) out.push( 'wcs-timetable--loading' );
			return out.join(' ');
		}
	},
	filters: {
		eventSlotCSS: function( val, event ){
			return val + ' wcs-class--slots-' + event.period / 10;
		}
	},
	methods: {
		isNavVisible: function( button ){
			if( ! this.options.label_mth_prev || ! this.options.label_mth_next ) return false;
			var calendarDay = moment( this.calendarDay ? this.calendarDay : this.today ).utc().format('YYYY-MM-DD');
			if( ! this.filter_var( this.options.show_past_events ) && button == 'prev' && moment(calendarDay).utc().startOf('month').subtract(1, 'days').isBefore( this.today, 'month' ) ) return false;
			return true;
		},
		isAgendaInside: function(n){
			var $allow = false;
			if( this.options.mth_cal_agenda_position == 3 ){
				if( moment( this.selectedDay.date ).utc().format('W') == parseInt(this.getCurrentWeek) + n - 1 ){
					$allow = true;
				}
			}
			return $allow;
		},
		selectDay: function(day, $event){
			if( day.future || day.past ) return;
			if( day.events.length <= 0 ) return;
			if( ! this.filter_var( this.options.show_past_events ) && moment( this.today ).utc().isAfter( day.date, 'day' ) ) return;
			this.selectedDay = day;
			$event.target.parentElement.className += ' wcs-week--selected';
		},
		updateCalendar: function( newEvents ){
			var $self = this;
			newEvents.forEach(function(val, index){
				var $start = moment(val.start).utc();
				var $year = $start.format('YYYY');
				var $month = $start.format('MM');
				var $day   = $start.format('DD');
				if(typeof $self.calendar['year_' + $year ] === 'undefined' ){
					$self.$set( $self.calendar, 'year_' + $year, {} );
				}
				if(typeof $self.calendar['year_' + $year ]['month_' + $month] === 'undefined' ){
					$self.$set( $self.calendar['year_' + $year], 'month_' + $month, {} );
				}
				if(typeof $self.calendar['year_' + $year ]['month_' + $month]['day_'+ $day] === 'undefined' ){
					$self.$set( $self.calendar['year_' + $year]['month_' + $month], 'day_'+ $day, [] );
				}
				var $allow = true;
				$self.calendar['year_' + $year ]['month_' + $month]['day_'+ $day].forEach(function(valItem, indexItem){
					if( valItem.hash === val.hash ){
						$allow = false;
					}
				});
				if( $allow ){
					$self.calendar['year_' + $year ]['month_' + $month]['day_'+ $day].push(val);
				}
			});
		},
		getDayEvents: function( day ){
			var $self = this;
			var events = [];
			var $start = moment(day);
			var $year  = $start.format('YYYY');
			var $month = $start.format('MM');
			var $day   = $start.format('DD');
			if(typeof $self.calendar['year_' + $year] !== 'undefined' ){
				if(typeof $self.calendar['year_' + $year]['month_' + $month] !== 'undefined' ){
					if(typeof $self.calendar['year_' + $year]['month_' + $month]['day_' + $day] !== 'undefined' ){
						events = $self.calendar['year_' + $year]['month_' + $month]['day_' + $day];
					}
				}
			}
			return events;
		},
		getFilteredCalendarEvents: function(events){
			var $self = this;
			var events_out = [];
			if(typeof this.events_filtered !== 'undefined' && this.events_filtered.length >= 1 ){
				events.forEach(function(val, index){
					if( $self.events_filtered.indexOf( val.hash ) < 0 && ! $self.isMultiDay(val) ){
						events_out.push( val );
					}
				});
			} else{
				events.forEach(function(val, index){
					if( ! $self.isMultiDay(val) ){
						events_out.push( val );
					}
				});
			}
			return events_out;
		},
		subtractMonth: function(){
			if( this.loading_process ) return;
			this.calendarDay = moment( this.calendarDay ? this.calendarDay : this.today ).subtract(1, 'month').format('YYYY-MM-DD');
			this.checkForCalendarUpdate();
		},
		addMonth: function(){
			if( this.loading_process ) return;
			this.calendarDay = moment( this.calendarDay ? this.calendarDay : this.today ).add(1, 'month').format('YYYY-MM-DD');
			this.checkForCalendarUpdate();
		},
		checkForCalendarUpdate: function(){
			this.start = moment( this.calendarDay ).startOf('month').format('YYYY-MM-DD');
			this.stop = moment( this.calendarDay ).endOf('month').format('YYYY-MM-DD');
			if( this.loading_history.indexOf( this.start + this.stop ) < 0 ){
				this.loading_history.push( this.start + this.stop );
				this.getEvents();
			}
		},
		isWeekday: function( day, method ){
			var show_weekends = this.filter_var( this.options.mth_cal_show_weekends );
			if( show_weekends ) return true;

			var weekend = [6,7];
			day = method === true ? ( day === 0 ? 7 : day ) : moment(day.date).isoWeekday();
			return weekend.indexOf(day) >= 0 ? false : true;
		},
		countWeekEvents: function(){
			var vm = this;
			var count = 0;
			for(var day in vm.week){
				count += vm.week[day].events.length;
			}
			return count;
		},
		day_name: function( day_num ){
			var out = [];
			switch( this.options.mth_cal_day_format ){
				case 'ddd' : out = wcs_locale.dayNamesShort[day_num]; break
				case 'd' : out = wcs_locale.dayNamesMin[day_num]; break
				default : out = wcs_locale.dayNames[day_num];
			}
			return out;
		},
		dayClasses: function(day){
			var out = [];
			if( day.past ) out.push('wcs-date--past-month');
			if( day.future ) out.push('wcs-date--future-month');
			if( day.today ) out.push('wcs-date--today');
			if( day.events.length > 0 && moment( day.date ).isSame( this.calendarDay ? this.calendarDay : this.today, 'month') ){
				if( this.getFilteredCalendarEvents(day.events).length > 0 ){
					out.push('wcs-date--with-events wcs-modal-call');
				}
			}
			if( moment( this.today ).utc().isAfter( day.date, 'day' ) ) out.push( 'wcs-date--past' );
			if( moment( this.today ).utc().isBefore( day.date, 'day' ) ) out.push( 'wcs-date--future' );
			if( moment(day.date).utc().isSame( this.selectedDay.date, 'day' ) ) out.push('wcs-date--selected');
			return out.join(' ');
		},
		weekClasses: function(n){
			var out = [];
			var weekNr = parseInt(this.getCurrentWeek) + n - 1;
			out.push( 'wcs-week--' + weekNr );
			if( moment(this.selectedDay.date).utc().format('W') == weekNr ) out.push( 'wcs-week--selected' );
			return out.join(' ');
		}
	}
}
