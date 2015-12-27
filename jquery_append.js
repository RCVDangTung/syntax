$(document).ready(function () {
    var count = 0;

    $("#add_btn").click(function () {
        count += 1;
        $('#container').append(
                '<tr class="records">'
                + '<td ><div id="' + count + '">' + count + '</div></td>'
                + '<td><input id="nim_' + count + '" name="nim_' + count + '" type="text"></td>'
                + '<td><input id="nama_depan_' + count + '" name="nama_depan_' + count + '" type="text"></td>'
                + '<td><input id="nama_belakang_' + count + '" name="nama_belakang_' + count + '" type="text"></td>'
                + '<td><a class="remove_item" href="#" >Delete</a>'
                + '<input id="rows_' + count + '" name="rows[]" value="' + count + '" type="hidden"></td></tr>'
                );
    });

    $(".remove_item").live('click', function (ev) {
        if (ev.type == 'click') {
            $(this).parents(".records").fadeOut();
            $(this).parents(".records").remove();
        }
    });
});



$(document).ready(function () {
    $(document).on("click", ".next", function (e) {
        e.preventDefault();
        var obj = $(this);
        var parent = obj.parents('form');
        var check = check_contact(parent);
        if(!check){
            return false;
        }
        var confirm = false;

        var current_section = obj.parents('section');
        var current_role = current_section.attr('role');

        var nex_role = parseInt(current_role) + 1;
        $('section').addClass('hidden');
        $("section[role=" + nex_role + "]").removeClass('hidden');
        $('#step-nav step').addClass('hidden');
        $("#step-nav step[role=" + nex_role + "]").removeClass('hidden');
        $('#step-nav span').removeClass('active-span');

        $("#option_payment").removeClass('hidden');

    });
    $(document).on('keyup', '.e_form_submit input[name=phone]', function (e) {
        if (e.which != 8 && e.which != 46 && e.which != 37 && e.which != 39 && e.which != 38 && e.which != 40) {
            //Keyboard ấn xuống ko phải là 1 trong các phím: backspace, del, tiến, lùi, lên, xuống thì thực hiện function check_phone_number()
            check_phone_number($(this));
        }
    });
    $(document).on('click','.myClass',function(){
        var value_payment = $(":input", this).prop('checked', true).val();
        // alert(value_payment);
        if(value_payment == 5){
            $('#payment_form').removeClass('sub_2c2p');
            $('#payment_form').addClass('sub_couterservice');
            $('#payment_form').attr('action','./mol_topmito/counter_service.php');

        }else{
            $('#payment_form').removeClass('sub_couterservice');
            $('#payment_form').attr('action','./mol_topmito/2c2p.php');
            $('#payment_form').addClass('sub_2c2p');
        }

        // var value = $("input[name=payment_method]:checked", this).prop('checked', true).val();
        // alert(value);
        // return;
        // var payment_type = $('input[name=payment_method]:checked').val();
        // $('#payment_form').addClass('sub_2c2p');
        // if(parseInt(payment_type)===4){
        //     // $('#payment_form').attr('action','nap-the.php');

        //     $('#payment_form').addClass('sub_2c2p');
        // }
        // else{
        //     $('#payment_form').addClass('sub_2c2p');
        //     // $('#payment_form').attr('action','./mol_topmito/OnePay.php');
        // }

    });

    // $('.myClass').click(function () {
    //     var value = $(":input", this).prop('checked', true).val();
    // });

$(document).on("submit", ".sub_2c2p", function (e) {
    e.preventDefault();
    var sent = sent_data_thb();
});

$(document).on("submit", ".sub_couterservice", function (e) {
    e.preventDefault();
    var sent = sent_data_couterservice();
});

});


