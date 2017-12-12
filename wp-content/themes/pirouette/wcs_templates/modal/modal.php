<script type="text/x-template" id="wcs_templates_modal--normal">
	<div class="wcs-modal" :class="modal_classes" v-on:click="closeModal" :data-wcs-modal-id="options.el_id">
		<div class="wcs-modal__box wcs-modal--pirouette">
			<a href="#" class="wcs-modal__close ti-close" v-on:click="closeModal"></a>
			<h2 class="wcs-modal__title">{{data.title}}
				<small v-if="data.terms.wcs_type">
					<template v-for="(type, index) in data.terms.wcs_type">
						{{type.name}}<template v-if="index !== (data.terms.wcs_type.length - 1)">, </template>
					</template>
				</small>
			</h2>
			<img v-if="data.image" :src="data.image" class='wcs-image'>
			<div class="wcs-modal__inner">
				<ul class="wcs-modal__meta">
					<li>{{ data.start | moment( options.label_modal_dateformat ) }}<br>
						<template v-if="filter_var(options.show_modal_ending)">
							{{ data.start | moment( options.show_time_format ? 'h' : 'HH' ) }}<sup>{{ data.start | moment('mm') }}
							{{ data.start | moment( options.show_time_format ? 'a' : ' ' ) }}</sup>
							-
							{{ data.end | moment( options.show_time_format ? 'h' : 'HH' ) }}<sup>{{ data.end | moment('mm') }}
							{{ data.end | moment( options.show_time_format ? 'a' : ' ' ) }}</sup>
							<span v-if="filter_var(options.show_modal_duration)" class="wcs-modal--muted wcs-addons--pipe">{{data.duration}}</span>
						</template>
					</li>
					<li>
						<template v-if="filter_var(options.modal_wcs_room) && data.terms.wcs_room" v-for="(room, index) in data.terms.wcs_room">
								{{room.name}}<template v-if="index !== (data.terms.wcs_room.length - 1)">, </template>
						</template>
						<template v-if="filter_var(options.modal_wcs_instructor) && data.terms.wcs_instructor" v-for="(instructor, index) in data.terms.wcs_instructor">
								<template v-if="index === 0"><br></template>{{instructor.name}}<template v-if="index !== (data.terms.wcs_instructor.length - 1)">, </template>
						</template>
					</li>
					<li class="wcs-modal__action">
						<template v-for="(button, button_type) in data.buttons">
							<template v-if="button_type == 'main' && button.label.length > 0">
								<a class="wcs-btn wcs-btn--action" v-if="button.method == 0" :href="button.permalink" :target="button.target ? '_blank' : '_self'">{{button.label}}</a>
								<a class="wcs-btn wcs-btn--action" v-else-if="button.method == 1" :href="button.custom_url" :target="button.target ? '_blank' : '_self'">{{button.label}}</a>
								<a class="wcs-btn wcs-btn--action" v-else-if="button.method == 2" :href="button.email" :target="button.target ? '_blank' : '_self'">{{button.label}}</a>
								<a class="wcs-btn wcs-btn--action" v-else-if="button.method == 3" :href="button.ical">{{button.label}}</a>
							</template>
							<template v-else-if="button_type == 'woo'">
								<a :class="button.classes" v-if="button.status" :href="button.href">{{button.label}}</a>
							</template>
						</template>
					</li>
				</ul>
				<div class="wcs-modal__content" v-html="data.content"></div>
				<div v-if="data.map" class="wcs-map"></div>
			</div>
		</div>
	</div>
</script>
