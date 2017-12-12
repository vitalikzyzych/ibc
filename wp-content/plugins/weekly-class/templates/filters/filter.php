<script type="text/x-template" id="wcs_templates_filter--checkbox">
  <label class='wcs-filters__filter-wrapper' :class="level == 1 ? 'wcs-filters__filter-wrapper--padded' : ''">
    <input v-bind:value="value" v-on:change="updateModelValue" :id='unique_id + "-filter-" + slug' type='checkbox' class='wcs-filter' :name='name' :value='slug'> {{title}}
  </label>
</script>
