<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
/*
 * Tinh da hinh 
 */

class Dong_Vat{
    
    public function An(){
        echo "Dong vat dang an";
    }
}

class ConHeo extends Dong_Vat{
    public function An() {
        parent::An();
        echo "Con Heo An Cam";
    }
}

$conheo = new ConHeo();
$conheo->An();

?>
