<?php
echo "<?php echo \$this->Html->h2(__('Reset Password', true)); ?>\n";
echo "<?php echo \$this->Session->flash(); ?>\n";
echo "<?php echo \$this->Form->create('User');?>\n";
echo "\t<?php echo \$this->Form->input('User.password',\n";
echo "\t\t\tarray('label' => __('New Password', true),\n";
echo "\t\t\t\t'placeholder' => __('your new password', true))); ?>\n";
echo "\t<?php echo \$this->Form->submit(__('Change Password', true),\n";
echo "\t\t\tarray('div' => 'submit cancel')); ?> or \n";
echo "\t<?php echo \$this->Html->link(__('login', true),\n";
echo "\t\t\tarray('action' => 'login'),\n";
echo "\t\t\tarray('class' => 'cancel-action')); ?>\n";
echo "<?php echo \$this->Form->end(); ?>";
?>