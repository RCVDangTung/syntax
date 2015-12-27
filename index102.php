<?php

//$total = array();
//for ($j = 1; $j <= 9; $j++) {
//    array_push($total, $j);
//    for ($i = 1; $i <= $j; $i++) {
//
//        echo $i;
//    }
//    echo ' = ' . array_sum($total) . " <br/>";
//}

$total = array();
for ($j = 1; $j <= 9; $j++) {
//    array_push($total, $j);
//    echo $j . "<br>";
//    for($i = 1 ; $i <= $j; $i++){
////        echo $i;
//    }
//    echo "<pre>";
//    var_dump($total);
//    echo ' = ' . array_sum($total) . "<br />";
}





//echo "<pre>";
//var_dump($total);

$array = array();
for ($x = 1; $x <= 10; $x++) {
    array_push($array, $x);
//    $array[] = $x;
}


//echo "<pre>";
//var_dump($array);

$array_sum = array(
    array(
        'gozhi' => 2,
        'uzorong' => 1,
        'ngangla' => 4,
        'langthel' => 5
    ),
    array(
        'gozhi' => 5,
        'uzorong' => 0,
        'ngangla' => 3,
        'langthel' => 2
    ),
    array(
        'gozhi' => 3,
        'uzorong' => 0,
        'ngangla' => 1,
        'langthel' => 3
    ),
);

//result 
//
//Array
//(
//    [gozhi] => 10
//    [uzorong] => 1
//    [ngangla] => 8
//    [langthel] => 10
//)


echo "<pre>";
var_dump($array_sum);

echo "<br /><br /><br />";
echo '================================';

echo "<br />";


//Case 1
foreach ($array_sum as $k => $subArray) {
    foreach($subArray as $t_k => $val){
        $array_sum[$t_k] += $val;
    }
}
//echo "<pre>";
//var_dump($array_sum);


//Case 2

$newarr=array();
foreach($arrs as $value)
{
  foreach($value as $key=>$secondValue)
   {
       if(!isset($newarr[$key]))
        {
           $newarr[$key]=0;
        }
       $newarr[$key]+=$secondValue;
   }
}





//$count = count($array_sum);

//for($t = 0;$t <= $count ; $t++){
//    $sumResult = array_map("sum", $array_sum[$t]);
//}
//$sumResult = array_map("sum", $array_sum[0], $array_sum[1], $array_sum[2]);
//
//function sum($arr1, $arr2, $arr3)
//{
//    return($arr1+$arr2+$arr3);
//}
//
//
//var_dump($sumResult);

//$test_array = array("first_key" => "first_value", 
//                "second_key" => "second_value");
//$result = array_map(function($key, $val) {
//    return $key . ' ' . $val;
//}, $test_array, array_keys($test_array));
//
//var_dump($result);
?>