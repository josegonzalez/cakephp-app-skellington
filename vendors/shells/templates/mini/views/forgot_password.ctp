<?php
echo "<?php echo \$this->Html->h2(__('Forgot password', true));  ?>\n";
echo "<?php echo \$this->Session->flash(); ?>\n";
echo "<?php echo \$this->Form->create('{$modelClass}');?>\n";
echo "<?php echo \$this->Form->input('{$modelClass}.email',\n";
echo "\t\tarray('label' => __('Email', true),\n";
echo "\t\t\t'placeholder' => 'your email address')); ?>\n";
echo "<?php echo \$this->Form->submit(__('Request Password Reset', true),\n";
echo "\t\tarray('div' => 'submit cancel')); ?> or \n";
echo "<?php echo \$this->Html->link(__('login', true),\n";
echo "\t\t\tarray('action' => 'login'),\n";
echo "\t\t\tarray('class' => 'cancel-action')); ?>\n";
echo "<?php echo \$this->Form->end(); ?>";
?>