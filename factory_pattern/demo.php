<?php

abstract class App {

    abstract function mtheodOne();
}

class cap1 extends App {

    public function mtheodOne() {
        return __METHOD__;
    }

}

class cap2 extends App {

    public function mtheodOne() {
        return __METHOD__;
    }

}

class CManager {

    const C1 = 1;
    const C2 = 2;

    private $mode;

    function __construct($mode = 1) {
        $this->mode = $mode;
    }

    public function getCapp() {
        switch ($this->mode) {
            case (CManager::C1):
                return new cap1();
            case (CManager::C2):
                return new cap2();
        }
    }

}

$s = new CManager(CManager::C1);

//echo "<pre>";
//var_dump($s);
//die();
$Capp = $s->getCapp();
echo $Capp->mtheodOne();
