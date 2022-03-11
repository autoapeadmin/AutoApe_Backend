<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require APPPATH . 'libraries/Base_controller.php';

/**
 * @access rochas
 * @package Account
 * @link Commercial: https://propertimax.co.nz/Docs
 * Transmission method: GET, POST, PUT, DELETE
 */
class Docs extends Base_controller {

    public function __construct() { 
        parent::__construct();
        $this->load->model('REST_Model');
    }

    public function index() { // Docs
        $result["show"] = "access";

        $this->load->view('docs/includes/head');
        $this->load->view('docs/includes/header');
        $this->load->view('docs/includes/sidebar', $result);  
        $this->load->view('docs/docs/access');
        $this->load->view('docs/includes/footer');
        $this->load->view('docs/includes/jsplugins');
    }

    public function request() { // Docs
        $result["show"] = "request";

        $this->load->view('docs/includes/head');
        $this->load->view('docs/includes/header');
        $this->load->view('docs/includes/sidebar', $result);  
        $this->load->view('docs/docs/request');
        $this->load->view('docs/includes/footer');
        $this->load->view('docs/includes/jsplugins');
    }

    public function response() { // Docs
        $result["show"] = "response";

        $this->load->view('docs/includes/head');
        $this->load->view('docs/includes/header');
        $this->load->view('docs/includes/sidebar', $result);  
        $this->load->view('docs/docs/response');
        $this->load->view('docs/includes/footer');
        $this->load->view('docs/includes/jsplugins');
    }

    public function encryption() { // Docs
        $result["show"] = "encryption";

        $this->load->view('docs/includes/head');
        $this->load->view('docs/includes/header');
        $this->load->view('docs/includes/sidebar', $result);  
        $this->load->view('docs/docs/encryption');
        $this->load->view('docs/includes/footer');
        $this->load->view('docs/includes/jsplugins');
    }

    public function validation() { // Docs
        $result["show"] = "validation";

        $this->load->view('docs/includes/head');
        $this->load->view('docs/includes/header');
        $this->load->view('docs/includes/sidebar', $result);  
        $this->load->view('docs/docs/validation');
        $this->load->view('docs/includes/footer');
        $this->load->view('docs/includes/jsplugins');
    }

    public function rate() { // Docs
        $result["show"] = "rate";

        $this->load->view('docs/includes/head');
        $this->load->view('docs/includes/header');
        $this->load->view('docs/includes/sidebar', $result);  
        $this->load->view('docs/docs/rate');
        $this->load->view('docs/includes/footer');
        $this->load->view('docs/includes/jsplugins');
    }

    public function getAgent() { // Docs
        $result["show"] = "get_agent";

        $this->load->view('docs/includes/head');
        $this->load->view('docs/includes/header');
        $this->load->view('docs/includes/sidebar', $result);  
        $this->load->view('docs/docs/getAgent');
        $this->load->view('docs/includes/footer');
        $this->load->view('docs/includes/jsplugins');
    }

    public function postAgent() { // Docs
        $result["show"] = "post_agent";

        $this->load->view('docs/includes/head');
        $this->load->view('docs/includes/header');
        $this->load->view('docs/includes/sidebar', $result);  
        $this->load->view('docs/docs/postAgent');
        $this->load->view('docs/includes/footer');
        $this->load->view('docs/includes/jsplugins');
    }

    public function postPropertySale() { // Docs
        $result["show"] = "post_property_sale";

        $this->load->view('docs/includes/head');
        $this->load->view('docs/includes/header');
        $this->load->view('docs/includes/sidebar', $result);  
        $this->load->view('docs/docs/postPropertySale');
        $this->load->view('docs/includes/footer');
        $this->load->view('docs/includes/jsplugins');
    }

    public function postPropertyRental() { // Docs
        $result["show"] = "post_property_rental";

        $this->load->view('docs/includes/head');
        $this->load->view('docs/includes/header');
        $this->load->view('docs/includes/sidebar', $result);  
        $this->load->view('docs/docs/postPropertyRental');
        $this->load->view('docs/includes/footer');
        $this->load->view('docs/includes/jsplugins');
    }

    public function location() { // Docs
        $result["show"] = "location";
        $result["locations"] = $this->REST_Model-> getAllLocationList();

        $this->load->view('docs/includes/head');
        $this->load->view('docs/includes/header');
        $this->load->view('docs/includes/sidebar', $result);  
        $this->load->view('docs/docs/location');
        $this->load->view('docs/includes/footer');
        $this->load->view('docs/includes/jsplugins');
    }


}