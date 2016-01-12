<?php

//https://www.youtube.com/watch?v=VuBVAgwMfLE&index=3&list=PLGJDCzBP5j3xGaW0AGlaVHK2TMEr2XkP9
interface Shape {

    public function draw();
}


class Position{
    
}

class Rectangle implements Shape {

    public $position;
    
    public function __construct($pos) {
        $this->position = $pos;
    }

    public function draw() {
        echo 'demo factory';
    }

}

class Monkey{
    
    
    public function monney(){
        echo 'demo';
    }
}

class ShapeFactory {

    public function create($type) {
        if($type == 'Rectangle') {
            return new Rectangle( new Position());
        }elseif ($type == 'Monkey') {
            return new Monkey();
        }
    }

}

$factory = new ShapeFactory();
//$rect =$factory->create('Rectangle');
$rect =$factory->create('Monkey');

//$rect->draw();


$rect->monney();
echo "<pre>";
var_dump($rect);
//$rect->draw();  
