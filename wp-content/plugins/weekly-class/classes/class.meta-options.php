<?php

class WCSMetaOption {

	private $_id;
	private $_label;
	private $_default;
	private $_description;
	private $_array;
	private $_class;


	public function __construct( $id = null, $label = null, $default = null, $description = null, $array = null, $class = null ) {

		$this->_id = $id;
		$this->_label = $label;
		$this->_default = $default;
		$this->_description = $description;
		$this->_array = $array;
		$this->_class = $class;

	}

	public function checkbox( $html = null ) {
		$html .= '<div class="wcs-form-control checkbox '.$this->_class.'" id="wrapper_'.$this->_id.'">';
			$html .= '<label for="'.$this->_id.'" class="name">'.$this->_label.'</label>';
			$html .= '<div>';
				$html .= '<label class="description"><input type="checkbox" name="'.$this->_id.'" id="'.$this->_id.'" value="true" '.checked( $this->_default, 'true' , false).' />';
				$html .= ( $this->_description ) ? $this->_description : null;
				$html .= '</label>';
			$html .= '</div>';
		$html .= '</div>';

		echo $html;
	}

	public function select( $html = null ) {
		$html .= '<div class="wcs-form-control select '.$this->_class.'" id="wrapper_'.$this->_id.'">';
			$html .= '<label for="'.$this->_id.'" class="name">'.$this->_label.'</label>';
			$html .= '<div>';
				$html .= '<select class="select" name="'.$this->_id.'" id="'.$this->_id.'">';

					foreach ($this->_array as $value) {
						$html .= '<option value="'.$value['value'].'" '.selected( $this->_default, $value['value'], false ).'>';
						$html .= $value['name'];
						$html .= '</option>';
					}

				$html .= '</select>';
				$html .= ( $this->_description ) ? '<span class="description">'.$this->_description.'</span>' : null;
			$html .= '</div>';
		$html .= '</div>';

		echo $html;
	}

	public function color( $html = null ) {
		$html .= '<div class="wcs-form-control color '.$this->_class.'" id="wrapper_'.$this->_id.'">';
			$html .= '<label for="'.$this->_id.'" class="name">'.$this->_label.'</label>';
			$html .= '<div>';
				$html .= '<input class="color-picker" type="text" name="'.$this->_id.'" id="'.$this->_id.'" value="'.$this->_default.'" />';
				$html .= ( $this->_description ) ? '<span class="description">'.$this->_description.'</span>' : null;
			$html .= '</div>';
		$html .= '</div>';

		echo $html;
	}

	public function image( $html = null ) {

		$clear_style = ( $this->_default ) ? 'style="display:inline-block"' : 'style="display:none"';

		$html .= '<div class="wcs-form-control image-field '.$this->_class.'" id="wrapper_'.$this->_id.'">';
			$html .= '<label for="'.$this->_id.'" class="name">'.$this->_label.'</label>';
			$html .= '<div>';
				$html .= '<input type="hidden" name="'.$this->_id.'_id" id="'.$this->_id.'_id" value="'.$this->_default[0].'">';
				$html .= '<input class="text-field" type="text" name="'.$this->_id.'" id="'.$this->_id.'" value="'.$this->_default[1].'" />';
				$html .= '<a href="#" class="image-upload-button button button-primary button-large" data-upload-title="'.$this->_array['upload_title'].'" data-upload-button="'.$this->_array['upload_button'].'">'.$this->_array['upload_link'].'</a>';
				$html .= '<a href="#" class="image-clear-button button button-large" '.$clear_style.'>'.$this->_array['clear_link'].'</a>';
				$html .= ( isset( $this->_default[1] ) ) ? '<img src="'.$this->_default[1].'" class="image-preview" />' : null;
				$html .= ( $this->_description ) ? '<span class="description">'.$this->_description.'</span>' : null;
			$html .= '</div>';
		$html .= '</div>';

		echo $html;
	}

