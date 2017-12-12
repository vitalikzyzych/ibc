<script type="text/x-template" id="wcs_templates_filter--radio">
  <label class='wcs-filters__filter-wrapper'>
    <input ref='input' v-bind:value="value" v-on:change="updateRadioModelValue" :id='unique_id + "-filter-" + slug' type='radio' class='wcs-filter' :name='name' :value='slug' :checked="isChecked(slug,value)"> <span>{{title}}</span>
  </label>
</script>
