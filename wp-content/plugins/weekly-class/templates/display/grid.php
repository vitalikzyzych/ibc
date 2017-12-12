<?php

/** Template: Display -> Masonry Grid */

?>

<div class="wcs-timetable wcs-timetable--grid">
	<h2 v-if="filter_var(options.show_title)">{{options.title}}</h2>
	<isotope ref="cpt" :options='getIsotopeOptions()' :list="events" v-images-loaded:on.progress="layout" @filter="isFilteredVal()" class="wcs-timetable__grid wcs-timetable__parent">
	  <div v-for="event in events" :key="event.id" class="wcs-class wcs-iso-item" :class="event | eventCSS | eventCSSIsotope(event)">
	    <div class="wcs-class__inner" :key="event.id">
				<div v-if="event.thumbnail" class="wcs-class__image"><img v-on:click="openModal( event, options, $event )" :src="event.thumbnail" :title="event.title"></div>
        <div class="wcs-class__title" :title="event.title" v-on:click="openModal( event, options, $event )" v-html="event.title"></div>
			  <div v-if="filter_var(options.show_excerpt)" class="wcs-class__excerpt" v-html="event.excerpt"></div>
				<div v-if=" ( filter_var( options.show_wcs_room ) && event.terms.wcs_room ) || ( filter_var( options.show_wcs_instructor ) && event.terms.wcs_instructor )" class="wcs-class__meta">
          <template v-if="filter_var( options.show_wcs_room ) && event.terms.wcs_room">
            <span class='wcs-class__meta-label'>{{options.label_wcs_room}}</span>
            <taxonomy-list :options="options" :tax="'wcs_room'" :event="event" v-on:open-modal="openTaxModal"></taxonomy-list>
          </template>
          <template v-if="filter_var( options.show_wcs_instructor ) && event.terms.wcs_instructor">
            <span class='wcs-class__meta-label'>{{options.label_wcs_instructor}}</span>
            <taxonomy-list :options="options" :tax="'wcs_instructor'" :event="event" v-on:open-modal="openTaxModal"></taxonomy-list>
          </template>
        </div>
        <div class="wcs-class__date-time">
          <div class="wcs-class__time"><span class="ti-time"></span><span v-html="starting_ending(event)"></span></div>
          <div class="wcs-class__date"><span class="ti-calendar"></span><span>{{event.start | moment( options.label_dateformat ? options.label_dateformat : 'MM/DD' ) }}  <template v-if="isMultiDay(event)">
						- {{ event.end | moment( options.label_dateformat ? options.label_dateformat : 'MM/DD' ) }}</template></span></div>
        </div>
				<div class="wcs-class__click-area" v-on:click="expandIsotopeItem(event.hash, $event)" v-show="!isIsotopeExpanded(event.hash)"></div>
        <div class="wcs-class__minimize ti-fullscreen" v-show="isIsotopeExpanded(event.hash)" v-on:click="minimizeIsotopeItem(event.hash, $event)"></div>
			</div>
	  </div>
		<div class="wcs-isotope-item" :key="'test-1'"></div>
		<div class="wcs-isotope-gutter" :key="'test-2'"></div>
	</isotope>
	<div v-show="isZero() && events.length === 0" class="wcs-timetable__zero-data wcs-timetable__zero-data-container">
		<h3>{{options.zero}}</h3>
	</div>
	<button-more v-if="hasMoreButton()" v-on:add-events="addEvents" :more="options.label_more" :color="options.color_special_contrast"></button-more>
</div><!-- .wcs-timetable -->
