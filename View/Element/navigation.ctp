<?php
	$controllers = array_diff(App::objects('controller'), array('App', 'Pages', 'Lost'));
	$i = 0;
	foreach ($controllers as $controller) {
		echo $this->Html->link(strtolower($controller), array(
			'plugin' => null, 'controller' => Inflector::tableize($controller), 'action' => 'index'));
		if (++$i != count($controllers)) echo ' &#183; ';
	}
?>