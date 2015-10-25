<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


$array = array('link1', 'link2', 'link3', 'link4', 'link5');
$leng = count($array);
for ($i = 1; $i <= $leng; $i++) {
    for ($j = 1; $j <= $i; $j++) {
        if ($j == 1)
            $link = 'link1';
        else
            $link .= '/link' . $j;
    }
    echo '<a href="' . $link . '">link1</a>';
}
?>
