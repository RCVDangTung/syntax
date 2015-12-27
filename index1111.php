<?php

class cronjob extends CI_Controller {

    /**
     * Gui email trong queue trong truong hop gui nhieu
     * email
     */
    //    var $wstoken = '69841fef63ef16966584804a989ec94e';
    var $wstoken = '53390a5986b45fbf876e3d6ecec94a75';
//    var $server = 'http://lmsautosale.local.topicanative.edu.vn/webservice/rest/server.php';
    var $server = 'http://thailand.lms.topicanative.asia/webservice/rest/server.php';

    public function __construct() {
        parent::__construct();
        $this->load->model('m_contact_submit');
    }

    function send_email_queue() {
        $this->load->model('m_email_history');
        $this->load->model("m_care_history");
        $this->load->model("m_config_email");
        $list_email_send = $this->m_email_history->get_list_email_queue(array('m.status_send' => 1), 5);
        $dates_5 = strtotime(date("Y-m-d", strtotime(date('Y-m-d'))) . " +5 day");

        if (count($list_email_send)) {
            foreach ($list_email_send as $item) {
                $replace = $item->email_template;
                $replace = str_replace('[NAME]', $item->name, $replace);
                // $replace_gender = str_replace('[GENDER]', $gender, $replace_hv);
                // $replace_gv = str_replace('[T&Ecirc;N GVHD]', $advisor->user_display_name, $replace_gender);
                $replace = str_replace('[PHONE]', $item->phone, $replace);
                $replace = str_replace('[EMAIL]', $item->email, $replace);
                $replace = str_replace('[LEVEL_STUDY]', $item->level_study, $replace);
                $replace = str_replace('[LMS_ACCOUNT]', $item->lms_account, $replace);
                $replace = str_replace('[LMS_PASS]', $item->lms_password, $replace);
                $replace = str_replace('[TRIAL_TIME]', $item->trial_time, $replace);
                $replace = str_replace('[NGAY_UU_DAI]', date('d') + 5 . '-' . date('m-Y'), $replace);
                $replace = str_replace('[NGAY_UU_DAI_TH]', date('d-m-Y', $dates_5), $replace);
                $replace = str_replace('[CONTACT_ID]', $item->contact_id, $replace);
                $replace = str_replace('[TROI_CHAY_MACH_LAC]', $item->coherent_point, $replace);
                $replace = str_replace('[TU_VUNG]', $item->vocabulary_point, $replace);
                $replace = str_replace('[NGU_PHAP]', $item->grammar_point, $replace);
                $replace = str_replace('[NGU_AM]', $item->phonetic_point, $replace);
                $replace = str_replace('[LINK_SB100]', $item->link_sb100, $replace);
                $replace = str_replace('[VOUCHER_URL]', "http://thailand.manager.topicanative.asia/get_voucher" . '?id=' . $item->contact_id, $replace);
                $replace = str_replace('[VOUCHER_URL_TENUP]', "http://thailand.manager.topicanative.asia/get_voucher_tenup" . '?id=' . $item->contact_id, $replace);
                $replace = str_replace('[VOUCHER_URL_NORMAL]', "http://thailand.manager.topicanative.asia/get_voucher_normal" . '?id=' . $item->contact_id, $replace);


                $base_url = preg_replace("{/$}", "", base_url());
                $replace = str_replace('[LINK_TECH_NEWBIE]', 'http://thailand.lms.topicanative.asia/local/tpebbb/process.php?roomType=TECHSUPPORTNEWBIE&from=LMS&classType=OTHER', $replace);
                $replace = str_replace('[BASE_URL]', 'http://thailand.manager.topicanative.asia', $replace);
//                $replace = str_replace('[BASE_URL]', 'http://localhost/manager-autosale', $replace);
                $replace = str_replace('[LINK_LMS]', 'http://topi.ca/trial-class-thailand', $replace);
                $replace = str_replace('[VIDEO_GIOI_THIEU]', 'https://www.youtube.com/watch?v=5rCuHUPw_fM&feature=youtu.be', $replace);
                $replace = str_replace('[VIDEO_HUONG_DAN]', 'https://www.youtube.com/watch?v=o8qeft4UHEE', $replace);
                $replace = str_replace('[HUONG_DAN_HOC_LIVE]', 'http://english.topicanative.edu.vn/upload/huong_dan_hoc_truc_tuyen.pdf', $replace);
                $replace = str_replace('[THANH_TOAN_LINK]', 'http://english.topicanative.edu.vn/thanh-toan.php', $replace);
                $replace = str_replace('[HUONG_DAN_THANH_TOAN]', 'http://english.topicanative.edu.vn/upload/HD-thanh-toan.pdf', $replace);
                $replace = str_replace('[TEST_KY_THUAT]', 'http://thailand.topicanative.asia/upload/HowToActivateAccountAndEnterTechTest.pdf', $replace);
                $replace = str_replace('[VIDEO_EMAIL04_BS]', 'https://www.youtube.com/watch?v=-ZfrxQp4eH0', $replace);
                $replace = str_replace('[VIDEO_EMAIL04_IT]', 'https://www.youtube.com/watch?v=YY3C1qu27Fw', $replace);

                $phone_t = str_split(trim($item->phone));
                $input_t = "";
                for ($t = 0; $t < count($phone_t); $t++) {
                    $input_t .= "<input type='text' value='" . $phone_t[$t] . "' readonly style='width:25px;height:20px;float:left;text-align:center;'>";
                }
                $replace = str_replace('[PHONE_COUNTER_SERVICE]', $input_t, $replace);

                $item->content = $replace;
                // var_dump($item->content);exit;
                $email = $this->m_config_email->send_email(NULL, NULL, 'support@topicanative.asia', 'TOPICA NATIVE', $item->email_to, $item->content, $item->subject, "TOPICA NATIVE", NULL, NULL);

                if ($email) {
                    $this->m_email_history->update($item->care_id, array(
                        'status_send' => 2,
                        'content' => $item->content
                    ));
                    $this->m_care_history->update($item->care_id, array(
                        'content_care' => $item->content
                    ));
                }
            }
        }
        echo "thanh cong\n";
    }

