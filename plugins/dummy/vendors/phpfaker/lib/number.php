<?php
/**
 * Library class for number related data
 * 
 *
 * @author Ronny 'rvv' Vindenes
 * @author Eskil Saadtvedt
 * @author Alexander 'alkemann' Morland
 * @modified 6. feb. 2009
 * 
 */
class Number extends Faker {
	public function __construct() {	}
	public function __get($var) { return $this->$var();	}
	

	public static function integer($options = array()) {
		//		A normal-size integer. The signed range is -2147483648 to 2147483647. The unsigned range is 0 to 4294967295.
		return self::createInt(4294967295, $options);
	}
		
	public static function tinyInt($options = array()) {
		//	A very small integer. The signed range is -128 to 127. The unsigned range is 0 to 255.
		return self::createInt(255, $options);
	}
	public static function boolean($options = array()) {
		return rand(0, 1);
	}
	public static function mediumInt($options = array()) {
		//		A medium-sized integer. The signed range is -8388608 to 8388607. The unsigned range is 0 to 16777215.
		return self::createInt(16777215, $options);
	}
	public static function smallInt($options = array()) {
		//		A small integer. The signed range is -32768 to 32767. The unsigned range is 0 to 65535.	
		return self::createInt(65535, $options);
	}
	public static function bigInt($options = array()) {
		//		A large integer. The signed range is -9223372036854775808 to 9223372036854775807. The unsigned range is 0 to 18446744073709551615 = 2^64 -1.
		// 		Dependant on underlying systems if using bigint in php, might differ from the mysql definition above.
		//		There is a number of known issues with BIGINT in PHP, so don not use it.
		

		$whatToDo = rand(1, 10);
		if ($whatToDo < 5) { // 40% chance to return a small number
			return rand(1, 200);
		} else if ($whatToDo < 6) { // 20% to return a slightly larger number
			return rand(1, 32768);
		}
		
		// 40% to return a very large number
		$bigFNumber = rand(0, 32767) * rand(1, 32768) * rand(1, 32768) * rand(1, 32768) * rand(1, 4); // max 2^63
		return sprintf("%.0f", $bigFNumber);
	}
	
	/**
	 * Generates a Floatingpoint number
	 *
	 * $options ['variable'] sets the display
	 * "%.8e"  output 8.1234567E-21
	 * "%.32f"  output  539588280000.00000000000000000000000000000000
	 * "%01.2f" output 123.00 typical  monney with two desimals
	 * "%.3e" output 3.142E+0 Scientific presession with 3 desimals
	 * 
	 * @param Array $options
	 * @return Stringrepresentation of the float
	 * 
	 */
	public static function float($options = array()) {
		//		A small (single-precision) floating-point number. Allowable values are -3.402823466E+38 to -1.175494351E-38, 0, and 1.175494351E-38 to 3.402823466E+38. 
		//		These are the theoretical limits, based on the IEEE standard. The actual range might be slightly smaller depending on your hardware or operating system.

		$numberOfDecimals = 9; // max 9
		$exponentMin = 38;
		$exponentMax = 38;
		
		$numberString = rand((isset($options['min'])) ? $options['min'] : 0, (isset($options['max'])) ? $options['max'] : 9) . '.';
		
		for ($i = 0; $i < $numberOfDecimals; $i++) {
			$numberString .= rand(0, 9);
		}
		
		$myFloat = floatval($numberString);
		
		$syntax = ((isset($options['variable']) && $options['variable'] != NULL) ? $options['variable'] : "%." . ($numberOfDecimals + 1) . "e");
		return sprintf($syntax, $myFloat); // 8.123456789E-21
	}
	
	public static function double($options = array()) {
		return 1;
	}
	
	public static function decimal($options = array()) {
		return 1;
	}	
	/**
	 * Return an integer
	 **
	 * 
	 * @param int $max maximum value, used to set the max for the different int types, 255 for tinyInt, 65535 for smallInt,  16777215 MediumInt, 4294967295 Int
	 * @param Array $options, the options in use is $options ['default'], $options ['max'], $options ['min'], $options ['unsinged']
	 * @return Int
	 */
	public static function createInt($max, $options = array()) {
		$smallMax = (($max + 1) / 2) - 1;
		if (isset($options['max']) || isset($options['min'])) {
			$max = isset($options['max']) ? $options['max'] : $smallMax;
			$min = isset($options['min']) ? $options['min'] : 0;
			return mt_rand($min, $max);
		} elseif (isset($options['unsigned'])) {
			if ($options['unsigned']) {
				return mt_rand(0, $max);
			} else {
				return mt_rand(0, $max) - $smallMax;
			}
		}
		return mt_rand(0, $smallMax);
	}	
}
?>