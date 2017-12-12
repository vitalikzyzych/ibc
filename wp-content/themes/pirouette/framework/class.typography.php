<?php

class PirouetteFont {

	public $_family;
	public $_size;
	public $_rem;
	public $_style;
	public $_variant;
	public $_spacing;

	public function __construct( $font = array() ) {
		$this->_family 	= $this->get_font_family( isset( $font[0] ) ? $font[0] : '' );
    $this->_style 	= $this->get_font_style( isset( $font[1] ) ? $font[1] : '' );
		$this->_size 	  = isset( $font[2] ) ? $font[2] : 14;
		$this->_rem 	  = 'font-size:' . ( $this->_size / 16 ) . 'rem;';
		$this->_variant = $this->get_font_variant( isset( $font[3] ) ? $font[3] : '' );
		$this->_spacing = $this->get_font_spacing( isset( $font[4] ) ? $font[4] : '' );
	}


	public static function font_transient(){

		//delete_option('pirouette_google_font_list' );
		//delete_transient('pirouette_google_font_list');

		$font_array = get_transient( 'pirouette_google_font_list' );

		if ( $font_array === false || empty( $font_array ) ) {

			$font_array = json_decode( get_option( 'pirouette_google_font_list' ), true );

			if( empty( $font_array ) ){
				PirouetteLoadFonts::theme_switch( 'manual', 'trigger' );
			}

			$font_array = json_decode( get_option( 'pirouette_google_font_list' ), true );

			set_transient( 'pirouette_google_font_list', $font_array, 60 * 60 * 24 * 30 );
		}

		return $font_array;

	}

	private function get_font_spacing( $spacing ) {

		if( $spacing === '0' || is_null( $spacing ) || empty( $spacing ) )
			return;

		$spacing = $spacing / 100;

		return "letter-spacing: {$spacing}em;";
	}


	private function get_font_family( $family ) {

    if( empty( $family ) ||  is_null( $family ) )
      return;

		if( preg_match( '/mono/i', $family ) ) return "font-family: '$family', monospace;";
		if( preg_match( '/serif/i', $family ) ) return "font-family: '$family', serif;";
		if( preg_match( '/sans/i', $family ) ) return "font-family: '$family', sans;";

		return "font-family: '$family';";
	}

	private function get_font_style( $style ){

		switch ( $style ) {
			case '100' : return 'font-weight: 100;';break;
			case '100italic' : return 'font-weight: 100;font-style: italic;';break;
			case '200' : return 'font-weight: 200;';break;
			case '200italic' : return 'font-weight: 200;font-style: italic;';break;
			case '300' : return 'font-weight: 300;';break;
			case '300italic' : return 'font-weight: 300;font-style: italic;';break;
			case '500' : return 'font-weight: 500;';break;
			case '500italic' : return 'font-weight: 500;font-style: italic;';break;
			case '600' : return 'font-weight: 600;';break;
			case '600italic' : return 'font-weight: 600;font-style: italic;';break;
			case '700' : return 'font-weight: 700;';break;
			case '700italic' : return 'font-weight: 700;font-style: italic;';break;
			case '800' : return 'font-weight: 800;';break;
			case '800italic' : return 'font-weight: 800;font-style: italic;';break;
			case '900' : return 'font-weight: 900;';break;
			case '900italic' : return 'font-weight: 900;font-style: italic;';break;
			case 'italic' : return 'font-weight: normal;font-style: italic;';break;
			default :
				return 'font-weight: normal;';
				break;
		}
	}

	private function get_font_variant( $variant ){
		switch ( $variant ) {
			case 'normal' : return 'text-transform: none;';break;
			case 'capitalize' : return 'text-transform: capitalize;';break;
			case 'uppercase' : return 'text-transform: uppercase;';break;
			case 'smallcaps' : return 'font-variant: small-caps;';break;
		}
	}

}

class PirouetteLoadFonts {

	public function __construct() {

		if ( is_admin() ) {

			/** Theme Activation */
			add_action( 'after_switch_theme', array( $this, 'theme_switch' ), 10 , 2 );

		}

    add_filter( 'pirouette_fonts_array', array( $this, 'generate_fonts_array' ), 10, 2 );
		add_filter( 'pirouette_pre_fonts_array', array( $this, 'pre_fonts_array' ), 10, 7 );

	}

	function pre_fonts_array( $tag, $family, $variant, $size, $transform = null, $spacing = null ){

		$default = array( $family, $variant, $size );

		if( ! is_null( $transform ) ){
			$default[] = $transform;
		}

		if( ! is_null( $spacing ) ){
			$default[] = $spacing;
		}

		$default = implode( ',', $default );

		$font = get_theme_mod( $tag, $default );
		$font = ! is_array( $font ) ? explode( ',', esc_attr( $font ) ) : $font ;
		$font = array(
			! empty( $font[0] ) ? $font[0] : null,
			$font[1],
			$font[2],
			is_null( $transform ) ? null :$font[3],
			is_null( $spacing ) ? null : $font[4]
		);

		return $font;
	}


  function generate_fonts_array( $fonts, $font ){

    $fonts = self::fonts_array(
      $fonts,
      $font[0],
      $font[1]
    );

    return $fonts;

  }


