<?php
//get data
$ref = isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : '';
$domain = isset($_SERVER[HTTP_HOST]) ? "http://$_SERVER[HTTP_HOST]" . $_SERVER["REQUEST_URI"] : '';
$id = isset($_GET["id"]) ? $_GET["id"] : "";
$preview = isset($_GET["preview"]) ? $_GET["preview"] : "-100";
$code_chanel = isset($_GET["code_chanel"]) ? $_GET["code_chanel"] : "";
$link = (!empty($id) && !empty($code_chanel)) ? '?id=' . $id . '&code_chanel=' . $code_chanel : '';

require_once './mol_topmito/save_a1.php';
// $rand_site_number = rand(1, 2);
// if ($rand_site_number === 1) {
//     $rand_img = './images/crossUNI.jpg';
//     $rand_site = 'http://hou.tuyensinh.topica.edu.vn/?code=CRS.Native.HOU.CaNuoc.190315';
//     $rand_string = 'Đừng bỏ lỡ Chương trình Cử nhân trực tuyến chất lượng cao HOU-TOPICA. Click trang này để khám phá và nhận tư vấn miễn phí!';
// } else {
//     $rand_img = './images/MM0033.png';
//     $rand_site = 'http://memo.edu.vn/?code_chanel=CS0004&id_landingpage=2&id_campaign=1&id=25';
//     $rand_string = 'Đừng bỏ lỡ cơ hội để trở thành CHUYÊN GIA NGOẠI NGỮ một cách HOÀN TOÀN MIỄN PHÍ, click ở lại trang này để KHÁM PHÁ NGAY!';
// }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>วิธีใหม่สำหรับการเรียนภาษาอังกฤษ Topica Native</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
        <link href="css/style_index.css" rel="stylesheet" type="text/css"/>
        <!--        <link href="css/w3.css" rel="stylesheet" type="text/css"/>-->

        <script src="mol_topmito/js/jquery.min.js" type="text/javascript"></script>
        <script src="mol_topmito/js/jquery.form.js" type="text/javascript"></script>
        <!--<script src="mol_topmito/js/save_contact.js" type="text/javascript"></script>-->
        <script src="js/bootstrap.min.js" type="text/javascript"></script>
        <link type="img/x-icon" href="./img/favicon.png" rel="shortcut icon">


        <script>
            !function (f, b, e, v, n, t, s) {
                if (f.fbq)
                    return;
                n = f.fbq = function () {
                    n.callMethod ?
                            n.callMethod.apply(n, arguments) : n.queue.push(arguments)
                };
                if (!f._fbq)
                    f._fbq = n;
                n.push = n;
                n.loaded = !0;
                n.version = '2.0';
                n.queue = [];
                t = b.createElement(e);
                t.async = !0;
                t.src = v;
                s = b.getElementsByTagName(e)[0];
                s.parentNode.insertBefore(t, s)
            }(window,
                    document, 'script', '//connect.facebook.net/en_US/fbevents.js');

            fbq('init', '1003510776366472');
            fbq('track', ""PageView"");
        </script>
        <noscript>
    <img height=""1"" width=""1"" style=""display:none"" src=""https://www.facebook.com/tr?id=1003510776366472&ev=PageView&noscript=1""
         />
         </noscript>
    <!-- End Facebook Pixel Code -->

