<script type="text/x-template" id="wcs_templates_timetable--wcs-app-<?php echo $id ?>">
  <div class="wcs-timetable__container <?php echo "wcs-timetable--$id"; ?>" :class="app_classes" data-id="<?php echo $id ?>"  id="wcs-app-<?php echo $id ?>" v-cloak>
  <?php
    if( $prepend_filters ) { if( isset( $template_filters ) && ! empty( $template_filters ) && $template_filters !== false ) include( $template_filters ); }
    if( isset( $template ) && ! empty( $template ) && $template !== false ) include( $template );
  ?>
  </div>
</script>
