<?php echo "<?php \$this->Html->h2(sprintf(__('{$pluralHumanName} - %s', true), \${$singularVar}['{$modelClass}']['{$displayField}'])); ?>\n"; ?>
<dl>
<?php
foreach ($fields as $field) {
	if ($field == $displayField) continue;
	$isKey = false;
	if (!empty($associations['belongsTo'])) {
		foreach ($associations['belongsTo'] as $alias => $details) {
			if ($field === $details['foreignKey']) {
				$isKey = true;
				echo "\t<?php echo \$this->Resource->term(__('" . Inflector::humanize(Inflector::underscore($alias)) . "', true)); ?>\n";
				echo "\t<?php echo \$this->Resource->definition(\${$singularVar}['{$alias}']['{$details['displayField']}'], array('controller' => '{$details['controller']}', 'action' => 'view', \${$singularVar}['{$alias}']['{$details['primaryKey']}'])); ?>\n";
				break;
			}
		}
	}
	if ($isKey !== true) {
		echo "\t<?php echo \$this->Resource->term(__('" . Inflector::humanize($field) . "', true)); ?>\n";
		echo "\t<?php echo \$this->Resource->definition(\${$singularVar}['{$modelClass}']['{$field}']); ?>\n";
	}
}
?>
</dl>
<?php echo "<?php \$this->Resource->secondary_navigation('Index', array('action' => 'index')); ?>\n"?>
<?php echo "<?php \$this->Resource->secondary_navigation('Edit', array('action' => 'edit', \${$singularVar}['{$modelClass}']['{$primaryKey}'])); ?>"?>
<?php echo "<?php \$this->Resource->secondary_navigation('Add', array('action' => 'add')); ?>"?>
<?php echo "<?php \$this->Resource->secondary_navigation('Delete', array('action' => 'delete', \${$singularVar}['{$modelClass}']['{$primaryKey}']), null, sprintf(__('Are you sure you want to delete # %s?', true), \${$singularVar}['{$modelClass}']['{$primaryKey}'])); ?>\n";
?>