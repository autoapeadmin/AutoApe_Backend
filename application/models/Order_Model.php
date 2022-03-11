<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Order_Model
 *
 * @author mac
 */
class Order_Model extends CI_Model {

    function saveOrder($data) {
        $this->db->insert('pm_order', $data);
        return $this->db->insert_id();
    }

    function updateOrder($id,$data) {
        $this->db->where('order_id', $id);
        $this->db->update('pm_order', $data);
    }

    function chkCode($co_code) {
        $this->db->select('
            co_category,
            IFNULL(pm_coupon.co_discount, 0) AS co_discount,
            count(pm_coupon.co_code) AS chk_code
        ');
        $this->db->from('pm_coupon');
        $this->db->where('pm_coupon.co_active', 0);
        $this->db->where('pm_coupon.co_code', $co_code);
        $query = $this->db->get();
        return $query;
    }
}
