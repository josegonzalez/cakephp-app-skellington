<?php

if (!class_exists('Permit')) App::import('Component', 'Sanction.PermitComponent');

Permit::access(
	array('plugin' => 'settings'),
	array('auth' => array('group' => 'admin')),
	array('redirect' => array('plugin' => null, 'controller' => 'users', 'action' => 'login')));

Permit::access(
	array('controller' => 'users', 'action' => array('change_password', 'dashboard', 'logout')),
	array('auth' => true),
	array('redirect' => array('plugin' => null, 'controller' => 'users', 'action' => 'login')));

Permit::access(
	array('controller' => 'users', 'action' => array('forgot_password', 'login', 'reset_password')),
	array('auth' => false),
	array('redirect' => array('plugin' => null, 'controller' => 'users', 'action' => 'dashboard')));

?>