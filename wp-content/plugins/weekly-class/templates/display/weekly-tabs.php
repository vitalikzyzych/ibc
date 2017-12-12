<?php

/** Template: Display -> Weekly Tabs */

?>
<div class="wcs-timetable wcs-timetable--tabs">
	<h2 v-if="filter_var(options.show_title)">{{options.title}}</h2>
	<template v-if="Object.keys(events).length > 0">
		<div class="wcs-timetable__tabs">
			<ol class="wcs-timetable__tabs-nav">
				<li v-for="(day, day_name) in all_days" class="wcs-timetable__tabs-nav-item wcs-timetable__tabs-nav-item--" :class=" active_day === day_name ? 'active' : '' " v-on:click="selected_day = day_name"><a>{{ day.date | moment('dddd') }}</a></li>
			</ol>
			<ol class="wcs-timetable__tabs-data">
				<li v-if="events_by_day[active_day]" class="wcs-day wcs-timetable__parent active" :class="'wcs-day--' + events_by_day[active_day].date | moment('D') ">
						<table>
							<thead>
								<tr>
									<th v-if="filter_var(options.show_duration)" class="wcs-class__time" colspan="2">{{ events_by_day[active_day].date | moment( options.label_dateformat ? options.label_dateformat : 'dddd, MMMM D' ) }}</th>
									<th v-else class="wcs-class__time">{{ events_by_day[active_day].date | moment( options.label_dateformat ? options.label_dateformat : 'dddd, MMMM D' ) }}</th>
									<th class="wcs-class">{{options.label_wcs_type}}</th>
									<th v-if="filter_var( options.show_wcs_room )" class="wcs-class__locations">{{ options.label_wcs_room }}</th>
									<th v-if="filter_var( options.show_wcs_instructor )" class="wcs-class__instructors">{{ options.label_wcs_instructor }}</th>
									<th class="wcs-class__action"></th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="event in events_by_day[active_day].events" class="wcs-class wcs-class--filterable" :class="event | eventCSS">
									<td class="wcs-class__time wcs-modal-call" v-on:click="openModal( event, options, $event )">
										<time class="wcs-class__time" :datetime="event.start" v-html="starting_ending(event)"></time>
									</td>
									<td v-if="filter_var(options.show_duration)" class='wcs-class__duration' v-on:click="openModal( event, options, $event )">{{event.duration}}</td>
									<td class="wcs-class__title wcs-modal-call" v-on:click="openModal( event, options, $event )">
										<div v-html="event.title"></div>
									</td>
									<td v-if="filter_var( options.show_wcs_room )" class='wcs-class__locations' :data-wcs-location='options.label_wcs_room'>
										<taxonomy-list :options="options" :tax="'wcs_room'" :event="event" v-on:open-modal="openTaxModal"></taxonomy-list>
									</td>
									<td v-if="filter_var( options.show_wcs_instructor )" class='wcs-class__instructors' :data-wcs-instructor='options.label_wcs_instructor'>
										<taxonomy-list :options="options" :tax="'wcs_instructor'" :event="event" v-on:open-modal="openTaxModal"></taxonomy-list>
									</td>
									<td class="wcs-class__action">
										<template v-for="(button, button_type) in event.buttons">
											<template v-if="button_type == 'main' && button.label.length > 0">
												<a class="wcs-btn wcs-btn--action" v-if="button.method == 0" :href="button.permalink" :target="button.target ? '_blank' : '_self'">{{button.label}}</a>
												<a class="wcs-btn wcs-btn--action" v-else-if="button.method == 1" :href="button.custom_url" :target="button.target ? '_blank' : '_self'">{{button.label}}</a>
												<a class="wcs-btn wcs-btn--action" v-else-if="button.method == 2" :href="button.email" :target="button.target ? '_blank' : '_self'">{{button.label}}</a>
												<a class="wcs-btn wcs-btn--action" v-else-if="button.method == 3" :href="button.ical">{{button.label}}</a>
											</template>
											<template v-else-if="button_type == 'woo'">
												<a :class="button.classes" v-if="button.status" :href="button.href">{{button.label}}</a>
												<a :class="button.classes" v-else href="#">{{button.label}}</a>
											</template>
										</template>
									</td>
							</tr>
							</tbody>
						</table>
				</li>
				<li v-else-if="typeof events_by_day[active_day] === 'undefined'" class="wcs-day wcs-timetable__parent active">
					<div class="wcs-timetable__zero-data wcs-timetable__zero-data-container"><p>{{options.zero}}</p></div>
				</li>
			</ol>
		</div><!-- .wcs-timetable__tabs -->
	</template>
	<template v-else>
		<div class="wcs-timetable__zero-data wcs-timetable__zero-data-container"><p>{{options.zero}}</p></div>
	</template>
</div>
