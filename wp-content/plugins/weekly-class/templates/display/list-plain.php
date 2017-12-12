<?php

/** Template: Display -> Plain List */

?>
<div class="wcs-timetable wcs-timetable--list">
	<h2 v-if="filter_var(options.show_title)">{{options.title}}</h2>
	<ul class="wcs-timetable__list wcs-timetable__parent">
		<template v-if="events_list">
			<li v-for="event in events_list" class="wcs-class"  :class="event | eventCSS">
				<div v-if="event.thumbnail" class='wcs-class__image' :style='"background-image: url(" + event.thumbnail +")"'></div>
				<time :datetime="event.start" class="wcs-class__time">
					<div class="wcs-class__inner-flex">
						<span>{{event.start | moment( 'D' ) }}</span>
						<span>{{event.start | moment( 'MMMM' ) }}</span>
						<template v-if="isMultiDay(event)">
							- <span>{{ event.end | moment( 'D' ) }}</span>
								<span>{{ event.end | moment( 'MMMM' ) }}</span>
						</template>
					</div>
				</time>
				<div class="wcs-class__meta">
					<div class="wcs-class__inner-flex">
						<h3 class="wcs-class__title" :title="event.title" v-html="event.title"></h3>
						<div class="wcs-class__time-duration">
							<span v-html="starting_ending(event)"></span>
							<span v-if="filter_var(options.show_duration)" class='wcs-class__duration wcs-addons--pipe'>{{event.duration}}</span>
							<template v-if="filter_var(options.show_wcs_room)"><span class='wcs-addons--pipe'>{{options.label_wcs_room}}</span>
								<taxonomy-list :options="options" :tax="'wcs_room'" :event="event" v-on:open-modal="openTaxModal"></taxonomy-list>
							</template>
							<template v-if="filter_var(options.show_wcs_instructor)"><span class='wcs-addons--pipe'>{{options.label_wcs_instructor}}</span>
								<taxonomy-list :options="options" :tax="'wcs_instructor'" :event="event" v-on:open-modal="openTaxModal"></taxonomy-list>
							</template>
						</div>
						<div v-if="filter_var(options.show_excerpt)" class="wcs-class__excerpt" v-html="event.excerpt"></div>
					</div>
				</div>
				<div class="wcs-class__action">
					<div class="wcs-class__inner-flex">
						<a v-if="hasModal(event) && options.label_info.length > 0" href="#" class="wcs-btn wcs-modal-call" v-on:click="openModal( event, options, $event )">{{options.label_info}}</a>
						<a v-else-if="hasLink(event) && options.label_info.length > 0" :href="event.permalink" class="wcs-btn">{{options.label_info}}</a>
						<template v-for="(button, button_type) in event.buttons">
							<template v-if="button_type == 'main' && button.label.length > 0 ">
								<a class="wcs-btn wcs-btn--action" v-if="button.method == 0" :href="button.permalink" :target="button.target ? '_blank' : '_self'">{{button.label}}</a>
								<a class="wcs-btn wcs-btn--action" v-else-if="button.method == 1" :href="button.custom_url" :target="button.target ? '_blank' : '_self'">{{button.label}}</a>
								<a class="wcs-btn wcs-btn--action" v-else-if="button.method == 2" :href="button.email" :target="button.target ? '_blank' : '_self'">{{button.label}}</a>
								<a class="wcs-btn wcs-btn--action" v-else-if="button.method == 3" :href="button.ical" target="_blank">{{button.label}}</a>
							</template>
							<template v-else-if="button_type == 'woo'">
								<a :class="button.classes" v-if="button.status" :href="button.href">{{button.label}}</a>
								<a :class="button.classes" v-else-if="!button.status && button.href" :href="button.href">{{button.label}}</a>
								<a :class="button.classes" v-else-if="!button.status" href="#">{{button.label}}</a>
							</template>
						</template>
					</div>
				</div>
			</li>
		</template>
		<template v-else>
			<li class="wcs-class wcs-timetable__zero-data">
				<div class="wcs-class__meta">
					<div class="wcs-class__inner-flex">
						<h3>{{options.zero}}</h3>
					</div>
				</div>
			</li>
		</template>
	</ul>
	<button-more v-if="hasMoreButton()" v-on:add-events="addEvents" :more="options.label_more" :color="options.color_special_contrast"></button-more>
</div>
