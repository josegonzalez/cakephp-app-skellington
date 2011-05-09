Dummy plugin
-------------

Requirements:

 * PHP 5
 * Cake version 1.2

Installation:

- Create a copy of your "default" db connection in /app/config/database.php and
  name it 'dummy'. Also add or change the 'prefix' to 'dum_'.

- Either use Dummy in realtime mode by making sure DummyTable and DummyField
  models have $useTable = false;
	or, 
  run this in console to create the tables they use : 
  cake schema run create Dummy -path plugins\dummy\config\sql
  
- All actions in this plugin uses Admin route 'admin'. So this must be enabled in /app/config/core.pp

 

Use instructions :

- Go to /admin/dummy/dummy_tables
- First time should analyze your tables and save default values
- If you plan on using the "Generate ALL" function, deactivate tables that you dont want filled
- Click on table names to change generation method, set custom values and deactivate fields
- You can generate values, empty the table and try again here.
- If you are running the plugin in "realtime" mode, you may not change the generation type of 
  fields, but you can use the configuration described bellow to set up your app specific rules.

- If the table has any foreign keys (ie belongsTo)then you should generate the table for the associated model first
  
- If you need new generators then you should add the code in the relevant file(s) in the 'phpfaker' vendor.
  For documentation on how to do this, check readme in vendors/phpfaker folder.
  
- A version of said vendor is included with this plugin, but updates may be found at
  http://github.com/alkemann/php-faker
  

 
Configuration (optional) :

You can add to or replace the default field type and name matches by creating 
a "dummy_config.php" file in /app/config/ containing one or both the follwing 
definitions:

$config['Dummy']['name_matches'] = array( 'field_name' => 'FakerClassName->generator_name' );
$config['Dummy']['type_matches'] = array( 'filed_type' => 'FakerClassName->generator_name' );

Example:

<?php
  $config['Dummy']['name_matches'] = array(
 	'name' => 'Name->firstname',
  	'count' => 'Number->bigInt'
  );
  $config['Dummy']['type_matches'] = array(
  	'integer' => 'Number->smallInt'
  );
?>