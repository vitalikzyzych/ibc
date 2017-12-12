<?php

/** Template: Display -> Countdown */

?>
<div class="wcs-timetable wcs-timetable--countdown wcs-class">
	<div class="wcs-class__titles">
		<h2 v-if="filter_var(options.show_title)" class="wcs-class__title">{{options.title}}</h2>
		<h3 class="wcs-class__title">{{single.title}}</h3>
		<div class="wcs-class__time-location">
			<span v-if="filter_var(options.countdown_starting)" class="wcs_class__time">{{single.start | moment( options.label_dateformat ? options.label_dateformat : 'dddd, MMMM D' ) }}</span>
			<template v-if="hasTax('wcs_type', single)"><span class='wcs-addons--pipe'>{{options.label_wcs_type}}</span>
				<taxonomy-list :options="options" :tax="'wcs_type'" :event="single" v-on:open-modal="openTaxModal"></taxonomy-list>
			</template>
			<template v-if="hasTax('wcs_room', single)"><span class='wcs-addons--pipe'>{{options.label_wcs_room}}</span>
				<taxonomy-list :options="options" :tax="'wcs_room'" :event="single" v-on:open-modal="openTaxModal"></taxonomy-list>
			</template>
			<template v-if="hasTax('wcs_instructor', single)"><span class='wcs-addons--pipe'>{{options.label_wcs_instructor}}</span>
				<taxonomy-list :options="options" :tax="'wcs_instructor'" :event="single" v-on:open-modal="openTaxModal"></taxonomy-list>
			</template>
		</div>
		<p v-if="filter_var(options.show_excerpt)" class="wcs-class__excerpt" v-html="single.excerpt"></p>
	</div>
	<div class="wcs-class__countdown">
		<div v-if="options.label_countdown_years" class="wcs-class__countdown-time wcs-class__countdown-years">
			<span>{{remaining_years | leadingZero}}</span>
			<span class="wcs-class__countdown-label" v-text="timeLabel('years')"></span>
		</div>
		<div v-if="options.label_countdown_months" class="wcs-class__countdown-time wcs-class__countdown-months">
			<span>{{remaining_months | leadingZero}}</span>
			<span class="wcs-class__countdown-label" v-text="timeLabel('months')"></span>
		</div>
		<div v-if="options.label_countdown_days" class="wcs-class__countdown-time wcs-class__countdown-days">
			<span>{{remaining_days | leadingZero}}</span>
			<span class="wcs-class__countdown-label" v-text="timeLabel('days')"></span>
		</div>
		<div v-if="options.label_countdown_hours" class="wcs-class__countdown-time wcs-class__countdown-hours">
			<span>{{remaining_hours | leadingZero}}</span>
			<span class="wcs-class__countdown-label" v-text="timeLabel('hours')"></span>
		</div>
		<div v-if="options.label_countdown_minutes" class="wcs-class__countdown-time wcs-class__countdown-minutes">
			<span>{{remaining_minutes | leadingZero}}</span>
			<span class="wcs-class__countdown-label" v-text="timeLabel('minutes')"></span>
		</div>
		<div v-if="options.label_countdown_seconds" class="wcs-class__countdown-time wcs-class__countdown-seconds">
			<span>{{remaining_seconds | leadingZero}}</span>
			<span class="wcs-class__countdown-label" v-text="timeLabel('seconds')"></span>
		</div>
	</div>
	<div v-if="hasModal(single)" class="wcs-class__action">
		<a v-if="hasModal(single)" href="#" class="wcs-btn wcs-btn--lg wcs-btn--action wcs-modal-call" v-on:click="openModal( single, options, $event )">{{options.label_info}}</a>
	</div>
	<div v-if="hasCountdownImage(single)" class="wcs-class__image" :style="single.thumbnail | bgImage"></div>
</div>
