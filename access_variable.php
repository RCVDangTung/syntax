<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$name_1  = "this is name 1";
$name_2  = "this is name 1";
$name_3  = "this is name 1";

for($i = 1;$i < 4;$i++){
    $varName = 'name_'.$i;
    echo $$varName;
    echo '<br />';
}
?>
