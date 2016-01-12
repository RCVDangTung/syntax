<?php

$d1 = strtotime("2009-09-01");
$d2 = strtotime("2010-05-01");
$min_date = min($d1, $d2);
$max_date = max($d1, $d2);
$i = 0;

while (($min_date = strtotime("+1 MONTH", $min_date)) <= $max_date) {
$i++;
}
echo $i; // 8
