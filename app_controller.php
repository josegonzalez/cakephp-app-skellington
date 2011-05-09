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
    public $components = array(
        'Authsome.Authsome' => array(
            'configureKey' => 'Auth',
            'sessionKey' => 'Auth',
            'cookieKey' => 'Auth',
            'model' => 'User'
        ),
        'CakeDjjob.CakeDjjob',
        'Log.Logging',
        'Mail',
        'RequestHandler',
        'Sanction.Permit' => array(
            'path' => 'Auth.User',
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
    public $helpers = array(
        'AssetCompress.AssetCompress',
        'Sanction.Clearance',
        'Wysiwyg.Tinymce',
        'UploadPack.Upload',
    );

/**
 * Used to set a max for the pagination limit
 *
 * @var int
 */
    public $paginationMaxLimit = 25;

/**
 * Sets the view class to AutoHelper, which autoloads helpers when needed
 *
 * @var string
 * @access public
 */
    public $view = 'AutoHelper';

/**
 * Object constructor - Adds the Debugkit panel if in development mode
 *
 * @return void
 */
    public function __construct() {
        if (Configure::read('debug')) {
            $this->components[] = 'DebugKit.Toolbar';
        }
        parent::__construct();
    }

/**
 * Called before the controller action.
 *
 * Used to set a max for the pagination limit
 *
 * @access public
 */
    public function beforeFilter() {
        // Enforces an absolute limit of 25
        if (isset($this->passedArgs['limit'])) {
            $this->passedArgs['limit'] = min(
                $this->paginationMaxLimit,
                $this->passedArgs['limit']
            );
        }
    }

/**
 * Given a component name from the `$component` parameter we will load
 * it up to the current controller with the provided `$configuration` --
 * note that if the same component is already loaded, you could wind up
 * with some shennanigans, so be careful with that.
 *
 * Loading a component that doesn't exist will result in a CakeError
 * being thrown and the execution will be halted.
 *
 * We return boolean to indicate success in loading the component.
 *
 * @param string $component
 * @param array $configuration
 * @return boolean
 * @access protected
 */
    protected function _loadComponent($component, $configuration = array()) {
        if (!isset($this->components[$component])) {
            $this->components[$component] = $configuration;
            $this->Component->_loadComponents($this);
            return isset($this->$component);
        }
        return false;
    }

/**
 * Convenience method for logging a user out of the application completely
 *
 * @param mixed $redirect If false, do not redirect, else redirect to specified action
 * @return void
 */
    protected function _logout($redirect = array('action' => 'login')) {
        $this->Authsome->logout();
        $this->Session->delete('Auth');
        if ($redirect) $this->redirect($redirect);
    }
}