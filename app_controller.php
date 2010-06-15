<?php
/**
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       cake
 * @subpackage    cake.app
 */
App::import('Core', 'Controller');
class AppController extends Controller {
	var $components = array(
		'Authsome.Authsome' => array('model' => 'User'),
		'DebugKit.Toolbar' => array('panels' => array(
			'Interactive.interactive', 'Sanction.permit', 'Settings.settings'
		)),
		'Log.Logging',
		'Mail',
		'RequestHandler',
		'Sanction.Permit',
		'Session',
		'Settings.Settings',
		'Webservice.Webservice'
	);
	var $helpers = array(
		'Form', 'Html', 'Js', 'Resource', 'Sanction.Clearance',
		'Session', 'Time', 'Wysiwyg.Tinymce',  'UploadPack.Upload'
	);
}
?>