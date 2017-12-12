<?php

/** Template: Display -> Weekly Schedule */

?>
<div class="wcs-timetable wcs-timetable--week">
	<h2 v-if="filter_var(options.show_title)">{{options.title}}</h2>
	<div v-if="options.show_navigation && options.label_weekly_schedule_prev && options.label_weekly_schedule_next" class="wcs-navigation">
		<button class="wcs-btn wcs-btn--prev wcs-btn--action" v-on:click="navigationGoPrev" :disabled="loading_process">{{options.label_weekly_schedule_prev}}</button>
		<div class="wcs-navigation__title">{{dateRangeTitle}}</div>
		<button class="wcs-btn wcs-btn--next wcs-btn--action" v-on:click="navigationGoNext" :disabled="loading_process">{{options.label_weekly_schedule_next}}</button>
	</div>
	<div class="wcs-timetable__week wcs-timetable__parent">
		<div class="wcs-calendar-loading" v-if="loading_process"><div class="wcs-spinner"><div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect5"></div></div></div>

		<template v-if="filter_var( options.show_starting_hours )">
			<div class="wcs-row">
				<div class="wcs-day wcs-day__time"></div>
				<div v-for="day in week" v-if="day.events.length > 0" class="wcs-day" :class="'wcs-day--' + day.day_num"><h3 class="wcs-day__title">{{day_name(day.day_num)}}</h3></div>
			</div>
			<div v-for="(starting, key_starting) in starting_times" class="wcs-row" v-if="hasHourlyEvents(starting)">
				<div class="wcs-day wcs-day__time"><span>{{starting | check12format( options.show_time_format ) }}</span></div>
				<div v-for="day in week" v-if="day.events.length > 0" class="wcs-day" :class="'wcs-day--' + day.day_num">
					<div class="wcs-timetable__classes">
						<div v-for="event in getHourlyEvents(starting, day.events)" class="wcs-class wcs-class--filterable" :class="event | eventCSS | eventSlotCSS(event)">
							<small class="wcs-class__title wcs-modal-call" v-on:click="openModal( event, options, $event )" :title="event.title" v-html="event.title"></small>
							<time class="wcs-class__time" :datetime="event.start" v-html="starting_ending(event)"></time>
							<template v-if="filter_var(options.show_duration)"><br><span class='wcs-class__duration'>{{event.duration}}</span></template>
							<div v-if="filter_var(options.show_wcs_room)" class="wcs-class__location">{{options.label_wcs_room}}
								<taxonomy-list :options="options" :tax="'wcs_room'" :event="event" v-on:open-modal="openTaxModal"></taxonomy-list>
							</div>
							<div v-if="filter_var(options.show_wcs_instructor)" class="wcs-class__instructor">{{options.label_wcs_instructor}}
								<taxonomy-list :options="options" :tax="'wcs_instructor'" :event="event" v-on:open-modal="openTaxModal"></taxonomy-list>
							</div>
						</div>
						<div class="wcs-empty-time"></div>
					</div>
				</div>
			</div>
		</template>

		<div v-for="day in week" v-if="day.events.length > 0" class="wcs-day" :class="'wcs-day--' + day.day_num">
			<h3 class="wcs-day__title">{{day_name(day.day_num)}}</h3>
			<div class="wcs-timetable__classes">
				<div v-for="event in day.events" class="wcs-class wcs-class--filterable" :class="event | eventCSS | eventSlotCSS(event)">
					<small class="wcs-class__title wcs-modal-call" v-on:click="openModal( event, options, $event )" :title="event.title" v-html="event.title"></small>
					<time class="wcs-class__time" :datetime="event.start" v-html="starting_ending(event)"></time>
					<template v-if="filter_var(options.show_duration)"><br><span class='wcs-class__duration'>{{event.duration}}</span></template>
					<div v-if="filter_var(options.show_wcs_room)" class="wcs-class__location">{{options.label_wcs_room}}
						<taxonomy-list :options="options" :tax="'wcs_room'" :event="event" v-on:open-modal="openTaxModal"></taxonomy-list>
					</div>
					<div v-if="filter_var(options.show_wcs_instructor)" class="wcs-class__instructor">{{options.label_wcs_instructor}}
						<taxonomy-list :options="options" :tax="'wcs_instructor'" :event="event" v-on:open-modal="openTaxModal"></taxonomy-list>
					</div>
				</div>
			</div><!-- .wcs-timetable__classes -->
			<div class="wcs-timetable__spacer"></div>
		</div><!-- .wcs-day -->

		<div v-show="countWeekEvents(week) == 0" class="wcs-timetable__zero-data wcs-timetable__zero-data-container">
			<h3>{{options.zero}}</h3>
		</div>
	</div><!-- .wcs-timetable__parent -->
</div><!-- .wcs-timetable -->
