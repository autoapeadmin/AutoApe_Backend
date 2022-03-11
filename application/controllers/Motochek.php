<?php

defined('BASEPATH') or exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . 'libraries/Base_controller.php';
require APPPATH . "libraries/simplehtmldom_1_9_1/simple_html_dom.php";
require APPPATH . "libraries/Facebook/autoload.php";
require APPPATH . "libraries/google-api-php-client-2.2.0/vendor/autoload.php";
require APPPATH . "libraries/Motocheck/AccessControl.php";

/**
 * Description of Adminpanel
 *
 * @package         CodeIgniter
 * @subpackage      Maxauto
 * @category        Controller
 * @author          Alvaro Pavez
 * @license         MIT
 */
class Motochek extends Base_controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('Maxauto_Model');
    }

    protected function chkLogged()
    {
        $logged = 0;
        if (isset($_SESSION['SESS_CUSTOMER_TOKEN']))
            $logged = 1;
        return $logged;
    }

    public function getVehicleDetails($rego)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://sandbox.api.business.govt.nz/services/v1/companies-office/ppsr/vehicles/" . $rego . "?vehicle-id-type=plate",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "{\n  \"searchBy\": \"motorVehicle\",\n  \"legitimateSearchReason\": \"yes\",\n  \"searchByMotorVehicle\": {\n    \"registrationPlate\": \"KAR007\"\n  },\n  \"page\": 1,\n  \"pageSize\": 10\n}",
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer 48e4cfd4b119fcc6a1a4504fb587ee4a",
                "cache-control: no-cache",
                "content-type: application/json",
                "postman-token: 82ab39b4-c2d0-453a-6aa3-6f116461fe71"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $response;
        }
    }

    public function getFinancial($rego)
    {

        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://api.business.govt.nz/services/v1/companies-office/ppsr/financing-statements-search",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => "{\n  \"searchBy\": \"motorVehicle\",\n  \"legitimateSearchReason\": \"yes\",\n  \"searchByMotorVehicle\": {\n    \"registrationPlate\": \"{$rego}\"\n  },\n  \"page\": 1,\n  \"pageSize\": 10\n}",
          CURLOPT_HTTPHEADER => array(
            "authorization: Bearer ec953b4a31dd1ef3bc8a58bfe5fbfa6c",
            "cache-control: no-cache",
            "content-type: application/json",
            "postman-token: 1ee1e344-810b-ac62-240e-5dd0642df194"
          ),
        ));
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);
        
        if ($err) {
          echo "cURL Error #:" . $err;
        } else {         
        $this->json_encode_msgs($response,false);
        }
    }

    public function getFinancialTest($rego)
    {

        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://sandbox.api.business.govt.nz/services/v1/companies-office/ppsr/financing-statements-search",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => "{\n  \"searchBy\": \"motorVehicle\",\n  \"legitimateSearchReason\": \"yes\",\n  \"searchByMotorVehicle\": {\n    \"registrationPlate\": \"{$rego}\"\n  },\n  \"page\": 1,\n  \"pageSize\": 10\n}",
          CURLOPT_HTTPHEADER => array(
            "authorization: Bearer 48e4cfd4b119fcc6a1a4504fb587ee4a",
            "cache-control: no-cache",
            "content-type: application/json",
            "postman-token: 1ee1e344-810b-ac62-240e-5dd0642df194"
          ),
        ));
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);
        
        if ($err) {
          echo "cURL Error #:" . $err;
        } else {         
        print_r($response);
        }
    }


    public function getWantedList($page = 1, $region)
    {
        $objVehNew = $this->Maxauto_Model->getWantedList($page, $region);
        $this->json_encode_msgs($objVehNew);
        return ($objVehNew);
    }

    //klk

    public function motoChekVehicleUsage()
    {

        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        require_once('application/libraries/Motocheck/AccessControl.php');
        require_once('application/libraries/Motocheck/AuthenticateClientRequest.php');
        require_once('application/libraries/Motocheck/AuthenticateClient.php');
        require_once('application/libraries/Motocheck/UserNameToken.php');
        require_once('application/libraries/Motocheck/Security.php');

        $userNameToken = new UserNameToken("autoape", "welcome1");
        $security = new Security($userNameToken);
        $AuthenticateClientRequest = new AuthenticateClientRequest($security);
        $AuthenticateClient = new AuthenticateClient($AuthenticateClientRequest);


    //    $accessControl = new AccessControl();
      //  $accessControl->AuthenticateClient($AuthenticateClient);
        //$AuthenticateClientRequest = new AuthenticateClientRequest();


        //  $resp = $hello->AuthenticateClient($params);

        //af
    
        $client = new SoapClient("https://tpt.services.nzta.govt.nz/CDTPT/WebServices/security/AccessControl.asmx?wsdl");
       
        //$client->__soapCall("AuthenticateClient", array("1","1","1"));
            var_dump($client->__getFunctions()); 
        var_dump($client->__getTypes()); 
    }

    public function getCamera()
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_PORT => "443",
            CURLOPT_URL => "https://infoconnect1.highwayinfo.govt.nz:443/ic/jbi/TrafficCameras2/REST/FeedService/",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                'Content-Type: application/x-www-form-urlencoded',
                "password: Roadsafe004",
                "postman-token: f79f6c8e-9101-6a3f-948e-dc9395000ab9",
                "username: Alvaro.Pavezb"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $xml = simplexml_load_string($response);
            $content2 = str_replace(array_map(function ($e) {
                return "$e:";
            }, array_keys($xml->getDocNamespaces())), array(), $response);
            $xml2 = simplexml_load_string($content2);
            $this->json_encode_msgs($xml2);
        }
    }

    public function modProfile()
    {
        $logged = $this->chkLogged();

        if ($logged == 0) {
            $this->errorMsg("Please login");
            return;
        } else {
            $data = array();
            $data['customer_name'] = $this->input->post_get('customer_name');
            $data['customer_description'] = $this->input->post_get('customer_description');
            $data['customer_mobile'] = $this->input->post_get('customer_mobile');

            if (!empty($_FILES)) {
                if ($_FILES['profile']["error"][0] == 0) {
                    $attachName = "profile";
                    $path = "images/" . $attachName . "/";
                    $pic_name = $attachName . "_" . date('Ymdhis');
                    $picNames = array();
                    $picNames = $this->saveImageToPath($path, $pic_name, $_FILES, $attachName);
                    $exten = $this->upload->data();

                    if ($picNames) {
                        $data['customer_pic'] = $path . $picNames[0] . $exten['file_ext'];
                    }
                }
            }



            $this->Customer_Model->updateCustomer($data, $_SESSION['SESS_CUSTOMER_ID']);
            $result = $this->Customer_Model->selectCustomerInfo($_SESSION['SESS_CUSTOMER_ID']);
            $this->json_encode_msgs($result, $logged);
        }
    }

    public function getLanguages()
    {
        $flags = $this->Common_Model->selectLanguage();
        $this->json_encode_msgs($flags, "0");
    }

    /** PropertiMax - Ver_1.3.4 (return value no check)
     * Requirements:
     * URL: https://propertimax.co.nz/Customer/allMaxList
     * Transmission method: POST
     * Parameters: search_flag, search_option
     */
    public function allMaxList()
    {

        /** Max List Flag
         * $search_flag = 0: Sale, 1: Rent
         * - if Sale $search_option = 0: All Sales, 1: Auctions, 2: OpenHome
         * - if Rent $search_option = 0: Now, 1: This Month, 2: Next Month
         */
        $search_flag = $this->input->post_get('search_flag');
        $search_option = $this->input->post_get('search_option');
        $page = $this->input->post_get('page');
        $logged = $this->chkLogged();

        if ($logged == 0) {
            $this->errorMsg("Please login");
            return;
        } else {
            $max_result = $this->Customer_Model->selectMaxList($_SESSION['SESS_CUSTOMER_ID'], $search_flag, $search_option, $page);

            if ($search_flag == 0 && $search_option == 2) { // OpenHome (0: Sale and 2: OpenHome)
                $this->maxOpenHomeList($logged, $search_flag, $max_result);
            } else {
                if ($search_flag == 0 & $search_option == 1) {
                    $this->maxPropertyAucktionList($logged, $search_flag, $max_result);
                } else {
                    $this->maxPropertyList($logged, $search_flag, $max_result);
                }
            }
        }
    }

    public function maxPropertyList($logged, $search_flag, $max_result)
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

                $line3 = "";
                if ($Property_infos['fk_suburb_id']) {
                    $line3 = $this->Common_Model->get_suburb_from_id($Property_infos['fk_suburb_id'])->suburb_name;
                }
                if ($Property_infos['property_sale_flag'] == 1) {
                    $propertyline = array(
                        "line1" => $Property_infos['property_type_name'] . " " . $this->checkprce($Property_infos['property_show_price']) . " pw",
                        "line2" => $Property_infos['address'],
                        "line3" => $line3
                    );
                } else {
                    $propertyline = array(
                        "line1" => $Property_infos['property_title'],
                        "line2" => $Property_infos['address'],
                        "line3" => $line3
                    );
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

        $this->json_encode_msgs($datesc, $logged);
    }
}

// END
