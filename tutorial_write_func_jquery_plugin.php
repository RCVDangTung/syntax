<!DOCTYPE html>
<html>
    <head>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    </head>
    <script>

        $.fn.fpmenu = function (params) {
            //code here 
        };
        (function ($) {
            $.fn.fpmenu = function (params) {
                //code here 
            };
        })(jQuery);
        jQuery.fn.fpmenu = function (params) {
            //code here 
        };

        // Khai báo liệt kê params đơn giản
        // thêm vào 2 tham số lần lượt là animEffect và animSpeed
        (function($) {
        $.fn.fpmenu = function(animEffect, animSpeed) { //code here };
        })(jQuery);
                //hiệu ứng swing và thời gian thực hiện là 500 milisecond
                $('#myMenu').fpmenu('swing', 500);
                // END

                        // thay vì liệt kê nhiều tham số ta chỉ cần đặt vào một đối tượng JSON 

        (function($) {
        $.fn.fpmenu = function(paramObject) { 
            //code here 
        };
        })(jQuery);
        //hiệu ứng swing và thời gian thực hiện là 500 milisecond
        $('#myMenu').fpmenu({animEffect :'swing', animSpeed :500});
        
        (function($) {
            $.fn.fpmenu = function(param) { 
                param = $.extend({animEffect :'swing', animSpeed :500},param);

                //trả về chính đối tượng hiện thời
                return this;
            };
        })(jQuery);
        
        
    </script>
    <body>

    </body>
</html>
<!--https://coderkhung.wordpress.com/2014/06/05/huong-dan-viet-jquery-plugin/-->
<!--http://tek.eten.vn/viet-plugin-cho-jquery-cua-rieng-minh-->