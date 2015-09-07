<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/*
 * Interface k phai la mot doi tuong
 * La khuon mau cho mot doi tuong
 * hằng số ở lớp implement không được định nghĩa lại
 */

interface Demo_2{
    
    const ten = "Nguyen Dang Tung";

    public function showInfo();
}

class Demo_3 implements Demo_2{
    
    public function showInfo(){
        echo self::ten;
    }
}

echo Demo_2::ten;

$demo_4 = new Demo_3();
$demo_4->showInfo();

echo $demo_4::ten;
        
?>
