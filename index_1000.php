<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
mb_internal_encoding("UTF-8");
$string = "0123456789";
$mystring = mb_substr($string, 5, 2);

//echo $mystring;
//echo sprintf("%s", '982938938fkdfkdhfa');
//echo sprintf("%012d", '19900');
//echo $invoice_no = date('YmdHis') . rand();
//$randomNumbersArray = array_map(function() {
//    return mt_rand();
//}, range(1, 6));
//echo date('Y-m-d H:i:s:B');
//var_dump($randomNumbersArray);
//echo mt_rand();
//echo "<br />";
//echo str_shuffle(mt_rand());
//echo date('YmdHis') . str_shuffle(mt_rand());
//function giveMeRandNumber($count = 12) {
//    $invoiceID = '';
//    for ($i = 0; $i <= $count; $i++) {
//        $invoiceID = str_shuffle(mt_rand());
//    }
//    return $invoiceID;
//}

function Message_Id($id_length = 12) {
    $alfa = "1234567890";
    $message_id = "";
    for ($i = 1; $i < $id_length; $i ++) {
        @$message_id .= $alfa[rand(1, strlen(str_shuffle($alfa)))];
    }
    return $message_id;
}

//function rand_char($length) {
//    $random = '';
//    for ($i = 0; $i < $length; $i++) {
//        $random .= chr(mt_rand(33, 126));
//    }
//    return $random;
//}
//
//echo rand_char(12);
//$invoiceID_1 = date('His') . str_shuffle(mt_rand(1, 5));
//
//$token = hash('sha512', uniqid(mt_rand()));
//echo $token;
//echo $invoiceID_1;
//echo $invoiceID_1;
//echo sprintf("%012d", $invoiceID_1);
//$randomNumbersArray = giveMeRandNumber(6);
//echo "<pre>";
//var_dump(giveMeRandNumber());

$stamp = date("His");
echo $stamp;
echo "<br>";
$random_id_length = 6;
$rndid = generateRandomString($random_id_length);

echo $rndid;
echo "<br>";

$orderid = str_shuffle($rndid) . $stamp;
//$orderid = rand() . $stamp;

echo $orderid;
//echo sprintf("%012d", $orderid);

//$orderid = $stamp . "-" . $rndid;
//echo($orderid);

function generateRandomString($length = 10) {
    $characters = '01234567891';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}