	/** Theme Activation */
	public static function theme_switch( $oldname, $oldtheme = false ) {

		$args = array(
			'sort' => 'alpha',
			'fields' => 'items(family%2Cvariants)',
			'key' => 'AIzaSyD1LWzIhUv1XuYTKYDQpMcaB6JY1vfId3w'
		);

		$url = 'https://www.googleapis.com/webfonts/v1/webfonts';
		$url = add_query_arg( $args, $url );

		$response = wp_remote_get( $url );

		if ( ! is_wp_error( $response ) ) {

			update_option( 'pirouette_google_font_list', $response['body'] );

		} else {

			add_option( 'pirouette_google_font_list', '{"items":[{"family":"ABeeZee","variants":["regular","italic"]},{"family":"Abel","variants":["regular"]},{"family":"Abril Fatface","variants":["regular"]},{"family":"Aclonica","variants":["regular"]},{"family":"Acme","variants":["regular"]},{"family":"Actor","variants":["regular"]},{"family":"Adamina","variants":["regular"]},{"family":"Advent Pro","variants":["100","200","300","regular","500","600","700"]},{"family":"Aguafina Script","variants":["regular"]},{"family":"Akronim","variants":["regular"]},{"family":"Aladin","variants":["regular"]},{"family":"Aldrich","variants":["regular"]},{"family":"Alef","variants":["regular","700"]},{"family":"Alegreya","variants":["regular","italic","700","700italic","900","900italic"]},{"family":"Alegreya SC","variants":["regular","italic","700","700italic","900","900italic"]},{"family":"Alegreya Sans","variants":["100","100italic","300","300italic","regular","italic","500","500italic","700","700italic","800","800italic","900","900italic"]},{"family":"Alegreya Sans SC","variants":["100","100italic","300","300italic","regular","italic","500","500italic","700","700italic","800","800italic","900","900italic"]},{"family":"Alex Brush","variants":["regular"]},{"family":"Alfa Slab One","variants":["regular"]},{"family":"Alice","variants":["regular"]},{"family":"Alike","variants":["regular"]},{"family":"Alike Angular","variants":["regular"]},{"family":"Allan","variants":["regular","700"]},{"family":"Allerta","variants":["regular"]},{"family":"Allerta Stencil","variants":["regular"]},{"family":"Allura","variants":["regular"]},{"family":"Almendra","variants":["regular","italic","700","700italic"]},{"family":"Almendra Display","variants":["regular"]},{"family":"Almendra SC","variants":["regular"]},{"family":"Amarante","variants":["regular"]},{"family":"Amaranth","variants":["regular","italic","700","700italic"]},{"family":"Amatic SC","variants":["regular","700"]},{"family":"Amethysta","variants":["regular"]},{"family":"Amiri","variants":["regular","italic","700","700italic"]},{"family":"Amita","variants":["regular","700"]},{"family":"Anaheim","variants":["regular"]},{"family":"Andada","variants":["regular"]},{"family":"Andika","variants":["regular"]},{"family":"Angkor","variants":["regular"]},{"family":"Annie Use Your Telescope","variants":["regular"]},{"family":"Anonymous Pro","variants":["regular","italic","700","700italic"]},{"family":"Antic","variants":["regular"]},{"family":"Antic Didone","variants":["regular"]},{"family":"Antic Slab","variants":["regular"]},{"family":"Anton","variants":["regular"]},{"family":"Arapey","variants":["regular","italic"]},{"family":"Arbutus","variants":["regular"]},{"family":"Arbutus Slab","variants":["regular"]},{"family":"Architects Daughter","variants":["regular"]},{"family":"Archivo Black","variants":["regular"]},{"family":"Archivo Narrow","variants":["regular","italic","700","700italic"]},{"family":"Arimo","variants":["regular","italic","700","700italic"]},{"family":"Arizonia","variants":["regular"]},{"family":"Armata","variants":["regular"]},{"family":"Artifika","variants":["regular"]},{"family":"Arvo","variants":["regular","italic","700","700italic"]},{"family":"Arya","variants":["regular","700"]},{"family":"Asap","variants":["regular","italic","700","700italic"]},{"family":"Asar","variants":["regular"]},{"family":"Asset","variants":["regular"]},{"family":"Astloch","variants":["regular","700"]},{"family":"Asul","variants":["regular","700"]},{"family":"Atomic Age","variants":["regular"]},{"family":"Aubrey","variants":["regular"]},{"family":"Audiowide","variants":["regular"]},{"family":"Autour One","variants":["regular"]},{"family":"Average","variants":["regular"]},{"family":"Average Sans","variants":["regular"]},{"family":"Averia Gruesa Libre","variants":["regular"]},{"family":"Averia Libre","variants":["300","300italic","regular","italic","700","700italic"]},{"family":"Averia Sans Libre","variants":["300","300italic","regular","italic","700","700italic"]},{"family":"Averia Serif Libre","variants":["300","300italic","regular","italic","700","700italic"]},{"family":"Bad Script","variants":["regular"]},{"family":"Balthazar","variants":["regular"]},{"family":"Bangers","variants":["regular"]},{"family":"Basic","variants":["regular"]},{"family":"Battambang","variants":["regular","700"]},{"family":"Baumans","variants":["regular"]},{"family":"Bayon","variants":["regular"]},{"family":"Belgrano","variants":["regular"]},{"family":"Belleza","variants":["regular"]},{"family":"BenchNine","variants":["300","regular","700"]},{"family":"Bentham","variants":["regular"]},{"family":"Berkshire Swash","variants":["regular"]},{"family":"Bevan","variants":["regular"]},{"family":"Bigelow Rules","variants":["regular"]},{"family":"Bigshot One","variants":["regular"]},{"family":"Bilbo","variants":["regular"]},{"family":"Bilbo Swash Caps","variants":["regular"]},{"family":"Biryani","variants":["200","300","regular","600","700","800","900"]},{"family":"Bitter","variants":["regular","italic","700"]},{"family":"Black Ops One","variants":["regular"]},{"family":"Bokor","variants":["regular"]},{"family":"Bonbon","variants":["regular"]},{"family":"Boogaloo","variants":["regular"]},{"family":"Bowlby One","variants":["regular"]},{"family":"Bowlby One SC","variants":["regular"]},{"family":"Brawler","variants":["regular"]},{"family":"Bree Serif","variants":["regular"]},{"family":"Bubblegum Sans","variants":["regular"]},{"family":"Bubbler One","variants":["regular"]},{"family":"Buda","variants":["300"]},{"family":"Buenard","variants":["regular","700"]},{"family":"Butcherman","variants":["regular"]},{"family":"Butterfly Kids","variants":["regular"]},{"family":"Cabin","variants":["regular","italic","500","500italic","600","600italic","700","700italic"]},{"family":"Cabin Condensed","variants":["regular","500","600","700"]},{"family":"Cabin Sketch","variants":["regular","700"]},{"family":"Caesar Dressing","variants":["regular"]},{"family":"Cagliostro","variants":["regular"]},{"family":"Calligraffitti","variants":["regular"]},{"family":"Cambay","variants":["regular","italic","700","700italic"]},{"family":"Cambo","variants":["regular"]},{"family":"Candal","variants":["regular"]},{"family":"Cantarell","variants":["regular","italic","700","700italic"]},{"family":"Cantata One","variants":["regular"]},{"family":"Cantora One","variants":["regular"]},{"family":"Capriola","variants":["regular"]},{"family":"Cardo","variants":["regular","italic","700"]},{"family":"Carme","variants":["regular"]},{"family":"Carrois Gothic","variants":["regular"]},{"family":"Carrois Gothic SC","variants":["regular"]},{"family":"Carter One","variants":["regular"]},{"family":"Caudex","variants":["regular","italic","700","700italic"]},{"family":"Cedarville Cursive","variants":["regular"]},{"family":"Ceviche One","variants":["regular"]},{"family":"Changa One","variants":["regular","italic"]},{"family":"Chango","variants":["regular"]},{"family":"Chau Philomene One","variants":["regular","italic"]},{"family":"Chela One","variants":["regular"]},{"family":"Chelsea Market","variants":["regular"]},{"family":"Chenla","variants":["regular"]},{"family":"Cherry Cream Soda","variants":["regular"]},{"family":"Cherry Swash","variants":["regular","700"]},{"family":"Chewy","variants":["regular"]},{"family":"Chicle","variants":["regular"]},{"family":"Chivo","variants":["regular","italic","900","900italic"]},{"family":"Cinzel","variants":["regular","700","900"]},{"family":"Cinzel Decorative","variants":["regular","700","900"]},{"family":"Clicker Script","variants":["regular"]},{"family":"Coda","variants":["regular","800"]},{"family":"Coda Caption","variants":["800"]},{"family":"Codystar","variants":["300","regular"]},{"family":"Combo","variants":["regular"]},{"family":"Comfortaa","variants":["300","regular","700"]},{"family":"Coming Soon","variants":["regular"]},{"family":"Concert One","variants":["regular"]},{"family":"Condiment","variants":["regular"]},{"family":"Content","variants":["regular","700"]},{"family":"Contrail One","variants":["regular"]},{"family":"Convergence","variants":["regular"]},{"family":"Cookie","variants":["regular"]},{"family":"Copse","variants":["regular"]},{"family":"Corben","variants":["regular","700"]},{"family":"Courgette","variants":["regular"]},{"family":"Cousine","variants":["regular","italic","700","700italic"]},{"family":"Coustard","variants":["regular","900"]},{"family":"Covered By Your Grace","variants":["regular"]},{"family":"Crafty Girls","variants":["regular"]},{"family":"Creepster","variants":["regular"]},{"family":"Crete Round","variants":["regular","italic"]},{"family":"Crimson Text","variants":["regular","italic","600","600italic","700","700italic"]},{"family":"Croissant One","variants":["regular"]},{"family":"Crushed","variants":["regular"]},{"family":"Cuprum","variants":["regular","italic","700","700italic"]},{"family":"Cutive","variants":["regular"]},{"family":"Cutive Mono","variants":["regular"]},{"family":"Damion","variants":["regular"]},{"family":"Dancing Script","variants":["regular","700"]},{"family":"Dangrek","variants":["regular"]},{"family":"Dawning of a New Day","variants":["regular"]},{"family":"Days One","variants":["regular"]},{"family":"Dekko","variants":["regular"]},{"family":"Delius","variants":["regular"]},{"family":"Delius Swash Caps","variants":["regular"]},{"family":"Delius Unicase","variants":["regular","700"]},{"family":"Della Respira","variants":["regular"]},{"family":"Denk One","variants":["regular"]},{"family":"Devonshire","variants":["regular"]},{"family":"Dhurjati","variants":["regular"]},{"family":"Didact Gothic","variants":["regular"]},{"family":"Diplomata","variants":["regular"]},{"family":"Diplomata SC","variants":["regular"]},{"family":"Domine","variants":["regular","700"]},{"family":"Donegal One","variants":["regular"]},{"family":"Doppio One","variants":["regular"]},{"family":"Dorsa","variants":["regular"]},{"family":"Dosis","variants":["200","300","regular","500","600","700","800"]},{"family":"Dr Sugiyama","variants":["regular"]},{"family":"Droid Sans","variants":["regular","700"]},{"family":"Droid Sans Mono","variants":["regular"]},{"family":"Droid Serif","variants":["regular","italic","700","700italic"]},{"family":"Duru Sans","variants":["regular"]},{"family":"Dynalight","variants":["regular"]},{"family":"EB Garamond","variants":["regular"]},{"family":"Eagle Lake","variants":["regular"]},{"family":"Eater","variants":["regular"]},{"family":"Economica","variants":["regular","italic","700","700italic"]},{"family":"Eczar","variants":["regular","500","600","700","800"]},{"family":"Ek Mukta","variants":["200","300","regular","500","600","700","800"]},{"family":"Electrolize","variants":["regular"]},{"family":"Elsie","variants":["regular","900"]},{"family":"Elsie Swash Caps","variants":["regular","900"]},{"family":"Emblema One","variants":["regular"]},{"family":"Emilys Candy","variants":["regular"]},{"family":"Engagement","variants":["regular"]},{"family":"Englebert","variants":["regular"]},{"family":"Enriqueta","variants":["regular","700"]},{"family":"Erica One","variants":["regular"]},{"family":"Esteban","variants":["regular"]},{"family":"Euphoria Script","variants":["regular"]},{"family":"Ewert","variants":["regular"]},{"family":"Exo","variants":["100","100italic","200","200italic","300","300italic","regular","italic","500","500italic","600","600italic","700","700italic","800","800italic","900","900italic"]},{"family":"Exo 2","variants":["100","100italic","200","200italic","300","300italic","regular","italic","500","500italic","600","600italic","700","700italic","800","800italic","900","900italic"]},{"family":"Expletus Sans","variants":["regular","italic","500","500italic","600","600italic","700","700italic"]},{"family":"Fanwood Text","variants":["regular","italic"]},{"family":"Fascinate","variants":["regular"]},{"family":"Fascinate Inline","variants":["regular"]},{"family":"Faster One","variants":["regular"]},{"family":"Fasthand","variants":["regular"]},{"family":"Fauna One","variants":["regular"]},{"family":"Federant","variants":["regular"]},{"family":"Federo","variants":["regular"]},{"family":"Felipa","variants":["regular"]},{"family":"Fenix","variants":["regular"]},{"family":"Finger Paint","variants":["regular"]},{"family":"Fira Mono","variants":["regular","700"]},{"family":"Fira Sans","variants":["300","300italic","regular","italic","500","500italic","700","700italic"]},{"family":"Fjalla One","variants":["regular"]},{"family":"Fjord One","variants":["regular"]},{"family":"Flamenco","variants":["300","regular"]},{"family":"Flavors","variants":["regular"]},{"family":"Fondamento","variants":["regular","italic"]},{"family":"Fontdiner Swanky","variants":["regular"]},{"family":"Forum","variants":["regular"]},{"family":"Francois One","variants":["regular"]},{"family":"Freckle Face","variants":["regular"]},{"family":"Fredericka the Great","variants":["regular"]},{"family":"Fredoka One","variants":["regular"]},{"family":"Freehand","variants":["regular"]},{"family":"Fresca","variants":["regular"]},{"family":"Frijole","variants":["regular"]},{"family":"Fruktur","variants":["regular"]},{"family":"Fugaz One","variants":["regular"]},{"family":"GFS Didot","variants":["regular"]},{"family":"GFS Neohellenic","variants":["regular","italic","700","700italic"]},{"family":"Gabriela","variants":["regular"]},{"family":"Gafata","variants":["regular"]},{"family":"Galdeano","variants":["regular"]},{"family":"Galindo","variants":["regular"]},{"family":"Gentium Basic","variants":["regular","italic","700","700italic"]},{"family":"Gentium Book Basic","variants":["regular","italic","700","700italic"]},{"family":"Geo","variants":["regular","italic"]},{"family":"Geostar","variants":["regular"]},{"family":"Geostar Fill","variants":["regular"]},{"family":"Germania One","variants":["regular"]},{"family":"Gidugu","variants":["regular"]},{"family":"Gilda Display","variants":["regular"]},{"family":"Give You Glory","variants":["regular"]},{"family":"Glass Antiqua","variants":["regular"]},{"family":"Glegoo","variants":["regular","700"]},{"family":"Gloria Hallelujah","variants":["regular"]},{"family":"Goblin One","variants":["regular"]},{"family":"Gochi Hand","variants":["regular"]},{"family":"Gorditas","variants":["regular","700"]},{"family":"Goudy Bookletter 1911","variants":["regular"]},{"family":"Graduate","variants":["regular"]},{"family":"Grand Hotel","variants":["regular"]},{"family":"Gravitas One","variants":["regular"]},{"family":"Great Vibes","variants":["regular"]},{"family":"Griffy","variants":["regular"]},{"family":"Gruppo","variants":["regular"]},{"family":"Gudea","variants":["regular","italic","700"]},{"family":"Gurajada","variants":["regular"]},{"family":"Habibi","variants":["regular"]},{"family":"Halant","variants":["300","regular","500","600","700"]},{"family":"Hammersmith One","variants":["regular"]},{"family":"Hanalei","variants":["regular"]},{"family":"Hanalei Fill","variants":["regular"]},{"family":"Handlee","variants":["regular"]},{"family":"Hanuman","variants":["regular","700"]},{"family":"Happy Monkey","variants":["regular"]},{"family":"Headland One","variants":["regular"]},{"family":"Henny Penny","variants":["regular"]},{"family":"Herr Von Muellerhoff","variants":["regular"]},{"family":"Hind","variants":["300","regular","500","600","700"]},{"family":"Holtwood One SC","variants":["regular"]},{"family":"Homemade Apple","variants":["regular"]},{"family":"Homenaje","variants":["regular"]},{"family":"IM Fell DW Pica","variants":["regular","italic"]},{"family":"IM Fell DW Pica SC","variants":["regular"]},{"family":"IM Fell Double Pica","variants":["regular","italic"]},{"family":"IM Fell Double Pica SC","variants":["regular"]},{"family":"IM Fell English","variants":["regular","italic"]},{"family":"IM Fell English SC","variants":["regular"]},{"family":"IM Fell French Canon","variants":["regular","italic"]},{"family":"IM Fell French Canon SC","variants":["regular"]},{"family":"IM Fell Great Primer","variants":["regular","italic"]},{"family":"IM Fell Great Primer SC","variants":["regular"]},{"family":"Iceberg","variants":["regular"]},{"family":"Iceland","variants":["regular"]},{"family":"Imprima","variants":["regular"]},{"family":"Inconsolata","variants":["regular","700"]},{"family":"Inder","variants":["regular"]},{"family":"Indie Flower","variants":["regular"]},{"family":"Inika","variants":["regular","700"]},{"family":"Inknut Antiqua","variants":["300","regular","500","600","700","800","900"]},{"family":"Irish Grover","variants":["regular"]},{"family":"Istok Web","variants":["regular","italic","700","700italic"]},{"family":"Italiana","variants":["regular"]},{"family":"Italianno","variants":["regular"]},{"family":"Jacques Francois","variants":["regular"]},{"family":"Jacques Francois Shadow","variants":["regular"]},{"family":"Jaldi","variants":["regular","700"]},{"family":"Jim Nightshade","variants":["regular"]},{"family":"Jockey One","variants":["regular"]},{"family":"Jolly Lodger","variants":["regular"]},{"family":"Josefin Sans","variants":["100","100italic","300","300italic","regular","italic","600","600italic","700","700italic"]},{"family":"Josefin Slab","variants":["100","100italic","300","300italic","regular","italic","600","600italic","700","700italic"]},{"family":"Joti One","variants":["regular"]},{"family":"Judson","variants":["regular","italic","700"]},{"family":"Julee","variants":["regular"]},{"family":"Julius Sans One","variants":["regular"]},{"family":"Junge","variants":["regular"]},{"family":"Jura","variants":["300","regular","500","600"]},{"family":"Just Another Hand","variants":["regular"]},{"family":"Just Me Again Down Here","variants":["regular"]},{"family":"Kadwa","variants":["regular","700"]},{"family":"Kalam","variants":["300","regular","700"]},{"family":"Kameron","variants":["regular","700"]},{"family":"Kantumruy","variants":["300","regular","700"]},{"family":"Karla","variants":["regular","italic","700","700italic"]},{"family":"Karma","variants":["300","regular","500","600","700"]},{"family":"Kaushan Script","variants":["regular"]},{"family":"Kavoon","variants":["regular"]},{"family":"Kdam Thmor","variants":["regular"]},{"family":"Keania One","variants":["regular"]},{"family":"Kelly Slab","variants":["regular"]},{"family":"Kenia","variants":["regular"]},{"family":"Khand","variants":["300","regular","500","600","700"]},{"family":"Khmer","variants":["regular"]},{"family":"Khula","variants":["300","regular","600","700","800"]},{"family":"Kite One","variants":["regular"]},{"family":"Knewave","variants":["regular"]},{"family":"Kotta One","variants":["regular"]},{"family":"Koulen","variants":["regular"]},{"family":"Kranky","variants":["regular"]},{"family":"Kreon","variants":["300","regular","700"]},{"family":"Kristi","variants":["regular"]},{"family":"Krona One","variants":["regular"]},{"family":"Kurale","variants":["regular"]},{"family":"La Belle Aurore","variants":["regular"]},{"family":"Laila","variants":["300","regular","500","600","700"]},{"family":"Lakki Reddy","variants":["regular"]},{"family":"Lancelot","variants":["regular"]},{"family":"Lateef","variants":["regular"]},{"family":"Lato","variants":["100","100italic","300","300italic","regular","italic","700","700italic","900","900italic"]},{"family":"League Script","variants":["regular"]},{"family":"Leckerli One","variants":["regular"]},{"family":"Ledger","variants":["regular"]},{"family":"Lekton","variants":["regular","italic","700"]},{"family":"Lemon","variants":["regular"]},{"family":"Libre Baskerville","variants":["regular","italic","700"]},{"family":"Life Savers","variants":["regular","700"]},{"family":"Lilita One","variants":["regular"]},{"family":"Lily Script One","variants":["regular"]},{"family":"Limelight","variants":["regular"]},{"family":"Linden Hill","variants":["regular","italic"]},{"family":"Lobster","variants":["regular"]},{"family":"Lobster Two","variants":["regular","italic","700","700italic"]},{"family":"Londrina Outline","variants":["regular"]},{"family":"Londrina Shadow","variants":["regular"]},{"family":"Londrina Sketch","variants":["regular"]},{"family":"Londrina Solid","variants":["regular"]},{"family":"Lora","variants":["regular","italic","700","700italic"]},{"family":"Love Ya Like A Sister","variants":["regular"]},{"family":"Loved by the King","variants":["regular"]},{"family":"Lovers Quarrel","variants":["regular"]},{"family":"Luckiest Guy","variants":["regular"]},{"family":"Lusitana","variants":["regular","700"]},{"family":"Lustria","variants":["regular"]},{"family":"Macondo","variants":["regular"]},{"family":"Macondo Swash Caps","variants":["regular"]},{"family":"Magra","variants":["regular","700"]},{"family":"Maiden Orange","variants":["regular"]},{"family":"Mako","variants":["regular"]},{"family":"Mallanna","variants":["regular"]},{"family":"Mandali","variants":["regular"]},{"family":"Marcellus","variants":["regular"]},{"family":"Marcellus SC","variants":["regular"]},{"family":"Marck Script","variants":["regular"]},{"family":"Margarine","variants":["regular"]},{"family":"Marko One","variants":["regular"]},{"family":"Marmelad","variants":["regular"]},{"family":"Martel","variants":["200","300","regular","600","700","800","900"]},{"family":"Martel Sans","variants":["200","300","regular","600","700","800","900"]},{"family":"Marvel","variants":["regular","italic","700","700italic"]},{"family":"Mate","variants":["regular","italic"]},{"family":"Mate SC","variants":["regular"]},{"family":"Maven Pro","variants":["regular","500","700","900"]},{"family":"McLaren","variants":["regular"]},{"family":"Meddon","variants":["regular"]},{"family":"MedievalSharp","variants":["regular"]},{"family":"Medula One","variants":["regular"]},{"family":"Megrim","variants":["regular"]},{"family":"Meie Script","variants":["regular"]},{"family":"Merienda","variants":["regular","700"]},{"family":"Merienda One","variants":["regular"]},{"family":"Merriweather","variants":["300","300italic","regular","italic","700","700italic","900","900italic"]},{"family":"Merriweather Sans","variants":["300","300italic","regular","italic","700","700italic","800","800italic"]},{"family":"Metal","variants":["regular"]},{"family":"Metal Mania","variants":["regular"]},{"family":"Metamorphous","variants":["regular"]},{"family":"Metrophobic","variants":["regular"]},{"family":"Michroma","variants":["regular"]},{"family":"Milonga","variants":["regular"]},{"family":"Miltonian","variants":["regular"]},{"family":"Miltonian Tattoo","variants":["regular"]},{"family":"Miniver","variants":["regular"]},{"family":"Miss Fajardose","variants":["regular"]},{"family":"Modak","variants":["regular"]},{"family":"Modern Antiqua","variants":["regular"]},{"family":"Molengo","variants":["regular"]},{"family":"Molle","variants":["italic"]},{"family":"Monda","variants":["regular","700"]},{"family":"Monofett","variants":["regular"]},{"family":"Monoton","variants":["regular"]},{"family":"Monsieur La Doulaise","variants":["regular"]},{"family":"Montaga","variants":["regular"]},{"family":"Montez","variants":["regular"]},{"family":"Montserrat","variants":["regular","700"]},{"family":"Montserrat Alternates","variants":["regular","700"]},{"family":"Montserrat Subrayada","variants":["regular","700"]},{"family":"Moul","variants":["regular"]},{"family":"Moulpali","variants":["regular"]},{"family":"Mountains of Christmas","variants":["regular","700"]},{"family":"Mouse Memoirs","variants":["regular"]},{"family":"Mr Bedfort","variants":["regular"]},{"family":"Mr Dafoe","variants":["regular"]},{"family":"Mr De Haviland","variants":["regular"]},{"family":"Mrs Saint Delafield","variants":["regular"]},{"family":"Mrs Sheppards","variants":["regular"]},{"family":"Muli","variants":["300","300italic","regular","italic"]},{"family":"Mystery Quest","variants":["regular"]},{"family":"NTR","variants":["regular"]},{"family":"Neucha","variants":["regular"]},{"family":"Neuton","variants":["200","300","regular","italic","700","800"]},{"family":"New Rocker","variants":["regular"]},{"family":"News Cycle","variants":["regular","700"]},{"family":"Niconne","variants":["regular"]},{"family":"Nixie One","variants":["regular"]},{"family":"Nobile","variants":["regular","italic","700","700italic"]},{"family":"Nokora","variants":["regular","700"]},{"family":"Norican","variants":["regular"]},{"family":"Nosifer","variants":["regular"]},{"family":"Nothing You Could Do","variants":["regular"]},{"family":"Noticia Text","variants":["regular","italic","700","700italic"]},{"family":"Noto Sans","variants":["regular","italic","700","700italic"]},{"family":"Noto Serif","variants":["regular","italic","700","700italic"]},{"family":"Nova Cut","variants":["regular"]},{"family":"Nova Flat","variants":["regular"]},{"family":"Nova Mono","variants":["regular"]},{"family":"Nova Oval","variants":["regular"]},{"family":"Nova Round","variants":["regular"]},{"family":"Nova Script","variants":["regular"]},{"family":"Nova Slim","variants":["regular"]},{"family":"Nova Square","variants":["regular"]},{"family":"Numans","variants":["regular"]},{"family":"Nunito","variants":["300","regular","700"]},{"family":"Odor Mean Chey","variants":["regular"]},{"family":"Offside","variants":["regular"]},{"family":"Old Standard TT","variants":["regular","italic","700"]},{"family":"Oldenburg","variants":["regular"]},{"family":"Oleo Script","variants":["regular","700"]},{"family":"Oleo Script Swash Caps","variants":["regular","700"]},{"family":"Open Sans","variants":["300","300italic","regular","italic","600","600italic","700","700italic","800","800italic"]},{"family":"Open Sans Condensed","variants":["300","300italic","700"]},{"family":"Oranienbaum","variants":["regular"]},{"family":"Orbitron","variants":["regular","500","700","900"]},{"family":"Oregano","variants":["regular","italic"]},{"family":"Orienta","variants":["regular"]},{"family":"Original Surfer","variants":["regular"]},{"family":"Oswald","variants":["300","regular","700"]},{"family":"Over the Rainbow","variants":["regular"]},{"family":"Overlock","variants":["regular","italic","700","700italic","900","900italic"]},{"family":"Overlock SC","variants":["regular"]},{"family":"Ovo","variants":["regular"]},{"family":"Oxygen","variants":["300","regular","700"]},{"family":"Oxygen Mono","variants":["regular"]},{"family":"PT Mono","variants":["regular"]},{"family":"PT Sans","variants":["regular","italic","700","700italic"]},{"family":"PT Sans Caption","variants":["regular","700"]},{"family":"PT Sans Narrow","variants":["regular","700"]},{"family":"PT Serif","variants":["regular","italic","700","700italic"]},{"family":"PT Serif Caption","variants":["regular","italic"]},{"family":"Pacifico","variants":["regular"]},{"family":"Palanquin","variants":["100","200","300","regular","500","600","700"]},{"family":"Palanquin Dark","variants":["regular","500","600","700"]},{"family":"Paprika","variants":["regular"]},{"family":"Parisienne","variants":["regular"]},{"family":"Passero One","variants":["regular"]},{"family":"Passion One","variants":["regular","700","900"]},{"family":"Pathway Gothic One","variants":["regular"]},{"family":"Patrick Hand","variants":["regular"]},{"family":"Patrick Hand SC","variants":["regular"]},{"family":"Patua One","variants":["regular"]},{"family":"Paytone One","variants":["regular"]},{"family":"Peddana","variants":["regular"]},{"family":"Peralta","variants":["regular"]},{"family":"Permanent Marker","variants":["regular"]},{"family":"Petit Formal Script","variants":["regular"]},{"family":"Petrona","variants":["regular"]},{"family":"Philosopher","variants":["regular","italic","700","700italic"]},{"family":"Piedra","variants":["regular"]},{"family":"Pinyon Script","variants":["regular"]},{"family":"Pirata One","variants":["regular"]},{"family":"Plaster","variants":["regular"]},{"family":"Play","variants":["regular","700"]},{"family":"Playball","variants":["regular"]},{"family":"Playfair Display","variants":["regular","italic","700","700italic","900","900italic"]},{"family":"Playfair Display SC","variants":["regular","italic","700","700italic","900","900italic"]},{"family":"Podkova","variants":["regular","700"]},{"family":"Poiret One","variants":["regular"]},{"family":"Poller One","variants":["regular"]},{"family":"Poly","variants":["regular","italic"]},{"family":"Pompiere","variants":["regular"]},{"family":"Pontano Sans","variants":["regular"]},{"family":"Poppins","variants":["300","regular","500","600","700"]},{"family":"Port Lligat Sans","variants":["regular"]},{"family":"Port Lligat Slab","variants":["regular"]},{"family":"Pragati Narrow","variants":["regular","700"]},{"family":"Prata","variants":["regular"]},{"family":"Preahvihear","variants":["regular"]},{"family":"Press Start 2P","variants":["regular"]},{"family":"Princess Sofia","variants":["regular"]},{"family":"Prociono","variants":["regular"]},{"family":"Prosto One","variants":["regular"]},{"family":"Puritan","variants":["regular","italic","700","700italic"]},{"family":"Purple Purse","variants":["regular"]},{"family":"Quando","variants":["regular"]},{"family":"Quantico","variants":["regular","italic","700","700italic"]},{"family":"Quattrocento","variants":["regular","700"]},{"family":"Quattrocento Sans","variants":["regular","italic","700","700italic"]},{"family":"Questrial","variants":["regular"]},{"family":"Quicksand","variants":["300","regular","700"]},{"family":"Quintessential","variants":["regular"]},{"family":"Qwigley","variants":["regular"]},{"family":"Racing Sans One","variants":["regular"]},{"family":"Radley","variants":["regular","italic"]},{"family":"Rajdhani","variants":["300","regular","500","600","700"]},{"family":"Raleway","variants":["100","200","300","regular","500","600","700","800","900"]},{"family":"Raleway Dots","variants":["regular"]},{"family":"Ramabhadra","variants":["regular"]},{"family":"Ramaraja","variants":["regular"]},{"family":"Rambla","variants":["regular","italic","700","700italic"]},{"family":"Rammetto One","variants":["regular"]},{"family":"Ranchers","variants":["regular"]},{"family":"Rancho","variants":["regular"]},{"family":"Ranga","variants":["regular","700"]},{"family":"Rationale","variants":["regular"]},{"family":"Ravi Prakash","variants":["regular"]},{"family":"Redressed","variants":["regular"]},{"family":"Reenie Beanie","variants":["regular"]},{"family":"Revalia","variants":["regular"]},{"family":"Rhodium Libre","variants":["regular"]},{"family":"Ribeye","variants":["regular"]},{"family":"Ribeye Marrow","variants":["regular"]},{"family":"Righteous","variants":["regular"]},{"family":"Risque","variants":["regular"]},{"family":"Roboto","variants":["100","100italic","300","300italic","regular","italic","500","500italic","700","700italic","900","900italic"]},{"family":"Roboto Condensed","variants":["300","300italic","regular","italic","700","700italic"]},{"family":"Roboto Mono","variants":["100","100italic","300","300italic","regular","italic","500","500italic","700","700italic"]},{"family":"Roboto Slab","variants":["100","300","regular","700"]},{"family":"Rochester","variants":["regular"]},{"family":"Rock Salt","variants":["regular"]},{"family":"Rokkitt","variants":["regular","700"]},{"family":"Romanesco","variants":["regular"]},{"family":"Ropa Sans","variants":["regular","italic"]},{"family":"Rosario","variants":["regular","italic","700","700italic"]},{"family":"Rosarivo","variants":["regular","italic"]},{"family":"Rouge Script","variants":["regular"]},{"family":"Rozha One","variants":["regular"]},{"family":"Rubik","variants":["300","300italic","regular","italic","500","500italic","700","700italic","900","900italic"]},{"family":"Rubik Mono One","variants":["regular"]},{"family":"Rubik One","variants":["regular"]},{"family":"Ruda","variants":["regular","700","900"]},{"family":"Rufina","variants":["regular","700"]},{"family":"Ruge Boogie","variants":["regular"]},{"family":"Ruluko","variants":["regular"]},{"family":"Rum Raisin","variants":["regular"]},{"family":"Ruslan Display","variants":["regular"]},{"family":"Russo One","variants":["regular"]},{"family":"Ruthie","variants":["regular"]},{"family":"Rye","variants":["regular"]},{"family":"Sacramento","variants":["regular"]},{"family":"Sahitya","variants":["regular","700"]},{"family":"Sail","variants":["regular"]},{"family":"Salsa","variants":["regular"]},{"family":"Sanchez","variants":["regular","italic"]},{"family":"Sancreek","variants":["regular"]},{"family":"Sansita One","variants":["regular"]},{"family":"Sarala","variants":["regular","700"]},{"family":"Sarina","variants":["regular"]},{"family":"Sarpanch","variants":["regular","500","600","700","800","900"]},{"family":"Satisfy","variants":["regular"]},{"family":"Scada","variants":["regular","italic","700","700italic"]},{"family":"Scheherazade","variants":["regular"]},{"family":"Schoolbell","variants":["regular"]},{"family":"Seaweed Script","variants":["regular"]},{"family":"Sevillana","variants":["regular"]},{"family":"Seymour One","variants":["regular"]},{"family":"Shadows Into Light","variants":["regular"]},{"family":"Shadows Into Light Two","variants":["regular"]},{"family":"Shanti","variants":["regular"]},{"family":"Share","variants":["regular","italic","700","700italic"]},{"family":"Share Tech","variants":["regular"]},{"family":"Share Tech Mono","variants":["regular"]},{"family":"Shojumaru","variants":["regular"]},{"family":"Short Stack","variants":["regular"]},{"family":"Siemreap","variants":["regular"]},{"family":"Sigmar One","variants":["regular"]},{"family":"Signika","variants":["300","regular","600","700"]},{"family":"Signika Negative","variants":["300","regular","600","700"]},{"family":"Simonetta","variants":["regular","italic","900","900italic"]},{"family":"Sintony","variants":["regular","700"]},{"family":"Sirin Stencil","variants":["regular"]},{"family":"Six Caps","variants":["regular"]},{"family":"Skranji","variants":["regular","700"]},{"family":"Slabo 13px","variants":["regular"]},{"family":"Slabo 27px","variants":["regular"]},{"family":"Slackey","variants":["regular"]},{"family":"Smokum","variants":["regular"]},{"family":"Smythe","variants":["regular"]},{"family":"Sniglet","variants":["regular","800"]},{"family":"Snippet","variants":["regular"]},{"family":"Snowburst One","variants":["regular"]},{"family":"Sofadi One","variants":["regular"]},{"family":"Sofia","variants":["regular"]},{"family":"Sonsie One","variants":["regular"]},{"family":"Sorts Mill Goudy","variants":["regular","italic"]},{"family":"Source Code Pro","variants":["200","300","regular","500","600","700","900"]},{"family":"Source Sans Pro","variants":["200","200italic","300","300italic","regular","italic","600","600italic","700","700italic","900","900italic"]},{"family":"Source Serif Pro","variants":["regular","600","700"]},{"family":"Special Elite","variants":["regular"]},{"family":"Spicy Rice","variants":["regular"]},{"family":"Spinnaker","variants":["regular"]},{"family":"Spirax","variants":["regular"]},{"family":"Squada One","variants":["regular"]},{"family":"Sree Krushnadevaraya","variants":["regular"]},{"family":"Stalemate","variants":["regular"]},{"family":"Stalinist One","variants":["regular"]},{"family":"Stardos Stencil","variants":["regular","700"]},{"family":"Stint Ultra Condensed","variants":["regular"]},{"family":"Stint Ultra Expanded","variants":["regular"]},{"family":"Stoke","variants":["300","regular"]},{"family":"Strait","variants":["regular"]},{"family":"Sue Ellen Francisco","variants":["regular"]},{"family":"Sumana","variants":["regular","700"]},{"family":"Sunshiney","variants":["regular"]},{"family":"Supermercado One","variants":["regular"]},{"family":"Sura","variants":["regular","700"]},{"family":"Suranna","variants":["regular"]},{"family":"Suravaram","variants":["regular"]},{"family":"Suwannaphum","variants":["regular"]},{"family":"Swanky and Moo Moo","variants":["regular"]},{"family":"Syncopate","variants":["regular","700"]},{"family":"Tangerine","variants":["regular","700"]},{"family":"Taprom","variants":["regular"]},{"family":"Tauri","variants":["regular"]},{"family":"Teko","variants":["300","regular","500","600","700"]},{"family":"Telex","variants":["regular"]},{"family":"Tenali Ramakrishna","variants":["regular"]},{"family":"Tenor Sans","variants":["regular"]},{"family":"Text Me One","variants":["regular"]},{"family":"The Girl Next Door","variants":["regular"]},{"family":"Tienne","variants":["regular","700","900"]},{"family":"Tillana","variants":["regular","500","600","700","800"]},{"family":"Timmana","variants":["regular"]},{"family":"Tinos","variants":["regular","italic","700","700italic"]},{"family":"Titan One","variants":["regular"]},{"family":"Titillium Web","variants":["200","200italic","300","300italic","regular","italic","600","600italic","700","700italic","900"]},{"family":"Trade Winds","variants":["regular"]},{"family":"Trocchi","variants":["regular"]},{"family":"Trochut","variants":["regular","italic","700"]},{"family":"Trykker","variants":["regular"]},{"family":"Tulpen One","variants":["regular"]},{"family":"Ubuntu","variants":["300","300italic","regular","italic","500","500italic","700","700italic"]},{"family":"Ubuntu Condensed","variants":["regular"]},{"family":"Ubuntu Mono","variants":["regular","italic","700","700italic"]},{"family":"Ultra","variants":["regular"]},{"family":"Uncial Antiqua","variants":["regular"]},{"family":"Underdog","variants":["regular"]},{"family":"Unica One","variants":["regular"]},{"family":"UnifrakturCook","variants":["700"]},{"family":"UnifrakturMaguntia","variants":["regular"]},{"family":"Unkempt","variants":["regular","700"]},{"family":"Unlock","variants":["regular"]},{"family":"Unna","variants":["regular"]},{"family":"VT323","variants":["regular"]},{"family":"Vampiro One","variants":["regular"]},{"family":"Varela","variants":["regular"]},{"family":"Varela Round","variants":["regular"]},{"family":"Vast Shadow","variants":["regular"]},{"family":"Vesper Libre","variants":["regular","500","700","900"]},{"family":"Vibur","variants":["regular"]},{"family":"Vidaloka","variants":["regular"]},{"family":"Viga","variants":["regular"]},{"family":"Voces","variants":["regular"]},{"family":"Volkhov","variants":["regular","italic","700","700italic"]},{"family":"Vollkorn","variants":["regular","italic","700","700italic"]},{"family":"Voltaire","variants":["regular"]},{"family":"Waiting for the Sunrise","variants":["regular"]},{"family":"Wallpoet","variants":["regular"]},{"family":"Walter Turncoat","variants":["regular"]},{"family":"Warnes","variants":["regular"]},{"family":"Wellfleet","variants":["regular"]},{"family":"Wendy One","variants":["regular"]},{"family":"Wire One","variants":["regular"]},{"family":"Work Sans","variants":["100","200","300","regular","500","600","700","800","900"]},{"family":"Yanone Kaffeesatz","variants":["200","300","regular","700"]},{"family":"Yantramanav","variants":["100","300","regular","500","700","900"]},{"family":"Yellowtail","variants":["regular"]},{"family":"Yeseva One","variants":["regular"]},{"family":"Yesteryear","variants":["regular"]},{"family":"Zeyada","variants":["regular"]}]}' );
		}

	}

