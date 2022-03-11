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
class Admin extends Base_controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('Admin_Model');
        $this->load->model('Agent_Model');
        $this->load->model('Common_Model');
        $this->load->model('WebadminModel');
        $this->load->helper('url');
        $this->load->helper('string');
        $this->load->library('email');
        $true_val = 1;
    }

    /** PropertiMax - 2.0 API
     * Requirements:
     * URL: https://propertimax.co.nz/max/Admin
     * Transmission method: POST
     * Parameters: 
     */
    public function index()
    {
        if (!isset($_SESSION['SESS_ADMIN'])) {
            redirect('Admin/logIn', 'refresh');
        } else {
            redirect('Admin/personal', 'refresh');
        }
        return;
    }


    function cominf()
    {
        $setting = $this->Common_Model->getConfig()->row(1);

      

        // New Alvaro
        $ip = $this->GetRealUserIp();

        //if ($ip != "47.72.212.139") {
         //   redirect('WebApp/comingsoon', 'refresh');
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


    /** PropertiMax - 2.0 API
     * Requirements:
     * URL: https://propertimax.co.nz/max/Admin/signInEmail
     * Transmission method: POST
     * Parameters: email, password
     */
    public function logIn()
    {

        $this->cominf();

        $email = $this->input->post_get('email');
        $password = $this->input->post_get("password");

        $data = $this->Agent_Model->selectAgentEmail($email);

        if ($data->verify_flag == 1) {
            // Check Email and Password
            if ($data->chk_email && $this->comparePassword($password, $data->agent_password)) {
                $this->session->set_userdata('SESS_ADMIN', $data->admin_flag);
                $this->session->set_userdata('SESS_AGENT_ID', $data->agent_id);
                //$this->session->set_userdata('SESS_AGENCY_ID', $data->fk_agency_id);
                $this->session->set_userdata('SESS_LAST_LOGIN', $data->last_login);
                $this->Agent_Model->updateProfile(array("last_login" => date("Y-m-d H:i:s")), $data->agent_id);
                $this->session->set_userdata('SESS_AGENT_NAME', $data->agent_first_name . " " . $data->agent_last_name);
                // $this->personal();
                redirect('Admin/personal', 'refresh');
                return;
            } else {

                //$this->errorMsg("Email or Password is Incorrect");
                // $this->load->view('admin/includes/head2');

                $this->load->view('admin/login');


                //redirect('Admin/login?e=1', 'refresh');
            }
        } else {
            $this->load->view('admin/login');
            //redirect('Admin/login?e=2', 'refresh');
        }
    }


    /** PropertiMax - 2.0 API
     * Requirements:
     * URL: https://propertimax.co.nz/max/Admin/signInEmail
     */
    public function verifyPassword()
    {

        $this->cominf();

        $email = $_POST['email'];
        $password = $_POST['password'];

        $data = $this->Agent_Model->selectAgentEmail($email);

        if ($data->verify_flag == 1) {
            // Check Email and Password
            if ($data->chk_email && $this->comparePassword($password, $data->agent_password)) {
                $data = "ok";
                $this->json_encode_msgs($data);
                return;
            } else {
                $data = "no";
                $this->json_encode_msgs($data);
            }
        } else {
            $data = "no";
            $this->json_encode_msgs($data);
            //redirect('Admin/login?e=2', 'refresh');
        }
    }





    public function logOut()
    {
        $this->session->unset_userdata('SESS_ADMIN');
        $this->session->unset_userdata('SESS_AGENT_ID');
        $this->session->unset_userdata('SESS_AGENCY_ID');
        $this->session->unset_userdata('SESS_LAST_LOGIN');
        $this->session->sess_destroy();
        redirect($_SERVER['HTTP_REFERER'], 'refresh');
        return;
    }

    public function forgotpassword()
    {
        $email = $this->input->post("email");
        $data = $this->Agent_Model->selectAgentEmail($email);
        if ($data) {
            $randpw = random_string('alnum', 10);

            $datan["agent_password"] = password_hash($randpw, PASSWORD_DEFAULT);
            $this->Agent_Model->updateProfile($datan, $data->agent_id);

            $config['protocol'] = 'smtp';
            $config['smtp_host'] = 'smtp.mandrillapp.com';
            $config['smtp_port'] = '587';
            $config['smtp_user'] = 'Propertimax';
            $config['smtp_pass'] = 'Zh4yjTIHtPb4vwD3GtdMMA';

            $config['mailtype'] = 'html'; // or html

            $this->email->initialize($config);
            //$this->email->set_header('Propertimax Agent Verification', 'Propertimax Agent Verification');
            $this->email->from('info@propertimax.co.nz', 'Propertimax Agent');
            $this->email->to($email);

            $this->email->subject('Password Recovery');
            $this->email->message('<p style="text-align:center;" >Folowing is the System generated password for the Propertimax Agent Admin panel. You need to enter your email and this password to login.</p> <h4 style="text-align:center;" > Your Password is: ' . $randpw . '</h4>');

            //$this->email->send();





        } else {
            redirect('Admin/login?forgt=err', 'refresh');
        }
    }

    public function forgotpasswordajax()
    {


        $emailTo = $_POST['email'];
        $data = $this->Agent_Model->selectAgentEmail($emailTo);
        if ($data) {

            $randpw = random_string('alnum', 10);
            $datan["agent_password"] = password_hash($randpw, PASSWORD_DEFAULT);
            $this->Agent_Model->updateProfile($datan, $data->agent_id);

            $email = new \SendGrid\Mail\Mail();
            $email->setFrom("alex.xu@propertimax.co.nz", "Propertimax");
            $email->setSubject("Password Recovery");
            $email->addTo($emailTo, "Propertimax User");
            $email->addContent(
                "text/html",
                "<p style='text-align:center;' >Your new password is: $randpw </h4>"
            );

            $sendgrid = new \SendGrid('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');

            try {
                $response = $sendgrid->send($email);
                print $response->statusCode() . "\n";
                print_r($response->headers());
                print $response->body() . "\n";
            } catch (Exception $e) {
                echo 'Caught exception: ' . $e->getMessage() . "\n";
            }

            //$this->email->send();
            $data = "success";
            $this->json_encode_msgs($result);
            return;
        } else {
            $data = "error";
            $this->json_encode_msgs($data);
            return;
        }
    }

    public function changepw()
    {
        $agent = $this->Agent_Model->selectTargetAgentList($_SESSION['SESS_AGENT_ID'])[0];
        if (password_verify($this->input->post("curpw"), $agent["agent_password"])) {
            $data = array(
                "agent_password" => password_hash($this->input->post("newpw"), PASSWORD_DEFAULT)
            );
            $this->Agent_Model->updateProfile($data, $_SESSION['SESS_AGENT_ID']);
            redirect(base_url("/Admin/personal?pwd=update"), 'refresh');
        } else {
            redirect(base_url("/Admin/personal?pwd=no"), 'refresh');
        }
    }

    public function changepwbranch()
    {
        $id_branch = $_SESSION["SESS_BRANCH_ID"];
        $branch_info = $this->WebadminModel->selectBranch($id_branch);

        if ($this->input->post("curpw") ==  $branch_info->branch_login_password) {
            $data = array(
                "branch_login_password" => $this->input->post("newpw")
            );

            $this->WebadminModel->updateBranch($id_branch, $data);
            redirect(base_url("/WebApp/adminapp"), 'refresh');
        } else {
            print_r("aca");
        }
    }

    public function dashBoard()
    {
        if (!isset($_SESSION['SESS_AGENT_ID'])) {
            redirect('Admin/logIn', 'refresh');
            return;
        } else {
            // $ar["hzards"] = $this->Hazards_Model->get_all_hazard_types();
            $this->load->view('admin/includes/head2');
            $this->load->view('admin/includes/header2');
            $this->load->view('admin/includes/leftSidebar2');
            $this->load->view('admin/htypes');
            $this->load->view('admin/includes/footer');
            $this->load->view('admin/includes/rightSidebar');
            $this->load->view('admin/includes/jsplugings');
        }
    }

    public function personal()
    {


        if (!isset($_SESSION['SESS_AGENT_ID'])) {
            redirect('Admin/logIn', 'refresh');
            return;
        } else {
            $result = $this->Agent_Model->selectTargetAgentList($_SESSION['SESS_AGENT_ID']);
            $lagent =   $this->Agent_Model->selectTargetAgentListLanguages($_SESSION['SESS_AGENT_ID']);

            $info_agent = $result[0];
            $agencies = $this->Agent_Model->get_all_agnecies();
            $flags = $this->Common_Model->selectLanguage();

            $branches = $this->Agent_Model->get_all_branches();
            $this->load->view('admin/includes/newAgentprofilehead', array("agent" => $info_agent, "agency" => $agencies, "branch" => $branches, "flags" => $flags, "lagent" => $lagent));
            $this->load->view('admin/includes/newAgentprofilemenu');
            $this->load->view('admin/includes/newAgentprofilemainmenu');
            $this->load->view('admin/newAgentprofile');
            $this->load->view('admin/includes/newAgentprofilefooter');
            $this->load->view('admin/includes/newAgentprofilejsplugins');
        }
    }

    public function personalNew()
    {
        if (!isset($_SESSION['SESS_AGENT_ID'])) {
            redirect('Admin/logIn', 'refresh');
            return;
        } else {
            $result = $this->Agent_Model->selectTargetAgentList($_SESSION['SESS_AGENT_ID']);


            $info_agent = $result[0];
            $agencies = $this->Agent_Model->get_all_agnecies();

            $branches = $this->Agent_Model->get_all_branches();
            $this->load->view('admin/includes/newAgentprofilehead', array("agent" => $info_agent, "agency" => $agencies, "branch" => $branches));
            $this->load->view('admin/includes/newAgentprofilemenu');
            $this->load->view('admin/includes/newAgentprofilemainmenu');
            $this->load->view('admin/newAgentprofilenew');
            $this->load->view('admin/includes/newAgentprofilefooter');
            $this->load->view('admin/includes/newAgentprofilejsplugins');
        }
    }




    public function modagentpic()
    {
        //*****************************CODIGO PARA CARGAR IMAGENES S3**************************

        $tmp_file = $_FILES['userfile']['tmp_name'];
        $image_name = $_SESSION['SESS_AGENT_ID'] . '-' . $_FILES['userfile']['name'];
        $urlS3 = $this->saveImageInS3($tmp_file, $image_name,$_FILES);

        //*****************************CODIGO PARA CARGAR IMAGENES S3**************************
        $data = array(
            "agent_pic" => $urlS3
        );
        $this->Agent_Model->updateProfile($data, $_SESSION['SESS_AGENT_ID']);
        redirect(base_url("/Admin/personal"), 'refresh');

    }

    public function modcoverpic()
    {

        $tmp_file = $_FILES['croppedImage']['tmp_name'];
        $image_name = $_SESSION['SESS_AGENT_ID'] . '-' . $_FILES['croppedImage']['name'];
        $urlS3 = $this->saveImageInS3AgentCover($tmp_file, $image_name);

        //*****************************CODIGO PARA CARGAR IMAGENES S3**************************
        $data = array(
            "agent_pic_cover" => $image_name,
        );
        $this->Agent_Model->updateProfile($data, $_SESSION['SESS_AGENT_ID']);
        redirect(base_url("/Admin/personal"), 'refresh');

        //$this->Agent_Model->updateProfile($data, $_SESSION['SESS_AGENT_ID']);
        //redirect(base_url("/Admin/personal"), 'refresh');

    }

    public function modagentprfile()
    {
        $fname = $this->input->post("fname");
        $lname = $this->input->post("lname");
        $email = $this->input->post("email");
        $mobile = $this->input->post("mobile");
        $phone = $this->input->post("phone");

        $data = array(
            "agent_first_name" => $fname,
            "agent_last_name" => $lname,
            "agent_email" => $email,
            "agent_mobile" => $mobile,
            "agent_phone" => $phone
        );
        $this->Agent_Model->updateProfile($data, $_SESSION['SESS_AGENT_ID']);
    }

    // Alvaro new

    public function changeVisibilityProfile()
    {
        $value = $this->input->post("status");
        $result = $this->Agent_Model->publicAgent($_SESSION['SESS_AGENT_ID'], $value);

        $this->output->set_output(json_encode($result));
        return $result;
    }

    public function updateAgentdetails()
    {
        //$email = $this->input->post("email");
        //$mobile = $this->input->post("mobile");
        //$phone = $this->input->post("phone");
        //$asistant = $this->input->post("assm");
        $fname = $this->input->post("fname");
        $lname = $this->input->post("lname");
        //$role = $this->input->post("role");
        //$reaa = $this->input->post("reaa");
        $languages = $this->input->post("selectLanguage");

        $data = array(
            //"agent_occupation" => $role,
            "agent_first_name" => $fname,
            "agent_last_name" => $lname,
            //"agent_license" => $reaa,
        );
        $this->Agent_Model->updateProfile($data, $_SESSION['SESS_AGENT_ID']);



        //update language
        if (isset($languages)) {

            //delete old languages
            $this->Common_Model->deleteLanguagesAgent($_SESSION['SESS_AGENT_ID']);

            foreach ($languages as $language) {
                //add new language

                $this->Common_Model->insertLanguageAgent($_SESSION['SESS_AGENT_ID'], $language);
            }
        } else {
            $this->Common_Model->deleteLanguagesAgent($_SESSION['SESS_AGENT_ID']);
        }

        redirect(base_url("/Admin/personal?update=ok"), 'refresh');
    }

    public function updateAgentdetails2()
    {
        $mobile = $this->input->post("mobile");
        $landline = $this->input->post("phone");
        $email = $this->input->post("email");
        $assis = $this->input->post("assm");
        $web = $this->input->post("web");


        $data = array(
            "agent_mobile" => $mobile,
            "agent_phone" => $landline,
            "agent_email" => $email,
            "agent_assist_mobile" => $assis,
            "agent_website" => $web
        );

        $this->Agent_Model->updateProfile($data, $_SESSION['SESS_AGENT_ID']);
        redirect(base_url("/Admin/personal?update=ok"), 'refresh');
    }

    public function updateAgentdetails3()
    {
        $role = $this->input->post("role");
        $reaa = $this->input->post("reaa");

        $data = array(
            "agent_occupation" => $role,
            "agent_license" => $reaa,
            "fk_branch_id" => $this->input->post("branchid")
        );
        $this->Agent_Model->updateProfile($data, $_SESSION['SESS_AGENT_ID']);
        redirect(base_url("/Admin/personal?update=ok"), 'refresh');
    }

    public function updateAgentdetails4()
    {
        $dec = $this->input->post("feDescription");

        $data = array(
            "agent_description" => $dec
        );
        $this->Agent_Model->updateProfile($data, $_SESSION['SESS_AGENT_ID']);
        redirect(base_url("/Admin/personal?update=ok"), 'refresh');
    }

    public function agentoffice()
    {

        $data = array(
            "fk_branch_id" => $this->input->post("branchid"),
        );
        $this->Agent_Model->updateProfile($data, $_SESSION['SESS_AGENT_ID']);
        //$params = $this->input->post(array("long", "lat"));
        //$params["agent_id"] = $_SESSION['SESS_AGENT_ID'];
        //$this->Agent_Model->insertorupdateagentlocation($params, $params["agent_id"]);

        redirect(base_url("/Admin/personal"), 'refresh');
    }

    public function modagentreea()
    {
        $reea = $this->input->post("reea");

        $data = array(
            "agent_license" => $reea
        );
        $this->Agent_Model->updateProfile($data, $_SESSION['SESS_AGENT_ID']);
    }

    public function modagentagency()
    {
        $agencyid = $this->input->post("agencyid");
        $agencyname = $this->input->post("agencyname");
        $agencyoffice = $this->input->post("agencyoffice");
        $data = array(
            "agency_name" => $agencyname,
            "agency_office" => $agencyoffice
        );
        $this->Agent_Model->updateagency($data, $agencyid);
    }

    public function modagentdec()
    {
        $dec = $this->input->post("agentdec");

        $data = array(
            "agent_description" => $dec
        );
        $this->Agent_Model->updateProfile($data, $_SESSION['SESS_AGENT_ID']);
    }

    public function modagentloc()
    {
        $address = $this->input->post("address");
        $lat = $this->input->post("lat");
        $lon = $this->input->post("lon");

        $data = array(
            "address" => $address,
            "lat" => $lat,
            "long" => $lon
        );
        $this->Agent_Model->updateagentlocation($data, $_SESSION['SESS_AGENT_ID']);
    }

    public function Property()
    {
        if (!isset($_SESSION['SESS_AGENT_ID'])) {
            redirect('Admin/logIn', 'refresh');
            return;
        } else {
            $property_lists = $this->Admin_Model->selectPropertyList($_SESSION['SESS_AGENT_ID'], $_SESSION['SESS_ADMIN']);
            $result["property_lists"] = $property_lists;
            $result["category"] = "property";

            $this->load->view('admin/includes/head2');
            $this->load->view('admin/includes/header2');
            $this->load->view('admin/includes/leftSidebar2');
            $this->load->view('admin/property', $result);
            $this->load->view('admin/includes/footer');
            $this->load->view('admin/includes/rightSidebar');
            $this->load->view('admin/includes/jsplugings');
        }
    }

    public function Assist()
    {
        if (!isset($_SESSION['SESS_AGENT_ID'])) {
            redirect('Admin/logIn', 'refresh');
            return;
        } else {
            $result = array();
            $agent_list = $this->Agent_Model->selectTargetAgentList($_SESSION['SESS_AGENT_ID'], $_SESSION['SESS_ADMIN']);
            $result["agent_list"] = $agent_list[0];
            $property_lists = $this->Agent_Model->selectTargetPropertyList($_SESSION['SESS_AGENT_ID'], $_SESSION['SESS_ADMIN'])->result_array();
            $result["property_lists"] = $property_lists;
            $result["category"] = "assist";

            $this->load->view('admin/includes/head2');
            $this->load->view('admin/includes/header2');
            $this->load->view('admin/includes/leftSidebar2');
            $this->load->view('admin/assist', $result);
            $this->load->view('admin/includes/footer');
            $this->load->view('admin/includes/rightSidebar');
            $this->load->view('admin/includes/jsplugings');
        }
    }

    public function Openhome()
    {
        if (!isset($_SESSION['SESS_AGENT_ID'])) {
            redirect('Admin/logIn', 'refresh');
            return;
        } else {
            $result = array();
            $agent_list = $this->Agent_Model->selectTargetAgentList($_SESSION['SESS_AGENT_ID'], $_SESSION['SESS_ADMIN']);
            $result["agent_list"] = $agent_list[0];
            $property_lists = $this->Agent_Model->selectTargetPropertyList($_SESSION['SESS_AGENT_ID'], $_SESSION['SESS_ADMIN'])->result_array();
            $result["property_lists"] = $property_lists;

            $this->load->view('admin/includes/head2');
            $this->load->view('admin/includes/header2');
            $this->load->view('admin/includes/leftSidebar2');
            $this->load->view('admin/openhome', $result);
            $this->load->view('admin/includes/footer');
            $this->load->view('admin/includes/rightSidebar');
            $this->load->view('admin/includes/jsplugings');
        }
    }

    public function tkAssist()
    { /////////////////// rochas
        $result = array();
        $result['assist_list'] = $this->Agent_Model->selectAssistList($property_id);

        // $this->errorMsg($result);
        // return;

        $this->load->view('admin/includes/head2');
        $this->load->view('admin/includes/header2');
        $this->load->view('admin/includes/leftSidebar2');
        $this->load->view('admin/assist', $result);
        $this->load->view('admin/includes/footer');
        $this->load->view('admin/includes/rightSidebar');
        $this->load->view('admin/includes/jsplugings');
    }

    /** PropertiMax - 2.0 API
     * Requirements:
     * URL: https://propertimax.co.nz/max/Admin/GetAssistAjax
     * Transmission method: POST
     * Parameters: email, password
     */
    public function GetAssistAjax()
    {
        $property_id = $this->input->post_get('property_id');

        $data = array();
        $data['assist_list'] = $this->Agent_Model->selectAssistList($property_id);

        $this->output->set_output(json_encode($data));
        return $data;
    }

    /** PropertiMax - 2.0 API
     * Requirements:
     * Transmission method: POST
     * Parameters: email, password
     */
    function GetOpenhomeAjax()
    {
        $property_id = $this->input->post_get('property_id');

        $data = array();
        $data['openhome_list'] = $this->Agent_Model->selectOpenHomeList($property_id);
        $this->output->set_output(json_encode($data));
        return $data;
    }

    function GetVisitorAjax()
    {
        // rochas
        $open_home_id = $this->input->post_get('open_home_id');
        $data = array();
        $data['visitor_list'] = $this->Common_Model->selectAjaxOpenHomeVisitor($open_home_id)->result_array;

        $this->output->set_output(json_encode($data));
        return $data;
    }

    /** PropertiMax - 2.0 API
     * Requirements:
     * URL: https://propertimax.co.nz/max/Admin/GetSearchAssistAjax
     * Transmission method: POST
     * Parameters: email, password
     */
    public function GetSearchAssistAjax()
    {
        //        $key_word = $this->input->post_get('key_word');
        $property_id = $this->input->post_get('property_id');

        $data = array();
        //        $data['result'] = $this->Agent_Model->selectSearchAssist($property_id, $_SESSION['SESS_AGENCY_ID'], $_SESSION['SESS_AGENT_ID'], $key_word);
        $data['result'] = $this->Agent_Model->getallagentsinagency($_SESSION['SESS_AGENCY_ID'], $_SESSION['SESS_AGENT_ID'], $property_id);

        $this->output->set_output(json_encode($data));
        return $data;
    }

    /** PropertiMax - 2.0 API
     * Requirements:
     * URL: https://propertimax.co.nz/max/Admin/SetAssistAjax
     * Transmission method: POST
     * Parameters: email, password
     */
    public function SetAssistAjax()
    {

        $agent_id = $this->input->post_get('assist_agent_id');
        $property_id = $this->input->post_get('property_id');

        $data = array();
        $data['assist_agent_id'] = $agent_id;
        $data['assist_property_id'] = $property_id;
        $result = $this->Agent_Model->insertAddList($data);

        $this->json_encode_msgs($result);
        return;
    }

    /** PropertiMax - 2.0 API
     * Requirements:
     * URL: https://propertimax.co.nz/max/Admin
     * Transmission method: POST
     * Parameters: email, password
     */
    public function DelAssistAjax()
    {
        $agent_id = $this->input->post_get('assist_agent_id');
        $property_id = $this->input->post_get('property_id');
        $result = $this->Agent_Model->deleteAssist($agent_id, $property_id);
        $this->json_encode_msgs($result);
        return;
    }

    // Only Admin
    public function News()
    {
        if (!isset($_SESSION['SESS_AGENT_ID'])) {
            redirect('Admin/logIn', 'refresh');
            return;
        } else {
            if ($_SESSION['SESS_ADMIN'] == 0) { // 0: Agent, 1: Admin
                redirect('Admin/personal', 'refresh');
                return;
            } else {
                $news_list = $this->Common_Model->selectNewsList();

                $result = array();
                $result["news_lists"] = $news_list;
                $this->load->view('admin/includes/head');
                $this->load->view('admin/includes/header');
                $this->load->view('admin/includes/leftSidebar');
                $this->load->view('admin/news', $result);
                $this->load->view('admin/includes/footer');
                $this->load->view('admin/includes/rightSidebar');
                $this->load->view('admin/includes/jsplugings');
            }
        }
    }

    public function Manager()
    {
        if (!isset($_SESSION['SESS_AGENT_ID'])) {
            redirect('Admin/logIn', 'refresh');
            return;
        } else {
            if ($_SESSION['SESS_ADMIN'] == 0) {
                redirect('Admin/personal', 'refresh');
                return;
            } else {
                $admin_list = $this->Admin_Model->selectAdminList();

                $result = array();
                $result["admin_lists"] = $admin_list;
                $this->load->view('admin/includes/head');
                $this->load->view('admin/includes/header');
                $this->load->view('admin/includes/leftSidebar');
                $this->load->view('admin/manager', $result);
                $this->load->view('admin/includes/footer');
                $this->load->view('admin/includes/rightSidebar');
                $this->load->view('admin/includes/jsplugings');
            }
        }
    }

    public function Agent()
    {
        if (!isset($_SESSION['SESS_AGENT_ID'])) {
            redirect('Admin/logIn', 'refresh');
            return;
        } else {
            if ($_SESSION['SESS_ADMIN'] == 0) {
                redirect('Admin/personal', 'refresh');
                return;
            } else {
                $agency_list = $this->Common_Model->selectAgencyList();
                $agent_list = $this->Admin_Model->selectAgentList();

                $result = array();
                $result["agency_lists"] = $agency_list;
                $result["agent_lists"] = $agent_list;
                $this->load->view('admin/includes/head');
                $this->load->view('admin/includes/header');
                $this->load->view('admin/includes/leftSidebar');
                $this->load->view('admin/agent', $result);
                $this->load->view('admin/includes/footer');
                $this->load->view('admin/includes/rightSidebar');
                $this->load->view('admin/includes/jsplugings');
            }
        }
    }

    //////////////////////////////////////////////////////////////////////////

    /** PropertiMax - 2.0 API
     * Requirements:
     * URL: https://propertimax.co.nz/max/Admin/allNewsList
     * Transmission method: POST
     * Parameters: email, password
     */
    public function allNewsList()
    {
        $news_list = $this->Common_Model->selectNewsList();
        $this->json_encode_msgs($news_list);
        return;
    }

    /** PropertiMax - 2.0 API
     * Requirements:
     * URL: https://propertimax.co.nz/max/Admin/allVisitorList
     * Transmission method: POST
     * Parameters: email, password
     */
    public function allVisitorList()
    {
        $visitor_list = $this->Common_Model->selectNewsList();
        $this->json_encode_msgs($visitor_list);
        return;
    }

    /** PropertiMax - 2.0 API
     * Requirements:
     * URL: https://propertimax.co.nz/max/Admin/targetNewsList
     * Transmission method: POST
     * Parameters: email, password
     */
    public function targetNewsList()
    {
        $news_id = $this->input->post_get('news_id');
        $news_list = $this->Common_Model->selectTargetNews($news_id);

        $this->json_encode_msgs($news_list);
        return;
    }

    /** PropertiMax - 2.0 API
     * Requirements:
     * Transmission method: POST
     * Parameters: name, email, password
     */
    public function addAgent()
    {

        $first_name = $this->input->post_get('first_name');
        $last_name = $this->input->post_get('last_name');
        $email = $this->input->post_get('email');
        $mobile = $this->input->post_get('mobile');
        $phone = $this->input->post_get('phone');
        $password = $this->input->post_get("password");
        $agency_id = $this->input->post_get("agency_id");

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
                $agent['agent_first_name'] = $first_name;
                $agent['agent_last_name'] = $last_name;
                $agent['agent_password'] = password_hash($password, PASSWORD_DEFAULT);
                $agent['agent_email'] = $email;
                $agent['agent_mobile'] = $mobile;
                $agent['agent_phone'] = $phone;
                $agent['agent_code'] = $agent_code;
                $agent['fk_agency_id'] = $agency_id;
                $agent['fk_location_id'] = $location_id;

                $result = $this->Agent_Model->inserAgent($agent);

                redirect('Admin/Agent');
                return;
            }
        }
    }

    /** PropertiMax - 2.0 API
     * Requirements:
     * Transmission method: POST
     * Parameters: name, email, password
     */
    public function addManager()
    {

        $first_name = $this->input->post_get('first_name');
        $last_name = $this->input->post_get('last_name');
        $email = $this->input->post_get('email');
        $mobile = $this->input->post_get('mobile');
        $phone = $this->input->post_get('phone');
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
            $agent_code = $this->uniqid_base36('admin'); // Gen Unique Code

            if ($location_id > 0) {
                $agent = array();
                $agent['agent_first_name'] = $first_name;
                $agent['agent_last_name'] = $last_name;
                $agent['agent_password'] = password_hash($password, PASSWORD_DEFAULT);
                $agent['agent_email'] = $email;
                $agent['agent_mobile'] = $mobile;
                $agent['agent_phone'] = $phone;
                $agent['agent_code'] = $agent_code;
                $agent['admin_flag'] = 1;
                $agent['fk_agency_id'] = 0; // ADMIN
                $agent['fk_location_id'] = $location_id;

                $result = $this->Agent_Model->inserAgent($agent);

                redirect('Admin/Manager');
                return;
            }
        }
    }

    /** PropertiMax - 2.0 API
     * Requirements:
     * Transmission method: POST
     * Parameters: news_title, news_description, news_pic
     */
    function addNews()
    {
        $news_title = $this->input->post_get('news_title');
        $news_description = $this->input->post_get('news_description');
        $news_pic = $this->input->post_get('news_pic');

        $data = array();
        $data['news_title'] = $news_title;
        $data['news_description'] = $news_description;
        $data['news_pic'] = $news_pic;
        $data['news_indate'] = $this->dateTime();
        $data['news_update'] = $this->dateTime();

        $result = $this->Common_Model->insertNews($data);

        redirect('Admin/News');
        return;
    }

    /**
     * Requirements:
     * Transmission method: POST
     * Parameters: name, email, password
     */
    public function modPersonal()
    {
        $first_name = $this->input->post_get('first_name');
        $last_name = $this->input->post_get('last_name');
        $mobile = $this->input->post_get('mobile');
        $phone = $this->input->post_get('phone');
        $news_pic = $this->input->post_get('news_pic'); // rochas

        $data = array();
        $data['agent_first_name'] = $first_name;
        $data['agent_last_name'] = $last_name;
        $data['agent_mobile'] = $mobile;
        $data['agent_phone'] = $phone;

        $result = $this->Common_Model->updateAgent($data, $_SESSION['SESS_AGENT_ID']);

        redirect('Admin/Personal');
        return;
    }

    /**
     * Requirements:
     * Transmission method: POST
     * Parameters: name, email, password
     */
    public function modAgent()
    {
        $agent_id = $this->input->post_get('agent_id');
        $agency_id = $this->input->post_get('agency_id');
        $first_name = $this->input->post_get('first_name');
        $last_name = $this->input->post_get('last_name');
        $mobile = $this->input->post_get('mobile');
        $phone = $this->input->post_get('phone');
        $news_pic = $this->input->post_get('news_pic'); // rochas

        $data = array();
        $data['agent_first_name'] = $first_name;
        $data['agent_last_name'] = $last_name;
        $data['agent_mobile'] = $mobile;
        $data['agent_phone'] = $phone;
        $data['fk_agency_id'] = $agency_id;

        $result = $this->Common_Model->updateAgent($data, $agent_id);

        redirect('Admin/Agent');
        return;
    }

    /**
     * Requirements:
     * Transmission method: POST
     * Parameters: name, email, password
     */
    public function modManager()
    {
        $agent_id = $this->input->post_get('agent_id');
        $first_name = $this->input->post_get('first_name');
        $last_name = $this->input->post_get('last_name');
        $mobile = $this->input->post_get('mobile');
        $phone = $this->input->post_get('phone');
        $news_pic = $this->input->post_get('news_pic'); // rochas

        $data = array();
        $data['agent_first_name'] = $first_name;
        $data['agent_last_name'] = $last_name;
        $data['agent_mobile'] = $mobile;
        $data['agent_phone'] = $phone;

        $result = $this->Common_Model->updateAgent($data, $agent_id);

        redirect('Admin/Manager');
        return;
    }

    /**
     * Requirements:
     * Transmission method: POST
     * Parameters: name, email, password
     */
    public function modNews()
    {
        $news_id = $this->input->post_get('news_id');
        $news_title = $this->input->post_get('news_title');
        $news_description = $this->input->post_get('news_description');
        $news_pic = $this->input->post_get('news_pic');

        $data = array();
        $data['news_title'] = $news_title;
        $data['news_description'] = $news_description;
        $data['news_pic'] = $news_pic;
        $data['news_update'] = $this->dateTime();

        $result = $this->Common_Model->updateNews($data, $news_id);

        redirect('Admin/News');
        return;
    }

    public function updateopenhone()
    {
        $todate = $this->input->post_get('todate');
        $fromdate = $this->input->post_get('fromdate');
        $propeid = $this->input->post_get('property_id');
        $date = array(
            "fk_property_id" => $propeid,
            "open_home_to" => $todate,
            "open_home_from" => $fromdate
        );
        $this->Agent_Model->insertOpenHome($date);
        redirect('Admin/openhome');
    }

    public function removeopenhone()
    {
        $openhomeid = $this->input->post_get('openhomeid');
        $this->Agent_Model->deleteOpenHome($openhomeid);
        redirect('Admin/openhome');
    }

    /**
     * Requirements:
     * Transmission method: POST
     * Parameters: news_id
     */
    public function remPersonal()
    {
        $result = $this->Common_Model->deleteAgent($_SESSION['SESS_AGENT_ID']);
        $this->logOut();
    }

    /**
     * Requirements:
     * Transmission method: POST
     * Parameters: news_id
     */
    public function remAgent()
    {
        $agent_id = $this->input->post_get('agent_id');
        $result = $this->Common_Model->deleteAgent($agent_id);

        redirect('Admin/Agent');
        return;
    }

    public function remProperty()
    {
        $p_id = $this->input->post_get('pid');
        $result = $this->Agent_Model->deleteProperty($p_id);

        redirect('Admin/property');
        return;
    }

    /**
     * Requirements:
     * Transmission method: POST
     * Parameters: news_id
     */
    public function remManager()
    {
        $agent_id = $this->input->post_get('agent_id');
        $result = $this->Common_Model->deleteAgent($agent_id);

        redirect('Admin/Manager');
        return;
    }

    /**
     * Requirements:
     * Transmission method: POST
     * Parameters: news_id
     */
    public function remNews()
    {
        $news_id = $this->input->post_get('news_id');
        $result = $this->Common_Model->deleteNews($news_id);

        redirect('Admin/News');
        return;
    }

    function uuid()
    {
        $data = random_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    public function getAPIKey()
    {
        // $uid = uniqid();
        // $api_key = hash('sha256', (time() . $uid . rand()));
        print_r($this->uuid());
    }

    public function cronTabs()
    {
        // [sales_listing_term] => 6
        // [rental_listing_term] => 4
        // [api_key] => 97e81e0e-95e1-4ffa-858f-472e3bbe2640
        $key = $this->input->post_get('key');
        $configs = $this->Common_Model->getConfig()->row(0);

        if ($configs->api_key === $key) {
            $sales_listing_term = $configs->sales_listing_term;
            $rental_listing_term = $configs->rental_listing_term;
            $result = $this->Common_Model->updateCronTabExpireList($sales_listing_term, $rental_listing_term);
            $this->savelog('Cron Job [ Result: ' . $result . ', Sales listing term: ' . $sales_listing_term . ', Rental listing term: ' . $rental_listing_term . ' ]'); // Log;
        }
    }

    public function cronTabs02()
    {
        // [api_key] => 97e81e0e-95e1-4ffa-858f-472e3bbe2640
        $key = $this->input->post_get('key');
        $configs = $this->Common_Model->getConfig()->row(0);

        if ($configs->api_key === $key) {
            $update_cnt = $this->Common_Model->chkCronTabExpireCoupon()->num_rows();

            if ($update_cnt > 0) {
                $result = $this->Common_Model->updateCronTabExpireCoupon();
                $this->savelog('Cron Job [ Result: ' . $result . ', Expired coupons: ' . $update_cnt . ' ]'); // Log;
            }
        }
    }
}
