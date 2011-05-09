<?php
/**
 * This class is for handling the DummyData metadata for each field in your tables.
 * 
 * You call DummyField::analyze() to (re-)analyze a field, for best results DummyField->data
 * should be populated with data from DummyTable::describe() first,
 * then you can call DummyField::generate() to generate a value for the corresponding field using the selected generator.
 * 
 * Custom generators that require full access to CakePHP can be added to this class, see generateBelongsTo() below and DummyType
 * 
 * @package DummyData plugin
 * @author Ronny Vindenes (rvv)
 * @author AlexandeR Morland (alkemann)
 * @modified 10. feb. 2009
 */
class DummyField extends DummyAppModel {
	
	public $name = 'DummyField';
	
	public $order = 'name';
	
	public $useDbConfig = 'dummy';
	
	public $belongsTo = array(
			'DummyTable' => array(
					'className' => 'Dummy.DummyTable', 
					'foreignKey' => 'id'), 
			'Dummy.DummyType');
	
	/**
	 * Analyze the field to guess the most suitable generator and set various options.
	 * Depends on the data from DummyTable::describe() being in $this->data for best results.
	 *
	 * @param integer $id
	 */
	public function analyze($id = null) {
		
		if ($id) {
			$this->read(null, $id);
		}
		
		if (!empty($this->data['DummyField']['key']) && $this->data['DummyField']['key'] == 'primary') {
			$this->set('active', false);
		}
		
		switch ($this->data['DummyField']['name']){
			case 'lft':
			case 'rght':
				$this->set('active', false);
			break;
		}
		
		if (preg_match('/_count$/', $this->data['DummyField']['name']) == 1) {
			$this->set('active', false);
		}
		
		if (!empty($this->data['DummyField']['null'])) {
			$this->set('allow_null', true);
		}
		
		$generator = $this->DummyType->defaultType($this->data['DummyField']);
		
		foreach ($generator as $field => $value) {
			$this->set($field, $value);
		}
	
	}
	
	/**
	 * Generate a value for this field using the specified generator and options.
	 *
	 * @param array $options
	 * @return mixed value
	 */
	public function generate($options = array()) {
		
		App::import('vendor', 'Dummy.DummyWrapper');
		
		$this->read();
		
		if (!empty($this->data['DummyField']['custom_max'])) {
			$options['max'] = $this->data['DummyField']['custom_max'];
		}
		
		if (!empty($this->data['DummyField']['custom_min'])) {
			$options['min'] = $this->data['DummyField']['custom_min'];
		}
		
		if (!empty($this->data['DummyField']['custom_variable'])) {
			$options['variable'] = $this->data['DummyField']['custom_variable'];
		}
		
		$generator = explode('->', $this->data['DummyField']['generator']);
		if (count($generator) != 2) {
			$method = 'generate' . $this->data['DummyField']['generator'];
			if (method_exists($this, $method)) {
				return $this->$method($options);
			} else {
				return NULL;
			}
		}
		
		return DummyWrapper::generate($generator[0], $generator[1], $options);
	
	}
	
	/**
	 * Custom generator for BelongsTo fields. Returns the id to an existing entry of the correct model if one exists or 0
	 *
	 * @param array $options
	 * @return mixed id
	 */
	private function generateBelongsTo($options = array()) {
		$modelName = Inflector::camelize($options['variable']);
		
		if (App::import('Model', $modelName)) {
			$Model = ClassRegistry::init($modelName);
		} else {
			$Model = new Model(array(
			    'table' => Inflector::tableize($options['variable']),
			    'name' => $options['variable']
			));
		}
		if ($Model) {
			$ids = $Model->find('all', 'id');
			$idcount = sizeof($ids);
			if ($idcount > 0) {
				$key = $ids[rand(0, $idcount - 1)][$Model->alias]['id'];
			} else {
				$key = 0;
			}
		} else {
			$key = 0;
		}
		return $key;
	}
}
?>