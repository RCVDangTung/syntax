<?php
//get data
$ref = isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : '';
$domain = isset($_SERVER[HTTP_HOST]) ? "http://$_SERVER[HTTP_HOST]" . $_SERVER["REQUEST_URI"] : '';
$id = isset($_GET["id"]) ? $_GET["id"] : "-100";
$preview = isset($_GET["preview"]) ? $_GET["preview"] : "-100";
$code_chanel = isset($_GET["code_chanel"]) ? $_GET["code_chanel"] : "-100";

$link = (!empty($id) && !empty($code_chanel)) ? '?id=' . $id . '&code_chanel=' . $code_chanel : '';


// $link = '?code_chanel=' . $code_chanel . '&id=' . $id;


// if($id != "157" || $code_chanel != "GDN.AT1000.031"){
// 	$link = '?code_chanel=' . $code_chanel . '&id=' . $id;
// }else{
// 	$link = '?code_chanel=GDN.AT1000.031&id=157';
// }



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
	<!-- <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css"/> -->
	<link href="css/style_new.css" rel="stylesheet" type="text/css"/>
	<!--        <link href="css/w3.css" rel="stylesheet" type="text/css"/>-->

	<script src="mol_topmito/js/jquery.min.js" type="text/javascript"></script>
	<script src="mol_topmito/js/jquery.form.js" type="text/javascript"></script>
	<!--<script src="mol_topmito/js/save_contact.js" type="text/javascript"></script>-->
	<script src="js/bootstrap.min.js" type="text/javascript"></script>
	<link type="img/x-icon" href="./img/favicon.png" rel="shortcut icon">

	<link href="plugins/css/owl.carousel.css" rel="stylesheet">
	<link href="plugins/css/owl.theme.css" rel="stylesheet">

	<!-- // <script src="plugins/js/jquery-1.9.1.min.js"></script>  -->
	<script src="plugins/js/owl.carousel.js"></script>
	<style>
		.pd-20{
			padding: 20px 15px;
		}

		#owl-demo .item{
			/*background: #3fbf79;*/
			padding: 30px 0px;
			margin: 10px;
			color: #FFF;
			-webkit-border-radius: 3px;
			-moz-border-radius: 3px;
			border-radius: 3px;
			text-align: center;
		}
	</style>



	<!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
document,'script','//connect.facebook.net/en_US/fbevents.js');

