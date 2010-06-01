<li class="first"><?php echo $this->Html->link('Main Page', '/'); ?></li>
<?php
	$controllers = array_diff(App::objects('controller'),
		array('App', 'LoginTokens', 'Lost', 'Pages', 'Statuses'));
	$i = 0;
	foreach ($controllers as $controller) {
		$class = ($this->params['controller'] == Inflector::tableize($controller)) ? ' class="active"' : null;
		echo "<li{$class}>" . $this->Html->link($controller, array(
			'plugin' => null, 'controller' => Inflector::tableize($controller), 'action' => 'index')) . '</li>';
	}
?>