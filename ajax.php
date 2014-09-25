<?php

require 'db.php';
require 'jsoncontroller.php';

class AjaxController extends JSONController {
	
	public function getnextcityAction() {
		$cities = $this->getParam('exclude', array());
		
		$sql = 'SELECT * '
				. 'FROM cities '
				. 'WHERE population > 80000 '
				. (!empty($cities) ? 'AND id NOT IN ' . DB::q($cities) : '')
				. 'ORDER BY RAND() '
				. 'LIMIT 1 ';
		
		$city = DB::fetchRow($sql);
		
		$this->_result->city = $city;
	}
	
	public function gethighscoresAction() {		
		$sql = 'SELECT * '
				. 'FROM score '
				. 'ORDER BY score DESC '
				. 'LIMIT 10';
		
		$scores = DB::fetchRow($sql);
		
		$this->_result->scores = $scores;
	}
}

new AjaxController();