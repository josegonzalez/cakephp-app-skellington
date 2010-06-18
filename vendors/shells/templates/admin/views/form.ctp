<?php
$invalid_fields = array('created', 'created_by', 'created_by_id', 'modified', 'modified_by', 'modified_by_id', 'updated', 'deleted');
$invalid_behavior_fields = array('lft', 'rght', 'slug');
$invalid_polymorphic_fields = array('class', 'foreign_id', 'model', 'model_id', 'model_key');
$original_fields = $fields;
foreach ($fields as $i => $field) {
	if (substr($field, -6) == '_count') unset($fields[$i]);
}
$fields = array_diff($fields, $invalid_fields);
$fields = array_diff($fields, $invalid_behavior_fields);
$fields = array_diff($fields, $invalid_polymorphic_fields);

$form_fields = array();
// Shuffle fields around for left and right input
$left_fields = array();
$right_fields = array();
$has_form_field = false;
foreach ($fields as $field) {
	if (substr($field, -10) == '_file_name') {
		$form_fields[$field] = substr($field, 0, -10);
		$right_fields[] = $field;
		$has_form_field = true;
		continue;
	}
	if (in_array($schema[$field]['type'], array('string', 'text'))) {
		$left_fields[] = $field;
	} else {
		$right_fields[] = $field;
	}
}
if ($has_form_field) $has_form_field = ", 'type' => 'file'";
?>
<h2 class="title"><?php echo "<?php echo sprintf(__('Add %s', true), __('{$pluralHumanName}', true)); ?>"; ?></h2>
<?php echo "<?php \$this->Html->h2(sprintf(__('Add %s', true), __('{$pluralHumanName}', true))); ?>\n"; ?>
<div class="inner">
	<?php echo "<?php echo \$this->Session->flash(); ?>\n"; ?>
