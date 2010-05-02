<?php
/**
 * Model template file.
 *
 * Used by bake to create new Model files.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2009, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2009, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.console.libs.templates.objects
 * @since         CakePHP(tm) v 1.3
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::import('Model', 'Model', false);
$modelObject =& new Model(array('name' => $name, 'table' => $useTable, 'ds' => 'default'));
$schema = $modelObject->schema();

echo "<?php\n"; ?>
class <?php echo $name ?> extends <?php echo $plugin; ?>AppModel {
	var $name = '<?php echo $name; ?>';
<?php if ($useDbConfig != 'default'): ?>
	var $useDbConfig = '<?php echo $useDbConfig; ?>';
<?php endif;?>
<?php if ($useTable && $useTable !== Inflector::tableize($name)):
	$table = "'$useTable'";
	echo "\tvar \$useTable = $table;\n";
endif;
if ($primaryKey !== 'id'): ?>
	var $primaryKey = '<?php echo $primaryKey; ?>';
<?php endif;
if ($displayField): ?>
	var $displayField = '<?php echo $displayField; ?>';
<?php endif;

$behaviors = array();
$isPublishable = false;
$isDeletable = false;
$isTrackable = false;
foreach($schema as $fieldName => $fieldConfig) :
	if (substr($fieldName, -9) == 'file_name' && $fieldConfig['type'] == 'string') {
		$uploadField = substr($fieldName, 0, -10);
		$behaviors[] = "'UploadPack.Upload' => array('{$uploadField}')";
		continue;
	}
	if ($fieldName == 'slug') {
		$behaviors[] = "'Sluggable'";
		continue;
	}
	if (in_array($fieldName, array('created_by', 'modified_by')) && !in_array("'Trackable'", $behaviors)) {
		$isTrackable == true;
		$behaviors[] = "'Trackable'";
	}
	if (in_array($fieldName, array('published', 'active'))) $isPublishable = $fieldName;
	if ($fieldName == 'deleted') $isDeletable = $fieldName;
endforeach;

if (!empty($behaviors)) :
	$behaviorCount = count($behaviors);
	if ($behaviorCount == 1) :
		echo "\tvar \$actsAs = array({$behaviors['0']});\n";
	else :
		echo "\tvar \$actsAs = array(";
		foreach ($behaviors as $i => $behavior) :
			$out = "\n\t\t{$behavior}";
			if ($i + 1 < $behaviorCount) :
				$out .= ",";
			endif;
			echo $out;
		endforeach;
		echo "\n\t);\n";
	endif;
endif;

if (isset($schema['owned_by'])) {
	$associations['belongsTo'][] = array(
		'alias' => 'Owner',
		'className' => 'User',
		'foreignKey' => 'owned_by'
	);
}

if (isset($schema['assigned_to'])) {
	$associations['belongsTo'][] = array(
		'alias' => 'AssignedTo',
		'className' => 'User',
		'foreignKey' => 'assigned_to'
	);
}

foreach (array('hasOne', 'belongsTo') as $assocType):
	if (!empty($associations[$assocType])):
		$typeCount = count($associations[$assocType]);
		echo "\tvar \$$assocType = array(";
		foreach ($associations[$assocType] as $i => $relation):
			$out = "\n\t\t'{$relation['alias']}' => array(\n";
			$out .= "\t\t\t'className' => '{$relation['className']}',\n";
			$out .= "\t\t\t'foreignKey' => '{$relation['foreignKey']}',\n";
			$out .= "\t\t)";
			if ($i + 1 < $typeCount) {
				$out .= ",";
			}
			echo $out;
		endforeach;
		echo "\n\t);\n";
	endif;
endforeach;

if (!empty($associations['hasMany'])):
	$belongsToCount = count($associations['hasMany']);
	echo "\tvar \$hasMany = array(";
	foreach ($associations['hasMany'] as $i => $relation):
		$out = "\n\t\t'{$relation['alias']}' => array(\n";
		$out .= "\t\t\t'className' => '{$relation['className']}',\n";
		$out .= "\t\t\t'foreignKey' => '{$relation['foreignKey']}',\n";
		$out .= "\t\t\t'dependent' => true,\n";
		$out .= "\t\t)";
		if ($i + 1 < $belongsToCount) {
			$out .= ",";
		}
		echo $out;
	endforeach;
	echo "\n\t);\n\n";
endif;

if (!empty($associations['hasAndBelongsToMany'])):
	$habtmCount = count($associations['hasAndBelongsToMany']);
	echo "\tvar \$hasAndBelongsToMany = array(";
	foreach ($associations['hasAndBelongsToMany'] as $i => $relation):
		$out = "\n\t\t'{$relation['alias']}' => array(\n";
		$out .= "\t\t\t'className' => '{$relation['className']}',\n";
		$out .= "\t\t\t'joinTable' => '{$relation['joinTable']}',\n";
		$out .= "\t\t\t'foreignKey' => '{$relation['foreignKey']}',\n";
		$out .= "\t\t\t'associationForeignKey' => '{$relation['associationForeignKey']}',\n";
		$out .= "\t\t\t'unique' => true,\n";
		$out .= "\t\t)";
		if ($i + 1 < $habtmCount) {
			$out .= ",";
		}
		echo $out;
	endforeach;
	echo "\n\t);\n\n";
endif;
?>
	function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);
<?php
if (!empty($validate)):
	echo "\t\t\$this->validate = array(\n";
	foreach ($validate as $field => $validations):
		echo "\t\t\t'$field' => array(\n";
		foreach ($validations as $key => $validator):
			if ($validator == 'notempty') :
			echo "\t\t\t\t'required' => array(\n";
			else :
			echo "\t\t\t\t'$key' => array(\n";
			endif;
			echo "\t\t\t\t\t'rule' => array('$validator'),\n";
			if ($validator == 'notempty') :
			echo "\t\t\t\t\t'message' => __('cannot be left empty', true),\n";
			elseif ($validator == 'alphanumeric') :
			echo "\t\t\t\t\t'message' => __('must contain only letters and numbers', true),\n";
			elseif ($validator == 'boolean') :
			echo "\t\t\t\t\t'message' => __('must be either yes or no', true),\n";
			elseif ($validator == 'date') :
			echo "\t\t\t\t\t'message' => __('must be a valid date in either YYYY-MM-DD or YY-MM-DD format', true),\n";
			elseif ($validator == 'email') :
			echo "\t\t\t\t\t'message' => __('must be a valid email', true),\n";
			elseif ($validator == 'isunique') :
			echo "\t\t\t\t\t'message' => __('cannot have been already defined in the database', true),\n";
			elseif ($validator == 'numeric') :
			echo "\t\t\t\t\t'message' => __('must contain only numbers', true),\n";
			elseif ($validator == 'url') :
			echo "\t\t\t\t\t'message' => __('must be a valid url', true),\n";
			else :
			echo "\t\t\t\t\t'message' => __('must be valid input', true),\n";
			endif;
			echo "\t\t\t\t),\n";
		endforeach;
		echo "\t\t\t),\n";
	endforeach;
	echo "\t\t);\n";
endif;
?>
	}

	function __findEdit($id = null) {
		if (!$slug) return false;

		return $this->find('first', array(
			'conditions' => array(
				"{$this->alias}.{$this->primaryKey}" => $id)));
	}

	function __findView($slug = null) {
		if (!$slug) return false;

		return $this->find('first', array(
<?php if ($isPublishable != false) : ?>
			'conditions' => array(
				"{$this->alias}.slug" => $slug,
				"{$this->alias}.<?php echo $isPublishable; ?>" => 1),
<?php elseif ($isDeletable != false) : ?>
			'conditions' => array(
				"{$this->alias}.slug" => $slug,
				"{$this->alias}.deleted" => 0),
<?php else : ?>
			'conditions' => array("{$this->alias}.slug" => $slug),
<?php endif?>
			'contain' => false));
	}
}
<?php echo '?>'; ?>