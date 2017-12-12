<?php
if ( ! class_exists( 'WP_Customize_Control' ) )
    return NULL;

/**
 * Class to create a custom date picker
 */
class Pirouette_Slider_Control extends WP_Customize_Control
{
    /**
    * Enqueue the styles and scripts
    */
    public function enqueue()
    {
        wp_enqueue_script( 'jquery' );
        wp_enqueue_script( 'jquery-ui-core' );
        wp_enqueue_script( 'jquery-ui-widget' );
        wp_enqueue_script( 'jquery-ui-slider' );
        wp_enqueue_script( 'jquery-ui-mouse' );
    }

    /**
    * Render the content on the theme customizer page
    */
    public function render_content()
    {

        ?>
            <label>
              <span class="customize-control-title">
              	<?php echo esc_html( $this->label ); ?>
              	<span class="range-value"><?php echo esc_attr( $this->value() ); echo esc_attr( $this->input_attrs['suffix'] ); ?></span>
              </span>
              <input type="hidden" id="<?php echo esc_attr( $this->id ); ?>" name="<?php echo esc_attr( $this->id ); ?>" value="<?php echo esc_attr( $this->value() ); ?>" data-customize-setting-link="<?php echo esc_attr( $this->id ); ?>" />
              <div class="curly-slider" id="slider-<?php echo esc_attr( $this->id ); ?>"></div>
              <span class="description customize-control-description"><?php echo esc_attr( $this->description ); ?></span>
              <script type="text/javascript">
	              (function($) {
				  "use strict";

				  $(function() {
				    $( "#slider-<?php echo esc_attr( $this->id ); ?>" ).slider({
					    value: <?php echo esc_attr( $this->value() ); ?>,
						step: <?php echo esc_attr( $this->input_attrs['step'] ); ?>,
						min: <?php echo esc_attr( $this->input_attrs['min'] ); ?>,
						max: <?php echo esc_attr( $this->input_attrs['max'] ); ?>,
						slide: function( event, ui ) {
							jQuery(this).siblings( '.customize-control-title' ).children('.range-value').text( ui.value + "<?php echo esc_attr( $this->input_attrs['suffix'] ); ?>" );
							jQuery(this).siblings("input[type=hidden]").val(ui.value).trigger('change');
						}
				    });
				  });

				})(jQuery);
              </script>
            </label>
        <?php
    }
}
?>
