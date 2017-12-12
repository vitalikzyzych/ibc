
/** Retina Logo */
wp.customize( 'logo', function( value ) {
	value.bind( function( newval ) {

		if( newval.length > 0 ){

			wp.customize.control( 'logo_retina' ).activate();

		} else{

			wp.customize.control( 'logo_retina' ).deactivate();
		}

	});
});

/** Layout Mode */
wp.customize( 'layout', function( value ) {
	value.bind( function( newval ) {

		if( newval === true ){

			wp.customize.control( 'layout_box_width' ).activate();

		} else{

			wp.customize.control( 'layout_box_width' ).deactivate();
		}

	});
});

/** Grid Width */
wp.customize( 'layout_fixed', function( value ) {
	value.bind( function( newval ) {

		if( newval === true ){

			wp.customize.control( 'layout_grid_width' ).deactivate();

		} else{

			wp.customize.control( 'layout_grid_width' ).activate();
		}

	});
});

/** Custom Code */
wp.customize( 'code', function( value ) {
	value.bind( function( newval ) {

		if( newval === true ){

			wp.customize.control( 'code_head' ).activate();
			wp.customize.control( 'code_body' ).activate();

		} else{

			wp.customize.control( 'code_head' ).deactivate();
			wp.customize.control( 'code_body' ).deactivate();
		}

	});
});

/** Retina Logo */
wp.customize( 'footer_logo', function( value ) {
	value.bind( function( newval ) {

		if( newval.length > 0 ){

			wp.customize.control( 'footer_logo_retina' ).activate();

		} else{

			wp.customize.control( 'footer_logo_retina' ).deactivate();

		}

	});
});

/** Footer Menu */
wp.customize( 'nav_menu_locations[footer_navigation]', function( value ) {
	value.bind( function( newval ) {

		if( parseInt( newval ) > 0 ){

			wp.customize.control( 'footer_top' ).activate();

		} else{

			wp.customize.control( 'footer_top' ).deactivate();
		}

	});
});
