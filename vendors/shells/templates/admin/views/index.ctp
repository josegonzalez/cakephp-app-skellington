<?php
	$doActions = true;
	$related_action_associations = $associations;
	$view_params = $this->params;

	$invalid_fields = array('created', 'created_by', 'created_by_id', 'modified', 'modified_by', 'modified_by_id', 'updated', 'owned_by', 'owner_id');
	$invalid_contact_fields = array('address', 'cell_number', 'city', 'fax_number', 'latitude', 'location', 'longitude', 'phone_number', 'state_id', 'zipcode');
	$invalid_behavior_fields = array('lft', 'parent_id', 'position', 'rght', 'slug');
	$invalid_content_fields = array('body', 'content', 'contents', 'description', 'text');
	$invalid_file_fields = array('dir', 'filename', 'filesize', 'mimetype', 'picture');
	$invalid_online_contact_fields = array('email', 'email_address', 'site', 'url', 'website');
	$invalid_polymorphic_fields = array('class', 'foreign_id', 'model', 'model_id', 'model_key', 'type', 'type_id');
	$invalid_time_fields = array('date', 'time', 'when');
	$invalid_user_fields = array('password', 'photo', 'profile_picture');
	$invalid_visibility_fields = array('enabled', 'deleted', 'published', 'visible');

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
	}

	if (!in_array('show_behavior_fields', array_keys($view_params))) {
		unset($view_params['show_behavior_fields']);
		$invalid_fields = array_merge($invalid_behavior_fields, $invalid_fields);
	}

	if (!in_array('show_contact_fields', array_keys($view_params))) {
		unset($view_params['show_contact_fields']);
		$invalid_fields = array_merge($invalid_contact_fields, $invalid_fields);
	}

	if (!in_array('show_content_fields', array_keys($view_params))) {
		unset($view_params['show_content_fields']);
		$invalid_fields = array_merge($invalid_content_fields, $invalid_fields);
	}

	if (!in_array('show_file_fields', array_keys($view_params))) {
		unset($view_params['show_file_fields']);
		$invalid_fields = array_merge($invalid_file_fields, $invalid_fields);
	}

	if (!in_array('show_online_contact_fields', array_keys($view_params))) {
		unset($view_params['show_online_contact_fields']);
		$invalid_fields = array_merge($invalid_online_contact_fields, $invalid_fields);
	}

	if (!in_array('show_polymorphic_fields', array_keys($view_params))) {
		unset($view_params['show_polymorphic_fields']);
		$invalid_fields = array_merge($invalid_polymorphic_fields, $invalid_fields);
	}

	if (!in_array('show_time_fields', array_keys($view_params))) {
		unset($view_params['show_time_fields']);
		$invalid_fields = array_merge($invalid_time_fields, $invalid_fields);
	}

	if (!in_array('show_user_fields', array_keys($view_params))) {
		unset($view_params['show_user_fields']);
		$invalid_fields = array_merge($invalid_user_fields, $invalid_fields);
	}

	if (!in_array('show_visibility_fields', array_keys($view_params))) {
		unset($view_params['show_visibility_fields']);
		$invalid_fields = array_merge($invalid_visibility_fields, $invalid_fields);
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

	foreach($fields as $field) {
		if (substr($field, -3) === '_id') {
			$fields = array_diff($fields, array($field));
			$fields[] = $field;
		}
	}
?>
<?php echo "<?php \$this->Html->h2(__('{$pluralHumanName}', true)); ?>\n"; ?>
<form action="#" class="form">
	<table class="table">
		<tr>
			<th class="first"><input type="checkbox" class="checkbox toggle" /></th>
<?php foreach ($fields as $field):?>
			<th><?php echo "<?php echo \$this->Paginator->sort('" . Inflector::humanize(preg_replace('/_id$/', '', $field)) . "', '{$modelClass}.{$field}');?>";?></th>
<?php endforeach;?>
			<?php if ($doActions) : ?><?php echo "<th class=\"actions last\">&nbsp;</th>\n";?><?php endif; ?>
		</tr>
<?php
echo "\t\t<?php \$i = 0; foreach (\${$pluralVar} as \${$singularVar}) : ?>\n";
echo "\t\t\t<tr<?php echo (\$i++ % 2 == 0) ? ' class=\"altrow odd\"' : 'even';?>>\n";
echo "\t\t\t<td><input type='checkbox' class='checkbox' name='id' value='1' /></td>\n";
	foreach ($fields as $field) {
		$isKey = false;
		if (!empty($associations['belongsTo'])) {
			foreach ($associations['belongsTo'] as $alias => $details) {
				if ($field === $details['foreignKey']) {
					$isKey = true;
					echo "\t\t\t<td>\n\t\t\t\t<?php echo \$this->Html->link(\${$singularVar}['{$alias}']['{$details['displayField']}'],\n";
					echo "\t\t\t\t\tarray('controller' => '{$details['controller']}', 'action' => 'view', \${$singularVar}['{$alias}']['{$details['primaryKey']}'])); ?>\n\t\t\t</td>\n";
					break;
				}
			}
		}
		if ($isKey !== true) {
			if ($field == $displayField) {
				echo "\t\t\t<td><?php echo \$html->link(\${$singularVar}['{$modelClass}']['{$field}'],\n";
				if (in_array('slug', $fields)) {
					echo "\t\t\t\tarray('action' => 'edit', \${$singularVar}['{$modelClass}']['slug'])); ?>&nbsp;</td>\n";
				} else {
					echo "\t\t\t\tarray('action' => 'edit', \${$singularVar}['{$modelClass}']['{$primaryKey}'])); ?>&nbsp;</td>\n";
				}
			} else if ($schema[$field]['type'] == 'datetime') {
				echo "\t\t\t<td><?php echo \$this->Time->niceShort(\${$singularVar}['{$modelClass}']['{$field}']); ?>&nbsp;</td>\n";
			} else if ($schema[$field]['type'] == 'date') {
				echo "\t\t\t<td><?php echo \$this->Time->relativeTime(\${$singularVar}['{$modelClass}']['{$field}']); ?>&nbsp;</td>\n";
			} else if ($schema[$field]['type'] == 'time') {
				echo "\t\t\t<td><?php echo \$this->Time->time(\${$singularVar}['{$modelClass}']['{$field}']); ?>&nbsp;</td>\n";
			} else if (in_array($field, array('created_by', 'modified_by', 'created_by_id', 'modified_by_id'))) {
				echo "\t\t\t<td><?php echo \$this->Html->link(\${$singularVar}['" . Inflector::classify($field) . "']['username'],\n";
				echo "\t\t\t\tarray('controller' => 'users', 'action' => 'view', \${$singularVar}['{$modelClass}']['{$field}'],";
				echo " Inflector::slug(\${$singularVar}['". Inflector::classify($field) . "']['username']))); ?></td>\n";
			} else {
				echo "\t\t\t<td><?php echo \${$singularVar}['{$modelClass}']['{$field}']; ?>&nbsp;</td>\n";
			}
		}
	}

	if ($doActions) {
		echo "\t\t\t<td class=\"actions last\">\n";
		echo "\t\t\t\t<?php echo \$this->Html->link(__('Show', true),\n";
		if (in_array('slug', $fields)) {
			echo "\t\t\t\t\tarray('action' => 'view', \${$singularVar}['{$modelClass}']['slug'])); ?> | \n";
		} else {
			echo "\t\t\t\t\tarray('action' => 'view', \${$singularVar}['{$modelClass}']['{$primaryKey}'])); ?> | \n";
		}
		echo "\t\t\t\t<?php echo \$this->Html->link(__('Edit', true),\n";
		echo "\t\t\t\t\tarray('action' => 'edit', \${$singularVar}['{$modelClass}']['{$primaryKey}'])); ?> | \n";
		echo "\t\t\t\t<?php echo \$this->Html->link(__('Delete', true),\n";
		echo "\t\t\t\t\tarray('action' => 'delete', \${$singularVar}['{$modelClass}']['{$primaryKey}']),\n";
		echo "\t\t\t\t\tnull, sprintf(__('Are you sure you want to delete %s?', true), \${$singularVar}['{$modelClass}']['{$displayField}'])); ?>\n";
		echo "\t\t\t</td>\n";
	}
echo "\t\t</tr>\n";

echo "\t<?php endforeach; ?>\n";
?>
	</table>
	<div class="actions-bar wat-cf">
		<div class="actions">
			<button class="button" type="submit">
				<?php echo "<?php echo \$this->Html->image('icons/cross.png', array('alt' => 'Delete')); ?> Delete"; ?>
			</button>
		</div>
		<div class="pagination">
			<?php echo "<?php echo \$this->Paginator->prev('« '.__('Previous', true), array('class' => 'next_page'), null, array('class' => 'disabled')); ?>\n";?>
			<?php echo "<?php echo \$this->Paginator->numbers(); ?>\n"?>
			<?php echo "<?php echo \$this->Paginator->next(__('Next', true).' »', array('rel' => 'next', 'class' => 'next_page'), null, array('class' => 'disabled')); ?>\n";?>
		</div>
	</div>
</form>
<?php echo "<?php \$this->Resource->secondary_navigation('Index', array('action' => 'index')); ?>\n"?>
<?php echo "<?php \$this->Resource->secondary_navigation('Add', array('action' => 'add')); ?>"?>