<?php
ini_set('memory_limit', '-1');
$csvFile = file('Product_Spec_Migration-alt1.csv');
$data = [];
foreach ($csvFile as $line) {
	$data[] = str_getcsv($line);
}
print '<pre>';
print_r($data);
print '</pre>';