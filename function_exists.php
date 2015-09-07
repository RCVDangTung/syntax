<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function demo() {
    
}

//$a = function_exists('demo');

$array = array(
    array(
        'id' => 2135,
        'first_name' => array(
            'id' => 3245,
            'first_name' => 'Sally',
            'last_name' => 'Smith',
        ),
        'salary' => '500000 - VND',
    ),
);

function array_column_1(array $array, $columnKey, $indexKey = null) {
    $result = array();
    foreach ($array as $subArray) {
        if (!is_array($subArray)) {
            continue;
        } elseif (is_null($indexKey) && array_key_exists($columnKey, $subArray)) {
            $result[] = $subArray[$columnKey];
        } elseif (array_key_exists($indexKey, $subArray)) {
            if (is_null($columnKey)) {
                $result[$subArray[$indexKey]] = $subArray;
            } elseif (array_key_exists($columnKey, $subArray)) {
                $result[$subArray[$indexKey]] = $subArray[$columnKey];
            }
        }
    }
    return $result;
}

$a = array_column_1($array, 'first_name', 'id');
echo "<pre>";
var_dump($a);
//echo "<pre>";
//var_dump($array);
?>
