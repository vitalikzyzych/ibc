/** Modal App */
var wcs_vue_modal = new Vue({
	el: '#wcs-vue-modal',
	template: '#wcs_templates_modal',
	data: function(){
		return {
			visible: false,
			loading: true,
			data: {},
			options: {},
			events: {},
			taxonomies: {}
		}
	},
	watch: {
		visible: function( newVal ){
			if( newVal ){
				document.body.className += ' ' + 'wcs_modal--opened';
			} else {
				document.body.className = document.body.className.replace( 'wcs_modal--opened', '' );
			}
		},
	},
	updated: function(){
		var vm = this;
		if( ! vm.loading ){
			if( typeof vm.data.start !== 'undefined' ){
				if(typeof vm.data.map !== 'undefined'){
					var $theme = [];
					if( vm.data.map.theme === 'light' ){
						$theme = [{"featureType":"administrative","elementType":"all","stylers":[{"visibility":"on"},{"saturation":-100},{"lightness":20}]},{"featureType":"road","elementType":"all","stylers":[{"visibility":"on"},{"saturation":-100},{"lightness":40}]},{"featureType":"water","elementType":"all","stylers":[{"visibility":"on"},{"saturation":-10},{"lightness":30}]},{"featureType":"landscape.man_made","elementType":"all","stylers":[{"visibility":"simplified"},{"saturation":-60},{"lightness":10}]},{"featureType":"landscape.natural","elementType":"all","stylers":[{"visibility":"simplified"},{"saturation":-60},{"lightness":60}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"off"},{"saturation":-100},{"lightness":60}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"off"},{"saturation":-100},{"lightness":60}]}];
					}

					if( vm.data.map.theme === 'dark' ){
						$theme = [{"featureType":"all","elementType":"labels.text.fill","stylers":[{"saturation":36},{"color":"#000000"},{"lightness":40}]},{"featureType":"all","elementType":"labels.text.stroke","stylers":[{"visibility":"on"},{"color":"#000000"},{"lightness":16}]},{"featureType":"all","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#000000"},{"lightness":20}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#000000"},{"lightness":17},{"weight":1.2}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":20}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":21}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#000000"},{"lightness":17}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#000000"},{"lightness":29},{"weight":0.2}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":18}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":16}]},{"featureType":"transit","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":19}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":17}]}];
					}
					jQuery(".wcs-map", vm.$el ).width("100%").height("100%").gmap3({
						center: [vm.data.map.latitude, vm.data.map.longitude],
						zoom: parseInt( vm.data.map.zoom ),
						disableDefaultUI: true,
						draggable: true,
						mapTypeId: google.maps.MapTypeId[vm.data.map.type.toUpperCase()],
						mapTypeControl: false,
						mapTypeControlOptions: {},
						navigationControl: true,
						scrollwheel: false,
						streetViewControl: true,
						styles: $theme
					}).marker([
	          { position: [vm.data.map.latitude, vm.data.map.longitude] }
	        ]);
				}
			}
		}

	},
	methods: {
		openModal: function( data, options ){
			var $self = this;
			this.data = data;
			this.options = options;
			if( ! this.visible ){
				this.visible = true;
			}
			this.loading = true;

			if( typeof data.start !== 'undefined' ){
				if( typeof this.events[data.id] === 'undefined' ){
					this.getClass( data.id );
				} else {
					this.data.content = this.events[data.id].content;
					this.data.image 	= this.events[data.id].image;
					this.loading = ! this.loading;
				}
			} else {
				if( typeof this.taxonomies[data.id] === 'undefined' ){
					this.getTaxonomy( data.id );
				} else {
					this.data.content = this.taxonomies[data.id];
					this.loading = ! this.loading;
				}
			}
		},
		closeModal: function(event){
			var classes = event.target.className.split(' ');
			if( classes.indexOf( 'wcs-modal' ) >= 0 || classes.indexOf( 'wcs-modal__close' ) >= 0 ){
				event.preventDefault();
				this.visible = ! this.visible;
				this.loading = true;
			}

		},
		getClass: function(val){
			this.$http.get( ajaxurl, { params: { action: 'wcs_get_class_json', id: this.data.id } } ).then( this.responseSuccessClass, this.responseError );
		},
		getTaxonomy: function(val){
			this.$http.get( ajaxurl, { params: { action: 'wcs_get_taxonomy_json', id: this.data.id } } ).then( this.responseSuccessTaxonomy, this.responseError );
		},
		responseSuccessClass: function(response){
			if( response.body !== 0 && typeof response.body.id !== 'undefined' ){
				this.events[response.body.id] = response.body;
				this.data.content = response.body.content;
				this.data.image 	= response.body.image;
				this.data.map 	= response.body.map;
				this.loading = ! this.loading;
			} else {
				this.visible = ! this.loading;
			}
		},
		responseSuccessTaxonomy: function(response){
			if( response.body !== 0 && typeof response.body.id !== 'undefined' ){
				this.taxonomies[response.body.id] = response.body.content;
				this.data.content = response.body.content;
				this.loading = ! this.loading;
			} else {
				this.visible = ! this.visible;
			}
		},
		responseError: function(data){
			this.visible = ! this.visible;
		},
		filter_var: function( $value ){
			return [ '1', 'true', 'on', 'yes', true, 1 ].indexOf( $value ) === -1 ? false : true;
		}
	},
	computed: {
		css_classes: function(){
			var classes = [ 'wcs-modal-container' ];
			if( this.filter_var( this.options.show_modal ) ){
				classes.push( 'wcs-modal--light' );
			}
			classes.push( 'wcs-modal--' + this.type );
			return classes.join( ' ' );

		},
		type: function(){
			var $out = 'normal';
			if(typeof this.data.start !== 'undefined' ){
				$out = parseInt( this.options.modal ) === 1 ? 'large' : $out;
			}
			else {
				$out = 'taxonomy';
			}
			return $out;
		}
	}
});
