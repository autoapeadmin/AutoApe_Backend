<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/Base_controller.php';
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Webbackend
 *
 * @author mac
 */
class Webbackend extends Base_controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Customer_Model');
        $this->load->model('Common_Model');
        $this->load->model('Agent_Model');
        $this->load->model('WebadminModel');
        $this->load->helper('string');
    }

    public function postpropertysale() {
        $showprice = "";
        $datea = "";
        $avadt = "";
        $isauc = 0;
        $petsava = 0;
        $priceoption = $this->input->post("priceoption");
        switch ($this->input->post("priceoption")) {
            case "1":
                $showprice = $this->input->post("askingp");
                break;
            case "2":
                $showprice = "Enquiries over $" . number_format($this->input->post("enquireprice")); //"Enquiries over " .
                break;
            case "3":
                $showprice = "Sales by Auction"; //Sales by Auction
                $isauc = 1;
                $datea = $this->input->post("auctionon");
                break;
            case "4":
                // $showprice ="Tender Closing on: ".$this->input->post("closingon"); //Tender
                $showprice = "Tender Closing on: " . date('d M Y', strtotime($this->input->post("closingon"))); //Tender
                $datea = $this->input->post("closingon");
                break;
            case "5":
                $showprice = "by Negotitation"; //by Negotitation
                break;
            case "6":
                // $showprice = "Sale Required by ".$this->input->post("required"); //"Sale Required by " .
                $showprice = "Sale Required by " . date('d M Y', strtotime($this->input->post("required"))); //"Sale Required by " .
                break;
        }

        if ($this->input->post("options") === "1") {
            $showprice = $this->input->post("rent");
            $avadt = $this->input->post("avadate");
        }

        if ($this->input->post("petsava") === "1") {
            $petsava = 1;
        }

        $locations = array(
            "address" => $this->input->post("address"),
            "lat" => $this->input->post("lat"),
            "long" => $this->input->post("lon"),
            "fk_suburb_id" => $this->input->post("suburb"),
            "fk_city_id" => $this->input->post("district"),
            "fk_region_id" => $this->input->post("region")
        );


        $locaid = $this->WebadminModel->addlocaltion($locations);



        $data = array(
            "property_title" => $this->input->post("title"),
            "property_description" => $this->input->post("dec"),
            "property_sale_flag" => "0",
            "property_show_price" => $showprice,
            "property_hidden_price" => $this->input->post("searchprice"),
            "property_bedroom" => $this->input->post("bedroom"),
            "property_bathroom" => $this->input->post("bathrom"),
            "property_carpark" => $this->input->post("carpark"),
            "property_auction" => $isauc,
            "property_auction_date" => $datea,
            "property_land_area" => $this->input->post("landarea"),
            "property_available_date" => $avadt,
            "price_option" => $priceoption,
            "fk_property_type_id" => $this->input->post("ptype"),
            "fk_location_id" => $locaid,
            "fk_property_status_id" => "0",
            'property_disable' => "0",
            'delete_flag' => "2",
            "agency_ref" => $this->input->post("agencyref"),
            "fk_customer_id" => $_SESSION["SESS_CUSTOMER_ID"],
            "is_private" => "1"
        );

        if ($_SESSION["SESS_CUSTOMER_AGENT"] == 1) {
            $data["delete_flag"]="0";
        }
        
        $proid = $this->WebadminModel->addproperty($data);


        if ($this->input->post("openhome") === "1") {

            $fromdt = $this->input->post("openfromdate");
            //$todt = $this->input->post("opentodate");
            $todt = $this->input->post("openfromdate");
            $opentime = $this->input->post("openfromtime");
            $opentotime = $this->input->post("opentotime");
            $vcs = 0;
            foreach ($fromdt as $value) {
                $opendata = array(
                    "open_home_from" => $value . " " . $opentime[$vcs] . ":00",
                    "open_home_to" => $todt[$vcs] . " " . $opentotime[$vcs] . ":00",
                    "fk_property_id" => $proid
                );
                $this->WebadminModel->addopenhome($opendata);

                $vcs++;
            }
        }

        switch ($this->input->post("cntcat")) {
            case 1:
                $dats = array(
                    "customer_mobile" => $this->input->post("mobile"),
                    "customer_phone" => $this->input->post("phone"),
                    "customer_contact" => $this->input->post("contactime")
                );
                $this->WebadminModel->updatecustomercontact($_SESSION["SESS_CUSTOMER_ID"], $dats);
                break;
            case 2:
                $dats = array(
                    "agency" => $this->input->post("agnecy"),
                    "agent" => $this->input->post("agnet"),
                    "agentmob" => $this->input->post("agnetmobi"),
                    "agnettele" => $this->input->post("agnettele"),
                    "agnet_email" => $this->input->post("agnetemail"),
                    "customer_id" => $_SESSION["SESS_CUSTOMER_ID"],
                    "property_id" => $proid
                );

                $this->WebadminModel->addcustomeragnets($dats);
                break;
        }
        $_SESSION["pripertyid"] = $proid;
        if ($_SESSION["SESS_CUSTOMER_AGENT"] == 0) {
            $configs = $this->WebadminModel->getConfig()->row(0);
            $_SESSION["SESS_PRICE"] = $configs->sales_listing_cost;
            $_SESSION["SESS_MAIN_PRICE"] = $configs->sales_listing_cost;
            $_SESSION["SESS_SERVCAT"] = "Property Sales Listing (" . $configs->sales_listing_term . " Week(s))";
        }

        $this->json_encode_msgs(array("pripertyid" => $proid));
    }

    public function postpropertyrent() {
        $showprice = "";
        $datea = "";
        $avadt = "";
        $isauc = 0;
        $showprice = $this->input->post("rent");
        $avadt = $this->input->post("avadate");




        $locations = array(
            "address" => $this->input->post("address"),
            "lat" => $this->input->post("lat"),
            "long" => $this->input->post("lon"),
            "fk_suburb_id" => $this->input->post("suburb"),
            "fk_city_id" => $this->input->post("district"),
            "fk_region_id" => $this->input->post("region")
        );

        $locaid = $this->WebadminModel->addlocaltion($locations);

        $data = array(
            "property_title" => $this->input->post("title"),
            "property_description" => $this->input->post("dec"),
            "property_sale_flag" => "1",
            "property_show_price" => $showprice,
            "property_hidden_price" => $this->input->post("searchprice"),
            "property_bedroom" => $this->input->post("bedroom"),
            "property_bathroom" => $this->input->post("bathrom"),
            "property_carpark" => $this->input->post("carpark"),
            "property_land_area" => $this->input->post("landarea"),
            "property_available_date" => $avadt,
            "property_pet" => $this->input->post("petsava"),
            "fk_property_type_id" => $this->input->post("ptype"),
            "fk_location_id" => $locaid,
            "fk_property_status_id" => "0",
            'property_disable' => "0",
            'delete_flag' => "2",
            "agency_ref" => $this->input->post("agencyref"),
            "fk_customer_id" => $_SESSION["SESS_CUSTOMER_ID"],
            "is_private" => "1"
        );
        
        if ($_SESSION["SESS_CUSTOMER_AGENT"] == 1) {
            $data["delete_flag"]="0";
        }

        $proid = $this->WebadminModel->addproperty($data);

        $dats = array(
            "customer_mobile" => $this->input->post("mobile"),
            "customer_phone" => $this->input->post("phone"),
            "customer_contact" => $this->input->post("contactime")
        );
        $this->WebadminModel->updatecustomercontact($_SESSION["SESS_CUSTOMER_ID"], $dats);

        $_SESSION["pripertyid"] = $proid;
        if ($_SESSION["SESS_CUSTOMER_AGENT"] == 0) {
            $configs = $this->WebadminModel->getConfig()->row(0);
            $_SESSION["SESS_PRICE"] = $configs->rental_listing_cost;
            $_SESSION["SESS_MAIN_PRICE"] = $configs->rental_listing_cost;
            $_SESSION["SESS_SERVCAT"] = "Property Rental Listing (" . $configs->rental_listing_term . " Week(s))";
        }

        $this->json_encode_msgs(array("pripertyid" => $proid));
    }

    public function uploadimages() {
        $output_dir = "images/property/";
        if (isset($_FILES["Filedata"])) {
            $ret = array();

            //	This is for custom errors;	
            /* 	$custom_error= array();
              $custom_error['jquery-upload-file-error']="File already exists";
              echo json_encode($custom_error);
              die();
             */
            $error = $_FILES["Filedata"]["error"];
            //You need to handle  both cases
            //If Any browser does not support serializing of multiple files using FormData() 
            if (!is_array($_FILES["Filedata"]["name"])) { //single file
                $temp = explode(".", $_FILES["Filedata"]["name"]);
                $fileName = random_string('alnum', 16) . round(microtime(true)) . "." . end($temp);
                move_uploaded_file($_FILES["Filedata"]["tmp_name"], $output_dir . $fileName);
                $datsd = array(
                    "property_pic" => "images/property/" . $fileName,
                    "fk_property_id" => $_SESSION["pripertyid"],
                    "property_first_pic" => 1
                );
                $this->WebadminModel->addpics($datsd);
                // $ret[] = "images/property/".$fileName;
                // array_push($ret, "images/property/" . $fileName);
            } else {  //Multiple files, file[]
                $fileCount = count($_FILES["Filedata"]["name"]);
                for ($i = 0; $i < $fileCount; $i++) {
                    $temp = explode(".", $_FILES["Filedata"]["name"][$i]);
                    $fileName = $i.random_string('alnum', 16) . round(microtime(true)) . "." . end($ret);
                    move_uploaded_file($_FILES["Filedata"]["tmp_name"][$i], $output_dir . $fileName);
                    $datsd = array(
                        "property_pic" => "images/property/" . $fileName,
                        "fk_property_id" => $_SESSION["pripertyid"],
                        "property_first_pic" => $i + 1
                    );
                    $this->WebadminModel->addpics($datsd);
                    // $ret[] = "images/property/".$fileName;
                    // array_push($ret, "images/property/" . $fileName);
                }
            }


            foreach ($this->WebadminModel->getpicsofproperti() as $value) {
                array_push($ret, array("image" => $value->property_pic, "id" => $value->property_pic_id));
            }

            echo json_encode($ret);
        }
    }

    public function imageposition() {
        $imagid = $this->input->post("imgid");
        $imgdata = array(
            "property_first_pic" => $this->input->post("posi")
        );
        $this->WebadminModel->imagepositin($imagid, $imgdata);
    }

    public function deleteimage() {
        $imagid = $this->input->post("imgid");
        $this->WebadminModel->delimages($imagid);
    }

}
