<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @access rochas
 * @author Edward An
 * PropertiMax
 * Note: REST Model (Use to Common Function)
 * 22-Feb-18: All Check Qurey
 */
class REST_Model extends CI_Model
{

    function __construct()
    {
        //parent::__construct();
        $this->db = $this->load->database('maxauto', TRUE);
    }

    /**
     * CRUD - Select
     */
    function chkApiKey($key)
    {
        // count(pm_agency.api_key) as chk_key,
        $this->db->select('
            ma_subscription.fk_branch_id,ma_location.fk_region_id
        ');
        $this->db->from('ma_dealership');
        $this->db->join('ma_subscription', 'ma_subscription.fk_branch_id = ma_dealership.dealership_id');
        $this->db->join('ma_location', 'ma_location.location_id = ma_dealership.fk_location');
        // $this->db->where('pm_agent.delete_flag', 0);
        $this->db->where('ma_subscription.delete_flag', 0);
        $this->db->where('ma_dealership.dealership_api_key', $key);
        $query = $this->db->get();
        return $query;
    }

    //find MAke
    function findMake($make)
    {
        // count(pm_agency.api_key) as chk_key,
        $this->db->select('
            *
        ');
        $this->db->from('ma_make');
        // $this->db->where('pm_agent.delete_flag', 0);
        $this->db->where('LOWER(make_description)', strtolower(trim($make)));
        $query = $this->db->get();
        return $query;
    }
    
    
    //Find Model
    function findModel($make,$model)
    {
        // count(pm_agency.api_key) as chk_key,
        $this->db->select('
            *
        ');
        $this->db->from('ma_model');
        $this->db->where('fk_make_id', $make);
        $this->db->where('LOWER(model_desc)', strtolower(trim($model)));
        $query = $this->db->get();
        return $query;
    }

    function insertVehicule($data)
    {
        $this->db->insert('ma_vehicle', $data);
        return $this->db->insert_id();

    }

    function insertPhoto($data)
    {
        return $this->db->insert('ma_vehicle_picture', $data);
    }

    function selectSubscriptionByDealership($id)
    {
        $this->db->select('*');
        $this->db->from('ma_subscription');
        $this->db->join('`ma_products_subs`', 'ma_subscription.fk_product = `ma_products_subs`.id');
        $this->db->join('ma_dealership', 'ma_subscription.fk_branch_id = ma_dealership.dealership_id');
        $this->db->where('ma_subscription.delete_flag', 0);
        $this->db->where('fk_branch_id', $id);
        $query = $this->db->get();
        return $query->result();
    }

    function selectVehicleByDealershipMonthly($id)
    {

        $this->db->select('count(vehicule_id) as total');
        $this->db->from('ma_vehicle');
        $this->db->where('MONTH(indate)', date('m'));
        $this->db->where('YEAR(indate)', date('Y'));
        $this->db->where('fk_dealership_id', $id);
        $query = $this->db->get();
        return $query->result();
    }



    function chkProperty($propertyRef)
    {
        $this->db->select('*');
        $this->db->from('pm_property');
        // $this->db->where('pm_agent.delete_flag', 0);
        $this->db->where('pm_property.property_ref', $propertyRef);
        $query = $this->db->get();
        return $query;
    }

    function chkAgentCode($email, $agency_id)
    {
        // count(pm_agency.api_key) as chk_key,
        $this->db->select('
            pm_agent.agent_code,
            pm_agent.agent_first_name,
            pm_agent.agent_last_name,
            pm_agent.agent_license,
            pm_agent.agent_email,
            pm_agent.agent_mobile,
            pm_agent.agent_phone
        ');
        $this->db->from('pm_agent');
        $this->db->where('pm_agent.delete_flag', 0);
        $this->db->where('pm_agent.agent_email', $email);
        $this->db->where('pm_agent.fk_agency_id', $agency_id);
        $query = $this->db->get();
        return $query;
    }

    function chkLocation($suburb_name, $city_name)
    {
        // count(pm_agency.api_key) as chk_key,
        $this->db->select('*');
        $this->db->from('pm_suburb');
        $this->db->join('pm_city', 'pm_suburb.fk_city_id = pm_city.city_id', 'left outer');
        $this->db->where('suburb_name', $suburb_name);
        $this->db->where('city_name', $city_name);
        $query = $this->db->get();
        return $query;
    }

    function chkSubAgentId($agent_code, $agency_id)
    {
        $this->db->select('
            pm_agent.agent_id,
            pm_agent.agent_code
        ');
        $this->db->from('pm_agent');
        $this->db->where('pm_agent.delete_flag', 0);
        $this->db->where('pm_agent.agent_code', $agent_code);
        $this->db->where('pm_agent.fk_agency_id', $agency_id);
        $query = $this->db->get();
        return $query;
    }

    function getAgentId($agent_name, $agent_last_name, $branch_id)
    {
        // count(pm_agency.api_key) as chk_key,
        $this->db->select('
            pm_agent.*
        ');
        $this->db->from('pm_agent');
        $this->db->where('pm_agent.delete_flag', 0);
        $this->db->where('pm_agent.agent_first_name', $agent_name);
        $this->db->where('pm_agent.agent_last_name', $agent_last_name);
        $this->db->where('pm_agent.fk_branch_id', $branch_id);
        $query = $this->db->get();
        return $query;
    }

    function getAllLocationList()
    {
        // count(pm_agency.api_key) as chk_key,
        $this->db->select('*');
        $this->db->from('nz_locations');
        $query = $this->db->get();
        return $query;
    }


    /**
     * [POST] Insert / Create
     */
    function addAgent($data)
    {
        $this->db->insert('pm_agent', $data);
        return $this->db->insert_id();
    }

    function addProperty($data)
    {
        $this->db->insert('pm_property', $data);
        return $this->db->insert_id();
    }

    function addPropertyPic($data)
    {
        $this->db->insert('pm_property_pic', $data);
    }

    function addPropertyAgent($data)
    {
        $this->db->insert('pm_property_agent', $data);
        return $this->db->insert_id();
    }

    function addopenhome($data)
    {
        $this->db->insert('pm_open_home', $data);
    }


    /**
     * [PUT] Update / Modify
     */


    /**
     * [DELETE] Delete / Destroy
     */
}
