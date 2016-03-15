<?php

interface Observer_1 {

    public function add(Company $subject);

    public function notify($price);
}

class StockSim_1 implements Observer_1 {

    private $compay;

    public function __construct() {
        $this->compay = array();
    }

    public function add(Company $subject) {
        array_push($this->compay, $subject);
    }

    public function UpdatePrice() {
        // Caculate new price 
        $this->notify(rand(29.99, 199.42));
    }

    public function notify($price) {
        foreach ($this->compay as $comp) {
            $comp->update($price);
        }
    }

}

interface Company {

    public function update($price);
}

class Google implements Company {

    private $price;

    public function __construct($price) {
        $this->price = $price;

        echo "<p>Creating Google at \${$price}</p>";
    }

    public function update($price) {
        $this->price = $price;
        echo "<p>Google  setting for \${$this->price}</p>";
    }

}

class Walmark implements Company {

    private $price;

    public function __construct($price) {
        $this->price = $price;

        echo "<p>Creating walmark at \${$price}</p>";
    }

    public function update($price) {
        $this->price = $price;
        echo "<p>walmark selling for \${$this->price}</p>";
    }

}

$StockSim = new StockSim_1();

$comp1 = new Google(19.99);
$comp2 = new Walmark(15.99);

$StockSim->add($comp1);
$StockSim->add($comp2);
$StockSim->UpdatePrice();