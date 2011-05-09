<?php
/**
 * Wrapper class for integrating the Faker vendor and the 'DummyData' CakePHP plugin 
 * 
 *
 * @author Ronny 'rvv' Vindenes
 * @author Alexander 'alkemann' Morland
 * @modified 10. feb. 2009
 * 
 */
include_once 'faker.php';
class DummyWrapper {
	// Holds the actual vendor main object. 
	private static $Faker = null;
	/**
	 * List of current generator classes.
	 * Expand this list when creating new classes. 
	 * Classes added here should be iincluded in one 
	 * of the tree list[Type]Generator methods bellow.
	 *
	 * @var array
	 * @access private
	 * @author Alexander Morland
	 */
	private static $generator_classes = array(
		'Address' => array(		
			'Uk',
			'Usa'
		),
		'Company' => array(),
		'English' => array(),
		'Lorem' => array(),
		'Name' => array(),
		'Number' => array(),
		'Time' => array(),
		'Web' => array()
	);

	/**
	 * Accesses the specified generator method and returns the random value
	 *
	 * @author Alexander Morland
	 * @param string $class
	 * @param string $method
	 * @param array $options
	 * @return mixed
	 */
	public static function generate($class, $method, $options = array()) {	
		if (is_null(self::$Faker) ) {
			self::$Faker = new Faker; 
		}
		return self::$Faker->$class->$method($options);
	}
	
	/**
	 * Returns array of all current generator classes
	 * If a $recursive value of FALSE is used, only first
	 * level classes will be returned.
	 *
	 * @author Alexander Morland
	 * @param boolean $recursive
	 * @return array
	 */
	public static function listClasses($recursive = true) {
		$ret = array();
		if ($recursive) {
			$ret =& self::$generator_classes;
		} else {
			$ret = array_keys(self::$generator_classes);
		}		
		return $ret;
	}

	/**
	 * Returns array list of all generators in the specified class
	 *
	 * @author Alexander Morland
	 * @param string $class
	 * @return array
	 */
	public static function listMethods($class) {
		if (is_null(self::$Faker) ) {
			self::$Faker = new Faker; 
		}
		$methods = get_class_methods(self::$Faker->$class);
		$ret = array();
		foreach ($methods as $one) {
			if (substr($one,0,2) != '__' && substr($one,0,8) != 'generate') {
				$ret[$class.'->'.$one] = $one;
			}
		}
		return $ret;
	}
	
	/**
	 * Returns list of all Number related generators, 
	 * grouped by class. Only Number class exist at this time.
	 *
	 * @author Alexander Morland
	 * @return array
	 */
	public static function listNumberGenerators() {
		return array(
			'Number' => self::listMethods('Number')
		);
	}
	
	/**
	 * Returns list of all date and time related generators, 
	 * grouped by class. Only Time class exist at this time.
	 *
	 * @author Alexander Morland
	 * @return array
	 */
	public static function listTimeGenerators() {
		return array('Time' => self::listMethods('Time') );
	}
	
	/**
	 * Returns list of all string related generators, 
	 * grouped by class. 
	 *
	 * @author Alexander Morland
	 * @return array
	 */
	public static function listStringGenerators() {
		$ret = array(
			'Name' => self::listMethods('Name'),
			'English' => self::listMethods('English'),
			'Web' => self::listMethods('Web'),
			'Company' => self::listMethods('Company'),
			'Address' => self::listMethods('Address'),
			'Uk' => self::listMethods('Uk'),
			'Usa' => self::listMethods('Usa'),
			'Lorem' => self::listMethods('Lorem')
		);
		return $ret;
	}

	/**
	 * Returns list of generator classes that extend the given class
	 *
	 * @example $address_classes = DummyWrapper::listSubClasses('Address');
	 * @param string $class
	 * @return array
	 */
	public static function listSubClasses($class) {
		return self::$generator_classes[$class];
	}
		
}
?>