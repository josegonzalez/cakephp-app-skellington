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
$modelObj =& new Model(array('name' => $name, 'table' => $useTable, 'ds' => 'default'));
$schema = $modelObj->schema();

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
if (!$displayField) {
	foreach ($schema as $fieldName => $fieldConfig) {
		if (in_array($fieldName, array('title', 'name'))) {
			$displayField = $fieldName;
			break;
		}
	}
}
if (!$displayField) {
	foreach ($schema as $fieldName => $fieldConfig) {
		if ($fieldName == 'model') continue;
		if ($fieldConfig['type'] == 'string') {
			$displayField = $fieldName;
			break;
		}
	}
}
if ($displayField) : ?>
	var $displayField = '<?php echo $displayField; ?>';
<?php endif;

$hasAttachments = false;
$hasComments = false;
$hasImages = false;

// We need to guess what type of Relationship this Model may have to any other
if (in_array("{$name}Attachment", array_keys($associations['hasMany']))) $hasAttachments = true;
if (in_array("{$name}Comment", array_keys($associations['hasMany']))) $hasComments = true;
if (in_array("{$name}Image", array_keys($associations['hasMany']))) $hasImages = true;

$behaviors = array();
$isPublishable = false;
$isDeletable = false;
$isTrackable = false;
$isTree = false;
foreach($schema as $fieldName => $fieldConfig) :
	if (substr($fieldName, -10) == '_file_name' && $fieldConfig['type'] == 'string') {
		$uploadField = substr($fieldName, 0, -10);
		if (strlen($uploadField) == 0) continue;
		$behaviors[] = "'UploadPack.Upload' => array('{$uploadField}'=> array('styles' => array('thumb' => '80x80')))";
		continue;
	}
	if ($fieldName == 'slug') {
		$behaviors[] = "'Sluggable'";
		continue;
	}
	if (in_array($fieldName, array('created_by', 'modified_by')) && !in_array("'Trackable'", $behaviors)) {
		$isTrackable = true;
		$behaviors[] = "'Trackable'";
	}
	if (in_array($fieldName, array('published', 'active'))) $isPublishable = $fieldName;
	if ($fieldName == 'deleted') $isDeletable = $fieldName;
endforeach;

foreach (array('parent_id', 'lft', 'rght') as $fieldName) {
	if (!in_array($fieldName, array_keys($schema))) {
		$isTree = false;
		break;
	}
	$isTree = true;
}
if ($isTree) $behaviors[] = "'Tree'";

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
			if ($name == $relation['alias']) $assocations[$assocType][$i]['alias'] = $relation['alias'] = "Parent{$name}";
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
		if (in_array($field, array('created_by', 'created_by_id', 'modified_by', 'modified_by_id'))) continue;
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
<?php if ($name == 'User') : ?>

	function __beforeSaveAdd($data, $extra) {
		$data[$this->alias]['password'] = Authsome::hash($data[$this->alias]['new_password']);
		return $data;
	}

	function __beforeSaveChangePassword($data, $extra) {
		if (!$data || !isset($data[$this->alias])) return false;

		$data = array(
			$this->alias => array(
				'password' => $data[$this->alias]['password'],
				'new_password' => $data[$this->alias]['new_password'],
				'new_password_confirm' => $data[$this->alias]['new_password_confirm']
			)
		);

		if ($data[$this->alias]['new_password'] != $data[$this->alias]['new_password_confirm']) return false;
		foreach($data[$this->alias] as $key => &$value) {
			$value = Security::hash($value, null, true);
			if ($value == Security::hash('', null, true)) {
				return false;
			}
		}
		$data[$this->alias]['<?php echo $primaryKey; ?>'] = Authsome::get('id');

		$user = $this->find('first', array(
			'conditions' => array(
				"{$this->alias}.id" => Authsome::get('id'),
				"{$this->alias}.password" => $data[$this->alias]['password']),
			'contain' => false,
			'fields' => array('id')
		));

		if (!$user) return false;
		return $data;
	}

	function __beforeSaveResetPassword($data, $extra) {
		return array($this->alias => array(
			'<?php echo $primaryKey; ?>' => $extra['user_id'],
			'password' => Authsome::hash($data[$this->alias]['password']),
			'activation_key' => md5(uniqid())
		));
	}

	function __findDashboard() {
		return $this->find('first', array(
			'conditions' => array("{$this->alias}.<?php echo $primaryKey; ?>" => Authsome::get('<?php echo $primaryKey; ?>')),
			'contain' => false
		));
	}

