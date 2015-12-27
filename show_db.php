<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//$this->output->enable_profiler(TRUE);
//mình có mảng array(1, 2, 4, 6, 2, 4)
//Làm thế nào so sánh các phần tử trong mảng với nhau

$mang = array(1, 2, 3, 4, 5, 21, 91, 22, 2);

//for ($i = 0; $i < count($mang); $i++) {
//    echo 'phan tu dau tien' . $mang[$i];
//    for ($j = 0; $j < count($mang); $j++) {
//
//        if ($mang[$i] > $mang[$j]) {
//            echo $mang[$i] . 'lon hon' . $mang[$j] . '<br>';
//        } elseif ($mang[$i] < $mang[$j]) {
//            echo $mang[$i] . 'be hon' . $mang[$j] . '<br>';
//        } else {
//            echo $mang[$i] . '= hon' . $mang[$j] . '<br>';
//        }
//    }
//}
//for($i = 0; $i < count($mang);$i++) {
//    echo $mang[$i] . '<br/>';
//    for($j = 0;$j < count($mang) ; $j++){
//        echo 'mang $mang[$j]';
//        echo $mang[$j] . '<br/>';
//    }
//}

$shop = array(
    array(array("rose", 1.25, 15),
        array("daisy", 0.75, 25),
        array("orchid", 1.15, 7)
    ),
    array(array("rose", 1.25, 15),
        array("daisy", 0.75, 25),
        array("orchid", 1.15, 7)
    ),
    array(array("rose", 1.25, 15),
        array("daisy", 0.75, 25),
        array("orchid", 1.15, 7)
    )
);
