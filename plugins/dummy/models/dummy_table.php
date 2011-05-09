<?php
/**
 * This class is for handling the DummyData metadata for your tables,
 * such as which tables to generate data for and how much data to generate
 *
 * You call DummyTable::analyze() to (re-)analyze a table i.e. creating the metadata for the table
 * then DummyTable::generate() is used to add data to the table using either a specified model or
 * a generic model
 * 
 *   
 * @package DummyData plugin
 * @author Ronny Vindenes (rvv)
 * @author AlexandeR Morland (alkemann)
 * @modified 10. feb. 2009
 */
class DummyTable extends DummyAppModel {
	
	public $name = 'DummyTable';
	
	public $useDbConfig = 'dummy';
	
	public $hasMany = array(
			'DummyField' => array(
					'className' => 'Dummy.DummyField', 
					'foreignKey' => 'dummy_table_id', 
					'conditions' => array('DummyField.active' => true)));
	/**
	 * Analyze the table and create dummy fields. If no table id is given it will analyze all the tables in the $default DataSource.
	 *
	 * @param integer $id
	 * @return boolean success
	 */
	public function analyze($id = null) {
		$success = false;
		
		if (!$id) {
			$tables = $this->showTables();
			if ($this->find('count')) {
				$this->deleteAll(array(1 => 1));
			}
			$success = $this->saveAll($tables);
			$tables = $this->find('all', array('fields' => 'id', 'recursive' => -1));
		} else {
			$tables = $this->find('all', array(
					'fields' => 'id', 
					'recursive' => -1, 
					'conditions' => array('DummyTable.id =' => $id)));
			$this->DummyField->deleteAll(array(
					'DummyField.dummy_table_id =' => $id));
			$success = true;
		}
		
		foreach ($tables as $table) {
			$fields = $this->describe($table['DummyTable']['id']);
			foreach ($fields as $field => $data) {
				$this->DummyField->create($data);
				$this->DummyField->set('name', $field);
				$this->DummyField->set('dummy_table_id', $table['DummyTable']['id']);
				$this->DummyField->analyze();
				$this->DummyField->save();
			}
		}
		return $success;
	}
	
	/**
	 * Wrapper for find('all', $options) on the data table
	 *
	 * @param integer $id
	 * @param array $options
	 * @return array result
	 */
	public function contents($id, $options = array()) {
		$Model = $this->getDataModel($id);
		if (!isset($options['order'])) {
			$options['order'] = $Model->primaryKey . ' DESC';
		}
		$options['recursive'] = -1;
		$Model->alias = 'Model';
		return $Model->find('all', $options);
	}
	
	/**
	 * Generate data and save it in the data table
	 *
	 * @param integer $id
	 * @return integer number of entries saved
	 */
	public function generate($id = null) {
		
		$data = $this->read(null, $id);
		
		$Model = &$this->getDataModel();
		$saveCount = 0;
		
		for ($i = 0; $i < $data['DummyTable']['number']; $i++) {
			$Model->create();
			foreach ($data['DummyField'] as $field) {
				$this->DummyField->id = $field['id'];
				$Model->set($field['name'], $this->DummyField->generate());
			}
			foreach ($data['DummyField'] as $field) {
				if (array_key_exists($field['generator'],$this->DummyField->DummyType->specialGenerators)) {
					$var = null;
					foreach ($this->DummyField->DummyType->specialGenerators[$field['generator']]['fields'] as $data_field) {	
						if (isset($Model->data[$Model->alias][$data_field])) {	
							if ($this->DummyField->DummyType->specialGenerators[$field['generator']]['field'] == 'first') {						
								$var = $Model->data[$Model->alias][$data_field];
								break;
							} else {
								$var[] = $Model->data[$Model->alias][$data_field];
							}
						}
					}
					if ($var) {
						if (is_array($var)) {
							$var = implode(' ', $var);
						}
						$this->DummyField->id = $field['id'];
						$Model->set($field['name'], $this->DummyField->generate(array('variable' => $var)));
					}
				}
			}
			$success = $Model->save();
			if ($success) {
				$saveCount++;
			}
		}
		
		return $saveCount;
	}
	
	/**
	 * Describe the table
	 *
	 * @param integer $id
	 * @return array
	 */
	private function describe($id) {
		$Model = &$this->getDataModel($id);
		return ConnectionManager::getDataSource('default')->describe($Model);
	}
	
	/**
	 * Get the model for the data table 
	 *
	 * @param integer $id
	 * @return object model
	 */
	private function getDataModel($id = null) {
		$data = $this->read(null, $id);
		
	//	debug($data);
		/* Try to use the specified model if it exits, otherwise create a generic model using the table as it's source */
		if (!empty($data['DummyTable']['model'])) {
			if (App::import('model', $data['DummyTable']['model'])) {
				$Model = ClassRegistry::init($data['DummyTable']['model'], 'model');
			} else {
				$Model = new Model(false, Inflector::tableize($data['DummyTable']['model']));
			}
		} else {
			$model_name = Inflector::singularize(Inflector::camelize($data['DummyTable']['table']));
			if (App::import('model', $model_name)) {
				$Model = ClassRegistry::init($model_name, 'model');
			} else {
				$Model = new Model(false, $data['DummyTable']['table']);
			}
		}
		return $Model;
	}
	
	/**
	 * Get a list of tables from the DataSource. Does not include the DummyData tables.
	 *
	 * @return array
	 */
	private function showTables($source = 'default') {
		$tables = ConnectionManager::getDataSource($source)->listSources();
		$data = array();
		
		$prefix = ConnectionManager::getDataSource('dummy')->config['prefix'];
		
		foreach ($tables as $table) {
			if (substr($table, 0, strlen($prefix)) != $prefix) {
				$active = !in_array($table, array('users', 'logs'));
				$data[] = array(
						$this->alias => array(
								'table' => $table, 
								'active' => $active));
			}
		}
		return $data;
	}
	
	/**
	 * Create displayField from model, table or id
	 *
	 * @param array $results
	 * @param boolean $primary
	 * @return array
	 */
	public function afterFind($results = array(), $primary = true) {
		if ($primary) {
			foreach ($results as $index => $data) {
				if (!empty($data['DummyTable']['model'])) {
					$results[$index]['DummyTable']['name'] = $data['DummyTable']['model'];
				} elseif (!empty($data['DummyTable']['table'])) {
					$results[$index]['DummyTable']['name'] = Inflector::camelize($data['DummyTable']['table']);
				} elseif (!empty($data['DummyTable']['id'])) {
					$results[$index]['DummyTable']['name'] = $data['DummyTable']['id'];
				}
			}
		}
		return $results;
	}
}
?>
