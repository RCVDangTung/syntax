<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

interface test {

    public function A();
}

class TestImplementor implements test {

    public function A() {
        print "A";
    }

}

$testImpl = new TestImplementor();
// Kiem tra doi tuong la cac lop hoac lop nay la lop cha
var_dump(is_a($testImpl, 'test'));
?>
