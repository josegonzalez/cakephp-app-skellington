<?php $continueFields = array($primaryKey, $displayField, 'slug', 'deleted', 'password', 'modified_by', 'modified_by_id'); ?>
<div class="block" id="block-text">
	<div class="secondary-navigation">
		<ul class="wat-cf">
			<li class="first"><?php echo "<?php echo \$this->Html->link('Index', array('action' => 'index')); ?>"; ?></li>
<?php if (in_array('slug', $fields)) : ?>
			<li class="active"><?php echo "<?php echo \$this->Html->link('View', array('action' => 'view', \${$singularVar}['{$modelClass}']['slug'] . '#')); ?>"; ?></li>
<?php else : ?>
			<li class="active"><?php echo "<?php echo \$this->Html->link('View', array('action' => 'view', \${$singularVar}['{$modelClass}']['{$primaryKey}'] . '#')); ?>"; ?></li>
<?php endif; ?>
			<li><?php echo "<?php echo \$this->Html->link('Edit', array('action' => 'edit', \${$singularVar}['{$modelClass}']['{$primaryKey}'])); ?>"; ?></li>
			<li><?php echo "<?php echo \$this->Html->link('Add', array('action' => 'add')); ?>"; ?></li>
			<li>
				<?php echo "<?php echo \$this->Html->link(__('Delete', true),\n"; ?>
				<?php echo "\tarray('action' => 'delete', \${$singularVar}['{$modelClass}']['{$primaryKey}']),\n"; ?>
				<?php echo "\tarray('class' => 'delete-link', 'title' => \${$singularVar}['{$modelClass}']['{$displayField}'], 'rel' => \"DeleteForm{$modelClass}{\${$singularVar}['{$modelClass}']['{$primaryKey}']}\")); ?>\n"; ?>
				<?php echo "<?php echo \$this->Form->create('{$modelClass}', array(\n"; ?>
					<?php echo "'id' => \"DeleteForm{$modelClass}{\${$singularVar}['{$modelClass}']['{$primaryKey}']}\",\n"; ?>
					<?php echo "'url' => array('action' => 'delete', \${$singularVar}['{$modelClass}']['{$primaryKey}']))); ?>\n"; ?>
					<?php echo "<?php echo \$this->Form->input('{$modelClass}.{$primaryKey}', array(\n"; ?>
						<?php echo "'type' => 'hidden', 'value' => \${$singularVar}['{$modelClass}']['{$primaryKey}'])); ?>\n"?>
				<?php echo "<?php echo \$this->Form->end(); ?>\n"; ?>
			</li>
		</ul>
	</div>
	<div class="content">
		<h2 class="title"><?php echo "<?php echo \${$singularVar}['{$modelClass}']['{$displayField}']; ?>"; ?></h2>
		<?php echo "<?php \$this->Html->h2(\${$singularVar}['{$modelClass}']['{$displayField}']); ?>\n"; ?>
		<div class="inner">
			<?php echo "<?php echo \$this->Session->flash(); ?>\n"; ?>
			<dl>
<?php
$simple_block_meta = '';
foreach ($fields as $field) {
	if (in_array($field, $continueFields)) continue;
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
		$simple_block_meta .= "<?php \$simple_block_meta .= \$this->Resource->definition(\$this->Time->timeAgoInWords(\${$singularVar}['{$modelClass}']['{$field}'])); ?>\n";
		continue;
	}
	if (in_array($schema[$field]['type'], array('date', 'time', 'datetime'))) {
		$simple_block_meta .= "<?php \$simple_block_meta .= \$this->Resource->term(__('" . Inflector::humanize($field) . "', true)); ?>\n";
		$simple_block_meta .= "<?php \$simple_block_meta .= \$this->Resource->definition(\$this->Time->niceShort(\${$singularVar}['{$modelClass}']['{$field}'])); ?>\n";
		continue;
	}
	echo "\t\t\t\t<?php echo \$this->Resource->term(__('" . Inflector::humanize($field) . "', true)); ?>\n";
	echo "\t\t\t\t<?php echo \$this->Resource->definition(\${$singularVar}['{$modelClass}']['{$field}']); ?>\n";
}
?>
			</dl>
		</div>
	</div>
