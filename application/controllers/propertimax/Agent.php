<?php

defined('BASEPATH') or exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . 'libraries/Base_controller.php';
require_once APPPATH . 'libraries/sendgrid-php/sendgrid-php.php';

/**
 * Description of Adminpanel
 *
 * @package         CodeIgniter
 * @subpackage      PropertiMax
 * @category        Controller
 * @author          Edward An
 * @license         MIT
 */
class Agent extends Base_controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->library('email');
        $this->load->model('Agent_Model');
        $this->load->model('Customer_Model');
        $this->load->model('Common_Model');
    }

    /** PropertiMax - 2.0 API
     * Requirements:
     * Test URL: http://localhost/max/index.php/Agent/refNumber
     * URL: http://54.252.238.45/index.php/Agent/refNumber
     * Transmission method: POST
     * Parameters: email, password
     */
    public function refNumber()
    {
        // $unique_name = md5(time());
        // $rand = mcrypt_create_iv(5, MCRYPT_DEV_URANDOM);

        $this->savelog('refNumber'); // Log ($function_name);
        //
        $result = $this->uniqid_base36('agent');
        return $this->json_encode_msgs($result);
    }

    public function tester()
    {
        // $rand = mcrypt_create_iv(5, MCRYPT_DEV_URANDOM);
        // $open_home_time = '2017-11-06 13:33:09';
        // $date = strtotime($open_home_time);
        // $result = date('d-M-Y', $date);

        $agent_id = $this->input->post_get('agent_id');

        $agent_info = $this->Agent_Model->selectTargetAgentList($agent_id);
        $agency_id = $agent_info[0]['agency_id'];

        $result = $this->Agent_Model->selectSearchAssist($agency_id, $agent_id);
        return $this->json_encode_msgs($result);
    }

    public function checkprce($price)
    {
        if (ctype_digit($price)) {
            return "$" . $price;
        } else {
            return $price;
        }
    }

    protected function chkLogged()
    {
        $logged = 0;

        if (isset($_SESSION['SESS_AGENT_TOKEN']))
            $logged = 1;

        return $logged;
    }

    /** PropertiMax - 2.0 API
     * Requirements:
     * Test URL: http://localhost/max/index.php/Agent/signInEmail
     * URL: http://54.252.238.45/index.php/Agent/signInEmail
     * Transmission method: POST
     * Parameters: email, password
     */
    public function signInEmail()
    {
        $email = $this->input->post_get('email');
        $password = $this->input->post_get("password");
        $data = $this->Agent_Model->selectAgentEmail($email);

        // Check Email and Password
        if ($data->chk_email && $this->comparePassword($password, $data->agent_password)) {

            $token_agent = $this->token_keys();
            $this->session->set_userdata('SESS_AGENT_ID', $data->agent_id);
            $this->session->set_userdata('SESS_AGENT_TOKEN', $token_agent);
            $this->session->set_userdata('SESS_ADMIN_FLAG', $data->admin_flag);

            $result = $this->Agent_Model->selectTargetAgentList($data->agent_id);
            $this->json_encode_msgs($result);
            return;
        } else {
            $this->errorMsg("Email or Password is Incorrect");
            return;
        }
    }

    public function validateLogin()
    {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $data = $this->Agent_Model->selectAgentEmail($email);

        // Check Email and Password
        if ($data->chk_email && $this->comparePassword($password, $data->agent_password)) {
            $data = "success";
            $this->json_encode_msgs($data);
            return;
        } else {
            $data = "incorrect";
            $this->json_encode_msgs($data);
            return;
        }
    }

    /** PropertiMax - 2.0 API
     * Requirements:
     * Test URL: http://localhost/max/index.php/Agent/signUp
     * URL: http://54.252.238.45/index.php/Agent/signUp
     * Transmission method: POST
     * Parameters: name, email, password
     */
    public function signUp()
    {

        $first_name = $this->input->post_get('first_name');
        $last_name = $this->input->post_get('last_name');
        $email = $this->input->post_get('email');
        $licence_id = $this->input->post_get('licence_id');
        $agency_id = $this->input->post_get('agency_id');
        $password = $this->input->post_get("password");

        $db_data = $this->Agent_Model->selectAgentEmail($email);
        $data = $db_data;

        // Exist Email		
        if ($data->chk_email) {
            $this->errorMsg("This email already exists");
            return;
        } else { // New Registration
            $location = array();
            $location_id = $this->Common_Model->insertLocation($location);
            $agent_code = $this->uniqid_base36('agent'); // Gen Unique Code

            if ($location_id > 0) {
                $agent = array();
                $agent['agent_code'] = $agent_code;
                $agent['agent_first_name'] = $first_name;
                $agent['agent_last_name'] = $last_name;
                $agent['agent_password'] = password_hash($password, PASSWORD_DEFAULT);
                $agent['agent_license'] = $licence_id;
                $agent['agent_email'] = $email;
                $agent['fk_agency_id'] = $agency_id;
                $agent['fk_location_id'] = $location_id;

                $result = $this->Agent_Model->inserAgent($agent);

                $this->json_encode_msgs($result);
                return;
            }
        }
    }

    // signup new thilan
    public function signUp2()
    {

        $first_name = $this->input->post_get('first_name');
        $last_name = $this->input->post_get('last_name');
        $email = $this->input->post_get('email');
        $licence_id = $this->input->post_get('licence_id');
        $agency_id = 0;
        $password = $this->input->post_get("password");

        $db_data = $this->Agent_Model->selectAgentEmail($email);
        $data = $db_data;

        // Exist Email		
        if ($data->chk_email) {
            $this->errorMsg("This email already exists");
            return;
        } else { // New Registration

            $agent_code = $this->uniqid_base36('agent'); // Gen Unique Code
            $agent = array();
            $agent['agent_code'] = $agent_code;
            $agent['agent_first_name'] = $first_name;
            $agent['agent_last_name'] = $last_name;
            $agent['agent_password'] = password_hash($password, PASSWORD_DEFAULT);
            $agent['agent_license'] = $licence_id;
            $agent['agent_email'] = $email;
            //$agent['fk_agency_id'] = $agency_id;
            $agent['fk_agent_title_id'] = 0;
            //            $agent['fk_brand_id'] = 0;
            $agent['verify_flag'] = 1;

            $result = $this->Agent_Model->inserAgent($agent);
            $this->json_encode_msgs($result);
            return;
        }
    }

    public function verify()
    {
        $id = base64_decode($this->input->get("ghk"));
        $this->Agent_model->updateProfile(array("verify_flag" => 1), $id);
        redirect('Admin/logIn', 'refresh');
    }


    public function verifyRegistedEmail()
    {
        $email = $_POST['email'];
        $db_data = $this->Agent_Model->selectAgentEmail($email);
        $data = $db_data;
        $this->json_encode_msgs($data);
        return;
    }

    /** PropertiMax - 2.0 API
     * Requirements:
     * Test URL: http://localhost/max/index.php/Agent/agentList
     * URL: http://54.252.238.45/index.php/Agent/agentList
     * Transmission method: POST
     * Parameters: name, email, password
     */
    public function agentList()
    {
        $agent_name = $this->input->post_get('agent_name');
        $agent_list = $this->Agent_Model->selectAgentList($agent_name);

        $this->json_encode_msgs($agent_list);
        return;
    }

    /** PropertiMax - 2.0 API
     * Requirements: for Agent (Active Listings)
     * Test URL: http://localhost/max/index.php/Agent/agentPropertyList
     * URL: http://54.252.238.45/index.php/Agent/agentPropertyList
     * Transmission method: POST
     * Parameters: name, email, password
     */
    public function agentPropertyList()
    {

        $agent_id = $this->input->post_get('agent_id'); // Session

        $result = $this->Agent_Model->selectAgentPropertyList($agent_id);

        $this->json_encode_msgs($result);
        return;
    }

    /** PropertiMax - 2.0 API
     * Requirements: for Agent (Active Listings)
     * Test URL: http://localhost/max/index.php/Agent/agentOpenHomeList
     * URL: http://54.252.238.45/index.php/Agent/agentOpenHomeList
     * Transmission method: POST
     * Parameters: name, email, password
     */
    public function agentOpenHomeList()
    {

        $agent_id = $this->input->post_get('agent_id'); // Session

        $OpenHomeTimeResult = $this->Agent_Model->selectAgentOpenHomeId($agent_id);
        $OpenHome_size = $OpenHomeTimeResult->num_rows();

        $result = array();
        $datesc = array();

        if ($OpenHome_size > 0) {
            $result_time = array();
            $result_info = array();

            for ($irow = 0; $irow < $OpenHome_size; $irow++) {
                $OpenHomeTime = $OpenHomeTimeResult->result_array()[$irow];
                $open_home_time = $OpenHomeTime['open_home_time'];
                array_push($result, $open_home_time);
                //$OpenHomeList = $this->Agent_Model->selectAgentOpenHomeList($agent_id, $result[0]);
                //                print_r($OpenHomeTime);
                //                exit();

                if ($irow === ($OpenHome_size - 1)) {

                    for ($yrow = 0; $yrow < count($result); $yrow++) {
                        $OpenHomeList = $this->Agent_Model->selectAgentOpenHomeList($agent_id, $result[$yrow]);
                        $OpenHomeList_size = $OpenHomeList->num_rows();
                        $date = date('d M Y', strtotime($result[$yrow]));
                        $dtsa = array("date" => $date);
                        $datesc[$yrow] = $dtsa;
                        $datesc[$yrow]["property"] = array();


                        for ($indexff = 0; $indexff < $OpenHomeList_size; $indexff++) {
                            $Property_info = $OpenHomeList->result_array()[$indexff];

                            $property_id = $Property_info['property_id'];
                            $line3 = "";
                            if ($Property_info['fk_suburb_id']) {
                                $line3 = $this->Common_Model->get_suburb_from_id($Property_info['fk_suburb_id'])->suburb_name;
                            }
                            $propertyline = array(
                                "line1" => $Property_info['property_title'] . " " . $Property_info['property_show_price'],
                                "line2" => $Property_info['address'],
                                "line3" => $line3
                            );
                            $lable = NULL;

                            if ($Property_info['property_indate'] != NULL) {
                                if (date('Ymd', strtotime('-3 days')) <= date('Ymd', strtotime($Property_info['property_indate']))) {
                                    $lable = "New Listing";
                                }
                            }

                            if (array_key_exists('open_home_id', $Property_info)) {
                                if (date('Ymd') === date('Ymd', strtotime($Property_info['open_home_to']))) {
                                    $lable = "Open Home";
                                }
                            }


                            if ($Property_info['fk_property_status_id'] === "1") {
                                $lable = "Sold";
                            }

                            $Property_info["lable"] = $lable;

                            if ($Property_info['property_available_date'] === "0000-00-00 00:00:00") {
                                $Property_info["property_available_date"] = NULL;
                            } else {
                                $Property_info["property_available_date"] = date('D d M', strtotime($Property_info['property_available_date']));
                            }
                            $PropertiPicList = $this->Agent_Model->selectPropertiPicList($property_id);
                            $PropertiPic_size = count($PropertiPicList);

                            $result_info['property_info'] = array_merge($propertyline, $Property_info);
                            $result_info['property_pic_size'] = $PropertiPic_size;
                            $result_info['property_pic'] = $PropertiPicList;
                            array_push($datesc[$yrow]["property"], $result_info);
                        }
                    }
                }
            }
        }

        $this->json_encode_msgs($datesc);
        return;
    }

    /** PropertiMax - 2.0 API
     * Requirements: for Customer
     * Test URL: http://localhost/max/index.php/Agent/targetAgentProperty
     * URL: http://54.252.238.45/index.php/Agent/targetAgentProperty
     * Transmission method: POST
     * Parameters: name, email, password
     */
    public function targetAgentProperty()
    {

        $property_id = $this->input->post_get('property_id');

        $result = array();
        $result['property_info'] = $this->Agent_Model->selectTargetProperty($property_id);

        $PropertiPicList = $this->Agent_Model->selectPropertiPicList($property_id);
        $PropertiPic_size = count($PropertiPicList);
        $PropertiOpenList = $this->Agent_Model->selectOpenHomeList($property_id);
        $PropertiOpen_size = count($PropertiOpenList);

        $result['property_pic_size'] = $PropertiPic_size;
        $result['property_pic'] = $PropertiPicList;
        $result['property_open_size'] = $PropertiOpen_size;
        $result['property_open'] = $PropertiOpenList;

        $this->json_encode_msgs($result);
        return;
    }

    /** PropertiMax - 2.0 API
     * Requirements: for Agent
     * Test URL: http://localhost/max/index.php/Agent/targetPropertyStatus
     * URL: http://54.252.238.45/index.php/Agent/targetPropertyStatus
     * Transmission method: POST
     * Parameters: property_id, property_status_id
     */
    public function targetPropertyStatus()
    {
        $property_id = $this->input->post_get('property_id');

        $result = $this->Agent_Model->selectTargetPropertyMin($property_id);
        //$auction_date = date('d M Y :', strtotime($result['property_auction_date']));
        $date = new DateTime($result['property_auction_date']);
        $auction_date = $date->format('d M Y h:i A');

        $property_status_id = $result['fk_property_status_id'];
        $all_status = $this->Agent_Model->selectPropertyAllStatus();

        $result = array();
        $result['auction_date'] = $auction_date;
        $result['current_status'] = $property_status_id;
        $result['all_status'] = $all_status;

        $this->json_encode_msgs($result);
        return;
    }

    /** PropertiMax - 2.0 API
     * Requirements: for Agent
     * Test URL: http://localhost/max/index.php/Agent/searchMyList
     * URL: http://54.252.238.45/index.php/Agent/searchMyList
     * Transmission method: POST
     * Parameters: property_id, property_status_id
     */
    public function searchMyList()
    { // rochas change assist
        // print_r("Change pm_assist Table so is this function need a update");
        // exit();
        $key_word = $this->input->post_get('key_word');

        $agent_info = $this->Agent_Model->selectTargetAgentList($_SESSION['SESS_AGENT_ID']);
        $agency_id = $agent_info[0]['agency_id'];

        $mylist_info = $this->Agent_Model->selectSearchAssist($agency_id, $_SESSION['SESS_AGENT_ID'], $key_word);
        // function selectSearchAssist($property_id, $agency_id, $agent_id, $key_word) { // for Agent (NOT IN Qurey)
        // $main_agent_id = $mylist_info[0]['agent_id'];
        // $chk_add = $this->Agent_Model->chkAssist($main_agent_id, $agent_id); // rochas

        $result = $mylist_info;
        return $this->json_encode_msgs($result);
    }

    public function getActiveListings()
    {
        if ($this->chkLogged() == 0) {
            $this->errorMsg("Please login");
            return;
        } else {
            $activeprop = $this->Agent_Model->getallactive($_SESSION['SESS_AGENT_ID']);
            $result = array();
            $result_info = array();
            $datesc = array();
            if ($activeprop->num_rows() > 0) {
                $dtf = array("date" => null);
                //array_push($datesc, $dtf);
                $datesc[0] = $dtf;
                $datesc[0]["property"] = array();


                for ($index = 0; $index < $activeprop->num_rows(); $index++) {
                    $Property_info = $activeprop->result_array()[$index];
                    $property_id = $Property_info['property_id'];
                    $line3 = "";
                    if ($Property_info['fk_suburb_id']) {
                        $line3 = $this->Common_Model->get_suburb_from_id($Property_info['fk_suburb_id'])->suburb_name;
                    }

                    $propertyline = array(
                        "line1" => $this->checkprce($Property_info['property_show_price']),
                        "line2" => $Property_info['address'],
                        "line3" => $line3
                    );

                    if ($Property_info['property_available_date'] != "0000-00-00 00:00:00" | $Property_info['property_available_date'] != "") {
                        $Property_info['property_available_date'] = date('d M Y', strtotime($Property_info['property_available_date']));
                    }



                    $lable = NULL;

                    //                if ($Property_info['property_indate'] != NULL) {
                    //                    if (date('Ymd', strtotime('-3 days')) <= date('Ymd', strtotime($Property_info['property_indate']))) {
                    //                        $lable = "New Listing";
                    //                    }
                    //                }
                    //
                    //                if (array_key_exists('open_home_id', $Property_info)) {
                    //                    if (date('Ymd') === date('Ymd', strtotime($Property_info['open_home_to']))) {
                    //                        $lable = "Open Home";
                    //                    }
                    //                }
                    //
                    //                if (array_key_exists('property_auction_date', $Property_info)) {
                    //                    if (date('Ymd') === date('Ymd', strtotime($Property_info['property_auction_date']))) {
                    //                        $lable = date('Y-m-d', strtotime($Property_info['property_auction_date']));
                    //                    }
                    //                }
                    //                if ($Property_info['fk_property_status_id'] === "1") {
                    //                    $lable = "Sold";
                    //                }

                    switch ($Property_info['fk_property_status_id']) {
                        case 0:
                            $lable = "Unsold";
                            break;
                        case 1:
                            $lable = "Sold";
                            break;
                        case 2:
                            $lable = "Sold Prior";
                            break;
                        case 3:
                            $lable = "Sold Post";
                            break;
                        case 4:
                            $lable = "Withdrawn";
                            break;
                        case 5:
                            $lable = "Passed In";
                            break;
                        case 6:
                            $lable = "Postponed";
                            break;
                    }



                    $Property_info["lable"] = $lable;




                    $PropertiPicList = $this->Customer_Model->selectPropertiPicList($property_id);
                    $PropertiPic_size = count($PropertiPicList);

                    $result_info['property_info'] = array_merge($propertyline, $Property_info);
                    $result_info['property_pic_size'] = $PropertiPic_size;
                    $result_info['property_pic'] = $PropertiPicList;
                    array_push($datesc[0]["property"], $result_info);
                }
            }
            return $this->json_encode_msgs($datesc);
        }
    }

    public function getAllPropertiesInAgency()
    {

        if ($this->chkLogged() == 0) {
            $this->errorMsg("Please login");
            return;
        } else {
            $agencypropetyID = $this->input->post_get('agency_property_id');
            $agent_info = $this->Agent_Model->selectTargetAgentList($_SESSION['SESS_AGENT_ID']);
            $agency_id = $agent_info[0]['agency_id'];
            $agencylist_info = $this->Agent_Model->getAllpropertiesInAgency($agency_id, $agencypropetyID);
            $result = array();
            $result_info = array();
            if ($agencylist_info->num_rows() > 0) {
                for ($index = 0; $index < $agencylist_info->num_rows(); $index++) {
                    $Property_info = $agencylist_info->result_array()[$index];
                    $property_id = $Property_info['property_id'];
                    $line3 = "";
                    if ($Property_info['fk_suburb_id']) {
                        $line3 = $this->Common_Model->get_suburb_from_id($Property_info['fk_suburb_id'])->suburb_name;
                    }
                    $addbut = FALSE;
                    if ($Property_info['agent_id'] == $_SESSION['SESS_AGENT_ID']) {
                        $addbut = TRUE;
                    }
                    $propertyline = array(
                        "line1" => $this->checkprce($Property_info['property_show_price']),
                        "line2" => $Property_info['address'],
                        "line3" => $line3,
                        "addbutton" => $addbut
                    );

                    if ($Property_info['property_auction'] == 1) {
                        $Property_info['property_auction_date'] = date('d M Y', strtotime($Property_info['property_auction_date']));
                    }

                    if ($Property_info['property_available_date'] != "0000-00-00 00:00:00" | $Property_info['property_available_date'] != "") {
                        $Property_info['property_available_date'] = date('d M Y', strtotime($Property_info['property_available_date']));
                    }

                    $PropertiPicList = $this->Customer_Model->selectPropertiPicList($property_id);
                    $PropertiPic_size = count($PropertiPicList);

                    $result_info['property_info'] = array_merge($propertyline, $Property_info);
                    $result_info['property_pic_size'] = $PropertiPic_size;
                    $result_info['property_pic'] = $PropertiPicList;
                    array_push($result, $result_info);
                }
            }
            return $this->json_encode_msgs($result);
        }
    }

    /** PropertiMax - 2.0 API
     * Requirements:
     * Test URL: http://localhost/max/index.php/Agent/openHomeVisitor
     * URL: http://54.252.238.45/index.php/Agent/openHomeVisitor
     * Transmission method: POST
     * Parameters: email, password
     */
    public function openHomeVisitor()
    {

        if ($this->chkLogged() == 0) {
            $this->errorMsg("Please login");
            return;
        } else {
            $result_info = array();
            $datesc = array();
            $lldates = array();

            $open_home_id = $this->input->post_get('open_home_id');
            $visitor_log = $this->Common_Model->selectOpenHomeVisitor($open_home_id);
            if ($visitor_log->num_rows() > 0) {
                for ($index = 0; $index < $visitor_log->num_rows(); $index++) {

                    $customerDetails = $visitor_log->result_array()[$index];
                    $date = date('Y-m-d', strtotime($customerDetails["visitor_indate"]));
                    //                    $dtf = array("date" => $date);
                    array_push($lldates, $date);

                    if ($index === ($visitor_log->num_rows() - 1)) {

                        $resdates = array_values(array_unique($lldates));


                        for ($index1 = 0; $index1 < count($resdates); $index1++) {

                            $datesc[$index1] = array("date" => $resdates[$index1]);
                            $datesc[$index1]["customers"] = array();

                            for ($index2 = 0; $index2 < $visitor_log->num_rows(); $index2++) {
                                if ($resdates[$index1] == date('Y-m-d', strtotime($visitor_log->result_array()[$index2]["visitor_indate"]))) {

                                    array_push($datesc[$index1]["customers"], $visitor_log->result_array()[$index2]);
                                }
                            }
                        }
                        //array_push($result_info,$datesc);
                    }
                }
            }
            $this->json_encode_msgs($datesc);
            return;
        }
    }

    /**
     * CRUD - add(Insert)
     */

    /** PropertiMax - 2.0 API
     * Requirements:
     * Test URL: http://localhost/max/index.php/Agent/addAgency
     * URL: http://54.252.238.45/index.php/Agent/addAgency
     * Transmission method: POST
     * Parameters: name, email, password
     */
    public function addAgency()
    {

        $agency_name = $this->input->post_get('agency_name');
        $agency_pic = $this->input->post_get('agency_pic');

        // $db_data = $this->Agent_Model->chkAgency($agency_name);
        // $data = $db_data;
        // // Exist Email		
        // if ($data->chk_agency) {
        //     $this->errorMsg("This agency already exists");
        //     return;
        // } else { // New Registration
        $agency = array();
        $agency['agency_name'] = $agency_name;
        $agency['agency_pic'] = $agency_pic;
        $result = $this->Agent_Model->insertAgency($agency);

        $this->json_encode_msgs($result);
        return;
        // }
    }

    /** PropertiMax - 2.0 API
     * Requirements:
     * Test URL: http://localhost/max/index.php/Agent/addProperty
     * URL: http://54.252.238.45/index.php/Agent/addProperty
     * Transmission method: POST
     * Parameters: name, email, password
     */
    public function addProperty()
    {

        $agent_id = $this->input->post_get('agent_id'); //Session Value
        $property_title = $this->input->post_get('property_title');

        $location = array();
        $location_id = $this->Common_Model->insertLocation($location);

        // if ($location_id == 1) {
        $property = array();
        $property['property_title'] = $property_title;
        $property['property_indate'] = $this->dateTime();
        $property['property_update'] = $this->dateTime();
        $property['fk_agent_id'] = $agent_id;
        $property['fk_location_id'] = $location_id;

        $result = $this->Agent_Model->insertProperty($property);

        $this->json_encode_msgs($result);
        return;
        // }
    }

    /** PropertiMax - 2.0 API
     * Requirements:
     * Test URL: http://localhost/max/index.php/Agent/addMyList
     * URL: http://54.252.238.45/index.php/Agent/addMyList
     * Transmission method: POST
     * Parameters: name, email, password
     */
    public function addMyList()
    {

        //$agent_id = $this->input->post_get('agent_id');

        if ($this->chkLogged() == 0) {
            $this->errorMsg("Please login");
            return;
        } else {
            $property_id = $this->input->post_get('property_id');

            $data = array();
            $data['assist_agent_id'] = $_SESSION['SESS_AGENT_ID'];
            $data['assist_property_id'] = $property_id;
            $result = $this->Agent_Model->insertAddList($data);

            $this->json_encode_msgs($result);
        }
    }

    /** PropertiMax - 2.0 API
     * Requirements:
     * Test URL: http://localhost/max/index.php/Agent/addOpenHome
     * URL: http://54.252.238.45/index.php/Agent/addOpenHome
     * Transmission method: POST
     * Parameters: name, email, password
     */
    public function addOpenHome()
    {

        $property_id = $this->input->post_get('property_id');
        $open_home_from = $this->input->post_get('open_home_from');
        $open_home_to = $this->input->post_get('open_home_to');

        $data = array();
        $data['fk_property_id'] = $property_id;
        $data['open_home_from'] = $open_home_from;
        $data['open_home_to'] = $open_home_to;

        $result = $this->Agent_Model->insertOpenHome($data);

        $this->json_encode_msgs($result);
        return;
    }

    /** PropertiMax - 2.0 API
     * Requirements:
     * Test URL: http://localhost/max/index.php/Agent/addVisitor
     * URL: http://54.252.238.45/index.php/Agent/addVisitor
     * Transmission method: POST
     * Parameters: name, email, password
     */
    public function addVisitor()
    {
        $customer_id = $this->input->post_get('customer_id');
        $open_home_id = $this->input->post_get('open_home_id');

        $visitor_cnt = $this->Common_Model->selectCntVisitor($customer_id, $open_home_id);

        if ($visitor_cnt > 0) {
            $this->errorMsg("It is already registered.");
        } else {
            $data = array();
            $data['fk_open_home_id'] = $open_home_id;
            $data['fk_customer_id'] = $customer_id;
            $data['visitor_indate'] = $this->dateTime();

            $result = $this->Common_Model->insertVisitor($data);

            $this->json_encode_msgs($result);
        }
        return;
    }

    /**
     * CRUD - mod(Update)
     */

    /** PropertiMax - 2.0 API
     * Requirements: for Customer
     * Test URL: http://localhost/max/index.php/Agent/modPropertyStatus
     * URL: http://54.252.238.45/index.php/Agent/modPropertyStatus
     * Transmission method: POST
     * Parameters: name, email, password
     */
    public function modPropertyStatus()
    {
        $data = array();
        $property_id = $this->input->post_get('property_id');
        $data['property_id'] = $property_id;
        $data['fk_property_status_id'] = $this->input->post_get('property_status');
        $date = new DateTime($this->input->post_get('auction_date'));

        //$auction_date=$date->format('Y-m-d h:i:s'); //2017-10-10 09:00:00
        $data['property_auction_date'] = $date->format('Y-m-d H:i:s');
        $data['property_update'] = $this->dateTime();
        $result = $this->Agent_Model->updatePropertyStatus($data, $property_id);

        $this->json_encode_msgs($result);
        return;
    }

    /**
     * CRUD - rem(Delete)
     */

    /** PropertiMax - 2.0 API
     * Requirements:
     * Test URL: http://localhost/max/index.php/Agent/remAgent
     * URL: http://54.252.238.45/index.php/Agent/remAgent
     * Transmission method: POST
     * Parameters: agent_id
     */
    public function remAgent()
    {
        if (!empty($this->input->post_get('agent_id'))) {
            foreach ($this->input->post_get('agent_id') as $agent_id) {
                $chk_result = $this->Agent_Model->deleteAgent($agent_id);
                if ($chk_result == 0) {
                    $this->json_encode_msgs(false);
                    return;
                }
            }
        }

        $this->json_encode_msgs(true);
        return;
    }

    /** PropertiMax - 2.0 API
     * Requirements:
     * Test URL: http://localhost/max/index.php/Agent/remOpenHome
     * URL: http://54.252.238.45/index.php/Agent/remOpenHome
     * Transmission method: POST
     * Parameters: name, email, password
     */
    public function remOpenHome()
    {
        $open_home_id = $this->input->post_get('open_home_id');

        $chk_visitoer = $this->Common_Model->selectOpenHomeVisitor($open_home_id);
        $visitor_size = count($chk_visitoer);

        if ($visitor_size > 0) {
            $this->errorMsg("It is already registered and can not be deleted.");
        } else {
            $result = $this->Agent_Model->deleteOpenHome($open_home_id);
            $this->json_encode_msgs($result);
        }
        return;
    }

    ///////////////////////////////////////////////////////////////////
    /**
     * Requirements:
     * URL: http://52.62.90.111/index.php/User/updateFbToken
     * Transmission method: POST or GET
     * Parameters: user_id(session), fb_token
     */
    public function updateFbToken()
    {
        if (!isset($_SESSION[parent::SESS_USERID])) {
            $this->errorMsg("Please login");
            return;
        } else {
            $fb_token = $this->input->post_get('fb_token');
            $result = $this->User_Model->updateFbToken($_SESSION[parent::SESS_USERID], $fb_token);
            if ($result === true) {
                $this->json_encode_msgs("updated");
            } else {
                $this->errorMsg("update error");
            }
        }
    }

    // radar functionalities with agents.
    // thilan 
    public function addgeolocation()
    {
        if ($this->chkLogged() == 0) {
            $this->errorMsg("Please login");
            return;
        } else {
            $params["agent_id"] = $_SESSION['SESS_AGENT_ID'];
            $params = $this->input->post(array("long", "lat"));
            $this->Agent_Model->insertorupdateagentlocation($params, $params["agent_id"]);
            $this->json_encode_msgs("updated");
        }
    }

    public function getRequestsWithinRadius()
    {
        $lat = $this->input->post_get('lat');
        $lng = $this->input->post_get('lng');
        $radius = $this->input->post_get('radius');
        if ($lat === "" | $lng === "") {
            $this->errorMsg("Please Enable the Location");
            return;
        }
        $result = $this->Agent_Model->getAllRequestsWithDistance($lat, $lng, $radius);
        $this->json_encode_msgs($result);
    }
}

// END
