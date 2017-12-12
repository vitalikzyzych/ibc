<?php

class PirouetteColor {

	public $_color;
	public $_bk_color;
	public $_hex_color;
	public $_hsl;

	public function __construct( $color = null, $bk_color = null ) {
		$this->set_color( $color, $bk_color );
		$this->_hex_color = $this->rgb2hex( $this->_color );
		$this->_hsl = self::hexToHsl( substr( $this->_hex_color, 1 ) );
	}

	public function set_color( $color = null, $bk_color = null ){

		if( isset( $color ) && $color !== '' ){
			$this->_color =  trim( $color );
		}

		elseif( isset( $bk_color ) && $bk_color !== '' ){
			$this->_color =  trim( $bk_color );
		}

		else {
			$this->_color = null;
		}

	}

	public function set_bk( $bk_color = null ){
		$this->_bk_color = $bk_color;
	}

	public function life(){
		if( isset( $this->_color )  ) return true; else return;
	}

	public function __toString() {
		return $this->color();
	}

	public function rule( $tag ){

		$color = $this->color();

		if( empty( $color ) || is_null( $color ) )
			return;

		return sprintf( "$tag;", $color );

	}


	public static function hexToHsl( $color ){

        $R = hexdec($color[0].$color[1]);
        $G = hexdec($color[2].$color[3]);
        $B = hexdec($color[4].$color[5]);
        $HSL = array();
        $var_R = ($R / 255);
        $var_G = ($G / 255);
        $var_B = ($B / 255);
        $var_Min = min($var_R, $var_G, $var_B);
        $var_Max = max($var_R, $var_G, $var_B);
        $del_Max = $var_Max - $var_Min;
        $L = ($var_Max + $var_Min)/2;
        if ($del_Max == 0)
        {
            $H = 0;
            $S = 0;
        }
        else
        {
            if ( $L < 0.5 ) $S = $del_Max / ( $var_Max + $var_Min );
            else            $S = $del_Max / ( 2 - $var_Max - $var_Min );
            $del_R = ( ( ( $var_Max - $var_R ) / 6 ) + ( $del_Max / 2 ) ) / $del_Max;
            $del_G = ( ( ( $var_Max - $var_G ) / 6 ) + ( $del_Max / 2 ) ) / $del_Max;
            $del_B = ( ( ( $var_Max - $var_B ) / 6 ) + ( $del_Max / 2 ) ) / $del_Max;
            if      ($var_R == $var_Max) $H = $del_B - $del_G;
            else if ($var_G == $var_Max) $H = ( 1 / 3 ) + $del_R - $del_B;
            else if ($var_B == $var_Max) $H = ( 2 / 3 ) + $del_G - $del_R;
            if ($H<0) $H++;
            if ($H>1) $H--;
        }
        $HSL['H'] = ($H*360);
        $HSL['S'] = $S;
        $HSL['L'] = $L;
        return $HSL;
    }



    private static function _huetorgb( $v1,$v2,$vH ) {
        if( $vH < 0 ) {
            $vH += 1;
        }
        if( $vH > 1 ) {
            $vH -= 1;
        }
        if( (6*$vH) < 1 ) {
               return ($v1 + ($v2 - $v1) * 6 * $vH);
        }
        if( (2*$vH) < 1 ) {
            return $v2;
        }
        if( (3*$vH) < 2 ) {
            return ($v1 + ($v2-$v1) * ( (2/3)-$vH ) * 6);
        }
        return $v1;
    }




    public static function hslToHex( $hsl = array() ){
         // Make sure it's HSL
        if(empty($hsl) || !isset($hsl["H"]) || !isset($hsl["S"]) || !isset($hsl["L"]) ) {
            throw new Exception("Param was not an HSL array");
        }
        list($H,$S,$L) = array( $hsl['H']/360,$hsl['S'],$hsl['L'] );
        if( $S == 0 ) {
            $r = $L * 255;
            $g = $L * 255;
            $b = $L * 255;
        } else {
            if($L<0.5) {
                $var_2 = $L*(1+$S);
            } else {
                $var_2 = ($L+$S) - ($S*$L);
            }
            $var_1 = 2 * $L - $var_2;
            $r = round(255 * self::_huetorgb( $var_1, $var_2, $H + (1/3) ));
            $g = round(255 * self::_huetorgb( $var_1, $var_2, $H ));
            $b = round(255 * self::_huetorgb( $var_1, $var_2, $H - (1/3) ));
        }
        // Convert to hex
        $r = dechex($r);
        $g = dechex($g);
        $b = dechex($b);
        // Make sure we get 2 digits for decimals
        $r = (strlen("".$r)===1) ? "0".$r:$r;
        $g = (strlen("".$g)===1) ? "0".$g:$g;
        $b = (strlen("".$b)===1) ? "0".$b:$b;
        return $r.$g.$b;
    }




