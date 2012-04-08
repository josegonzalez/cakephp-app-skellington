<h2>Change password</h2>
<?php echo $this->Form->create('User', array('url' => array('controller' => 'account', 'action' => 'password'))); ?>
    <?php echo $this->Form->input('User.new_password',
            array('label' => __('New Password', true),
                'placeholder' => __('your new password', true),
                'type' => 'password')); ?>
    <?php echo $this->Form->input('User.confirm_password',
            array('label' => __('Verify New Password', true),
                'placeholder' => __('confirm your password', true),
                'type' => 'password')); ?>
    <?php echo $this->Form->submit(__('Change', true),
            array('div' => 'submit')); ?>
<?php echo $this->Form->end(); ?>
