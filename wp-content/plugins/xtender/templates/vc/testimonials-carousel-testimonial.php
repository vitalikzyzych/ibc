<?php if( ! empty( $name ) || ! empty( $date ) || ! empty( $testimonial ) ) : ?>
<div class='testimonial'>
  <?php if( ! empty( $name ) ) : ?>
    <h4 class='testimonial-title'>
      <?php echo $name ?>
      <?php if( ! empty( $date ) ) : ?>
        <small>
          <?php echo $date ?>
        </small>
      <?php endif; ?>
    </h4>
  <?php endif; ?>
  <?php if( ! empty( $testimonial ) ) : ?>
    <p><?php echo $testimonial ?></p>
  <?php endif; ?>
  <a href='<?php echo $link ?>' class='btn-inline btn' target="<?php echo $target ?>"><?php echo $text_link ?></a>
</div>
<?php endif ?>