    /**
     * Gui sms trong queue trong truong hop gui nhieu
     * sms
     */
    function send_sms_queue() {
        $this->load->model('m_sms_history');
        $this->load->model('m_config_sms');
        $list_sms_send = $this->m_sms_history->get_list_sms_queue(array('m.status_send' => 1), 5);

        if (count($list_sms_send)) {
            $list_phone = array();
            foreach ($list_sms_send as $item) {
                $data_send_sms['list_phone'] = $list_phone;
                $list_phone[$item->subject]["list_phone"][] = $item->phone;
                $list_phone[$item->subject]["id"][$item->phone] = $item->id;
                $list_phone[$item->subject]["content"] = $item->content;
            }
            // Gui sms
            $this->load->model('m_config_sms');

            foreach ($list_phone as $key => $item) {
                $result = $this->m_config_sms->send_multi_sms($item);
                if ($result && $result->data) {
                    foreach ($result->data as $item) {
                        $this->m_sms_history->update($list_phone[$key]["id"][$item->phone], array('status_send' => '2'));
                    }
                }
            }
        }
        echo "thanh cong\n";
    }

    public function check_contact_crm() {
        $this->load->model('m_contact_submit');
        $this->load->model('m_contact');
        $list_contact = $this->m_contact_submit->get_list_contact_check(array('m.check_crm_time' => 0), 20);
        $wsdl = 'http://dev.crmtpe.topica.vn:8090/ServiceCasec.asmx?wsdl';
        $soap_client = new SoapClient($wsdl);
        // $param = array(
        //         'mobile' => '9820230067',
        //         'email' => ''
        //     );
        // $result = $soap_client->checkDulicateCRMfromAutoSale($param);
        // echo "<pre>";
        // var_dump($result);exit();
        foreach ($list_contact as $key => $value) {
            $param = array(
                'mobile' => $value->phone,
                'email' => $value->email
            );
            $result = $soap_client->checkDulicateCRMfromAutoSale($param);
            if (isset($result->checkDulicateCRMfromAutoSaleResult) && isset($result->checkDulicateCRMfromAutoSaleResult)) {
                if ($result->checkDulicateCRMfromAutoSaleResult->Code == 1) {
                    $crm_info = json_decode($result->checkDulicateCRMfromAutoSaleResult->Description);
                    $crm_care_time = isset($crm_info->CallConsulantDate) ? $crm_info->CallConsulantDate : 0;
                    if ((strtotime($value->time_created) - strtotime($crm_care_time)) > (30 * 86400)) {
                        $check_dulicate = 2;
                        $this->m_contact_submit->update($value->id, array(
                            'check_crm_time' => time(),
                            'crm_info' => $result->checkDulicateCRMfromAutoSaleResult->Description,
                            'check_dulicate' => $check_dulicate,
                        ));
                        $this->m_contact->update(array('contact_id' => $value->contact_id), array(
                            'crm_care_time' => $crm_care_time,
                            'crm_status_care' => isset($crm_info->StatusCare) ? $crm_info->StatusCare : 0,
                            'crm_level' => isset($crm_info->Level) ? $crm_info->Level : 0,
                            'crm_info' => $result->checkDulicateCRMfromAutoSaleResult->Description,
                            'crm_check_dulicate' => $check_dulicate
                        ));
                    } else {
                        $check_dulicate = 3;
                        $this->m_contact_submit->update($value->id, array(
                            'check_crm_time' => time(),
                            'crm_info' => $result->checkDulicateCRMfromAutoSaleResult->Description,
                            'check_dulicate' => $check_dulicate,
                        ));
                        $this->m_contact->update(array('contact_id' => $value->contact_id), array(
                            'crm_care_time' => $crm_care_time,
                            'crm_status_care' => isset($crm_info->StatusCare) ? $crm_info->StatusCare : 0,
                            'crm_level' => isset($crm_info->Level) ? $crm_info->Level : 0,
                            'crm_info' => $result->checkDulicateCRMfromAutoSaleResult->Description,
                            'crm_check_dulicate' => $check_dulicate
                        ));
                    }
                } elseif ($result->checkDulicateCRMfromAutoSaleResult->Code == 2) {
                    $this->m_contact_submit->update($value->id, array(
                        'check_crm_time' => time(),
                    ));
                }
            }
        }
        echo "thanh cong";
    }

    public function check_dulicate_mol() {
        $this->load->model('m_contact_submit');
        $this->load->model('m_contact');
        $list_contact = $this->m_contact_submit->get_list_contact_check();
        $arr = array();
        foreach ($list_contact as $key => $value) {
            if (isset($arr[$value->contact_id])) {
                $arr[$value->contact_id] += 1;
            } else {
                $arr[$value->contact_id] = 1;
            }
            if ($arr[$value->contact_id] == 1) {
                $this->m_contact_submit->update($value->id, array(
                    'check_dulicate' => 1,
                ));
            } else {
                $this->m_contact_submit->update($value->id, array(
                    'check_dulicate' => 4,
                ));
            }
        }
        echo "thanh cong";
    }