	public function color() {
		if( $this->_color === 'transparent' ){
			return $this->_color;
		}

		elseif( strlen( $this->_color ) === 6 && is_numeric( $this->_color ) ) {
			return 'rgba('.$this->hex2rgb( $this->_color ).', 1)';
		}

		elseif( substr( $this->_color, 0, 1 ) === '#' ){
			return 'rgba('.$this->hex2rgb( $this->_color ).', 1)';
		}

		elseif( substr( $this->_color, 0, 3 ) === 'rgb' ){
			return $this->_color;
		}

		elseif( $this->_bk_color === 'transparent' ) {
			return $this->_bk_color;
		}

		elseif( strlen( $this->_bk_color ) === 6 && is_numeric( $this->_bk_color ) ) {
			return 'rgba('.$this->hex2rgb( $this->_bk_color ).', 1)';
		}

		elseif( substr( $this->_bk_color, 0, 1 ) === '#' ) {
			return 'rgba('.$this->hex2rgb( $this->_bk_color ).', 1)';
		}

		elseif( substr( $this->_bk_color, 0, 3 ) === 'rgb' ){
			return $this->_bk_color;
		}

		else{
			return '';
		}
	}

	public function opacity( $opacity = 1 ) {
		if( $this->_color ){
			if( $this->_color === 'transparent' ){
				return $this->_color;
			}

			elseif( strlen( $this->_color ) === 6 && is_numeric( $this->_color) ) {
				return 'rgba('.$this->hex2rgb( $this->_color ).", $opacity)";
			}

			elseif( substr( $this->_color, 0, 1 ) === '#' ){
				return 'rgba('.$this->hex2rgb( $this->_color ).", $opacity)";
			}

			elseif( substr( $this->_color, 0, 4 ) === 'rgb(' ){
				return 'rgba('.substr( substr( $this->_color, 4 ), 0, -1 ).", $opacity)";
			}

			elseif( substr( $this->_color, 0, 4 ) === 'rgba' ){
				$out = substr( substr( $this->_color, 5 ), 0, -1 );
				$out = explode( ',', $out );
				$out = $out[0].','.$out[1].','.$out[2];
				return "rgba($out, $opacity)";
			}

			elseif( $this->_bk_color === 'transparent' ) {
				return 'rgba('.$this->hex2rgb( $this->_bk_color ).", $opacity)";
			}

			elseif( strlen( $this->_bk_color ) === 6 && is_numeric( $this->_color ) ) {
				return 'rgba('.$this->hex2rgb( $this->_bk_color ).", $opacity)";
			}

			elseif( substr( $this->_bk_color, 0, 1 ) === '#' ) {
				return 'rgba('.$this->hex2rgb( $this->_bk_color ).", $opacity)";
			}

			elseif( substr( $this->_color, 0, 4 ) === 'rgb(' ){
				return 'rgba('.substr( substr( $this->_bk_color, 4 ), 0, -1 ).", $opacity)";
			}

			elseif( substr( $this->_bk_color, 0, 4 ) === 'rgba' ){
				$out = substr( substr( $this->_bk_color, 5 ), 0, -1 );
				$out = explode( ',', $out );
				$out = $out[0].','.$out[1].','.$out[2];
				return "rgba($out, $opacity)";
			}

			else{
				return 'transparent';
			}
		}
	}

	public function hex2rgb( $hex ) {
	   $hex = str_replace("#", "", $hex);

	   if(strlen($hex) == 3) {
	      $r = hexdec(substr($hex,0,1).substr($hex,0,1));
	      $g = hexdec(substr($hex,1,1).substr($hex,1,1));
	      $b = hexdec(substr($hex,2,1).substr($hex,2,1));
	   } else {
	      $r = hexdec(substr($hex,0,2));
	      $g = hexdec(substr($hex,2,2));
	      $b = hexdec(substr($hex,4,2));
	   }
	   $rgb = array($r, $g, $b);
	   return implode(",", $rgb);
	}

