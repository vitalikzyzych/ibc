<?php

/** Template: Display -> Compact List */

?>
<div class="wcs-timetable wcs-timetable--compact-list">
	<h2 v-if="filter_var(options.show_title)">{{options.title}}</h2>
	<ul class="wcs-timetable__compact-list wcs-timetable__parent">
		<template v-if="Object.keys(events_by_day).length > 0">
			<template v-for="day in events_by_day">
				<li class="wcs-day wcs-day--visible">
					<div class="wcs-day__date">
            <div class="wcs-day__wrapper">
  						{{day.date | moment( 'DD' ) }}
  						<small>{{day.date | moment( 'ddd' ) }}</small>
            </div>
					</div>
					<ul class="wcs-timetable__classes">
						<li v-for="event in day.events" class="wcs-class wcs-class--visible" :class="event | eventCSS">
              <div v-if="filter_var(options.show_duration)"class="wcs-class__duration color-primary"><span>{{event.duration}}</span></div>
              <div class="wcs-class__title-meta">
                <small class="wcs-class__date">{{ event.start | moment('dddd, MMM D, YYYY') }}</small>
                <div class="wcs-class__title wcs-modal-call" v-on:click="openModal( event, options, $event )" :title="event.title">
  								<span class="font-weight-bold">{{event.title}}</span>
                  <template v-if="filter_var(options.show_wcs_room)"> &mdash; <taxonomy-list :options="options" :tax="'wcs_room'" :event="event" v-on:open-modal="openModal"></taxonomy-list> </template>
  							</div>
                <div class="wcs-meta">
                  <time class="wcs-class__time" datetime="event.start">
                    <span class="ti-time color-primary"></span>
                    <span v-html="starting_ending(event)"></span>
                  </time>
                  <template v-if="filter_var(options.show_wcs_instructor)"><span class='wcs-addons--pipe'>{{options.label_instructors}}</span>
										<taxonomy-list :options="options" :tax="'wcs_instructor'" :event="event" v-on:open-modal="openModal"></taxonomy-list>
									</template>
                </div>
              </div>
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
