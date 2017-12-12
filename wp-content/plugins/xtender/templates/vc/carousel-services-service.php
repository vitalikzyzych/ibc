<div class="item">
  <?php if( ! empty( $image ) ) : ?><a href="<?php echo $link ?>" target="<?php echo $target ?>" class="link-image" title="<?php echo $title ?>"><?php echo $image; ?></a><?php endif ?>
  <?php if ( ! empty( $title ) || ! empty( $description ) ) : ?>
    <div class="item-content">
      <?php if( ! empty( $title ) ) : ?><h4><a href="<?php echo $link ?>" target="<?php echo $target ?>" ><?php echo $title ?></a></h4><?php endif ?>
      <?php if( ! empty( $description ) ) : ?><p><?php echo $description ?></p><?php endif ?>
    </div>
  <?php endif; ?>
</div>
