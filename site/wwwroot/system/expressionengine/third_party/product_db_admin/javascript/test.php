<?php

$str = "spec_PULSE_WIDTH_uom";
//print strpos($str,"uom");
$temp_length = strlen($str);
$product["attribute"] = substr($str,0,$temp_length-4);
print $product["attribute"];
print '<br/>';


$product["attribute"] = substr($product["attribute"],5);

print $product["attribute"];
print '<br/>';