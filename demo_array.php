<?php

// Khoi tao mang 2 chieu
//$array[] = new stdClass;
$array = array();
//
//
for ($i = 0; $i <= 2; $i++) {
//    $array[$i] = array();
    for ($j = 0; $j <= 2; $j++) {
        $array[$i][$j] = $i * $j;
    }
}
echo "<pre>";
var_dump($array);
die();
// & refrence
//http://www.qhonline.info/forum/showthread.php/1480-references-in-php
//http://www.if-not-true-then-false.com/2009/php-tip-convert-stdclass-object-to-multidimensional-array-and-convert-multidimensional-array-to-stdclass-object/
//$myVar = "Hello Tung dep zai !";
//$anotherVar = &$myVar;
//
//$anotherVar = "See you later";
//unset($anotherVar);
//echo $myVar; // Displays "Hi there"


//echo $anotherVar; // Displays "See you later"  

//Removing a reference

echo "================";


//$bands = array( "The Who", "The Beatles", "The Rolling Stones" );
//
//foreach ( $bands as &$band ) {
//  $band = strtoupper( $band );
//}
//
//echo "<pre>";
//print_r( $bands );
//echo "</pre>";  

echo "=============";

$init = new stdClass;

// Add some test data
$init->foo = "Test data";
$init->bar = new stdClass;
$init->bar->baaz = "Testing";
$init->bar->fooz = new stdClass;
$init->bar->fooz->baz = "Testing again";
$init->foox = "Just test";
echo "<pre>";
//print_r((array) $init);
print_r($init);

foreach ($init as $key => $value) {
    $columns[] = $key;
}
$columns = array_keys($columns);
var_dump($columns);

echo "================";





$attributes = array(
    'data-href'   => 'http://example.com',
    'data-width'  => '300',
    'data-height' => '250',
    'data-type'   => 'cover',
);

$dataAttributes = array_map(function($value, $key) {
    return $key.'="'.$value.'"';
}, array_values($attributes), array_keys($attributes));

$dataAttributes = implode(' ', $dataAttributes);
//var_dump($dataAttributes);
?>

<div class="image-box" <?= $dataAttributes; ?> >
    <img src="" alt="">
</div>

<?php

//echo "<pre>";
//var_dump($array[4][3]);

function table_row($col = 10, $row = 10) {
    $array = array();
    $html = "";
    $html .= "<table border = '1'><thead><tbody>";
    for ($i = 0; $i <= $col; $i++) {
        $html .= "<tr>";
        for ($j = 0; $j <= 10; $j++) {
            if ($i == 2 && $j == 4) {
                $html .= "<td>";
                $html .= "0";
                $html .= "</td>";
            } else {
                $html .= "<td>";
                $html .= "1";
                $html .= "</td>";
            }
        }
        $html .= "</tr>";
    }
    $html .= "</thead></tbody></table>";
    return $html;
}

echo table_row();
?>
