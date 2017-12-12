<div id='single-wcs-event' class='single-wcs-event--<?php echo $settings['wcs_single_box'] ?>'>
  <div class='wcs-single-left'>
    <?php echo "%%page_content%%" . do_shortcode( '[wcs_event_map]' ) ?>
  </div>
  <div class='wcs-single-right'>
    <?php echo do_shortcode( '[wcs_event_image]' ) ?>
    <div class='wcs-single-right__content'>
      <?php echo do_shortcode( '[wcs_event_date]' ); ?>
      <?php if( $settings['wcs_single_duration'] ) : ?><span class='wcs-single__duration'>(<?php echo do_shortcode( '[wcs_event_duration]' ) ?>)</span><?php endif; ?></div>
      <?php if( $settings['wcs_single_location'] ) : ?><p class='wcs-single__location'><?php echo do_shortcode( '[wcs_event_terms term="wcs-room"]' ) ?></p><?php endif; ?>
      <?php if( $settings['wcs_single_instructor'] ) : ?><p class='wcs-single__instructor'><?php echo do_shortcode( '[wcs_event_terms term="wcs-instructor"]' ) ?></p><?php endif; ?>
      <?php echo do_shortcode( '<p class="wcs-single__action">[wcs_event_button btn="action"][wcs_event_button btn="woo"]</p>' ); ?>
    </div>
  </div>
</div>
