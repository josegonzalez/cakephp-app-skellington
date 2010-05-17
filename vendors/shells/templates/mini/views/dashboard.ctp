<?php
echo "<?php echo \$this->Html->h2(__('Dashboard', true)); ?>\n";
echo "<?php echo \$this->Session->flash(); ?>\n";
echo "<ul class=\"actions\">\n";
echo "\t<li><?php echo \$this->Html->link(__('Change Password', true),\n";
echo "\t\t\t\tarray('action' => 'change_password')); ?></li>\n";
echo "\t<li><?php echo \$this->Html->link(__('Logout', true),\n";
echo "\t\t\t\tarray('action' => 'logout')); ?></li>\n";
echo "</ul>";
?>