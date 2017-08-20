<?php

function __autoload($className){
	echo 'class_name'.$className;
	$className = str_replace('..','',$className);
	require_once ("models/$className.php");
}

$user  = new User();
echo "<pre>";

print_r($user);
echo "</pre>";