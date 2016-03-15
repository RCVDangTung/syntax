<?php

interface Command {

    public function excute();
}

class GetCompanyCommand implements Command {

    private $stockSim;

    public function excute() {
        $this->stockSim->getCompany();
    }

}

class UpdatePriceCommand implements Command {

    private $stockSim;

    public function excute() {
        $this->stockSim->updatePrice();
    }

}

class StockSim {

    public function getCompany() {
        
    }

    public function updatePrice() {
        
    }

}

// client

$in = getAction();

function getAction() {
    
}



