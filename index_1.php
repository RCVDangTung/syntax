<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//
//$row = new stdClass();
//$row->hoten = 'Nguyễn Đăng Tung';
//$row->email = 'dangtungbk92@gmail.com';
//$row->sdt = '0962785428';
//$data[] = array(
//    $row->hoten,
//    $row->email,
//    $row->sdt
//);
//
//echo "<pre>";
//var_dump($data);
// Sắp xếp nổi bọt:
//$mang = array(1, 5, 9, 2, 4, 9); // mảng theo đề bài
//  
//$sophantu = 5; // hoặc dùng hàm $sophantu = count($mang);
//  
//// Sắp xếp mảng
//for ($i = 0; $i <= ($sophantu - 1); $i++)
//{   
//    echo $i . "<br>";
//////    echo $mang[$i] . "<br>";
//    for ($j = $i + 1; $j <= $sophantu; $j++) // lặp các phần tử phía sau
//    {   
//        echo $j . "'<br>";
//        
//        echo $mang[$i] . " '' <br>" . $mang[$j] . " '' <br>";
//        if ($mang[$i] > $mang[$j]) // nếu phần tử $i bé hơn phần tử phía sau
//        {
//            // hoán vị
//            $tmp = $mang[$j];
//            $mang[$j] = $mang[$i];
//            $mang[$i] = $tmp;
//        }
//    }
//}
//  
//// Hiển thị các phần tử của mảng đã sắp xếp
//for ($i = 0; $i < $sophantu; $i++){
//    echo $mang[$i] . ' ';
//}
//for ($i = 0; $i < 10; $i++){
//    echo $i . ' - ';
//}



for ($k = 10; $k > 0; $k--) {
    echo $k . "<br>";
}

echo '----------' . "<br>";


for ($m = 10; $m >= 0; $m--) {
    echo $m . "<br>";
}


echo '----------' . "<br>";

for ($i = 1; $i < 10; $i++) {
    for ($j = 9; $j >= $i; $j--) {
        echo $j;
    }
    echo '<br/>';
    ;
}

for ($i = 9, $count = 0; $i <= $count; $i--) {
    echo $i . ' - ';
}

echo "<pre>";

//die();
//
//for ($i = 9, $count = 10; $i <= $count; $i--){
//    echo $i . ' - ';
//}
//$array = array(1,5,9,10,4,6,8,2,4,6);
//
//$n = count($array);
//
//for($i = 0;$i < $n ; $i++){
////    echo $array[$i] . "<br>";
//    
//    for($j = ($n - 1);$j > $i;$j--){
//        echo $array[$j] . "<br>";
//    }
//}
//$i = 0;
//while ($i < 10){
//    echo $i . '<br>';
//    $i++;
//}

$ww = 1;
while ($ww <= 5) {
    echo "The number is: $ww <br>";
    $ww++;
}


$x = 1;

do {
    echo $x . "<br>";
    $x++;
} while ($x <= 5);


$xu = 0;

do {
    echo $xu . "<br>";
    $xu--;
} while ($xu < 10);

// linh canh
// TÌm phần tử nhỏ nhất trong mảng


function search_min($array) {
    $total = count($array);

    // Gọi min là lính cầm canh
    // lúc đầu chọn vị trí số 0 ngồi canh
    $min = 0;

    for ($s = 0; $s < $total; $s++) {
        // Nếu phần tử cầm canh lớn hơn phần tử thứ $i thì
        // lấy vị trí $i ngồi canh
        if($array[$min] > $array[$s]) {
            $min = $s;
        }
    }
    // Trả về vị trí nhỏ nhất

    return $min;
}
