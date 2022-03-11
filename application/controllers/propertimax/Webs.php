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
class Webs extends Base_controller {

    function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('Webs_Model');
    }


    /** PropertiMax - 2.0 API
     * Requirements:
     * URL: https://propertimax.co.nz/Webs/agentListLimit
     * Transmission method: POST
     * Parameters: limit
     */
    public function agentListLimit() {
        $limit = $this->input->post_get('limit');

        $result = $this->Webs_Model->selectAgentListLimit($limit);
        $this->json_encode_msgs($result);
        return;
    }

    /** PropertiMax - 2.0 API
     * Requirements:
     * URL: https://propertimax.co.nz/Webs/propertyRecentListLimit
     * Transmission method: POST
     * Parameters: limit
     */
    public function propertyRecentListLimit() {
        $limit = $this->input->post_get('limit');

        $result = $this->Webs_Model->selectPropertyRecentListLimit($limit);
        $this->json_encode_msgs($result);
        return;
    }

    /** PropertiMax - Ver_1.3.4
     * Requirements:
     * URL: https://propertimax.co.nz/Webs/searchAgentList
     * Transmission method: POST
     * Parameters: email, password
     */
    public function searchAgentList() {
        $key_word = $this->input->post_get('key_word');
        $result = $this->Webs_Model->selectSearchAgentList($key_word);

        $this->json_encode_msgs($result);
        return;
    }


}

// END
