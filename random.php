<?php

//    echo date('Ymd') . rand();
//echo rand();
echo time();
echo "<br>";
echo date('Ymd') . rand();
echo "<br>";
$signData = hash_hmac('sha1', "hello", '746D7SCHAIQ0QUZ0MRJWU0PQ3AD7PJ8B', false);
$signData = strtoupper($signData);
echo urlencode($signData);
echo "<br>";


$Amount = '1111111111';

$OrderInfo = 'aaaaaaaa';
echo HashValue($Amount,$OrderInfo);

function HashValue($Amount = NULL, $OrderInfo = NULL) {
    $string_value =  $Amount . $OrderInfo;
    $HashValue = hash_hmac('sha1', $string_value, $secret_key, false);
    return $HashValue;
}


$params = array(
   "name" => "Ravishanker Kusuma",
   "age" => "32",
   "location" => "India"
);
 
echo httpPost("http://hayageek.com/examples/php/curl-examples/post.php",$params);