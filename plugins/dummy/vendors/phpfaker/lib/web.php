<?php
/**
 * Library class for generating web related data.
 * 
 *
 * @author Caius Durling
 * @author Alexander 'alkemann' Morland
 * @modified 6. feb. 2009
 * 
 */
class Web extends Faker {
	private static $_domain_suffix = array('co.uk','com','us','org','ca','biz','info','name','no');
	
	public function __construct(){}
	
	public function __get($var) {
		return $this->$var();
	}

	public static function email($options = array()) {
		if (isset($options['variable']) && is_string($options['variable'])) {
			$name = low(str_replace(' ', '.', $options['variable']));
			return $name . '@example.com';
		}
		if (isset($options['variable']) && is_array($options['variable'])) {
			$name = low(str_replace(' ', '.', implode('.', $options['variable'])));
			return $name . '@example.com';
		}
		$options['devider'] = '.';
		$f = new Faker;
		$Name = $f->Name;
		return strtolower($Name->first_name($options) . '.' . $Name->surname($options) . '@example.com');
	}
	
	public static function username($options = array()) {
		if (isset($options['variable'])) {
			$name = explode(' ', $options['variable']);
			$name = low($name[0]);
			return $name . self::generate_random_alphanumeric_str('xx');
		}
		$f = new Faker;
		$Name = $f->Name;
		$fname = strtolower($Name->first_name(array('single' => true)));
		return $fname . self::generate_random_alphanumeric_str('xx');
	}
	
	public static function password($options = array()) {
		return md5('pass');
	}
	
	public static function url($options = array()) {
		App::import('vendor', 'dummy.phpfaker/lib/dummy_data');
		$urls = &DummyData::get_urls();
		return $urls[rand(0, count($urls) - 1)];
	}
	
	/**
 	 * @author Caius Durling
	 * @return string
	 */
	public function domain_suffix()
	{
		return parent::random( self::$_domain_suffix );
	}
	
	/**
 	 * @author Caius Durling
	 * @return string
	 */
	public function domain_word()
	{
		$result = explode( ' ', parent::__get('Company')->name );
		$result = $result[0];
		$result = strtolower( $result );
		$result = preg_replace( "/\W/", '', $result );
		return $result;
	}
	
