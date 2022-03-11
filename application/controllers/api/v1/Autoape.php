<?php

defined('BASEPATH') or exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/aws/aws-autoloader.php';

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Alvaro
 * @license         MIT
 */
class Autoape extends REST_Controller
{

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->library('session');
        //$this->load->library(APPPATH .'libraries/Base_controller');
        //$this->load->library('../../libraries/Base_controller');
        $this->load->model('Common_Model');
        $this->load->helper('form');
        $this->load->model('WebadminModel');
        $this->load->model('Agent_Model');
        $this->load->model('Customer_Model');
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
     * @link Commercial: https://propertimax.co.nz/api-manager/v1/account
     * Transmission method: POST
     */

    /**
     * [GET] Select / Retrieve
     */
    public function rest_get()
    {
        $res_result = $this->res_false(FALSE, 'not found');
        $this->response($res_result, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404)
    }

    public function empty_field($empty_field)
    {
        $res_result = $this->res_false("ERROR:" . $empty_field . " is empty", 'bad request');
        $this->response($res_result, REST_Controller::HTTP_BAD_REQUEST); // Errors 400
    }


    function resizeImage($source, $dest, $new_width, $new_height)
    {
        list($width, $height) = getimagesize($source);
        $image_p = imagecreatetruecolor($width, $height);
        $image = imagecreatefromjpeg($source);
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width, $height);
        imagejpeg($image_p, $dest, 100);
    }

    public function rest_xml($params)
    {
        try {

            //Get api Key
            $key = (isset($params["access_key"])) ? $params["access_key"] : $this->empty_field("access_key"); // Errors 400;
            $method = $this->uri->segments[4];
            //Validate api-key
            $branch_detail = $this->REST_Model->chkApiKey($key);
            $chk_key = $branch_detail->num_rows();

            if ($chk_key) {
                switch ($method) {
                    case 'sendListing':
                        $property_ref = $this->uniqid_base36('property'); // Gen Unique Code

                        $propertyInfo = (isset($params["property"]["vehicle_info"])) ? $params["property"]["vehicle_info"] : $this->empty_field("vehicle_info");
                        $propertyAddress = (isset($params["property"]["property_address"])) ? $params["property"]["property_address"] : $this->empty_field("property_address");
                        $propertyImages = (isset($params["property"]["property_images"]["image"])) ? $params["property"]["property_images"]["image"] : $this->empty_field("property_images");
                        $agent = (isset($params["agent"]["agent_info"])) ? $params["agent"]["agent_info"] : $this->empty_field("agent_info");

                        $branch_id = $branch_detail->row(0)->branch_id;

                        $agent_name = (isset($agent["agent_name"])) ? $agent["agent_name"] : $this->empty_field("agent_name");
                        $agent_last_name = (isset($agent["agent_last_name"])) ? $agent["agent_last_name"] : $this->empty_field("agent_last_name");
                        $agent_email = (isset($agent["agent_email"])) ? $agent["agent_email"] : $this->empty_field("agent_email");
                        $agent_img_url = (isset($agent["agent_img_url"])) ? $agent["agent_img_url"] : $this->empty_field("agent_img_url");
                        $agent_phone = (isset($agent["agent_phone"])) ? $agent["agent_phone"] : $this->empty_field("agent_phone");

                        $agent_data = $this->REST_Model->getAgentId($agent_name, $agent_last_name, $branch_id);
                        $chk_code = $agent_data->num_rows();

                        if ($chk_code) {
                            $agent_id = $agent_data->row(0)->agent_id;
                        } else {
                            //create agent
                            $agent_code = $this->uniqid_base36('agent'); // Gen Unique Code
                            $agent = array();
                            $agent['agent_code'] = $agent_code;
                            $agent['agent_first_name'] = $agent_name;
                            $agent['agent_last_name'] = $agent_last_name;
                            $agent['agent_mobile'] = $agent_phone;
                            $agent['agent_phone'] = $agent_phone;
                            $agent['agent_pic'] = $agent_img_url;
                            $agent['fk_branch_id'] = $branch_id;
                            //$agent['agent_password'] = password_hash($password, PASSWORD_DEFAULT);
                            $agent['agent_email'] = $agent_email;
                            //$agent['fk_agency_id'] = $agency_id;
                            $agent['fk_agent_title_id'] = 0;
                            //$agent['fk_brand_id'] = 0;
                            $agent['verify_flag'] = 1;

                            $agent_id = $this->Agent_Model->inserAgent($agent);
                        }

                        $property_list_type = (isset($propertyInfo["property_list_type"])) ? $propertyInfo["property_list_type"] : $this->empty_field("property_list_type");

                        if ($property_list_type == 'sale') {

                            $propertyOpenHomes = (isset($params["property_open_home"]["open_home"])) ? $params["property_open_home"]["open_home"] : "";

                            $chk_location = $this->REST_Model->chkLocation($propertyAddress["suburb"], $propertyAddress["district"]);

                            if ($chk_location->num_rows()) {

                                //Validate Location
                                $location = array();
                                $location['fk_suburb_id'] = $chk_location->row(0)->suburb_id;
                                $location['fk_city_id'] = $chk_location->row(0)->city_id;
                                $location['fk_region_id'] = $chk_location->row(0)->fk_region_id;
                                $location['address']  = (isset($propertyAddress["address"])) ? $propertyAddress["address"] : $this->empty_field("address");
                                $location['lat'] = (isset($propertyAddress["lat"])) ? $propertyAddress["lat"] : $this->empty_field("lat");
                                $location['long'] = (isset($propertyAddress["long"])) ? $propertyAddress["long"] : $this->empty_field("long");


                                $property_type = (isset($propertyInfo["property_type"])) ? $propertyInfo["property_type"] : $this->empty_field("property_type");
                                $property_title =  (isset($propertyInfo["property_title"])) ? $propertyInfo["property_title"] : $this->empty_field("property_title");
                                $property_description = (isset($propertyInfo["property_desc"])) ? $propertyInfo["property_desc"] : $this->empty_field("property_desc");
                                $property_bedroom = (isset($propertyInfo["property_bedrooms"])) ? $propertyInfo["property_bedrooms"] : "";
                                $property_bathroom = (isset($propertyInfo["property_bathrooms"])) ? $propertyInfo["property_bathrooms"] : "";
                                $property_carpark = (isset($propertyInfo["property_carpark"])) ? $propertyInfo["property_carpark"] : "";

                                $agency_ref = (isset($propertyInfo["agency_ref"])) ? $propertyInfo["agency_ref"] : "";
                                $property_floor_area = (isset($propertyInfo["property_floor_area"])) ? $propertyInfo["property_floor_area"] : "";

                                $property_land_hectarea = (isset($propertyInfo["property_land_hectarea"])) ? $propertyInfo["property_land_hectarea"] : "";
                                $property_land_square_meters = (isset($propertyInfo["property_land_square_meters"])) ? $propertyInfo["property_land_square_meters"] : "";
                                $property_sales_option =   (isset($propertyInfo["property_sales_option"])) ? $propertyInfo["property_sales_option"] : "";
                                $property_search_price = (isset($propertyInfo["property_search_price"])) ? $propertyInfo["property_search_price"] : "";
                                $property_price_option = (isset($propertyInfo["property_price_option"])) ? $propertyInfo["property_price_option"] : "";
                                $property_date_option = (isset($propertyInfo["property_date_option"])) ? $propertyInfo["property_date_option"] : "";

                                $property = array();

                                $property['property_floor_area'] = $property_floor_area;
                                $property['agency_ref'] = $agency_ref;

                                $property['property_title'] = $property_title;
                                $property['property_description'] = $property_description;
                                $property['property_sale_flag'] = "0";
                                $property['property_bedroom'] = (int) $property_bedroom;
                                $property['property_bathroom'] = (int) $property_bathroom;
                                $property['property_carpark'] = (int) $property_carpark;
                                $property['fk_property_type_id'] = (int) $property_type;
                                $property['fk_agent_id'] = $agent_id;
                                $property['property_disable'] = 0;
                                $property['property_ref'] = $property_ref;


                                if ($property_search_price > 100000) {
                                    $property['property_hidden_price'] = (int) $property_search_price;
                                } else {
                                    // Sale minimum price is $100,000
                                    $res_result = $this->res_false(false, 'bad request');
                                    $this->response($res_result, REST_Controller::HTTP_BAD_REQUEST); // Errors 400
                                }

                                $property['property_show_price'] = $property_search_price;

                                $property['price_option'] = (int) $property_sales_option;
                                $property['property_option_price'] = $property_price_option;
                                $property['property_option_date'] = date("Y-m-d", strtotime($property_date_option));

                                $property['property_land_hectare'] = $property_land_hectarea;
                                $property['property_land_meter'] = $property_land_square_meters;

                                $location_id = $this->WebadminModel->addlocaltion($location);
                                $property['fk_location_id'] = $location_id;
                                $property_id = $this->WebadminModel->addproperty($property);

                                if ($propertyImages) {
                                    $file_size = count($propertyImages);

                                    if ($file_size > 10) {
                                        $file_size = 10;
                                        // $res_result = $this->res_true(NULL, 'Up to 10 file uploads');
                                        // $this->response($res_result, REST_Controller::HTTP_OK); // OK (200)
                                    }

                                    //$path = "./images/property/";
                                    $pic_name = $this->uniqid_pic();
                                    //$picNames = array();
                                    //$picNames = $this->saveImageToPath($path, $pic_name, $_FILES, $file_name, $file_size);


                                    for ($i = 0; $i < $file_size; $i++) {
                                        $file_data = array();
                                        $image_url = (isset($propertyImages[$i]['image_url'])) ? $propertyImages[$i]['image_url'] : $this->empty_field("image_url");

                                        $this->resizeImage($image_url, "images/property/" . $property_id . $i, 1280, 720);


                                        //upload to S3
                                        $retu =  $this->saveImageInS3API("images/property/" . $property_id . $i, $property_id . $i . '.jpg');
                                        print_r($retu);

                                        $file_data['property_pic'] = "images/property/" . $property_id . $i;
                                        $file_data['fk_property_id'] = $property_id;
                                        $file_data['property_first_pic'] = $i + 1;
                                        $this->WebadminModel->addpics($file_data);
                                    }
                                }

                                if ($propertyOpenHomes) {
                                    $count = count($propertyOpenHomes);

                                    $show_timelist = array();
                                    for ($i = 0; $i < $count; $i++) {
                                        $timelist = array();

                                        $timelist['open_home_date'] = date("Y-m-d", strtotime($propertyOpenHomes[$i]['open_home_date']));
                                        $timelist['open_home_time_from'] = $propertyOpenHomes[$i]['open_home_time_from'];
                                        $timelist['open_home_time_to'] = $propertyOpenHomes[$i]['open_home_time_to'];
                                        $timelist['fk_property_id'] = (int) $property_id;
                                        $this->WebadminModel->addopenhome($timelist);
                                        $show_timelist[$i] = $timelist;
                                    }
                                }

                                //$property['openhome'] = $show_timelist;



                                $property['property_id'] = (int) $property_id;

                                $result['property_reference_code'] = $property_ref;
                                $res_result = $this->res_true($result, 'inserted the resource');
                                //$this->->sendNoticForNewListing($property_id,$agent_id);
                                $this->response($res_result, REST_Controller::HTTP_CREATED); // OK (201)

                            } else {
                                $res_result = $this->res_false(false, 'bad request');
                                $this->response($res_result, REST_Controller::HTTP_BAD_REQUEST); // Errors 400 
                            }
                        }

                        if ($propertyInfo["property_list_type"] == 'rental') {

                            $chk_location = $this->REST_Model->chkLocation($propertyAddress["suburb"], $propertyAddress["district"]);

                            if ($chk_location->num_rows()) {

                                //Validate Location
                                $location = array();
                                $location['fk_suburb_id'] = $chk_location->row(0)->suburb_id;
                                $location['fk_city_id'] = $chk_location->row(0)->city_id;
                                $location['fk_region_id'] = $chk_location->row(0)->fk_region_id;
                                $location['address']  = (isset($propertyAddress["address"])) ? $propertyAddress["address"] : $this->empty_field("address");
                                $location['lat'] = (isset($propertyAddress["lat"])) ? $propertyAddress["lat"] : $this->empty_field("lat");
                                $location['long'] = (isset($propertyAddress["long"])) ? $propertyAddress["long"] : $this->empty_field("long");

                                $property_type = (isset($propertyInfo["property_type"])) ? $propertyInfo["property_type"] : $this->empty_field("property_type");
                                $property_title =  (isset($propertyInfo["property_title"])) ? $propertyInfo["property_title"] : $this->empty_field("property_title");
                                $property_description = (isset($propertyInfo["property_desc"])) ? $propertyInfo["property_desc"] : $this->empty_field("property_desc");
                                $property_bedroom = (isset($propertyInfo["property_bedrooms"])) ? $propertyInfo["property_bedrooms"] : "";
                                $property_bathroom = (isset($propertyInfo["property_bathrooms"])) ? $propertyInfo["property_bathrooms"] : "";
                                $property_carpark = (isset($propertyInfo["property_carpark"])) ? $propertyInfo["property_carpark"] : "";
                                $property_land_hectarea = (isset($propertyInfo["property_land_hectarea"])) ? $propertyInfo["property_land_hectarea"] : "";
                                $property_land_square_meters = (isset($propertyInfo["property_land_square_meters"])) ? $propertyInfo["property_land_square_meters"] : "";
                                $property_sales_option =   (isset($propertyInfo["property_sales_option"])) ? $propertyInfo["property_sales_option"] : "";
                                $property_search_price = (isset($propertyInfo["property_search_price"])) ? $propertyInfo["property_search_price"] : "";
                                $property_price_option = (isset($propertyInfo["property_price_option"])) ? $propertyInfo["property_price_option"] : "";
                                $property_date_option = (isset($propertyInfo["property_date_option"])) ? $propertyInfo["property_date_option"] : "";
                                $agency_ref = (isset($propertyInfo["agency_ref"])) ? $propertyInfo["agency_ref"] : "";
                                $property_floor_area = (isset($propertyInfo["property_floor_area"])) ? $propertyInfo["property_floor_area"] : "";
                                //rental
                                $property_pet = (isset($propertyInfo["property_pet"])) ? $propertyInfo["property_pet"] : "";
                                $property_available_date = (isset($propertyInfo["property_available_date"])) ? $propertyInfo["property_available_date"] : "";

                                $property = array();

                                $property['property_floor_area'] = $property_floor_area;
                                $property['agency_ref'] = $agency_ref;
                                $property['property_title'] = $property_title;
                                $property['property_description'] = $property_description;
                                $property['property_sale_flag'] = "1";
                                $property['property_bedroom'] = (int) $property_bedroom;
                                $property['property_bathroom'] = (int) $property_bathroom;
                                $property['property_carpark'] = (int) $property_carpark;
                                $property['fk_property_type_id'] = (int) $property_type;
                                $property['fk_agent_id'] = $agent_id;
                                $property['property_disable'] = 0;
                                $property['property_ref'] = $property_ref;

                                if ($property_search_price > 80) {
                                    $property['property_hidden_price'] = (int) $property_search_price;
                                } else {
                                    // Rent minimum price is $80
                                    $res_result = $this->res_false(false, 'bad request');
                                    $this->response($res_result, REST_Controller::HTTP_BAD_REQUEST); // Errors 400
                                }



                                $property['property_show_price'] = $property_search_price;
                                $property['price_option'] = (int) $property_price_option;
                                $property['property_land_hectare'] = $property_land_hectarea;
                                $property['property_land_meter'] = $property_land_square_meters;
                                $property['property_pet'] = (int) $property_pet;
                                $property['property_available_date'] = $property_available_date;

                                $location_id = $this->WebadminModel->addlocaltion($location);
                                $property['fk_location_id'] = $location_id;
                                $property_id = $this->WebadminModel->addproperty($property);

                                if ($propertyImages) {
                                    $file_size = count($propertyImages);

                                    if ($file_size > 10) {
                                        $file_size = 10;
                                    }


                                    for ($i = 0; $i < $file_size; $i++) {
                                        $file_data = array();
                                        $image_url = (isset($propertyImages[$i]['image_url'])) ? $propertyImages[$i]['image_url'] : $this->empty_field("image_url");

                                        $this->resizeImage($image_url, "images/property/" . $property_id . $i, 1280, 720);
                                        $file_data['property_pic'] = "images/property/" . $property_id . $i;
                                        $file_data['fk_property_id'] = $property_id;
                                        $file_data['property_first_pic'] = $i + 1;
                                        $this->WebadminModel->addpics($file_data);
                                    }
                                }


                                $result['property_reference_code'] = $property_ref;

                                $res_result = $this->res_true($result, 'Property inserted');
                                $this->response($res_result, REST_Controller::HTTP_CREATED); // OK (201)

                            } else {
                                $res_result = $this->res_false(false, 'bad request');
                                $this->response($res_result, REST_Controller::HTTP_BAD_REQUEST); // Errors 400
                            }
                        }

                        break;

                    case 'updateListing':

                        $propertyRef = (isset($params["property_ref"])) ? $params["property_ref"] : "";

                        $property_detail = $this->REST_Model->chkProperty($propertyRef);

                        $propertyInfo = (isset($params["property"]["vehicle_info"])) ? $params["property"]["vehicle_info"] : $this->empty_field("vehicle_info");
                        $propertyAddress = (isset($params["property"]["property_address"])) ? $params["property"]["property_address"] : $this->empty_field("property_address");
                        $propertyImages = (isset($params["property"]["property_images"]["image"])) ? $params["property"]["property_images"]["image"] : $this->empty_field("property_images");
                        //$this->response($property_detail->row(0), REST_Controller::HTTP_BAD_REQUEST); // Errors 400
                        $chk_location = $this->REST_Model->chkLocation($propertyAddress["suburb"], $propertyAddress["district"]);

                        if (!empty($propertyRef) && $property_detail->num_rows() && $chk_location->num_rows()) {

                            //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++UPDATE START   
                            $propertyOpenHomes = (isset($params["property_open_home"]["open_home"])) ? $params["property_open_home"]["open_home"] : "";
                            //Validate Location
                            $location = array();
                            $location['fk_suburb_id'] = $chk_location->row(0)->suburb_id;
                            $location['fk_city_id'] = $chk_location->row(0)->city_id;
                            $location['fk_region_id'] = $chk_location->row(0)->fk_region_id;
                            $location['address']  = (isset($propertyAddress["address"])) ? $propertyAddress["address"] : $this->empty_field("address");
                            $location['lat'] = (isset($propertyAddress["lat"])) ? $propertyAddress["lat"] : $this->empty_field("lat");
                            $location['long'] = (isset($propertyAddress["long"])) ? $propertyAddress["long"] : $this->empty_field("long");

                            $property_type = (isset($propertyInfo["property_type"])) ? $propertyInfo["property_type"] : $this->empty_field("property_type");
                            $property_title =  (isset($propertyInfo["property_title"])) ? $propertyInfo["property_title"] : $this->empty_field("property_title");
                            $property_description = (isset($propertyInfo["property_desc"])) ? $propertyInfo["property_desc"] : $this->empty_field("property_desc");
                            $property_bedroom = (isset($propertyInfo["property_bedrooms"])) ? $propertyInfo["property_bedrooms"] : "";
                            $property_bathroom = (isset($propertyInfo["property_bathrooms"])) ? $propertyInfo["property_bathrooms"] : "";
                            $property_carpark = (isset($propertyInfo["property_carpark"])) ? $propertyInfo["property_carpark"] : "";

                            $agency_ref = (isset($propertyInfo["agency_ref"])) ? $propertyInfo["agency_ref"] : "";
                            $property_floor_area = (isset($propertyInfo["property_floor_area"])) ? $propertyInfo["property_floor_area"] : "";

                            $property_land_hectarea = (isset($propertyInfo["property_land_hectarea"])) ? $propertyInfo["property_land_hectarea"] : "";
                            $property_land_square_meters = (isset($propertyInfo["property_land_square_meters"])) ? $propertyInfo["property_land_square_meters"] : "";
                            $property_sales_option =   (isset($propertyInfo["property_sales_option"])) ? $propertyInfo["property_sales_option"] : "";
                            $property_search_price = (isset($propertyInfo["property_search_price"])) ? $propertyInfo["property_search_price"] : "";
                            $property_price_option = (isset($propertyInfo["property_price_option"])) ? $propertyInfo["property_price_option"] : "";
                            $property_date_option = (isset($propertyInfo["property_date_option"])) ? $propertyInfo["property_date_option"] : "";

                            //rental
                            $property_pet = (isset($propertyInfo["property_pet"])) ? $propertyInfo["property_prproperty_petice_option"] : "";
                            $property_available_date = (isset($propertyInfo["property_available_date"])) ? $propertyInfo["property_available_date"] : "";


                            $property = array();
                            $property['property_floor_area'] = $property_floor_area;
                            $property['agency_ref'] = $agency_ref;
                            $property['property_title'] = $property_title;
                            $property['property_description'] = $property_description;
                            $property['property_sale_flag'] = "0";
                            $property['property_bedroom'] = (int) $property_bedroom;
                            $property['property_bathroom'] = (int) $property_bathroom;
                            $property['property_carpark'] = (int) $property_carpark;
                            $property['fk_property_type_id'] = (int) $property_type;
                            $property['fk_agent_id'] = $property_detail->row(0)->fk_agent_id;
                            $property['property_disable'] = 0;
                            $property['property_ref'] = $propertyRef;



                            if ($property_search_price > 100000) {
                                $property['property_hidden_price'] = (int) $property_search_price;
                            } else {
                                // Sale minimum price is $100,000
                                $res_result = $this->res_false(false, 'bad request');
                                $this->response($res_result, REST_Controller::HTTP_BAD_REQUEST); // Errors 400
                            }

                            $property['property_show_price'] = $property_search_price;
                            $property['price_option'] = (int) $property_sales_option;
                            $property['property_option_price'] = $property_price_option;
                            $property['property_option_date'] = date("Y-m-d", strtotime($property_date_option));
                            $property['property_land_hectare'] = $property_land_hectarea;
                            $property['property_land_meter'] = $property_land_square_meters;
                            $property['property_pet'] = (int) $property_pet;
                            $property['property_available_date'] = $property_available_date;


                            //$this->response($property['property_option_date'] , REST_Controller::HTTP_BAD_REQUEST); // Errors 400

                            $this->WebadminModel->updateLocation($location, $property_detail->row(0)->fk_location_id);
                            $property['fk_location_id'] = $property_detail->row(0)->fk_location_id;
                            $this->WebadminModel->updateProperty($property, $property_detail->row(0)->property_id);
                            $property_id = $property_detail->row(0)->property_id;

                            if ($propertyImages) {
                                $file_size = count($propertyImages);

                                if ($file_size > 10) {
                                    $file_size = 10;
                                }

                                for ($i = 0; $i < $file_size; $i++) {
                                    $file_data = array();
                                    $image_url = (isset($propertyImages[$i]['image_url'])) ? $propertyImages[$i]['image_url'] : $this->empty_field("image_url");

                                    $this->resizeImage($image_url, "images/property/" . $property_id . $i, 1280, 720);
                                    $file_data['property_pic'] = "images/property/" . $property_id . $i;
                                    $file_data['fk_property_id'] = $property_id;
                                    $file_data['property_first_pic'] = $i + 1;
                                    $this->WebadminModel->updatePic($file_data);
                                }
                            }

                            if ($propertyOpenHomes) {
                                $count = count($propertyOpenHomes);
                                //updateopenhomes
                                $this->WebadminModel->updateAllOpenHome($property_id);
                                $show_timelist = array();
                                for ($i = 0; $i < $count; $i++) {
                                    $timelist = array();
                                    $timelist['open_home_date'] = date("Y-m-d", strtotime($propertyOpenHomes[$i]['open_home_date']));
                                    $timelist['open_home_time_from'] = $propertyOpenHomes[$i]['open_home_time_from'];
                                    $timelist['open_home_time_to'] = $propertyOpenHomes[$i]['open_home_time_to'];
                                    $timelist['fk_property_id'] = (int) $property_id;
                                    $this->WebadminModel->addopenhome($timelist);
                                    $show_timelist[$i] = $timelist;
                                }
                            }

                            //$property['openhome'] = $show_timelist;



                            $property['property_id'] = (int) $property_id;

                            unset($property['property_agency_id']);
                            unset($property['property_disable']);
                            unset($property['fk_agent_id']);
                            unset($property['fk_location_id']);
                            $res_result = $this->res_true(true, 'Property updated');
                            //$this->->sendNoticForNewListing($property_id,$agent_id);
                            $this->response($res_result, REST_Controller::HTTP_CREATED); // OK (201)


                            break;

                            //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++UPDATE END

                            $this->response($propertyRef, REST_Controller::HTTP_CREATED); // OK (201)
                        } else {
                            $res_result = $this->res_false("property_ref error", 'bad request');
                            $this->response($res_result, REST_Controller::HTTP_CREATED); // OK (201)
                        }
                        break;

                    case 'removeListing':
                        $propertyRef = (isset($params["property_ref"])) ? $params["property_ref"] : $this->empty_field("property_ref");

                        if (!empty($propertyRef)) {
                            $this->WebadminModel->delPropertyByReference($propertyRef);
                            $res_result = $this->res_true(true, 'Property removed');
                            $this->response($res_result, REST_Controller::HTTP_CREATED); // OK (201)
                            break;
                        } else {
                            $res_result = $this->res_false(false, 'bad request');
                            $this->response($res_result, REST_Controller::HTTP_BAD_REQUEST); // Errors 400
                        }

                    case 'updateStatusListing': {
                            $propertyRef = (isset($params["property_ref"])) ? $params["property_ref"] : $this->empty_field("property_ref");
                            $status['fk_property_status_id'] = (isset($params["status"])) ? $params["status"] : $this->empty_field("status");

                            if (!empty($propertyRef)) {
                                $this->WebadminModel->updateStatusHome($status, $propertyRef);
                                $res_result = $this->res_true(true, 'Property updated');
                                $this->response($res_result, REST_Controller::HTTP_CREATED); // OK (201)
                                break;
                            } else {
                                $res_result = $this->res_false(false, 'bad request');
                                $this->response($res_result, REST_Controller::HTTP_BAD_REQUEST); // Errors 400
                            }
                        }
                }
            }


            //code...
        } catch (Exception $e) {
            $this->response("cath", REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404)
        }
    }

    public function countTotalMonthly($id)
    {
        $total = $this->REST_Model->selectVehicleByDealershipMonthly($id);
        return $total[0]->total;
    }

    /**
     * [POST] Insert / Create
     */
    public function rest_post()
    {
        try {
            //getParams
            $input = (array) json_decode(file_get_contents('php://input'), TRUE);
            if ($input) {
                $params = $input;
            } else {
                $params2 = file_get_contents('php://input');
                $xml = simplexml_load_string($params2);
                $json = json_encode($xml);
                $params = (array) json_decode($json, TRUE);
                $this->rest_xml($params);
            }

            //Get api Key
            $key = (isset($params["access_key"])) ? $params["access_key"] : $this->empty_field("access_key"); // Errors 400;
            $method = $this->uri->segments[4];
            //Validate api-key
            $branch_detail = $this->REST_Model->chkApiKey($key);
            $chk_key = $branch_detail->num_rows();
            $dealership_id = $branch_detail->row(0)->fk_branch_id;

            //*************************** Validate total subscription
            $subsFound = $this->REST_Model->selectSubscriptionByDealership($dealership_id);
            $totalMonth = $this->countTotalMonthly($dealership_id);
            if ($subsFound[0]->max_listing_monthly <= $totalMonth) {
                $res_result = $this->res_false($totalMonth, 'Maximun per month reached');
                $this->response($res_result, REST_Controller::HTTP_BAD_REQUEST); // OK (201)
            }
            //*************************************************** */

            if ($chk_key) {

                //Default Values
                $region_id = $branch_detail->row(0)->fk_region_id;
                $vehicle_info = (isset($params["vehicle_info"])) ? $params["vehicle_info"][0] : $this->empty_field("vehicle_info");
                $vehicle['fk_vehicule_type'] = (isset($vehicle_info["vehicle_type"])) ? $vehicle_info["vehicle_type"] : $this->empty_field("vehicle_type");
                $vehicle['fk_customer'] = 0;
                $vehicle['fk_listing_type'] = 2;
                $vehicle['fk_region'] = $region_id;
                $vehicle['fk_dealership_id'] = $dealership_id;
                $vehicleImages = (isset($params["vehicle_images"])) ? $params["vehicle_images"] : [];

                switch ($method) {

                    case 'addListing':

                        $vehicle['vehicule_rego'] = (isset($vehicle_info["vehicle_rego"])) ? $vehicle_info["vehicle_rego"] : $this->empty_field("vehicle_rego");
                        $make = (isset($vehicle_info["vehicle_make"])) ? $vehicle_info["vehicle_make"] : $this->empty_field("vehicle_make");
                        $make_found = $this->REST_Model->findMake($make);
                        $make_id = $make_found->row(0)->make_id;
                        $vehicle['vehicule_year'] = (isset($vehicle_info["vehicle_year"])) ? $vehicle_info["vehicle_year"] : $this->empty_field("vehicle_year");
                        $vehicle['vehicule_odometer'] = (isset($vehicle_info["vehicle_odometer"])) ? $vehicle_info["vehicle_odometer"] : $this->empty_field("vehicle_odometer");
                        $vehicle['vehicule_transmission'] = (isset($vehicle_info["vehicle_transmission"])) ? $vehicle_info["vehicle_transmission"] : $this->empty_field("vehicle_transmission");
                        $vehicle['vehicule_engine'] = (isset($vehicle_info["vehicle_engine_size"])) ? $vehicle_info["vehicle_engine_size"] : $this->empty_field("vehicle_engine_size");
                        $vehicle['vehicule_price'] = (isset($vehicle_info["vehicle_price"])) ? $vehicle_info["vehicle_price"] : $this->empty_field("vehicle_price");
                        $vehicle['vehicule_desc'] = (isset($vehicle_info["vehicle_desc"])) ? $vehicle_info["vehicle_desc"] : $this->empty_field("vehicle_desc");
                        $vehicle['external_ref_number'] = (isset($vehicle_info["ref_number"])) ? $vehicle_info["ref_number"] : $this->empty_field("ref_number");
                        if ($make_id != null) {
                            $vehicle['fk_vehicule_make'] = $make_id;
                        } else {
                            $res_result = $this->res_false($make, 'Make not Found.');
                            $this->response($res_result, REST_Controller::HTTP_BAD_REQUEST); // OK (201)
                        }

                        //extra info if it is car
                        if ($vehicle['fk_vehicule_type'] == 0) {
                            $vehicle['fk_vehicule_body_id'] = (isset($vehicle_info["vehicle_body_type"])) ? $vehicle_info["vehicle_body_type"] : $this->empty_field("vehicle_body_type");
                            $vehicle['fk_vehicule_fuel'] = (isset($vehicle_info["vehicle_fuel"])) ? $vehicle_info["vehicle_fuel"] : $this->empty_field("vehicle_fuel");
                            $model = (isset($vehicle_info["vehicle_model"])) ? $vehicle_info["vehicle_model"] : $this->empty_field("vehicle_model");
                            $vehicle['vehicule_4x4'] = (isset($vehicle_info["vehicle_4x4"])) ? $vehicle_info["vehicle_4x4"] : $this->empty_field("vehicle_4x4");
                            $model_found = $this->REST_Model->findModel($make_id, $model);
                            $model_id = $model_found->row(0)->model_id;
                            if ($model_id != null) {
                                $vehicle['fk_vehicule_model'] = $model_id;
                            } else {
                                $res_result = $this->res_false($model, 'Model not Found.');
                                $this->response($res_result, REST_Controller::HTTP_BAD_REQUEST); // OK (201)
                            }
                        }

                        //save vehicle
                        $vehicle_id = $this->REST_Model->insertVehicule($vehicle);


                        //save images
                        if ($vehicleImages) {
                            $file_size = count($vehicleImages);
                            if ($file_size > 15) {
                                $file_size = 15;
                            }

                            $vehicleImages = array_reverse($vehicleImages);

                            for ($i = 0; $i < $file_size; $i++) {
                                $file_data = array();
                                $image_url = (isset($vehicleImages[$i]['image_url'])) ? $vehicleImages[$i]['image_url'] : $this->empty_field("image_url");

                                $this->resizeImage($image_url, "images/vehicle/" . $dealership_id . $i, 1280, 720);

                                //upload to S3
                                $retu =  $this->uploadImageMaxAutoDealership("images/vehicle/" . $dealership_id . $i, $dealership_id . $i . '.png');
                                print_r($retu);

                                $file_data['pic_url'] =  $retu;
                                $file_data['fk_vehicule_id'] = $vehicle_id;
                                $file_data['number'] = $i + 1;
                                $this->REST_Model->insertPhoto($file_data);
                            }
                        }

                        $result['vehicle_reference_code'] = $vehicle_id;
                        $res_result = $this->res_true($result, 'inserted the resource');
                        //$this->sendNoticForNewListing($property_id,$agent_id);
                        $this->response($res_result, REST_Controller::HTTP_CREATED); // OK (201
                        break;

                    case 'updateListing':

                        $propertyRef = (isset($params["property_ref"])) ? $params["property_ref"] : "";
                        $property_detail = $this->REST_Model->chkProperty($propertyRef);
                        $propertyInfo = (isset($vehicle_info["property_list_type"])) ? $vehicle_info["property_list_type"] : $this->empty_field("property_list_type");
                        $propertyAddress = (isset($params["property_address"])) ? $params["property_address"][0] : $this->empty_field("property_address");
                        $propertyImages = (isset($params["property_images"])) ? $params["property_images"] : $this->empty_field("property_images");
                        //$this->response($property_detail->row(0), REST_Controller::HTTP_BAD_REQUEST); // Errors 400
                        $chk_location = $this->REST_Model->chkLocation($propertyAddress["suburb"], $propertyAddress["district"]);

                        if (!empty($propertyRef) && $property_detail->num_rows() && $chk_location->num_rows()) {

                            //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++UPDATE START   
                            $propertyOpenHomes = (isset($params["property_open_home"])) ? $params["property_open_home"] : "";
                            //Validate Location
                            $location = array();
                            $location['fk_suburb_id'] = $chk_location->row(0)->suburb_id;
                            $location['fk_city_id'] = $chk_location->row(0)->city_id;
                            $location['fk_region_id'] = $chk_location->row(0)->fk_region_id;
                            $location['address']  = (isset($propertyAddress["address"])) ? $propertyAddress["address"] : $this->empty_field("address");
                            $location['lat'] = (isset($propertyAddress["lat"])) ? $propertyAddress["lat"] : $this->empty_field("lat");
                            $location['long'] = (isset($propertyAddress["long"])) ? $propertyAddress["long"] : $this->empty_field("long");

                            $property_type = (isset($propertyInfo["property_type"])) ? $propertyInfo["property_type"] : $this->empty_field("property_type");
                            $property_title =  (isset($propertyInfo["property_title"])) ? $propertyInfo["property_title"] : $this->empty_field("property_title");
                            $property_description = (isset($propertyInfo["property_desc"])) ? $propertyInfo["property_desc"] : $this->empty_field("property_desc");
                            $property_bedroom = (isset($propertyInfo["property_bedrooms"])) ? $propertyInfo["property_bedrooms"] : "";
                            $property_bathroom = (isset($propertyInfo["property_bathrooms"])) ? $propertyInfo["property_bathrooms"] : "";
                            $property_carpark = (isset($propertyInfo["property_carpark"])) ? $propertyInfo["property_carpark"] : "";

                            $agency_ref = (isset($propertyInfo["agency_ref"])) ? $propertyInfo["agency_ref"] : "";
                            $property_floor_area = (isset($propertyInfo["property_floor_area"])) ? $propertyInfo["property_floor_area"] : "";

                            $property_land_hectarea = (isset($propertyInfo["property_land_hectarea"])) ? $propertyInfo["property_land_hectarea"] : "";
                            $property_land_square_meters = (isset($propertyInfo["property_land_square_meters"])) ? $propertyInfo["property_land_square_meters"] : "";
                            $property_sales_option =   (isset($propertyInfo["property_sales_option"])) ? $propertyInfo["property_sales_option"] : "";
                            $property_search_price = (isset($propertyInfo["property_search_price"])) ? $propertyInfo["property_search_price"] : "";
                            $property_price_option = (isset($propertyInfo["property_price_option"])) ? $propertyInfo["property_price_option"] : "";
                            $property_date_option = (isset($propertyInfo["property_date_option"])) ? $propertyInfo["property_date_option"] : "";

                            //rental
                            $property_pet = (isset($propertyInfo["property_pet"])) ? $propertyInfo["property_prproperty_petice_option"] : "";
                            $property_available_date = (isset($propertyInfo["property_available_date"])) ? $propertyInfo["property_available_date"] : "";


                            $property = array();
                            $property['property_floor_area'] = $property_floor_area;
                            $property['agency_ref'] = $agency_ref;
                            $property['property_title'] = $property_title;
                            $property['property_description'] = $property_description;
                            $property['property_sale_flag'] = "0";
                            $property['property_bedroom'] = (int) $property_bedroom;
                            $property['property_bathroom'] = (int) $property_bathroom;
                            $property['property_carpark'] = (int) $property_carpark;
                            $property['fk_property_type_id'] = (int) $property_type;
                            $property['fk_agent_id'] = $property_detail->row(0)->fk_agent_id;
                            $property['property_disable'] = 0;
                            $property['property_ref'] = $propertyRef;

                            if ($property_search_price > 100000) {
                                $property['property_hidden_price'] = (int) $property_search_price;
                            } else {
                                // Sale minimum price is $100,000
                                $res_result = $this->res_false(false, 'bad request');
                                $this->response($res_result, REST_Controller::HTTP_BAD_REQUEST); // Errors 400
                            }

                            $property['property_show_price'] = $property_search_price;
                            $property['price_option'] = (int) $property_sales_option;
                            $property['property_option_price'] = $property_price_option;
                            $property['property_option_date'] = date("Y-m-d", strtotime($property_date_option));
                            $property['property_land_hectare'] = $property_land_hectarea;
                            $property['property_land_meter'] = $property_land_square_meters;
                            $property['property_pet'] = (int) $property_pet;
                            $property['property_available_date'] = $property_available_date;


                            //$this->response($property['property_option_date'] , REST_Controller::HTTP_BAD_REQUEST); // Errors 400

                            $this->WebadminModel->updateLocation($location, $property_detail->row(0)->fk_location_id);
                            $property['fk_location_id'] = $property_detail->row(0)->fk_location_id;
                            $this->WebadminModel->updateProperty($property, $property_detail->row(0)->property_id);
                            $property_id = $property_detail->row(0)->property_id;

                            if ($propertyImages) {
                                $file_size = count($propertyImages);

                                if ($file_size > 10) {
                                    $file_size = 10;
                                }

                                for ($i = 0; $i < $file_size; $i++) {
                                    $file_data = array();
                                    $image_url = (isset($propertyImages[$i]['image_url'])) ? $propertyImages[$i]['image_url'] : $this->empty_field("image_url");

                                    $this->resizeImage($image_url, "images/property/" . $property_id . $i, 1280, 720);
                                    $file_data['property_pic'] = "images/property/" . $property_id . $i;
                                    $file_data['fk_property_id'] = $property_id;
                                    $file_data['property_first_pic'] = $i + 1;
                                    $this->WebadminModel->updatePic($file_data);
                                }
                            }

                            if ($propertyOpenHomes) {
                                $count = count($propertyOpenHomes);
                                //updateopenhomes
                                $this->WebadminModel->updateAllOpenHome($property_id);
                                $show_timelist = array();
                                for ($i = 0; $i < $count; $i++) {
                                    $timelist = array();
                                    $timelist['open_home_date'] = date("Y-m-d", strtotime($propertyOpenHomes[$i]['open_home_date']));
                                    $timelist['open_home_time_from'] = $propertyOpenHomes[$i]['open_home_time_from'];
                                    $timelist['open_home_time_to'] = $propertyOpenHomes[$i]['open_home_time_to'];
                                    $timelist['fk_property_id'] = (int) $property_id;
                                    $this->WebadminModel->addopenhome($timelist);
                                    $show_timelist[$i] = $timelist;
                                }
                            }

                            //$property['openhome'] = $show_timelist;

                            $property['property_id'] = (int) $property_id;

                            unset($property['property_agency_id']);
                            unset($property['property_disable']);
                            unset($property['fk_agent_id']);
                            unset($property['fk_location_id']);
                            $res_result = $this->res_true(true, 'Property removed');
                            //$this->->sendNoticForNewListing($property_id,$agent_id);
                            $this->response($res_result, REST_Controller::HTTP_CREATED); // OK (201)
                            break;

                            //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++UPDATE END

                            $this->response($propertyRef, REST_Controller::HTTP_CREATED); // OK (201)
                        } else {
                            $res_result = $this->res_false("property_ref error", 'bad request');
                            $this->response($res_result, REST_Controller::HTTP_CREATED); // OK (201)
                        }
                        break;

                    case 'removeListing':
                        $propertyRef = (isset($params["property_ref"])) ? $params["property_ref"] : $this->empty_field("property_ref");

                        if (!empty($propertyRef)) {
                            $this->WebadminModel->delPropertyByReference($propertyRef);
                            $res_result = $this->res_true(true, 'Property removed');
                            $this->response($res_result, REST_Controller::HTTP_CREATED); // OK (201)
                            break;
                        } else {
                            $res_result = $this->res_false(false, 'bad request');
                            $this->response($res_result, REST_Controller::HTTP_BAD_REQUEST); // Errors 400
                        }

                    case 'updateStatusListing': {
                            $propertyRef = (isset($params["property_ref"])) ? $params["property_ref"] : $this->empty_field("property_ref");
                            $status['fk_property_status_id'] = (isset($params["status"])) ? $params["status"] : $this->empty_field("status");

                            if (!empty($propertyRef)) {
                                $this->WebadminModel->updateStatusHome($status, $propertyRef);
                                $res_result = $this->res_true(true, 'Property updated');
                                $this->response($res_result, REST_Controller::HTTP_CREATED); // OK (201)
                                break;
                            } else {
                                $res_result = $this->res_false(false, 'bad request');
                                $this->response($res_result, REST_Controller::HTTP_BAD_REQUEST); // Errors 400
                            }
                        }
                }
            } else {
                $res_result = $this->res_true('API Key not found', 'Error');

                $this->response($res_result, REST_Controller::HTTP_UNAUTHORIZED); // OK (201
            }

            //code...
        } catch (Exception $e) {
            $this->response("cath", REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404)
        }
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
        $res_result = $this->res_false(FALSE, 'aca');
        $this->response($res_result, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404)
    }

    function random_string($length)
    {
        $key = '';
        $keys = array_merge(range(0, 9), range('a', 'z'));

        for ($i = 0; $i < $length; $i++) {
            $key .= $keys[array_rand($keys)];
        }

        return $key;
    }



    public function uploadImageMaxAutoDealership($tmp_file)
    {
        //*****************************CODIGO PARA CARGAR IMAGENES S3**************************
        $bucket = 'maxauto';
        $keyname = 'maxauto/listingCar/';
        // $filepath should be absolute path to a file on disk                      
        $s3Key = "AKIARSXI5AQF3E7I3QXL";
        $s3Secret = "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";


        //$tmp_file = $_FILES['userfile']['tmp_name'];
        //print_r($_FILES);
        //$path = $file['images']['name'];
        //$ext = pathinfo($path, PATHINFO_EXTENSION);
        $image_name = $this->random_string(50) . '.' . 'png';

        // Instantiate the client.
        $s3 = S3Client::factory(array(
            'credentials' => [
                'key'    => $s3Key,
                'secret' => $s3Secret,
            ],
            'region' => 'ap-southeast-2',
            'version' => "latest"
        ));

        try {
            if (!file_exists('/tmp/tmpfile')) {
                mkdir('/tmp/tmpfile');
            }
            // Create temp file
            $tempFilePath = '/tmp/tmpfile/' . basename($tmp_file);
            $tempFile = fopen($tempFilePath, "w") or die("Error: Unable to open file.");
            $fileContents = file_get_contents($tmp_file);
            $tempFile = file_put_contents($tempFilePath, $fileContents);

            // Upload data.
            $result = $s3->putObject(array(
                'Bucket' => $bucket,
                'Key'    => $keyname . $image_name,
                'SourceFile'   => $tempFilePath
            ));
       
            return ($image_name);
        } catch (S3Exception $e) {
            print_r($e);
        }
    }


    function make_thumb($src, $dest, $desired_width, $extension)
    {
        $extension = strtolower($extension);
        if ($extension == 'jpeg' ||  $extension == 'jpg') {
            $source_image = imagecreatefromjpeg($src);
        }
        if ($extension == 'png') {
            $source_image = imagecreatefrompng($src);
        }
        if ($extension == 'gif') {
            $source_image = imagecreatefromgif($src);
        }
        $width = imagesx($source_image);
        $height = imagesy($source_image);
        $desired_height = floor($height * ($desired_width / $width));
        $virtual_image = imagecreatetruecolor($desired_width, $desired_height);
        imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);
        if ($extension == 'jpeg' ||  $extension == 'jpg') {
            imagejpeg($virtual_image, $dest);
        }
        if ($extension == 'png') {
            imagepng($virtual_image, $dest);
        }
        if ($extension == 'gif') {
            imagegif($virtual_image, $dest);
        }
    }
}
