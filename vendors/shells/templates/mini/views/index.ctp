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
	$doActions = true;
	$related_action_associations = $associations;
	$view_params = $this->params;

	$invalid_fields = array('id', 'created', 'created_by', 'created_by_id', 'modified', 'modified_by', 'modified_by_id', 'updated', 'owned_by', 'owner_id');
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
<div class="<?php echo $pluralVar;?> index">
	<?php echo "<?php \$this->Html->h2(__('{$pluralHumanName}', true)); ?>\n"; ?>
	<table cellpadding="0" cellspacing="0">
	<tr>
	<?php foreach ($fields as $field):?>
	<th><?php echo "<?php echo \$this->Paginator->sort('" . Inflector::humanize(preg_replace('/_id$/', '', $field)) . "', '{$modelClass}.{$field}');?>";?></th>
	<?php endforeach;?>
	<?php if ($doActions) : ?><?php echo "<th class=\"actions\"><?php __('Actions');?></th>\n";?><?php endif; ?>
	</tr>
	<?php
	echo "<?php \$i = 0; foreach (\${$pluralVar} as \${$singularVar}) : ?>\n";
	echo "\t<tr<?php echo (\$i++ % 2 == 0) ? ' class=\"altrow\"' : '';?>>\n";
		foreach ($fields as $field) {
			$isKey = false;
			if (!empty($associations['belongsTo'])) {
				foreach ($associations['belongsTo'] as $alias => $details) {
					if ($field === $details['foreignKey']) {
						$isKey = true;
						echo "\t\t<td>\n\t\t\t<?php echo \$this->Html->link(\${$singularVar}['{$alias}']['{$details['displayField']}'],\n";
						echo "\t\t\t\tarray('controller' => '{$details['controller']}', 'action' => 'view', \${$singularVar}['{$alias}']['{$details['primaryKey']}'])); ?>\n\t\t</td>\n";
						break;
					}
				}
			}
			if ($isKey !== true) {
				if ($field == $displayField) {
					echo "\t\t<td><?php echo \$html->link(\${$singularVar}['{$modelClass}']['{$field}'],\n";
					if (in_array('slug', $fields)) {
						echo "\t\t\tarray('action' => 'edit', \${$singularVar}['{$modelClass}']['slug'])); ?>&nbsp;</td>\n";
					} else {
						echo "\t\t\tarray('action' => 'edit', \${$singularVar}['{$modelClass}']['{$primaryKey}'])); ?>&nbsp;</td>\n";
					}
				} else if ($schema[$field]['type'] == 'datetime') {
					echo "\t\t<td><?php echo \$this->Time->niceShort(\${$singularVar}['{$modelClass}']['{$field}']); ?>&nbsp;</td>\n";
				} else if ($schema[$field]['type'] == 'date') {
					echo "\t\t<td><?php echo \$this->Time->timeAgoInWords(\${$singularVar}['{$modelClass}']['{$field}']); ?>&nbsp;</td>\n";
				} else if ($schema[$field]['type'] == 'time') {
					echo "\t\t<td><?php echo \$this->Time->time(\${$singularVar}['{$modelClass}']['{$field}']); ?>&nbsp;</td>\n";
				} else if (in_array($field, array('created_by', 'modified_by', 'created_by_id', 'modified_by_id'))) {
					echo "\t\t<td><?php echo \$this->Html->link(\${$singularVar}['" . Inflector::classify($field) . "']['username'],\n";
					echo "\t\t\tarray('controller' => 'users', 'action' => 'view', \${$singularVar}['{$modelClass}']['{$field}'],";
					echo " Inflector::slug(\${$singularVar}['". Inflector::classify($field) . "']['username']))); ?></td>\n";
				} else {
					echo "\t\t<td><?php echo \${$singularVar}['{$modelClass}']['{$field}']; ?>&nbsp;</td>\n";
				}
			}
		}

		if ($doActions) {
			echo "\t\t<td class=\"actions\">\n";
			echo "\t\t\t<?php echo \$this->Html->link(__('View', true),\n";
			if (in_array('slug', $fields)) {
				echo "\t\t\t\tarray('action' => 'view', \${$singularVar}['{$modelClass}']['slug'])); ?>\n";
			} else {
				echo "\t\t\t\tarray('action' => 'view', \${$singularVar}['{$modelClass}']['{$primaryKey}'])); ?>\n";
			}
			echo "\t\t\t<?php echo \$this->Html->link(__('Edit', true),\n";
			echo "\t\t\t\tarray('action' => 'edit', \${$singularVar}['{$modelClass}']['{$primaryKey}'])); ?>\n";
			echo "\t\t\t<?php echo \$this->Html->link(__('Delete', true),\n";
			echo "\t\t\t\tarray('action' => 'delete', \${$singularVar}['{$modelClass}']['{$primaryKey}']),\n";
			echo "\t\t\t\tnull, sprintf(__('Are you sure you want to delete # %s?', true), \${$singularVar}['{$modelClass}']['{$primaryKey}'])); ?>\n";
			echo "\t\t</td>\n";
		}
	echo "\t</tr>\n";

	echo "\t<?php endforeach; ?>\n";
	?>
	</table>
	<?php echo "<p><?php echo \$this->Paginator->counter(array('format' => __('Page %page% of %pages%, showing %current% of %count%', true)));?></p>";?>


	<div class="paging">
	<?php echo "\t<?php echo \$this->Paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled')); ?>\n";?>
	<?php echo "\t<?php echo \$paginator_numbers = \$this->Paginator->numbers(); ?>\n"?>
	<?php echo "\t<?php if (!empty(\$paginator_numbers)) echo \" | {\$paginator_number} | \"; ?>\n"?>
	<?php echo "\t<?php echo \$this->Paginator->next(__('next', true).' >>', array(), null, array('class' => 'disabled')); ?>\n";?>
	</div>
</div>
<div class="actions">
	<h3><?php echo "<?php __('Actions'); ?>"; ?></h3>
	<ul>
		<li><?php echo "<?php echo \$this->Html->link(sprintf(__('New %s', true), __('{$singularHumanName}', true)), array('action' => 'add')); ?>";?></li>
<?php
	$done = array();
	foreach ($related_action_associations as $type => $data) {
		foreach ($data as $alias => $details) {
			if ($details['controller'] != $this->name && !in_array($details['controller'], $done)) {
				if (in_array(Inflector::humanize(Inflector::underscore($alias)), array('Created By', 'Modified By'))) continue;
				echo "\t\t<li><?php echo \$this->Html->link(sprintf(__('List %s', true), __('" . Inflector::humanize($details['controller']) . "', true)), array('controller' => '{$details['controller']}', 'action' => 'index')); ?></li>\n";
				echo "\t\t<li><?php echo \$this->Html->link(sprintf(__('New %s', true), __('" . Inflector::humanize(Inflector::underscore($alias)) . "', true)), array('controller' => '{$details['controller']}', 'action' => 'add')); ?></li>\n";
				$done[] = $details['controller'];
			}
		}
	}
?>
	</ul>
</div>