<?php

$array = array(
    array(
        'Cat_ID' => 81751,
        'Cat_Name' => 'Äá»i sá»‘ng',
        'Cat_ParentID' => 104,
        'Description' => ""
    ),
    
    array(
        'Cat_ID' => 1424,
        'Cat_Name' => 'Giáº£i trÃ­',
        'Cat_ParentID' => 104,
        'Description' => ""
    ),
    
    array(
        'Cat_ID' => 1451,
        'Cat_Name' => 'GiÃ¡o dá»¥c',
        'Cat_ParentID' => 104,
        'Description' => ""
    ),
    
    array(
        'Cat_ID' => 80923,
        'Cat_Name' => 'Kh - cn',
        'Cat_ParentID' => 104,
        'Description' => ""
    ),
    
    array(
        'Cat_ID' => 1426,
        'Cat_Name' => 'Kh - cn',
        'Cat_ParentID' => 104,
        'Description' => ""
    ),
    array(
        'Cat_ID' => 1433,
        'Cat_Name' => 'Kh - cn',
        'Cat_ParentID' => 104,
        'Description' => ""
    ),
    
    array(
        'Cat_ID' => 1442,
        'Cat_Name' => 'NhÃ  Ä‘áº¥t',
        'Cat_ParentID' => 104,
        'Description' => ""
    ),
    
    array(
        'Cat_ID' => 80891,
        'Cat_Name' => 'Ã” tÃ´ - xe mÃ¡y',
        'Cat_ParentID' => 104,
        'Description' => ""
    ),
    
    array(
        'Cat_ID' => -999,
        'Cat_Name' => 'Topnews',
        'Cat_ParentID' => 0,
        'Description' => ""
    ),
);

function orderBy($data, $field){
    $code = "return strnatcmp(\$a['$field'], \$b['$field']);";
    usort($data, create_function('$a,$b', $code));
    return $data;
}

$sorted_data = orderBy($array, 'Cat_ParentID');

echo "<pre>";
var_dump($sorted_data);