	public function rgb2hex( $rgb ){

		if( $rgb === 'transparent' ) return;

		else if( substr( $rgb, 0, 4 ) === 'rgb(' ){
			$rgb = explode(',', substr( trim( $rgb ), 4, -1 ) );
			$R = $rgb[0]; $G = $rgb[1]; $B = $rgb[2];
		}

		else if( substr( $rgb, 0, 4 ) === 'rgba' ){
			$rgb = explode(',', substr( trim( $rgb ), 5 ) );
			$R = $rgb[0]; $G = $rgb[1]; $B = $rgb[2];
		}

		else return $rgb;

		$R=dechex($R);
		If (strlen($R)<2)
		$R='0'.$R;

		$G=dechex($G);
		If (strlen($G)<2)
		$G='0'.$G;

		$B=dechex($B);
		If (strlen($B)<2)
		$B='0'.$B;

		return '#' . $R . $G . $B;
	}

	public static function brightness( $hexStr ) {
		$hexStr = preg_replace("/[^0-9A-Fa-f]/", '', $hexStr);
		$rgbArray = array();
		if (strlen($hexStr) == 6) {
			$colorVal = hexdec($hexStr);
			$rgbArray['red'] = 0xFF & ($colorVal >> 0x10);
			$rgbArray['green'] = 0xFF & ($colorVal >> 0x8);
			$rgbArray['blue'] = 0xFF & $colorVal;
		} elseif (strlen($hexStr) == 3) {
			$rgbArray['red'] = hexdec(str_repeat(substr($hexStr, 0, 1), 2));
			$rgbArray['green'] = hexdec(str_repeat(substr($hexStr, 1, 1), 2));
			$rgbArray['blue'] = hexdec(str_repeat(substr($hexStr, 2, 1), 2));
		} else {
			return false;
		}
		return (($rgbArray['red']*299) + ($rgbArray['green']*587) + ($rgbArray['blue']*114))/1000;
	}

	public function contrast( $opacity = 1 ) {
		$test1 = '#000000';
		$test2 = '#ffffff';
		if( abs( $this->brightness( $test1 ) - $this->brightness( $this->rgb2hex($this->_color) ) ) > abs($this->brightness($test2) - $this->brightness( $this->rgb2hex($this->_color) )) ){
			return 'rgba('.$this->hex2rgb($test1).', '.$opacity.')';
		} else {
			return 'rgba('.$this->hex2rgb($test2).', '.$opacity.')';
		}
	}

	public function darken( $dif=20 ){
		$color = $this->rgb2hex($this->_color);
	    $color = str_replace('#', '', $color);
	    if (strlen($color) != 6){ return '000000'; }
	    $rgb = '';
	    for ($x=0;$x<3;$x++){
	        $c = hexdec(substr($color,(2*$x),2)) - $dif;
	        $c = ($c < 0) ? 0 : dechex($c);
	        $rgb .= (strlen($c) < 2) ? '0'.$c : $c;
	    }
	    return 'rgb('.$this->hex2rgb('#'.$rgb).')';
	}


	public function lighten( $amount = 60 ){

		$color = $this->_hex_color;

        // Calculate straight from rbg
        $r = hexdec($color[0].$color[1]);
        $g = hexdec($color[2].$color[3]);
        $b = hexdec($color[4].$color[5]);

        if( ( $r*299 + $g*587 + $b*114 )/1000 > 130 ){
	        return self::darken( $amount + 40 );
        } else{
	        return self::_lighten( $amount );
        }


    }



    public function _lighten( $amount = 60 ){
		$hsl = $this->_hsl;

        if( $amount ) {
            $hsl['L'] = ($hsl['L'] * 100) + $amount;
            $hsl['L'] = ($hsl['L'] > 100) ? 1:$hsl['L']/100;
        } else {
            $hsl['L'] += (1-$hsl['L'])/2;
        }
        return 'rgba( ' . self::hex2rgb( self::hslToHex( $hsl ) ) . ', 1);';
    }




}

?>
