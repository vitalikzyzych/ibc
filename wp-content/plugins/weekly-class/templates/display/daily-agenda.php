<?php

/** Template: Display -> Agenda */

?>
<div class="wcs-timetable wcs-timetable--agenda">
	<h2 v-if="filter_var(options.show_title)">{{options.title}}</h2>
	<template v-if="Object.keys(events_by_day).length > 0">
		<div class="wcs-timetable__agenda">
			<ol class="wcs-timetable__agenda-nav wcs-timetable__parent-nav">
				<li v-for="(day, day_name) in all_days" class="wcs-timetable__agenda-nav-item" :class=" active_day === day_name ? 'active' : '' " v-on:click="selected_day = day_name"><a>{{ day.date | moment('ddd') }}<span>{{ day.date | moment('D') }}</span></a></li>
			</ol>
			<ol class="wcs-timetable__agenda-data wcs-timetable__parent-data">
				<li v-if="events_by_day[active_day]" class="wcs-day wcs-timetable__parent active" :class="'wcs-day--' + events_by_day[active_day].date | moment('D') ">
					<div v-for="event in events_by_day[active_day].events" class="wcs-class wcs-class--filterable"  :class="event | eventCSS | eventCSSIsotope(event)">
						<div v-if="filter_var(options.show_duration)" class="wcs-class__duration"><span>{{event.duration}}</span></div>
						<div class="wcs-class__title-meta">
							<div class="wcs-class__title wcs-modal-call" :title="event.title" v-on:click="openModal( event, options, $event )">
								<span v-html="event.title"></span>
								<template v-if="filter_var(options.show_wcs_room)">
									<template v-for="(room, index) in event.terms.wcs_room">
										<template v-if="index === (event.terms.wcs_room.length - event.terms.wcs_room.length)">- </template>
											{{room.name}}
										<template v-if="index !== (event.terms.wcs_room.length - 1)">, </template>
									</template>
								</template>
							</div><!-- .wcs-class__title -->
							<div class="wcs-meta">
								<time class="wcs-class__time" :datetime="event.start"><span class="ti-time"></span><span v-html="starting_ending(event)"></span></time>
								<template v-if="filter_var(options.show_wcs_instructor) && event.terms.wcs_instructor"><span class='wcs-addons--pipe'>{{options.label_wcs_instructor}}</span>
									<taxonomy-list :options="options" :tax="'wcs_instructor'" :event="event" v-on:open-modal="openTaxModal"></taxonomy-list>
								</template>
							</div>
						</div><!-- .wcs-class__title-meta -->
					</div><!-- .wcs-class -->
				</li><!-- .wcs-day -->
			</ol>
		</div><!-- .wcs-timetable__agenda -->
	</template>
	<template v-else>
		<div class="wcs-timetable__zero-data wcs-timetable__zero-data-container"><p>{{options.zero}}</p></div>
	</template>
</div><!-- .wcs-timetable -->
