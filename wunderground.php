<?
class Wunderground {
	var $lon = 9.58;
	var $lat = 60.10;
	var $msl = 70; // Meters above sea level

	var $cache_dir = FALSE;
	var $cache_expiry = 3600; // 1 hour

	var $xml;

	function set_cache_dir($cache_dir) {
		$this->cache_dir = $cache_dir;
	}

	function get_data($force = FALSE) {
		$req = "http://api.yr.no/weatherapi/locationforecast/1.8/?lat={$this->lat};lon={$this->lon};msl={$this->msl}";
		if ($this->cache_dir || !$force) {
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
			$this->xml = simplexml_load_file($cfile);
		} else {
			$this->xml = simplexml_load_file($req);
		}
	}

	function get_forecast($epoc) {
		if (!$this->xml) die("Called get_forecast() before get_data()");
		$from = date('Y-m-d', $epoc) . 'T' . date('H', $epoc) .':00:00Z';

		$info = array('date' => array('epoc' => $epoc, 'iso' => $from));
		foreach ($this->xml->xpath("//time[@from='$from']/location") as $forecast) {
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

	function get_forecast_steps($start, $end) {
		$forecast = array();
		$epoc = mktime(date('h', $start), 0, 0, date('m', $start), date('d', $start), date('Y', $start)); // Correct $start point to hour
		do {
			$forecast[$epoc] = $this->get_forecast($epoc);
			$epoc += 60*60;
		} while ($epoc < $end);
		return $forecast;
	}
}

$w = new Wunderground();
$w->set_cache_dir('cache');
$w->get_data();
#print_r($w->get_forecast(strtotime('2011-05-15 15:00')));
print_r($w->get_forecast_steps(strtotime('2011-05-15 15:00'), strtotime('2011-05-16 15:00')));
?>
