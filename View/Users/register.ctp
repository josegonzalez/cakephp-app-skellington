<h2>Register</h2>
<?php echo $this->Form->create('User', array('class' => 'form')); ?>
<?php echo $this->Form->input('User.first_name',
        array('placeholder' => __('your first name', true),
            'type' => 'text')); ?>
    <?php echo $this->Form->input('User.email_address',
            array('placeholder' => __('your email address', true),
                'type' => 'text')); ?>
    <?php echo $this->Form->input('User.password',
            array('placeholder' => __('your password', true))); ?>
    <?php echo $this->Form->submit(__('Register', true),
            array('div' => 'submit')); ?>
<?php echo $this->Form->end(); ?>