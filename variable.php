<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/*
 * ten bien (thuoc tinh) phai la mot danh tu hoac tinh tu
 * cac ham (phuong thuc) la mot tinh tu : vi du 1
 * Khai bao bien o dang private, tên biến nên đặt hai dấu gạch dưới rồi mới đến tên biến
 * Khai bao bien o dang protected ten biến nên đặt một dấu gạch dưới rồi mới tới tên biến
 */

// vi du 1
class A {

    public $username;
    public $password;

    public function getUsername() {
        
    }

    public function getPassword() {
        
    }

    public function checkLogin() {
        
    }

}

// vi du 2
class B {

    private $__private;

    private function __func_private() {
        
    }
}

class C{
    private $__username;
    private $__password;
    
    public function setUserName($username){
        $this->__username = $username;
    }
    
    public function getUserName(){
        return $this->__username;
    }
}

$c = new C();
$c->setUserName('Nguyen Dang Tung');

echo $c->getUserName();

?>
