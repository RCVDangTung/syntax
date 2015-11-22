<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cronjob extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('m_contact_submit');
    }

    public function email_sms_01() {
        $array = array();
        $list_contact = $this->m_contact_submit->get_list(array('m.date >=' => '2015-09-24 20:00:00'));
        foreach ($list_contact as $key => $value) {
            if (!$value->payment) {
                $array[$value->contact_id] = $value;
            }
        }
        echo "<pre>";
        var_dump($array);
        exit;
        if (count($array)) {
            foreach ($array as $key => $value) {
                $data['user'] = $value;
                $content_email = $this->load->view('default/email/EM01', $data, true);
                $subject_email = 'Xác nhận thông tin học thử';
                $this->send_email('', '', $data['user']->email, $content_email, $subject_email);

                $sms_info = array(
                    "sendTo" => $value->phone,
                    "content" => 'cam on ban da dang ky hoc thu. Vui long kiem tra EMAIL de nhan thong tin TAI KHOAN HOC THU. Hotline: 0981228979'
                );
                $data_send_sms[] = $sms_info;
            }

            if (count($data_send_sms)) {
                $this->send_multi_sms($data_send_sms);
            }
            echo "<pre>";
            var_dump($data_send_sms);
            exit;
        }
    }

    public function email_sms_02() {
        $array = array();
        $list_contact = $this->m_contact_submit->get_list(array('m.date' => date('Y-m-d')));
        foreach ($list_contact as $key => $value) {
            if (!$value->payment && (intval($value->time) < date('H'))) {
                $array[$value->contact_id] = $value;
            }
        }
        if (count($array)) {
            foreach ($array as $key => $value) {
                $data['user'] = $value;
                $content_email = $this->load->view('default/email/EM02', $data, true);
                $subject_email = 'Mời đăng ký buổi học thử mới';
                $this->send_email('', '', $data['user']->email, $content_email, $subject_email);

                $sms_info = array(
                    "sendTo" => $value->phone,
                    "content" => 'thong bao lop HOC TRUC TUYEN cua ban da QUA HEN. Vui long kiem tra EMAIL de dang ky lai. Hotline: 0981228979'
                );
                $data_send_sms[] = $sms_info;
            }

            if (count($data_send_sms)) {
                $this->send_multi_sms($data_send_sms);
            }
            echo "<pre>";
            var_dump($data_send_sms);
            exit;
        }
    }

    public function email_sms_04() {
        $array = array();
        $list_contact = $this->m_contact_submit->get_list(array('m.date >=' => date('Y-m-d')));
        foreach ($list_contact as $key => $value) {
            if ($value->payment && ((strtotime($value->date . ' ' . $value->time) - strtotime(date('Y-m-d'))) < 86400)) {
                $array[$value->contact_id] = $value;
            }
        }
        // echo "<pre>";
        // var_dump($array);exit;
        if (count($array)) {
            foreach ($array as $key => $value) {
                $data['user'] = $value;
                $content_email = $this->load->view('default/email/EM04', $data, true);
                // $content_email = $this->load->view('default/email/EM04', $data);
                // return TRUE;
                $subject_email = 'Anh/chị đã kiểm tra kĩ thuật chưa?';
                $this->send_email('', '', $data['user']->email, $content_email, $subject_email);

                $sms_info = array(
                    "sendTo" => $value->phone,
                    "content" => 'moi ban KIEM TRA KY THUAT. Day la buoc bat buoc de DAM BAO CHAT LUONG buoi hoc. Hotline: 0981228979'
                );
                $data_send_sms[] = $sms_info;
                $sms_info = array(
                    "sendTo" => '0982160788',
                    "content" => 'moi ' . $value->name . ' KIEM TRA KY THUAT. Day la buoc bat buoc de DAM BAO CHAT LUONG buoi hoc. Hotline: 0981228979'
                );
                $data_send_sms[] = $sms_info;
                // $sms_info = array(
                //     "sendTo" => '0936109818',
                //     "content" => 'moi '. $value->name .' KIEM TRA KY THUAT. Day la buoc bat buoc de DAM BAO CHAT LUONG buoi hoc. Hotline: 0981228979'
                // );
                // $data_send_sms[] = $sms_info;
            }

            if (count($data_send_sms)) {
                $this->send_multi_sms($data_send_sms);
            }
            echo "<pre>";
            var_dump($data_send_sms);
            exit;
        }
    }

    public function email_sms_05() {
        $array = array();
        $list_contact = $this->m_contact_submit->get_list(array('m.date' => date('Y-m-d')));
        foreach ($list_contact as $key => $value) {
            if ($value->payment) {
                $array[$value->contact_id] = $value;
            }
        }
        // echo "<pre>";
        // var_dump($array);exit;
        if (count($array)) {
            foreach ($array as $key => $value) {
                $data['user'] = $value;
                // $content_email = $this->load->view('default/email/EM04', $data, true);
                $content_email = $this->load->view('default/email/EM05', $data);
                return TRUE;
                //       $subject_email = 'Anh/chị đã kiểm tra kĩ thuật chưa?';
                //       $this->send_email('', '', $data['user']->email, $content_email, $subject_email);
                // $sms_info = array(
                //              "sendTo" => $value->phone,
                //              "content" => 'moi ban KIEM TRA KY THUAT. Day la buoc bat buoc de DAM BAO CHAT LUONG buoi hoc. Hotline: 0981228979'
                //          );
                //          $data_send_sms[] = $sms_info;
                //          $sms_info = array(
                //              "sendTo" => '0982160788',
                //              "content" => 'moi '. $value->name .' KIEM TRA KY THUAT. Day la buoc bat buoc de DAM BAO CHAT LUONG buoi hoc. Hotline: 0981228979'
                //          );
                //          $data_send_sms[] = $sms_info;
            }

            // if(count($data_send_sms)){
            //  $this->send_multi_sms($data_send_sms);
            // }
            echo "<pre>";
            var_dump($data_send_sms);
            exit;
        }
    }

    public function email_sms_08() {
        $array = array();
        $list_contact = $this->m_contact_submit->get_list(array('m.contact_id' => '20150916000260AS'));
        foreach ($list_contact as $key => $value) {
            // if ($value->payment && $value->contact_id =='20150914000096AS') {
            $array[$value->contact_id] = $value;
            // }
        }
        // echo "<pre>";
        // var_dump($array);exit;
        if (count($array)) {
            foreach ($array as $key => $value) {
                $data['user'] = $value;
                $content_email = $this->load->view('default/email/EM08', $data, true);
                $subject_email = 'Tổng hợp kết quả học tập và tư vấn lộ trình';
                $this->send_email('', '', $data['user']->email, $content_email, $subject_email);

                $sms_info = array(
                    "sendTo" => $value->phone,
                    "content" => 'chuc mung ban da co KET QUA HOC THU. Vui long kiem tra EMAIL de xem chi tiet. Hotline: 0981228979'
                );
                $data_send_sms[] = $sms_info;
            }

            if (count($data_send_sms)) {
                $this->send_multi_sms($data_send_sms);
            }
            echo "<pre>";
            var_dump($data_send_sms);
            exit;
        }
    }

    public function email_sms_09() {
        $array = array();
        $list_contact = $this->m_contact_submit->get_list(array('c.level_study !=' => '', 'm.payment' => 'OK'));
        foreach ($list_contact as $key => $value) {
            // if ($value->payment && $value->contact_id =='20150914000096AS') {
            $array[$value->contact_id] = $value;
            // }
        }
        // echo "<pre>";
        // var_dump($array);exit;
        if (count($array)) {
            foreach ($array as $key => $value) {
                $data['user'] = $value;
                $content_email = $this->load->view('default/email/EM09', $data, true);
                $subject_email = 'Bảng học phí và các hình thức thanh toán';
                $this->send_email('', '', $data['user']->email, $content_email, $subject_email);

                $sms_info = array(
                    "sendTo" => $value->phone,
                    "content" => 'gui ban BANG HOC PHI. Vui long kiem tra EMAIL de xem chi tiet. Hotline: 0981228979'
                );
                $data_send_sms[] = $sms_info;
            }

            if (count($data_send_sms)) {
                $this->send_multi_sms($data_send_sms);
            }
            echo "<pre>";
            var_dump($data_send_sms);
            exit;
        }
    }

    public function email_sms_12() {
        $array = array();
        $this->load->model('m_contact');
        $list_contact = $this->m_contact_submit->get_list(array('c.level_care' => 'A3', 'email_flag' => 0), 20);
        /*
          var_dump($list_contact);

          die(); */
        foreach ($list_contact as $key => $value) {
            // if ($value->payment && $value->contact_id =='20150914000096AS') {
            $array[$value->contact_id] = $value;
            // }
        }
        /* echo "<pre>";
          var_dump($array);exit; */
        if (count($array)) {
            foreach ($array as $key => $value) {
                $data['user'] = $value;
                $content_email = $this->load->view('default/email/EM12', $data, true);
                $subject_email = 'HỌC TIẾNG ANH TRỰC TUYẾN DÀNH CHO NGƯỜI BẬN RỘN';
                $this->send_email('', '', $data['user']->email, $content_email, $subject_email);
                $this->m_contact->update(array('contact_id' => $key), array('email_flag' => 1));
                // $sms_info = array(
                //     "sendTo" => $value->phone,
                //     "content" => 'gui ban BANG HOC PHI. Vui long kiem tra EMAIL de xem chi tiet. Hotline: 0981228979'
                // );
                // $data_send_sms[] = $sms_info;
            }

            // if(count($data_send_sms)){
            //     $this->send_multi_sms($data_send_sms);
            // }
            // echo "<pre>";
            // var_dump($data_send_sms);exit;
        }
        echo "thanh cong";
    }

    public function email_sms_14() {
        $array = array();
        $this->load->model('m_contact');
        $list_contact = $this->m_contact_submit->get_list(array('c.level_care' => 'A3', 'email_flag' => 0), 20);
		/* echo "<pre>";
        var_dump($list_contact);
        exit(); */
        
          /* var_dump($list_contact);
          die(); */
        foreach ($list_contact as $key => $value) {
            // if ($value->payment && $value->contact_id =='20150914000096AS') {
            $array[$value->contact_id] = $value;
            // }
        }
        /* echo "<pre>";
          var_dump($array);exit; */
        if (count($array)) {
            foreach ($array as $key => $value) {
                $data['user'] = $value;
                $content_email = $this->load->view('default/email/E4_06', $data, true);
                $subject_email = 'TOPICA Native - 7 Câu hỏi “khó tránh” khi đi phỏng vấn bằng tiếng Anh';
                $this->send_email('', '', $data['user']->email, $content_email, $subject_email);
                $this->m_contact->update(array('contact_id' => $key), array('email_flag' => 1));
                // $sms_info = array(
                //     "sendTo" => $value->phone,
                //     "content" => 'gui ban BANG HOC PHI. Vui long kiem tra EMAIL de xem chi tiet. Hotline: 0981228979'
                // );
                // $data_send_sms[] = $sms_info;
            }

            // if(count($data_send_sms)){
            //     $this->send_multi_sms($data_send_sms);
            // }
            // echo "<pre>";
            // var_dump($data_send_sms);exit;
        }
        echo "thanh cong";
    }

    public function email_sms_15() {
        $array = array();
        $this->load->model('m_contact');
        $list_contact = $this->m_contact_submit->get_list(array('c.level_care' => 'A9', 'email_flag' => 0), 20);
        /* $list_contact = $this->m_contact_submit->get_list(array('email_flag' => 0),20); */
        foreach ($list_contact as $key => $value) {
            // if ($value->payment && $value->contact_id =='20150914000096AS') {
            $array[$value->contact_id] = $value;
            // }
        }
        /* echo "<pre>";
          var_dump($array);exit; */
        if (count($array)) {
            foreach ($array as $key => $value) {
                $data['user'] = $value;
                $content_email = $this->load->view('default/email/E4_06', $data, true);
                $subject_email = 'TOPICA Native - 7 Câu hỏi “khó tránh” khi đi phỏng vấn bằng tiếng Anh';
                $this->send_email('', '', $data['user']->email, $content_email, $subject_email);
                $this->m_contact->update(array('contact_id' => $key), array('email_flag' => 1));
                // $sms_info = array(
                //     "sendTo" => $value->phone,
                //     "content" => 'gui ban BANG HOC PHI. Vui long kiem tra EMAIL de xem chi tiet. Hotline: 0981228979'
                // );
                // $data_send_sms[] = $sms_info;
            }

            // if(count($data_send_sms)){
            //     $this->send_multi_sms($data_send_sms);
            // }
            // echo "<pre>";
            // var_dump($data_send_sms);exit;
        }
        echo "thanh cong";
    }

    public function send_email_test() {
        $data = $this->input->get();
        $data['user'] = array();
        $content_email = $this->load->view('default/email/' . $data['code'], $data);
        $content_email = $this->load->view('default/email/' . $data['code'], $data, true);
        $subject_email = 'TEST mau' . $data['code'];
        $subject_email = 'EM10_TL';
        $this->send_email('', '', 'vunt@topica.edu.vn', $content_email, $subject_email);
        echo "Thanh cong";
    }

    public function send_email($emailReply = 'topica-native@topmito.edu.vn', $nameReply = 'TOPICA NATIVE', $emailTo = '', $content = '', $subject = '', $title = "TOPICA NATIVE", $attach_files = NULL) {
        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'ssl://112.78.5.200';
        $config['smtp_port'] = '465';
        $config['smtp_timeout'] = '600';
//        $config['smtp_user'] = $emailFrom;
//        $config['smtp_pass'] = $emailPass;
        $config['smtp_user'] = 'topica-native@topmito.edu.vn';
        $config['smtp_pass'] = 'Auto.sale@123';
        $config['charset'] = 'utf-8';
        $config['newline'] = "\r\n";
        $config['mailtype'] = 'html'; // or html
        $config['validation'] = TRUE; // bool whether to validate email or not

        $this->load->library("email");
        $this->email->initialize($config);
        $this->email->from('topica-native@topmito.edu.vn', 'TOPICA NATIVE');
        $this->email->to($emailTo);
        $this->email->cc('topica.native@topica.edu.vn');
        // $this->email->bcc('locmx@topica.edu.vn');
        $this->email->subject($subject);
        $this->email->message($content);
        // if ($emailReply && $nameReply) {
        $this->email->reply_to('topica.native@topica.edu.vn', 'TOPICA NATIVE');
        // }

        if (is_array($attach_files)) {
            foreach ($attach_files as $file_path) {
                $this->email->attach($file_path);
            }
        }

        $check = $this->email->send();
        $this->email->clear(TRUE);
//        echo $this->email->print_debugger();
        return $check;
    }

    public function send_sms($phone, $msg) {
        $this->config->load('api_config');
        $config = $this->config->item('api_server_smsservice');
        // var_dump($config);exit;
        $this->load->library("Rest_Client");
        $this->rest_client->initialize($config);
        // Load library send sms
        $uri = "sms_send_queue/addMultiSMS";

        $param = Array(
            "data" => array(
                array(
                    "sendTo" => $phone,
                    "content" => $msg,
                ),
            ),
        );
        $result = $this->rest_client->post($uri, $param);
        // $result = $this->rest_client->debug($uri, $param);
        // var_dump($result);exit;
        return $result;
    }

    public function send_multi_sms($data) {
        $this->config->load('api_config');
        $config = $this->config->item('api_server_smsservice');
        $this->load->library("Rest_Client");
        $this->rest_client->initialize($config);
        $uri = "sms_send_queue/addMultiSMS";
        $param = Array(
            "data" => $data
        );
        $result = $this->rest_client->post($uri, $param);
        return $result;
    }

    public function add_class() {
        $this->load->model("m_rest_client_lms");
        $this->load->model("m_contact_submit");

        $rest_add_calendarteach = Rest_Client_Factory::create('local_add_calendarteachs');
        $list_class = $this->m_contact_submit->get_list(array('m.payment !=' => '', 'm.status_create_class !=' => 1));
        $info_calendar = array();
        $arr_subject_code = array(
            '9' => 'AT01',
            '10' => 'AT04',
            '15' => 'AT03',
            '16' => 'AT05',
            '19' => 'AT01',
            '20' => 'AT02',
            '21' => 'AT03',
            '23' => 'AT04',
            '22' => 'AT05',
            'SBASIC' => 'AT06'
        );
        foreach ($list_class as $key => $item) {
            // $subject_code = array_rand($arr_subject_code,1);
            $temp = array(
                'submit_id' => $item->id,
                'week' => date('W', strtotime($item->date)),
                'year' => date('o', strtotime($item->date)),
                'week_day' => strtoupper(date('l', strtotime($item->date))),
                'hour_id' => intval($item->time) - 7,
                'hourBegin' => intval($item->time),
                'date_time' => strtotime($item->date),
                'teacher_id' => 0,
                'assistant_id' => 0,
                'teacher_backup' => '',
                'calendar_code' => date('Ymd', strtotime($item->date)) . 'ATHT' . $item->id,
                'level_class' => 'basic',
                'teacher_type' => 'AM',
                'address_teach' => '',
                'type_class' => 'LS',
                'subject_code' => $arr_subject_code[intval($item->time)],
                'status' => 1,
                'contact_id' => $item->contact_id,
                'name' => $arr_subject_code[intval($item->time)] . ' - ' . $item->name,
                'course_id' => 2
            );
            if (trim($item->level) == 'Chưa nghe nói được') {
                $temp['subject_code'] = $arr_subject_code['SBASIC'];
                $temp['name'] = $arr_subject_code['SBASIC'] . ' - ' . $item->name;
            }

            $info_calendar[$item->id] = $temp;
        }
        // echo "<pre>";
        // var_dump($info_calendar);exit;
        // echo "<pre>";
        // var_dump($info_calendar);exit;
        if (count($info_calendar)) {
            $data_calendar = json_encode($info_calendar);
            $params = $data_calendar;
            $rest_add_calendarteach->add_param('data_calendar', $params);
            $result = $rest_add_calendarteach->send();
            if (isset($result->data_submit)) {
                foreach ($result->data_submit as $key => $value) {
                    $this->m_contact_submit->update($value, array('status_create_class' => 1, 'calendar_code' => $result->data[$key]));
                }
            }
        }

        echo "Thanh cong";
    }

    public function add_class_2() {
        $this->load->model("m_rest_client_lms");
        $this->load->model("m_contact_submit");

        $rest_add_calendarteach = Rest_Client_Factory::create('local_add_calendarteachs');
        $list_class = $this->m_contact_submit->get_list(array('m.payment !=' => '', 'm.status_create_class !=' => 0));
        $info_calendar = array();
        $arr_subject_code = array(
            '9' => 'AT01',
            '10' => 'AT04',
            '15' => 'AT03',
            '16' => 'AT05',
            '19' => 'AT01',
            '20' => 'AT02',
            '21' => 'AT03',
            '23' => 'AT04',
            '22' => 'AT05',
            'SBASIC' => 'AT06'
        );
        foreach ($list_class as $key => $item) {
            // $subject_code = array_rand($arr_subject_code,1);
            $temp = array(
                'submit_id' => $item->id,
                'week' => date('W', strtotime($item->date)),
                'year' => date('o', strtotime($item->date)),
                'week_day' => strtoupper(date('l', strtotime($item->date))),
                'hour_id' => intval($item->time) - 7,
                'hourBegin' => intval($item->time),
                'date_time' => strtotime($item->date),
                'teacher_id' => 0,
                'assistant_id' => 0,
                'teacher_backup' => '',
                'calendar_code' => date('Ymd', strtotime($item->date)) . 'ATHT' . $item->id,
                'level_class' => 'basic',
                'teacher_type' => 'AM',
                'address_teach' => '',
                'type_class' => 'LS',
                // 'subject_code' => $arr_subject_code[intval($item->time)],
                'status' => 1,
                'contact_id' => $item->contact_id,
                'name' => $arr_subject_code[intval($item->time)] . ' - ' . $item->name,
                'course_id' => 2
            );
            if (trim($item->level) == 'Chưa nghe nói được') {
                $temp['subject_code'] = $arr_subject_code['SBASIC'];
                $temp['name'] = $arr_subject_code['SBASIC'] . ' - ' . $item->name;
            }

            $info_calendar[$item->id] = $temp;
        }
        echo "<pre>";
        var_dump($info_calendar);exit;
        echo "<pre>";
        var_dump($info_calendar);exit;
        if (count($info_calendar)) {
            $data_calendar = json_encode($info_calendar);
            $params = $data_calendar;
            $rest_add_calendarteach->add_param('data_calendar', $params);
            $result = $rest_add_calendarteach->send();
            if (isset($result->data_submit)) {
                foreach ($result->data_submit as $key => $value) {
                    $this->m_contact_submit->update($value, array('status_create_class' => 1, 'calendar_code' => $result->data[$key]));
                }
            }
        }

        echo "Thanh cong";
    }

    function test_add_user() {
        $this->load->model("m_rest_client_lms");
        $rest_adduseronly = Rest_Client_Factory::create('add_user');

        $rest_adduseronly->add_params(array(
            'users' => array(
                array(
                    'username' => 'thieulm',
                    'firstname' => 'thieu',
                    'lastname' => 'thieu',
                    'email' => 'thieulm@gmail.com',
                    'phone1' => '0123456789',
                    'password' => 'topica123',
                    'lang' => 'vi',
                    'customfields' => array(
                        array(
                            'type' => 'contactid',
                            'value' => '11023293291923'
                        ),
                        array(
                            'type' => 'currentlevel',
                            'value' => 'basic',
                        ),
                        array(
                            'type' => 'package',
                            'value' => 0,
                        ),
                        array(
                            'type' => 'packageparent',
                            'value' => 'TAAM',
                        ),
                        array(
                            'type' => 'studenttype',
                            'value' => 'AUTOSALE',
                        ),
                    )
                )
            ),
            'enrol' => array(
                'timestart' => time(),
                'timeend' => 0,
                'courseid' => 2,
                'role' => 'student',
            )
        ));
        $result_add_user = $rest_adduseronly->send();
        echo "<pre>";
        var_dump($result_add_user);
        exit;
        if ($result_add_user && isset($result_add_user->exception)) {
            $debug = '';
            $debug = isset($result_add_user->debuginfo) ? $result_add_user->debuginfo : '';
            $data_return["state"] = 0;
            $data_return["msg"] = $result_add_user->message . ' - ' . $debug;
            $data_return["redirect"] = "";
            echo json_encode($data_return);
            return FALSE;
        } else if ($result_add_user == NULL) {
            $data_return["state"] = 0;
            $data_return["msg"] = "Chết API, chưa tạo được tài khoản";
            $data_return["redirect"] = "";
            echo json_encode($data_return);
            return FALSE;
        }
    }

    function test_contact_id() {
        $c = 'AS20150913000001AS';
        echo substr(filter_var($c, FILTER_SANITIZE_NUMBER_INT), -6);
    }

}