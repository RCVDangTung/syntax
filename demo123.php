<?php
//get data
$ref = isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : '';
$domain = isset($_SERVER[HTTP_HOST]) ? "http://$_SERVER[HTTP_HOST]" . $_SERVER["REQUEST_URI"] : '';
$id = isset($_GET["id"]) ? $_GET["id"] : "-100";
$preview = isset($_GET["preview"]) ? $_GET["preview"] : "-100";
$code_chanel = isset($_GET["code_chanel"]) ? $_GET["code_chanel"] : "-100";

require_once './mol_topmito/save_a2.php';
require_once './mol_topmito/BaoKim.php';
$BaoKim = new BaoKim();
$getMethodPayment = $BaoKim->getMethodPayment();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Luyện nói thỏa thích - TOPICA NATIVE</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
        <link href="css/style.css" rel="stylesheet" type="text/css"/>
        <link href="css/baokim.css" rel="stylesheet" type="text/css"/>
        <link href="css/thanh-toan.css" rel="stylesheet" type="text/css"/>
        <link type="img/x-icon" href="./img/favicon.png" rel="shortcut icon">

        <script src="mol_topmito/js/jquery.min.js" type="text/javascript"></script>
        <script src="mol_topmito/js/jquery.form.js" type="text/javascript"></script>
        <script src="mol_topmito/js/save_contact.js" type="text/javascript"></script>
        <script src="js/bootstrap.min.js" type="text/javascript"></script>
        <script src="js/main.js" type="text/javascript"></script>
        <script src="js/baokim.js" type="text/javascript"></script>

        <!--datepicker-->
        <link href="css/jquery.datetimepicker.css" rel="stylesheet" type="text/css"/>
        <script src="js/jquery.datetimepicker.js" type="text/javascript"></script>
        <!--end datepicker-->

        <style type="text/css">
            td.xdsoft_disabled{
                background: #000 !important;
            }
        </style>
    </head>
    <body>
        <div id="parent-nav">
            <nav class="container">
                <div class="row">
                    <div class="col-md-3 col-xs-4">
                        <a href="./index.php"><img src="./img/TOPICA NATIVE.png" alt="" class="full-width"/></a>
                    </div>
                    <div class="col-md-7 col-md-offset-2 col-xs-8 col-xs-offset-0 text-right">
                        <ul class="row mr-0">
                            <li class="col-xs-4 text-right bold font-th-bold">
                                <span class="fa fa-phone"></span> Hotline: 0981 228 979
                            </li>
                            <li class="col-xs-6 text-right bold font-th-bold">
                                <span class="fa fa-envelope"></span> Email: topica.native@topica.edu.vn
                            </li>
                            <li class="col-xs-1 text-right bold size-normal">
                                <a href="https://line.me/ti/p/%40dsn9535q">
                                    <img src="./img/line.png" alt="">
                                </a>	
                            </li>
                            <li class="col-xs-1 text-right bold size-normal">
                                <a href="https://www.facebook.com/topicanativethailand/">
                                    <img src="./img/icon-fb.png" alt="">
                                </a>	
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <div id="step-nav" class="container size-normal">
                <div class="text-center color-red">
                    <span class="bg-red bold size-supper-normal active-span" role="1">1</span> &nbsp;&nbsp;<span role="1" class="active-span">ลงทะเบียน</span>
                    <span class="border-span"></span>
                    <span class="bg-red bold size-supper-normal" role="2">2</span> &nbsp;&nbsp;<span role="2" class="">ยืนยัน</span>
                    <span class="border-span"></span>
                    <span class="bg-red bold size-supper-normal" role="3">3</span> &nbsp;&nbsp;<span role="3" class="">ชำระเงิน</span>
                </div>
                <step role="1">
                    <h1 class="text-center color-red size-lg mr-top-30">
                        Luyện nói trực tuyến<br/>
                        cùng giáo viên bản ngữ Âu - Úc - Mỹ chỉ với 100.000đ
                    </h1>
                </step>
                <step role="2" class="hidden">
                    <h1 class="text-center color-red size-lg mr-top-30">
                        Xác nhận thông tin đăng ký học thử của bạn
                    </h1>
                </step>
                <step role="3" class="hidden">
                    <h1 class="text-center color-red size-lg mr-top-30">
                        Bước cuối cùng để hoàn thành đăng ký
                    </h1>
                </step>
            </div>
        </div>

        <div id="content" class="container mr-top-45">
            <div class="row">
                <form id='payment_form' role="form" role="1" class="" method="POST" data-action="./mol_topmito/save_a3.php" data_url="" action="./mol_topmito/OnePay.php">
                    <div class="col-md-8">
                        <div id="check-hour" url-hour='./mol_topmito/get_hour.php'></div>
                        <!--Step 1-->
                        <!-- <div class="row">
                        <div class="col-xs-8"> -->
                        <?php
                        echo "<input type='hidden' class='e_domain_ref' name='http_referer' value=" . $ref . ">";
                        echo "<input type='hidden' name='domain' value=" . $domain . ">";
                        echo "<input type='hidden' name='id_camp_landingpage' value=" . $id . ">";
                        echo "<input type='hidden' name='preview' value=" . $preview . ">";
                        echo "<input type='hidden' name='code_chanel' value=" . $code_chanel . ">";
                        ?>
                        <input type="hidden" class="" name="id">
                        <input type="hidden" class="" name="contact_id">
                        <section role="1">
                            <p class="title bg-red bold row" style="font-family: 'Conv_THSARABUNNEW BOLD' !important;font-size: 25px;">จองห้องเรียนทดลอง</p>
                            <div class="row detail">
                                <div class="form-group col-xs-6">
                                    <label class="font-th-bold">ชื่อจริง</label>
                                    <input type="text" class="form-control step1" name="name"  placeholder="Nguyen Van A">
                                </div>
                                <div class="form-group col-xs-6">
                                    <label class="font-th-bold">เบอร์โทรศัพท์</label>
                                    <input type="text" class="form-control step1" name="phone" placeholder="090 xxx xxxx">
                                </div>
                                <div class="form-group col-xs-6">
                                    <label class="font-th-bold">อีเมล์</label>
                                    <input type="email" class="form-control step1" name="email"  placeholder="example@gmail.com">
                                </div>
                                <div class="form-group col-xs-4" style="position: relative;">
                                    <label class="font-th-bold">เวลาเรียน</label>
                                    <input type="text"  class="form-control step1 change_date" id="datepicker" name="date" style="padding-left: 35px;">
                                    <img src="./img/Layer 51.png" alt="" style="position: absolute; bottom: 5px; left: 20px; width: 25px;"/>
                                </div>
                                <div class="form-group col-xs-2" style="position: relative;">
                                    <label class="font-th-bold">&nbsp;&nbsp;</label>
                                    <input type="text"  class="form-control step1" id="timepicker" name="time"  style="padding-left: 35px;">
                                    <img src="./img/Layer 52.png" alt="" style="position: absolute; bottom: 5px; left: 20px; width: 25px;"/>
                                </div>

                                <div class="form-group col-xs-1">
                                    <label class="font-th-bold">อายุ</label>
                                    <select class="form-control step1" name="age" style="padding: 6px 5px;width: 58px;">
                                        <option value="">---</option>
                                        <option value="18 - 24">18 - 24</option>
                                        <option value="25 - 34">25 - 34</option>
                                        <option value="35 - 44">35 - 44</option>
                                        <option value="45 - 54">45 - 54</option>
                                        <option value="55+">55+</option>
                                    </select>
                                </div>
                                <div class="form-group col-xs-5">
                                    <label class="font-th-bold">การประเมินระดับภาษาอังกฤศของตนเอง</label>
                                    <select class="form-control step1" name="level">
                                        <option value="">---</option>
                                        <option value="Chưa nghe nói được">ไม่สามารถพูดและฟังภาษาอังกฤษได้</option>
                                        <option value="Nghe nói được chút ít">สามารถพูดและฟังภาษาอังกฤษได้นิดหน่อย</option>
                                        <option value="Nghe nói được ở mức khá">สามารถพูดและฟังภาษาอังกฤษได้ค่อนข้างดี</option>
                                        <option value="Nghe nói tốt">สามารถพูดและฟังภาษาอังกฤษได้เป็นอย่างดี</option>
                                    </select>
                                </div>
                                <div class="form-group col-xs-6">
                                    <label class="font-th-bold">จุดประสงค์ในการเรียน</label>
                                    <select class="form-control step1" name="purpose">
                                        <option value="">---</option>
                                        <option value="Đi du học">การศึกษาต่างประเทศ</option>
                                        <option value="Phục vụ công việc">เพื่อการทำงาน</option>
                                        <option value="Du lịch nước ngoài">การท่องเที่ยวต่างประเทศ</option>
                                        <option value="Dạy con">การสอนเด็ก</option>
                                        <option value="Học để không quên tiếng Anh">เพื่่อการทบทวนภาษาอังกฤษ</option>
                                        <option value="Học cho vui">เพื่อความสนุก</option>
                                        <option value="Chưa biết để làm gì">ไม่ทราบค่ะ/ ไม่รู้ค่ะ</option>
                                    </select>
                                </div>
                                <div class="form-group col-xs-6">
                                    <i class="font-th-italic">ข้อมูลของท่านจะเป็นความลับ และจะใช้เพียงเพื่อช่วยเหลือคุณในขณะที่เรียนกับ TOPICA Native <br />
                                        <a href="#">อ่านข้อมูลเพิ่มเติมเกี่ยวกับการรักษาความปลอดภัย</a></i>
                                </div>
                                <div class="form-group col-xs-6">
                                    <button class="btn bg-red btn-lg radius-35 full-width next e_submit font-th-bold">ต่อไป / ถัดไป</button>
                                </div>
                            </div>
                        </section>
                    </div>
            </div>
        </div>
    </div>
