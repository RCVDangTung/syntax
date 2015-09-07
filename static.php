<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


/*
 * dấu chấm (::) để truy xuất đến hàm tĩnh của lớp cha
 */
class Demo_6 {

    protected static $_name = "";

    public static function setName($name) {
        Demo_6::$_name = $name;
    }

    public static function getName() {
        return Demo_6::$_name;
    }
}

class Demo_7 extends Demo_6{
    public static function setName($name) {
        parent::setName($name);
    }
}

Demo_6::setName('Nguyen Dang Tung');
echo Demo_6::getName();
?>
