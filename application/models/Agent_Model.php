<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * PropertiMax
 * @author Edward An
 */
class Agent_Model extends CI_Model
{

    const item_per_page = 10;

    function __construct()
    {
        //parent::__construct();
        $this->load->database();
    }

    /**
     * CRUD - Select
     */
    function chkActivateToken($email)
    {
        $this->db->select('
            pm_agent.agent_id,
            count(pm_agent.agent_email) as chk_token,
            pm_agent.admin_flag
        ');
        $this->db->from('pm_agent');
        $this->db->where('pm_agent.delete_flag', 0);
        $this->db->where('pm_agent.agent_fb_token', $email);
        $query = $this->db->get();
        $result = $query->row(0);
        return $result;
    }

    function selectAgentLogin($email, $password)
    {
        $this->db->select('
            pm_agent.agent_id,
            count(pm_agent.agent_email) as chk_email,
            pm_agent.admin_flag
        ');
        $this->db->from('pm_agent');
        $this->db->where('pm_agent.delete_flag', 0);
        $this->db->where('pm_agent.agent_password', $password);
        $this->db->where('pm_agent.agent_email', $email);
        $query = $this->db->get();
        $result = $query->result();
        return $result[0];
    }

    function selectAgentEmail($email)
    {
        $this->db->select('
            pm_agent.agent_id,
            pm_agent.agent_first_name,
            pm_agent.agent_last_name,
            pm_agent.agent_fb_token,
            pm_agent.agent_password,
            pm_agent.agent_email,
            count(pm_agent.agent_email) as chk_email,
            pm_agent.admin_flag,
            pm_agent.last_login,
            pm_agent.verify_flag
        ');
        $this->db->from('pm_agent');
        //$this->db->join('pm_branch', 'pm_agent.fk_branch_id = pm_branch.branch_id');
        $this->db->where('pm_agent.delete_flag', 0);
        $this->db->where('pm_agent.agent_email', $email);
        $query = $this->db->get();
        $result = $query->result();


        return $result[0];
    }

    function selectAgentList($key_word)
    {
        $this->db->select('
            pm_agent.*,
            pm_location.*, 
            pm_agency.*
        ');
        $this->db->from('pm_agent');
        $this->db->join('pm_agency', 'pm_agency.agency_id = pm_branch.fk_agency_id');
        $this->db->join('pm_location', 'pm_agent.fk_location_id = pm_location.location_id');
        $this->db->where('pm_agent.delete_flag', 0);
        $this->db->like('pm_agent.agent_first_name', $key_word, 'both');
        $this->db->like('pm_agent.agent_last_name', $key_word, 'both');
        $this->db->order_by('pm_agent.agent_id', 'DESC');
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }

    function selectTargetAgentListLanguages($agent_id)
    {
        $this->db->select('*');
        $this->db->from('pm_agent_language');
        $this->db->join('pm_language', 'pm_language.id_flag = pm_agent_language.fk_id_language');
        $this->db->where('fk_id_agent', $agent_id);
        $query = $this->db->get();
        $result = $query->result_array();
        //print_r($this->db->last_query());
        return $result;
    }


    //With Branch
    function selectTargetAgentList($agent_id)
    {
        $this->db->select('
            pm_agent.*,
            pm_location.*,
            pm_branch.*,
            pm_agency.*
        ');
        $this->db->from('pm_agent');
        $this->db->join('pm_branch', 'pm_agent.fk_branch_id = pm_branch.branch_id');
        $this->db->join('pm_agency', 'pm_agency.agency_id = pm_branch.fk_agency_id');
        $this->db->join('pm_location', 'pm_branch.fk_location_id = pm_location.location_id');
        //$this->db->join('pm_agentLocation', 'pm_agent.agent_id = pm_agentLocation.agent_id', 'left outer');
        $this->db->where('pm_agent.agent_id', $agent_id);
        $this->db->where('pm_agent.delete_flag', 0);
        $query = $this->db->get();
        $result = $query->result_array();
        //print_r($this->db->last_query());

        if (count($result) > 0) {
            return $result;
        } else {
            return $this->selectTargetAgentList2($agent_id);
        }
    }

    //Without branch
    //With Branch
    function selectTargetAgentList2($agent_id)
    {
        $this->db->select('
            pm_agent.*,
        ');
        $this->db->from('pm_agent');
        $this->db->where('pm_agent.agent_id', $agent_id);
        $this->db->where('pm_agent.delete_flag', 0);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }



    function selectTargetPropertyList($agent_id)
    {
        $this->db->select('*');
        $this->db->from('pm_property');
        $this->db->join('pm_property_type', 'pm_property.fk_property_type_id = pm_property_type.property_type_id', 'left outer');
        $this->db->join('pm_location', 'pm_property.fk_location_id = pm_location.location_id');
        $this->db->where('pm_property.fk_agent_id', $agent_id);
        $this->db->where('pm_property.delete_flag', 0);
        $query = $this->db->get();
        // $result = $query->result_array();

        return $query;
    }

    // Show Add List
    function selectAgentPropertyList($agent_id)
    { // for Agent (UNION ALL and IN Query)
        $this->db->select('*');
        $this->db->from('pm_property');
        $this->db->join('pm_property_type', 'pm_property.fk_property_type_id = pm_property_type.property_type_id', 'left outer');
        $this->db->join('pm_location', 'pm_property.fk_location_id = pm_location.location_id');
        $this->db->where('pm_property.fk_agent_id', $agent_id);
        $this->db->where('pm_property.delete_flag', 0);
        $query1 = $this->db->get_compiled_select();

        $this->db->select('*');
        $this->db->from('pm_property');
        $this->db->join('pm_property_type', 'pm_property.fk_property_type_id = pm_property_type.property_type_id', 'left outer');
        $this->db->join('pm_location', 'pm_property.fk_location_id = pm_location.location_id');
        $this->db->where("pm_property.property_id IN (select assist_property_id from pm_assist where assist_agent_id = '$agent_id')");
        $this->db->where('pm_property.delete_flag', 0);
        $query2 = $this->db->get_compiled_select();

        $query = $this->db->query($query1 . ' UNION ALL ' . $query2);
        $result = $query->result_array();


        return $result;
    }

    function selectSearchAssist($agency_id, $agent_id, $key_word)
    { // for Agent (NOT IN Qurey)
        $this->db->select('*');
        $this->db->from('pm_agent');
        $this->db->join('pm_property', 'pm_property.fk_agent_id = pm_agent.agent_id');
        $this->db->where('pm_branch.fk_agency_id', $agency_id);
        $this->db->where('pm_agent.agent_id !=', $agent_id);
        $this->db->where("pm_property.property_id NOT IN (select assist_property_id from pm_assist where assist_agent_id = '$agent_id')");
        $this->db->where('pm_property.agency_ref=', $key_word);
        $query = $this->db->get();
        $result = $query->result_array();

        return $result;
    }

    // thilan
    function getAllpropertiesInAgency($agency_id, $agencypropetyID)
    {
        $this->db->select('*');
        $this->db->from('pm_property');
        $this->db->join('pm_agent', 'pm_agent.agent_id = pm_property.fk_agent_id');
        $this->db->join('pm_location', 'pm_property.fk_location_id = pm_location.location_id');
        $this->db->where('pm_branch.fk_agency_id', $agency_id);
        if ($agencypropetyID) {
            $this->db->where('pm_property.property_agency_id', $agencypropetyID);
        }
        $this->db->where("pm_property.property_id NOT IN (select pm_assist.assist_property_id from pm_assist where pm_assist.assist_agent_id = pm_branch.fk_agency_id)");
        $this->db->where('pm_property.delete_flag', 0);
        $this->db->where('pm_property.property_disable', 0);
        $query = $this->db->get();
        return $query;
    }

    function getagencyfromagent($agent_id)
    {
        $this->db->select('pm_agency.*');
        $this->db->from('pm_agency');
        $this->db->join('pm_agent', 'pm_branch.fk_agency_id = pm_agency.agency_id');
        $this->db->where('pm_agent.agent_id', $agent_id);
        $query = $this->db->get();
        return $query->result();
    }

    function getallactive($agentid)
    {
        $this->db->select('*');
        $this->db->from('pm_property');
        $this->db->join('pm_location', 'pm_property.fk_location_id = pm_location.location_id');
        $this->db->where("pm_property.property_id IN (select pm_assist.assist_property_id from pm_assist where pm_assist.assist_agent_id = " . $agentid . ")");
        $this->db->where('pm_property.delete_flag', 0);
        $this->db->where('pm_property.property_disable', 0);
        $query = $this->db->get();
        return $query;
    }

    // thilan end

    function getallagentsinagency($agency_id, $agent_id, $propertyid)
    {
        $this->db->select('*');
        $this->db->from('pm_agent');
        $this->db->where('pm_branch.fk_agency_id', $agency_id);
        $this->db->where('pm_agent.agent_id !=', $agent_id);
        $this->db->where("pm_agent.agent_id NOT IN (select pm_assist.assist_agent_id from pm_assist where pm_assist.assist_property_id = '$propertyid')");
        $query = $this->db->get();
        //        echo $this->db->last_query();
        //        exit();
        return $query->result();
    }

    function selectOldSearchAssist($agency_id, $agent_id, $key_word)
    { // for Agent (NOT IN Qurey)
        $this->db->select('pm_agent.agent_id, pm_property.*, pm_property_type.*, pm_location.*');
        $this->db->from('pm_property');
        $this->db->join('pm_property_type', 'pm_property.fk_property_type_id = pm_property_type.property_type_id', 'left outer');
        // $this->db->join('pm_location', 'pm_property.fk_location_id = pm_location.location_id');
        $this->db->join('pm_agent', 'pm_property.fk_agent_id = pm_agent.agent_id');
        $this->db->where("pm_property.property_id NOT IN (select assist_property_id from pm_assist where assist_agent_id = '$agent_id')");
        $this->db->where('pm_branch.fk_agency_id', $agency_id);
        $this->db->where('pm_agent.agent_id !=', $agent_id);
        $this->db->where('pm_property.delete_flag', 0);
        // $this->db->like('pm_property.property_agency_id', $key_word, 'both');
        $this->db->where('pm_agent.agent_code', $key_word);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    function chkAssist($assist_id, $property_id)
    {
        $this->db->select('*');
        $this->db->from('pm_assist');
        $this->db->where('pm_assist.assist_agent_id', $assist_id);
        $this->db->where('pm_assist.assist_property_id', $property_id);
        $result = $this->db->count_all_results();
        return $result;
    }

    function selectAssistList($property_id)
    {
        $this->db->select('*');
        $this->db->from('pm_assist');
        $this->db->join('pm_agent', 'pm_agent.agent_id = pm_assist.assist_agent_id');
        $this->db->where('pm_assist.assist_property_id', $property_id);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    function selectAgentOpenHomeId($agent_id)
    { // for Agent
        $this->db->select('pm_open_home.open_home_from as open_home_time');
        $this->db->from('pm_property');
        $this->db->join('pm_open_home', 'pm_property.property_id = pm_open_home.fk_property_id');
        $this->db->where('pm_property.fk_agent_id', $agent_id);
        $this->db->where('pm_open_home.open_home_from > CURRENT_TIMESTAMP()');
        $this->db->where('pm_property.delete_flag', 0);
        $this->db->group_by("DATE(pm_open_home.open_home_from)");
        $query = $this->db->get();
        // $result = $query->result_array();

        return $query;
    }

    function selectAgentOpenHomeList($agent_id, $open_home_time)
    { // for Agent (UNION ALL and IN Query)
        //        $this->db->select('*');
        //        $this->db->from('pm_property');
        //        $this->db->join('pm_location', 'pm_property.fk_location_id = pm_location.location_id');
        //        $this->db->join('pm_open_home', 'pm_property.property_id = pm_open_home.fk_property_id');
        //        $this->db->where('pm_property.fk_agent_id', $agent_id);
        //        $this->db->where('pm_open_home.open_home_from', $open_home_time);
        //        $this->db->where('pm_property.delete_flag', 0);
        //        $query1 = $this->db->get_compiled_select();
        //
        //        $this->db->select('*');
        //        $this->db->from('pm_property');
        //        $this->db->join('pm_location', 'pm_property.fk_location_id = pm_location.location_id');
        //        $this->db->join('pm_open_home', 'pm_property.property_id = pm_open_home.fk_property_id');
        //        $this->db->where("pm_property.property_id IN (select assist_property_id from pm_assist where assist_agent_id = '$agent_id')");
        //        $this->db->where('pm_open_home.open_home_from', $open_home_time);
        //        $this->db->where('pm_property.delete_flag', 0);
        //        $query2 = $this->db->get_compiled_select();
        //
        //        $query = $this->db->query($query1 . ' UNION ALL ' . $query2);
        $this->db->select('*');
        $this->db->from('pm_property');
        $this->db->join('pm_location', 'pm_location.location_id = pm_property.fk_location_id', 'left outer');
        $this->db->join('pm_open_home', 'pm_open_home.fk_property_id = pm_property.property_id');
        $this->db->where('pm_property.fk_agent_id', $agent_id);
        $this->db->where('pm_open_home.open_home_from', $open_home_time);
        $this->db->where('pm_property.delete_flag', 0);
        $query = $this->db->get();



        return $query;
    }

    function selectTargetPropertyMin($property_id)
    {
        $this->db->select('*');
        $this->db->from('pm_property');
        $this->db->where('pm_property.property_id', $property_id);
        //        $this->db->where('pm_property.fk_property_status_id!=',0);
        $this->db->where('pm_property.delete_flag', 0);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result[0];
    }

    function selectTargetProperty($property_id)
    {
        $this->db->select('*');
        $this->db->from('pm_property');
        $this->db->join('pm_property_type', 'pm_property.fk_property_type_id = pm_property_type.property_type_id', 'left outer');
        $this->db->join('pm_location', 'pm_property.fk_location_id = pm_location.location_id');
        $this->db->where('pm_property.property_id', $property_id);
        $this->db->where('pm_property.delete_flag', 0);
        $query = $this->db->get();
        $result = $query->result_array();

        return $result;
    }

    function selectSearchPropertyList($search, $property_type)
    { // for Customer
        $this->db->select('*');
        $this->db->from('pm_property');
        $this->db->join('pm_property_type', 'pm_property.fk_property_type_id = pm_property_type.property_type_id', 'left outer');
        $this->db->join('pm_location', 'pm_property.fk_location_id = pm_location.location_id');

        $this->db->where('pm_property.property_sale_flag >=', $search['search_sale_flag']); // 0: Sale, 1: Rent
        $this->db->where('pm_property.property_bedroom >=', $search['search_bedroom_from']);
        $this->db->where('pm_property.property_bedroom <=', $search['search_bedroom_to']);
        $this->db->where('pm_property.property_bathroom >=', $search['search_bathroom_from']);
        $this->db->where('pm_property.property_bathroom <=', $search['search_bathroom_to']);
        $this->db->where('pm_property.property_hidden_price >=', $search['search_price_from']);

        if (!($search['search_sale_flag'] == 1 && $search['search_price_to'] >= 2000)) { // Rental Max Value is 2000 Over
            $this->db->where('pm_property.property_hidden_price <=', $search['search_price_to']);
        }

        if ($search['search_sale_flag'] == 0 && $search['search_open_now'] == 1) {  // Sale - 0: All, 1: Open Now
            $this->db->where('pm_open_home.open_home_from >= CURRENT_TIMESTAMP()');
        }

        if ($search['search_sale_flag'] == 1) { // 1: Rent
            $this->db->where('pm_property.property_pet', $search['search_pet']);

            if ($search['search_available'] == 1) { // 1: Available Now
                $this->db->where('pm_property.property_available_date < CURRENT_TIMESTAMP()');
            }
        }

        if ($search['suburb_id'] != 0)
            $this->db->where('pm_location.fk_suburb_id', $search['suburb_id']);

        if ($search['city_id'] != 0)
            $this->db->where('pm_location.fk_city_id', $search['city_id']);

        if ($search['region_id'] != 0)
            $this->db->where('pm_location.fk_region_id', $search['region_id']);

        if (sizeof($property_type) > 0)
            $this->db->where_in('pm_property.fk_property_type_id', $property_type);

        $this->db->where('pm_property.property_disable', 0);
        $this->db->where('pm_property.delete_flag', 0);
        $query = $this->db->get();

        return $query;
    }

    function selectPropertiPicList($property_id)
    {
        $this->db->select('*');
        $this->db->from('pm_property_pic');
        $this->db->where('pm_property_pic.fk_property_id', $property_id);
        $this->db->order_by("property_first_pic", "asc");
        $query = $this->db->get();
        $result = $query->result_array();

        return $result;
    }

    function selectOpenHomeList($property_id)
    {
        $this->db->select('
            (select count(pm_visitor_log.visitor_id) from pm_visitor_log where fk_open_home_id = open_home_id) as chk_visitor,
            pm_open_home.*
        ');
        $this->db->from('pm_open_home');
        $this->db->where('pm_open_home.fk_property_id', $property_id);
        $query = $this->db->get();
        $result = $query->result_array();

        return $result;
    }

    function selectTargetOpenHome($open_home_id)
    {
        $this->db->select('*');
        $this->db->from('pm_open_home');
        $this->db->where('open_home_id', $open_home_id);
        $query = $this->db->get();
        $result = $query->result_array();

        return $result;
    }

    function selectPropertyAllStatus()
    {
        $this->db->select('*');
        $this->db->from('pm_property_status');
        $this->db->where('pm_property_status.property_status_id!=', 0);
        $query = $this->db->get();
        $result = $query->result_array();

        return $result;
    }

    function get_all_agents()
    {
        $this->db->select('*');
        $this->db->from('pm_agent');
        $this->db->join('pm_branch', 'pm_agent.fk_branch_id = pm_branch.branch_id');
        $this->db->join('pm_agency', 'pm_branch.fk_agency_id = pm_agency.agency_id');
        $this->db->where('pm_agent.delete_flag', 0);
        $this->db->order_by("RAND()");
        $this->db->limit(10);
        $query = $this->db->get();
        return $query->result();
    }

    function get_all_agnecies()
    {
        $this->db->select('*');
        $this->db->from('pm_agency');
        $query = $this->db->get();
        return $query->result();
    }

    function get_all_branches()
    {
        $this->db->select('*');
        $this->db->from('pm_branch');
        $query = $this->db->get();
        return $query->result();
    }

    // Thilan agent radious 
    public function getAllRequestsWithDistance($lat, $lng, $radius)
    {
        $this->db->select('
        pm_agent.agent_id, 0 as is_added,
        pm_agent.agent_pic, 
        pm_agent.agent_first_name, 
        pm_agent.agent_last_name, 
        pm_agent.agent_occupation,
        pm_agent.agent_mobile,
        pm_agency.agency_id, 
        pm_agency.agency_name,
        pm_agency.agency_pic
        ');
        $this->db->from('pm_agentLocation');
        $this->db->join('pm_agent', 'pm_agent.agent_id = pm_agentLocation.agent_id');
        $this->db->join('pm_agency', 'pm_branch.fk_agency_id = pm_agency.agency_id');
        $this->db->join('pm_location', 'pm_agent.fk_location_id = pm_location.location_id');
        $this->db->where("(6371 * ACOS(LEAST(COS(RADIANS(pm_agentLocation.lat)) * COS(RADIANS(" . $lat . ")) * COS(RADIANS(pm_agentLocation.long -  " . $lng . ")) + SIN(RADIANS(pm_agentLocation.lat)) * SIN(RADIANS(" . $lat . ")),1.0)) < " . $radius . ")");
        $this->db->where('pm_agent.delete_flag', 0);
        //$query = $this->db->query("select pm_agent.* from pm_agentLocation , pm_agent left join `en_application` c using(jobid) where a.is_deleted=0 and a.`userid`=b.`userid` and a.`status` in (\"open\", \"processing\") and a.jobid<? and (((acos(sin((?*pi()/180)) * sin((a.`latitude`*pi()/180))+cos((?*pi()/180)) * cos((a.`latitude`*pi()/180)) * cos(((?-a.`longitude`)*pi()/180))))*180/pi())*60*1.1515*1.609344) < ? group by a.jobid order by publish_time desc limit ?;", array($last_index, $lat, $lat, $lng, $radius, self::item_per_page));
        $query = $this->db->get();
        $result = $query->result();

        //print_r($this->db->last_query());    

        return $result;
    }

    /**
     * CRUD - Insert
     */
    function insertAgency($data)
    {
        return $this->db->insert('pm_agency', $data);
    }

    function inserAgent($data)
    {
        $this->db->insert('pm_agent', $data);
        return $this->db->insert_id();
    }

    function insertProperty($data)
    {
        return $this->db->insert('pm_property', $data);
    }

    function insertPropertyPic($data)
    {
        return $this->db->insert('pm_property_pic', $data);
    }

    function insertOpenHome($data)
    {
        return $this->db->insert('pm_open_home', $data);
    }

    function insertAddList($data)
    {
        return $this->db->insert('pm_assist', $data);
    }

    function insertorupdateagentlocation($data, $agentid)
    {
        $query = $this->db->get_where('pm_agentLocation', array('agent_id' => $agentid));
        $res = $query->result();
        if ($res) {
            $this->db->where('agent_id', $agentid);
            $this->db->update('pm_agentLocation', $data);
        } else {
            $this->db->insert('pm_agentLocation', $data);
        }
    }

    /**
     * CRUD - Update
     */
    function updateVerifyEmail($email)
    {
        $data = array();
        $data['verify_chk'] = "1";
        $this->db->where('email', $email);
        return $this->db->update('ws_all_user', $data);
    }

    function updateUserType($user_type, $user_id)
    {
        $data = array();
        $data['user_type'] = $user_type;
        $this->db->where('user_id', $user_id);
        return $this->db->update('ws_all_user', $data);
    }

    function updateComment($data, $comment_id)
    {
        $this->db->where('comment_id', $comment_id);
        return $this->db->update('ws_all_comments', $data);
    }

    function updatePropertyStatus($data, $property_id)
    {
        $this->db->where('property_id', $property_id);
        return $this->db->update('pm_property', $data);
    }

    function updateProfile($data, $agent_id)
    {
        $this->db->where('agent_id', $agent_id);
        return $this->db->update('pm_agent', $data);
    }

    function updateagency($data, $agency_id)
    {
        $this->db->where('agency_id', $agency_id);
        return $this->db->update('pm_agency', $data);
    }

    function updateagentlocation($data, $agent_id)
    {
        $this->db->select('fk_location_id');
        $this->db->from('pm_agent');
        $this->db->where('pm_agent.agent_id', $agent_id);
        $query = $this->db->get();
        $res = $query->result();

        $this->db->where('location_id', $res[0]->fk_location_id);
        return $this->db->update('pm_location', $data);
    }

    /**
     * CRUD - Delete
     */
    function deleteAgent($agent_id)
    {
        $data = array();
        $data['delete_flag'] = 1;
        $this->db->where('agent_id', $agent_id);
        return $this->db->update('pm_agent', $data);
    }

    function publicAgent($agent_id,$value)
    {
        $data = array();
        $data['agent_public'] = $value;
        $this->db->where('agent_id', $agent_id);
        return $this->db->update('pm_agent', $data);
    }


    function deletePropertyPic($property_pic_id)
    {
        $this->db->where('property_pic_id', $property_pic_id);
        return $this->db->delete('pm_property_pic');
    }

    function deleteOpenHome($open_home_id)
    {
        $this->db->where('open_home_id', $open_home_id);
        return $this->db->delete('pm_open_home');
    }

    function deleteAssist($assist_agent_id, $assist_property_id)
    {
        $this->db->where('assist_agent_id', $assist_agent_id);
        $this->db->where('assist_property_id', $assist_property_id);
        return $this->db->delete('pm_assist');
    }

    function deleteProperty($p_id)
    {
        $data = array();
        $data['delete_flag'] = 1;
        $this->db->where('property_id', $p_id);
        return $this->db->update('pm_property', $data);
    }
}
