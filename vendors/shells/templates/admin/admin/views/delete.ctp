<div class="block" id="block-text">
	<div class="secondary-navigation">
		<ul class="wat-cf">
			<li class="first"><?php echo "<?php echo \$this->Html->link('Index', array('action' => 'index')); ?>"; ?></li>
<?php if (strpos($action, 'add') === false) : ?>
<?php $the_key = (in_array('slug', $fields)) ? 'slug' : $primaryKey; ?>
			<li><?php echo "<?php echo \$this->Html->link('View', array('action' => 'view', \$this->data['{$modelClass}']['{$the_key}'])); ?>"; ?></li>
			<li><?php echo "<?php echo \$this->Html->link('Edit', array('action' => 'edit', \$this->data['{$modelClass}']['{$primaryKey}'] . '#')); ?>"; ?></li>
			<li><?php echo "<?php echo \$this->Html->link('Add', array('action' => 'add')); ?>"; ?></li>
			<li class="active">
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
		<h2 class="title"><?php echo "<?php echo sprintf(__('Delete %s #%s?', true), __('{$singularHumanName}', true), \$this->data['{$modelClass}']['{$primaryKey}']); ?>"; ?></h2>
		<div class="inner">
			<?php echo "<?php echo \$this->Session->flash(); ?>\n"; ?>
			<?php echo "<?php echo \$this->Form->create('{$modelClass}', array(\n"; ?>
			<?php echo "\t'class' => 'form', 'inputDefaults' => array('div' => false, 'label' => false))); ?>\n"; ?>
<?php foreach ($fields as $field) : ?>
<?php	if ($field == $primaryKey) : ?>
			<div class="group">
				<?php echo "<?php echo \$this->Form->input('{$modelClass}.{$field}', array('class' => 'text_field', 'type' => 'hidden')); ?>\n"; ?>
			</div>
<?php	endif; ?>
<?php endforeach; ?>
			<div class="group navform wat-cf">
				<button class="button" type="submit">
					<?php echo "<?php echo \$this->Html->image('icons/tick.png', array('alt' => 'Delete')); ?> Delete\n"; ?>
				</button>
				<?php echo "<?php echo \$this->Html->link(\n"; ?>
				<?php echo "\t\$this->Html->image('icons/cross.png', array('alt' => __('Go To Index', true))) .' Go To Index',\n"; ?>
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