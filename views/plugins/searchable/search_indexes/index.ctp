<h2 class="title"><?php __('Search Results'); ?></h2>
<?php $this->Html->h2(__('Search Results', true)); ?>
<div class="inner">
	<?php echo $this->Session->flash(); ?>
	<?php echo $this->Form->create('SearchIndex', array(
		'class' => 'form', 'inputDefaults' => array('div' => false, 'label' => false),
		'url' => array(
			'plugin' => 'searchable',
			'controller' => 'search_indexes',
			'action' => 'index'))); ?>
		<div class="columns wat-cf">
			<div class="column left">
				<div class="group">
					<?php echo $this->Form->label('SearchIndex.term', __('Terms', true), array('class' => 'label')); ?>
					<?php echo $this->Form->input('SearchIndex.term', array('class' => 'text_field', 'label' => false)); ?>
				</div>
			</div>
		</div>
		<div class="group navform wat-cf">
			<button class="button" type="submit">
				<?php echo $this->Html->image('icons/tick.png', array('alt' => __('View Search Results', true))) . __('View Search Results', true); ?>
			</button>
		</div>
	<?php echo $this->Form->end(); ?>
<?php if (!empty($results)) : ?>
	<table cellpadding="0" cellspacing="0">
		<tr>
				<th><?php echo $this->Paginator->sort('name');?></th>
		</tr>
		<?php $term = (isset($this->data['SearchIndex']['term'])) ? trim($this->data['SearchIndex']['term']) : '';?>
		<?php $i = 0; foreach ($results as $result): ?>
			<tr<?php echo ($i++ % 2 == 0) ? ' class="altrow"' : '';?>>
				<td>
					<?php echo $this->Html->link($result['SearchIndex']['name'],
					 			json_decode($result['SearchIndex']['url'], true)); ?><br />
					<?php if (!empty($result['SearchIndex']['summary'])): ?>
						<?php echo $this->Text->highlight($result['SearchIndex']['summary'], $term); ?>
					<?php else : ?>
						<?php echo $this->Searchable->snippets($result['SearchIndex']['data']); ?>
					<?php endif; ?>
					&nbsp;
				</td>
			</tr>
		<?php endforeach; ?>
	</table>
	<?php $params = array_intersect_key($this->params, array_flip(array('type', 'term'))); ?>
	<?php $params = array_map('urlencode', $params); ?>
	<?php $params = array_map('urlencode', $params); ?>
	<?php $this->Paginator->options(array('url' => $params)); ?>
	<div class="paging">
		<?php echo $this->Paginator->prev('<< '.__('previous', true), array(), null,
				array('class' => 'disabled')); ?>
	 | 	<?php echo $this->Paginator->numbers(); ?> |
		<?php echo $this->Paginator->next(__('next', true).' >>', array(), null,
				array('class' => 'disabled')); ?>
	</div>
<?php elseif ($term) : ?>
	<p>Sorry, your search did not return any matches.</p>
<?php endif; ?>
</div>