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

	$index_associations = $associations;
	$related_action_associations = $associations;

	$invalidFields = array('created', 'modified', 'updated', 'created_by', 'modified_by', 'model', 'model_key');
	$invalidFields = array_merge(array('id', 'password'), $invalidFields);
	$invalidFields = array_merge(array('enabled', 'deleted'), $invalidFields);
	$invalidFields = array_merge(array('contents', 'description'), $invalidFields);
	if ($modelClass != 'Event') $invalidFields[] = 'date';
	if ($modelClass == 'Project') {
		$invalidFields = array_merge(array('owned_by', 'repository_path'), $invalidFields);
	}
	if ($modelClass == 'Ticket') {
		$doActions = false;
		if (!empty($index_associations['belongsTo']) && isset($index_associations['belongsTo']['Project'])) {
			$invalidFields = array_merge(array('project_id', 'milestone_id'), $invalidFields);
			unset($index_associations['belongsTo']['Project']);
			unset($index_associations['belongsTo']['Milestone']);
			unset($related_action_associations['belongsTo']['Project']);
			unset($related_action_associations['belongsTo']['AssignedTo']);
		}
	}
	foreach ($fields as $i => $field) {
		if (substr($field, -6) == '_count') unset($fields[$i]);
		if (in_array($field, $invalidFields)) unset($fields[$i]);
	}
?>
<div class="<?php echo $pluralVar;?> index">
	<h2><?php echo "<?php __('{$pluralHumanName}');?>";?></h2>
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
			if (!empty($index_associations['belongsTo'])) {
				foreach ($index_associations['belongsTo'] as $alias => $details) {
					if ($field === $details['foreignKey']) {
						$isKey = true;
						echo "\t\t<td>\n\t\t\t<?php echo \$this->Html->link(\${$singularVar}['{$alias}']['{$details['displayField']}'],\n";
						echo "\t\t\t\tarray('controller' => '{$details['controller']}', 'action' => 'view', \${$singularVar}['{$alias}']['{$details['primaryKey']}'])); ?>\n\t\t</td>\n";
						break;
					}
				}
			}
			if ($isKey !== true) {
				echo "\t\t<td><?php echo \${$singularVar}['{$modelClass}']['{$field}']; ?>&nbsp;</td>\n";
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