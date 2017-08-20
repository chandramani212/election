<?php

function encode_arr($data) {
    return base64_encode(serialize($data));
}

function decode_arr($data) {
    return unserialize(base64_decode($data));
}

function getOtp(){
	$x = 4; // Amount of digits
	$min = pow(10,$x);
	$max = (pow(10,$x+1)-1);
	$value = rand($min, $max);
	
	return $value;
}

function dd($arr){
	echo '<pre>';
	print_r ($arr);
	echo '</pre>';
	
	exit;
	
}

function getMethod(){
	
	return $_SERVER['REQUEST_METHOD'] ;
}

function base64StringToImage($base64String, $writeFolder, $outputFile)
{
	//open  file
	$file = fopen($writeFolder . "/" . $outputFile, "wb");

	//problem with open a file
	if (!$file)
		throw new \Exception('can able to open file');

	//write file from base64 string
	$writeStatus = fwrite($file, base64_decode($base64String));

	//problem with write
	if (!$writeStatus)
		throw new \Exception('file is not writable');

	//close the file
	fclose($file);

	//return file name
	return ($outputFile);
}

function base64ToImage($imageData, $targetPath){
    //$data = 'data:image/png;base64,AAAFBfj42Pj4';
	 $imgArr = explode(';', $imageData);
	 //print_r($imgArr);
	 if(count($imgArr) > 1 ){
		$type = $imgArr[0];
		$imageData = $imgArr[1];
		list(,$extension) = explode('/',$type);
		list(,$imageData)      = explode(',', $imageData);
		 $imageName = uniqid().'.'.$extension;
		 $fileName = $targetPath.'/'.$imageName;
		 $imageData = base64_decode($imageData);
		 
	 }else{
		$imageName = uniqid().'.png';
		$fileName = $targetPath.'/'.$imageName;
	 }
    
    file_put_contents($fileName, base64_decode($imageData));
	
	return $imageName;
}

function getIdFromUrl($currentPage){
	
	//echo"pages:".$currentPageName = $_SERVER['PHP_SELF'];
	//$currentPage	 = str_replace('.php','',$currentPageName);
	$url =  $_SERVER['REQUEST_URI'];
	$id = preg_replace("/(.*)\/".$currentPage."(.*)\/(.*)/","$3",$url);
	return $id;
}