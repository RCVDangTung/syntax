<?php

$i = 0;
$j = 0;

while ($i < 10) {
//    echo $i . "<br /> <hr />";
    while ($j < 10) {
//        echo $j . "<br />";
        if ($j == 5) {
            break 2;
        } // breaks out of two while loops
        $j++;
    }
    $i++;
}
echo "The first number is " . $i . "<br />";
echo "The second number is " . $j . "<br />";


echo "<br /> <hr />";

$j = 0;
for($i = 0; $i < 10;$i++){
    echo $i . "<br /><br /><br />";
    while ($j < 5){
        echo $j . "<br /><br />";
        $j++;
    }
    
    
}

$z = array('playerName1', 'playerName2', 'playerName3', 'playerName4', 'playerName5',
    'playerName6', 'playerName7', 'playerName8', 'playerName9', 'playerName10');

echo '<pre>';

var_dump($z);


$zCounter = 1;
foreach ($z as $allNames) {
    echo $zCounter . '<br /><br />';
    while ($allNames != "" && $zCounter < 11) {
        echo $allNames . "<br>";
        $zCounter++;
    }
    $zCounter = 0;
    echo $zCounter . '<br /><br />';
     
}  


