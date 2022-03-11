<?php

defined('BASEPATH') or exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . 'libraries/Base_controller.php';
require APPPATH . "libraries/simplehtmldom_1_9_1/simple_html_dom.php";
require APPPATH . "libraries/Facebook/autoload.php";
require APPPATH . "libraries/google-api-php-client-2.2.0/vendor/autoload.php";


/**
 * Description of Adminpanel
 *
 * @package         CodeIgniter
 * @subpackage      Maxauto
 * @category        Controller
 * @author          Alvaro Pavez
 * @license         MIT
 */
class Maxauto extends Base_controller
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

    public function getWantedList($page = 1, $region)
    {
        $objVehNew = $this->Maxauto_Model->getWantedList($page, $region);
        $this->json_encode_msgs($objVehNew);
        return ($objVehNew);
    }





    public function callMotochekApi($rego, $fn)
    {
        $url = "http://3.106.101.41/api/values?";
        $obj = [];
        switch ($fn) {
            case 'details':
                $result = file_get_contents($url . "rego=" . $rego . "&fn=details");
                print_r($result);
                break;
            case 'police':
                $result = file_get_contents($url . "rego=" . $rego . "&fn=police");
                $result =  str_replace('false', '"false"', $result);
                $result =  str_replace('true', '"true"', $result);
                $json_data = json_decode($result, true);

                if (isset($json_data['vehicleField'])) {
                    //create object
                    $obj['year'] = $json_data['vehicleField']['yearOfManufactureField'];
                    $obj['make'] = $json_data['vehicleField']['makeField'];
                    $obj['model'] = $json_data['vehicleField']['modelField'];
                    $obj['isStolen'] = $json_data['vehicleField']['reportedStolenField'];
                    $obj['find'] = true;
                    //find Make and Model ID
                    $objVehNew = $this->Maxauto_Model->selectMakeId($obj['make']);

                    if (isset($objVehNew[0])) {
                        $obj['makeId'] =  $objVehNew[0]['make_id'];
                        $objVehNew2 = $this->Maxauto_Model->selectModelId($obj['model'], $obj['makeId']);
                        if (isset($objVehNew2[0])) {
                            $obj['modelId'] = $objVehNew2[0]['model_id']; //[0]['make_id'];
                        } else {
                            $objVehNew2 = $this->Maxauto_Model->selectOtherModelID($obj['makeId']);
                            $obj['modelId'] = $objVehNew2[0]['model_id']; //[0]['make_id'];
                        }
                    } else {
                        $obj['makeId'] = 160; //Other
                        $obj['modelId'] = 1776; //Other
                    }
                } else {
                    $obj['find'] = false;
                }

                $this->json_encode_msgs($obj);
                return ($obj);
                break;
            case 'free':
                $result = file_get_contents($url . "rego=" . $rego . "&fn=free");
                $result =  str_replace('false', '"false"', $result);
                $result =  str_replace('true', '"true"', $result);
                $json_data = json_decode($result, true);

                //create object

                if (isset($json_data['makeField'])) {
                    $obj['rego'] = $json_data['platesField'][0]['plateNumberField'];
                    $obj['year'] = $json_data['yearOfManufactureField'];
                    $obj['make'] = $json_data['makeField'];
                    $obj['model'] = $json_data['modelField'];
                    $obj['vin'] = $json_data['vINField'];
                    $obj['find'] = true;

                    //find Make and Model ID
                    $objVehNew = $this->Maxauto_Model->selectMakeId($obj['make']);

                    if (isset($objVehNew[0])) {
                        $obj['makeId'] =  $objVehNew[0]['make_id'];
                        $objVehNew2 = $this->Maxauto_Model->selectModelId($obj['model'], $obj['makeId']);
                        if (isset($objVehNew2[0])) {
                            $obj['modelId'] = $objVehNew2[0]['model_id']; //[0]['make_id'];
                        } else {
                            $objVehNew2 = $this->Maxauto_Model->selectOtherModelID($obj['makeId']);
                            $obj['modelId'] = $objVehNew2[0]['model_id']; //[0]['make_id'];
                        }
                    } else {
                        $obj['makeId'] = 160; //Other
                        $obj['modelId'] = 1776; //Other
                    }
                } else {
                    $obj['find'] = false;
                }

                $this->json_encode_msgs($obj);
                return ($obj);
                break;
            case 'value':
                $result = file_get_contents($url . "rego=" . $rego . "&fn=details");
                print_r($result);
                break;
                break;

            case 'owner':
                $result = file_get_contents($url . "rego=" . $rego . "&fn=details");
                print_r($result);
                break;
                break;
        }
    }


    public function callOwnerCompany($rego, $companyName)
    {
        try {
            $url = "http://3.106.101.41/api/values?rego=" . $rego . "&fn=owner&companyName=" . $companyName;
            $obj = [];
            $result = file_get_contents($url);
            $result =  str_replace('false', '"false"', $result);
            $json_data = json_decode($result, true);
            $this->json_encode_msgs($json_data);
            return ($json_data);
        } catch (\Throwable $th) {
            //$this->callOwnerCompany($rego, $companyName);
        }
    }

    public function callOwnerName($rego, $firstName, $lastName)
    {
        try {
            $url = "http://3.106.101.41/api/values?rego=" . $rego . "&fn=owner&firstName=" . $firstName . "&lastName=" . $lastName;
            $obj = [];
            $result = file_get_contents($url);
            $result =  str_replace('false', '"false"', $result);
            $result =  str_replace('true', '"true"', $result);
            $json_data = json_decode($result, true);
            $this->json_encode_msgs($json_data);
            return ($json_data);
        } catch (\Throwable $th) {
            //$this->callOwnerName($rego, $firstName, $lastName);
        }
    }

    public function callOwnerLicence($rego, $licence)
    {
        try {
            $url = "http://3.106.101.41/api/values?rego=" . $rego . "&fn=owner" . "&licence=" . $licence;
            $obj = [];
            $result = file_get_contents($url);
            $result =  str_replace('false', '"false"', $result);
            $result =  str_replace('true', '"true"', $result);
            $json_data = json_decode($result, true);
            $this->json_encode_msgs($json_data);
            return ($json_data);
        } catch (\Throwable $th) {
            //$this->callOwnerLicence($rego, $licence);
        }
    }


    public function motoChekVehicleUsage()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_PORT => "443",
            CURLOPT_URL => "https://services.nzta.govt.nz/CDPRO/WebServices/Security/AccessControl.asmx",
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
            )
        ));

        //Alvaro Pavez


        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        //asd asde jkkj kj k
        //amigos de instagram.

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $response;
            // $xml = simplexml_load_string($response);
            //$content2 = str_replace(array_map(function($e) { return "$e:"; }, array_keys($xml->getDocNamespaces())), array(), $response);
            //$xml2 = simplexml_load_string($content2);
            //$this->json_encode_msgs($xml2);
        }

        // alvaro pavez briones  jkj
        //ajdfhs kj  kjf  afg lk llkmlkj   lk 
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

            $theDate    = new DateTime();
            $stringDate = $theDate->format('YmdHis'); 
            $xml3 = str_replace(".jpg", ".jpg?time=" . $stringDate, $content2);

            $xml2 = simplexml_load_string($xml3);
            $this->json_encode_msgs($xml2);
        }
    }

    public function getMyDocuments($customerID)
    {
        //getWantedList
        $objVehNew = $this->Maxauto_Model->selectMyDocuments($customerID);

        $finalCar = [];

        foreach ($objVehNew as $key) {
            //get image
            $dateRe1 = strtotime($key->indate);
            $dateRe11 = date('j F Y', $dateRe1);
            $key->indate = $dateRe11;

            array_push($finalCar, $key);
        }

        $this->json_encode_msgs($finalCar);
        return ($finalCar);
    }


    public function getMyVehicles()
    {
        $logged = $this->chkLogged();

        if ($logged == 1) {
            $logged_code = $_SESSION['SESS_CUSTOMER_ID'];
            //getWantedList
            $objVehNew = $this->Maxauto_Model->selectMyVehicles($logged_code);
            $finalCar = [];

            foreach ($objVehNew as $key) {
                //get image
                $val =  $key->vehicle_id;
                $dat = $this->Maxauto_Model->getFirstImageMy($val);
                if (count($dat) > 0) $key->pic_url = $dat[0]->pic_url;
                array_push($finalCar, $key);
            }

            if (count($finalCar) == 0) {
                $this->json_encode_msgs($finalCar);
                return ($finalCar);
            } else {
                $this->json_encode_msgs($finalCar);
                return ($finalCar);
            }
        } else {
            $return = [];
            $return['data'] = false;
            $this->json_encode_msgs($return);
            return ($return);
        }
    }


    public function getListingCustomer()
    {
        $logged = $this->chkLogged();
        $logged_code = 0;
        if ($logged == 1) {
            $logged_code = $_SESSION['SESS_CUSTOMER_ID'];
            //getWantedList
            $objVehNew = $this->Maxauto_Model->selectVehiclesCustomer($logged_code);
            $finalCar = [];

            foreach ($objVehNew as $key) {
                //get image
                $val =  $key->vehicule_id;
                $dat = $this->Maxauto_Model->getFirstImage($val);
                if (count($dat) > 0) $key->pic_url = $dat[0]->pic_url;
                array_push($finalCar, $key);
            }

            $return['objListing'] = $finalCar;
            //getListing
            $return['objWantedListing'] = $this->Maxauto_Model->selectWantedCustomer($logged_code);

            $this->json_encode_msgs($return);
            return ($return);
        } else {
        }
    }


    public function getNearbyCustomer($lat, $lng, $radius, $page = 1)
    {
        $logged = $this->chkLogged();
        $logged_code = 0;
        if ($logged == 1)
            $logged_code = $_SESSION['SESS_CUSTOMER_ID'];

        $objVehNew = $this->Maxauto_Model->getVehicleNearbyCustomer($lat, $lng, $radius, $logged_code, $page);
        $this->json_encode_msgs($objVehNew);
        return ($objVehNew);
    }

    public function getNearby($lat, $lng, $radius, $page = 1)
    {
        $logged = $this->chkLogged();
        $logged_code = 0;
        if ($logged == 1)
            $logged_code = $_SESSION['SESS_CUSTOMER_ID'];

        //$radius = $this->input->post_get('radius');

        if ($lat === "" | $lng === "") {
            $this->errorMsg("Please Enable the Location");
            return;
        }

        //Dealership
        $data['dealership'] = $this->Maxauto_Model->getDealershipNearby($lat, $lng, $radius, $logged_code);

        $datareturn = [];
        $dealershipsid =  [];
        $agents =  [];

        foreach ($data['dealership'] as $key) {
            //get image            
            $array = [];
            $finalCar = [];
            $array['dealership_info'] = $key;
            $id =  $key->dealership_id;
            array_push($dealershipsid, $id);
            $distance = $array['dealership_info']->distance;
            $dealerImage = $array['dealership_info']->rec_img_base64;
            $dealerName = $array['dealership_info']->dealership_name;
            $objAgent = $this->Maxauto_Model->selectAgentByDealershipNearby($id, $distance, $dealerImage, $dealerName);
            if (sizeof($objAgent) > 0) array_push($agents, $objAgent);
        }

        //add dealership to array
        $array['dealership'] =  $data['dealership'];
        $array['agents'] =  $agents;
        //add agents

        //get agents

        //get vehicles 
        $finalCar = [];
        $objVehNew = [];
        //if (count($dealershipsid) > 0) {
        //    $objVehNew = $this->Maxauto_Model->selectVehicleByDealershipByStatusWashlist2($dealershipsid, 0, $logged_code, $page);
        //}

        $objVehNew = $this->Maxauto_Model->getVehicleNearbyCustomer($lat, $lng, $radius, $logged_code, $page);

        foreach ($objVehNew as $key) {
            //get image
            $val =  $key->vehicule_id;
            $dat = $this->Maxauto_Model->getFirstImage($val);
            if (count($dat) > 0) $key->pic_url = $dat[0]->pic_url;
            array_push($finalCar, $key);
        }

        $array['vehicles'] = $finalCar;

        array_push($datareturn, $array);

        //Agent for Dealership
        //$data['agent'] = $this->Customer_Model->getAllRequestsWithDistance($lat, $lng, $radius, $logged_code, null, $sort_option);
        //Vehicles for Dealership
        //$data['vehicles'] = $this->Customer_Model->getAllRequestsWithDistance($lat, $lng, $radius, $logged_code, null, $sort_option);

        $this->json_encode_msgs($datareturn);

        return ($datareturn);
    }

    public function findVehicle(
        $flag,
        $fk_region = null,
        $priceFrom = null,
        $priceTo = null,
        $bodyType = null,
        $makeId = null,
        $modelId = null,
        $odoFrom = null,
        $odoTo = null,
        $yearFrom = null,
        $yearTo = null,
        $page = null,
        $sort = null
    ) {

        $logged = $this->chkLogged();
        $logged_code = 0;

        if ($logged == 1)
            $logged_code = $_SESSION['SESS_CUSTOMER_ID'];

        $bodyType = explode('a', $bodyType);
        array_pop($bodyType);
        $data2 = $this->Maxauto_Model->findVehicule($flag, $fk_region, $priceFrom, $priceTo, $bodyType, $makeId, $modelId, $odoFrom, $odoTo, $yearFrom, $yearTo, $page, $sort, $logged_code);

        if ($page == 1) {
            $dataCount = $this->Maxauto_Model->findVehiculeCount($flag, $fk_region, $priceFrom, $priceTo, $bodyType, $makeId, $modelId, $odoFrom, $odoTo, $yearFrom, $yearTo, $page, $sort, $logged_code);
        } else {
            $dataCount = 0;
        }
        $data = [];

        foreach ($data2 as $key) {
            //get image
            $val =  $key->vehicule_id;
            $dat = $this->Maxauto_Model->getAllImages($val);

            if (count($dat) > 0) {
                $key->images = $dat;
            } else {
                //$key->images[0]->pic_url = "placeholdercar.png";
            }

            array_push($data, $key);
        }

        $datareturn['vehicules'] = $data;
        $datareturn['count'] = $dataCount;

        $this->json_encode_msgs($datareturn);
        return $datareturn;
    }

    public function findVehicleById($id)
    {
        $data2 = $this->Maxauto_Model->findVehiculeById($id);
        $data = [];

        foreach ($data2 as $key) {
            //get image
            $val =  $key->vehicule_id;
            $dat = $this->Maxauto_Model->getAllImages($val);
        }

        $listingType =  $data2[0]->fk_listing_type;

        if ($listingType == "2") { //agency
            $contact = $this->Maxauto_Model->getContactAgency($data2[0]->fk_dealership_id);
        } else {
            $contact = $this->Maxauto_Model->getContactCustomer($data2[0]->fk_customer);
        }




        //checkTime
        /*   $now = getdate();
        $day = 0;
        if ($now['wday'] == 0) {
            $day = 7;
        } else {
            $day = $now['wday'];
        }

        $time = $this->Maxauto_Model->findTimeByDealership($data2[0]->fk_dealership_id, $day);
        $now2 = date("G:i A", time());
        $timeF['open'] = $time[0]->open_time;
        $timeF['close'] = $time[0]->close_time; */

        //$returnData['time'] = $timeF;
        $returnData['details'] = $data2;
        $returnData['photos'] = $dat;
        $returnData['contact'] = $contact;

        $this->json_encode_msgs($returnData);
        return $returnData;
    }




    public function findSalesConsultantById($id, $page = 1)
    {
        $data = [];
        $data["agent"] = $this->Maxauto_Model->selectAgentById($id);
        $data["lagent"] =   $this->Maxauto_Model->selectTargetAgentListLanguages($id);
        $data["flags"] = $this->Maxauto_Model->selectLanguage();

        $finalCar = [];
        $logged = $this->chkLogged();
        $logged_code = 0;

        if ($logged == 1)
            $logged_code = $_SESSION['SESS_CUSTOMER_ID'];

        $id = $data["agent"][0]->fk_dealership_id;

        $array['dealer']  = $this->Maxauto_Model->selectDealershipById($id);
        $dealerName =   $array['dealer'][0]->dealership_name;
        $dealerImage = $array['dealer'][0]->rec_img_base64;


        $dealershipsid = [];
        array_push($dealershipsid, $id);

        $objVeh = $this->Maxauto_Model->selectVehicleByDealershipByStatusWashlist($id, 0, $logged_code, $page);

        foreach ($objVeh as $key) {
            //get image
            $val =  $key->vehicule_id;
            $dat = $this->Maxauto_Model->getFirstImage($val);
            $key->pic_url = $dat[0]->pic_url;
            array_push($finalCar, $key);
        }

        $data['details'] = $array['dealer'][0];
        $data['vehicles'] = $finalCar;

        $this->json_encode_msgs($data);
        return $data;
    }

    public function getPrices()
    {
        $objReturn = $this->Maxauto_Model->selectPrices();
        $this->json_encode_msgs($objReturn);
        return $objReturn;
    }

    public function getBanners()
    {
        $data = [];
        $data["banners"] = $this->Maxauto_Model->getBannersApp("1");
        $this->json_encode_msgs($data);
        return $data;
    }


    public function getDashboardNumber()
    {
        $logged = $this->chkLogged();
        $logged_code = 0;

        if ($logged == 1) {
            $logged_code = $_SESSION['SESS_CUSTOMER_ID'];


            $objpdf = $this->Maxauto_Model->selectCountMyDocuments($logged_code);
            $objWash =  $this->Maxauto_Model->selectWashList($logged_code);
            $objDealer["pdf"] = count($objpdf);
            $objDealer["watch"] = count($objWash);
            $this->json_encode_msgs($objDealer);
            return $objDealer;
        } else {
        }
    }

    public function getListcontact()
    {
        $logged = $this->chkLogged();
        $logged_code = 0;

        if ($logged == 1) {
            $logged_code = $_SESSION['SESS_CUSTOMER_ID'];
            $finalCar = [];
            $objDealer = [];
            $objDealer = $this->Maxauto_Model->selectContactList($logged_code);
            $this->json_encode_msgs($objDealer);
            return $objDealer;
        } else {
        }
    }

    public function findNews($page = 1)
    {
        $logged = $this->chkLogged();
        $logged_code = 0;

        if ($logged == 1) {
            $logged_code = $_SESSION['SESS_CUSTOMER_ID'];
            $finalCar = [];
            $objVeh = [];
            $objVeh = $this->Maxauto_Model->selectVehicleNews($logged_code, $page);
            foreach ($objVeh as $key) {
                //get image
                $val =  $key->vehicule_id;
                $dat = $this->Maxauto_Model->getFirstImage($val);
                $key->likes =  $this->Maxauto_Model->selectLikeVehicle($val);
                $key->is_likes =  $this->Maxauto_Model->isLikeVehicle($logged_code, $val);
                if (count($dat) > 0) $key->pic_url = $dat[0]->pic_url;
                array_push($finalCar, $key);
            }

            $this->json_encode_msgs($finalCar);
            return $finalCar;
        } else {
        }
    }

    public function findDealershipById($id, $lat = 0, $long = 0, $page = 1)
    {

        $finalCar = [];
        $logged = $this->chkLogged();
        $logged_code = 0;

        if ($logged == 1)
            $logged_code = $_SESSION['SESS_CUSTOMER_ID'];

        $array['dealer']  = $this->Maxauto_Model->selectDealershipById($id, $logged_code, $lat, $long);
        $dealerName =   $array['dealer'][0]->dealership_name;
        $dealerImage = $array['dealer'][0]->rec_img_base64;

        $array['agents'] = $this->Maxauto_Model->selectAgentByDealership2($id, $dealerImage, $dealerName);

        $dealershipsid = [];
        array_push($dealershipsid, $id);

        $objVeh = $this->Maxauto_Model->selectVehicleByDealershipByStatusWashlist($id, 0, $logged_code, $page);

        foreach ($objVeh as $key) {
            //get image
            $val =  $key->vehicule_id;
            $dat = $this->Maxauto_Model->getFirstImage($val);
            $key->pic_url = $dat[0]->pic_url;
            array_push($finalCar, $key);
        }

        //checkTime
        $now = getdate();
        $day = 0;
        if ($now['wday'] == 0) {
            $day = 7;
        } else {
            $day = $now['wday'];
        }

        $time = $this->Maxauto_Model->findTimeByDealership($id, $day);
        if (count($time) != 0) {
            $timeF['open'] = $time[0]->open_time;
            $timeF['close'] = $time[0]->close_time;
        } else {
            $timeF['open'] = "00:00";
            $timeF['close'] = "00:00";
        }
        $arrayreturn['time'] = $timeF;
        $arrayreturn['details'] = $array['dealer'][0];
        $arrayreturn['agents'] = $array['agents'];
        $arrayreturn['vehicles'] = $finalCar;

        $this->json_encode_msgs($arrayreturn);
        return $arrayreturn;
    }


    public function addWashList($customer_id, $vehicle_id)
    {
        $data = [];
        $data['fk_vehicule_id'] = $vehicle_id;
        $data['fk_customer_id'] = $customer_id;
        $data2 = $this->Maxauto_Model->addWashList($data);
        $this->json_encode_msgs($data2);
        return $data2;
    }

    public function deleteMyVehicle($id)
    {

        $vehicule_db['delete_flag'] = 1;



        $id = $this->Maxauto_Model->updateMyVehicle($vehicule_db, $id);

        $data = $this->json_encode_msgs($id);

        //guardar auto

        return $data;
    }


    public function removeWashList($customer_id, $vehicle_id)
    {
        $data2 = $this->Maxauto_Model->deleteWashList($customer_id, $vehicle_id);
        $this->json_encode_msgs($data2);
        return $data2;
    }

    public function addLike($customer_id, $vehicle_id)
    {
        $data = [];
        $data['fk_vehicle'] = $vehicle_id;
        $data['fk_customer'] = $customer_id;
        $data2 = $this->Maxauto_Model->addLike($data);
        $this->json_encode_msgs($data2);
        return $data2;
    }

    public function removeLike($customer_id, $vehicle_id)
    {
        $data2 = $this->Maxauto_Model->deleteLike($customer_id, $vehicle_id);
        $this->json_encode_msgs($data2);
        return $data2;
    }

    public function addWashListDealer($customer_id, $dealer_id)
    {
        $data = [];
        $data['fk_dealership'] = $dealer_id;
        $data['fk_customer'] = $customer_id;

        $data2 = $this->Maxauto_Model->addWashListDealer($data);

        $this->json_encode_msgs($data2);
        return $data2;
    }


    public function removeWashListDealer($customer_id, $dealer_id)
    {
        $data2 = $this->Maxauto_Model->deleteWashListDealer($customer_id, $dealer_id);
        $this->json_encode_msgs($data2);
        return $data2;
    }


    public function findVehicleByCustomerId($id)
    {
        $data2 = $this->Maxauto_Model->findVehicleByCustomerId($id);

        $data = [];

        foreach ($data2 as $key) {
            //get image
            $val =  $key->vehicule_id;
            $dat = $this->Maxauto_Model->getFirstImage($val);
            $key->pic_url = $dat[0]->pic_url;
            array_push($data, $key);
        }

        $this->json_encode_msgs($data);
        return $data;
    }

    /** Maxauto - Ver_1.3.4
     * Requirements:
     * URL: http://45.64.60.240//Maxauto/
     * Transmission method: POST
     * Author: Alvaro Pavez
     */

    public function allPlacesList()
    {
        $data['region_list'] = $this->allRegionList();
        $data['suburb_list'] = $this->allSuburbListAll();
        $data['city_list'] = $this->allCityListAll();
        $this->json_encode_msgs($data);
        return $data;
    }

    public function allPlacesListCameras()
    {
        $data['region_list'] = $this->allRegionListCamera();
        $this->json_encode_msgs($data);
        return $data;
    }

    public function getRecentList()
    {
        $data2 = $this->Maxauto_Model->selectRecentVehicule();
        $data = [];

        foreach ($data2 as $key) {
            //get image
            $val =  $key->vehicule_id;
            $dat = $this->Maxauto_Model->getFirstImage($val);
            $key->pic_url = $dat[0]->pic_url;
            array_push($data, $key);
        }

        $this->json_encode_msgs($data);
        return $data;
    }

    public function getWashlist()
    {
        $logged = $this->chkLogged();
        $logged_code = 0;

        if ($logged == 1)
            $logged_code = $_SESSION['SESS_CUSTOMER_ID'];

        $data2 = $this->Maxauto_Model->selectWashList($logged_code);
        $data = [];

        foreach ($data2 as $key) {
            //get image
            $val =  $key->vehicule_id;
            $dat = $this->Maxauto_Model->getFirstImage($val);
            if (count($dat) > 0) $key->pic_url = $dat[0]->pic_url;
            array_push($data, $key);
        }

        $this->json_encode_msgs($data);
        return $data;
    }

    public function getAllMake()
    {
        $data = $this->Maxauto_Model->selectAllMake();
        $this->json_encode_msgs($data);
        return $data;
    }

    public function getAllMakeSearch()
    {
        $data = $this->Maxauto_Model->selectAllMakeSearch();
        $this->json_encode_msgs($data);
        return $data;
    }

    public function getAllMakeMotoSearch()
    {
        $data = $this->Maxauto_Model->selectAllMakeMotoSearch();
        $this->json_encode_msgs($data);
        return $data;
    }

    public function getAllMakeMoto()
    {
        $data = $this->Maxauto_Model->selectAllMakeMoto();
        $this->json_encode_msgs($data);
        return $data;
    }


    public function getModels($make_id)
    {
        $data = $this->Maxauto_Model->selectModelByMake($make_id);
        $this->json_encode_msgs($data);
        return $data;
    }

    public function allRegionList()
    {
        $region_list = $this->Maxauto_Model->selectRegionList();
        return $region_list;
    }

    public function allRegionListCamera()
    {
        $region_list = $this->Maxauto_Model->selectRegionListCamera();
        return $region_list;
    }


    public function allSuburbListAll()
    {
        $suburb_list = $this->Maxauto_Model->selectSuburbListAll();
        return $suburb_list;
    }

    public function allCityListAll()
    {
        $city_list = $this->Maxauto_Model->selectCityListAll();
        return $city_list;
    }

    public function allBodyTypes()
    {
        $data['car_list'] = $this->Maxauto_Model->selectABodiesById("0");
        $data['moto_list'] = $this->Maxauto_Model->selectAllBodies("1");
        $this->json_encode_msgs($data);
        return $data;
    }

    public function Excel()
    {
        $csvFile = file('../../convertcsv.csv');
        $data = [];
        foreach ($csvFile as $line) {
            $data[] = str_getcsv($line);
        }

        $model = [];

        for ($i = 0; $i <  count($data); $i++) {

            for ($x = 0; $x < count($data[$i]); $x++) {

                if ($x == 0) {
                    $model["fk_make_id"] = $data[$i][$x];
                } else {
                    if ($data[$i][$x] != "") {
                        $model["model_desc"] = $data[$i][$x];
                        print_r($model);
                        //save model
                        $this->Maxauto_Model->insertModel($model);
                    }
                }
            }
        }
    }


    public function findMake($make)
    {
        $return =  $this->Maxauto_Model->findMake($make);

        if (empty($return)) {
            return "81";
        } else {
            return $return[0]->make_id;
        }
    }

    public function findModel($make_id, $model)
    {
        $return =  $this->Maxauto_Model->findModel($make_id, $model);

        if (empty($return)) {
            //get other
            $return =  $this->Maxauto_Model->findModelByOther($make_id);
        } else {
            return $return[0]->model_id;
        }
    }

    public function rightCarTest($rego)
    {

        $opts = array(
            'http' => array(
                'header' =>  "User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/24.0.3215.75 Safari/537.36", "Accept-Encoding: gzip, deflate, sdch",
                "Accept-Language: en-US,en;q=0.8\r\n",
                "Cookie:ASP.NET_SessionId=lrv3rfjl1ay0aj45v33s0ozm;OnCoreWeb=AutoLoadImages=-1&ImageViewer=2&DefaultNumberOfRows=10", "Connection: keep-alive",
                "Cache-Control: max-age=0",
            )
        );

        $context = stream_context_create($opts);

        $html = file_get_html('https://thatcar.nz/c/' . $rego, false, $context);
        //print_r($url);

        //get_first_table
        $table = $html->find("table", 0);
        $table2 = $html->find("table", 1);

        $vehicule_tmp = array();
        $vehicule_db = array();

        if ($table == null) {
            print_r("nothing");
            return false;
        } else {
            $numcounter = count($dot2 = $table->find('td'));
            $numcounter2 = count($dot3 = $table2->find('td'));

            for ($i = 0; $i < $numcounter; $i += 2) {
                $vehicule_tmp[trim($table->find("td", $i)->plaintext)] = trim($table->find("td", $i + 1)->plaintext);
            }

            for ($i = 0; $i < $numcounter2; $i += 2) {
                $vehicule_tmp[trim($table2->find("td", $i)->plaintext)] = trim($table2->find("td", $i + 1)->plaintext);
            }

            //check if vehicle has the field and change it.
            if (array_key_exists('Year', $vehicule_tmp)) {
                $vehicule_db['vehicle_year'] = $vehicule_tmp['Year'];
            }

            if (array_key_exists('Make', $vehicule_tmp)) {

                $vehicule_db['fk_vehicle_make'] = $vehicule_tmp['Make'];
            }

            if (array_key_exists('Model', $vehicule_tmp)) {
                $vehicule_db['fk_vehicle_model'] = $vehicule_tmp['Model'];
            }

            if (array_key_exists('Colour', $vehicule_tmp)) {
                $vehicule_db['vehicle_color'] = $vehicule_tmp['Colour'];
            }

            if (array_key_exists('Plate number', $vehicule_tmp)) {
                $vehicule_db['vehicle_rego'] = $vehicule_tmp['Plate number'];
            }

            if (array_key_exists('Number of doors', $vehicule_tmp)) {
                $vehicule_db['vehicle_door'] = $vehicule_tmp['Number of doors'];
            }

            if (array_key_exists('Number of seats', $vehicule_tmp)) {
                $vehicule_db['vehicle_seat'] = $vehicule_tmp['Number of seats'];
            }

            if (array_key_exists('CC rating', $vehicule_tmp)) {
                $vehicule_db['vehicle_engine'] = $vehicule_tmp['CC rating'];
            }

            if (array_key_exists('Fuel type', $vehicule_tmp)) {
                $vehicule_db['vehicle_fuel'] = $vehicule_tmp['Fuel type'];
            }

            if (array_key_exists('Vehicle type', $vehicule_tmp)) {

                $vehicule_db['vehicle_type'] = $vehicule_tmp['Vehicle type'];
            }

            $table3 = $html->find("table", 13);
            if ($table3 != null) {


                $numcounter3 = count($dot3 = $table3->find('td'));

                for ($i = 0; $i < $numcounter3; $i += 2) {

                    $let1 = trim($table3->find("td", $i)->plaintext);
                    if ($let1 == "Fuel consumption") {
                        $let2 = $table3->find("div[class=star-rating]", $i);

                        //$vehicule_tmp2[trim($table3->find("td", $i)->plaintext)] = trim($table3->find("td", $i + 1)->plaintext);
                        //$link = $html->find('a[href=http://mylink.se]', 0); //As the OP pointed out in comments, you need to select the first element
                        //$title = $link->title
                        $vehicule_db["Fuel consumption"] = $let2->title;
                    }
                }
            }

            $table4 = $html->find("p", 0);
            if (isset($table4)) {
                $let2 = $table4->plaintext;


                if (strpos($let2, "Yes")) {
                    $vehicule_db["vehicle_police"] = "Listed as stolen";
                } else {
                    $vehicule_db["vehicle_police"] = " Not listed as stolen";
                }
            }

            $vehicule_db["vehicle_imported"] = "0";

            print_r($vehicule_db);
            return $vehicule_db;
        }
    }

    public function chargingStation()
    {
        $data["owner"] = $this->Maxauto_Model->selectOwnerChargin();
        $data["data"] = $this->Maxauto_Model->selectAllCharginStation();
        $this->load->view("maxauto/first", $data);
    }


    public function getNewCars()
    {
        $newCar = $this->Maxauto_Model->selectAllNewCar();
        $data = $this->json_encode_msgs($newCar);
        return $data;
    }

    public function getMainNewCar()
    {
        $newCar = $this->Maxauto_Model->selectAllMainNewCar();
        $data = $this->json_encode_msgs($newCar);
        return $data;
    }


    public function getSubModels($model, $fk_make)
    {
        $newCar = $this->Maxauto_Model->selectAllSubModelNewCar($model, $fk_make);
        $data = $this->json_encode_msgs($newCar);
        return $data;
    }




    public function newCars()
    {
        $data['body'] = $this->Maxauto_Model->selectABodiesById("0");
        $data["allMake"] = $this->Maxauto_Model->selectAllMake();
        $data["data"] = $this->Maxauto_Model->selectAllNewCar();

        $this->load->view("maxauto/newcar", $data);
    }


    public function editWantedListing()
    {
    }



    public function editPhoto($idCar, $oldFile)
    {
        //add Image S3
        $tmp_file = $_FILES['images']['tmp_name'];
        $image_name = "newcar" . '-' . $_FILES['images']['name'];
        $urlS3 = $this->imageMaxAutoS3($tmp_file, $image_name, $_FILES);
        $charging["url_image"] = $urlS3;


        //deleteImage
        $this->deleteMaxAutoImage($oldFile);

        //print_r($urlS3);
        $result = $this->Maxauto_Model->updateNewCar($charging, $idCar);
        redirect('maxauto/newCars');
    }

    public function index()
    {
        redirect('about');
    }

    public function sendContactUs()
    {
        $charging["email"] = $this->input->post_get('email');
        $charging["phone"] = $this->input->post_get('phone');
        $charging["bussines_name"] = $this->input->post_get('bussines_name');
        $charging["desc"] = $this->input->post_get('desc');
        $charging["name"] = $this->input->post_get('name');

        $result = $this->Maxauto_Model->sendAboutUs($charging);
        //redirect('http://45.64.60.240/about/contactus.php');
    }

    public function addNewCar()
    {
        $charging["fk_make"] = $this->input->post_get('make');
        $charging["model"] = $this->input->post_get('model');
        $charging["submodel"] = $this->input->post_get('submodel');
        //$charging["price"] = $this->input->post_get('price');
        $charging["fk_body_type"] = $this->input->post_get('body');

        $charging["fuel_consu"] = $this->input->post_get('fuel_consu');



        $charging["transmission"] = $this->input->post_get('trans');
        $charging["drive_type"] = $this->input->post_get('drive');
        $charging["engine_size"] = $this->input->post_get('engine');
        $charging["url"] = $this->input->post_get('url');
        $charging["safety"] = $this->input->post_get('safety');
        $charging["fuel"] = $this->input->post_get('fuel');
        $charging["description"] = $this->input->post_get('desc');
        $charging["fueltype"] = $this->input->post_get('fueltype');

        //add Image S3
        $tmp_file = $_FILES['images']['tmp_name'];
        $image_name = "newcar" . '-' . $_FILES['images']['name'];
        $urlS3 = $this->imageMaxAutoS3($tmp_file, $image_name, $_FILES);

        $charging["url_image"] = $urlS3;

        //check if new model
        if ($this->input->post_get('mainmodel') == "on") {
            $charging["mainmodel"] = 1;
        } else {
            $charging["mainmodel"] = 2;
        }


        //print_r($urlS3);
        $result = $this->Maxauto_Model->insertNewCar($charging);


        redirect('maxauto/newCars');
    }




    public function deleteCar($id)
    {

        $result = $this->Maxauto_Model->deleteNewCar($id);
        redirect('maxauto/newCars');
    }



    public function addCharging()
    {
        $charging["name"] = $this->input->post_get('name');
        $charging["address"] = $this->input->post_get('address');
        $charging["address_desc"] = $this->input->post_get('desc');
        $charging["lat"] = $this->input->post_get('lat1');
        $charging["long"] = $this->input->post_get('lon1');

        $charging["phone"] = $this->input->post_get('phone');

        //insert charging
        if ($this->input->post_get('con1') == "on") {
            $charging["chademo"] = 1;
        }
        if ($this->input->post_get('con2') == "on") {
            $charging["ccs"] = 1;
        }
        if ($this->input->post_get('con3') == "on") {
            $charging["tesla"] = 1;
        }

        if ($charging["name"] == "") {
            $charging["name"] = $this->input->post_get('nuevo');

            $char["name"] = $this->input->post_get('nuevo');
            $this->Maxauto_Model->insertOwner($char);
        }


        //name

        $result = $this->Maxauto_Model->insertCharging($charging);


        redirect('maxauto/chargingStation');
    }

    public function stripeGetsession($price)
    {
        require_once('application/libraries/stripe-php/init.php');

        $stripe = new \Stripe\StripeClient(
            'sk_test_BWe7m4QNoFghgOGIWPJH8zKq'
        );

        $session =   $stripe->checkout->sessions->create([
            'success_url' => 'https://autoape.co.nz?sc_checkout=success',
            'cancel_url' => 'https://autoape.co.nz?stripeGetsession?sc_checkout=cancel',
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'amount' => $price,
                    'currency' => "nzd",
                    'quantity' => 1,
                    'name' => "AutoApe Listing",
                ],
            ],
            'mode' => 'payment',
        ]);

        $data = $this->json_encode_msgs($session['id']);
        return $data;
    }

    public function thatCar($rego)
    {
        $html = file_get_html('https://thatcar.nz/c/' . $rego);
        //print_r($url);

        //get_first_table
        $table = $html->find("table", 0);
        $table2 = $html->find("table", 1);

        $vehicule_tmp = array();
        $vehicule_db = array();

        if ($table == null) {
            return false;
        } else {


            $numcounter = count($dot2 = $table->find('td'));
            $numcounter2 = count($dot3 = $table2->find('td'));

            for ($i = 0; $i < $numcounter; $i += 2) {
                $vehicule_tmp[trim($table->find("td", $i)->plaintext)] = trim($table->find("td", $i + 1)->plaintext);
            }

            for ($i = 0; $i < $numcounter2; $i += 2) {
                $vehicule_tmp[trim($table2->find("td", $i)->plaintext)] = trim($table2->find("td", $i + 1)->plaintext);
            }

            //check if vehicle has the field and change it.
            if (array_key_exists('Year', $vehicule_tmp)) {
                $vehicule_db['vehicule_year'] = $vehicule_tmp['Year'];
            }

            if (array_key_exists('Make', $vehicule_tmp)) {

                $vehicule_db['fk_vehicule_make'] = $this->findMake($vehicule_tmp['Make']);
            }

            if (array_key_exists('Model', $vehicule_tmp)) {

                if ($vehicule_db['fk_vehicule_make'] == "81") {
                    $vehicule_db['fk_vehicule_model'] = "1774";
                } else {
                    $vehicule_db['fk_vehicule_model'] = $this->findModel($vehicule_db['fk_vehicule_make'], $vehicule_tmp['Model']);
                }
            }

            if (array_key_exists('Colour', $vehicule_tmp)) {
                $vehicule_db['vehicule_color'] = $vehicule_tmp['Colour'];
            }

            if (array_key_exists('Plate number', $vehicule_tmp)) {
                $vehicule_db['vehicule_rego'] = $vehicule_tmp['Plate number'];
            }

            if (array_key_exists('Number of doors', $vehicule_tmp)) {
                $vehicule_db['vehicule_door'] = $vehicule_tmp['Number of doors'];
            }

            if (array_key_exists('Number of seats', $vehicule_tmp)) {
                $vehicule_db['vehicule_seat'] = $vehicule_tmp['Number of seats'];
            }

            if (array_key_exists('CC rating', $vehicule_tmp)) {
                $vehicule_db['vehicule_engine'] = $vehicule_tmp['CC rating'];
            }

            if (array_key_exists('Fuel type', $vehicule_tmp)) {
                $vehicule_db['vehicule_fuel'] = $vehicule_tmp['Fuel type'];
            }

            if (array_key_exists('Vehicle type', $vehicule_tmp)) {
                if ($vehicule_tmp['Vehicle type'] == "Motorcycle") {
                    $vehicule_db['fk_vehicule_type'] = "1";
                    $vehicule_db['fk_vehicule_body_id'] = "18";
                } else {
                    $vehicule_db['fk_vehicule_type'] = "0";
                    if (array_key_exists('Body style', $vehicule_tmp)) {
                        switch ($vehicule_tmp['Body style']) {
                            case 'Hatchback':
                                $vehicule_db['fk_vehicule_body_id'] = "2";
                                break;

                            default:
                                $vehicule_db['fk_vehicule_body_id'] = "8";
                                break;
                        }
                    }
                }
            }
            //print_r($vehicule_db);

            $table3 = $html->find("table", 13);
            if ($table3 != null) {
                $numcounter3 = count($dot3 = $table3->find('td'));
                for ($i = 0; $i < $numcounter3; $i += 2) {

                    $let1 = trim($table3->find("td", $i)->plaintext);
                    if ($let1 == "Fuel consumption") {
                        $let2 = $table3->find("div[class=star-rating]", $i);
                        $vehicule_db["vehicule_fuel_ranking"] = $let2->title;
                    }
                }
            }

            return $vehicule_db;
        }
    }

    public function listWantedList()
    {
        $veh = json_decode(file_get_contents('php://input'), true);

        $vehicule_db['wanted_year'] = $veh['yearF'];
        $vehicule_db['wanted_price'] = $veh['priceF'];
        $vehicule_db['fk_region_id'] = $veh['fk_region'];
        $vehicule_db['fk_model_id'] = $veh['modelF'];
        $vehicule_db['fk_make_id'] = $veh['makeF'];
        $vehicule_db['contact_phone'] = $veh['phoneF'];
        $vehicule_db['contact_email'] = $veh['emailF'];
        $vehicule_db['fk_customer_id'] = $veh['customerId'];

        //photos

        $id = $this->Maxauto_Model->insertWantedList($vehicule_db);

        $data = $this->json_encode_msgs($id);

        //guardar auto

        return $data;
    }


    public function generatePdfAgreement($idCustomer)
    {
        $veh = json_decode(file_get_contents('php://input'), true);

        $vehicule_db['dateOfSale'] =  (isset($veh['dateOfSale']) ? $veh['dateOfSale'] : "-");
        $vehicule_db['idMake'] = (isset($veh['idMake']) ? $veh['idMake'] : "-");
        $vehicule_db['idModel'] = (isset($veh['idModel']) ? $veh['idModel'] : "-");
        $vehicule_db['rego'] = (isset($veh['rego']) ? $veh['rego'] : "-");
        $vehicule_db['vin'] = (isset($veh['vin']) ? $veh['vin'] : "-");
        $vehicule_db['dateLicense'] = (isset($veh['dateLicense']) ? $veh['dateLicense'] : "-");
        $vehicule_db['dateWof'] = (isset($veh['dateWof']) ? $veh['dateWof'] : "-");
        $vehicule_db['odo'] = (isset($veh['odo']) ? $veh['odo'] : "-");
        $vehicule_db['nameSeller'] = (isset($veh['nameSeller']) ? $veh['nameSeller'] : "-");
        $vehicule_db['addressSeller'] = (isset($veh['addressSeller']) ? $veh['addressSeller'] : "-");
        $vehicule_db['phoneSeller'] = (isset($veh['phoneSeller']) ? $veh['phoneSeller'] : "-");
        $vehicule_db['nameBuyer'] = (isset($veh['nameBuyer']) ? $veh['nameBuyer'] : "-");

        $vehicule_db['addressBuyer'] = (isset($veh['addressBuyer']) ? $veh['addressBuyer'] : "-");
        $vehicule_db['phoneBuyer'] = (isset($veh['phoneBuyer']) ? $veh['phoneBuyer'] : "-");
        $vehicule_db['price'] = (isset($veh['price']) ? $veh['price'] : "-");
        $vehicule_db['method'] = (isset($veh['method']) ? $veh['method'] : "-");
        $vehicule_db['delivery'] = (isset($veh['delivery']) ? $veh['delivery'] : "-");
        $vehicule_db['datePay'] = (isset($veh['datePay']) ? $veh['datePay'] : "-");
        $vehicule_db['dateDelivery'] = (isset($veh['dateDelivery']) ? $veh['dateDelivery'] : "-");
        $vehicule_db['year'] = (isset($veh['year']) ? $veh['year'] : "-");


        $vehicule_db['desc'] = (isset($veh['desc']) ? $veh['desc'] : "-");

        $url = $this->testPdf($vehicule_db);

        $urlFinal = "https://autoape.co.nz/" . $url;
        //$data = $this->json_encode_msgs($urlFinal);

        //saveFile in Documents Customer
        $objDB = [];

        $objDB['document_url'] = $url;
        $objDB['fk_customer'] = $idCustomer;
        $objDB['document_type'] = 0;


        $id = $this->Maxauto_Model->insertDocument($objDB);

        $objDB['document_url_final'] = $urlFinal;
        $data = $this->json_encode_msgs($objDB);
        return $objDB;

        $data = $this->json_encode_msgs($url);
        return $data;
    }

    public function testOdometer($rego)
    {
        $url = "http://3.106.101.41/api/values?";
        $result = file_get_contents($url . "rego=" . $rego . "&fn=vehicleDetails");
        $obj = [];
        json_decode($result, true);
        $result =  str_replace('false', '"false"', $result);
        $result =  str_replace('true', '"true"', $result);
        $json_data = json_decode($result, true);

        $tempOdo = $json_data['vehicleField']['odometerReadingsField'];
        $tempOdo = array_reverse($tempOdo);
        $odoReal = [];
        $lastFrom = null;

        foreach ($tempOdo as $odo) {
            if (isset($lastFrom)) {
                if ($lastFrom['value'] < $odo['readingField']) {
                    $objOdoTemp['value'] = $odo['readingField'];
                    $objOdoTemp['date'] = $odo['readingDateField'];
                    $objOdoTemp['isCorrect'] = true;
                } else {
                    $objOdoTemp['value'] = $odo['readingField'];
                    $objOdoTemp['date'] = $odo['readingDateField'];
                    $objOdoTemp['isCorrect'] = false;
                }
            } else {
                $objOdoTemp['value'] = $odo['readingField'];
                $objOdoTemp['date'] = $odo['readingDateField'];
                $objOdoTemp['isCorrect'] = true;
            }

            array_push($odoReal, $objOdoTemp);

            $lastFrom = $objOdoTemp;
        }



        $data = $this->json_encode_msgs($odoReal);
        return $data;
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
            CURLOPT_POSTFIELDS => "{\n  \"searchBy\": \"motorVehicle\",\n  \"legitimateSearchReason\": \"yes\",\n  \"searchByMotorVehicle\": {\n    \"vin\": \"{$rego}\"\n  },\n  \"page\": 1,\n  \"pageSize\": 10\n}",
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
            return $response;
        }
    }

    public function daysBetween($dt1, $dt2)
    {
        return date_diff(
            date_create($dt2),
            date_create($dt1)
        )->format('%a');
    }

    public function generatePPSRReport($rego, $idCustomer)
    {

        $url = "http://3.106.101.41/api/values?";
        $result = file_get_contents($url . "rego=" . $rego . "&fn=free");
        $obj = [];
        json_decode($result, true);
        $result =  str_replace('false', '"false"', $result);
        $result =  str_replace('true', '"true"', $result);
        $json_data = json_decode($result, true);
        //PPSR
        //$objPPSR = $this->getFinancial($obj['vin']); 
        $obj['rego'] = $rego;
        $obj['vin'] = $json_data['vINField'];
        $obj['make'] = $json_data['makeField'];
        $obj['model'] = $json_data['modelField'];
        $obj['submodel'] = $json_data['subModelField'];
        $obj['year'] = $json_data['yearOfManufactureField'];
        //$objPPSR =json_decode($this->getFinancial($obj['vin']));
        $objPPSR = json_decode("{\n  \"billingReference\" : null,\n  \"paymentResultId\" : \"cbfae2-XnjptWauYaQe3vT\",\n  \"note\" : \"Results are listed by date and time of PPSR registration, based on the search criteria, and do NOT establish priority.\",\n  \"searchId\" : \"9867954\",\n  \"searchDateTime\" : \"2021-08-31T10:38:30.811+12:00\",\n  \"totalPages\" : 1,\n  \"totalItems\" : 1,\n  \"page\" : 1,\n  \"pageSize\" : 10,\n  \"sortBy\" : \"registrationDate\",\n  \"sortOrder\" : \"asc\",\n  \"searchRequest\" : {\n    \"searchBy\" : \"motorVehicle\",\n    \"legitimateSearchReason\" : \"yes\",\n    \"searchByMotorVehicle\" : {\n      \"vin\" : \"7AT0DH79X17332335\"\n    },\n    \"page\" : 1,\n    \"pageSize\" : 10\n  },\n  \"items\" : [ {\n    \"status\" : \"registered\",\n    \"expiryDate\" : \"2022-08-01T14:09:11.000+12:00\",\n    \"priorRegistrationDate\" : null,\n    \"registrationDate\" : \"2017-08-01T14:09:11.000+12:00\",\n    \"financingStatementKey\" : {\n      \"registrationNumber\" : \"FN0ND18MR7871225\",\n      \"version\" : 0\n    },\n    \"debtors\" : [ {\n      \"debtorType\" : \"person\",\n      \"debtorName\" : \"Oliver Fabilitante CABRERA\",\n      \"dateOfBirth\" : \"1973-10-14\",\n      \"debtorReference\" : \"1-7-639718\",\n      \"cityTown\" : \"AUCKLAND 0600\"\n    } ],\n    \"securedParties\" : [ {\n      \"securedPartyName\" : \"FINANCE NOW LIMITED\",\n      \"securedPartyType\" : \"organisation\"\n    } ],\n    \"collateralTypes\" : [ {\n      \"code\" : \"MV\",\n      \"description\" : \"Goods - Motor Vehicles\"\n    } ],\n    \"motorVehicles\" : [ {\n      \"chassis\" : null,\n      \"make\" : \"NISSAN\",\n      \"model\" : \"NOTE\",\n      \"registrationPlate\" : \"KPS921\",\n      \"vin\" : \"7AT0DH79X17332335\",\n      \"year\" : \"2008\"\n    } ]\n  } ]\n}");
        $obj['PPSR']['totalItems'] = $objPPSR->totalItems;
        $obj['PPSR']['items'] = $objPPSR->items;

        $url =  $this->testPdfReportPPSR($obj);
        $urlFinal = "https://autoape.co.nz/" . $url;
        //$data = $this->json_encode_msgs($urlFinal);

        //saveFile in Documents Customer
        $objDB = [];

        $objDB['document_url'] = $url;
        $objDB['fk_customer'] = $idCustomer;
        $objDB['document_type'] = 2;


        $id = $this->Maxauto_Model->insertDocument($objDB);

        $objDB['document_url_final'] = $urlFinal;
        $data = $this->json_encode_msgs($objDB);
        return $objDB;
    }

    public function testPdfReportPPSR($obj)
    {

        date_default_timezone_set("Pacific/Auckland");

        //set Vehicles Values
        $year = $obj['year'];
        $make = $obj['make'];
        $model = $obj['model'];
        $submodel = $obj['submodel'];
        $rego = $obj['rego'];
        $totalItemPPSR = $obj['PPSR']['totalItems'];
        $vin = $obj['vin'];


        //styles==
        $colorPrimary = "#0e4e92";
        $colorBlack = "#685b59";
        $colorLigth = "#5e7b9b";
        $colorBox = "#ededed";
        $yellow = "#fea504";


        //PPSR
        if ($totalItemPPSR > 0) {
            $ppsrDiv = <<<EOD
            <tr >
            <td width="15"></td>
            <td  width="565">
            <br style="line-height:45px;">
            <b style="color:$yellow;font-size:14px;letter-spacing: 2px;">Finance Check (PPSR)</b><br style="line-height:24px;">
            <span style="color:$yellow;font-size:13px;">$totalItemPPSR Financing Statement Found</span>
            </td>
            <td width="45" ><br style="line-height:65px;"><img width="45" height="40"  src="https://www.autoape.co.nz/assets/pdf/warning.png" alt="Laapp"></td>
            </tr>
            EOD;
        } else {
            $ppsrDiv = <<<EOD
            <tr >
            <td width="15"></td>
            <td  width="565">
            <br style="line-height:45px;">
            <b style="color:black;font-size:14px;letter-spacing: 2px;">Finance Check (PPSR)</b><br style="line-height:24px;">
            <span style="color:'$colorBlack';font-size:13px;">No financing statement found</span>
            </td>
            <td width="55" ><br style="line-height:65px;"><img width="45" height="40"  src="https://www.autoape.co.nz/assets/pdf/true.png" alt="Laapp"></td>
            </tr>
            EOD;
        }


        require_once('application/libraries/TCPDF/tcpdf2.php');
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'A4', true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('AutoApe');
        $pdf->SetTitle('Vehicle Sales Agreement');
        $pdf->SetSubject($rego);

        $pdf->SetPrintHeader(false);
        $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

        // set header and footer fonts
        $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        //left top rigth
        $pdf->SetMargins(12, 10, 12);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
            require_once(dirname(__FILE__) . '/lang/eng.php');
            $pdf->setLanguageArray($l);
        }

        // set default font subsetting mode
        $pdf->setFontSubsetting(true);

        // Set font
        // dejavusans is a UTF-8 Unicode font, if you only need to
        // print standard ASCII chars, you can use core fonts like
        // helvetica or times to reduce file size.
        $pdf->SetFont('helvetica', '', 14, '', true);

        // Add a page
        // This method has several options, check the source code documentation for more information.
        $pdf->AddPage();

        //Util
        $date = date("d M Y @ H:i");
        $space = "<p style='color:'white''></p>";
        $html = <<<EOD
          <table>
          <tr>
        <td><img width="370" height="105"  src="https://www.autoape.co.nz/assets/pdf/fc3.png" alt="Laapp"></td>
            <td style="text-align:right"><br style="line-height:24px;">
            <b style="color:$colorBlack;font-size:12px;">Report produced as at </b><br style="line-height:24px;">
            <span style="color:'$colorBlack';font-size:12px;">$date NZT</span><br style="line-height:24px;">
            <span style="color:'$colorBlack';font-size:12px;">Sourced from MBIE</span>
            </td>
          </tr>
          </table>
          $space
    EOD;


        //<b style="color:black;font-size:19px;letter-spacing: 2px;">$year $make $model</b><br style="line-height:24px;">

        //PPST
        $ppsrDivHeader = "";
        $ppsrCenter = "";
        $ppsrFooter = "";
        $ppsrDIV2 = "";
        if ($totalItemPPSR > 0) {

            $ppsrDivHeader = <<<EOD
            <div style="background-color:$colorBox;border-radius:50%;width:100%">
        <table cellpadding="1">
        <tr style="background-color:#fea500">
        <th><table><tr><td width="10"><p style='color:$colorBox'></p></td><td width="30"><b style="color:white;font-size:10px;line-height:10px;"></b><img width="25" height="25"  src="https://www.autoape.co.nz/assets/pdf/warning.png" alt="Laapp"></td><td width="600"  height="30" ><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:29px;">Finance Check (PPSR) - </b><span style="color:white;font-size:14px;line-height:29px;">$totalItemPPSR Financing statement found.</span></td></tr></table></th>
        </tr>
        <tr><td></td><td></td><td></td><td></td></tr>         
        EOD;

            $ppsrFooter = <<<EOD
        </table>
        </div>
        EOD;


            foreach ($obj['PPSR']['items'] as $ppsrItem) {
                $financeStatement = $ppsrItem->financingStatementKey->registrationNumber;
                $registrationDate = $ppsrItem->registrationDate;
                $registrationPlate = $ppsrItem->motorVehicles[0]->registrationPlate;
                $registrationVIN = $ppsrItem->motorVehicles[0]->vin;
                $securedParty = $ppsrItem->securedParties[0]->securedPartyName;

                $date5 = strtotime($registrationDate); //
                $newformat5 = date('j F Y', $date5);

                $ppsrCenter = $ppsrCenter . <<<EOD
        <tr><td width="15"></td>
        <td width="280"><b style="color:black;font-size:12px;letter-spacing: 1px;">Finance Statement #:</b></td>
        <td width="200"><span style="color:'$colorBlack';font-size:13px;">$financeStatement</span></td>
        </tr>
        <tr><td width="15"></td>
        <td width="280"><b style="color:black;font-size:12px;letter-spacing: 1px;">Registration date #:</b></td>
        <td width="200"><span style="color:'$colorBlack';font-size:13px;">$newformat5</span></td>
        </tr>
        <tr><td width="15"></td>
        <td width="280"><b style="color:black;font-size:12px;letter-spacing: 1px;">Registration Plate:</b></td>
        <td width="350"><span style="color:'$colorBlack';font-size:13px;">$registrationPlate</span></td>
        </tr>   
        <tr><td width="15"></td>
        <td width="280"><b style="color:black;font-size:12px;letter-spacing: 1px;">Registration Vin:</b></td>
        <td width="350"><span style="color:'$colorBlack';font-size:13px;">$registrationVIN</span></td>
        </tr>      
        <tr><td width="15"></td>
        <td width="280"><b style="color:black;font-size:12px;letter-spacing: 1px;">Secured Party:</b></td>
        <td width="350"><span style="color:'$colorBlack';font-size:13px;">$securedParty</span></td>
        </tr><br>           
        EOD;
            }

            $ppsrDIV2 = $ppsrDivHeader . $ppsrCenter . $ppsrFooter;
        } else {
            $ppsrDIV2 = <<<EOD
            <div style="background-color:$colorBox;border-radius:50%;width:100%">
        <table cellpadding="1">
        <tr style="background-color:#637887">
        <th><table><tr><td width="10"><p style='color:$colorBox'></p></td><td width="30"><b style="color:white;font-size:10px;line-height:10px;"></b><img width="25" height="25"  src="https://www.autoape.co.nz/assets/pdf/true.png" alt="Laapp"></td><td width="600"  height="30" ><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:29px;">Finance Check (PPSR) -</b><span style="color:white;font-size:14px;line-height:29px;"> No financing statement found.</span></td></tr></table></th>
        </tr>
        <tr><td></td><td></td><td></td><td></td></tr>
        <tr><td width="15"></td>
        <td width="400"><b style="color:black;font-size:12px;letter-spacing: 1px;">No Security Interest or financing Statements found.</b></td>
        <td width="50"><span style="color:'$colorBlack';font-size:13px;"></span></td>
        </tr>
            </table>
            </div>
        EOD;
        }

        //extra

        $divExtra1 = <<<EOD
        <i style="color:grat;font-size:9">Important: This report has been prepared based on the Plate number or Vehicle Identification Number (VIN) supplied by the user. It is
        provided by Auto Ape Limited with information from Government sources and other third parties. While AutoApe has used its best efforts to
        provide correct information, it does not guarantee or make any representations regarding the accuracy or suitability of this report for your
        needs. To the full extent permitted by law, AutoApe will not be liable for any loss or damage relating to your use of, or reliance on, this
        report.</i>
        EOD;

        $divExtra = <<<EOD
        
        <div style="background-color:$colorBox;border-radius:50%;width:100%">
        <table cellpadding="1">
        <tr height="10"><td width="80"></td><td width="550"></td></tr>
        <tr>
          <td width="30"></td>
          <td width="30"><img width="25" height="25"  src="https://www.autoape.co.nz/assets/pdf/info5.png" alt="Laapp"></td>
          <td width="550">
          <b style="color:$colorPrimary;font-size:15px;letter-spacing: 2px;">What is a Finance Check? </b>
          </td>
        </tr>
        <tr>
        <td width="15"></td>
        <td width="15"></td>
        <td width="600">
        <p style="color:$colorBlack;font-size:12px;line-height: 1.5; text-align: justify;">A security Interest or Financing Statement may indicate there is money owing on this vehicle. There are over 500,000
        registered debts on vehicles each year. If you are purchasing from a private seller, ask the seller to clear any money
        owing before finalising the purchase. Any outstanding debt could result in the repossession of the vehicle. For peace
        of mind, it is also a good idea to request proof of repayment and do a second Finance Check. AutoApe checks on all
        plates previously attached to a vehicle.</p>
        </td>
        </tr>
        </table>
        </div>
        <br><br>
        <b style="color:black;font-size:19px;letter-spacing: 2px;">$year $make $model</b><br style="line-height:24px;">
        EOD;



        // Print text using writeHTMLCell()

        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);


        $pdf->writeHTMLCell(0, 0, '', '', $divExtra, 0, 1, 0, true, '', true);


        $pdf->writeHTMLCell(0, 0, '', '', $ppsrDIV2, 0, 1, 0, true, '', true);

        $pdf->writeHTMLCell(0, 0, '', '', $divExtra1, 0, 1, 0, true, '', true);




        // ---------------------------------------------------------

        // Close and output PDF document
        // This method has several options, check the source code documentation for more information.
        //$pdf->Output(dirname(__FILE__) . '/documents/agreements/example_001.pdf', 'F');

        $now1 = time();
        $newformatNow1 = date('Ymdhs', $now1);

        $pdf->Output('/var/www/html/documents/ppsr/' . $rego . $newformatNow1 . '.pdf', 'F');
        //$pdf->Output($rego . $idMake . $idModel .  '.pdf', 'I');

        $stringUrl = "documents/ppsr/" . $rego .   $newformatNow1 . ".pdf";
        return $stringUrl;
        //============================================================+
        // END OF FILE
        //============================================================+

        //return $data;



    }

    public function generateVehicleReport($rego, $idCustomer)
    {

        //call funciones API
        $url = "http://3.106.101.41/api/values?";
        $result = file_get_contents($url . "rego=" . $rego . "&fn=vehicleDetails");
        $obj = [];
        json_decode($result, true);
        $result =  str_replace('false', '"false"', $result);
        $result =  str_replace('true', '"true"', $result);
        $json_data = json_decode($result, true);

        $result3 = file_get_contents($url . "rego=" . $rego . "&fn=ownerHistory");
        json_decode($result3, true);
        $result3 =  str_replace('false', '"false"', $result3);
        $result3 =  str_replace('true', '"true"', $result3);
        $json_owner = json_decode($result3, true);

        //vehicleDetails
        $obj['rego'] = $rego;
        $obj['year'] = $json_data['vehicleField']['yearOfManufactureField'];
        $obj['make'] = $json_data['vehicleField']['makeField'];
        $obj['model'] = $json_data['vehicleField']['modelField'];
        $obj['submodel'] = $json_data['vehicleField']['subModelField'];
        $obj['vin'] = $json_data['vehicleField']['vINField'];
        $obj['isOdometerProblem'] = false;
        $obj['firstRegitrationDate'] = $json_data['vehicleField']['dateOfFirstNZRegistrationField'];
        $obj['lastRegistrationDate'] = $json_data['vehicleField']['dateOfLastRegistrationField'];
        $obj['importedDamagedField'] = $json_data['vehicleField']['importedDamagedField'];

        $obj['powerField'] =  (isset($json_data['vehicleField']['powerField']) ? $json_data['vehicleField']['powerField'] : "-");
        $obj['cCRating'] =  (isset($json_data['vehicleField']['cCRatingField']) ? $json_data['vehicleField']['cCRatingField'] : "-");
        $obj['fuelType'] =  (isset($json_data['vehicleField']['fuelTypeField']['descriptionField']) ? $json_data['vehicleField']['fuelTypeField']['descriptionField'] : "-");
        $obj['seat'] =  (isset($json_data['vehicleField']['numberOfSeatsField']) ? $json_data['vehicleField']['numberOfSeatsField'] : "-");
        $obj['color'] =  (isset($json_data['vehicleField']['basicColourField']) ? $json_data['vehicleField']['basicColourField'] : "-");
        $obj['mass'] =  (isset($json_data['vehicleField']['grossVehicleMassField']) ? $json_data['vehicleField']['grossVehicleMassField'] : "-");
        $obj['country'] = $json_data['vehicleField']['countryOfOriginField']['descriptionField'];
        $obj['engineNumberField'] = $json_data['vehicleField']['engineNumberField'];
        $obj['subjectToWOFField'] = $json_data['vehicleField']['subjectToWOFField'];
        $obj['subjectToCOFField'] = $json_data['vehicleField']['subjectToCOFField'];
        $obj['isRegisteredOverseas'] = $json_data['vehicleField']['registeredOverseasField'];

        $obj['plates']  =  $json_data['vehicleField']['platesField'];

        if ($obj['isRegisteredOverseas'] === "true") {

            //getDate
            $dayOver = (isset($json_data['vehicleField']['dateOfFirstRegistrationOverseasField']['dayField']) ? $json_data['vehicleField']['dateOfFirstRegistrationOverseasField']['dayField'] : "01");
            $mothOver = (isset($json_data['vehicleField']['dateOfFirstRegistrationOverseasField']['monthField']) ? $json_data['vehicleField']['dateOfFirstRegistrationOverseasField']['monthField'] : "01");
            $yearOVer = (isset($json_data['vehicleField']['dateOfFirstRegistrationOverseasField']['yearField']) ? $json_data['vehicleField']['dateOfFirstRegistrationOverseasField']['yearField'] : "1990");

            $obj['isRegisteredOverseas'] = true;
            $obj['previousCountry'] = $json_data['vehicleField']['previousCountryOfRegistrationField']['descriptionField'];
            $obj['dateOverseas'] = $dayOver . "/" . $mothOver . "/" . $yearOVer;
            $obj['damagedUsedImport'] = $json_data['vehicleField']['importedDamagedField'];
        } else {
            $obj['isRegisteredOverseas'] = false;
            $obj['previousCountry'] = "Not Applicable";
            $obj['dateOverseas'] = "Not Applicable";
            $obj['damagedUsedImport'] = "Not Applicable";
        }


        //IMPORTED CHECK


        //Re-Registration
        $obj['reRegistrationField'] = $json_data['vehicleField']['latestDateOfRegistrationCancellationFieldSpecified'];
        $obj['registrationStatus'] = $json_data['vehicleField']['vehicleRegistrationStatusField']['descriptionField'];

        //COf



        $obj['isRUC'] =  $json_data['vehicleField']['subjectToRUCField'];
        //RUC

        if ($obj['isRUC'] === "true") {
            $result2 = file_get_contents($url . "rego=" . $rego . "&fn=ruc");
            json_decode($result2, true);
            $result2 =  str_replace('false', '"false"', $result2);
            $result2 =  str_replace('true', '"true"', $result2);
            $json_ruc = json_decode($result2, true);
            $obj['issueRucDate'] = $json_ruc['rucLicencesField'][0]['issueDateField'];
            $obj['licenceStartKMS'] = $json_ruc['rucLicencesField'][0]['itemsField'][0];
            $obj['licenceFinishKMS'] = $json_ruc['rucLicencesField'][0]['itemsField'][1];
        } else {
            $obj['issueRucDate'] = "Not Applicable";
            $obj['licenceStartKMS'] = "Not Applicable";
            $obj['licenceFinishKMS'] = "Not Applicable";
        }

        //re-registration field:

        if ($obj['reRegistrationField'] === "true") {
            $obj['reRegisteredField'] = true;
            $obj['reRegistrationDesc'] = $json_data['vehicleField']['latestRegistrationCancellationReasonField']['descriptionField'];
        } else {
            $obj['reRegisteredField'] = false;
            $obj['reRegistrationDesc'] = (isset($json_data['vehicleField']['latestRegistrationCancellationReasonField']) ? $json_data['vehicleField']['latestRegistrationCancellationReasonField']['descriptionField'] : "-");
        }

        if ($obj['registrationStatus'] === "Cancelled") {
            $obj['cancelledDesc'] = $json_data['vehicleField']['causeOfLatestRegistrationField']['descriptionField'];
        } else {
            $obj['cancelledDesc'] = $json_data['vehicleField']['causeOfLatestRegistrationField']['descriptionField'];
        }


        //re-registration
        if ($obj['firstRegitrationDate'] === $obj['lastRegistrationDate']) {
            $obj['reRegistered'] = false;
        } else {
            $obj['reRegistered'] = true;
        }


        //rego

        //$obj['regoStatus'] = 
        $obj['regoDateExpired'] = (isset($json_data['vehicleField']['mvrLicencesField'][0]) ? $json_data['vehicleField']['mvrLicencesField'][0]['expiryDateField'] : "Not Specified");
        $obj['regoIssueDate'] = (isset($json_data['vehicleField']['mvrLicencesField'][0]) ? $json_data['vehicleField']['mvrLicencesField'][0]['issueDateTimeField'] : "Not Specified");

        if ($obj['regoDateExpired'] !== "Not Specified") {

            $date = strtotime($obj['regoDateExpired']);
            $now = time();
            $newformatNow = date('j F Y', $now);
            $newformat = date('j F Y', $date);

            if ($newformat < $newformatNow) {
                $dif = $this->daysBetween($newformat, $newformatNow);
                $obj['isRegoExpired'] = false;
                $obj['RegoExpiredDays'] = $dif;
            } else {
                $obj['isRegoExpired'] = true;
                $obj['RegoExpiredDays'] = 0;
            }
        } else {
            $obj['isRegoExpired'] = true;
            $obj['RegoExpiredDays'] = 0;
        }


        //wof
        $obj['wofStatus'] = (isset($json_data['vehicleField']['inspectionsField'][0]) ? $json_data['vehicleField']['inspectionsField'][0]['resultField']['descriptionField'] : "Not Specified");
        $obj['wofDateExpired'] = (isset($json_data['vehicleField']['inspectionsField'][0]) ? $json_data['vehicleField']['inspectionsField'][0]['expiryDateField'] : "Not Specified");



        if ($obj['wofDateExpired'] !== "Not Specified") {

            $date = strtotime($obj['wofDateExpired']);
            //$date = strtotime('2021-12-10');
            $now = time();
            $newformatNow = date('j F Y', $now);
            $newformat = date('j F Y', $date);

            if ($obj['wofStatus'] === "Pass" && $newformat > $newformatNow) {
                $dif = $this->daysBetween($newformat, $newformatNow);
                $obj['isWOFExpired'] = false;
                $obj['WOFExpiredDays'] = $dif;
            } else {
                $obj['isWOFExpired'] = true;
                $obj['WOFExpiredDays'] = 0;
            }
        } else {
            $obj['isWOFExpired'] = true;
            $obj['WOFExpiredDays'] = 0;
        }


        //odometer
        $tempOdo = $json_data['vehicleField']['odometerReadingsField'];
        $tempOdo = array_reverse($tempOdo);
        $odoReal = [];
        $lastFrom = null;

        foreach ($tempOdo as $odo) {
            if (isset($lastFrom)) {
                if ($lastFrom['value'] <= $odo['readingField']) {
                    $objOdoTemp['value'] = $odo['readingField'];
                    $objOdoTemp['date'] = $odo['readingDateField'];
                    $objOdoTemp['source'] = $odo['sourceField']['descriptionField'];
                    $objOdoTemp['isCorrect'] = true;
                    $obj['lastDateWof'] = $odo['readingDateField'];;
                } else {
                    $objOdoTemp['value'] = $odo['readingField'];
                    $objOdoTemp['date'] = $odo['readingDateField'];
                    $objOdoTemp['source'] = $odo['sourceField']['descriptionField'];
                    $objOdoTemp['isCorrect'] = false;
                    $obj['isOdometerProblem'] = true;
                    $obj['lastDateWof'] = $odo['readingDateField'];;
                }
            } else {
                //$objOdoTemp['value'] = $odo['readingField']; //change production
                $objOdoTemp['value'] = "2000";
                $objOdoTemp['date'] = $odo['readingDateField'];
                $objOdoTemp['source'] = $odo['sourceField']['descriptionField'];
                $objOdoTemp['isCorrect'] = true;
                $obj['lastDateWof'] = $odo['readingDateField'];;
            }

            array_push($odoReal, $objOdoTemp);

            $lastFrom = $objOdoTemp;
        }

        $obj['odometers'] = $odoReal;

        //end odometer

        //Police Check
        $result = file_get_contents($url . "rego=" . $rego . "&fn=police");
        $result =  str_replace('false', '"false"', $result);
        $result =  str_replace('true', '"true"', $result);
        $json_data2 = json_decode($result, true);
        $obj['isStolen'] = $json_data2['vehicleField']['reportedStolenField'];

        //Import Check

        //Ownership history
        $obj['ownerHistory'] = $json_owner['itemField']['registrationsField'];
        $obj['ownerTotal'] = count($obj['ownerHistory']);
        if ($obj['ownerTotal'] > 0) {
            $dateOwner = strtotime($obj['ownerHistory'][0]['registrationDateField']);
            $now = time();
            $newformatNow = date('j F Y', $now);
            $newformat = date('j F Y', $dateOwner);
            $newformatPFG = date('d-m-Y', $dateOwner);
            $obj['currentDateOwner'] = $newformatPFG;
            $dif = $this->daysBetween($newformat, $newformatNow);
            $obj['ownerDays'] = $dif;
        } else {
            $obj['ownerDays'] = 0;
            $obj['currentDateOwner'] = "Information not available";
        }

        if (isset($obj['ownerHistory'][0]['partiesField'][0]['partyNameField'])) {

            $currentOwnerObj = $obj['ownerHistory'][0]['partiesField'][0]['partyNameField'];
            $obj['currentOwnerStatus'] = $obj['ownerHistory'][0]['registrationStatusField']['descriptionField'];


            if (isset($currentOwnerObj['organisationNameField'])) {
                $obj['nameOwner'] = $currentOwnerObj['organisationNameField'][0]['nameElementField'][0]['valueField'];
            } else {
                $obj['nameOwner'] = $currentOwnerObj['personNameField'][0]['nameElementField'][0]['valueField'];
            }
        } else {
            $obj['nameOwner'] = "Information not available";
        }


        //PPSR
        //$objPPSR = $this->getFinancial($obj['vin']); 
        $objPPSR = json_decode($this->getFinancial($obj['vin']));
        //$objPPSR =json_decode("{\n  \"billingReference\" : null,\n  \"paymentResultId\" : \"cbfae2-XnjptWauYaQe3vT\",\n  \"note\" : \"Results are listed by date and time of PPSR registration, based on the search criteria, and do NOT establish priority.\",\n  \"searchId\" : \"9867954\",\n  \"searchDateTime\" : \"2021-08-31T10:38:30.811+12:00\",\n  \"totalPages\" : 1,\n  \"totalItems\" : 1,\n  \"page\" : 1,\n  \"pageSize\" : 10,\n  \"sortBy\" : \"registrationDate\",\n  \"sortOrder\" : \"asc\",\n  \"searchRequest\" : {\n    \"searchBy\" : \"motorVehicle\",\n    \"legitimateSearchReason\" : \"yes\",\n    \"searchByMotorVehicle\" : {\n      \"vin\" : \"7AT0DH79X17332335\"\n    },\n    \"page\" : 1,\n    \"pageSize\" : 10\n  },\n  \"items\" : [ {\n    \"status\" : \"registered\",\n    \"expiryDate\" : \"2022-08-01T14:09:11.000+12:00\",\n    \"priorRegistrationDate\" : null,\n    \"registrationDate\" : \"2017-08-01T14:09:11.000+12:00\",\n    \"financingStatementKey\" : {\n      \"registrationNumber\" : \"FN0ND18MR7871225\",\n      \"version\" : 0\n    },\n    \"debtors\" : [ {\n      \"debtorType\" : \"person\",\n      \"debtorName\" : \"Oliver Fabilitante CABRERA\",\n      \"dateOfBirth\" : \"1973-10-14\",\n      \"debtorReference\" : \"1-7-639718\",\n      \"cityTown\" : \"AUCKLAND 0600\"\n    } ],\n    \"securedParties\" : [ {\n      \"securedPartyName\" : \"FINANCE NOW LIMITED\",\n      \"securedPartyType\" : \"organisation\"\n    } ],\n    \"collateralTypes\" : [ {\n      \"code\" : \"MV\",\n      \"description\" : \"Goods - Motor Vehicles\"\n    } ],\n    \"motorVehicles\" : [ {\n      \"chassis\" : null,\n      \"make\" : \"NISSAN\",\n      \"model\" : \"NOTE\",\n      \"registrationPlate\" : \"KPS921\",\n      \"vin\" : \"7AT0DH79X17332335\",\n      \"year\" : \"2008\"\n    } ]\n  } ]\n}");
        $obj['PPSR']['totalItems'] = $objPPSR->totalItems;
        $obj['PPSR']['items'] = $objPPSR->items;

        $url =  $this->testPdfReport($obj);
        $urlFinal = "https://autoape.co.nz/" . $url;
        //$data = $this->json_encode_msgs($urlFinal);

        //saveFile in Documents Customer
        $objDB = [];

        $objDB['document_url'] = $url;
        $objDB['fk_customer'] = $idCustomer;
        $objDB['document_type'] = 1;


        $id = $this->Maxauto_Model->insertDocument($objDB);

        $objDB['document_url_final'] = $urlFinal;
        $data = $this->json_encode_msgs($objDB);
        return $objDB;
    }

    public function testPdfReport($obj)
    {

        date_default_timezone_set("Pacific/Auckland");

        //set Vehicles Values
        $year = $obj['year'];
        $make = $obj['make'];
        $model = $obj['model'];
        $submodel = $obj['submodel'];
        $rego = $obj['rego'];
        $isStolen =  $obj['isStolen'];
        $isOdometerProblem = $obj['isOdometerProblem'];
        $space = "<p style='color:'white''></p>";
        $totalItemPPSR = $obj['PPSR']['totalItems'];
        $item = $obj['PPSR']['items'];
        $wofStatus = $obj['wofStatus'];
        $isWOFExpired = $obj['isWOFExpired'];
        $WOFExpiredDays = $obj['WOFExpiredDays'];
        $ownerTotal = $obj['ownerTotal'];
        $ownerDays = $obj['ownerDays'];
        $nameOwner = $obj['nameOwner'];
        $currentOwnerStatus = $obj['currentOwnerStatus'];
        $currentDateOwner = $obj['currentDateOwner'];
        $ownerHistory = $obj['ownerHistory'];
        $reRegistered = $obj['reRegistered'];
        $importedDamaged = $obj['importedDamagedField'];
        $importedDamagedField = $obj['importedDamagedField'];
        $damagedUsedImport = $obj['damagedUsedImport'];
        $vin = $obj['vin'];
        $power = $obj['powerField'];
        $cCRating = $obj['cCRating'];
        $fuelType = $obj['fuelType'];
        $seat = $obj['seat'];
        $color = $obj['color'];
        $mass = $obj['mass'];
        $country = $obj['country'];
        $engineNumber = $obj['engineNumberField'];
        $subjectToWof = $obj['subjectToWOFField'];
        $subjectToCOF = $obj['subjectToCOFField'];

        $reRegistrationDesc = $obj['reRegistrationDesc'];


        $plates =  $obj['plates'];

        $lastDateWof = $obj['lastDateWof'];

        $isRUC = $obj['isRUC'];
        $issueRucDate = $obj['issueRucDate'];
        $licenceStartKMS = $obj['licenceStartKMS'];
        $licenceFinishKMS = $obj['licenceFinishKMS'];


        $registrationStatus = $obj['registrationStatus'];
        $cancelledDesc = $obj['cancelledDesc'];
        $firstRegitrationDate = $obj['firstRegitrationDate'];
        $lastRegistrationDate = $obj['lastRegistrationDate'];

        $previousCountry = $obj['previousCountry'];
        $dateOverseas = $obj['dateOverseas'];
        $isRegisteredOverseas = $obj['isRegisteredOverseas'];


        $regoDateExpired = $obj['regoDateExpired'];
        $regoIssueDate = $obj['regoIssueDate'];
        $isRegoExpired = $obj['isRegoExpired'];
        $RegoExpiredDays = $obj['RegoExpiredDays'];

        //styles==
        $colorPrimary = "#0e4e92";
        $colorBlack = "#685b59";
        $colorLigth = "#5e7b9b";
        $colorBox = "#ededed";
        $yellow = "#fea504";

        //Smart Function
        if ($isStolen == "true") {
            $stolenDiv = <<<EOD
            <tr >
            <td width="15"></td>
            <td  width="565">
            <br style="line-height:45px;">
            <b style="color:$yellow;font-size:14px;letter-spacing: 2px;">Stolen Vehicle – Police Check</b><br style="line-height:24px;">
            <span style="color:$yellow;font-size:13px;">Vehicle reported as STOLEN to the NZ Police</span>
            </td>
            <td width="45" ><br style="line-height:65px;"><img width="45" height="40"  src="https://www.autoape.co.nz/assets/pdf/warning.png" alt="Laapp"></td>
            </tr>
            EOD;
        } else {
            $stolenDiv = <<<EOD
            <tr >
            <td width="15"></td>
            <td  width="565">
            <br style="line-height:45px;">
            <b style="color:black;font-size:14px;letter-spacing: 2px;">Stolen Vehicle – Police Check</b><br style="line-height:24px;">
            <span style="color:'$colorBlack';font-size:13px;">Vehicle Not reported as stolen</span>
            </td>
            <td width="55" ><br style="line-height:65px;"><img width="45" height="40"  src="https://www.autoape.co.nz/assets/pdf/true.png" alt="Laapp"></td>
            </tr>
            EOD;
        }

        //odemeter
        if ($isOdometerProblem == "true") {
            $odometerDiv = <<<EOD
            <tr >
            <td width="15"></td>
            <td  width="565">
            <br style="line-height:45px;">
            <b style="color:$yellow;font-size:14px;letter-spacing: 2px;">Odometer Record</b><br style="line-height:24px;">
            <span style="color:$yellow;font-size:13px;">Inconsistent record detected</span>
            </td>
            <td width="45" ><br style="line-height:65px;"><img width="45" height="40"  src="https://www.autoape.co.nz/assets/pdf/warning.png" alt="Laapp"></td>
            </tr>
            EOD;
        } else {
            $odometerDiv = <<<EOD
            <tr >
            <td width="15"></td>
            <td  width="565">
            <br style="line-height:45px;">
            <b style="color:black;font-size:14px;letter-spacing: 2px;">Odometer Record</b><br style="line-height:24px;">
            <span style="color:'$colorBlack';font-size:13px;">0 Inconsistent record detected</span>
            </td>
            <td width="55" ><br style="line-height:65px;"><img width="45" height="40"  src="https://www.autoape.co.nz/assets/pdf/true.png" alt="Laapp"></td>
            </tr>
            EOD;
        }

        //PPSR
        if ($totalItemPPSR > 0) {
            $ppsrDiv = <<<EOD
            <tr >
            <td width="15"></td>
            <td  width="565">
            <br style="line-height:45px;">
            <b style="color:$yellow;font-size:14px;letter-spacing: 2px;">Finance Check (PPSR)</b><br style="line-height:24px;">
            <span style="color:$yellow;font-size:13px;">$totalItemPPSR Financing Statement Found</span>
            </td>
            <td width="45" ><br style="line-height:65px;"><img width="45" height="40"  src="https://www.autoape.co.nz/assets/pdf/warning.png" alt="Laapp"></td>
            </tr>
            EOD;
        } else {
            $ppsrDiv = <<<EOD
            <tr >
            <td width="15"></td>
            <td  width="565">
            <br style="line-height:45px;">
            <b style="color:black;font-size:14px;letter-spacing: 2px;">Finance Check (PPSR)</b><br style="line-height:24px;">
            <span style="color:'$colorBlack';font-size:13px;">No financing statement found</span>
            </td>
            <td width="55" ><br style="line-height:65px;"><img width="45" height="40"  src="https://www.autoape.co.nz/assets/pdf/true.png" alt="Laapp"></td>
            </tr>
            EOD;
        }

        //WOF OR COF


        if ($subjectToCOF == "true") {
            if ($isWOFExpired === true) {
                $wofDiv = <<<EOD
                <tr >
                <td width="15"></td>
                <td  width="565">
                <br style="line-height:45px;">
                <b style="color:$yellow;font-size:14px;letter-spacing: 2px;">Certificate of Fitness Inspection</b><br style="line-height:24px;">
                <span style="color:$yellow;font-size:13px;">WOF has expired</span>
                </td>
                <td width="45" ><br style="line-height:65px;"><img width="45" height="40"  src="https://www.autoape.co.nz/assets/pdf/warning.png" alt="Laapp"></td>
                </tr>
                EOD;
            } else {
                if ($WOFExpiredDays <= 30) {
                    $wofDiv = <<<EOD
                    <tr >
                    <td width="15"></td>
                    <td  width="565">
                    <br style="line-height:45px;">
                    <b style="color:$yellow;font-size:14px;letter-spacing: 2px;">Certificate of Fitness Inspection</b><br style="line-height:24px;">
                    <span style="color:$yellow;font-size:13px;">WOF expiring in the next $WOFExpiredDays days</span>
                    </td>
                    <td width="45" ><br style="line-height:65px;"><img width="45" height="40"  src="https://www.autoape.co.nz/assets/pdf/warning.png" alt="Laapp"></td>
                    </tr>
                    EOD;
                } else {
                    $wofDiv = <<<EOD
                    <tr >
                    <td width="15"></td>
                    <td  width="565">
                    <br style="line-height:45px;">
                    <b style="color:black;font-size:14px;letter-spacing: 2px;">Certificate of Fitness Inspection</b><br style="line-height:24px;">
                    <span style="color:'$colorBlack';font-size:13px;">Passed.</span>
                    </td>
                    <td width="55" ><br style="line-height:65px;"><img width="45" height="40"  src="https://www.autoape.co.nz/assets/pdf/true.png" alt="Laapp"></td>
                    </tr>
                    EOD;
                }
            }
        } else {
            if ($isWOFExpired === true) {
                $wofDiv = <<<EOD
                <tr >
                <td width="15"></td>
                <td  width="565">
                <br style="line-height:45px;">
                <b style="color:$yellow;font-size:14px;letter-spacing: 2px;">Warrant of Fitness</b><br style="line-height:24px;">
                <span style="color:$yellow;font-size:13px;">WOF has expired</span>
                </td>
                <td width="45" ><br style="line-height:65px;"><img width="45" height="40"  src="https://www.autoape.co.nz/assets/pdf/warning.png" alt="Laapp"></td>
                </tr>
                EOD;
            } else {
                if ($WOFExpiredDays <= 30) {
                    $wofDiv = <<<EOD
                    <tr >
                    <td width="15"></td>
                    <td  width="565">
                    <br style="line-height:45px;">
                    <b style="color:$yellow;font-size:14px;letter-spacing: 2px;">Warrant of Fitness</b><br style="line-height:24px;">
                    <span style="color:$yellow;font-size:13px;">WOF expiring in the next $WOFExpiredDays days</span>
                    </td>
                    <td width="45" ><br style="line-height:65px;"><img width="45" height="40"  src="https://www.autoape.co.nz/assets/pdf/warning.png" alt="Laapp"></td>
                    </tr>
                    EOD;
                } else {
                    $wofDiv = <<<EOD
                    <tr >
                    <td width="15"></td>
                    <td  width="565">
                    <br style="line-height:45px;">
                    <b style="color:black;font-size:14px;letter-spacing: 2px;">Warrant of Fitness</b><br style="line-height:24px;">
                    <span style="color:'$colorBlack';font-size:13px;">Passed.</span>
                    </td>
                    <td width="55" ><br style="line-height:65px;"><img width="45" height="40"  src="https://www.autoape.co.nz/assets/pdf/true.png" alt="Laapp"></td>
                    </tr>
                    EOD;
                }
            }
        }



        if ($registrationStatus === "Cancelled") {
            $regoDiv = <<<EOD
            <tr >
            <td width="15"></td>
            <td  width="565">
            <br style="line-height:45px;">
            <b style="color:$yellow;font-size:14px;letter-spacing: 2px;">Registration</b><br style="line-height:24px;">
            <span style="color:$yellow;font-size:13px;">Registration has been cancelled</span>
            </td>
            <td width="45" ><br style="line-height:65px;"><img width="45" height="40"  src="https://www.autoape.co.nz/assets/pdf/warning.png" alt="Laapp"></td>
            </tr>
            EOD;
        } else {
            //En Production REGO
            if ($isRegoExpired === true) {
                $regoDiv = <<<EOD
    <tr >
    <td width="15"></td>
    <td  width="565">
    <br style="line-height:45px;">
    <b style="color:$yellow;font-size:14px;letter-spacing: 2px;">Registration</b><br style="line-height:24px;">
    <span style="color:$yellow;font-size:13px;">Registration is expired</span>
    </td>
    <td width="45" ><br style="line-height:65px;"><img width="45" height="40"  src="https://www.autoape.co.nz/assets/pdf/warning.png" alt="Laapp"></td>
    </tr>
    EOD;
            } else {
                if ($RegoExpiredDays <= 30) {
                    $regoDiv = <<<EOD
        <tr >
        <td width="15"></td>
        <td  width="565">
        <br style="line-height:45px;">
        <b style="color:$yellow;font-size:14px;letter-spacing: 2px;">Registration</b><br style="line-height:24px;">
        <span style="color:$yellow;font-size:13px;">REGO expiring in the next $RegoExpiredDays days</span>
        </td>
        <td width="45" ><br style="line-height:65px;"><img width="45" height="40"  src="https://www.autoape.co.nz/assets/pdf/warning.png" alt="Laapp"></td>
        </tr>
        EOD;
                } else {
                    $regoDiv = <<<EOD
        <tr >
        <td width="15"></td>
        <td  width="565">
        <br style="line-height:45px;">
        <b style="color:black;font-size:14px;letter-spacing: 2px;">Registration</b><br style="line-height:24px;">
        <span style="color:'$colorBlack';font-size:13px;">Vehicle Registration is Active.</span>
        </td>
        <td width="55" ><br style="line-height:65px;"><img width="45" height="40"  src="https://www.autoape.co.nz/assets/pdf/true.png" alt="Laapp"></td>
        </tr>
        EOD;
                }
            }
        }


        if ($ownerDays <= 30) {
            $ownerDiv = <<<EOD
            <tr >
            <td width="15"></td>
            <td  width="565">
            <br style="line-height:45px;">
            <b style="color:$yellow;font-size:14px;letter-spacing: 2px;">Ownership History</b><br style="line-height:24px;">
            <span style="color:$yellow;font-size:13px;">Current owner has owned the vehicle for only $ownerDays days. Please refer to report &
            Buyer’s resources for more details.</span>
            </td>
            <td width="45" ><br style="line-height:65px;"><img width="45" height="40"  src="https://www.autoape.co.nz/assets/pdf/warning.png" alt="Laapp"></td>
            </tr>
            EOD;
        } else {
            $ownerDiv = <<<EOD
            <tr >
            <td width="15"></td>
            <td  width="565">
            <br style="line-height:45px;">
            <b style="color:black;font-size:14px;letter-spacing: 2px;">Ownership History</b><br style="line-height:24px;">
            <span style="color:'$colorBlack';font-size:13px;">$ownerTotal Previous owners found</span>
            </td>
            <td width="55" ><br style="line-height:65px;"><img width="45" height="40"  src="https://www.autoape.co.nz/assets/pdf/true.png" alt="Laapp"></td>
            </tr>
            EOD;
        }

        $registerDiv = "";
        if ($reRegistered === true) {
            $registerDiv = <<<EOD
            <tr >
            <td width="15"></td>
            <td  width="565">
            <br style="line-height:45px;">
            <b style="color:$yellow;font-size:14px;letter-spacing: 2px;">Ownership History</b><br style="line-height:24px;">
            <span style="color:$yellow;font-size:13px;">Vehicle has been re-registered. Please refer to report & Buyer’s resources for more
            details.</span>
            </td>
            <td width="45" ><br style="line-height:65px;"><img width="45" height="40"  src="https://www.autoape.co.nz/assets/pdf/warning.png" alt="Laapp"></td>
            </tr>
            EOD;
        } else {
            $registerDiv = <<<EOD
            <tr >
            <td width="15"></td>
            <td  width="565">
            <br style="line-height:45px;">
            <b style="color:black;font-size:14px;letter-spacing: 2px;">Re-registration Check</b><br style="line-height:24px;">
            <span style="color:'$colorBlack';font-size:13px;">No history of re-registration</span>
            </td>
            <td width="55" ><br style="line-height:65px;"><img width="45" height="40"  src="https://www.autoape.co.nz/assets/pdf/true.png" alt="Laapp"></td>
            </tr>
            EOD;
        }

        if ($importedDamaged === "true") {
            $importedDiv = <<<EOD
            <tr >
            <td width="15"></td>
            <td  width="565">
            <br style="line-height:45px;">
            <b style="color:$yellow;font-size:14px;letter-spacing: 2px;">Import Check</b><br style="line-height:24px;">
            <span style="color:$yellow;font-size:13px;">Border Inspection has declared the Vehicle has been imported with structural
            damage or deterioration. Please refer to Buyer’s resources for more details.</span>
            </td>
            <td width="45" ><br style="line-height:45px;"><img width="45" height="40"  src="https://www.autoape.co.nz/assets/pdf/warning.png" alt="Laapp"></td>
            </tr>
            EOD;
        } else {
            $importedDiv = <<<EOD
            <tr >
            <td width="15"></td>
            <td width="565">
            <br style="line-height:45px;">
            <b style="color:black;font-size:14px;letter-spacing: 2px;">Import Check</b><br style="line-height:24px;">
            <span style="color:'$colorBlack';font-size:13px;">No record of Structural Damage found</span>
            </td>
            <td width="55" ><br style="line-height:45px;"><img width="45" height="40"  src="https://www.autoape.co.nz/assets/pdf/true.png" alt="Laapp"></td>
            </tr>
            EOD;
        }


        require_once('application/libraries/TCPDF/tcpdf2.php');
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'A4', true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('AutoApe');
        $pdf->SetTitle('Vehicle Sales Agreement');
        $pdf->SetSubject($rego);

        $pdf->SetPrintHeader(false);
        $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

        // set header and footer fonts
        $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        //left top rigth
        $pdf->SetMargins(12, 10, 12);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
            require_once(dirname(__FILE__) . '/lang/eng.php');
            $pdf->setLanguageArray($l);
        }

        // set default font subsetting mode
        $pdf->setFontSubsetting(true);

        // Set font
        // dejavusans is a UTF-8 Unicode font, if you only need to
        // print standard ASCII chars, you can use core fonts like
        // helvetica or times to reduce file size.
        $pdf->SetFont('helvetica', '', 14, '', true);

        // Add a page
        // This method has several options, check the source code documentation for more information.
        $pdf->AddPage();

        //Util
        $date = date("d M Y @ H:i");

        $html = <<<EOD
          <table>
          <tr>
        <td><img width="370" height="85"  src="https://www.autoape.co.nz/assets/pdf/logo.png" alt="Laapp"></td>
            <td style="text-align:right"><br style="line-height:24px;">
            <b style="color:$colorBlack;font-size:12px;">Report produced as at </b><br style="line-height:24px;">
            <span style="color:'$colorBlack';font-size:12px;">$date NZT</span><br style="line-height:24px;">
            <span style="color:'$colorBlack';font-size:12px;">Sourced from NZTA & MBIE</span>
            </td>
          </tr>
          </table>
          $space
          <div style="background-color:$colorBox;border-radius:50%;width:100%">
          <table>
          <tr>
            <td>
            <br style="line-height:60px;">
            <b style="color:black;font-size:19px;letter-spacing: 2px;">$year $make $model</b><br style="line-height:24px;">
            <br style="line-height:14px;">
            <span style="color:$colorBlack;font-size:14px;letter-spacing: 2px;">$submodel</span>
            </td>
          </tr>
          </table>
          </div>

          <br style="line-height:24px;">

          <div style="background-color:$colorBox;border-radius:50%;width:100%">
    <table cellpadding="1">
    <tr style="background-color:#637887">
    <th><table><tr><td width="10"><p style='color:$colorBox'></p></td><td width="40"><b style="color:white;font-size:14px;line-height:13px;"></b><img width="32" height="30"  src="https://www.autoape.co.nz/assets/pdf/logodoc.png" alt="Laapp"></td><td  height="30" ><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:29px;">Report Summary</b></td></tr></table></th>
    </tr>
        $stolenDiv
        $odometerDiv
        $ppsrDiv
        $wofDiv
        $regoDiv
        $ownerDiv
        $registerDiv
        $importedDiv
          </table>
          </div>

    EOD;

        $html2 = <<<EOD
    <div style="background-color:$colorBox;border-radius:50%;width:100%">
