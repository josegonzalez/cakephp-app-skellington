<?php
/**
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       cake
 * @subpackage    cake.app
 */
class AppController extends Controller {
	var $components = array(
		'Authsome.Authsome' => array('model' => 'User'),
		'DebugKit.Toolbar',
		'Mail',
		'RequestHandler',
		'Sanction.Permit',
		'Session',
		'Settings.Settings',
	);
	var $helpers = array(
		'Form', 'Html', 'Js', 'Resource', 'Sanction.Clearance', 
		'Session', 'Time', 'Wysiwyg.Tinymce',  'UploadPack.Upload'
	);

	function beforeFilter() {
		if (in_array($this->RequestHandler->ext, array('json', 'xml'))) {
			$this->view = 'WebService';
			$this->set('baseRoute', Router::url('/', true));
		}
	}
}
?>