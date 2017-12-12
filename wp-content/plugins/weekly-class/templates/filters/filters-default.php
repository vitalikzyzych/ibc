<div v-if="hasToggler()" v-on:click="filters.visible = ! filters.visible" class='wcs-filter-toggler-container'>
	<span class='wcs-filter-toggler'>{{ filters.options.label_toggle }} <em class='icon' :class="filters.visible ? 'ti-minus' : 'ti-plus'"></em></span>
</div>
<div v-if="hasFilters()" v-show="filters.visible" class='wcs-filters__container'>
	<form class='wcs-filters' :class="filters_classes">
		<div v-for="filter in filters.taxonomies" v-if="filter.terms.length > 0" class='wcs-filters__filter-column' :class="'wcs-filters--' + filter.name">
			<template v-if="getFiltersType() === 'checkbox'">
				<span v-if="filter.title.length > 0" class='wcs-filters__title'>{{filter.title}}</span>
				<template v-for="term in filter.terms">
					<filter-checkbox :name="filter.name" :title="term.name" :slug="term.slug" :unique_id="options.el_id" v-bind:value="filters_active[filter.name]" v-on:input="updateFilterModel( filter.name, arguments )"></filter-checkbox>
					<filter-checkbox v-for="child_term in term.children" :name="filter.name" :key="child_term.slug" :level="1" :title="child_term.name" :slug="child_term.slug" :unique_id="options.el_id" v-bind:value="filters_active[filter.name]" v-on:input="updateFilterModel( filter.name, arguments )"></filter-checkbox>
				</template>
			</template>
			<template v-else-if="getFiltersType() === 'switch'">
				<span v-if="filter.title.length > 0" class='wcs-filters__title'>{{filter.title}}</span>
				<template v-for="term in filter.terms">
					<filter-switch :name="filter.name" :title="term.name" :slug="term.slug" :unique_id="options.el_id" v-bind:value="filters_active[filter.name]" v-on:input="updateFilterModel( filter.name, arguments )"></filter-switch>
					<filter-switch v-for="child_term in term.children" :name="filter.name" :key="child_term.slug" :level="1" :title="child_term.name" :slug="child_term.slug" :unique_id="options.el_id" v-bind:value="filters_active[filter.name]" v-on:input="updateFilterModel( filter.name, arguments )"></filter-switch>
				</template>
			</template>
			<template v-else-if="getFiltersType() === 'select2'">
				<filter-select2 :options="getSelect2Options(filter.terms)" :multiple="filter_var(options.filters_select2_multiple)" :placeholder="filter.title" v-on:input="updateFilterModelSelect2( filter.name, arguments )">
		      <option value="-1" v-if="!filter_var(options.filters_select2_multiple)">{{filter.title}}</option>
		    </filter-select2>
			</template>
		</div>
	</form>
</div>
