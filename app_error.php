<?php
/**
 * Application Error class
 *
 * Contains Application Specific cakeErrors
 *
 * @package       app
 */
class AppError extends ErrorHandler {

/**
 * Output message
 *
 * Does not output debug info on errors
 *
 * @param string $action Action name to render
 * @access protected
 * @author Jose Diaz-Gonzalez
 */
	function _outputMessage($template) {
		Configure::write('debug', 0);
		$this->controller->render($template);
		$this->controller->afterFilter();
		echo $this->controller->output;
	}

/**
 * Renders the Failed Assertion web page.
 *
 * @param array $params Parameters for controller
 * @access public
 * @author Jose Diaz-Gonzalez
 */
	function assertion($params) {
		extract($params, EXTR_OVERWRITE);
		$this->controller->set(array(
			'code' => '412',
			'name' => 'Precondition Failed',
			'message' => 'An assertion was made and the condition failed',
			'file' => $file,
			'line' => $line,
			'function' => $function,
			'assertType' => $assertType,
			'val' => $val,
			'expected' => $expected
		));
		$this->_outputMessage('assertion');
	}
}
?>