    public function send_email_sms_auto() {
        $this->load->model('m_contact');
        $this->load->model('m_contact_submit');
        $this->load->model("m_care_history");
        $this->load->model("m_care_history_month");
        $this->load->model('m_sms_history');
        $this->load->model('m_email_history');
        $this->load->model('m_sys_email_template');
        $this->load->model('m_sys_sms_template');
        $this->load->model('m_contact_phone');

        $list_email_temp = $this->m_sys_email_template->get_list();
        $arr_email_temp = array();
        foreach ($list_email_temp as $key => $value) {
            $arr_email_temp[$value->code] = $value;
        }

        $list_sms_temp = $this->m_sys_sms_template->get_list();
        $arr_sms_temp = array();
        foreach ($list_sms_temp as $key => $value) {
            $arr_sms_temp[$value->code] = $value;
        }

        $list_contact = $this->m_contact_submit->get_list_contact_check(array(
//            'm.contact_id' => '20151212000681ASTL',
            // 'm.auto_flag <=' => 7,
            // 'm.date >' => '2015-09-24',
            'm.status' => 1
        ));
        
        // echo "<pre>";
        // var_dump($list_contact);exit;

        $check_time_em01 = 3 * 3600; //Config gửi EM01 (đơn vị s)
        $check_time_em02 = 3 * 3600; //Config gửi EM02 (đơn vị s)
        $check_time_em04 = 12 * 3600; //Config gửi EM04 (đơn vị s)
        $check_time_em05 = 6 * 3600; //Config gửi EM05 (đơn vị s)
        $check_time_em07 = 1 * 3600; //Config gửi EM07 (đơn vị s)

        $data_send_sms = array(); //Danh sach SMS gui cho chi minh

        if (count($list_contact)) {

            foreach ($list_contact as $key => $value) {
                // if ($value->auto_flag == 0) {//gui EM01 + SM01
                $trial_time = strtotime($value->date . ' ' . $value->time);
                $check_time_submit = 0;
                $date_submit = date('Y-m-d', strtotime($value->time_created));
                if (strtotime($value->time_created) >= strtotime($date_submit) && strtotime($value->time_created) < strtotime($date_submit . ' 19:00:00')) {
                    $check_time_em04 = strtotime($date_submit . ' 19:00:00');
                } else {
                    $check_time_em04 = date('Y-m-d 08:00:00', strtotime($date_submit) + 86400);
                }

                if ($value->auto_flag == 0 && $value->level_care == 'A3' && ( (time() - strtotime($value->time_created)) >= 60)) { //Gui email counter-service
                    // echo "send" . ' ...... ';
                    if ($value->type == 5 && $value->flag_email_counter_service == 0) {
                        $care_data_couterse = array();
                        $care_data_couterse["contact_id"] = $value->contact_id;
                        $care_data_couterse["content_care"] = '';
                        $care_data_couterse["time_created"] = time();
                        $care_data_couterse["care_type_code"] = 'EMAIL';
                        $care_data_couterse["note"] = $arr_email_temp['EM_COUNTER_SERVICE_TL']->subject;
                        $care_data_couterse["status"] = "success";
                        $care_id_couterse = $this->m_care_history->add($care_data_couterse);
                        $care_data["id"] = $care_id_couterse;
                        $this->m_care_history_month->add($care_data_couterse);
                        
                        $email_data_couterse = array();
                        $email_data_couterse["id"] = $care_id_couterse;
                        $email_data_couterse["status_send"] = 1;
                        $email_data_couterse["email_to"] = $value->email;
                        $email_data_couterse["template_id"] = $arr_email_temp['EM_COUNTER_SERVICE_TL']->id;
                        $email_data_couterse["subject"] = $arr_email_temp['EM_COUNTER_SERVICE_TL']->subject;
                        $email_insert_id_couterse = $this->m_email_history->add($email_data_couterse);

                        //Cap nhat flag_email_counter_service = 1
                        $this->m_contact_submit->update($value->id, array('flag_email_counter_service' => 1));
                    }
                }

                if ($value->auto_flag == 0 && $value->level_care == 'A3' && ( (time() - strtotime($value->time_created)) >= $check_time_em01)) {//gui EM01 + SM01
                    //EMAIL
                    
                	                    
                    $care_data = array();
                    $care_data["contact_id"] = $value->contact_id;
                    $care_data["content_care"] = '';
                    $care_data["time_created"] = time();
                    $care_data["care_type_code"] = 'EMAIL';
                    $care_data["note"] = $arr_email_temp['EM01_TL']->subject;
                    $care_data["status"] = "success";
                    $care_id = $this->m_care_history->add($care_data);
                    $care_data["id"] = $care_id;
                    $this->m_care_history_month->add($care_data);


                    $email_data = array();
                    $email_data["id"] = $care_id;
                    $email_data["status_send"] = 1;
                    $email_data["email_to"] = $value->email;
                    $email_data["template_id"] = $arr_email_temp['EM01_TL']->id;
                    $email_data["subject"] = $arr_email_temp['EM01_TL']->subject;
                    $email_insert_id = $this->m_email_history->add($email_data);
                    //SMS (gửi tất cả sdt của HV)
                    $list_phone = $this->m_contact_phone->get_list(array('contact_id' => $value->contact_id));
                    foreach ($list_phone as $key_phone => $val_phone) {
                        $care_data = array();
                        $care_data["contact_id"] = $value->contact_id;
                        $care_data["content_care"] = $arr_sms_temp['SMS01']->content;
                        $care_data["time_created"] = time();
                        $care_data["care_type_code"] = 'SMS';
                        $care_data["note"] = $arr_sms_temp['SMS01']->subject;
                        $care_data["status"] = "success";

                        $care_id = $this->m_care_history->add($care_data);
                        $care_data["id"] = $care_id;
                        $this->m_care_history_month->add($care_data);

                        $sms_data = array();
                        $sms_data["id"] = $care_id;
                        $sms_data["status_send"] = 1;
                        $sms_data["phone"] = $val_phone->phone;
                        $sms_data["content"] = $arr_sms_temp['SMS01']->content;
                        $sms_data["template_id"] = $arr_sms_temp['SMS01']->id;
                        $sms_data["subject"] = $arr_sms_temp['SMS01']->subject;
                        $sms_insert_id = $this->m_sms_history->add($sms_data);
                    }
                                        
                    if ($sms_insert_id && $email_insert_id) {
                        $this->m_contact_submit->update($value->id, array('auto_flag' => 1));
                    }
                } elseif ($value->auto_flag == 1 && $value->level_care == 'A3' && (time() - $trial_time) >= $check_time_em02) {//gui EM02 + SM02
                    //EMAIL
                    $care_data = array();
                    $care_data["contact_id"] = $value->contact_id;
                    $care_data["content_care"] = '';
                    $care_data["time_created"] = time();
                    $care_data["care_type_code"] = 'EMAIL';
                    $care_data["note"] = $arr_email_temp['EM02_TL']->subject;
                    $care_data["status"] = "success";

                    $care_id = $this->m_care_history->add($care_data);
                    $care_data["id"] = $care_id;
                    $this->m_care_history_month->add($care_data);

                    $email_data = array();
                    $email_data["id"] = $care_id;
                    $email_data["status_send"] = 1;
                    $email_data["email_to"] = $value->email;
                    $email_data["template_id"] = $arr_email_temp['EM02_TL']->id;
                    $email_data["subject"] = $arr_email_temp['EM02_TL']->subject;
                    $email_insert_id = $this->m_email_history->add($email_data);


                    //SMS
//                     $care_data = array();
//                     $care_data["contact_id"] = $value->contact_id;
//                     $care_data["content_care"] = $arr_sms_temp['SMS02']->content;
//                     $care_data["time_created"] = time();
//                     $care_data["care_type_code"] = 'SMS';
//                     $care_data["note"] = $arr_sms_temp['SMS02']->subject;
//                     $care_data["status"] = "success";
//                     $care_id = $this->m_care_history->add($care_data);
//                     $care_data["id"] = $care_id;
//                     $this->m_care_history_month->add($care_data);
//                     $sms_data = array();
//                     $sms_data["id"] = $care_id;
//                     $sms_data["status_send"] = 1;
//                     $sms_data["phone"] = $value->phone;
//                     $sms_data["content"] = $arr_sms_temp['SMS02']->content;
//                     $sms_data["template_id"] = $arr_sms_temp['SMS02']->id;
//                     $sms_data["subject"] = $arr_sms_temp['SMS02']->subject;
//                     $sms_insert_id = $this->m_sms_history->add($sms_data);
                    //SMS (gửi tất cả sdt của HV)
                    $list_phone = $this->m_contact_phone->get_list(array('contact_id' => $value->contact_id));
                    foreach ($list_phone as $key_phone => $val_phone) {
                        $care_data = array();
                        $care_data["contact_id"] = $value->contact_id;
                        $care_data["content_care"] = $arr_sms_temp['SMS02']->content;
                        $care_data["time_created"] = time();
                        $care_data["care_type_code"] = 'SMS';
                        $care_data["note"] = $arr_sms_temp['SMS02']->subject;
                        $care_data["status"] = "success";

                        $care_id = $this->m_care_history->add($care_data);
                        $care_data["id"] = $care_id;
                        $this->m_care_history_month->add($care_data);

                        $sms_data = array();
                        $sms_data["id"] = $care_id;
                        $sms_data["status_send"] = 1;
                        $sms_data["phone"] = $val_phone->phone;
                        $sms_data["content"] = $arr_sms_temp['SMS02']->content;
                        $sms_data["template_id"] = $arr_sms_temp['SMS02']->id;
                        $sms_data["subject"] = $arr_sms_temp['SMS02']->subject;
                        $sms_insert_id = $this->m_sms_history->add($sms_data);
                    }

                    //GUI SMS CHO CHI MINH
                    $sms_info = array(
                        "sendTo" => '0982160788',
                        "content" => $value->name . ' ' . $arr_sms_temp['SMS02']->content
                    );
                    $data_send_sms[] = $sms_info;
                    if ($sms_insert_id && $email_insert_id) {
                        $this->m_contact_submit->update($value->id, array('auto_flag' => 2));
                    }
                } elseif ($value->auto_flag == 2 && ($value->level_care == 'A5' || $value->level_care == 'A6')) {//gui EM03 + SM03
                    //EMAIL
                    $care_data = array();
                    $care_data["contact_id"] = $value->contact_id;
                    $care_data["content_care"] = '';
                    $care_data["time_created"] = time();
                    $care_data["care_type_code"] = 'EMAIL';
                    $care_data["note"] = $arr_email_temp['EM03_TL']->subject;
                    $care_data["status"] = "success";

                    $care_id = $this->m_care_history->add($care_data);
                    $care_data["id"] = $care_id;
                    $this->m_care_history_month->add($care_data);

                    $email_data = array();
                    $email_data["id"] = $care_id;
                    $email_data["status_send"] = 1;
                    $email_data["email_to"] = $value->email;
                    $email_data["template_id"] = $arr_email_temp['EM03_TL']->id;
                    $email_data["subject"] = $arr_email_temp['EM03_TL']->subject;
                    $email_insert_id = $this->m_email_history->add($email_data);
                    //SMS (gửi tất cả sdt của HV)
                    $list_phone = $this->m_contact_phone->get_list(array('contact_id' => $value->contact_id));
                    foreach ($list_phone as $key_phone => $val_phone) {
                        $care_data = array();
                        $care_data["contact_id"] = $value->contact_id;
                        $care_data["content_care"] = $arr_sms_temp['SMS03']->content;
                        $care_data["time_created"] = time();
                        $care_data["care_type_code"] = 'SMS';
                        $care_data["note"] = $arr_sms_temp['SMS03']->subject;
                        $care_data["status"] = "success";

                        $care_id = $this->m_care_history->add($care_data);
                        $care_data["id"] = $care_id;
                        $this->m_care_history_month->add($care_data);

                        $sms_data = array();
                        $sms_data["id"] = $care_id;
                        $sms_data["status_send"] = 1;
                        $sms_data["phone"] = $val_phone->phone;
                        $sms_data["content"] = $arr_sms_temp['SMS03']->content;
                        $sms_data["template_id"] = $arr_sms_temp['SMS03']->id;
                        $sms_data["subject"] = $arr_sms_temp['SMS03']->subject;
                        $sms_insert_id = $this->m_sms_history->add($sms_data);
                    }


                    //GUI SMS CHO CHI MINH
                    $sms_info = array(
                        "sendTo" => '0982160788',
                        "content" => $value->name . ' ' . $arr_sms_temp['SMS03']->content
                    );
                    $data_send_sms[] = $sms_info;

                    if ($sms_insert_id && $email_insert_id) {
                        $this->m_contact->update(array('contact_id' => $value->contact_id), array('level_care' => 'A6'));
                        $this->m_contact_submit->update($value->id, array('auto_flag' => 3));
                    }
                } elseif ($value->auto_flag == 3 && $value->payment != '' && ($value->level_care == 'A5' || $value->level_care == 'A6') && time() >= $check_time_em04) {//gui EM04 + SM04
                    //EMAIL
                    $care_data = array();
                    $care_data["contact_id"] = $value->contact_id;
                    $care_data["content_care"] = '';
                    $care_data["time_created"] = time();
                    $care_data["care_type_code"] = 'EMAIL';
                    $care_data["note"] = $arr_email_temp['EM04_TL']->subject;
                    $care_data["status"] = "success";

                    $care_id = $this->m_care_history->add($care_data);
                    $care_data["id"] = $care_id;
                    $this->m_care_history_month->add($care_data);

                    $email_data = array();
                    $email_data["id"] = $care_id;
                    $email_data["status_send"] = 1;
                    $email_data["email_to"] = $value->email;
                    $email_data["template_id"] = $arr_email_temp['EM04_TL']->id;
                    $email_data["subject"] = $arr_email_temp['EM04_TL']->subject;
                    $email_insert_id = $this->m_email_history->add($email_data);

                    //SMS
                    $list_phone = $this->m_contact_phone->get_list(array('contact_id' => $value->contact_id));
                    foreach ($list_phone as $key_phone => $val_phone) {
                        $care_data = array();
                        $care_data["contact_id"] = $value->contact_id;
                        $care_data["content_care"] = $arr_sms_temp['SMS04']->content;
                        $care_data["time_created"] = time();
                        $care_data["care_type_code"] = 'SMS';
                        $care_data["note"] = $arr_sms_temp['SMS04']->subject;
                        $care_data["status"] = "success";

                        $care_id = $this->m_care_history->add($care_data);
                        $care_data["id"] = $care_id;
                        $this->m_care_history_month->add($care_data);

                        $sms_data = array();
                        $sms_data["id"] = $care_id;
                        $sms_data["status_send"] = 1;
                        $sms_data["phone"] = $val_phone->phone;
                        $sms_data["content"] = $arr_sms_temp['SMS04']->content;
                        $sms_data["template_id"] = $arr_sms_temp['SMS04']->id;
                        $sms_data["subject"] = $arr_sms_temp['SMS04']->subject;
                        $sms_insert_id = $this->m_sms_history->add($sms_data);
                    }


                    //GUI SMS CHO CHI MINH
                    $sms_info = array(
                        "sendTo" => '0982160788',
                        "content" => $value->name . ' ' . $arr_sms_temp['SMS04']->content
                    );
                    $data_send_sms[] = $sms_info;

                    if ($sms_insert_id && $email_insert_id) {
                        $this->m_contact_submit->update($value->id, array('auto_flag' => 4));
                    }
                } elseif ($value->auto_flag == 4 && $value->payment != '' && ($value->level_care == 'A5' || $value->level_care == 'A6') && time() >= strtotime(date('Y-m-d 11:00:00', $trial_time))) {//gui EM05 + SM05
                    //EMAIL
                    $care_data = array();
                    $care_data["contact_id"] = $value->contact_id;
                    $care_data["content_care"] = '';
                    $care_data["time_created"] = time();
                    $care_data["care_type_code"] = 'EMAIL';
                    $care_data["note"] = $arr_email_temp['EM05_TL']->subject;
                    $care_data["status"] = "success";

                    $care_id = $this->m_care_history->add($care_data);
                    $care_data["id"] = $care_id;
                    $this->m_care_history_month->add($care_data);

                    $email_data = array();
                    $email_data["id"] = $care_id;
                    $email_data["status_send"] = 1;
                    $email_data["email_to"] = $value->email;
                    $email_data["template_id"] = $arr_email_temp['EM05_TL']->id;
                    $email_data["subject"] = $arr_email_temp['EM05_TL']->subject;
                    $email_insert_id = $this->m_email_history->add($email_data);

                    //SMS
                    $list_phone = $this->m_contact_phone->get_list(array('contact_id' => $value->contact_id));
                    foreach ($list_phone as $key_phone => $val_phone) {
                        $care_data = array();
                        $care_data["contact_id"] = $value->contact_id;
                        $care_data["content_care"] = $arr_sms_temp['SMS05']->content;
                        $care_data["time_created"] = time();
                        $care_data["care_type_code"] = 'SMS';
                        $care_data["note"] = $arr_sms_temp['SMS05']->subject;
                        $care_data["status"] = "success";

                        $care_id = $this->m_care_history->add($care_data);
                        $care_data["id"] = $care_id;
                        $this->m_care_history_month->add($care_data);

                        $sms_data = array();
                        $sms_data["id"] = $care_id;
                        $sms_data["status_send"] = 1;
                        $sms_data["phone"] = $val_phone->phone;
                        $sms_data["content"] = $arr_sms_temp['SMS05']->content;
                        $sms_data["template_id"] = $arr_sms_temp['SMS05']->id;
                        $sms_data["subject"] = $arr_sms_temp['SMS05']->subject;
                        $sms_insert_id = $this->m_sms_history->add($sms_data);
                    }


                    //GUI SMS CHO CHI MINH
                    $sms_info = array(
                        "sendTo" => '0982160788',
                        "content" => $value->name . ' ' . $arr_sms_temp['SMS05']->content
                    );
                    $data_send_sms[] = $sms_info;

                    if ($sms_insert_id && $email_insert_id) {
                        $this->m_contact_submit->update($value->id, array('auto_flag' => 5));
                    }
                } elseif (($value->auto_flag == 5 || $value->auto_flag == 6) && $value->payment != '' && ($value->level_care == 'A5' || $value->level_care == 'A6') && ($trial_time - time()) <= $check_time_em07) {//gui EM07 + SM07
                    //EMAIL
                    $care_data = array();
                    $care_data["contact_id"] = $value->contact_id;
                    $care_data["content_care"] = '';
                    $care_data["time_created"] = time();
                    $care_data["care_type_code"] = 'EMAIL';
                    $care_data["note"] = $arr_email_temp['EM07_TL']->subject;
                    $care_data["status"] = "success";

                    $care_id = $this->m_care_history->add($care_data);
                    $care_data["id"] = $care_id;
                    $this->m_care_history_month->add($care_data);

                    $email_data = array();
                    $email_data["id"] = $care_id;
                    $email_data["status_send"] = 1;
                    $email_data["email_to"] = $value->email;
                    $email_data["template_id"] = $arr_email_temp['EM07_TL']->id;
                    $email_data["subject"] = $arr_email_temp['EM07_TL']->subject;
                    $email_insert_id = $this->m_email_history->add($email_data);

                    //SMS
                    $list_phone = $this->m_contact_phone->get_list(array('contact_id' => $value->contact_id));
                    foreach ($list_phone as $key_phone => $val_phone) {
                        $care_data = array();
                        $care_data["contact_id"] = $value->contact_id;
                        $care_data["content_care"] = $arr_sms_temp['SMS07']->content;
                        $care_data["time_created"] = time();
                        $care_data["care_type_code"] = 'SMS';
                        $care_data["note"] = $arr_sms_temp['SMS07']->subject;
                        $care_data["status"] = "success";

                        $care_id = $this->m_care_history->add($care_data);
                        $care_data["id"] = $care_id;
                        $this->m_care_history_month->add($care_data);

                        $sms_data = array();
                        $sms_data["id"] = $care_id;
                        $sms_data["status_send"] = 1;
                        $sms_data["phone"] = $val_phone->phone;
                        $sms_data["content"] = $arr_sms_temp['SMS07']->content;
                        $sms_data["template_id"] = $arr_sms_temp['SMS07']->id;
                        $sms_data["subject"] = $arr_sms_temp['SMS07']->subject;
                        $sms_insert_id = $this->m_sms_history->add($sms_data);
                    }


                    //GUI SMS CHO CHI MINH
                    $sms_info = array(
                        "sendTo" => '0982160788',
                        "content" => $value->name . ' ' . $arr_sms_temp['SMS07']->content
                    );
                    $data_send_sms[] = $sms_info;

                    if ($sms_insert_id && $email_insert_id) {
                        $this->m_contact->update(array('contact_id' => $value->contact_id), array('level_care' => 'A7'));
                        $this->m_contact_submit->update($value->id, array('auto_flag' => 7));
                    }
                }
            }
        }


        // echo "<pre>";
        // var_dump($data_send_sms);
        // die();
//        if (count($data_send_sms)) {
//            $this->send_multi_sms($data_send_sms);
//        }
        echo "thanh cong";
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

    /* ------------- Cronjob lấy dữ liệu đánh giá bên LMS -------------- */

    function log_tpebbb_ratingclass() {
        $this->load->model('m_contact_assessment');

        $this->load->library("Rest_Client");
        $config_LMS = array(
            'server' => $this->server,
        );
        $this->rest_client->initialize($config_LMS);
        $uri = "";
        $timecreate = $this->m_contact_assessment->get_last_row();
        if (!$timecreate) {
            $timecreate = strtotime(date("2015-10-01"));
        } else {
            $timecreate = $timecreate->timecreated;
        }
        if (!$timecreate) {
            echo "Dữ liệu thời gian không chính xác";
            exit;
        }
        $param = array(
            'wstoken' => $this->wstoken,
            'wsfunction' => 'local_assessmentlog',
            'moodlewsrestformat' => 'json',
            'timecreated' => $timecreate,
        );

        $result = $this->rest_client->post($uri, $param);

        //Nếu dữ liệu trả về rỗng thì exit
        if (!$result->data) {
            echo "Dữ liệu đã được cập nhật";
            exit;
        }

        $data_insert = array();
        $i = 0;
        foreach ($result->data as $key => $value) {
            $data_insert[$i] = (array) $result->data[$key];
            if (isset($data_insert[$i]['id'])) {
                unset($data_insert[$i]['id']);
            }
            $i++;
        }

        $data_return = $this->m_contact_assessment->add_muti($data_insert);

        if ($data_return) {
            echo 'Thêm dữ liệu thành công';
        } else {
            echo 'Thêm dữ liệu thất bại';
        }
    }

    function logsservice_assessment_at() {
        $this->load->model('m_teacher_assessment');
        $this->load->model('m_contact');

        $this->load->library("Rest_Client");
        $config_LMS = array(
            'server' => $this->server,
        );
        $this->rest_client->initialize($config_LMS);
        $uri = "";
        $timecreate = $this->m_teacher_assessment->get_last_row();
        if (!$timecreate) {
            $timecreate = strtotime(date("2015-10-01"));
        } else {
            $timecreate = $timecreate->timecreated;
        }
        if (!$timecreate) {
            echo "Dữ liệu thời gian không chính xác";
            exit;
        }
        $param = array(
            'wstoken' => $this->wstoken,
            'wsfunction' => 'local_assessmentlogsservice',
            'moodlewsrestformat' => 'json',
            'timecreated' => $timecreate,
        );

        $result = $this->rest_client->post($uri, $param);
        //Nếu dữ liệu trả về rỗng thì exit
        if (!$result->data) {
            echo "Dữ liệu đã được cập nhật";
            exit;
        }

        $data_insert = array();
        $i = 0;
        foreach ($result->data as $key => $value) {
            $data_insert[$i] = (array) $result->data[$key];
            if (isset($data_insert[$i]['id'])) {
                unset($data_insert[$i]['id']);
            }
            $temp = json_decode($result->data[$i]->scorecard);
            $data_insert[$i]['fluency'] = isset($temp->fluency->score) ? $temp->fluency->score : '';
            $data_insert[$i]['grammar'] = isset($temp->grammar->score) ? $temp->grammar->score : '';
            $data_insert[$i]['part6'] = isset($temp->part6->total) ? $temp->part6->total : '';
            $data_insert[$i]['pronunciations'] = isset($temp->pronunciations->score) ? $temp->pronunciations->score : '';
            $data_insert[$i]['vocabulary'] = isset($temp->vocabulary->score) ? $temp->vocabulary->score : '';
            $data_insert[$i]['q1'] = isset($temp->questions->q1) ? $temp->questions->q1 : '';
            $data_insert[$i]['q2'] = isset($temp->questions->q2) ? $temp->questions->q2 : '';
            $data_insert[$i]['q3'] = isset($temp->questions->q3) ? $temp->questions->q3 : '';
            $data_insert[$i]['q4'] = isset($temp->questions->q4) ? $temp->questions->q4 : '';
            $data_insert[$i]['q5'] = isset($temp->questions->q5) ? $temp->questions->q5 : '';
//            $data_insert[$i]['contact_id'] = isset($temp->contact_id) ? $temp->contact_id : '';
            $table_contact = $this->m_contact->get_one($data_insert[$i]['contact_id']);
            if ($table_contact) {
                if ($table_contact->level_care != 'A8' && $table_contact->level_care != 'A9' && $table_contact->level_care != 'A10') {
                    $this->m_contact->update(array('id' => $table_contact->m_id), array('level_care' => 'A8'));
                }
            }
            $i++;
        }
        $data_return = $this->m_teacher_assessment->add_muti($data_insert);

        if ($data_return) {
            echo 'Thêm dữ liệu thành công';
        } else {
            echo 'Thêm dữ liệu thất bại';
        }
    }

    public function add_class_lms() {
        $this->load->model("m_rest_client_lms");
        $this->load->model("m_open_class_calendar");

        $rest_add_calendarteach = Rest_Client_Factory::create('local_add_calendarteachs');
        $list_class = $this->m_open_class_calendar->get_list(array('m.created_class_lms !=' => 1));
        $info_calendar = array();
        $arr_subject_code = array(
            '9' => 'AT01',
            '10' => 'AT04',
            '12' => 'AT04',
            '14' => 'AT01',
            '15' => 'AT03',
            '16' => 'AT05',
            '18' => 'AT05',
            '19' => 'AT01',
            '20' => 'AT02',
            '21' => 'AT03',
            '23' => 'AT04',
            '22' => 'AT05',
            'SBASIC' => 'AT06'
        );
        foreach ($list_class as $key => $item) {
            if ($item->classnumber && $item->classnumber > $item->old_classnumber) {
                $offset = $item->classnumber - $item->old_classnumber;
                for ($i = 1; $i <= $offset; $i++) {
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
                        'calendar_code' => date('Ymd', strtotime($item->date)) . '-' . 'ASTL' . '-' . $item->id . $i,
                        'level_class' => 'basic',
                        'teacher_type' => 'AM',
                        'address_teach' => '',
                        'type_class' => 'LS',
                        'subject_code' => $arr_subject_code[intval($item->time)],
                        'status' => 1,
                        'name' => $arr_subject_code[intval($item->time)],
                        'course_id' => 2,
                        'time_created' => time(),
                        'student_type' => 'TL'
                    );
                    $info_calendar[] = $temp;
                }
            }
        }
        // echo "<pre>";
        // var_dump($info_calendar);
        // exit;
        if (count($info_calendar)) {
            $data_calendar = json_encode($info_calendar);
            $params = $data_calendar;
            $rest_add_calendarteach->add_param('data_calendar', $params);
            $result = $rest_add_calendarteach->send();
            if (isset($result->data_submit)) {
                foreach ($result->data_submit as $key => $value) {
                    $this->m_open_class_calendar->update($value, array('created_class_lms' => 1));
                }
            }
        }

        echo "Thanh cong";
    }