<?php endif; ?>

	function __findView($<?php echo (isset($schema['slug'])) ? 'slug' : $primaryKey; ?> = null) {
		if (!$<?php echo (isset($schema['slug'])) ? 'slug' : $primaryKey; ?>) return false;

		return $this->find('first', array(
<?php if ($isPublishable != false) : ?>
			'conditions' => array(
				"{$this->alias}.<?php echo (isset($schema['slug'])) ? 'slug' : "{\$this->primaryKey}"; ?>" => $<?php echo (isset($schema['slug'])) ? 'slug' : $primaryKey; ?>,
				"{$this->alias}.<?php echo $isPublishable; ?>" => 1),
<?php elseif ($isDeletable != false) : ?>
			'conditions' => array(
				"{$this->alias}.<?php echo (isset($schema['slug'])) ? 'slug' : "{\$this->primaryKey}"; ?>" => $<?php echo (isset($schema['slug'])) ? 'slug' : $primaryKey; ?>,
				"{$this->alias}.deleted" => 0),
<?php else : ?>
			'conditions' => array("{$this->alias}.<?php echo (isset($schema['slug'])) ? 'slug' : "{\$this->primaryKey}"; ?>" => $<?php echo (isset($schema['slug'])) ? 'slug' : $primaryKey; ?>),
<?php endif?>
<?php if (!empty($associations['belongsTo']) || $isTrackable || $hasComments) : ?>
			'contain' => array(
<?php foreach ($associations['belongsTo'] as $i => $relation) : ?>
<?php echo "\t\t\t\t'" . $relation['alias'] ."',\n"; ?>
<?php endforeach; ?>
<?php if ($isTrackable) echo "\t\t\t\t'CreatedBy',\n"; ?>
<?php if ($hasComments) echo "\t\t\t\t'{$name}Comment',\n"; ?>
			)
		));
<?php else: ?>
			'contain' => false
		));
<?php endif; ?>
	}

	function __findEdit($<?php echo $primaryKey; ?> = null) {
		if (!$<?php echo $primaryKey; ?>) return false;

		return $this->find('first', array(
			'conditions' => array("{$this->alias}.<?php echo $primaryKey; ?>" => $<?php echo $primaryKey; ?>)
		));
	}

	function __findDelete($<?php echo $primaryKey; ?> = null) {
		if (!$<?php echo $primaryKey; ?>) return false;

		return $this->find('first', array(
			'conditions' => array("{$this->alias}.<?php echo $primaryKey; ?>" => <?php echo $primaryKey; ?>),
			'contain' => false
		));
	}
<?php if ($name == 'User') :?>

	function __findForgotPassword($email = null) {
		if (!$email) return false;

		return $this->find('first', array(
			'conditions' => array("{$this->alias}.email" => $email),
			'contain' => false
		));
	}

<?php
	$profile_fields = array_diff(array_keys($schema), array('password'));
	$profile_fields_string = '';
	foreach ($profile_fields as $key => $field) {
		$profile_fields_string .= "'{$field}'";
		if (count($profile_fields) == $key+1) break;
		$profile_fields_string .= ", ";
	}

?>
	function __findProfile() {
		return $this->find('first', array(
			'conditions' => array("{$this->alias}.<?php echo $primaryKey; ?>" => Authsome::get('<?php echo $primaryKey; ?>')),
			'fields' => array(<?php echo $profile_fields_string; ?>)
		));
	}

	function __findResetPassword($options = array()) {
		if (!isset($options['username']) || !isset($options['key'])) return false;

		return $this->find('first', array(
			'conditions' => array(
				"{$this->alias}.<?php echo $displayField; ?>" => $options['username'],
				"{$this->alias}.activation_key" => $options['key']
			)
		));
	}

	function authsomeLogin($type, $credentials = array()) {
		switch ($type) {
			case 'guest':
				// You can return any non-null value here, if you don't
				// have a guest account, just return an empty array
				return array('guest' => 'guest');
			case 'single_signon':
				// This is set for sites that have 1 maintainer and thus
				// do not require a users table
				if ($credentials['username'] != Configure::read('User.username')) return false;
				if ($credentials['password'] == Configure::read('User.password')) return false;
				return array(Configure::read('User'));
			case 'credentials':
				// This is the logic for validating the login
				$conditions = array(
					"{$this->alias}.email" => $credentials['email'],
					"{$this->alias}.password" => Authsome::hash($credentials['password']),
				);
				break;
			case 'cookie':
				list($token, $userId) = split(':', $credentials['token']);
				$duration = $credentials['duration'];

				$loginToken = $this->LoginToken->find('first', array(
					'conditions' => array(
						'user_id' => $userId,
						'token' => $token,
						'duration' => $duration,
						'used' => false,
						'expires <=' => date('Y-m-d H:i:s', strtotime($duration)),
					),
					'contain' => false
				));

				if (!$loginToken) {
					return false;
				}

				$loginToken['LoginToken']['used'] = true;
				$this->LoginToken->save($loginToken);

				$conditions = array(
					"{$this->alias}.<?php echo $primaryKey; ?>" => $loginToken['LoginToken']['user_id'],
				);
				break;
			default:
				return null;
		}

		$user = $this->find('first', compact('conditions'));
		if (!$user) {
			return false;
		}
		$user[$this->alias]['loginType'] = $type;
		return $user;
	}

	function authsomePersist($user, $duration) {
		$token = md5(uniqid(mt_rand(), true));
		$userId = $user[$this->alias]['<?php echo $primaryKey; ?>'];

		$this->LoginToken->create(array(
			'user_id' => $userId,
			'token' => $token,
			'duration' => $duration,
			'expires' => date('Y-m-d H:i:s', strtotime($duration)),
		));
		$this->LoginToken->save();

		return "{$token}:{$userId}";
	}

	function changeActivationKey($id) {
		$activationKey = md5(uniqid());
		$data = array(
			"{$this->alias}" => array(
				'<?php echo $primaryKey; ?>' => $id,
				'activation_key' => $activationKey,
			)
		);

		if (!$this->save($data, array('callbacks' => false))) return false;
		return $activationKey;
	}
<?php endif; ?>
}
<?php echo '?>'; ?>