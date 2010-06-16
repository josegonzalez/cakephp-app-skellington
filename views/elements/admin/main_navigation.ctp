<li class="first"><?php echo $this->Html->link('Main Page', '/'); ?></li>
<?php
	$output = '';
	if (($output = Cache::read('main_navigation' . md5($this->params['controller']))) === false) {
		if (($controllers = Cache::read('main_navigation')) === false) {
			$controllers = array_diff(App::objects('controller'),
				array('App', 'LoginTokens', 'Lost', 'Pages', 'Statuses'));
			foreach ($controllers as $key => $controller_name) {
				$model = ClassRegistry::init(Inflector::singularize($controller_name));
				$schema = $model->schema();
				$controllers[$key] = array(
				    'title' => $controller_name,
				    'url' => Inflector::tableize($controller_name)
				);
				if (count($schema) == 2 && (isset($schema['id']) && isset($schema['name']))) unset($controllers[$key]);
			}
			Cache::write('main_navigation', $controllers);
		}
		$i = 0;
		foreach ($controllers as $controller) {
			$class = ($this->params['controller'] == $controller['url']) ? ' class="active"' : null;
			$output .= "<li{$class}>" . $this->Html->link($controller['title'], array(
				'plugin' => null, 'controller' => $controller['url'], 'action' => 'index')) . '</li>';
		}
		Cache::write('main_navigation' . md5($this->params['controller']), $output);
	}
	echo $output;
?>