<?php
echo "\t<?php echo \$this->Form->create('{$modelClass}', array(\n";
echo "\t\t'class' => 'form', 'inputDefaults' => array('div' => false, 'label' => false){$has_form_field})); ?>\n";
?>
<?php
	if (empty($left_fields) || empty($right_fields)) {
		echo "\t<?php\n";
		foreach ($fields as $field) {
			if (strpos($action, 'add') !== false && $field == $primaryKey) {
				continue;
			}
?>
	<div class="group">

<?php			if ($field != $primaryKey) {
				echo "\t\t\techo \$this->Form->label('{$modelClass}.{$field}', '" . Inflector::humanize(preg_replace('/_id$/', '', $field)) . "', array('class' => 'label'));\n";
			}
			if ($field == 'owned_by') {
				echo "\t\t\t\techo \$this->Form->input('{$modelClass}.{$field}',\n";
				echo "\t\t\t\t\tfarray('type' => 'select', 'options' => \$owners));\n"; ?>
	</div>
<?php			continue;
			}
			if ($field == 'assigned_to') {
				echo "\t\t\t\techo \$this->Form->input('{$modelClass}.{$field}',\n";
				echo "\t\t\t\t\tarray('type' => 'select', 'options' => \$assignedTos));\n"; ?>
	</div>
<?php			continue;
			}
			if ($field == 'password') {
				echo "\t\t\t<?php echo \$this->Form->input('{$modelClass}.new_{$field}', array('class' => 'text_field', 'type' => 'password')); ?>\n"; ?>
	</div>
<?php			continue;
			}
			if ($schema[$field]['type'] == 'boolean') {
				echo "\t\t\t\t<?php echo \$this->Form->input('{$modelClass}.{$field}', array('type' => 'select', 'options' => array('0' => 'No', '1' => 'Yes'))); ?>\n"; ?>
	</div>
<?php			continue;
			}

			if (strstr($field, 'description')) {
				echo "\t\t\techo \$this->Form->input('{$modelClass}.{$field}', array('type' => 'textarea', 'class' => 'text_area'));\n"; ?>
	</div>
<?php			continue;
			}

			if ($schema[$field]['type'] == 'string') {
				echo "\t\t\techo \$this->Form->input('{$modelClass}.{$field}', array('class' => 'text_field'));\n"; ?>
	</div>
<?php			continue;
			}
			echo "\t\t\techo \$this->Form->input('{$modelClass}.{$field}');\n";
			if ($field != $primaryKey) { ?>
</div>
<?php			continue;
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
	foreach ($left_fields as $field) {
		if (strpos($action, 'add') !== false && $field == $primaryKey) {
			continue;
		} ?>
				<div class="group">
<?php		echo "\t\t\t\t\t<?php echo \$this->Form->label('{$modelClass}.{$field}', '" . Inflector::humanize(preg_replace('/_id$/', '', $field)) . "', array('class' => 'label')); ?>\n";
		if ($field == 'password') {
			echo "\t\t\t\t\t<?php echo \$this->Form->input('{$modelClass}.new_{$field}',\n";
			echo "\t\t\t\t\t\tarray('class' => 'text_field', 'type' => 'password')); ?>\n"; ?>
				</div>
<?php			continue;
		}

		if (strstr($field, 'description')) {
			echo "\t\t\t\t\t<?php echo \$this->Form->input('{$modelClass}.{$field}', array('type' => 'textarea', 'class' => 'text_area')); ?>\n"; ?>
				</div>
<?php			continue;
		}

		if ($schema[$field]['type'] == 'string') {
			echo "\t\t\t\t\t<?php echo \$this->Form->input('{$modelClass}.{$field}', array('class' => 'text_field')); ?>\n"; ?>
				</div>
<?php			continue;
		}
		echo "\t\t\t\t\t<?php echo \$this->Form->input('{$modelClass}.{$field}'); ?>\n"; ?>
				</div>
<?php } ?>
			</div>
			<div class="column right">
<?php
	foreach ($right_fields as $field) {
		if (strpos($action, 'add') !== false && $field == $primaryKey) {
			continue;
		}
		if ($field != $primaryKey) { ?>
				<div class="group">
<?php				echo "\t\t\t\t\t<?php echo \$this->Form->label('{$modelClass}.{$field}', '" . Inflector::humanize(preg_replace('/_id$/', '', $field)) . "', array('class' => 'label')); ?>\n";
		}
		if (in_array($field, (array) array_keys($form_fields))) {
			echo "\t\t\t\t <?php echo \$this->Form->input('{$modelClass}.{$form_fields[$field]}', array('type' => 'file')); ?>\n"; ?>
				</div>
<?php		continue;
		}
		if ($field == 'owned_by') {
			echo "\t\t\t\t\t<?php echo \$this->Form->input('{$modelClass}.{$field}', array('type' => 'select', 'options' => \$owners)); ?>\n"; ?>
				</div>
<?php		continue;
		}
		if ($field == 'assigned_to') {
			echo "\t\t\t\t\t<?php echo \$this->Form->input('{$modelClass}.{$field}', array('type' => 'select', 'options' => \$assignedTos)); ?>\n"; ?>
				</div>
<?php		continue;
		}
		if ($schema[$field]['type'] == 'boolean') {
			echo "\t\t\t\t\t<?php echo \$this->Form->input('{$modelClass}.{$field}', array('type' => 'select', 'options' => array('0' => 'No', '1' => 'Yes'))); ?>\n"; ?>
				</div>
<?php		continue;
		}
		echo "\t\t\t\t\t<?php echo \$this->Form->input('{$modelClass}.{$field}'); ?>\n";
		if ($field != $primaryKey) { ?>
				</div>
<?php	}
	}
	if (!empty($associations['hasAndBelongsToMany'])) {
		foreach ($associations['hasAndBelongsToMany'] as $assocName => $assocData) {
			echo "\t\t\t\t<?php echo \$this->Form->input('{$assocName}'); ?> \n";
		}
	}
?>
			</div>
		</div>
<?php } ?>
		<div class="group navform wat-cf">
			<button class="button" type="submit">
				<?php echo "<?php echo \$this->Html->image('icons/tick.png', array('alt' => 'Save')); ?> Save\n"; ?>
			</button>
			<?php echo "<?php echo \$this->Html->link(\$this->Html->image('icons/cross.png', array('alt' => __('Cancel', true))) .' Cancel',\n"; ?>
			<?php echo "\tarray('action' => 'index'), array('escape' => false, 'class' => 'button')); ?>\n"; ?>
		</div>
<?php echo "\t<?php echo \$this->Form->end();?>\n"; ?>
</div>
<?php
echo "\n<?php \$this->Resource->secondary_navigation('Index', array('action' => 'index')); ?>";
if (strpos($action, 'add') === false) {
	$the_key = (in_array('slug', $original_fields)) ? 'slug' : $primaryKey;
	echo "\n<?php \$this->Resource->secondary_navigation('View', array('action' => 'view', \$this->data['{$modelClass}']['{$the_key}'])); ?>\n";
	echo "<?php \$this->Resource->secondary_navigation('Edit', array('action' => 'edit', \$this->data['{$modelClass}']['{$primaryKey}'] . '#')); ?>\n";
	echo "<?php \$this->Resource->secondary_navigation('Add', array('action' => 'add')); ?>\n";
	echo "<?php \$this->Resource->secondary_navigation('Delete', array('action' => 'delete', \$this->data['{$modelClass}']['{$primaryKey}']), null, sprintf(__('Are you sure you want to delete %s?', true), \$this->data['{$modelClass}']['{$displayField}'])); ?>";
}
?>