<table cellpadding="1">
<tr style="background-color:#637887">
<th><table><tr><td width="10"><p style='color:$colorBox'></p></td><td width="30"><b style="color:white;font-size:14px;line-height:13px;"></b><img width="25" height="25"  src="https://www.autoape.co.nz/assets/pdf/info3.png" alt="Laapp"></td><td  height="30" ><b style="color:white;font-size:14px;line-height:12px;"></b><b style="color:white;font-size:14px;line-height:24px;">Vehicle Details</b></td></tr></table></th>
</tr>
<tr><td></td><td></td><td></td><td></td></tr>
<tr style="line-height: 2;"><td width="15"></td>
<td width="150"><b style="color:black;font-size:12px;letter-spacing: 1px;">VIN</b></td>
<td style="text-align:center" width="200"><span style="color:'$colorBlack';font-size:13px;">$vin</span></td>
<td width="120"><b style="color:black;font-size:12px;letter-spacing: 1px;">Power</b></td>
<td style="text-align:center" width="150"><span style="color:'$colorBlack';font-size:13px;">$power KW</span></td>
</tr>
<tr style="line-height: 2;"><td width="15"></td>
<td width="150"><b style="color:black;font-size:12px;letter-spacing: 1px;">Plate</b></td>
<td style="text-align:center" width="200"><span style="color:'$colorBlack';font-size:13px;">$rego</span></td>
<td width="120"><b style="color:black;font-size:12px;letter-spacing: 1px;">Engine CC </b></td>
<td style="text-align:center" width="150"><span style="color:'$colorBlack';font-size:13px;">$cCRating</span></td>
</tr>
<tr style="line-height: 2;"><td width="15"></td>
<td width="150"><b style="color:black;font-size:12px;letter-spacing: 1px;">Make</b></td>
<td style="text-align:center" width="200"><span style="color:'$colorBlack';font-size:13px;">$make</span></td>
<td width="120"><b style="color:black;font-size:12px;letter-spacing: 1px;">Fuel Type</b></td>
<td style="text-align:center" width="150"><span style="color:'$colorBlack';font-size:13px;">$fuelType</span></td>
</tr>
<tr style="line-height: 2;"><td width="15"></td>
<td width="150"><b style="color:black;font-size:12px;letter-spacing: 1px;">Model</b></td>
<td style="text-align:center" width="200"><span style="color:'$colorBlack';font-size:13px;">$model</span></td>
<td width="120"><b style="color:black;font-size:12px;letter-spacing: 1px;">Number of Seats</b></td>
<td style="text-align:center" width="150"><span style="color:'$colorBlack';font-size:13px;">$seat</span></td>
</tr>
<tr style="line-height: 2;"><td width="15"></td>
<td width="150"><b style="color:black;font-size:12px;letter-spacing: 1px;">Color</b></td>
<td style="text-align:center" width="200"><span style="color:'$colorBlack';font-size:13px;">$color</span></td>
<td width="120"><b style="color:black;font-size:12px;letter-spacing: 1px;">Vehicle Mass</b></td>
<td style="text-align:center" width="150"><span style="color:'$colorBlack';font-size:13px;">$mass</span></td>
</tr>
<tr><td width="15"></td>
<td width="150"><b style="color:black;font-size:12px;letter-spacing: 1px;">Country of Assembly</b></td>
<td style="text-align:center" width="200"><span style="color:'$colorBlack';font-size:13px;">$country</span></td>
<td width="120"><b style="color:black;font-size:12px;letter-spacing: 1px;">Engine Number</b></td>
<td style="text-align:center" width="150"><span style="color:'$colorBlack';font-size:13px;">$engineNumber</span></td>
</tr>
    </table>
    </div>
