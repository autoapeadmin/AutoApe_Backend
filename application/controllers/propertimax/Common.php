<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . 'libraries/Base_controller.php';

/**
 * Description of Adminpanel
 *
 * @package         CodeIgniter
 * @subpackage      PropertiMax
 * @category        Controller
 * @author          Edward An
 * @license         MIT
 */
class Common extends Base_controller {

    function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('Common_Model');
        $this->load->model('Customer_Model');
    }

    //oldAndroidFunction
    public function allRegionList1() {
        $region_list = $this->Common_Model->selectRegionList();
        $this->json_encode_msgs($region_list);
        return;
    }

    public function allSuburbListAll1() {
        $suburb_list = $this->Common_Model->selectSuburbListAll();
        $this->json_encode_msgs($suburb_list);
        return;
    }

    public function allCityListAll1() {
        $city_list = $this->Common_Model->selectCityListAll();
        $this->json_encode_msgs($city_list);
        return;
    }


    /** PropertiMax - 2.0 API
     * Requirements:
     * URL: https://propertimax.co.nz/Common/allAgencyList
     * Transmission method: POST
     * Parameters: email, password
     */
    public function allAgencyList() {
        $agency_list = $this->Common_Model->selectAgencyList();
        $this->json_encode_msgs($agency_list);
        return;
    }


    public function allPlacesList(){
        $data['region_list'] = $this->allRegionList();
        $data['suburb_list'] = $this->allSuburbListAll();
        $data['city_list'] = $this->allCityListAll();

        $this->json_encode_msgs($data);
        return $data;
    }

    /** PropertiMax - 2.0 API
     * Requirements:
     * URL: https://propertimax.co.nz/Common/allRegionList
     * Transmission method: POST
     * Parameters: 
     */
    public function allRegionList() {
        $region_list = $this->Common_Model->selectRegionList();
        return $region_list;
    }

    /** PropertiMax - 2.0 API
     * Requirements:
     * URL: https://propertimax.co.nz/Common/allCityList
     * Transmission method: POST
     * Parameters: region_id
     */
    public function allCityList() {
        $region_id = $this->input->post_get('region_id');
        $city_list = $this->Common_Model->selectCityList($region_id);
        $this->json_encode_msgs($city_list);
        return;
    }

    /** PropertiMax - 2.0 API
     * Requirements:
     * URL: https://propertimax.co.nz/Common/allSuburbList
     * Transmission method: POST
     * Parameters: city_id
     */
    public function allSuburbList() {
        $city_id = $this->input->post_get('city_id');
        $suburb_list = $this->Common_Model->selectSuburbList($city_id);
        $this->json_encode_msgs($suburb_list);
        return;
    }

    public function allSuburbListAll() {
        $suburb_list = $this->Common_Model->selectSuburbListAll();
        return $suburb_list;
    }

    public function allCityListAll() {
        $city_list = $this->Common_Model->selectCityListAll();
        return $city_list;
    }


    public function saveEmail() {
        $email_address = $this->input->post_get('email_user');
        $email_name = $this->input->post_get('name_user');
        $data = array(
            'email_address' => $email_address,
            'email_name' =>$email_name,
            'email_type' => "0"
        );

        $result = $this->Common_Model->insertUserEmail($data);
        $this->json_encode_msgs($result);
        return;
    }

    public function saveEmailA() {
        $email_address = $this->input->post_get('email_user');
        $email_name = $this->input->post_get('name_user');
        $data = array(
            'email_address' => $email_address,
            'email_name' =>$email_name,
            'email_type' => "1"
        );

        $result = $this->Common_Model->insertUserEmail($data);
        $this->json_encode_msgs($result);
        return;
    }


    /** PropertiMax - 2.0 API
     * Requirements:
     * URL: https://propertimax.co.nz/Common/allNewsList
     * Transmission method: POST
     * Parameters: city_id
     */
    public function allNewsList() {


        $suburb_list = $this->Common_Model->selectNewsList();
        for ($index = 0; $index < count($suburb_list); $index++) {
            $suburb_list[$index]["news_indate"] = date('d M Y', strtotime($suburb_list[$index]["news_indate"]));
            $suburb_list[$index]["news_update"] = date('d M Y', strtotime($suburb_list[$index]["news_update"]));
        }
        $this->json_encode_msgs($suburb_list);
        return;
    }

    /** PropertiMax - 2.0 API
     * Requirements:
     * URL: https://propertimax.co.nz/Common/targetNewsList
     * Transmission method: POST
     * Parameters: city_id
     */
    public function targetNewsList() {
        $news_id = $this->input->post_get('news_id');
        $suburb_list = $this->Common_Model->selectTargetNews($news_id);
        $suburb_list[0]["news_indate"] = date('d M Y', strtotime($suburb_list[0]["news_indate"]));
        $suburb_list[0]["news_update"] = date('d M Y', strtotime($suburb_list[0]["news_update"]));
        $this->json_encode_msgs($suburb_list);
        return;
    }

    /** PropertiMax - 2.0 API
     * Requirements:
     * URL: https://propertimax.co.nz/Common/sendNotic
     * Transmission method: POST
     * Parameters: city_id
     */
    public function sendNotice() {
        $token = $this->input->post_get("token"); //it need to define how to import and send individual smartphone tokens.
        $noticeList = $this->Common_Model->getNoticeList();
        for ($i = 0; $i < count($noticeList); $i++) {
            $data[$i] = array(
                'title' => $noticeList[$i]["title"],
                'msg' => $noticeList[$i]["message"]
            );
            //$code[$i] = $this->sendPushAlarm($token, $data[$i], false, (60 * 60 * 24 * 7));
            if ($this->sendPushAlarm("6", $noticeList[$i]["notification_token"], $data[$i], false, (60 * 60 * 24 * 7)) == 200) {
                $dataLog[$i] = array(
                    'fk_notice_id' => $noticeList[$i]["notice_id"],
                    'title' => $noticeList[$i]["title"],
                    'message' => $noticeList[$i]["message"],
                    'fk_customer_id' => $noticeList[$i]["fk_customer_id"],
                    'fk_notice_indate' => $noticeList[$i]["notice_indate"]
                );
                $result[$i] = $this->Common_Model->insertNoticeLog($dataLog[$i]);
                if ($result[$i]) {
                    $this->Common_Model->deleteNotice($noticeList[$i]["notice_id"]);
                }
                $code = 200;
            } else {
                $code = "error";
            }
            //sleep(2);
        }

        $msg = "success";
        if (count($noticeList) == 0) {
            $msg = "no notice message!";
            $code = "no data";
        }
        $this->json_encode_msgs($code, 0, $msg);
        return;
    }

    /** PropertiMax - 2.0 API
     * Requirements:
     * URL: https://propertimax.co.nz/Common/sendNoticForAuction3Day
     * Transmission method: POST
     * Parameters: city_id
     */
    public function sendNoticForAuction3Day() {
        //$token = $this->input->post_get("token");//it need to define how to import and send individual smartphone tokens.
        $date = new DateTime("now", new DateTimeZone('Pacific/Auckland'));
        $currenthour = $date->format('H');
        $currentmin = $date->format('i');
        // only validate after the time(in case of crobjob passing the time)
        //  $currenthour >= 7 && $currentmin >= 28 && $currenthour < 8
        if (TRUE) {
            $noticeList = $this->Common_Model->sendAuction3DayNoticeList();
            for ($i = 0; $i < count($noticeList); $i++) {
                $propertyInfo = $this->Common_Model->getAuction3DayNoticeList($noticeList[$i]["customer_id"], "");
                for ($j = 0; $j < count($propertyInfo); $j++) {

                    //                print_r($propertyInfo);
//                exit();
                    $dataLog[$i] = array(
                        'title' => "Don't forget - Auction in 3 days!",
                        'message' => $propertyInfo[$j]["address"] . ", " . $propertyInfo[$j]["suburb_name"],
                        'fk_customer_id' => $noticeList[$i]["customer_id"],
                        'fk_search_type' => "3days",
                        'notice_type' => "1" //1: 3day 2: one day 3: update 4: save search
                    );
                    $insertId = $this->Common_Model->insertNoticeLog($dataLog[$i]);


                    $this->Common_Model->insertPropertyLog($insertId, $propertyInfo[$j]["property_id"]);


                    $data[$i] = array(
                        'title' => "Don't forget - Auction in 3 days!",
                        'msg' => $propertyInfo[$j]["address"] . ", " . $propertyInfo[$j]["suburb_name"],
                        'img' => $propertyInfo[$j]["property_pic"],
                        'notice_id' => $insertId
                    );

                    //$code[$i] = $this->sendPushAlarm($token, $data[$i], false, (60 * 60 * 24 * 7));
                    if ($this->sendPushAlarm("1", $noticeList[$i]["notification_token"], $data[$i], false, (60 * 60 * 24 * 7)) == 200) {
                        $code = 200;
                    } else {
                        $code = "error";
                    }

                }

                //sleep(2);
            }

            $msg = "success";
            if (count($noticeList) == 0) {
                $msg = "no notice message!";
                $code = "no data";
            }
            $this->json_encode_msgs($code, 0, $msg);
        }
        return;
    }

    /** PropertiMax - 2.0 API
     * Requirements:
     * URL: https://propertimax.co.nz/Common/getNoticForAuction3Day
     * Transmission method: POST
     * Parameters: city_id
     */
    public function getNoticForAuction3Day() {
        $customerId = $this->input->post_get("customer_id"); //it need to define how to import and send individual smartphone tokens.
        $sentDate = $this->input->post_get("sent_date"); //it need to define how to import and send individual smartphone tokens.
        $noticeList = $this->Common_Model->getAuction3DayNoticeList($customerId, $sentDate);

        $this->json_encode_msgs($noticeList);
        return;
    }

    /** PropertiMax - 2.0 API
     * Requirements:
     * URL: https://propertimax.co.nz/Common/sendNoticForAuctionDay
     * Transmission method: POST
     * Parameters: city_id
     */
    public function sendNoticForAuctionDay() {
        //$token = $this->input->post_get("token");//it need to define how to import and send individual smartphone tokens.
        $date = new DateTime("now", new DateTimeZone('Pacific/Auckland'));
        $currenthour = $date->format('H');
        $currentmin = $date->format('i');
        // only validate after the time(in case of crobjob passing the time)
        // $currenthour >= 7 && $currentmin >= 28 && $currenthour < 8
        if (TRUE) {
            $noticeList = $this->Common_Model->sendAuctionDayNoticeList();
            $cnt = 0;
            for ($i = 0; $i < count($noticeList); $i++) {
                $cnt = $cnt . "," . $i;
                $propertyInfo = $this->Common_Model->getAuctionDayNoticeList($noticeList[$i]["customer_id"], "");
                for ($index = 0; $index < count($propertyInfo); $index++) {
                    $dataLog[$index] = array(
                        'title' => "Don't forget - Auction today!",
                        'message' => $propertyInfo[$index]["address"] . ", " . $propertyInfo[$index]["suburb_name"],
                        'fk_customer_id' => $noticeList[$i]["customer_id"],
                        'fk_search_type' => "today",
                        'notice_type' => "2" //1: 3day 2: one day 3: update 4: save search
                    );

                    $insertId = $this->Common_Model->insertNoticeLog($dataLog[$index]);
                    for ($j = 0; $j < count($propertyInfo); $j++) {
                        $this->Common_Model->insertPropertyLog($insertId, $propertyInfo[$j]["property_id"]);
                    }
                    $data[$index] = array(
                        'title' => "Don't forget - Auction today!",
                        'msg' => $propertyInfo[$index]["address"] . ", " . $propertyInfo[$index]["suburb_name"],
                        'img' => $propertyInfo[$index]["property_pic"],
                        'notice_id' => $insertId
                    );

                    if ($this->sendPushAlarm("2", $noticeList[$i]["notification_token"], $data[$index], false, (60 * 60 * 24 * 7)) == 200) {
                        $code = 200;
                    } else {
                        $code = "error";
                    }
                }
                //$code[$i] = $this->sendPushAlarm($token, $data[$i], false, (60 * 60 * 24 * 7));
            }

            $msg = "success";
            if (count($noticeList) == 0) {
                $msg = "no notice message!";
                $code = "no data";
            }
            $this->json_encode_msgs($code, 0, $msg);
        }
        return;
    }

    /** PropertiMax - 2.0 API
     * Requirements:
     * URL: https://propertimax.co.nz/Common/sendNoticForAuctionDay
     * Transmission method: POST
     * Parameters: city_id
     */
    public function sendNoticForAvailableDay() {
        //$token = $this->input->post_get("token");//it need to define how to import and send individual smartphone tokens.
        $date = new DateTime("now", new DateTimeZone('Pacific/Auckland'));
        $currenthour = $date->format('H');
        $currentmin = $date->format('i');
        // only validate after the time(in case of crobjob passing the time)
        // $currenthour >= 7 && $currentmin >= 28 && $currenthour < 8
        if (TRUE) {
            $noticeList = $this->Common_Model->sendAvailableDayNoticeList();
            $cnt = 0;
            for ($i = 0; $i < count($noticeList); $i++) {
                $cnt = $cnt . "," . $i;
                $propertyInfo = $this->Common_Model->getAvailableDayNoticeList($noticeList[$i]["customer_id"], "");
                for ($index = 0; $index < count($propertyInfo); $index++) {
                    $dataLog[$index] = array(
                        'title' => "Property Available",
                        'message' => $propertyInfo[$index]["address"] . ", " . $propertyInfo[$index]["suburb_name"] . " is now available.",
                        'fk_customer_id' => $noticeList[$i]["customer_id"],
                        'fk_search_type' => "today",
                        'notice_type' => "3" //1: 3day 2: one day 3: update 4: save search
                    );

                    $insertId = $this->Common_Model->insertNoticeLog($dataLog[$index]);
                    for ($j = 0; $j < count($propertyInfo); $j++) {
                        $this->Common_Model->insertPropertyLog($insertId, $propertyInfo[$j]["property_id"]);
                    }
                    $data[$index] = array(
                        'title' => "Property Available",
                        'msg' => $propertyInfo[$index]["address"] . ", " . $propertyInfo[$index]["suburb_name"]. " is now available.",
                        'img' => $propertyInfo[$index]["property_pic"],
                        'notice_id' => $insertId
                    );

                    if ($this->sendPushAlarm("2", $noticeList[$i]["notification_token"], $data[$index], false, (60 * 60 * 24 * 7)) == 200) {
                        $code = 200;
                    } else {
                        $code = "error";
                    }
                }
                //$code[$i] = $this->sendPushAlarm($token, $data[$i], false, (60 * 60 * 24 * 7));
            }

            $msg = "success";
            if (count($noticeList) == 0) {
                $msg = "no notice message!";
                $code = "no data";
            }
            $this->json_encode_msgs($code, 0, $msg);
        }
        return;
    }

    /** PropertiMax - 2.0 API
     * Requirements:
     * URL: https://propertimax.co.nz/Common/sendNoticForNewListing
     * Transmission method: POST
     * Parameters: city_id
     */
    public function sendNoticForNewListing() {
        //$token = $this->input->post_get("token");//it need to define how to import and send individual smartphone tokens.
        $date = new DateTime("now", new DateTimeZone('Pacific/Auckland'));
        $currenthour = $date->format('H');
        $currentmin = $date->format('i');
        //Get new property
        $idproperty = $this->input->post_get('idproperty'); //Session Value
        $agent_id = $this->input->post_get('agentid'); //Session Value

        //$property = $this->Customer_Model->selectTargetProperty($idproperty);
        //get notice list
        $noticeList = $this->Common_Model->sendNewListingNoticeList($agent_id);
        $propertyInfo = $this->Common_Model->getNewListNotice($idproperty);

        if (TRUE) {
            $cnt = 0;
            for ($i = 0; $i < count($noticeList); $i++) {
                $cnt = $cnt . "," . $i;
    
                for ($index = 0; $index < count($propertyInfo); $index++) {
                    $dataLog[$index] = array(
                        'title' => "New List from ".$noticeList[$i]["agent_first_name"] . " " . $noticeList[$i]["agent_last_name"],
                        'message' => $propertyInfo[$index]["address"] . ", " . $propertyInfo[$index]["suburb_name"] . " new listing.",
                        'fk_customer_id' => $noticeList[$i]["customer_id"],
                        'fk_search_type' => "today",
                        'notice_type' => "5" //1: 3day 2: one day 3: update 4: save search
                    );

                    $insertId = $this->Common_Model->insertNoticeLog($dataLog[$index]);
                    for ($j = 0; $j < count($propertyInfo); $j++) {
                        $this->Common_Model->insertPropertyLog($insertId, $propertyInfo[$j]["property_id"]);
                    }
                    $data[$index] = array(
                        'title' => "New List from ".$noticeList[$i]["agent_first_name"] . " " . $noticeList[$i]["agent_last_name"],
                        'msg' => $propertyInfo[$index]["address"] . ", " . $propertyInfo[$index]["suburb_name"]. " new listing.",
                        'img' => $propertyInfo[$index]["property_pic"],
                        'notice_id' => $insertId
                    );

                    if ($this->sendPushAlarm("2", $noticeList[$i]["notification_token"], $data[$index], false, (60 * 60 * 24 * 7)) == 200) {
                        $code = 200;
                    } else {
                        $code = "error";
                    }
                }
                //$code[$i] = $this->sendPushAlarm($token, $data[$i], false, (60 * 60 * 24 * 7));
            }

            $msg = "success";
            if (count($noticeList) == 0) {
                $msg = "no notice message!";
                $code = "no data";
            }
            $this->json_encode_msgs($code, 0, $msg);
        }
        return;
    }


    /** PropertiMax - 2.0 API
     * Requirements:
     * URL: https://propertimax.co.nz/Common/getNoticForAuctionDay
     * Transmission method: POST
     * Parameters: city_id
     */
    public function getNoticForAuctionDay() {
        $customerId = $this->input->post_get("customer_id"); //it need to define how to import and send individual smartphone tokens.
        $sentDate = $this->input->post_get("sent_date");
        $noticeList = $this->Common_Model->getAuctionDayNoticeList($customerId, $sentDate);

        $this->json_encode_msgs($noticeList);
        return;
    }

    /** PropertiMax - 2.0 API
     * Requirements:
     * URL: https://propertimax.co.nz/Common/sendNoticForAuction3Day
     * Transmission method: POST
     * Parameters: city_id
     */
    public function sendNoticForUpdate() {
        //$token = $this->input->post_get("token");//it need to define how to import and send individual smartphone tokens.
        $date = new DateTime("now", new DateTimeZone('Pacific/Auckland'));
        $currenthour = $date->format('H');
        $currentmin = $date->format('i');
        // only validate after the time(in case of crobjob passing the time)
        // $currenthour >= 7 && $currentmin >= 28 && $currenthour < 8
        if (TRUE) {
            $noticeList = $this->Common_Model->sendUpdateNoticeList();
            for ($i = 0; $i < count($noticeList); $i++) {

                $dataLog[$i] = array(
                    'title' => "Some property info changed",
                    'message' => "There are " . $noticeList[$i]["property"] . " property info changed.",
                    'fk_customer_id' => $noticeList[$i]["customer_id"],
                    'fk_search_type' => "update",
                    'notice_type' => "3" //1: 3day 2: one day 3: update 4: save search
                );
                $insertId = $this->Common_Model->insertNoticeLog($dataLog[$i]);
                $propertyInfo = $this->Common_Model->getUpdateNoticeList($noticeList[$i]["customer_id"], "");
                for ($j = 0; $j < count($propertyInfo); $j++) {
                    $this->Common_Model->insertPropertyLog($insertId, $propertyInfo[$j]["property_id"]);
                }
                $data[$i] = array(
                    'title' => "Some property info changed",
                    'msg' => "There are " . $noticeList[$i]["property"] . " property info changed.",
                    'notice_id' => $insertId
                );
                //$code[$i] = $this->sendPushAlarm($token, $data[$i], false, (60 * 60 * 24 * 7));
                if ($this->sendPushAlarm("3", $noticeList[$i]["notification_token"], $data[$i], false, (60 * 60 * 24 * 7)) == 200) {
                    $code = $insertId;
                } else {
                    $code = "error";
                }
                //sleep(2);
            }

            $msg = "success";
            if (count($noticeList) == 0) {
                $msg = "no notice message!";
                $code = "no data";
            }
            $this->json_encode_msgs($code, 0, $msg);
        }
        return;
    }

    /** PropertiMax - 2.0 API
     * Requirements:
     * URL: https://propertimax.co.nz/Common/getNoticForAuctionDay
     * Transmission method: POST
     * Parameters: city_id
     */
    public function getNoticForUpdate() {
        $customerId = $this->input->post_get("customer_id"); //it need to define how to import and send individual smartphone tokens.
        $sentDate = $this->input->post_get("sent_date");
        $noticeList = $this->Common_Model->getUpdateNoticeList($customerId, $sentDate);

        $this->json_encode_msgs($noticeList);
        return;
    }

    /** PropertiMax - 2.0 API
     * Requirements:
     * URL: https://propertimax.co.nz/Common/sendNoticForSavedW
     * Transmission method: POST
     * Parameters: city_id
     */
    public function sendNoticForSavedW() {//send notic weekly
        //$token = $this->input->post_get("token");//it need to define how to import and send individual smartphone tokens.
        $date = new DateTime("now", new DateTimeZone('Pacific/Auckland'));
        $currenthour = $date->format('H');
        $currentmin = $date->format('i');
        // only validate after the time(in case of crobjob passing the time)
        // $currenthour >= 7 && $currentmin >= 28 && $currenthour < 8
        if (TRUE) {
            $searchList = $this->Common_Model->getSavedSearchListW("", ""); //get search option 
            for ($i = 0; $i < count($searchList); $i++) {
                $result[$i] = $this->Common_Model->getSavedSearchForW($searchList[$i]["search_sale_flag"], $searchList[$i]["search_price_from"], $searchList[$i]["search_price_to"], $searchList[$i]["search_bedroom_from"], $searchList[$i]["search_bedroom_to"], $searchList[$i]["search_bathroom_from"], $searchList[$i]["search_bathroom_to"], $searchList[$i]["search_pet"], $searchList[$i]["search_open_now"], $searchList[$i]["search_available"], $searchList[$i]["fk_location_id"], $searchList[$i]["fk_city_id"], $searchList[$i]["fk_region_id"], $searchList[$i]["search_id"], "");
                if ($result[$i]) {

                    $dataLog[$i] = array(
                        'title' => "Latest rental & flatmates listings in " . $searchList[$i]["search_name"],
                        'message' => "There are " . count($result[$i]) . " property are new.",
                        'fk_customer_id' => $searchList[$i]["customer_id"],
                        'fk_search_id' => $searchList[$i]["search_id"],
                        'fk_search_type' => "weekly",
                        'notice_type' => "4" //1: 3day 2: one day 3: update 4: save search
                    );
                    $insertId = $this->Common_Model->insertNoticeLog($dataLog[$i]);

                    for ($j = 0; $j < count($result[$i]); $j++) {
                        $this->Common_Model->insertPropertyLog($insertId, $result[$i][$j]["property_id"]);
                    }

                    $data[$i] = array(
                        'title' => "Latest rental & flatmates listings in " . $searchList[$i]["search_name"],
                        'msg' => "There are " . count($result[$i]) . " property are new.",
                        'notice_id' => $insertId
                    );
                    //$code[$i] = $this->sendPushAlarm($token, $data[$i], false, (60 * 60 * 24 * 7));
                    if ($this->sendPushAlarm("4", $searchList[$i]["notification_token"], $data[$i], false, (60 * 60 * 24 * 7)) == 200) {

                        $code = 200;
                    } else {
                        $code = "error";
                    }
                }
                //sleep(2);
            }

            $msg = "success";
            if (count($searchList) == 0) {
                $msg = "no notice message!";
                $code = "no data";
            }
            $this->json_encode_msgs($code, 0, $msg);
        }
        return;
    }

    /** PropertiMax - 2.0 API
     * Requirements:
     * URL: https://propertimax.co.nz/Common/getNoticForSavedW
     * Transmission method: POST
     * Parameters: city_id
     */
    public function getNoticForSavedW() {//send notic weekly
        $customerId = $this->input->post_get("customer_id");
        $search_id = $this->input->post_get("search_id");
        $sentDate = $this->input->post_get("sent_date");
        $searchList = $this->Common_Model->getSavedSearchListW($customerId, $search_id); //get search option
        if ($searchList) {
            $result = $this->Common_Model->getSavedSearchForW($searchList[0]["search_sale_flag"], $searchList[0]["search_price_from"], $searchList[0]["search_price_to"], $searchList[0]["search_bedroom_from"], $searchList[0]["search_bedroom_to"], $searchList[0]["search_bathroom_from"], $searchList[0]["search_bathroom_to"], $searchList[0]["search_pet"], $searchList[0]["search_open_now"], $searchList[0]["search_available"], $searchList[0]["fk_location_id"], $searchList[0]["fk_city_id"], $searchList[0]["fk_region_id"], $searchList[0]["search_id"], $sentDate);
            if ($result) {
                $this->json_encode_msgs($result);
            } else {
                $msg = "no notice message!";
                $code = "no data";
                $this->json_encode_msgs($code, 0, $msg);
            }
        } else {
            $msg = "no notice message!";
            $code = "no data";
            $this->json_encode_msgs($code, 0, $msg);
        }
        return;
    }

    /** PropertiMax - 2.0 API
     * Requirements:
     * URL: https://propertimax.co.nz/Common/sendNoticForSavedF
     * Transmission method: POST
     * Parameters: city_id
     */
    public function sendNoticForSavedF() {//send notic fortnightly
        //$token = $this->input->post_get("token");//it need to define how to import and send individual smartphone tokens.
        $date = new DateTime("now", new DateTimeZone('Pacific/Auckland'));
        $currenthour = $date->format('H');
        $currentmin = $date->format('i');
        // only validate after the time(in case of crobjob passing the time)
        //  $currenthour >= 7 && $currentmin >= 28 && $currenthour < 8
        if (TRUE) {

            $searchList = $this->Common_Model->getSavedSearchListF("", ""); //get search option 
            for ($i = 0; $i < count($searchList); $i++) {

                $result[$i] = $this->Common_Model->getSavedSearchForF($searchList[$i]["search_sale_flag"], $searchList[$i]["search_price_from"], $searchList[$i]["search_price_to"], $searchList[$i]["search_bedroom_from"], $searchList[$i]["search_bedroom_to"], $searchList[$i]["search_bathroom_from"], $searchList[$i]["search_bathroom_to"], $searchList[$i]["search_pet"], $searchList[$i]["search_open_now"], $searchList[$i]["search_available"], $searchList[$i]["fk_location_id"], $searchList[$i]["fk_city_id"], $searchList[$i]["fk_region_id"], $searchList[$i]["search_id"], "");
                if ($result[$i]) {
                    $dataLog[$i] = array(
                        'title' => "Latest rental & flatmates listings in " . $searchList[$i]["search_name"],
                        'message' => "There are " . count($result[$i]) . " property are new.",
                        'fk_customer_id' => $searchList[$i]["customer_id"],
                        'fk_search_id' => $searchList[$i]["search_id"],
                        'fk_search_type' => "fortnightly",
                        'notice_type' => "4" //1: 3day 2: one day 3: update 4: save search
                    );
                    $insertId = $this->Common_Model->insertNoticeLog($dataLog[$i]);

                    for ($j = 0; $j < count($result[$i]); $j++) {
                        $this->Common_Model->insertPropertyLog($insertId, $result[$i][$j]["property_id"]);
                    }

                    $data[$i] = array(
                        'title' => "Latest rental & flatmates listings in " . $searchList[$i]["search_name"],
                        'msg' => "There are " . count($result[$i]) . " property are new.",
                        'notice_id' => $insertId
                    );
                    //$code[$i] = $this->sendPushAlarm($token, $data[$i], false, (60 * 60 * 24 * 7));
                    if ($this->sendPushAlarm("4", $searchList[$i]["notification_token"], $data[$i], false, (60 * 60 * 24 * 7)) == 200) {
                        $code = 200;
                    } else {
                        $code = "error";
                    }
                }
                //sleep(2);
            }

            $msg = "success";
            if (count($searchList) == 0) {
                $msg = "no notice message!";
                $code = "no data";
            }
            $this->json_encode_msgs($code, 0, $msg);
        }
        return;
    }

    /** PropertiMax - 2.0 API
     * Requirements:
     * URL: https://propertimax.co.nz/Common/getNoticForSavedF
     * Transmission method: POST
     * Parameters: city_id
     */
    public function getNoticForSavedF() {//send notic weekly
        $customerId = $this->input->post_get("customer_id");
        $search_id = $this->input->post_get("search_id");
        $sentDate = $this->input->post_get("sent_date");
        $searchList = $this->Common_Model->getSavedSearchListF($customerId, $search_id); //get search option
        if ($searchList) {
            $result = $this->Common_Model->getSavedSearchForF($searchList[0]["search_sale_flag"], $searchList[0]["search_price_from"], $searchList[0]["search_price_to"], $searchList[0]["search_bedroom_from"], $searchList[0]["search_bedroom_to"], $searchList[0]["search_bathroom_from"], $searchList[0]["search_bathroom_to"], $searchList[0]["search_pet"], $searchList[0]["search_open_now"], $searchList[0]["search_available"], $searchList[0]["fk_location_id"], $searchList[0]["fk_city_id"], $searchList[0]["fk_region_id"], $searchList[0]["search_id"], $sentDate);
            if ($result) {
                $this->json_encode_msgs($result);
            } else {
                $msg = "no notice message!";
                $code = "no data";
                $this->json_encode_msgs($code, 0, $msg);
            }
        } else {
            $msg = "no notice message!";
            $code = "no data";
            $this->json_encode_msgs($code, 0, $msg);
        }
        return;
    }

    /** PropertiMax - 2.0 API
     * Requirements:
     * URL: https://propertimax.co.nz/Common/sendNoticForSavedD
     * Transmission method: POST
     * Parameters: city_id
     */
    public function sendNoticForSavedD() {//send notic dayly
        //$token = $this->input->post_get("token");//it need to define how to import and send individual smartphone tokens.
        $date = new DateTime("now", new DateTimeZone('Pacific/Auckland'));
        $currenthour = $date->format('H');
        $currentmin = $date->format('i');
        // only validate after the time(in case of crobjob passing the time)
        // $currenthour >= 7 && $currentmin >= 28 && $currenthour < 8
        if (TRUE) {
            $searchList = $this->Common_Model->getSavedSearchListD("", ""); //get search option 
            ///////////////print_r($searchList);
            ////////////////exit();
            for ($i = 0; $i < count($searchList); $i++) {

                ///////////if($searchList[$i]["search_id"] == "323"){
                $result[$i] = $this->Common_Model->getSavedSearchForD($searchList[$i]["customer_id"], $searchList[$i]["search_sale_flag"], $searchList[$i]["search_price_from"], $searchList[$i]["search_price_to"], $searchList[$i]["search_bedroom_from"], $searchList[$i]["search_bedroom_to"], $searchList[$i]["search_bathroom_from"], $searchList[$i]["search_bathroom_to"], $searchList[$i]["search_pet"], $searchList[$i]["search_open_now"], $searchList[$i]["search_available"], $searchList[$i]["fk_location_id"], $searchList[$i]["fk_city_id"], $searchList[$i]["fk_region_id"], $searchList[$i]["search_id"], "");
                ///////////print_r($searchList[$i]["customer_id"]."--".$searchList[$i]["search_sale_flag"]."--".$searchList[$i]["search_id"]."--".$searchList[$i]["search_pet"]);
                if ($result[$i]) {
                    $dataLog[$i] = array(
                        'title' => "Latest rental & flatmates listings in " . $searchList[$i]["search_name"],
                        'message' => "There are " . count($result[$i]) . " property are new.",
                        'fk_customer_id' => $searchList[$i]["customer_id"],
                        'fk_search_id' => $searchList[$i]["search_id"],
                        'fk_search_type' => "dayly",
                        'notice_type' => "4" //1: 3day 2: one day 3: update 4: save search
                    );
                    $insertId = $this->Common_Model->insertNoticeLog($dataLog[$i]);

                    for ($j = 0; $j < count($result[$i]); $j++) {
                        $this->Common_Model->insertPropertyLog($insertId, $result[$i][$j]["property_id"]);
                    }

                    if (count($result[$i]) == 1) {
                        $data[$i] = array(
                            'title' => "New Listings",
                            'msg' => "You have " . count($result[$i]) . " listings matching saved search ". $searchList[$i]["search_name"]."",
                            'notice_id' => $insertId
                        );
                    } else {
                        $data[$i] = array(
                            'title' => "New Listings",
                            'msg' => "You have " . count($result[$i]) . " listings matching saved search ". $searchList[$i]["search_name"]."",
                            'notice_id' => $insertId
                        );
                    }

                    //$code[$i] = $this->sendPushAlarm($token, $data[$i], false, (60 * 60 * 24 * 7));



                    if ($this->sendPushAlarm("4", $searchList[$i]["notification_token"], $data[$i], false, (60 * 60 * 24 * 7)) == 200) {
                        $code = 200;
                    } else {
                        $code = "error";
                    }
                }
                ////////print_r($result[$i]);
                ///////exit();
                ////////}
                //sleep(2);
            }

            $msg = "success";
            if (count($searchList) == 0) {
                $msg = "no notice message!";
                $code = "no data";
            }
            $this->json_encode_msgs($code, 0, $msg);
        }
        return;
    }

    /** PropertiMax - 2.0 API
     * Requirements:
     * URL: https://propertimax.co.nz/Common/getNoticForSavedD
     * Transmission method: POST
     * Parameters: city_id
     */
    public function getNoticForSavedD() {//send notic weekly
        $customerId = $this->input->post_get("customer_id");
        $search_id = $this->input->post_get("search_id");
        $sentDate = $this->input->post_get("sent_date");
        $searchList = $this->Common_Model->getSavedSearchListD($customerId, $search_id); //get search option
        if ($searchList) {
            $result = $this->Common_Model->getSavedSearchForD($searchList[0]["search_sale_flag"], $searchList[0]["search_price_from"], $searchList[0]["search_price_to"], $searchList[0]["search_bedroom_from"], $searchList[0]["search_bedroom_to"], $searchList[0]["search_bathroom_from"], $searchList[0]["search_bathroom_to"], $searchList[0]["search_pet"], $searchList[0]["search_open_now"], $searchList[0]["search_available"], $searchList[0]["fk_location_id"], $searchList[0]["fk_city_id"], $searchList[0]["fk_region_id"], $searchList[0]["search_id"], $sentDate);
            if ($result) {
                $this->json_encode_msgs($result);
            } else {
                $msg = "no notice message!";
                $code = "no data";
                $this->json_encode_msgs($code, 0, $msg);
            }
        } else {
            $msg = "no notice message!";
            $code = "no data";
            $this->json_encode_msgs($code, 0, $msg);
        }
        return;
    }

    /** PropertiMax - Ver_1.3.4
     * Requirements:
     * Transmission method: Function Call
     * Parameters: $search(Array), $property_type(Array)
     */
    public function checkprce($price) {
        if (ctype_digit($price)) {
            return "$" . $price;
        } else {
            return $price;
        }
    }

    /** PropertiMax - 2.0 API
     * Requirements:
     * URL: https://propertimax.co.nz/Common/getNoticList
     * Transmission method: POST
     * Parameters: city_id
     */
    public function getNoticeList() {//get notic list
        //$customerId = $this->input->post_get("customer_id");
        $notice_id = $this->input->post_get("notice_id");
        $noticeLogList = $this->Common_Model->getNoticeLogList($notice_id); //get search option

        $result = array();
        $result_info = array();

        if ($noticeLogList) {
            $PropertyList = $this->Common_Model->getNoticePropertyLogList($notice_id);
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
                        "line1" => $this->checkprce($Property_info['property_show_price']),
                        "line2" => $Property_info['address'],
                        "line3" => $line3
                    );

                    $lable = NULL;


                    if ($noticeLogList[0]['notice_type'] == 3) {

                        switch ($Property_info['fk_property_status_id']) {
                            case "0":
                                $lable = "Unsold";
                                break;
                            case "1":
                                $lable = "Sold";
                                break;
                            case "2":
                                $lable = "Sold Prior";
                                break;
                            case "3":
                                $lable = "Sold Post";
                                break;
                            case "4":
                                $lable = "Withdrawn";
                                break;
                            case "5":
                                $lable = "Passed In";
                                break;
                            case "6":
                                $lable = "Postponed";
                                break;
                        }
                    } else {
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
                    }



                    $Property_info["lable"] = $lable;
                    if ($Property_info['property_available_date'] === "0000-00-00 00:00:00") {
                        $Property_info["property_available_date"] = NULL;
                    } else {
                        $Property_info["property_available_date"] = date('D d M', strtotime($Property_info['property_available_date']));
                    }





                    $PropertiPicList = $this->Customer_Model->selectPropertiPicList($property_id);
                    $PropertiPic_size = count($PropertiPicList);

                    $MaxList = $this->Customer_Model->cntMaxList($noticeLogList[0]["fk_customer_id"], $property_id);
                    if ($MaxList) {
                        
                    } else {
                        $MaxList = 0;
                    }

                    $result_info['property_info'] = array_merge($propertyline, $Property_info);
                    $result_info['property_pic_size'] = $PropertiPic_size;
                    $result_info['property_pic'] = $PropertiPicList;
                    $result_info['max_list'] = $MaxList;
                    array_push($result, $result_info);
                }
            }
            $this->json_encode_msgs($result, 1);
        } else {
            $this->errorMsg("no notice message!");
        }
        /*
          if($noticeLogList){
          $noticeList = $this->Common_Model->getNoticePropertyLogList($notice_id);
          if($noticeList){
          $this->json_encode_msgs($noticeList);
          }else{
          $this->errorMsg("no notice message!");
          }
          return;
          }else{
          $this->errorMsg("no notice message!");
          }
         */
        /*
          if($noticeLogList){
          if($noticeLogList[0]["fk_search_type"] == "3days"){
          $noticeList = $this->Common_Model->getAuction3DayNoticeLogList($noticeLogList[0]["fk_customer_id"],$noticeLogList[0]["notice_sent_date"]);
          if($noticeList){
          $this->json_encode_msgs($noticeList);
          }else{
          $this->errorMsg("no notice message!");
          }
          return;
          }else if($noticeLogList[0]["fk_search_type"] == "1day"){
          $noticeList = $this->Common_Model->getAuctionDayNoticeList($noticeLogList[0]["fk_customer_id"],$noticeLogList[0]["notice_sent_date"]);
          if($noticeList){
          $this->json_encode_msgs($noticeList);
          }else{
          $this->errorMsg("no notice message!");
          }
          return;
          }else if($noticeLogList[0]["fk_search_type"] == "update"){
          $noticeList = $this->Common_Model->getUpdateNoticeList($noticeLogList[0]["fk_customer_id"],$noticeLogList[0]["notice_sent_date"]);
          if($noticeList){
          $this->json_encode_msgs($noticeList);
          }else{
          $this->errorMsg("no notice message!");
          }
          return;
          }else if($noticeLogList[0]["fk_search_type"] == "weekly"){

          $searchList = $this->Common_Model->getSavedSearchListW($noticeLogList[0]["fk_customer_id"],$noticeLogList[0]["fk_search_id"]);//get search option
          if($searchList){
          $result = $this->Common_Model->getSavedSearchForW($searchList[0]["search_sale_flag"],$searchList[0]["search_price_from"],$searchList[0]["search_price_to"],
          $searchList[0]["search_bedroom_from"],$searchList[0]["search_bedroom_to"],$searchList[0]["search_bathroom_from"],$searchList[0]["search_bathroom_to"],
          $searchList[0]["search_pet"],$searchList[0]["search_open_now"],$searchList[0]["search_available"],$searchList[0]["fk_location_id"],
          $searchList[0]["fk_city_id"],$searchList[0]["fk_region_id"],$searchList[0]["search_id"],$noticeLogList[0]["notice_sent_date"]);
          if($result){
          $this->json_encode_msgs($result);
          }else{
          $this->errorMsg("no notice message!");
          }
          }else{
          $this->errorMsg("no notice message!");
          }
          return;
          }else if($noticeLogList[0]["fk_search_type"] == "fortnightly"){
          $searchList = $this->Common_Model->getSavedSearchListF($noticeLogList[0]["fk_customer_id"],$noticeLogList[0]["fk_search_id"]);//get search option
          if($searchList){
          $result = $this->Common_Model->getSavedSearchForF($searchList[0]["search_sale_flag"],$searchList[0]["search_price_from"],$searchList[0]["search_price_to"],
          $searchList[0]["search_bedroom_from"],$searchList[0]["search_bedroom_to"],$searchList[0]["search_bathroom_from"],$searchList[0]["search_bathroom_to"],
          $searchList[0]["search_pet"],$searchList[0]["search_open_now"],$searchList[0]["search_available"],$searchList[0]["fk_location_id"],
          $searchList[0]["fk_city_id"],$searchList[0]["fk_region_id"],$searchList[0]["search_id"],$noticeLogList[0]["notice_sent_date"]);
          if($result){
          $this->json_encode_msgs($result);
          }else{
          $this->errorMsg("no notice message!");
          }
          }else{
          $this->errorMsg("no notice message!");
          }
          return;
          }else if($noticeLogList[0]["fk_search_type"] == "dayly"){
          $searchList = $this->Common_Model->getSavedSearchListD($noticeLogList[0]["fk_customer_id"],$noticeLogList[0]["fk_search_id"]);//get search option
          if($searchList){
          $result = $this->Common_Model->getSavedSearchForD($searchList[0]["search_sale_flag"],$searchList[0]["search_price_from"],$searchList[0]["search_price_to"],
          $searchList[0]["search_bedroom_from"],$searchList[0]["search_bedroom_to"],$searchList[0]["search_bathroom_from"],$searchList[0]["search_bathroom_to"],
          $searchList[0]["search_pet"],$searchList[0]["search_open_now"],$searchList[0]["search_available"],$searchList[0]["fk_location_id"],
          $searchList[0]["fk_city_id"],$searchList[0]["fk_region_id"],$searchList[0]["search_id"],$noticeLogList[0]["notice_sent_date"]);
          if($result){
          $this->json_encode_msgs($result);
          }else{
          $this->errorMsg("no notice message!");
          }
          }else{
          $this->errorMsg("no notice message!");
          }
          return;
          }
          }else{
          $this->errorMsg("no notice message!");
          }
         */
    }

    /** PropertiMax - 2.0 API
     * Requirements:
     * URL: https://propertimax.co.nz/Common/sendPushtoCustomer
     * Transmission method: POST
     * Parameters: city_id
     */
    public function sendPushtoCustomer() {//send notic test
        $customer_id = $this->input->post_get("customer_id");

        $customerToken = $this->Common_Model->getTokenNum($customer_id);


        $data = array(
            'click_action' => ".CoreActivity",
            'title' => "send push notification test",
            'msg' => "test",
            'notice_id' => 67
        );
        $sendPush = $this->sendPushAlarm("5", $customerToken[0]["notification_token"], $data, false, (60 * 60 * 24 * 7));
        if ($sendPush == 200) {
            $this->json_encode_msgs($sendPush);
        } else {
            $this->errorMsg($sendPush);
        }
        return;
    }

    /** PropertiMax - 2.0 API
     * Requirements:
     * URL: https://propertimax.co.nz/Common/getPushNoticList
     * Transmission method: POST
     * Parameters: city_id
     */
    public function getPushNoticeList() {
        $customerId = $this->input->post_get("customer_id");
        $pushNoticeList = $this->Common_Model->getPushNoticList($customerId);

        $this->json_encode_msgs($pushNoticeList);
        return;
    }

    /** PropertiMax - 2.0 API
     * Requirements:
     * Transmission method: POST
     * Parameters:
     */
    function addNotice() {
        $title = $this->input->post_get('title');
        $message = $this->input->post_get('message');
        $customerId = $this->input->post_get('fk_customer_id');

        $data = array();
        $data['title'] = $title;
        $data['message'] = $message;
        $data['fk_customer_id'] = $customerId;

        $result = $this->Common_Model->insertNotice($data);

        $this->json_encode_msgs($result);
        return;
    }

    /** PropertiMax - 2.0 API
     * Requirements:
     * Transmission method: POST
     * Parameters:
     */
    function delNotice() {
        $noticeId = $this->input->post_get('notice_id');

        $result = $this->Common_Model->deleteNotice($noticeId);

        $this->json_encode_msgs($result);
        return;
    }

    function checkTime() {
        $date = new DateTime("now", new DateTimeZone('Pacific/Auckland'));
        $currenthour = $date->format('H');
        $currentmin = $date->format('i');
        $currentTime = $date->format('H:i');
        // only validate after the time(in case of crobjob passing the time)
        if ($currenthour >= 7 && $currentmin >= 28 && $currenthour < 8) {
            //logic here
            //echo "some logics";
        }
        // for debug
        echo "current time " . $currentTime;
        return;
    }

}

// END
