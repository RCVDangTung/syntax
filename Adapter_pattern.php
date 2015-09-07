<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class BaoKim {

    public function __construct() {
        
    }

    public function senPayment($amount = NULL) {
        // Code xử lý gửi thanh toán tại đây
        echo isset($amount) ? $amount : NULL;
    }

}

// Tạo một interface để các adapter không có quyền đổi tên phương thu

interface paymentAdapter {

    public function pay($amount);
}

// Lớp adapter sử dụng
class baokimAdapter implements paymentAdapter {

    private $__baokim;

    public function __construct(Baokim $baokim) {
        $this->__baokim = $baokim;
    }

    public function pay($amount) {
        $this->__baokim->sendPayment($amount);
    }
}
$baokimAdapter = new baokimAdapter(new Baokim());
$baokimAdapter->pay(1234);
