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
class Maxautoadmin extends Base_controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('Maxauto_Model');
        $this->load->model('Dealership_Model');
    }


    public function contactus()
    {
        if (!isset($_SESSION['ADMIN'])) {
            redirect("/dealership/login");
        } else {
            $id = $_SESSION["ADMIN"];
            $data2["page"] = "message";
            $data["messages"] = $this->Maxauto_Model->selectContactUs();
            $this->load->view('maxautoadmin/includes/header.php', $data2);
            $this->load->view('maxautoadmin/contactuslist.php', $data);
            $this->load->view('maxautoadmin/includes/footer.php');
        }
    }



    public function messages()
    {
        if (!isset($_SESSION['ADMIN'])) {
            redirect("/dealership/login");
        } else {
            $id = $_SESSION["ADMIN"];
            $data2["page"] = "message";
            $data["dealer"] = $this->Maxauto_Model->selectDealership();
            $data["messages"] = $this->Dealership_Model->findMessageByAdmin();
            $this->load->view('maxautoadmin/includes/header.php', $data2);
            $this->load->view('maxautoadmin/messagelist.php', $data);
            $this->load->view('maxautoadmin/includes/footer.php');
        }
    }

    public function sendMessage()
    {
        if (!isset($_SESSION['ADMIN'])) {
            redirect("/dealership/login");
        } else {

            $sold["subject"] = $this->input->post_get('subject');
            $sold["message"] = $this->input->post_get('message');
            $sold["fk_dealership"] = $this->input->post_get('business');
            $sold["is_admin"] = 1;


            $this->Dealership_Model->sendMessage($sold);
            redirect("/Maxautoadmin/messages");
        }
    }

    public function statusMesssage($id, $status, $is_admin)
    {
        if ($is_admin == 0) {
            $sold["status"] = $status;
        } else {
            $sold["status_admin"] = $status;
        }
        $this->Dealership_Model->updateMessage($sold, $id);
    }

    public function closeMessage($id, $status, $is_admin)
    {
        if ($is_admin == 0) {
            $sold["status"] = $status;
        } else {
            $sold["status_admin"] = $status;
        }
        $this->Dealership_Model->updateMessage($sold, $id);
        redirect("/Maxautoadmin/messages");
    }

    public function addReply($idMessage)
    {
        if (!isset($_SESSION['ADMIN'])) {
            redirect("/dealership/login");
        } else {
            $sold["is_admin_reply"] = 1;
            $sold["fk_message_id"] = $idMessage;
            $sold["reply"] = $this->input->post_get('message');

            $this->Dealership_Model->sendReply($sold);
            redirect("/Maxautoadmin/viewmessage/" . $idMessage);
        }
    }

    public function viewmessage($idMessage)
    {
        if (!isset($_SESSION['ADMIN'])) {
            redirect("/dealership/login");
        } else {
            $data2["page"] = "message";
            $data["messages"] = $this->Dealership_Model->findMessageById($idMessage);
            $data["replys"] = $this->Dealership_Model->findReplyByMessage($idMessage);


            if ($data["messages"][0]->status_admin != 3) {
                $this->statusMesssage($idMessage, "1", 1);
            }



            $this->load->view('maxautoadmin/includes/header.php', $data2);
            $this->load->view('maxautoadmin/viewmessage.php', $data);
            $this->load->view('maxautoadmin/includes/footer.php');
        }
    }

    public function login()
    {
        $data2["page"] = "login";
        $this->load->view('maxautoadmin/includes/header.php',  $data2);
        $this->load->view('maxautoadmin/login.php');
        $this->load->view('maxautoadmin/includes/footer.php');
    }

    public function appconfig()
    {
        if (!isset($_SESSION['ADMIN'])) {
            redirect("/Maxautoadmin/login");
        } else {
            $data["config"] = $this->Maxauto_Model->selectConfig();
            $data["banners"] = $this->Maxauto_Model->getBanners();
            $data["region"] = $this->Maxauto_Model->selectRegionList();
            $data2["page"] = "app";
            $this->load->view('maxautoadmin/includes/header.php', $data2);
            $this->load->view('maxautoadmin/appconfig.php', $data);
            $this->load->view('maxautoadmin/includes/footer.php');
        }
    }

    public function dashboarda()
    {

        if (!isset($_SESSION['ADMIN'])) {
            redirect("/Maxautoadmin/login");
        } else {
            $data2["page"] = "dashboard";
            $data2["nCustomer"] = $this->Maxauto_Model->selectDashCustomer();
            $data2["nVehicle"] = $this->Maxauto_Model->selectDashVehicle();
            $data2["nSubs"] = $this->Maxauto_Model->selectDashSubsc();
            $data2["nDealer"] = $this->Maxauto_Model->selectDashDealership();

            $this->load->view('maxautoadmin/includes/header.php', $data2);
            $this->load->view('maxautoadmin/dashboard.php');
            $this->load->view('maxautoadmin/includes/footer.php');
        }
    }

    public function signInAdmin()
    {
        $user = $this->input->post_get('user');
        $pass = $this->input->post_get('password');
        $res1 = $this->Maxauto_Model->selectUserAdmin($user, $pass);
        $res =  $res1[0];
        if ($res) {
            if ($res->admin_user == $user & $pass == $res->admin_pass) {
                $_SESSION["ADMIN"] = $res->id;
                $_SESSION["admin_name"] = $res->admin_name;
                redirect("/Maxautoadmin/dashboarda");
            } else {
                redirect(base_url("/Maxautoadmin/login?log=error"), 'refresh');
            }
        } else {
        }
    }

    public function insertadmin()
    {

        $randpw = "alvaroboy55";

        $data = array(
            "admin_user" => $this->input->post("user"),
            "admin_name" => "Alvaro Pavez",
            "admin_pass" => password_hash($randpw, PASSWORD_DEFAULT)
        );
        $this->Maxauto_Model->addadmin($data);
    }

    public function dealership()
    {
        if (!isset($_SESSION['ADMIN'])) {
            redirect("/Maxautoadmin/login");
        } else {
            $data["business"] = $this->Maxauto_Model->businessgrouplist();
            $data["region"] = $this->Maxauto_Model->selectRegionList();
            $data2["page"] = "dealership";
            $this->load->view('maxautoadmin/includes/header.php', $data2);
            $this->load->view('maxautoadmin/dealership.php', $data);
            $this->load->view('maxautoadmin/includes/footer.php');
        }
    }

    // 

    public function apimanagement()
    {
        if (!isset($_SESSION['ADMIN'])) {
            redirect("/Maxautoadmin/login");
        } else {
            $data["dealer"] = $this->Maxauto_Model->selectDealership();
            $data2["page"] = "api";
            $this->load->view('maxautoadmin/includes/header.php', $data2);
            $this->load->view('maxautoadmin/listapi.php', $data);
            $this->load->view('maxautoadmin/includes/footer.php');
        }
    }

    public function businessgroup()
    {
        if (!isset($_SESSION['ADMIN'])) {
            redirect("/Maxautoadmin/login");
        } else {
            //$data["dealer"] = $this->Maxauto_Model->selectDealership();
            $data2["page"] = "buss";
            $this->load->view('maxautoadmin/includes/header.php', $data2);
            $this->load->view('maxautoadmin/business.php');
            $this->load->view('maxautoadmin/includes/footer.php');
        }
    }

    public function businesslist()
    {
        if (!isset($_SESSION['ADMIN'])) {
            redirect("/Maxautoadmin/login");
        } else {
            $data["dealer"] = $this->Maxauto_Model->businessgrouplist();
            $data2["page"] = "busslist";
            $this->load->view('maxautoadmin/includes/header.php', $data2);
            $this->load->view('maxautoadmin/businesslist.php', $data);
            $this->load->view('maxautoadmin/includes/footer.php');
        }
    }



    public function subscription()
    {
        if (!isset($_SESSION['ADMIN'])) {
            redirect("/Maxautoadmin/login");
        } else {
            $data["dealer"] = $this->Maxauto_Model->selectDealership();
            $data["products"] = $this->Maxauto_Model->selectProducts();
            $data2["page"] = "subs";
            $this->load->view('maxautoadmin/includes/header.php', $data2);
            $this->load->view('maxautoadmin/subscription.php', $data);
            $this->load->view('maxautoadmin/includes/footer.php');
        }
    }

    public function editsubscription($id)
    {
        if (!isset($_SESSION['ADMIN'])) {
            redirect("/Maxautoadmin/login");
        } else {
            $data2["page"] = "subs";
            $data["dealer"] = $this->Maxauto_Model->selectDealership();
            $data["products"] = $this->Maxauto_Model->selectProducts();
            $data["subscription"] = $this->Maxauto_Model->selectSubById($id);
            $this->load->view('maxautoadmin/includes/header.php', $data2);
            $this->load->view('maxautoadmin/subscriptionedit.php', $data);
            $this->load->view('maxautoadmin/includes/footer.php');
        }
    }

    public function products()
    {
        if (!isset($_SESSION['ADMIN'])) {
            redirect("/Maxautoadmin/login");
        } else {
            $data2["page"] = "product";
            $data["products"] = $this->Maxauto_Model->selectProducts();
            $this->load->view('maxautoadmin/includes/header.php', $data2);
            $this->load->view('maxautoadmin/productlist.php', $data);
            $this->load->view('maxautoadmin/includes/footer.php');
        }
    }

    //update dealer data
    public function changeStatusProduct($id, $status)
    {
        $dealer["delete_flag"] = $status;
        $success = $this->Maxauto_Model->updateProduct($dealer, $id);
        $this->json_encode_msgs($success);
        return $success;
    }



    public function createProduct()
    {
        if (!isset($_SESSION['ADMIN'])) {
            redirect("/dealership/login");
        } else {

            $data["product_name"] = $this->input->post_get('name');
            $data["product_price"] = $this->input->post_get('price');
            $data["payment_frequency"] = $this->input->post_get('payment');
            $data["description"] = $this->input->post_get('desc');
            $data["max_listing_monthly"] = $this->input->post_get('max');

            $this->Dealership_Model->insertProduct($data);
            redirect("/Maxautoadmin/products");
        }
    }

    public function editProduct($id)
    {
        if (!isset($_SESSION['ADMIN'])) {
            redirect("/dealership/login");
        } else {

            $data["product_name"] = $this->input->post_get('name');
            $data["product_price"] = $this->input->post_get('price');
            $data["payment_frequency"] = $this->input->post_get('payment');
            $data["description"] = $this->input->post_get('desc');
            $data["max_listing_monthly"] = $this->input->post_get('max');

            $this->Dealership_Model->updateProduct($data, $id);
            redirect("/Maxautoadmin/products");
        }
    }


    public function subscriptionlist()
    {
        if (!isset($_SESSION['ADMIN'])) {
            redirect("/Maxautoadmin/login");
        } else {
            $data["dealer"] = $this->Maxauto_Model->selectSubscription();
            $data["noactivedealer"] = $this->Maxauto_Model->selectSubscription("1");
            $data2["page"] = "sublist";
            $this->load->view('maxautoadmin/includes/header.php', $data2);
            $this->load->view('maxautoadmin/subscriptionlist.php', $data);
            $this->load->view('maxautoadmin/includes/footer.php');
        }
    }

    public function listdealership()
    {
        if (!isset($_SESSION['ADMIN'])) {
            redirect("/Maxautoadmin/login");
        } else {
            $data["dealer"] = $this->Maxauto_Model->selectDealership();
            $data2["page"] = "list";
            $data["noactivedealer"] = $this->Maxauto_Model->selectDealership("1");
            $this->load->view('maxautoadmin/includes/header.php', $data2);
            $this->load->view('maxautoadmin/listdealer.php', $data);
            $this->load->view('maxautoadmin/includes/footer.php');
        }
    }

    public function businessedit($id)
    {
        if (!isset($_SESSION['ADMIN'])) {
            redirect("/Maxautoadmin/login");
        } else {
            $data2["page"] = "busslist";
            $data["dealer"] = $this->Maxauto_Model->selectBusinessById($id);
            $this->load->view('maxautoadmin/includes/header.php', $data2);
            $this->load->view('maxautoadmin/businessedit.php', $data);
            $this->load->view('maxautoadmin/includes/footer.php');
        }
    }

    public function editdealership($id)
    {
        if (!isset($_SESSION['ADMIN'])) {
            redirect("/Maxautoadmin/login");
        } else {
            $data2["page"] = "list";
            $data["dealer"] = $this->Maxauto_Model->selectDealershipById($id);
            $data["region"] = $this->Maxauto_Model->selectRegionList();
            $this->load->view('maxautoadmin/includes/header.php', $data2);
            $this->load->view('maxautoadmin/dealershipedit.php', $data);
            $this->load->view('maxautoadmin/includes/footer.php');
        }
    }

    public function edit_app_conf()
    {
        $dealer["price_car"] = $this->input->post_get('car_price');
        $dealer["price_discount_car"] = $this->input->post_get('car_d_price');
        $dealer["price_wanted_list"] = $this->input->post_get('wanted');
        $dealer["price_moto"] = $this->input->post_get('price_moto');
        $dealer["price_discount_moto"] = $this->input->post_get('price_moto_d');
        $this->Maxauto_Model->updateConfig($dealer);


        redirect("/Maxautoadmin/appconfig?updatec=ok");
    }

    public function edit_dealership()
    {
        $dealer["dealership_name"] = $this->input->post_get('dealership_name');
        $dealer["dealership_email"] = $this->input->post_get('dealership_email');
        $dealer["dealer_notes"] = $this->input->post_get('dealership_desc');

        $dealer["company_name"] = $this->input->post_get('company_name');
        $dealer["dealership_website"] = $this->input->post_get('dealership_web');

        $dealer["director_name"] = $this->input->post_get('director_name');
        $dealer["contact_email"] = $this->input->post_get('envoice_email');
        $dealer["contact_person"] = $this->input->post_get('contact_name');
        $dealer["contact_phone"] = $this->input->post_get('contact_phone');

        $dealer["dealership_postal_address"] = $this->input->post_get('postal_address');

        //imagenes
        $photoLogo = $this->input->post_get('imageCroped');
        $photoLogoRec = $this->input->post_get('imageCroped2');

        $imgNameLogo = $this->generateImage($photoLogo);
        $imgNameLogoRec = $this->generateImage($photoLogoRec);

        $imgUrl1 = base_url() . "uploads/" . $imgNameLogo;
        $imgUrl2 = base_url() . "uploads/" . $imgNameLogoRec;

        $s3Url1 = $this->S3Logo($imgUrl1);
        $s3Url2 = $this->S3LogoRec($imgUrl2);


        unlink("/var/www/html/uploads/" . $imgUrl1);
        unlink("/var/www/html/uploads/" . $imgUrl2);
        //

        $dealer["img_base64"] = $s3Url1;
        $dealer["rec_img_base64"] = $s3Url2;


        //add location
        $address["address"] = $this->input->post_get('address');
        $address["lat"] = $this->input->post_get('lat');
        $address["long"] = $this->input->post_get('lon');
        $address["fk_region_id"] = $this->input->post_get('region');

        $location_id = $this->input->post_get('location_id');
        $this->Maxauto_Model->updateLocation($address, $location_id);


        //create dealership
        $id = $this->input->post_get('dealer_id');
        $data2 = $this->Maxauto_Model->updateDealership($dealer, $id);

        $data = $this->json_encode_msgs($data2);

        redirect("/Maxautoadmin/listdealership?update=ok");
    }


    public function create_business_group()
    {
        $dealer["business_name"] = $this->input->post_get('business_name');
        $dealer["business_email"] = $this->input->post_get('business_email');
        $dealer["contact_number"] = $this->input->post_get('contact_number');
        $dealer["contact_person"] = $this->input->post_get('contact_person');
        $dealer["address"] =  $this->input->post_get('address');
        $data2 = $this->Maxauto_Model->createBusinessGroup($dealer);

       redirect("/Maxautoadmin/businesslist/?update=ok");
    }



    public function create_subscription()
    {
        $dealer["fk_branch_id"] = $this->input->post_get('dealership');
        $dealer["subscription_start_date"] = $this->input->post_get('start');
        $dealer["fk_product"] = $this->input->post_get('productn');

        $data2 = $this->Maxauto_Model->creatSubs($dealer);

        redirect("/Maxautoadmin/subscriptionlist/?update=ok");
    }

    public function edit_subscription($id)
    {
        $dealer["fk_branch_id"] = $this->input->post_get('dealership');
        $dealer["subscription_desc"] = $this->input->post_get('subs_desc');
        $dealer["subscription_start_date"] = $this->input->post_get('start');
        $dealer["subscription_end_date"] =  $this->input->post_get('end');

        $dealer["fk_subscription_type_id"] = $this->input->post_get('payment');
        $dealer["subscription_fee"] = $this->input->post_get('fees');
        $data2 = $this->Maxauto_Model->updateSubs($dealer, $id);
    }

    public function edit_business_group($id)
    {
        $dealer["business_name"] = $this->input->post_get('business_name');
        $dealer["business_email"] = $this->input->post_get('business_email');
        $dealer["contact_number"] = $this->input->post_get('contact_number');
        $dealer["contact_person"] = $this->input->post_get('contact_person');
        $dealer["address"] =  $this->input->post_get('address');
        $data2 = $this->Maxauto_Model->updateBusinessGroup($dealer, $id);

        redirect("/Maxautoadmin/businesslist/?update=ok");
    }


    public function generateImage($img)
    {

        $folderPath = "uploads/";
        $image_parts = explode(";base64,", $img);
        $image_type_aux = explode("uploads/", $image_parts[0]);
        $image_base64 = base64_decode($image_parts[1]);
        $name = uniqid() . '.png';
        $file = $folderPath . $name;
        file_put_contents($file, $image_base64);
        return  $name;
        //$this->saveImageAmazomS3($name);
    }



    public function create_banner(){
        $dealer["title"] = $this->input->post_get('title');
        $dealer["title_color"] = $this->input->post_get('titleColour');
        $dealer["subtitle"] = $this->input->post_get('subtitle');
        $dealer["subtitle_color"] = $this->input->post_get('subtitleColour');
        $dealer["button_text"] = $this->input->post_get('buttonText');
        $dealer["button_text_color"] = $this->input->post_get('buttonTextColour');


        $dealer["fk_region"] = $this->input->post_get('region');
        $dealer["number"] = $this->input->post_get('number');


        $dealer["type_action"] = $this->input->post_get('actiontype');
        $dealer["action"] = $this->input->post_get('action');
        $dealer["action_screen"] = $this->input->post_get('actionScreen');

        $tmp_file = $_FILES['imgBg']['tmp_name'];
        $image_name = "banner" . '-' . $_FILES['imgBg']['name'];
        print_r($image_name);
        $urlS3 = $this->imageBannerS3($tmp_file, $image_name, $_FILES);
        $dealer["bg_image"]  = $urlS3;


        //createBanner
        $data2 = $this->Maxauto_Model->createBanner($dealer);

        $data = $this->json_encode_msgs($data2);

        redirect(base_url("/Maxautoadmin/appconfig?create=true"), 'refresh');

    }

    public function delete_banner($id){
        $data2 = $this->Maxauto_Model->deleteBanner($id);
        redirect(base_url("/Maxautoadmin/appconfig?create=true"), 'refresh');
    }

    public function edit_banner($id){
        $dealer["title"] = $this->input->post_get('title');
        $dealer["title_color"] = $this->input->post_get('titleColour');
        $dealer["subtitle"] = $this->input->post_get('subtitle');
        $dealer["subtitle_color"] = $this->input->post_get('subtitleColour');
        $dealer["button_text"] = $this->input->post_get('buttonText');
        $dealer["button_text_color"] = $this->input->post_get('buttonTextColour');
        $dealer["fk_region"] = $this->input->post_get('region');
        $dealer["number"] = $this->input->post_get('number');
        $dealer["button_text"] = $this->input->post_get('buttonText');
        $dealer["button_text_color"] = $this->input->post_get('buttonTextColour');

        $dealer["delete_flag"] = $this->input->post_get('delete');

        //createBanner
        $data2 = $this->Maxauto_Model->updateBanner($dealer,$id);

        $data = $this->json_encode_msgs($data2);

        redirect(base_url("/Maxautoadmin/appconfig?create=true"), 'refresh');


    }


    public function create_dealership()
    {
        $dealer["dealership_name"] = $this->input->post_get('dealership_name');
        $dealer["dealership_email"] = $this->input->post_get('dealership_email');
        $dealer["dealer_notes"] = $this->input->post_get('dealership_desc');


        $dealer["company_name"] = $this->input->post_get('company_name');
        $dealer["dealership_website"] = $this->input->post_get('dealership_web');

        $dealer["director_name"] = $this->input->post_get('director_name');
        $dealer["contact_email"] = $this->input->post_get('envoice_email');
        $dealer["contact_person"] = $this->input->post_get('contact_name');
        $dealer["contact_phone"] = $this->input->post_get('contact_phone');

        $dealer["dealership_postal_address"] = $this->input->post_get('postal_address');
        $dealer["fk_business_group"] = $this->input->post_get('business');


        $dealer['dealership_api_key'] = $this->apiKeyGenerator();



        //imagenes
        $photoLogo = $this->input->post_get('imageCroped');
        $photoLogoRec = $this->input->post_get('imageCroped2');

        $imgNameLogo = $this->generateImage($photoLogo);
        $imgNameLogoRec = $this->generateImage($photoLogoRec);

        $imgUrl1 = base_url() . "uploads/" . $imgNameLogo;
        $imgUrl2 = base_url() . "uploads/" . $imgNameLogoRec;

        $s3Url1 = $this->S3Logo($imgUrl1);
        $s3Url2 = $this->S3LogoRec($imgUrl2);

        
        unlink("/var/www/html/uploads/" . $imgNameLogo);
        unlink("/var/www/html/uploads/" . $imgNameLogoRec);
        //

        $dealer["img_base64"] = $s3Url1;
        $dealer["rec_img_base64"] = $s3Url2;

        //add location
        $address["address"] = $this->input->post_get('address');
        $address["lat"] = $this->input->post_get('lat');
        $address["long"] = $this->input->post_get('lon');
        $address["fk_region_id"] = $this->input->post_get('region');
        $location_id = $this->Maxauto_Model->insertLocation($address);

        $dealer["fk_location"] = $location_id;
        //create dealership
        $data2 = $this->Maxauto_Model->createDealersip($dealer);

        $data = $this->json_encode_msgs($data2);

        redirect(base_url("/Maxautoadmin/listdealership?create=true"), 'refresh');
        //return $data;
    }


    //update dealer data
    public function changeStatusDealership($id, $status)
    {
        $dealer["delete_flag"] = $status;
        $success = $this->Maxauto_Model->updateDealership($dealer, $id);
        $this->json_encode_msgs($success);
        return $success;
    }

    //update dealer data
    public function changeStatusSubs($id, $status)
    {
        $dealer["delete_flag"] = $status;
        $success = $this->Maxauto_Model->updateSubs($dealer, $id);
        $this->json_encode_msgs($success);
        return $success;
    }

    public function changeStatusGroup($id, $status)
    {
        $dealer["delete_flag"] = $status;
        $success = $this->Maxauto_Model->updateBusinessGroup($dealer, $id);
        $this->json_encode_msgs($success);
        return $success;
    }

    protected function chkLogged()
    {
        $logged = 0;
        if (isset($_SESSION['SESS_CUSTOMER_TOKEN']))
            $logged = 1;
        return $logged;
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

        $data2 = $this->Maxauto_Model->findVehicule($flag, $fk_region, $priceFrom, $priceTo, $bodyType, $makeId, $modelId, $odoFrom, $odoTo, $yearFrom, $yearTo, $page, $sort, $logged_code);

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

    public function findVehicleById($id)
    {

        $data2 = $this->Dealership_Model->findVehiculeById($id);

        $data = [];

        foreach ($data2 as $key) {
            //get image
            $val =  $key->vehicule_id;
            $data = $this->Dealership_Model->getAllImages($val);
        }


        $listingType =  $data2[0]->fk_listing_type;

        if ($listingType == "2") { //agency
            $contact = $this->Dealership_Model->getContactAgency($data2[0]->fk_dealership_id);
        } else {
            $contact = $this->Dealership_Model->getContactCustomer($data2[0]->fk_customer);
        }


        $returnData['details'] = $data2;
        $returnData['photos'] = $data;
        $returnData['contact'] = $contact;

        $this->json_encode_msgs($returnData);
        return $returnData;
    }



    //--------------------------------------------------------------------Dealer ---------------------------------------------------------------------
    //get dealer by id
    public function findDealerById($id)
    {
        $data2 = $this->Dealership_Model->findDealerById($id);

        $length = count($data2);

        if ($length > 0) {
            $returnData['deatils'] = $data2;
            $returnData['error'] = "false";
        } else {
            $returnData['deatils'] = "";
            $returnData['error'] = "true";
        }
        $this->json_encode_msgs($returnData);
        return $returnData;
    }


    //update dealer data
    public function updateDealerData($data, $openingHours, $id)
    {
        $data2 = $this->json_decode($data);
        $hours = $this->json_decode($openingHours);

        $success = $this->Dealership_Model->updateDealer($data2, $hours, $id);
        if ($success) {
            $data2 = $this->Dealership_Model->findDealerById($id);
            $returnData['deatils'] = $data2;
            $returnData['error'] = "false";
        } else {
            $returnData['deatils'] = $success;
            $returnData['error'] = "true";
        }
        $this->json_encode_msgs($returnData);
        return $returnData;
    }


    //-------------------------------------------------------------------Salesperson-----------------------------------------------------------------------
    public function getSalespesonById($id)
    {
        $data2 = $this->Dealership_Model->findSalespersonById($id);

        $length = count($data2);

        if ($length > 0) {
            $returnData['deatils'] = $data2;
            $returnData['error'] = "false";
        } else {
            $returnData['deatils'] = "";
            $returnData['error'] = "true";
        }
        $this->json_encode_msgs($returnData);
        return $returnData;
    }


    public function getAllSalesperson()
    {
        $data2 = $this->Dealership_Model->getAllSalesperson();
        $length = count($data2);

        if ($length > 0) {
            $returnData['deatils'] = $data2;
            $returnData['error'] = "false";
        } else {
            $returnData['deatils'] = "";
            $returnData['error'] = "true";
        }
        $this->json_encode_msgs($returnData);
        return $returnData;
    }

    public function updateSalespersonDeatils($data, $id)
    {
        $data2 = $this->json_decode($data);


        $success = $this->Dealership_Model->updateSalesperson($data2, $id);
        if ($success) {
            $data2 = $this->Dealership_Model->findSalespersonById($id);
            $returnData['deatils'] = $data2;
            $returnData['error'] = "false";
        } else {
            $returnData['deatils'] = $success;
            $returnData['error'] = "true";
        }
        $this->json_encode_msgs($returnData);
        return $returnData;
    }


    public function deleteSaleperson($id)
    {
        $success =  $this->Dealership_Model->deleteSaleperson($id);
        if ($success) {
            $data2 = $this->Dealership_Model->getAllSalesperson();
            $returnData['deatils'] = $data2;
            $returnData['error'] = "false";
        } else {
            $returnData['deatils'] = $success;
            $returnData['error'] = "true";
        }
        $this->json_encode_msgs($returnData);
        return $returnData;
    }

    public function createSaleperson($data)
    {
        $success = $this->db->insertSaleperson($data);
        if ($success) {
            $data2 = $this->Dealership_Model->getAllSalesperson();
            $returnData['deatils'] = $data2;
            $returnData['error'] = "false";
        } else {
            $returnData['deatils'] = $success;
            $returnData['error'] = "true";
        }
        $this->json_encode_msgs($returnData);
        return $returnData;
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

    public function removeWashList($customer_id, $vehicle_id)
    {
        $data2 = $this->Maxauto_Model->deleteWashList($customer_id, $vehicle_id);
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
            $key->pic_url = $dat[0]->pic_url;
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



    public function deleteCharging($id)
    {

        $result = $this->Maxauto_Model->deleteCharging($id);
        redirect('maxauto/chargingStation');
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

    public function listCar()
    {
        $veh = json_decode(file_get_contents('php://input'), true);

        $vehicule_db['vehicule_rego'] = strtoupper($veh['regoF']);
        $vehicule_db['fk_region'] = $veh['fk_region'];
        $price1 = str_replace(',', '',  $veh['priceF']);
        $price2 = str_replace('$', '',  $price1);
        $vehicule_db['vehicule_price'] = $price2;
        $vehicule_db['fk_vehicule_make'] = $veh['makeF'];
        $vehicule_db['fk_vehicule_model'] = $veh['modelF'];
        $vehicule_db['fk_vehicule_body_id'] = $veh['bodyF'];
        $vehicule_db['vehicule_year'] = $veh['yearF'];
        $vehicule_db['vehicule_odometer'] = str_replace(',', '', $veh['odoF']);
        $vehicule_db['vehicule_engine'] = $veh['engiF'];
        $vehicule_db['vehicule_transmission'] = $veh['tranF'];
        $vehicule_db['fk_vehicule_fuel'] = $veh['fuelF'];
        $vehicule_db['vehicule_4x4'] = $veh['driveF'];
        $vehicule_db['vehicule_desc'] = $veh['descF'];

        $vehicule_db['phone_contact'] = $veh['phoneF'];
        $vehicule_db['email_contact'] = $veh['emailF'];
        $vehicule_db['fk_customer'] = $veh['customerId'];

        $vehicule_db['fk_vehicule_type'] = $veh['flagType'];

        $vehicule_db['fk_listing_type'] = $veh['typeList'];

        //photos

        $id = $this->Maxauto_Model->insertCar($vehicule_db);

        $data = $this->json_encode_msgs($id);

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
                        // $lable = date('Y-m-d', strtotime($Property_info['property_auction_date']));
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
                        // $lable = date('Y-m-d', strtotime($Property_info['property_auction_date']));
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
                                    // $lable = date('Y-m-d', strtotime($Property_info['property_auction_date']));
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
                                // $lable = date('Y-m-d', strtotime($Property_info['property_auction_date']));
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
                    $resultAgent = $this->Customer_Model->selectTargetAllAgentList($property_id);
                    $result_info['target_agent'] = $resultAgent[0];
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
        return $result;
    }
}

// END
