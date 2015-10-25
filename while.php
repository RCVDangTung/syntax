<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$i = 1;

while ($i < 10) {

    $j = $i;

    while ($j < 10) {

        echo $j;
        $j++;
    }
    
    echo '<br/>';
//    echo $i;
    $i++;
}

echo "<br />";
echo '====================';
echo "<br />";

for($a = 1; $a < 10; $a++){
    for($b = 1;$b <= $a;$b++){
        echo $b;
    }
    echo "<br />";
}

echo "<br />";
echo '====================';
echo "<br />";

for($c = 1; $c < 10; $c++){
    for($d = 10;$d > $c;$d--){
        echo $d;
    }
    echo "<br />";
}

?>