EOD;

        $platesDivHeader = <<<EOD
    <div style="background-color:$colorBox;border-radius:50%;width:100%">
<table cellpadding="1">
<tr style="background-color:#637887">
<th><table><tr><td width="10"><p style='color:$colorBox'></p></td><td width="30"><b style="color:white;font-size:14px;line-height:13px;"></b><img width="25" height="25"  src="https://www.autoape.co.nz/assets/pdf/info3.png" alt="Laapp"></td><td  height="30" ><b style="color:white;font-size:14px;line-height:12px;"></b><b style="color:white;font-size:14px;line-height:24px;">Plate History</b></td></tr></table></th>
</tr>
<tr><td></td><td></td><td></td><td></td></tr>
<tr style="line-height: 2;"><td width="15"></td>
<td  style="text-align:left" width="220"><b style="color:black;font-size:12px;letter-spacing: 1px;">Plate Number</b></td>
<td  style="text-align:center" width="220"><b style="color:black;font-size:12px;letter-spacing: 1px;">Plate Type</b></td>
<td  style="text-align:rigth" width="180"><b style="color:black;font-size:12px;letter-spacing: 1px;">Issue date</b></td>
</tr>
EOD;
        $platesDivCenter = "";
        $platesDivFooter =  <<<EOD
    </table>
    </div>
