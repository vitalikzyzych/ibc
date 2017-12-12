if( document.getElementById('wcs-builder-app') !== null ){
  Vue.component( 'control-switch', {
    template: '#wcs_templates_switch',
    model: {
      prop: 'checked',
      event: 'change'
    },
    mounted: function(){
      if(typeof this.description !== 'undefined' ){
        jQuery(this.$el).tooltip({
          direction: 'left'
        });
      }
    },
  	props: ['value', 'title', 'name', 'description'],
  	methods: {
  		updateModelValue: function ( event ) {
  			this.$emit( 'change', event.target.checked );
  		},
      filter_var: function( $value ){
  			return [ '1', 'true', 'on', 'yes', true, 1 ].indexOf( $value ) === -1 ? false : true;
  		}
  	}
  });
  Vue.component( 'control-group', {
    template: '#wcs_templates_group',
    model: {
      prop: 'checked',
      event: 'change'
    },
  	props: ['value', 'title', 'name', 'description', 'options'],
  	methods: {
  		updateModelValue: function ( event ) {
  			this.$emit( 'change', event.target.value );
  		}
  	}
  });
  Vue.component( 'control-select', {
    template: '#wcs_templates_select',
    model: {
      prop: 'checked',
      event: 'change'
    },
  	props: ['value', 'title', 'name', 'description', 'options'],
  	methods: {
  		updateModelValue: function ( event ) {
  			this.$emit( 'change', event.target.value );
  		}
  	}
  });
  Vue.component( 'control-text', {
    template: '#wcs_templates_text',
  	props: ['value', 'title', 'name', 'description', 'placeholder'],
  	methods: {
  		updateModelValue: function ( event ) {
  			this.$emit( 'input', event.target.value );
  		}
  	}
  });
  Vue.component( 'control-color', {
    template: '#wcs_templates_color',
  	props: ['value', 'title', 'name', 'description', 'color' ],
  	methods: {
  		updateModelValue: function ( event ) {
  			this.$emit( 'input', event.target.value );
  		}
  	},
    mounted: function(){
      var vm = this;
      jQuery('.wp-color-picker-field', vm.$el).wpColorPicker({
        change: function(event, ui){
          vm.$emit( 'input', ui.color.toString() );
        },
        clear: function(event, ui){
          vm.$emit( 'input', '' );
        }
      });
    }
  });
  /** Component: Datepicker */
  Vue.component( 'control-datepicker', {
    template: '#wcs_templates_datepicker',
  	props: ['value', 'title', 'name', 'description', 'options'],
    methods:{
      clearValue: function(){
        this.$emit('input', '');
      }
    },
    mounted: function(){
      var vm = this;
      var datepicker = vm.$el.getElementsByClassName('wcs_datepicker');
      jQuery( datepicker[0] ).datepicker({
  			dateFormat: "yy-mm-dd",
        defaultDate: vm.value,
  			changeMonth: true,
		    changeYear: true,
        onSelect: function(dataText){
          vm.$emit( 'input', dataText );
        },
        maxDate: -1,
        firstDay : vm.options.firstDay,
		    monthNames: vm.options.monthNames,
        monthNamesShort: vm.options.monthNamesShort,
        dayNames: vm.options.dayNames,
        dayNamesShort: vm.options.dayNamesShort,
        dayNamesMin: vm.options.dayNamesMin
  		});
    }
  });
  /** Component: Slider */
  Vue.component( 'control-slider', {
    template: '#wcs_templates_slider',
  	props: ['value', 'title', 'name', 'description', 'attributes'],
  	methods: {
  		updateModelValue: function ( event ) {
  			this.$emit( 'input', event.target.value );
  		}
  	},
    computed: {
      prefix: function(){
        return this.attributes.pref;
      },
      suffix: function(){
        var $out = '';
        if(typeof this.attributes.suf === 'object' ){
          if(typeof this.attributes.suf.zero !== 'undefined' && this.value == 0 ){
            $out = this.attributes.suf.zero;
          }
          else if( typeof this.attributes.suf.one !== 'undefined' && this.value == 1 ){
            $out = this.attributes.suf.one;
          }
          else if(typeof this.attributes.suf.many !== 'undefined' && this.value > 1 ){
            $out = this.attributes.suf.many;
          }
          else if(typeof this.attributes.suf.many !== 'undefined'){
            $out = this.attributes.suf.many;
          }
          else if(typeof this.attributes.suf.zero !== 'undefined'){
            $out = this.attributes.suf.zero;
          }
          else if(typeof this.attributes.suf.one !== 'undefined'){
            $out = this.attributes.suf.one;
          }
        } else if(typeof this.attributes.suf === 'string'){
            $out = this.attributes.suf;
        }
        return $out;
      }
    },
    mounted: function(){
      var vm = this;
      jQuery( ".wcs_slider", this.$el ).slider({
        min: this.attributes.min,
        max: this.attributes.max,
        step: this.attributes.step,
        value: this.value,
        slide: function( event, ui ) {
          jQuery(this).siblings("input[type=hidden]").val();
          vm.$emit('input', ui.value);
        }
      });
    }
  });

  var wcs_builder_app = new Vue({
  	el: '#wcs-builder-app',
  	data: function(){
  		var $form = {
  			'view' 		: typeof wcs_timetable_data.view !== 'undefined' ? parseInt( wcs_timetable_data.view ) : 0,
  			'days'		: typeof wcs_timetable_data.days !== 'undefined' ? parseInt( wcs_timetable_data.days ) : 7,
        'limit'		: typeof wcs_timetable_data.limit !== 'undefined' ? parseInt( wcs_timetable_data.limit ) : 0,
        'title'   : typeof wcs_timetable_data.title !== 'undefined' && wcs_timetable_data.title.length > 0 ? wcs_timetable_data.title : '',
        'content' : typeof wcs_timetable_data.content !== 'undefined' ? wcs_timetable_data.content : {},
        'filters' : typeof wcs_timetable_data.filters !== 'undefined' ? wcs_timetable_data.filters : {},
        'single'  : typeof wcs_timetable_data.single !== 'undefined' ? wcs_timetable_data.single : ''
  		};
  		$form['id'] = wcs_timetable_data.id;
  		var $tabs = {
  			'view' : typeof wcs_timetable_data.view !== 'undefined' ? parseInt( wcs_timetable_data.view ) : 0,
  			'content' : 'wcs_type',
  			'filters' : 'wcs_type',
  			'design'	: 'general'
  		};
  		for(var taxonomy in wcs_taxonomies){
  			$form['content'][taxonomy] = typeof wcs_timetable_data['content'] !== 'undefined' && typeof wcs_timetable_data['content'][taxonomy] !== 'undefined' ? wcs_timetable_data['content'][taxonomy] : [];
  			$form['filters'][taxonomy] = typeof wcs_timetable_data['filters'] !== 'undefined' && typeof wcs_timetable_data['filters'][taxonomy] !== 'undefined' ? wcs_timetable_data['filters'][taxonomy] : [];
        $form['label_' + taxonomy] = typeof wcs_timetable_data['label_' + taxonomy] !== 'undefined' ? wcs_timetable_data['label_' + taxonomy] : '';
        $form['label_filter_' + taxonomy] = typeof wcs_timetable_data['label_filter_' + taxonomy] !== 'undefined' ? wcs_timetable_data['label_filter_' + taxonomy] : '';
        $form['show_' + taxonomy] = typeof wcs_timetable_data['show_' + taxonomy] !== 'undefined' ? wcs_timetable_data['show_' + taxonomy] : [];
        $form['show_filter_' + taxonomy] = typeof wcs_timetable_data['show_filter_' + taxonomy] !== 'undefined' ? wcs_timetable_data['show_filter_' + taxonomy] : [];
        $form['show_modal_' + taxonomy] = typeof wcs_timetable_data['show_modal_' + taxonomy] !== 'undefined' ? wcs_timetable_data['show_modal_' + taxonomy] : [];
      }
  		var $side_options = {};

  		//Parse Views
  		for(var view in wcs_builder.sections.style.options){

  			//Parse Labels
  			for(var label in wcs_builder.sections.style.options[view].labels){
  				wcs_builder.sections.labels.options.unshift(wcs_builder.sections.style.options[view].labels[label]);
  				wcs_conditions[wcs_builder.sections.style.options[view].labels[label].id] = {
  					'relation' : 'AND',
  					'conditions' : [ [ 'view', '==', wcs_builder.sections.style.options[view].value ] ]
  				};
  			}

  			//Parse options
        var options = wcs_builder.sections.style.options[view].options;
        for(var option in options){
          var option_name = wcs_builder.sections.style.options[view].options[option].id;
  				$form[option_name] = typeof wcs_timetable_data[option_name] !== 'undefined' ? wcs_timetable_data[option_name] : wcs_builder.sections.style.options[view].options[option].value;
          if( typeof wcs_conditions[option_name] === 'undefined' ){
            wcs_conditions[option_name] = {
    					'relation' : 'AND',
    					'conditions' : [ [ 'view', '==', wcs_builder.sections.style.options[view].value ] ]
    				};
          }

  			}


        //Parse colors
        if(typeof wcs_builder.sections.style.options[view].colors !== 'undefined'){
          wcs_builder.sections.design.options.push({
            'label' : wcs_builder.sections.style.options[view].title,
            'id'  : wcs_builder.sections.style.options[view].slug,
            'colors' : wcs_builder.sections.style.options[view].colors
          });

          wcs_conditions['tab_colors_' +  wcs_builder.sections.style.options[view].slug] = {
  					'relation' : 'AND',
  					'conditions' : [ [ 'view', '==', wcs_builder.sections.style.options[view].value ] ]
  				};
        }

  			//Parse Sections
  			wcs_conditions['section_side_' + view] = {
  				'relation' : 'AND',
  				'conditions' : [ [ 'view', '==', view ] ]
  			};
  		}

      // Display
      for(var option in wcs_builder.sections.display.options){
        var option_name = wcs_builder.sections.display.options[option].id;
        $form[option_name] = typeof wcs_timetable_data[option_name] !== 'undefined' ? wcs_timetable_data[option_name] : wcs_builder.sections.display.options[option].default;
      }

      // Filters Options
      for(var option in wcs_builder.sections.filters_options.options){
        var option_name = wcs_builder.sections.filters_options.options[option].id
        $form[option_name] = typeof wcs_timetable_data[option_name] !== 'undefined' ? wcs_timetable_data[option_name] : wcs_builder.sections.filters_options.options[option].default;
      }

      // Modal
      for(var option in wcs_builder.sections.modal.options){
        var option_name = wcs_builder.sections.modal.options[option].id;
        $form[option_name] = typeof wcs_timetable_data[option_name] !== 'undefined' ? wcs_timetable_data[option_name] : wcs_builder.sections.modal.options[option].default;
      }

      // Colors
      for(var color in wcs_builder.sections.design.options){
        for(var option in wcs_builder.sections.design.options[color].colors){
          var option_name = wcs_builder.sections.design.options[color].colors[option].id;
          $form[option_name] = typeof wcs_timetable_data[option_name] !== 'undefined' ? wcs_timetable_data[option_name] : wcs_builder.sections.design.options[color].colors[option].default;
        }
      }

      // Labels
      for(var label in wcs_builder.sections.labels.options){
        var option_name = wcs_builder.sections.labels.options[label].id;
        var option_default = typeof wcs_builder.sections.labels.options[label].default !== 'undefined' ? wcs_builder.sections.labels.options[label].default : '';
        $form[option_name] = typeof wcs_timetable_data[option_name] !== 'undefined' ? wcs_timetable_data[option_name] : option_default;
      }

  		return {
  			default_data : {},
  			builder: wcs_builder,
  			timetable: wcs_timetable_data,
  			taxonomies: wcs_taxonomies,
        posts: wcs_posts,
  			form: $form,
  			tabs: $tabs,
  			conditions: wcs_conditions,
  			side_options: $side_options,
        loading: false,
        ladda: false,
        notification: {
          show: false,
          success: false,
          message: ''
        }
  		};

  	},
    mounted: function(){
      console.log(this.conditions);
      this.ladda = Ladda.create( document.getElementById("save") );
      jQuery('.clipboard-text').tooltip({
        direction: 'left'
      });
      var clipboard = new Clipboard('.clipboard-text');
      clipboard.on('success', function(e){
      var text = e.text;
      jQuery('.clipboard-text').text(e.trigger.dataset.clipboardSuccess);
      jQuery('.clipboard-text').addClass('success');
      setTimeout(function(){
        jQuery('.clipboard-text').text(text);
        jQuery('.clipboard-text').removeClass('success');
      }, 4000);
      });
    },
  	methods: {
      isTaxSelected: function( term, tax ){
        return '';
        if( typeof term.term_id === 'undefined' ) return '';
        if( typeof this.form['content'][tax] === 'undefined' ) return '';
        return this.form['content'][tax].indexOf( String(term.term_id) ) >= 0 || this.form['content'][tax].indexOf(term.term_id) >= 0 ? 'selected' : '';
      },
      startLoader: function(){
        this.loading = true;
        this.ladda.start();
      },
      stopLoader: function(){
        this.loading = false;
        this.ladda.stop();
      },
      updateSchedule: function(){
        this.startLoader();
  			this.$http.post( ajaxurl, this.form, { emulateJSON: true, params: { action: 'wcs_update_schedule', wp_nonce: wcs_builder_nonce } } ).then( this.responseSuccess, this.responseError );
  		},
      responseSuccess: function(response){
  			if( this.loading ){
  				this.stopLoader();
  			}
        if( response.body.success === true ){
          this.form.id = this.form.id == response.body.data.id ? this.form.id : response.body.data.id;
          this.showNotificationSuccess( response.body );
        }
        else {
          this.showNotificationError( response.body );
        }
  		},
  		responseError: function(data){
  			if( this.loading ){
  				this.stopLoader();
  			}
        this.showNotificationError( response.body );
  		},
      showNotificationSuccess: function( response ){
        var vm = this;
        vm.notification = {
          show : true,
          success: true,
          message : response.data.id == vm.form.id ? vm.builder.notifications.updated : vm.builder.notifications.created
        };
        setTimeout( function(){
          vm.notification.show = false;
        }, 5000 );
      },
      showNotificationError: function( response ){
        var vm = this;
        vm.notification = {
          show : true,
          success: false,
          message : this.builder.notifications.error
        };
        setTimeout( function(){
          vm.notification.show = false;
        }, 5000 );
      },
      filter_var: function( $value ){
  			return [ '1', 'true', 'on', 'yes', true, 1 ].indexOf( $value ) === -1 ? false : true;
  		},
  		updateModel: function( $value ){
  			//this.$emit( 'input', $value );
  		},
  		isChecked: function( test, val ){
  			return test === val ? true: false;
  		},
  		inList: function( test, val ){
  			return val.indexOf(test) >= 0 ? true : false;
  		},
  		isVisible: function( option ){

  			var vm = this;
  			var $visible = true;
  			if(typeof this.conditions[option] !== 'undefined' ){
  				var $conditions = [];
  				this.conditions[option].conditions.forEach(function(val, index){
  					switch (true) {
  						case val[1] === '==' : $conditions.push( vm.form[val[0]] == val[2] ? true : false ); break;
  						case val[1] === '!=' : $conditions.push( vm.form[val[0]] != val[2] ? true : false ); break;
              case val[1] === 'not_in_array' : $conditions.push( val[2].indexOf(vm.form[val[0]]) < 0 ? true : false ); break;
              case val[1] === 'in_array' : $conditions.push( val[2].indexOf( vm.form[val[0]] ) >= 0 ? true : false ); break;
              case val[1] === 'is_true' : $conditions.push( vm.filter_var( vm.form[val[0]] ) ? true : false ); break;
  					}
  				});

  				if( this.conditions[option].relation === 'OR' ){
  					$visible = $conditions.indexOf(true) >= 0 ? true : false;
  				} else{
  					$visible = $conditions.indexOf(false) >= 0 ? false : true;
  				}
  			}
  			return $visible;
  		},
  		isFieldVisible: function( field ){
  			var $visible = true;
  			return $visible;
  		},
  		isSectionVisible: function( field ){
  			var vm = this;
  			var $visible = true;

  			return $visible;
  		},
  		changeView: function( $val, event ){
        var vm = this;
        vm.tabs['design'] = vm.tabs['design'] === $val.slug ? vm.tabs['design'] : 'general';
        //vm.tabs['style'] = $val.value;
        //this.$emit('input', $val.value);
  		}
  	}
  });
}