fbq('init', '1003510776366472');
fbq('track', "PageView");</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=1003510776366472&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code -->
	</head>
	<body>
		<section class="container pd-20">
			<header class="row">
				<section class="col-md-3">
					<a href="#"><img src="./img_2/logo.png" class="img-responsive" alt=""></a>
				</section>
				<section class="col-md-6 col-md-offset-3">
					<ul class="nav-left">
						<li class="col-md-3">
							<img src="./img_2/icon-phone.png" alt="" style="min-width: 13px;height: auto;">
							<strong class="font-hotline">02-105-4415</strong>
						</li>
						<li class="col-md-7">
							<img src="./img_2/icon-email.png" alt="" style="min-width: 19px;height: auto;">
							<strong class="font-sup" style="white-space: nowrap;">support@topicanative.asia</strong>
						</li>
						<li class="col-md-2" style="padding-left: 37px;padding-right:0px;">
							<a href="https://line.me/ti/p/%40dsn9535q">
								<img src="./img_2/line.png" alt="" style="max-width:25px;height:auto;">    
							</a>
							<a href="https://www.facebook.com/topicanativethailand/">
								<img src="./img_2/icon-fb.png" alt="" style="max-width:25px;height:auto;">
							</a>    
						</li>
					</ul>
				</section> 
			</header><!-- End header -->
			<section class="content_1">
				<section class="row" style="position: relative;">
					<section class="col-md-12">
						<img src="./img_2/banner16_new_v2.png" alt="" class="img-responsive">    
					</section>
               <!--  <article class="col-md-7" style="position: absolute;">
                    <h4 class="pd-tl-40 fz-40 fb">คุณอยากลองเรียนภาษาอังกฤษออนไลน์ไหม</h4>
                    <h3 class="pd-l-40 fb fz-52">เรียนออนไลน์กับคนนับล้านทั่วโลก</h3>
                    <ul class="col-md-10 col-sm-offset-2">
                        <li class="text-bn">
                            <span>เรียนออนไลน์ตัวต่อตัว <br />
                                อาจารย์เจ้าของภาษา 1 ท่าน ต่อ นักเรียน 1 คน
                            </span>
                        </li>
                        <li class="text-bn">
                            <span>รับประกันคืนเงิน หลังจากสมัครคอร์สเต็ม</span>
                        </li>
                        <li class="text-bn">
                            <span>อาจารย์ผู้ช่วยส่วนตัวสอนตลอดการเรียน</span>
                        </li>
                    </ul>
                </article> -->
                <section class="rs_btn_img" style="position: absolute;bottom: 52px;">
                	<a href="./payment.php<?php echo $link; ?>">
                	<img src="./img_2/button-reg.gif" alt="" class="img-responsive rs-btn max-w-50">
                </a>    
            </section>
        </section>
        <section class="row mg-34">

        	<section class="col-md-12 candle" style="padding: 0px;">
        		
        		<img src="./img_2/shadow_1.png" alt="" class="img-responsive">
        		<div class="text-center" style="padding: 20px 0px;">
                    <img src="./img_2/5-steps_v9.png" alt="" class="">   
                </div>
        		<!-- <h3 class="fb text-center fz-52 rs-fz-30" style="padding-bottom: 20px;color:#810c15;">3 ขั้นตอนในการทดลองเรียนกับ TOPICA Native</h3>
        		<img src="./img_2/3-steps_v2.png" alt="" class="col-md-10 col-md-offset-1 img-responsive" style="margin-bottom: 35px;">	
        		<h4 class="fb text-center" style="padding-bottom: 20px;color:#810c15;font-size:45px;clear:both;">ทดลองเรียนภาษาอังกฤษรูปแบบใหม่ กับครูเจ้าของภาษา แบบตัวต่อตัว</h4> -->
        		<div class="text-center">
        			<img src="./img_2/sa_new_v2.png" alt="" class="">	
        		</div>


        	</section>    
        </section>
        <!-- border-top:1px solid #871719; -->
        <section class="row" style="margin:15px 0px 0px 0px;padding: 45px 0px 0px 0px;">
        	<article class="col-md-6 new mrg-bn">
        		<figure id="info">
        			<a href=""><img src="./img_2/sa_de2_new .png" alt="" class="img-responsive col-sm-12"></a>
        			<figcaption class="detail col-md-12">
        				<div class="col-md-12 title_detail">
        					<p class="fb rs-text-18 rs-text-25" style="font-size: 25px;">เลือกเรียนได้ถึง <strong class="rs-text-23 rs-text-52" style="font-size: 40px;">16 ชั่วโมง ต่อวัน</strong></p>
        					<p class="fb rs-text-18 lh-48" style="font-size: 25px;">ตั้งแต่ 8 โมงเช้า จนถึงเที่ยงคืน</p>
        				</div>
        				<div class="col-md-12 description">
        					<p class="fb rs-text-13 rs-text-25" style="font-size: 25px;color:#000;">
        						ด้วยเทคโนโลยีการเรียนแบบ E-Learning <br>
        						Topica Native จะทำให้คุณได้ฝึกการพูดภาษาอังกฤษ <br>
        						กับอาจารย์เจ้าของภาษาได้มากอย่างที่คุณต้องการ <br>
        						ทุกที่ ทุกเวลา เพื่อการพัฒนาทักษะได้อย่างมีประสิทธิภาพ <br>
        						และหมดห่วงเรื่องปัญหารถติด
        					</p>
        				</div>
        			</figcaption>
        		</figure>
        	</article>
        	<article class="col-md-6 new">
        		<figure id="info">
        			<a href=""><img src="./img_2/sa_de1_new .png" alt="" class="img-responsive col-sm-12"></a>    
        			<figcaption class="detail col-md-12">
        				<div class="col-md-12 title_detail">
        					<h4 class="fb rs-text-52" style="font-size: 40px;line-height: 2.5em;">แผนการเรียนและที่ปรึกษาส่วนตัว</h4>    
        				</div>
        				<div class="col-md-12 description">   
        					<p class="fb rs-text-13 rs-text-25" style="font-size: 25px;color:#000;">
        						ผู้เรียนของ TOPICA Native จะมีที่ปรึกษาส่วนตัว <br>
        						ที่คอยให้คำแนะนำตลอดการเรียนรู้ <br>
        						โดยวิเคราะห์และติดตามตารางเรียนของผู้เรียนอย่างใกล้ชิด<br>
        						อ้างอิงจากการประเมินของอาจารย์เจ้าของภาษา <br>
        						เพื่อให้ผู้เรียนสามารถพัฒนาภาษาอังกฤษได้อย่างรวดเร็ว
        					</p>
        				</div>

        			</figcaption>
        		</figure>
        	</article>
        </section>
    </section> <!-- END content_1 -->