</div>
</form>



<footer class="container-fluid mr-top-30">
    <div class="row">
        <div class="container">
            <div class="row mr-top-20">
                <div class="col-xs-4">
                    <img src="img/logo.png" alt="" style="width: 100%;"/>
                </div>
                <div class="col-xs-8">
                    <p class="line-2 font-th-normal" style="line-height: 20px;margin: 0px;">
                        องค์กรเทคโนโลยีการศึกษา TOPICA เป็นผู้บุกเบิกด้านการศึกษาออนไลน์ในเอเชียตะวันออกเฉียงใต้ โดยโปรแกรมการศึกษาระดับ
                        มหาวิทยาลัย TOPICA UNI ได้ร่วมมือกับมหาวิทยาลัยชั้นนำ 8 แห่งของฟิลิปปินส์ และเวียดนามให้บริการด้านการศึกษาที่มีคุณภาพในเอเชีย
                        ตะวันออกเฉียงใต้ และโปรแกรมสอนภาษาอังกฤษ ออนไลน์ TOPICA Native และ TOPICA Memo ได้เปิดให้บริการในไทย อินโดนีเซีย และ
                        เวียดนาม อีกทั้งยังเป็น รายแรกที่ประยุกต์การใช้ Google Glass
                        ในการสอนออนไลน์ ตั้งแต่มีการก่อตั้งตลอดระยะเวลา 3 ปีที่ผ่านมา TOPICA 
                        ได้รับทุนสนับสนุนในโปรเจกต์ต่างๆ มากถึง 10 ล้านเหรียญสหรัฐฯ โดยมี Bill Gates ประธานบริษัท Microsoft Corp. เป็นผู้ให้ทุนสนับสนุนใน
                        การก่อตั้ง ปัจจุบัน TOPICA มีบุคลากรมากกว่า 500 คน พนักงานชั่วคราวมากกว่า 1,400 คน และมีสำนักงานที่กรุงมะนิลา กรุงเทพฯ ฮานอย 
                        โฮจิมินห์ และดานัง.
                    </p>
                    <div class="row mr-top-15">
                        <p class="col-xs-6 font-th-normal">
                            TOPICA Thailand: 2 Ploenchit Center, Bangkok<br/>
                            TOPICA Singapore: One Raffles Place Tower One 048616 
                        </p>
                        <p class="col-xs-6 font-th-normal">
                            TOPICA Philippines: Quezon City, 1109 Philippines<br/>
                            TOPICA Vietnam: 75 Phuong Mai, Dong Da, Hanoi
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mr-top-20" style="background: #000; padding-top: 10px; padding-bottom: 10px;">
        <div class="col-xs-6 text-right color-white size-normal">Hotline: 0981 228 979</div>
        <div class="col-xs-6 color-white size-normal">Email: topica.native@topica.edu.vn</div>
    </div>
</footer>





<script type="text/javascript">
    jQuery('#datepicker').datetimepicker({
        timepicker: false,
        format: 'Y-m-d',
        minDate: '+1970/01/02',
        maxDate: '+1970/01/07'
    });
    /* jQuery('#timepicker').datetimepicker({
     datepicker: false,
     format: 'H:i',
     allowTimes: ['15:00', '16:00', '19:00', '20:00', '21:00', '22:00', '23:00',]
     }); */
</script>
</body>
</html>
