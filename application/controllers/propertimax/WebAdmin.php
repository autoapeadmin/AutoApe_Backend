<?php

defined('BASEPATH') or exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require APPPATH . 'libraries/Base_controller.php';

/**
 * Description of WebAdmin
 *
 * @author mac
 */
class WebAdmin extends Base_controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('WebadminModel');
        $this->load->helper('string');
        $this->load->library('email');
        $this->load->model('Common_Model');
        $this->load->model('Customer_Model');
    }

    public function index()
    { // payment
        if (isset($_SESSION["ADMIN"])) {
            $result["configs"] = $this->WebadminModel->getConfig()->row(0);
            $query = $this->WebadminModel->getPaymentList();
            $query->num_rows();
            $result["orders"] = $query->result();
            $sidebar["show"] = "payment";

            $this->load->view('admin/includes/head');
            $this->load->view('admin/includes/header');
            $this->load->view('admin/includes/sidebar', $sidebar);
            $this->load->view('admin/payments/payment', $result);
            $this->load->view('admin/includes/footer');
            $this->load->view('admin/includes/jsplugins');
        } else {
            redirect(base_url("WebAdmin/login"), 'refresh');
        }
    }

    public function login()
    {
        $this->load->view('admin/loginwebadmin');
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect($_SERVER['HTTP_REFERER'], 'refresh');
    }

    public function loginsub()
    {
        $email = $this->input->post("un");
        $pw = $this->input->post("pw");

        if ($email != "" & $pw != "") {
            $res = $this->WebadminModel->adminlogin($email);

            if ($res) {
                if ($res->admin_email == $email & password_verify($pw, $res->admin_pw)) {
                    $_SESSION["ADMIN"] = $res->admin_id;
                    $_SESSION["admin_name"] = $res->admin_name;
                    $_SESSION["admin_access"] = $res->admin_access;
                    redirect(base_url("WebAdmin/sampledataform"), 'refresh');
                } else {
                    redirect(base_url("WebAdmin/login?log=error"), 'refresh');
                }
            } else {
                redirect(base_url("WebAdmin/login?log=error"), 'refresh');
            }
        } else {
            redirect(base_url("WebAdmin/login?log=error"), 'refresh');
        }
    }

    public function profiles()
    {
        if (isset($_SESSION["ADMIN"])) {
            $query = $this->WebadminModel->getCustomerList();
            $query->num_rows();
            $result["customers"] = $query->result();
            $sidebar["show"] = "profile";

            $this->load->view('admin/includes/head');
            $this->load->view('admin/includes/header');
            $this->load->view('admin/includes/sidebar', $sidebar);
            $this->load->view('admin/profiles/profile', $result);
            $this->load->view('admin/includes/footer');
            $this->load->view('admin/includes/jsplugins');
        } else {
            redirect(base_url("WebAdmin/login"), 'refresh');
        }
    }

    public function properties()
    {
        if (isset($_SESSION["ADMIN"])) {

            $result["properties"] = $this->WebadminModel->propertiesListOrder();
            $result["agents"] = $this->WebadminModel->propertyAgentList("all");
            $result["pics"] = $this->WebadminModel->propertyPicList("all");
            $result["types"] = $this->WebadminModel->propertyTypeList();
            $sidebar["show"] = "property";

            $this->load->view('admin/includes/head');
            $this->load->view('admin/includes/header');
            $this->load->view('admin/includes/sidebar', $sidebar);
            $this->load->view('admin/properties/property', $result);
            $this->load->view('admin/includes/footer');
            $this->load->view('admin/includes/jsplugins');
        } else {
            redirect(base_url("WebAdmin/login"), 'refresh');
        }
    }

    public function renewToday()
    {
        if (isset($_SESSION["ADMIN"])) {
            $order_id = $this->input->post_get("order_id");
            $data = array();
            $data['start_date'] = $this->dateTime();
            $data['expired_flag'] = 0;
            $result["properties"] = $this->WebadminModel->renewToday($order_id, $data);
            $this->properties();
        } else {
            redirect(base_url("WebAdmin/login"), 'refresh');
        }
    }

    public function editPropertyForm()
    {
        if (isset($_SESSION["ADMIN"])) {

            $property_id = $this->input->post_get("id");
            $result["properties"] = $this->WebadminModel->propertyList($property_id);
            $result["pics"] = $this->WebadminModel->propertyPicList($property_id);
            $result["types"] = $this->WebadminModel->propertyTypeList();
            $result["openhomes"] = $this->WebadminModel->newOpenHomeList($property_id);
            $sidebar["show"] = "property";

            if ($this->WebadminModel->propertyList($property_id)["is_private"] == "1") {
                $result["customer"] = $this->WebadminModel->getcustomerdetails($this->WebadminModel->propertyList($property_id)["fk_customer_id"]);
            } else {
                $result["agents"] = $this->WebadminModel->propertyAgentList($property_id);
            }

            $this->load->view('admin/includes/head');
            $this->load->view('admin/includes/header');
            $this->load->view('admin/includes/sidebar', $sidebar);
            $this->load->view('admin/editPropertyForm', $result);
            $this->load->view('admin/includes/footer');
            $this->load->view('admin/includes/jsplugins');
        } else {
            redirect(base_url("WebAdmin/login"), 'refresh');
        }
    }

    public function addOpenHome()
    {
        if (isset($_SESSION["ADMIN"])) {
            $property_id = $this->input->post_get("property_id");

            $data = array();
            $data['open_home_from'] = $this->input->post_get("fromdate");
            $data['open_home_to'] = $this->input->post_get("todate");
            $data['fk_property_id'] = $property_id;
            $this->WebadminModel->addNewOpenHome($data);
            redirect(base_url("WebAdmin/editPropertyForm?id=" . $property_id), 'refresh');
        } else {
            redirect(base_url("WebAdmin/login"), 'refresh');
        }
    }

    public function modOpenHome()
    {
        if (isset($_SESSION["ADMIN"])) {
            $property_id = $this->input->post_get("property_id");
            $open_home_id = $this->input->post_get("open_home_id");

            $data = array();
            $data['open_home_from'] = $this->input->post_get("fromdate");
            $data['open_home_to'] = $this->input->post_get("todate");

            $this->WebadminModel->modNewOpenHome($open_home_id, $data);
            redirect(base_url("WebAdmin/editPropertyForm?id=" . $property_id), 'refresh');
        } else {
            redirect(base_url("WebAdmin/login"), 'refresh');
        }
    }

    public function delOpenHome()
    {
        if (isset($_SESSION["ADMIN"])) {
            $open_home_id = $this->input->post_get("open_home_id");
            $property_id = $this->input->post_get("property_id");
            $this->WebadminModel->delNewOpenHome($open_home_id);
            redirect(base_url("WebAdmin/editPropertyForm?id=" . $property_id), 'refresh');
        } else {
            redirect(base_url("WebAdmin/login"), 'refresh');
        }
    }

    public function deleteProperty()
    {
        if (isset($_SESSION["ADMIN"])) {
            $property_id = $this->input->post_get("propertyId");
            $result = $this->WebadminModel->delProperty($property_id);
            echo "<script>alert('Success');</script>";
            redirect(base_url("WebAdmin/properties"), 'refresh');
        } else {
            redirect(base_url("WebAdmin/login"), 'refresh');
        }
    }

    public function reports()
    {
        if (isset($_SESSION["ADMIN"])) {
            $sidebar["show"] = "report";

            $this->load->view('admin/includes/head');
            $this->load->view('admin/includes/header');
            $this->load->view('admin/includes/sidebar', $sidebar);
            $this->load->view('admin/reports');
            $this->load->view('admin/includes/footer');
            $this->load->view('admin/includes/jsplugins');
        } else {
            redirect(base_url("WebAdmin/login"), 'refresh');
        }
    }

    public function emaildataform()
    {
        if (isset($_SESSION["ADMIN"])) {
            $sidebar["show"] = "iemail";

            $query = $this->WebadminModel->getEmailList();
            $result["customers"] = $query;

            $this->load->view('admin/includes/head');
            $this->load->view('admin/includes/header');
            $this->load->view('admin/includes/sidebar', $sidebar);
            $this->load->view('admin/emails',$result);
            $this->load->view('admin/includes/footer');
            $this->load->view('admin/includes/jsplugins');
        } else {
            redirect(base_url("WebAdmin/login"), 'refresh');
        }
    }

    public function sampledataform()
    {
        if (isset($_SESSION["ADMIN"])) {
            $sidebar["show"] = "iproperty";

            $this->load->view('admin/includes/head');
            $this->load->view('admin/includes/header');
            $this->load->view('admin/includes/sidebar', $sidebar);
            $this->load->view('admin/sampledata');
            $this->load->view('admin/includes/footer');
            $this->load->view('admin/includes/jsplugins');
        } else {
            redirect(base_url("WebAdmin/login"), 'refresh');
        }
    }

    public function agencies()
    {
        if (isset($_SESSION["ADMIN"])) {
            $sidebar["show"] = "iagency";
            $result["agencies"] = $this->WebadminModel->agencyList();

            $this->load->view('admin/includes/head');
            $this->load->view('admin/includes/header');
            $this->load->view('admin/includes/sidebar', $sidebar);
            $this->load->view('admin/agency/list', $result);
            $this->load->view('admin/includes/footer');
            $this->load->view('admin/includes/jsplugins');
        } else {
            redirect(base_url("WebAdmin/login"), 'refresh');
        }
    }

    public function agents()
    {
        if (isset($_SESSION["ADMIN"])) {
            $sidebar["show"] = "agent";
            $result["agents"] = $this->WebadminModel->agentList();

            $this->load->view('admin/includes/head');
            $this->load->view('admin/includes/header');
            $this->load->view('admin/includes/sidebar', $sidebar);
            $this->load->view('admin/agent/list', $result);
            $this->load->view('admin/includes/footer');
            $this->load->view('admin/includes/jsplugins');
        } else {
            redirect(base_url("WebAdmin/login"), 'refresh');
        }
    }

    public function brands()
    {
        if (isset($_SESSION["ADMIN"])) {
            $sidebar["show"] = "brands";
            $result["brands"] = $this->Common_Model->getBrand();

            $this->load->view('admin/includes/head');
            $this->load->view('admin/includes/header');
            $this->load->view('admin/includes/sidebar', $sidebar);
            $this->load->view('admin/brand/list', $result);
            $this->load->view('admin/includes/footer');
            $this->load->view('admin/includes/jsplugins');
        } else {
            redirect(base_url("WebAdmin/login"), 'refresh');
        }
    }

    public function addBrand()
    {

        if (isset($_SESSION["ADMIN"])) {




            $attachNameFist = "brand_logo";

            if ($_FILES) {
                $config['upload_path'] = "./agencylogos/";
                $config['allowed_types'] = '*';
                $config['max_size'] = 6310000;
                $config['overwrite'] = true;
                $config['encrypt_name'] = TRUE;
                $this->load->library('upload', $config);

                if ($_FILES[$attachNameFist]['name']) {
                    // delete exist first image file

                    if ($this->upload->do_upload($attachNameFist)) {
                        $datapic = $this->upload->data();
                        $brand_logo = "agencylogos/" . $datapic["file_name"];
                    } else {
                        $error = array('error' => $this->upload->display_errors());
                        print_r($error);
                        exit();
                    }
                }
            }

            $data['brand_name'] = $this->input->post("brand_name");
            $data['brand_logo'] = $brand_logo;
            $data['brand_quotation'] = $this->input->post("brand_quotation");
            print_r(1);
            exit();

            $this->Common_Model->insertBrand($data);
            redirect(base_url("WebAdmin/brands"), 'refresh');
        } else {
            redirect(base_url("WebAdmin/login"), 'refresh');
        }
    }

    /** PropertiMax - 2.0 API
     * Requirements:
     * URL: https://propertimax.co.nz/Common/sendNoticForNewListing
     * Transmission method: POST
     * Parameters: city_id
     */
    public function sendNoticForNewListing($idproperty, $agent_id)
    {
        //$token = $this->input->post_get("token");//it need to define how to import and send individual smartphone tokens.
        $date = new DateTime("now", new DateTimeZone('Pacific/Auckland'));
        $currenthour = $date->format('H');
        $currentmin = $date->format('i');
        //Get new property
        //$idproperty = $this->input->post_get('idproperty'); //Session Value
        //$agent_id = $this->input->post_get('agentid'); //Session Value

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
                        'title' => "New List from " . $noticeList[$i]["agent_first_name"] . " " . $noticeList[$i]["agent_last_name"],
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
                        'title' => "New List from " . $noticeList[$i]["agent_first_name"] . " " . $noticeList[$i]["agent_last_name"],
                        'msg' => $propertyInfo[$index]["address"] . ", " . $propertyInfo[$index]["suburb_name"] . " new listing.",
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




    public function oldAddAgency()
    {
        if (isset($_SESSION["ADMIN"])) {
            $sidebar["show"] = "iagency";

            $this->load->view('admin/includes/head');
            $this->load->view('admin/includes/header');
            $this->load->view('admin/includes/sidebar', $sidebar);
            $this->load->view('admin/agencyadding');
            $this->load->view('admin/includes/footer');
            $this->load->view('admin/includes/jsplugins');
        } else {
            redirect(base_url("WebAdmin/login"), 'refresh');
        }
    }

    public function coupons()
    {
        if (isset($_SESSION["ADMIN"])) {
            $result["coupon"] = $this->WebadminModel->couponList();
            $message = $this->session->flashdata('data_name');

            if (isset($message) || $message != null) {
                $result["alert_handle"] = $message;
                $this->session->set_flashdata('data_name', null);
            } else {
                $result["alert_handle"] = 0;
            }
            $sidebar["show"] = "coupon";

            $this->load->view('admin/includes/head');
            $this->load->view('admin/includes/header');
            $this->load->view('admin/includes/sidebar', $sidebar);
            $this->load->view('admin/coupon/list', $result);
            $this->load->view('admin/includes/footer');
            $this->load->view('admin/includes/jsplugins');
        } else {
            redirect(base_url("WebAdmin/login"), 'refresh');
        }
    }

    public function subscriptions()
    {
        if (isset($_SESSION["ADMIN"])) {
            $result["subscription"] = $this->WebadminModel->subscriptionList();
            $result["subscriptionType"] = $this->WebadminModel->typeSubscriptionList();
            $result["branch"] = $this->WebadminModel->branchList()->result();
            $message = $this->session->flashdata('data_name');

            if (isset($message) || $message != null) {
                $result["alert_handle"] = $message;
                $this->session->set_flashdata('data_name', null);
            } else {
                $result["alert_handle"] = 0;
            }
            $sidebar["show"] = "subscription";

            $this->load->view('admin/includes/head');
            $this->load->view('admin/includes/header');
            $this->load->view('admin/includes/sidebar', $sidebar);
            $this->load->view('admin/subscription/list', $result);
            $this->load->view('admin/includes/footer');
            $this->load->view('admin/includes/jsplugins');
        } else {
            redirect(base_url("WebAdmin/login"), 'refresh');
        }
    }

    public function editbranch($idbranch = null)
    {
        if (isset($_SESSION["ADMIN"])) {
            $result["branch"] = $this->WebadminModel->selectBranch($idbranch);
            $result["agencis"] = $this->WebadminModel->getallagency();
            $sidebar["show"] = "branch";
            $this->load->view('admin/includes/head');
            $this->load->view('admin/includes/header');
            $this->load->view('admin/includes/sidebar', $sidebar);
            $this->load->view('admin/branch/editBranch', $result);
            $this->load->view('admin/includes/footer');
            $this->load->view('admin/includes/jsplugins');
        } else {
            redirect(base_url("WebAdmin/login"), 'refresh');
        }
    }

    public function branches()
    {
        if (isset($_SESSION["ADMIN"])) {
            $result["branch"] = $this->WebadminModel->branchList();
            $result["agencis"] = $this->WebadminModel->getallagency();


            $message = $this->session->flashdata('data_name');

            if (isset($message) || $message != null) {
                $result["alert_handle"] = $message;
                $this->session->set_flashdata('data_name', null);
            } else {
                $result["alert_handle"] = 0;
            }
            $sidebar["show"] = "branch";

            $this->load->view('admin/includes/head');
            $this->load->view('admin/includes/header');
            $this->load->view('admin/includes/sidebar', $sidebar);
            $this->load->view('admin/branch/list', $result);
            $this->load->view('admin/includes/footer');
            $this->load->view('admin/includes/jsplugins');
        } else {
            redirect(base_url("WebAdmin/login"), 'refresh');
        }
    }

    public function addCoupon()
    {
        if (isset($_SESSION["ADMIN"])) {
            $copname = $this->input->post_get('copname');
            $copcode = $this->input->post_get('copcode');
            $copcategory = $this->input->post_get('copcategory');
            $copdiscount = $this->input->post_get('copdiscount');
            $copexdate = $this->input->post_get('copexdate');
            $data = $this->WebadminModel->selectChkCoupon($copcode);

            if ($data->chk_coupon > 0) { // Exist Coupon Code
                // This coupon code already exists.
                $this->session->set_flashdata('data_name', 1);
                redirect(base_url("WebAdmin/coupons"), 'refresh');
            } else { // Create a New Coupon
                $data = array();
                $data['co_name'] = $copname;
                $data['co_code'] = $copcode;
                $data['co_category'] = $copcategory;
                $data['co_discount'] = $copdiscount;
                $data['co_expdate'] = $copexdate;
                $result = $this->WebadminModel->insertCoupon($data);
                redirect(base_url("WebAdmin/coupons"), 'refresh');
            }
        } else {
            redirect(base_url("WebAdmin/login"), 'refresh');
        }
    }

    public function modBranch()
    {
        if (isset($_SESSION["ADMIN"])) {


            $fkl = trim($this->input->post_get('fkl'));
            $bid = trim($this->input->post_get('bid'));
            $bnameF = trim($this->input->post_get('bnameF'));
            $bnameL = trim($this->input->post_get('bnameL'));
            $bagency = $this->input->post_get('bagency');
            $bemailaccount = trim($this->input->post_get('bemailaccount'));
            $bemailpassword = trim($this->input->post_get('bemailpassword'));
            $bemailE = trim($this->input->post_get('bemailE'));
            $bwebsite = trim($this->input->post_get('bwebsite'));
            $bphone = trim($this->input->post_get('bphone'));
            $bfax = trim($this->input->post_get('bfax'));
            $bpostaladdress = trim($this->input->post_get('bpostaladdress'));
            $officeaddress = trim($this->input->post_get('baddress'));
            $lat = $this->input->post_get('lat');
            $long = $this->input->post_get('long');
            $bcontact = trim($this->input->post_get('bcontact'));


            $locations = array(
                "fk_suburb_id" => $this->input->post("suburb"),
                "fk_city_id" => $this->input->post("district"),
                "fk_region_id" => $this->input->post("region"),
                "lat" => $lat,
                "long" => $long,
                "address" => $officeaddress
            );


            //add new location
            $locaid = $this->WebadminModel->addlocaltion($locations);

            $data = array();
            $data['branch_name'] = $bnameF;
            $data['branch_full_name'] = $bnameL;
            $data['branch_email_account'] = $bemailaccount;
            $data['branch_email_invoice'] = $bemailE;
            $data['branch_website'] = $bwebsite;
            $data['branch_tel'] = $bphone;
            $data['branch_fax'] = $bfax;
            $data['branch_postal_address'] = $bpostaladdress;
            $data['branch_office_address'] = $officeaddress;
            $data['branch_login_password'] = $bemailpassword;
            $data['branch_contact_person'] = $bcontact;
            $data['fk_agency_id'] = $bagency;
            $data['fk_location_id'] = $locaid;

            $locaid = $this->WebadminModel->updateBranch($bid, $data);

            redirect(base_url("WebAdmin/branches"), 'refresh');
        }
    }

    public function addBranch()
    {
        if (isset($_SESSION["ADMIN"])) {

            $bname = trim($this->input->post_get('bname'));
            $bagency = $this->input->post_get('bagency');
            $bemailaccount = trim($this->input->post_get('bemailaccount'));
            $bemailpassword = trim($this->input->post_get('bemailpassword'));
            $bemailE = trim($this->input->post_get('bemailE'));
            $bwebsite = trim($this->input->post_get('bwebsite'));
            $bphone = trim($this->input->post_get('bphone'));
            $bfax = trim($this->input->post_get('bfax'));
            $bpostaladdress = trim($this->input->post_get('bpostaladdress'));
            $officeaddress = trim($this->input->post_get('baddress'));
            $lat = $this->input->post_get('lat');
            $long = $this->input->post_get('long');
            $bcontact = trim($this->input->post_get('bcontact'));

            $bLicense = $this->input->post_get('bLicense');
            if ($bLicense != '') {
                $fullName = $bname . ' (' . $bLicense . ')';
            } else {
                $fullName = $bname;
            }

            $locations = array(
                "fk_suburb_id" => $this->input->post("suburb"),
                "fk_city_id" => $this->input->post("district"),
                "fk_region_id" => $this->input->post("region"),
                "lat" => $lat,
                "long" => $long,
                "address" => $officeaddress
            );

            $locaid = $this->WebadminModel->addlocaltion($locations);

            $data = $this->WebadminModel->selectChkBranch($bname);

            if ($data->chk_coupon > 0) { // Exist Coupon Code
                // This coupon code already exists.
                $this->session->set_flashdata('data_name', 1);
                redirect(base_url("WebAdmin/branches"), 'refresh');
            } else { // Create a New Coupon
                $data = array();
                $data['branch_name'] = $bname;
                $data['branch_full_name'] = $fullName;
                $data['branch_email_account'] = $bemailaccount;
                $data['branch_email_invoice'] = $bemailE;
                $data['branch_website'] = $bwebsite;
                $data['branch_tel'] = $bphone;
                $data['branch_fax'] = $bfax;
                $data['branch_postal_address'] = $bpostaladdress;
                $data['branch_office_address'] = $officeaddress;
                $data['branch_login_password'] = $bemailpassword;
                $data['branch_contact_person'] = $bcontact;
                $data['branch_api_key'] = $this->apiKeyGenerator();
                $data['fk_agency_id'] = $bagency;
                $data['fk_location_id'] = $locaid;

                $this->WebadminModel->addbranch($data);
                redirect(base_url("WebAdmin/branches"), 'refresh');
            }
        } else {
            redirect(base_url("WebAdmin/login"), 'refresh');
        }
    }

    public function addSubscription()
    {
        if (isset($_SESSION["ADMIN"])) {

            $sdesc = $this->input->post_get('sdesc');
            $sbranch = $this->input->post_get('sbranch');
            $sfee = $this->input->post_get('sfee');
            $sType = $this->input->post_get('sType');
            $bStartEnd = $this->input->post_get('bStartEnd');
            $btDateEnd = $this->input->post_get('btDateEnd');

            //$locaid = $this->WebadminModel->addlocaltion($locations);

            //$data = $this->WebadminModel->selectChkBranch($bname);

            $data = array();
            $data['subscription_desc'] = $sdesc;
            $data['subscription_fee'] = $sfee;
            $data['fk_subscription_type_id'] = $sType;
            $data['subscription_start_date'] = $bStartEnd;
            $data['subscription_end_date'] = $btDateEnd;
            $data['fk_branch_id'] = $sbranch;
            $this->WebadminModel->addSubscription($data);

            redirect(base_url("WebAdmin/subscriptions"), 'refresh');
        } else {
            redirect(base_url("WebAdmin/login"), 'refresh');
        }
    }

    public function modCoupon()
    {
        if (isset($_SESSION["ADMIN"])) {
            $copid = $this->input->post_get('copid');
            $coactive = $this->input->post_get('coactive');
            if ($coactive == 0) {
                $coactive = 1;
            } else {
                $coactive = 0;
            }

            $data = array();
            $data['co_active'] = $coactive;

            $this->WebadminModel->updateCoupon($copid, $data);
            redirect(base_url("WebAdmin/coupons"), 'refresh');
        } else {
            redirect(base_url("WebAdmin/login"), 'refresh');
        }
    }

    public function sampleagent()
    {
        if (isset($_SESSION["ADMIN"])) {
            $data = array(
                "branch" => $this->WebadminModel->branchList()->result()
            );
            $sidebar["show"] = "iagent";

            $this->load->view('admin/includes/head', $data);
            $this->load->view('admin/includes/header');
            $this->load->view('admin/includes/sidebar', $sidebar);
            $this->load->view('admin/sampleagent');
            $this->load->view('admin/includes/footer');
            $this->load->view('admin/includes/jsplugins');
        } else {
            redirect(base_url("WebAdmin/login"), 'refresh');
        }
    }

    public function addAgency()
    {

        if (isset($_SESSION["ADMIN"])) {
            $config['upload_path'] = "./agencylogos/";
            $config['allowed_types'] = '*';
            $config['encrypt_name'] = TRUE;

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('userfile')) {
                $error = array('error' => $this->upload->display_errors());
            } else {
                $datapic = $this->upload->data();
                $data = array(
                    "agency_name" => $this->input->post("agencyname"),
                    "agency_pic" => "https://propertimax.co.nz/agencylogos/" . $datapic["file_name"],
                    "agency_tel" => $this->input->post("tel"),
                    "agency_fax" => $this->input->post("fax"),
                    "agency_address" => $this->input->post("address"),
                    "agency_email" => $this->input->post("email")
                );

                $this->WebadminModel->addagency($data);
                redirect(base_url("WebAdmin/agencies"), 'refresh');
            }
        } else {
            redirect(base_url("WebAdmin/login"), 'refresh');
        }
    }

    public function modAgency()
    {
        if (isset($_SESSION["ADMIN"])) {
            $agency_id = $this->input->post_get('agency_id');
            $data = array();
            $config['upload_path'] = "./images/agency/";
            $config['allowed_types'] = '*';
            $config['encrypt_name'] = TRUE;
            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('userfile')) {
                $error = array('error' => $this->upload->display_errors());
            } else {
                $datapic = $this->upload->data();
                $data['agency_pic'] = "images/agency/" . $datapic["file_name"];
            }

            $data['agency_name'] = $this->input->post("agencyname");
            $data['agency_office'] = $this->input->post("office");
            $data['agency_tel'] = $this->input->post("tel");
            $data['agency_fax'] = $this->input->post("fax");
            $data['agency_address'] = $this->input->post("address");
            $data['agency_email'] = $this->input->post("email");
            $this->WebadminModel->updateAgency($agency_id, $data);
            redirect(base_url("WebAdmin/agencies"), 'refresh');
        } else {
            redirect(base_url("WebAdmin/login"), 'refresh');
        }
    }

    public function modAgencyActive()
    {
        if (isset($_SESSION["ADMIN"])) {
            $agency_id = $this->input->post_get('agency_id');
            $coactive = $this->input->post_get('coactive');
            if ($coactive == 0) {
                $coactive = 1;
            } else {
                $coactive = 0;
            }

            $data = array();
            $data['delete_flag'] = $coactive;

            $this->WebadminModel->updateAgency($agency_id, $data);
            redirect(base_url("WebAdmin/agencies"), 'refresh');
        } else {
            redirect(base_url("WebAdmin/login"), 'refresh');
        }
    }

    public function modSubscription()
    {
        if (isset($_SESSION["ADMIN"])) {
            $sub_id = $this->input->post_get('copid');
            $coactive = $this->input->post_get('coactive');
            if ($coactive == 0) {
                $coactive = 1;
            } else {
                $coactive = 0;
            }

            $data = array();
            $data['subscription_status'] = $coactive;

            $this->WebadminModel->updateSubscription($sub_id, $data);
            redirect(base_url("WebAdmin/subscriptions"), 'refresh');
        } else {
            redirect(base_url("WebAdmin/login"), 'refresh');
        }
    }

    public function modBranchActive()
    {
        if (isset($_SESSION["ADMIN"])) {
            $Branch_id = $this->input->post_get('Branch_id');
            $coactive = $this->input->post_get('coactive');
            if ($coactive == 0) {
                $coactive = 1;
            } else {
                $coactive = 0;
            }
            $data = array();
            $data['branch_delete_flag'] = $coactive;

            $this->WebadminModel->updateBranch($Branch_id, $data);
            redirect(base_url("WebAdmin/branches"), 'refresh');
        } else {
            redirect(base_url("WebAdmin/login"), 'refresh');
        }
    }



    public function modAgencyApiKey()
    {
        if (isset($_SESSION["ADMIN"])) {
            $agency_id = $this->input->post_get('agency_id');

            $data = array();
            $data['api_key'] = $this->apiKeyGenerator();
            $this->WebadminModel->updateAgency($agency_id, $data);

            redirect(base_url("WebAdmin/agencies"), 'refresh');
        } else {
            redirect(base_url("WebAdmin/login"), 'refresh');
        }
    }

    public function addagent()
    {
        if (isset($_SESSION["ADMIN"])) {

            $config['upload_path'] = "./images/agent/";
            $config['allowed_types'] = '*';
            $config['max_size'] = 6310000;
            $config['overwrite'] = true;
            $config['encrypt_name'] = TRUE;

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('userfile')) {
                $error = array('error' => $this->upload->display_errors());
            } else {
                $datapic = $this->upload->data();
                $agent_code = $this->uniqid_base36('agent'); // Gen Unique Code

                $randpw = random_string('alnum', 10);

                $data = array(
                    "agent_code" => $agent_code,
                    "agent_first_name" => $this->input->post("fname"),
                    "agent_last_name" => $this->input->post("lname"),
                    "agent_description" => $this->input->post("adec"),
                    "agent_license" => $this->input->post("reaa"),
                    "agent_email" => $this->input->post("email"),
                    "agent_mobile" => $this->input->post("mobi"),
                    "agent_phone" => $this->input->post("lline"),
                    "agent_office" => $this->input->post("office"),
                    "fk_branch_id" => $this->input->post("agency"),
                    "agent_pic" => "images/agent/" . $datapic["file_name"],
                    "agent_password" => password_hash($randpw, PASSWORD_DEFAULT)
                );

                $this->WebadminModel->addagent($data);

                $config['protocol'] = 'smtp';
                $config['smtp_host'] = 'smtp.mandrillapp.com';
                $config['smtp_port'] = '587';
                $config['smtp_user'] = 'Propertimax';
                $config['smtp_pass'] = 'Zh4yjTIHtPb4vwD3GtdMMA';

                $config['mailtype'] = 'html'; // or html



                $this->email->initialize($config);

                $this->email->from('info@propertimax.co.nz', 'Propertimax Agent');
                $this->email->to($this->input->post("email"));

                $this->email->subject('Welcome to the Propertimax');
                $this->email->message('<p style="text-align:center;" >Folowing is the System generated password for the Propertimax Agent Admin panel. You need to enter your email and this password to login.</p> <h4 style="text-align:center;" > Your Password is: ' . $randpw . '</h4>');

                $this->email->send();


                redirect($_SERVER['HTTP_REFERER'] . "?suc=ok", 'refresh');
            }
        } else {
            redirect(base_url("WebAdmin/login"), 'refresh');
        }
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

    public function addsampleproperty()
    {
        if (isset($_SESSION["ADMIN"]) || isset($_SESSION["SESS_BRANCH_ID"])) {
            $showprice = "";
            $property_option_price = "";
            $property_option_date = "";
            $datea = "";
            $avadt = "";
            $isauc = 0;
            $petsava = 0;
            $searchprice = str_replace(',', '', $this->input->post("searchprice"));
            $priceoption = $this->input->post("priceoption");

            //$data
            $this->console_log($this->input->post("images")[0]);
            return;


            switch ($this->input->post("priceoption")) {
                case "1":
                    $showprice = $this->input->post("askingp");
                    $property_option_price = $showprice;
                    break;
                case "2":
                    $showprice = "Enquiries over " . $this->input->post("enquireprice"); //"Enquiries over " .
                    $property_option_price = $this->input->post("enquireprice");
                    break;
                case "3":
                    $showprice = "Sales by Auction"; //Sales by Auction
                    $isauc = 1;
                    $datea = $this->input->post("auctionon");
                    $property_option_date = $datea;
                    break;
                case "4":
                    $showprice = "Tender closing on: " . date('d M Y', strtotime($this->input->post("closingon"))); //Tender
                    $datea = $this->input->post("closingon");
                    $property_option_date = $datea;
                    break;
                case "5":
                    $showprice = "by  Negotiation"; //by Negotitation
                    break;
                case "6":
                    $showprice = "Sale Required by " . date('d M Y', strtotime($this->input->post("required"))); //"Sale Required by " .
                    $datea = $this->input->post("required");
                    $property_option_date = $datea;
                    break;
            }

            if ($this->input->post("options") === "1") {
                $showprice = $this->input->post("rent");
                $avadt = $this->input->post("avadate");
                $searchprice = $this->input->post("rent");
            }

            if ($this->input->post("petsava") === "1") {
                $petsava = 1;
            }

            //get a location from address
            //$xml = simplexml_load_file("http://maps.google.com/maps/api/geocode/xml?address=" . $this->input->post("findaddress") . "&language=en&sensor=false");
            $lat = $this->input->post("lat");
            $lng = $this->input->post("lon");

            $locations = array(
                "address" => $this->input->post("findaddress"),
                "lat" => $lat,
                "long" => $lng,
                "fk_suburb_id" => $this->input->post("suburb"),
                "fk_city_id" => $this->input->post("district"),
                "fk_region_id" => $this->input->post("region")
            );

            $locaid = $this->WebadminModel->addlocaltion($locations);


            $data = array(
                "property_agency_id" => $this->input->post("agency")[0],
                "property_title" => $this->input->post("title"),
                "property_description" => $this->input->post("dec"),
                "property_sale_flag" => $this->input->post("options"),
                "property_show_price" => $showprice,
                "property_option_date" => $property_option_date,
                "property_option_price" => $property_option_price,
                "property_hidden_price" => $searchprice,
                "property_bedroom" => $this->input->post("bedroom"),
                "property_bathroom" => $this->input->post("bathrom"),
                "property_carpark" => $this->input->post("carpark"),
                "property_pet" => $petsava,
                "property_auction" => $isauc,
                "property_auction_date" => $datea,
                "property_land_hectare" => $this->input->post("landaream2"),
                "property_land_meter" => $this->input->post("landareahc"),
                "property_school" => $this->input->post("school"),
                "property_available_date" => $avadt,
                "price_option" => $priceoption,
                "fk_property_type_id" => $this->input->post("ptype"),
                "fk_agent_id" => $this->input->post("agent")[0],
                "fk_location_id" => $locaid,
                "fk_property_status_id" => "0",
                'property_disable' => "0",
                "agency_ref" => $this->input->post("agenref")
            );


            $proid = $this->WebadminModel->addproperty($data);



            if ($this->input->post("openhome") === "1") {

                $fromdt = $this->input->post("openfromdate");
                $todt = $this->input->post("opentodate");
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
            $agentId = $this->input->post("agent");
            $count = count($agentId);
            //add agent
            for ($i = 0; $i < $count; $i++) {
                $agentList = array();
                $agentList = array(
                    'property_agency_id' => $this->input->post("agency")[$i],
                    'property_agent_id' => $this->input->post("agent")[$i],
                    'fk_property_id' => $proid
                );
                $this->WebadminModel->addPropertyAgent($agentList);
            }

            if (isset($_FILES)) {

                $attachName = "images";

                $config['upload_path'] = "./images/property/";
                $config['allowed_types'] = '*';
                $config['encrypt_name'] = TRUE;

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('fimage')) {
                    $datapic = $this->upload->data();
                    $imadata = array(
                        "property_pic" => "images/property/" . $datapic["file_name"],
                        "fk_property_id" => $proid,
                        "property_first_pic" => 1
                    );
                    $this->WebadminModel->addpics($imadata);
                }

                $files = $_FILES;
                $count = count($_FILES[$attachName]['name']);

                $pic_number = 1;
                for ($i = 0; $i < $count; $i++) {
                    $pic_number++;

                    $_FILES[$attachName]['name'] = $files[$attachName]['name'][$i];
                    $_FILES[$attachName]['type'] = $files[$attachName]['type'][$i];
                    $_FILES[$attachName]['tmp_name'] = $files[$attachName]['tmp_name'][$i];
                    $_FILES[$attachName]['error'] = $files[$attachName]['error'][$i];
                    $_FILES[$attachName]['size'] = $files[$attachName]['size'][$i];

                    $config['file_name'] = "mx_" . rand(100, 15200) . $i;
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
                    } else {
                        $error = array('error' => $this->upload->display_errors());
                        print_r($error);
                    }
                }

                //send notification: $proid
                $this->sendNoticForNewListing($proid, $this->input->post("agent")[0]);


                redirect($_SERVER['HTTP_REFERER'] . "?suc=ok", 'refresh');
            }
        } else {
            redirect(base_url("WebAdmin/login"), 'refresh');
        }
    }


    function compress($source, $destination, $quality)
    {

        $info = getimagesize($source);

        if ($info['mime'] == 'image/jpeg')
            $image = imagecreatefromjpeg($source);

        elseif ($info['mime'] == 'image/gif')
            $image = imagecreatefromgif($source);

        elseif ($info['mime'] == 'image/png')
            $image = imagecreatefrompng($source);

        imagejpeg($image, $destination, $quality);

        return $destination;
    }

    public function addproperty()
    {
        if (isset($_SESSION["ADMIN"]) || isset($_SESSION["SESS_BRANCH_ID"])) {
            $showprice = "";
            $property_option_price = "";
            $property_option_date = "";
            $datea = "";
            $avadt = "";
            $isauc = 0;
            $petsava = 0;
            $searchprice = str_replace(',', '', $this->input->post("searchprice"));
            $priceoption = $this->input->post("priceoption");


            switch ($this->input->post("priceoption")) {
                case "1":
                    $showprice = $this->input->post("askingp");
                    $property_option_price = $showprice;
                    break;
                case "2":
                    $showprice = "Enquiries over " . $this->input->post("enquireprice"); //"Enquiries over " .
                    $property_option_price = $this->input->post("enquireprice");
                    break;
                case "3":
                    $showprice = "Sales by Auction"; //Sales by Auction
                    $isauc = 1;
                    $datea = $this->input->post("auctionon");
                    $property_option_date = $datea;
                    break;
                case "4":
                    $showprice = "Tender closing on: " . date('d M Y', strtotime($this->input->post("closingon"))); //Tender
                    $datea = $this->input->post("closingon");
                    $property_option_date = $datea;
                    break;
                case "5":
                    $showprice = "by  Negotiation"; //by Negotitation
                    break;
                case "6":
                    $showprice = "Sale Required by " . date('d M Y', strtotime($this->input->post("required"))); //"Sale Required by " .
                    $datea = $this->input->post("required");
                    $property_option_date = $datea;
                    break;
            }

            if ($this->input->post("options") === "1") {
                $showprice = $this->input->post("rent");
                $avadt = $this->input->post("avadate");
                $searchprice = $this->input->post("rent");
            }

            if ($this->input->post("petsava") === "1") {
                $petsava = 1;
            }

            //get a location from address
            //$xml = simplexml_load_file("http://maps.google.com/maps/api/geocode/xml?address=" . $this->input->post("findaddress") . "&language=en&sensor=false");
            $lat = $this->input->post("lat");
            $lng = $this->input->post("lon");

            $locations = array(
                "address" => $this->input->post("findaddress"),
                "lat" => $lat,
                "long" => $lng,
                "fk_suburb_id" => $this->input->post("suburb"),
                "fk_city_id" => $this->input->post("district"),
                "fk_region_id" => $this->input->post("region")
            );

            $locaid = $this->WebadminModel->addlocaltion($locations);


            $data = array(
                "property_agency_id" => $this->input->post("agency")[0],
                "property_title" => $this->input->post("title"),
                "property_description" => $this->input->post("dec"),
                "property_sale_flag" => $this->input->post("options"),
                "property_show_price" => $showprice,
                "property_option_date" => $property_option_date,
                "property_option_price" => $property_option_price,
                "property_hidden_price" => $searchprice,
                "property_bedroom" => $this->input->post("bedroom"),
                "property_bathroom" => $this->input->post("bathrom"),
                "property_carpark" => $this->input->post("carpark"),
                "property_pet" => $petsava,
                "property_auction" => $isauc,
                "property_auction_date" => $datea,
                "property_land_hectare" => $this->input->post("landaream2"),
                "property_land_meter" => $this->input->post("landareahc"),
                "property_school" => $this->input->post("school"),
                "property_available_date" => $avadt,
                "price_option" => $priceoption,
                "fk_property_type_id" => $this->input->post("ptype"),
                "fk_agent_id" => $this->input->post("agent")[0],
                "fk_location_id" => $locaid,
                "fk_property_status_id" => "0",
                'property_disable' => "0",
                "agency_ref" => $this->input->post("agenref")
            );


            $proid = $this->WebadminModel->addproperty($data);

            $openhome = $this->input->post("openhome");
            $opentime = $this->input->post("opentime");
            $openduration = $this->input->post("openduration");
            $count = count($openhome);
            if ($openhome[0] != "") {
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
                        "fk_property_id" => $proid
                    );
                    $this->WebadminModel->addopenhome($opendata);
                }
            }

            $agentId = $this->input->post("agent");
            $count = count($agentId);
            //add agent
            for ($i = 0; $i < $count; $i++) {
                $agentList = array();
                $agentList = array(
                    'property_agency_id' => $this->input->post("agency")[$i],
                    'property_agent_id' => $this->input->post("agent")[$i],
                    'fk_property_id' => $proid
                );
                $this->WebadminModel->addPropertyAgent($agentList);
            }

            if (isset($_FILES)) {

                $photos = $this->input->post("readyimg");
                $files = $_FILES;
                $count = count($_FILES['images']['name']);
                $attachName = "images";
                //$config['file_name'] = "mx_" . rand(100, 15200) . $b;
                $config['upload_path'] = "./images/property/";
                $config['allowed_types'] = '*';
                $config['encrypt_name'] = TRUE;

                foreach ($photos as $photo) {
                    $pic_number = 1;
                    for ($b = 0; $b < $count; $b++) {
                        if ($photo == $files['images']['name'][$b]) {
                            $_FILES[$attachName]['name'] = $files[$attachName]['name'][$b];
                            $_FILES[$attachName]['type'] = $files[$attachName]['type'][$b];
                            $_FILES[$attachName]['tmp_name'] = $files[$attachName]['tmp_name'][$b];
                            $_FILES[$attachName]['error'] = $files[$attachName]['error'][$b];
                            $_FILES[$attachName]['size'] = $files[$attachName]['size'][$b];

                            $imagedetails = getimagesize($_FILES[$attachName]['tmp_name']);
                            $width = $imagedetails[0];
                            $height = $imagedetails[1];
                            $config['image_library'] = 'gd2';
                            $config['quality'] = '20';
                            $config['width'] = $width - 1;
                            $config['height'] = $height - 1;
                            print_r($config);

                            //$this->load->library('upload', $config);
                            //$this->upload->initialize($config);

                            //$upload = $this->upload->do_upload($attachName);

                            //add Image S3
                            $tmp_file = $_FILES[$attachName]['tmp_name'];
                            $image_name =$_SESSION["SESS_BRANCH_ID"] . '-' . $_FILES[$attachName]['name'];
                            $urlS3 = $this->saveImageInS3Property($tmp_file, $image_name,$_FILES);


                            

                            if (isset($urlS3)) {
                                //$dataimg = $this->upload->data();

                                //get the size of the image
                                //$file_size = filesize("images/property/" . $dataimg["file_name"]); // Get file size in bytes
                                //$file_size = $file_size / 1024; // Get file size in KB
                                //print_r($file_size);

                                //if($file_size>1000){
                                //    $this->compress("images/property/" . $dataimg["file_name"], "images/property/" . $dataimg["file_name"], "20");
                                //}
                                
                                $imadata = array(
                                    "property_pic" => $urlS3,
                                    "fk_property_id" => $proid,
                                    "property_first_pic" => $pic_number
                                );
                                //print_r($imadata);
                                $this->WebadminModel->addpics($imadata);
                                $pic_number++;
                            } else {
                                $error = array('error' => $this->upload->display_errors());
                                print_r($error);
                            }
                        }
                    }
                }

                //send notification: $proid
                //$this->sendNoticForNewListing($proid, $this->input->post("agent")[0]);



                redirect($_SERVER['HTTP_REFERER'] . "?suc=ok", 'refresh');
            }
        } else {
            //redirect(base_url("WebAdmin/login"), 'refresh');
        }
    }

    //Gisung 03/07/2018 - add location table that lat,lng from address
    public function updateAddressToLatLng()
    {

        $address = $this->WebadminModel->getLocaltionList();

        for ($i = 0; $i < count($address); $i++) {
            //print_r($address[$i]["address"]);
            //get a location from address
            $xml = simplexml_load_file("http://maps.google.com/maps/api/geocode/xml?address=" . $address[$i]["address"] . "&language=en&sensor=false");
            $lat = $xml->result->geometry->location->lat;
            $lng = $xml->result->geometry->location->lng;
            if ($lat && $lng) {
                $locations = array(
                    "lat" => $lat,
                    "long" => $lng
                );
                $this->WebadminModel->modlocaltion($address[$i]["location_id"], $locations);
            }
        }
    }

    //Gisung 09/05/2018
    public function modProperty()
    {
        if (isset($_SESSION["ADMIN"])) {
            $showprice = "";
            $datea = "";
            $avadt = "";
            $isauc = 0;
            $petsava = 0;
            $hidprice = $this->input->post("searchprice");
            $priceoption = $this->input->post("priceoption");
            $property_id = $this->input->post("propertyId");
            $location_id = $this->input->post("locationId");

            switch ($this->input->post("priceoption")) {
                case "1":
                    $showprice = $this->input->post("askingp");
                    break;
                case "2":
                    $showprice = "Enquiries over " . $this->input->post("enquireprice"); //"Enquiries over " .
                    break;
                case "3":
                    $showprice = "Sales by Auction"; //Sales by Auction
                    $isauc = 1;
                    $datea = $this->input->post("auctionon");
                    break;
                case "4":
                    $showprice = "Tender closing on: " . date('d M Y', strtotime($this->input->post("closingon"))); //Tender
                    $datea = $this->input->post("closingon");
                    break;
                case "5":
                    $showprice = "by  Negotiation"; //by Negotitation
                    break;
                case "6":
                    $showprice = "Sale Required by " . date('d M Y', strtotime($this->input->post("required"))); //"Sale Required by "
                    $datea = $this->input->post("required");
                    break;
            }

            if ($this->input->post("options") === "1") {
                $showprice = $this->input->post("rent");
                $avadt = $this->input->post("avadate");
                $hidprice = $this->input->post("rent");
            }

            if ($this->input->post("petsava") === "1") {
                $petsava = 1;
            }

            //get a location from address
            //$xml = simplexml_load_file("http://maps.google.com/maps/api/geocode/xml?address=" . $this->input->post("findaddress") . "&language=en&sensor=false");
            $lat = $this->input->post("lat");
            $lng = $this->input->post("lon");

            $locations = array(
                "address" => $this->input->post("findaddress"),
                "lat" => $lat,
                "long" => $lng,
                "fk_suburb_id" => $this->input->post("suburb"),
                "fk_city_id" => $this->input->post("district"),
                "fk_region_id" => $this->input->post("region")
            );
            $locaid = $this->WebadminModel->modlocaltion($location_id, $locations);

            $data = array(
                //"property_agency_id" => $this->input->post("agency"),
                "property_title" => $this->input->post("title"),
                "property_description" => $this->input->post("dec"),
                "property_sale_flag" => $this->input->post("options"),
                "property_show_price" => $showprice,
                "property_hidden_price" => $hidprice,
                "property_bedroom" => $this->input->post("bedroom"),
                "property_bathroom" => $this->input->post("bathrom"),
                "property_carpark" => $this->input->post("carpark"),
                "property_pet" => $petsava,
                "property_auction" => $isauc,
                "property_auction_date" => $datea,
                "property_land_area" => $this->input->post("landarea"),
                "property_school" => $this->input->post("school"),
                "property_available_date" => $avadt,
                "price_option" => $priceoption,
                "fk_property_type_id" => $this->input->post("ptype"),
                //"fk_agent_id" => $this->input->post("agent"),
                //"fk_location_id" => $locaid,
                "fk_property_status_id" => "0",
                "property_update" => date("Y-m-d H:i:s"),
                "agency_ref" => $this->input->post("agenref")
            );

            $proid = $this->WebadminModel->modproperty($property_id, $data);

            if ($this->input->post("openhome") === "1") {

                $this->WebadminModel->delopenhome($property_id);

                $fromdt = $this->input->post("openfromdate");
                $todt = $this->input->post("opentodate");
                $opentime = $this->input->post("openfromtime");
                $opentotime = $this->input->post("opentotime");
                $vcs = 0;
                foreach ($fromdt as $value) {
                    $opendata = array(
                        "open_home_from" => $value . " " . $opentime[$vcs] . ":00",
                        "open_home_to" => $todt[$vcs] . " " . $opentotime[$vcs] . ":00",
                        "fk_property_id" => $property_id
                    );
                    $this->WebadminModel->addopenhome($opendata);

                    $vcs++;
                }
            }
            if ($this->input->post("isprivate") == "0") {
                $agentId = $this->input->post("agent");
                $count = count($agentId);

                if ($agentId) {
                    $this->WebadminModel->delPropertyAgent($property_id);
                    //update agent
                    for ($i = 0; $i < $count; $i++) {
                        $agentList = array();
                        $agentList = array(
                            'property_agency_id' => $this->input->post("agency")[$i],
                            'property_agent_id' => $this->input->post("agent")[$i],
                            'fk_property_id' => $property_id
                        );
                        $this->WebadminModel->addPropertyAgent($agentList);
                    }
                }
            }
            redirect($_SERVER['HTTP_REFERER'] . "", 'refresh');
        } else {
            redirect(base_url("WebAdmin/login"), 'refresh');
        }
    }

    // Added by Edward
    public function modPropertyAgents()
    {
        if (isset($_SESSION["ADMIN"])) {
            $property_id = $this->input->post("propertyId");

            if ($this->input->post("isprivate") == "0") {
                $agentId = $this->input->post("agent");
                $count = count($agentId);

                if ($agentId) {
                    $this->WebadminModel->delPropertyAgent($property_id);
                    //update agent
                    for ($i = 0; $i < $count; $i++) {
                        $agentList = array();
                        $agentList = array(
                            'property_agency_id' => $this->input->post("agency")[$i],
                            'property_agent_id' => $this->input->post("agent")[$i],
                            'fk_property_id' => $property_id
                        );
                        $this->WebadminModel->addPropertyAgent($agentList);
                    }
                }
            }
            redirect($_SERVER['HTTP_REFERER'] . "", 'refresh');
        } else {
            redirect(base_url("WebAdmin/login"), 'refresh');
        }
    }

    // Added by Edward
    public function modPropertyPics()
    {
        if (isset($_SESSION["ADMIN"])) {
            $property_id = $this->input->post("propertyId");
            $attachNameFist = "firstimage";
            $attachName = "images";

            if ($_FILES) {
                $config['upload_path'] = "./images/property/";
                $config['allowed_types'] = '*';
                $config['max_size'] = 6310000;
                $config['overwrite'] = true;
                $config['encrypt_name'] = TRUE;
                $this->load->library('upload', $config);

                if ($_FILES[$attachNameFist]['name']) {
                    // delete exist first image file
                    $this->WebadminModel->delFirstPic($property_id);

                    if ($this->upload->do_upload($attachNameFist)) {
                        $datapic = $this->upload->data();

                        $imadata = array();
                        $imadata['property_pic'] = "images/property/" . $datapic["file_name"];
                        $imadata['fk_property_id'] = $property_id;
                        $imadata['property_first_pic'] = 1;
                        $this->WebadminModel->addpics($imadata);
                    }
                }

                if ($_FILES[$attachName]['name'][0]) {
                    // delete exist Other image file
                    $this->WebadminModel->delOtherPics($property_id);
                    $files = $_FILES;
                    $count = count($_FILES[$attachName]['name']);

                    $pic_number = 1;
                    for ($i = 0; $i < $count; $i++) {
                        $pic_number++;
                        $this->upload->initialize($config);
                        $_FILES[$attachName]['name'] = $files[$attachName]['name'][$i];
                        $_FILES[$attachName]['type'] = $files[$attachName]['type'][$i];
                        $_FILES[$attachName]['tmp_name'] = $files[$attachName]['tmp_name'][$i];
                        $_FILES[$attachName]['error'] = $files[$attachName]['error'][$i];
                        $_FILES[$attachName]['size'] = $files[$attachName]['size'][$i];
                        $upload = $this->upload->do_upload($attachName);

                        if ($upload) {
                            $dataimg = $this->upload->data();
                            // chmod("./images/property/" . $dataimg["file_name"], 0777);

                            $imadata = array();
                            $imadata['property_pic'] = "images/property/" . $dataimg["file_name"];
                            $imadata['fk_property_id'] = $property_id;
                            $imadata['property_first_pic'] = $pic_number;
                            $this->WebadminModel->addpics($imadata);
                            //redirect($_SERVER['HTTP_REFERER'] . "", 'refresh');
                        } else {
                            $error = array('error' => $this->upload->display_errors());
                            print_r($error);
                        }
                    }
                }
            }
            redirect($_SERVER['HTTP_REFERER'] . "", 'refresh');
        } else {
            redirect(base_url("WebAdmin/login"), 'refresh');
        }
    }

    public function config()
    {
        if (isset($_SESSION["ADMIN"])) {
            $result["configs"] = $this->WebadminModel->getConfig()->row(0);

            // print_r($result["configs"]->sales_listing_cost);
            // exit();
            $sidebar["show"] = "config";

            $this->load->view('admin/includes/head');
            $this->load->view('admin/includes/header');
            $this->load->view('admin/includes/sidebar', $sidebar);
            $this->load->view('admin/config/detail', $result);
            $this->load->view('admin/includes/footer');
            $this->load->view('admin/includes/jsplugins');
        } else {
            redirect(base_url("WebAdmin/login"), 'refresh');
        }
    }

    public function modConfig()
    {
        if (isset($_SESSION["ADMIN"])) {
            $ipart = $this->input->post_get('ipart');

            $data = array();
            if ($ipart == "cost") {
                $data['sales_listing_cost'] = $this->input->post_get('sales_listing_cost');
                $data['rental_listing_cost'] = $this->input->post_get('rental_listing_cost');
            } else {
                $data['sales_listing_term'] = $this->input->post_get('sales_listing_term');
                $data['rental_listing_term'] = $this->input->post_get('rental_listing_term');
            }
            $data['indate'] = date('Y-m-d H:i:s');
            $this->WebadminModel->updateConfig($data);

            redirect(base_url("WebAdmin/config"), 'refresh');
        } else {
            redirect(base_url("WebAdmin/login"), 'refresh');
        }
    }

    public function import()
    {
        if (isset($_SESSION["ADMIN"])) {
            $result["configs"] = $this->WebadminModel->getConfig()->row(0);
            $result["agencys"] = $this->WebadminModel->getAgencyList();
            $sidebar["show"] = "import";
            $sidebar["alert"] = null;
            $sidebar["msgs"] = null;
            // print_r($result["configs"]->sales_listing_cost);
            // exit();

            $this->load->view('admin/includes/head');
            $this->load->view('admin/includes/header');
            $this->load->view('admin/includes/sidebar', $sidebar);
            $this->load->view('admin/imports/detail', $result);
            $this->load->view('admin/includes/footer');
            $this->load->view('admin/includes/jsplugins');
        } else {
            redirect(base_url("WebAdmin/login"), 'refresh');
        }
    }

    function importMsgs($msgs)
    {
        if (isset($_SESSION["ADMIN"])) {
            $result["configs"] = $this->WebadminModel->getConfig()->row(0);
            $result["agencys"] = $this->WebadminModel->getAgencyList();
            $sidebar["show"] = "import";
            $sidebar["alert"] = "show";
            $sidebar["msgs"] = $msgs;

            $this->load->view('admin/includes/head');
            $this->load->view('admin/includes/header');
            $this->load->view('admin/includes/sidebar', $sidebar);
            $this->load->view('admin/imports/detail', $result);
            $this->load->view('admin/includes/footer');
            $this->load->view('admin/includes/jsplugins');
        } else {
            redirect(base_url("WebAdmin/login"), 'refresh');
        }
    }

    public function importCSV()
    {
        if (isset($_SESSION["ADMIN"])) {

            $mimes = array('application/vnd.ms-excel', 'text/plain', 'text/csv', 'text/tsv'); // checked csv type
            if ($_FILES['icsv']['size'] > 0 && in_array($_FILES['icsv']['type'], $mimes)) {


                $files = $_FILES['icsv'];
                // $file = $_FILES['icsv']['tmp_name']; 
                // $handle = fopen($file,"r"); 
                $isize = $files['size'];
                for ($i = 0; $i < $isize; $i++) {
                    print_r($files['tmp_name']);
                }

                exit();

                $file_data = $this->upload->data();

                print_r($file_data);
                exit();
                $file_path = './uploads/' . $file_data['file_name'];

                $csv_array = $this->csvimport->get_array($file_path);
                foreach ($csv_array as $row) {
                    $insert_data = array(
                        'firstname' => $row['firstname'],
                        'lastname' => $row['lastname'],
                        'phone' => $row['phone'],
                        'email' => $row['email'],
                    );
                    $this->csv_model->insert_csv($insert_data);
                }
            } else {
                $msgs = "Either the file format is different or there is a problem with the file's contents.";
                $this->importMsgs($msgs);
            }



            // $msgs = "here massages!!";
            // $this->importMsgs($msgs);
            // $this->import();
            // // redirect(base_url("WebAdmin/importError(".$errors.")"), 'refresh');
        } else {
            redirect(base_url("WebAdmin/login"), 'refresh');
        }
    }

    public function settings()
    {
        if (isset($_SESSION["ADMIN"])) {
            $data = array(
                "show" => "settings",
                "setting" => $this->Common_Model->getConfig()->row(1),
                "alladmins" => $this->WebadminModel->getadmins(),
                "adminprofile" => $this->WebadminModel->getadmindetails()
            );
            $this->load->view('admin/includes/head', $data);
            $this->load->view('admin/includes/header');
            $this->load->view('admin/includes/sidebar');
            $this->load->view('admin/settings');
            $this->load->view('admin/includes/footer');
            $this->load->view('admin/includes/jsplugins');
        } else {
            redirect(base_url("WebAdmin/login"), 'refresh');
        }
    }

    public function openHomepage()
    {
        $open_flag = $this->input->get_post("open_flag");

        $data = array();
        $data['open_flag'] = ($open_flag) ? $open_flag : 0;
        $this->Common_Model->updateConfig($data);

        redirect('WebAdmin/settings', 'refresh');
    }

    public function updateadmin()
    {
        $adminid = $this->input->post("adminid");
        $data = array(
            "admin_name" => $this->input->post("name"),
            "admin_email" => $this->input->post("email"),
            "admin_pw" => password_hash($this->input->post("pw"), PASSWORD_DEFAULT)
        );
        $this->WebadminModel->adminupdate($data, $adminid);
        redirect('WebAdmin/settings', 'refresh');
    }

    public function forgotpassword()
    {
        $randpw = random_string('alnum', 10);

        $email = $this->input->post("email");

        $chek = $this->WebadminModel->checkemail($email);
        if ($chek) {

            $this->WebadminModel->updatepassword($email, $randpw);

            $config['protocol'] = 'smtp';
            $config['smtp_host'] = 'smtp.mandrillapp.com';
            $config['smtp_port'] = '587';
            $config['smtp_user'] = 'Propertimax';
            $config['smtp_pass'] = 'Zh4yjTIHtPb4vwD3GtdMMA';

            $config['mailtype'] = 'html'; // or html



            $this->email->initialize($config);

            $this->email->from('noreply@propertimax.co.nz', 'Administration');
            $this->email->to($email);

            if ($this->input->post("cnd") == "ok") {
                $this->email->subject('Propertimax Administration');
                $this->email->message('<p style="text-align:center;" >Folowing is the automatically generated password for the Propertimax Admin panel. You need to enter your email and this password to login. Also your password can be changed in your settings tab.</p> <h4 style="text-align:center;" > Your Password is: ' . $randpw . '</h4>');
            } else {
                $this->email->subject('Propertimax Administration Password Recovery');
                $this->email->message('<p style="text-align:center;" >Seems like you requested a temporary Password. You need to enter your email and this password to login. Also your password can be changed in your settings tab.</p> <h4 style="text-align:center;" > Your Password is: ' . $randpw . '</h4>');
            }


            $this->email->send();


            redirect($_SERVER['HTTP_REFERER'] . '?msg=ok', 'refresh');
        } else {
            redirect($_SERVER['HTTP_REFERER'] . '?msg=no', 'refresh');
        }
    }

    public function insertadmin()
    {

        if (isset($_SESSION["ADMIN"])) {

            $randpw = random_string('alnum', 10);

            $data = array(
                "admin_email" => $this->input->post("email"),
                "admin_name" => $this->input->post("name"),
                "admin_access" => $this->input->post("adminacs"),
                "admin_pw" => password_hash($randpw, PASSWORD_DEFAULT)
            );
            $this->WebadminModel->addadmin($data);

            $config['protocol'] = 'smtp';
            $config['smtp_host'] = 'smtp.mandrillapp.com';
            $config['smtp_port'] = '587';
            $config['smtp_user'] = 'Propertimax';
            $config['smtp_pass'] = 'Zh4yjTIHtPb4vwD3GtdMMA';

            $config['mailtype'] = 'html'; // or html

            $this->email->initialize($config);

            $this->email->from('noreply@propertimax.co.nz', 'Administration');
            $this->email->to($this->input->post("email"));

            $this->email->subject('Welcome to the Propertimax Administration');
            $this->email->message('<p style="text-align:center;" >Folowing is the automatically generated password for the Propertimax Admin panel. You need to enter your email and this password to login. Also your password can be changed in your settings tab.</p> <h4 style="text-align:center;" > Your Password is: ' . $randpw . '</h4>');

            $this->email->send();

            redirect($_SERVER['HTTP_REFERER'] . '?msg=ok', 'refresh');
        } else {
            redirect($_SERVER['HTTP_REFERER'] . '?msg=no', 'refresh');
        }
    }

    public function deleteadmin()
    {
        $adminid = $this->input->post("adminid");
        $this->WebadminModel->deleteadmin($adminid);
    }

    public function News()
    {
        if (isset($_SESSION["ADMIN"])) {
            $news_list = $this->Common_Model->selectNewsList();
            $sidebar["show"] = "news";
            $result = array();
            $result["news_lists"] = $news_list;

            $this->load->view('admin/includes/head');
            $this->load->view('admin/includes/header');
            $this->load->view('admin/includes/sidebar', $sidebar);
            $this->load->view('admin/news', $result);
            $this->load->view('admin/includes/footer');
            $this->load->view('admin/includes/jsplugins');
        } else {
            redirect(base_url("WebAdmin/login"), 'refresh');
        }
    }

    public function modNews()
    {
        if (isset($_SESSION["ADMIN"])) {
            $news_id = $this->input->post_get('news_id');
            $news_title = $this->input->post_get('news_title');
            $news_description = $this->input->post_get('news_description');
            $news_pic = "";

            $data = array();
            $data['news_title'] = $news_title;
            $data['news_description'] = $news_description;


            $attachNameFist = "news_pic";

            if ($_FILES) {
                $config['upload_path'] = "./images/profile/";
                $config['allowed_types'] = '*';
                $config['max_size'] = 6310000;
                $config['overwrite'] = true;
                $config['encrypt_name'] = TRUE;
                $this->load->library('upload', $config);

                if ($_FILES[$attachNameFist]['name']) {
                    // delete exist first image file

                    if ($this->upload->do_upload($attachNameFist)) {
                        $datapic = $this->upload->data();
                        $news_pic = "images/profile/" . $datapic["file_name"];
                    } else {
                        $error = array('error' => $this->upload->display_errors());
                        print_r($error);
                        exit();
                    }
                }
            }

            $data['news_pic'] = $news_pic;
            $data['news_update'] = $this->dateTime();

            $result = $this->Common_Model->updateNews($data, $news_id);

            redirect('WebAdmin/News');
        } else {
            redirect(base_url("WebAdmin/login"), 'refresh');
        }
    }

    public function remNews()
    {
        if (isset($_SESSION["ADMIN"])) {
            $news_id = $this->input->post_get('news_id');
            $result = $this->Common_Model->deleteNews($news_id);
            redirect('WebAdmin/News');
        } else {
            redirect(base_url("WebAdmin/login"), 'refresh');
        }
    }

    function addNews()
    {
        if (isset($_SESSION["ADMIN"])) {
            $news_title = $this->input->post_get('news_title');
            $news_description = $this->input->post_get('news_description');
            $news_pic = "";

            $data = array();
            $data['news_title'] = $news_title;
            $data['news_description'] = $news_description;


            $attachNameFist = "news_pic";

            if ($_FILES) {
                $config['upload_path'] = "./images/profile/";
                $config['allowed_types'] = '*';
                $config['max_size'] = 6310000;
                $config['overwrite'] = true;
                $config['encrypt_name'] = TRUE;
                $this->load->library('upload', $config);

                if ($_FILES[$attachNameFist]['name']) {
                    // delete exist first image file

                    if ($this->upload->do_upload($attachNameFist)) {
                        $datapic = $this->upload->data();
                        $news_pic = "images/profile/" . $datapic["file_name"];
                    } else {
                        $error = array('error' => $this->upload->display_errors());
                        print_r($error);
                        exit();
                    }
                }
            }



            $data['news_pic'] = $news_pic;
            $data['news_indate'] = $this->dateTime();
            $data['news_update'] = $this->dateTime();



            $result = $this->Common_Model->insertNews($data);

            redirect('WebAdmin/News');
        } else {
            redirect(base_url("WebAdmin/login"), 'refresh');
        }
    }

    //  21-01-2019


    public function privateagents_reg()
    {
        $sidebar["show"] = "pagent";
        $this->load->view('admin/includes/head');
        $this->load->view('admin/includes/header');
        $this->load->view('admin/includes/sidebar', $sidebar);
        $this->load->view('admin/privateagent/agent');
        $this->load->view('admin/includes/footer');
        $this->load->view('admin/includes/jsplugins');
    }

    public function privateagents()
    {
        $sidebar["show"] = "pagentv";
        $res["result"] = $this->WebadminModel->getprivateagents();
        $this->load->view('admin/includes/head');
        $this->load->view('admin/includes/header');
        $this->load->view('admin/includes/sidebar', $sidebar);
        $this->load->view('admin/privateagent/all_agents', $res);
        $this->load->view('admin/includes/footer');
        $this->load->view('admin/includes/jsplugins');
    }

    public function signUpprivateAgents()
    {
        $name = $this->input->post_get('name');
        $email = $this->input->post_get('email');
        $mobile = $this->input->post_get('mobile');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || empty($name)) {
            //$this->errorMsg("Check the transmitted parameters.");
            redirect($_SERVER['HTTP_REFERER'] . '?msg=invalid', 'refresh');
        } else {
            $data = $this->Customer_Model->selectCustomerEmail($email);
            if ($data->chk_email) { // Exist Email
                //$this->errorMsg("Your Email account exists.");
                redirect($_SERVER['HTTP_REFERER'] . '?msg=email', 'refresh');
            } else if (!empty($data->facebook_token)) { // Exist Email
                //$this->errorMsg("Your Facebook account exists.");
                redirect($_SERVER['HTTP_REFERER'] . '?msg=fb', 'refresh');
            } else if (!empty($data->google_token)) { // Exist Email
                //$this->errorMsg("Your Google+ account exists.");
                redirect($_SERVER['HTTP_REFERER'] . '?msg=google', 'refresh');
            } else { // New Registration
                $data = array();
                $data['customer_type'] = 0;
                $data['is_private_agent'] = 1;
                $data['customer_name'] = $name;
                $data['customer_mobile'] = $mobile;
                $randpw = random_string('alnum', 10);
                $data['customer_password'] = password_hash($randpw, PASSWORD_DEFAULT);
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

                $id = $this->Customer_Model->insertCustomerId($data);
                $config['protocol'] = 'smtp';
                $config['smtp_host'] = 'smtp.mandrillapp.com';
                $config['smtp_port'] = '587';
                $config['smtp_user'] = 'Propertimax';
                $config['smtp_pass'] = 'Zh4yjTIHtPb4vwD3GtdMMA';

                $config['mailtype'] = 'html'; // or html

                $this->email->initialize($config);

                $this->email->from('noreply@propertimax.co.nz', 'Administration');
                $this->email->to($email);

                $this->email->subject('Welcome to the Propertimax');
                $this->email->message('<p style="text-align:center;" >Folowing is the automatically generated password for the Propertimax Account. You need to enter your email and this password to login. Also your password can be changed in your settings tab.</p> <h4 style="text-align:center;" > Your Password is: ' . $randpw . '</h4>');

                $this->email->send();
                redirect($_SERVER['HTTP_REFERER'] . '?msg=ok', 'refresh');
            }
        }
    }
}
