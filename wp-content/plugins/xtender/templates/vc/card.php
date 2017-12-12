<div class='xtd-card <?php echo $el_css ?>'>
  <?php echo isset( $link['url'] ) ? "<a href='{$link['url']}'>$img</a>" : $img ?>
  <div class='xtd-card__info'>
    <?php if( ! is_null( $title ) && ! empty( $title  ) ) : ?>
    <div class='xtd-card__title h4'>
      <div>
        <?php echo $title ?>
        <?php if( ! is_null( $subtitle ) && ! empty( $subtitle  ) ) : ?>
          <small><?php $subtitle ?></small>
          <?php endif; ?>
      </div>
    </div>
  <?php endif; ?>
    <div class='xtd-card__content'>
      <?php echo do_shortcode( $content ) ?>
    </div>
    <?php if( ! is_null( $more ) && ! empty( $more  ) && isset( $link['url'] ) ) : ?>
      <a href="<?php echo $link['url'] ?>" class="btn btn-link btn-sm"><?php echo $more ?></a>
    <?php endif; ?>
  </div>
</div>
