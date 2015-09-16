<?php

//Linear Search : Tìm kiếm tuyến tính

function findIndex($input = array(),$output = ''){
    for($i = 0;$i < count($input);++$i){
        if($input[$i] == $output){
            return $i.'-----'.$input[$i];
        }
        return FALSE;
    }
}

$array = array('tung','trang');

$flag = findIndex($array,'tung');
echo $flag;


function sort($array_1 = array()){
    for($i = 0;$i < count($array_1);++$i){
        $temp = $array_1[$i];
        for($j = $i - 1;$j >= 0 && $array_1[$j] > $temp; --$j){
            $array_1[$j+1] = $array_1[$j];
        }
        $array_1[$j + 1] = $temp;
    }
}
$array_1 = array(1,3,4,6,8,10,9);
sort($array_1);