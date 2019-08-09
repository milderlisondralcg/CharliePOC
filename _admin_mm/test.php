<?php

$str = "3153-yag--laser-cutting-aint-machine-png-";

//get the last character
// check to see if it is a dash
if(substr($str,-1,1) == "-"){
	print 'found a dash';
	print substr($str,0,strlen($str) - 1);
}else{
	print 'no dash';
}



$title_temp = trim(preg_replace('/-+/', '-', $str), '-');

print $title_temp;