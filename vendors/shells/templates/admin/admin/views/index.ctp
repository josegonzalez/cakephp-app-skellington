<?php
	$doActions = true;
	$related_action_associations = $associations;
	$view_params = $this->params;

	$invalid_fields = array('created', 'created_by', 'created_by_id', 'modified', 'modified_by', 'modified_by_id', 'updated', 'owned_by', 'owner_id');
	$invalid_address_fields = array('address_one', 'address_two', 'state', 'zip_code');
	$invalid_behavior_fields = array('lft', 'parent_id', 'position', 'rght', 'slug');
	$invalid_contact_fields = array('address', 'cell_number', 'city', 'fax_number', 'latitude', 'location', 'longitude', 'phone_number', 'state_id', 'zipcode');
	$invalid_content_fields = array('body', 'content', 'contents', 'description', 'text', 'interests');
	$invalid_file_fields = array('dir', 'filename', 'filesize', 'mimetype', 'picture');
	$invalid_online_contact_fields = array('email', 'email_address', 'site', 'url', 'website');
	$invalid_polymorphic_fields = array('class', 'foreign_id', 'model', 'model_id', 'model_key', 'type', 'type_id');
	$invalid_relation_fields = array('milestone_id');
	$invalid_time_fields = array('date', 'time', 'when', 'date_due_by');
	$invalid_user_fields = array('psword', 'password', 'photo', 'profile_picture', 'user_icon', 'aim', 'icq', 'yahoo', 'msnm', 'msn', 'jabber');
	$invalid_visibility_fields = array('enabled', 'deleted', 'published', 'visible');

	$invalid_fashion_fields = array('season', 'main_color_id', 'received_date', 'sold_for_price', 'original_retail_price', 'purchase_price', 'retail_source', 'sell_date');

	foreach ($schema as $key => $value) {
		if (in_array($value['type'], array('text', 'datetime')) and !in_array($key, array_keys($this->params))) {
			$invalid_fields[] = $key;
		} elseif (substr($key, -6) === '_count') {
			$invalid_fields[] = $key;
		} elseif (substr($key, -5) === '_time') {
			$invalid_time_fields[] = $key;
		} elseif (substr($key, -4) === '_url') {
			$invalid_online_contact_fields[] = $key;
		} elseif (substr($key, -4) === '_dir') {
			$invalid_file_fields[] = $key;
		} elseif (substr($key, -9) === '_mimetype') {
			$invalid_file_fields[] = $key;
		} elseif (substr($key, -9) === '_filesize') {
			$invalid_file_fields[] = $key;
		} elseif (substr($key, -6) === '_photo') {
			$invalid_file_fields[] = $key;
		}
		if (strstr($key, 'password') !== false) {
			$invalid_user_fields[] = $key;
		}
		if (strstr($key, '_token') !== false) {
			$invalid_user_fields[] = $key;
		}
	}

	$invalid_field_types = array(
		'show_address_fields' => 'invalid_address_fields',
		'show_behavior_fields' => 'invalid_behavior_fields',
		'show_contact_fields' => 'invalid_contact_fields',
		'show_content_fields' => 'invalid_content_fields',
		'show_file_fields' => 'invalid_file_fields',
		'show_online_contact_fields' => 'invalid_online_contact_fields',
		'show_polymorphic_fields' => 'invalid_polymorphic_fields',
		'show_relation_fields' => 'invalid_relation_fields',
		'show_time_fields' => 'invalid_time_fields',
		'show_user_fields' => 'invalid_user_fields',
		'show_visibility_fields' => 'invalid_visibility_fields',
		'show_fashion_fields' => 'invalid_fashion_fields'
	);

	foreach ($invalid_field_types as $key => $value) {
		if (!in_array($key, array_keys($view_params))) {
			unset($view_params[$key]);
			$invalid_fields = array_merge($$value, $invalid_fields);
		}
	}

	foreach ($view_params as $key => $value) {
		if (substr($key, 0, 8) === 'noindex:') {
			$invalid_fields[] = substr($key, 8);
			continue;
		}
		if (substr($key, 0, 6) === 'onindex:') {
			$invalid_fields = array_diff($invalid_fields, array(substr($key, 0, 6)));
			continue;
		}
	}

	$fields = array_diff($fields, $invalid_fields);

	foreach ($fields as $field) {
		if (substr($field, -3) === '_id' && $field != 'user_id') {
			$fields = array_diff($fields, array($field));
			$fields[] = $field;
		} else if (substr($field, -10) == '_file_name') {
			$fields = array_diff($fields, array($field));
		}
	}
?>
<div class="block" id="block-text">
	<div class="secondary-navigation">
		<ul class="wat-cf">
			<li class="first active"><?php echo "<?php echo \$this->Html->link('Index', array('action' => 'index', '#')); ?>\n"?></li>
			<li><?php echo "<?php echo \$this->Html->link('Add', array('action' => 'add')); ?>"?></li>
		</ul>
	</div>
	<div class="content">
		<h2 class="title"><?php echo "<?php echo __('{$pluralHumanName}', true); ?>"; ?></h2>
		<?php echo "<?php \$this->Html->h2(__('{$pluralHumanName}', true)); ?>\n"; ?>
		<div class="inner">
			<?php echo "<?php echo \$this->Session->flash(); ?>\n"; ?>
			<table class="table">
				<tr>
<?php $first_field = true; ?>
<?php foreach ($fields as $field):?>
					<th<?php if ($first_field) {echo ' class="first"';$first_field = false;}?>><?php echo "<?php echo \$this->Paginator->sort('" . Inflector::humanize(preg_replace('/_id$/', '', $field)) . "', '{$modelClass}.{$field}');?>";?></th>
