<?php
/**
* Very simple WeatherUnderground.com data retrieval for PHP
* This file should stand alone within any project and can safely be moved to anywhere in your hierarchy.
*
* Feel free to use this class any way you wish.
* Even thinking of not properly crediting the author will result in feelings of guilt and low karma.
*
* @package PHP-Wunderground
* @version 1.0
* @author "Matt Carter" <m@ttcarter.com>
* @link http://hash-bang.net
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*/
class Wunderground {
	/**
	* The longitude of the area we are querying
	* @var float
	*/
	var $lon = 9.58;

	/**
	* The latitude of the area we are querying
	* @var float
	*/
	var $lat = 60.10;

	/**
	* The distance above sea-level (in meters) of the area we are querying
	* @var float
	*/
	var $msl = 70;

	/**
	* What caching directory to use
	* Set this to FALSE to not use any caching
	* @var bool|string
	*/
	var $cache_dir = FALSE;

	/**
	* The period of life for a cache file
	* Files older than this will be removed on subsequent data requests
	*/
	var $cache_expiry = 3600; // 1 hour

	/**
	* The last XML object of a forecast request
	* @var object
	*/
	var $forecast_xml;

	/**
	* Magic method binder to dumbly set the above variables
	* e.g. set_cache_dir is bound to $this->cache_dir = $x
	* @param string $method The method being called
	* @param array $args The arguments being passed to the magic method
	*/
	function __call($method, $args = null) {
		if (substr($method, 0, 4) == 'set_') {
			$var = substr($method, 4);
			$this->$var = $args[0];
		} else {
			trigger_error("Unknown method: $method");
		}
	}

	/**
	* Perform a retrieval for the Wunderground forecast information
	*/
	function get_forecast_data($force = FALSE) {
		$req = "http://api.yr.no/weatherapi/locationforecast/1.8/?lat={$this->lat};lon={$this->lon};msl={$this->msl}";
		if ($this->cache_dir && !$force) {
			$cfile = "{$this->cache_dir}/WU-{$this->lat}-{$this->lon}-{$this->msl}.xml";

			// Tidy cache
			$expiry = mktime() + $this->cache_expiry;
			foreach (glob("{$this->cache_dir}/*.xml") as $file)
				if (filectime($file) > $expiry)
					unlink($file);

			if (!file_exists($cfile)) {
				$blob = file_get_contents($req);
				if (!$blob) die("Invalid return from request to $req");
				$fh = fopen($cfile, 'w');
				fwrite($fh, $blob);
				fclose($fh);
			}
			$this->forecast_xml = simplexml_load_file($cfile);
		} else {
			$this->forecast_xml = simplexml_load_file($req);
		}
	}

	/**
	* Get the forecast of a specific date
	* The date will be rounded backwards to the beginning of the given hour
	* So 2011-01-01 15:43 becomes 2011-01-01 15:00
	* @param int $epoc The epoc of the date to retrieve
	*/
	function get_forecast($epoc) {
		if (!$this->forecast_xml) die("Called get_forecast() before get_forecast_data()");
		$from = date('Y-m-d', $epoc) . 'T' . date('H', $epoc) .':00:00Z';

		$info = array('date' => array('epoc' => $epoc, 'iso' => $from));
		if (!$casts = $this->forecast_xml->xpath("//time[@from='$from']/location"))
			return FALSE;
		foreach ($casts as $forecast) {
			if ($forecast->xpath("//temperature")) {
				foreach ((array) $forecast as $key => $branch) {
					if ($key == '@attributes') continue;
					$branch = (array) $branch; 
					$info[$key] = $branch['@attributes'];
				}
			}
		}
		return (count($info) == 1) ? FALSE : $info;
	}

	/**
	* Retrieves a range of forecasts from a beginning epoc to the end
	* Times are rounded in the same was as get_forecast()
	* @param int $start The epoc starting point
	* @param int $start The epoc end point
	* @param int $step What value to step by. The default is one hour
	* @ see get_forecast()
	*/
	function get_forecast_steps($start, $end, $step = 3600) {
		$forecast = array();
		$epoc = mktime(date('h', $start), 0, 0, date('m', $start), date('d', $start), date('Y', $start)); // Correct $start point to hour
		do {
			if ($slice = $this->get_forecast($epoc))
				$forecast[$epoc] = $slice;
			$epoc += $step;
		} while ($epoc < $end);
		return $forecast;
	}
}
?>
