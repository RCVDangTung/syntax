<?php

class successfull extends CI_Controller {

    var $tcoin_need = 100000;

    public function __construct() {
        date_default_timezone_set('Asia/Ho_Chi_Minh'); //setup lai timezone
        parent::__construct();
    }

    public function index() {
        $data = $this->input->post();

        echo "<pre>";
        var_dump($data);
        exit();

        // || !isset($data['checksum_topica']) || !$data['checksum_topica']
        if (!$data || !isset($data['RESPONSE']) || !$data['RESPONSE'] || $data['RESPONSE'] != '00') {
//        if (!$data && $data['RESPONSE'] != '00') {
            // echo 'Du lieu khong hop le';
            // exit();
            $data['link_img'] = base_url() . 'themes/default/images/Failed_2.5.jpg';
            $this->load->view('default/home/ketqua', $data);
            return FALSE;
        } else {
            $urlReturn = 'http://thailand.payment.topicanative.asia/success_invoice/index';
            header("Location: " . $urlReturn);
        }
        // if($data || isset($data['RESPONSE']) || $data['RESPONSE'] || $data['RESPONSE'] == '00'){
//        if ($data['RESPONSE'] && $data['RESPONSE'] == '00') {
//            $urlReturn = 'http://thailand.payment.topicanative.asia/success_invoice/index';
//            header("Location: " . $urlReturn);
//        }
        // $this->load->model('m_contact_submit');
        // $this->load->model('m_contact_deposit');
        // $this->load->model('m_contact');
        // $deposit_checksum_code = $this->m_contact_deposit->get_one(array('m.checksum_topica' => $data['checksum_topica']));
        // // var_dump($deposit_checksum_code);
        // // exit();
        // if (!$deposit_checksum_code) {
        //     $data['link_img'] = base_url() . 'themes/default/images/Failed_2.5.jpg';
        //     $this->load->view('default/home/ketqua', $data);
        //     return FALSE;
        // }
        // $contact = $this->m_contact_submit->get_one(array('m.contact_id' => $deposit_checksum_code->contact_id));
        // if ($contact) {
        //     //THUC HIEN TAO TAI KHOAN CHO HOC VIEN
        //     $contact_id = $data['contact_id'] = $contact->contact_id;
        //     $user_info = $contact;
        //     $data['phone'] = $user_info->phone;
        //     $data['actually_paid'] = ($deposit_checksum_code->value < $this->tcoin_need) ? $this->tcoin_need : $deposit_checksum_code->value;
        //     $data['full_name'] = $user_info->name;
        //     $data['email'] = $user_info->email;
        //     $arr_name = explode(" ", $data['full_name']);
        //     $data['first_name'] = $arr_name[count($arr_name) - 1];
        //     $data['last_name'] = substr($data['full_name'], 0, strlen($data['full_name']) - strlen($arr_name[count($arr_name) - 1]) - 1);
        //     if (!$data['last_name']) {
        //         $data['last_name'] = $data['first_name'];
        //     }
        //     $data['user_name'] = 'free_test' . strtolower($contact_id) . '@gmail.com';
        //     // Tao tai khoan ngan hang payment =====================================
        //     $this->load->model('m_sys_payment');
        //     $param_payment = array(
        //         "ContactId" => $contact_id,
        //         "LmsId" => NULL,
        //         "UserName" => $data['full_name'],
        //         "UserPhone" => $data['phone'],
        //         "UserAtmCard" => NULL,
        //         "UserEmail" => $data['user_name'],
        //         "UserLoginName" => $data['user_name'],
        //         "UserRole" => "student",
        //         "UserArchetype" => "student",
        //     );
        //     if (!$user_info->payment) {
        //         $result_payment = $this->m_sys_payment->add($param_payment);
        //         if (!is_object($result_payment)) {
        //             $data_return["state"] = 0; /* state = 0 : dữ liệu không hợp lệ */
        //             $data_return["msg"] = "Có lỗi, vui lòng thử lại sau. Object";
        //             echo json_encode($data_return);
        //             return FALSE;
        //         } else if ($result_payment->status == FALSE) {
        //             $data_return["state"] = 0; /* state = 0 : dữ liệu không hợp lệ */
        //             $data_return["msg"] = "Có lỗi, vui lòng thử lại sau. Status FALSE" . $result_payment->status_code;
        //             echo json_encode($data_return);
        //             return FALSE;
        //         }
        //         $this->m_contact->update(array('contact_id' => $contact_id), array('payment' => 'OK'));
        //     }
        //     // Ket thuc tao tai khoan payment ======================================
        //     // Deposit ngay sau tao tai khoan
        //     $otherInfo = array(
        //         "ContactId" => $contact_id,
        //         'lang' => isset($data['lang']) ? $data['lang'] : 'th',
        //         "Value" => $data['actually_paid'],
        //         'TransactionBy' => 'AUTOSALE',
        //     );
        //     $param_deposit = array(
        //         "ContactId" => $contact_id,
        //         "UserName" => $data['full_name'],
        //         "UserPhone" => $data['phone'],
        //         "UserEmail" => $data['user_name'],
        //         "Value" => $data['actually_paid'],
        //         "Reason" => 'Mua goi hoc thu',
        //         "BillCode" => time(),
        //         "OtherInfo" => json_encode($otherInfo),
        //         "Time" => date('Y-m-d'),
        //         "DisableWarning" => true,
        //         "AutoTransfer" => true,
        //         'Country' => 'VN',
        //         'TransactionOwner' => 'AUTOSALE',
        //         'Package' => 'TCHT',
        //     );
        //     $result_deposit = $this->m_sys_payment->deposit($param_deposit);
        //     $this->m_contact->update(array('contact_id' => $contact_id), array('payment' => 'OK', 'tcoin' => $data['actually_paid'] + $contact->tcoin));
        //     //THUC HIEN MUA GOI HOC
        //     $this->load->model("m_sys_package");
        //     // Mua goi hoc
        //     $param_pricing = array(
        //         'data' => array(
        //             array(
        //                 'user_info' => array(
        //                     'contact_id' => $contact_id,
        //                     'username' => $data['full_name'],
        //                     'useremail' => $data['user_name'],
        //                 ),
        //                 'buyer_info' => array(
        //                     'buyer_id' => 'AUTOSALE',
        //                     'buyer_name' => 'AUTOSALE',
        //                 ),
        //                 'price_info' => array(
        //                     array(
        //                         'cat_code' => 'TCHT',
        //                         'price' => $data['actually_paid'],
        //                         'actual_price' => $data['actually_paid']
        //                     )
        //                 ),
        //                 'invoice_info' => array(
        //                     'reason' => 'Mua goi hoc thu'
        //                 ),
        //                 'course_id' => 2,
        //                 'has_active' => false
        //             )
        //         )
        //     );
        //     $result_pricing = $this->m_sys_package->pricing($param_pricing);
        //     if (!is_object($result_pricing)) {
        //         $data_return["state"] = 0;
        //         $data_return["msg"] = "Chưa mua được, vui lòng thử lại sau! APIER_NOTANOJ";
        //         echo json_encode($data_return);
        //         return FALSE;
        //     } elseif ($result_pricing->status == false) {
        //         $data_return["state"] = 0;
        //         $data_return["msg"] = $result_pricing->msg;
        //         echo json_encode($data_return);
        //         return FALSE;
        //     } elseif ($result_pricing->status == true) {
        //         if ($result_pricing->data[0]->status == FALSE) {
        //             $data_return["state"] = 0;
        //             $data_return["msg"] = $result_pricing->data[0]->msg;
        //             echo json_encode($data_return);
        //             return FALSE;
        //         }
        //     }
        //     //ACTIVE GOI
        //     $param_active_package = array(
        //         'product_id' => isset($result_pricing->data[0]->data->product_id[0]) ? $result_pricing->data[0]->data->product_id[0] : 0,
        //         'starttime' => time(),
        //         'timecreated' => time(),
        //     );
        //     $result_active_package = $this->m_sys_package->activePackage($param_active_package);
        //     // =========================== Bat dau tao user tren LMS ====================================
        //     $student_email = $data['email'];
        //     $student_phone = $data['phone'];
        //     $user_name_lms = trim('astl' . substr(filter_var($contact_id, FILTER_SANITIZE_NUMBER_INT), -6));
        //     $password = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6);
        //     $this->load->model("m_rest_client_lms");
        //     $rest_adduseronly = Rest_Client_Factory::create('add_user');
        //     $rest_adduseronly->add_params(array(
        //         'users' => array(
        //             array(
        //                 'username' => $user_name_lms,
        //                 'firstname' => $data['first_name'],
        //                 'lastname' => $data['last_name'],
        //                 'email' => $data['user_name'],
        //                 'phone1' => $student_phone,
        //                 'password' => $password,
        //                 'lang' => 'th',
        //                 'customfields' => array(
        //                     array(
        //                         'type' => 'contactid',
        //                         'value' => $contact_id
        //                     ),
        //                     array(
        //                         'type' => 'currentlevel',
        //                         'value' => 'basic',
        //                     ),
        //                     array(
        //                         'type' => 'package',
        //                         'value' => 0,
        //                     ),
        //                     array(
        //                         'type' => 'packageparent',
        //                         'value' => 'TAAM',
        //                     ),
        //                     array(
        //                         'type' => 'studenttype',
        //                         'value' => 'AUTOSALE',
        //                     ),
        //                 )
        //             )
        //         ),
        //         'enrol' => array(
        //             'timestart' => time(),
        //             'timeend' => 0,
        //             'courseid' => 2,
        //             'role' => 'student',
        //         )
        //     ));
        //     if (!$user_info->lms_account) {
        //         /**
        //          * id, user_name
        //          */
        //         $result_add_user = $rest_adduseronly->send();
        //         if ($result_add_user && isset($result_add_user->exception)) {
        //             $debug = '';
        //             $debug = isset($result_add_user->debuginfo) ? $result_add_user->debuginfo : '';
        //             $data_return["state"] = 0;
        //             $data_return["msg"] = $result_add_user->message . ' - ' . $debug;
        //             $data_return["redirect"] = "";
        //             echo json_encode($data_return);
        //             return FALSE;
        //         } else if ($result_add_user == NULL) {
        //             $data_return["state"] = 0;
        //             $data_return["msg"] = "Chết API, chưa tạo được tài khoản";
        //             $data_return["redirect"] = "";
        //             echo json_encode($data_return);
        //             return FALSE;
        //         }
        //         $this->m_contact->update(array('contact_id' => $contact_id), array('lms_account' => $user_name_lms, 'lms_password' => $password));
        //     }
        //     $this->m_contact_deposit->update($deposit_checksum_code->id, array(
        //         'order_id' => isset($data['order_id']) ? $data['order_id'] : '',
        //         'payment_type' => isset($data['payment_type']) ? $data['payment_type'] : '',
        //         'transaction_id' => isset($data['transaction_id']) ? $data['transaction_id'] : $deposit_checksum_code->transaction_id,
        //         'transaction_status' => isset($data['transaction_status']) ? $data['transaction_status'] : '',
        //         'checksum' => isset($data['checksum']) ? $data['checksum'] : '',
        //         'checksum_topica' => '',
        //         'state' => 2//da duoc verify
        //     ));
        //     $data['user'] = $this->m_contact_submit->get_one(array('m.contact_id' => $contact_id));
        //     $this->m_contact_submit->update($contact->id, array('payment' => 'OK', 'auto_flag' => 2));
        //     $tcoin = $contact->tcoin - $this->tcoin_need;
        //     $this->m_contact->update(array('contact_id' => $contact_id), array('level_care' => 'A5', 'tcoin' => $tcoin));
        //     // echo "<pre>";
        //     // echo 'ssss';
        //     // exit;
        //     // //gui email
        //     // $content_email = $this->load->view('default/email/EM03_TL', $data, true);
        //     // $subject_email = 'คู่มือการทดลองเรียน';
        //     // $this->send_email('', '', $data['user']->email, $content_email, $subject_email);
        //     // //gui sms
        //     // $content_sms = 'ลงทะเบียนเวลาการทดสอบยืนยัน'.$data['user']->time.' ngay '. date('m/d/Y',strtotime($data['user']->date)) .'. Tai khoan: '. $data['user']->lms_account .', mat khau: '. $data['user']->lms_password .'. ตรวจสอบอีเมลของคุณเพื่อดูรายละเอียด. Hotline: 02-105-4415';
        //     // $this->send_sms($data['user']->phone, $content_sms);
        //     // $this->send_sms('0982160788', $data['user']->name . ' ' . $content_sms);
        // } else {
        //     // echo "Fail xxx";
        //     // exit();
        // }
        // // $data['msg'] = 'Giao dich thanh cong. Vui long check email de lay thong tin huong dan hoc thu';
        // $data['msg'] = 'การชำระเงินสำเร็จ ! กรุณาตรวจสอบอีเมล์ของคุณสำหรับคำแนะนำในการเข้าห้องเรียน';
        // $data['link_img'] = base_url() . '/themes/default/images/Thankyou_2.5.jpg';
        // $this->load->view('default/home/ketqua', $data);
    }

//     public function send_email($emailReply = 'topica.native@topica.edu.vn', $nameReply = 'TOPICA NATIVE', $emailTo = '', $content = '', $subject = '', $title = "TOPICA NATIVE", $attach_files = NULL) {
//         $config['protocol'] = 'smtp';
//         $config['smtp_host'] = 'ssl://112.78.5.200';
//         $config['smtp_port'] = '465';
//         $config['smtp_timeout'] = '600';
// //        $config['smtp_user'] = $emailFrom;
// //        $config['smtp_pass'] = $emailPass;
//         $config['smtp_user'] = 'topica-native@topmito.edu.vn';
//         $config['smtp_pass'] = 'Auto.sale@123';
//         $config['charset'] = 'utf-8';
//         $config['newline'] = "\r\n";
//         $config['mailtype'] = 'html'; // or html
//         $config['validation'] = TRUE; // bool whether to validate email or not
//         $this->load->library("email");
//         $this->email->initialize($config);
//         $this->email->from('topica-native@topmito.edu.vn', 'TOPICA NATIVE');
//         $this->email->to($emailTo);
//         // $this->email->to('tungnd@topica.edu.vn');
//         $this->email->cc('support@topicanative.asia');
//         // $this->email->bcc('tungnd@topica.edu.vn');
//         $this->email->subject($subject);
//         $this->email->message($content);
//         // if ($emailReply && $nameReply) {
//         $this->email->reply_to('support@topicanative.asia', 'TOPICA NATIVE');
//         // }
//         if (is_array($attach_files)) {
//             foreach ($attach_files as $file_path) {
//                 $this->email->attach($file_path);
//             }
//         }
//         $check = $this->email->send();
//         $this->email->clear(TRUE);
// //        echo $this->email->print_debugger();
//         return $check;
//     }
//     public function send_sms($phone, $msg) {
//         $this->config->load('api_config');
//         $config = $this->config->item('api_server_smsservice_th');
//         // var_dump($config);exit;
//         $this->load->library("Rest_Client");
//         $this->rest_client->initialize($config);
//         // Load library send sms
//         // $uri = "sms_send_queue/addMultiSMS";
//         $uri = "sms_send_queue/sendFastMultiSMS";
//         $param = Array(
//             'data' =>array(
//                 'list_phone' => array($phone),
//                 'content' => $msg,
//                 )    
//             );
//         $result = $this->rest_client->post($uri, $param);
//         // $result = $this->rest_client->debug($uri, $param);
//         // var_dump($result);exit;
//         return $result;
//     }
}
