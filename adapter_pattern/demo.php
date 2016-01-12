<?php

class Booker {

    public function __construct() {
        // Hàm khởi tạo
    }

    public function paymentRoom($amount) {
        // Code xử lý gửi thanh toán tại đây
        echo $amount;
    }

}

// Tạo một interface để các adapter không có quyền đổi tên hàm 
interface paymentAdapter {

    public function pay($amount);
}

// Lớp adapter sử dụng
class bookerAdapter implements paymentAdapter {

    private $__booker;
    
    private $__test;
    
    public function __construct(Booker $booker,Array $demo) {
        $this->__booker = $booker;
        
        $this->__test = $demo;
        
        var_dump($this->__test);
    }

    public function pay($amount) {
        $this->__booker->paymentRoom($amount);
    }

}

// Cách dùng
$test = array();
$bookerAdapter = new bookerAdapter(new Booker() , $test);

echo "<pre>";
var_dump($bookerAdapter);
//$bookerAdapter->pay(1234);
