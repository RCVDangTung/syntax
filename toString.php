<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class toString{
    public $foo;
    public function __construct($foo){
        $this->foo = $foo;
    }
    public function __toString(){
        return $this->foo;
    }
}

$class = new toString('Hello');
echo $class;