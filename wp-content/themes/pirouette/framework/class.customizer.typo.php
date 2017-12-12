<?php
if ( ! class_exists( 'WP_Customize_Control' ) )
    return NULL;

/**
 * Class to create a custom date picker
 */
class Pirouette_Typo_Control extends WP_Customize_Control {


    /**
    * Enqueue the styles and scripts
    */
    public function enqueue() {

        wp_enqueue_script( 'jquery' );
        wp_enqueue_script( 'jquery-ui-core' );
        wp_enqueue_script( 'jquery-ui-widget' );
        wp_enqueue_script( 'jquery-ui-slider' );
        wp_enqueue_script( 'jquery-ui-mouse' );
        wp_enqueue_script(
        'pirouette-typo-control',
        get_template_directory_uri().'/assets/admin/js/min/customizer.control.typo-min.js',
			  array( 'jquery' ),
			  rand(),
			  true
        );
    }

    /**
    * Render the content on the theme customizer page
    */
    public function render_content() {

        $fonts = PirouetteFont::font_transient();

        $default = isset( $this->input_attrs['default'] ) ? $this->input_attrs['default'] : false;
        $default = ! $default && ! is_array( $default ) ? explode(',', $default) : $default;
        $value   = $this->value();
        $value   = ! is_array( $value ) ? explode( ',', $value ) : $value;

        $variants_array = array(
          '100'       => 'Thin',
          '100italic' => 'Thin Italic',
          '200'       => 'Extra Light',
          '200italic' => 'Extra Light Italic',
          '300'       => 'Light',
          '300italic' => 'Light Italic',
          'regular'   => 'Regular',
          'italic'    => 'Italic',
          '500'       => 'Medium',
          '500italic' => 'Medium Italic',
          '600'       => 'Semi Bold',
          '600italic' => 'Semi Bold Italic',
          '700'       => 'Bold',
          '700italic' => 'Bold Italic',
          '800'       => 'Extra Bold',
          '800italic' => 'Extra Bold Italic',
          '900'       => 'Black',
          '900italic' => 'Black Italic',
        );

        $fonts_array = array();
        $default_variants = apply_filters( 'pirouette_default_font_variants', array( 'regular', 'italic', '700' ) );

        ?>

          <span class="customize-control-title">
            <?php echo esc_html( $this->label ); ?>
          </span>
          <span class="description customize-control-description"><?php echo wp_kses_post( $this->description ); ?></span>

          <label class="label-title">
            <span><?php esc_html_e( 'Font Family', 'pirouette' ); ?></span>
            <select name="<?php echo esc_attr( $this->id ); ?>[family]" class="curly_font_family">

              <option <?php echo empty( $value[0] ) ? 'selected' : '' ?> value='' data-variants="<?php echo implode( ',', $default_variants ); ?>">
                <?php

                if( isset( $this->default_state['family'] ) ){
                  echo esc_attr( $this->default_state['family'] );
                } else {
                  esc_html_e( 'Default Font', 'pirouette' );
                }

                ?>
              </option>
              <?php foreach( $fonts['items'] as $key => $font ) : $fonts_array[ $font['family']  ] = $font['variants']; ?>
                <option value="<?php echo esc_attr( $font['family'] ) ?>" data-variants="<?php echo implode( ',', $font['variants'] ); ?>" <?php selected( $font['family'], $value[0] ) ?>><?php echo esc_attr( $font['family'] ) ?></option>
              <?php endforeach; ?>

            </select>
          </label>

        <?php if( isset( $default[1] ) ) : ?>

          <label class="label-title">
            <span><?php esc_html_e( 'Font Variant', 'pirouette' ); ?></span>
            <select name="<?php echo esc_attr( $this->id ); ?>[variant]" class="curly_font_variant">

              <?php foreach( $variants_array as $variant => $label ) : ?>
                <option value="<?php echo esc_attr( $variant ) ?>" <?php selected( $variant, $value[1] ); echo isset( $fonts_array[ $value[0] ] ) ? ! in_array( $variant, $fonts_array[ $value[0] ] ) ? 'disabled' : '' : ! in_array( $variant, $default_variants ) ? 'disabled' : ''; ?>><?php echo esc_attr( $label ) ?></option>
              <?php endforeach; ?>

            </select>
          </label>

          <?php endif; if( isset( $default[2] ) ) : ?>

          <label class="label-title">
            <span>
              <?php esc_html_e( 'Font Size', 'pirouette' ); ?>
              <span class="range-value"><?php echo esc_attr( $value[2] ); echo esc_attr( $this->input_attrs['suffix'] ); ?></span>
            </span>
            <input type="hidden" class="curly_font_size" name="<?php echo esc_attr( $this->id ); ?>[size]" value="<?php echo esc_attr( $value[2] ); ?>" />
            <div class="curly-slider typo-slider" data-min="<?php echo esc_attr( $this->input_attrs['min'] ); ?>" data-max="<?php echo esc_attr( $this->input_attrs['max'] ); ?>" data-suffix="<?php echo esc_attr( $this->input_attrs['suffix'] ); ?>" id="slider-size-<?php echo esc_attr( $this->id ); ?>"></div>
          </label>

          <?php endif; if( isset( $default[3] ) ) : ?>
          <label class="label-title">
            <span><?php esc_html_e( 'Text Transform', 'pirouette' ); ?></span>

            <select name="<?php echo esc_attr( $this->id ); ?>[transform]" class="curly_text_transform">
              <option value="normal" <?php selected( 'normal', $value[3] );  ?>><?php esc_html_e( 'Normal', 'pirouette' ); ?></option>
              <option value="capitalize" <?php selected( 'capitalize', $value[3] );  ?>><?php esc_html_e( 'Capitalize', 'pirouette' ); ?></option>
              <option value="uppercase" <?php selected( 'uppercase', $value[3] );  ?>><?php esc_html_e( 'Uppercase', 'pirouette' ); ?></option>
              <option value="smallcaps" <?php selected( 'smallcaps', $value[3] );  ?>><?php esc_html_e( 'Small Caps', 'pirouette' ); ?></option>
            </select>

          </label>

        <?php endif; if( isset( $default[4] ) ) : ?>

        <label class="label-title">
          <span>
            <?php esc_html_e( 'Letter Spacing', 'pirouette' ); ?>
            <span class="range-value"><?php echo esc_attr( $value[4] ); ?>%</span>
          </span>
          <input type="hidden" class="curly_letter_spacing" name="<?php echo esc_attr( $this->id ); ?>[spacing]" value="<?php echo esc_attr( $value[4] ); ?>" />
          <div class="curly-slider typo-slider" data-min="-15" data-max="40" data-suffix="%" id="slider-spacing-<?php echo esc_attr( $this->id ); ?>"></div>
        </label>

        <?php endif; ?>

          <input type="hidden" <?php $this->link(); ?> value="<?php echo implode( ',', $value ); ?>" class="curly_font">



        <?php
    }
}
?>
