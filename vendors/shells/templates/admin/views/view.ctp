<?php echo "<?php \$this->Html->h2(\${$singularVar}['{$modelClass}']['{$displayField}']); ?>\n"; ?>
<?php echo "<?php \$simple_block_meta = ''; ?>\n"; ?>
<dl>
<?php
$simple_block_meta = '';
foreach ($fields as $field) {
	if (in_array($field, array($primaryKey, $displayField, 'slug', 'deleted', 'modified_by', 'modified_by_id'))) continue;
	$isKey = false;
	if (!empty($associations['belongsTo'])) {
		foreach ($associations['belongsTo'] as $alias => $details) {
			if ($field === $details['foreignKey']) {
				$isKey = true;
				$simple_block_meta .= "<?php \$simple_block_meta .= \$this->Resource->term(__('" . Inflector::humanize(Inflector::underscore($alias)) . "', true)); ?>\n";
				$simple_block_meta .= "<?php \$simple_block_meta .= \$this->Resource->definition(\${$singularVar}['{$alias}']['{$details['displayField']}'],\n\tarray('controller' => '{$details['controller']}', 'action' => 'view', \${$singularVar}['{$alias}']['{$details['primaryKey']}'])); ?>\n";
				break;
			}
		}
	}
	if ($isKey === true) {
		continue;
	}
	if ($schema[$field]['type'] == 'boolean') {
		$simple_block_meta .= "<?php \$simple_block_meta .= \$this->Resource->term(__('" . Inflector::humanize($field) . "', true)); ?>\n";
		$simple_block_meta .= "<?php \$simple_block_meta .= \$this->Resource->definition(\${$singularVar}['{$modelClass}']['{$field}'], array(), array('type' => 'boolean')); ?>\n";
		continue;
	}
	if (in_array($field, array('created', 'modified', 'updated'))) {
		$simple_block_meta .= "<?php \$simple_block_meta .= \$this->Resource->term(__('" . Inflector::humanize($field) . "', true)); ?>\n";
		$simple_block_meta .= "<?php \$simple_block_meta .= \$this->Resource->definition(\${$singularVar}['{$modelClass}']['{$field}']); ?>\n";
		continue;
	}
	echo "\t<?php echo \$this->Resource->term(__('" . Inflector::humanize($field) . "', true)); ?>\n";
	echo "\t<?php echo \$this->Resource->definition(\${$singularVar}['{$modelClass}']['{$field}']); ?>\n";
}
?>
</dl>
<?php
	echo "<?php \$this->Resource->secondary_navigation('Index', array('action' => 'index')); ?>\n";
	if (in_array('slug', $fields)) {
		echo "<?php \$this->Resource->secondary_navigation('View', array('action' => 'view', \${$singularVar}['{$modelClass}']['slug'] . '#')); ?>\n";
	} else {
		echo "<?php \$this->Resource->secondary_navigation('View', array('action' => 'view', \${$singularVar}['{$modelClass}']['{$primaryKey}'] . '#')); ?>\n";
	}
	echo "<?php \$this->Resource->secondary_navigation('Edit', array('action' => 'edit', \${$singularVar}['{$modelClass}']['{$primaryKey}'])); ?>\n";
	echo "<?php \$this->Resource->secondary_navigation('Add', array('action' => 'add')); ?>\n";
	echo "<?php \$this->Resource->secondary_navigation('Delete', array('action' => 'delete', \${$singularVar}['{$modelClass}']['{$primaryKey}']), null, sprintf(__('Are you sure you want to delete %s?', true), \${$singularVar}['{$modelClass}']['{$displayField}'])); ?>\n";
	if (!empty($simple_block_meta)) {
		echo "\n{$simple_block_meta}";
		echo "<?php echo \$this->Resource->sidebar_simple_block(__('{$singularHumanName} Metadata', true), \"<dl>{\$simple_block_meta}</dl>\"); ?>\n";
	}
?>