EOD;

        foreach ($plates as $plate) {
            $plateNumber = $plate['plateNumberField'];
            $plateType = (isset($plate['plateTypeField']) ? $plate['plateTypeField']['descriptionField'] : "-");
            $plateDate = $plate['effectiveDateField'];
            $date = strtotime($plateDate);
            $newformat = date('j F Y', $date);

            $platesDivCenter = $platesDivCenter .  <<<EOD
        <tr style="line-height: 2;"><td width="15"></td>
        <td  style="text-align:left" width="220"><span style="color:'$colorBlack';font-size:13px;">$plateNumber</span></td>
        <td  style="text-align:center" width="220"><span style="color:'$colorBlack';font-size:13px;">$plateType</span></td>
        <td  style="text-align:rigth" width="180"><span style="color:'$colorBlack';font-size:13px;">$newformat</span></td>
        </tr>
        EOD;
        }

        $plateDivFinal = $platesDivHeader . $platesDivCenter . $platesDivFooter;


        $odoDivHeader = "";
        $odoCenter = "";
        $odoFooter = "";

        if ($isOdometerProblem == "true") {

            $odoDivHeader = <<<EOD
    <div style="background-color:$colorBox;border-radius:50%;width:100%">
    <table cellpadding="1">
    <tr style="background-color:#fea500">
    <th><table><tr><td width="10"><p style='color:$colorBox'></p></td><td width="30"><b style="color:white;font-size:10px;line-height:10px;"></b><img width="25" height="25"  src="https://www.autoape.co.nz/assets/pdf/warning.png" alt="Laapp"></td><td width="300"  height="30" ><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:29px;">Odometer History – </b><span style="color:white;font-size:14px;line-height:29px;">Inconsistency found</span></td></tr></table></th>
    </tr>
    <tr><td></td><td></td><td></td><td></td></tr>
    <tr style="line-height: 2;"><td width="15"></td>
    <td  style="text-align:left" width="220"><b style="color:black;font-size:12px;letter-spacing: 1px;">Type</b></td>
    <td  style="text-align:center" width="220"><b style="color:black;font-size:12px;letter-spacing: 1px;">Date</b></td>
    <td  style="text-align:rigth" width="180"><b style="color:black;font-size:12px;letter-spacing: 1px;">Odometer (KM)</b></td>
    </tr>
    EOD;

            $odoFooter = <<<EOD
    </table>
    </div>
    EOD;

            $obj['odometers'] = array_reverse($obj['odometers']);

            foreach ($obj['odometers'] as $odometers) {

                $value = $odometers['value'];
                $source = $odometers['source'];
                $isCorrect = $odometers['isCorrect'];

                $date = strtotime($odometers['date']);
                $newformat = date('j F Y', $date);

                if ($isCorrect == "true") {
                    $odoCenter = $odoCenter .  <<<EOD
            <tr style="line-height: 2;"><td width="15"></td>
            <td  style="text-align:left" width="220"><span style="color:'$colorBlack';font-size:13px;">$source</span></td>
            <td  style="text-align:center" width="220"><span style="color:'$colorBlack';font-size:13px;">$newformat</span></td>
            <td  style="text-align:rigth" width="180"><span style="color:'$colorBlack';font-size:13px;">$value</span></td>
            </tr>
            EOD;
                } else {
                    $odoCenter = $odoCenter .  <<<EOD
            <tr style="line-height: 2;">
            <td width="15"></td>
            <td  style="text-align:left" width="220"><span style="color:#fea500;font-size:13px;">$source</span></td>
            <td  style="text-align:center" width="220"><span style="color:#fea500;font-size:13px;">$newformat</span></td>
            <td  style="text-align:rigth" width="180"><span style="color:#fea500;font-size:13px;">$value</span></td>
            </tr>
            EOD;
                }
            }
        } else {

            $odoDivHeader = <<<EOD
    <div style="background-color:$colorBox;border-radius:50%;width:100%">
    <table cellpadding="1">
    <tr style="background-color:#637887">
    <th><table><tr><td width="10"><p style='color:$colorBox'></p></td><td width="30"><b style="color:white;font-size:10px;line-height:10px;"></b><img width="25" height="25"  src="https://www.autoape.co.nz/assets/pdf/true.png" alt="Laapp"></td><td width="300"  height="30" ><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:29px;">Odometer History – </b><span style="color:white;font-size:14px;line-height:29px;">No Inconsistency found</span></td></tr></table></th>
    </tr>
    <tr><td></td><td></td><td></td><td></td></tr>
    <tr style="line-height: 2;"><td width="15"></td>
    <td  style="text-align:left" width="220"><b style="color:black;font-size:12px;letter-spacing: 1px;">Type</b></td>
    <td  style="text-align:center" width="220"><b style="color:black;font-size:12px;letter-spacing: 1px;">Date</b></td>
    <td  style="text-align:rigth" width="180"><b style="color:black;font-size:12px;letter-spacing: 1px;">Odometer (KM)</b></td>
    </tr>
    EOD;

            $odoFooter = <<<EOD
    </table>
    </div>
    EOD;


            $obj['odometers'] = array_reverse($obj['odometers']);

            foreach ($obj['odometers'] as $odometers) {

                $value = $odometers['value'];
                $source = $odometers['source'];

                $date = strtotime($odometers['date']);
                $newformat = date('j F Y', $date);

                $odoCenter = $odoCenter .  <<<EOD
        <tr style="line-height: 2;"><td width="15"></td>
        <td  style="text-align:left" width="220"><span style="color:'$colorBlack';font-size:13px;">$source</span></td>
        <td  style="text-align:center" width="220"><span style="color:'$colorBlack';font-size:13px;">$newformat</span></td>
        <td  style="text-align:rigth" width="180"><span style="color:'$colorBlack';font-size:13px;">$value</span></td>
        </tr>
        EOD;
            }
        }

        //WOF
        $wofDivDetails = "";
        $headerFitness = "";
        $word = "";

        if ($subjectToWof == "true") {
            $sub = "Yes";
            $headerFitness = "Warrant of Fitness (WOF)";
            $word = "WOF";
        } else {
            $sub = "No";
            $headerFitness = "Certificate of Fitness Inspection (COF)";
            $word = "COF";
        }



        if ($isWOFExpired === true) {
            //wofExpired
            $date2 = strtotime($obj['lastDateWof']);
            $newformat2 = date('j F Y', $date2);
            $date3 = strtotime($obj['wofDateExpired']);
            $newformat3 = date('j F Y', $date3);


            $wofDivDetails  = <<<EOD
                <div style="background-color:$colorBox;border-radius:50%;width:100%">
            <table cellpadding="1">
            <tr style="background-color:#fea500">
            <th><table><tr><td width="10"><p style='color:$colorBox'></p></td><td width="30"><b style="color:white;font-size:10px;line-height:10px;"></b><img width="25" height="25"  src="https://www.autoape.co.nz/assets/pdf/warning.png" alt="Laapp"></td><td width="600"  height="30" ><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:29px;">$headerFitness – </b><span style="color:white;font-size:14px;line-height:29px;">Expired</span></td></tr></table></th>
            </tr>
            <tr><td></td><td></td><td></td><td></td></tr>
            <tr style="line-height: 2;"><td width="15"></td>
            <td width="280"><b style="color:black;font-size:12px;letter-spacing: 1px;">Subject to $word:</b></td>
            <td width="200"><span style="color:'$colorBlack';font-size:13px;">Yes</span></td>

            </tr>
            <tr style="line-height: 2;"><td width="15"></td>
            <td width="280"><b style="color:black;font-size:12px;letter-spacing: 1px;">Last $word Date & Result: </b></td>
            <td width="200"><span style="color:'$colorBlack';font-size:13px;">$newformat2 ($wofStatus)</span></td>
            </tr>
            <tr style="line-height: 2;"><td width="15"></td>
            <td width="280"><b style="color:black;font-size:12px;letter-spacing: 1px;">Expiry date of last $word:</b></td>
            <td width="200"><span style="color:'$colorBlack';font-size:13px;">$newformat3</span></td>
            </tr>
                </table>
                </div>
            
            EOD;
        } else {
            if ($WOFExpiredDays <= 30) {

                $date2 = strtotime($obj['lastDateWof']);
                $newformat2 = date('j F Y', $date2);
                $date3 = strtotime($obj['wofDateExpired']);
                $newformat3 = date('j F Y', $date3);


                $wofDivDetails  = <<<EOD
                    <div style="background-color:$colorBox;border-radius:50%;width:100%">
                <table cellpadding="1">
                <tr style="background-color:#fea500">
                <th><table><tr><td width="10"><p style='color:$colorBox'></p></td><td width="30"><b style="color:white;font-size:10px;line-height:10px;"></b><img width="25" height="25"  src="https://www.autoape.co.nz/assets/pdf/warning.png" alt="Laapp"></td><td width="600"  height="30" ><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:29px;">$headerFitness – </b><span style="color:white;font-size:14px;line-height:29px;">Passed. WOF expiring in the next $WOFExpiredDays days.</span></td></tr></table></th>
                </tr>
                <tr><td></td><td></td><td></td><td></td></tr>
                <tr style="line-height: 2;"><td width="15"></td>
                <td width="280"><b style="color:black;font-size:12px;letter-spacing: 1px;">Subject to $word:</b></td>
                <td width="200"><span style="color:'$colorBlack';font-size:13px;">$sub</span></td>

                </tr>
                <tr style="line-height: 2;"><td width="15"></td>
                <td width="280"><b style="color:black;font-size:12px;letter-spacing: 1px;">Last $word Date & Result: </b></td>
                <td width="200"><span style="color:'$colorBlack';font-size:13px;">$newformat2 ($wofStatus)</span></td>
                </tr>
                <tr style="line-height: 2;"><td width="15"></td>
                <td width="280"><b style="color:black;font-size:12px;letter-spacing: 1px;">Expiry date of last $word:</b></td>
                <td width="200"><span style="color:'$colorBlack';font-size:13px;">$newformat3</span></td>
                </tr>
                    </table>
                    </div>
                
                EOD;
            } else {
                //wofExpired
                $date2 = strtotime($obj['lastDateWof']);
                $newformat2 = date('j F Y', $date2);
                $date3 = strtotime($obj['wofDateExpired']);
                $newformat3 = date('j F Y', $date3);

                $date4 = strtotime($obj['wofDateExpired']);
                $newformat4 = date('d M Y', $date4);

                $wofDivDetails  = <<<EOD
                                <div style="background-color:$colorBox;border-radius:50%;width:100%">
                            <table cellpadding="1">
                            <tr style="background-color:#637887">
                            <th><table><tr><td width="10"><p style='color:$colorBox'></p></td><td width="30"><b style="color:white;font-size:10px;line-height:10px;"></b><img width="25" height="25"  src="https://www.autoape.co.nz/assets/pdf/true.png" alt="Laapp"></td><td width="600"  height="30" ><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:29px;">$headerFitness – </b><span style="color:white;font-size:14px;line-height:29px;">Passed. Expire $newformat4</span></td></tr></table></th>
                            </tr>
                            <tr><td></td><td></td><td></td><td></td></tr>
                            <tr style="line-height: 2;"><td width="15"></td>
                            <td width="280"><b style="color:black;font-size:12px;letter-spacing: 1px;">Subject to $word:</b></td>
                            <td width="200"><span style="color:'$colorBlack';font-size:13px;">Yes</span></td>
                
                            </tr>
                            <tr style="line-height: 2;"><td width="15"></td>
                            <td width="280"><b style="color:black;font-size:12px;letter-spacing: 1px;">Last $word Date & Result: </b></td>
                            <td width="200"><span style="color:'$colorBlack';font-size:13px;">$newformat2 ($wofStatus)</span></td>
                            </tr>
                            <tr style="line-height: 2;"><td width="15"></td>
                            <td width="280"><b style="color:black;font-size:12px;letter-spacing: 1px;">Expiry date of last $word:</b></td>
                            <td width="200"><span style="color:'$colorBlack';font-size:13px;">$newformat3</span></td>
                            </tr>
                                </table>
                                </div>
                            
                            EOD;
            }
        }

        $regoDivDetails = "";


        if ($registrationStatus === "Cancelled") {
            $regoDivDetails  = <<<EOD
                <div style="background-color:$colorBox;border-radius:50%;width:100%">
            <table cellpadding="1">
            <tr style="background-color:#fea500">
            <th><table><tr><td width="10"><p style='color:$colorBox'></p></td><td width="30"><b style="color:white;font-size:10px;line-height:10px;"></b><img width="25" height="25"  src="https://www.autoape.co.nz/assets/pdf/warning.png" alt="Laapp"></td><td width="600"  height="30" ><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:29px;">Registration </b><span style="color:white;font-size:14px;line-height:29px;">– Vehicle Registration has been Cancelled.</span></td></tr></table></th>
            </tr>
            <tr><td></td><td></td><td></td><td></td></tr>
            <tr style="line-height: 2;"><td width="15"></td>
            <td width="280"><b style="color:black;font-size:12px;letter-spacing: 1px;">Status:</b></td>
            <td width="200"><span style="color:'$colorBlack';font-size:13px;">Cancelled</span></td>
            </tr>
            <tr style="line-height: 2;"><td width="15"></td>
            <td width="280"><b style="color:black;font-size:12px;letter-spacing: 1px;">Licence(rego) expiry date: </b></td>
            <td width="300"><span style="color:'$colorBlack';font-size:13px;">$cancelledDesc</span></td>
            </tr>
                </table>
                </div>
            
            EOD;
        } else {
            if ($isRegoExpired === true) {
                //wofExpired
                $date2 = strtotime($obj['regoIssueDate']);
                $newformat2 = date('j F Y', $date2);
                $date3 = strtotime($obj['regoDateExpired']);
                $newformat3 = date('j F Y', $date3);


                $regoDivDetails  = <<<EOD
                    <div style="background-color:$colorBox;border-radius:50%;width:100%">
                <table cellpadding="1">
                <tr style="background-color:#fea500">
                <th><table><tr><td width="10"><p style='color:$colorBox'></p></td><td width="30"><b style="color:white;font-size:10px;line-height:10px;"></b><img width="25" height="25"  src="https://www.autoape.co.nz/assets/pdf/warning.png" alt="Laapp"></td><td width="600"  height="30" ><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:29px;">Registration – </b><span style="color:white;font-size:14px;line-height:29px;">Vehicle Registration has expired. Renewal is required</span></td></tr></table></th>
                </tr>
                <tr><td></td><td></td><td></td><td></td></tr>
                <tr style="line-height: 2;"><td width="15"></td>
                <td width="280"><b style="color:black;font-size:12px;letter-spacing: 1px;">Status:</b></td>
                <td width="200"><span style="color:'$colorBlack';font-size:13px;">$wofStatus</span></td>
    
                </tr>
                <tr style="line-height: 2;"><td width="15"></td>
                <td width="280"><b style="color:black;font-size:12px;letter-spacing: 1px;">Licence(rego) expiry date: </b></td>
                <td width="200"><span style="color:'$colorBlack';font-size:13px;">$newformat3</span></td>
                </tr>
                <tr style="line-height: 2;"><td width="15"></td>
                <td width="280"><b style="color:black;font-size:12px;letter-spacing: 1px;">Licence Issue date/time:</b></td>
                <td width="200"><span style="color:'$colorBlack';font-size:13px;">$newformat2</span></td>
                </tr>
                    </table>
                    </div>
                
                EOD;
            } else {
                if ($RegoExpiredDays <= 30) {

                    $date2 = strtotime($obj['regoIssueDate']);
                    $newformat2 = date('j F Y', $date2);
                    $date3 = strtotime($obj['regoDateExpired']);
                    $newformat3 = date('j F Y', $date3);


                    $regoDivDetails  = <<<EOD
                        <div style="background-color:$colorBox;border-radius:50%;width:100%">
                    <table cellpadding="1">
                    <tr style="background-color:#fea500">
                    <th><table><tr><td width="10"><p style='color:$colorBox'></p></td><td width="30"><b style="color:white;font-size:10px;line-height:10px;"></b><img width="25" height="25"  src="https://www.autoape.co.nz/assets/pdf/warning.png" alt="Laapp"></td><td width="600"  height="30" ><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:29px;">Registration – </b><span style="color:white;font-size:14px;line-height:29px;">Vehicle Registration is expiring in the next $RegoExpiredDays days.</span></td></tr></table></th>
                    </tr>
                    <tr><td></td><td></td><td></td><td></td></tr>
                    <tr style="line-height: 2;"><td width="15"></td>
                    <td width="280"><b style="color:black;font-size:12px;letter-spacing: 1px;">Status:</b></td>
                    <td width="200"><span style="color:'$colorBlack';font-size:13px;">$registrationStatus</span></td>
        
                    </tr>
                    <tr style="line-height: 2;"><td width="15"></td>
                    <td width="280"><b style="color:black;font-size:12px;letter-spacing: 1px;">Licence(rego) expiry date: </b></td>
                    <td width="200"><span style="color:'$colorBlack';font-size:13px;">$newformat3</span></td>
                    </tr>
                    <tr style="line-height: 2;"><td width="15"></td>
                    <td width="280"><b style="color:black;font-size:12px;letter-spacing: 1px;">Licence Issue date/time:</b></td>
                    <td width="200"><span style="color:'$colorBlack';font-size:13px;">$newformat2</span></td>
                    </tr>
                        </table>
                        </div>
                    
                    EOD;
                } else {
                    //wofExpired
                    $date2 = strtotime($obj['regoIssueDate']);
                    $newformat2 = date('j F Y', $date2);
                    $date3 = strtotime($obj['regoDateExpired']);
                    $newformat3 = date('j F Y', $date3);

                    $date4 = strtotime($obj['regoDateExpired']);
                    $newformat4 = date('d M Y', $date4);

                    $regoDivDetails  = <<<EOD
                                    <div style="background-color:$colorBox;border-radius:50%;width:100%">
                                <table cellpadding="1">
                                <tr style="background-color:#637887">
                                <th><table><tr><td width="10"><p style='color:$colorBox'></p></td><td width="30"><b style="color:white;font-size:10px;line-height:10px;"></b><img width="25" height="25"  src="https://www.autoape.co.nz/assets/pdf/true.png" alt="Laapp"></td><td width="600"  height="30" ><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:29px;">Registration – </b><span style="color:white;font-size:14px;line-height:29px;">Active. Expire $newformat4</span></td></tr></table></th>
                                </tr>
                                <tr><td></td><td></td><td></td><td></td></tr>
                                <tr style="line-height: 2;"><td width="15"></td>
                                <td width="280"><b style="color:black;font-size:12px;letter-spacing: 1px;">Status:</b></td>
                                <td width="200"><span style="color:'$colorBlack';font-size:13px;">$registrationStatus</span></td>
                    
                                </tr>
                                <tr style="line-height: 2;"><td width="15"></td>
                                <td width="280"><b style="color:black;font-size:12px;letter-spacing: 1px;">Licence(rego) expiry date: </b></td>
                                <td width="200"><span style="color:'$colorBlack';font-size:13px;">$newformat3</span></td>
                                </tr>
                                <tr style="line-height: 2;"><td width="15"></td>
                                <td width="280"><b style="color:black;font-size:12px;letter-spacing: 1px;">Licence Issue date/time:</b></td>
                                <td width="200"><span style="color:'$colorBlack';font-size:13px;">$newformat2</span></td>
                                </tr>
                                    </table>
                                    </div>
                                EOD;
                }
            }
        }



        //RUC SECTION
        $rucDIV = "";
        if ($isRUC == "true") {
            $rucDIV  = <<<EOD
            <div style="background-color:$colorBox;border-radius:50%;width:100%">
        <table cellpadding="1">
        <tr style="background-color:#637887">
        <th><table><tr><td width="10"><p style='color:$colorBox'></p></td><td width="30"><b style="color:white;font-size:10px;line-height:10px;"></b><img width="25" height="25"  src="https://www.autoape.co.nz/assets/pdf/true.png" alt="Laapp"></td><td width="600"  height="30" ><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:29px;">Road User Charges (RUC)</b></td></tr></table></th>
        </tr>
        <tr><td></td><td></td><td></td><td></td></tr>
        <tr><td width="15"></td>
        <td width="280"><b style="color:black;font-size:12px;letter-spacing: 1px;">Licence Issue date:</b></td>
        <td width="200"><span style="color:'$colorBlack';font-size:13px;">$issueRucDate</span></td>

        </tr>
        <tr><td width="15"></td>
        <td width="280"><b style="color:black;font-size:12px;letter-spacing: 1px;">Licence start kms:</b></td>
        <td width="200"><span style="color:'$colorBlack';font-size:13px;">$licenceStartKMS</span></td>
        </tr>
        <tr><td width="15"></td>
        <td width="280"><b style="color:black;font-size:12px;letter-spacing: 1px;">Licence finish kms:</b></td>
        <td width="200"><span style="color:'$colorBlack';font-size:13px;">$licenceFinishKMS</span></td>
        </tr>
            </table>
            </div>
        
        EOD;
        } else {
            $rucDIV = "";
        }

        //Ownership History
        $ownercheckDiv = "";

        $dateCurrent = strtotime($currentDateOwner);
        $newformatCurrent = date('j F Y', $dateCurrent);


        $currentOwnerDesc = "aca";

        if ($ownerDays <= 30) {
            $ownercheckDiv  = <<<EOD
            <div style="background-color:$colorBox;border-radius:50%;width:100%">
        <table cellpadding="1">
        <tr style="background-color:#fea500">
        <th><table><tr><td width="10"><p style='color:$colorBox'></p></td><td width="30"><b style="color:white;font-size:10px;line-height:10px;"></b><img width="25" height="25"  src="https://www.autoape.co.nz/assets/pdf/warning.png" alt="Laapp"></td><td width="600"  height="30" ><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:29px;">Ownership History - </b><span style="color:white;font-size:14px;line-height:29px;">$ownerTotal previous registered owners. Current ownership duration $ownerDays days</span></td></tr></table></th>
        </tr>
        <tr><td></td><td></td><td></td><td></td></tr>
        <tr><td width="15"></td>
        <td width="280"><b style="color:black;font-size:12px;letter-spacing: 1px;">Current registered person:</b></td>
        <td width="200"><span style="color:'$colorBlack';font-size:13px;">$nameOwner</span></td>

        </tr>
        <tr><td width="15"></td>
        <td width="280"><b style="color:black;font-size:12px;letter-spacing: 1px;">Vehicle acquisition date:</b></td>
        <td width="200"><span style="color:'$colorBlack';font-size:13px;">$newformatCurrent</span></td>
        </tr>
        <tr><td width="15"></td>
        <td width="280"><b style="color:black;font-size:12px;letter-spacing: 1px;">Registered person status:</b></td>
        <td width="200"><span style="color:'$colorBlack';font-size:13px;">$currentOwnerStatus</span></td>
        </tr>
        <tr><td width="15"></td>
        <td width="280"><b style="color:black;font-size:12px;letter-spacing: 1px;">Ownership duration:</b></td>
        <td width="200"><span style="color:'$colorBlack';font-size:13px;">$ownerDays days</span></td>
        </tr>
            </table>
            </div>
                 
        EOD;
        } else {
            $ownercheckDiv  = <<<EOD
            <div style="background-color:$colorBox;border-radius:50%;width:100%">
        <table cellpadding="1">
        <tr style="background-color:#637887">
        <th><table><tr><td width="10"><p style='color:$colorBox'></p></td><td width="30"><b style="color:white;font-size:10px;line-height:10px;"></b><img width="25" height="25"  src="https://www.autoape.co.nz/assets/pdf/true.png" alt="Laapp"></td><td width="600"  height="30" ><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:29px;">Ownership History - </b><span style="color:white;font-size:14px;line-height:29px;">$ownerTotal previous registered owners. Current ownership duration $ownerDays days</span></td></tr></table></th>
        </tr>
        <tr><td></td><td></td><td></td><td></td></tr>
        <tr><td width="15"></td>
        <td width="280"><b style="color:black;font-size:12px;letter-spacing: 1px;">Current registered person:</b></td>
        <td width="200"><span style="color:'$colorBlack';font-size:13px;">$nameOwner</span></td>

        </tr>
        <tr><td width="15"></td>
        <td width="280"><b style="color:black;font-size:12px;letter-spacing: 1px;">Vehicle acquisition date:</b></td>
        <td width="200"><span style="color:'$colorBlack';font-size:13px;">$newformatCurrent</span></td>
        </tr>
        <tr><td width="15"></td>
        <td width="280"><b style="color:black;font-size:12px;letter-spacing: 1px;">Registered person status:</b></td>
        <td width="200"><span style="color:'$colorBlack';font-size:13px;">$currentOwnerStatus</span></td>
        </tr>
        <tr><td width="15"></td>
        <td width="280"><b style="color:black;font-size:12px;letter-spacing: 1px;">Ownership duration:</b></td>
        <td width="200"><span style="color:'$colorBlack';font-size:13px;">$ownerDays days</span></td>
        </tr>
            </table>
            </div>
                 
        EOD;
        }


        $reregisterDiv = "";

        $dateRe1 = strtotime($firstRegitrationDate);
        $dateRe11 = date('j F Y', $dateRe1);

        $dateRe2 = strtotime($lastRegistrationDate);
        $dateRe22 = date('j F Y', $dateRe2);

        if ($reRegistered == "true") {
            $reregisterDiv  = <<<EOD
            <div style="background-color:$colorBox;border-radius:50%;width:100%">
        <table cellpadding="1">
        <tr style="background-color:#fea500">
        <th><table><tr><td width="10"><p style='color:$colorBox'></p></td><td width="30"><b style="color:white;font-size:10px;line-height:10px;"></b><img width="25" height="25"  src="https://www.autoape.co.nz/assets/pdf/warning.png" alt="Laapp"></td><td width="600"  height="30" ><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:29px;">Re-registration Check - </b><span style="color:white;font-size:14px;line-height:29px;">Vehicle has been Re-registered.</span></td></tr></table></th>
        </tr>
        <tr><td></td><td></td><td></td><td></td></tr>
        <tr><td width="15"></td>
        <td width="280"><b style="color:black;font-size:12px;letter-spacing: 1px;">Date first registered in NZ:</b></td>
        <td width="200"><span style="color:'$colorBlack';font-size:13px;">$dateRe11</span></td>

        </tr>
        <tr><td width="15"></td>
        <td width="280"><b style="color:black;font-size:12px;letter-spacing: 1px;">Date of last registration:</b></td>
        <td width="200"><span style="color:'$colorBlack';font-size:13px;">$dateRe22</span></td>
        </tr>
        <tr><td width="15"></td>
        <td width="280"><b style="color:black;font-size:12px;letter-spacing: 1px;">Cause of latest registration:</b></td>
        <td width="350"><span style="color:'$colorBlack';font-size:13px;">$cancelledDesc</span></td>
        </tr>
            </table>
            </div>
                 
        EOD;
        } else {
            $reregisterDiv  = <<<EOD
            <div style="background-color:$colorBox;border-radius:50%;width:100%">
        <table cellpadding="1">
        <tr style="background-color:#637887">
        <th><table><tr><td width="10"><p style='color:$colorBox'></p></td><td width="30"><b style="color:white;font-size:10px;line-height:10px;"></b><img width="25" height="25"  src="https://www.autoape.co.nz/assets/pdf/true.png" alt="Laapp"></td><td width="600"  height="30" ><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:29px;">Re-registration Check </b></td></tr></table></th>
        </tr>
        <tr><td></td><td></td><td></td><td></td></tr>
        <tr><td width="15"></td>
        <td width="280"><b style="color:black;font-size:12px;letter-spacing: 1px;">Date first registered in NZ:</b></td>
        <td width="200"><span style="color:'$colorBlack';font-size:13px;">$dateRe11</span></td>

        </tr>
        <tr><td width="15"></td>
        <td width="280"><b style="color:black;font-size:12px;letter-spacing: 1px;">Date of last registration:</b></td>
        <td width="200"><span style="color:'$colorBlack';font-size:13px;">$dateRe22</span></td>
        </tr>
        <tr><td width="15"></td>
        <td width="280"><b style="color:black;font-size:12px;letter-spacing: 1px;">Cause of latest registration:</b></td>
        <td width="350"><span style="color:'$colorBlack';font-size:13px;">$cancelledDesc</span></td>
        </tr>
            </table>
            </div>
                 
        EOD;
        }

        $importCheckDiv = "";

        if ($importedDamagedField == "true") {
            $importCheckDiv  = <<<EOD
            <div style="background-color:$colorBox;border-radius:50%;width:100%">
        <table cellpadding="1">
        <tr style="background-color:#fea500">
        <th><table><tr><td width="10"><p style='color:$colorBox'></p></td><td width="30"><b style="color:white;font-size:10px;line-height:10px;"></b><img width="25" height="25"  src="https://www.autoape.co.nz/assets/pdf/warning.png" alt="Laapp"></td><td width="600"  height="30" ><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:29px;">Re-registration Check - </b><span style="color:white;font-size:14px;line-height:29px;">Vehicle has been Re-registered.</span></td></tr></table></th>
        </tr>
        <tr><td></td><td></td><td></td><td></td></tr>
        <tr><td width="15"></td>
        <td width="280"><b style="color:black;font-size:12px;letter-spacing: 1px;">Date first registered in NZ:</b></td>
        <td width="200"><span style="color:'$colorBlack';font-size:13px;">$dateRe11</span></td>

        </tr>
        <tr><td width="15"></td>
        <td width="280"><b style="color:black;font-size:12px;letter-spacing: 1px;">Date of last registration:</b></td>
        <td width="200"><span style="color:'$colorBlack';font-size:13px;">$dateRe22</span></td>
        </tr>
        <tr><td width="15"></td>
        <td width="280"><b style="color:black;font-size:12px;letter-spacing: 1px;">Cause of latest registration:</b></td>
        <td width="350"><span style="color:'$colorBlack';font-size:13px;">$cancelledDesc</span></td>
        </tr>
            </table>
            </div>
                 
        EOD;
        } else {
            $importCheckDiv  = <<<EOD
            <div style="background-color:$colorBox;border-radius:50%;width:100%">
        <table cellpadding="1">
        <tr style="background-color:#637887">
        <th><table><tr><td width="10"><p style='color:$colorBox'></p></td><td width="30"><b style="color:white;font-size:10px;line-height:10px;"></b><img width="25" height="25"  src="https://www.autoape.co.nz/assets/pdf/true.png" alt="Laapp"></td><td width="600"  height="30" ><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:29px;">Re-registration Check </b></td></tr></table></th>
        </tr>
        <tr><td></td><td></td><td></td><td></td></tr>
        <tr><td width="15"></td>
        <td width="280"><b style="color:black;font-size:12px;letter-spacing: 1px;">Date first registered in NZ:</b></td>
        <td width="200"><span style="color:'$colorBlack';font-size:13px;">$dateRe11</span></td>

        </tr>
        <tr><td width="15"></td>
        <td width="280"><b style="color:black;font-size:12px;letter-spacing: 1px;">Date of last registration:</b></td>
        <td width="200"><span style="color:'$colorBlack';font-size:13px;">$dateRe22</span></td>
        </tr>
        <tr><td width="15"></td>
        <td width="280"><b style="color:black;font-size:12px;letter-spacing: 1px;">Cause of latest registration:</b></td>
        <td width="350"><span style="color:'$colorBlack';font-size:13px;">$cancelledDesc</span></td>
        </tr>
            </table>
            </div>
                 
        EOD;
        }

        $importCheckDiv2 = "";
        if ($isRegisteredOverseas === true) {

            if ($damagedUsedImport === "true") {
                $importCheckDiv2  = <<<EOD
                <div style="background-color:$colorBox;border-radius:50%;width:100%">
            <table cellpadding="1">
            <tr style="background-color:#fea500">
            <th><table><tr><td width="10"><p style='color:$colorBox'></p></td><td width="30"><b style="color:white;font-size:10px;line-height:10px;"></b><img width="25" height="25"  src="https://www.autoape.co.nz/assets/pdf/warning.png" alt="Laapp"></td><td width="600"  height="30" ><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:29px;">Re-registration Check - </b><span style="color:white;font-size:14px;line-height:29px;">Vehicle has been Re-registered.</span></td></tr></table></th>
            </tr>
            <tr><td></td><td></td><td></td><td></td></tr>
            <tr><td width="15"></td>
            <td width="280"><b style="color:black;font-size:12px;letter-spacing: 1px;">Previous Country:</b></td>
            <td width="200"><span style="color:'$colorBlack';font-size:13px;">$previousCountry</span></td>
    
            </tr>
            <tr><td width="15"></td>
            <td width="280"><b style="color:black;font-size:12px;letter-spacing: 1px;">Date first registered overseas:</b></td>
            <td width="200"><span style="color:'$colorBlack';font-size:13px;">$dateOverseas</span></td>
            </tr>
            <tr><td width="15"></td>
            <td width="280"><b style="color:black;font-size:12px;letter-spacing: 1px;">Damaged used Import:</b></td>
            <td width="350"><span style="color:'$colorBlack';font-size:13px;">Yes</span></td>
            </tr>
                </table>
                </div>           
            EOD;
            } else {
                $importCheckDiv2  = <<<EOD
                <div style="background-color:$colorBox;border-radius:50%;width:100%">
            <table cellpadding="1">
            <tr style="background-color:#637887">
            <th><table><tr><td width="10"><p style='color:$colorBox'></p></td><td width="30"><b style="color:white;font-size:10px;line-height:10px;"></b><img width="25" height="25"  src="https://www.autoape.co.nz/assets/pdf/true.png" alt="Laapp"></td><td width="600"  height="30" ><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:29px;">Import Check </b></td></tr></table></th>
            </tr>
            <tr><td></td><td></td><td></td><td></td></tr>
            <tr><td width="15"></td>
            <td width="280"><b style="color:black;font-size:12px;letter-spacing: 1px;">Previous Country:</b></td>
            <td width="200"><span style="color:'$colorBlack';font-size:13px;">$previousCountry</span></td>
    
            </tr>
            <tr><td width="15"></td>
            <td width="280"><b style="color:black;font-size:12px;letter-spacing: 1px;">Date first registered overseas:</b></td>
            <td width="200"><span style="color:'$colorBlack';font-size:13px;">$dateOverseas</span></td>
            </tr>
            <tr><td width="15"></td>
            <td width="280"><b style="color:black;font-size:12px;letter-spacing: 1px;">Damaged used Import:</b></td>
            <td width="350"><span style="color:'$colorBlack';font-size:13px;">No</span></td>
            </tr>
                </table>
                </div>
                     
            EOD;
            }
        } else {
            $importCheckDiv2  = <<<EOD
            <div style="background-color:$colorBox;border-radius:50%;width:100%">
        <table cellpadding="1">
        <tr style="background-color:#637887">
        <th><table><tr><td width="10"><p style='color:$colorBox'></p></td><td width="30"><b style="color:white;font-size:10px;line-height:10px;"></b><img width="25" height="25"  src="https://www.autoape.co.nz/assets/pdf/true.png" alt="Laapp"></td><td width="600"  height="30" ><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:29px;">Import Check </b></td></tr></table></th>
        </tr>
        <tr><td></td><td></td><td></td><td></td></tr>
        <tr><td width="15"></td>
        <td width="280"><b style="color:black;font-size:12px;letter-spacing: 1px;">Previous Country:</b></td>
        <td width="200"><span style="color:'$colorBlack';font-size:13px;">Not Applicable</span></td>

        </tr>
        <tr><td width="15"></td>
        <td width="280"><b style="color:black;font-size:12px;letter-spacing: 1px;">Date first registered overseas:</b></td>
        <td width="200"><span style="color:'$colorBlack';font-size:13px;">Not Applicable</span></td>
        </tr>
        <tr><td width="15"></td>
        <td width="280"><b style="color:black;font-size:12px;letter-spacing: 1px;">Damaged used Import:</b></td>
        <td width="350"><span style="color:'$colorBlack';font-size:13px;">Not Applicable</span></td>
        </tr>
            </table>
            </div>
                 
        EOD;
        }

        //PPST
        $ppsrDivHeader = "";
        $ppsrCenter = "";
        $ppsrFooter = "";
        $ppsrDIV2 = "";
        if ($totalItemPPSR > 0) {

            $ppsrDivHeader = <<<EOD
            <div style="background-color:$colorBox;border-radius:50%;width:100%">
        <table cellpadding="1">
        <tr style="background-color:#fea500">
        <th><table><tr><td width="10"><p style='color:$colorBox'></p></td><td width="30"><b style="color:white;font-size:10px;line-height:10px;"></b><img width="25" height="25"  src="https://www.autoape.co.nz/assets/pdf/warning.png" alt="Laapp"></td><td width="600"  height="30" ><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:29px;">Finance Check (PPSR) - </b><span style="color:white;font-size:14px;line-height:29px;">$totalItemPPSR Financing statement found.</span></td></tr></table></th>
        </tr>
        <tr><td></td><td></td><td></td><td></td></tr>         
        EOD;

            $ppsrFooter = <<<EOD
        </table>
        </div>
        EOD;


            foreach ($obj['PPSR']['items'] as $ppsrItem) {
                $financeStatement = $ppsrItem->financingStatementKey->registrationNumber;
                $registrationDate = $ppsrItem->registrationDate;
                $registrationPlate = $ppsrItem->motorVehicles[0]->registrationPlate;
                $registrationVIN = $ppsrItem->motorVehicles[0]->vin;
                $securedParty = $ppsrItem->securedParties[0]->securedPartyName;

                $date5 = strtotime($registrationDate); //
                $newformat5 = date('j F Y', $date5);

                $ppsrCenter = $ppsrCenter . <<<EOD
        <tr><td width="15"></td>
        <td width="280"><b style="color:black;font-size:12px;letter-spacing: 1px;">Finance Statement #:</b></td>
        <td width="200"><span style="color:'$colorBlack';font-size:13px;">$financeStatement</span></td>
        </tr>
        <tr><td width="15"></td>
        <td width="280"><b style="color:black;font-size:12px;letter-spacing: 1px;">Registration date #:</b></td>
        <td width="200"><span style="color:'$colorBlack';font-size:13px;">$newformat5</span></td>
        </tr>
        <tr><td width="15"></td>
        <td width="280"><b style="color:black;font-size:12px;letter-spacing: 1px;">Registration Plate:</b></td>
        <td width="350"><span style="color:'$colorBlack';font-size:13px;">$registrationPlate</span></td>
        </tr>   
        <tr><td width="15"></td>
        <td width="280"><b style="color:black;font-size:12px;letter-spacing: 1px;">Registration Vin:</b></td>
        <td width="350"><span style="color:'$colorBlack';font-size:13px;">$registrationVIN</span></td>
        </tr>      
        <tr><td width="15"></td>
        <td width="280"><b style="color:black;font-size:12px;letter-spacing: 1px;">Secured Party:</b></td>
        <td width="350"><span style="color:'$colorBlack';font-size:13px;">$securedParty</span></td>
        </tr><br>           
        EOD;
            }

            $ppsrDIV2 = $ppsrDivHeader . $ppsrCenter . $ppsrFooter;
        } else {
            $ppsrDIV2 = <<<EOD
            <div style="background-color:$colorBox;border-radius:50%;width:100%">
        <table cellpadding="1">
        <tr style="background-color:#637887">
        <th><table><tr><td width="10"><p style='color:$colorBox'></p></td><td width="30"><b style="color:white;font-size:10px;line-height:10px;"></b><img width="25" height="25"  src="https://www.autoape.co.nz/assets/pdf/true.png" alt="Laapp"></td><td width="600"  height="30" ><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:13px;"></b><b style="color:white;font-size:14px;line-height:29px;">Finance Check (PPSR) -</b><span style="color:white;font-size:14px;line-height:29px;"> No financing statement found.</span></td></tr></table></th>
        </tr>
        <tr><td></td><td></td><td></td><td></td></tr>
        <tr><td width="15"></td>
        <td width="400"><b style="color:black;font-size:12px;letter-spacing: 1px;">No Security Interest or financing Statements found.</b></td>
        <td width="50"><span style="color:'$colorBlack';font-size:13px;"></span></td>
        </tr>
            </table>
            </div>
        EOD;
        }

        //extra

        $divExtra1 = <<<EOD
        <i style="color:grat;font-size:9">Important: This report has been prepared based on the Plate number or Vehicle Identification Number (VIN) supplied by the user. It is
        provided by Auto Ape Limited with information from the New Zealand Transport Agency, Government sources and other third parties. While
        AutoApe has used its best efforts to provide correct information, it does not guarantee or make any representations regarding the accuracy
        or suitability of this report for your needs. AutoApe does not act on behalf of NZTA. To the full extent permitted by law, AutoApe will not be
        liable for any loss or damage relating to your use of, or reliance on, this report.</i>
        EOD;

        $divExtra = <<<EOD
        <b style="color:$colorPrimary;font-size:25px;letter-spacing: 2px;">Buyer’s Resources </b>
        <br>
        <div style="background-color:$colorBox;border-radius:50%;width:100%">
        <table cellpadding="1">
        <tr height="10"><td width="80"></td><td width="550"></td></tr>
        <tr>
          <td width="30"></td>
          <td width="60"><img width="35" height="35"  src="https://www.autoape.co.nz/assets/pdf/info5.png" alt="Laapp"></td>
          <td width="550">
        <i style="color:$colorBlack;font-size:12px;line-height: 1.3;">Purchasing a vehicle is a big decision and can be costly if things go wrong. Buyer’s Resources helps you make sense of the information and interpret the results contained in this report.</i>
          </td>
        </tr>
        </table>
        </div>
        <br>
        <b style="color:$colorPrimary;font-size:18px;letter-spacing: 2px;">Stolen Vehicle – Police Check</b>
        <p style="color:grat;font-size:9">If the vehicle has a Stolen Vehicle alert, this indicates that the vehicle has been reported Stolen to the NZ Police. You
        should cease all negotiations with the seller and contact the Police for more information.</p>
        <b style="color:$colorPrimary;font-size:18px;letter-spacing: 2px;">Odometer Record Alert</b>
        <p style="color:grat;font-size:9">A vehicle’s odometer reading should increase over time. Odometer readings are recorded from WOF inspections. An
        inconsistent reading could indicate a faulty odometer or an entry error or it may have been tampered with. According
        to section 99 of The Motor Vehicle Sales Act 2003, it is an offence, without reasonable excuse, to tamper with a vehicle’s
        odometer. Contact NZTA if you believe an odometer reading has been incorrectly recorded.</p>
        <b style="color:$colorPrimary;font-size:18px;letter-spacing: 2px;">Finance Check/PPSR</b>
        <p style="color:grat;font-size:9">A security Interest or Financing Statement may indicate there is money owing on this vehicle. There are over 500,000
        registered debts on vehicles each year. If you are purchasing from a private seller, ask the seller to clear any money
        owing before finalising the purchase. Any outstanding debt could result in the repossession of the vehicle. For peace
        of mind, it is also a good idea to request proof of repayment and do a second Finance Check. AutoApe checks on all
        plates previously attached to a vehicle.</p>
        <b style="color:$colorPrimary;font-size:18px;letter-spacing: 2px;">Warrant of Fitness (WOF)</b>
        <p style="color:grat;font-size:9">Most vehicles on NZ road requires a periodic inspection to ensure that it meets required safety standards. It is illegal to
        use a vehicle that does not meet WOF requirements or not displaying a valid WOF label. An alert here indicates that the
        WOF has expired or expiring soon. Discuss with the seller about getting a WOF inspection and update the label
        accordingly.</p>
        <b style="color:$colorPrimary;font-size:18px;letter-spacing: 2px;">Registration/Rego/Vehicle License</b>
        <p style="color:grat;font-size:9">Most vehicles need to have a vehicle license to drive on public roads. Vehicle owners must periodically pay to renew
        this license. If the vehicle license is cancelled, make sure you understand why. If the license is expiring soon, ask the
        seller to renew it or reduce the fee from the purchase price.</p>
        <b style="color:$colorPrimary;font-size:18px;letter-spacing: 2px;">Ownership History</b>
        <p style="color:grat;font-size:9">This check provides information about the vehicle’s current registered owner and number of previous owners in NZ. If
        the current owner has owned the vehicle for a short duration, find out why they are selling it.</p>
        <b style="color:$colorPrimary;font-size:18px;letter-spacing: 2px;">Re-registration Check</b>
        <p style="color:grat;font-size:9">If vehicle registration is cancelled and then re-registered later, find out from the seller why this occurred. The vehicle
        may have been damaged and subsequently repaired. In any case, it is always prudent to ask the seller about this.</p>
        <b style="color:$colorPrimary;font-size:18px;letter-spacing: 2px;">Import Check</b>
        <p style="color:grat;font-size:9">For imported used vehicles, the border inspection will determine if the vehicle is structurally damaged. Be extra vigilant
        when purchasing a used import with a Damage Alert.</p>
        EOD;



        // Print text using writeHTMLCell()
        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

        $pdf->AddPage();

        $pdf->writeHTMLCell(0, 0, '', '', $html2, 0, 1, 0, true, '', true);

        $pdf->writeHTMLCell(0, 0, '', '', $plateDivFinal, 0, 1, 0, true, '', true);

        $pdf->writeHTMLCell(0, 0, '', '', $wofDivDetails, 0, 1, 0, true, '', true);

        $pdf->writeHTMLCell(0, 0, '', '', $regoDivDetails, 0, 1, 0, true, '', true);

        $pdf->writeHTMLCell(0, 0, '', '', $rucDIV, 0, 1, 0, true, '', true);

        $pdf->writeHTMLCell(0, 0, '', '', $ownercheckDiv, 0, 1, 0, true, '', true);

        $pdf->writeHTMLCell(0, 0, '', '', $reregisterDiv, 0, 1, 0, true, '', true);

        // $pdf->writeHTMLCell(0, 0, '', '', $importCheckDiv, 0, 1, 0, true, '', true);

        $pdf->writeHTMLCell(0, 0, '', '', $importCheckDiv2, 0, 1, 0, true, '', true);

        $pdf->writeHTMLCell(0, 0, '', '', $ppsrDIV2, 0, 1, 0, true, '', true);

        $odoDivFinal = $odoDivHeader . '' . $odoCenter . '' . $odoFooter;
        $pdf->writeHTMLCell(0, 0, '', '', $odoDivFinal, 0, 1, 0, true, '', true);

        $pdf->writeHTMLCell(0, 0, '', '', $divExtra1, 0, 1, 0, true, '', true);

        $pdf->AddPage();

        $pdf->writeHTMLCell(0, 0, '', '', $divExtra, 0, 1, 0, true, '', true);

        // ---------------------------------------------------------

        // Close and output PDF document
        // This method has several options, check the source code documentation for more information.
        //$pdf->Output(dirname(__FILE__) . '/documents/agreements/example_001.pdf', 'F');

        $now1 = time();
        $newformatNow1 = date('Ymdhs', $now1);

        $pdf->Output('/var/www/html/documents/vehiclereport/' . $rego . $newformatNow1 . '.pdf', 'F');
        //$pdf->Output($rego . $idMake . $idModel .  '.pdf', 'I');

        $stringUrl = "documents/vehiclereport/" . $rego .   $newformatNow1 . ".pdf";
        return $stringUrl;
        //============================================================+
        // END OF FILE
        //============================================================+

        //return $data;



    }

    public function testPdf($vehicule_db)
    {
        $dateOfSale = $vehicule_db['dateOfSale'];
        $idMake =  $vehicule_db['idMake'];
        $idModel = $vehicule_db['idModel'];
        $rego = $vehicule_db['rego'];
        $vin = $vehicule_db['vin'];
        $dateLicense =  $vehicule_db['dateLicense'];
        $dateWof =  $vehicule_db['dateWof'];
        $odo =  $vehicule_db['odo'];
        $nameSeller = $vehicule_db['nameSeller'];
        $addressSeller = $vehicule_db['addressSeller'];
        $phoneSeller =  $vehicule_db['phoneSeller'];
        $nameBuyer = $vehicule_db['nameBuyer'];
        $addressBuyer = $vehicule_db['addressBuyer'];
        $phoneBuyer = $vehicule_db['phoneBuyer'];
        $price = $vehicule_db['price'];
        $method = $vehicule_db['method'];
        $delivery = $vehicule_db['delivery'];
        $datePay = $vehicule_db['datePay'];
        $dateDelivery = $vehicule_db['dateDelivery'];


        $stringMethod1 = ($method  ? 'checked="checked"' : "");
        $stringDelivery1 = ($delivery ? 'checked="checked"' : "");

        $stringMethod2 = (!$method  ? 'checked="checked"' : "");
        $stringDelivery2 = (!$delivery  ? 'checked="checked"' : "");

        $year =  $vehicule_db['year'];

        $desc =  $vehicule_db['desc'];

        require_once('application/libraries/TCPDF/tcpdf.php');
        // create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'A4', true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('AutoApe');
        $pdf->SetTitle('Vehicle Sales Agreement');
        $pdf->SetSubject($rego);

        $pdf->SetPrintHeader(false);
        // set default header data
        //$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE . ' 001', PDF_HEADER_STRING, array(0, 64, 255), array(0, 64, 128));
        $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

        // set header and footer fonts
        $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
            require_once(dirname(__FILE__) . '/lang/eng.php');
            $pdf->setLanguageArray($l);
        }

        // ---------------------------------------------------------

        // set default font subsetting mode
        $pdf->setFontSubsetting(true);

        // Set font
        // dejavusans is a UTF-8 Unicode font, if you only need to
        // print standard ASCII chars, you can use core fonts like
        // helvetica or times to reduce file size.
        $pdf->SetFont('helvetica', '', 14, '', true);

        // Add a page
        // This method has several options, check the source code documentation for more information.
        $pdf->AddPage();

        // set text shadow effect
        //$pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.2, 'depth_h' => 0.2, 'color' => array(196, 196, 196), 'opacity' => 1, 'blend_mode' => 'Normal'));

        // Set some content to print
        $html = <<<EOD
