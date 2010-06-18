<?php
/**
 * Base class for Rebake Tasks.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc.
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.cake.console.libs.tasks
 * @since         CakePHP(tm) v 1.3
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
class RebakeTask extends Shell {

/**
 * Tables to skip when running all()
 *
 * @var array
 * @access protected
 */
	var $skipTables = array('i18n', 'logs', 'search_index', 'settings');
	var $skipTablesView = array('login_tokens');

/**
 * Name of plugin
 *
 * @var string
 * @access public
 */
	var $plugin = null;

/**
 * The db connection being used for baking
 *
 * @var string
 * @access public
 */
	var $connection = null;

/**
 * Flag for interactive mode
 *
 * @var boolean
 */
	var $interactive = false;

/**
 * Gets the path for output.  Checks the plugin property
 * and returns the correct path.
 *
 * @return string Path to output.
 * @access public
 */
	function getPath() {
		$path = $this->path;
		if (isset($this->plugin)) {
			$name = substr($this->name, 0, strlen($this->name) - 4);
			$path = $this->_pluginPath($this->plugin) . Inflector::pluralize(Inflector::underscore($name)) . DS;
		}
		return $path;
	}

/**
 * Clears the Cache using the Folder class
 *
 * @param boolean $empty True to delete empty files
 * @param boolean $aggressive Deletes both files and folders
 * @return void
 * @author Jose Diaz-Gonzalez
 */
	function clearCache($empty = false, $aggressive = true) {
		$return = array();

		$paths = array('data', 'models', 'persistent', 'views');
		$folder = new Folder();
		foreach ($paths as $path) {
			clearCache(null, $path, null);
			if (!$folder->cd(CACHE . $path)) continue;
			$files = $folder->read();

			foreach ($files[1] as $file) {
				if ($file == 'empty' && !$empty) continue;
				$return[] = CACHE . $path . DS . $file;
				unlink(CACHE . $path . DS . $file);
			}
			if ($aggressive) foreach ($files[0] as $a_folder) {
				$return[] = CACHE . $path . DS . $a_folder;
				unlink(CACHE . $path . DS . $a_folder);
			}
		}
		$folder->cd(CACHE);
		$files = $folder->read();
		foreach ($files[1] as $file) {
			if ($file == 'empty' && !$empty) continue;
			$return[] = CACHE . $file;
			unlink(CACHE . $file);
		}
		foreach ($paths as $path) {
			clearCache(null, $path, null);
		}
		return $return;
	}

/**
 * Resets a particular bake type to the "defaults"
 *
 * @return void
 * @author Jose Diaz-Gonzalez
 **/
	function resetFolder($type = 'views') {
		$return = array();
		$validViewFolders = array('elements', 'errors', 'helpers', 'layouts', 'pages', 'plugins', 'scaffolds');
		$validControllerFiles = array('pages_controller.php');

		$folder = new Folder();
		if ($type == 'views') {
			$folder->cd(VIEWS);
			$files = $folder->read();
			foreach ($files[0] as $a_folder) {
				if (!in_array($a_folder, $validViewFolders)) {
					$return[] = VIEWS . $a_folder;
					$folder->delete(VIEWS . $a_folder);
					@unlink(VIEWS . $a_folder);
				}
			}
		}
		if ($type == 'controllers') {
			$folder->cd(CONTROLLERS);
			$files = $folder->read();
			foreach ($files[1] as $a_file) {
				if (!in_array($a_file, $validControllerFiles)) {
					$return[] = CONTROLLERS . $a_file;
					$file = new File(CONTROLLERS . $a_file);
					$file->delete();
					@unlink(CONTROLLERS . $a_file);
				}
			}
			$folder->cd(CONTROLLER_TESTS);
			$files = $folder->read();
			foreach ($files[1] as $a_file) {
				$return[] = CONTROLLER_TESTS . $a_file;
				$file = new File(CONTROLLER_TESTS . $a_file);
				$file->delete();
				@unlink(CONTROLLER_TESTS . $a_file);
			}
		}
		if ($type == 'models') {
			foreach (array(MODELS, TESTS . 'fixtures', MODEL_TESTS) as $path) {
				$folder->cd($path);
				$files = $folder->read();
				foreach ($files[1] as $a_file) {
					$return[] = $path . $a_file;
					$file = new File($path . $a_file);
					$file->delete();
					@unlink($path . $a_file);
				}
			}
		}
		return $return;
	}
}