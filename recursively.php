<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$multiArr = array(
    'name' => 'Dev',
    'hobby' => array(
        '1' => 'Tung',
        '2' => '23'
    ),
    'simplearr' => array(
        'val_1' , 'val_2', 'val_3'
    ),
);


function multiDimArrayMap($array){
    $retArr  = array();
    foreach ($array as $key => $value) {
        if(is_array($value)){
            $retArr[$key] = multiDimArrayMap($value);
        }  else {
            $retArr[$key] = strtoupper($value);
        }
    }
    return $retArr;
}

$finArr = multiDimArrayMap($multiArr);
echo "<pre>";
print_r($finArr);
?>