    public function add_class() {
        $this->load->model("m_rest_client_lms");
        $this->load->model("m_contact_submit");

        $rest_add_calendarteach = Rest_Client_Factory::create('local_update_calendarteachs');
        $list_class = $this->m_contact_submit->get_list(array('m.payment !=' => '', 'm.status_create_class !=' => 1));
        $info_calendar = array();
        $arr_subject_code = array(
            '9' => 'AT01',
            '10' => 'AT04',
            '12' => 'AT04',
            '14' => 'AT01',
            '15' => 'AT03',
            '16' => 'AT05',
            '18' => 'AT05',
            '19' => 'AT01',
            '20' => 'AT02',
            '21' => 'AT03',
            '23' => 'AT04',
            '22' => 'AT05',
            'SBASIC' => 'AT06'
        );
        // echo "<pre>";
        // var_dump($list_class);
        // exit;
        foreach ($list_class as $key => $item) {
            // $subject_code = array_rand($arr_subject_code,1);
            $temp = array(
                'submit_id' => $item->id,
                'week' => date('W', strtotime($item->date)),
                'year' => date('o', strtotime($item->date)),
                'week_day' => strtoupper(date('l', strtotime($item->date))),
                'hour_id' => intval($item->time) - 7,
                // 'hourBegin' => intval($item->time),
                // 'date_time' => strtotime($item->date),
                // 'teacher_id' => 0,
                // 'assistant_id' => 0,
                // 'teacher_backup' => '',
                // 'calendar_code' => date('Ymd', strtotime($item->date)) . 'ATHT' . $item->id,
                'level_class' => 'basic',
                'teacher_type' => 'AM',
                // 'address_teach' => '',
                'type_class' => 'LS',
                // 'subject_code' => $arr_subject_code[intval($item->time)],
                // 'status' => 1,
                'contact_id' => $item->contact_id,
                'name' => $arr_subject_code[intval($item->time)] . ' - ' . $item->name,
                // 'course_id' => 2
                'student_type' => 'TL'
            );
            if (trim($item->level) == 'Chưa nghe nói được') {
                // $temp['subject_code'] = $arr_subject_code['SBASIC'];
                $temp['name'] = $arr_subject_code['SBASIC'] . ' - ' . $item->name;
            }

            $info_calendar[$item->id] = $temp;
        }

        if (count($info_calendar)) {
            $data_calendar = json_encode($info_calendar);
            $params = $data_calendar;
            $rest_add_calendarteach->add_param('data_calendar', $params);
            $result = $rest_add_calendarteach->send();

            if ($result) {
                foreach ($result->data as $key => $value) {
                    $data_update = json_decode($value);
                    // var_dump($data_update);
                    if ($data_update->calendar_code) {
                        $this->m_contact_submit->update($data_update->id, array('status_create_class' => 1, 'calendar_code' => $data_update->calendar_code));
                    }
                }
            }
        }

        echo "Thanh cong";
    }

