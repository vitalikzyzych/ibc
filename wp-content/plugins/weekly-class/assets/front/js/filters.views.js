/** Vue Filters */
Vue.filter('moment', function (date, format, convert) {
	return convert !== false ? moment(date).utc().format(format) : moment(date).format(format);
});

Vue.filter( 'eventCSS', function(event){
	var $classes = [];
	$classes.push( event.future ? 'wcs-class--not-started' : 'wcs-class--started' );
	$classes.push( event.finished ? 'wcs-class--finished' : 'wcs-class--not-finished' );
	for(var term in event.terms ){
		for(var tax in event.terms[term] ){
			$classes.push( 'wcs-class--term-id-' + event.terms[term][tax].id );
			$classes.push( 'wcs-class--term-' + event.terms[term][tax].slug );
		}
	}
	$classes.push( 'wcs-class--day-' + moment( event.start ).utc().day() );
	switch ( true ) {
		case moment( event.start ).utc().hour() >= 0 && moment( event.start ).utc().hour() <= 11 :
			$classes.push( 'wcs-class--time-morning' );
			break;
		case moment( event.start ).utc().hour() > 11 && moment( event.start ).utc().hour() <= 16 :
			$classes.push( 'wcs-class--time-afternoon' );
			break;
		case moment( event.start ).utc().hour() > 16 && moment( event.start ).utc().hour() <= 23 :
			$classes.push( 'wcs-class--time-evening' );
			break;
	}
	if( ! event.visible ){
		$classes.push( 'wcs-class--canceled' );
	}
	return $classes.join(' ');
});
