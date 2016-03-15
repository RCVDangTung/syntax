<?php

/*
 * Lớp abstract là lớp dùng để định nghĩa những thuộc tính hoặc phương thức của lớp khác
 * (Khai báo thuộc tính phương thức cho lớp khác sử dụng) 
 * không cho phép khởi tạo đối tượng (instance)
 * 
 *  */

abstract class Demo_ab {
    abstract public function name();
}



interface star {

    public function bright();

    // trong interfac access modifiers chỉ sử dụn public
//    private function functionName();
}







