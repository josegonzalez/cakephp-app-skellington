<?php
/**
 * Application Controller class
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       app
 */
class AppController extends Controller {

/**
 * Array containing the names of components this controller uses. Component names
 * should not contain the "Component" portion of the classname.
 *
 * Example: `var $components = array('Session', 'RequestHandler', 'Acl');`
 *
 * @var array
 * @access public
 * @link http://book.cakephp.org/view/961/components-helpers-and-uses
 */
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

/**
 * An array containing the names of helpers this controller uses. The array elements should
 * not contain the "Helper" part of the classname.
 *
 * Example: `var $helpers = array('Html', 'Javascript', 'Time', 'Ajax');`
 *
 * @var mixed A single name as a string or a list of names as an array.
 * @access protected
 * @link http://book.cakephp.org/view/961/components-helpers-and-uses
 */
	var $helpers = array(
		'Form', 'Html', 'Js', 'Resource', 'Sanction.Clearance',
		'Session', 'Time', 'Wysiwyg.Tinymce',  'UploadPack.Upload'
	);
}
?>