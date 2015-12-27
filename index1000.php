<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class One23 extends CI_Controller {

    var $CI;

    public function __construct() {
        parent::__construct();
        $this->CI = & get_instance();
        $this->CI->load->library('One23Payment');
    }

     public function index() {
     }


    public function response() {
        $return_data = $this->input->post();

        if ($return_data) {
            $decrypt = $this->CI->one23payment->decrypt($return_data);

            $respData = (array) simplexml_load_string($decrypt, 'SimpleXMLElement', LIBXML_NOCDATA);
        } else {
            $response = array(
                'status' => 0,
                'msg' => 'Du lieu khong hop le',
            );
            $this->response($response);
            return FALSE;
        }
    }

}
