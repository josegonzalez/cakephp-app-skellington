<?php
$invalid_fields = array('created', 'created_by', 'created_by_id', 'modified', 'modified_by', 'modified_by_id', 'updated', 'deleted');
$invalid_behavior_fields = array('lft', 'rght', 'slug');
$invalid_polymorphic_fields = array('class', 'foreign_id', 'model', 'model_id', 'model_key');
$original_fields = $fields;
foreach ($fields as $i => $field) if (substr($field, -6) == '_count') unset($fields[$i]);
$fields = array_diff($fields, array_merge($invalid_fields, $invalid_behavior_fields, $invalid_polymorphic_fields));

$form_fields = $left_fields = $right_fields = array();
// Shuffle fields around for left and right input
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
if ($has_form_field) $has_form_field = ", 'type' => 'file'"; ?>
<div class="block" id="block-text">
	<div class="secondary-navigation">
		<ul class="wat-cf">
			<li class="first"><?php echo "<?php echo \$this->Html->link('Index', array('action' => 'index')); ?>"; ?></li>
<?php if (strpos($action, 'add') === false) : ?>
<?php $the_key = (in_array('slug', $original_fields)) ? 'slug' : $primaryKey; ?>
			<li><?php echo "<?php echo \$this->Html->link('View', array('action' => 'view', \$this->data['{$modelClass}']['{$the_key}'])); ?>"; ?></li>
			<li class="active"><?php echo "<?php echo \$this->Html->link('Edit', array('action' => 'edit', \$this->data['{$modelClass}']['{$primaryKey}'] . '#')); ?>"; ?></li>
			<li><?php echo "<?php echo \$this->Html->link('Add', array('action' => 'add')); ?>"; ?></li>
			<li>
				<?php echo "<?php echo \$this->Html->link(__('Delete', true),\n"; ?>
				<?php echo "\tarray('action' => 'delete', \$this->data['{$modelClass}']['{$primaryKey}']),\n"; ?>
				<?php echo "\tarray('class' => 'delete-link', 'title' => \$this->data['{$modelClass}']['{$displayField}'], 'rel' => \"DeleteForm{$modelClass}{\$this->data['{$modelClass}']['{$primaryKey}']}\")); ?>\n"; ?>
				<?php echo "<?php echo \$this->Form->create('{$modelClass}', array(\n"; ?>
					<?php echo "'id' => \"DeleteForm{$modelClass}{\$this->data['{$modelClass}']['{$primaryKey}']}\",\n"; ?>
					<?php echo "'action' => 'delete')); ?>\n"; ?>
					<?php echo "<?php echo \$this->Form->input('{$modelClass}.{$primaryKey}', array(\n"; ?>
						<?php echo "'type' => 'hidden', 'value' => \$this->data['{$modelClass}']['{$primaryKey}'])); ?>\n"?>
				<?php echo "<?php echo \$this->Form->end(); ?>\n"; ?>
			</li>
<?php else : ?>
			<li class="active"><?php echo"<?php echo \$this->Html->link('Add', array('action' => 'add', '#')); ?>"; ?></li>
<?php endif;?>
		</ul>
	</div>
	<div class="content">
		<h2 class="title"><?php echo "<?php echo sprintf(__('Add %s', true), __('{$pluralHumanName}', true)); ?>"; ?></h2>
		<div class="inner">
			<?php echo "<?php echo \$this->Session->flash(); ?>\n"; ?>
			<?php echo "<?php echo \$this->Form->create('{$modelClass}', array(\n"; ?>
			<?php echo "\t'class' => 'form', 'inputDefaults' => array('div' => false, 'label' => false){$has_form_field})); ?>\n"; ?>
<?php if (empty($left_fields) || empty($right_fields)) : ?>
<?php	foreach ($fields as $field) : ?>
<?php		if (strpos($action, 'add') !== false && $field == $primaryKey) continue;?>
			<div class="group">
