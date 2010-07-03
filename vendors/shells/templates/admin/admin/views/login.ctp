<div class="block" id="block-login">
	<h2>Login Box</h2>
	<div class="content login">
		<?php echo "<?php echo \$this->Session->flash(); ?>"; ?>
		<?php echo "<?php echo \$this->Form->create('User', array('class' => 'form login')); ?>\n"; ?>
			<div class="group wat-cf">
				<div class="left">
					<?php echo "<?php echo \$this->Form->label('{$modelClass}.email', __('Email', true), array('class' => 'label right')); ?>\n"; ?>
				</div>
				<div class="right">
					<?php echo "<?php echo \$this->Form->input('{$modelClass}.email', array('class' => 'text_field', 'label' => false)); ?>\n"; ?>
				</div>
			</div>
			<div class="group wat-cf">
				<div class="left">
					<?php echo "<?php echo \$this->Form->label('{$modelClass}.password', __('Password', true), array('class' => 'label right')); ?>\n"; ?>

				</div>
				<div class="right">
					<?php echo "<?php echo \$this->Form->input('{$modelClass}.password', array('class' => 'text_field', 'label' => false)); ?>\n"; ?>
				</div>
			</div>
			<div class="group navform wat-cf">
				<div class="right">
					<button class="button" type="submit">
						<?php echo "<?php echo \$this->Html->image('icons/key.png', array('alt' => __('Login', true))); ?> <?php __('Login'); ?>\n"; ?>
					</button>
				</div>
			</div>
		<?php echo "<?php echo \$this->Form->end(); ?>\n"; ?>
	</div>
</div>