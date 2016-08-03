<?php

function decmo($temp) {
    if ($temp > 40) {
        echo 'ok';
        return;
    } else if ($temp > 25 && $temp < 40) {
        echo 'ok2';
        return;
    }
    echo 'ok3';
    return;
}

//$temp = 49;
echo decmo(49);
