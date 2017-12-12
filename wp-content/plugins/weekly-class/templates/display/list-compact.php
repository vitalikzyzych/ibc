<?php

/** Template: Display -> Compact List */

?>
<div class="wcs-timetable wcs-timetable--compact">
	<h2 v-if="filter_var(options.show_title)">{{options.title}}</h2>
	<ul class="wcs-timetable__compact-list wcs-timetable__parent">
		<template v-if="Object.keys(events_by_day).length > 0">
			<template v-for="day in events_by_day">
				<li class="wcs-day wcs-day--visible">
					<div class="wcs-day__date">
						{{day.date | moment( 'dddd' ) }}
						<small>{{day.date | moment( options.label_dateformat ? options.label_dateformat : 'DD/MM' ) }}</small>
					</div>
					<ul class="wcs-timetable__classes">
						<li v-for="event in day.events" class="wcs-class wcs-class--visible" :class="event | eventCSS">
							<div class="wcs-class__time">
								<span v-html="starting_ending(event)"></span>
								<small v-if="filter_var(options.show_duration)" class='wcs-class__duration'>{{event.duration}}</small>
							</div><!-- .wcs-class__time -->
							<div class="wcs-class__content">
								<h3 class="wcs-class__title wcs-modal-call" v-on:click="openModal( event, options, $event )" :title="event.title" v-html="event.title"></h3>
								<small>
									<template v-if="filter_var(options.show_wcs_room)"><span>{{options.label_locations}}</span>
										<taxonomy-list :options="options" :tax="'wcs_room'" :event="event" v-on:open-modal="openTaxModal"></taxonomy-list>
									</template>
									<template v-if="filter_var(options.show_wcs_instructor)"><span class='wcs-addons--pipe'>{{options.label_instructors}}</span>
										<taxonomy-list :options="options" :tax="'wcs_instructor'" :event="event" v-on:open-modal="openTaxModal"></taxonomy-list>
									</template>
								</small>
								<div v-if="filter_var(options.show_excerpt)" class="wcs-class__excerpt" v-html="event.excerpt"></div>
							</div><!-- .wcs-class__content -->
						</li>
					</ul>
				</li>
			</template>
		</template>
		<template v-else>
			<li class="wcs-timetable__zero-data wcs-timetable__zero-data-container">
				<h3>{{options.zero}}</h3>
			</li>
		</template>
	</ul>
	<button-more v-if="hasMoreButton()" v-on:add-events="addEvents" :more="options.label_more" :color="options.color_special_contrast"></button-more>
</div>
