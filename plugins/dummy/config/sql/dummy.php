<?php 
/**
 * Schema file for creating tables that may be used to run Dummy plugin in "full" mode.
 * As apposed to the "realtime" mode where all fields are reanalyzed on each run and
 * user is unable to change settings.
 * 
 * To use this file run this console command:
 * 
 *  cake schema run create Dummy -path plugins\dummy\config\sql
 *
 * @package DummyData plugin
 * @author Alexander Morland (alkemann)
 * @author Ronny Vindenes (rvv)
 * @modified 8. feb 2009
 */
class DummySchema extends CakeSchema {
	var $name = 'Dummy';

	var $dum_dummy_fields = array(
			'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
			'dummy_table_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
			'name' => array('type' => 'string', 'null' => false, 'default' => NULL),
			'allow_null' => array('type' => 'boolean', 'null' => false, 'default' => NULL),
			'default' => array('type' => 'string', 'null' => true, 'default' => NULL),
			'active' => array('type' => 'boolean', 'null' => false, 'default' => '1'),
			'type' => array('type' => 'string', 'null' => false, 'default' => NULL),
			'generator' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100),
			'custom_min' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100),
			'custom_max' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100),
			'custom_variable' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100),
			'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
		);
		
	var $dum_dummy_tables = array(
			'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
			'model' => array('type' => 'string', 'null' => true, 'default' => NULL ),
			'table' => array('type' => 'string', 'null' => true, 'default' => NULL),
			'active' => array('type' => 'boolean', 'null' => false, 'default' => '1'),
			'number' => array('type' => 'integer', 'null' => false, 'default' => '10', 'length' => 6),
			'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
			'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
		);
}
?>