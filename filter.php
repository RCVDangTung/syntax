<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

// Lấy thông tin lọc
$filter = array(
    'email' => isset($_GET['email']) ? mysql_escape_string($_GET['email']) : false,
    'phone' => isset($_GET['email']) ? mysql_escape_string($_GET['phone']) : false,
    'address' => isset($_GET['email']) ? mysql_escape_string($_GET['address']) : false,
    'fullname' => isset($_GET['email']) ? mysql_escape_string($_GET['fullname']) : false
);


$filter_1 = array(
    'email' => 'nguyendangtung92@gmail.com',
    'phone' => '0962785428',
    'address' => 'Thanh Liet - Thanh Tri - Ha Noi',
    'fullname' => 'nguyendangtung'
);
//echo "<pre>";
//var_dump($filter_1);
//die;
// Biến lưu trữ lọc
$where = array();

// Nếu có chọn lọc thì thêm điều kiện vào mảng
if ($filter_1['email']) {
    $where[] = "email = '{$filter_1['email']}'";
}

if ($filter_1['phone']) {
    $where[] = "phone = '{$filter_1['phone']}'";
}

if ($filter_1['address']) {
    $where[] = "address = '{$filter_1['address']}'";
}

if ($filter_1['fullname']) {
    $where[] = "fullname = '{$filter_1['fullname']}'";
}

// Câu truy vấn cuối cùng
$sql = 'SELECT * FROM customer';
if ($where) {
    $sql .= ' WHERE ' . implode(' AND ', $where);
}
echo "<pre>";
var_dump($sql);
echo "<br />";
?>
