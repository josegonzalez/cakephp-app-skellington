<h2>Login</h2>
<?php echo $this->Form->create('User', array('class' => 'form')); ?>
    <?php echo $this->Form->input('User.login',
            array('label' => __('Username', true),
                'placeholder' => __('your email address', true),
                'type' => 'text')); ?>
    <?php echo $this->Form->input('User.credential',
            array('label' => __('Password', true),
                'placeholder' => __('your password', true),
                'type' => 'password')); ?>
    <?php echo $this->Form->input('User.remember',
            array('label' => __('Remember me for 2 weeks', true),
                'type' => 'checkbox')); ?>
    <?php echo $this->Form->submit(__('Login', true),
            array('div' => 'submit')); ?>
<?php echo $this->Form->end(); ?>