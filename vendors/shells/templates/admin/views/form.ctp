<?php
$invalidFields = array();
$invalid_fields = array('created', 'created_by', 'created_by_id', 'modified', 'modified_by', 'modified_by_id', 'updated');
$invalid_behavior_fields = array('lft', 'rght', 'slug');
$invalid_polymorphic_fields = array('class', 'foreign_id', 'model', 'model_id', 'model_key');
$original_fields = $fields;
foreach ($fields as $i => $field) {
	if (substr($field, -6) == '_count') unset($fields[$i]);
}
$fields = array_diff($fields, $invalid_fields);
$fields = array_diff($fields, $invalid_behavior_fields);
$fields = array_diff($fields, $invalid_polymorphic_fields);

?>
<?php echo "<?php \$this->Html->h2(sprintf(__('Add %s', true), __('{$pluralHumanName}', true))); ?>\n"; ?>
<?php echo "<?php echo \$this->Form->create('{$modelClass}', array('class' => 'form', 'inputDefaults' => array('div' => false, 'label' => false)));?>\n";?>
<?php
	// Shuffle fields around for left and right input
	$left_fields = array();
	$right_fields = array();
	foreach ($fields as $field) {
		if (in_array($schema[$field]['type'], array('string', 'text'))) {
			$left_fields[] = $field;
		} else {
			$right_fields[] = $field;
		}
	}

	if (empty($left_fields) || empty($right_fields)) {
		echo "\t<?php\n";
		foreach ($fields as $field) {
			if (strpos($action, 'add') !== false && $field == $primaryKey) {
				continue;
			}
			echo "\t\techo '<div class=\"group\">';\n";
			if ($field != $primaryKey) {
				echo "\t\t\techo \$this->Form->label('{$modelClass}.{$field}', '" . Inflector::humanize(preg_replace('/_id$/', '', $field)) . "', array('class' => 'label'));\n";
			}
			if ($field == 'owned_by') {
				echo "\t\t\techo \$this->Form->input('{$modelClass}.{$field}',\n";
				echo "\t\t\t\tarray('type' => 'select', 'options' => \$owners));\n";
				echo "\t\techo '</div>';\n";
				continue;
			}
			if ($field == 'assigned_to') {
				echo "\t\t\techo \$this->Form->input('{$modelClass}.{$field}',\n";
				echo "\t\t\t\tarray('type' => 'select', 'options' => \$assignedTos));\n";
				echo "\t\techo '</div>';\n";
				continue;
			}
			if ($field == 'password') {
				echo "\t\t\techo \$this->Form->input('{$modelClass}.new_{$field}',\n";
				echo "\t\t\t\tarray('class' => 'text_field', 'type' => 'password'));\n";
				echo "\t\techo '</div>';\n";
				continue;
			}
			if ($schema[$field]['type'] == 'boolean') {
				echo "\t\t\techo \$this->Form->input('{$modelClass}.{$field}',\n";
				echo "\t\t\t\tarray('type' => 'select', 'options' => array('0' => 'No', '1' => 'Yes')));\n";
				echo "\t\techo '</div>';\n";
				continue;
			}

			if (strstr($field, 'description')) {
				echo "\t\t\techo \$this->Form->input('{$modelClass}.{$field}', array('type' => 'textarea', 'class' => 'text_area'));\n";
				echo "\t\techo '</div>';\n";
				continue;
			}

			if ($schema[$field]['type'] == 'string') {
				echo "\t\t\techo \$this->Form->input('{$modelClass}.{$field}', array('class' => 'text_field'));\n";
				echo "\t\techo '</div>';\n";
				continue;
			}
			echo "\t\t\techo \$this->Form->input('{$modelClass}.{$field}');\n";
			if ($field != $primaryKey) {
				echo "\t\techo '</div>';\n";
			}
		}
		if (!empty($associations['hasAndBelongsToMany'])) {
			foreach ($associations['hasAndBelongsToMany'] as $assocName => $assocData) {
				echo "\t\techo \$this->Form->input('{$assocName}');\n";
			}
		}
		echo "\t?>\n";
	} else {
	?>
	<div class="columns wat-cf">
		<div class="column left">
<?php
	echo "\t<?php\n";
	foreach ($left_fields as $field) {
		if (strpos($action, 'add') !== false && $field == $primaryKey) {
			continue;
		}
		echo "\t\techo '<div class=\"group\">';\n";
		echo "\t\t\techo \$this->Form->label('{$modelClass}.{$field}', '" . Inflector::humanize(preg_replace('/_id$/', '', $field)) . "', array('class' => 'label'));\n";
		if ($field == 'password') {
			echo "\t\t\techo \$this->Form->input('{$modelClass}.new_{$field}',\n";
			echo "\t\t\t\tarray('class' => 'text_field', 'type' => 'password'));\n";
			echo "\t\techo '</div>';\n";
			continue;
		}

		if (strstr($field, 'description')) {
			echo "\t\t\techo \$this->Form->input('{$modelClass}.{$field}', array('type' => 'textarea', 'class' => 'text_area'));\n";
			echo "\t\techo '</div>';\n";
			continue;
		}

		if ($schema[$field]['type'] == 'string') {
			echo "\t\t\techo \$this->Form->input('{$modelClass}.{$field}', array('class' => 'text_field'));\n";
			echo "\t\techo '</div>';\n";
			continue;
		}
		echo "\t\t\techo \$this->Form->input('{$modelClass}.{$field}');\n";
		echo "\t\techo '</div>';\n";
	}
	echo "\t?>\n";
?>
		</div>
		<div class="column right">
<?php
	echo "\t<?php\n";
	foreach ($right_fields as $field) {
		if (strpos($action, 'add') !== false && $field == $primaryKey) {
			continue;
		}
		if ($field != $primaryKey) {
			echo "\t\techo '<div class=\"group\">';\n";
			echo "\t\t\techo \$this->Form->label('{$modelClass}.{$field}', '" . Inflector::humanize(preg_replace('/_id$/', '', $field)) . "', array('class' => 'label'));\n";
		}
		if ($field == 'owned_by') {
			echo "\t\t\techo \$this->Form->input('{$modelClass}.{$field}',\n";
			echo "\t\t\t\tarray('type' => 'select', 'options' => \$owners));\n";
			echo "\t\techo '</div>';\n";
			continue;
		}
		if ($field == 'assigned_to') {
			echo "\t\t\techo \$this->Form->input('{$modelClass}.{$field}',\n";
			echo "\t\t\t\tarray('type' => 'select', 'options' => \$assignedTos));\n";
			echo "\t\techo '</div>';\n";
			continue;
		}
		if ($schema[$field]['type'] == 'boolean') {
			echo "\t\t\techo \$this->Form->input('{$modelClass}.{$field}',\n";
			echo "\t\t\t\tarray('type' => 'select', 'options' => array('0' => 'No', '1' => 'Yes')));\n";
			echo "\t\techo '</div>';\n";
			continue;
		}
		echo "\t\t\techo \$this->Form->input('{$modelClass}.{$field}');\n";
		if ($field != $primaryKey) {
			echo "\t\techo '</div>';\n";
		}
	}
	if (!empty($associations['hasAndBelongsToMany'])) {
		foreach ($associations['hasAndBelongsToMany'] as $assocName => $assocData) {
			echo "\t\techo \$this->Form->input('{$assocName}');\n";
		}
	}
	echo "\t?>\n";
?>
		</div>
	</div>
	<?php } ?>
	<div class="group navform wat-cf">
			<button class="button" type="submit">
				<?php echo "<?php echo \$this->Html->image('icons/tick.png', array('alt' => 'Save')); ?> Save\n"; ?>
			</button>
			<?php echo "<?php echo \$this->Html->link(\$this->Html->image('icons/cross.png', array('alt' => 'Cancel')) .' Cancel',\n"; ?>
			<?php echo "\tarray('action' => 'index'), array('escape' => false, 'class' => 'button')); ?>\n"; ?>
	</div>
<?php echo "<?php echo \$this->Form->end();?>\n"; ?>
<?php
echo "<?php \$this->Resource->secondary_navigation('Index', array('action' => 'index')); ?>\n";
if (strpos($action, 'add') === false) {
	$the_key = (in_array('slug', $original_fields)) ? 'slug' : $primaryKey;
	echo "<?php \$this->Resource->secondary_navigation('View', array('action' => 'view', \$this->data['{$modelClass}']['{$the_key}'])); ?>\n";
	echo "<?php \$this->Resource->secondary_navigation('Add', array('action' => 'add')); ?>\n";
	echo "<?php \$this->Resource->secondary_navigation('Delete', array('action' => 'delete', \$this->data['{$modelClass}']['{$primaryKey}']), null, sprintf(__('Are you sure you want to delete %s?', true), \$this->data['{$modelClass}']['{$displayField}'])); ?>\n";
}
?>