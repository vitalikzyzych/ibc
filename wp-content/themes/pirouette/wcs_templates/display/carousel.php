<?php

/** Template: Display -> Carousel */
if( ! wp_style_is( 'wcs-owl-theme' ) )
	wp_enqueue_style( 'wcs-owl-theme' );

CurlyWeeklyClassShortcodes::set_dependency('wcs-owl');

?>
<div class="wcs-timetable wcs-timetable--carousel">
	<h2 v-if="filter_var(options.show_title)">{{options.title}}</h2>
	<div class="wcs-timetable__carousel wcs-timetable__parent owl-carousel">
		<div v-for="event in events_list" class="wcs-class wcs-class--filterable"  :class="event | eventCSS">
			<div v-if="event.thumbnail" class="wcs-class__image">
        <div class='responsive-image filtered-image'>
          <img class='wcs-modal-call' :width="event.thumbnail_size ? event.thumbnail_size[0] : ''" :height="event.thumbnail_size ? event.thumbnail_size[1] : ''" :src="event.thumbnail" :title="event.title" v-on:click="openModal( event, options, $event )">
        </div>
      </div>
      <div class="wcs-class__timestamp color-primary">
        <span class="date-day">{{ event.start | moment( 'D' ) }}</span>
        <span class="date-short">{{ event.start | moment( 'MMMM / dddd YYYY' ) }}</span>
      </div>
			<div class="wcs-class__title wcs-modal-call" :title="event.title" v-on:click="openModal( event, options, $event )" v-html="event.title"></div>
			<div v-if="filter_var(options.show_excerpt)" class="wcs-class__excerpt" v-html="event.excerpt"></div>
      <div class="wcs-class__action">
        <template v-for="(button, button_type) in event.buttons">
					<template v-if="button_type == 'main' && button.label.length > 0">
						<a class="wcs-btn wcs-btn--action" v-if="button.method == 0" :href="button.permalink" :target="button.target ? '_blank' : '_self'">{{button.label}}</a>
						<a class="wcs-btn wcs-btn--action" v-else-if="button.method == 1" :href="button.custom_url" :target="button.target ? '_blank' : '_self'">{{button.label}}</a>
						<a class="wcs-btn wcs-btn--action" v-else-if="button.method == 2" :href="button.email" :target="button.target ? '_blank' : '_self'">{{button.label}}</a>
						<a class="wcs-btn wcs-btn--action" v-else-if="button.method == 3" :href="button.ical" target="_blank">{{button.label}}</a>
					</template>
					<template v-else-if="button_type == 'woo'">
						<a :class="button.classes" v-if="button.status" :href="button.href">{{button.label}}</a>
						<a :class="button.classes" v-else href="#">{{button.label}}</a>
					</template>
				</template>
      </div>
		</div><!-- .wcs-class -->
	</div><!-- .wcs-timetable__carousel -->
	<button-more v-if="hasMoreButton()" v-on:add-events="addEvents" :more="options.label_more" :color="options.color_special_contrast"></button-more>
</div><!-- .wcs-timetable -->
