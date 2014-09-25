<?php

require dirname(__FILE__) . '/lib/db.php';

$cities = DB::fetchAll('SELECT * FROM cities WHERE lat IS NULL');

foreach ($cities as $city) {
	$url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . str_replace(' ', '+', $city->name) . ',+France&key=AIzaSyCu4EvVbT9D3uLj-Ei0fOVs2s_Ie7Hmck0';
	$json = json_decode(file_get_contents($url));
	
	if (isset($json->results[0])) {
		try {
			$sql = 'UPDATE cities SET '
					. 'lat = ' . DB::q($json->results[0]->geometry->location->lat) . ', '
					. 'lng = ' . DB::q($json->results[0]->geometry->location->lng) . ' '
					. 'WHERE id = ' . DB::q($city->id);

			DB::execute($sql);

		} catch(PDOException $e) {
			var_dump($e);
		}
	} else {
		var_dump($url, $json);
	}
}
