An exceptionally simple Weather Underground PHP class for simple data retrieval.

Examples
========
The following will retrieve a detailed 2 day forecast in the form of an array.
The last perameter specifies the gaps between data. In the below example its set to a day (60 seconds * 60 minutes * 24 hours). Omit this if you want as much data as possible.

	<?php
	require('wunderground.php');
	$w = new Wunderground();
	$w->get_forecast_data();
	print_r($w->get_forecast_steps(time(), strtotime('+2 days'), 60*60*24));
	?>

Will return
	Array
	(
	    [1305385200] => Array
		(
		    [date] => Array
			(
			    [epoc] => 1305385200
			    [iso] => 2011-05-15T01:00:00Z
			)

		    [precipitation] => Array
			(
			    [unit] => mm
			    [value] => 0.4
			    [minvalue] => 0.0
			    [maxvalue] => 0.8
			)

		    [symbol] => Array
			(
			    [id] => LIGHTRAINSUN
			    [number] => 5
			)

		)

	    [1305471600] => Array
		(
		    [date] => Array
			(
			    [epoc] => 1305471600
			    [iso] => 2011-05-16T01:00:00Z
			)

		    [temperature] => Array
			(
			    [id] => TTT
			    [unit] => celcius
			    [value] => 3.6
			)

		    [windDirection] => Array
			(
			    [id] => dd
			    [deg] => 305.2
			    [name] => NW
			)

		    [windSpeed] => Array
			(
			    [id] => ff
			    [mps] => 2.2
			    [beaufort] => 2
			    [name] => Svak vind
			)

		    [humidity] => Array
			(
			    [value] => 90.2
			    [unit] => percent
			)

		    [pressure] => Array
			(
			    [id] => pr
			    [unit] => hPa
			    [value] => 1007.8
			)

		    [cloudiness] => Array
			(
			    [id] => NN
			    [percent] => 41.2
			)

		    [fog] => Array
			(
			    [id] => FOG
			    [percent] => 0.0
			)

		    [lowClouds] => Array
			(
			    [id] => LOW
			    [percent] => 37.6
			)

		    [mediumClouds] => Array
			(
			    [id] => MEDIUM
			    [percent] => 5.7
			)

		    [highClouds] => Array
			(
			    [id] => HIGH
			    [percent] => 0.0
			)

		    [precipitation] => Array
			(
			    [unit] => mm
			    [value] => 0.0
			    [minvalue] => 0.0
			    [maxvalue] => 0.0
			)

		    [symbol] => Array
			(
			    [id] => LIGHTCLOUD
			    [number] => 2
			)

		)

	    [1305558000] => Array
		(
		    [date] => Array
			(
			    [epoc] => 1305558000
			    [iso] => 2011-05-17T01:00:00Z
			)

		    [temperature] => Array
			(
			    [id] => TTT
			    [unit] => celcius
			    [value] => 6.0
			)

		    [windDirection] => Array
			(
			    [id] => dd
			    [deg] => 227.9
			    [name] => SW
			)

		    [windSpeed] => Array
			(
			    [id] => ff
			    [mps] => 1.6
			    [beaufort] => 1
			    [name] => Flau vind
			)

		    [humidity] => Array
			(
			    [value] => 90.1
			    [unit] => percent
			)

		    [pressure] => Array
			(
			    [id] => pr
			    [unit] => hPa
			    [value] => 1003.2
			)

		    [cloudiness] => Array
			(
			    [id] => NN
			    [percent] => 2.7
			)

		    [fog] => Array
			(
			    [id] => FOG
			    [percent] => 0.0
			)

		    [lowClouds] => Array
			(
			    [id] => LOW
			    [percent] => 2.7
			)

		    [mediumClouds] => Array
			(
			    [id] => MEDIUM
			    [percent] => 0.3
			)

		    [highClouds] => Array
			(
			    [id] => HIGH
			    [percent] => 0.0
			)

		    [precipitation] => Array
			(
			    [unit] => mm
			    [value] => 0.0
			    [minvalue] => 0.0
			    [maxvalue] => 0.0
			)

		    [symbol] => Array
			(
			    [id] => SUN
			    [number] => 1
			)

		)

	)