	/**
 	 * @author Caius Durling
	 * @return string
	 */
	public function domain_name()
	{
		$result[] = $this->domain_word;
		$result[] = $this->domain_suffix;
		return join( $result, '.' );
	}	
	/**
	 * Generates HTML content for a string. Adding some html formating code to the content
	 * $options min_size and max
	 * 
	 * @param array $options
	 * @return String
	 */
	public static function html($options = array()) {
		$min_size = isset($options['min_size']) ? $options['min_size'] : 1;
		$max = isset($options['max']) ? $options['max'] : 255;
		$loremLarge = array(
				' dolor sit amet, consectetuer adipiscing elit.', 
				' phasellus suscipit, quam id pretium luctus, nulla lectus tempor odio, in ultricies est purus at lacus. Suspendisse potenti. Phasellus viverra laoreet mi. Cras aliquam orci vel justo. Morbi sit amet felis ac massa feugiat fermentum. Nulla elementum faucibus nisi. Mauris viverra, arcu ornare accumsan vestibulum, turpis pede rhoncus odio, at ultrices neque nisi scelerisque magna. Aenean eu neque. Vivamus quis mi quis dui fringilla consequat. Nulla libero purus, laoreet a, pretium vestibulum, dapibus a, enim. Phasellus et nisi.', 
				' hendrerit ligula nec magna. Sed tempus est nec lacus. Nunc urna metus, vulputate rutrum, tincidunt et, tincidunt eget, quam. Sed non ante. Integer elementum orci nec sem. Ut ac ante sed massa consectetuer aliquam. Sed velit ligula, cursus eget, dignissim quis, tempus sed, lorem. Pellentesque purus. Nunc id mauris. Donec dui. Mauris quis mauris sodales elit tincidunt feugiat. Etiam bibendum, sapien vitae ullamcorper suscipit, nulla metus convallis lacus, vitae aliquam lectus dui eu tellus. Nullam lobortis sollicitudin nulla. Aliquam erat volutpat. Integer tristique. Suspendisse eros. Sed venenatis facilisis lectus. Sed sed velit.', 
				' dictum, nisl vitae malesuada lobortis, diam risus eleifend pede, eget fringilla nibh leo in ligula. Integer ac quam at dolor placerat adipiscing. Mauris gravida commodo urna. Nunc hendrerit. Ut sit amet leo quis velit pellentesque posuere. Ut mollis ligula a nunc. Maecenas commodo, augue vitae sollicitudin auctor, arcu ligula accumsan nunc, vitae consequat leo purus sit amet ante. Aenean tempor nunc non massa. Praesent nonummy ornare felis. Etiam vel lectus sit amet eros commodo pellentesque. Nunc auctor sodales libero. Morbi massa.', 
				' pulvinar. Mauris consequat, massa non accumsan fringilla, urna libero laoreet risus, at luctus quam quam at enim. Aliquam erat volutpat. Vivamus eu sem sed dui placerat consectetuer. Vestibulum semper augue et nunc. Nunc lobortis enim sit amet erat. Etiam aliquet enim quis massa. Sed libero augue, dapibus non, vulputate ac, nonummy eget, metus. Donec tempus consectetuer ligula. Pellentesque posuere nisl. Mauris mi risus, tempor in, congue dictum, venenatis nec, odio. Nunc ante metus, interdum a, vulputate et, faucibus sed, justo.', 
				' euismod diam et sapien. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos hymenaeos. Mauris eget nunc. In venenatis fringilla purus. Donec imperdiet, ipsum id posuere egestas, ligula ante porta neque, ut tempus neque nulla in nisl. Nulla vulputate tellus nec nulla. Nunc ut ipsum. Fusce consequat purus ut pede. Pellentesque augue elit, pulvinar ac, pulvinar id, blandit vitae, turpis. Vestibulum mattis convallis metus. In faucibus tortor sed risus. Integer feugiat mauris et urna. Integer laoreet sagittis neque. Cras id ante id ipsum congue aliquet. Suspendisse potenti. Duis est justo, euismod ac, aliquet a, tempor et, turpis. Donec tristique viverra tellus.', 
				' eget magna et odio tempor viverra. Phasellus hendrerit libero id quam. Integer eu massa sed nulla elementum vestibulum. Quisque eu pede. Quisque vestibulum. Aenean congue eros sit amet felis. Proin ullamcorper vulputate nunc. Donec ultricies eros non erat. Aliquam ultrices mi sed arcu. Maecenas pharetra. Etiam venenatis. Fusce id nunc. Phasellus ut lacus. Sed vitae nibh. Nulla ac nunc quis erat ullamcorper varius. Duis condimentum risus vitae nisl. Fusce nunc nulla, tincidunt id, aliquet sed, consequat eget, enim.');
		$loremSmall = array(
				' dolor sit amet, consectetuer adipiscing elit.', 
				' phasellus suscipit, quam id pretium luctus, nulla lectus tempor odio, in ultricies est purus at lacus.', 
				' suspendisse potenti. Phasellus viverra laoreet mi.', 
				' cras aliquam orci vel justo. Morbi sit amet felis ac massa feugiat fermentum.', 
				' nulla elementum faucibus nisi.', 
				' mauris viverra, arcu ornare accumsan vestibulum, turpis pede rhoncus odio.', 
				' aenean eu neque.', 
				' vivamus quis mi quis dui fringilla consequat.', 
				' nulla libero purus, laoreet a, pretium vestibulum, dapibus a, enim. Phasellus et nisi.', 
				' hendrerit ligula nec magna. Sed tempus est nec lacus.', 
				' nunc urna metus, vulputate rutrum, tincidunt et, tincidunt eget, quam. Sed non ante.', 
				' integer elementum orci nec sem. Ut ac ante sed massa consectetuer aliquam.', 
				' sed velit ligula, cursus eget, dignissim quis, tempus sed, lorem. Pellentesque purus.', 
				' nunc id mauris. Donec dui. Mauris quis mauris sodales elit tincidunt feugiat.', 
				' etiam bibendum, sapien vitae ullamcorper suscipit, nulla eu tellus.', 
				' nullam lobortis sollicitudin nulla. Aliquam erat volutpat.', 
				' integer tristique. Suspendisse eros. Sed venenatis facilisis lectus. Sed sed velit.', 
				' dictum, nisl vitae malesuada lobortis, diam risus eleifend pede, eget fringilla nibh leo in ligula.', 
				' integer ac quam at dolor placerat adipiscing. Mauris gravida commodo urna.', 
				' nunc hendrerit. Ut sit amet leo quis velit pellentesque posuere.');
		$loremWords = array(
				'proin', 
				'volutpat', 
				'lectus', 
				'sed', 
				'dui', 
				'maecenas', 
				'venenatis', 
				'commodo', 
				'nibh', 
				'vivamus', 
				'viverra', 
				'cursus', 
				'risus', 
				'praesent', 
				'is', 
				'sapien', 
				'at', 
				'nunc', 
				'sodales', 
				'laoreet', 
				'aenean', 
				'vel', 
				'massa', 
				'et', 
				'mauris', 
				'cursus', 
				'elementum', 
				'etiam', 
				'magna', 
				'eget', 
				'urna', 
				'euismod', 
				'ornare');
		$htmlSetOn = array(
				'<p>', 
				'<b>', 
				'<i>', 
				'<h1>', 
				'<h2>', 
				'<h3>', 
				'<h4>', 
				'<h5>', 
				'<div>', 
				'<span>', 
				'<left>', 
				'<right>', 
				'<center>', 
				'<strong>', 
				'<big>', 
				'<em>', 
				'<a href="" alt="">');
		$htmlSetOff = array(
				'</p>', 
				'</b>', 
				'</i>', 
				'</h1>', 
				'</h2>', 
				'</h3>', 
				'</h4>', 
				'</h5>', 
				'</div>', 
				'</span>', 
				'</left>', 
				'</right>', 
				'</center>', 
				'</strong>', 
				'</big>', 
				'</em>', 
				'</a>');
		
		$htmlLength = rand($min_size, $max);
		// Select the array to use, for small html parts use the small one
		$loremArrayToUse = ($htmlLength < 500 ? $loremSmall : $loremLarge);
		
		$returnString = '';
		// Less than 20 char don't format
		if ($htmlLength < 20) {
			$returnString = $loremSmall[rand(0,sizeof($loremSmall))-1];
		}
		
		$htmlSet = rand(0, sizeof($htmlSetOn) - 1);
		
		$returnString = $htmlSetOn[$htmlSet];
		$returnString .= ucfirst($loremWords[rand(0, sizeof($loremWords) - 1)] . ' ' . $loremWords[rand(0, sizeof($loremWords) - 1)]);
		$returnString .= $htmlSetOff[$htmlSet];
		$bufferedString = '';
		
		$charCount = strlen($returnString);
		while ($charCount < $htmlLength) {
			$returnString .= $bufferedString;
			$htmlSet = rand(0, sizeof($htmlSetOn) - 1);
			
			$bufferedString = $htmlSetOn[$htmlSet];
			$bufferedString .= ucfirst($loremWords[rand(0, sizeof($loremWords) - 1)] . ' ' . $loremWords[rand(0, sizeof($loremWords) - 1)]);
			$bufferedString .= $htmlSetOff[$htmlSet];
			
			$htmlSet = rand(0, sizeof($htmlSetOn) - 1);
			$bufferedString .= $htmlSetOn[$htmlSet];
			$bufferedString .= ucfirst($loremWords[rand(0, sizeof($loremWords) - 1)] . ' ' . $loremWords[rand(0, sizeof($loremWords) - 1)]);
			$bufferedString .= $htmlSetOff[$htmlSet];
			$htmlSet = rand(0, sizeof($htmlSetOn) - 1);
			$bufferedString .= $htmlSetOn[$htmlSet];
			$bufferedString .= $loremArrayToUse[rand(0, sizeof($loremArrayToUse) - 1)];
			$bufferedString .= $htmlSetOff[$htmlSet];
			$charCount = (strlen($returnString) + strlen($bufferedString));
		}
		return $returnString;
	}	
}
?>