<h3 style="text-align:center">Vehicle sales agreement</h3>
<p style="font-size:12px"><strong>DATE OF SALE:</strong>  $dateOfSale <p>
<p><strong>SELLERS DETAILS</strong></p>
<br>
<table>
<tr>
<td style="height:30px;">Name</td>
<td style="height:30px;">: $nameSeller</td>
<td></td>
</tr>
<tr>
<td style="height:30px;">Address</td>
<td style="height:30px;">: $addressSeller</td>
<td></td>
</tr>
<tr>
<td style="height:30px;">Phone</td>
<td style="height:30px;">: $phoneSeller</td>

</tr>
</table>
<p><strong>BUYER DETAILS</strong></p>
<table>
<tr>
<td style="height:30px;">Name</td>
<td style="height:30px;">: $nameBuyer</td>
<td></td>
</tr>
<tr>
<td style="height:30px;">Address</td>
<td style="height:30px;">: $addressBuyer</td>
</tr>
<tr>
<td style="height:30px;">Phone</td>
<td style="height:30px;">: $phoneBuyer</td>
</tr>
</table>
<p><strong>VEHICLE DETAILS</strong></p>
<table>
<tr>
<td style="height:30px;">Make/model/year</td>
<td style="height:30px;">: $year $idMake $idModel</td>
<td></td>
</tr>
<tr>
<td style="height:30px;">Plate number</td>
<td style="height:30px;">: $rego</td>
</tr>
<tr>
<td style="height:30px;">Vehicle identification number (VIN)</td>
<td style="height:30px;">: $vin</td>
</tr>
<tr>
<td style="height:30px;">License expiry date</td>
<td style="height:30px;">: $dateLicense</td>
</tr>
<tr>
<td style="height:30px;">Warrant of Fitness expiry date</td>
<td style="height:30px;">: $dateWof</td>
</tr>
<tr>
<td style="height:30px;">Odometer reading</td>
<td style="height:30px;">: $odo KM</td>
</tr>
<tr>
<td style="height:60px;">Additional conditions</td>
<td style="height:60px;">: $desc</td>

