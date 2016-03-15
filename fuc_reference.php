<?php

function sb_plus_number(&$a) {
    $a += 10;
}

$a = 1;
sb_plus_number($a);
echo 'tham chiếu';
echo "<br />";
echo $a; // Kết quả là số 11

echo "<hr />";
echo "<br />";

// Tham tri

function sb_plus_number_new($a) {
    $a += 10;
}

$a = 1; // truyền vào hàm xong gán lại
sb_plus_number_new($a);
echo 'tham trị';
echo "<br />";
echo $a; // Kết quả là số 1

echo "<br />";

function sb_plus_number_news($a) {
    $c = $a;
    $c += 10;
}

$a = 1;

sb_plus_number_news($a);

echo $a;

//function sb_plus_number(&$a) {
//    $c = &$a;
//    $c += 10;
//}
//$a = 1;
//sb_plus_number($a);
//echo $a;



$a = 0;
$b = $a;
$a = 1;

echo 'bien a' . $a;
echo "<br />";
echo 'bien b' . $b;

$m = 0;

$n = &$m;
$n = 9;

echo "<br />";
echo "<br />";
echo 'bien m' . $m;
echo "<br />";
echo 'bien n' . $n;


echo "<br />";
echo "<br />";

$k = 2;

function convert1(&$x) {
    $x*= -1;
    return $x;
}

$result = convert1($k);
echo $result;
echo $k;

// tham chiếu các biến truyền vào hàm khi kết thúc hàm gia trị biến thay đổi nội dung bên trong hàm
// tham trị các biến truyền vào hàm sau khi chạy kết thúc hàm giá trị biến k thay đổi 
echo "<br />";
echo "<br />";

$a1 = "WILLIAM";
$a2 = "henry";
$a3 = "gatES";

echo $a1 . " " . $a2 . " " . $a3 . "<br /><br /><br />";


fix_names($a1, $a2, $a3);

echo $a1 . " " . $a2 . " " . $a3 . "<br /><br /><br />";

function fix_names(&$m1, &$m2, &$m3) {
    $m1 = ucfirst(strtolower($m1));
    $m2 = ucfirst(strtolower($m2));
    $m3 = ucfirst(strtolower($m3));
}



