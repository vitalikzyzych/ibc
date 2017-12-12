<?php

/** Template: Display -> Carousel */

if( ! wp_style_is( 'wcs-owl-theme' ) )
	wp_enqueue_style( 'wcs-owl-theme' );


?>
<div class="wcs-timetable wcs-timetable--carousel">
	<h2 v-if="filter_var(options.show_title)">{{options.title}}</h2>
	<div ref="carousel" class="wcs-timetable__carousel wcs-timetable__parent owl-carousel">
		<div v-for="event in events_list" class="wcs-class wcs-class--filterable" :class="event | eventCSS">
			<div v-if="event.thumbnail" class="wcs-class__image"><img class='wcs-modal-call' :src="event.thumbnail" :title="event.title" v-on:click="openModal( event, options, $event )"></div>
			<div class="wcs-class__title wcs-modal-call" :title="event.title" v-on:click="openModal( event, options, $event )" v-html="event.title"></div>
			<div v-if="filter_var(options.show_excerpt)" class="wcs-class__excerpt" v-html="event.excerpt"></div>
			<div class="wcs-spacer"></div>
			<div v-if=" ( filter_var( options.show_wcs_room ) && event.terms.wcs_room ) || ( filter_var( options.show_wcs_instructor ) && event.terms.wcs_instructor )" class="wcs-class__meta">
				<template v-if="filter_var(options.show_wcs_room) && event.terms.wcs_room">
					<span class='wcs-class__meta-label'>{{options.label_wcs_room}}</span>
					<taxonomy-list :options="options" :tax="'wcs_room'" :event="event" v-on:open-modal="openTaxModal"></taxonomy-list>
					</template>
				</template>
				<template v-if="filter_var( options.show_wcs_instructor ) && event.terms.wcs_instructor">
					<span class='wcs-class__meta-label'>{{options.label_wcs_instructor}}</span>
					<taxonomy-list :options="options" :tax="'wcs_instructor'" :event="event" v-on:open-modal="openTaxModal"></taxonomy-list>
				</template>
			</div><!-- .wcs-class__meta -->
			<div class="wcs-class__date-time">
				<div class="wcs-class__time"><span class="ti-time"></span> <span v-html="starting_ending(event)"></span></div>
				<div class="wcs-class__date"><span class="ti-calendar"></span><span>{{event.start | moment( options.label_dateformat ? options.label_dateformat : 'MM/DD' ) }} <template v-if="isMultiDay(event)">
					- {{ event.end | moment( options.label_dateformat ? options.label_dateformat : 'MM/DD' ) }}</template></span></div>
			</div><!-- .wcs-class__date-time -->
		</div><!-- .wcs-class -->
	</div><!-- .wcs-timetable__carousel -->
	<button-more v-if="hasMoreButton()" v-on:add-events="addEvents" :more="options.label_more" :color="options.color_special_contrast"></button-more>
</div><!-- .wcs-timetable -->