<?php endforeach;?>
					<?php if ($doActions) : ?><?php echo "<th class=\"actions last\">&nbsp;</th>\n";?><?php endif; ?>
				</tr>
				<?php echo "<?php \$i = 0; foreach (\${$pluralVar} as \${$singularVar}) : ?>\n"; ?>
				<?php echo "<tr<?php echo (\$i++ % 2 == 0) ? ' class=\"altrow odd\"' : 'even';?>>\n"; ?>
<?php foreach ($fields as $field) : ?>
<?php	$isKey = false; ?>
<?php	if (!empty($associations['belongsTo'])) : ?>
<?php		foreach ($associations['belongsTo'] as $alias => $details) : ?>
<?php			if ($field === $details['foreignKey']) : ?>
<?php				$isKey = true; ?>
<?php				if (is_object($aliased_model = ClassRegistry::init($alias))) : ?>
<?php					if (in_array('slug', array_keys($aliased_model->schema()))) $details['primaryKey'] = 'slug'; ?>
<?php				endif; ?>
					<?php echo "<td><?php echo \$this->Html->link(\${$singularVar}['{$alias}']['{$details['displayField']}'],\n"; ?>
						<?php echo "\tarray('controller' => '{$details['controller']}', 'action' => 'view', \${$singularVar}['{$alias}']['{$details['primaryKey']}'])); ?></td>\n"; ?>
<?php				break; ?>
<?php			endif; ?>
<?php		endforeach; ?>
<?php	endif; ?>
<?php	if ($isKey !== true) : ?>
<?php		if ($field == $displayField) : ?>
					<?php echo "<td><?php echo \$html->link(\${$singularVar}['{$modelClass}']['{$field}'], array(\n"; ?>
<?php			if (in_array('slug', $fields)) : ?>
					<?php echo "\t'action' => 'edit', \${$singularVar}['{$modelClass}']['slug'])); ?>&nbsp;</td>\n"; ?>
<?php			else : ?>
					<?php echo "\t'action' => 'edit', \${$singularVar}['{$modelClass}']['{$primaryKey}'])); ?>&nbsp;</td>\n"; ?>
<?php			endif; ?>
<?php		elseif ($schema[$field]['type'] == 'datetime') : ?>
					<?php echo "<td><?php echo \$this->Time->niceShort(\${$singularVar}['{$modelClass}']['{$field}']); ?>&nbsp;</td>\n"; ?>
<?php		elseif ($schema[$field]['type'] == 'date') : ?>
					<?php echo "<td><?php echo \$this->Time->timeAgoInWords(\${$singularVar}['{$modelClass}']['{$field}']); ?>&nbsp;</td>\n"; ?>
<?php		elseif ($schema[$field]['type'] == 'time') : ?>
					<?php echo "<td><?php echo \$this->Time->time(\${$singularVar}['{$modelClass}']['{$field}']); ?>&nbsp;</td>\n"; ?>
<?php		elseif (in_array($field, array('created_by', 'modified_by', 'created_by_id', 'modified_by_id'))) : ?>
					<?php echo "<td><?php echo \$this->Html->link(\${$singularVar}['" . Inflector::classify($field) . "']['username'],\n"; ?>
					<?php echo "\narray('controller' => 'users', 'action' => 'view', \${$singularVar}['{$modelClass}']['{$field}'],"; ?>
					<?php echo " Inflector::slug(\${$singularVar}['". Inflector::classify($field) . "']['username']))); ?></td>\n"; ?>
<?php		else : ?>
					<?php echo "<td><?php echo \${$singularVar}['{$modelClass}']['{$field}']; ?>&nbsp;</td>\n"; ?>
<?php		endif; ?>
<?php	endif; ?>
<?php endforeach; ?>
<?php if ($doActions) : ?>
					<?php echo "<td class=\"actions last\">\n"; ?>
						<?php echo "<?php echo \$this->Html->link(__('Show', true),\n"; ?>
<?php	if (in_array('slug', $fields)) : ?>
						<?php echo "\tarray('action' => 'view', \${$singularVar}['{$modelClass}']['slug'])); ?> | \n"; ?>
<?php	else : ?>
						<?php echo "\tarray('action' => 'view', \${$singularVar}['{$modelClass}']['{$primaryKey}'])); ?> | \n"; ?>
<?php	endif; ?>
						<?php echo "<?php echo \$this->Html->link(__('Edit', true),\n"; ?>
						<?php echo "\tarray('action' => 'edit', \${$singularVar}['{$modelClass}']['{$primaryKey}'])); ?> | \n"; ?>
						<?php echo "<?php echo \$this->Html->link(__('Delete', true),\n"; ?>
						<?php echo "\tarray('action' => 'delete', \${$singularVar}['{$modelClass}']['{$primaryKey}']),\n"; ?>
						<?php echo "\tnull, sprintf(__('Are you sure you want to delete %s?', true), \${$singularVar}['{$modelClass}']['{$displayField}'])); ?>\n"; ?>
					</td>
<?php endif; ?>
				</tr>
			<?php echo "\t<?php endforeach; ?>\n"; ?>
			</table>
		</div>
		<div class="actions-bar wat-cf">
			<div class="pagination">
				<?php echo "<?php echo \$this->Paginator->prev('« '.__('Previous', true), array('class' => 'next_page'), null, array('class' => 'disabled')); ?>\n";?>
				<?php echo "<?php echo \$this->Paginator->numbers(array('separator' => false)); ?>\n"?>
				<?php echo "<?php echo \$this->Paginator->next(__('Next', true).' »', array('rel' => 'next', 'class' => 'next_page'), null, array('class' => 'disabled')); ?>\n";?>
			</div>
		</div>
	</div>
</div>