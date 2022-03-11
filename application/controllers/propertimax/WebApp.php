<?php

function cors()
{

    // Allow from any origin
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
        // you want to allow, and if so:
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day

        header('content-type: application/json; charset=utf-8');
        header("access-control-allow-origin: https://www.propertimax.co.nz/");
        header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition, Content-Description');
    }

    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            // may also be using PUT, PATCH, HEAD etc
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

        exit(0);
    }

    echo "You have CORS!";
}

defined('BASEPATH') or exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require APPPATH . 'libraries/Base_controller.php';

/**
 * Description of WebApp
 *
 * @author mac
 */
class WebApp extends Base_controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Customer_Model');
        $this->load->model('Common_Model');
        $this->load->model('Agent_Model');
        $this->load->model('WebadminModel');
        $this->load->library('image_lib');
        $this->load->helper('string');
    }

    protected function chkLogged()
    {
        $logged = 0;

        if (isset($_SESSION['SESS_CUSTOMER_TOKEN']))
            $logged = 1;

        return $logged;
    }

    protected function getallmaxlist()
    {
        $rentthismonth = array();
        $allsales = array();
        $rentnow = array();
        $rentnow2 = array();

        if (count($this->maxPropertyList($this->Customer_Model->selectMaxList($_SESSION['SESS_CUSTOMER_ID'], 0, 0, 0))) > 0) {
            $allsales = $this->maxPropertyList($this->Customer_Model->selectMaxList($_SESSION['SESS_CUSTOMER_ID'], 0, 0, 0))[0]["property"];
        }

        if (count($this->maxPropertyList($this->Customer_Model->selectMaxList($_SESSION['SESS_CUSTOMER_ID'], 1, 1))) > 0) {
            $rentnow = $this->maxPropertyList($this->Customer_Model->selectMaxList($_SESSION['SESS_CUSTOMER_ID'], 1, 1))[0]["property"];
        }

        if (count($this->maxPropertyList($this->Customer_Model->selectMaxList($_SESSION['SESS_CUSTOMER_ID'], 1, 2))) > 0) {
            $rentthismonth = $this->maxPropertyList($this->Customer_Model->selectMaxList($_SESSION['SESS_CUSTOMER_ID'], 1, 2))[0]["property"];
        }

        if (count($this->maxPropertyList($this->Customer_Model->selectMaxList($_SESSION['SESS_CUSTOMER_ID'], 1, 0))) > 0) {
            $rentnow2 = $this->maxPropertyList($this->Customer_Model->selectMaxList($_SESSION['SESS_CUSTOMER_ID'], 1, 0))[0]["property"];
        }

        $allrent = array_merge($rentnow, $rentthismonth);
        $allrent = array_merge($allrent, $rentnow2);

        $allmaxres = array_merge($allsales, $allrent);
        return $allmaxres;
    }

    protected function checkprce($price)
    {
        if (ctype_digit($price)) {
            return "$" . number_format($price);
        } else {
            return $price;
        }
    }




    /**
     * SITE LOCK - Start
     */
    function cominf()
    {
        $setting = $this->Common_Model->getConfig()->row(1);

        if ($setting->open_flag == 0) {
            redirect('WebApp/comingsoon', 'refresh');
        }

        // New Alvaro
        //$ip = $this->GetRealUserIp();

        //if ($ip != "47.72.212.139") {
            
        //}

        //if ($ip != "122.57.20.233") {
        //    redirect('WebApp/comingsoon', 'refresh');
        //}
    }

    function getRealUserIp()
    {
        switch (true) {
            case (!empty($_SERVER['HTTP_X_REAL_IP'])):
                return $_SERVER['HTTP_X_REAL_IP'];
            case (!empty($_SERVER['HTTP_CLIENT_IP'])):
                return $_SERVER['HTTP_CLIENT_IP'];
            case (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])):
                return $_SERVER['HTTP_X_FORWARDED_FOR'];
            default:
                return $_SERVER['REMOTE_ADDR'];
        }
    }



    public function comingsoon()
    {
        $setting = $this->Common_Model->getConfig()->row(1);

        if ($setting->open_flag == 1) {
            //redirect(base_url(), 'refresh');
        }
        $this->load->view("sitelock/index");
    }

    // public function activate_alex_view() {
    //     $_SESSION["sitelock"] = "ok";
    //     redirect('WebApp', 'refresh');
    // }
    /**
     * SITE LOCK - End
     */

    public function index($debug = 1)
    { // by rochas
        $this->cominf();

        //redirect('welcome', 'refresh');
        redirect('WebApp/comingsoon', 'refresh');
        $data = array();
        $data['page'] = "index";
        $data["searchresult"] = $this->listingsRecentlySe();
        $data['agents'] =  $this->Agent_Model->get_all_agents();

        $data['regions'] = $this->Customer_Model->selectAllRegion();
        $data['suburbs'] = $this->Customer_Model->selectAllSuburb();
        $data['city'] = $this->Customer_Model->selectAllCity();
        //get Regions,Suburbs, City:
        // $this->console_log($data);


        if ($this->chkLogged()) {
            $data["maxlist"] = $this->getallmaxlist();
        }

        $this->console_log($data);

        $this->load->view('includes/headnew', $data);
        $this->load->view('includes/headermenu');
        $this->load->view('home/index');
        $this->load->view('includes/footer');
        $this->load->view('includes/jsplugins');
    }

    public function index2($debug = 1)
    { // by rochas
        $this->cominf();

        
        $data = array();
        $data['page'] = "index";
        $data["searchresult"] = $this->listingsRecentlySe();
        $data['agents'] =  $this->Agent_Model->get_all_agents();

        $data['regions'] = $this->Customer_Model->selectAllRegion();
        $data['suburbs'] = $this->Customer_Model->selectAllSuburb();
        $data['city'] = $this->Customer_Model->selectAllCity();
        //get Regions,Suburbs, City:
        // $this->console_log($data);


        if ($this->chkLogged()) {
            $data["maxlist"] = $this->getallmaxlist();
        }

        $this->console_log($data);

        $this->load->view('includes/headnew', $data);
        $this->load->view('includes/headermenu');
        $this->load->view('home/index');
        $this->load->view('includes/footer');
        $this->load->view('includes/jsplugins');
    }

    function console_log($output, $with_script_tags = true)
    {
        $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) .
            ');';
        if ($with_script_tags) {
            $js_code = '<script>' . $js_code . '</script>';
        }
        echo $js_code;
    }

    function listingsRecentlySe()
    {
        $this->cominf();

        $data = array(
            "page" => "listings"
        );

        $savesearch = "";
        $sort = "3";
        $page = "1";

        $search_sale_flag = "0"; // 0: Sale, 1: Rent, 2: Auction
        $data["sale_flag"] = "0";
        // Location
        $suburb_id = "0";
        $city_id = "0";
        $region_id = "0";
        $sorting = "3";
        $types = array(1, 2, 3, 4, 5, 6, 7);

        $property_type = array(); // Property Type (Check Box)
        if (!empty($types)) {

            if (in_array("0", $types)) {
                array_push($property_type, "1");
                array_push($property_type, "2");
                array_push($property_type, "3");
                array_push($property_type, "4");
                array_push($property_type, "5");
                array_push($property_type, "6");
                array_push($property_type, "7");
            } else {
                foreach ($types as $property_type_id) {
                    array_push($property_type, $property_type_id);
                }
            }
        }


        $subrubs = array();
        if (!empty($suburb_id)) {
            foreach ($suburb_id as $subval) {
                array_push($subrubs, $subval);
            }
        }

        $search_name = ""; // Search Condition
        $search_price_from = "50000"; // Search Condition
        $search_price_to = "7500000"; // Search Condition
        $search_bedroom_from = "Any"; // Search Condition
        $search_bedroom_to = "6+"; // Search Condition
        $search_bathroom_from = "Any"; // Search Condition
        $search_bathroom_to = "6+"; // Search Condition
        // Sale (Checked: 1)
        $search_open_now = "0"; // Open Homes Only - 0: No, 1: Yes
        // Rental
        $search_pet = ""; // Pet Allowed - 0: No, 1: Yes
        $search_available = ""; // Available Now - 0: No, 1: Yes

        $save_search = "0"; // Save This Search

        $search = array();
        $search['search_sale_flag'] = $search_sale_flag;
        $search['search_price_from'] = $search_price_from;
        $search['search_price_to'] = $search_price_to;
        $search['search_bedroom_from'] = $search_bedroom_from;
        $search['search_bedroom_to'] = $search_bedroom_to;
        $search['search_bathroom_from'] = $search_bathroom_from;
        $search['search_bathroom_to'] = $search_bathroom_to;
        $search['search_pet'] = $search_pet;
        $search['search_open_now'] = $search_open_now;
        $search['search_available'] = $search_available;
        $search['sorting'] = $sorting;
        $search['search_name'] = $search_name;
        $search['savesearch'] = $save_search;

        $search['fk_suburb_id'] = $suburb_id;
        $search['fk_city_id'] = $city_id;
        $search['fk_region_id'] = $region_id;

        $result = $this->findSearchRecent($search, $property_type, $subrubs, $page);
        $data["searchresult"] = $result['result'];

        if ($this->input->post_get('labelSearch') == "") {
            $data["labelSearch"] = "All New Zealand";
        } else {
            $data["labelSearch"] = $this->input->post_get('labelSearch');
        }

        return $data;
    }


    public function modcoverpic()
    {

        $config['upload_path'] = './images/branchcover/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['encrypt_name'] = TRUE;


        $files = $_FILES;
        $count = count($_FILES['croppedImage']['name']);

        if (isset($_FILES)) {

            $attachName = "croppedImage";
            $config['upload_path'] = "./images/branchcover/";
            $config['allowed_types'] = '*';
            $config['encrypt_name'] = TRUE;

            $this->load->library('upload', $config);
            $config['file_name'] = "mx_" . rand(100, 15200) . ".png";
            $this->upload->initialize($config);
            $upload = $this->upload->do_upload($attachName);

            if ($upload) {
                $dataimg = $this->upload->data();
                $data = array(
                    "branc_cover_photo" => "images/branchcover/" . $dataimg["file_name"],
                );
                $this->WebadminModel->updateBranch($_SESSION['SESS_BRANCH_ID'],$data);
            } else {
            }
        }


        //$this->Agent_Model->updateProfile($data, $_SESSION['SESS_AGENT_ID']);
        //redirect(base_url("/Admin/personal"), 'refresh');
        //print_r($data);
    }


    /**
     * Summary Page
     */
    public function summary()
    { // by rochas
        $data = array(
            "page" => "index",
            "recent" => $this->Customer_Model->getRecentListings(),
            "agents" => $this->Agent_Model->get_all_agents()
        );

        $property_type = $this->input->post_get('property_type'); // Search Condition

        $type_checker = $this->Common_Model->chkPropertyType($property_type);
        if ($type_checker->chk_property_type) {
            $pages = $this->input->post_get('pages'); // pages
            $location_tab = $this->input->post_get('location_tab'); // Search Condition
            $price_tab = $this->input->post_get('price_tab'); // Search Condition

            // Param Check
            $quickSummaryConfig = $this->Customer_Model->selectQuickSummaryConfig();

            $location_tab = ($location_tab) ? $location_tab : 0;
            $price_tab = ($price_tab) ? $price_tab : 0;
            $pages = ($pages) ? $pages : 1;

            // $result['property_type_name'] = $type_checker->property_type_name;
            $result['property_type'] = $property_type;
            $_SESSION['SESS_PROPERTY_TYPE_NAME'] = $type_checker->property_type_name;

            $result['pages'] = $pages;
            $result['location_tab'] = $location_tab;
            $result['price_tab'] = $price_tab;

            $result['condition'] = $quickSummaryConfig->num_rows();
            $location_config = $quickSummaryConfig->row($location_tab);
            $price_config = $quickSummaryConfig->row($price_tab);

            $result['location_name'] = $location_config->location_name;
            $result['setups'] = $quickSummaryConfig->result();

            $page_unit = 32;
            // $page_unit = 10;
            $start = ($pages < 2) ? 0 : (($pages - 1) * $page_unit);
            $cnt_query = $this->Customer_Model->selectAllQuickSummaryPropertyList($location_config->fk_city_id, $location_config->fk_region_id, $price_config->price_min, $price_config->price_max, $property_type);
            $query = $this->Customer_Model->selectQuickSummaryPropertyList($location_config->fk_city_id, $location_config->fk_region_id, $price_config->price_min, $price_config->price_max, $property_type, $start, $page_unit);

            $summary_size = $cnt_query->num_rows();
            $result['summary_size'] = $summary_size;
            $result['page_size'] = ceil($summary_size / $page_unit);
            $result['summary'] = $query;

            $this->load->view('includes/headnew', $data);
            $this->load->view('includes/headermenu_summary');
            $this->load->view('home/summary', $result);
            $this->load->view('includes/footer');
            $this->load->view('includes/jsplugins');
        } else {
            redirect($_SERVER['HTTP_REFERER'], 'refresh');
        }
    }

    public function deleteAgent()
    {
        $idAgent = $this->input->post("idAgent");
        $idAgency = $this->input->post("idAgency");
        $AgentCode = $this->input->post("codeAgent");

        $result = $this->WebadminModel->deleteagent($idAgent, $idAgency, $AgentCode);
        return $this->json_encode_msgs($result);
    }

    public function editAgent()
    {
        //$data
        $idAgent = $this->input->post("idAgent");
        $idAgency = $this->input->post("idAgency");
        $AgentCode = $this->input->post("codeAgent");

        //$result = $this->WebadminModel->modAgent($idAgent,$data);
        return $this->json_encode_msgs($result);
    }



    public function addnewagent()
    {

        $config['upload_path'] = "./images/agent/";
        $config['allowed_types'] = '*';
        $config['max_size'] = 6310000;
        $config['overwrite'] = true;
        $config['encrypt_name'] = TRUE;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('file')) {
            $error = array('error' => $this->upload->display_errors());
            return $this->json_encode_msgs($error);
        } else {
            $agent_code = $this->uniqid_base36('agent'); // Gen Unique Code
            $randpw = random_string('alnum', 10);
            $datapic = $this->upload->data();
            $data = array(
                "agent_code" => $agent_code,
                "agent_first_name" => $this->input->post("fname"),
                "agent_last_name" => $this->input->post("lname"),
                "agent_email" => $this->input->post("email"),
                "agent_mobile" => $this->input->post("mobile"),
                "agent_phone" => $this->input->post("landline"),
                "fk_branch_id" =>  $_SESSION['SESS_BRANCH_ID'],
                "agent_pic"  => "images/agent/" . $datapic["file_name"],
                //"agent_pic" => "images/agent/" . $datapic["file_name"],
                "agent_password" => password_hash($randpw, PASSWORD_DEFAULT)
            );
            $result = $this->WebadminModel->addagent($data);
            return $this->json_encode_msgs($result);
        }
    }

    public function newListings()
    {
        $this->cominf(); //verificar que es
        $data = array(
            "page" => "listings"
        );

        //get rent or sale
        $page = "1";
        $sort = "3";
    }

    public function listings()
    {
        $this->cominf();

        $data = array(
            "page" => "listings"
        );

        $this->console_log($_GET);

        $savesearch = $this->input->get("savesrch");
        $sort = $this->input->get("sort");
        $page = $this->input->get("page");

        if (!empty($this->input->get("ajax"))) {
            if ($savesearch) {

                $save_search = $this->Customer_Model->selectTargetSearch($savesearch);
                $search = $save_search[0];
                if ($sort) {
                    $search["sorting"] = $sort;
                }

                $search['savesearch'] = "1";

                $save_result = $this->Customer_Model->selectPropertyType($savesearch);
                $savesubs = $this->Customer_Model->selectSuburbs($savesearch);

                $property_type = array(); // Property Type
                foreach ($save_result->result() as $save_property_type) {
                    array_push($property_type, $save_property_type->fk_property_type_id);
                }

                $suburbs = array(); // Suburbs
                foreach ($savesubs->result() as $save_subrub_ids) {
                    array_push($suburbs, $save_subrub_ids->fk_proprty_subrub_id);
                }

                $data["searchresult"] = $this->findSearch($search, $property_type, $suburbs);
            } else {
                $search_sale_flag = $this->input->post_get('search_sale_flag'); // 0: Sale, 1: Rent, 2: Auction
                $data["sale_flag"] = $this->input->post_get('search_sale_flag');
                // Location
                $suburb_id = $this->input->post_get('suburb_id');
                $city_id = $this->input->post_get('city_id');
                $region_id = $this->input->post_get('region_id');
                $sorting = $this->input->post_get('sort');


                $property_type = array(); // Property Type (Check Box)
                if (!empty($this->input->post_get('types'))) {

                    if (in_array("0", $this->input->post_get('types'))) {
                        array_push($property_type, "1");
                        array_push($property_type, "2");
                        array_push($property_type, "3");
                        array_push($property_type, "4");
                        array_push($property_type, "5");
                        array_push($property_type, "6");
                        array_push($property_type, "7");
                    } else {
                        foreach ($this->input->post_get('types') as $property_type_id) {
                            array_push($property_type, $property_type_id);
                        }
                    }
                }


                $subrubs = array();
                if (!empty($this->input->post_get('suburb_id'))) {
                    foreach ($this->input->post_get('suburb_id') as $subval) {
                        array_push($subrubs, $subval);
                    }
                }

                $search_name = $this->input->post_get('search_name'); // Search Condition
                $search_price_from = $this->input->post_get('search_price_from'); // Search Condition
                $search_price_to = $this->input->post_get('search_price_to'); // Search Condition
                $search_bedroom_from = $this->formatAny($this->input->post_get('search_bedroom_from')); // Search Condition
                $search_bedroom_to = $this->formatAny($this->input->post_get('search_bedroom_to')); // Search Condition
                $search_bathroom_from =  $this->formatAny($this->input->post_get('search_bathroom_from')); // Search Condition
                $search_bathroom_to = $this->formatAny($this->input->post_get('search_bathroom_to')); // Search Condition
                // Sale (Checked: 1)
                $search_open_now = $this->input->post_get('search_open_now'); // Open Homes Only - 0: No, 1: Yes
                // Rental
                $search_pet = $this->input->post_get('search_pet'); // Pet Allowed - 0: No, 1: Yes
                $search_available = $this->input->post_get('search_available'); // Available Now - 0: No, 1: Yes

                $save_search = $this->input->post_get('save_search'); // Save This Search

                $search = array();
                $search['search_sale_flag'] = $search_sale_flag;
                $search['search_price_from'] = $search_price_from;
                $search['search_price_to'] = $search_price_to;
                $search['search_bedroom_from'] = $search_bedroom_from;
                $search['search_bedroom_to'] = $search_bedroom_to;
                $search['search_bathroom_from'] = $search_bathroom_from;
                $search['search_bathroom_to'] = $search_bathroom_to;
                $search['search_pet'] = $search_pet;
                $search['search_open_now'] = $search_open_now;
                $search['search_available'] = $search_available;
                $search['sorting'] = $sorting;
                $search['search_name'] = $search_name;
                $search['savesearch'] = $save_search;

                if ($save_search == 1) { // Insert Search
                    $location = array();
                    $location['fk_suburb_id'] = $suburb_id[0];
                    $location['fk_city_id'] = $city_id;
                    $location['fk_region_id'] = $region_id;
                    $location_id = $this->Common_Model->insertLocation($location);

                    $search['search_name'] = $search_name;
                    $search['fk_location_id'] = $location_id;
                    $search_id = $this->Customer_Model->insertSearch($search);

                    foreach ($property_type as $property_type_id) {
                        $property_type_list = array();
                        $property_type_list['fk_property_type_id'] = $property_type_id;
                        $property_type_list['fk_search_id'] = $search_id;
                        $this->Customer_Model->insertPropertyType($property_type_list);
                    }

                    foreach ($subrubs as $subrubval) {
                        $subrubv = array();
                        $subrubv["fk_proprty_subrub_id"] = $subrubval;
                        $subrubv["fk_search_id"] = $search_id;
                        $this->Customer_Model->insertSuburbsinsave($subrubv);
                    }


                    $search_list = array();
                    $search_list['fk_customer_id'] = $_SESSION['SESS_CUSTOMER_ID'];
                    $search_list['fk_search_id'] = $search_id;
                    $this->Customer_Model->insertSearchList($search_list);
                }

                $search['fk_suburb_id'] = $suburb_id;
                $search['fk_city_id'] = $city_id;
                $search['fk_region_id'] = $region_id;

                $result = $this->findSearch($search, $property_type, $subrubs, $page);
                $data["searchresult"] = $result['result'];
                $data["count"] = $result['count'];
            }
        } else {

            if ($savesearch) {

                $save_search = $this->Customer_Model->selectTargetSearch($savesearch);
                $search = $save_search[0];
                if ($sort) {
                    $search["sorting"] = $sort;
                }

                $search['savesearch'] = "1";

                $save_result = $this->Customer_Model->selectPropertyType($savesearch);
                $savesubs = $this->Customer_Model->selectSuburbs($savesearch);

                $property_type = array(); // Property Type
                foreach ($save_result->result() as $save_property_type) {
                    array_push($property_type, $save_property_type->fk_property_type_id);
                }

                $suburbs = array(); // Suburbs
                foreach ($savesubs->result() as $save_subrub_ids) {
                    array_push($suburbs, $save_subrub_ids->fk_proprty_subrub_id);
                }

                $data["searchresult"] = $this->findSearch($search, $property_type, $suburbs, $page);
            } else {

                $search_sale_flag = $this->input->post_get('search_sale_flag'); // 0: Sale, 1: Rent, 2: Auction

                // Location
                $suburb_id = $this->input->post_get('suburb_id');
                $suburb_id_2 = $this->input->post_get('suburbsAuto');

                if (isset($suburb_id_2)) {
                    $suburb_id = $suburb_id_2;
                }

                $city_id = $this->input->post_get('city_id');
                $region_id = $this->input->post_get('region_id');
                $sorting = $this->input->post_get('sort');


                $property_type = array(); // Property Type (Check Box)
                if (!empty($this->input->post_get('types'))) {

                    foreach ($this->input->post_get('types') as $property_type_id) {
                        array_push($property_type, $property_type_id);
                    }
                }

                $subrubs = array();
                if (!empty($suburb_id)) {
                    foreach ($suburb_id as $subval) {
                        array_push($subrubs, $subval);
                    }
                }

                $search_name = $this->input->post_get('search_name'); // Search Condition
                $search_price_from = $this->input->post_get('search_price_from'); // Search Condition
                $search_price_to = $this->input->post_get('search_price_to'); // Search Condition
                $search_bedroom_from = $this->formatAny($this->input->post_get('search_bedroom_from')); // Search Condition
                $search_bedroom_to = $this->formatAny($this->input->post_get('search_bedroom_to')); // Search Condition
                $search_bathroom_from =  $this->formatAny($this->input->post_get('search_bathroom_from')); // Search Condition
                $search_bathroom_to = $this->formatAny($this->input->post_get('search_bathroom_to')); // Search Condition
                // Sale (Checked: 1)
                $search_open_now = $this->input->post_get('search_open_now'); // Open Homes Only - 0: No, 1: Yes
                // Rental
                $search_pet = $this->input->post_get('search_pet'); // Pet Allowed - 0: No, 1: Yes
                $search_available = $this->input->post_get('search_available'); // Available Now - 0: No, 1: Yes

                $save_search = $this->input->post_get('save_search'); // Save This Search

                $search = array();
                $search['search_sale_flag'] = $search_sale_flag;
                $search['search_price_from'] = $search_price_from;
                $search['search_price_to'] = $search_price_to;
                $search['search_bedroom_from'] = $search_bedroom_from;
                $search['search_bedroom_to'] = $search_bedroom_to;
                $search['search_bathroom_from'] = $search_bathroom_from;
                $search['search_bathroom_to'] = $search_bathroom_to;
                $search['search_pet'] = $search_pet;
                $search['search_open_now'] = $search_open_now;
                $search['search_available'] = $search_available;
                $search['sorting'] = $sorting;
                $search['search_name'] = $search_name;
                $search['savesearch'] = $save_search;

                if ($save_search == 1) { // Insert Search
                    $location = array();
                    $location['fk_suburb_id'] = $suburb_id[0];
                    $location['fk_city_id'] = $city_id;
                    $location['fk_region_id'] = $region_id;
                    $location_id = $this->Common_Model->insertLocation($location);

                    $search['search_name'] = $search_name;
                    $search['fk_location_id'] = $location_id;
                    $search_id = $this->Customer_Model->insertSearch($search);

                    foreach ($property_type as $property_type_id) {
                        $property_type_list = array();
                        $property_type_list['fk_property_type_id'] = $property_type_id;
                        $property_type_list['fk_search_id'] = $search_id;
                        $this->Customer_Model->insertPropertyType($property_type_list);
                    }

                    foreach ($subrubs as $subrubval) {
                        $subrubv = array();
                        $subrubv["fk_proprty_subrub_id"] = $subrubval;
                        $subrubv["fk_search_id"] = $search_id;
                        $this->Customer_Model->insertSuburbsinsave($subrubv);
                    }


                    $search_list = array();
                    $search_list['fk_customer_id'] = $_SESSION['SESS_CUSTOMER_ID'];
                    $search_list['fk_search_id'] = $search_id;
                    $this->Customer_Model->insertSearchList($search_list);
                }

                $search['fk_suburb_id'] = $suburb_id;
                $search['fk_city_id'] = $city_id;
                $search['fk_region_id'] = $region_id;

                $data["searchresult"] = $this->findSearch($search, $property_type, $subrubs, $page);
            }
        }

        $this->console_log("Flag: " . $this->input->post_get('search_sale_flag'));


        $data["sale_flag"] = $this->input->post_get('search_sale_flag');

        $data["labelSearch"] = $this->input->post_get('labelSearch');

        $data['regions'] = $this->Customer_Model->selectAllRegion();
        $data['suburbs'] = $this->Customer_Model->selectAllSuburb();
        $data['city'] = $this->Customer_Model->selectAllCity();
        $this->console_log($data);


        $this->load->view('includes/headnew', $data);
        $this->load->view('includes/headermenu');
        $this->load->view('listings', $data);
        // $this->load->view('includes/footer');
        $this->load->view('includes/jsplugins');
    }

    public function listingsAjax()
    {
        $this->cominf();

        $data = array(
            "page" => "listings"
        );

        $savesearch = $this->input->get("savesrch");
        $sort = $this->input->get("sort");
        $page = $this->input->get("page");

        if ($savesearch) {

            $save_search = $this->Customer_Model->selectTargetSearch($savesearch);
            $search = $save_search[0];
            if ($sort) {
                $search["sorting"] = $sort;
            }

            $search['savesearch'] = "1";

            $save_result = $this->Customer_Model->selectPropertyType($savesearch);
            $savesubs = $this->Customer_Model->selectSuburbs($savesearch);

            $property_type = array(); // Property Type
            foreach ($save_result->result() as $save_property_type) {
                array_push($property_type, $save_property_type->fk_property_type_id);
            }

            $suburbs = array(); // Suburbs
            foreach ($savesubs->result() as $save_subrub_ids) {
                array_push($suburbs, $save_subrub_ids->fk_proprty_subrub_id);
            }

            $data["searchresult"] = $this->findSearch($search, $property_type, $suburbs);
        } else {
            $search_sale_flag = $this->input->post_get('search_sale_flag'); // 0: Sale, 1: Rent, 2: Auction
            $data["sale_flag"] = $this->input->post_get('search_sale_flag');
            // Location
            $suburb_id = $this->input->post_get('suburb_id');
            $city_id = $this->input->post_get('city_id');
            $region_id = $this->input->post_get('region_id');
            $sorting = $this->input->post_get('sort');


            $property_type = array(); // Property Type (Check Box)
            if (!empty($this->input->post_get('types'))) {

                if (in_array("0", $this->input->post_get('types'))) {
                    array_push($property_type, "1");
                    array_push($property_type, "2");
                    array_push($property_type, "3");
                    array_push($property_type, "4");
                    array_push($property_type, "5");
                    array_push($property_type, "6");
                    array_push($property_type, "7");
                } else {
                    foreach ($this->input->post_get('types') as $property_type_id) {
                        array_push($property_type, $property_type_id);
                    }
                }
            }


            $subrubs = array();
            if (!empty($this->input->post_get('suburb_id'))) {
                foreach ($this->input->post_get('suburb_id') as $subval) {
                    array_push($subrubs, $subval);
                }
            }

            $search_name = $this->input->post_get('search_name'); // Search Condition
            $search_price_from = $this->input->post_get('search_price_from'); // Search Condition
            $search_price_to = $this->input->post_get('search_price_to'); // Search Condition
            $search_bedroom_from = $this->formatAny($this->input->post_get('search_bedroom_from')); // Search Condition
            $search_bedroom_to = $this->formatAny($this->input->post_get('search_bedroom_to')); // Search Condition
            $search_bathroom_from =  $this->formatAny($this->input->post_get('search_bathroom_from')); // Search Condition
            $search_bathroom_to = $this->formatAny($this->input->post_get('search_bathroom_to')); // Search Condition
            // Sale (Checked: 1)
            $search_open_now = $this->input->post_get('search_open_now'); // Open Homes Only - 0: No, 1: Yes
            // Rental
            $search_pet = $this->input->post_get('search_pet'); // Pet Allowed - 0: No, 1: Yes
            $search_available = $this->input->post_get('search_available'); // Available Now - 0: No, 1: Yes

            $save_search = $this->input->post_get('save_search'); // Save This Search

            $search = array();
            $search['search_sale_flag'] = $search_sale_flag;
            $search['search_price_from'] = $search_price_from;
            $search['search_price_to'] = $search_price_to;
            $search['search_bedroom_from'] = $search_bedroom_from;
            $search['search_bedroom_to'] = $search_bedroom_to;
            $search['search_bathroom_from'] = $search_bathroom_from;
            $search['search_bathroom_to'] = $search_bathroom_to;
            $search['search_pet'] = $search_pet;
            $search['search_open_now'] = $search_open_now;
            $search['search_available'] = $search_available;
            $search['sorting'] = $sorting;
            $search['search_name'] = $search_name;
            $search['savesearch'] = $save_search;

            if ($save_search == 1) { // Insert Search
                $location = array();
                $location['fk_suburb_id'] = $suburb_id[0];
                $location['fk_city_id'] = $city_id;
                $location['fk_region_id'] = $region_id;
                $location_id = $this->Common_Model->insertLocation($location);

                $search['search_name'] = $search_name;
                $search['fk_location_id'] = $location_id;
                $search_id = $this->Customer_Model->insertSearch($search);

                foreach ($property_type as $property_type_id) {
                    $property_type_list = array();
                    $property_type_list['fk_property_type_id'] = $property_type_id;
                    $property_type_list['fk_search_id'] = $search_id;
                    $this->Customer_Model->insertPropertyType($property_type_list);
                }

                foreach ($subrubs as $subrubval) {
                    $subrubv = array();
                    $subrubv["fk_proprty_subrub_id"] = $subrubval;
                    $subrubv["fk_search_id"] = $search_id;
                    $this->Customer_Model->insertSuburbsinsave($subrubv);
                }


                $search_list = array();
                $search_list['fk_customer_id'] = $_SESSION['SESS_CUSTOMER_ID'];
                $search_list['fk_search_id'] = $search_id;
                $this->Customer_Model->insertSearchList($search_list);
            }

            $search['fk_suburb_id'] = $suburb_id;
            $search['fk_city_id'] = $city_id;
            $search['fk_region_id'] = $region_id;

            $result = $this->findSearch($search, $property_type, $subrubs, $page);
            $data["searchresult"] = $result['result'];
            $data["count"] = $result['count'];
        }

        if ($this->input->post_get('labelSearch') == "") {
            $data["labelSearch"] = "All New Zealand";
        } else {
            $data["labelSearch"] = $this->input->post_get('labelSearch');
        }




        $this->console_log($data);

        $this->load->view('listingList', $data);
    }


    public function listingsRecently()
    {
        $this->cominf();

        $data = array(
            "page" => "listings"
        );

        $savesearch = "";
        $sort = "3";
        $page = "1";

        $search_sale_flag = "0"; // 0: Sale, 1: Rent, 2: Auction
        $data["sale_flag"] = "0";
        // Location
        $suburb_id = array();
        $city_id = array();
        $region_id = array();
        $sorting = "3";
        $types = array();

        $property_type = array(); // Property Type (Check Box)
        if (!empty($types)) {

            if (in_array("0", $types)) {
                array_push($property_type, "1");
                array_push($property_type, "2");
                array_push($property_type, "3");
                array_push($property_type, "4");
                array_push($property_type, "5");
                array_push($property_type, "6");
                array_push($property_type, "7");
            } else {
                foreach ($types as $property_type_id) {
                    array_push($property_type, $property_type_id);
                }
            }
        }


        $subrubs = array();
        if (!empty($suburb_id)) {
            foreach ($suburb_id as $subval) {
                array_push($subrubs, $subval);
            }
        }

        $search_name = ""; // Search Condition
        $search_price_from = "50000"; // Search Condition
        $search_price_to = "7500000"; // Search Condition
        $search_bedroom_from = "Any"; // Search Condition
        $search_bedroom_to = "6+"; // Search Condition
        $search_bathroom_from = "Any"; // Search Condition
        $search_bathroom_to = "6+"; // Search Condition
        // Sale (Checked: 1)
        $search_open_now = "0"; // Open Homes Only - 0: No, 1: Yes
        // Rental
        $search_pet = ""; // Pet Allowed - 0: No, 1: Yes
        $search_available = ""; // Available Now - 0: No, 1: Yes

        $save_search = "0"; // Save This Search

        $search = array();
        $search['search_sale_flag'] = $search_sale_flag;
        $search['search_price_from'] = $search_price_from;
        $search['search_price_to'] = $search_price_to;
        $search['search_bedroom_from'] = $search_bedroom_from;
        $search['search_bedroom_to'] = $search_bedroom_to;
        $search['search_bathroom_from'] = $search_bathroom_from;
        $search['search_bathroom_to'] = $search_bathroom_to;
        $search['search_pet'] = $search_pet;
        $search['search_open_now'] = $search_open_now;
        $search['search_available'] = $search_available;
        $search['sorting'] = $sorting;
        $search['search_name'] = $search_name;
        $search['savesearch'] = $save_search;

        $search['fk_suburb_id'] = $suburb_id;
        $search['fk_city_id'] = $city_id;
        $search['fk_region_id'] = $region_id;

        $result = $this->findSearchRecent($search, $property_type, $subrubs, $page);
        $data["searchresult"] = $result['result'];
        $data["count"] = $result['count'];

        if ($this->input->post_get('labelSearch') == "") {
            $data["labelSearch"] = "All New Zealand";
        } else {
            $data["labelSearch"] = $this->input->post_get('labelSearch');
        }

        return $data;
    }

    public function findSearch($search, $property_type, $subrubs, $page)
    {

        //        $logged = $this->chkLogged();
        //        $logged_code = 0;
        //
        //        if ($logged == 1)
        //            $logged_code = $_SESSION['SESS_CUSTOMER_ID'];

        $result = array();
        $result_info = array();

        $Count1 = $this->Customer_Model->selectCount($search, $property_type, $subrubs, $page);

        $PropertyList = $this->Customer_Model->selectSearchPropertyListAjax($search, $property_type, $subrubs, $page);

        $property_size = $PropertyList->num_rows();

        //$property_size = intval($Count1);

        if ($property_size > 0) {
            for ($irow = 0; $irow < $property_size; $irow++) {
                $Property_info = $PropertyList->result_array()[$irow];
                $property_id = $Property_info['property_id'];
                $agent_id = $Property_info['fk_agent_id'];
                $customer_id = $Property_info['fk_customer_id'];



                if ($agent_id) {
                    $result_info['target_agent'] = $this->Customer_Model->selectTargetAllAgentList($property_id);
                    $result_info['target_customer'] = [];
                }
                if ($customer_id) {
                    $result_info['target_customer'] = $this->Customer_Model->selectTargetCustomerList($property_id);
                    $result_info['target_agent'] = [];
                }


                $line3 = "";
                if ($Property_info['fk_suburb_id']) {
                    $line3 = $this->Common_Model->get_suburb_from_id($Property_info['fk_suburb_id'])->suburb_name;
                }
                $propertyline = array(
                    "line1" => $this->checkprce($Property_info['property_show_price']),
                    "line2" => str_replace(", Auckland, New Zealand", "", $Property_info['address']),
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

                if (array_key_exists('property_auction_date', $Property_info)) {
                    if (date('Ymd') === date('Ymd', strtotime($Property_info['property_auction_date']))) {
                        $lable = date('Y-m-d', strtotime($Property_info['property_auction_date']));
                    }
                }

                if ($Property_info['fk_property_status_id'] === "1") {
                    $lable = "Sold";
                }

                $Property_info["lable"] = $lable;
                if ($Property_info['property_available_date'] === "0000-00-00 00:00:00") {
                    $Property_info["property_available_date"] = NULL;
                } else {

                    $today = new DateTime();
                    $today = $today->format('Y-m-d');
                    $contractDateBegin = new DateTime($Property_info["property_available_date"]);
                    $contractDateBegin = $contractDateBegin->format('Y-m-d');

                    if ($today <= $contractDateBegin) {
                        $Property_info["property_available_date"] = "now";
                    } else {
                        $Property_info["property_available_date"] = date('jS M Y', strtotime($Property_info['property_available_date']));
                    }
                }

                $PropertiPicList = $this->Customer_Model->selectPropertiPicList($property_id);

                $PropertiPicList =  array_slice($PropertiPicList, 0, 3);

                $PropertiPic_size = count($PropertiPicList);

                if (isset($_SESSION['SESS_CUSTOMER_ID'])) {
                    $MaxList = $this->Customer_Model->cntMaxList($_SESSION['SESS_CUSTOMER_ID'], $property_id);
                } else {
                    $MaxList = 0;
                }

                //                if ($logged_code > 0) {
                //                    $MaxList = $this->Customer_Model->cntMaxList($logged_code, $property_id);
                //                } else {
                //                    $MaxList = 0;
                //                }

                //new address

                $result_info['property_info'] = array_merge($propertyline, $Property_info);
                $result_info['property_pic_size'] = $PropertiPic_size;
                $result_info['property_pic'] = $PropertiPicList;
                $result_info['max_list'] = $MaxList;
                array_push($result, $result_info);
            }
        }

        $response['result'] = $result;
        $response['count'] = $Count1;

        return $response;
    }

    public function findSearchRecent($search, $property_type, $subrubs, $page)
    {

        //        $logged = $this->chkLogged();
        //        $logged_code = 0;
        //
        //        if ($logged == 1)
        //            $logged_code = $_SESSION['SESS_CUSTOMER_ID'];

        $result = array();
        $result_info = array();

        $PropertyList = $this->Customer_Model->selectSearchPropertyListAjaxRecent($search, $property_type, $subrubs);

        $property_size = $PropertyList->num_rows();

        //$property_size = intval($Count1);

        if ($property_size > 0) {
            for ($irow = 0; $irow < $property_size; $irow++) {
                $Property_info = $PropertyList->result_array()[$irow];
                $property_id = $Property_info['property_id'];
                $agent_id = $Property_info['fk_agent_id'];
                $customer_id = $Property_info['fk_customer_id'];



                if ($agent_id) {
                    $result_info['target_agent'] = $this->Customer_Model->selectTargetAllAgentList($property_id);
                    $result_info['target_customer'] = [];
                }
                if ($customer_id) {
                    $result_info['target_customer'] = $this->Customer_Model->selectTargetCustomerList($property_id);
                    $result_info['target_agent'] = [];
                }


                $line3 = "";
                if ($Property_info['fk_suburb_id']) {
                    $line3 = $this->Common_Model->get_suburb_from_id($Property_info['fk_suburb_id'])->suburb_name;
                }

                $line1 = "";
                switch ($Property_info['price_option']) {
                    case "1":
                        $price1 = $this->checkprce($Property_info['property_show_price']);
                        $line1 = "Asking " . $price1;
                        break;
                    case "2":
                        $line1 = "Enquires Over";
                        break;
                    case "3":
                        $line1 = "Sales by Auction";
                        break;
                    case "4":
                        $line1 = "Sales by Tender";
                        break;
                    case "5":
                        $line1 = "Price by Negotiation";
                        break;
                    case "6":
                        $line1 = "Deadline Sale";
                        break;
                }

                $arr1 = explode(",", $Property_info['address']);

                $propertyline = array(
                    "line1" => $line1,
                    "line2" => str_replace(", Auckland, New Zealand", "", $arr1[0]),
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

                if (array_key_exists('property_auction_date', $Property_info)) {
                    if (date('Ymd') === date('Ymd', strtotime($Property_info['property_auction_date']))) {
                        $lable = date('Y-m-d', strtotime($Property_info['property_auction_date']));
                    }
                }

                if ($Property_info['fk_property_status_id'] === "1") {
                    $lable = "Sold";
                }

                $Property_info["lable"] = $lable;
                if ($Property_info['property_available_date'] === "0000-00-00 00:00:00") {
                    $Property_info["property_available_date"] = NULL;
                } else {

                    $today = new DateTime();
                    $today = $today->format('Y-m-d');
                    $contractDateBegin = new DateTime($Property_info["property_available_date"]);
                    $contractDateBegin = $contractDateBegin->format('Y-m-d');

                    if ($today <= $contractDateBegin) {
                        $Property_info["property_available_date"] = "now";
                    } else {
                        $Property_info["property_available_date"] = date('jS M Y', strtotime($Property_info['property_available_date']));
                    }
                }

                $PropertiPicList = $this->Customer_Model->selectPropertiPicList($property_id);

                $PropertiPicList =  array_slice($PropertiPicList, 0, 3);

                $PropertiPic_size = count($PropertiPicList);

                if (isset($_SESSION['SESS_CUSTOMER_ID'])) {
                    $MaxList = $this->Customer_Model->cntMaxList($_SESSION['SESS_CUSTOMER_ID'], $property_id);
                } else {
                    $MaxList = 0;
                }

                //                if ($logged_code > 0) {
                //                    $MaxList = $this->Customer_Model->cntMaxList($logged_code, $property_id);
                //                } else {
                //                    $MaxList = 0;
                //                }

                //new address

                $result_info['property_info'] = array_merge($propertyline, $Property_info);
                $result_info['property_pic_size'] = $PropertiPic_size;
                $result_info['property_pic'] = $PropertiPicList;
                $result_info['max_list'] = $MaxList;
                array_push($result, $result_info);
            }
        }

        $response['result'] = $result;
        return $response;
    }

    public function old_summarySearch()
    { // Don't used

        $location_tab = $this->input->post_get('location_tab'); // Search Condition
        $price_tab = $this->input->post_get('price_tab'); // Search Condition

        // Param Check
        $quickSummaryConfig = $this->Customer_Model->selectQuickSummaryConfig();

        // stdClass Object
        // (
        // [quick_summary_id] => 2
        // [price_min] => 600
        // [price_max] => 700
        // [fk_city_id] => 13
        // [fk_region_id] => 3
        // [indate] => 2019-10-05 14:23:12
        // )
        // stdClass Object
        // (
        // [quick_summary_id] => 5
        // [price_min] => 1000
        // [price_max] => 1200
        // [fk_city_id] => 51
        // [fk_region_id] => 11
        // [indate] => 2019-10-05 14:23:24
        // )
        $location_tab = ($location_tab) ? $location_tab : 0;
        $price_tab = ($price_tab) ? $price_tab : 0;

        $location_config = $quickSummaryConfig->row($location_tab);
        $price_config = $quickSummaryConfig->row($price_tab);


        $result = $this->Customer_Model->selectQuickSummaryPropertyList($location_config->fk_city_id, $location_config->fk_region_id, $price_config->price_min, $price_config->price_max);

        // print_r($result->result());
        // exit();

        return $result->result();
    }

    public function agentsearch()
    {
        $this->cominf();
        $data = array(
            "page" => "agentsearch"
        );
        $this->load->view('includes/head', $data);
        $this->load->view('includes/headermenu');
        $this->load->view('findagent');
        $this->load->view('includes/footer');
        $this->load->view('includes/jsplugins');
    }

    public function viewproperty()
    {
        // $this->cominf();
        if (!$this->input->get("pid")) {
            redirect('?log=err', 'refresh');
            return;
        }

        $data = array(
            "page" => "viewprperty",
            "property" => $this->targetProperty($this->input->get("pid"))
        );

        $this->console_log($data);

        $this->load->view('includes/head', $data);
        $this->load->view('includes/headermenu');
        $this->load->view('viewproperty');
        $this->load->view('includes/footer');
        $this->load->view('includes/jsplugins');
    }

    public function targetProperty($property_id)
    {

        $target_property = $this->Customer_Model->selectTargetProperty($property_id);

        if ($target_property) {
            $agent_id = $target_property[0]['fk_agent_id'];
            $customer_id = $target_property[0]['fk_customer_id'];
            $result = array();
            // $result['target_agent'] = $this->Customer_Model->selectTargetAgentList($agent_id);
            if ($agent_id) {
                $result['target_agent'] = $this->Customer_Model->selectTargetAllAgentList($property_id);
                $result['target_customer'] = [];
            }
            if ($customer_id) {
                $result['target_customer'] = $this->Customer_Model->selectTargetCustomerList($property_id);
                $result['target_agent'] = [];
            }


            $subrub = "";
            $city = "";
            $region = "";
            if ($target_property[0]['fk_suburb_id']) {
                $subrub = $this->Common_Model->get_suburb_from_id($target_property[0]['fk_suburb_id'])->suburb_name;
            }
            if ($target_property[0]['fk_city_id']) {
                $city = $this->Common_Model->get_city_by_id($target_property[0]['fk_city_id'])->city_name;
            }
            if ($target_property[0]['fk_region_id']) {
                $region = $this->Common_Model->get_region_by_id($target_property[0]['fk_region_id'])->region_name;
            }

            $porpertymore = array(
                'suburb_name' => $subrub,
                'city_name' => $city,
                'region_name' => $region
            );

            $priceconvert = array(
                "property_show_price" => $this->checkprce($target_property[0]['property_show_price'])
            );
            $newreplace = array_replace($target_property[0], $priceconvert);

            $result['property_info'] = array_merge($newreplace, $porpertymore);

            $PropertiPicList = $this->Customer_Model->selectPropertiPicList($property_id);
            $PropertiPic_size = count($PropertiPicList);
            $PropertiOpenList = $this->Customer_Model->selectOpenHomeList($property_id);
            $PropertiOpen_size = count($PropertiOpenList);

            if ($target_property[0]['is_private'] == "1") {
                $result['cusagent'] = $this->Customer_Model->getcustomer_agents($target_property[0]['fk_customer_id'], $target_property[0]['property_id']);
            }


            $MaxList = "";
            if (isset($_SESSION["SESS_CUSTOMER_ID"])) {
                $MaxList = $this->Customer_Model->cntMaxList($_SESSION["SESS_CUSTOMER_ID"], $property_id);
            }

            $result['property_pic_size'] = $PropertiPic_size;
            $result['property_pic'] = $PropertiPicList;
            $result['property_open_size'] = $PropertiOpen_size;
            $result['property_open'] = $PropertiOpenList;
            $result['max_list'] = $MaxList;

            return $result;
        } else {
            return FALSE;
        }
    }

    public function targetPropertyadmin($property_id)
    {

        $target_property = $this->Customer_Model->selectTargetPropertyListAdmin($property_id);

        if ($target_property) {
            $agent_id = $target_property[0]['fk_agent_id'];
            $customer_id = $target_property[0]['fk_customer_id'];
            $result = array();
            // $result['target_agent'] = $this->Customer_Model->selectTargetAgentList($agent_id);
            if ($agent_id) {
                $result['target_agent'] = $this->Customer_Model->selectTargetAllAgentList($property_id);
                $result['target_customer'] = [];
            }
            if ($customer_id) {
                $result['target_customer'] = $this->Customer_Model->selectTargetCustomerList($property_id);
                $result['target_agent'] = [];
            }

            $subrub = "";
            $city = "";
            $region = "";
            if ($target_property[0]['fk_suburb_id']) {
                $subrub = $this->Common_Model->get_suburb_from_id($target_property[0]['fk_suburb_id'])->suburb_name;
            }
            if ($target_property[0]['fk_city_id']) {
                $city = $this->Common_Model->get_city_by_id($target_property[0]['fk_city_id'])->city_name;
            }
            if ($target_property[0]['fk_region_id']) {
                $region = $this->Common_Model->get_region_by_id($target_property[0]['fk_region_id'])->region_name;
            }

            $porpertymore = array(
                'suburb_name' => $subrub,
                'city_name' => $city,
                'region_name' => $region
            );

            $priceconvert = array(
                "property_show_price" => $this->checkprce($target_property[0]['property_show_price'])
            );
            $newreplace = array_replace($target_property[0], $priceconvert);

            $result['property_info'] = array_merge($newreplace, $porpertymore);

            $PropertiPicList = $this->Customer_Model->selectPropertiPicList($property_id);
            $PropertiPic_size = count($PropertiPicList);
            $PropertiOpenList = $this->Customer_Model->selectOpenHomeList($property_id);
            $PropertiOpen_size = count($PropertiOpenList);

            if ($target_property[0]['is_private'] == "1") {
                $result['cusagent'] = $this->Customer_Model->getcustomer_agents($target_property[0]['fk_customer_id'], $target_property[0]['property_id']);
            }


            $MaxList = "";
            if (isset($_SESSION["SESS_CUSTOMER_ID"])) {
                $MaxList = $this->Customer_Model->cntMaxList($_SESSION["SESS_CUSTOMER_ID"], $property_id);
            }

            $result['property_pic_size'] = $PropertiPic_size;
            $result['property_pic'] = $PropertiPicList;
            $result['property_open_size'] = $PropertiOpen_size;
            $result['property_open'] = $PropertiOpenList;
            $result['max_list'] = $MaxList;

            return $result;
        } else {
            return FALSE;
        }
    }

    public function removemaxlist()
    {
        $propertid = $this->input->post("property_id");
        $this->Customer_Model->deleteMaxList($_SESSION["SESS_CUSTOMER_ID"], $propertid);
        echo TRUE;
    }

    public function about()
    {
        $this->cominf();
        $data = array(
            "page" => "about"
        );
        $this->load->view('includes/head', $data);
        $this->load->view('includes/headermenu');
        $this->load->view('about');
        $this->load->view('includes/footer');
        $this->load->view('includes/jsplugins');
    }

    public function career()
    {
        $this->cominf();
        $data = array(
            "page" => "career"
        );


        if ($this->input->post("caree") == "ok") {
            $config['upload_path'] = './cvs/';
            $config['allowed_types'] = 'pdf|jpg|png|doc|docx';
            $config['encrypt_name'] = TRUE;

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('userfile')) {
                $updata = $this->upload->data();
                $data["msg"] = "ok";
                $this->load->library('email');
                $configd['mailtype'] = 'html';
                $this->email->initialize($configd);

                $this->email->from('noreply@propertimax.co.nz', 'PropertiMax Web Platform');
                $this->email->to('alex.xu@propertimax.co.nz, properti.max15@gmail.com');
                //$this->email->to('thilan@imperialdigital.co.nz');
                $this->email->attach(base_url("cvs/") . $updata["file_name"]);
                $this->email->subject('C.V Submission');
                $msg = '<p>Name:' . $this->input->post("name") . '</p>';
                $msg .= '<p>Email: ' . $this->input->post("email") . '</p>';
                $msg .= '<p>Visa: ' . $this->input->post("visa") . '</p>';
                $this->email->message($msg);
                $this->email->send();
            } else {
                $error = array('error' => $this->upload->display_errors());
                print_r($error);
                exit();
            }
        }
        $this->load->view('includes/head', $data);
        $this->load->view('includes/headermenu');
        $this->load->view('career');
        $this->load->view('includes/footer');
        $this->load->view('includes/jsplugins');
    }

    public function contact()
    {
        $this->cominf();
        $data = array(
            "page" => "contact"
        );


        if ($this->input->post("cnt") == "ok") {
            $data["msg"] = "ok";
            $this->load->library('email');
            $config['protocol'] = 'smtp';
            $config['smtp_host'] = 'smtp.mandrillapp.com';
            $config['smtp_port'] = '587';
            $config['smtp_user'] = 'Propertimax';
            $config['smtp_pass'] = 'Zh4yjTIHtPb4vwD3GtdMMA';

            $config['mailtype'] = 'html'; // or html



            $this->email->initialize($config);

            $this->email->from('noreply@propertimax.co.nz', 'PropertiMax Web Platform');
            $this->email->to('alex.xu@propertimax.co.nz, properti.max15@gmail.com');
            $this->email->subject('Contact Us Message');
            $msg = '<p>Name:' . $this->input->post("name") . '</p>';
            $msg .= '<p>Email: ' . $this->input->post("email") . '</p>';
            $msg .= '<p>Tel: ' . $this->input->post("tel") . '</p>';
            $msg .= '<p>Message: ' . $this->input->post("msg") . '</p>';
            $this->email->message($msg);

            $this->email->send();
        }


        $this->load->view('includes/head', $data);
        $this->load->view('includes/headermenu');
        $this->load->view('contact');
        $this->load->view('includes/footer');
        $this->load->view('includes/jsplugins');
    }

    public function team()
    {
        $this->cominf();
        $data = array(
            "page" => "team"
        );
        $this->load->view('includes/head', $data);
        $this->load->view('includes/headermenu');
        $this->load->view('team');
        $this->load->view('includes/footer');
        $this->load->view('includes/jsplugins');
    }

    //<?php if($page=="edit"){ echo "value='" . $property['property_info']['property_title'] ."'"; }
    function adminapp($filter = null)
    {

        if (!isset($_SESSION['SESS_BRANCH_ID'])) {
            redirect('WebApp/', 'refresh');
            return;
        } else {
            //get my branch id
            $id_branch = $_SESSION["SESS_BRANCH_ID"];

            //get properties_branch
            $properties = $this->WebadminModel->propertiesListBranch($id_branch,$filter);
            $branch_info = $this->WebadminModel->selectBranch($id_branch);

            //get info properties
            //array_merge
            $fullarray = array();
            foreach ($properties as $property) {
                $val =  $this->targetPropertyadmin($property["property_id"]);
                array_push($fullarray, $val);
                $this->console_log($val);
            }

            $this->console_log($fullarray);

            $data = array(
                "page" => "adminapp",
                "properties" => $fullarray,
                "branch" => $branch_info,
                "sort"  => $filter
            );

            $this->load->view('adminbranch/includes/newAgentprofilehead', $data);
            $this->load->view('adminbranch/includes/newAgentprofilemenu');
            $this->load->view('adminbranch/includes/newAgentprofilemainmenu');
            //$this->load->view('adminbranch/first', $data);
            $this->load->view('adminbranch/newAgentprofile', $data);
            //$this->load->view('adminbranch/includes/newAgentprofilefooter');
            $this->load->view('adminbranch/includes/newAgentprofilejsplugins');
        }
    }

    function withdrawProperty($pid)
    {
        $data = array(
            "delete_flag" => "1",
        );
        $this->WebadminModel->updatewithdraw($pid, $data);
        $this->adminapp();
    }

    //Update Page 1
    function updateList1()
    {
        $data = array();
        $data['fk_property_type_id'] = $this->input->post("inputTypes");
        $data['property_title'] = $this->input->post("inputListing");
        $data['property_bedroom'] = $this->input->post("bedroom");
        $data['property_bathroom'] = $this->input->post("bathrom");
        $data['property_carpark'] = $this->input->post("carpark");
        $data['property_description'] = $this->input->post("dec");
        $propertyid = $this->input->post("propertyid");

        if ($this->input->post("land_area_type") == "0") {
            $data['property_land_meter'] = $this->input->post("inputLandArea");
            $data['property_land_hectare'] = "0";
        } else {
            $data['property_land_meter'] = "0";
            $data['property_land_hectare'] = $this->input->post("inputLandArea");
        }

        $result =  $this->Common_Model->updateProperty($data, $propertyid);

        return $this->json_encode_msgs($result);
    }

    function removeImage()
    {
        $imgId = $this->input->post("imgId");
        $result =  $this->Common_Model->deleteImage($imgId);
        return $this->json_encode_msgs($result);
    }

    //Update Page 2
    function updateList2()
    {
        $photos[] = $this->input->post("readyimg");
        $proid = $this->input->post("propertyid");
        $files = $_FILES;


        $count = count($_FILES['name']);

        if (isset($_FILES)) {

            $photos = $this->input->post("readyimg");
            $files = $_FILES;
            $count = count($_FILES['images']['name']);
            $attachName = "images";
            $config['upload_path'] = "./images/property/";
            $config['allowed_types'] = '*';
            $config['encrypt_name'] = TRUE;

            $this->load->library('upload', $config);
            foreach ($photos as $photo) {
                $pic_number = 1;
                for ($b = 0; $b < $count; $b++) {
                    if ($photo == $files['images']['name'][$b]) {
                        $_FILES[$attachName]['name'] = $files[$attachName]['name'][$b];
                        $_FILES[$attachName]['type'] = $files[$attachName]['type'][$b];
                        $_FILES[$attachName]['tmp_name'] = $files[$attachName]['tmp_name'][$b];
                        $_FILES[$attachName]['error'] = $files[$attachName]['error'][$b];
                        $_FILES[$attachName]['size'] = $files[$attachName]['size'][$b];

                        $config['file_name'] = "mx_" . rand(100, 15200) . $b;
                        $this->upload->initialize($config);

                        $upload = $this->upload->do_upload($attachName);

                        if ($upload) {
                            $dataimg = $this->upload->data();

                            $imadata = array(
                                "property_pic" => "images/property/" . $dataimg["file_name"],
                                "fk_property_id" => $proid,
                                "property_first_pic" => $pic_number
                            );
                            $this->WebadminModel->addpics($imadata);
                            $pic_number++;
                        } else {
                            $error = array('error' => $this->upload->display_errors());
                            print_r($error);
                        }
                    }
                }
            }
        }

        print_r($count);
        return $this->json_encode_msgs($count);
    }

    //Update Page 3
    function updateList3()
    {
        switch ($this->input->post("priceoption")) {
            case "1":
                $showprice = $this->input->post("pricevalue");
                $property_option_price = $showprice;
                break;
            case "2":
                $showprice = "Enquiries over " . $this->input->post("pricevalue"); //"Enquiries over " .
                $property_option_price = $this->input->post("pricevalue");
                break;
            case "3":
                $showprice = "Sales by Auction"; //Sales by Auction
                $datea = $this->input->post("pricevalue");
                $property_option_date = $datea;
                break;
            case "4":
                $showprice = "Tender closing on: " . date('d M Y', strtotime($this->input->post("pricevalue"))); //Tender
                $datea = $this->input->post("pricevalue");
                $property_option_date = $datea;
                break;
            case "5":
                $showprice = "by  Negotiation"; //by Negotitation
                break;
            case "6":
                $showprice = "Sale Required by " . date('d M Y', strtotime($this->input->post("pricevalue"))); //"Sale Required by " .
                $datea = $this->input->post("pricevalue");
                $property_option_date = $datea;
                break;
        }

        $priceoption = $this->input->post("priceoption");
        $searchprice = $this->input->post("inputSearchPrice");
        $propertyid = $this->input->post("propertyid");


        $data = array();
        $data['property_show_price'] = $showprice;
        $data['price_option'] = $priceoption;
        $data['property_option_date'] = $property_option_date;
        $data['property_option_price'] = $property_option_price;
        $data['property_hidden_price'] = $searchprice;


        //Open Homes

        $openhome = explode(',', $this->input->post("openhome"));
        $opentime = explode(',', $this->input->post("opentime"));
        $openduration = explode(',', $this->input->post("openduration"));
        $count = count($openhome);
        if ($openhome[0] != "") {
            $this->WebadminModel->delopenhome($propertyid);
            for ($f = 0; $f < $count; $f++) {
                //echo $openhome[$i] . '-' . $opentime[$i] . '-' . $openduration[$i];
                $s = $opentime[$f] . ":00";
                $timestart1 = strtotime($s);
                $timestart = date('H:i:s', $timestart1);

                $minutesToAdd = $openduration[$f] * 60;

                $timeend  = date("H:i:s", strtotime($timestart) + ($minutesToAdd)); // 15:30:00

                $opendata = array(
                    "open_home_time_from" => $timestart,
                    "open_home_time_to" => $timeend,
                    "open_home_date" => $openhome[$f],
                    "open_home_from" => $openhome[$f],
                    "fk_property_id" => $propertyid
                );
                $this->WebadminModel->addopenhome($opendata);
            }
            //remove allopen homes

        }

        $result = $this->Common_Model->updateProperty($data, $propertyid);
        //save price option
        return $this->json_encode_msgs($result);
    }


    //Update Page 4
    function updateList4()
    {
        $fk_agent_id = $this->input->post("selectAgent");
        $propertyid = $this->input->post("propertyid");

        $data = array(
            "fk_agent_id" => $fk_agent_id, //OK
        );

        $result = $this->Common_Model->updateProperty($data, $propertyid);
        return $this->json_encode_msgs($result);
    }


    function newlisting($flag, $step, $pid = null)
    {

        if (isset($_SESSION["SESS_BRANCH_ID"])) {

            $this->cominf();

            //get Agent of the branch.
            $agents = $this->WebadminModel->agentBranchList($_SESSION["SESS_BRANCH_ID"]);


            switch ($step) {
                case "1":
                    $data = array(
                        "page" => "listing",
                        "flag" => $flag,
                        "agent" => $agents
                    );
                    $this->load->view('newlisting/includes/head', $data);
                    $this->load->view('newlisting/includes/headermenu');
                    $this->load->view('newlisting/first', $data);
                    break;
                case "2":
                    //getPropertyinfo


                    //edit
                    $data = array(
                        "page" => "edit",
                        "flag" => $flag,
                        "agent" => $agents,
                        "property" => $this->targetProperty($pid)
                    );
                    $this->load->view('newlisting/includes/head', $data);
                    $this->load->view('newlisting/includes/headermenu');
                    $this->load->view('newlisting/first', $data);
                    break;
            }

            $this->console_log($data);
            $this->load->view('includes/footer');
        } else {
            redirect("/", 'refresh');
        }
    }

    public function listaproperty()
    {
        $this->cominf();
        $data = array(
            "page" => "listproperty"
        );
        $result["configs"] = $this->WebadminModel->getConfig()->row(0);

        $this->load->view('includes/head', $data);
        $this->load->view('includes/headermenu');
        $this->load->view('listaproperty', $result);
        $this->load->view('includes/footer');
        $this->load->view('includes/jsplugins');
    }



    public function potential()
    {
        $this->cominf();
        $data = array(
            "page" => "listproperty"
        );
        $result["configs"] = $this->WebadminModel->getConfig()->row(0);

        $this->load->view('includes/head', $data);
        $this->load->view('includes/headermenu');
        $this->load->view('home/potential', $result);
        $this->load->view('includes/footer');
        $this->load->view('includes/jsplugins');
    }

    public function sales()
    { // rochas
        // if (isset($_SESSION["SESS_CUSTOMER_ID"])) {
        // if ($this->chkLogged()) {
        $progress = $this->input->post_get('progress');

        $data['page'] = "listproperty";
        $this->load->view('includes/head', $data);
        $this->load->view('includes/headermenu');

        if ($progress == 2) {
            $result["configs"] = $this->WebadminModel->getConfig()->row(0);
            $data['progress'] = 2;
            $this->load->view('home/sales/progress', $data);
            $this->load->view('home/sales/progress_02', $result);
        } else if ($progress == 3) {
            $result["configs"] = $this->WebadminModel->getConfig()->row(0);
            $data['progress'] = 3;
            $this->load->view('home/sales/progress', $data);
            $this->load->view('home/sales/progress_03', $result);
        } else if ($progress == 4) {
            $result["configs"] = $this->WebadminModel->getConfig()->row(0);
            $data['progress'] = 4;
            $this->load->view('home/sales/progress', $data);
            $this->load->view('home/sales/progress_04', $result);
        } else if ($progress == 5) {
            $result["sub_contact_query"] = $this->Common_Model->getSubContact($_SESSION["SESS_CUSTOMER_ID"]);
            $result["configs"] = $this->WebadminModel->getConfig()->row(0);
            $data['progress'] = 5;
            $this->load->view('home/sales/progress', $data);
            $this->load->view('home/sales/progress_05', $result);
        } else { // progress_01
            $result["configs"] = $this->WebadminModel->getConfig()->row(0);
            $data['progress'] = 1;
            $this->load->view('home/sales/progress', $data);
            $this->load->view('home/sales/progress_01', $result);
        }

        $this->load->view('includes/footer');
        $this->load->view('includes/jsplugins');
        // } else {
        //     // redirect('webApp/signin', 'refresh');
        //     redirect(base_url());
        // }
    }

    public function salesNewList()
    { // rochas
        // if (isset($_SESSION["SESS_CUSTOMER_ID"])) {
        if ($this->chkLogged()) {
            $progress = $this->input->post_get('progress');

            $data['page'] = "listproperty";
            $this->load->view('includes/head', $data);
            $this->load->view('includes/headermenu');

            $result["sub_contact_query"] = $this->Common_Model->getSubContact($_SESSION["SESS_CUSTOMER_ID"]);
            $result["configs"] = $this->WebadminModel->getConfig()->row(0);
            $data['progress'] = 5;

            $this->load->view('home/sales/progress', $data);
            $this->load->view('home/sales/progress_05', $result);
            $this->load->view('includes/footer');
            $this->load->view('includes/jsplugins');
        } else {
            // redirect('webApp/signin', 'refresh');
            redirect(base_url());
        }
    }

    public function addSubContact()
    { // rochas
        // if (isset($_SESSION["SESS_CUSTOMER_ID"])) {
        if ($this->chkLogged()) {
            $data['sub_first_name'] = $this->input->post("sub_first_name");
            $data['sub_last_name'] = $this->input->post("sub_last_name");
            $data['sub_email'] = $this->input->post("sub_email");
            $data['sub_mobile'] = $this->input->post("sub_mobile");
            $data['sub_phone'] = $this->input->post("sub_phone");
            $data['fk_customer_id'] = $_SESSION['SESS_CUSTOMER_ID'];

            $this->Common_Model->insertSubContact($data);
            redirect('webApp/salesNewList');
        } else {
            // redirect('webApp/salesNewList', 'refresh');
            redirect(base_url());
        }
    }

    public function modSubPhoto()
    {
        $sub_contact_id = $this->input->post("sub_contact_id");

        if ($this->chkLogged()) {
            $data = array();

            if ($_FILES) {
                $pic_key = $this->pic_keys();

                $path = "./images/agent/";
                $fieldName = "sub_pic";
                $fileName = $_FILES[$fieldName]['name'];

                if ($_FILES[$fieldName]['size'] > 0) {
                    $fileNewName = $pic_key . '.' . pathinfo($fileName, PATHINFO_EXTENSION);
                    $this->addImageFile($path, $fieldName, $fileNewName);

                    $data['sub_pic'] = "images/agent/" . $fileNewName;
                    $this->Common_Model->updateSubContact($data, $sub_contact_id);
                }
            }
            redirect('webApp/salesNewList');
        } else {
            redirect(base_url());
        }
    }

    public function rental()
    {
        $data = array(
            "page" => "listproperty"
        );
        $result["configs"] = $this->WebadminModel->getConfig()->row(0);

        $this->load->view('includes/head', $data);
        $this->load->view('includes/headermenu');
        $this->load->view('home/potential', $result);
        $this->load->view('includes/footer');
        $this->load->view('includes/jsplugins');
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect(base_url());
    }

    public function advertisewithus()
    {
        $this->cominf();
        $data = array(
            "page" => "advertiseus"
        );


        if ($this->input->post("cnt") == "ok") {
            $data["msg"] = "ok";
            $this->load->library('email');
            $config['protocol'] = 'smtp';
            $config['smtp_host'] = 'smtp.mandrillapp.com';
            $config['smtp_port'] = '587';
            $config['smtp_user'] = 'Propertimax';
            $config['smtp_pass'] = 'Zh4yjTIHtPb4vwD3GtdMMA';
            $config['mailtype'] = 'html'; // or html

            $this->email->initialize($config);
            $this->email->from('noreply@propertimax.co.nz', 'PropertiMax Web Platform');
            $this->email->to('alex.xu@propertimax.co.nz, properti.max15@gmail.com');
            $this->email->subject('Advertise Request');
            $msg = '<p>Name:' . $this->input->post("name") . '</p>';
            $msg .= '<p>Email: ' . $this->input->post("email") . '</p>';
            $msg .= '<p>Tel: ' . $this->input->post("tel") . '</p>';
            $msg .= '<p>Business Name: ' . $this->input->post("bus") . '</p>';
            $msg .= '<p>Message: ' . $this->input->post("msg") . '</p>';
            $this->email->message($msg);
            $this->email->send();
        }

        $this->load->view('includes/head', $data);
        $this->load->view('includes/headermenu');
        $this->load->view('advertiseus');
        $this->load->view('includes/footer');
        $this->load->view('includes/jsplugins');
    }

    public function ourapp()
    {
        $this->cominf();
        $data = array(
            "page" => "ourapp"
        );
        $this->load->view('includes/head', $data);
        $this->load->view('includes/headermenu');
        $this->load->view('ourapp');
        $this->load->view('includes/footer');
        $this->load->view('includes/jsplugins');
    }

    public function help()
    {
        $this->cominf();
        $data = array(
            "page" => "help"
        );
        //        $this->load->view('includes/head', $data);
        //        $this->load->view('includes/headermenu');
        //        $this->load->view('help');
        //        $this->load->view('includes/footer');
        //        $this->load->view('includes/jsplugins');
        $this->load->view('MainFAQ', $data);
    }

    public function terms()
    {
        $this->cominf();
        $data = array(
            "page" => "terms"
        );
        $this->load->view('includes/head', $data);
        $this->load->view('includes/headermenu');
        $this->load->view('terms');
        $this->load->view('includes/footer');
        $this->load->view('includes/jsplugins');
    }

    public function usefullsites()
    {
        $this->cominf();
        $data = array(
            "page" => "usefullsites"
        );
        $this->load->view('includes/head', $data);
        $this->load->view('includes/headermenu');
        $this->load->view('usefullsites');
        $this->load->view('includes/footer');
        $this->load->view('includes/jsplugins');
    }

    public function profile()
    {
        $this->cominf();
        if ($this->chkLogged()) {
            $customer_id = $_SESSION['SESS_CUSTOMER_ID'];
            $customerInfo = $this->Customer_Model->selectCustomerInfo($customer_id);
            $customer_properties = $this->Common_Model->getPersonalListings($customer_id);
            $congigs = $this->Common_Model->getConfig()->row(0);

            $data = array(
                "page" => "profile",
                "customerinfo" => $customerInfo,
                "mylistings" => $customer_properties,
                "configsd" => $congigs
            );

            $this->load->view('includes/head', $data);
            $this->load->view('includes/headermenu');
            $this->load->view('profile');
            $this->load->view('includes/footer');
            $this->load->view('includes/jsplugins');
        } else {
            redirect(base_url());
        }
    }

    public function savedsearch()
    {
        $this->cominf();
        if ($this->chkLogged()) {
            $customer_id = $_SESSION['SESS_CUSTOMER_ID'];

            $savesale = $this->Customer_Model->selectSearchList($customer_id, 0);
            $saverent = $this->Customer_Model->selectSearchList($customer_id, 1);


            $customer_res = array_merge($savesale, $saverent);
            $data = array(
                "page" => "profile",
                "savesearch" => $customer_res
            );
            $this->load->view('includes/head', $data);
            $this->load->view('includes/headermenu');
            $this->load->view('profile2');
            $this->load->view('includes/footer');
            $this->load->view('includes/jsplugins');
        } else {
            redirect(base_url());
        }
    }

    public function viewagent($agentid = null){
        $result = $this->Agent_Model->selectTargetAgentList($agentid);
        $lagent =   $this->Agent_Model->selectTargetAgentListLanguages($agentid);
        $info_agent = $result[0];
    
        $this->load->view('includes/headnew', array("agent" => $info_agent,"lagent" => $lagent,"page" => "agentinfo"));
        $this->load->view('includes/headermenu');
        $this->load->view('viewagent/newAgentprofile');
        $this->load->view('viewagent/includes/newAgentprofilefooter');
        $this->load->view('viewagent/includes/newAgentprofilejsplugins');
    }

    public function agentList()
    {
        $this->cominf();
        $key_word = $this->input->post_get('agent_name');
        $company = NULL;
        $sort = "A";
        $sort_option = "1";
        $city_id = NULL;
        $region_id = NULL;
        $logged_code = 0;

        $subrubs = array();
        if (!empty($this->input->post_get('suburb_id'))) {
            foreach ($this->input->post_get('suburb_id') as $subval) {
                array_push($subrubs, $subval);
            }
        }

        // $agent_list = $this->Customer_Model->selectAgentList($key_word, $logged_code);
        $agent_list = $this->Customer_Model->selectAgentList($key_word, $logged_code, $company, $sort, $sort_option, $city_id, $region_id, $subrubs);
        $data = array(
            "page" => "agentlist",
            "agentlist" => $agent_list
        );
        $this->load->view('includes/head', $data);
        $this->load->view('includes/headermenu');
        $this->load->view('agentlist');
        $this->load->view('includes/footer');
        $this->load->view('includes/jsplugins');
    }

    public function agent()
    {
        $this->cominf();
        $agent_id = $this->input->post_get('agentid');
        $logged = $this->chkLogged();
        $logged_code = 0;

        if ($logged == 1)
            $logged_code = $_SESSION['SESS_CUSTOMER_ID'];

        $result = array();
        $result_property = array();
        $result_info = array();
        $result['target_agent'] = $this->Customer_Model->selectTargetAgentList($agent_id);

        $PropertyList = $this->Customer_Model->selectTargetPropertyList($agent_id);
        $property_size = $PropertyList->num_rows();

        if ($property_size > 0) {
            for ($irow = 0; $irow < $property_size; $irow++) {
                $Property_info = $PropertyList->result_array()[$irow];
                $property_id = $Property_info['property_id'];

                $line3 = "";
                if ($Property_info['fk_suburb_id']) {
                    $line3 = $this->Common_Model->get_suburb_from_id($Property_info['fk_suburb_id'])->suburb_name;
                }
                $propertyline = array(
                    "line1" => $Property_info['property_title'] . " " . $Property_info['property_show_price'],
                    "line2" => str_replace(", Auckland, New Zealand", "", $Property_info['address']),
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

                if (array_key_exists('property_auction_date', $Property_info)) {
                    if (date('Ymd') === date('Ymd', strtotime($Property_info['property_auction_date']))) {
                        $lable = date('Y-m-d', strtotime($Property_info['property_auction_date']));
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

                $Property_info['property_show_price'] = $this->checkprce($Property_info['property_show_price']);


                $PropertiPicList = $this->Customer_Model->selectPropertiPicList($property_id);
                $PropertiPic_size = count($PropertiPicList);

                if ($logged_code > 0) {
                    $MaxList = $this->Customer_Model->cntMaxList($logged_code, $property_id);
                } else {
                    $MaxList = 0;
                }

                $result_info['property_info'] = array_merge($propertyline, $Property_info);
                $result_info['property_pic_size'] = $PropertiPic_size;
                $result_info['property_pic'] = $PropertiPicList;
                $result_info['max_list'] = $MaxList;
                array_push($result_property, $result_info);
            }
        }

        $result['target_property'] = $result_property;

        $data = array(
            "page" => "agent",
            "agent" => $result
        );
        $this->load->view('includes/head', $data);
        $this->load->view('includes/headermenu');
        $this->load->view('agent');
        $this->load->view('includes/footer');
        $this->load->view('includes/jsplugins');
    }

    public function maxlist()
    {
        $this->cominf();
        if ($this->chkLogged()) {

            $allmaxres = $this->getallmaxlist();
            $data = array(
                "page" => "maxlist",
                "maxlist" => $allmaxres
            );
            $this->load->view('includes/head', $data);
            $this->load->view('includes/headermenu');
            $this->load->view('maxlist');
            $this->load->view('includes/footer');
            $this->load->view('includes/jsplugins');
        } else {
            redirect(base_url());
        }
    }

    public function maxPropertyList($max_result)
    {
        $result = array();
        $result_info = array();
        $PropertyList = $max_result;
        $property_size = $PropertyList->num_rows();
        $datesc = array();


        if ($property_size > 0) {
            $dtf = array("date" => null);
            //array_push($datesc, $dtf);
            $datesc[0] = $dtf;
            $datesc[0]["property"] = array();
            for ($indexlkt = 0; $indexlkt < $property_size; $indexlkt++) {

                $Property_infos = $PropertyList->result_array()[$indexlkt];

                $property_id = $Property_infos['property_id'];


                $agent_id = $Property_infos['fk_agent_id'];
                $customer_id = $Property_infos['fk_customer_id'];

                if ($agent_id) {
                    $result_info['target_agent'] = $this->Customer_Model->selectTargetAllAgentList($property_id);
                    $result_info['target_customer'] = [];
                }
                if ($customer_id) {
                    $result_info['target_customer'] = $this->Customer_Model->selectTargetCustomerList($property_id);
                    $result_info['target_agent'] = [];
                }


                $line3 = "";
                if ($Property_infos['fk_suburb_id']) {
                    $line3 = $this->Common_Model->get_suburb_from_id($Property_infos['fk_suburb_id'])->suburb_name;
                }
                $propertyline = array(
                    "line1" => $Property_infos['property_title'] . " " . $Property_infos['property_show_price'],
                    "line2" => str_replace(", Auckland, New Zealand", "", $Property_infos['address']),
                    "line3" => $line3
                );

                if ($Property_infos['property_sale_flag'] == 1) {
                    $Property_infos['property_show_price'] = $this->checkprce($Property_infos['property_show_price']) . "pw";
                }

                if ($Property_infos['is_private'] != 1) {
                    if ($this->Agent_Model->getagencyfromagent($Property_infos['fk_agent_id'])) {
                        $Property_infos['agencypic'] = $this->Agent_Model->getagencyfromagent($Property_infos['fk_agent_id'])[0]->agency_pic;
                    }
                }


                $lable = NULL;

                if ($Property_infos['property_indate'] != NULL) {
                    if (date('Ymd', strtotime('-3 days')) <= date('Ymd', strtotime($Property_infos['property_indate']))) {
                        $lable = "New Listing";
                    }
                }

                if (array_key_exists('open_home_id', $Property_infos)) {
                    if (date('Ymd') === date('Ymd', strtotime($Property_infos['open_home_to']))) {
                        $lable = "Open Home";
                    }
                }

                if (array_key_exists('property_auction_date', $Property_infos)) {
                    if (date('Ymd') === date('Ymd', strtotime($Property_infos['property_auction_date']))) {
                        $lable = date('Y-m-d', strtotime($Property_infos['property_auction_date']));
                    }
                }

                if ($Property_infos['fk_property_status_id'] === "1") {
                    $lable = "Sold";
                }



                $Property_infos["lable"] = $lable;

                if ($Property_infos['property_available_date'] === "0000-00-00 00:00:00") {
                    $Property_infos["property_available_date"] = NULL;
                } else {
                    $Property_infos["property_available_date"] = date('D d M', strtotime($Property_infos['property_available_date']));
                }




                $PropertiPicList = $this->Customer_Model->selectPropertiPicList($property_id);
                $PropertiPic_size = count($PropertiPicList);
                $MaxList = $this->Customer_Model->cntMaxList($_SESSION['SESS_CUSTOMER_ID'], $property_id);

                $result_info['property_info'] = array_merge($propertyline, $Property_infos);
                $result_info['property_pic_size'] = $PropertiPic_size;
                $result_info['property_pic'] = $PropertiPicList;
                $result_info['max_list'] = $MaxList;

                array_push($datesc[0]["property"], $result_info);
            }
        }

        return $datesc;
    }

    public function withdraw()
    {
        if (isset($_SESSION["SESS_CUSTOMER_ID"])) {
            $property_id = $this->input->post_get("pid");
            $data = array();
            $data['delete_flag'] = 1;
            $this->Common_Model->updateProperty($data, $property_id);
            redirect('WebApp/profile', 'refresh');
        }
    }

    public function formatAny($value)
    {
        if ($value == "Any") {
            return 0;
        } else if ($value == "6+") {
            return 10;
        } else {
            return $value;
        }
    }

    public function setSessions()
    {
        if (isset($_SESSION["SESS_CUSTOMER_ID"])) {
            $property_id = $this->input->post_get("pid");
            $configs = $this->WebadminModel->getConfig()->row(0);
            $propertys = $this->WebadminModel->getPropertyDetail($property_id)->row(0);
            $flags = $propertys->property_sale_flag;

            if ($flags == 1) {
                $_SESSION["SESS_PRICE"] = $configs->rental_listing_cost;
            } else {
                $_SESSION["SESS_PRICE"] = $configs->sales_listing_cost;
            }

            $_SESSION["orderid"] = "new";
            $_SESSION["pripertyid"] = $property_id;
            $this->json_encode_msgs($flags);
        }
    }
}
