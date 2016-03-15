<?php

//https://www.xeweb.net/2011/02/11/generate-a-random-string-a-z-0-9-in-php/
//http://www.i-fubar.com/random-string-generator.php
//function randomString($length = 3) {
//    $str = "";
//    $characters = array_merge(range('A', 'Z'), range('a', 'z'));
//    $max = count($characters) - 1;
//    for ($i = 0; $i < $length; $i++) {
//        $rand = mt_rand(0, $max);
//        $str .= $characters[$rand];
//    }
//    return $str;
//}
//
////echo randomString();
//
//function randomNumber($length = 8) {
//    $salt = array_merge(range(0, 9));
//    $maxIndex = count($salt) - 1;
//    $result = '';
//    for ($i = 0; $i < $length; $i++) {
//        $index = mt_rand(0, $maxIndex);
//        $result .= $salt[$index];
//    }
//    return $result;
//}
//
//function join_code() {
//    return randomString() . randomNumber();
//}
//
////echo join_code();
//
//function random_code($length = 12) {
//    $strRandom = "";
//}
//
////https://github.com/ntaback26/angular-tutorial
////https://github.com/timjacobi/angular2-education
//
//
//
//random_string();
//foreach (range(0, 12) as $number) {
//    echo "$number, ";
//}
//foreach (range('A', 'Z') as $char) {
//    echo $char . "\n";
//}


function random_string($length = NULL, $type = NULL) {
    $result = "";
    $range_string = array_merge(range('A', 'Z'), range('a', 'z'));
//    echo "<pre>";
//    var_dump(count($range_string));
//    die();
    $range_number = array_merge(range(0, 9));
    for ($i = 0; $i <= $length; $i++) {
        if ($type == "string") {
            $key = count($range_string) - 1;
            $rand = mt_rand(0, $key);
            $result .= $range_string[$rand];
        } elseif ($type == "number") {
            $key = count($range_number) - 1;
            $rand = mt_rand(0, $key);
            $result .= $range_number[$rand];
        }
    }
    return $result;
}

//echo random_string(9, $type = 'string');

//die();

function random_t($length_min = NULL , $length_max = NULL) {
    $result = "";
    $range_string = array_merge(range('A', 'Z'), range('a', 'z'));
    $range_number = array_merge(range(0, 9));
    for ($i = 1; $i <= $length_min; $i++) {
        $key = count($range_number) - 1;
        $rand = mt_rand(0, $key);
        $result .= $range_number[$rand];
        
    }
    return $result;
}






//for ($i = 0; $i <= $length; $i++) {
//
//
//    if ($type == "string") {
//        $key = count($range_string) - 1;
//        $rand = mt_rand(0, $key);
//        $result .= $range_string[$rand];
//    } elseif ($type == "number") {
//        $key = count($range_number) - 1;
//        $rand = mt_rand(0, $key);
//        $result .= $range_number[$rand];
//    }
//}


echo "<pre>";
echo "<br />";
$type = array("string", "number");
random_string(10, $type);
//die();

//echo random_string(9, "number");
//echo "<pre>";
//var_dump($type);
//die();

function rand_length($length) {
    $array_random = array();
    $x = 1;
    while ($x <= $length) {
        $array_random[] = random_string(9, "number");
        $x++;
    }

    return $array_random;
}

var_dump(rand_length(100));


  
//echo "<hr />";
//echo "<br />";echo "<br />";
//echo "<br />";echo "<br />";
//echo "<br />";echo "<br />";
//echo "<br />";echo "<br />";
//
//$len = 10;  // total number of numbers
//$min = 0; // minimum
//$max = 10; // maximum
//foreach (range(0, $len - 1) as $i) {
//    while(in_array($num = mt_rand($min, $max), $range));
//    $range[] = $num;
//}
//print_r($range);

$n = 100;  
$numbers = range(0, $n-1);
$rands = array();
for ($i=0; $i < $n; $i++) {
  $ok = false;
  while (!$ok) {
    $x = array_rand($numbers);
    $ok = !in_array($numbers[$x], $rands) && $numbers[$x] != $i;
  }
  $rands[$i] = $numbers[$x];
  unset($numbers[$x]);
}

var_dump($rands);


$n = 100;  
$numbers = range(0, $n-1);

$x = array_rand($numbers);

var_dump($x);

$a = strtolower('13 hello MANH BUI');
var_dump($a);