function sent_data_couterservice(){
    var btn_submit_ct = $("#payment_form").find('#i_btn_submit');
    btn_submit_ct.attr('disabled', 'disabled');
    var url_sent_2 = $('.sub_couterservice').attr('action');
    // var data_return = true;
    $('.sub_couterservice').ajaxSubmit({
        type : 'POST',
        url : url_sent_2,
        dataType : 'json',
        async: false,
        success : function (data_return_cs){
            btn_submit_2c2p.removeAttr('disabled');

            alert(data_return_cs);
            
            // alert(data_return_cs.status);
            // alert(data_return_cs.phone);
            
            // if(result_ct.status){
            //     window.location.href = "http://speak.topicanative.asia/counter-service.php?phone=" + result_ct.phone;
            // }else {
            //     // alert(result.msg);
            //     data_return = false;
            // }
        },error: function () {
            alert('Đăng ký thất bại, vui lòng thử lại');
            btn_submit_ct.removeAttr('disabled');
            // data_return = false;
        } 
    });
    // return data_return;
}


function sent_data_thb(){
    var btn_submit_2c2p = $("#payment_form").find('#i_btn_submit');
    btn_submit_2c2p.attr('disabled', 'disabled');
    var url_sent = $('.sub_2c2p').attr('action');
    var data_return = true;
    $('.sub_2c2p').ajaxSubmit({
        type : 'POST',
        url : url_sent,
        dataType : 'json',
        async: false,
        success : function (result){
            btn_submit_2c2p.removeAttr('disabled');
            if (result.status) {
                data_return = true;
                $('#standard_checkout').attr('action' , result.data.link_redirect_2c2p);
                var input_version = "<input type='hidden' id='version' name='version' value='" + result.data.version + "'>";
                $('#standard_checkout').append(input_version);
                var input_merchant_id = "<input type='hidden' id='merchant_id' name='merchant_id' value='" + result.data.merchant_id + "'>";
                $('#standard_checkout').append(input_merchant_id);
                var input_payment_description = "<input type='hidden' id='payment_description' name='payment_description' value='" + result.data.payment_description + "'>";
                $('#standard_checkout').append(input_payment_description);
                var input_order_id = "<input type='hidden' id='order_id' name='order_id' value='" + result.data.order_id + "'>";
                $('#standard_checkout').append(input_order_id);
                var input_invoice_no = "<input type='hidden' id='invoice_no' name='invoice_no' value='" + result.data.invoice_no + "'>";
                $('#standard_checkout').append(input_invoice_no);
                var input_currency = "<input type='hidden' id='currency' name='currency' value='" + result.data.currency + "'>";
                $('#standard_checkout').append(input_currency);
                var input_amount = "<input type='hidden' id='amount' name='amount' value='" + result.data.amount + "'>";
                $('#standard_checkout').append(input_amount);
                var input_promotion = "<input type='hidden' id='promotion' name='promotion' value=''>";
                $('#standard_checkout').append(input_promotion);
                var input_customer_email = "<input type='hidden' id='customer_email' name='customer_email' value=''>";
                $('#standard_checkout').append(input_customer_email);
                var input_pay_category_id = "<input type='hidden' id='pay_category_id' name='pay_category_id' value=''>";
                $('#standard_checkout').append(input_pay_category_id);
                var payment_option = "<input type='text' id='payment_option' name='payment_option' value='C'/>";
                $('#standard_checkout').append(payment_option);
                var default_lang = "<input type='text' id='default_lang' name='default_lang' value='th'/>";
                $('#standard_checkout').append(default_lang);
                var input_result_url_1 = "<input type='hidden' id='result_url_1' name='result_url_1' value=''>";
                $('#standard_checkout').append(input_result_url_1);
                var input_result_url_2 = "<input type='hidden' id='result_url_2' name='result_url_2' value=''>";
                $('#standard_checkout').append(input_result_url_2);
                var input_checksum_topica = "<input type='hidden' id='CHECKSUM' name='CHECKSUM' value='" + result.data.checksum_topica + "'>";
                $('#standard_checkout').append(input_checksum_topica);
                var input_hash_value = "<input type='hidden' id='hash_value' name='hash_value' value='" + result.data.hash_value + "'>";
                $('#standard_checkout').append(input_hash_value);
                $('#standard_checkout').submit();
            } else {
                // alert(result.msg);
                data_return = false;
            }

        },error: function () {
            alert('Đăng ký thất bại, vui lòng thử lại');
            btn_submit_2c2p.removeAttr('disabled');
            data_return = false;
        }
    });
return data_return;
}

