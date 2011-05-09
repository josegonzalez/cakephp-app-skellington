# author Alexander Morland (alkemann) 
# modified 10. feb. 2009

Creating custom generation method instructions:
#################################################

1. First make sure you are not creating a duplicate of an existing method.

2. Decide if you are making a 
 
   a. new generation class or 
   b. adding a method to existing class.
   
   If b. skipp to point 5.
   
3. Decide if you new class should extend an existing one. 
   It should only do this if it makes logical sense and the new class
   partially or completely overwrite the existing methods.
   Example of this is the "Uk" class that extends "Address" class by
   making post_codes in a United Kingdom specific way.
   
   If you extend use such code :

		include_once 'address.php';
		class Uk extends Address {

   If not, just extend the "Faker" class.
	
4. All generator classes should overwrite constructor and get methods:
	
	 
5. Next give your method a descriptive name, preferably a noun. The 
   method should take in one parameter, an array. The keys that are
   passed directly from the webinterface of DummyData plugin are
   'min','max' and 'variable', so using these if any is preferable.
   
6. The method should return a value (ie string or number). 

Example:

To create a new method that returns a random Donald Duck name, we could
extend the Name class and replace the name property:

<?php
include_once 'name.php';
class Donald extends Name {
	public function __construct() {	}
	
	public function __get( $var ) {
		return $this->$var();
	}
	
	public static function name($options = array()) {
		$names = array('Donald Duck','Mickey Mouse','Goofy','Pluto','Minnie Mouse');
		return $names[ rand(1,count($names)) - 1 ];
	}
}