<?php		if ($field != $primaryKey) : ?>
				<?php echo "<?php echo \$this->Form->label('{$modelClass}.{$field}', '" . Inflector::humanize(preg_replace('/_id$/', '', $field)) . "', array('class' => 'label')); ?>\n"; ?>
<?php		endif; ?>
<?php		if ($field == 'owned_by') : ?>
				<?php echo "<?php echo \$this->Form->input('{$modelClass}.{$field}',\n"; ?>
				<?php echo "\tarray('type' => 'select', 'options' => \$owners));\n"; ?>
			</div>
<?php		elseif ($field == 'assigned_to') : ?>
				<?php echo "<?php echo \$this->Form->input('{$modelClass}.{$field}', array(\n"; ?>
				<?php echo "\t'type' => 'select', 'options' => \$assignedTos));\n"; ?>
			</div>
<?php		elseif ($field == 'password') : ?>
				<?php echo "<?php echo \$this->Form->input('{$modelClass}.new_{$field}', array(\n"; ?>
				<?php echo "\t'class' => 'text_field', 'type' => 'password')); ?>\n"; ?>
			</div>
<?php		elseif ($schema[$field]['type'] == 'boolean') : ?>
				<?php echo "<?php echo \$this->Form->input('{$modelClass}.{$field}', array(\n"; ?>
				<?php echo "\t'type' => 'select', 'options' => array('0' => 'No', '1' => 'Yes'))); ?>\n"; ?>
			</div>
<?php		elseif (strstr($field, 'description') || strstr($field, 'content')) : ?>
				<?php echo "<?php echo \$this->Form->input('{$modelClass}.{$field}', array(\n"; ?>
				<?php echo "\t'type' => 'textarea', 'class' => 'text_area')); ?>\n"; ?>
			</div>
<?php		elseif ($schema[$field]['type'] == 'string') : ?>
				<?php echo "<?php echo \$this->Form->input('{$modelClass}.{$field}', array(\n"; ?>
				<?php echo "\t'class' => 'text_field')); ?>\n"; ?>
			</div>
<?php			continue; ?>
<?php		endif; ?>
				<?php echo "<?php echo \$this->Form->input('{$modelClass}.{$field}'); ?>\n"; ?>
<?php		if ($field != $primaryKey) : ?>
			</div>
<?php			continue; ?>
<?php		endif; ?>
<?php	endforeach; ?>
<?php	if (!empty($associations['hasAndBelongsToMany'])) : ?>
<?php		foreach ($associations['hasAndBelongsToMany'] as $assocName => $assocData) : ?>
				<?php echo "<?php echo \$this->Form->input('{$assocName}'); ?>\n"; ?>
<?php		endforeach; ?>
<?php	endif; ?>
<?php else : ?>
			<div class="columns wat-cf">
				<div class="column left">
<?php	foreach ($left_fields as $field) : ?>
<?php			if (strpos($action, 'add') !== false && $field == $primaryKey) continue; ?>
					<div class="group">
						<?php echo "<?php echo \$this->Form->label('{$modelClass}.{$field}',\n" ?>
						<?php echo "\t'" . Inflector::humanize(preg_replace('/_id$/', '', $field)) . "', array('class' => 'label')); ?>\n"; ?>
<?php				if ($field == 'password') : ?>
						<?php echo "<?php echo \$this->Form->input('{$modelClass}.new_{$field}', array(\n"; ?>
						<?php echo "\t'class' => 'text_field', 'type' => 'password')); ?>\n"; ?>
					</div>
<?php				elseif (strstr($field, 'description') || strstr($field, 'content')) : ?>
						<?php echo "<?php echo \$this->Form->input('{$modelClass}.{$field}', array(\n"; ?>
						<?php echo "\t'type' => 'textarea', 'class' => 'text_area')); ?>\n"; ?>
					</div>
<?php				elseif ($schema[$field]['type'] == 'string') : ?>
						<?php echo "<?php echo \$this->Form->input('{$modelClass}.{$field}', array(\n"; ?>
						<?php echo "\t'class' => 'text_field')); ?>\n"; ?>
					</div>