	public static function fonts( $fonts ) {


		foreach ( $fonts as $key => $value) {

			asort( $value );
			$fonts[$key] = str_replace( ' ', '+', $key ) . ':'.implode( ',', $value );

		}

		$args = array(
			'family' => implode('%7C', $fonts),
			'subset' => self::get_font_subset()
		);

		$url = '//fonts.googleapis.com/css';
		$url = add_query_arg( $args, $url );

		return esc_url_raw( $url );

		return $fonts;

	}


	public static function web_fonts( $fonts ) {


		foreach ( $fonts as $key => $value) {

			asort( $value );
			$fonts[$key] = $key . ':'.implode( ',', $value ).":".self::get_font_subset();

		}

		return $fonts;

	}


	public static function fonts_array( $array, $font, $weight ) {

		if( empty( $font ) )
			return $array;

		$weight = $weight === 'regular' ? '400' : $weight;
		$weight = $weight === 'italic' ? '400italic' : $weight;

		if ( ! array_key_exists( $font, $array ) ) {
			$array[$font] = array($weight);
		} else {
			if ( ! in_array( $weight , $array[$font] ) ) {
				array_push( $array[$font], $weight );
			}
		}

		if( ! in_array( '400', $array[$font] ) ){
			array_push( $array[$font], '400' );
		}

		return $array;

	}

	public static function get_font_subset() {
		switch ( esc_attr( get_theme_mod( 'font_subset', 0 ) ) ){
			case 0 	: return 'latin'; break;
			case 1 	: return 'latin,cyrillic-ext,cyrillic'; break;
			case 2 	: return 'latin,greek-ext,greek'; break;
			case 3 	: return 'latin,greek'; break;
			case 4 	: return 'latin,vietnamese'; break;
			case 5 	: return 'latin,latin-ext'; break;
			case 5 	: return 'latin,cyrillic'; break;
		}
	}

}

$pirouette_typography = new PirouetteLoadFonts();

?>