</head>
<body>
    <button id="nav-top" class="btn btn-lg hidden-xs"><a style="padding-top: 30px;" href="./payment.php<?php echo $link; ?>" class="nav color-white"><span class="fa fa-user"></span>&nbsp;&nbsp;&nbsp;ลงทะเบียน</a></button>
    <nav class="container-fluid" id="header">
        <div class="row">
            <div class="col-md-3 col-md-offset-1 col-xs-6 col-xs-offset-3 col-sm-3 col-sm-offset-1 ">
                <a href="#"><img src="./img_1/logo thailand.png" alt="" class="full-width"/></a>
            </div>

            <div class="col-md-6 col-sm-5 col-sm-offset-0 col-xs-12 col-xs-offset-0 text-right">
                <ul class="row mr-0" id="">
                    <li class="contact-1 col-xs-5 col-md-5  col-sm-12 font-u  text-contact-md bold  text-center">
                        <span class="fa fa-phone"></span><span class="font-u ">&nbsp;&nbsp;Hotline: 02-105-4415</span>
                    </li>
                    <li class="contact-1 col-xs-7 col-md-7 col-sm-12 font-u bold  text-contact-md text-center">
                        <span class="fa fa-envelope"></span><span class="font-u ">&nbsp;&nbsp;Email: support@topicanative.asia</span>
                    </li>

                </ul>

            </div>

            <div class="col-md-2 col-sm-3 col-xs-12">
                <ul class="row ">
                    <li class="col-xs-1 col-xs-offset-9  col-md-3 col-md-offset-3  col-sm-4 col-sm-offset-2" style="padding:0">
                        <a target="_blank" href=" https://line.me/ti/p/%40dsn9535q">
                            <img src="./img_1/logo-line.png" alt="" class="logo-md img-responsive icon-cont line-img line-cont"/>
                        </a>
                    </li>
                    <li class="col-xs-2 col-md-6 col-md-offset-0 col-sm-5 ">
                        <a target="_blank" href="https://www.facebook.com/topicanativethailand/">
                            <img src="./img_1/logo-fb.png" alt=""  class=" logo-md img-responsive icon-cont"/>
                        </a>
                    </li>

                </ul>
            </div>
        </div>
    </nav>
    <a style="padding-top: 30px;" href="./payment.php<?php echo $link; ?>" class="nav color-white">
        <button id="nav-top" class="btn btn-lg hidden-xs" style=" font-family: Thaifont;  font-size: 26px;"><span class="fa fa-user">
            </span>&nbsp;&nbsp;&nbsp;ลงทะเบียน </button>
    </a>
    <div class="container-fluid">
        <div class="row relative">
            <img src="img_1/banner-2.png" alt="" class="full-width"/>
            <div class="col-md-offset-4 col-md-8  col-xs-offset-0 col-xs-12 col-sm-offset-3 col-sm-9 pos-text" style="">
                <p class=" text-center p-banner col-sm-12 col-xs-12 ">คุณอยากลองเรียนภาษาอังกฤษออนไลน์ไหม </br>
                    เรียนออนไลน์กับคนนับล้านทั่วโลก
                </p>
                <p class="relative">
                    <img src="./img_1/Vector Smart Object.png" alt="" class="absolute img-responsive"  style="position: absolute;top: 125px;left: 146px;width: 15%;" />
                    <a href="./payment.php" class="btn btn-dk btn-lg btn-md btn-sm btn-xs bg-red size-lg mr-20 col-md-offset-4 col-xs-offset-4" style="border-radius: 35px;padding: 3px 40px;color:#FFF;">
                        จองห้องเรียนทดลอง
                    </a>
                </p>
                <!-- <button class="btn btn-dk btn-lg btn-md btn-sm btn-xs bg-red size-lg mr-20 relative col-md-offset-4 col-xs-offset-4" style="border-radius: 35px;">
                    <a href="http://thailand.topicanative.asia/payment.php"
                     class="" style="padding:5%;color:#fff;font-family: Thaifont_b;">จองห้องเรียนทดลอง</a>
                     <img src="./img_1/Vector Smart Object.png" alt="" class="absolute img-responsive img-vector" />
                 </button> -->
                <p class="p-banner-sm col-md-offset-1 col-xs-offset-2 col-xs- 12 col-sm-offset-2 hidden-xs" style="padding-left: 4%;margin-left: 100px;">เรียนออนไลน์แบบตัวต่อตัว อาจารย์ 1 ท่าน : นักเรียน 1 คน เป็นเวลา 45 นาที</p>
                <p class="p-banner-sm col-md-offset-2 col-xs-offset-1  col-xs-11 col-sm-offset-2  hidden-xs " style="margin-left: 24%;">คืนเงินคอร์สทดลอง หลังจากคุณสมัครคอร์สเต็ม</p>
                <p class="p-banner-sm col-md-offset-2 col-xs-offset-1 col-xs-11  col-sm-offset-2  hidden-xs" style="margin-left: 24%;">มีที่ปรึกษาส่วนตัวที่พร้อมให้คำแนะนำตลอดเวลา</p>
            </div>

        </div>
    </div>

    <h1 class="color-red text-center size-lg font-u mr-45" style="font-family: 'Thaifont_b';font-size: 60px !important;">แนะนำโปรแกรม TOPICA Native</h1>
    <div class="container-fluid" style=" padding: 0;">

        <img src="./img_1/SA_2.jpg" alt="" class="full-width hidden-xs"/>
        <div class="row hidden-lg hidden-md hidden-sm " style="background: #810c15;
             padding: 3% 0;">

            <div class="col-xs-offset-0 col-xs-4  ">
                <img src="img_1/Clock.png" class="img-intro">

                <p class="p-intro ">เรียนตามที่คุณต้องการไ
                    ด้มากถึง 16 
                    ชั่วโมงทุกวัน
                </p>

            </div>
            <div class="col-xs-offset-0 col-xs-4">
                <img src="img_1/Teacher.png"class="img-intro">

                <p class="p-intro">
                    อาจารย์ชาวต่างชาติ 100% 
                    จากอเมริกา, 
                    ยุโรป และ ออสเตรเลีย
                </p>

            </div>
            <div class="col-xs-offset-0 col-xs-4">
                <img src="img_1/Schedule.png" class="img-intro">

                <p class="p-intro">
                    ที่ปรึกษาส่วนตัว
                    และการเรียน
                    ที่เหมาะกับแต่ละคน
                </p>

            </div>
        </div>
    </div>

    <div id="article" class="margin-45">
        <div class="container">
            <div class="row">
                <div class="col-xs-6 col-xs-offset-0 col-sm-6 col-sm-offset-1 col-md-7 col-md-offset-0 ">
                    <p class="size-supper-normal p-tit-md color-red  text-justify mr-20">
                        เรียนได้ถึง 16 ชั่วโมงทุกวันตั้งแต่แปดโมงเช้าจนถึงเที่ยงคืน <br>
                        เรียนได้ทุกที่ทุกเวลาที่คุณต้องการ
                        <!-- เรียนได้ถึง 16 ชั่วโมงทุกวัน ตั้งแต่ แปดโมงเช้า 
                        จนถึง เที่ยงคืน เรียนได้ทุกที่ทุกเวลาที่คุณต้องการ -->
                    </p>

                    <!-- text-justify -->
                    <p class="text-left p-normal-md">
                        สิ่งหนึ่งที่จะช่วยให้ผู้เรียนพูดภาษาอังกฤษได้อย่างคล่องแคล่ว <br>
                        คือการฝึกการพูดกับเจ้าของภาษาเป็นประจำ <br>
                        <!-- สิ่งหนึ่งที่จะช่วยให้ผู้เรียนพูดภาษาอังกฤษได้อย่างคล่องแคล่วคือการฝึกการพูดกับเจ้าของภาษาเป็นประจำ <br> -->
                        <!-- <br> -->
                        โชคไม่ดีที่คนไทยไม่มีสภาพแวดล้อมในการใช้ภาษา เพื่อฝึกฝนอย่างเหมาะสม <br>
                        ด้วยเทคโนโลยีล่าสุด TOPICA Native มีคาบเรียนให้เลือก <br>
                        ได้มากถึง 16 คาบต่อวัน ตั้งแต่ 8 โมงเช้า ถึงเที่ยงคืน<br>
                        <!-- ซึ่งจะทำให้ผู้เรียนสามารถฝึกพูดภาษาอังกฤษได้มาก<br> -->

                    </p>
                </div>
                <div class="col-xs-6 col-sm-4 col-md-5">
                    <img src="./img_1/peo-cl-1.png" alt="" class="full-width img-responsive img-peo"/>
                </div>
            </div>
            <div class="row mr-top-45">
                <div class="col-xs-6 col-xs-offset-0 col-sm-4 col-sm-offset-1 col-md-5 col-md-offset-0">
                    <img src="./img_1/pic2.png" alt="" class="full-width img-responsive img-peo"/>
                </div>
                <div class="col-xs-6 col-sm-6 col-sm-soffset-1 col-md-7">
                    <p class="size-supper-normal p-normal-md color-red p-tit-md  text-justify mr-20">
                        <!-- ที่ปรึกษาส่วนตัวและการเรียนที่เหมาะกับแต่ละคน -->
                        มีแผนการเรียนและที่ปรึกษาส่วนตัว
                    </p>
                    <p class="text-justify">
                        มีแผนการเรียนและที่ปรึกษาส่วนตัว <br>
                        ผู้เรียนของ TOPICA Native จะมีที่ปรึกษาส่วนตัว <br>
                        ที่จะคอยให้คำแนะนำกับคุณตลอดการเรียนรู้ <br>
                        ที่ปรึกษาจะวิเคราะห์และติดตามตารางการเรียนของผู้เรียน <br>
                        โดยอิงจากการประเมินของอาจารย์เจ้าของภาษา <br>
                        ที่ปรึกษาจะให้คำแนะนำกับผู้เรียน ในส่วนที่ผู้เรียนยังขาดและต้องการฝึกฝน <br>
                        เพื่อให้ผู้เรียนสามารถพัฒนาภาษาอังกฤษได้อย่างรวดเร็ว<br>
                        <!-- 
                        คนที่จะคอยอยู่เคียงข้างนักเรียนตลอดกระบวนการเรียนรู้และช่วยให้เราสามารถกำหนด
                        วิธีการเรียนเองได้ 
                        ผู้ให้คำแนะนำจะวิเคราะห์ประวัติการเรียนและติดตามตารางการเรียนของนักเรียน 
                        อิงจากคำแนะนำจากอาจารย์เจ้าของภาษาและวัตถุประสงค์การศึกษา 
                        พวกเขาจะให้คำแนะนำที่ถูกต้องให้กับนักเรียน เช่น 
                        นักเรียนต้องการที่จะฝึกบางสิ่งบางอย่าง 
                        ระยะเวลานานแค่ไหนที่จะต้องฝึกหรือเรียนในแต่ละวัน 
                        ดังนั้นนักเรียนจะสามารถบรรลุวัตถุประสงค์และพัฒนาภาษาอังกฤษได้อย่างรวดเร็ว  -->
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div id="teacher" class="border-bottom">
        <div class="container">
            <h1 class="text-center color-red size-lg mr-top-30" style="font-family: Thaifont_b;">อาจารย์เจ้าของภาษา 100%</h1>
            <div class="row mr-top-30">
                <div class="col-xs-4 col-md-4 col-sm-4 text-center">
                    <img src="./img/teacher1.png" alt="" class="full-width img-responsive img-tea"/>
                    <h2 class="tea-name ">กาย ดิกสัน</h2>
                    <h3 class="tea-name " style="color: #000">ชาวอังกฤษ</h3>
                    <p class="text-center p-sm">
                        ผมสามารถสอนนักเรียนได้ <br>
                        ในทุกๆระดับและทุกๆช่วงอายุ

                        <!-- ผมสามารถสอนนักเรียนได้ใน <br>
                        ทุกๆระดับและทุกๆช่วงอายุ -->
                        <!-- ผมสามารถสอนนักเรียนได้ใน </br>
                        ทุกๆระดับและทุกๆช่วงอายุ -->
                    </p>
                </div>
                <div class="col-xs-4 col-md-4 col-sm-4 text-center">
                    <img src="./img/teacher2.png" alt="" class="full-width img-responsive img-tea"/>
                    <h2 class="tea-name">เดวิด เดวิน</h2>
                    <h3 class="tea-name " style="color: #000">ชาวอเมริกัน</h3>
                    <p class="text-center p-sm">
                        <!-- ผมมุ่งเน้นเรื่องการออกเสียงเป็นส่วนใหญ่ <br>
                        นักเรียนหลายคนจะได้พัฒนาอย่างรวดเร็วหลังจากเรียนได้ 1 เดือน -->

                        ผมมุ่งเน้นเรื่องการออกเสียงเป็นส่วนใหญ่<br>
                        นักเรียนหลายคนจะได้พัฒนาอย่างรวดเร็ว<br>
                        หลังจากเรียนได้ 1 เดือน

                        <!-- ผมมุ่งเน้นเรื่องการออกเสียงเป็นส่วนใหญ <br>
                                                        นักเรียนหลายคนจะได้พัฒนาอย่างรวดเร็ว<br> 
                                                        หลังจากเรียนได้ 1 เดือน<br> -->

                        <!-- ผมมุ่งเน้นเรื่องการออกเสียงเป็นส่วนใหญ <br>
                        นักเรียนหลายคน <br>
                        จะได้พัฒนาอย่างรวดเร็ว <br>
                        หลังจากเรียนได้ 1 เดือน -->
                        <!-- ผมมุ่งเน้นเรื่องการออกเสียงเป็นส่วนใหญ่ 
                        นักเรียนหลายคน
                        จะได้พัฒนาอย่างรวดเร็ว
                        หลังจากเรียนได้ 1 เดือน -->
                    </p>
                </div>
                <div class="col-xs-4 col-md-4 col-sm-4 text-center">
                    <img src="./img/teacher3.png" alt="" class='full-width img-responsive img-tea'/>
                    <h2 class="tea-name">เอ็กเบิร์ท เวลท์แมน</h2>
                    <h3 class="tea-name " style="color: #000">ชาวฮอลแลนด์</h3>
                    <p class="text-center p-sm" style="white-space: nowrap;">
                        ผมรักที่จะสร้างโอกาสให้บรรดานักเรียน <br>
                        เพื่อขยายวงความรู้และพูดคุยในหัวข้อที่แตกต่างๆไปทุกวัน
                        <!-- ผมรักที่จะสร้างโอกาส <br>
                        ให้บรรดานักเรียน <br>
                        เพื่อขยายวงความรู้และพูดคุย<br>
                        ในหัวข้อที่แตกต่างๆไปทุกวัน -->
                        <!--     ผมรักที่จะสร้างโอกาส
                            ให้บรรดานักเรียน 
                            เพื่อขยายวงความรู้และพูดคุย
                            ในหัวข้อที่แตกต่างๆไปทุกวัน -->
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div id="about" class="  container">
        <h1 class="text-center color-red size-lg mr-top-30 tit font-u" style="font-family: 'Thaifont_b';font-size: 60px !important;">ทำความรู้จักกับ TOPICA</h1>

        <div class="row ">
            <div class="mr-top-20 col-xs-10 col-xs-offset-1 col-sm-offset-0 col-sm-12 col-md-6 col-md-offset-1 vd-md" >
                <!--embed-responsive embed-responsive-16by9-->
            <!-- <iframe src="https://www.youtube.com/watch?v=o8qeft4UHEE&feature=youtu.be" 
            frameborder="0" allowfullscreen class="video" style="width: 100%;height: 100%;"></iframe> -->

                <iframe style="width: 100%;height: 100%;" src="https://www.youtube.com/embed/o8qeft4UHEE" frameborder="0" class="video" allowfullscreen></iframe>        
                <!--<iframe class=" embed-responsive-item"  id="yt-md"  src="https://www.youtube.com/embed/dgQnNF8giWY" style="border: none;"></iframe>-->
            </div>
            <div class="col-xs-offset-1 col-xs-10 col-sm-12 col-md-5 detail-video mr-20 p-video">
                <p  class="col-xs-12 col-xs-offset-0 col-sm-5 col-sm-offset-0 sm col-md-11 col-md-offset-0 p-top" style="background-position: 0px 10px;">
                    TOPICA องค์กรชั้นนำด้านการศึกษาออนไลน์ <br>
                    ในเอเชียตะวันออกเฉียงใต้
                    <!-- TOPICA องค์กรชั้นนำด้านการศึกษาออนไล</br>
                    น์ในเอเชียตะวันออกเฉียงใต้ -->
                </p>
                <p class="col-sm-3 col-sm-offset-0 col-md-11 col-md-offset-0 col-xs-offset-1 col-xs-8 " style="background-position: 0px 10px;">
                    เทคโนโลยีชั้นสูง
                </p>
                <p class="p-video-sm col-sm-3 col-sm-offset-1 col-md-11 col-md-offset-0 col-xs-offset-2 col-xs-8" style="background-position: 0px 10px;">
                    ผู้เรียนกว่าพันคนในเอเชียตะวันออกเฉียงใต้
                </p>
            </div>
        </div>

    </div>
    <div id='topica-bc' class="border-bottom mr-45" style="background: #ebebeb;">
        <h1 class="text-center color-red size-lg mr-30" style=" padding-top: 30px;font-family: Thaifont_b">TOPICA ในสื่อต่างๆ</h1>
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-xs-6 col-sm-6">
                    <img src="img_1/col-2.png" alt="" class="img-responsive">
                </div>
                <div class="col-md-6 col-xs-6 col-sm-6">
                    <img src="img_1/pante.png" alt="" class="img-responsive">
                </div>
            </div>
        </div>
        <!--/#slide-bc/-->
        <div class="container-fluid mr-30 mr-top-10 "> 
            <div id="slide-bc" class="carousel slide" data-ride="carousel">

                <!-- Wrapper for slides -->
                <div class="carousel-inner  slide-in" role="listbox">
                    <div class="item item-slide  active " style="display: inline-block">
                        <div class="col-md-7 col-sm-6 col-xs-6 col-xs-offset-0  ">
                            <p  class="p-topica-bc">
                                CEO ของ Lenovo  
                                เริ่มเรียนภาษาอังกฤษตอนอายุ 40<br/><br/>
                            </p>
                        </div>
                        <div class="col-md-5 col-sm-6 col-xs-6 div-img-slide">
                            <a href="http://www.prachachat.net/news_detail.php?newsid=1444116011"><img src="img_1/slide-p1.png" class=" img-slide " ></a>
                        </div>
                    </div>

                    <div class="item item-slide " style="display: inline-block">
                        <div class="col-md-7 col-sm-6 col-xs-6 col-xs-offset-0">
                            <p  class="p-topica-bc">
                                แชร์ประสบการณ์!!! </br>
                                เรียนภาษาอังกฤษกับ </br>
                                Topica Native
                            </p>
                        </div>
                        <div class="col-md-5 col-sm-6 col-xs-6 div-img-slide">
                            <a href="http://pantip.com/topic/34193173"><img src="img_1/slide-p2.png" class=" img-slide " ></a>
                        </div>
                    </div>



                </div>

                <!-- Left and right controls -->
                <a class="left carousel-control click-icon slide-left" href="#slide-bc" role="button" data-slide="prev">
                    <span class="glyphicon glyphicon-chevron-left pre-ic" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="right carousel-control click-icon" href="#slide-bc" role="button" data-slide="next">
                    <span class="glyphicon glyphicon-chevron-right next-ic" aria-hidden="true"  style="margin-right: 0"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>
    </div>

    <div id="3-steps" class="container text-center ">
        <div class="row">
            <h1 class="text-center color-red size-lg mr-top-30" style="font-family: Thaifont_b;">3 ขั้นตอนในการทดลองเรียนกับ 
                TOPICA Native</h1>
            <img src="./img_1/3-steps_1.png" alt="" class="mr-top-30 full-width hidden-xs"/>
            <img src="./img_1/3-steps-2.png" alt="" class="mr-top-30 full-width hidden-lg hidden-md hidden-sm"/>
        </div>
        <div class="row hidden-lg hidden-md hidden-sm">
            <div class="col-xs-offset-0 col-xs-4" style="padding-right: 0;">
                <img src="./img_1/step-1.png" alt="" style=" margin-left: 18px;" >
                <p  class='p-step'>จองห้องเรียนทดลอง</p>
            </div>

            <div class="col-xs-offset-0 col-xs-4" style="padding-right: 0;">
                <img src="./img_1/step-2.png" alt="" >
                <p class='p-step'>ทำการทดสอบทางเทคนิค</p>
            </div>

            <div class="col-xs-offset-0 col-xs-4" style="padding-left: 0;">
                <img src="./img_1/step-3.png" alt="" >
                <p  class='p-step'>เริ่มต้นการเรียน</p>
            </div>
        </div>
        <div class="row">

            <p class="text-center bold color-red size-supper-normal  mr-top-45" style=" font-size: 35px;">

                สัมผัสประสบการณ์การเรียนภาษาอังกฤษออนไลน์</br>
                45 นาทีกับครูเจ้าของภาษาแบบตัวต่อตัว
            </p>
        </div>
        <div class="row " id="btn-dk-2">

            <p class="relative">
                <img src="./img_1/Vector Smart Object.png" alt="" class="absolute img-responsive"  style="position: absolute;top: -59px;left: 20%;width: 17%;" />
                <a href="./payment.php" class="btn btn-dk btn-lg btn-md btn-sm btn-xs bg-red size-lg mr-20 col-md-offset-4 col-xs-offset-4" style="border-radius: 35px;padding: 3px 40px;color:#FFF;margin: 35px 0px 15px 5px;">
                    จองห้องเรียนทดลอง
                </a>
            </p>

            <!-- <button class="btn btn-lg btn-md btn-sm btn-xs bg-red size-lg mr-top-20 relative" style="border-radius: 35px;">
                <a href="./payment.php<?php echo $link; ?>
                   " class="" style="padding:15%;color:#fff">จองห้องเรียนทดลอง</a>
                   <img src="./img_1/Vector Smart Object.png"  alt="" class="absolute img-responsive img-vector" />

               </button> -->


                           <!-- <p class="text-center mr-top-30"><a href="http://advantage.topicanative.edu.vn/" style="color: #810c15;font-weight: bold;font-size: 30px;" class="border-bottom .p-tit-a">ทำไมต้อง TOPICA</a></p> -->
        </div>

    </div>

    <footer class="container-fluid mr-top-30">

        <div class="row mr-top-20">
            <div class="col-xs-4 col-sm-0 col-md-4 hidden-sm hidden-xs">
                <img src="img/logo.png" alt="" style="width: 100%;" class="img-responsive"/>
            </div>
            <div class="col-xs-10 col-xs-offset-1 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-0">
                <div class="row">
                    <p class="p-footer">
                        องค์กรเทคโนโลยีการศึกษา TOPICA เป็นผู้บุกเบิกด้านการศึกษาออนไลน์ในเอเชียตะวันออกเฉียงใต้ โดยโปรแกรมการศึกษาระดับ 
                        มหาวิทยาลัย TOPICA UNI ได้ร่วมมือกับมหาวิทยาลัยชั้นนำ 8 แห่งของฟิลิปปินส์ และเวียดนามให้บริการด้านการศึกษาที่มีคุณภาพในเอเชีย
                        ตะวันออกเฉียงใต้ และโปรแกรมสอนภาษาอังกฤษ ออนไลน์ TOPICA Native และ TOPICA Memo ได้เปิดให้บริการในไทย อินโดนีเซีย และ
                        เวียดนาม อีกทั้งยังเป็น รายแรกที่ประยุกต์การใช้ Google Glass ในการสอนออนไลน์ ตั้งแต่มีการก่อตั้งตลอดระยะเวลา 3 ปีที่ผ่านมา TOPICA 
                        ได้รับทุนสนับสนุนในโปรเจกต์ต่างๆ มากถึง 10 ล้านเหรียญสหรัฐฯ โดยมี Bill Gates ประธานบริษัท Microsoft Corp. เป็นผู้ให้ทุนสนับสนุนใน
                        การก่อตั้ง ปัจจุบัน TOPICA มีบุคลากรมากกว่า 500 คน พนักงานชั่วคราวมากกว่า 1,400 คน และมีสำนักงานที่กรุงมะนิลา กรุงเทพฯ ฮานอย 
                        โฮจิมินห์ และดานัง

                    </p>
                </div>
                <div class="row mr-top-30 mr-top-10">
                    <p class="p-footer col-xs-offset-0 col-xs-12 col-md-6 col-md-offset-0 col-sm-6 col-sm-offset-0 p-sm-u">
                        TOPICA Thailand: 2 Ploenchit Center, Bangkok<br/>
                        TOPICA ingapore: One Raffles Place Tower One 048616 
                    </p>
                    <p class="p-footer col-xs-offset-0 col-xs-12 col-md-6 col-md-offset-0 col-sm-6  col-sm-offset-0 p-sm-u">
                        TOPICA Philippines: Quezon City, 1109 Philippines<br/>
                        TOPICA Vietnam: 75 Phuong Mai, Dong Da, Hanoi
                    </p>
                </div>
            </div>


        </div>

        <div class="row mr-top-20 " style="background: #000; padding-top: 10px; padding-bottom: 10px;">
            <div class="p-footer-m col-xs-10  col-xs-offset-1 col-sm-3 col-sm-offset-2 col-md-3 col-md-offset-3 text-right color-white ">Hotline: 02-105-4415</div>
            <div class="p-footer-m col-xs-0 col-sm-1 col-md-1  text-center bold">|</div>
            <div class="p-footer-m col-xs-10 col-xs-offset-1 col-sm-5  col-md-4 col-md-offset-0 text-left color-white ">Email: support@topicanative.asia</div>

        </div>

    </footer>

</body>
</html>
