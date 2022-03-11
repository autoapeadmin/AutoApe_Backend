<?php

defined('BASEPATH') or exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Webadmin
 *
 * @author mac
 */
class WebadminModel extends CI_Model
{

    function propertiesList()
    {
        $this->db->select('*');
        $this->db->from('pm_property');
        $this->db->join('pm_location', 'pm_location.location_id = pm_property.fk_location_id', 'left outer');
        $this->db->join('pm_agency', 'pm_agency.agency_id = pm_property.property_agency_id', 'left outer');
        $this->db->join('pm_property_type', 'pm_property_type.property_type_id = pm_property.fk_property_type_id', 'left outer');
        $this->db->where('pm_property.delete_flag', 0);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    function propertiesListBranch($branch_id, $filter)
    {
        $this->db->select('pm_property.*');
        $this->db->from('pm_property');
        $this->db->join('pm_location', 'pm_location.location_id = pm_property.fk_location_id', 'left outer');
        $this->db->join('pm_agent', 'pm_agent.agent_id = pm_property.fk_agent_id', 'left outer');
        $this->db->join('pm_branch', 'pm_branch.branch_id = pm_agent.fk_branch_id', 'left outer');
        $this->db->join('pm_property_type', 'pm_property_type.property_type_id = pm_property.fk_property_type_id', 'left outer');
        $this->db->where('pm_branch.branch_id', $branch_id);

        if ($filter == "1") {
            $this->db->order_by("pm_property.property_indate", "desc");
        } else if ($filter == "0") {
            $this->db->order_by("pm_agent.agent_first_name", "asc");
        }


        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    function propertiesListOrder()
    {
        $this->db->select('*');
        $this->db->from('pm_property');
        $this->db->join('pm_location', 'pm_location.location_id = pm_property.fk_location_id', 'left outer');
        $this->db->join('pm_agency', 'pm_agency.agency_id = pm_property.property_agency_id', 'left outer');
        $this->db->join('pm_property_type', 'pm_property_type.property_type_id = pm_property.fk_property_type_id', 'left outer');
        $this->db->join('pm_order', 'pm_property.property_id = pm_order.propertyid', 'left outer');
        $this->db->where('pm_property.delete_flag', 0);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }



    function propertyList($propertyId)
    {
        $this->db->select('*');
        $this->db->from('pm_property');
        $this->db->join('pm_location', 'pm_location.location_id = pm_property.fk_location_id', 'left outer');
        $this->db->join('pm_agency', 'pm_agency.agency_id = pm_property.property_agency_id', 'left outer');
        $this->db->join('pm_property_type', 'pm_property_type.property_type_id = pm_property.fk_property_type_id', 'left outer');
        $this->db->join('pm_city', 'pm_city.city_id = pm_location.fk_city_id', 'left outer');
        $this->db->join('pm_region', 'pm_region.region_id = pm_location.fk_region_id', 'left outer');
        $this->db->join('pm_suburb', 'pm_suburb.suburb_id = pm_location.fk_suburb_id', 'left outer');
        $this->db->where('pm_property.delete_flag', 0);

        $this->db->where('pm_property.property_id', $propertyId);

        $query = $this->db->get();
        $result = $query->result_array();

        return $result[0];
    }

    function openHomeList($propertyId)
    {
        $this->db->select('*');
        $this->db->from('pm_open_home');
        $this->db->where('pm_open_home.fk_property_id', $propertyId);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    function propertyAgentList($propertyId)
    {
        $this->db->select('*');
        $this->db->from('pm_property_agent');
        $this->db->join('pm_agent', 'pm_agent.agent_id = pm_property_agent.property_agent_id', 'left outer');
        $this->db->where('pm_agent.delete_flag', 0);
        //$this->db->where('pm_agent.verify_flag', 1);
        if ($propertyId != "all") {
            $this->db->where('pm_property_agent.fk_property_id', $propertyId);
        }
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    function propertyPicList($propertyId)
    {
        $this->db->select('*');
        $this->db->from('pm_property_pic');
        if ($propertyId != "all") {
            $this->db->where('pm_property_pic.fk_property_id', $propertyId);
        }
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    function getBanners()
    {
        $this->db->select('*');
        $this->db->from('ma_banner');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    function propertyTypeList()
    {
        $this->db->select('*');
        $this->db->from('pm_property_type');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    function getEmailList()
    {
        $this->db->select('*');
        $this->db->from('pm_email_landing');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    function agentBranchList($id)
    {
        $this->db->select('*');
        $this->db->from('pm_agent');
        $this->db->join('pm_branch', 'pm_branch.branch_id = pm_agent.fk_branch_id', 'left outer');
        $this->db->where('pm_agent.delete_flag', 0);
        $this->db->where('pm_branch.branch_id', $id);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    function deleteagent($idAgent, $idAgency, $AgentCode)
    { // del property
        $this->db->where('agent_id', $idAgent);
        $this->db->where('agent_code', $AgentCode);
        $this->db->where('fk_branch_id', $idAgency);
        $this->db->set('delete_flag', 1);
        return $this->db->update('pm_agent');
    }

    function delProperty($property_id)
    { // del property
        $this->db->where('property_id', $property_id);
        $this->db->set('delete_flag', 1);
        return $this->db->update('pm_property');
    }

    function delPropertyByReference($property_ref)
    { // del property
        $this->db->where('property_ref', $property_ref);
        $this->db->set('delete_flag', 1);
        return $this->db->update('pm_property');
    }

    function addcustomeragnets($data)
    {
        $this->db->insert('customer_agent', $data);
        return $this->db->insert_id();
    }

    function getpicsofproperti()
    {
        $query = $this->db->get_where('pm_property_pic', array('fk_property_id' => $_SESSION["pripertyid"]));
        return $query->result();
    }

    function addlocaltion($data)
    {
        $this->db->insert('pm_location', $data);
        return $this->db->insert_id();
    }

    function updatewithdraw($property_id, $data)
    {
        $this->db->where('property_id', $property_id);
        return $this->db->update('pm_property', $data);
    }



    function updatecustomercontact($customerid, $data)
    {

        $this->db->where('customer_id', $customerid);
        return $this->db->update('pm_customer', $data);
    }

    function modlocaltion($location_id, $data)
    {

        $this->db->where('location_id', $location_id);
        return $this->db->update('pm_location', $data);
    }

    function modAgent($id, $data)
    {
        $this->db->where('agent_id', $id);
        return $this->db->update('pm_agent', $data);
    }

    function addproperty($data)
    {

        $this->db->insert('pm_property', $data);
        return $this->db->insert_id();
    }

    function modproperty($property_id, $data)
    {

        $this->db->where('property_id', $property_id);
        return $this->db->update('pm_property', $data);
    }

    function getcustomerdetails($cusid)
    {
        $query = $this->db->get_where('pm_customer', array('customer_id' => $cusid));
        return $query->result();
    }

    function addPropertyAgent($data)
    {
        $this->db->insert('pm_property_agent', $data);
        return $this->db->insert_id();
    }

    function delPropertyAgent($property_id)
    {
        $this->db->where('fk_property_id', $property_id);
        return $this->db->delete('pm_property_agent');
    }

    function deleteLocation($location)
    {
        $this->db->where('location_id', $location);
        return $this->db->delete('pm_location');
        
    }

    function newOpenHomeList($propertyId)
    {
        $this->db->select('
            (select count(pm_visitor_log.visitor_id) from pm_visitor_log where fk_open_home_id = open_home_id) as chk_visitor,
            pm_open_home.*
        ');
        $this->db->from('pm_open_home');
        $this->db->where('pm_open_home.fk_property_id', $propertyId);
        $query = $this->db->get();
        return $query;
    }

    function addNewOpenHome($data)
    {
        $this->db->insert('pm_open_home', $data);
        return $this->db->insert_id();
    }

    function modNewOpenHome($open_home_id, $data)
    {
        $this->db->where('open_home_id', $open_home_id);
        return $this->db->update('pm_open_home', $data);
    }

    function delNewOpenHome($open_home_id)
    {
        $this->db->where('open_home_id', $open_home_id);
        return $this->db->delete('pm_open_home');
    }

    function addopenhome($data)
    {
        $this->db->insert('pm_open_home', $data);
    }

    function delopenhome($property_id)
    {

        $query = $this->db->get_where('pm_open_home', array('fk_property_id' => $property_id));
        $res = $query->result();

        if ($res) {
            foreach ($res as $value) {
                $this->db->where('fk_open_home_id', $value->open_home_id);
                $this->db->delete('pm_visitor_log');
            }
        }

        $this->db->where('fk_property_id', $property_id);
        return $this->db->delete('pm_open_home');
    }

    function addpics($data)
    {
        $this->db->insert('pm_property_pic', $data);
    }

    function delFirstPic($property_id)
    {
        $this->db->where('fk_property_id', $property_id);
        $this->db->where('property_first_pic', 1);
        return $this->db->delete('pm_property_pic');
    }

    function delOtherPics($property_id)
    {
        $this->db->where('fk_property_id', $property_id);
        $this->db->where('property_first_pic !=', 1);
        return $this->db->delete('pm_property_pic');
    }

    function getallagency()
    {
        $query = $this->db->get('pm_agency');
        return $query->result();
    }

    function addagent($data)
    {
        $this->db->insert('pm_agent', $data);
        return $this->db->insert_id();
    }


    function addbranchSubscription($data)
    {
        $this->db->insert('pm_branch_subscription', $data);
    }



    function addbranch($data)
    {
        $this->db->insert('pm_branch', $data);
        return $this->db->insert_id();
    }

    function addSubscription($data)
    {
        $this->db->insert('pm_subscription', $data);
        return $this->db->insert_id();
    }

    function addagency($data)
    {
        $this->db->insert('pm_agency', $data);
    }

    function imagepositin($image_id, $data)
    {
        $this->db->where('property_pic_id', $image_id);
        return $this->db->update('pm_property_pic', $data);
    }

    function couponList()
    {
        $this->db->select('*');
        $this->db->from('pm_coupon');
        $query = $this->db->get();
        return $query;
    }

    function subscriptionList()
    {
        $this->db->select('*');
        $this->db->from('pm_subscription');
        $this->db->join('pm_branch', 'pm_branch.branch_id = pm_subscription.fk_branch_id', 'left outer');
        $query = $this->db->get();
        return $query;
    }

    function typeSubscriptionList()
    {
        $query = $this->db->get('pm_subscription_type');
        return $query->result();
    }

    function branchList()
    {
        $this->db->select('*');
        $this->db->from('pm_branch');
        $this->db->join('pm_agency', 'pm_branch.fk_agency_id = pm_agency.agency_id', 'left outer');
        $query = $this->db->get();
        return $query;
    }

    function selectBranch($branch_id)
    {
        $this->db->select('*');
        $this->db->from('pm_branch');
        $this->db->join('pm_agency', 'pm_branch.fk_agency_id = pm_agency.agency_id', 'left outer');
        $this->db->where('pm_branch.branch_id', $branch_id);
        $query = $this->db->get();
        $result = $query->result();
        return $result[0];
    }



    function agencyList()
    {
        $this->db->select('*');
        $this->db->from('pm_agency');
        $query = $this->db->get();
        return $query;
    }

    function agentList()
    {
        $this->db->select('*');
        $this->db->from('pm_agent');
        $this->db->join('pm_branch', 'pm_branch.branch_id = pm_agent.fk_branch_id', 'left outer');
        $query = $this->db->get();
        return $query;
    }

    function selectChkCoupon($copcode)
    { // Checked
        $this->db->select('
            count(pm_coupon.co_code) as chk_coupon, 
        ');
        $this->db->from('pm_coupon');
        $this->db->where('pm_coupon.co_code', $copcode);
        $query = $this->db->get();
        $result = $query->result();
        return $result[0];
    }

    function selectChkBranch($bname)
    { // Checked
        $this->db->select('
            count(pm_branch.branch_id) as chk_branch, 
        ');
        $this->db->from('pm_branch');
        $this->db->where('pm_branch.branch_name', $bname);
        $query = $this->db->get();
        $result = $query->result();
        return $result[0];
    }

    function insertCoupon($data)
    { // Checked
        $this->db->insert('pm_coupon', $data);
        return $this->db->insert_id();
    }

    function updateCoupon($copid, $data)
    { // Checked
        $this->db->where('co_id', $copid);
        return $this->db->update('pm_coupon', $data);
    }

    function updateAgency($agency_id, $data)
    { // Checked
        $this->db->where('agency_id', $agency_id);
        return $this->db->update('pm_agency', $data);
    }

    function updateSubscription($agency_id, $data)
    { // Checked
        $this->db->where('subscription_id', $agency_id);
        return $this->db->update('pm_subscription', $data);
    }

    function updateBranch($agency_id, $data)
    { // Checked
        $this->db->where('branch_id', $agency_id);
        return $this->db->update('pm_branch', $data);
    }

    function getConfig()
    {
        $this->db->select('*');
        $this->db->from('pm_config');
        $this->db->where('config_id', 1);
        $query = $this->db->get();
        return $query;
    }

    function updateConfig($data)
    {
        $this->db->where('config_id', 1);
        return $this->db->update('pm_config', $data);
    }

    function getPaymentList()
    {
        $this->db->select('*');
        $this->db->from('pm_order');
        $this->db->join('pm_customer', 'pm_order.customer_id = pm_customer.customer_id', 'left outer');
        $this->db->join('pm_property', 'pm_order.propertyid = pm_property.property_id', 'left outer');
        $query = $this->db->get();
        return $query;
    }

    function getprivateagents()
    {
        $query = $this->db->get_where('pm_customer', array('is_private_agent' => 1));
        return $query->result();
    }
    function getCustomerList()
    {
        $this->db->select('*');
        $this->db->from('pm_customer');
        $query = $this->db->get();
        return $query;
    }

    function delimages($imageid)
    {
        $this->db->where('property_pic_id', $imageid);
        return $this->db->delete('pm_property_pic');
    }

    function getPropertyDetail($propertyId)
    {
        $this->db->select('*');
        $this->db->from('pm_property');
        $this->db->where('pm_property.property_id', $propertyId);
        $query = $this->db->get();
        return $query;
    }

    function renewToday($order_id, $data)
    {
        $this->db->where('pm_order.order_id', $order_id);
        return $this->db->update('pm_order', $data);
    }

    function adminlogin($email)
    {
        $this->db->select('*');
        $this->db->from('pm_admin');
        $this->db->where('pm_admin.admin_email', $email);
        $query = $this->db->get();
        $result = $query->result();

        if ($result) {
            return $result[0];
        } else {
            return FALSE;
        }
    }

    function getadmins()
    {
        $query = $this->db->get('pm_admin');
        return $query->result();
    }

    function getadmindetails()
    {
        $query = $this->db->get_where('pm_admin', array('admin_id' => $_SESSION["ADMIN"]));
        return $query->result();
    }

    function checkemail($email)
    {
        $query = $this->db->get_where('pm_admin', array('admin_email' => $email));
        $result = $query->result();
        if ($result) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function adminupdate($data, $advid)
    {
        $this->db->where('admin_id', $advid);
        $this->db->update('pm_admin', $data);
    }

    function updatepassword($email, $pw)
    {
        $this->db->where('admin_email', $email);
        $pws = password_hash($pw, PASSWORD_DEFAULT);
        $this->db->update('pm_admin', array("admin_pw" => $pws));
    }

    function addadmin($data)
    {
        $this->db->insert('pm_admin', $data);
    }

    function deleteadmin($id)
    {
        $this->db->where('admin_id', $id);
        $this->db->delete('pm_admin');
    }

    //API
    function updateLocation($data, $location_id)
    {
        $this->db->where('location_id', $location_id);
        return $this->db->update('pm_location', $data);
    }

    function updateProperty($data, $property_id)
    {
        $this->db->where('property_id', $property_id);
        return $this->db->update('pm_property', $data);
    }

    function updatePic($data)
    {
        $this->db->where('fk_property_id', $data['fk_property_id']);
        $this->db->where('property_first_pic', $data['property_first_pic']);
        return $this->db->update('pm_property_pic', $data);
    }

    function updateAllOpenHome($property_id)
    {
        $this->db->where('fk_property_id', $property_id);
        return $this->db->delete('pm_open_home');
    }


    function updateStatusHome($data, $property_ref)
    {
        $this->db->where('property_ref', $property_ref);
        return $this->db->update('pm_property', $data);
    }
}
