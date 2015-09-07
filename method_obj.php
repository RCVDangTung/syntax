<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Hello {

    private $name;
    private $age;
    private $chain;

//    Khoi tai gia tri mac dinh bang false
    public function __construct($chain = FALSE) {
        $this->chain = $chain;
    }

    public function setName($name) {
        if ($this->chain == FALSE) {
            return $this->name = $name;
        } else {
            $this->name = $name;
            return $this;
        }
    }

    public function setAge($age) {
        if ($this->chain == FALSE) {
            return $this->name = $age;
        } else {
            $this->name = $age;
            return $this;
        }
    }

    public function introduce() {
        if (empty($this->name)) {
            print("I need a name!");
        } else if (empty($this->age)) {
            print("I dont have an age!");
        } else {
            printf("Hello there, my name is <b>%s</b> and I am <b>%s</b> years old.", $this->name, $this->age);
        }
    }

}

//$hello = new Hello();
$name = "Tung";
$age = 17;
//echo $hello->setName($name);
//echo $hello->setAge($age);
//$hello->introduce();
$hello = new Hello(true); // chaining method 
$hello->setName($name)->setAge($age)->introduce();
?>
