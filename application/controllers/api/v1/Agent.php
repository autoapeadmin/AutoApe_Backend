<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . 'libraries/REST_Controller.php';

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Edward An
 * @license         MIT
 */
class Agent extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->library('session');
        $this->load->model('Common_Model');
        $this->load->model('Agent_Model');
        $this->load->model('REST_Model');
        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['rest_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['rest_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['rest_put']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['rest_delete']['limit'] = 50; // 50 requests per hour per user/key
    }

    /**
     * @access rochas
     * @package Account
     * @link Commercial: https://propertimax.co.nz/api/agent
     * Transmission method: GET, POST
     */

    
    /**
     * [GET] Select / Retrieve
     */
    public function rest_get() 
    {
        // $key = $this->security->xss_clean($this->input->request_headers()['access_key']);
        $key = $this->security->xss_clean($this->input->get('access_key'));

        if(empty($key)) {
            $res_result = $this->res_false(false, 'bad request');
            $this->response($res_result, REST_Controller::HTTP_BAD_REQUEST); // Errors 400

        } else {
            $agency_detail = $this->REST_Model->chkApiKey($key);
            $chk_key = $agency_detail->num_rows();

            if ($chk_key) {
                $agency_id = $agency_detail->row(0)->agency_id;
                $email = $this->security->xss_clean($this->input->get('email'));

                if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $agent_detail = $this->REST_Model->chkAgentCode($email, $agency_id);
                    $chk_agent = $agent_detail->num_rows();

                    if ($chk_agent) {
                        $result = $agent_detail->row(0);
                        $res_result = $this->res_true($result, 'agent search results');
                        $this->response($res_result, REST_Controller::HTTP_OK); // OK (200)

                    } else {
                        $res_result = $this->res_false(NULL, 'bad request');
                        $this->response($res_result, REST_Controller::HTTP_BAD_REQUEST); // Errors 400
                    }
                }
            }

            $res_result = $this->res_false(NULL, 'not found');
            $this->response($res_result, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404)
        }
    }


    /**
     * [POST] Insert / Create
     */
    public function rest_post() 
    {
        // $key = $this->security->xss_clean($this->input->request_headers()['access_key']);
        $key = $this->security->xss_clean($this->input->get('access_key'));
        $email = $this->security->xss_clean($this->input->post('email'));
        $region_id = $this->security->xss_clean($this->input->post('region'));
        $city_id = $this->security->xss_clean($this->input->post('city'));
        $suburb_id = $this->security->xss_clean($this->input->post('suburb'));
        $address = $this->security->xss_clean($this->input->post('address'));
        $lat = $this->security->xss_clean($this->input->post('lat'));
        $long = $this->security->xss_clean($this->input->post('long'));

        $first_name = $this->security->xss_clean($this->input->post('first_name'));
        $last_name = $this->security->xss_clean($this->input->post('last_name'));
        $password = $this->security->xss_clean($this->input->post('password'));
        $mobile = $this->security->xss_clean($this->input->post('mobile'));
        $phone = $this->security->xss_clean($this->input->post('phone'));
        $desc = $this->security->xss_clean($this->input->post('desc'));
        $license = $this->security->xss_clean($this->input->post('license'));
        $office = $this->security->xss_clean($this->input->post('office'));

        if (empty($key) || !filter_var($email, FILTER_VALIDATE_EMAIL) 
        || empty($region_id) || empty($city_id) || empty($suburb_id)
        || empty($first_name) || empty($last_name) || empty($password) || empty($mobile) || empty($phone)
        || empty($license) || empty($office)) {
            $res_result = $this->res_false(false, 'bad request');
            $this->response($res_result, REST_Controller::HTTP_BAD_REQUEST); // Errors 400

        } else {
            $agency_detail = $this->REST_Model->chkApiKey($key);
            $chk_key = $agency_detail->num_rows();

            if ($chk_key && empty($this->uri->segments[3])) {
                $email = $this->security->xss_clean($this->input->post('email'));
                $data = $this->Agent_Model->selectAgentEmail($email);
        
                if ($data->chk_email) {
                    $res_result = $this->res_false(false, 'bad request');
                    $this->response($res_result, REST_Controller::HTTP_BAD_REQUEST); // Errors 400
                
                } else {
                    $agency_id = $agency_detail->row(0)->agency_id;
                    $chk_location = $this->REST_Model->chkLocation($region_id, $city_id, $suburb_id)->num_rows();

                    if ($chk_location) {
                        $location = array();
                        $location['fk_suburb_id'] = (int)$suburb_id;
                        $location['fk_city_id'] = (int)$city_id;
                        $location['fk_region_id'] = (int)$region_id;
                        $location['address'] = $address;
                        $location['lat'] = $lat;
                        $location['long'] = $long;
                        $location_id = $this->Common_Model->insertLocation($location);
                        $unicode = $this->uniqid_base36('agent'); // Gen Unique Code
            
                        if ($location_id > 0) { 
                            $agent = array();
                            $agent['agent_first_name'] = $first_name;
                            $agent['agent_last_name'] = $last_name;
                            $agent['agent_password'] = password_hash($password, PASSWORD_DEFAULT);
                            $agent['agent_email'] = $email;
                            $agent['agent_description'] = $desc;
                            $agent['agent_license'] = $license;
                            $agent['agent_office'] = $office;
                            $agent['agent_mobile'] = $mobile;
                            $agent['agent_phone'] = $phone;
                            $agent['agent_code'] = $unicode;
                            $agent['fk_agency_id'] = (int)$agency_id;
                            $agent['fk_location_id'] = (int)$location_id;
                            
                            if ($_FILES) {
                                $config['upload_path'] = "./images/agent/";
                                $config['allowed_types'] = '*';
                                $config['max_size'] = 6310000;
                                $config['overwrite'] = TRUE;
                                $config['encrypt_name'] = TRUE;
                    
                                $this->load->library('upload', $config);
                    
                                if (!$this->upload->do_upload('upload_file')) {
                                    $error = array('error' => $this->upload->display_errors());
                                } else {
                                    $datapic = $this->upload->data();
                                    $agent['agent_pic'] = "images/agent/" . $datapic["file_name"];
                                }
                            }
                            $agent_id = $this->REST_Model->addAgent($agent);

                            unset($agent['fk_agency_id']);
                            unset($agent['fk_location_id']);
                            unset($agent['agent_password']);
                            unset($agent['agent_pic']);
                            $res_result = $this->res_true($agent, 'inserted the resource');
                            $this->response($res_result, REST_Controller::HTTP_CREATED); // OK (201)
                        }

                    } else {
                        $res_result = $this->res_false(false, 'bad request');
                        $this->response($res_result, REST_Controller::HTTP_BAD_REQUEST); // Errors 400
                    }
                }
            }
        }

        $res_result = $this->res_false(NULL, 'not found');
        $this->response($res_result, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404)
    }


    /**
     * [PUT] Update / Modify
     */
    public function rest_put() 
    {
        $res_result = $this->res_false(FALSE, 'not found');
        $this->response($res_result, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404)
    }

    
    /**
     * [DELETE] Delete / Destroy
     */
    public function rest_delete() 
    {
        $res_result = $this->res_false(FALSE, 'not found');
        $this->response($res_result, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404)
    }

                        // $location = array();
                        // $location_id = $this->Common_Model->insertLocation($location);
                
                        // // if ($location_id == 1) {
                        // $property = array();
                        // $property['property_title'] = $property_title;
                        // $property['property_indate'] = $this->dateTime();
                        // $property['property_update'] = $this->dateTime();
                        // $property['fk_agent_id'] = $agent_id;
                        // $property['fk_location_id'] = $location_id;
                
                        // $result = $this->Agent_Model->insertProperty($property);
}
