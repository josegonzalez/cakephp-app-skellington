<h2>Delete My Account</h2>
<?php echo $this->Form->create('User', array('url' => array('controller' => 'account', 'action' => 'delete'))); ?>
    <?php echo $this->Form->input('User.delete',
            array('label' => __('Check this box to delete my account', true),
                'type' => 'checkbox')); ?>
    <?php echo $this->Form->submit(__('Delete account', true),
            array('div' => 'submit')); ?>
<?php echo $this->Form->end(); ?>