<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/*
 * Lop k cho ke thua
 * Cac ham (phuong thuc) chi duoc goi su dung khong duoc Overwrite
 */

final class Home_1{
    protected $ten;
    protected $tuoi;
    
    public function showInfo(){
        echo "Nguyễn Đăng TÙng";
    }
}

$demo = new Home_1();
$demo->showInfo();

?>