</div>
<?php if (!empty($associations['hasMany']) && in_array("{$singularHumanName}Comment", array_keys($associations['hasMany']))) : ?>
<div class="block" id="block-text">
	<div class="content">
		<h2 class="title"><?php echo "<?php __('$singularHumanName Comments'); ?>" ?></h2>
		<div class="inner">
<?php $commentDetails = $associations['hasMany']["{$singularHumanName}Comment"]; ?>
<?php $otherSingularVar = Inflector::variable("{$singularHumanName}Comment"); ?>
<?php $commentModel = ClassRegistry::init("{$singularHumanName}Comment"); ?>
<?php $commentSchema = $commentModel->schema(); ?>
			<?php echo "<?php foreach (\${$singularVar}['{$singularHumanName}Comment'] as \${$otherSingularVar}): ?>\n" ?>
				<dl>
<?php foreach ($commentDetails['fields'] as $field) : ?>
<?php	if (in_array($field, array_merge($continueFields, array("{$singularVar}_id", 'modified', 'updated')))) continue; ?>
					<?php echo "<?php echo \$this->Resource->term(__('" . Inflector::humanize($field) . "', true)); ?>\n"; ?>
<?php	if (in_array($commentSchema[$field]['type'], array('date', 'time', 'datetime'))) : ?>
					<?php echo "<?php echo \$this->Resource->definition(\$this->Time->timeAgoInWords(\${$otherSingularVar}['{$singularHumanName}Comment']['{$field}'])); ?>\n"; ?>
<?php	else : ?>
					<?php echo "<?php echo \$this->Resource->definition(\${$otherSingularVar}['{$singularHumanName}Comment']['{$field}']); ?>\n"; ?>
<?php	endif; ?>
<?php endforeach; ?>
				</dl>
				<br /><br />
				<hr />
			<?php echo "<?php endforeach; ?>\n"?>
			<?php echo "<?php echo \$this->Form->create('{$singularHumanName}', array(\n"; ?>
			<?php echo "\t'url' => array('action' => 'add_comment', \${$singularVar}['{$modelClass}']['$primaryKey']),\n"; ?>
			<?php echo "\t'class' => 'form', 'inputDefaults' => array('div' => false, 'label' => false))); ?>\n"; ?>
<?php foreach ($commentDetails['fields'] as $field) : ?>
<?php	if (in_array($field, array_merge($continueFields, array('created', 'modified', 'updated', 'created_by', 'created_by_id')))) continue; ?>
<?php		if ($field == "{$singularVar}_id"): ?>
				<?php echo "<?php echo \$this->Form->input('{$singularHumanName}Comment.{$field}', array(\n"; ?>
				<?php echo "\t'type' => 'hidden', 'value' => \${$singularVar}['{$modelClass}']['$primaryKey'])); ?>\n"; ?>
<?php			continue; ?>
<?php		endif; ?>
				<div class="group">
					<?php echo "<?php echo \$this->Form->label('{$singularHumanName}Comment.{$field}', '" . Inflector::humanize(preg_replace('/_id$/', '', $field)) . "', array('class' => 'label')); ?>\n"; ?>
<?php		if ($field == 'owned_by') : ?>
					<?php echo "<?php echo \$this->Form->input('{$modelClass}.{$field}',\n"; ?>
					<?php echo "\tfarray('type' => 'select', 'options' => \$owners));\n"; ?>
<?php		elseif ($field == 'assigned_to') : ?>
					<?php echo "<?php echo \$this->Form->input('{$modelClass}.{$field}', array(\n"; ?>
					<?php echo "\t'type' => 'select', 'options' => \$assignedTos));\n"; ?>
<?php		elseif ($field == 'password') : ?>
					<?php echo "<?php echo \$this->Form->input('{$modelClass}.new_{$field}', array(\n"; ?>
					<?php echo "\t'class' => 'text_field', 'type' => 'password')); ?>\n"; ?>
