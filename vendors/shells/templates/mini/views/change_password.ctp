<?php 
echo "<?php echo \$this->Html->h2(__('Change Password', true)); ?>\n";
echo "<?php echo \$this->Session->flash(); ?>\n";
echo "<?php echo \$this->Form->create('{$modelClass}'); ?>\n";
echo "\t<?php echo \$this->Form->input('{$modelClass}.password',\n";
echo "\t\t\tarray('label' => __('Current Password', true))); ?>\n";
echo "\t<?php echo \$this->Form->input('{$modelClass}.new_password',\n";
echo "\t\t\tarray('div' => 'input password required',\n";
echo "\t\t\t\t'label' => __('New Password', true),\n";
echo "\t\t\t\t'type' => 'password')); ?>\n";
echo "\t<?php echo \$this->Form->input('{$modelClass}.new_password_confirm',\n";
echo "\t\t\tarray('div' => 'input password required',\n";
echo "\t\t\t\t'label' => __('Confirm New Password', true),\n";
echo "\t\t\t\t'type' => 'password')); ?>\n";
echo "\t<?php echo \$this->Form->submit(__('Change Password', true),\n";
echo "\t\t\tarray('div' => 'submit cancel')); ?> or \n";
echo "\t<?php echo \$this->Html->link(__('go to dashboard', true),\n";
echo "\t\t\tarray('action' => 'dashboard'),\n";
echo "\t\t\tarray('class' => 'cancel-action')); ?>\n";
echo "<?php echo \$this->Form->end(); ?>\n";
?>