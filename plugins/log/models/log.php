<?php
class Log extends LogAppModel {
	var $name = 'Log';
	var $order = 'Log.created DESC';
	var $belongsTo = array('User');

	function __findDashboard() {
		return $this->find('all', array(
			'contain' => array('User'),
			'limit' => 20
		));
	}
}
?>