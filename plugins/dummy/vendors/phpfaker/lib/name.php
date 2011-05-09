<?php

/**
* Name Class
* 
* @package faker
*/
App::import('vendor', 'dummy.phpfaker/lib/dummy_data');
class Name extends Faker
{
	/**
	 * Do nothing on being instanced
	 *
	 * @return void
	 * @author Caius Durling
	 */
	 
	private static $_formats = array(array('first_name','surname'),array('first_name','surname'),array('first_name','surname'),array('first_name','surname'),array('first_name','surname'),array('prefix','first_name','surname'),array('first_name','surname','suffix'),array('prefix','first_name','surname','suffix'));
	
	private static $_prefix = array('Mr.','Mrs.','Ms.','Miss','Dr.');
	
	private static $_suffix = array('Jr.','Sr.','I','II','III','IV','V','MD','DDS','PhD','DVM');
	/**/
	public function __construct()
	{
	}
	
	public function __get($var)
	{
		return $this->$var();
	}
	
	public function name()
	{
		$a = parent::random( self::$_formats );
					
		foreach ($a as $method) {
			$b[] = $this->$method;
		}
		$result = join($b, " ");
		
		return $result;
	}
	
	public function full_name($options = array()) {
		$dev = (isset($options['devider'])) ? $options['devider'] : ' ';
		return self::firstname($options) . $dev . self::surname($options);
	}
	
	public function firstname($options = array()) {
		return self::first_name($options);
	}
	
	public function first_name($options = array()) {
		$first_names =& DummyData::get_firstnames();
		if ((isset($options['single']) && $options['single']) || (isset($options['variable']) && $options['variable'] == 'single'))
			return self::random_name( $first_names );
			
		$dev = (isset($options['devider'])) ? $options['devider'] : ' ';
		$ret = self::random_name( $first_names );
		if (rand(1, 10) < 4)
			$ret .= $dev . self::random_name( $first_names );
		if (rand(1, 10) < 1)
			$ret .= $dev . self::random_name( $first_names );
		return $ret;
	}	
	
	public function surname($options = array()) {
		$surnames =& DummyData::get_surnames();
		if ((isset($options['single']) && $options['single']) || (isset($options['variable']) && $options['variable'] == 'single'))
			return parent::random( $surnames );
			
		$dev = (isset($options['devider'])) ? $options['devider'] : ' ';
		$ret = parent::random( $surnames );
		if (rand(1, 10) < 3)
			$ret .= $dev . parent::random( $surnames );
		if (rand(1, 10) < 1)
			$ret .= $dev . parent::random( $surnames );
		return $ret;
	}
	
	public function prefix()
	{
		return parent::random( self::$_prefix );
	}
	
	public function suffix()
	{
		return parent::random( self::$_suffix );
	}	
	
	private static function random_name(& $array) {
		$res =  parent::random($array);
		return $res[0];
	}
}

?>