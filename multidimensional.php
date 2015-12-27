<?php

$myArray = Array(
    [0] => Array(
        ['id'] => 1,
        ['pid'] => 120,
        ['uuid'] => 1
    ),
    [1] => Array(
        ['id'] => 2,
        ['pid'] => 132,
        ['uuid'] => 1
    ),
    [2] => Array(
        ['id'] => 5,
        ['pid'] => 121,
        ['uuid'] => 1
    )
);

function inMyArray($array, $search) {
    foreach ($array as $value) {
        if (is_array($value)) {
            if (inMyArray($value, $search)) {
                return(TRUE);
            }
        } elseif ($value == $search) {
            return(TRUE);
        }
    }
    return(FALSE);
}

$id = 121;

if (inMyArray($myArray, $id)) {

    echo "Value 121 found in array";
} else {

    echo "Other value";
}

// Multidimensional array
$superheroes = array(
    "spider-man" => array(
        "name" => "Peter Parker",
        "email" => "peterparker@mail.com",
    ),
    "super-man" => array(
        "name" => "Clark Kent",
        "email" => "clarkkent@mail.com",
    ),
    "iron-man" => array(
        "name" => "Harry Potter",
        "email" => "harrypotter@mail.com",
    )
);

// Printing all the keys and values one by one
$keys = array_keys($superheroes);
for ($i = 0; $i < count($superheroes); $i++) {
    echo $keys[$i] . "{<br>";
    foreach ($superheroes[$keys[$i]] as $key => $value) {
        echo $key . " : " . $value . "<br>";
    }
    echo "}<br>";
}
