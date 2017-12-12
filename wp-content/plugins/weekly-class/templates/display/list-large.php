<?php

/** Template: Display -> Large List */

?>
<div class="wcs-timetable wcs-timetable--large">
	<h2 v-if="filter_var(options.show_title)">{{options.title}}</h2>
	<div class="wcs-timetable__large wcs-table">
		<template v-if="Object.keys(events_by_day).length > 0">
			<template v-for="day in events_by_day" v-if="day.events">
				<div class="wcs-timetable__heading wcs-table__tr">
					<div class="wcs-class__time wcs-table__td">{{ day.date | moment( options.label_dateformat ? options.label_dateformat : 'dddd, MMMM D' ) }}</div>
					<div v-if="filter_var(options.show_duration)" class="wcs-table__td wcs-class__duration"></div>
					<div class="wcs-class__title wcs-table__td">{{ options.label_wcs_type }}</div>
					<div v-if="filter_var(options.show_wcs_room)" class="wcs-class__locations wcs-table__td">{{ options.label_wcs_room }}</div>
					<div v-if="filter_var(options.show_wcs_instructor)" class="wcs-class__instructors wcs-table__td">{{ options.label_wcs_instructor }}</div>
				</div>
				<div v-for="event in day.events" class="wcs-class wcs-table__tr" :class="event | eventCSS">
					<div class="wcs-class__time wcs-table__td" v-html="starting_ending(event)"></div>
					<div v-if="filter_var(options.show_duration)" class='wcs-class__duration wcs-table__td'>{{event.duration}}</div>
					<div class="wcs-class__title wcs-modal-call wcs-table__td" v-on:click="openModal( event, options, $event )">
						<div v-html="event.title"></div>
						<div v-if="filter_var(options.show_excerpt)" class="wcs-class__excerpt" v-html="event.excerpt"></div>
					</div>
					<div v-if="filter_var(options.show_wcs_room)" class='wcs-class__locations wcs-table__td' :data-wcs-location='options.label_wcs_room'>
						<taxonomy-list :options="options" :tax="'wcs_room'" :event="event" v-on:open-modal="openTaxModal"></taxonomy-list>
					</div>
					<div v-if="filter_var(options.show_wcs_instructor)" class='wcs-class__instructors wcs-table__td' :data-wcs-instructor='options.label_wcs_instructor'>
						<taxonomy-list :options="options" :tax="'wcs_instructor'" :event="event" v-on:open-modal="openTaxModal"></taxonomy-list>
					</div>
				</div>
			</template>
		</template>
		<template v-else>
			<div class="wcs-timetable__zero-data-container">
				<div><div class="wcs-timetable__zero-data"><p v-html="options.zero"></p></div></div>
			</div>
		</template>
	</div>
	<button-more v-if="hasMoreButton()" v-on:add-events="addEvents" :more="options.label_more" :color="options.color_special_contrast"></button-more>
</div>
