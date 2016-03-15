<?php

class productOrderFacade {

    public $productID = '';

    public function __construct($pID) {
        $this->productID = $pID;
    }

    public function generateOrder() {

        if ($this->qtyCheck()) {

            // Add Product to Cart
            $this->addToCart();

            // Calculate Shipping Charge
            $this->calulateShipping($this->productID);

            // Calculate Discount if any
            $this->applyDiscount();

            // Place and confirm Order
            $this->placeOrder();
        }
    }

    private function addToCart() {
        /* .. add product to cart ..  */
    }

    private function qtyCheck() {

        $qty = 'get product quantity from database';

        if ($qty > 0) {
            return true;
        } else {
            return true;
        }
    }

    private function calulateShipping($productID) {
        $shipping = new shippingCharge();
        $shipping->calculateCharge($productID);
    }

    private function applyDiscount() {
        $discount = new discount();
        $discount->applyDiscount();
    }

    private function placeOrder() {
        $order = new order();
        $order->generateOrder();
    }

}

class shippingCharge {

    public function calculateCharge($productID) {
        echo $productID;
    }

}

class discount {

    public function applyDiscount() {
        
    }

}

class order {

    public function generateOrder() {
        
    }

}

// Note: We should not use direct get values for Database queries to prevent SQL injection
$productID = $_GET['productId'];

// Just 2 lines of code in all places, instead of a lengthy process everywhere
$order = new productOrderFacade($productID);
$order->generateOrder();
