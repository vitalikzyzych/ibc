<div class="item <?php echo is_array( $filters ) ? implode(' ', $filters ) : '' ?>">
  <?php if( ! empty( $image ) ) : ?><a href="<?php echo $link ?>" class="link-image" target="<?php echo $target ?>"><?php echo $image ?></a><?php endif; ?>
  <?php if( $title || $description ) : ?>
  <div class="item-content">
    <?php if( ! empty( $title ) ) : ?><h4><a href="<?php echo $link ?>" target="<?php echo $target ?>"><?php echo $title ?></a></h4><?php endif; ?>
    <?php echo $description ?>
  </div>
  <?php endif; ?>
</div>