</tr>
</table>
<p><strong>PAYMENT AND DELIVERY
</strong></p>
<table>
<tr>
<td style="height:30px;">Sale Price</td>
<td style="height:30px;">: $price NZD</td>
<td style="height:30px;"></td>
</tr>
<tr>
<td style="height:30px;">Method of payment</td>
</tr>
<tr>
<td style="width:500px;height:30px;"> &#9; &#9; &#9; &#9;<input style="left:10"  $stringMethod1  type="checkbox" name="box" value="1" readonly="true" />&#9; Buyer will pay on date of sale</td>
</tr>
<tr>
<td style="height:30px;"> &#9; &#9; &#9; &#9;<input style="left:10" $stringMethod2  type="checkbox" name="box2" value="1" readonly="true" />&#9; Buyer will pay by: <u>&#9; $datePay  </u> </td>
</tr>
<tr>
<td style="height:30px;">Delivery details:</td>
</tr>
<tr>
<td style="width:500px;height:30px;"> &#9; &#9; &#9; &#9;<input style="left:10" $stringDelivery1 type="checkbox" name="box3" value="1" readonly="true" />&#9; Buyer will pick-up on:</td>
</tr>
<tr>
<td style="height:30px;"> &#9; &#9; &#9; &#9;<input style="left:10" type="checkbox" $stringDelivery2 name="box4" value="1" readonly="true" />&#9; Seller will deliver on: <u>&#9; $dateDelivery  </u></td>
</tr>
</table>
<p><strong>STATEMENTS AND SIGNATURES</strong></p>
<p><strong>As the seller, I certify I:</strong></p>
<ul style="
line-height:2"><li>am legally authorised to sell this vehicle</li>
<li>have no money left owing on the vehicle, or have notified the buyer of any money still owing</li>
<li>have not tampered with the odometer reading</li>
<li>will notify NZ Transport Agency of change of ownership within five (5) days after the sale</li>
<li>everything in this agreement is true to the best of my knowledge.</li>
</ul>
<p></p>
<p>Signature of seller: &#9;&#9; ____________________________________________</p>
<p>Date of signature:&#9;&#9;&#9;&#9;&#9;____________________________________________</p>

