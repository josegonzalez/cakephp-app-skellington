<?php

/**
 * Library class for adress related data
 *
 *
 * @author Caius Durling
 * @author Alexander 'alkemann' Morland
 * @modified 6. feb. 2009
 *
 */
class Address extends Faker
{

	private static $_street_suffix = array('Alley','Avenue','Branch','Bridge','Brook','Brooks','Burg','Burgs','Bypass','Camp','Canyon','Cape','Causeway','Center','Centers','Circle','Circles','Cliff','Cliffs','Club','Common','Corner','Corners','Course','Court','Courts','Cove','Coves','Creek','Crescent','Crest','Crossing','Crossroad','Curve','Dale','Dam','Divide','Drive','Drive','Drives','Estate','Estates','Expressway','Extension','Extensions','Fall','Falls','Ferry','Field','Fields','Flat','Flats','Ford','Fords','Forest','Forge','Forges','Fork','Forks','Fort','Freeway','Garden','Gardens','Gateway','Glen','Glens','Green','Greens','Grove','Groves','Harbor','Harbors','Haven','Heights','Highway','Hill','Hills','Hollow','Inlet','Inlet','Island','Island','Islands','Islands','Isle','Isle','Junction','Junctions','Key','Keys','Knoll','Knolls','Lake','Lakes','Land','Landing','Lane','Light','Lights','Loaf','Lock','Locks','Locks','Lodge','Lodge','Loop','Mall','Manor','Manors','Meadow','Meadows','Mews','Mill','Mills','Mission','Mission','Motorway','Mount','Mountain','Mountain','Mountains','Mountains','Neck','Orchard','Oval','Overpass','Park','Parks','Parkway','Parkways','Pass','Passage','Path','Pike','Pine','Pines','Place','Plain','Plains','Plains','Plaza','Plaza','Point','Points','Port','Port','Ports','Ports','Prairie','Prairie','Radial','Ramp','Ranch','Rapid','Rapids','Rest','Ridge','Ridges','River','Road','Road','Roads','Roads','Route','Row','Rue','Run','Shoal','Shoals','Shore','Shores','Skyway','Spring','Springs','Springs','Spur','Spurs','Square','Square','Squares','Squares','Station','Station','Stravenue','Stravenue','Stream','Stream','Street','Street','Streets','Summit','Summit','Terrace','Throughway','Trace','Track','Trafficway','Trail','Trail','Tunnel','Tunnel','Turnpike','Turnpike','Underpass','Union','Unions','Valley','Valleys','Via','Viaduct','View','Views','Village','Village','Villages','Ville','Vista','Vista','Walk','Walks','Wall','Way','Ways','Well','Wells');
	private static $_cities = array(
	    'Shanghai', 'Mumbai (Bombay)', 'Buenos Aires', 'Moscow', 'Karachi', 'Delhi', 'Manila', 'Sao Paulo', 'Seoul', 'Istanbul', 'Jakarta', 'Mexico City', 'Lagos', 'Lima', 'Tokyo', 'New York City', 'Cairo', 'London', 'Teheran', 'Beijing',
	);
	private static $_street_name_formats = array('first_name','surname');

	public function __construct()
	{
	}

	public function __get( $var )
	{
		return $this->$var();
	}

	public function phone($options = array()) {
		$syntax = isset($options['variable']) ? $options['variable'] : '(47) Xx xx xx xx';
		return self::generate_random_num_str($syntax);
	}

	public function street_suffix() {
		return parent::random( self::$_street_suffix );
	}

	public function city() {
		return parent::random( self::$_cities );
	}

	public function street_name() {
		$method = parent::random( self::$_street_name_formats );
		$result[] = parent::__get('Name')->$method;
		$result[] = self::street_suffix();
		return implode($result, " ");
	}

	public function street_address() {
		return parent::generate_random_alphanumeric_str( implode( " ", array( 'xxxxx' , self::street_name() ) ) );
	}

	public function abode_address( $include_street=false ) {
		if ( $include_street ) {
			$str[] = 'xxxxx';
		}
		$formats = array(
				'Apt. Xxx',
				'Suite Xxx'
			);
		$str[] = parent::random( $formats );
		if ( $include_street ) {
			$str[] = self::street_name();
		}
		return parent::generate_random_alphanumeric_str( implode( " ", $str ) );
	}

	public function post_code($options = array()) {
		return self::zip_code($options);
	}

	public function zip_code($options = array()) {
		if (isset($options['variable'])) {
			$a = $options['variable'];
		} else {
			$a = 'Xxxx';
		}
		$result = parent::generate_random_alphanumeric_str( $a );
		return $result;
	}



}

?>