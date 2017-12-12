<?php if( ! empty( $title ) ) : ?>
<li>
  <h3>
    <a href="<?php echo $link ?>">
      <?php echo $title ?>
      <?php if( ! empty( $description ) ) : ?><small><?php echo $description ?></small><?php endif; ?>
    </a>
  </h3>
</li>
<?php endif; ?>