</section> <!-- END container -->
<section class="container-fluid boder-top mrt-30">
	<section class="container" style="padding: 0px;">
		<section class="row">
			<div class="col-md-2 pdt-22">
				<img src="./img_2/next.png" alt="">
			</div>
			<div class="col-md-8">
				<h3 class="text-center fb fz-50 rs-fz-30" style="line-height: 90px;color:#810c15;">อาจารย์เจ้าของภาษา 100%</h3>
			</div>
			<div class="col-md-2 pdt-22">
				<img src="./img_2/next.png" alt="" class="pd-l-0" style="padding-left: 85px;">
			</div>
		</section>
		<section class="info_teacher">
			<section class="row">
				<section class="col-md-4 col-md-offset-2 text-center">
					<img src="./img_2/teacher_1_new.png" alt="" class="img-responsive mg-lr-10" style="margin: 0 20%;">
					<h3 class="fb pd-t-30" style="font-size: 35px;color:#810c15;">กาย ดิกสัน</h3>
					<h4 class="fb" style="font-size: 30px;color:#810c15;">ชาวอังกฤษ</h4>
					<p class="fn fz-26">ผมสามารถสอนนักเรียนได้ใน</p>
					<p class="fn fz-26">ทุกๆระดับและทุกๆช่วงอายุ</p>
				</section>
                   <!--  <section class="col-md-4 text-center">
                        <img src="./img_2/teacher_2.png" alt="" class="img-responsive mg-lr-10" style="margin: 0 20%;">
                        <h3 class="fb pd-t-30" style="font-size: 35px;color:#810c15;">เดวิด เดวิน</h3>
                        <h4 class="fb" style="font-size: 30px;color:#810c15;">ชาวอเมริกัน</h4>
                        <p class="fn fz-26">ผมมุ่งเน้นเรื่องการออกเสียงเป็นส่วนใหญ่</p>
                        <p class="fn fz-26">นักเรียนหลายคน</p>  
                        <p class="fn fz-26">จะได้พัฒนาอย่างรวดเร็ว</p>
                        <p class="fn fz-26">หลังจากเรียนได้ 1 เดือน</p>
                    </section> -->
                    <section class="col-md-4 text-center">
                    	<img src="./img_2/teacher_3_new.png" alt="" class="img-responsive mg-lr-10" style="margin: 0 20%;">  
                    	<h3 class="fb pd-t-30" style="font-size: 35px;color:#810c15;">เอ็กเบิร์ท เวลท์แมน</h3>
                    	<h4 class="fb" style="font-size: 30px;color:#810c15;">ชาวฮอลแลนด์</h4>
                    	<p class="fn fz-26">ผมรักที่จะสร้างโอกาส</p>
                    	<p class="fn fz-26">ให้บรรดานักเรียน</p>
                    	<p class="fn fz-26">เพื่อขยายวงความรู้และพูดคุย</p>
                    	<p class="fn fz-26">ในหัวข้อที่แตกต่างๆไปทุกวัน</p>
                    </section>
                </section>
            </section>    
        </section>
    </section>
    <section class="container-fluid border-top-1 mrt-30">
    	<section class="container">
    		<section class="info_topica">
    			<section class="row">
    				<section class="col-md-5">
    					<h3 class="fb fz-45 pdb-20 rs-fz-30" style="color:#810c15;">ทำความรู้จักกับ TOPICA</h3>
    					<ul>
    						<li class="item_n">
    							<span>TOPICA องค์กรชั้นนำด้านการศึกษาออนไลน์</span><br/>
    							<span>ในเอเชียตะวันออกเฉียงใต้</span>    
    						</li>
    						<li class="item_n">
    							<span>เทคโนโลยีชั้นสูง</span>
    						</li>
    						<li class="item_n">
    							<span>ผู้เรียนกว่าพันคนในเอเชียตะวันออกเฉียงใต้</span>
    						</li>
    					</ul>
    				</section>

    				<section class="col-md-6 col-md-offset-1 embed-responsive pdb-60" style="padding-bottom: 30%;">
    					<iframe class="embed-responsive-item" style="width: 100%;height: 100%;" src="https://www.youtube.com/embed/o8qeft4UHEE" frameborder="0" allowfullscreen=""></iframe>
    				</section>
    			</section>
    		</section>
    	</section>    
    </section>

    <section class="container-fluid bg-ebebeb hidden-xs">
    	<section class="container">
    		<h2 class="fb text-center fz-52 rs-fz-30" style="padding-top: 30px;color:#810c15;">TOPICA ในสื่อต่างๆ</h2>
    	</section>
    	<section>
    		<div id="demo" style="position: relative;">
    			<div class="container">
    				<div class="row">
    					<div class="span12">
    						<div id="owl-demo" class="owl-carousel">
    							<div class="item">
    								<div class="col-md-4" style="padding-bottom: 10px;">
    									<a href="http://www.prachachat.net/news_detail.php?newsid=1444116011"><img src="./img_2/col-2.png" alt="" class="img-responsive"></a>    
    								</div>
    								<div style="background-color: #FFF;height: 147px;clear: both;color: #000;border-radius: 3px;">
    									<div class="col-md-6 text-left" style="padding-top: 30px;padding-left: 38px;">
    										<a href="http://www.prachachat.net/news_detail.php?newsid=1444116011" target="_blank" style="color: #000;">
    											<h1 class="fb fz-26 fz-14">CEO ของ Lenovo</h1>
    											<p class="fb fz-26 fz-14" style="white-space: nowrap;">เริ่มเรียนภาษาอังกฤษตอนอายุ 40</p>    
    										</a>

    									</div>
    									<div class="col-md-4 col-md-offset-2" style="margin: 25px 0px 0px 90px;">
    										<a href="http://www.prachachat.net/news_detail.php?newsid=1444116011"><img src="./img_2/slide-p1.png" alt="" class="img-responsive"></a>
    									</div>    
    								</div>   
    							</div>
    							<div class="item">
    								<div class="col-md-4" style="padding-bottom: 24px;">
    									<a href="http://pantip.com/topic/34193173"><img src="./img_2/pante.png" alt="" class="img-responsive"></a>
    								</div>
    								<div style="background-color: #FFF;height: 147px;clear: both;color: #000;border-radius: 3px;">
    									<div class="col-md-6 text-left" style="padding-top: 20px;padding-left: 40px;">
    										<a href="http://pantip.com/topic/34193173" target="_blank" style="color: #000;">
    											<h1 class="fb fz-26 fz-14">แชร์ประสบการณ์!!!</h1>
    											<p class="fb fz-26 fz-14">เรียนภาษาอังกฤษกับ </p>
    											<p class="fb fz-26 fz-14">Topica Native</p>        
    										</a>
    									</div>
    									<div class="col-md-4 col-md-offset-2" style="margin: 25px 0px 0px 90px;">
    										<a href="http://pantip.com/topic/34193173" target="_blank"><img src="./img_2/slide-p2.png" alt="" class="img-responsive"></a>
    									</div>    
    								</div>
    							</div>
    							<div class="item">
    								<div class="col-md-4" style="padding-bottom: 3px;">
    									<a href="http://www.khaosod.co.th/view_newsonline.php?newsid=1429169253"><img src="./img_2/khaosod.png" alt="" class="img-responsive" style="max-height: 50px;"></a>    
    								</div>
    								<div style="background-color: #FFF;height: 147px;clear: both;color: #000;border-radius: 3px;">
    									<div class="col-md-6 text-left" style="padding-top: 20px;padding-left: 40px;">
    										<a href="http://www.khaosod.co.th/view_newsonline.php?newsid=1429169253" target="_blank" style="color: #000;">
    											<h1 class="fb fz-26 fz-14">บุฟเฟ่ต์ภาษาอังกฤษ</h1>
    											<p class="fb fz-26 fz-14">เรียน 16 ชั่วโมงต่อวัน</p>
    											<p class="fb fz-26 fz-14">กับครูเจ้าของภาษา</p>    
    										</a>
    									</div>
    									<div class="col-md-4 col-md-offset-2" style="margin: 25px 0px 0px 90px;">
    										<a href="http://www.khaosod.co.th/view_newsonline.php?newsid=1429169253" target="_blank"><img src="./img_2/slide3.jpg" alt="" class="img-responsive"></a>
    									</div>    
    								</div>
    							</div>
    							<div class="item">
    								<div class="col-md-4" style="padding-bottom: 10px;">
    									<a href="http://www.matichon.co.th/news_detail.php?newsid=1433757635">
    										<img src="./img_2/logo-matichon-online.gif" alt="" class="img-responsive">    
    									</a>
    								</div>
    								<div style="background-color: #FFF;height: 147px;clear: both;color: #000; border-radius: 3px;">
    									<div class="col-md-6 text-left" style="padding-top: 20px;padding-left: 40px;">
    										<a href="http://www.matichon.co.th/news_detail.php?newsid=1433757635" target="_blank" style="color: #000;">
    											<h1 class="fb fz-26 fz-14" style="white-space: nowrap;">ภาษาอังกฤษคือกุญแจสู่ความสำเร็จ</h1>
    											<p class="fb fz-26 fz-14">วิกรม กรมดิษฐ</p>
    										</a>
    									</div>
    									<div class="col-md-4 col-md-offset-2" style="margin: 25px 0px 0px 90px;">
    										<a href="http://www.matichon.co.th/news_detail.php?newsid=1433757635" target="_blank"><img src="./img_2/slide4.jpg" alt="" class="img-responsive"></a>
    									</div>    
    								</div>
    							</div>
    							<div class="item">
    								<div class="col-md-4" style="padding-bottom: 3px;">
    									<a href="http://www.khaosod.co.th/view_newsonline.php?newsid=1443681923"><img src="./img_2/khaosod.png" alt="" class="img-responsive" style="max-height: 50px;"></a>   
    								</div>
    								<div style="background-color: #FFF;height: 147px;clear: both;color: #000;border-radius: 3px;">
    									<div class="col-md-6 text-left" style="padding-top: 20px;padding-left: 40px;">
    										<a href="http://www.khaosod.co.th/view_newsonline.php?newsid=1443681923" target="_blank" style="color: #000;">
    											<h1 class="fb fz-26 fz-14" style="white-space: nowrap;">สถานการณ์ที่ไม่คาดคิดในต่างประเทศ</h1>
    											<p class="fb fz-26 fz-14">ของคนที่ไม่ใช่เจ้าของภาษา</p>
    										</a>    
    									</div>
    									<div class="col-md-4 col-md-offset-2" style="margin: 25px 0px 0px 90px;">
    										<a href="http://www.khaosod.co.th/view_newsonline.php?newsid=1443681923" target="_blank"><img src="./img_2/slide5.jpg" alt="" class="img-responsive"></a>
    									</div>    
    								</div>
    							</div>
    						</div>
    					</div>
    				</div>
    			</div>
    		</div>
    	</section>
    </section>
    <section class="container-fluid border-top-1">   
    	<section class="step">
    		<section class="row">
    			<!-- <h3 class="fb text-center fz-52 rs-fz-30" style="padding-bottom: 20px;color:#810c15;">3 ขั้นตอนในการทดลองเรียนกับ TOPICA Native</h3> -->
    			<section class="col-md-12">
    				<!-- height: 100px;width: 90%; -->
    				<!-- <img src="./img_2/3_steps_new.png" alt="" class="col-md-10 col-md-offset-1 img-responsive" style="margin-bottom: 35px;">
    				<p class="fb text-center rs-text-18" style="font-size: 30px;clear: both;">ทดลองเรียนภาษาอังกฤษรูปแบบใหม่</p>
    				<p class="fb text-center rs-text-18" style="font-size: 30px;">กับครูเจ้าของภาษา แบบตัวต่อตัว</p> -->
    				<p class="text-center col-md-offset-4">
    					<a href="./payment.php<?php echo $link; ?>"><img src="./img_2/unnamed.gif" alt="" class="img-responsive"></a>    
    				</p>

    			</section>
    		</section>    
    	</section>
    </section>

    <footer class="container-fluid mr-top-30">

    	<div class="row mr-top-20">
    		<div class="col-xs-4 col-sm-0 col-md-4 hidden-sm hidden-xs">
    			<img src="img/logo.png" alt="" style="width: 100%;" class="img-responsive">
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
    					TOPICA Thailand: 2 Ploenchit Center, Bangkok<br>
    					TOPICA ingapore: One Raffles Place Tower One 048616 
    				</p>
    				<p class="p-footer col-xs-offset-0 col-xs-12 col-md-6 col-md-offset-0 col-sm-6  col-sm-offset-0 p-sm-u">
    					TOPICA Philippines: Quezon City, 1109 Philippines<br>
    					TOPICA Vietnam: 75 Phuong Mai, Dong Da, Hanoi
    				</p>
    			</div>
    		</div>


    	</div>

    	<div class="row mr-top-20 " style="background: #000; padding-top: 10px; padding-bottom: 10px;">
    		<div class="p-footer-m col-xs-10  col-xs-offset-1 col-sm-3 col-sm-offset-2 col-md-3 col-md-offset-3 text-right color-white text_c">Hotline: 02-105-4415</div>
    		<div class="p-footer-m col-xs-0 col-sm-1 col-md-1  text-center bold color-white hidden-xs">|</div>
    		<div class="p-footer-m col-xs-10 col-xs-offset-1 col-sm-5  col-md-4 col-md-offset-0 text-left color-white ">Email: support@topicanative.asia</div>

    	</div>

    </footer>



    <script>
    	$(document).ready(function() {
    		var owl = $("#owl-demo"),
    		status = $("#owlStatus");



    		owl.owlCarousel({
    			autoPlay: 3000,
    			items : 2,
    			navigation : true,
    			afterAction : afterAction
    		});

    		function updateResult(pos,value){
    			status.find(pos).find(".result").text(value);
    		}

    		function afterAction(){
    			updateResult(".owlItems", this.owl.owlItems.length);
    			updateResult(".currentItem", this.owl.currentItem);
    			updateResult(".prevItem", this.prevItem);
    			updateResult(".visibleItems", this.owl.visibleItems);
    			updateResult(".dragDirection", this.owl.dragDirection);
    		}

    	});
    </script>

</body>
</html>
