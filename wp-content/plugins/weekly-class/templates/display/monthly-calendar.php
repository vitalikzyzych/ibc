<?php

/** Template: Display -> Monthly Calendar */

?>

<div class="wcs-timetable wcs-timetable--monthly-calendar" :class="calendarClasses">
	<div class="wcs-timetable__main-col">
		<div class="wcs-calendar__header">
			<div class="wcs-calendar-nav" :class="!loading_process ? 'wcs-modal-call' : ''">
				<span v-if="isNavVisible('prev')" class="wcs-calendar-nav-prev" v-on:click.prevent="subtractMonth()"><i class="ti-angle-left"></i> {{options.label_mth_prev}}</span>
			</div>
			<h3 v-text="getCurrentMonth"></h3>
			<div class="wcs-calendar-nav" :class="!loading_process ? 'wcs-modal-call' : ''">
				<span v-if="isNavVisible('next')" class="wcs-calendar-nav-next" v-on:click.prevent="addMonth()">{{options.label_mth_next}} <i class="ti-angle-right"></i> </span>
			</div>
		</div>
		<div class="wcs-timetable__monthly-calendar wcs-table">
			<div class="wcs-spinner"><div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect5"></div></div>
			<div class="wcs-table-tr wcs-table-thead">
				<div v-for="day in week" v-show="isWeekday(day.day_num, true)" class="wcs-day wcs-table-td" :class="'wcs-day--' + day.day_num">
					<h4 class="wcs-day__title">{{day_name(day.day_num)}}</h4>
				</div><!-- .wcs-day -->
			</div>
			<template v-for="n in 6">
				<div v-if="n * 7 <= days.length" class="wcs-table-tr wcs-week" :class="weekClasses(n)">
					<div v-for="(day, key) in days" v-if="key + 1 > (n - 1) * 7 && key + 1 <= n * 7" v-show="isWeekday(day)" class="wcs-table-td wcs-date" :class="dayClasses(day)" v-on:click="selectDay(day, $event)">
						<span>{{ day.date | moment('D', false) }}</span>
					</div>
				</div>
				<div v-if="isAgendaInside(n) && n * 7 <= days.length" class="wcs-table-tr--full">
					<div class="wcs-table-td--full">
							<div v-if="selectedDay" class="wcs-day-agenda">
								<div v-for="event in getFilteredCalendarEvents(selectedDay.events)" class="wcs-class" :class="event | eventCSS">
									<div v-if="event.thumbnail" class='wcs-class__image wcs-modal-call' :style='"background-image: url(" + event.thumbnail +")"' v-on:click="openModal( event, options, $event )"></div>
									<div class="wcs-class__inner">
										<div class="wcs-class__time-duration">
											<span v-html="starting_ending(event)"></span>
											<span v-if="filter_var(options.show_duration)" class='wcs-class__duration wcs-addons--pipe'>{{event.duration}}</span>
										</div>
										<h3 class="wcs-class__title wcs-modal-call" :title="event.title" v-html="event.title" v-on:click="openModal( event, options, $event )"></h3>
										<div class="wcs-class__meta">
											<template v-if="filter_var(options.show_wcs_room)">{{options.label_wcs_room}}</span>
												<taxonomy-list :options="options" :tax="'wcs_room'" :event="event" v-on:open-modal="openTaxModal"></taxonomy-list>
											</template>
											<template v-if="filter_var(options.show_wcs_instructor)"><span class='wcs-addons--pipe'>{{options.label_wcs_instructor}}</span>
												<taxonomy-list :options="options" :tax="'wcs_instructor'" :event="event" v-on:open-modal="openTaxModal"></taxonomy-list>
											</template>
										</div>
										<div v-if="filter_var(options.show_excerpt)" class="wcs-class__excerpt" v-html="event.excerpt"></div>
									</div>
								</div>
						</div>
					</div>
				</div>
			</template>
		</div><!-- .wcs-table -->
	</div>
	<div v-if="options.mth_cal_agenda_position != 3" class="wcs-timetable__side-col">
		<div v-if="selectedDay" class="wcs-day-agenda">
			<h4 class="wcs-day-agenda__title" v-if="getOption( 'mth_cal_date_format', 'MMMM DD' )">{{selectedDay.date | moment( getOption( 'mth_cal_date_format', 'MMMM DD' ), false) }}</h4>
			<template v-if="getFilteredCalendarEvents(selectedDay.events).length > 0">
			<div v-for="event in getFilteredCalendarEvents(selectedDay.events)" class="wcs-class" :class="event | eventCSS">
				<div v-if="event.thumbnail" class='wcs-class__image wcs-modal-call' :style='"background-image: url(" + event.thumbnail +")"' v-on:click="openModal( event, options, $event )"></div>
				<div class="wcs-class__inner">
					<div class="wcs-class__time-duration">
						<span v-html="starting_ending(event)"></span>
						<span v-if="filter_var(options.show_duration)" class='wcs-class__duration wcs-addons--pipe'>{{event.duration}}</span>
					</div>
					<h3 class="wcs-class__title wcs-modal-call" :title="event.title" v-html="event.title" v-on:click="openModal( event, options, $event )"></h3>
					<div class="wcs-class__meta">
						<template v-if="filter_var(options.show_wcs_room)">{{options.label_wcs_room}}</span>
							<taxonomy-list :options="options" :tax="'wcs_room'" :event="event" v-on:open-modal="openTaxModal"></taxonomy-list>
						</template>
						<template v-if="filter_var(options.show_wcs_instructor)"><span class='wcs-addons--pipe'>{{options.label_wcs_instructor}}</span>
							<taxonomy-list :options="options" :tax="'wcs_instructor'" :event="event" v-on:open-modal="openTaxModal"></taxonomy-list>
						</template>
					</div>
					<div v-if="filter_var(options.show_excerpt)" class="wcs-class__excerpt" v-html="event.excerpt"></div>
				</div>
			</div>
		</template>
		<template v-else>
			<p class="wcs-zero-data">{{options.zero}}</p>
		</template>
		</div>
	</div>
</div><!-- .wcs-timetable -->
