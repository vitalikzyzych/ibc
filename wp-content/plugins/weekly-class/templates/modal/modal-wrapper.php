<script type="text/x-template" id="wcs_templates_modal">
  <div v-if="visible" class="wcs-vue-modal">
    <template v-if="!loading">
      <modal-normal v-if="type === 'normal'" :data="data" :options="options" :classes="css_classes"></modal-normal>
      <modal-large v-if="type === 'large'" :data="data" :options="options" :classes="css_classes"></modal-large>
      <modal-taxonomy v-else-if="type === 'taxonomy'" :data="data" :options="options" :classes="css_classes"></modal-taxonomy>
    </template>
    <div v-if="loading" class="wcs-modal wcs-modal__loader" :class="css_classes" v-on:click="closeModal">
      <div class="wcs-modal__box"><wcs-loader></wcs-loader></div>
    </div>
  </div>
</script>
