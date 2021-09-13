<?php

$url = 'https://c.xkcd.com/random/comic/';      //xkcd url

$ch  = curl_init();     
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //Must be set to true so that PHP follows any "Location:" header.

$a = curl_exec($ch);    //execute

$url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);   //return the last effective url

$str  = file_get_contents($url.'info.0.json');      //getting the contents of the url in json format

$json = json_decode($str, true);        //decoding 

$imageTitle = $json['title'];       //getting image title

$imageUrl = $json['img'];       //getting image url


curl_close($ch);        //closing the curl

$image = 'image.jpg';       

$fimage = fopen($image, 'w+');      //opening the file 
$ch = curl_init($imageUrl);     
curl_setopt($ch, CURLOPT_FILE, $fimage);       //transfer should be written to $fimage
curl_exec($ch);     //execute url
curl_close($ch);    //close curl
fclose($fimage);    //close file
