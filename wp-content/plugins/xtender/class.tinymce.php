<?php

class XtenderTinyMCE {

	function __construct() {

		/** MCE Buttons */
		add_filter( 'tiny_mce_before_init', array( $this, 'mce_before_init' ) );

		/** MCE Buttons Order */
		add_filter( 'mce_buttons_2', array( $this, 'mce_editor_buttons' ) );

	}





	function mce_before_init( $settings ) {

		if( defined( 'XTENDER_THEMPREFIX' ) && XTENDER_THEMPREFIX === 'leisure' ){

			$style_formats = array(
	        array(
	            'title' => 'Button - Inline',
	            'selector' => 'a',
	            'classes' => 'btn btn-inline'
	            ),
	        array(
	            'title' => 'Button - Defaut',
	            'selector' => 'a',
	            'classes' => 'btn btn-default'
	            ),
	        array(
	            'title' => 'Button - Link',
	            'selector' => 'a',
	            'classes' => 'btn btn-link'
	            ),
	        array(
	            'title' => 'Paragraph - Lead',
	            'selector' => 'p',
	            'classes' => 'lead',
	        )
	    );

	    $settings['style_formats'] = json_encode( $style_formats );

		} else {

			$style_formats = array(
	        array(
		        'title' => __( 'Buttons', 'xtender' ),
		        'items' => array(

							/** Primary */
							array(
				        'title' => __( 'Primary', 'xtender' ),
				        'items' => array(
					        array(
					            'title' => 'Small',
					            'selector' => 'a',
					            'classes' => 'btn btn-sm btn-primary'
					        ),
					        array(
					            'title' => 'Normal',
					            'selector' => 'a',
					            'classes' => 'btn btn-primary'
									),
					        array(
					            'title' => 'Large',
					            'selector' => 'a',
					            'classes' => 'btn btn-lg btn-primary'
					        )
				        )
			        ),

							/** Secondary */
							array(
				        'title' => __( 'Secondary', 'xtender' ),
				        'items' => array(
					        array(
					            'title' => 'Small',
					            'selector' => 'a',
					            'classes' => 'btn btn-sm btn-secondary'
					        ),
					        array(
					            'title' => 'Normal',
					            'selector' => 'a',
					            'classes' => 'btn btn-secondary'
									),
					        array(
					            'title' => 'Large',
					            'selector' => 'a',
					            'classes' => 'btn btn-lg btn-secondary'
					        )
				        )
			        ),


							/** Link */
							array(
				        'title' => __( 'Link', 'xtender' ),
				        'items' => array(
					        array(
					            'title' => 'Small',
					            'selector' => 'a',
					            'classes' => 'btn btn-sm btn-link'
					        ),
					        array(
					            'title' => 'Normal',
					            'selector' => 'a',
					            'classes' => 'btn btn-link'
									),
					        array(
					            'title' => 'Large',
					            'selector' => 'a',
					            'classes' => 'btn btn-lg btn-link'
					        )
				        )
			        ),


							/** Primary Outline */
							array(
				        'title' => __( 'Primary Outline', 'xtender' ),
				        'items' => array(
					        array(
					            'title' => 'Small',
					            'selector' => 'a',
					            'classes' => 'btn btn-sm btn-outline-primary'
					        ),
					        array(
					            'title' => 'Normal',
					            'selector' => 'a',
					            'classes' => 'btn btn-outline-primary'
									),
					        array(
					            'title' => 'Large',
					            'selector' => 'a',
					            'classes' => 'btn btn-lg btn-outline-primary'
					        )
				        )
			        ),


							/** Secondary Outline */
							array(
				        'title' => __( 'Secondary Outline', 'xtender' ),
				        'items' => array(
					        array(
					            'title' => 'Small',
					            'selector' => 'a',
					            'classes' => 'btn btn-sm btn-outline-secondary'
					        ),
					        array(
					            'title' => 'Normal',
					            'selector' => 'a',
					            'classes' => 'btn btn-outline-secondary'
									),
					        array(
					            'title' => 'Large',
					            'selector' => 'a',
					            'classes' => 'btn btn-lg btn-outline-secondary'
					        )
				        )
			        ),


		        )
	        ),
					array(
	            'title' => 'Colors',
	            'items' => array(
								array(
										'title' => 'Primary Color',
										'inline' => 'span',
										'classes' => 'color-primary'
								),
								array(
										'title' => 'Secondary Color',
										'inline' => 'span',
										'classes' => 'color-secondary'
								)
							)
	        ),
					array(
	            'title' => 'Special Title',
	            'selector' => 'h1,h2,h3,h4,h5,h6',
	            'classes' => 'special-title'
	        ),
					array(
	            'title' => 'Small Text',
							'inline'	=> 'small'
	        ),
					array(
						'title'	=> __( 'Text Format', 'xtender' ),
						'items' => array(
							array(
			            'title' => 'Lead',
			            'selector' => 'p',
			            'classes' => 'lead'
			        ),
							array(
			            'title' => 'Large Lead',
			            'selector' => 'p',
			            'classes' => 'lead lead-lg'
			        ),
							array(
			            'title' => 'Small Text',
			            'selector' => 'p',
			            'classes' => 'text-small'
			        ),
							array(
			            'title' => 'Display 1',
			            'selector' => 'p,h1,h2,h3,h4,h5',
			            'classes' => 'display-1'
			        ),
							array(
			            'title' => 'Display 2',
			            'selector' => 'p,h1,h2,h3,h4,h5',
			            'classes' => 'display-2'
			        ),
							array(
			            'title' => 'Display 3',
			            'selector' => 'p,h1,h2,h3,h4,h5',
			            'classes' => 'display-3'
			        ),
							array(
			            'title' => 'Display 4',
			            'selector' => 'p,h1,h2,h3,h4,h5',
			            'classes' => 'display-4'
			        )
						)
					)
	    );

	    $settings['style_formats_merge'] = true;
	    $settings['style_formats'] = json_encode( $style_formats );

		}

	  return $settings;

	}

	function mce_editor_buttons( $buttons ){

	    array_unshift( $buttons, 'styleselect' );
	    return $buttons;
	}


}

new XtenderTinyMCE();
?>
