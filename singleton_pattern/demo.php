<?php

/*
 * Khi khởi tạo đối tượng lần đầu tiên dk lưu vào một thuộc tính ở trạng thái rỗng chưa có giá trị
 * Những lần khởi tạo sau chỉ cần trả về thuộc tính được lưu thông tin đối tượng
 * Thuộc tính này chỉ được lưu ở phạm vi private -> ngoài lớp k truy cập dk vào thuộc tính này
 */

class Demo {

    private static $info;

    public function __construct() {
        if (!self::$info) {
            self::$info = $this;
            echo 'Khai bao moi<br />';
            return self::$info;
        } else {
            echo 'Khai bao cu<br />';
            return self::$info;
        }
    }

}

$new = new Demo();//Khai bao moi
$new = new Demo();//Khai bao cu
$new = new Demo();//Khai bao cu
