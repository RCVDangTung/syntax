<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


echo date('n_Y', time());

echo "<br />";

echo date('W');


echo "<br />";
$thisYear = date('Y');
$start_week = date('W');
echo $data['start_day'] = strtotime("$thisYear-W$start_week-1");
echo "<br />";
echo $start_day = date('Y-m-d', $data['start_day']);


echo "<br />";

$lastMonth = date("n") - 1;
$thisMonth = date("n");
$nextMonth = date("n") + 1;

echo "<br />";

$thisYear = date("Y");

// Set days. mktime format: mktime(hr, min, sec, mth, day, yr)
$today = date("j");
echo $numDays = date("t", mktime(0, 0, 0, $thisMonth, 1, $thisYear)); // Number of days in current month
echo "<br />";
echo $startDay = date("w", mktime(0, 0, 0, $thisMonth, 1, $thisYear)); // Day the current month starts on
