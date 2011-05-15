<?php
require('../wunderground.php');
$w = new Wunderground();
//$w->set_cache_dir('cache'); // Enable this if you wish to use caching and you've set the 'cache' directory to be writable
$w->get_forecast_data();
print_r($w->get_forecast(strtotime('2011-05-15 15:00')));
print_r($w->get_forecast_steps(strtotime('2011-05-15 15:00'), strtotime('2011-05-16 15:00')));
?>
