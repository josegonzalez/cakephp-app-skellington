<?php
class LoggingComponent extends Object {
/**
 * Initialize component
 *
 * @param object $controller Instantiating controller
 * @access public
 */
	function initialize(&$controller, $settings = array()) {
		if (!count($controller->uses) || get_parent_class($controller->{$controller->modelClass}) == 'Object') return;
		if (!$controller->{$controller->modelClass}->Behaviors->attached('Logable')) return;

		if (!Authsome::get('guest')) $controller->{$controller->modelClass}->setUserData(Authsome::get());
		$controller->{$controller->modelClass}->setRequestParameters($controller->params);
		$controller->{$controller->modelClass}->setUserBrowser($_SERVER['HTTP_USER_AGENT']);
		$controller->{$controller->modelClass}->setUserIp($_SERVER['REMOTE_ADDR']);
	}
}
?>