/* 
 * function: Kiểm tra cookie khi submit
 * @param obj: Đối tượng chứa dữ liệu <=> form
 * @param time: thời gian sống của cookie (phút) 
 */
 function check_cookie(obj, time) {
    var url = "./mol_topmito/check_cookie.php";
    var email = obj.find('input[name=email]').val();//val(): gia tri the trong form
    var data_result = '';
    $.ajax({
        type: "POST",
        url: url,
        data: {
            'data': email,
            'time': time
        },
        dataType: 'json',
        async: false, //phai co du lieu thi tiep tuc
        success: function (result) {
            if (result.status) {
                data_result = true;
            } else {
                alert(result.msg);
                data_result = false;
            }
        },
        error: function () {
            alert('Đăng ký thất bại. Mời thử lại sau!');
            data_result = false;
        }
    });
    return data_result;
}

/*
 *  function: Tự động thêm dấu cách (backspace) khi nhập số điện thoại
 *  08x xxx xxxx
 *  09x xxx xxxx
 *  01xx xxx xxx
 *  @param obj: đối tượng (input[name=phone])
 */
 function check_phone_number(obj) {
    var val = obj.val();
    var lenght = val.length;
    if (val.match(/^08/i)) {
        if (lenght == 3) {
            val = val + ' ';
            obj.val(val);
        } else if (lenght == 7) {
            val = val + ' ';
            obj.val(val);
        } else if (lenght > 12) {
            alert('กรุณากรอกหมายเลขโทรศัพท์ที่ถูกต้อง');
            obj.css('border', '1px solid red');
            //obj.val('');
        }
    } else if (val.match(/^01/i)) {
        if (lenght == 4) {
            val = val + ' ';
            obj.val(val);
        } else if (lenght == 8) {
            val = val + ' ';
            obj.val(val);
        } else if (lenght > 13) {
            alert('กรุณากรอกหมายเลขโทรศัพท์ที่ถูกต้อง');
            obj.css('border', '1px solid red');
            //obj.val('');
        }
    }
}

