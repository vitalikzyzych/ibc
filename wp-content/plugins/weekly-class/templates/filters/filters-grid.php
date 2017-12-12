<div v-if="hasToggler()" v-on:click="filters.visible = ! filters.visible" class='wcs-filter-toggler-container'>
	<span class='wcs-filter-toggler'>{{ filters.options.label_toggle }} <em class='icon' :class="filters.visible ? 'ti-minus' : 'ti-plus'"></em></span>
</div>
<div v-if="hasFilters()" v-show="filters.visible" class='wcs-filters__container'>
	<form class='wcs-filters' :class="filters_classes">
		<div v-for="filter in filters.taxonomies" v-if="filter.terms.length > 0" class='wcs-filters__filter-column' :class="'wcs-filters--' + filter.name">
			<span v-if="filter.title.length > 0" class='wcs-filters__title'>{{filter.title}}</span>
			<template v-for="(term, index) in filter.terms">
				<filter-radio v-if="index === 0" :name="filter.name" :title="getLabelAll(filter.name)" :unique_id="options.el_id" v-bind:value="filters_active[filter.name]" v-on:input="updateFilterModel( filter.name, arguments, true )"></filter-radio>
				<filter-radio :name="filter.name" :title="term.name" :slug="term.slug" :unique_id="options.el_id" v-bind:value="filters_active[filter.name]" v-on:input="updateFilterModel( filter.name, arguments, true )"></filter-radio>
				<filter-radio v-for="child_term in term.children" :name="filter.name" :title="child_term.title" :slug="child_term.slug" :unique_id="options.el_id" v-bind:value="filters_active[filter.name]" v-on:input="updateFilterModel( filter.name, arguments, true )"></filter-radio>
			</template>
		</div>
	</form>
</div>
