<?php
class SettingsPanel extends DebugPanel {
	var $plugin = 'settings';
	var $elementName = 'settings_panel';
	var $title = 'Settings';

	function beforeRender(&$controller) {
		return Configure::getInstance();
	}
}
?>