<p><strong>As the buyer, I certify I:</strong></p>
<ul style="
line-height:2"><li>accept the condition of the car as represented by the seller</li>
<li>agree to pay the agreed sale price</li>
<li>will notify NZ Transport Agency of change of ownership within five (5) days after the sale</li>
<li>everything in this agreement is true to the best of my knowledge.</li>
</ul>
<p></p>
<p>Signature of buyer: &#9;&#9; ____________________________________________</p>
<p>Date of signature:&#9;&#9;&#9;&#9;&#9;____________________________________________</p>
EOD;

        // Print text using writeHTMLCell()
        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

        // ---------------------------------------------------------

        // Close and output PDF document
        // This method has several options, check the source code documentation for more information.
        //$pdf->Output(dirname(__FILE__) . '/documents/agreements/example_001.pdf', 'F');
        $pdf->Output('/var/www/html/documents/agreements/' . $rego . $idMake . $idModel .  '.pdf', 'F');
        //$pdf->Output($rego . $idMake . $idModel .  '.pdf', 'I');

        $stringUrl = "documents/agreements/" . $rego . $idMake . $idModel .  ".pdf";
        return $stringUrl;
        //============================================================+
        // END OF FILE
        //============================================================+

        //return $data;
    }



    public function testingGetValues($rego)
    {
        $vehicule_db['vehicule_rego'] = $rego;

        $url = "http://3.106.101.41/api/values?";
        $result = file_get_contents($url . "rego=" . $rego . "&fn=details");
        $obj = [];
        json_decode($result, true);
        $result =  str_replace('false', '"false"', $result);
        $result =  str_replace('true', '"true"', $result);
        $json_data = json_decode($result, true);

        $obj['year'] = $json_data['bodyStyleField'];
        $obj['color'] = (isset($json_data['basicColourField']) ? $json_data['basicColourField'] : "Not Specified");
        $obj['ccrating'] = (isset($json_data['cCRatingField']) ? $json_data['cCRatingField'] : "Not Specified");
        $obj['seat'] = (isset($json_data['numberOfSeatsField']) ? $json_data['numberOfSeatsField'] : "Not Specified");
        $obj['fuelTypeField'] = (isset($json_data['fuelTypeField']) ? $json_data['fuelTypeField'] : "Not Specified");
        $obj['bodyStyleField']  = (isset($json_data['bodyStyleField']['descriptionField']) ? $json_data['bodyStyleField']['descriptionField'] : "Not Specified");


        $this->json_encode_msgs($obj);
        return ($obj);
    }

    public function listCar()
    {
        $veh = json_decode(file_get_contents('php://input'), true);

        $vehicule_db['vehicule_rego'] = strtoupper($veh['regoF']); //ok
        $vehicule_db['fk_region'] = $veh['fk_region']; //ok
        $price1 = str_replace(',', '',  $veh['priceF']); //ok
        $price2 = str_replace('$', '',  $price1); //ok
        $vehicule_db['vehicule_price'] = $price2; //ok
        $vehicule_db['fk_vehicule_make'] = $veh['makeF']; //ok
        $vehicule_db['fk_vehicule_model'] = $veh['modelF']; //ok
        $vehicule_db['fk_vehicule_body_id'] = $veh['bodyF']; //????????????
        $vehicule_db['vehicule_year'] = $veh['yearF']; //
        $vehicule_db['vehicule_odometer'] = str_replace(',', '', $veh['odoF']);

        $vehicule_db['vehicule_transmission'] = $veh['tranF'];
        $vehicule_db['vehicule_desc'] = $veh['descF'];



        $vehicule_db['phone_contact'] = $veh['phoneF'];
        $vehicule_db['email_contact'] = $veh['emailF'];
        $vehicule_db['fk_customer'] = $veh['customerId'];
        $vehicule_db['fk_vehicule_type'] = $veh['flagType'];
        $vehicule_db['fk_listing_type'] = $veh['typeList'];
        $vehicule_db['listing_customer_lat'] = $veh['lati'];
        $vehicule_db['listing_customer_long'] = $veh['longi'];
        $vehicule_db['fk_vehicule_fuel'] = $veh['fuelF']; //checkfuelF



        //FROM API
        $url = "http://3.106.101.41/api/values?";
        $result = file_get_contents($url . "rego=" . $vehicule_db['vehicule_rego'] . "&fn=details");
        $obj = [];
        json_decode($result, true);
        $result =  str_replace('false', '"false"', $result);
        $result =  str_replace('true', '"true"', $result);
        $json_data = json_decode($result, true);



        //$obj['fuelTypeField'] = (isset($json_data['fuelTypeField'])?$json_data['fuelTypeField']:"Not Specified");

        $vehicule_db['vehicule_engine'] = (isset($json_data['cCRatingField']) ? $json_data['cCRatingField'] : "Not Specified");
        $vehicule_db['vehicule_color'] = (isset($json_data['basicColourField']) ? $json_data['basicColourField'] : "Not Specified");
        $vehicule_db['vehicule_seat'] = (isset($json_data['numberOfSeatsField']) ? $json_data['numberOfSeatsField'] : "Not Specified");


        //$vehicule_db['vehicule_4x4'] = "";
        //get Values From API

        //photos

        $id = $this->Maxauto_Model->insertCar($vehicule_db);

        $data = $this->json_encode_msgs($id);

        //guardar auto

        return $data;
    }

    public function listMyCar()
    {
        $veh = json_decode(file_get_contents('php://input'), true);

        $vehicule_db['vehicle_registration'] = strtoupper($veh['regoF']);
        $vehicule_db['vehicle_title'] = $veh['title'];
        $vehicule_db['vehicle_wof'] = $veh['dateWof'];
        $vehicule_db['vehicle_rego'] = $veh['dateRego'];
        $vehicule_db['fk_customer'] = $veh['customerId'];

        //photos

        $id = $this->Maxauto_Model->insertMyCar($vehicule_db);

        $data = $this->json_encode_msgs($id);

        //guardar auto

        return $data;
    }

    public function addNotiVehicle($id)
    {

        $vehicule_db['is_notification'] = 0;
        $this->Maxauto_Model->updateMyVehicle($vehicule_db, $id);

        $data = $this->json_encode_msgs("OK");
        return $data;
    }

    public function deleteDocument($id)
    {
        $result = $this->Maxauto_Model->deleteDocument($id);
        $data = $this->json_encode_msgs("OK");
        return $data;
    }


    public function removeNotiVehicle($id)
    {

        $vehicule_db['is_notification'] = 1;
        $this->Maxauto_Model->updateMyVehicle($vehicule_db, $id);


        $data = $this->json_encode_msgs("OK");
        return $data;
    }

    public function editMyCar($id)
    {
        $veh = json_decode(file_get_contents('php://input'), true);

        $newWof = $veh['isWof'];
        $newRegp = $veh['isRego'];

        if ($newWof) {
            $vehicule_db['vehicle_wof'] = $veh['dateWof'];
        }

        if ($newRegp) {
            $vehicule_db['vehicle_rego'] = $veh['dateRego'];
        }


        //photos

        $id = $this->Maxauto_Model->updateMyVehicle($vehicule_db, $id);

        $data = $this->json_encode_msgs($id);

        //guardar auto

        return $data;
    }



    public function updateListingStatus($vehicle_id, $flag)
    {
        $vehicule_db['delete_flag'] = $flag;
        $id = $this->Maxauto_Model->updateVehicle($vehicule_db, $vehicle_id);
        $data = $this->json_encode_msgs($id);
        return $data;
    }

    public function updateWantedListStatus($vehicle_id, $flag)
    {
        $vehicule_db['delete_flag'] = $flag;
        $id = $this->Maxauto_Model->updateVehicle($vehicule_db, $vehicle_id);
        $data = $this->json_encode_msgs($id);
        return $data;
    }

    public function editListing()
    {
        $veh = json_decode(file_get_contents('php://input'), true);

        $vehicule_db['vehicule_price'] = $veh['priceF'];
        $vehicule_db['vehicule_year'] = $veh['yearF'];
        $vehicule_db['vehicule_odometer'] = $veh['odoF'];
        $vehicule_db['vehicule_engine'] = $veh['engiF'];
        $vehicule_db['vehicule_desc'] = $veh['descF'];

        $vehicule_id = $veh['idVeh'];


        //photos

        $id = $this->Maxauto_Model->updateVehicle($vehicule_db, $vehicule_id);

        $data = $this->json_encode_msgs($id);

        //guardar auto

        return $data;
    }


    public function getEvCharger()
    {


        $process = curl_init('https://evroam.azure-api.net/consumer/api/ChargingStation?resultPage=1');
        curl_setopt($process, CURLOPT_HTTPHEADER, array(
            'Ocp-Apim-Subscription-Key:{91f3fe76819f4e80a1adc767f9379c12}', 'Content-Length: 0'
        ));
        curl_setopt($process, CURLOPT_TIMEOUT, 30);
        curl_setopt($process, CURLOPT_POST, 1);
        curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
        $return = curl_exec($process);
        curl_close($process);

        //finally print your API response
        print_r($return);
    }

    public function uploadCarPhotoWantedList($id)
    {

        $tmp_file = $_FILES['photo']['tmp_name'];
        $image_name = "newcar" . '-' . $_FILES['photo']['name'];
        $urlS3 = $this->listAutoS3($tmp_file, $image_name, $_FILES);



        //save link on database
        $vehicule_db['pic_url'] = $urlS3;
        $vehicule_db['fk_wanted_id'] = $id;

        try {
            //code...
            $data1 = $this->Maxauto_Model->insertPhotoWantedList($vehicule_db);
        } catch (Exception $th) {
            //throw $th;
            $urlS3 = $th;
        }

        $data = $this->json_encode_msgs($urlS3);
        //guardar auto

        return $data;
    }



    public function uploadCarPhoto($id)
    {

        $tmp_file = $_FILES['photo']['tmp_name'];
        $image_name = "newcar" . '-' . $_FILES['photo']['name'];
        $urlS3 = $this->listAutoS3($tmp_file, $image_name, $_FILES);



        //save link on database
        $vehicule_db['pic_url'] = $urlS3;
        $vehicule_db['fk_vehicule_id'] = $id;

        try {
            //code...
            $data1 = $this->Maxauto_Model->insertPhoto($vehicule_db);
        } catch (Exception $th) {
            //throw $th;
            $urlS3 = $th;
        }

        $data = $this->json_encode_msgs($urlS3);
        //guardar auto

        return $data;
    }

    public function uploadCarPhotoMy($id)
    {

        $tmp_file = $_FILES['photo']['tmp_name'];
        $image_name = "newcar" . '-' . $_FILES['photo']['name'];
        $urlS3 = $this->listAutoS3($tmp_file, $image_name, $_FILES);


        //save link on database
        $vehicule_db['pic_url'] = $urlS3;
        $vehicule_db['fk_my_id'] = $id;

        try {
            //code...
            $data1 = $this->Maxauto_Model->insertPhotoMy($vehicule_db);
        } catch (Exception $th) {
            //throw $th;
            $urlS3 = $th;
        }

        $data = $this->json_encode_msgs($urlS3);
        //guardar auto

        return $data;
    }

    public function editMyCarImage($id)
    {

        $tmp_file = $_FILES['photo']['tmp_name'];
        $image_name = "newcar" . '-' . $_FILES['photo']['name'];
        $urlS3 = $this->listAutoS3($tmp_file, $image_name, $_FILES);


        //save link on database
        $vehicule_db['pic_url'] = $urlS3;

        try {
            //code...
            $data1 = $this->Maxauto_Model->updateMyVehicleImg($vehicule_db, $id);
        } catch (Exception $th) {
            //throw $th;
            $urlS3 = $th;
        }

        $data = $this->json_encode_msgs($urlS3);
        //guardar auto

        return $data;
    }



    public function listTradeInd()
    {
        $veh = json_decode(file_get_contents('php://input'), true);

        $vehicule_db['make_id'] = strtoupper($veh['make_id']);
        $vehicule_db['model_id'] = $veh['model_id'];
        $vehicule_db['submodel'] = $veh['submodel'];
        $vehicule_db['odometer'] = $veh['odometer'];
        $vehicule_db['description'] = $veh['description'];
        $vehicule_db['fk_customer'] = $veh['fk_customer'];
        $vehicule_db['fk_dealership'] = $veh['fk_dealership'];

        //photos

        $id = $this->Maxauto_Model->insertTradein($vehicule_db);

        $data = $this->json_encode_msgs($id);

        //guardar auto

        return $data;
    }

    public function uploadCarPhotoTradeIn($id)
    {

        $tmp_file = $_FILES['photo']['tmp_name'];
        $image_name = "newcar" . '-' . $_FILES['photo']['name'];
        $urlS3 = $this->listAutoS3($tmp_file, $image_name, $_FILES);



        //save link on database
        $vehicule_db['pic_url'] = $urlS3;
        $vehicule_db['fk_vehicule_id'] = $id;

        try {
            //code...
            $data1 = $this->Maxauto_Model->insertPhotoTradeIn($vehicule_db);
        } catch (Exception $th) {
            //throw $th;
            $urlS3 = $th;
        }

        $data = $this->json_encode_msgs($urlS3);
        //guardar auto

        return $data;
    }

    public function checkREGO($rego)
    {
        //check if the rego is on the db
        $vehicule = $this->Maxauto_Model->checkVehiculeByREGO($rego);

        if (empty($vehicule)) {
            $data = $this->json_encode_msgs(true);
            return $data;
        } else {
            $data = $this->json_encode_msgs(false);
            return $data;
        }
    }

    public function silentLogin()
    {
        //remove later;
        //return;
        $id_customer = $this->input->post_get('id_customer');
        $token_id = $this->input->post_get('token_id');

        if (isset($id_customer) && isset($token_id)) {
            $data = $this->Maxauto_Model->selectCustomerByIdAndToken($id_customer, $token_id);
            $result = $data;

            if (isset($result[0])) {
                //login
                $this->session->set_userdata('SESS_CUSTOMER_ID', $result[0]->customer_id);
                $this->session->set_userdata('SESS_CUSTOMER_TOKEN', $result[0]->is_token);
                $this->session->set_userdata('SESS_CUSTOMER_TOKEN_TYPE', 0);
                $this->session->set_userdata('SESS_CUSTOMER_EMAIL', $result[0]->customer_email);
                $this->session->set_userdata('SESS_CUSTOMER_NAME', $result[0]->customer_name);
                $this->json_encode_msgs($result, $this->chkLogged());
                return;
            } else {
                $this->json_encode_msgs(false, $this->chkLogged());
                return false;
            }
        } else {
            //return false;     
        }
        //get value
    }

    public function signUp()
    {
        $name = $this->input->post_get('name');
        $email = $this->input->post_get('email');
        $mobile = $this->input->post_get('mobile');
        $password = $this->input->post_get("password");

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || empty($name) || empty($password)) {
            $this->errorMsg("Check the transmitted parameters.");
        } else {
            $data = $this->Maxauto_Model->selectCustomerEmail($email);
            if ($data->chk_email) { // Exist Email
                $this->errorMsg("Your Email account exists.");
            } else if (!empty($data->facebook_token)) { // Exist Email
                $this->errorMsg("Your Facebook account exists.");
            } else if (!empty($data->google_token)) { // Exist Email
                $this->errorMsg("Your Google+ account exists.");
            } else { // New Registration
                $data = array();
                $data['customer_type'] = 0;
                $data['customer_name'] = $name;
                $data['customer_mobile'] = $mobile;
                $data['customer_password'] = password_hash($password, PASSWORD_DEFAULT);
                $data['customer_email'] = $email;

                if ($_FILES) {
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

                // auto login
                $id = $this->Maxauto_Model->insertCustomerId($data);
                $token_customer = $this->token_keys();
                $this->session->set_userdata('SESS_CUSTOMER_ID', $id);
                $this->session->set_userdata('SESS_CUSTOMER_TOKEN', $token_customer);
                $this->session->set_userdata('SESS_CUSTOMER_TOKEN_TYPE', 0);
                $this->session->set_userdata('SESS_CUSTOMER_EMAIL', $email);
                $this->session->set_userdata('SESS_CUSTOMER_NAME', $name);

                $token_data = array();
                $token_data['is_token'] = $token_customer;
                $token_data['indate'] = $this->dateTime();
                $token_data['fk_customer_id'] = $id;
                $this->Maxauto_Model->insertTokenLog($token_data);
                $result = $this->Maxauto_Model->selectCustomerInfo($_SESSION['SESS_CUSTOMER_ID']);
                $this->json_encode_msgs($result, $this->chkLogged());
            }
        }
    }






    function console_log($data)
    {
        echo '<script>';
        echo 'console.log(' . json_encode($data) . ')';
        echo '</script>';
    }




    //new
    /** PropertiMax - Ver_1.3.4
     * Requirements:
     * URL: https://propertimax.co.nz/Customer/signInFacebook
     * Transmission method: POST
     * Parameters: email, password
     */
    public function signInFacebook()
    {
        $social_id = $this->input->post_get("social_id");
        $social_name = $this->input->post_get("social_name");
        $social_email = $this->input->post_get('social_email');
        $access_token = $this->input->post_get('access_token');

        if (!filter_var($social_email, FILTER_VALIDATE_EMAIL) || empty($social_id) || empty($social_name) || empty($access_token)) {
            $this->errorMsg("Check the transmitted parameters.");
        } else {
            $data = $this->Maxauto_Model->selectCustomerSocial($social_id);

            $token_type = 1;
            $token_customer = $this->token_keys();
            $this->session->set_userdata('SESS_CUSTOMER_TOKEN', $token_customer);
            $this->session->set_userdata('SESS_CUSTOMER_TOKEN_TYPE', $token_type);

            $token_data = array();
            $token_data['is_token'] = $token_customer;
            $token_data['token_type'] = $token_type;
            $token_data['indate'] = $this->dateTime();

            $customer_data = array();
            $customer_data['customer_name'] = $social_name;
            $customer_data['customer_email'] = $social_email;
            $customer_data['social_token'] = $access_token;

            $this->session->set_userdata('SESS_CUSTOMER_EMAIL', $social_email);
            $this->session->set_userdata('SESS_CUSTOMER_NAME', $social_name);

            if (!empty($data->social_id) | $data->customer_email == $social_email) { // Exist
                $token_data['fk_customer_id'] = $data->customer_id;
                $this->Maxauto_Model->insertTokenLog($token_data);
                $this->session->set_userdata('SESS_CUSTOMER_ID', $data->customer_id);
                $this->Maxauto_Model->updateCustomer($customer_data, $_SESSION['SESS_CUSTOMER_ID']);
                $result = $this->Maxauto_Model->selectCustomerInfo($_SESSION['SESS_CUSTOMER_ID']);
                $result->is_token = $token_customer;
                $this->json_encode_msgs($result, $this->chkLogged());
                return;
            } else {
                $customer_data['social_id'] = $social_id;
                $return_customer_id = $this->Maxauto_Model->insertCustomerId($customer_data);

                $token_data['fk_customer_id'] = $return_customer_id;
                $this->Maxauto_Model
                    ->insertTokenLog($token_data);

                $this->session->set_userdata('SESS_CUSTOMER_ID', $return_customer_id);

                $result = $this->Maxauto_Model->selectCustomerInfo($_SESSION['SESS_CUSTOMER_ID']);
                $result->is_token = $token_customer;
                $this->json_encode_msgs($result, $this->chkLogged());
                return;
            }
            $this->errorMsg("Facebook log in Fail.");
        }
    }

    /** PropertiMax - Ver_1.3.4
     * Requirements:
     * URL: https://propertimax.co.nz/Customer/signInGoogle
     * Transmission method: POST
     * Parameters: email, password
     */
    public function signInGoogle()
    {
        $social_id = $this->input->post_get("social_id");
        $social_name = $this->input->post_get("social_name");
        $social_email = $this->input->post_get('social_email');
        $access_token = $this->input->post_get('access_token');
        $customer_photo = $this->input->post_get('customer_photo');

        if (!filter_var($social_email, FILTER_VALIDATE_EMAIL) || empty($social_id) || empty($social_name) || empty($access_token)) {
            $this->errorMsg("Check the transmitted parameters.");
        } else {
            $data = $this->Maxauto_Model->selectCustomerSocial($social_id);

            $token_type = 2;
            $token_customer = $this->token_keys();
            $this->session->set_userdata('SESS_CUSTOMER_TOKEN', $token_customer);
            $this->session->set_userdata('SESS_CUSTOMER_TOKEN_TYPE', $token_type);

            $token_data = array();
            $token_data['is_token'] = $token_customer;
            $token_data['token_type'] = $token_type;
            $token_data['indate'] = $this->dateTime();

            $customer_data = array();
            $customer_data['customer_name'] = $social_name;
            $customer_data['customer_email'] = $social_email;
            $customer_data['social_token'] = $access_token;
            $customer_data['customer_pic'] = $customer_photo;

            $this->session->set_userdata('SESS_CUSTOMER_EMAIL', $social_email);
            $this->session->set_userdata('SESS_CUSTOMER_NAME', $social_name);

            if (!empty($data->social_id) | $data->customer_email == $social_email) { // Exist
                $token_data['fk_customer_id'] = $data->customer_id;
                $this->Maxauto_Model->insertTokenLog($token_data);

                $this->session->set_userdata('SESS_CUSTOMER_ID', $data->customer_id);

                $this->Maxauto_Model->updateCustomer($customer_data, $_SESSION['SESS_CUSTOMER_ID']);
                $result = $this->Maxauto_Model->selectCustomerInfo($_SESSION['SESS_CUSTOMER_ID']);
                $result->is_token = $token_customer;
                $this->json_encode_msgs($result, $this->chkLogged());
                return;
            } else {
                $customer_data['social_id'] = $social_id;
                $return_customer_id = $this->Maxauto_Model->insertCustomerId($customer_data);

                $token_data['fk_customer_id'] = $return_customer_id;
                $this->Maxauto_Model->insertTokenLog($token_data);

                $this->session->set_userdata('SESS_CUSTOMER_ID', $return_customer_id);

                $result = $this->Maxauto_Model->selectCustomerInfo($_SESSION['SESS_CUSTOMER_ID']);
                $result->is_token = $token_customer;
                $this->json_encode_msgs($result, $this->chkLogged());
                return;
            }
            $this->errorMsg("Google+ log in Fail.");
        }
    }

    /** PropertiMax - Ver_1.3.4
     * Requirements:
     * URL: https://propertimax.co.nz/Customer/signInEmail
     * Transmission method: POST
     * Parameters: email, password
     */
    public function signInEmail()
    {
        $email = $this->input->post_get('email');
        $password = $this->input->post_get("password");

        $data = $this->Maxauto_Model->selectCustomerEmail($email);
        if ($data->chk_email && $this->comparePassword($password, $data->customer_password)) { // Check Email and Password
            // Set Session
            $token_customer = $this->token_keys();
            $this->session->set_userdata('SESS_CUSTOMER_ID', $data->customer_id);
            $this->session->set_userdata('SESS_CUSTOMER_TOKEN', $token_customer);
            $this->session->set_userdata('SESS_CUSTOMER_TOKEN_TYPE', 0);
            $this->session->set_userdata('SESS_CUSTOMER_EMAIL', $email);
            $this->session->set_userdata('SESS_CUSTOMER_NAME', $data->customer_name);
            $this->session->set_userdata('SESS_CUSTOMER_AGENT', $data->is_private_agent);

            $token_data = array();
            $token_data['is_token'] = $token_customer;
            $token_data['indate'] = $this->dateTime();
            $token_data['fk_customer_id'] = $data->customer_id;
            $this->Common_Model->insertTokenLog($token_data);

            $result = $this->Maxauto_Model->selectCustomerInfo($_SESSION['SESS_CUSTOMER_ID']);
            $result->is_token = $token_customer;
            $this->json_encode_msgs($result, $this->chkLogged());
            return;
        } else {
            $this->errorMsg("Email or Password is Incorrect");
            return;
        }
    }

    /** PropertiMax - Ver_1.3.4
     * Requirements:
     * URL: https://propertimax.co.nz/Customer/signInAgencies
     * Transmission method: POST
     * Parameters: email, password
     */
    public function signInAgencies()
    {
        $email = $this->input->post_get('branch_email');
        $password = $this->input->post_get("branch_password");


        $data = $this->Customer_Model->selectBranchEmail($email);

        if ($data->chk_email && $password == $data->branch_login_password) { // Check Email and Password
            // Set Session
            $token_customer = $this->token_keys();
            $this->session->set_userdata('SESS_BRANCH_ID', $data->branch_id);
            $this->session->set_userdata('SESS_BRANCH_TOKEN', $token_customer);
            $this->session->set_userdata('SESS_BRANCH_TOKEN_TYPE', 0);
            $this->session->set_userdata('SESS_BRANCH_EMAIL', $email);
            $this->session->set_userdata('SESS_BRANCH_NAME', $data->branch_name);

            $token_data = array();
            $token_data['is_token'] = $token_customer;
            $token_data['indate'] = $this->dateTime();
            $token_data['fk_customer_id'] = $data->branch_id;
            $this->Common_Model->insertTokenLog($token_data);

            $result = $data;
            $this->json_encode_msgs($result, $this->chkLogged());
            return;
        } else {
            $this->errorMsg("Email or Password is Incorrect");
            return;
        }
    }

    /** PropertiMax - Ver_1.3.4
     * Requirements:
     * URL: https://propertimax.co.nz/Customer/signOut
     * Transmission method: POST
     * Parameters: email, password
     */
    public function signOut()
    {
        $this->session->unset_userdata('SESS_CUSTOMER_ID');
        $this->session->unset_userdata('SESS_CUSTOMER_TOKEN');
        $this->session->sess_destroy();
        $this->json_encode_msgs(NULL, $this->chkLogged(), 'Log Out.');
    }

    /** PropertiMax - Ver_1.3.4
     * Requirements:
     * URL: https://propertimax.co.nz/Customer/signUp
     * Transmission method: POST
     * Parameters: name, email, mobile, password
     * File: profile[0]
     */



    public function snedmailtest()
    {
        $this->sendFeedbackEmail("thilan@imperialdigital.co.nz", "thilan", "url link will bw hwrwrdkj");
        echo 'ok';
    }

    public function activation()
    {
        $email = base64_decode($this->input->post_get('jhg'));
        $data = $this->Customer_Model->selectCustomerEmail($email);
        if ($data->chk_email) {
        } else {
        }
    }

    /** PropertiMax - Ver_1.3.4
     * Requirements:
     * URL: https://propertimax.co.nz/Customer/searchList
     * Transmission method: POST
     * Parameters: sale_flag
     */
    public function searchList()
    {
        if ($this->chkLogged() == 0) {
            $this->errorMsg("Please login");
            return;
        } else {
            $sale_flag = $this->input->post_get('sale_flag'); //0: Sale, 1: Rent
            $search_list = $this->Customer_Model->selectSearchList($_SESSION['SESS_CUSTOMER_ID'], $sale_flag);

            $this->json_encode_msgs($search_list, 1);
        }
    }

    /** PropertiMax - Ver_1.3.4
     * Requirements:
     * URL: https://propertimax.co.nz/Customer/agentList
     * Transmission method: POST
     * Parameters: agent_name
     */
    public function agentList()
    {
        $key_word = $this->input->post_get('agent_name');
        $company = $this->input->post_get('company');
        $sort = $this->input->post_get('sort');
        $sort_option = $this->input->post_get('sort_option');
        $city_id = $this->input->post_get('city_id');
        $region_id = $this->input->post_get('region_id');
        $logged = $this->chkLogged();
        $logged_code = 0;

        if ($logged == 1)
            $logged_code = $_SESSION['SESS_CUSTOMER_ID'];

        $subrubs = array();
        if (!empty($this->input->post_get('suburb_id'))) {
            foreach ($this->input->post_get('suburb_id') as $subval) {
                array_push($subrubs, $subval);
            }
        }

        // $agent_list = $this->Customer_Model->selectAgentList($key_word, $logged_code);
        $agent_list = $this->Customer_Model->selectAgentList($key_word, $logged_code, $company, $sort, $sort_option, $city_id, $region_id, $subrubs);
        $this->json_encode_msgs($agent_list, $logged);
    }

    /** PropertiMax - Ver_1.3.4 (added - 27-Feb-18)
     * Requirements:
     * URL: https://propertimax.co.nz/Customer/forgotPassword
     * Transmission method: POST
     * Parameters: agent_name
     */
    public function forgotPassword()
    {
        $email = $this->input->post_get('email');

        $data = $this->Customer_Model->selectCustomerEmail($email);
        if ($data->chk_email) { // Exist Email
            $temp_password = $this->uniqid_base36('password');
            $password_data = array();
            $password_data['customer_password'] = password_hash($temp_password, PASSWORD_DEFAULT);
            $this->Customer_Model->updateCustomer($password_data, $data->customer_id);

            // set temp_password and send email
            $result = $this->sendResetPasswordEmail($email, $data->customer_name, $temp_password);

            $this->json_encode_msgs($result, $this->chkLogged());
        } else {
            $this->errorMsg("The email does not exist.");
        }
    }

    /** PropertiMax - Ver_1.3.4 (added - 27-Feb-18)
     * Requirements:
     * URL: https://propertimax.co.nz/Customer/initFeedback
     * Transmission method: POST
     * Parameters: customer_id(session)
     */
    public function initFeedback()
    {
        $logged = $this->chkLogged();
        $result = array();

        if ($logged == 1) {
            $result = $this->Customer_Model->selectCustomerInfo($_SESSION['SESS_CUSTOMER_ID']);
        } else {
            $result['customer_name'] = NULL;
            $result['customer_email'] = NULL;
        }

        $this->json_encode_msgs($result, $logged);
    }

    /** PropertiMax - Ver_1.3.4 (added - 27-Feb-18)
     * Requirements:
     * URL: https://propertimax.co.nz/Customer/sendFeedback
     * Transmission method: POST
     * Parameters: fname, email, send_mags
     */
    public function sendFeedback()
    {
        $fname = $this->input->post_get('fname');
        $email = $this->input->post_get('email');
        $send_mags = $this->input->post_get('send_mags');

        $result = $this->sendFeedbackEmail($email, $fname, $send_mags);
        $this->json_encode_msgs($result, $this->chkLogged());
    }

    /** PropertiMax - Ver_1.3.4 (added - 01-Mar-18)
     * Requirements:
     * URL: https://propertimax.co.nz/Customer/setNotification
     * Transmission method: POST
     * Parameters: fname, email, send_mags
     */
    public function setNotification()
    {
        $cusid = "";
        $logged = $this->chkLogged();

        if ($logged == 0) {
            $this->errorMsg("Please login");
            return;
        } else {
            $cusid = $_SESSION['SESS_CUSTOMER_ID'];
            $auction_3days = $this->input->post_get('auction_3days');
            $auction_day = $this->input->post_get('auction_day');
            $agent_updates = $this->input->post_get('agent_updates');
            $saved_search = $this->input->post_get('saved_search');
            $frequency = $this->input->post_get('frequency');
            $available_day = $this->input->post_get('available_day');
            $data = array();

            if ($auction_3days == 0 || $auction_3days == 1)
                $data['notifi_auction_3days'] = $auction_3days;

            if ($auction_day == 0 || $auction_day == 1)
                $data['notifi_auction_day'] = $auction_day;

            if ($agent_updates == 0 || $agent_updates == 1)
                $data['notifi_agent_updates'] = $agent_updates;

            if ($saved_search == 0 || $saved_search == 1)
                $data['notifi_saved_search'] = $saved_search;

            if ($frequency == 0 || $frequency == 1 || $frequency == 2)
                $data['nofifi_frequency'] = $frequency;

            if ($available_day == 0 || $available_day == 1) {
                $data['notifi_available_day'] = $available_day;
            }

            $this->Customer_Model->updateCustomer($data, $cusid);
            $result = $this->Customer_Model->selectCustomerInfo($cusid);
            $this->json_encode_msgs($result, $logged);
        }
    }

    public function getImage($folder, $file_out)
    {

        switch ($folder) {
            case 'flag':
                $image_url = base_url("/assets/img/flags/" . $file_out);
                break;

            default:
                # code...
                break;
        }

        if (file_exists($image_url)) {

            $image_info = getimagesize($file_out);

            //Set the content-type header as appropriate
            header('Content-Type: ' . $image_info['mime']);

            //Set the content-length header
            header('Content-Length: ' . filesize($file_out));

            //Write the image bytes to the client
            readfile($file_out);
        } else { // Image file not found


            //header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");

        }
    }

    /** PropertiMax - Ver_1.3.4
     * Requirements: for Customer
     * URL: https://propertimax.co.nz/Customer/getRequestsWithinRadius
     * Transmission method: POST
     * Parameters: agent_id
     */
    public function getRequestsWithinRadius()
    {
        $logged = $this->chkLogged();
        $logged_code = 0;
        if ($logged == 1)
            $logged_code = $_SESSION['SESS_CUSTOMER_ID'];


        $lat = $this->input->post_get('lat');
        $lng = $this->input->post_get('lng');
        //$radius = $this->input->post_get('radius');
        $radius = 80;
        $page = $this->input->post_get('page');
        $sort_option = $this->input->post_get('sort');
        if ($lat === "" | $lng === "") {
            $this->errorMsg("Please Enable the Location");
            return;
        }
        $result = $this->Customer_Model->getAllRequestsWithDistance($lat, $lng, $radius, $logged_code, null, $sort_option);
        $this->json_encode_msgs($result);
    }

    public function getAgentFilter()
    {
        $logged = $this->chkLogged();
        $logged_code = 0;
        if ($logged == 1)
            $logged_code = $_SESSION['SESS_CUSTOMER_ID'];

        $lat = $this->input->post_get('lat');
        $lng = $this->input->post_get('lng');
        $radiusFrom = $this->input->post_get('radiusFrom');
        $radiusTo = $this->input->post_get('radiusTo');
        $role = $this->input->post_get('role');
        $language = $this->input->post_get('language');

        if ($language == "0") {
            $language = "";
        }

        if ($lat === "" | $lng === "") {
            $this->errorMsg("Please Enable the Location");
            return;
        }
        $result = $this->Customer_Model->getAgentFilter($lat, $lng, $radiusFrom, $radiusTo, $role, $language, $logged);
        $this->json_encode_msgs($result);
    }

    public function getPropertiesNearby()
    {
        $logged = $this->chkLogged();
        $logged_code = 0;
        if ($logged == 1) $logged_code = $_SESSION['SESS_CUSTOMER_ID'];
        $lat = $this->input->post_get('lat');
        $lng = $this->input->post_get('lng');
        $saleflag = $this->input->post_get('saleFlag');
        if ($lat === "" | $lng === "") {
            $this->errorMsg("Please Enable the Location");
            return;
        }
        $result = array();
        $PropertyList = $this->Customer_Model->getPropertiesNear($lat, $lng, $saleflag);

        //

        $property_size = $PropertyList->num_rows();

        if ($property_size > 0) {
            for ($irow = 0; $irow < $property_size; $irow++) {
                $Property_info = $PropertyList->result_array()[$irow];
                $property_id = $Property_info['property_id'];

                $line3 = "";
                if ($Property_info['fk_suburb_id']) {
                    $line3 = $this->Common_Model->get_suburb_from_id($Property_info['fk_suburb_id'])->suburb_name;
                }
                if ($Property_info['property_sale_flag'] == 1) {
                    $propertyline = array(
                        "line1" => $Property_info['property_type_name'] . " " . $this->checkprce($Property_info['property_show_price']) . " pw",
                        "line2" => $Property_info['address'],
                        "line3" => $line3
                    );
                } else {
                    $propertyline = array(
                        "line1" => $Property_info['property_title'],
                        "line2" => $Property_info['address'],
                        "line3" => $line3
                    );
                }
                $lable = NULL;

                if ($Property_info['property_indate'] != NULL) {
                    if (date('Ymd', strtotime('-1 day')) <= date('Ymd', strtotime($Property_info['property_indate']))) {
                        $lable = "New Listing";
                    }
                }

                if (array_key_exists('open_home_id', $Property_info)) {
                    if (date('Ymd') === date('Ymd', strtotime($Property_info['open_home_to']))) {
                        $lable = "Open Home";
                    }
                }

                if (array_key_exists('property_auction_date', $Property_info)) {
                    if (date('Ymd') === date('Ymd', strtotime($Property_info['property_option_date']))) {
                        // $lable = date('j F Y', strtotime($Property_info['property_auction_date']));
                        $lable = "Auction Today";
                    }
                }

                if ($Property_info['fk_property_status_id'] === "1") {
                    $lable = "Sold";
                }



                // thilan 19-05-2019 land hect to m2
                //                if($Property_info[""]){
                //                    
                //                }


                $Property_info["lable"] = $lable;
                if ($Property_info['property_available_date'] === "0000-00-00 00:00:00") {
                    $Property_info["property_available_date"] = NULL;
                } else {
                    $Property_info["property_available_date"] = date('D d M', strtotime($Property_info['property_available_date']));
                }


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
                $result_info["proprty_count"] = $property_size;
                array_push($result, $result_info);
            }
        }

        $this->json_encode_msgs($result, $logged);
    }

    public function getRequestsWithinRadiusTest()
    {


        $logged_code = "166";

        $lat = $this->input->post_get('lat');
        $lng = $this->input->post_get('lng');
        $radius = $this->input->post_get('radius');
        $page = $this->input->post_get('page');
        $sort_option = $this->input->post_get('sort');

        if ($lat === "" | $lng === "") {
            $this->errorMsg("Please Enable the Location");
            return;
        }
        $result = $this->Customer_Model->getAllRequestsWithDistance($lat, $lng, $radius, $logged_code, $page, $sort_option);
        $this->json_encode_msgs($result);
    }




    /** PropertiMax - Ver_1.3.4
     * Requirements: for Customer
     * URL: https://propertimax.co.nz/Customer/getRequestsWithinRadius
     * Transmission method: POST
     * Parameters: agent_id
     */
    public function getRequestsWithinRadiusSearch()
    {
        $logged = $this->chkLogged();
        $logged_code = 0;
        if ($logged == 1)
            $logged_code = $_SESSION['SESS_CUSTOMER_ID'];


        $lat = $this->input->post_get('lat');
        $lng = $this->input->post_get('lng');
        $keyword = trim($this->input->post_get('key_word'));
        $radius = $this->input->post_get('radius');
        $page = $this->input->post_get('page');
        $sort_option = $this->input->post_get('sort');

        if ($lat === "" | $lng === "") {
            $this->errorMsg("Please Enable the Location");
            return;
        }

        $result = $this->Customer_Model->getAllRequestsWithDistanceSearch($lat, $lng, $radius, $logged_code, $keyword, $page, $sort_option);
        $this->json_encode_msgs($result);
    }

    /** PropertiMax - Ver_1.3.4
     * Requirements: for Customer
     * URL: https://propertimax.co.nz/Customer/targetAgentList
     * Transmission method: POST
     * Parameters: agent_id
     */
    public function targetAgentList()
    {
        $agent_id = $this->input->post_get('agent_id');
        $lat = $this->input->post_get('lat');
        $long = $this->input->post_get('long');
        $logged = $this->chkLogged();
        $logged_code = 0;

        if ($logged == 1)
            $logged_code = $_SESSION['SESS_CUSTOMER_ID'];

        $result = array();
        $result_property = array();
        $result_info = array();
        $result['target_agent'] = $this->Customer_Model->selectTargetAgentList($agent_id, $logged_code, $lat, $long);
        $result['target_language'] = $this->Customer_Model->selectLanguageAgent($agent_id);




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

                if (array_key_exists('property_auction_date', $Property_info)) {
                    if (date('Ymd') === date('Ymd', strtotime($Property_info['property_auction_date']))) {
                        $lable = date('j F Y', strtotime($Property_info['property_auction_date']));
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
        $this->json_encode_msgs($result, $logged);
        return;
    }


    public function allAgent()
    {
        $result = $this->Customer_Model->selectTargetAllAgentList('1122');
        $this->json_encode_msgs($result);
    }


    public function allTypeUser()
    {
        $result = $this->Customer_Model->selectUserTypeList();
        $this->json_encode_msgs($result);
    }
    /** PropertiMax - Ver_1.3.4
     * Requirements: for Customer
     * URL: https://propertimax.co.nz/Customer/targetProperty
     * Transmission method: POST
     * Parameters: property_id
     */
    public function targetProperty()
    {
        $property_id = $this->input->post_get('property_id');
        $proexp = $this->input->post_get('propertyload');
        $logged = $this->chkLogged();
        $logged_code = 0;

        if ($logged == 1)
            $logged_code = $_SESSION['SESS_CUSTOMER_ID'];

        $target_property = $this->Customer_Model->selectTargetProperty($property_id, $proexp);
        $agent_id = $target_property[0]['fk_agent_id'];
        $customer_id = $target_property[0]['fk_customer_id'];

        if ($target_property) {
            $result = array();
            // $result['target_agent'] = $this->Customer_Model->selectTargetAgentList($agent_id);
            if ($agent_id) {
                $result['target_agent'] = $this->Customer_Model->selectTargetAllAgentList($property_id);
                $result['target_agent_assis'] = $this->Customer_Model->selectTargetAllAgentListAssis($property_id);


                for ($index = 0; $index < count($result['target_agent']); $index++) {
                    $result['target_agent'][$index]["agency_full_name"] = $result['target_agent'][$index]["agency_office"]; //. " (Licenced REAA 2008)"
                }

                for ($index = 0; $index < count($result['target_agent_assis']); $index++) {
                    $result['target_agent_assis'][$index]["agency_full_name"] = $result['target_agent'][$index]["agency_office"]; //. " (Licenced REAA 2008)"
                }

                $result['target_agent'] = array_merge($result['target_agent'], $result['target_agent_assis']);

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
                'region_name' => $region,
                'shareLink' =>  base_url("WebApp/viewproperty?pid=" . $property_id)
            );
            $aucdate = "";
            if ($target_property[0]['property_auction'] == 1) {
                $aucdate = date('d M Y', strtotime($target_property[0]['property_auction_date']));
            }

            $avabledt = "";
            if ($target_property[0]['property_available_date'] != "0000-00-00 00:00:00") {
                $avabledt = date('d M Y', strtotime($target_property[0]['property_available_date']));
            }
            if ($target_property[0]['property_sale_flag'] == 1) {
                $priceconvert = array(
                    "property_show_price" => $this->checkprce($target_property[0]['property_show_price']) . " pw",
                    "property_auction_date" => $aucdate,
                    "property_available_date" => $avabledt,
                    "property_indate" => date('d M Y', strtotime($target_property[0]['property_indate'])),
                    "property_update" => date('d M Y', strtotime($target_property[0]['property_update']))
                );
            } else {
                $priceconvert = array(
                    "property_show_price" => $this->checkprce($target_property[0]['property_show_price']),
                    "property_auction_date" => $aucdate,
                    "property_available_date" => $avabledt,
                    "property_indate" => date('d M Y', strtotime($target_property[0]['property_indate'])),
                    "property_update" => date('d M Y', strtotime($target_property[0]['property_update']))
                );
            }
            $newreplace = array_replace($target_property[0], $priceconvert);



            $result['property_info'] = array_merge($newreplace, $porpertymore);

            $PropertiPicList = $this->Customer_Model->selectPropertiPicList($property_id);
            $PropertiPic_size = count($PropertiPicList);
            $PropertiOpenList = $this->Customer_Model->selectOpenHomeList($property_id);
            $PropertiOpen_size = count($PropertiOpenList);

            if ($PropertiOpen_size > 0) {
                for ($index2 = 0; $index2 < count($PropertiOpenList); $index2++) {
                    //$PropertiOpenList[$index2]["open_home_from"] = date('d M Y H:i', strtotime($PropertiOpenList[$index2]["open_home_from"]));
                    //$PropertiOpenList[$index2]["open_home_to"] = date('d M Y H:i', strtotime($PropertiOpenList[$index2]["open_home_to"]));


                    //$date1 = new DateTime($PropertiOpenList[$index2]["open_home_from"]);
                    //$date2 = new DateTime($PropertiOpenList[$index2]["open_home_to"]);

                    //$PropertiOpenList[$index2]["open_home_date"] = date('d M Y', strtotime($PropertiOpenList[$index2]["open_home_from"]));
                    //$PropertiOpenList[$index2]["start_time"] = $date1->format('h:i A');
                    //$PropertiOpenList[$index2]["end_time"] = $date2->format('h:i A');

                    //news
                    $PropertiOpenList[$index2]["open_home_date"] = date('l d M', strtotime($PropertiOpenList[$index2]["open_home_date"]));
                    $PropertiOpenList[$index2]["start_time"] = $PropertiOpenList[$index2]["open_home_time_from"];
                    $PropertiOpenList[$index2]["end_time"] = $PropertiOpenList[$index2]["open_home_time_to"];
                }
            }

            if ($logged_code > 0) {
                $MaxList = $this->Customer_Model->cntMaxList($logged_code, $property_id);
            } else {
                $MaxList = 0;
            }

            $result['property_pic_size'] = $PropertiPic_size;
            $result['property_pic'] = $PropertiPicList;
            $result['property_open_size'] = $PropertiOpen_size;
            $result['property_open'] = $PropertiOpenList;
            $result['max_list'] = $MaxList;

            $this->json_encode_msgs($result, $logged);
        } else {
            $this->errorMsg("Property Not exist");
        }
    }

    /** PropertiMax - Ver_1.3.4
     * Requirements:
     * URL: https://propertimax.co.nz/Customer/changePasswd
     * Transmission method: POST
     * Parameters: email, password, customer_id(session)
     */
    public function changePasswd()
    {
        $customer_password = $this->input->post_get('old_passwd');
        $new_password = $this->input->post_get('new_passwd');
        $logged = $this->chkLogged();

        if ($logged == 0) {
            $this->errorMsg("Please login.");
            return;
        } else {
            if (empty($customer_password) || empty($new_password)) {
                $this->errorMsg("Check the transmitted parameters.", $logged);
            } else {
                $customer_id = $_SESSION['SESS_CUSTOMER_ID']; //Session Value
                $data = $this->Customer_Model->selectCustomerPasswd($customer_id);

                if ($this->comparePassword($customer_password, $data->customer_password)) {
                    $data = array();
                    $data['customer_password'] = password_hash($new_password, PASSWORD_DEFAULT);
                    $result = $this->Customer_Model->updateCustomer($data, $customer_id);
                    $this->json_encode_msgs($result);
                } else {
                    $this->errorMsg("Password is Incorrect.", $logged);
                }
            }
        }
    }

    /** PropertiMax - Ver_1.3.4
     * Requirements:
     * URL: https://propertimax.co.nz/Customer/saveSearch
     * Transmission method: POST
     * Parameters: search_sale_flag(0: Sale, 1: Rent, 2: Auction)
     */
    public function saveSearch()
    {
        $search_sale_flag = $this->input->post_get('search_sale_flag'); // 0: Sale, 1: Rent, 2: Auction
        // Location
        $suburb_id = $this->input->post_get('suburb_id');
        $city_id = $this->input->post_get('city_id');
        $region_id = $this->input->post_get('region_id');
        $sorting = $this->input->post_get('sort');
        $page = $this->input->post_get('page');

        $property_type = array(); // Property Type (Check Box)
        if (!empty($this->input->post_get('property_type'))) {
            foreach ($this->input->post_get('property_type') as $property_type_id) {
                array_push($property_type, $property_type_id);
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
        $search_bedroom_from = $this->input->post_get('search_bedroom_from'); // Search Condition
        $search_bedroom_to = $this->input->post_get('search_bedroom_to'); // Search Condition
        $search_bathroom_from = $this->input->post_get('search_bathroom_from'); // Search Condition
        $search_bathroom_to = $this->input->post_get('search_bathroom_to'); // Search Condition
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
        $search['search_name'] = $search_name;
        $search['sorting'] = $sorting;
        $search['savesearch'] = $save_search;

        if ($save_search == 1) { // Insert Search
            if ($this->chkLogged() == 0) {
                $this->errorMsg("Please login");
                return;
            } else {
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
        }

        $search['fk_suburb_id'] = $suburb_id;
        $search['fk_city_id'] = $city_id;
        $search['fk_region_id'] = $region_id;

        $this->findSearch($search, $property_type, $subrubs, $page);
    }

    /** PropertiMax - Ver_1.3.4
     * Requirements:
     * URL: https://propertimax.co.nz/Customer/saveTargetSearch
     * Transmission method: POST
     * Parameters: search_id
     * search_sale_flag(0: Sale, 1: Rent, 2: Auction)
     */
    public function saveTargetSearch()
    {
        $search_id = $this->input->post_get('search_id');
        $sort = $this->input->post_get('sort');
        $page = $this->input->post_get('page');
        if ($this->chkLogged() == 0) {
            $this->errorMsg("Please login");
            return;
        } else {
            $save_search = $this->Customer_Model->selectTargetSearch($search_id);
            $search = $save_search[0];
            if ($sort) {
                $search["sorting"] = $sort;
            }

            $search['savesearch'] = "1";

            $save_result = $this->Customer_Model->selectPropertyType($search_id);
            $savesubs = $this->Customer_Model->selectSuburbs($search_id);

            $property_type = array(); // Property Type
            foreach ($save_result->result() as $save_property_type) {
                array_push($property_type, $save_property_type->fk_property_type_id);
            }

            $suburbs = array(); // Suburbs
            foreach ($savesubs->result() as $save_subrub_ids) {
                array_push($suburbs, $save_subrub_ids->fk_proprty_subrub_id);
            }

            $this->findSearch($search, $property_type, $suburbs, $page);
        }
    }

    /** PropertiMax - Ver_1.3.4
     * Requirements: 
     * Transmission method: Function Call
     * Parameters: $search(Array), $property_type(Array)
     */
    public function checkprce($price)
    {
        if (ctype_digit($price)) {
            return "$" . number_format($price);
        } else {
            return $price;
        }
    }



    public function findSearch($search, $property_type, $subrubs, $page)
    {
        $logged = $this->chkLogged();
        $logged_code = 0;

        if ($logged == 1)
            $logged_code = $_SESSION['SESS_CUSTOMER_ID'];

        $result = array();
        $result_info = array();
        $PropertyList = $this->Customer_Model->selectSearchPropertyList($search, $property_type, $subrubs, $page);
        $property_size = $PropertyList->num_rows();

        if ($property_size > 0) {
            for ($irow = 0; $irow < $property_size; $irow++) {
                $Property_info = $PropertyList->result_array()[$irow];
                $property_id = $Property_info['property_id'];

                $line3 = "";
                if ($Property_info['fk_suburb_id']) {
                    $line3 = $this->Common_Model->get_suburb_from_id($Property_info['fk_suburb_id'])->suburb_name;
                }
                if ($Property_info['property_sale_flag'] == 1) {
                    $propertyline = array(
                        "line1" => $Property_info['property_type_name'] . " " . $this->checkprce($Property_info['property_show_price']) . " pw",
                        "line2" => $Property_info['address'],
                        "line3" => $line3
                    );
                } else {
                    $propertyline = array(
                        "line1" => $Property_info['property_title'],
                        "line2" => $Property_info['address'],
                        "line3" => $line3
                    );
                }
                $lable = NULL;

                if ($Property_info['property_indate'] != NULL) {
                    if (date('Ymd', strtotime('-1 day')) <= date('Ymd', strtotime($Property_info['property_indate']))) {
                        $lable = "New Listing";
                    }
                }

                if (array_key_exists('open_home_id', $Property_info)) {
                    if (date('Ymd') === date('Ymd', strtotime($Property_info['open_home_to']))) {
                        $lable = "Open Home";
                    }
                }

                if (array_key_exists('property_auction_date', $Property_info)) {
                    if (date('Ymd') === date('Ymd', strtotime($Property_info['property_option_date']))) {
                        // $lable = date('j F Y', strtotime($Property_info['property_auction_date']));
                        $lable = "Auction Today";
                    }
                }

                if ($Property_info['fk_property_status_id'] === "1") {
                    $lable = "Sold";
                }



                // thilan 19-05-2019 land hect to m2
                //                if($Property_info[""]){
                //                    
                //                }



                $Property_info["lable"] = $lable;
                if ($Property_info['property_available_date'] === "0000-00-00 00:00:00") {
                    $Property_info["property_available_date"] = NULL;
                } else {
                    $Property_info["property_available_date"] = date('D d M', strtotime($Property_info['property_available_date']));
                }


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
                $result_info["proprty_count"] = $property_size;
                array_push($result, $result_info);
            }
        }

        $this->json_encode_msgs($result, $logged);
    }

    /** PropertiMax - Ver_1.3.4
     * Requirements:
     * URL: https://propertimax.co.nz/Customer/searchAgentList
     * Transmission method: POST
     * Parameters: email, password
     */
    public function searchAgentList()
    {
        $logged = $this->chkLogged();

        if ($logged == 0) {
            $this->errorMsg("Please login");
            return;
        } else {
            $key_word = $this->input->post_get('key_word'); //Session Value
            $search_list = $this->Customer_Model->selectSearchAgentList($_SESSION['SESS_CUSTOMER_ID'], $key_word);

            $this->json_encode_msgs($search_list, $logged);
        }
    }

    public function searchAgentListTest()
    {
        $logged = $this->chkLogged();

        $key_word = $this->input->post_get('key_word'); //Session Value
        $lat = $this->input->post_get('lat'); //Session Value
        $long = $this->input->post_get('long'); //Session Value
        $search_list = $this->Customer_Model->selectSearchAgentList('160', $key_word, $lat, $long);

        $this->json_encode_msgs($search_list, $logged);
    }

    /** PropertiMax - Ver_1.3.4
     * Requirements:
     * URL: https://propertimax.co.nz/Customer/dashBoard
     * Transmission method: POST
     * Parameters: 
     */
    public function dashBoard()
    {
        $logged = $this->chkLogged();

        if ($logged == 0) {
            $this->errorMsg("Please login");
            return;
        } else {
            $result = array();
            $customer_id = $_SESSION['SESS_CUSTOMER_ID'];
            $customerInfo = $this->Customer_Model->selectCustomerInfo($customer_id);
            $dash_sort = explode(",", "0,1,2,3,4,5,6,7,8");
            $dash_size = sizeof($dash_sort);

            $result['Customer_Info'] = $customerInfo;
            $result['Dashboard_Info'] = array();

            for ($irow = 0; $irow < $dash_size; $irow++) {
                if ($dash_sort[$irow] == '0') {
                    $tmp0 = array();
                    $tmp0['flag'] = 0;
                    $tmp0['count'] = $this->Customer_Model->selectForSale($customer_id); // 0
                    array_push($result['Dashboard_Info'], $tmp0);
                } else if ($dash_sort[$irow] == '1') {
                    $tmp1 = array();
                    $tmp1['flag'] = 1;
                    $tmp1['count'] = $this->Customer_Model->selectAuctions($customer_id); // 1
                    array_push($result['Dashboard_Info'], $tmp1);
                } else if ($dash_sort[$irow] == '2') {
                    $tmp2 = array();
                    $tmp2['flag'] = 2;
                    $tmp2['count'] = $this->Customer_Model->selectOpenHome($customer_id); // 2
                    array_push($result['Dashboard_Info'], $tmp2);
                } else if ($dash_sort[$irow] == '3') {
                    $tmp3 = array();
                    $tmp3['flag'] = 3;
                    $tmp3['count'] = $this->Customer_Model->selectForRental($customer_id); // 3
                    array_push($result['Dashboard_Info'], $tmp3);
                } else if ($dash_sort[$irow] == '4') {
                    $tmp4 = array();
                    $tmp4['flag'] = 4;
                    $tmp4['count'] = $this->Customer_Model->selectAvailableAgo($customer_id); // Available Now (-1 Month)
                    array_push($result['Dashboard_Info'], $tmp4);
                } else if ($dash_sort[$irow] == '5') {
                    $tmp5 = array();
                    $tmp5['flag'] = 5;
                    $tmp5['count'] = $this->Customer_Model->selectAvailablePast($customer_id); // Available This Month (+1 Month)
                    array_push($result['Dashboard_Info'], $tmp5);
                } else if ($dash_sort[$irow] == '6') { // All Save Search List
                    $tmp6 = array();
                    $tmp6['flag'] = 6;
                    $tmp6['count'] = $this->Customer_Model->selectAllSearchList($customer_id); // 6
                    array_push($result['Dashboard_Info'], $tmp6);
                } else if ($dash_sort[$irow] == '7') { // All Notification
                    $tmp7 = array();
                    $tmp7['flag'] = 7;
                    $tmp7['count'] = $this->Customer_Model->countNewsNotices($customer_id); // 7
                    array_push($result['Dashboard_Info'], $tmp7);
                } else if ($dash_sort[$irow] == '8') { // All Notification
                    $tmp8 = array();
                    $tmp8['flag'] = 8;
                    $tmp8['count'] = $this->Customer_Model->selectAllAgentContact($customer_id); // 8
                    array_push($result['Dashboard_Info'], $tmp8);
                }
            }
        }

        $this->json_encode_msgs($result, $logged);
    }

    public function dashBoardTest()
    {

        $result = array();
        $customer_id = 168;
        $customerInfo = $this->Customer_Model->selectCustomerInfo($customer_id);
        $dash_sort = explode(",", $customerInfo->customer_sort);
        $dash_size = sizeof($dash_sort);

        $result['Customer_Info'] = $customerInfo;
        $result['Dashboard_Info'] = array();

        for ($irow = 0; $irow < $dash_size; $irow++) {
            if ($dash_sort[$irow] == '0') {
                $tmp0 = array();
                $tmp0['flag'] = 0;
                $tmp0['count'] = $this->Customer_Model->selectForSale($customer_id); // 0
                array_push($result['Dashboard_Info'], $tmp0);
            } else if ($dash_sort[$irow] == '1') {
                $tmp1 = array();
                $tmp1['flag'] = 1;
                $tmp1['count'] = $this->Customer_Model->selectAuctions($customer_id); // 1
                array_push($result['Dashboard_Info'], $tmp1);
            } else if ($dash_sort[$irow] == '2') {
                $tmp2 = array();
                $tmp2['flag'] = 2;
                $tmp2['count'] = $this->Customer_Model->selectOpenHome($customer_id); // 2
                array_push($result['Dashboard_Info'], $tmp2);
            } else if ($dash_sort[$irow] == '3') {
                $tmp3 = array();
                $tmp3['flag'] = 3;
                $tmp3['count'] = $this->Customer_Model->selectForRental($customer_id); // 3
                array_push($result['Dashboard_Info'], $tmp3);
            } else if ($dash_sort[$irow] == '4') {
                $tmp4 = array();
                $tmp4['flag'] = 4;
                $tmp4['count'] = $this->Customer_Model->selectAvailableAgo($customer_id); // Available Now (-1 Month)
                array_push($result['Dashboard_Info'], $tmp4);
            } else if ($dash_sort[$irow] == '5') {
                $tmp5 = array();
                $tmp5['flag'] = 5;
                $tmp5['count'] = $this->Customer_Model->selectAvailablePast($customer_id); // Available This Month (+1 Month)
                array_push($result['Dashboard_Info'], $tmp5);
            }
            //                else if ($dash_sort[$irow] == '6') { // All Save Search List
            //                    $tmp6 = array();
            //                    $tmp6['flag'] = 6;
            //                    $tmp6['count'] = $this->Customer_Model->selectAllSearchList($customer_id); // 6
            //                    array_push($result['Dashboard_Info'], $tmp6);
            //                }
        }
        $this->json_encode_msgs($result, "true");
    }

    /** PropertiMax - Ver_1.3.4
     * Requirements:
     * URL: https://propertimax.co.nz/Customer/modSortDashboard
     * Transmission method: POST
     * Parameters: customer_sort
     */
    public function modSortDashboard()
    {
        $logged = $this->chkLogged();

        if ($logged == 0) {
            $this->errorMsg("Please login");
            return;
        } else {
            $data = array();
            $data['customer_sort'] = $this->input->post_get('customer_sort');
            $result = $this->Customer_Model->updateCustomer($data, $_SESSION['SESS_CUSTOMER_ID']);
            $this->json_encode_msgs($result, $logged);
        }
    }

    /** PropertiMax - Ver_1.3.4
     * Requirements:
     * URL: https://propertimax.co.nz/Customer/modProfile
     * Transmission method: POST
     * Parameters: customer_name, customer_description, customer_mobile, profile[0]
     */
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
                        $lable = date('j F Y', strtotime($Property_infos['property_auction_date']));
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

    public function maxPropertyAucktionList($logged, $search_flag, $max_result)
    {
        $result = array();
        $result_info = array();
        $PropertyList = $max_result;
        $property_size = $PropertyList->num_rows();
        $cnttest = 0;
        $datesc = array();
        $alldates = array();
        if ($property_size > 0) {

            for ($indexlkt = 0; $indexlkt < $property_size; $indexlkt++) {
                $Property_info = $PropertyList->result_array()[$indexlkt];

                $dates = date('d M Y', strtotime($Property_info['property_auction_date']));

                array_push($alldates, $dates);
                if ($indexlkt === ($property_size - 1)) {



                    $resdates = array_values(array_unique($alldates));


                    for ($yrow = 0; $yrow < count($resdates); $yrow++) {
                        $date = $resdates[$yrow];
                        $dtf = array("date" => $date);
                        //array_push($datesc, $dtf);
                        $datesc[$yrow] = $dtf;
                        $datesc[$yrow]["property"] = array();
                        for ($irow = 0; $irow < $property_size; $irow++) {
                            $Property_infos = $PropertyList->result_array()[$irow];
                            if ($date === date('d M Y', strtotime($Property_infos['property_auction_date']))) {

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


                                if (array_key_exists('property_auction_date', $Property_infos)) {
                                    if (date('Ymd') === date('Ymd', strtotime($Property_infos['property_auction_date']))) {
                                        $lable = date('j F Y', strtotime($Property_infos['property_auction_date']));
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

                                array_push($datesc[$yrow]["property"], $result_info);
                            }
                        }
                        if ($yrow === (count($resdates) - 1)) {
                            //array_push($result, $datesc);
                        }
                    }
                }
            }
        }

        $this->json_encode_msgs($datesc, $logged);
    }

    public function maxOpenHomeList($logged, $search_flag, $max_result)
    {
        $OpenHome_size = $max_result->num_rows();
        $result = array();
        $mainres = array();

        $result_time = array();
        $result_info = array();
        $datesc = array();
        $alldates = array();

        if ($OpenHome_size > 0) {


            for ($irow = 0; $irow < $OpenHome_size; $irow++) {
                $OpenHomeTime = $max_result->result_array()[$irow];
                $open_home_time = $OpenHomeTime['open_home_time'];
                array_push($result, $open_home_time);



                if ($irow === ($OpenHome_size - 1)) {

                    for ($yrow = 0; $yrow < count($result); $yrow++) {
                        $OpenHomeList = $this->Customer_Model->selectMaxOpenHomeList($_SESSION['SESS_CUSTOMER_ID'], $search_flag, $result[$yrow]);
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
                            /*  if ($Property_info['property_sale_flag'] == 1) {
                              $propertyline = array(
                              "line1" => $Property_info['property_type_name']." ".$this->checkprce($Property_info['property_show_price'])." pw",
                              "line2" => $Property_info['address'],
                              "line3" => $line3
                              );
                              }else{ */
                            $propertyline = array(
                                "line1" => $Property_info['property_title'],
                                "line2" => $Property_info['address'],
                                "line3" => $line3
                            );
                            //}


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

                            $PropertiPicList = $this->Customer_Model->selectPropertiPicList($property_id);
                            $PropertiPic_size = count($PropertiPicList);




                            $result_info['property_info'] = array_merge($propertyline, $Property_info);
                            $result_info['property_pic_size'] = $PropertiPic_size;
                            $result_info['property_pic'] = $PropertiPicList;
                            $MaxList = $this->Customer_Model->cntMaxList($_SESSION['SESS_CUSTOMER_ID'], $property_id);
                            $result_info['max_list'] = $MaxList;
                            array_push($datesc[$yrow]["property"], $result_info);
                        }
                    }
                }
            }
        }

        $this->json_encode_msgs($datesc, $logged);
    }

    /** PropertiMax - Ver_1.3.4
     * Requirements:
     * URL: https://propertimax.co.nz/Customer/addAgentList
     * Transmission method: POST
     * Parameters: name, email, password
     */
    public function addAgentList()
    {

        $customer_id = $this->input->post_get('customer_id');
        $agent_id = $this->input->post_get('agent_id');

        $chk_agent = $this->Customer_Model->chkAgentList($customer_id, $agent_id);
        $result = false;
        if ($this->Customer_Model->checkCustomerAndAgentExsist($agent_id, $customer_id)) {
            if ($chk_agent > 0) { // Exist Check
                $result = false;
            } else {
                $data = array();
                $data['fk_customer_id'] = $customer_id;
                $data['fk_agent_id'] = $agent_id;
                $result = $this->Customer_Model->insertAgentList($data);
            }
        }

        $this->json_encode_msgs($result, 1);
        return;
    }

    /** PropertiMax - Ver_1.3.4
     * Requirements:
     * URL: https://propertimax.co.nz/Customer/getThumbsUp
     * Transmission method: POST
     * Parameters: name, email, password
     */
    public function getThumbsUp()
    {
        $customer_id = $this->input->post_get('customer_id');
        $property_id = $this->input->post_get('property_id');

        if (empty($customer_id) || empty($property_id)) {
            $this->errorMsg("Please check your parameter");
            return;
        }

        $result = array();
        $check_flag = $this->Customer_Model->getThumbsUp($customer_id, $property_id)->chk_flag;
        $result['check_flag'] = (int)$check_flag;
        $count_thumbs = $this->Customer_Model->getPropertyThumbsUp($property_id)->cnt_thumbs;
        $result['count_thumbs'] = (int)$count_thumbs;

        $this->json_encode_msgs($result, 1);
        return;
    }

    /** PropertiMax - Ver_1.3.4
     * Requirements:
     * URL: https://propertimax.co.nz/Customer/setThumbsUp
     * Transmission method: POST
     * Parameters: name, email, password
     */
    public function setThumbsUp()
    {
        $customer_id = $this->input->post_get('customer_id');
        $property_id = $this->input->post_get('property_id');

        if (empty($customer_id) || empty($property_id)) {
            $this->errorMsg("Please check your parameter");
            return;
        }

        $result = array();
        $check_flag = $this->Customer_Model->getThumbsUp($customer_id, $property_id)->chk_flag;

        if ($check_flag > 0) {
            $result['check_flag'] = 0;
            $this->Customer_Model->deleteThumbsUp($customer_id, $property_id);
        } else {
            $result['check_flag'] = 1;
            $this->Customer_Model->insertThumbsUp($customer_id, $property_id);
        }

        $count_thumbs = $this->Customer_Model->getPropertyThumbsUp($property_id)->cnt_thumbs;
        $result['count_thumbs'] = (int)$count_thumbs;

        $this->json_encode_msgs($result, 1);
        return;
    }

    /** PropertiMax - Ver_1.3.4
     * Requirements:
     * URL: https://propertimax.co.nz/Customer/subNewsList
     * Transmission method: POST
     * Parameters: name, email, password
     */
    public function subNewsList()
    {
        $customer_id = $this->input->post_get('customer_id');
        $page = $this->input->post_get('page');

        if (empty($customer_id)) {
            $this->errorMsg("Please login");
            return;
        }

        $sub_info = $this->Customer_Model->getSubNews($customer_id, $page);
        $sub_count = $sub_info->num_rows();

        $result = array();
        if ($sub_count > 0) {
            for ($irow = 0; $irow < $sub_count; $irow++) {
                $tmp_data = $sub_info->row($irow);
                $result[$irow]['info'] = $tmp_data;
                $result[$irow]['images'] = $this->Customer_Model->selectAllPropertiPicList($tmp_data->property_id);
            }
        }

        $this->json_encode_msgs($result, 1);
        return;
    }

    /** PropertiMax - Ver_1.3.4
     * Requirements:
     * URL: https://propertimax.co.nz/Customer/subNewsList
     * Transmission method: POST
     * Parameters: name, email, password
     */
    public function subNewsListTest()
    {
        $customer_id = $this->input->post_get('customer_id');
        $page = $this->input->post_get('page');


        $sub_info = $this->Customer_Model->getSubNews($customer_id, $page);
        $sub_count = $sub_info->num_rows();

        $result = array();
        if ($sub_count > 0) {
            for ($irow = 0; $irow < $sub_count; $irow++) {
                $tmp_data = $sub_info->row($irow);
                $result[$irow]['info'] = $tmp_data;
                $result[$irow]['images'] = $this->Customer_Model->selectAllPropertiPicList($tmp_data->property_id);
            }
        }

        $this->json_encode_msgs($result, 1);
        return;
    }

    /** PropertiMax - Ver_1.3.4
     * Requirements:
     * URL: https://propertimax.co.nz/Customer/addMaxList
     * Transmission method: POST
     * Parameters: property_id
     * return: A1: Insert(ADD) Success, A0: Insert Fail, D1: Delete Success, D0: Delete Fail
     */
    public function addMaxList()
    {
        $logged = $this->chkLogged();

        if ($logged == 0) {
            $this->errorMsg("Please login");
            return;
        } else {
            $property_id = $this->input->post_get('property_id');

            $result = $this->Customer_Model->switchMaxList($_SESSION['SESS_CUSTOMER_ID'], $property_id);
            if ($result == false) {
                $this->errorMsg("Check the delete function.");
            } else {
                $this->json_encode_msgs($result, $logged);
            }
        }
    }

    /** PropertiMax - Ver_1.3.4
     * Requirements:
     * URL: https://propertimax.co.nz/Customer/remSearch
     * Transmission method: POST
     * Parameters: search_sale_flag(0: Sale, 1: Rent)
     */
    public function remSearch()
    {
        $logged = $this->chkLogged();

        if ($logged == 0) {
            $this->errorMsg("Please login");
            return;
        } else {
            if (!empty($this->input->post_get('search_id'))) { // Check Box
                $result = false;
                foreach ($this->input->post_get('search_id') as $search_id) {
                    $location_id = $this->Customer_Model->getLocationID($search_id);

                    if ($location_id > 0) {
                        $chk_delete = $this->Customer_Model->deleteSearchList($search_id);

                        if ($chk_delete > 0)
                            $chk_delete = $this->Customer_Model->deletePropertyType($search_id);

                        if ($chk_delete > 0)
                            $chk_delete = $this->Customer_Model->deleteSearch($search_id);

                        if ($chk_delete > 0)
                            $chk_delete = $this->Common_Model->deleteLocation($location_id);

                        if ($chk_delete == 0) {
                            $this->errorMsg("Check the delete function.");
                        }
                        $result = true;
                    }
                }
                $this->json_encode_msgs($result, $logged);
            } else {
                $this->errorMsg("Check the transmitted parameters.");
            }
        }
    }

    /** PropertiMax - Ver_1.3.4
     * Requirements:
     * URL: https://propertimax.co.nz/Customer/remSearch
     * Transmission method: POST
     * Parameters: search_sale_flag(0: Sale, 1: Rent)
     */
    public function renameSearch()
    {
        $logged = $this->chkLogged();

        if ($logged == 0) {
            $this->errorMsg("Please login");
            return;
        } else {
            if (!empty($this->input->post_get('search_id'))) { // Check Box
                $name = $this->input->post_get('search_name');
                $result = false;
                foreach ($this->input->post_get('search_id') as $search_id) {

                    $data = array(
                        'search_name' => $name
                    );
                    $this->Customer_Model->updateSaveSearch($search_id, $data);
                }
                $this->json_encode_msgs($result, $logged);
            } else {
                $this->errorMsg("Check the transmitted parameters.");
            }
        }
    }

    /** PropertiMax - Ver_1.3.4
     * Requirements:
     * URL: https://propertimax.co.nz/Customer/remAgentList
     * Transmission method: POST
     * Parameters: agent_id
     */
    public function remAgentList()
    {
        $logged = $this->chkLogged();

        if ($logged == 0) {
            $this->errorMsg("Please login");
            return;
        } else {
            if (!empty($this->input->post_get('agent_id'))) {
                $result = false;
                foreach ($this->input->post_get('agent_id') as $agent_id) {
                    $chk_agent = $this->Customer_Model->chkAgentList($_SESSION['SESS_CUSTOMER_ID'], $agent_id);

                    if ($chk_agent > 0) { // Exist Check
                        $chk_delete = $this->Customer_Model->deleteAgentList($_SESSION['SESS_CUSTOMER_ID'], $agent_id);

                        if ($chk_delete == 0) {
                            $this->errorMsg("Check the delete function.");
                        }
                        $result = true;
                    }
                }
                $this->json_encode_msgs($result, $logged);
            } else {
                $this->errorMsg("Check the transmitted parameters.");
            }
        }
    }


    /** PropertiMax - Ver_1.3.4
     * Requirements:
     * URL: https://propertimax.co.nz/Customer/updateCustomerToken
     * Transmission method: POST
     * Parameters: 
     */
    public function updateCustomerToken()
    {
        $logged = $this->chkLogged();
        $customer_id = $this->input->post_get('customer_id');
        $notification_token = $this->input->post_get('notification_token');

        if ($logged == 0) {
            $this->errorMsg("Please login");
            return;
        } else {
            $data = array(
                'notification_token' => $notification_token
            );
            $result = $this->Customer_Model->updateCustomerToken($data, $customer_id);
            $this->json_encode_msgs($result, $logged);
        }
    }

    /** PropertiMax - Ver_1.3.4
     * Requirements:
     * URL: https://propertimax.co.nz/Customer/listingsRecentlySe
     * Transmission method: POST
     * Parameters: agent_id
     */
    function listingsRecentlySe()
    {
        $logged = $this->chkLogged();
        $data = array(
            "page" => "listings"
        );

        $savesearch = "";
        $sort = "3";
        $page = "1";

        $search_sale_flag = $this->input->post_get('flagSale');
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


        $this->json_encode_msgs($result, 1);


        /* if ($this->input->post_get('labelSearch') == "") {
            $data["labelSearch"] = "All New Zealand";
        } else {
            $data["labelSearch"] = $this->input->post_get('labelSearch');
        } */
    }


    /** PropertiMax - Ver2.00
     * Requirements:
     * URL: https://propertimax.co.nz/Customer/getNotification
     * Transmission method: POST
     * Parameters:
     */

    /** PropertiMax - 2.0 API
     * Requirements:
     * URL: https://propertimax.co.nz/Common/getNotifications
     * Transmission method: POST
     * Parameters: city_id
     */
    public function getNotifications()
    { //get notic list


        $logged = $this->chkLogged();
        $page = $this->input->post_get('page');

        if ($logged == 0) {
            $this->errorMsg("Please login");
            return;
        } else {

            $customer_id = $_SESSION['SESS_CUSTOMER_ID'];
            $noticeLogList = $this->Customer_Model->getNoticeLogList($customer_id, $page); //get search option
            $result = array();
            $result_info = array();
            $datesc = array();

            if ($noticeLogList) {

                for ($i = 0; $i < count($noticeLogList); $i++) {

                    $notice_id = $noticeLogList[$i]['id'];
                    $PropertyList = $this->Customer_Model->getNoticePropertyLogList($notice_id);
                    $property_size = $PropertyList->num_rows();


                    if ($property_size > 0) {
                        for ($irow = 0; $irow < $property_size; $irow++) {
                            $Property_info = $PropertyList->result_array()[$irow];
                            $property_id = $Property_info['property_id'];

                            $line3 = "";
                            if ($Property_info['fk_suburb_id']) {
                                $line3 = $this->Common_Model->get_suburb_from_id($Property_info['fk_suburb_id'])->suburb_name;
                            }
                            if ($Property_info['property_sale_flag'] == 1) {
                                $propertyline = array(
                                    "line1" => $Property_info['property_type_name'] . " " . $this->checkprce($Property_info['property_show_price']) . " pw",
                                    "line2" => $Property_info['address'],
                                    "line3" => $line3
                                );
                            } else {
                                $propertyline = array(
                                    "line1" => $Property_info['property_title'],
                                    "line2" => $Property_info['address'],
                                    "line3" => $line3
                                );
                            }
                            $lable = NULL;

                            if ($Property_info['property_indate'] != NULL) {
                                if (date('Ymd', strtotime('-1 day')) <= date('Ymd', strtotime($Property_info['property_indate']))) {
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
                                    // $lable = date('j F Y', strtotime($Property_info['property_auction_date']));
                                    $lable = "Auction Today";
                                }
                            }

                            if ($Property_info['fk_property_status_id'] === "1") {
                                $lable = "Sold";
                            }

                            // thilan 19-05-2019 land hect to m2
                            //                if($Property_info[""]){
                            //                    
                            //                }

                            $Property_info["lable"] = $lable;
                            if ($Property_info['property_available_date'] === "0000-00-00 00:00:00") {
                                $Property_info["property_available_date"] = NULL;
                            } else {
                                $Property_info["property_available_date"] = date('D d M', strtotime($Property_info['property_available_date']));
                            }


                            $PropertiPicList = $this->Customer_Model->selectPropertiPicList($property_id);
                            $PropertiPic_size = count($PropertiPicList);

                            $logged_code = 0;
                            if ($logged_code > 0) {
                                $MaxList = $this->Customer_Model->cntMaxList($logged_code, $property_id);
                            } else {
                                $MaxList = 0;
                            }


                            $result_info['property_info'] = array_merge($propertyline, $Property_info);
                            $result_info['property_pic_size'] = $PropertiPic_size;
                            $result_info['property_pic'] = $PropertiPicList;
                            $result_info['max_list'] = $MaxList;
                            $result_info["proprty_count"] = $property_size;
                            $result_info["notice_info"] = $noticeLogList[$i];

                            array_push($result, $result_info);
                        }
                    }
                    $this->json_encode_msgs($result, 1);
                }
            } else {
                $this->errorMsg("no notice message!");
            }
        }
    }


    public function getNotificationsTest()
    { //get notic list



        $logged = $this->chkLogged();
        $page = $this->input->post_get('page');

        $customer_id = "44";
        $noticeLogList = $this->Customer_Model->getNoticeLogList($customer_id, $page); //get search option
        $result = array();
        $result_info = array();
        $datesc = array();

        if ($noticeLogList) {

            for ($i = 0; $i < count($noticeLogList); $i++) {

                $notice_id = $noticeLogList[$i]['id'];
                $PropertyList = $this->Customer_Model->getNoticePropertyLogList($notice_id);
                $property_size = $PropertyList->num_rows();


                if ($property_size > 0) {
                    for ($irow = 0; $irow < $property_size; $irow++) {
                        $Property_info = $PropertyList->result_array()[$irow];
                        $property_id = $Property_info['property_id'];

                        $line3 = "";
                        if ($Property_info['fk_suburb_id']) {
                            $line3 = $this->Common_Model->get_suburb_from_id($Property_info['fk_suburb_id'])->suburb_name;
                        }
                        if ($Property_info['property_sale_flag'] == 1) {
                            $propertyline = array(
                                "line1" => $Property_info['property_type_name'] . " " . $this->checkprce($Property_info['property_show_price']) . " pw",
                                "line2" => $Property_info['address'],
                                "line3" => $line3
                            );
                        } else {
                            $propertyline = array(
                                "line1" => $Property_info['property_title'],
                                "line2" => $Property_info['address'],
                                "line3" => $line3
                            );
                        }
                        $lable = NULL;

                        if ($Property_info['property_indate'] != NULL) {
                            if (date('Ymd', strtotime('-1 day')) <= date('Ymd', strtotime($Property_info['property_indate']))) {
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
                                // $lable = date('j F Y', strtotime($Property_info['property_auction_date']));
                                $lable = "Auction Today";
                            }
                        }

                        if ($Property_info['fk_property_status_id'] === "1") {
                            $lable = "Sold";
                        }

                        // thilan 19-05-2019 land hect to m2
                        //                if($Property_info[""]){
                        //                    
                        //                }

                        $Property_info["lable"] = $lable;
                        if ($Property_info['property_available_date'] === "0000-00-00 00:00:00") {
                            $Property_info["property_available_date"] = NULL;
                        } else {
                            $Property_info["property_available_date"] = date('D d M', strtotime($Property_info['property_available_date']));
                        }


                        $PropertiPicList = $this->Customer_Model->selectPropertiPicList($property_id);
                        $PropertiPic_size = count($PropertiPicList);

                        $logged_code = 0;
                        if ($logged_code > 0) {
                            $MaxList = $this->Customer_Model->cntMaxList($logged_code, $property_id);
                        } else {
                            $MaxList = 0;
                        }


                        $result_info['property_info'] = array_merge($propertyline, $Property_info);
                        $result_info['property_pic_size'] = $PropertiPic_size;
                        $result_info['property_pic'] = $PropertiPicList;
                        $result_info['max_list'] = $MaxList;
                        $result_info["proprty_count"] = $property_size;
                        $result_info["notice_info"] = $noticeLogList[$i];

                        array_push($result, $result_info);
                    }
                }
                $this->json_encode_msgs($result, 1);
            }
        } else {
            $this->errorMsg("no notice message!");
        }
    }


    /** PropertiMax - 2.0 API
     * Requirements:
     * URL: https://propertimax.co.nz/Customer/updateNotification
     * Transmission method: POST
     * Parameters: status,notice_id
     */
    public function updateNotification()
    {

        $status = $this->input->post_get('status');
        $notice_id = $this->input->post_get('notice_id');
        $result = $this->Customer_Model->updateNotification($status, $notice_id);
        $this->json_encode_msgs($result, 1);
    }


    public function updateAllNotification()
    {

        $logged = $this->chkLogged();

        if ($logged == 0) {
            $this->errorMsg("Please login");
            return;
        } else {
            $customer_id = $_SESSION['SESS_CUSTOMER_ID'];
            $result = $this->Customer_Model->updateAllNotification($customer_id);
            $this->json_encode_msgs($result, 1);
        }
    }

    /** PropertiMax - 2.0 API
     * Requirements:
     * URL: https://propertimax.co.nz/Customer/countNoSeenNotification
     * Transmission method: POST
     * Parameters: status,notice_id
     */
    public function countNoSeenNotification()
    {

        $logged = $this->chkLogged();

        if ($logged == 0) {
            $this->errorMsg("Please login");
            return;
        } else {
            $customer_id = $_SESSION['SESS_CUSTOMER_ID'];
            $result = $this->Customer_Model->getNewsNotices($customer_id);
            $this->json_encode_msgs($result, $logged);
        }
    }
}

// END
