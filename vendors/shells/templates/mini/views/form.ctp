<?php
/**
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
 * @subpackage    cake.cake.console.libs.templates.views
 * @since         CakePHP(tm) v 1.2.0.5234
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<?php
$invalidFields = array('created', 'modified', 'updated', 'created_by', 'modified_by');
foreach ($fields as $i => $field) {
	if (substr($field, -6) == '_count') unset($fields[$i]);
	if (in_array($field, $invalidFields)) unset($fields[$i]);
}
?>
<div class="<?php echo $pluralVar;?> form">
<?php echo "<?php \$this->Html->h2(sprintf(__('" . Inflector::humanize($action) . " %s', true), __('{$singularHumanName}', true))); ?>\n";?>
<?php echo "<?php echo \$this->Form->create('{$modelClass}');?>\n";?>
<?php
		echo "\t<?php\n";
		foreach ($fields as $field) {
			if (strpos($action, 'add') !== false && $field == $primaryKey) {
				continue;
			}
			if ($schema[$field]['type'] == 'boolean') {
				echo "\t\techo \$this->Form->input('{$modelClass}.{$field}',\n";
				echo "\t\t\tarray('type' => 'select', 'options' => array('0' => 'No', '1' => 'Yes')));\n";
				continue;
			}
			if ($field == 'owned_by') {
				echo "\t\techo \$this->Form->input('{$modelClass}.{$field}',\n";
				echo "\t\t\tarray('type' => 'select', 'options' => \$owners));\n";
				continue;
			}
			if ($field == 'assigned_to') {
				echo "\t\techo \$this->Form->input('{$modelClass}.{$field}',\n";
				echo "\t\t\tarray('type' => 'select', 'options' => \$assignedTos));\n";
				continue;
			}
			if ($field == 'password') {
				echo "\t\techo \$this->Form->input('{$modelClass}.new_{$field}',\n";
				echo "\t\t\tarray('label' => __('Password', true), 'type' => 'password');\n";
				continue;
			}
			echo "\t\techo \$this->Form->input('{$modelClass}.{$field}');\n";
		}
		if (!empty($associations['hasAndBelongsToMany'])) {
			foreach ($associations['hasAndBelongsToMany'] as $assocName => $assocData) {
				echo "\t\techo \$this->Form->input('{$assocName}');\n";
			}
		}
		echo "\t?>\n";
?>
<?php echo "<?php echo \$this->Form->submit(__('Submit', true), array('class' => 'awesome cake'));?>\n"; ?>
<?php echo "<?php echo \$this->Form->end();?>\n"; ?>
</div>
<div class="actions">
	<h3><?php echo "<?php __('Actions'); ?>"; ?></h3>
	<ul>
<?php if (strpos($action, 'add') === false): ?>
		<li><?php echo "<?php echo \$this->Html->link(__('Delete', true), array('action' => 'delete', \$this->Form->value('{$modelClass}.{$primaryKey}')), null, sprintf(__('Are you sure you want to delete # %s?', true), \$this->Form->value('{$modelClass}.{$primaryKey}'))); ?>";?></li>
<?php endif;?>
		<li><?php echo "<?php echo \$this->Html->link(sprintf(__('List %s', true), __('{$pluralHumanName}', true)), array('action' => 'index'));?>";?></li>
<?php
		$done = array();
		foreach ($associations as $type => $data) {
			foreach ($data as $alias => $details) {
				if ($details['controller'] != $this->name && !in_array($details['controller'], $done)) {
					if (in_array(Inflector::humanize(Inflector::underscore($alias)), array('Created By', 'Modified By'))) continue;
					echo "\t\t<li><?php echo \$this->Html->link(sprintf(__('List %s', true), __('" . Inflector::humanize($details['controller']) . "', true)), array('controller' => '{$details['controller']}', 'action' => 'index')); ?> </li>\n";
					echo "\t\t<li><?php echo \$this->Html->link(sprintf(__('New %s', true), __('" . Inflector::humanize(Inflector::underscore($alias)) . "', true)), array('controller' => '{$details['controller']}', 'action' => 'add')); ?> </li>\n";
					$done[] = $details['controller'];
				}
			}
		}
?>
	</ul>
</div>