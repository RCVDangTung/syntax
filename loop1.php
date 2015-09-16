<?php

//Indexed two-dimensional array
$cars = array(
                array("Honda Accord", "V6", 30000),
                array("Toyota Camry", "LE", 24000),
                array("Nissan Altima", "V1"),
            );

//printing array information            
//print_r($cars);
echo "<pre>";
var_dump($cars);
echo "</pre>";
//looping through two-dimensional indexed array
 
for($i=0;$i<count($cars);$i++){
    for($j=0;$j<count($cars[$i]);$j++){
        echo $cars[$i][$j] . " ";
    }
    echo "<br>";
}
echo "<br><br>";
