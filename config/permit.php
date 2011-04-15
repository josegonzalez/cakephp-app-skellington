<?php

if (!class_exists('Permit')) App::import('Component', 'Sanction.PermitComponent');

Permit::access(
	array('plugin' => 'settings'),
	array('auth' => array('group' => 'admin')),
	array('redirect' => array('plugin' => null, 'controller' => 'users', 'action' => 'login'), 'element' => 'flash/error'));

Permit::access(
	array('controller' => 'users', 'action' => array('login', 'register', 'forgot_password', 'reset_password')),
	array('auth' => false),
	array('redirect' => array('controller' => 'users', 'action' => 'index')));

Permit::access(
	array('controller' => 'users', 'action' => array('change_password', 'dashboard', 'profile', 'logout')),
	array('auth' => true),
	array('redirect' => array('controller' => 'users', 'action' => 'login')));

Permit::access(
	array('controller' => 'account'),
	array('auth' => true),
	array('redirect' => array('controller' => 'users', 'action' => 'login')));

Permit::access(
	array('admin' => true),
	array('auth' => array('is_admin' => true)),
	array('redirect' => array('controller' => 'users', 'action' => 'index', 'admin' => false)));

?>