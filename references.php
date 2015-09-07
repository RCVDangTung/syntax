<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function references($a) {
    echo "Truyen kieu tham tri" . $a;
}

references(5);
echo "========================" . "<br />";

function add_some_extra(&$string) {
    $string .= 'and something extra.';
}

$str = 'This is a string, ';
add_some_extra($str);
echo $str;    // xuất ra là 'This is a string, and something extra.'
echo "========================" . "<br />";

echo "============ Truyen tham chieu =========" . "<br />";

function count_1(&$var) {
    $var++;
}

$b = 5;
count_1($b);
echo $b;
// kết quả sẽ là 6
echo "<br />";
echo "============ Truyen tham tri =========" . "<br />";

function count_2($var) {
    $var++;
}

$c = 5;
count_2($c);
echo $c;
// kết quả sẽ là 5
?>

<?php

class cha {

    public $ten_cha = "toi la cha";

    public function test() {
        echo $this->ten_cha . "<br />";
    }

}

class con extends cha {

    public $ten_con = 'toi la con';

    public function test() {
        parent::test(); //phuong thuc test la cua class cha
        echo $this->ten_con . "<br />";
    }

}

$a = new con;
$a->test();
?>
