<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
/*
 * Bat duoc cac su kien  khi goi toi mot phuong thuc 
 * khong ton tai
 * 
 * __callStatic dung goi cac phuong thuc tinh
 * 
 * It Dung => De thong bao loi trong qtrinh code
 */

class Demo_5 {

    public function __call($method, $param) {
        echo 'Tên phương thức: ' . $method . '<br/>';
        echo 'Tham số truyền vào: ' . $method . '<br/>';
        echo '<pre>';
        print_r($param);
    }

}

$demo_5 = new Demo_5();
$demo_5->get_by_id("nguyen dang tung");

?>
