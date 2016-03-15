<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

interface SortStrategy {

    public function sort();
}

class QuickSort {

    private $data;

    public function __construct(Array $data) {
        $this->data = $data;
    }

    public function sort() {
        
    }

}

class MergeSort {

    private $data;

    public function __construct(Array $data) {
        $this->data = $data;
    }

    public function sort() {
        
    }

}

function sort(Array &$data) {
    if (count($data) > 10) {
        $tempData = new QuickSort($data);
    } else {
        $tempData = new MergeSort($data);
    }
    
    $tempData->sort();
}

$array = array(3, 5, 2, 5, 65, 32, 12, 53, 21, 71, 12, 43, 64);

sort($data);
