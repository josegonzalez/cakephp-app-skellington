<?php
/**
 * Library class for united kingdom related address data
 * 
 *
 * @author Caius Durling
 * @author Alexander 'alkemann' Morland
 * @modified 6. feb. 2009
 * 
 */
include_once 'address.php';
class Uk extends Address {
	private static $_uk_counties = array('Avon','Bedfordshire','Berkshire','Borders','Buckinghamshire','Cambridgeshire','Central','Cheshire','Cleveland','Clwyd','Cornwall','CountyAntrim','CountyArmagh','CountyDown','CountyFermanagh','CountyLondonderry','CountyTyrone','Cumbria','Derbyshire','Devon','Dorset','DumfriesandGalloway','Durham','Dyfed','EastSussex','Essex','Fife','Gloucestershire','Grampian','GreaterManchester','Gwent','GwyneddCounty','Hampshire','Herefordshire','Hertfordshire','HighlandsandIslands','Humberside','IsleofWight','Kent','Lancashire','Leicestershire','Lincolnshire','Lothian','Merseyside','MidGlamorgan','Norfolk','NorthYorkshire','Northamptonshire','Northumberland','Nottinghamshire','Oxfordshire','Powys','Rutland','Shropshire','Somerset','SouthGlamorgan','SouthYorkshire','Staffordshire','Strathclyde','Suffolk','Surrey','Tayside','TyneandWear','Warwickshire','WestGlamorgan','WestMidlands','WestSussex','WestYorkshire','Wiltshire','Worcestershire');
	private static $_uk_countries = array('England', 'Scotland','Wales', 'Northern Ireland');
	private static $_uk_postcode_formats = array( 'LLxx xLL', 'LLx xLL' );
		
	public function __construct(){}
	
	public function __get( $var ) {
		return $this->$var();
	}	
	
	public function uk_county()
	{
		return parent::random( self::$_uk_counties );
	}
	
	public function uk_country()
	{
		return parent::random( self::$_uk_countries );
	}
	
	public function post_code($options = array()) {
		if (isset($options['variable'])) {
			$a = $options['variable'];
		} else {
			$a = parent::random( self::$_uk_postcode_formats );
		}
		$result = parent::generate_random_alphanumeric_str( $a );
		return strtoupper($result);
	}		
}
?>