    function update_calendar_teach() {
        $this->load->model("m_calendar_teach");
        $this->load->library("Rest_Client");
        $config_LMS = array(
            'server' => $this->server,
        );
        $time_now = time();
        $time_update_calendar = $this->m_calendar_teach->get_row_time_flag();
        $time_update_check = isset($time_update_calendar->time_flag) ? $time_update_calendar->time_flag : (strtotime("next monday") - 7 * 86400);
        $this->rest_client->initialize($config_LMS);
        $uri = "";
        $param = array(
            // 'wstoken' => $this->wstoken,
            'wstoken' => '962c2d86546673a1efe1d673a07ed05a',
            'wsfunction' => 'local_get_calendarteachsnew',
            'moodlewsrestformat' => 'json',
            'time' => $time_update_check - 60,
        );
        // echo "<pre>";
        // var_dump($param);
        // exit;
        $result = $this->rest_client->post($uri, $param);
        // $result = $this->rest_client->debug($uri, $param);
        // echo "<pre>";
        // var_dump($result);
        // exit;
        $log = date('d-m-Y H:i') . ": " . json_encode($result) . PHP_EOL;
        file_put_contents('./logs/log-' . date("d-m-Y") . '.txt', $log, FILE_APPEND);
        if (is_object($result) && !isset($result->exception)) {
            $data_response = json_decode($result->data);
            foreach ($data_response as $key => $response) {
                $id_calendar_code = $this->m_calendar_teach->get_calendar_update(array('m.calendar_code' => $response->calendar_code));
                $data_calendar = array();
                $data_calendar['id'] = $response->id;
                $data_calendar['week'] = $response->week;
                $data_calendar['year'] = $response->year;
                $data_calendar['week_day'] = $response->week_day;
                $data_calendar['hour_id'] = $response->hour_id;
                $data_calendar['date_time'] = $response->date_time;
                $data_calendar['teacher_id'] = $response->teacher_id;
                $data_calendar['assistant_id'] = $response->assistant_id;
                $data_calendar['teacher_backup'] = $response->teacher_backup;
                $data_calendar['level_class'] = $response->level_class;
                $data_calendar['teacher_type'] = $response->teacher_type;
                $data_calendar['student_type'] = $response->student_type;
                $data_calendar['type_class'] = $response->type_class;
                $data_calendar['subject_code'] = $response->subject_code;
                $data_calendar['calendar_code'] = $response->calendar_code;
                $data_calendar['timecreated'] = $response->time_created;
                $data_calendar['timemodified'] = $response->time_modified;
                $data_calendar['status'] = $response->status;
                $data_calendar['flag'] = 2; //1 chua dong bo;2 la da dc dong bo
                if ($id_calendar_code) {
                    $this->m_calendar_teach->update(array('id' => $id_calendar_code->id), $data_calendar);
                } else {
                    $this->m_calendar_teach->add($data_calendar);
                }
                echo $data_calendar['calendar_code'];
            }
            if (count($data_response) > 0) {
                $this->m_calendar_teach->update(array('id' => $time_update_calendar->id), array("time_flag" => $time_now));
            }
        }
    }

}
