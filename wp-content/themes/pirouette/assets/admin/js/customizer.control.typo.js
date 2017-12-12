
(function ($) {
    "use strict";


    $(function() {

      $(".typo-slider").each(function(){

        var $typo_slider  = $(this);
        $typo_slider.slider({
          value: $typo_slider.siblings("input[name*=size]").val(),
          step: 1,
          min: parseInt( $typo_slider.data('min') ),
          max: parseInt( $typo_slider.data('max') ),
          slide: function( event, ui ) {
            $typo_slider.siblings( 'span' ).children('.range-value').text( ui.value + $typo_slider.data('suffix') );
            $typo_slider.siblings("input[type=hidden]").val(ui.value).trigger('change');
          }
        });

      });

    });

    $('body').on( 'update_typo', function( event, $element ){

      var $value = $($element).find('.curly_font_family').val();


      if( $($element).find('.curly_font_variant').length > 0 ){
        $value += "," + $($element).find('.curly_font_variant').val();
      }

      if( $($element).find('.curly_font_size').length > 0 ){
        $value += "," + $($element).find('.curly_font_size').val();
      }

      if( $($element).find('.curly_text_transform').length > 0 ){
        $value += "," + $($element).find('.curly_text_transform').val();
      }

      if( $($element).find('.curly_letter_spacing').length > 0 ){
        $value += "," + $($element).find('.curly_letter_spacing').val();
      }

     $($element).find('.curly_font').val($value).trigger('change');

    });


    $( document ).ready( function() {

      $( '.customize-control-typo .curly_font_family' ).on( 'change', function() {

        var $font_variants =  $( this ).find(':selected').data('variants').split(',');

        $(this).parents( '.customize-control' ).find( '.curly_font_variant' ).children().each(function(){

          var $val = $(this).val();

          if( $.inArray( $val, $font_variants ) === -1 ){
            $(this).attr('disabled','disabled');
          } else {
            $(this).removeAttr('disabled');
          }


        });

        if( $( this ).parents( '.customize-control' ).find( '.curly_font_variant :selected' ).is(':disabled') ){
          $( this ).parents( '.customize-control' ).find( '.curly_font_variant').val('regular').trigger( 'change' );
        }

        $('body').trigger( 'update_typo', [ $( this ).parents( '.customize-control' ) ] );

      });


      $( '.customize-control-typo .curly_font_variant' ).on( 'change', function() {

        $('body').trigger( 'update_typo', [ $( this ).parents( '.customize-control' ) ] );

      });

      $( '.customize-control-typo .curly_font_size' ).on( 'change', function() {

        $('body').trigger( 'update_typo', [ $( this ).parents( '.customize-control' ) ] );

      });

      $( '.customize-control-typo .curly_text_transform' ).on( 'change', function() {

        $('body').trigger( 'update_typo', [ $( this ).parents( '.customize-control' ) ] );

      });

      $( '.customize-control-typo .curly_letter_spacing' ).on( 'change', function() {

        $('body').trigger( 'update_typo', [ $( this ).parents( '.customize-control' ) ] );

      });


    });


      /*
      $( this ).parents( '.customize-control' ).find( 'select[name*=variant]' ).children();



            var checkbox_values = $( this ).parents( '.customize-control' ).find( 'option:selected' ).map(
                function() {
                    return this.value;
                }
            ).get().join( ',' );

            $( this ).parents( '.customize-control' ).find( 'input[type="hidden"]' ).val( checkbox_values ).trigger( 'change' );
        }

    );
    */

	})(jQuery);
