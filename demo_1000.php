<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class demo_1000 extends CI_Controller {

    function index() {
        $this->load->model("m_care_history_month");
        $this->m_care_history_month->key_table = "02_2016";
        $this->m_care_history_month->get_one(array());
    }

}