	public function video( $html = null ) {

		$clear_style = ( $this->_default ) ? 'style="display:inline-block"' : 'style="display:none"';

		$html .= '<div class="wcs-form-control image-field '.$this->_class.'" id="wrapper_'.$this->_id.'">';
			$html .= '<label for="'.$this->_id.'" class="name">'.$this->_label.'</label>';
			$html .= '<div>';
				$html .= '<input class="text-field" type="text" name="'.$this->_id.'" id="'.$this->_id.'" value="'.$this->_default.'" />';
				$html .= '<a href="#" class="video-upload-button button button-primary button-large" data-upload-title="'.$this->_array['upload_title'].'" data-upload-button="'.$this->_array['upload_button'].'">'.$this->_array['upload_link'].'</a>';
				$html .= '<a href="#" class="video-clear-button button button-large" '.$clear_style.'>'.$this->_array['clear_link'].'</a>';
				$html .= ( $this->_description ) ? '<span class="description">'.$this->_description.'</span>' : null;
			$html .= '</div>';
		$html .= '</div>';

		echo $html;
	}

	public function slider( $html = null ) {
		$html .= '<div class="wcs-form-control" id="wrapper_'.$this->_id.'">';
			$html .= '<label for="'.$this->_id.'" class="name">'.$this->_label.'</label>';
			$html .= '<div style="position: relative;">';
				$html .= '<input type="hidden" name="'.$this->_id.'" id="'.$this->_id.'" value="'.$this->_default.'" />';
				$html .= '<div class="slider" id="'.$this->_id.'_slider"></div>';
				$html .= '<div class="slider_value">'.(( $this->_default ) ? $this->_default.$this->_array['suf'] :  null ).'</div>';
				$html .= ( $this->_description ) ? '<span class="description">'.$this->_description.'</span>' : null;
				$html .= '<script type="text/javascript">';
					$html .= 'jQuery(function() {
									jQuery( "#'.$this->_id.'_slider" ).slider({
										value: '.$this->_default.' ,
										step: '.$this->_array['step'].' ,
										min: '.$this->_array['min'].' ,
										max: '.$this->_array['max'].' ,
										slide: function( event, ui ) {
											jQuery(this).siblings(".slider_value").text( ui.value + "'.$this->_array['suf'].'" );
											jQuery(this).siblings("input[type=hidden]").val(ui.value);
										}
									});
								});';
				$html .= '</script>';
			$html .= '</div>';
		$html .= '</div>';

		echo $html;
	}

	public function editor( $html = null ) {

		ob_start(); wp_editor( $this->_default , $this->_id, array('textarea_rows' => 10 , 'teeny' => true) );

		$html .= '<div class="wcs-form-control" id="wrapper_'.$this->_id.'">';
			$html .= '<label for="'.$this->_id.'" class="name">'.$this->_label.'</label>';
			$html .= '<div>';
				$html .= ob_get_clean();
			$html .= '</div>';
		$html .= '</div>';

		echo $html;
	}

	public function input( $html = null ) {
		$html .= '<div class="wcs-form-control" id="wrapper_'.$this->_id.'">';
			$html .= '<label for="'.$this->_id.'" class="name">'.$this->_label.'</label>';
			$html .= '<div>';
				$html .= '<input class="text-field" type="text" name="'.$this->_id.'" id="'.$this->_id.'" value="'.$this->_default.'" />';
				$html .= ( $this->_description ) ? '<span class="description">'.$this->_description.'</span>' : null;
			$html .= '</div>';
		$html .= '</div>';

		echo $html;
	}

	public function radio( $html = null ) {
		$selected = ( $this->_default ) ? $this->_default : key($this->_array);
		$html .= '<div class="wcs-form-control radio" id="wrapper_'.$this->_id.'">';
			$html .= '<label for="'.$this->_id.'" class="name">'.$this->_label.'</label>';
			$html .= '<div>';

				foreach ( $this->_array as $key => $value ) {
					$html .= '<label class="'.$this->_id.'_'.$value.'">';
						$html .= '<input type="radio" name="'.$this->_id.'" id="'.$key.'" value="'.$key.'" '.checked( $selected, $key, false ).' />';
						$html .= $value;
					$html .= '</label>';
				}

				$html .= ( $this->_description ) ? '<span class="description">'.$this->_description.'</span>' : null;
			$html .= '</div>';
		$html .= '</div>';

		echo $html;
	}

}
?>
