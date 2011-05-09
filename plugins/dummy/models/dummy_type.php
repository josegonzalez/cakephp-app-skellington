<?php
/**
 * This class as the function of guessing what generator a field should use
 * and also is used to list out all generators for each field typpe.
 *
 * DummyType::_nameMatch and DummyType::_typeMatch defines the basic rules,
 * if you wish to expand or replace rules you should not change this file.
 * You can do this by adding a "dummy_config.php" to your /app/config folder.
 * It should contain at least one of the following definitions:
 *
 * $config['Dummy']['name_matches'] = array( 'field_name' => 'FakerClassName->generator_name' );
 * $config['Dummy']['type_matches'] = array( 'filed_type' => 'FakerClassName->generator_name' );
 *
 * Example :
 * <?php
 * $config['Dummy']['name_matches'] = array(
 * 	'name' => 'Name->firstname',
 * 	'count' => 'Number->bigInt'
 * );
 * $config['Dummy']['type_matches'] = array(
 * 	'integer' => 'Number->smallInt'
 * );
 * ?>
 *
 * @package DummyData plugin
 * @author Ronny Vindenes (rvv)
 * @author AlexandeR Morland (alkemann)
 * @modified 10. feb. 2009
 */
class DummyType extends DummyAppModel {
	public $name = 'DummyType';
	public $useTable = false;

	private $_nameMatch = array(
		'username' => 'Web->username',
		'password' => 'Web->password',
		'name' => 'Name->name',
		'firstname' => 'Name->firstname',
		'lastname' => 'Name->surname',
		'surname' => 'Name->surname',
		'full_name' => 'Name->full_name',
		'fullname' => 'Name->full_name',
		'email' => 'Web->email',
		'phone' => 'Address->phone',
		'phonenumber' => 'Address->phone',
		'fax' => 'Address->phone',
		'url' => 'Web->url',
		'website' => 'Web->url',
		'color' => 'English->color',
		'colour' => 'English->color',
		'timestamp' => 'Time->timestamp',
		'year' => 'Time->year',
		'month' => 'Time->month',
		'day' => 'Time->day',
		'title' => 'English->title',
		'model' => 'English->noun',
		'action' => 'English->verb',
		'filename' => 'English->filename',
		'file_name' => 'English->filename',
		'extension' => 'English->extension',
		'description' => 'English->quote',
		'signature' => 'English->quote',
		'address' => 'Address->street_address',
		'address1' => 'Address->street_address',
		'address2' => 'Address->abode_address',
		'state' => 'Usa->us_state',
		'city' => 'Address->city',
		'zip' => 'Address->zip_code',
		'zip_code' => 'Address->zip_code',
		'zipcode' => 'Address->zip_code',
		'brief_description' => 'Lorem->sentence'
	);

	private $_typeMatch = array(
		'boolean' => 'Number->boolean',
		'string' => 'Lorem->sentence',
		'integer' => 'Number->tinyInt',
		'datetime' => 'Time->datetime',
		'date' => 'Time->date',
		'time' => 'Time->time',
		'float' => 'Number->float',
		'binary' => 'Lorem->sentence',
		'text' => 'English->quote'
	);

	public $specialGenerators = array(
		'Web->username' => array(
			'field' => 'first',
			'fields' => array('firstname','fullname','name','email')
		),
		'Web->full_name' => array(
			'field' => 'all',
			'fields' => array('firstname','surname','lastname')
		),
		'Web->email' => array(
			'field' => 'all',
			'fields' => array('firstname','surname','lastname')
		)
	);

	function __construct($id = false,$table = null, $ds = null) {
		parent::__construct($id,$table,$ds);
		$loaded = Configure::load('dummy_config');
		$name_matches = Configure::read('Dummy.name_matches');
		if ($name_matches) {
			$this->_nameMatch = am($this->_nameMatch,$name_matches);
		}
		$type_matches = Configure::read('Dummy.type_matches');
		if ($type_matches) {
			$this->_typeMatch = am($this->_nameMatch,$type_matches);
		}
	}

	public function options() {
		App::import('vendor','Dummy.DummyWrapper');
		$numbers = DummyWrapper::listNumberGenerators();
		$strings = DummyWrapper::listStringGenerators();
		$times = DummyWrapper::listTimeGenerators();
		$ret = array(
			'string' => $strings,
			'text' => $strings,
			'date' => $times,
			'datetime' => $times,
			'time' => $times,
			'timestamp' => $times,
			'integer' => $numbers,
			'float' => $numbers,
			'boolean' => $numbers,
			'binary' => $strings
		);

		return $ret;
	}

	private function _matchName($fieldName) {
		if (isset($this->_nameMatch[$fieldName])) {
			return $this->_nameMatch[$fieldName];
		}
		return null;
	}

	private function _matchType($fieldType) {
		if (isset($this->_typeMatch[$fieldType])) {
			return $this->_typeMatch[$fieldType];
		}
		return null;
	}

	public function _defaultSettings($field = array()) {
		$type = array();

		if (preg_match('/unsigned$/', $field['type']) == 1) {
			$type['custom_variable'] = 'unsigned';
		}

		if (preg_match('/_id$/', $field['name']) == 1) {
			if ($field['name']=='parent_id') {
				$Dummytable = ClassRegistry::init('Dummy.DummyTable','model');
				$Dummytable->read(null,$field['dummy_table_id']);
				$model = $Dummytable->data['DummyTable']['name'];
			} else {
				$model = substr($field['name'], 0, strlen($field['name']) - 3);
			}

			$type['generator'] = 'BelongsTo';
			$type['custom_variable'] = Inflector::camelize($model);
		}

		if ($field['type'] == 'float') {
			$type['custom_variable'] = '%01.2f';
		}

		$size = array();
		if (preg_match('/^varchar\((\d*)\)/', $field['type'], $size) == 1) {
			$type['custom_max'] = $size[1];
		}

		return $type;
	}

	public function defaultType($field = array()) {

		$generator['generator'] = $this->_matchType($field['type']);

		if ($nameMatch = $this->_matchName($field['name'])) {
			$generator['generator'] = $nameMatch;
		}

		$generator = am($generator, $this->_defaultSettings($field));

		if (!$generator['generator']) {
			$generator['generator'] = 'Number->tinyInt';
		}

		return $generator;
	}
}
?>