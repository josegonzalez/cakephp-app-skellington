<h2>Update your Account</h2>
<?php echo $this->Form->create('User', array('url' => array('controller' => 'account', 'action' => 'settings'))); ?>
    <?php echo $this->Form->input('User.first_name',
            array('label' => __('First Name', true),
                'placeholder' => __('your first name', true),
                'type' => 'text')); ?>
    <?php echo $this->Form->input('User.last_name',
            array('label' => __('Last Name', true),
                'placeholder' => __('your last name', true))); ?>
    <?php echo $this->Form->input('User.location',
            array('label' => __('Location', true),
                'placeholder' => __('where in the world are you?', true))); ?>
    <?php echo $this->Form->submit(__('Save', true),
            array('div' => 'submit')); ?>
<?php echo $this->Form->end(); ?>
<?php echo $this->Html->link('Update Password', array('controller' => 'account', 'action' => 'password')); ?>
<br />
<?php echo $this->Html->link('Delete Account', array('controller' => 'account', 'action' => 'delete')); ?>
