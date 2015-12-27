<?php

$cars = array(
                array("Name" => "Honda Accord", "Model" => "V6", "Cost" => 30000),
                array("Name" => "Toyota Camry", "Model" => "LE", "Cost" => 24000),
                array("Name" => "Nissan Altima", "Model" => "V1"),
            );

            echo "<pre>";
            var_dump($cars);
            
for($i=0;$i<count($cars);$i++){
    $c=0;
    foreach($cars[$i] as $key=>$value){
        $c++;
        echo $key."=".$value;
        echo $c;
        echo "-------------";
        echo count($cars[$i]);
        if($c < count($cars[$i])) echo ",";
    }
    echo "<br>";
}
