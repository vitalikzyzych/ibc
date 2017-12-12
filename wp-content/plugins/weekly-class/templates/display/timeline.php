<?php
/** Template: Display -> Timeline */
?>
<div class="wcs-timetable wcs-timetable--timeline">
	<h2 v-if="filter_var(options.show_title)">{{options.title}}</h2>
	<isotope ref="timeline" :options='getIsotopeOptions()' :list="timeline_events" class="wcs-timetable__timeline wcs-timetable__parent">
		<div v-for="(day, key) in timeline_events" class="wcs-day wcs-iso-item" :key="'day-'+key">
			<h3 class="wcs-day__title">{{day.date | moment( options.label_dateformat ? options.label_dateformat : 'dddd, MMMM D' )}}</h3>
			<div class="wcs-timetable__classes">
				<div v-for="event in day.events" class="wcs-class">
					<span class="wcs-class__title wcs-modal-call" :title="event.title" v-on:click="openModal( event, options, $event )">
						<time class="wcs-class__time" :datetime="event.start" v-html="starting_ending(event)"></time> - <span v-html="event.title"></span>
						<div v-if="filter_var(options.show_excerpt)" class="wcs-class__excerpt" v-html="event.excerpt"></div>
					</span>
				</div>
			</div><!-- .wcs-timetable__classes -->
		</div><!-- .wcs-day -->
		<div class="wcs-isotope-item" :key="'test-1'"></div>
		<div class="wcs-isotope-gutter" :key="'test-2'"></div>
	</isotope>
	<div class="wcs-timetable__zero-data wcs-timetable__zero-data-container" v-show="Object.keys(events_by_day).length == 0">
		<h3>{{options.zero}}</h3>
	</div>
	<button-more v-if="hasMoreButton()" v-on:add-events="addEvents" :more="options.label_more" :color="options.color_special_contrast"></button-more>
</div><!-- .wcs-timetable -->
