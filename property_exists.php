<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class myClass{
    public $name;
    public $age;
    
    static protected $test;
    static function test(){
        var_dump(property_exists('myClass', 'age'));
    }
}


myClass::test();

class TestClass{
    public $declared = null;
}
$testObject = new TestClass;
$testObject->monkey = NULL;
//  kiem tra có phải 1 đối tượng cà tồn tại thuộc tính
var_dump(property_exists($testObject, 'monkey'));

?>