<?php		elseif ($commentSchema[$field]['type'] == 'boolean') : ?>
					<?php echo "<?php echo \$this->Form->input('{$modelClass}.{$field}', array(\n"; ?>
					<?php echo "\t'type' => 'select', 'options' => array('0' => 'No', '1' => 'Yes'))); ?>\n"; ?>
<?php		elseif (strstr($field, 'description') || strstr($field, 'content')) : ?>
					<?php echo "<?php echo \$this->Form->input('{$modelClass}.{$field}', array('type' => 'textarea', 'class' => 'text_area')); ?>\n"; ?>
<?php		elseif ($commentSchema[$field]['type'] == 'string') : ?>
					<?php echo "<?php echo \$this->Form->input('{$modelClass}.{$field}', array(\n"; ?>
					<?php echo "\t'class' => 'text_field')); ?>\n"; ?>
<?php		else : ?>
					<?php echo "<?php echo \$this->Form->input('{$singularHumanName}Comment.{$field}'); ?>\n"; ?>
<?php		endif; ?>
				</div>
<?php endforeach; ?>
				<div class="group navform wat-cf">
					<button class="button" type="submit">
						<?php echo "<?php echo \$this->Html->image('icons/tick.png', array('alt' => 'Save')); ?> Save\n"; ?>
					</button>
					<?php echo "<?php echo \$this->Html->link(\n"; ?>
					<?php echo "\t\$this->Html->image('icons/cross.png', array('alt' => __('Cancel', true))) .' Cancel',\n"; ?>
					<?php echo "\tarray('action' => 'index'), array('escape' => false, 'class' => 'button')); ?>\n"; ?>
				</div>
			<?php echo "<?php echo \$this->Form->end(); ?>\n"; ?>
		</div>
	</div>
</div>
<?php endif; ?>
<?php
	if (!empty($simple_block_meta)) {
		echo "\n<?php \$simple_block_meta = ''; ?>\n";
		echo "{$simple_block_meta}";
		echo "<?php echo \$this->Resource->sidebar_simple_block(__('{$singularHumanName} Metadata', true), \"<dl>{\$simple_block_meta}</dl>\"); ?>";
	}
?>
<?php if (!empty($associations['hasMany']) && in_array("{$singularHumanName}Attachment", array_keys($associations['hasMany']))) : ?>
<?php echo "\n<?php echo \$this->Resource->sidebar_simple_block(__('Attachments', true),\n"; ?>
	<?php echo " \$this->Form->create('{$singularHumanName}', array('url' => array('action' => 'add_attachment', \${$singularVar}['{$modelClass}']['$primaryKey']),\n"; ?>
			<?php echo "'class' => 'form', 'inputDefaults' => array('div' => false, 'label' => false), 'type' => 'file'))\n"; ?>
		<?php echo ". \$this->Form->input('{$singularHumanName}Attachment.{$singularVar}_id', array('type' => 'hidden', 'value' => \${$singularVar}['{$modelClass}']['$primaryKey']))\n"; ?>
		<?php echo ". '<div class=\"columns wat-cf\">'\n"; ?>
			<?php echo ". '<div class=\"group\">'\n"; ?>
				<?php echo ". \$this->Form->label('{$singularHumanName}Attachment.description', 'Description', array('class' => 'label'))\n"; ?>
				<?php echo ". \$this->Form->input('{$singularHumanName}Attachment.description', array('class' => 'text_field'))\n"; ?>
			<?php echo ". '</div>'\n"; ?>
			<?php echo ". '<div class=\"group\">'\n"; ?>
				<?php echo ". \$this->Form->input('{$singularHumanName}Attachment.attachment', array('type' => 'file'))\n"; ?>
			<?php echo ". '</div>'\n"; ?>
		<?php echo ". '</div>'\n"; ?>
	<?php echo ". \$this->Form->end('Submit')); ?>"; ?>
<?php endif; ?>
<?php echo "\n<?php echo \$this->Html->scriptBlock(\"
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