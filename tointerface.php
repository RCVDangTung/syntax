<?php
header('Content-Type: text/html; charset=utf-8');
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


// Là lớp rỗng chỉ chứa khai báo về tên phương thức 
// không chứa khai báo thuộc tính
// Các phương thức khai báo cũng rỗng
// Lớp nào kế thừa lớp lớp interface phải định nghĩa lại 
// các phương thức khai báo ở lớp interface
// Để sử dụng được lớp interface khai báo từ khóa implements
// Một lớp có thể sử dụng được nhiểu interface
// Một lớp có thể vừa kế thừa và interface
// Các thuộc tính phương thức trong interface phải đặt public
class GiamDoc{
    public function __construct() {
        $this->name();
    }
    public function name(){
        echo 'Nguyễn Văn A';
    }
    
    
}
interface A{
    public function nhanvien();
    public function nhanvien_1();
    public function nhanvien_3();
}

interface B{
    public function ctv(); 
    public function ctv1(); 
    public function ctv2(); 
}


class Quanly extends GiamDoc implements A,  B{
    public function nhanvien() {
        
    }

    public function nhanvien_1() {
        
    }

    public function nhanvien_3() {
        
    }

    public function ctv() {
        
    }

    public function ctv1() {
        
    }

    public function ctv2() {
        
    }

}

$ql = new Quanly();

