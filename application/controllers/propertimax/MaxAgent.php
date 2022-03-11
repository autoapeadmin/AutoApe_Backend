<?php

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
class MaxAgent extends Base_controller {

    function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('Admin_Model');
        $this->load->model('Agent_Model');
        $this->load->model('Common_Model');
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
    public function index() {
        if (!isset($_SESSION['SESS_ADMIN'])) {
            redirect('maxAgent/logIn', 'refresh');
        } else {
            redirect('maxAgent/personal', 'refresh');
        }
        return;
    }

    /** PropertiMax - 2.0 API
     * Requirements:
     * URL: https://propertimax.co.nz/max/maxAgent/signInEmail
     * Transmission method: POST
     * Parameters: email, password
     */
    public function logIn() {
        if (isset($_SESSION['SESS_AGENT_ID'])) {
            redirect('maxAgent/personal', 'refresh');
            return;

        } else {
            $email = $this->input->post_get('email');
            $password = $this->input->post_get("password");
    
            $data = $this->Agent_Model->selectAgentEmail($email);
            // Check Email and Password
            if ($data->chk_email && $this->comparePassword($password, $data->agent_password)) {
                $this->session->set_userdata('SESS_ADMIN', $data->admin_flag);
                $this->session->set_userdata('SESS_AGENT_ID', $data->agent_id);
                $this->session->set_userdata('SESS_AGENCY_ID', $data->fk_agency_id);
                $this->session->set_userdata('SESS_AGENT_NAME', $data->agent_first_name . " " . $data->agent_last_name);
                // $this->personal();
                redirect('maxAgent/personal', 'refresh');
                return;
            }
        }
        $this->load->view('maxAgent/login');
        return;
    }

    public function logOut() {
        $this->session->unset_userdata('SESS_ADMIN');
        $this->session->unset_userdata('SESS_AGENT_ID');
        $this->session->unset_userdata('SESS_AGENCY_ID');
        redirect($_SERVER['HTTP_REFERER'], 'refresh');
        return;
    }

    public function signUp() {
        $first_name = $this->input->post_get('first_name');
        $last_name = $this->input->post_get('last_name');
        $email = $this->input->post_get('email');
        $password = $this->input->post_get("password");
        $repassword = $this->input->post_get("repassword");

        if ($password != $repassword) {
            echo "<script>alert('Please re-check you email, password and login again.');</script>";
            redirect('maxAgent/login', 'refresh');
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<script>alert('Please re-check you email, password and login again.');</script>";
            redirect('maxAgent/login', 'refresh');
            return;
        }

        $data = $this->Agent_Model->selectAgentEmail($email);

        if ($data->chk_email) { // Exist Email
            echo "<script>alert('This email already exists.');</script>";
            redirect('maxAgent/login', 'refresh');
            return;

        } else { // New Registration
            $location = array();
            $location['fk_suburb_id'] = 0;
            $location['fk_city_id'] = 0;
            $location['fk_region_id'] = 0;
            $location_id = $this->Common_Model->insertLocation($location);
            $agent_code = $this->uniqid_base36('agent'); // Gen Unique Code

            if ($location_id > 0) {
                $agent = array();
                $agent['agent_first_name'] = $first_name;
                $agent['agent_last_name'] = $last_name;
                $agent['agent_password'] = password_hash($password, PASSWORD_DEFAULT);
                $agent['agent_email'] = $email;
                $agent['agent_code'] = $agent_code;
                $agent['agent_fb_token'] = $this->apiKeyGenerator();
                // $agent['fk_agency_id'] = $agency_id;
                $agent['fk_location_id'] = $location_id;
                $result = $this->Agent_Model->inserAgent($agent);

                $this->activate_email($email);

                echo "<script>alert('To activate your PropertiMax agent account, Please check your email.');</script>";
                redirect('maxAgent/login', 'refresh');
                return;
            }
        }
    }

