<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/*
 * Lop Abstract dinh nghia cac ham (phuong thuc)
 * Lop con ke thua no se  Overwrite lại (tính đa hình)
 * Cac phuong thuc cua abstract deu duoc khai bao abstract
 * muc do protected va public
 * Cac thuoc tinh khong o dang abstract
 * Khong phai la mot doi tuong
 */

abstract class Home{
    abstract public function showInfo();
}



class Demo extends Home{
    public function showInfo() {
        ;
    }
}

?>
