<?php

class checkout_2c2p extends CI_Controller {

    //    public $secret_key = 'HVAQSnGbv7iJ'; //API Secret Key
//    public $secret_key = 'nfdSVdxZ43i3'; //API Secret Key test


    public function __construct() {
        parent::__construct();
    }
    
    public function result() {
        $get_data = $this->input->post();

        // echo "<pre>";
        // var_dump($get_data);
        
        // echo "<pre>";
        // var_dump($get_data["order_id"]);

        // die();
        
        $Secure_Hash = $get_data["order_id"]; // = '60c70edf4fd53b9f49e532b8a4e6c6b629f682ba'
        // unset($get_data["hash_value"]);

        $this->load->model('m_contact_deposit');
        if ($get_data['order_id'] && $deposit_info = $this->m_contact_deposit->get_one(array('m.order_info' => $get_data['order_id']))) {
            
            $checksum_topica = $deposit_info->checksum_topica;

            $this->m_contact_deposit->update($deposit_info->id, array(
                'order_id' => isset($get_data['order_id']) ? $order_id : '',//
                'transaction_id' => isset($get_data["invoice_no"]) ? $get_data["invoice_no"] : '', // Mã giao dịch do 2c2p trả về
                'transaction_status' => isset($get_data['payment_status']) ? $get_data['payment_status'] : '',// Trạng thái giao dịch do 2c2p trả về
                'other_info' => json_encode($get_data)// thông tin chi tiết thanh toán 2c2p
                ));
        }
        
        // kiểm tra dữ liệu trả về từ 2c2p
        $variable = array();
        $variable['version'] = $this->validate_input($get_data['version']);
        $variable['amount'] = $this->validate_input($get_data['amount']);
        $variable['transaction_status'] = $this->validate_input($get_data['payment_status']);
        $variable['merchant_id'] = $this->validate_input($get_data['merchant_id']);
        $variable['order_id'] = $this->validate_input($get_data['order_id']);
        $variable['transaction_ref'] = $this->validate_input($get_data['transaction_ref']);
        $variable['channel_response_code'] = $this->validate_input($get_data['channel_response_code']);
        $variable['channel_response_desc'] = $this->validate_input($get_data['channel_response_desc']);
        $variable['paid_agent'] = $this->validate_input($get_data['paid_agent']);
        $variable['masked_pan'] = $this->validate_input($get_data['masked_pan']);
        $variable['payment_channel'] = $this->validate_input($get_data['payment_channel']);

        //check mã hash_value va trang thai thanh toan


        // if ($get_data['order_id']) {
            // thanh toán thành công
        if($variable['transaction_status'] == '000'){
            $hashValidated = "SUCCESS";
        }elseif ($variable['transaction_status'] == '001') {
                // giao dịch bị pending chờ khách hàng thanh toán
            $transStatus = "Giao dịch Pendding";
            $hashValidated = "PENDING";
        }elseif ($variable['transaction_status'] == '002' || $variable['transaction_status'] == '003' || $variable['transaction_status'] == '999') {
                // Thanh toán lỗi giao dich k thành công
            $transStatus = "Giao dịch thất bại";
            $hashValidated = "FAILED_PAYMENT";
        }
        // }
        // else {
        //     $transStatus = "Giao dịch thất bại";
        //     $hashValidated = "INVALID_HASH";
        // }

        if ($hashValidated == "SUCCESS") {
            $urlReturn = 'http://autosale.topicanative.edu.vn/successfull?checksum_topica=' . $checksum_topica;
            header("Location: " . $urlReturn);
        }  elseif($hashValidated == "PENDING" || $hashValidated == "FAILED_PAYMENT") {
            $urlReturn = 'http://autosale.topicanative.edu.vn/cancel/index';
            header("Location: " . $urlReturn);

        }
    }

    public function validate_input($data) {
        if (isset($data) && $data != null) {
            return $data;
        } else {
            return 'Not value!';
        }
    }

}
