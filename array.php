<?php

$array_1 = Array(
    'apple' => 1,
    'banana' => 2,
    'choco' => 1,
    'donut' => 2,
    'egg' => 1
);

$array_2 = array(
    'apple' => 8,
    'banana' => 2,
    'choco' => 1
);

$array_3 = array(
    'donut' => 5,
    'choco' => 2,
    'egg' => 3
);

$tmp_array = array();
foreach ($array_1 as $key => $value) {
//    echo "<pre>";
//    var_dump($array_2[$key]);
    if (!isset($array_2[$key])) {
        $tmp_array[$key] = $value;
    } else {
        $tmp_array[$key] = $array_2[$key] + $value;
    }
//    echo '<pre>';
//    var_dump($key);
}

//echo "<pre>";
//var_dump($tmp_array);

function doCalc($array_check, $array_input) {
    $array_tmp = array();
    foreach ($array_input as $key => $val) {
        if (!isset($array_check[$key])) {
            $array_tmp[$key] = $val;
        } else {
            $array_tmp[$key] = $array_check[$key] + $val;
        }
    }
    return $array_tmp;
}


$result_1 = doCalc($array_2, $array_1);
$result_2 = doCalc($array_3, $array_1);


echo "<pre>";
var_dump($result_1);
echo '<hr />';
echo "<pre>";
var_dump($result_2);