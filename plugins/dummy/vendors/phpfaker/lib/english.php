<?php
/**
 * Library class for dummy texts in english
 * 
 * @author Alexander 'alkemann' Morland
 * @modified 6. feb. 2009
 * 
 */
App::import('vendor', 'dummy.phpfaker/lib/dummy_data');
class English extends Faker {
	
	public function __construct() {
	}
	
	public function __get($var) {
		return $this->$var();
	}
	
	public static function city($options = array()) {
		$cities = &DummyData::get_cities();
		return $cities[rand(0, count($cities) - 1)];
	}
	
	public static function color($options = array()) {
		$colors = &DummyData::get_colors();
		return $colors[rand(0, count($colors) - 1)];
	}
	public static function title($options = array()) {
		$max = (isset($options['max'])) ? $options['max'] : 255;
		$nouns = DummyData::getNouns();
		$noun = $nouns[rand(0, count($nouns) - 1)];
		if ($max < 10) {
			return ucfirst($noun);
		}
		$adjectives = DummyData::getAdjectives();
		$adj_count = count($adjectives);
		$adj = $adjectives[rand(0, $adj_count - 1)];
		$adj = ucfirst($adj);
		if ($max < 25) {
			return $adj . ' ' . $noun;
		}
		if ($max > 150 && rand(0, 2) == 1) {
			$adj2 = $adjectives[rand(0, $adj_count - 1)];
			$adj .= ' ' . $adj2;
		}
		
		if ($max > 200 && rand(0, 4) == 1) {
			$adj2 = $adjectives[rand(0, $adj_count - 1)];
			$adj .= ' ' . $adj2;
		}
		return $adj . ' ' . $noun;
	}
	
	public static function noun($options = array()) {
		$nouns = DummyData::getNouns();
		$noun = $nouns[rand(0, count($nouns) - 1)];
		return ucfirst($noun);
	}
	
	public static function verb($options = array()) {
		$verbs = DummyData::getVerbs();
		$verb = $verbs[rand(0, count($verbs) - 1)];
		return $verb;
	}
	
	public static function quote($options = array()) {
		$quotes = DummyData::getQuotes();
		return $quotes[rand(0, count($quotes) - 1)];
	}
	
	public static function extension($options = array()) {
		$extensions = DummyData::get_file_extension();
		$extension = $extensions[rand(0, count($extensions) - 1)];
		return $extension;
	}
	
	public static function filename($options = array()) {
		$extensions = DummyData::get_file_extension();
		$extension = $extensions[rand(0, count($extensions) - 1)];
		return low(self::noun($options) . '.' . self::extension($options));
	}	
}
?>