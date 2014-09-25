<?php

require 'controller.php';

/**
 * JSONController class
 * Extends Controller class
 */
class JSONController extends Controller {
	
	/**
	 * Override default destructor to format results
	 */
	public function __destruct() {
		header('Content-type: application/json');
		if (!empty($this->_errors)) {
			echo json_encode($this->_errors);
		} else {
			echo json_encode($this->_result);
		}
		exit;
	}
}