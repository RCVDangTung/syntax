<?php

//for($i=10; $i>=1; $i--){
//    echo $i . ' ';
//}
//for($i=-6; $i<=0; $i++){
//   printf('<br>%s<br>', date('m/d', strtotime("+$i days")));
//}
//for($i=6; $i>=0; $i--) {
//    echo '<br />' . date('m/d' , strtotime("$i days"));
//}

$n = 10;
$tong = 0;
for ($i = 0; $i <= $n; $i++) {
    $tong += $i;
}

//echo $tong;
//for($i = 1; $i < 10; $i++){
//    
//    for($j = 9;$j >= $i;$j--){
//        echo $j;
//    }
//    echo "<br>";
//}
//$array = array();
//
//
//for ($i = 0; $i <= 12; $i++) {
////    $array[$i] = array();
//    for ($j = 0; $j <= 12; $j++) {
//        $array[$i][$j] = $i * $j;
//    }
//}
//echo "<pre>";
//var_dump($array);
//$total = array();
//for ($j = 1; $j <= 9; $j++) {
//    array_push($total, $j);
//    for ($i = 1; $i <= $j; $i++) {
//        echo $i . ' ';
//    }
//    echo ' = ' . array_sum($total) . " <br/>";
//}
//
//echo "<pre>";
//
//var_dump($total);
//http://laptrinhccanban.com/ky-thuat-xoa-kt-06/
//$array = array(1, 4, 5, 6, 5, 6);
//$count = count($array);
//
//$t = demo($array, $count, 1);
//
//function demo($array_ib, &$count, $vitrixoa) {
//    for ($i = $vitrixoa; $i <= $count - 1; $i++) {
//        $array_ib[$i] = $array_ib[$i + 1];
//        $count--;
//    }
//    
//    return $array_ib;
//}
//
//echo "<pre>";
//var_dump($t);



$sanpham = array(array("ITL", "INTEL", "HARD"),
    array("MIR", "MICROSOFT", "SOFT"),
    array("PHP", "PHPVN.ORG", "TUTORIAL")
);
for ($row = 0; $row < 3; $row++) {
    
    echo "<pre>";
    var_dump($sanpham[$row]);
//    for ($col = 0; $col < 3; $col++) {
//        echo "|" . $sanpham[$row][$col];
//    }
//    echo "<br>";
}
