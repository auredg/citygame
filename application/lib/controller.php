<?php

/**
 * Controller class
 */
class Controller {
	
	/**
	 * Params
	 * 
	 * @var object
	 */
	private	$_params = null;
	
	/**
	 * Action
	 * 
	 * @var string 
	 */
	protected $_action = '';
	
	/**
	 * Resultat
	 * 
	 * @var object 
	 */
	protected $_result = null;
	
	/**
	 * Errors
	 * 
	 * @var array 
	 */
	protected $_errors = [];
	
	/**
	 * Default constructor
	 * Call the action or raise errors
	 */
	public function __construct() {
		$this->_params = !empty($_REQUEST) ? (object) $_REQUEST : new stdClass();
		
		$this->_action = isset($this->_params->action) ? $this->_params->action : '';
		
		$this->_result = new stdClass();
		
		if (!empty($this->_action)) {
			$method = strtolower($this->_action) . 'Action';
			if (method_exists($this, $method)) {
				call_user_func(array($this, $method));
			} else {
				$this->_errors[] = 'METHOD <' . $method . '()> NOT SET';
			}
		} else {
			$this->_errors[] = 'NO ACTION';
		}
	}
	
	/**
	 * Get one parameter with a default value if not exist
	 * 
	 * @param string $name
	 * @param mixed $default
	 * @return mixed
	 */
	protected function getParam($name, $default = null) {
		return !empty($this->_params->$name) ? $this->_params->$name : $default;
	}
	
	/**
	 * Get all the parameters with a default value if empty
	 * 
	 * @param mixed $default
	 * @return mixed
	 */
	protected function getParams($default = null) {
		return !empty($this->_params) ? $this->_params : $default;
	}
	
	/**
	 * Default destructor to format results and call the view
	 */
	public function __destruct() {
		if (!empty($this->_errors)) {
			// Call error view
		} else {
			// Call classic view with results as var
		}
		exit;
	}
}