<?php				else : ?>
						<?php echo "<?php echo \$this->Form->input('{$modelClass}.{$field}'); ?>\n"; ?>
					</div>
<?php				endif; ?>
<?php	endforeach; ?>
				</div>
				<div class="column right">
<?php	foreach ($right_fields as $field) : ?>
<?php			if (strpos($action, 'add') !== false && $field == $primaryKey) continue; ?>
<?php				if ($field != $primaryKey) : ?>
					<div class="group">
						<?php echo "<?php echo \$this->Form->label('{$modelClass}.{$field}',\n" ?>
						<?php echo "\t'" . Inflector::humanize(preg_replace('/_id$/', '', $field)) . "', array('class' => 'label')); ?>\n"; ?>
<?php				endif; ?>
<?php			if (in_array($field, (array) array_keys($form_fields))) : ?>
						<?php echo "<?php echo \$this->Form->input('{$modelClass}.{$form_fields[$field]}', array('type' => 'file')); ?>\n"; ?>
					</div>
<?php			elseif ($field == 'owned_by') : ?>
						<?php echo "<?php echo \$this->Form->input('{$modelClass}.{$field}', array(\n"; ?>
						<?php echo "\t'type' => 'select', 'options' => \$owners)); ?>\n"; ?>
					</div>
<?php			elseif ($field == 'assigned_to') : ?>
						<?php echo "<?php echo \$this->Form->input('{$modelClass}.{$field}', array(\n"; ?>
						<?php echo "\t'type' => 'select', 'options' => \$assignedTos)); ?>\n"; ?>
					</div>
<?php			elseif ($schema[$field]['type'] == 'boolean') : ?>
						<?php echo "<?php echo \$this->Form->input('{$modelClass}.{$field}', array(\n"; ?>
						<?php echo "\t'type' => 'select', 'options' => array('0' => 'No', '1' => 'Yes'))); ?>\n"; ?>
					</div>
<?php			else : ?>
						<?php echo "<?php echo \$this->Form->input('{$modelClass}.{$field}'); ?>\n"; ?>
<?php				if ($field != $primaryKey) : ?>
					</div>
<?php	 			endif; ?>
<?php	 		endif; ?>
<?php	endforeach; ?>
<?php	if (!empty($associations['hasAndBelongsToMany'])) : ?>
<?php		foreach ($associations['hasAndBelongsToMany'] as $assocName => $assocData) : ?>
					<div class="group">
						<?php echo "<?php echo \$this->Form->input('{$assocName}'); ?> \n"; ?>
					</div>
<?php		endforeach; ?>
<?php	endif; ?>
				</div>
			</div>
<?php endif; ?>
			<div class="group navform wat-cf">
				<button class="button" type="submit">
					<?php echo "<?php echo \$this->Html->image('icons/tick.png', array('alt' => 'Save')); ?> Save\n"; ?>
				</button>
				<?php echo "<?php echo \$this->Html->link(\n"; ?>
				<?php echo "\t\$this->Html->image('icons/cross.png', array('alt' => __('Cancel', true))) .' Cancel',\n"; ?>
				<?php echo "\tarray('action' => 'index'), array('escape' => false, 'class' => 'button')); ?>\n"; ?>
			</div>
		<?php echo "\t<?php echo \$this->Form->end();?>\n"; ?>
		</div>
	</div>
</div>
<?php echo "<?php echo \$this->Html->scriptBlock(\"
(function($){
    $.fn.deleteForm = function() {
        return this.each(function(){
            \\\$this = $(this);
            \\\$this.click(function(){
                var answer = confirm('Are you sure you want to delete \\\"' + \\\$this.attr('title') + '\\\"?');
                if (answer) \$('#' + \\\$this.attr('rel')).submit();
                return false;
            });
        });
    };
}(jQuery));

$('.delete-link').deleteForm();\"); ?>"?>