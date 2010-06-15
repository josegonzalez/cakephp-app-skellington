<?php
/**
 * Bake Template for Controller action generation.
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
 * @subpackage    cake.console.libs.template.objects
 * @since         CakePHP(tm) v 1.3
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<?php
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

$slugField = (in_array('slug', array_keys($modelObj->schema()))) ? 'slug' : $modelObj->primaryKey;

// Find all the paginate displayFields
$paginate_models = array();
foreach ($modelObj->belongsTo as $associationName => $relation) {
	$related_model = ClassRegistry::init($relation['className']);
	if (is_object($related_model)) {
		$paginate_model = array(
			'alias' => $this->_modelName($associationName),
			'displayField' => $related_model->displayField,
			'primaryKey' => $related_model->primaryKey
		);
		if ($related_model->hasField('slug')) {
			$paginate_model['primaryKey'] = 'slug';
		}
		$paginate_models[] = $paginate_model;
	}
}
?>
<?php if ($singularHumanName == 'User') : ?>

	function <?php echo $admin; ?>dashboard() {
		$this->layout = 'alternate';
		$<?php echo $singularName; ?> = $this-><?php echo $currentModelName; ?>->find('dashboard');
		$this->set(compact('<?php echo $singularName; ?>'));
	}

	function <?php echo $admin; ?>login() {
		$this->layout = 'alternate';
		if (empty($this->data)) {
			return;
		}

		$<?php echo $singularName; ?> = Authsome::login($this->data['<?php echo $currentModelName; ?>']);

		if (!$<?php echo $singularName; ?>) {
			$this->Session->setFlash(__('Unknown user or Wrong Password', true), 'flash/error');
			return;
		}

		$remember = (!empty($this->data['<?php echo $currentModelName; ?>']['remember']));
		if ($remember) {
			Authsome::persist('2 weeks');
		}

		if ($<?php echo $singularName; ?>) {
			$this->Session->setFlash(__('You have been logged in', true), 'flash/success');
			$this->redirect(array('action' => 'dashboard'));
		}
	}

	function <?php echo $admin; ?>logout() {
		$this->layout = 'alternate';
		$this->Authsome->logout();
		$this->Session->delete('<?php echo $currentModelName; ?>');
		$this->redirect(array('action' => 'login'));
	}

	function <?php echo $admin; ?>forgot_password() {
		if (!empty($this->data) && isset($this->data['<?php echo $currentModelName; ?>']['email'])) {
			if ($this->data['<?php echo $currentModelName; ?>']['email'] == '') {
				$this->Session->setFlash(__('Invalid email address', true), 'flash/error');
				$this->redirect(array('action' => 'forgot_password'));
			}

			$<?php echo $singularName; ?> = $this-><?php echo $currentModelName; ?>->find('forgot_password', $this->data['<?php echo $currentModelName; ?>']['email']);
			if (!$<?php echo $singularName; ?>) {
				$this->Session->setFlash(__('No <?php echo $singularName; ?> found for this email address', true), 'flash/error');
				$this->redirect(array('action' => 'forgot_password'));
			}

			$activationKey = $this-><?php echo $currentModelName; ?>->changeActivationKey($<?php echo $singularName; ?>['<?php echo $currentModelName; ?>']['id']);

			try {
				if ($this->Mail->send(array(
					'to' => $<?php echo $singularName; ?>['<?php echo $currentModelName; ?>']['email'],
					'mailer' => 'swift',
					'subject' => '[' . Configure::read('Settings.SiteTitle') .'] ' . __('Reset Password', true),
					'variables' => compact('<?php echo $singularName; ?>', 'activationKey')))) {
						$this->Session->setFlash(__('An email has been sent with instructions for resetting your password', true), 'flash/success');
						$this->redirect(array('action' => 'login'));
				} else {
					$this->Session->setFlash(__('An error occurred', true), 'flash/error');
					$this->log("Error sending email");
				}
			} catch (Exception $e) {
				$this->Session->setFlash(__('An error occurred', true), 'flash/error');
				$this->log("Failed to send email: " . $e->getMessage());
			}
		}
	}

	function <?php echo $admin; ?>reset_password($username = null, $key = null) {
		$this->layout = 'alternate';
		if ($username == null || $key == null) {
			$this->Session->setFlash(__('An error occurred', true), 'flash/error');
			$this->redirect(array('action' => 'login'));
		}

		$<?php echo $singularName; ?> = $this-><?php echo $currentModelName; ?>->find('reset_password', array('username' => $username, 'key' => $key));
		if (!isset($<?php echo $singularName; ?>)) {
			$this->Session->setFlash(__('An error occurred', true), 'flash/error');
			$this->redirect(array('action' => 'login'));
		}

		if (!empty($this->data) && isset($this->data['<?php echo $currentModelName; ?>']['password'])) {
			if ($this-><?php echo $currentModelName; ?>->save($this->data, array('fields' => array('id', 'password', 'activation_key'), 'callback' => 'reset_password', 'user_id' => $<?php echo $singularName; ?>['<?php echo $currentModelName; ?>']['id']))) {
				$this->Session->setFlash(__('Your password has been reset successfully', true), 'flash/success');
				$this->redirect(array('action' => 'login'));
			} else {
				$this->Session->setFlash(__('An error occurred please try again', true), 'flash/error');
			}
		}

		$this->set(compact('<?php echo $singularName; ?>', 'username', 'key'));
	}

	function <?php echo $admin; ?>change_password() {
		$this->layout = 'alternate';
		if (!empty($this->data)) {
			if ($this-><?php echo $currentModelName; ?>->save($this->data, array('fieldList' => array('id', 'password'), 'callback' => 'change_password'))) {
				$this->Session->setFlash(__('Your password has been successfully changed', true), 'flash/success');
				$this->redirect(array('action' => 'dashboard'));
			} else {
				$this->Session->setFlash(__('Your password could not be changed', true), 'flash/error');
			}
		}
	}

	function <?php echo $admin; ?>profile() {
		$this->layout = 'alternate';
		$<?php echo $singularName; ?> = $this-><?php echo $currentModelName; ?>->find('profile');
		$this->set(compact('<?php echo $singularName; ?>'));
	}
<?php endif; ?>

	function <?php echo $admin ?>index() {
<?php if (empty($paginate_models)) : ?>
		$this->paginate = array('contain' => false);
<?php else : ?>
		$this->paginate = array(
			'contain' => array(
<?php foreach ($paginate_models as $p_model): ?>
				'<?php echo $p_model['alias']; ?>' => array('fields' => array('<?php echo $p_model['primaryKey']?>', '<?php echo $p_model['displayField']?>')),
<?php endforeach; ?>
			)
		);
<?php endif; ?>
		$<?php echo $pluralName ?> = $this->paginate();
		$this->set(compact('<?php echo $pluralName ?>'));
	}

	function <?php echo $admin ?>view($<?php echo $slugField; ?> = null) {
		$<?php echo $slugField; ?> = (!$<?php echo $slugField; ?> && !empty($this->params['named']['id'])) ? $this->params['named']['id'] : $<?php echo $slugField; ?>;
		$<?php echo $singularName; ?> = $this-><?php echo $currentModelName; ?>->find('view', $<?php echo $slugField; ?>);

		if (!$<?php echo $singularName; ?>) {
<?php if ($wannaUseSession): ?>
			$this->Session->setFlash(__('Invalid <?php echo strtolower($singularHumanName) ?>', true), 'flash/error');
			$this->redirect(array('action' => 'index'));
<?php else: ?>
			$this->flash(__('Invalid <?php echo strtolower($singularHumanName); ?>', true), array('action' => 'index'));
<?php endif; ?>
		}
		$this->set(compact('<?php echo $singularName ?>'));
	}

<?php $compact = array(); ?>
	function <?php echo $admin ?>add() {
		if (!empty($this->data)) {
			$this-><?php echo $currentModelName; ?>->create();
			if ($this-><?php echo $currentModelName; ?>->save($this->data, array('callback' => '<?php echo $admin ?>add'))) {
<?php if ($wannaUseSession): ?>
				$this->Session->setFlash(__('The <?php echo strtolower($singularHumanName); ?> has been saved', true), 'flash/success');
				$this->redirect(array('action' => 'index'));
<?php else: ?>
				$this->flash(__('<?php echo ucfirst(strtolower($currentModelName)); ?> saved.', true), array('action' => 'index'));
<?php endif; ?>
			} else {
<?php if ($wannaUseSession): ?>
				$this->Session->setFlash(__('The <?php echo strtolower($singularHumanName); ?> could not be saved.', true), 'flash/error');
<?php endif; ?>
			}
		}
<?php
	foreach (array('belongsTo', 'hasAndBelongsToMany') as $assoc):
		foreach ($modelObj->{$assoc} as $associationName => $relation):
			if (!empty($associationName)):
				if (in_array($associationName, array('CreatedBy', 'ModifiedBy'))) continue;
				$otherModelName = $this->_modelName($associationName);
				$otherPluralName = $this->_pluralName($associationName);
				echo "\t\t\${$otherPluralName} = \$this->{$currentModelName}->{$otherModelName}->find('list');\n";
				$compact[] = "'{$otherPluralName}'";
			endif;
		endforeach;
	endforeach;
	if (!empty($compact)):
		echo "\t\t\$this->set(compact(".join(', ', $compact)."));\n";
	endif;
?>
	}

<?php $compact = array(); ?>
	function <?php echo $admin; ?>edit($id = null) {
		if (!$id && empty($this->data)) {
<?php if ($wannaUseSession): ?>
			$this->Session->setFlash(__('Invalid <?php echo strtolower($singularHumanName); ?>', true), 'flash/error');
			$this->redirect(array('action' => 'index'));
<?php else: ?>
			$this->flash(__('Invalid <?php echo strtolower($singularHumanName); ?>', true), array('action' => 'index'));
<?php endif; ?>
		}
		if (!empty($this->data)) {
			if ($this-><?php echo $currentModelName; ?>->save($this->data, array('callback' => '<?php echo $admin ?>edit'))) {
<?php if ($wannaUseSession): ?>
				$this->Session->setFlash(__('The <?php echo strtolower($singularHumanName); ?> has been saved', true), 'flash/success');
				$this->redirect(array('action' => 'index'));
<?php else: ?>
				$this->flash(__('The <?php echo strtolower($singularHumanName); ?> has been saved.', true), array('action' => 'index'));
<?php endif; ?>
			} else {
<?php if ($wannaUseSession): ?>
				$this->Session->setFlash(__('The <?php echo strtolower($singularHumanName); ?> could not be saved.', true), 'flash/error');
<?php endif; ?>
			}
		}
		if (empty($this->data)) {
			$this->data = $this-><?php echo $currentModelName; ?>->find('edit', $id);
		}
		if (empty($this->data)) {
<?php if ($wannaUseSession): ?>
			$this->Session->setFlash(__('Invalid <?php echo strtolower($singularHumanName); ?>', true), 'flash/error');
			$this->redirect(array('action' => 'index'));
<?php else: ?>
			$this->flash(__('Invalid <?php echo strtolower($singularHumanName); ?>', true), array('action' => 'index'));
<?php endif; ?>
		}
<?php
		foreach (array('belongsTo', 'hasAndBelongsToMany') as $assoc):
			foreach ($modelObj->{$assoc} as $associationName => $relation):
				if (!empty($associationName)):
					if (in_array($associationName, array('CreatedBy', 'ModifiedBy'))) continue;
					$otherModelName = $this->_modelName($associationName);
					$otherPluralName = $this->_pluralName($associationName);
					echo "\t\t\${$otherPluralName} = \$this->{$currentModelName}->{$otherModelName}->find('list');\n";
					$compact[] = "'{$otherPluralName}'";
				endif;
			endforeach;
		endforeach;
		if (!empty($compact)):
			echo "\t\t\$this->set(compact(".join(', ', $compact)."));\n";
		endif;
	?>
	}

	function <?php echo $admin; ?>delete($id = null) {
		if (!$id) {
<?php if ($wannaUseSession): ?>
			$this->Session->setFlash(__('Invalid id for <?php echo strtolower($singularHumanName); ?>', true), 'flash/error');
			$this->redirect(array('action'=>'index'));
<?php else: ?>
			$this->flash(__('Invalid <?php echo strtolower($singularHumanName); ?>', true), array('action' => 'index'));
<?php endif; ?>
		}
		if ($this-><?php echo $currentModelName; ?>->delete($id)) {
<?php if ($wannaUseSession): ?>
			$this->Session->setFlash(__('<?php echo ucfirst(strtolower($singularHumanName)); ?> deleted', true), 'flash/success');
			$this->redirect(array('action'=>'index'));
<?php else: ?>
			$this->flash(__('<?php echo ucfirst(strtolower($singularHumanName)); ?> deleted', true), array('action' => 'index'));
<?php endif; ?>
		}
<?php if ($wannaUseSession): ?>
		$this->Session->setFlash(__('<?php echo ucfirst(strtolower($singularHumanName)); ?> was not deleted', true), 'flash/error');
<?php else: ?>
		$this->flash(__('<?php echo ucfirst(strtolower($singularHumanName)); ?> was not deleted', true), array('action' => 'index'));
<?php endif; ?>
		$this->redirect(array('action' => 'index'));
	}