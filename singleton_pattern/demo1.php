<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class demo1 {

    public $ta = 'Khuong Van Ngo';
    private static $tb;

    private function __construct() {
        
    }

    public static function getInstance() {
        if (!self::$tb) {
            self::$tb = new demo1();
        }
        return self::$tb;
    }

}


//$demo = new demo1();
//echo "<pre>";
//var_dump($demo);
$s = demo1::getInstance(); // khởi tạo hàm getInstance lưu vào biến $tb
echo $s->ta = 'VanKhuong777';
unset($s);
$s2 = demo1::getInstance();
echo $s2->ta; // vankhuong777


//http://sothichweb.com/article.php?aid=huong_doi_tuong_trong_php_factory%20_pattern_032fc66