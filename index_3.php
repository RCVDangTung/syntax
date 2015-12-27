<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


//$phone = '0942848397';
//
//$data_phone = explode('',$phone);
//
//var_dump($data_phone);


//$str='0942848397';
////echo $str.'   ';
//$len=strlen($str) -1;
//
////var_dump($len);
////die();
////$new='';
//$new = array();
//for($i=$len;$i>=0;$i--){
////$new.=$str[$i];
//    $new[] = $str[$i];
//}
//echo "<pre>";
//var_dump($new);
//
//
////for($m =){
////    
////}
//$leng = count($new);
////var_dump($leng);
////die();
//$input = "";
//for ($a = 0; $a <= ($leng - 1); $a++) {
//    $input .= "<input type='text' value='".$new[$a]."'>";
//}
//
//echo $input;


$str='0942848397';
$str_new = trim($str);
$len = strlen($str_new);
$arr = array();
for($i=0;$i < $len;$i++){
    $arr[] = $str[$i];
}

$input = "";
for($k = 0; $k < count($arr);$k++){
    $input .= "<input type='text' value='".$arr[$k]."' style='width: 20px;text-align: center;'>";
}
echo $input;


echo "<br>";
$split = str_split(trim('01234567'));

echo count($split);

$input_t = "";
for($t = 0;$t < count($split);$t++){
    $input_t .= "<input type='text' value='".$arr[$t]."' style='width: 20px;text-align: center;'>";
}
echo $input_t;
echo "<pre>";
var_dump($split);

/*
 * str_replace   
 * String change
 * String replace
 * String input
 *  */

function convert_chars_to_entities( $str ) { 
    
    $str = str_replace( 'À', '&#192;', $str ); 
    $str = str_replace( 'Á', '&#193;', $str ); 
    $str = str_replace( 'Â', '&#194;', $str ); 
    $str = str_replace( 'Ã', '&#195;', $str ); 
    $str = str_replace( 'Ä', '&#196;', $str ); 
    
    $str = str_replace( 'Ø', '&#216;', $str ); 
    $str = str_replace( 'Ù', '&#217;', $str ); 
    $str = str_replace( 'Ú', '&#218;', $str ); 
    $str = str_replace( 'Û', '&#219;', $str ); 
    $str = str_replace( 'Ü', '&#220;', $str ); 
    $str = str_replace( 'Ý', '&#221;', $str ); 
    $str = str_replace( 'Þ', '&#222;', $str ); 
    $str = str_replace( 'ß', '&#223;', $str ); 
    $str = str_replace( 'à', '&#224;', $str ); 
    $str = str_replace( 'á', '&#225;', $str ); 
    $str = str_replace( 'â', '&#226;', $str ); 
    $str = str_replace( 'ã', '&#227;', $str );
    return $str; 
}

$str = 'adadada131313&#216;';

echo convert_chars_to_entities($str);