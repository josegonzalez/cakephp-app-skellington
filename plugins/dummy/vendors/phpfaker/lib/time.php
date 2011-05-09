<?php
/**
 * Library class for date and time related data
 * 
 * @author Ronny 'rvv' Vindenes
 * @author Alexander 'alkemann' Morland
 * @modified 6. feb. 2009
 * 
 */
class Time extends Faker {

	public function __construct()
	{
	}
	
	public function __get( $var )
	{
		return $this->$var();		
	}
	
	public static function date($options = array()) {
		$options = am(array('variable'=>null,'min'=>'','max'=>''),$options);
		switch ($options['variable']) {
			case 'now' :
				return date('Y-m-d');
			break;
			case 'future' :
				$min_timestamp = time() + (60*60*24);
				if ($options['max'] != '') {
					$max_timestamp = strtotime($options['max']);
				} else {
					$max_timestamp = time() + (60*60*24*7*52);
				}	
			break;
			case 'past' :
				$max_timestamp = time() - (60*60*24);
				if ($options['min'] != '') {
					$min_timestamp = strtotime($options['min']);
				} else {
					$min_timestamp = time() - (60*60*24*7*52);
				}	
			break;
			default:
				if ($options['max'] != '') {
					$max_timestamp = strtotime($options['max']);
				} else {
					$max_timestamp = time() + (60*60*24*7*52);
				}			
				if ($options['min'] != '') {
					$min_timestamp = strtotime($options['min']);
				} else {
					$min_timestamp = time() - (60*60*24*7*52);
				}					
		}
		$timestamp = rand($min_timestamp,$max_timestamp);
		return date('Y-m-d',$timestamp);
	}
	
	public static function time($options = array()) {	
		if (isset($options['variable']) && $options['variable'] == 'now') {
			return date('H:i:s');
		}			
		if (isset($options['max']) && $options['max'] != '') {
			$arr = explode(' ',$options['max']);
			if (sizeof($arr) > 1) {
				$max = '1970-01-01 '.$arr[1];
			} else {
				$max = $options['max'];
			}
			$max_timestamp = strtotime($max);
		} else {
			$max_timestamp = strtotime('23:59:59');
		}			
		if (isset($options['min']) && $options['min'] != '') {
			$arr = explode(' ',$options['min']);
			if (sizeof($arr) > 1) {
				$min = '1970-01-01 '.$arr[1];
			} else {
				$min = $options['min'];
			}
			$min_timestamp = strtotime($min);
		} else {
			$min_timestamp = strtotime('00:00:00');
		}	
		$timestamp = rand($min_timestamp, $max_timestamp);
		return date('H:i:s',$timestamp);
	}
	
	public static function datetime($options = array()) {
		return self::date($options) . ' ' . self::time($options);
	}
	
	public static function timestamp($options = array()) {
		if (isset($options['variable']) && $options['variable'] == 'now') {
			return time();
		}
		return rand(0,time());
	}

	public static function year($options = array()) {
		$now = date('Y');
		$min = (isset($options['min'])) ? $options['min'] : $now - 75;
		$max = (isset($options['max'])) ? $options['max'] : $now;
		if (isset($options['variable'])) 
			switch ($options['variable']) {
				case 'now' :
					return $now;
				break;
				case 'future' :
					if ($now+1 >= $max) return $max;
					return rand($now+1,$max);
				break;
				case 'past' :
					if ($now-1 <= $min) return $min;
					return rand($min,$now-1);
				break;
				default:
					
			}
		return rand($min,$max);
	}
	public static function month($options = array()) {		
		$min = (isset($options['min'])) ? $options['min'] : 1;
		$max = (isset($options['max'])) ? $options['max'] : 12;
		if (isset($options['variable'])) 
			switch ($options['variable']) {
				case 'now' :
					return date('n');
				break;
				case 'future' :
					$now = date('n');
					if ($now+1 >= $max) return $max;
					return rand($now+1,$max);
				break;
				case 'past' :
					$now = date('n');
					if ($now-1 <= $min) return $min;
					return rand($min,$now-1);
				break;
				default:
					
			}
		return rand($min,$max);
	}
	public static function day($options = array()) {
		$min = (isset($options['min'])) ? $options['min'] : 1;
		$max = (isset($options['max'])) ? $options['max'] : 28;
		if (isset($options['variable'])) 
			switch ($options['variable']) {
				case 'now' :
					return date('j');
				break;
				case 'future' :
					$now = date('j');
					$max = date('t');
					if ($now+1 >= $max) return $max;
					return rand($now+1,$max);
				break;
				case 'past' :
					$now = date('j');
					if ($now-1 <= $min) return $min;
					return rand($min,$now-1);
				break;
				default:
					
			}
		return rand($min,$max);
	}
	
	
	
}

?>