/*
 *  function: Check dữ liệu contact; return nếu false; gửi dữ liệu lên sever nếu true
 *  @param obj: đối tượng check (các input, select trong form)
 */
 function check_contact(obj) {
    var button = obj.find('.e_btn_submit');
    var email = obj.find('input[name="email"]').val();
    var name = obj.find('input[name="name"]').val();
    var nick_name = obj.find('input[name="nick_name"]').val();
    var aCong = email.indexOf("@");
    var dauCham = email.lastIndexOf(".");
    var phone_number = obj.find('input[name="phone"]').val();
    phone_number = phone_number.replace(/ /g, '');
    var phone_lenght = phone_number.length;
    var url = obj.attr("data-action");
    var age = obj.find("select[name=age]").val();
    var date = obj.find('input[name="date"]').val();
    var time = obj.find('input[name="time"]').val();
    var level = obj.find("select[name=level]").val();
    var purpose = obj.find("select[name=purpose]").val();
    var data_return = true;
    if ((name.trim() == "") || (name == "Họ tên *")) {
        alert("กรุณากรอกชื่อและนามสกุลของคุณ");
        obj.find('input[name="name"]').focus();
        return (false);
    }
    if ((nick_name.trim() == "") || (nick_name == "nick name*")) {
        alert("กรุุณาใส่อีเมล์ของคุณ");
        obj.find('input[name="nick_name"]').focus();
        return (false);
    }

    if ((email == "") || (email == "email_address@gmail.com")) {
        alert("กรุุณาใส่อีเมล์ของคุณ");
        obj.find('input[name="email"]').focus();
        return (false);
    }

    if ((aCong < 1) || (dauCham < aCong + 2) || (dauCham + 2 > email.length)) {
        alert("อีเมล์ :email@example.com");
        obj.find('input[name="email"]').focus();
        return false;
    }

    if ((phone_number == "") || (phone_number == "Điện thoại *")) {
        alert("กรุุณาใส่เบอร์โทรศัพท์มือถือของคุณ");
        obj.find('input[name="phone"]').focus();
        return (false);
    }

    // if (phone_number.match(/^08/i)) {
    //     if (phone_lenght != 10) {
    //         alert("โทรตรวจสอบเบอร์โทรศัพท์มือถืของคุณอีกครั้ง");
    //         obj.find('input[name="phone"]').focus();
    //         return (false);
    //     }
    // } else if (phone_number.match(/^09/i)) {
    //     if (phone_lenght != 10) {
    //         alert("โทรตรวจสอบเบอร์โทรศัพท์มือถืของคุณอีกครั้ง");
    //         obj.find('input[name="phone"]').focus();
    //         return (false);
    //     }
    // }else if(phone_number.match(/^06/i)){
    //     if (phone_lenght != 10) {
    //         alert("โทรตรวจสอบเบอร์โทรศัพท์มือถืของคุณอีกครั้ง");
    //         obj.find('input[name="phone"]').focus();
    //         return (false);
    //     }
    // }
    // else {
    //     alert("โทรตรวจสอบเบอร์โทรศัพท์มือถืของคุณอีกครั้ง");
    //     obj.find('input[name="phone"]').focus();
    //     return false;
    // }

    if (phone_number.match(/^0/i)) {
        if (phone_lenght != 10) {
            alert("โทรตรวจสอบเบอร์โทรศัพท์มือถืของคุณอีกครั้ง");
            obj.find('input[name="phone"]').focus();
            return (false);
        }
    }else {
        alert("โทรตรวจสอบเบอร์โทรศัพท์มือถืของคุณอีกครั้ง");
        obj.find('input[name="phone"]').focus();
        return false;
    }

    if(date.trim() == ''){
        alert("กรุณาเลือกวันสำหรับเรียนในห้องทดลองเรียน");
        obj.find('input[name="date"]').focus();
        return false;
    }
    
    if(time.trim() == ''){
        alert("กรุณาเลือกเวลาสำหรับเรียนในห้องทดลองเรียน");
        obj.find('input[name="time"]').focus();
        return false;
    }

    if(age == ''){
        alert("ระบุเพศของคุณ");
        obj.find("select[name=age]").focus();
        return (false);
    }

    if(level == ''){
        alert("รระบุระดับภาษาอังกฤษของคุณ");
        obj.find("select[name=level]").focus();
        return (false);
    }

    if(purpose == ''){
        alert("เลือกเป้าหมายการเรียนของคุณ");
        obj.find("select[name=purpose]").focus();
        return (false);
    }
    /* 
     * Check cookie nếu cần
     * @params: time - thời gian sống cookies
     */
    // var check_cookies = check_cookie(obj, 5);
    // if (!check_cookies) {
    //     return false;
    // }

    button.attr('disabled', 'disabled');
    obj.ajaxSubmit({
        type: "POST",
        url: url,
        dataType: 'json',
        data: {
            'phone': phone_number
        },
        async: false,
        success: function (data) {
            // alert(data);
            button.removeAttr('disabled');
            $('input[name=id]').attr('value', data.id);
            $('input[name=contact_id]').attr('value', data.contact_id);
            if (data.status) {
                data_return = true;
            } else {
                alert(data.msg);
                data_return = false;
            }
            // window.open('./conversion.php', '_blank');
        }, error: function () {
            alert('ลงทะเบียนไม่สำเร็จ กรุณาลองใหม่อีกครั้ง');
            button.removeAttr('disabled');
            data_return = false;
        }, complete: function () {
        }
    });
    return data_return;
}