    public function activate_email($email) {
        $data = $this->Agent_Model->selectAgentEmail($email);

        if ($data) {
            $config['mailtype'] = "html";
            $config['charset'] = "utf8";
            $config['protocol'] = "sendmail";
            $config['mailpath'] = '/usr/sbin/sendmail';
            $config['wordwrap'] = TRUE;

            $link_path = base_url('maxAgent/agent_activate')."?token=".$data->agent_fb_token;
    
            $this->load->library('email', $config);
            $this->email->initialize($config);
            $this->email->from('info@propertimax.co.nz', 'Propertimax Agent');
            $this->email->to($email);
            $this->email->subject('PropertiMax - Activate your PropertiMax agent account');

            $msgs = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                <html xmlns="http://www.w3.org/1999/xhtml" style="font-family: Helvetica Neue, Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                    <head>
                        <meta name="viewport" content="width=device-width" />
                        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                        <title>Activate your PropertiMax Agent Account</title>
                        <style type="text/css">
                            img { max-width: 100%; }
                            body { -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none; width: 100% !important; height: 100%; line-height: 1.6em; }
                            body { background-color: #f6f6f6; }
                            @media only screen and (max-width: 640px) {
                                body { padding: 0 !important; }
                                h1 { font-weight: 800 !important; margin: 20px 0 5px !important; }
                                h2 { font-weight: 800 !important; margin: 20px 0 5px !important; }
                                h3 { font-weight: 800 !important; margin: 20px 0 5px !important; }
                                h4 { font-weight: 800 !important; margin: 20px 0 5px !important; }
                                h1 { font-size: 22px !important; }
                                h2 { font-size: 18px !important; }
                                h3 { font-size: 16px !important; }
                                .container { padding: 0 !important; width: 100% !important; }
                                .content { padding: 0 !important; }
                                .content-wrap { padding: 10px !important; }
                                .invoice { width: 100% !important; }
                            }
                        </style>
                    </head>

                    <body itemscope itemtype="http://schema.org/EmailMessage" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none; width: 100% !important; height: 100%; line-height: 1.6em; background-color: #f6f6f6; margin: 0;" bgcolor="#f6f6f6">
                        <table class="body-wrap" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; background-color: #f6f6f6; margin: 0;" bgcolor="#f6f6f6">
                            <tr style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                <td style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;" valign="top"></td>
                                <td class="container" width="600" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; display: block !important; max-width: 600px !important; clear: both !important; margin: 0 auto;" valign="top">
                                    <div class="content" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; max-width: 600px; display: block; margin: 0 auto; padding: 20px;">
                                        <table class="main" width="100%" cellpadding="0" cellspacing="0" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; border-radius: 3px; background-color: #fff; margin-top: 20px; border: 1px solid #e9e9e9;" bgcolor="#fff">
                                            <tr style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                <td class="alert alert-warning" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 16px; vertical-align: top; color: #fff; font-weight: bold; text-align: center; bold; border-radius: 3px 3px 0 0; background-color: #96c355; margin: 0; padding: 20px;" align="center" valign="top">
                                                <br>Please activate your PropertiMax agent account
                                                </td>
                                            </tr>
                                            <tr style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                <td class="content-wrap" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 20px;" valign="top">
                                                    <table width="100%" cellpadding="0" cellspacing="0" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                        <tr style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                            <td class="content-block" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
                                                            <strong style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">Hi '.$data->agent_first_name.',</strong>
                                                            </td>
                                                        </tr>
                                                        <tr style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                            <td class="content-block" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">Welcome to Propertimax! To activate your personal PropertiMax Account, please click on the link below：</td>
                                                        </tr>
                                                        <tr style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                            <td class="content-block" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" align="center" valign="top">
                                                                link: <a href="'.$link_path.'">'.$link_path.'</a>
                                                            </td>
                                                        </tr>
                                                        <tr style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                            <td class="content-block" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">We advise you to activate your account within 3 days after you receive this email, to avoid account expiration and termination of related services.</td>
                                                        </tr>
                                                        <tr style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                            <td class="content-block" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">Sincerely,
                                                                <br/>PropertiMax
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                        <div class="footer" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; clear: both; color: #999; margin: 0; padding: 20px;">
                                            <table width="100%" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                <tr style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                    <td class="aligncenter content-block" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 12px; vertical-align: top; color: #999; text-align: center; margin: 0; padding: 0 0 20px;" align="center" valign="top">Email <a href="mailto:info@propertimax.co.nz" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 12px; color: #999; text-decoration: underline; margin: 0;">info@propertimax.co.nz</a></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </td>
                                <td style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;" valign="top"></td>
                            </tr>
                        </table>
                    </body>
                </html>
            ';
    
            $this->email->message($msgs);
            $this->email->send();
        }
    }

    public function agent_activate() {
        $token = $this->input->get_post("token");
        $data = $this->Agent_Model->chkActivateToken($token);
        if ($data->chk_token) {
            $agent_data["verify_flag"] = 1;
            $agent_data["agent_fb_token"] = NULL;
            $this->Agent_Model->updateProfile($agent_data, $data->agent_id);
            redirect('maxAgent/login', 'refresh');
        }
    }

    public function forgotpassword() {

        $email = $this->input->post("email");
        $data = $this->Agent_Model->selectAgentEmail($email);
        if ($data) {
            $randpw = random_string('alnum', 10);
            
            $datan["agent_password"]=password_hash($randpw, PASSWORD_DEFAULT);
            $this->Agent_Model->updateProfile($datan,$data->agent_id);
            
            $config['protocol'] = 'smtp';
            $config['smtp_host'] = 'smtp.mandrillapp.com';
            $config['smtp_port'] = '587';
            $config['smtp_user'] = 'Propertimax';
            $config['smtp_pass'] = 'Zh4yjTIHtPb4vwD3GtdMMA';
            $config['mailtype'] = 'html'; // or html

            $this->email->initialize($config);
            $this->email->from('info@propertimax.co.nz', 'Propertimax Agent');
            $this->email->to($email);
            $this->email->subject('Password Recovery');
            $this->email->message('<p style="text-align:center;" >Folowing is the System generated password for the Propertimax Agent Admin panel. You need to enter your email and this password to login.</p> <h4 style="text-align:center;" > Your Password is: ' . $randpw . '</h4>');
            $this->email->send();
            
            redirect('maxAgent/login?forgt=ok', 'refresh');
            
        } else {
            redirect('maxAgent/login?forgt=err', 'refresh');
        }
    }

    public function forgotPass() {
        $email = $this->input->post("forgot_email");
        $data = $this->Agent_Model->selectAgentEmail($email);

        if ($data) {
            $randpw = random_string('alnum', 10);
            
            $datan["agent_password"]=password_hash($randpw, PASSWORD_DEFAULT);
            $this->Agent_Model->updateProfile($datan,$data->agent_id);

            $config['mailtype'] = "html";
            $config['charset'] = "utf8";
            $config['protocol'] = "sendmail";
            $config['mailpath'] = '/usr/sbin/sendmail';
            $config['wordwrap'] = TRUE;
    
            $this->load->library('email', $config);
            $this->email->initialize($config);
            $this->email->from('info@propertimax.co.nz', 'Propertimax Agent');
            $this->email->to($email);
            $this->email->subject('PropertiMax - Password Recovery of Agent');
            $this->email->message('<p style="text-align:center;" >Folowing is the System generated password for the Propertimax Agent Admin panel. You need to enter your email and this password to login.</p> <h4 style="text-align:center;" > Your Password is: ' . $randpw . '</h4>');
            $this->email->send();
            echo "<script>alert('Send your password reset email.');</script>";
            redirect('maxAgent/login', 'refresh');
            return;
            
        } else {
            redirect('maxAgent/login', 'refresh');
        }
    }

    public function dashBoard() {
        if (!isset($_SESSION['SESS_AGENT_ID'])) {
            redirect('maxAgent/logIn', 'refresh');
            return;
        } else {
            // $ar["hzards"] = $this->Hazards_Model->get_all_hazard_types();
            $this->load->view('maxAgent/includes/head2');
            $this->load->view('maxAgent/includes/header2');
            $this->load->view('maxAgent/includes/leftSidebar2');
            $this->load->view('maxAgent/htypes');
            $this->load->view('maxAgent/includes/footer');
            $this->load->view('maxAgent/includes/rightSidebar');
            $this->load->view('maxAgent/includes/jsplugings');
        }
    }

    public function personal() {
        if (!isset($_SESSION['SESS_AGENT_ID'])) {
            redirect('maxAgent/logIn', 'refresh');
            return;
            
        } else {
            $result = $this->Agent_Model->selectTargetAgentList($_SESSION['SESS_AGENT_ID']);
            $info_agent = $result[0];

            $brands = $this->Common_Model->getBrand();
            $brand_list = array();
            $data_value = $brands->result();

            for ($i=0; $i<$brands->num_rows(); $i++) {
                $brand_list[$i]['label'] = $data_value[$i]->brand_name;
                $brand_list[$i]['value'] = $data_value[$i]->brand_id;
            }

            $info_agent["brand_lists"] = json_encode($brand_list);
            $info_agent["category"] = "personal";
            $info_agent["errors"] = 0;
            $this->load->view('maxAgent/includes/head');
            $this->load->view('maxAgent/includes/header2');
            $this->load->view('maxAgent/includes/leftSidebar2');
            $this->load->view('maxAgent/agent/personal', $info_agent);
            $this->load->view('maxAgent/includes/footer');
            $this->load->view('maxAgent/includes/rightSidebar');
            $this->load->view('maxAgent/includes/jsplugings');
        }
    }

    public function error_personal() {
        if (!isset($_SESSION['SESS_AGENT_ID'])) {
            redirect('maxAgent/logIn', 'refresh');
            return;
            
        } else {
            $result = $this->Agent_Model->selectTargetAgentList($_SESSION['SESS_AGENT_ID']);
            $info_agent = $result[0];

            $brands = $this->Common_Model->getBrand();
            $brand_list = array();
            $data_value = $brands->result();

            for ($i=0; $i<$brands->num_rows(); $i++) {
                $brand_list[$i]['label'] = $data_value[$i]->brand_name;
                $brand_list[$i]['value'] = $data_value[$i]->brand_id;
            }

            $info_agent["brand_lists"] = json_encode($brand_list);
            $info_agent["category"] = "personal";
            $info_agent["errors"] = 1;
            
            $this->load->view('maxAgent/includes/head2');
            $this->load->view('maxAgent/includes/header2');
            $this->load->view('maxAgent/includes/leftSidebar2');
            $this->load->view('maxAgent/agent/personal', $info_agent);
            $this->load->view('maxAgent/includes/footer');
            $this->load->view('maxAgent/includes/rightSidebar');
            $this->load->view('maxAgent/includes/jsplugings');
        }
    }

    public function modPersonalDetail() {
        if (!isset($_SESSION['SESS_AGENT_ID'])) {
            redirect('maxAgent/logIn', 'refresh');
            return;

        } else {
            $agent_title = $this->input->post("agent_title");
            $license_option = $this->input->post("license_option");
            $reaa_number = $this->input->post("reaa_number");
            $website = $this->input->post("website");
            $office = $this->input->post("office");
            $address = $this->input->post("address");
            $address_lat = $this->input->post("address_lat");
            $address_lng = $this->input->post("address_lng");
            $brand_value = $this->input->post("brand_value");

            if ($agent_title == 0) {
                redirect('maxAgent/error_personal');
                return;
            }

            if (is_numeric($brand_value)) {
                if ($agent_title == 1 || $agent_title == 2) {
                    $data = array();
                    $location_data = array();
    
                    // location update
                    $location_data['address'] = $address;
                    $location_data['lat'] = $address_lat;
                    $location_data['long'] = $address_lng;
                    $this->Agent_Model->updateagentlocation($location_data, $_SESSION['SESS_AGENT_ID']);
    
                    if ($agent_title == 1) { // Property Manager
                        $data['fk_agent_title_id'] = $agent_title;
                        $data['license_flag'] = $license_option;
                        $data['agent_license'] = $reaa_number;
                        $data['fk_brand_id'] = $brand_value;
                        $data['agent_office'] = $office;
                        $data['agent_website'] = $website;
                        $result = $this->Common_Model->updateAgent($data, $_SESSION['SESS_AGENT_ID']);
                        
                        redirect('maxAgent/Personal');
                        return;
                    }
    
                    if ($agent_title == 2) { // Residential Sales
                        $data['fk_agent_title_id'] = $agent_title;
                        $data['license_flag'] = 0; // Set default Value
                        $data['agent_license'] = $reaa_number;
                        $data['fk_brand_id'] = $brand_value;
                        $data['agent_office'] = $office;
                        $data['agent_website'] = $website;
                        $result = $this->Common_Model->updateAgent($data, $_SESSION['SESS_AGENT_ID']);
                        
                        redirect('maxAgent/Personal');
                        return;
                    }
                }
            } else {
                redirect('maxAgent/error_personal');
                return;
            }
            
            redirect($_SERVER['HTTP_REFERER'], 'refresh');
            return;
        }
    }

    public function personal_desc() { /////////
        if (!isset($_SESSION['SESS_AGENT_ID'])) {
            redirect('maxAgent/logIn', 'refresh');
            return;
        } else {
            $result = $this->Agent_Model->selectTargetAgentList($_SESSION['SESS_AGENT_ID']);
            $info_agent = $result[0];
            $info_agent["category"] = "personal";
            $this->load->view('maxAgent/includes/head2');
            $this->load->view('maxAgent/includes/header2');
            $this->load->view('maxAgent/includes/leftSidebar2');
            $this->load->view('maxAgent/agent/personal', $info_agent);
            $this->load->view('maxAgent/includes/footer');
            $this->load->view('maxAgent/includes/rightSidebar');
            $this->load->view('maxAgent/includes/jsplugings');
        }
    }

    public function modagentpic() {
        $config['upload_path'] = './images/agent/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['encrypt_name'] = TRUE;


        $this->load->library('upload', $config);
        $image;

        if (!$this->upload->do_upload('userfile')) {
            $error = array('error' => $this->upload->display_errors());
        } else {
            $image = $this->upload->data()["file_name"];
        }

        $data = array(
            "agent_pic" => "images/agent/" . $image
        );
        $this->Agent_Model->updateProfile($data, $_SESSION['SESS_AGENT_ID']);
        //print_r($data);
    }

    public function modagentprfile() {
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

    public function modagentreea() {
        $reea = $this->input->post("reea");

        $data = array(
            "agent_license" => $reea
        );
        $this->Agent_Model->updateProfile($data, $_SESSION['SESS_AGENT_ID']);
    }

    public function modagentagency() {
        $agencyid = $this->input->post("agencyid");
        $agencyname = $this->input->post("agencyname");
        $agencyoffice = $this->input->post("agencyoffice");
        $data = array(
            "agency_name" => $agencyname,
            "agency_office" => $agencyoffice
        );
        $this->Agent_Model->updateagency($data, $agencyid);
    }

    public function modagentdec() {
        $dec = $this->input->post("agentdec");

        $data = array(
            "agent_description" => $dec
        );
        $this->Agent_Model->updateProfile($data, $_SESSION['SESS_AGENT_ID']);
    }

    public function modagentloc() {
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

    public function Property() {
        if (!isset($_SESSION['SESS_AGENT_ID'])) {
            redirect('maxAgent/logIn', 'refresh');
            return;
        } else {
            $property_lists = $this->Admin_Model->selectPropertyList($_SESSION['SESS_AGENT_ID'], $_SESSION['SESS_ADMIN']);
            $result["property_lists"] = $property_lists;
            $result["category"] = "property";

            $this->load->view('maxAgent/includes/head2');
            $this->load->view('maxAgent/includes/header2');
            $this->load->view('maxAgent/includes/leftSidebar2');
            $this->load->view('maxAgent/property', $result);
            $this->load->view('maxAgent/includes/footer');
            $this->load->view('maxAgent/includes/rightSidebar');
            $this->load->view('maxAgent/includes/jsplugings');
        }
    }

    public function Assist() {
        if (!isset($_SESSION['SESS_AGENT_ID'])) {
            redirect('maxAgent/logIn', 'refresh');
            return;
        } else {
            $result = array();
            $agent_list = $this->Agent_Model->selectTargetAgentList($_SESSION['SESS_AGENT_ID'], $_SESSION['SESS_ADMIN']);
            $result["agent_list"] = $agent_list[0];
            $property_lists = $this->Agent_Model->selectTargetPropertyList($_SESSION['SESS_AGENT_ID'], $_SESSION['SESS_ADMIN'])->result_array();
            $result["property_lists"] = $property_lists;
            $result["category"] = "assist";

            $this->load->view('maxAgent/includes/head2');
            $this->load->view('maxAgent/includes/header2');
            $this->load->view('maxAgent/includes/leftSidebar2');
            $this->load->view('maxAgent/assist', $result);
            $this->load->view('maxAgent/includes/footer');
            $this->load->view('maxAgent/includes/rightSidebar');
            $this->load->view('maxAgent/includes/jsplugings');
        }
    }

    public function Openhome() {
        if (!isset($_SESSION['SESS_AGENT_ID'])) {
            redirect('maxAgent/logIn', 'refresh');
            return;
        } else {
            $result = array();
            $agent_list = $this->Agent_Model->selectTargetAgentList($_SESSION['SESS_AGENT_ID'], $_SESSION['SESS_ADMIN']);
            $result["agent_list"] = $agent_list[0];
            $property_lists = $this->Agent_Model->selectTargetPropertyList($_SESSION['SESS_AGENT_ID'], $_SESSION['SESS_ADMIN'])->result_array();
            $result["property_lists"] = $property_lists;

            $this->load->view('maxAgent/includes/head2');
            $this->load->view('maxAgent/includes/header2');
            $this->load->view('maxAgent/includes/leftSidebar2');
            $this->load->view('maxAgent/openhome', $result);
            $this->load->view('maxAgent/includes/footer');
            $this->load->view('maxAgent/includes/rightSidebar');
            $this->load->view('maxAgent/includes/jsplugings');
        }
    }

    public function tkAssist() { /////////////////// rochas
        $result = array();
        $result['assist_list'] = $this->Agent_Model->selectAssistList($property_id);

        // $this->errorMsg($result);
        // return;

        $this->load->view('maxAgent/includes/head2');
        $this->load->view('maxAgent/includes/header2');
        $this->load->view('maxAgent/includes/leftSidebar2');
        $this->load->view('maxAgent/assist', $result);
        $this->load->view('maxAgent/includes/footer');
        $this->load->view('maxAgent/includes/rightSidebar');
        $this->load->view('maxAgent/includes/jsplugings');
    }

    /** PropertiMax - 2.0 API
     * Requirements:
     * URL: https://propertimax.co.nz/max/maxAgent/GetAssistAjax
     * Transmission method: POST
     * Parameters: email, password
     */
    public function GetAssistAjax() {
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
    function GetOpenhomeAjax() {
        $property_id = $this->input->post_get('property_id');

        $data = array();
        $data['openhome_list'] = $this->Agent_Model->selectOpenHomeList($property_id);

        $this->output->set_output(json_encode($data));
        return $data;
    }

    function GetVisitorAjax() { // rochas
        $open_home_id = $this->input->post_get('open_home_id');

        $data = array();
        $data['visitor_list'] = $this->Common_Model->selectAjaxOpenHomeVisitor($open_home_id)->result_array;

        $this->output->set_output(json_encode($data));
        return $data;
    }

    /** PropertiMax - 2.0 API
     * Requirements:
     * URL: https://propertimax.co.nz/max/maxAgent/GetSearchAssistAjax
     * Transmission method: POST
     * Parameters: email, password
     */
    public function GetSearchAssistAjax() {
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
     * URL: https://propertimax.co.nz/max/maxAgent/SetAssistAjax
     * Transmission method: POST
     * Parameters: email, password
     */
    public function SetAssistAjax() {

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
    public function DelAssistAjax() {
        $agent_id = $this->input->post_get('assist_agent_id');
        $property_id = $this->input->post_get('property_id');
        $result = $this->Agent_Model->deleteAssist($agent_id, $property_id);
        $this->json_encode_msgs($result);
        return;
    }

    // Only Admin
    public function News() {
        if (!isset($_SESSION['SESS_AGENT_ID'])) {
            redirect('maxAgent/logIn', 'refresh');
            return;
        } else {
            if ($_SESSION['SESS_ADMIN'] == 0) { // 0: Agent, 1: Admin
                redirect('maxAgent/personal', 'refresh');
                return;
            } else {
                $news_list = $this->Common_Model->selectNewsList();

                $result = array();
                $result["news_lists"] = $news_list;
                $this->load->view('maxAgent/includes/head');
                $this->load->view('maxAgent/includes/header');
                $this->load->view('maxAgent/includes/leftSidebar');
                $this->load->view('maxAgent/news', $result);
                $this->load->view('maxAgent/includes/footer');
                $this->load->view('maxAgent/includes/rightSidebar');
                $this->load->view('maxAgent/includes/jsplugings');
            }
        }
    }

    public function Manager() {
        if (!isset($_SESSION['SESS_AGENT_ID'])) {
            redirect('maxAgent/logIn', 'refresh');
            return;
        } else {
            if ($_SESSION['SESS_ADMIN'] == 0) {
                redirect('maxAgent/personal', 'refresh');
                return;
            } else {
                $admin_list = $this->Admin_Model->selectAdminList();

                $result = array();
                $result["admin_lists"] = $admin_list;
                $this->load->view('maxAgent/includes/head');
                $this->load->view('maxAgent/includes/header');
                $this->load->view('maxAgent/includes/leftSidebar');
                $this->load->view('maxAgent/manager', $result);
                $this->load->view('maxAgent/includes/footer');
                $this->load->view('maxAgent/includes/rightSidebar');
                $this->load->view('maxAgent/includes/jsplugings');
            }
        }
    }

    public function Agent() {
        if (!isset($_SESSION['SESS_AGENT_ID'])) {
            redirect('maxAgent/logIn', 'refresh');
            return;
        } else {
            if ($_SESSION['SESS_ADMIN'] == 0) {
                redirect('maxAgent/personal', 'refresh');
                return;
            } else {
                $agency_list = $this->Common_Model->selectAgencyList();
                $agent_list = $this->Admin_Model->selectAgentList();

                $result = array();
                $result["agency_lists"] = $agency_list;
                $result["agent_lists"] = $agent_list;
                $this->load->view('maxAgent/includes/head');
                $this->load->view('maxAgent/includes/header');
                $this->load->view('maxAgent/includes/leftSidebar');
                $this->load->view('maxAgent/agent', $result);
                $this->load->view('maxAgent/includes/footer');
                $this->load->view('maxAgent/includes/rightSidebar');
                $this->load->view('maxAgent/includes/jsplugings');
            }
        }
    }

//////////////////////////////////////////////////////////////////////////

    /** PropertiMax - 2.0 API
     * Requirements:
     * URL: https://propertimax.co.nz/max/maxAgent/allNewsList
     * Transmission method: POST
     * Parameters: email, password
     */
    public function allNewsList() {
        $news_list = $this->Common_Model->selectNewsList();
        $this->json_encode_msgs($news_list);
        return;
    }

    /** PropertiMax - 2.0 API
     * Requirements:
     * URL: https://propertimax.co.nz/max/maxAgent/allVisitorList
     * Transmission method: POST
     * Parameters: email, password
     */
    public function allVisitorList() {
        $visitor_list = $this->Common_Model->selectNewsList();
        $this->json_encode_msgs($visitor_list);
        return;
    }

    /** PropertiMax - 2.0 API
     * Requirements:
     * URL: https://propertimax.co.nz/max/maxAgent/targetNewsList
     * Transmission method: POST
     * Parameters: email, password
     */
    public function targetNewsList() {
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
    public function addAgent() {

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

                redirect('maxAgent/Agent');
                return;
            }
        }
    }

    /** PropertiMax - 2.0 API
     * Requirements:
     * Transmission method: POST
     * Parameters: name, email, password
     */
    public function addManager() {

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

                redirect('maxAgent/Manager');
                return;
            }
        }
    }

    /** PropertiMax - 2.0 API
     * Requirements:
     * Transmission method: POST
     * Parameters: news_title, news_description, news_pic
     */
    function addNews() {
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

        redirect('maxAgent/News');
        return;
    }

    /**
     * Requirements:
     * Transmission method: POST
     * Parameters: name, email, password
     */
    public function modPersonal() {
        if (!isset($_SESSION['SESS_AGENT_ID'])) {
            redirect('maxAgent/logIn', 'refresh');
            return;
        } else {
            $first_name = $this->input->post_get('first_name');
            $last_name = $this->input->post_get('last_name');
            $mobile = $this->input->post_get('mobile');
            $phone = $this->input->post_get('phone');
            $assist_mobile = $this->input->post_get('assist_mobile');

            $data = array();
            $data['agent_first_name'] = $first_name;
            $data['agent_last_name'] = $last_name;
            $data['agent_mobile'] = $mobile;
            $data['agent_phone'] = $phone;
            $data['agent_assist_mobile'] = $assist_mobile;
            $result = $this->Common_Model->updateAgent($data, $_SESSION['SESS_AGENT_ID']);

            echo "<script>alert('Update Successful');</script>";
            redirect('maxAgent/Personal');
            return;
        }
    }

    public function modPersonalDesc() {
        if (!isset($_SESSION['SESS_AGENT_ID'])) {
            redirect('maxAgent/logIn', 'refresh');
            return;
        } else {
            $data = array();
            $data['agent_description'] = $this->input->post_get('agent_description');
            $result = $this->Common_Model->updateAgent($data, $_SESSION['SESS_AGENT_ID']);
            redirect('maxAgent/Personal');
            return;
        }
    }

    public function modPersonalImage() {
        if (!isset($_SESSION['SESS_AGENT_ID'])) {
            redirect('maxAgent/logIn', 'refresh');
            return;
        } else {
            $data = array();

      

            if ($_FILES) {
                $pic_key = $this->pic_keys();
                $path = "./images/agent/";
                $fieldName = "agent_pic";
                $fileName = $_FILES[$fieldName]['name'];

                if ($_FILES[$fieldName]['size'] > 0) {
                    $fileNewName = $pic_key.'.'.pathinfo($fileName, PATHINFO_EXTENSION);
                    $this->addImageFile($path, $fieldName, $fileNewName);

                    $data['agent_pic'] = "images/agent/".$fileNewName;
                    $this->Common_Model->updateAgent($data, $_SESSION['SESS_AGENT_ID']);
                }
            }
            redirect($_SERVER['HTTP_REFERER'], 'refresh');
        }
    }

    /**
     * Requirements:
     * Transmission method: POST
     * Parameters: name, email, password
     */
    public function modAgent() {
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

        echo "<script>alert('Update Successful');</script>";
        redirect('maxAgent/Agent');
        return;
    }

    /**
     * Requirements:
     * Transmission method: POST
     * Parameters: name, email, password
     */
    public function modManager() {
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

        redirect('maxAgent/Manager');
        return;
    }

    /**
     * Requirements:
     * Transmission method: POST
     * Parameters: name, email, password
     */
    public function modNews() {
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

        redirect('maxAgent/News');
        return;
    }
    
    public function updateopenhone(){
        $todate = $this->input->post_get('todate');
        $fromdate = $this->input->post_get('fromdate');
        $propeid=$this->input->post_get('property_id');
        $date= array(
            "fk_property_id"=>$propeid,
            "open_home_to"=>$todate,
            "open_home_from"=>$fromdate
        );
        $this->Agent_Model->insertOpenHome($date);
        redirect('maxAgent/openhome');
    }
    
    public function removeopenhone(){
        $openhomeid = $this->input->post_get('openhomeid');
        $this->Agent_Model->deleteOpenHome($openhomeid);
        redirect('maxAgent/openhome');
    }

    /**
     * Requirements:
     * Transmission method: POST
     * Parameters: news_id
     */
    public function remPersonal() {
        $result = $this->Common_Model->deleteAgent($_SESSION['SESS_AGENT_ID']);
        $this->logOut();
    }

    /**
     * Requirements:
     * Transmission method: POST
     * Parameters: news_id
     */
    public function remAgent() {
        $agent_id = $this->input->post_get('agent_id');
        $result = $this->Common_Model->deleteAgent($agent_id);

        redirect('maxAgent/Agent');
        return;
    }

    public function remProperty() {
        $p_id = $this->input->post_get('pid');
        $result = $this->Agent_Model->deleteProperty($p_id);

        redirect('maxAgent/property');
        return;
    }

    /**
     * Requirements:
     * Transmission method: POST
     * Parameters: news_id
     */
    public function remManager() {
        $agent_id = $this->input->post_get('agent_id');
        $result = $this->Common_Model->deleteAgent($agent_id);

        redirect('maxAgent/Manager');
        return;
    }

    /**
     * Requirements:
     * Transmission method: POST
     * Parameters: news_id
     */
    public function remNews() {
        $news_id = $this->input->post_get('news_id');
        $result = $this->Common_Model->deleteNews($news_id);

        redirect('maxAgent/News');
        return;
    }

    function uuid() {
        $data = random_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    public function getAPIKey() {
        // $uid = uniqid();
        // $api_key = hash('sha256', (time() . $uid . rand()));
        print_r($this->uuid());
    }

    public function cronTabs() {
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

    public function cronTabs02() {
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
