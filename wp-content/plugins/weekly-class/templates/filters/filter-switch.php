<script type="text/x-template" id="wcs_templates_filter--switch">
  <label class='wcs-filters__filter-wrapper' :class="level == 1 ? 'wcs-filters__filter-wrapper--padded' : ''">
    <input v-bind:value="value" v-on:change="updateModelValue" :id='unique_id + "-filter-" + slug' type='checkbox' class='wcs-filter' :name='name' :value='slug'> {{title}}
    <span class="wcs-switcher__switch"><span class="wcs-switcher__handler"></span></span>
  </label>
</script>
