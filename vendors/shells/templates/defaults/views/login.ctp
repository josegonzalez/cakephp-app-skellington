<?php
echo "<?php echo \$this->Html->h2(__('Login', true)); ?>\n";
echo "<?php echo \$this->Session->flash(); ?>\n";
echo "<?php echo \$this->Form->create('{$modelClass}'); ?>\n";
echo "\t<?php echo \$this->Form->input('{$modelClass}.email',\n";
echo "\t\t\tarray('label' => __('Username', true),\n";
echo "\t\t\t\t'placeholder' => __('your email address', true),\n";
echo "\t\t\t\t'type' => 'text')); ?>\n";
echo "\t<?php echo \$this->Form->input('{$modelClass}.password',\n";
echo "\t\t\tarray('label' => __('Password', true),\n";
echo "\t\t\t\t'placeholder' => __('your password', true))); ?>\n";
echo "\t<?php echo \$this->Form->input('{$modelClass}.remember',\n";
echo "\t\t\tarray('label' => __('Remember me for 2 weeks', true),\n";
echo "\t\t\t\t'type' => 'checkbox')); ?>\n";
echo "\t<?php echo \$this->Form->submit(__('Login', true),\n";
echo "\t\t\tarray('div' => 'submit forgot')); ?>\n";
echo "\t<?php echo \$this->Html->link(__('Forgot your password?', true),\n";
echo "\t\tarray('action' => 'forgot_password'),\n";
echo "\t\tarray('class' => 'forgot-action')); ?>\n";
echo "<?php echo \$this->Form->end(); ?>";
?>