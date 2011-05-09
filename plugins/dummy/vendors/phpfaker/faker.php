<?php

/**
 * Class for generating fake data
 * 
 * This version of faker has significantly broken off from original project, but
 * it's methods are highly inspired by the Php-Faker by Caius Durling.
 * 
 * @license MIT
 * @author Caius Durling
 * @author ifunk
 * @author FionaSarah
 * @author Alexander 'alkemann' Morland
 * @version 2.0
 * @modified 6. feb 2009
 */
class Faker
{
	
	public static $_instances = array();
	
	public function __construct()
	{
	}
	
	public function __tostring() {
		return "";
	}
		
	public function &__get( $var ){
		if (empty(self::$_instances[$var])) {
			$filename = "lib/".strtolower($var).".php";
			include $filename;
			self::$_instances[$var] = new $var;

		}
		return self::$_instances[$var];
	}
	
	// todo: use __autoload()
	
	/**
	 * Returns a random element from a passed array
	 *
	 * @param array $array 
	 * @return string
	 * @author Caius Durling
	 */	
	protected function random(&$array)
	{
		return $array[mt_rand(0, count($array)-1)];
	}
	
	/**
	 * Returns a random number between 0 and 9
	 *
	 * @return integer
	 * @author Caius Durling
	 */
	protected function rand_num()
	{
		return mt_rand(0, 9);
	}
	
	/**
	 * Returns a random letter from a to z
	 *
	 * @return string
	 * @author Caius Durling
	 */
	protected function rand_letter()
	{
		return chr(mt_rand(97, 122));
	}

	public static function generate_random_num_str($str) {
		// loop through each character and convert all unescaped X's to 1-9 and 
		// unescaped x's to 0-9.
		$new_str = "";
		for ($i = 0; $i < strlen($str); $i++) {
			if ($str[$i] == '\\' && ($str[$i + 1] == "X" || $str[$i + 1] == "x"))
				continue;
			else if ($str[$i] == "X") {
				if ($i != 0 && ($str[$i - 1] == '\\'))
					$new_str .= "X";
				else
					$new_str .= rand(1, 9);
			} else if ($str[$i] == "x")
				if ($i != 0 && ($str[$i - 1] == '\\'))
					$new_str .= "x";
				else
					$new_str .= rand(0, 9);
			else
				$new_str .= $str[$i];
		}
		
		return trim($new_str);
	}
	public static function generate_random_alphanumeric_str($str) {
		$letters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$consonants = "BCDFGHJKLMNPQRSTVWXYZ";
		$vowels = "AEIOU";
		
		// loop through each character and convert all unescaped X's to 1-9 and 
		// unescaped x's to 0-9.
		$new_str = "";
		for ($i = 0; $i < strlen($str); $i++) {
			switch ($str[$i]){
				// Numbers
				case "X":
					$new_str .= rand(1, 9);
				break;
				case "x":
					$new_str .= rand(0, 9);
				break;
				
				// Letters
				case "L":
					$new_str .= $letters[rand(0, strlen($letters) - 1)];
				break;
				case "l":
					$new_str .= strtolower($letters[rand(0, strlen($letters) - 1)]);
				break;
				case "D":
					$bool = rand() & 1;
					if ($bool)
						$new_str .= $letters[rand(0, strlen($letters) - 1)];
					else
						$new_str .= strtolower($letters[rand(0, strlen($letters) - 1)]);
				break;
				
				// Consonants
				case "C":
					$new_str .= $consonants[rand(0, strlen($consonants) - 1)];
				break;
				case "c":
					$new_str .= strtolower($consonants[rand(0, strlen($consonants) - 1)]);
				break;
				case "E":
					$bool = rand() & 1;
					if ($bool)
						$new_str .= $consonants[rand(0, strlen($consonants) - 1)];
					else
						$new_str .= strtolower($consonants[rand(0, strlen($consonants) - 1)]);
				break;
				
				// Vowels
				case "V":
					$new_str .= $vowels[rand(0, strlen($vowels) - 1)];
				break;
				case "v":
					$new_str .= strtolower($vowels[rand(0, strlen($vowels) - 1)]);
				break;
				case "F":
					$bool = rand() & 1;
					if ($bool)
						$new_str .= $vowels[rand(0, strlen($vowels) - 1)];
					else
						$new_str .= strtolower($vowels[rand(0, strlen($vowels) - 1)]);
				break;
				
				default:
					$new_str .= $str[$i];
				break;
			}
		}
		
		return trim($new_str);
	}	
}
?>