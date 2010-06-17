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
		'Sanction.Permit' => array(
			'path' => 'User.User',
			'check' => 'role'
		),
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

/**
 * The name of the layout file to render the view inside of. The name specified
 * is the filename of the layout in /app/views/layouts without the .ctp
 * extension.
 *
 * @var string
 * @access public
 * @link http://book.cakephp.org/view/962/Page-related-Attributes-layout-and-pageTitle
 */
	var $layout = 'admin';

/**
 * Called after the controller action is run, but before the view is rendered.
 *
 * @access public
 * @link http://book.cakephp.org/view/984/Callbacks
 */
	function beforeRender() {
		if (Configure::read('Settings.layout') == 'admin' && $this->layout == 'alternate') $this->layout = 'alternate_admin';
	}
}
?>