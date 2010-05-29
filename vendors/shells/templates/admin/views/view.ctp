<?php echo "<?php \$this->Html->h2(sprintf(__('{$pluralHumanName} - %s', true), \${$singularVar}['{$modelClass}']['{$displayField}'])); ?>\n"; ?>
<dl><?php echo "<?php \$i = 0; \$class = ' class=\"altrow\"';?>\n";?>
<?php
foreach ($fields as $field) {
	if ($field == $displayField) continue;
	$isKey = false;
	if (!empty($associations['belongsTo'])) {
		foreach ($associations['belongsTo'] as $alias => $details) {
			if ($field === $details['foreignKey']) {
				$isKey = true;
				echo "\t\t\t\t<dt<?php if (\$i % 2 == 0) echo \$class;?>><?php __('" . Inflector::humanize(Inflector::underscore($alias)) . "'); ?></dt>\n";
				echo "\t\t\t\t<dd<?php if (\$i++ % 2 == 0) echo \$class;?>>\n\t\t\t<?php echo \$this->Html->link(\${$singularVar}['{$alias}']['{$details['displayField']}'], array('controller' => '{$details['controller']}', 'action' => 'view', \${$singularVar}['{$alias}']['{$details['primaryKey']}'])); ?>\n\t\t\t&nbsp;\n\t\t</dd>\n";
				break;
			}
		}
	}
	if ($isKey !== true) {
		echo "\t\t\t\t<dt<?php if (\$i % 2 == 0) echo \$class;?>><?php __('" . Inflector::humanize($field) . "'); ?></dt>\n";
		echo "\t\t\t\t<dd<?php if (\$i++ % 2 == 0) echo \$class;?>>\n";
		echo "\t\t\t\t\t<?php echo \${$singularVar}['{$modelClass}']['{$field}']; ?>\n\t\t\t\t\t&nbsp;\n\t\t\t\t</dd>\n";
	}
}
?>
</dl>
<?php echo "<?php \$this->Resource->secondary_navigation('Index', array('action' => 'index')); ?>\n"?>
<?php echo "<?php \$this->Resource->secondary_navigation('Add', array('action' => 'add')); ?>"?>