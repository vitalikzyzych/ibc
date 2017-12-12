<?php

/** Template: Display -> Countdown */

?>
<div class="wcs-timetable wcs-timetable--cover wcs-class">
	<div v-if="single.thumbnail" class="wcs-class__image" :style="single.thumbnail | bgImage"></div>
	<div class="wcs-class__content">
		<p v-if="filter_var(options.show_title)" class="wcs-title">{{options.title}}</p>
		<h2 class="wcs-class__title wcs-modal-call" v-on:click="openModal( single, options, $event )">{{single.title}}</h2>
		<div class="wcs-class__time-location">
			<span class="wcs-class__time">{{single.start | moment( options.label_dateformat ? options.label_dateformat : 'dddd, MMMM D' ) }}</span>
			<span v-if="options.show_ending" v-html="starting_ending(single)" class='wcs-addons--pipe'></span>
			<span v-if="filter_var(options.show_duration)" class='wcs-class__duration wcs-addons--pipe'>{{single.duration}}</span>
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
		<p v-if="filter_var(options.show_excerpt)" class="wcs-class__excerpt">{{single.excerpt}}</p>
		<div class="wcs-class__action">
			<a v-if="hasModal(single) && options.label_info" href="#" class="wcs-btn wcs-btn--lg wcs-btn--action wcs-modal-call" v-on:click="openModal( single, options, $event )">{{options.label_info}}</a>
		</div>
	</div>
</div>
