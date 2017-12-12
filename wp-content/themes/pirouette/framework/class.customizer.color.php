<?php
if ( ! class_exists( 'WP_Customize_Control' ) )
    return NULL;

/**
 * Class to create a custom date picker
 */
class Pirouette_Color_Control extends WP_Customize_Control {

  public $type = 'coloor';

    /**
    * Enqueue the styles and scripts
    */
    public function enqueue() {

      wp_enqueue_style('wp-color-picker');

  		wp_enqueue_script(
      	'pirouette-color-picker',
      	get_template_directory_uri() . '/assets/admin/js/wp-color-picker-alpha.js' ,
      	array( 'wp-color-picker' ),
      	rand(),
      	true
      );

    }

    /**
    * Render the content on the theme customizer page
    */
    public function render_content() {
      ?>
        <span class="customize-control-title">
          <?php echo esc_html( $this->label ); ?>
        </span>
        <span class="description customize-control-description"><?php echo wp_kses_post( $this->description ); ?></span>
        <input type="text" <?php $this->link(); ?> data-alpha="true" data-alpha-reset="true" class="color-picker" data-default-color="<?php echo isset( $this->setting->default ) ? $this->setting->default : '' ?>" value="<?php echo esc_attr( $this->value() ); ?>" />
      <?php
    }
}
?>
