<?php
/**
 * Application Helper class
 *
 * Add your application-wide methods in the class below, your helpers
 * will inherit them.
 *
 * @package       app
 */
class AppHelper extends Helper {
	var $view = null;

	function h2($contents, $alternate = null) {
		if ((empty($contents) || $contents == '' || $contents == ' ') && isset($alternate)) $contents = $alternate;
		if (!$this->view) $this->view = ClassRegistry::getObject('view');

		$this->view->set('title_for_layout', "{$contents} |");
		$this->view->set("h2_for_layout", $contents);
	}
}
?>