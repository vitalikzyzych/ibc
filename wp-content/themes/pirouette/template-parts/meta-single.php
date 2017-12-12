<div class="ct-single__entry-meta">
	<span><i class="ti-calendar color-primary"></i> <em><?php the_date(); ?></em></span>
	<span><i class="ti-layers color-primary"></i> <em><?php echo get_the_category_list( ', ' ); ?></em></span>
	<?php echo get_the_tag_list( '<span><i class="ti-tag color-primary"></i> <em>', ', ', '</em></span>'); ?>
</div>
