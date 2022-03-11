<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * PropertiMax
 * @author Edward An
 */
class Customer_Model extends CI_Model {

    function __construct() {
        //parent::__construct();
        $this->load->database();
    }

    /**
     * CRUD - Select
     */
    // getting the chat list of the current user
    function selectCustomerLogin($email, $password) {
        $sql = "
            SELECT pcr.customer_id , 
                   count(pcr.customer_email) as chk_email
             FROM  pm_customer pcr
            WHERE  pcr.delete_flag = 0
              AND  pcr.customer_email = ?
              AND  pcr.customer_password = ?
        ";
        $query = $this->db->query($sql, array($email, $password));
        $result = $query->result();
        return $result[0];
    }

    function selectCustomerEmail($email) {
        $this->db->select('
            pm_customer.customer_id, 
            pm_customer.customer_type,
            pm_customer.customer_fb_token, 
            pm_customer.social_id,
            pm_customer.social_token,
            pm_customer.customer_password, 
            pm_customer.customer_name, 
            pm_customer.customer_description,
            pm_customer.customer_email,
            pm_customer.customer_mobile, 
            pm_customer.customer_sort, 
            pm_customer.customer_pic, 
            pm_customer.is_private_agent,
            pm_customer.notifi_auction_3days,
            pm_customer.notifi_auction_day,
            pm_customer.notifi_agent_updates,
            pm_customer.notifi_saved_search,
            pm_customer.nofifi_frequency,
            pm_customer.verify_flag,
            count(pm_customer.customer_email) as chk_email
        ');
        $this->db->from('pm_customer');
        $this->db->where('pm_customer.customer_email', $email);
        $this->db->where('pm_customer.delete_flag', 0);
        $query = $this->db->get();
        $result = $query->result();
        return $result[0];
    }

    function selectBranchEmail($email) {
        $this->db->select('
           *,
            count(pm_branch.branch_email_account) as chk_email
        ');
        $this->db->from('pm_branch');
        $this->db->join('pm_agency', 'pm_agency.agency_id = pm_branch.fk_agency_id', 'left outer');
        $this->db->where('pm_branch.branch_email_account', $email);
        $this->db->where('pm_branch.branch_delete_flag', 0);
        $query = $this->db->get();
        $result = $query->result();
        return $result[0];
    }

    function selectCustomerSocial($social_id) {
        $this->db->select('
            pm_customer.customer_id, 
            pm_customer.customer_type,
            pm_customer.customer_fb_token, 
            pm_customer.social_id,
            pm_customer.social_token,
            pm_customer.customer_password, 
            pm_customer.customer_name, 
            pm_customer.customer_description,
            pm_customer.customer_email,
            pm_customer.customer_mobile, 
            pm_customer.customer_sort, 
            pm_customer.customer_pic, 
            pm_customer.notifi_auction_3days,
            pm_customer.notifi_auction_day,
            pm_customer.notifi_agent_updates,
            pm_customer.notifi_saved_search,
            pm_customer.nofifi_frequency,
            pm_customer.verify_flag,
            count(pm_customer.customer_email) as chk_email
        ');
        $this->db->from('pm_customer');
        $this->db->where('pm_customer.social_id', $social_id);
        $this->db->where('pm_customer.delete_flag', 0);
        $query = $this->db->get();
        $result = $query->result();
        return $result[0];
    }

    function selectCustomerPasswd($customer_id) {
        $this->db->select('customer_password');
        $this->db->from('pm_customer');
        $this->db->where('customer_id', $customer_id);
        $result = $this->db->get()->result();
        return $result[0];
    }

    

    function selectCustomerInfo($customer_id) {
        $this->db->select('
            pm_customer.customer_id, 
            pm_customer.customer_type,
            pm_customer.customer_fb_token, 
            pm_customer.social_id,
            pm_customer.social_token,
            pm_customer.customer_name, 
            pm_customer.customer_description,
            pm_customer.customer_email,
            pm_customer.customer_mobile, 
            pm_customer.customer_phone,
            pm_customer.customer_contact,
            pm_customer.customer_sort, 
            pm_customer.customer_pic, 
            pm_customer.notifi_auction_3days,
            pm_customer.notifi_auction_day,
            pm_customer.notifi_agent_updates,
            pm_customer.notifi_saved_search,
            pm_customer.nofifi_frequency,
            pm_customer.notifi_available_day,
            pm_customer.verify_flag
        ');
        $this->db->from('pm_customer');
        $this->db->where('customer_id', $customer_id);
        $result = $this->db->get()->result();
        return $result[0];
    }

    function selectUserTypeList(){
        $this->db->select('*');
        $this->db->from('pm_user_type');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    function selectAllRegion(){
        $this->db->select('*');
        $this->db->from('pm_region');
        $this->db->where('region_id !=', "0");
        $query = $this->db->get();
        return $query->result();
    }

    function selectAllSuburb(){
        $this->db->select('*');
        $this->db->from('pm_city');
        $this->db->where('city_id !=', "0");
        $query = $this->db->get();
        return $query->result();
    }

    function selectAllCity(){
        $this->db->select('*');
        $this->db->from('pm_suburb');
        $this->db->join('pm_city', 'pm_suburb.fk_city_id = pm_city.city_id', 'left outer');
        $this->db->where('suburb_id !=', "0");
        $query = $this->db->get();
        return $query->result();
    }

    function selectSearchList($customer_id, $search_sale_flag) {
        $this->db->select('*');
        $this->db->from('pm_search_list');
        $this->db->join('pm_search', 'pm_search.search_id = pm_search_list.fk_search_id', 'left outer');
        $this->db->join('pm_location', 'pm_search.fk_location_id = pm_location.location_id', 'left outer');
        $this->db->where('pm_search_list.fk_customer_id', $customer_id);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    // $ForSale = $this->Customer_Model->selectForSale($csustomer_id);
    // $Auctions = $this->Customer_Model->selectAuctions($customer_id);
    // $OpenHome = $this->Customer_Model->selectOpenHome($customer_id);
    // $Local = $thi s->Customer_Model->selectLocal($customer_id);

    function selectForSale($customer_id) {
        $this->db->select('pm_property.property_id');
        $this->db->from('pm_max_list');
        $this->db->join('pm_property', 'pm_property.property_id = pm_max_list.fk_property_id', 'left outer');
        $this->db->where('pm_max_list.fk_customer_id', $customer_id);
        $this->db->where('pm_property.property_sale_flag', 0); // Sale
        $this->db->where('pm_property.property_disable', 0);
        $this->db->where('pm_property.delete_flag', 0);
        $result = $this->db->count_all_results();

        return $result;
    }

    function selectAuctions($customer_id) {
        $this->db->select('pm_property.property_id');
        $this->db->from('pm_max_list');
        $this->db->join('pm_property', 'pm_property.property_id = pm_max_list.fk_property_id', 'left outer');
        $this->db->where('pm_max_list.fk_customer_id', $customer_id);
        $this->db->where('pm_property.property_auction', 1);
        //$this->db->where('pm_property.property_auction_date >= CURRENT_TIMESTAMP()');
        // Alex told to update in next day 19-05-2019
        //$addtoday1day=date('Y-m-d', strtotime(' + 1 day'));
        
        $addtoday1day=date('Y-m-d');
            $this->db->where('DATE_FORMAT(pm_property.property_auction_date,"%Y-%m-%d ") >= ',$addtoday1day);
            
            
        $this->db->where('pm_property.property_sale_flag', 0); // Sale
        $this->db->where('pm_property.property_disable', 0);
        $this->db->where('pm_property.delete_flag', 0);
        $result = $this->db->count_all_results();

        return $result;
    }

    function selectOpenHome($customer_id) {
        $this->db->select('pm_property.property_id, DATE(pm_open_home.open_home_date) as open_home_time');
        $this->db->from('pm_max_list');
        $this->db->join('pm_property', 'pm_property.property_id = pm_max_list.fk_property_id', 'left outer');
        $this->db->join('pm_open_home', 'pm_open_home.fk_property_id = pm_property.property_id');
        //$this->db->where('DATE_FORMAT(pm_open_home.open_home_from,"%Y-%m-%d ") = DATE_FORMAT(now(),"%Y-%m-%d ")');
        $nextsat = date("Y-m-d",strtotime('saturday'));
        $nextsun = date("Y-m-d",strtotime('sunday'));
        $this->db->or_where('DATE(pm_open_home.open_home_date) >',$nextsat);
        $this->db->or_where('DATE(pm_open_home.open_home_date) >',$nextsun);
        $this->db->where('pm_max_list.fk_customer_id', $customer_id);
        $this->db->where('pm_property.property_sale_flag', 0); // Sale
        $this->db->where('pm_property.property_disable', 0);
        $this->db->where('pm_property.delete_flag', 0);
        $this->db->group_by("pm_open_home.open_home_id");
        $result = $this->db->count_all_results();
        return $result;
    }

    function selectForRental($customer_id) {
        $this->db->select('pm_property.property_id');
        $this->db->from('pm_max_list');
        $this->db->join('pm_property', 'pm_property.property_id = pm_max_list.fk_property_id', 'left outer');
        $this->db->where('pm_max_list.fk_customer_id', $customer_id);
        $this->db->where('pm_property.property_sale_flag', 1); // Rental
        $date = date('n', strtotime('+1 month'));
        $this->db->where('MONTH(`pm_property`.`property_available_date`) >= ', $date);
         $this->db->where('YEAR(`pm_property`.`property_available_date`) >= YEAR(NOW())');
        $this->db->where('pm_property.property_disable', 0);
        $this->db->where('pm_property.delete_flag', 0);
        $result = $this->db->count_all_results();

        return $result;
    }

    function selectAllAgentContact($customer_id) {
        $this->db->select('pm_search.search_id');
        $this->db->from('pm_agent_list');
        $this->db->where('pm_agent_list.fk_customer_id', $customer_id);
        $result = $this->db->count_all_results();
        return $result;
    }

    function selectAllSearchList($customer_id) {
        $this->db->select('pm_search.search_id');
        $this->db->from('pm_search_list');
        $this->db->join('pm_search', 'pm_search.search_id = pm_search_list.fk_search_id', 'left outer');
        $this->db->join('pm_location', 'pm_search.fk_location_id = pm_location.location_id', 'left outer');
        $this->db->where('pm_search_list.fk_customer_id', $customer_id);
        $result = $this->db->count_all_results();
        return $result;
    }


    function selectAvailableAgo($customer_id) {
        $this->db->select('pm_property.property_id');
        $this->db->from('pm_max_list');
        $this->db->join('pm_property', 'pm_property.property_id = pm_max_list.fk_property_id', 'left outer');
        $this->db->where('pm_max_list.fk_customer_id', $customer_id);
        $this->db->where('pm_property.property_sale_flag', 1); // Rental
        //$this->db->where('pm_property.property_available_date BETWEEN NOW()-INTERVAL 1 MONTH AND NOW()'); // Betweent 1 Month Ago and Now
        $this->db->where('`pm_property`.`property_available_date` <= NOW()');
        $this->db->where('pm_property.property_disable', 0);
        $this->db->where('pm_property.delete_flag', 0);
        $result = $this->db->count_all_results();
        return $result;
    }

    function selectAvailablePast($customer_id) {
        $this->db->select('pm_property.property_id');
        $this->db->from('pm_max_list');
        $this->db->join('pm_property', 'pm_property.property_id = pm_max_list.fk_property_id', 'left outer');
        $this->db->where('pm_max_list.fk_customer_id', $customer_id);
        $this->db->where('pm_property.property_sale_flag', 1); // Rental
        $this->db->where('`pm_property`.`property_available_date` >= NOW()');
        $this->db->where('MONTH(`pm_property`.`property_available_date`) = MONTH(NOW())'); // Betweent 1 Month Ago and Now
        
        $this->db->where('pm_property.property_disable', 0);
        $this->db->where('pm_property.delete_flag', 0);
        $result = $this->db->count_all_results();
        return $result;
    }

    function selectLocal($customer_id) {
        $this->db->select('pm_property.property_id, pm_location.*');
        $this->db->from('pm_max_list');
        $this->db->join('pm_property', 'pm_property.property_id = pm_max_list.fk_property_id', 'left outer');
        $this->db->join('pm_location', 'pm_location.location_id = pm_property.fk_location_id', 'left outer');
        $this->db->where('pm_max_list.fk_customer_id', $customer_id);
        $this->db->where('pm_property.property_sale_flag', 0); // Sale
        $this->db->where('pm_property.property_disable', 0);
        $this->db->where('pm_property.delete_flag', 0);
        $query = $this->db->get();
        $result = $query->result_array();

        return $result;
    }

    /** Max List Flag
     * $search_flag = 0: Sale, 1: Rent
     * - Sale $search_option = 0: All Sales, 1: Auctions, 2: OpenHome
     * - Rent $search_option = 0: Now, 1: This Month, 2: Next Month
     */
    function selectMaxList($customer_id, $search_flag, $search_option,$page) {
        if ($search_flag == 0 && $search_option == 2) { // OpenHome
            $this->db->select('pm_open_home.open_home_from as open_home_time');
        } else {
            $this->db->select('*');
        }

        $this->db->from('pm_max_list');

        $this->db->join('pm_property', 'pm_property.property_id = pm_max_list.fk_property_id', 'left outer');
        $this->db->join('pm_location', 'pm_location.location_id = pm_property.fk_location_id', 'left outer');
        $this->db->join('pm_property_type', ' pm_property_type.property_type_id= pm_property.fk_property_type_id', 'left outer');
        if ($search_flag == 0 && $search_option == 1) { // Auctions = 0: No, 1: Yes
            $this->db->where('pm_property.property_auction', 1);
            //$addtoday1day=date('Y-m-d', strtotime(' + 1 day'));
            $addtoday1day=date('Y-m-d');
            $this->db->where('DATE_FORMAT(pm_property.property_auction_date,"%Y-%m-%d ") >= ',$addtoday1day);
        }

        if ($search_flag == 0 && $search_option == 2) { // OpenHome
            $this->db->join('pm_open_home', 'pm_open_home.fk_property_id = pm_property.property_id');
            $this->db->where('DATE_FORMAT(pm_open_home.open_home_from,"%Y-%m-%d ") >= DATE_FORMAT(NOW(),"%Y-%m-%d ")');
            ///// rochas
        }



        $this->db->where('pm_max_list.fk_customer_id', $customer_id);
        $this->db->where('pm_property.property_sale_flag', $search_flag);
        $this->db->where('pm_property.property_disable', 0);
        $this->db->where('pm_property.delete_flag', 0);

        if ($search_flag == 1) {
            switch ($search_option) {
                case 0:
                    $this->db->where('`pm_property`.`property_available_date` <= NOW()');
                    break;
                case 1:
                    $this->db->where('MONTH(`pm_property`.`property_available_date`) = MONTH(NOW())');
                    $this->db->where('YEAR(`pm_property`.`property_available_date`) = YEAR(NOW())');
                    $this->db->where('DATE_FORMAT(`pm_property`.`property_available_date`,"%Y-%m-%d ") > DATE_FORMAT(NOW(),"%Y-%m-%d ")'); // alex changed 24/08/2018 at 12:59 PM
                    break;
                case 2:
                    $date = date('n', strtotime('+1 month'));
                    $this->db->where('MONTH(`pm_property`.`property_available_date`) >= ', $date);
                    $this->db->where('YEAR(`pm_property`.`property_available_date`) >= YEAR(NOW())');
                    break;
            }
            $this->db->order_by("property_available_date", "desc");
        }

        if ($search_flag == 0 && $search_option == 0) { // all sale
            $this->db->order_by("pm_max_list.maxlistaddate", "desc");
        }

        if ($search_flag == 0 && $search_option == 1) { // Auctions = 0: No, 1: Yes
            $this->db->order_by("pm_property.property_auction_date", "asc");
        }   


        if ($search_flag == 0 && $search_option == 2) { // OpenHome
            $this->db->group_by("DATE(pm_open_home.open_home_from)");
        }

        //Pagination
        if(isset($page)){
            $this->db->limit(10,$page); 
        }else{
            $this->db->limit(50); 
        }

        $query = $this->db->get();

        //      $result = $query->result_array();
//echo $this->db->last_query();
//exit();
        return $query;
    }

    function selectMaxOpenHomeList($customer_id, $search_flag, $open_home_time) { // for Customer
        $this->db->select('*');
        $this->db->from('pm_max_list');
        $this->db->join('pm_property', 'pm_property.property_id = pm_max_list.fk_property_id', 'left outer');
        $this->db->join('pm_location', 'pm_location.location_id = pm_property.fk_location_id', 'left outer');
        $this->db->join('pm_open_home', 'pm_open_home.fk_property_id = pm_property.property_id');
        $this->db->where('pm_max_list.fk_customer_id', $customer_id);
        $this->db->where('pm_property.property_sale_flag', $search_flag);
        $this->db->where("DATE(pm_open_home.open_home_from)= DATE('" . $open_home_time . "')");
        $this->db->where('pm_property.delete_flag', 0);
        $query = $this->db->get();

        //echo $this->db->last_query();
        //exit();
        // $result = $query->result_array();

        return $query;
    }

    function selectTargetSearch($search_id) {
        $this->db->select('*');
        $this->db->from('pm_search');
        $this->db->join('pm_location', 'pm_search.fk_location_id = pm_location.location_id', 'left outer');
        $this->db->where('pm_search.search_id', $search_id);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    function selectPropertyType($search_id) {
        $this->db->select('fk_property_type_id');
        $this->db->from('pm_property_type_list');
        $this->db->where('pm_property_type_list.fk_search_id', $search_id);
        $query = $this->db->get();
        // $result = $query->result_array();
        return $query;
    }

    function selectSuburbs($search_id) {
        $this->db->select('fk_proprty_subrub_id');
        $this->db->from('pm_subrub_save_list');
        $this->db->where('pm_subrub_save_list.fk_search_id', $search_id);
        $query = $this->db->get();
        // $result = $query->result_array();
        return $query;
    }

    function selectTargetPropertyType($search_id) {
        $this->db->select('*');
        $this->db->from('pm_property_type');
        $this->db->join('pm_property_type_list', 'pm_property_type.property_type_id = pm_property_type_list.fk_property_type_id', 'left outer');
        $this->db->where('pm_property_type_list.fk_search_id', $search_id);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    function selectAgentList($key_word, $logged, $company, $sort, $sort_option, $city_id, $region_id, $subrubs) {
        if ($logged == 0) {
            $this->db->select('
                0 as is_added,
                pm_agent.*,
                pm_branch.*,
                pm_agency.agency_address,
                CONCAT(pm_agent.agent_first_name, " ",pm_agent.agent_last_name) agent_full_name,
                pm_agency.agency_id, 
                pm_agency.agency_name,
                pm_agency.agency_pic
            ');
            $this->db->from('pm_agent');
            $this->db->join('pm_branch', 'pm_agent.fk_branch_id = pm_branch.branch_id');
            $this->db->join('pm_agency', 'pm_branch.fk_agency_id = pm_agency.agency_id');
            $this->db->join('pm_location', 'pm_branch.fk_location_id = pm_location.location_id');
            $this->db->join('pm_suburb', 'pm_location.fk_suburb_id = pm_suburb.suburb_id');
            $this->db->join('pm_region', 'pm_location.fk_region_id = pm_region.region_id');
            $this->db->join('pm_city', 'pm_location.fk_city_id = pm_city.city_id');
            $this->db->where('pm_agent.delete_flag', 0);
            // $this->db->where('pm_agency.agency_id != ', 0);
        } else {
            $this->db->select('
                IF(pm_agent_list.fk_customer_id IS NULL,0,1) as is_added,
                pm_agent.*,
                pm_branch.*,
                CONCAT(pm_agent.agent_first_name, " ",pm_agent.agent_last_name) agent_full_name, 
                pm_agency.agency_id, 
                pm_agency.agency_name,
                pm_agency.agency_pic
            ');
            $this->db->from('pm_agent');
            $this->db->join('pm_branch', 'pm_agent.fk_branch_id = pm_branch.branch_id');
            $this->db->join('pm_agency', 'pm_branch.fk_agency_id = pm_agency.agency_id');
            $this->db->join('pm_agent_list', "pm_agent_list.fk_agent_id = pm_agent.agent_id and pm_agent_list.fk_customer_id = '$logged'", 'left outer');
            $this->db->join('pm_location', 'pm_branch.fk_location_id = pm_location.location_id');
            $this->db->join('pm_suburb', 'pm_location.fk_suburb_id = pm_suburb.suburb_id');
            $this->db->join('pm_region', 'pm_location.fk_region_id = pm_region.region_id');
            $this->db->join('pm_city', 'pm_location.fk_city_id = pm_city.city_id');
            $this->db->where('pm_agent.delete_flag', 0);
            // $this->db->where('pm_agency.agency_id != ', 0);
            // $this->db->where('pm_agent_list.fk_customer_id', $logged);

        }

        if (!empty($sort_option) && $sort_option == '4') {
            if (sizeof($subrubs) > 0) {
                if (!in_array("0", $subrubs)) {
                    $this->db->where_in('pm_location.fk_suburb_id', $subrubs);
                }
            }
            if ($city_id != 0) {
                $this->db->where('pm_location.fk_city_id', $city_id);
            }
            if ($region_id != 0) {
                $this->db->where('pm_location.fk_region_id', $region_id);
            }
        }

        if (!empty($company)) {
            $this->db->like('pm_agency.agency_name', $company, 'both');
            $this->db->or_like('pm_agent.agent_office', $company, 'both');
        }

        $this->db->get();

        // save the sub query in variable (Sub Query - Start)
        $sub_query = $this->db->last_query();
        $this->db->select("T1.*");
        $this->db->from("($sub_query) as T1");



        if (!empty($key_word)) { ////
            // $this->db->where("'%".$key_word."%' LIKE CONCAT(CONCAT('%', `T1`.`agent_first_name`),'%')");
            // $this->db->where("'%".$key_word."%' LIKE CONCAT(CONCAT('%', `T1`.`agent_first_name`),'%')");
            // $this->db->or_where("'%".$key_word."%' LIKE CONCAT(CONCAT('%', `T1`.`agent_last_name`), '%')");
            $this->db->like('T1.agent_first_name', $key_word, 'both');
            $this->db->or_like('T1.agent_last_name', $key_word, 'both');
            $this->db->or_like('T1.agent_full_name', $key_word, 'both');
        }

        if (!empty($location)) {
            $this->db->where("T1.location_id", $location);
        }

        if (!empty($sort) && $sort == 'D') {
            if (!empty($sort_option) && $sort_option == '1') {
                $this->db->order_by('T1.agent_first_name', 'DESC');
            } else if (!empty($sort_option) && $sort_option == '2') {
                $this->db->order_by('T1.agent_last_name', 'DESC');
            } else if (!empty($sort_option) && $sort_option == '3') {
                $this->db->order_by('T1.agency_id', 'DESC');
            } else if (!empty($sort_option) && $sort_option == '4') {
                $this->db->order_by('T1.location_id', 'DESC');
            } else {
                $this->db->order_by('T1.agent_first_name', 'DESC');
            }
        } else {
            if (!empty($sort_option) && $sort_option == '1') {
                $this->db->order_by('T1.agent_first_name', 'ASC');
            } else if (!empty($sort_option) && $sort_option == '2') {
                $this->db->order_by('T1.agent_last_name', 'ASC');
            } else if (!empty($sort_option) && $sort_option == '3') {
                $this->db->order_by('T1.agency_id', 'ASC');
            } else if (!empty($sort_option) && $sort_option == '4') {
                $this->db->order_by('T1.location_id', 'ASC');
            } else {
                $this->db->order_by('T1.agent_first_name', 'ASC');
            }
        }

        //$this->db->order_by('T1.agent_first_name', 'ASC');

        $query = $this->db->get();
        // save the sub query in variable (Sub Query - End)
        //echo $this->db->last_query();
        //exit();

        $result = $query->result_array();

        
        return $result;
    }

    public function getPropertiesNear($lat, $lng, $search_flag) {

        $this->db->select('
        pm_property.*,
        pm_agency.*,
        pm_location.*,
        pm_property_type.*,
        ROUND(6371 * ACOS(LEAST(COS(RADIANS(pm_location.lat)) * COS(RADIANS('.$lat .')) * COS(RADIANS(pm_location.long -  '. $lng .')) + SIN(RADIANS(pm_location.lat)) * SIN(RADIANS('. $lat .')),1.0)),1) as distance
        ');
        $this->db->from('pm_property');
        $this->db->join('pm_location', 'pm_location.location_id = pm_property.fk_location_id');
        $this->db->join('pm_agent', 'pm_property.fk_agent_id = pm_agent.agent_id');
        $this->db->join('pm_branch', 'pm_agent.fk_branch_id = pm_branch.branch_id');
        $this->db->join('pm_agency', 'pm_branch.fk_agency_id = pm_agency.agency_id');
        $this->db->join('pm_property_type', 'pm_property.fk_property_type_id = pm_property_type.property_type_id', 'left outer');
        $this->db->where("6371 * ACOS(LEAST(COS(RADIANS(pm_location.lat)) * COS(RADIANS(".$lat .")) * COS(RADIANS(pm_location.long -  ". $lng .")) + SIN(RADIANS(pm_location.lat)) * SIN(RADIANS(". $lat .")),1.0)) <= 3");
        $this->db->where('pm_property.delete_flag', 0);
        $this->db->where('pm_property.property_sale_flag', $search_flag);
        $this->db->order_by('distance', 'asc');
        $this->db->limit(15,0); 
        $query = $this->db->get();
//exit();
        return $query;
    }

    public function getAgentFilter($lat,$lng,$radiusFrom,$radiusTo,$role,$languages,$logged){
        
        if ($logged == 0){
            $this->db->select("
            pm_agent.agent_id, 0 as is_added,
            pm_agent.agent_pic,
            pm_agent.agent_first_name,
            pm_agent.agent_last_name, 
            pm_agent.agent_occupation,
            pm_agent.agent_mobile, 
            pm_branch.branch_office_address as agency_address,
            pm_agency.agency_id,
            pm_branch.branch_full_name as office_name,
            pm_agent.agent_website, 
            pm_agency.agency_name,
            pm_agency.agency_pic,
            pm_location.lat,
            pm_location.long as _long,
            ROUND(6371 * ACOS(LEAST(COS(RADIANS(pm_location.lat)) * COS(RADIANS(".$lat .")) * COS(RADIANS(pm_location.long -  ". $lng .")) + SIN(RADIANS(pm_location.lat)) * SIN(RADIANS(". $lat .")),1.0)),1) as distance");
            $this->db->from('pm_agent');
            $this->db->join('pm_branch', 'pm_agent.fk_branch_id = pm_branch.branch_id');
            $this->db->join('pm_agency', 'pm_branch.fk_agency_id = pm_agency.agency_id');
            $this->db->join('pm_location', 'pm_branch.fk_location_id = pm_location.location_id');

           

            if($languages!=null){
                $this->db->join('pm_agent_language', 'pm_agent.agent_id = pm_agent_language.fk_id_agent');
            }


            $this->db->where("6371 * ACOS(LEAST(COS(RADIANS(pm_location.lat)) * COS(RADIANS(".$lat .")) * COS(RADIANS(pm_location.long -  ". $lng .")) + SIN(RADIANS(pm_location.lat)) * SIN(RADIANS(". $lat .")),1.0)) <= ".$radiusTo.""); 
            $this->db->where("6371 * ACOS(LEAST(COS(RADIANS(pm_location.lat)) * COS(RADIANS(".$lat .")) * COS(RADIANS(pm_location.long -  ". $lng .")) + SIN(RADIANS(pm_location.lat)) * SIN(RADIANS(". $lat .")),1.0)) >= ".$radiusFrom.""); 
            $this->db->where('pm_agent.delete_flag', 0);
            $this->db->where('pm_agent.agent_public', 0);
            $this->db->where('pm_agent.agent_occupation', $role);

            if($languages!=null){
                $this->db->where_in('pm_agent_language.fk_id_language', $languages);
            }
            


            $this->db->group_by('pm_agent.agent_id');
        }else{
            $this->db->select("
            pm_agent.agent_id,
            IF(pm_agent_list.fk_customer_id IS NULL,0,1) as is_added,
            pm_agent.agent_pic,
            pm_branch.branch_office_address as agency_address,
            pm_agent.agent_first_name,
            pm_agent.agent_last_name,
            pm_agent.agent_occupation,
            pm_agent.agent_mobile,
            pm_agency.agency_id, 
            pm_agent.agent_website, 
            pm_agency.agency_name,
            pm_branch.branch_full_name as office_name,
            pm_agency.agency_pic,
            pm_location.lat,
            pm_location.long as _long,
            ROUND(6371 * ACOS(LEAST(COS(RADIANS(pm_location.lat)) * COS(RADIANS(".$lat .")) * COS(RADIANS(pm_location.long -  ". $lng .")) + SIN(RADIANS(pm_location.lat)) * SIN(RADIANS(". $lat .")),1.0)),1) as distance");
            $this->db->from('pm_agent');
            $this->db->join('pm_branch', 'pm_agent.fk_branch_id = pm_branch.branch_id');
            $this->db->join('pm_agency', 'pm_branch.fk_agency_id = pm_agency.agency_id');
            $this->db->join('pm_location', 'pm_branch.fk_location_id = pm_location.location_id');
            $this->db->join('pm_agent_list', "pm_agent_list.fk_agent_id = pm_agent.agent_id and pm_agent_list.fk_customer_id = '$logged'", 'left outer');
            if($languages!=null){
                $this->db->join('pm_agent_language', 'pm_agent.agent_id = pm_agent_language.fk_id_agent');
            }
            $this->db->where("6371 * ACOS(LEAST(COS(RADIANS(pm_location.lat)) * COS(RADIANS(".$lat .")) * COS(RADIANS(pm_location.long -  ". $lng .")) + SIN(RADIANS(pm_location.lat)) * SIN(RADIANS(". $lat .")),1.0)) <= ".$radiusTo.""); 
            $this->db->where("6371 * ACOS(LEAST(COS(RADIANS(pm_location.lat)) * COS(RADIANS(".$lat .")) * COS(RADIANS(pm_location.long -  ". $lng .")) + SIN(RADIANS(pm_location.lat)) * SIN(RADIANS(". $lat .")),1.0)) >= ".$radiusFrom.""); 
            $this->db->where('pm_agent.delete_flag', 0);
            $this->db->where('pm_agent.agent_public', 0);
            $this->db->where('pm_agent.agent_occupation', $role);
            if($languages!=null){
                $this->db->where_in('pm_agent_language.fk_id_language', $languages);
            }
            $this->db->group_by('pm_agent.agent_id');
        }
    
        $query = $this->db->get();
        $result = $query->result();
        //print_r($this->db->last_query());
        return $result;

    }

    public function getAllRequestsWithDistance($lat, $lng, $radius,$logged,$page,$sort_option) {

        if ($logged == 0){
            $this->db->select('
        pm_agent.agent_id, 0 as is_added,
        pm_agent.agent_pic, 
        pm_agent.agent_first_name, 
        pm_agent.agent_last_name, 
        pm_agent.agent_occupation,
        pm_agent.agent_mobile,
        pm_branch.branch_office_address as agency_address,
        pm_agency.agency_id,
        pm_branch.branch_full_name as office_name,
        pm_agent.agent_website, 
        pm_agency.agency_name,
        pm_agency.agency_pic,
        pm_location.lat,
        pm_location.long as _long,
        ROUND(6371 * ACOS(LEAST(COS(RADIANS(pm_location.lat)) * COS(RADIANS('.$lat .')) * COS(RADIANS(pm_location.long -  '. $lng .')) + SIN(RADIANS(pm_location.lat)) * SIN(RADIANS('. $lat .')),1.0)),1) as distance
        ');
        $this->db->from('pm_agent');
        $this->db->join('pm_branch', 'pm_agent.fk_branch_id = pm_branch.branch_id');
        $this->db->join('pm_agency', 'pm_branch.fk_agency_id = pm_agency.agency_id');
        $this->db->join('pm_location', 'pm_branch.fk_location_id = pm_location.location_id');
        $this->db->where("6371 * ACOS(LEAST(COS(RADIANS(pm_location.lat)) * COS(RADIANS(".$lat .")) * COS(RADIANS(pm_location.long -  ". $lng .")) + SIN(RADIANS(pm_location.lat)) * SIN(RADIANS(". $lat .")),1.0)) <= ".$radius.""); 
        $this->db->where('pm_agent.delete_flag', 0);
        $this->db->where('pm_agent.agent_public', 0);

        }else{
            $this->db->select('
        pm_agent.agent_id,
        IF(pm_agent_list.fk_customer_id IS NULL,0,1) as is_added,
        pm_agent.agent_pic, 
        pm_branch.branch_office_address as agency_address,
        pm_agent.agent_first_name, 
        pm_agent.agent_last_name, 
        pm_agent.agent_occupation,
        pm_agent.agent_mobile,
        pm_agency.agency_id, 
        pm_agent.agent_website, 
        pm_agency.agency_name,
        pm_branch.branch_full_name as office_name,
        pm_agency.agency_pic,
        pm_location.lat,
        pm_location.long as _long,
        ROUND(6371 * ACOS(LEAST(COS(RADIANS(pm_location.lat)) * COS(RADIANS('.$lat .')) * COS(RADIANS(pm_location.long -  '. $lng .')) + SIN(RADIANS(pm_location.lat)) * SIN(RADIANS('. $lat .')),1.0)),1) as distance
         ');
         $this->db->from('pm_agent');
         $this->db->join('pm_branch', 'pm_agent.fk_branch_id = pm_branch.branch_id');
         $this->db->join('pm_agency', 'pm_branch.fk_agency_id = pm_agency.agency_id');
         $this->db->join('pm_location', 'pm_branch.fk_location_id = pm_location.location_id');
        $this->db->join('pm_agent_list', "pm_agent_list.fk_agent_id = pm_agent.agent_id and pm_agent_list.fk_customer_id = '$logged'", 'left outer');
        $this->db->where("6371 * ACOS(LEAST(COS(RADIANS(pm_location.lat)) * COS(RADIANS(".$lat .")) * COS(RADIANS(pm_location.long -  ". $lng .")) + SIN(RADIANS(pm_location.lat)) * SIN(RADIANS(". $lat .")),1.0)) < ".$radius."");     
        $this->db->where('pm_agent.delete_flag', 0);
        $this->db->where('pm_agent.agent_public', 0);

        }

        //sort
        if (!empty($sort_option) && $sort_option == '1') {
            $this->db->order_by('pm_agent.agent_first_name', 'ASC');
        } else if (!empty($sort_option) && $sort_option == '2') {
            $this->db->order_by('pm_agent.agent_last_name', 'ASC');
        } else if (!empty($sort_option) && $sort_option == '3') {
            $this->db->order_by('pm_branch.branch_name', 'ASC');
        } else {
            $this->db->order_by('distance', 'ASC');
        }

          //Pagination
        if(isset($page)){
            $this->db->limit(8,$page); 
        }else{
            $this->db->limit(50); 
        }
    
        //$query = $this->db->query("select pm_agent.* from pm_location , pm_agent left join `en_application` c using(jobid) where a.is_deleted=0 and a.`userid`=b.`userid` and a.`status` in (\"open\", \"processing\") and a.jobid<? and (((acos(sin((?*pi()/180)) * sin((a.`latitude`*pi()/180))+cos((?*pi()/180)) * cos((a.`latitude`*pi()/180)) * cos(((?-a.`longitude`)*pi()/180))))*180/pi())*60*1.1515*1.609344) < ? group by a.jobid order by publish_time desc limit ?;", array($last_index, $lat, $lat, $lng, $radius, self::item_per_page));
        $query = $this->db->get();
        $result = $query->result();
        
        //print_r($this->db->last_query());

        return $result;
    }

    public function getAllRequestsWithDistanceSearch($lat, $lng, $radius,$logged,$key_word,$page) {

        if ($logged == 0){

            $this->db->select('
            pm_agent.agent_id, 0 as is_added,
            pm_agent.agent_pic, 
            pm_agent.agent_first_name, 
            pm_agent.agent_last_name, 
            pm_agent.agent_occupation,
            pm_agent.agent_mobile,
            pm_branch.branch_office_address as agency_address,
            pm_agency.agency_id,
            pm_branch.branch_full_name as office_name,
            pm_agent.agent_website, 
            pm_agency.agency_name,
            pm_agency.agency_pic,
            pm_location.lat,
            pm_location.long as _long,
            ROUND(6371 * ACOS(LEAST(COS(RADIANS(pm_location.lat)) * COS(RADIANS('.$lat .')) * COS(RADIANS(pm_location.long -  '. $lng .')) + SIN(RADIANS(pm_location.lat)) * SIN(RADIANS('. $lat .')),1.0)),1) as distance
            ');
        $this->db->from('pm_agent');
        $this->db->join('pm_branch', 'pm_agent.fk_branch_id = pm_branch.branch_id');
        $this->db->join('pm_agency', 'pm_branch.fk_agency_id = pm_agency.agency_id');
        $this->db->join('pm_location', 'pm_branch.fk_location_id = pm_location.location_id');
          
        $this->db->where('pm_agent.delete_flag', 0);
        $this->db->where('pm_agent.agent_public', 0);
        $this->db->like('TRIM(pm_agent.agent_first_name)', $key_word,'none');
        $this->db->order_by('pm_agent.agent_id', 'DESC');

        $this->db->or_where('pm_agent.delete_flag', 0);
        $this->db->like('TRIM(pm_agent.agent_last_name)', $key_word,'none');
        $this->db->order_by('pm_agent.agent_id', 'DESC');

        $this->db->or_where('pm_agent.delete_flag', 0);
        $this->db->like('CONCAT(pm_agent.agent_first_name, " ",pm_agent.agent_last_name)', $key_word,'none');
        $this->db->order_by('pm_agent.agent_id', 'DESC');


        $this->db->or_where('pm_agent.delete_flag', 0);
        $this->db->like('pm_agent.agent_mobile', $key_word, 'both');
        $this->db->order_by('pm_agent.agent_id', 'DESC');

        $this->db->or_where('pm_agent.delete_flag', 0);
        $this->db->like('pm_agent.agent_phone', $key_word, 'both');
        $this->db->order_by('pm_agent.agent_id', 'DESC');



           //Pagination
           //if(isset($page)){
           // $this->db->limit(10,$page); 
        //}else{
            $this->db->limit(50); 
        //}
        $this->db->order_by('pm_agent.agent_id', 'DESC');

        }else{

            $this->db->select('
            pm_agent.agent_id,
            IF(pm_agent_list.fk_customer_id IS NULL,0,1) as is_added,
            pm_agent.agent_pic, 
            pm_branch.branch_office_address as agency_address,
            pm_agent.agent_first_name, 
            pm_agent.agent_last_name, 
            pm_agent.agent_occupation,
            pm_agent.agent_mobile,
            pm_agency.agency_id, 
            pm_agent.agent_website, 
            pm_agency.agency_name,
            pm_branch.branch_full_name as office_name,
            pm_agency.agency_pic,
            pm_location.lat,
            pm_location.long as _long,
            ROUND(6371 * ACOS(LEAST(COS(RADIANS(pm_location.lat)) * COS(RADIANS('.$lat .')) * COS(RADIANS(pm_location.long -  '. $lng .')) + SIN(RADIANS(pm_location.lat)) * SIN(RADIANS('. $lat .')),1.0)),1) as distance
        ');
        $this->db->from('pm_agent');
        $this->db->join('pm_branch', 'pm_agent.fk_branch_id = pm_branch.branch_id');
        $this->db->join('pm_agent_list', "pm_agent_list.fk_agent_id = pm_agent.agent_id and pm_agent_list.fk_customer_id = '$logged'", 'left outer');
        $this->db->join('pm_agency', 'pm_branch.fk_agency_id = pm_agency.agency_id');
        $this->db->join('pm_location', 'pm_branch.fk_location_id = pm_location.location_id');
          
        $this->db->where('pm_agent.delete_flag', 0);
        $this->db->where('pm_agent.agent_public', 0);
        $this->db->like('TRIM(pm_agent.agent_first_name)', $key_word,'none');
        $this->db->order_by('pm_agent.agent_id', 'DESC');

        $this->db->or_where('pm_agent.delete_flag', 0);
        $this->db->like('TRIM(pm_agent.agent_last_name)', $key_word,'none');
        $this->db->order_by('pm_agent.agent_id', 'DESC');

        $this->db->or_where('pm_agent.delete_flag', 0);
        $this->db->like('CONCAT(pm_agent.agent_first_name, " ",pm_agent.agent_last_name)', $key_word,'none');
        $this->db->order_by('pm_agent.agent_id', 'DESC');

        $this->db->or_where('pm_agent.delete_flag', 0);
        $this->db->like('pm_agent.agent_mobile', $key_word, 'both');
        $this->db->order_by('pm_agent.agent_id', 'DESC');

        $this->db->or_where('pm_agent.delete_flag', 0);
        $this->db->like('pm_agent.agent_phone', $key_word, 'both');
        $this->db->order_by('pm_agent.agent_id', 'DESC');

        $this->db->or_where('pm_agent.delete_flag', 0);
        $this->db->like('pm_agent.agent_office', $key_word, 'both');

           //Pagination
        //   if(isset($page)){
        //    $this->db->limit(10,$page); 
        //}else{
            $this->db->limit(50); 
        //}

        $this->db->order_by('pm_agent.agent_id', 'DESC');

        }

     
        
        //$query = $this->db->query("select pm_agent.* from pm_location , pm_agent left join `en_application` c using(jobid) where a.is_deleted=0 and a.`userid`=b.`userid` and a.`status` in (\"open\", \"processing\") and a.jobid<? and (((acos(sin((?*pi()/180)) * sin((a.`latitude`*pi()/180))+cos((?*pi()/180)) * cos((a.`latitude`*pi()/180)) * cos(((?-a.`longitude`)*pi()/180))))*180/pi())*60*1.1515*1.609344) < ? group by a.jobid order by publish_time desc limit ?;", array($last_index, $lat, $lat, $lng, $radius, self::item_per_page));
        $query = $this->db->get();
        $result = $query->result();
        
        //print_r($this->db->last_query());

        return $result;
    }

    //get languages

    function selectLanguageAgent($agent_id){
        $this->db->select("*,CONCAT('assets/img/flags/',lenguage_desc,'.png') as react_img");
        $this->db->from('pm_agent_language');
        $this->db->join('pm_language', 'pm_agent_language.fk_id_language = pm_language.id_flag');
        $this->db->where('fk_id_agent', $agent_id);
        $query = $this->db->get();
        return $query->result();
    }

    function selectTargetAgentList($agent_id,$logged,$lat,$lng) {

        if ($logged == 0){
            $this->db->select('
            pm_agent.agent_id, 0 as is_added,
            pm_agent.agent_pic,
            pm_agent.agent_pic_cover, 
            pm_agent.agent_first_name, 
            pm_branch.branch_full_name as office_name,
            pm_agent.agent_last_name, 
            pm_agent.agent_occupation,
            pm_agent.agent_mobile,
            pm_agency.agency_id, 
            pm_agency.agency_name,
            pm_agency.agency_pic,
            pm_branch.branch_office_address as agency_address,
            pm_agent.agent_website,
            pm_agent.agent_description,
            pm_agent.agent_email,
            pm_location.lat,
            pm_location.long,
            ROUND(6371 * ACOS(LEAST(COS(RADIANS(pm_location.lat)) * COS(RADIANS('.$lat .')) * COS(RADIANS(pm_location.long -  '. $lng .')) + SIN(RADIANS(pm_location.lat)) * SIN(RADIANS('. $lat .')),1.0)),1) as distance
            ');
            $this->db->from('pm_agent');
            $this->db->join('pm_branch', 'pm_agent.fk_branch_id = pm_branch.branch_id');
            $this->db->join('pm_agency', 'pm_agency.agency_id = pm_branch.fk_agency_id');
            $this->db->join('pm_location', 'pm_branch.fk_location_id = pm_location.location_id');
            $this->db->where('pm_agent.agent_id', $agent_id);
            $this->db->where('pm_agent.delete_flag', 0);
        }
        else{
            $this->db->select('
            pm_agent.agent_id,
            IF(pm_agent_list.fk_customer_id IS NULL,0,1) as is_added,
            pm_agent.agent_pic, 
            pm_agent.agent_pic_cover,
            pm_agent.agent_first_name, 
            pm_agent.agent_last_name, 
            pm_agent.agent_occupation,
            pm_branch.branch_full_name as office_name,
            pm_agent.agent_mobile,
            pm_branch.branch_office_address as agency_address,
            pm_agency.agency_id, 
            pm_agency.agency_name,
            pm_agency.agency_pic,
            pm_agent.agent_website,
            pm_agent.agent_description,
            pm_agent.agent_email,
            pm_location.lat,
            pm_location.long,
            ROUND(6371 * ACOS(LEAST(COS(RADIANS(pm_location.lat)) * COS(RADIANS('.$lat .')) * COS(RADIANS(pm_location.long -  '. $lng .')) + SIN(RADIANS(pm_location.lat)) * SIN(RADIANS('. $lat .')),1.0)),1) as distance
            ');
            $this->db->from('pm_agent');
            $this->db->join('pm_branch', 'pm_agent.fk_branch_id = pm_branch.branch_id');
            $this->db->join('pm_agent_list', "pm_agent_list.fk_agent_id = pm_agent.agent_id and pm_agent_list.fk_customer_id = '$logged'", 'left outer');
            $this->db->join('pm_agency', 'pm_agency.agency_id = pm_branch.fk_agency_id');
            $this->db->join('pm_location', 'pm_branch.fk_location_id = pm_location.location_id');
            $this->db->where('pm_agent.agent_id', $agent_id);
            $this->db->where('pm_agent.delete_flag', 0);
        }

        $query = $this->db->get();
        $result = $query->result_array();

        return $result;

    }

    // Show Add List
    function selectTargetAllAgentList($property_id) { // for Agent (UNION ALL and IN Query) 
        $this->db->select('
            pm_agent.agent_id, 
            pm_agent.agent_pic,
            pm_agent.agent_first_name, 
            pm_agent.agent_last_name, 
            pm_agent.agent_description, 
            pm_agent.agent_mobile, 
            pm_agent.agent_email, 
            pm_agent.agent_phone,
            pm_agent.fk_agent_title_id,
            pm_branch.branch_full_name as agency_office,
            pm_location.*, 
            pm_agency.*,
            pm_suburb.*
        ');
        $this->db->from('pm_agent');
        $this->db->join('pm_branch', 'pm_agent.fk_branch_id = pm_branch.branch_id');
        $this->db->join('pm_property', 'pm_property.fk_agent_id = pm_agent.agent_id');
        $this->db->join('pm_agency', 'pm_agency.agency_id = pm_branch.fk_agency_id');
        $this->db->join('pm_location', 'pm_branch.fk_location_id = pm_location.location_id');
        $this->db->join('pm_suburb', 'pm_location.fk_suburb_id = pm_suburb.suburb_id', 'left outer');
        $this->db->where('pm_property.property_id', $property_id);
        $this->db->where('pm_agent.delete_flag', 0);
        $query1 = $this->db->get_compiled_select();

        $this->db->select('
            pm_agent.agent_id, 
            pm_agent.agent_pic, 
            pm_agent.agent_first_name, 
            pm_agent.agent_last_name, 
            pm_agent.agent_description,
            pm_agent.fk_agent_title_id,
            pm_agent.agent_mobile, 
            pm_agent.agent_email, 
            pm_agent.agent_phone, 
            pm_branch.branch_full_name as agency_office,
            pm_location.*, 
            pm_agency.*,
            pm_suburb.*
        ');
        $this->db->from('pm_agent');
        $this->db->join('pm_branch', 'pm_agent.fk_branch_id = pm_branch.branch_id');
        $this->db->join('pm_assist', 'pm_assist.assist_agent_id = pm_agent.agent_id');
        $this->db->join('pm_agency', 'pm_agency.agency_id = pm_branch.fk_agency_id');
        $this->db->join('pm_location', 'pm_branch.fk_location_id = pm_location.location_id');
        $this->db->join('pm_suburb', 'pm_location.fk_suburb_id = pm_suburb.suburb_id', 'left outer');
        $this->db->where("pm_assist.assist_property_id", $property_id);
        $this->db->where('pm_agent.delete_flag', 0);
        $query2 = $this->db->get_compiled_select();

        $query = $this->db->query($query1 . ' UNION ALL ' . $query2);

        //print_r($this->db->last_query());

        $result = $query->result_array();

        return $result;
    }

    function selectTargetAllAgentListAssis($property_id) { // for Agent (UNION ALL and IN Query) 
        $this->db->select('
            pm_agent.agent_id, 
            pm_agent.agent_pic,
            pm_agent.agent_first_name, 
            pm_agent.agent_last_name, 
            pm_agent.agent_description, 
            pm_agent.agent_mobile, 
            pm_agent.agent_email, 
            pm_agent.agent_phone,
            pm_branch.branch_full_name as agency_office,
            pm_location.*, 
            pm_agency.*,
            pm_suburb.*
        ');
        $this->db->from('pm_agent');
        $this->db->join('pm_branch', 'pm_agent.fk_branch_id = pm_branch.branch_id');
        $this->db->join('pm_property', 'pm_property.fk_agent_assis_id = pm_agent.agent_id');
        $this->db->join('pm_agency', 'pm_agency.agency_id = pm_branch.fk_agency_id');
        $this->db->join('pm_location', 'pm_branch.fk_location_id = pm_location.location_id');
        $this->db->join('pm_suburb', 'pm_location.fk_suburb_id = pm_suburb.suburb_id', 'left outer');
        $this->db->where('pm_property.property_id', $property_id);
        $this->db->where('pm_agent.delete_flag', 0);
        $query1 = $this->db->get_compiled_select();

        $this->db->select('
            pm_agent.agent_id, 
            pm_agent.agent_pic, 
            pm_agent.agent_first_name, 
            pm_agent.agent_last_name, 
            pm_agent.agent_description,
            pm_agent.agent_mobile, 
            pm_agent.agent_email, 
            pm_agent.agent_phone, 
            pm_branch.branch_full_name as agency_office,
            pm_location.*, 
            pm_agency.*,
            pm_suburb.*
        ');
        $this->db->from('pm_agent');
        $this->db->join('pm_branch', 'pm_agent.fk_branch_id = pm_branch.branch_id');
        $this->db->join('pm_assist', 'pm_assist.assist_agent_id = pm_agent.agent_id');
        $this->db->join('pm_agency', 'pm_agency.agency_id = pm_branch.fk_agency_id');
        $this->db->join('pm_location', 'pm_branch.fk_location_id = pm_location.location_id');
        $this->db->join('pm_suburb', 'pm_location.fk_suburb_id = pm_suburb.suburb_id', 'left outer');
        $this->db->where("pm_assist.assist_property_id", $property_id);
        $this->db->where('pm_agent.delete_flag', 0);
        $query2 = $this->db->get_compiled_select();

        $query = $this->db->query($query1 . ' UNION ALL ' . $query2);
        $result = $query->result_array();

        return $result;
    }

    function selectTargetCustomerList($property_id) {
        $this->db->select('
            pm_customer.customer_id, pm_customer.customer_pic, pm_customer.customer_name, 
            pm_customer.customer_description, pm_customer.customer_mobile, pm_customer.customer_phone,
            pm_customer.customer_email, pm_customer.customer_type,pm_customer.customer_contact
        ');
        $this->db->from('pm_customer');
        $this->db->join('pm_property', 'pm_property.fk_customer_id = pm_customer.customer_id');
        $this->db->where('pm_property.property_id', $property_id);
        $this->db->where('pm_customer.delete_flag', 0);
//        $this->db->where('pm_customer.verify_flag', 1);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    function selectTargetPropertyList($agent_id) {
        $this->db->select('*');
        $this->db->from('pm_property');
        $this->db->join('pm_property_type', 'pm_property.fk_property_type_id = pm_property_type.property_type_id', 'left outer');
        $this->db->join('pm_location', 'pm_property.fk_location_id = pm_location.location_id');
        $this->db->where('pm_property.fk_agent_id', $agent_id);
        $this->db->where('pm_property.delete_flag', 0);
        $query = $this->db->get();

        return $query;
    }


    function selectTargetPropertyListAdmin($agent_id) {
        $this->db->select('*');
        $this->db->from('pm_property');
        $this->db->join('pm_property_type', 'pm_property.fk_property_type_id = pm_property_type.property_type_id', 'left outer');
        $this->db->join('pm_location', 'pm_property.fk_location_id = pm_location.location_id');
        $this->db->where('pm_property.property_id', $agent_id);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    function selectPropertiPicList($property_id) {
        $this->db->select('*');
        $this->db->from('pm_property_pic');
        $this->db->where('pm_property_pic.fk_property_id', $property_id);
        $this->db->order_by("property_first_pic", "asc");
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    function selectSearchAgentList($customer_id, $key_word) {
        $this->db->select('
                pm_agent.agent_id, 
                IF(pm_agent_list.fk_customer_id IS NULL,0,1) as is_added,
                pm_agent.agent_pic, 
                pm_agent.agent_first_name, 
                pm_agent.agent_last_name, 
                pm_agent.agent_occupation,
                pm_agent.agent_mobile,
                pm_agency.agency_id, 
                pm_branch.branch_full_name as office_name,
                pm_agency.agency_name,
                pm_agency.agency_pic,
                "0" as distance
        ');
        $this->db->from('pm_agent');
        $this->db->join('pm_branch', 'pm_agent.fk_branch_id = pm_branch.branch_id');
        $this->db->join('pm_location', 'pm_branch.fk_location_id = pm_location.location_id');
        $this->db->join('pm_agency', 'pm_agency.agency_id = pm_branch.fk_agency_id');
        $this->db->join('pm_agent_list', 'pm_agent_list.fk_agent_id = pm_agent.agent_id');
        $this->db->where('pm_agent.delete_flag', 0);
        $this->db->where('pm_agent_list.fk_customer_id', $customer_id);
        $this->db->like('pm_agent.agent_first_name', $key_word, 'both');
        $this->db->order_by('pm_agent.agent_first_name', 'DESC');

        $this->db->or_where('pm_agent.delete_flag', 0);
        $this->db->where('pm_agent_list.fk_customer_id', $customer_id);
        $this->db->like('pm_agent.agent_last_name', $key_word, 'both');
        $this->db->order_by('pm_agent.agent_first_name', 'DESC');

        $this->db->or_where('pm_agent.delete_flag', 0);
        $this->db->where('pm_agent_list.fk_customer_id', $customer_id);
        $this->db->like('pm_agent.agent_mobile', $key_word, 'both');
        $this->db->order_by('pm_agent.agent_first_name', 'DESC');

        $this->db->or_where('pm_agent.delete_flag', 0);
        $this->db->where('pm_agent_list.fk_customer_id', $customer_id);
        $this->db->like('pm_agent.agent_phone', $key_word, 'both');
        $this->db->order_by('pm_agent.agent_first_name', 'DESC');

        $this->db->or_where('pm_agent.delete_flag', 0);
        $this->db->where('pm_agent_list.fk_customer_id', $customer_id);
        $this->db->like('pm_agency.agency_name', $key_word, 'both');
        $this->db->order_by('pm_agent.agent_first_name', 'DESC');


        $this->db->or_where('pm_agent.delete_flag', 0);
        $this->db->where('pm_agent_list.fk_customer_id', $customer_id);
        $this->db->like('pm_agent.agent_office', $key_word, 'both');
        $this->db->order_by('pm_agent.agent_first_name', 'DESC');

        $query = $this->db->get();
        $result = $query->result_array();

        return $result;
    }

    function chkAgentList($customer_id, $agent_id) {
        $this->db->select('*');
        $this->db->from('pm_agent_list');
        $this->db->where('fk_customer_id', $customer_id);
        $this->db->where('fk_agent_id', $agent_id);
        $query = $this->db->get();
        $result = $query->result_array();

        if (array_key_exists('0', $result)) {
            $chk_agnet = 1;
        } else {
            $chk_agnet = 0;
        }

        return $chk_agnet;
    }

    function cntMaxList($customer_id, $property_id) {
        $this->db->select('*');
        $this->db->from('pm_max_list');
        $this->db->where('fk_customer_id', $customer_id);
        $this->db->where('fk_property_id', $property_id);
        $result = $this->db->count_all_results();
        return $result;
    }

    function switchMaxList($customer_id, $property_id) {
        $this->db->select('*');
        $this->db->from('pm_max_list');
        $this->db->where('fk_customer_id', $customer_id);
        $this->db->where('fk_property_id', $property_id);
        $query = $this->db->get();
        $result = $query->result_array();

        if (array_key_exists('0', $result)) {
            $chk_max = $this->deleteMaxList($customer_id, $property_id);
            if ($chk_max == 1)
                return 'delete'; // Delete
        } else {
            $chk_max = $this->insertMaxList($customer_id, $property_id);
            if ($chk_max == 1)
                return 'add'; // Insert
        }
        return false;
    }

    function getLocationID($search_id) {
        $this->db->select('fk_location_id as location_id');
        $this->db->from('pm_search');
        $this->db->where('search_id', $search_id);
        $query = $this->db->get();
        $result = $query->result_array();

        if (array_key_exists('0', $result)) {
            $location_id = $result[0]['location_id'];
        } else {
            $location_id = 0;
        }
        return $location_id;
    }


    function selectTargetProperty($property_id,$proexp=0) {
        $this->db->select('*');
        $this->db->from('pm_property');
        $this->db->join('pm_property_type', 'pm_property.fk_property_type_id = pm_property_type.property_type_id', 'left outer');
        $this->db->join('pm_location', 'pm_property.fk_location_id = pm_location.location_id');
        $this->db->where('pm_property.property_id', $property_id);
        if($proexp!=="1"){
            $this->db->where('pm_property.delete_flag', 0);
        }
        
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    function selectOpenHomeList($property_id) {
        $this->db->select('*');
        $this->db->from('pm_open_home');
        $this->db->where('fk_property_id', $property_id);
        $query = $this->db->get();
        $result = $query->result_array();
        
        return $result;
    }

    function getcustomer_agents($cusid, $propert) {
        $query = $this->db->get_where('customer_agent', array('customer_id' => $cusid, 'property_id' => $propert));
        return $query->result();
    }


    function selectSearchPropertyList($search, $property_type, $subrubs,$page) { // for Customer
        
        $this->db->select('*');
        $this->db->from('pm_property');
        $this->db->join('pm_property_type', 'pm_property.fk_property_type_id = pm_property_type.property_type_id', 'left outer');
        $this->db->join('pm_location', 'pm_property.fk_location_id = pm_location.location_id');
        $this->db->join('pm_agent', 'pm_property.fk_agent_id = pm_agent.agent_id');
        $this->db->join('pm_branch', 'pm_agent.fk_branch_id = pm_branch.branch_id');
        $this->db->join('pm_agency', 'pm_branch.fk_agency_id = pm_agency.agency_id');
        if ($search['search_sale_flag'] == 0 && $search['search_open_now'] == 1) {  // Sale - 0: All, 1: Open Now
            $this->db->join('pm_open_home', 'pm_property.property_id = pm_open_home.fk_property_id');
            $this->db->where('DATE(pm_open_home.open_home_from) >= CURRENT_DATE()');
        }

        //$this->db->where('pm_property.property_sale_flag >=', $search['search_sale_flag']); // 0: Sale, 1: Rent
        $this->db->where('pm_property.property_sale_flag', $search['search_sale_flag']);
        $this->db->where('pm_property.property_bedroom >=', $search['search_bedroom_from']);
        $this->db->where('pm_property.property_bedroom <=', $search['search_bedroom_to']);
        $this->db->where('pm_property.property_bathroom >=', $search['search_bathroom_from']);
        $this->db->where('pm_property.property_bathroom <=', $search['search_bathroom_to']);

        if ($search['search_name'] != "" & $search['savesearch'] != "1") {
            $this->db->where('pm_property.property_id ', $search['search_name']);
        }

        if ($search['search_sale_flag'] === "1") {
            $this->db->where('CAST(pm_property.property_show_price AS UNSIGNED) >=', $search['search_price_from']);
            if (!($search['search_price_to'] >= 2000)) { // Rental Max Value is 2000 Over
                $this->db->where('CAST(pm_property.property_show_price AS UNSIGNED) <=', $search['search_price_to']);
            }
        } else {
            $this->db->where('pm_property.property_hidden_price >=', $search['search_price_from']);
            $this->db->where('pm_property.property_hidden_price <=', $search['search_price_to']);
        }



        if ($search['search_sale_flag'] == 1) { // 1: Rent
            if ($search['search_pet'] == "1") {
                $this->db->where('pm_property.property_pet', $search['search_pet']);
            }


            if ($search['search_available'] == 1) { // 1: Available Now
                $this->db->where('pm_property.property_available_date < CURRENT_TIMESTAMP()');
            }
        }

//        if ($search['fk_suburb_id'] != 0)
//            $this->db->where('pm_location.fk_suburb_id', $search['fk_suburb_id']);

        if ($search['fk_city_id'] != 0)
            $this->db->where('pm_location.fk_city_id', $search['fk_city_id']);

        if ($search['fk_region_id'] != 0)
            $this->db->where('pm_location.fk_region_id', $search['fk_region_id']);

        if (sizeof($property_type) > 0)
            $this->db->where_in('pm_property.fk_property_type_id', $property_type);

        if (sizeof($subrubs) > 0) {
            if (!in_array("0", $subrubs)) {
                $this->db->where_in('pm_location.fk_suburb_id', $subrubs);
            }
        }

        $this->db->where('pm_property.property_disable', 0);
        $this->db->where('pm_property.delete_flag', 0);

        if ($search['search_sale_flag'] == 0 && $search['search_open_now'] == 1) {
            $this->db->group_by('pm_open_home.fk_property_id');
        }

        switch ($search['sorting']) {
            case "1":
                if($search['search_sale_flag']==1){
                    $this->db->order_by("CAST(property_show_price AS UNSIGNED)", "asc");
                }else{
                    $this->db->order_by("property_hidden_price", "asc");
                }
                break;
            case "2":
                if($search['search_sale_flag']==1){
                    $this->db->order_by("CAST(property_show_price AS UNSIGNED)", "desc");
                }else{
                    $this->db->order_by("property_hidden_price", "desc");
                }
                break;
            case "3":
                $this->db->order_by("property_indate", "desc");
                break;

            default:
                $this->db->order_by("property_title", "desc");
                break;
        }

        //Pagination
        if(isset($page)){
            $this->db->limit(10,$page); 
        }else{
            $this->db->limit(50); 
        }
        
        $query = $this->db->get();
           
          //  exit();

        
        return $query;
    }

    function selectSearchPropertyListAjax($search, $property_type, $subrubs,$page) { // for Customer
        
        $limit = (($page * 24)-24);


        $this->db->select('*');
        $this->db->from('pm_property');
        $this->db->join('pm_property_type', 'pm_property.fk_property_type_id = pm_property_type.property_type_id', 'left outer');
        $this->db->join('pm_location', 'pm_property.fk_location_id = pm_location.location_id');
        if ($search['search_sale_flag'] == 0 && $search['search_open_now'] == 1) {  // Sale - 0: All, 1: Open Now
            $this->db->join('pm_open_home', 'pm_property.property_id = pm_open_home.fk_property_id');
            $this->db->where('DATE(pm_open_home.open_home_from) >= CURRENT_DATE()');
        }

        //$this->db->where('pm_property.property_sale_flag >=', $search['search_sale_flag']); // 0: Sale, 1: Rent
        $this->db->where('pm_property.property_sale_flag', $search['search_sale_flag']);
        $this->db->where('pm_property.property_bedroom >=', $search['search_bedroom_from']);
        $this->db->where('pm_property.property_bedroom <=', $search['search_bedroom_to']);
        $this->db->where('pm_property.property_bathroom >=', $search['search_bathroom_from']);
        $this->db->where('pm_property.property_bathroom <=', $search['search_bathroom_to']);

        if ($search['search_name'] != "" & $search['savesearch'] != "1") {
            $this->db->where('pm_property.property_id ', $search['search_name']);
        }

        if ($search['search_sale_flag'] === "1") {
            $this->db->where('CAST(pm_property.property_show_price AS UNSIGNED) >=', $search['search_price_from']);
            if (!($search['search_price_to'] >= 2000)) { // Rental Max Value is 2000 Over
                $this->db->where('CAST(pm_property.property_show_price AS UNSIGNED) <=', $search['search_price_to']);
            }
        } else {
            $this->db->where('pm_property.property_hidden_price >=', $search['search_price_from']);
            $this->db->where('pm_property.property_hidden_price <=', $search['search_price_to']);
        }



        if ($search['search_sale_flag'] == 1) { // 1: Rent
            if ($search['search_pet'] == "1") {
                $this->db->where('pm_property.property_pet', $search['search_pet']);
            }


            if ($search['search_available'] == 1) { // 1: Available Now
                $this->db->where('pm_property.property_available_date < CURRENT_TIMESTAMP()');
            }
        }

//        if ($search['fk_suburb_id'] != 0)
//            $this->db->where('pm_location.fk_suburb_id', $search['fk_suburb_id']);

        if ($search['fk_city_id'] != 0)
            $this->db->where('pm_location.fk_city_id', $search['fk_city_id']);

        if ($search['fk_region_id'] != 0)
            $this->db->where('pm_location.fk_region_id', $search['fk_region_id']);

        if (sizeof($property_type) > 0)
            $this->db->where_in('pm_property.fk_property_type_id', $property_type);

        if (sizeof($subrubs) > 0) {
            if (!in_array("0", $subrubs)) {
                $this->db->where_in('pm_location.fk_suburb_id', $subrubs);
            }
        }

        $this->db->where('pm_property.property_disable', 0);
        $this->db->where('pm_property.delete_flag', 0);

        if ($search['search_sale_flag'] == 0 && $search['search_open_now'] == 1) {
            $this->db->group_by('pm_open_home.fk_property_id');
        }

        switch ($search['sorting']) {
            case "1":
                if($search['search_sale_flag']==1){
                    $this->db->order_by("CAST(property_show_price AS UNSIGNED)", "asc");
                }else{
                    $this->db->order_by("property_hidden_price", "asc");
                }
                break;
            case "2":
                if($search['search_sale_flag']==1){
                    $this->db->order_by("CAST(property_show_price AS UNSIGNED)", "desc");
                }else{
                    $this->db->order_by("property_hidden_price", "desc");
                }
                break;
            case "3":
                $this->db->order_by("property_indate", "desc");
                break;

            default:
                $this->db->order_by("property_title", "desc");
                break;
        }

        $this->db->limit(24,$limit);


        $query = $this->db->get();
        
          //  echo $this->db->last_query();
          //  exit();
        return $query;
    }

    function selectSearchPropertyListAjaxRecent($search, $property_type, $subrubs) { // for Customer
        
        $this->db->select('*');
        $this->db->from('pm_property');
        $this->db->join('pm_property_type', 'pm_property.fk_property_type_id = pm_property_type.property_type_id', 'left outer');
        $this->db->join('pm_agent', 'pm_property.fk_agent_id = pm_agent.agent_id');
        $this->db->join('pm_branch', 'pm_agent.fk_branch_id = pm_branch.branch_id');
        $this->db->join('pm_agency', 'pm_branch.fk_agency_id = pm_agency.agency_id');
        $this->db->join('pm_location', 'pm_property.fk_location_id = pm_location.location_id');
        if ($search['search_sale_flag'] == 0 && $search['search_open_now'] == 1) {  // Sale - 0: All, 1: Open Now
            $this->db->join('pm_open_home', 'pm_property.property_id = pm_open_home.fk_property_id');
            $this->db->where('DATE(pm_open_home.open_home_from) >= CURRENT_DATE()');
        }

        //$this->db->where('pm_property.property_sale_flag >=', $search['search_sale_flag']); // 0: Sale, 1: Rent
        $this->db->where('pm_property.property_sale_flag', $search['search_sale_flag']);
        $this->db->where('pm_property.property_bedroom >=', $search['search_bedroom_from']);
        $this->db->where('pm_property.property_bedroom <=', $search['search_bedroom_to']);
        $this->db->where('pm_property.property_bathroom >=', $search['search_bathroom_from']);
        $this->db->where('pm_property.property_bathroom <=', $search['search_bathroom_to']);

        if ($search['search_name'] != "" & $search['savesearch'] != "1") {
            $this->db->where('pm_property.property_id ', $search['search_name']);
        }

        if ($search['search_sale_flag'] === "1") {
            $this->db->where('CAST(pm_property.property_show_price AS UNSIGNED) >=', $search['search_price_from']);
            if (!($search['search_price_to'] >= 2000)) { // Rental Max Value is 2000 Over
                $this->db->where('CAST(pm_property.property_show_price AS UNSIGNED) <=', $search['search_price_to']);
            }
        } else {
            $this->db->where('pm_property.property_hidden_price >=', $search['search_price_from']);
            $this->db->where('pm_property.property_hidden_price <=', $search['search_price_to']);
        }



        if ($search['search_sale_flag'] == 1) { // 1: Rent
            if ($search['search_pet'] == "1") {
                $this->db->where('pm_property.property_pet', $search['search_pet']);
            }


            if ($search['search_available'] == 1) { // 1: Available Now
                $this->db->where('pm_property.property_available_date < CURRENT_TIMESTAMP()');
            }
        }

//        if ($search['fk_suburb_id'] != 0)
//            $this->db->where('pm_location.fk_suburb_id', $search['fk_suburb_id']);

        if ($search['fk_city_id'] != 0)
            $this->db->where('pm_location.fk_city_id', $search['fk_city_id']);

        if ($search['fk_region_id'] != 0)
            $this->db->where('pm_location.fk_region_id', $search['fk_region_id']);

        if (sizeof($property_type) > 0)
            $this->db->where_in('pm_property.fk_property_type_id', $property_type);

        if (sizeof($subrubs) > 0) {
            if (!in_array("0", $subrubs)) {
                $this->db->where_in('pm_location.fk_suburb_id', $subrubs);
            }
        }

        $this->db->where('pm_property.property_disable', 0);
        $this->db->where('pm_property.delete_flag', 0);

        if ($search['search_sale_flag'] == 0 && $search['search_open_now'] == 1) {
            $this->db->group_by('pm_open_home.fk_property_id');
        }

        switch ($search['sorting']) {
            case "1":
                if($search['search_sale_flag']==1){
                    $this->db->order_by("CAST(property_show_price AS UNSIGNED)", "asc");
                }else{
                    $this->db->order_by("property_hidden_price", "asc");
                }
                break;
            case "2":
                if($search['search_sale_flag']==1){
                    $this->db->order_by("CAST(property_show_price AS UNSIGNED)", "desc");
                }else{
                    $this->db->order_by("property_hidden_price", "desc");
                }
                break;
            case "3":
                $this->db->order_by("property_indate", "desc");
                break;

            default:
                $this->db->order_by("property_title", "desc");
                break;
        }

        $this->db->limit(10,0);


        $query = $this->db->get();
        
          //  echo $this->db->last_query();
          //  exit();
        return $query;
    }



    function selectCount($search, $property_type, $subrubs) { // for Customer
        
        
        $this->db->select('count(*) as total');
        $this->db->from('pm_property');
        $this->db->join('pm_property_type', 'pm_property.fk_property_type_id = pm_property_type.property_type_id', 'left outer');
        $this->db->join('pm_location', 'pm_property.fk_location_id = pm_location.location_id');
        if ($search['search_sale_flag'] == 0 && $search['search_open_now'] == 1) {  // Sale - 0: All, 1: Open Now
            $this->db->join('pm_open_home', 'pm_property.property_id = pm_open_home.fk_property_id');
            $this->db->where('DATE(pm_open_home.open_home_from) >= CURRENT_DATE()');
        }

        //$this->db->where('pm_property.property_sale_flag >=', $search['search_sale_flag']); // 0: Sale, 1: Rent
        $this->db->where('pm_property.property_sale_flag', $search['search_sale_flag']);
        $this->db->where('pm_property.property_bedroom >=', $search['search_bedroom_from']);
        $this->db->where('pm_property.property_bedroom <=', $search['search_bedroom_to']);
        $this->db->where('pm_property.property_bathroom >=', $search['search_bathroom_from']);
        $this->db->where('pm_property.property_bathroom <=', $search['search_bathroom_to']);

        if ($search['search_name'] != "" & $search['savesearch'] != "1") {
            $this->db->where('pm_property.property_id ', $search['search_name']);
        }

        if ($search['search_sale_flag'] === "1") {
            $this->db->where('CAST(pm_property.property_show_price AS UNSIGNED) >=', $search['search_price_from']);
            if (!($search['search_price_to'] >= 2000)) { // Rental Max Value is 2000 Over
                $this->db->where('CAST(pm_property.property_show_price AS UNSIGNED) <=', $search['search_price_to']);
            }
        } else {
            $this->db->where('pm_property.property_hidden_price >=', $search['search_price_from']);
            $this->db->where('pm_property.property_hidden_price <=', $search['search_price_to']);
        }



        if ($search['search_sale_flag'] == 1) { // 1: Rent
            if ($search['search_pet'] == "1") {
                $this->db->where('pm_property.property_pet', $search['search_pet']);
            }


            if ($search['search_available'] == 1) { // 1: Available Now
                $this->db->where('pm_property.property_available_date < CURRENT_TIMESTAMP()');
            }
        }

//        if ($search['fk_suburb_id'] != 0)
//            $this->db->where('pm_location.fk_suburb_id', $search['fk_suburb_id']);

        if ($search['fk_city_id'] != 0)
            $this->db->where('pm_location.fk_city_id', $search['fk_city_id']);

        if ($search['fk_region_id'] != 0)
            $this->db->where('pm_location.fk_region_id', $search['fk_region_id']);

        if (sizeof($property_type) > 0)
            $this->db->where_in('pm_property.fk_property_type_id', $property_type);

        if (sizeof($subrubs) > 0) {
            if (!in_array("0", $subrubs)) {
                $this->db->where_in('pm_location.fk_suburb_id', $subrubs);
            }
        }

        $this->db->where('pm_property.property_disable', 0);
        $this->db->where('pm_property.delete_flag', 0);

        if ($search['search_sale_flag'] == 0 && $search['search_open_now'] == 1) {
            $this->db->group_by('pm_open_home.fk_property_id');
        }

        switch ($search['sorting']) {
            case "1":
                if($search['search_sale_flag']==1){
                    $this->db->order_by("CAST(property_show_price AS UNSIGNED)", "asc");
                }else{
                    $this->db->order_by("property_hidden_price", "asc");
                }
                break;
            case "2":
                if($search['search_sale_flag']==1){
                    $this->db->order_by("CAST(property_show_price AS UNSIGNED)", "desc");
                }else{
                    $this->db->order_by("property_hidden_price", "desc");
                }
                break;
            case "3":
                $this->db->order_by("property_indate", "desc");
                break;

            default:
                $this->db->order_by("property_title", "desc");
                break;
        }

        $query = $this->db->get();
          //  echo $this->db->last_query();
          //  exit();
        $arr   = $query->row_array(); 
        $total = $arr['total']; 
        return $total;
    }




    function checkCustomerAndAgentExsist($agentid, $cusid) {
        $query1 = $this->db->get_where('pm_agent', array('agent_id' => $agentid));
        $query2 = $this->db->get_where('pm_customer', array('customer_id' => $cusid));

        if ($query1->result() & $query2->result()) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function getRecentListings() {
        $this->db->select('*');
        $this->db->from('pm_property_pic');
        $this->db->join('pm_property', 'pm_property_pic.fk_property_id = pm_property.property_id');
        $this->db->join('pm_agent', 'pm_property.fk_agent_id = pm_agent.agent_id');
        $this->db->join('pm_branch', 'pm_agent.fk_branch_id = pm_branch.branch_id');
        $this->db->join('pm_agency', 'pm_branch.fk_agency_id = pm_agency.agency_id');
        $this->db->join('pm_location', 'pm_property.fk_location_id = pm_location.location_id');
        $this->db->join('pm_suburb', 'pm_location.fk_suburb_id = pm_suburb.suburb_id');
        $this->db->where('pm_property_pic.property_first_pic', 1);
        $this->db->where('pm_property.property_disable', 0);
        $this->db->where('pm_property.delete_flag', 0);
        $this->db->order_by("pm_property.property_id", "desc");
        $this->db->limit(10);
        $query = $this->db->get();
        return $query->result();
    }

    public function getpersonalListings($customerid) {
        $this->db->select('*');
        $this->db->from('pm_property_pic');
        $this->db->join('pm_property', 'pm_property_pic.fk_property_id = pm_property.property_id');
        $this->db->where('pm_property_pic.property_first_pic', 1);
        $this->db->where('pm_property.property_disable', 0);
        $this->db->where('pm_property.delete_flag', 0);
        $this->db->where('pm_property.fk_customer_id', $customerid);
        $this->db->order_by("pm_property.property_id", "desc");
        $query = $this->db->get();
        return $query->result();
    }

    public function getSubNews($customerid,$page) {
        $this->db->select('
            pm_agency.agency_name,
            (select count(pm_max_list.fk_property_id) 
            from pm_max_list where pm_max_list.fk_property_id = pm_property.property_id and pm_max_list.fk_customer_id = "'.$customerid.'") as max_flag,
            (select count(pm_thumbs.fk_customer_id) from pm_thumbs where pm_thumbs.fk_property_id = pm_property.property_id and pm_thumbs.fk_customer_id = pm_agent_list.fk_customer_id) as check_flag,
            (select count(pm_thumbs.fk_property_id) from pm_thumbs where pm_thumbs.fk_property_id = pm_property.property_id) as count_thumbs,
            pm_agency.agency_pic,
            pm_agent.agent_first_name,
            pm_agent.agent_last_name,
            pm_agent.agent_pic,
            pm_agent.agent_occupation as pm_agent_title,
            pm_location.*,
            pm_suburb.*,
            pm_property.*
        ');
        $this->db->from('pm_agent_list'); //
        $this->db->join('pm_property', 'pm_agent_list.fk_agent_id = pm_property.fk_agent_id', 'left outer');
        $this->db->join('pm_location', 'pm_property.fk_location_id = pm_location.location_id');
        $this->db->join('pm_suburb', 'pm_location.fk_suburb_id = pm_suburb.suburb_id');
        $this->db->join('pm_agent', 'pm_agent_list.fk_agent_id = pm_agent.agent_id');
        $this->db->join('pm_branch', 'pm_agent.fk_branch_id = pm_branch.branch_id');
        $this->db->join('pm_agency', 'pm_branch.fk_agency_id = pm_agency.agency_id');

        //$this->db->join('pm_agent_title', 'pm_agent.fk_agent_title_id = pm_agent_title.pm_agent_title_id');
        $this->db->where('pm_property.property_disable', 0);
        $this->db->where('pm_property.delete_flag', 0);
        $this->db->where('pm_agent_list.fk_customer_id', $customerid);

               //Pagination
               if(isset($page)){
                $this->db->limit(10,$page); 
            }else{
                $this->db->limit(50); 
            }

        $this->db->order_by("pm_property.property_indate", "desc");
        $query = $this->db->get();
        return $query;
    }

    function selectQuickSummaryConfig() {
        $this->db->select('*');
        $this->db->from('pm_quick_summary');
        // $this->db->join('pm_city', 'pm_quick_summary.fk_city_id = pm_city.city_id', 'left outer');
        $this->db->order_by("pm_quick_summary.quick_summary_id", "asc");
        $query = $this->db->get();
        return $query;
    }

    function selectAllQuickSummaryPropertyList($city_id, $region_id, $price_min, $price_max, $property_type_id) {
        $this->db->select('
            pm_property.property_id
        ');
        $this->db->from('pm_property');
        $this->db->join('pm_location', 'pm_property.fk_location_id = pm_location.location_id');
        $this->db->join('pm_agent', 'pm_property.fk_agent_id = pm_agent.agent_id');
        $this->db->join('pm_agency', 'pm_branch.fk_agency_id = pm_agency.agency_id');
        $this->db->where('pm_property.property_disable', 0);
        $this->db->where('pm_property.delete_flag', 0);
        $this->db->where('pm_property.fk_property_type_id', $property_type_id);

        if (!empty($city_id)) {
            $this->db->where('pm_location.fk_city_id', $city_id);
        }

        if (!empty($region_id)) {
            $this->db->where('pm_location.fk_region_id', $region_id);
        }

        if (!empty($price_min)) {
            $this->db->where('pm_property.property_hidden_price >=', $price_min);
        }

        if (!empty($price_max)) {
            $this->db->where('pm_property.property_hidden_price <', $price_max);
        }
        
        $query = $this->db->get();
        return $query;
    }

    function selectQuickSummaryPropertyList($city_id, $region_id, $price_min, $price_max, $property_type_id, $start, $page_unit) {
        $this->db->select('
            pm_property.*,
            pm_agency.agency_pic,
            (select pm_property_pic.property_pic from pm_property_pic where pm_property_pic.fk_property_id = pm_property.property_id LIMIT 1) as property_pic,
            pm_suburb.*,
            pm_location.*
        ');
        $this->db->from('pm_property');
        $this->db->join('pm_location', 'pm_property.fk_location_id = pm_location.location_id');
        $this->db->join('pm_suburb', 'pm_location.fk_suburb_id = pm_suburb.suburb_id');
        $this->db->join('pm_agent', 'pm_property.fk_agent_id = pm_agent.agent_id');
        $this->db->join('pm_agency', 'pm_branch.fk_agency_id = pm_agency.agency_id');
        $this->db->where('pm_property.property_disable', 0);
        $this->db->where('pm_property.delete_flag', 0);
        $this->db->where('pm_property.fk_property_type_id', $property_type_id);

        if (!empty($city_id)) {
            $this->db->where('pm_location.fk_city_id', $city_id);
        }

        if (!empty($region_id)) {
            $this->db->where('pm_location.fk_region_id', $region_id);
        }

        if (!empty($price_min)) {
            $this->db->where('pm_property.property_hidden_price >=', $price_min);
        }

        if (!empty($price_max)) {
            $this->db->where('pm_property.property_hidden_price <', $price_max);
        }
        
        $this->db->limit($page_unit, $start);
        $this->db->order_by("pm_property.property_indate", "desc");
        $query = $this->db->get();
        return $query;
    }

    function selectAllPropertiPicList($property_id) {
        $this->db->select('*');
        $this->db->from('pm_property_pic');
        $this->db->where('pm_property_pic.fk_property_id', $property_id);
        $this->db->order_by("property_first_pic", "asc");
        $query = $this->db->get();
        return $query->result();
    }

    function getThumbsUp($customer_id, $property_id) {
        $this->db->select('
            count(pm_thumbs.fk_customer_id) as chk_flag
        ');
        $this->db->from('pm_thumbs');
        $this->db->where('pm_thumbs.fk_customer_id', $customer_id);
        $this->db->where('pm_thumbs.fk_property_id', $property_id);
        $query = $this->db->get();
        return $query->row(0);
    }

    function getPropertyThumbsUp($property_id) {
        $this->db->select('
            count(pm_thumbs.fk_customer_id) as cnt_thumbs
        ');
        $this->db->from('pm_thumbs');
        $this->db->where('pm_thumbs.fk_property_id', $property_id);
        $query = $this->db->get();
        return $query->row(0);
    }


    /**
     * CRUD - Insert
     */
    function insertCustomer($data) {
        return $this->db->insert('pm_customer', $data);
    }

    function insertCustomerId($data) {
        $this->db->insert('pm_customer', $data);
        return $this->db->insert_id();
    }

    function insertSearch($data) {
        unset($data["savesearch"]);
        $this->db->insert('pm_search', $data);
        return $this->db->insert_id();
    }

    function insertSearchList($data) {
        return $this->db->insert('pm_search_list', $data);
    }

    function insertPropertyType($data) {
        return $this->db->insert('pm_property_type_list', $data);
    }

    function insertSuburbsinsave($data) {
        return $this->db->insert('pm_subrub_save_list', $data);
    }

    function insertAgentList($data) {
        return $this->db->insert('pm_agent_list', $data);
    }

    function insertMaxList($customer_id, $property_id) {
        $data = array(
            'fk_customer_id' => $customer_id,
            'fk_property_id' => $property_id
        );
        return $this->db->insert('pm_max_list', $data);
    }

    function insertThumbsUp($customer_id, $property_id) {
        $data = array(
            'fk_customer_id' => $customer_id,
            'fk_property_id' => $property_id
        );
        return $this->db->insert('pm_thumbs', $data);
    }

    /**
     * CRUD - Update
     */
    // function updateSearch($email) {
    //     $data = array(
    //         'verify_chk' => "1"
    //     );
    //     $this->db->where('email', $email);
    //     $this->db->update('ws_all_user', $data);
    // }

    function updateSaveSearch($idSearch, $data) {
        $this->db->where('search_id', $idSearch);
        return $this->db->update('pm_search', $data);
    }

    function updateCustomer($data, $customer_id) {
        $this->db->where('customer_id', $customer_id);
        return $this->db->update('pm_customer', $data);
    }

    function updateSearch($data, $search_id) {
        $this->db->where('search_id', $search_id);
        return $this->db->update('pm_search', $data);
    }

    function updateCustomerToken($data, $customer_id) {
        $this->db->where('customer_id', $customer_id);
        return $this->db->update('pm_customer', $data);
    }

    /**
     * CRUD - Delete
     */
    // function deleteAgent($agent_id) { ///////////////////////////////////////////////////
    //     $data = array();
    //     $data['delete_flag'] = 1;
    //     $this->db->where('agent_id', $agent_id);
    //     return $this->db->update('pm_agent', $data);
    // }

    function deleteSearch($search_id) {
        $this->db->where('search_id', $search_id);
        return $this->db->delete('pm_search');
    }

    function deleteSearchList($search_id) {
        $this->db->where('fk_search_id', $search_id);
        return $this->db->delete('pm_search_list');
    }

    function deletePropertyType($search_id) {
        $this->db->where('fk_search_id', $search_id);
        return $this->db->delete('pm_property_type_list');
    }

    function deleteAgentList($customer_id, $agent_id) {
        $this->db->where('fk_customer_id', $customer_id);
        $this->db->where('fk_agent_id', $agent_id);
        return $this->db->delete('pm_agent_list');
    }

    function deleteMaxList($customer_id, $property_id) {
        $this->db->where('fk_customer_id', $customer_id);
        $this->db->where('fk_property_id', $property_id);
        return $this->db->delete('pm_max_list');
    }

    function deleteThumbsUp($customer_id, $property_id) {
        $this->db->where('fk_customer_id', $customer_id);
        $this->db->where('fk_property_id', $property_id);
        return $this->db->delete('pm_thumbs');
    }


    function getNoticeLogList($customer_id,$page) {

        $this->db->select('*');
        $this->db->from('pm_notice_log');
        //$this->db->where('fk_customer_id', $customerId);
        $this->db->where('fk_customer_id', $customer_id);
        $this->db->where('notice_sent_date >= ', date('Y/m/d', strtotime('-2 week')));
        $this->db->where_in('notice_type', explode(",","1,2,5,3"));
        $this->db->where_in('seen', explode(",","0,1"));
        //$this->db->limit(10);
        $this->db->order_by("notice_sent_date", "desc");

            //Pagination
            if(isset($page)){
                $this->db->limit(8,$page); 
            }else{
                //$this->db->limit(50); 
            }

        $query = $this->db->get();
        $result = $query->result_array();

        //print_r( $this->db->last_query());
        return $result;
    }

    function getNoticePropertyLogList($noticeId) {
        $this->db->select('pm_agency.*,pm_property.*,pm_location.*,pm_property_type.*, pm_notice_property_log.*, pm_notice_property_log.*,pm_agent.* ');
        $this->db->from('pm_notice_property_log');
        $this->db->join('pm_property', 'pm_property.property_id = pm_notice_property_log.fk_property_id', 'left outer');
        $this->db->join('pm_location', 'pm_location.location_id = pm_property.fk_location_id', 'left outer');
        $this->db->join('pm_agent', 'pm_agent.agent_id = pm_property.fk_agent_id', 'left outer');
        $this->db->join('pm_branch', 'pm_branch.branch_id = pm_agent.fk_branch_id', 'left outer');
        $this->db->join('pm_agency', 'pm_branch.fk_agency_id = pm_agency.agency_id', 'left outer');
        $this->db->join('pm_property_type', 'pm_property.fk_property_type_id = pm_property_type.property_type_id', 'left outer');
        $this->db->where('pm_notice_property_log.fk_notice_id', $noticeId);
        $query = $this->db->get();
        $result = $query->result_array();
        return $query;
    }

    function updateNotification($value, $notice_id) {

        $this->db->set('seen', $value); //value that used to update column  
        $this->db->where('id', $notice_id); //which row want to upgrade  
        return $this->db->update('pm_notice_log');
    }

    function updateAllNotification($customer_id) {

        $this->db->set('seen', '2'); //value that used to update column  
        $this->db->where('fk_customer_id', $customer_id); //which row want to upgrade  
        return $this->db->update('pm_notice_log');
    }

    function getNewsNotices($customer_id) {

        $this->db->select('count(*) as total'); 
        $this->db->from('pm_notice_log'); 
        $this->db->join('pm_notice_property_log', 'pm_notice_log.id = pm_notice_property_log.fk_notice_id');
        $this->db->where('fk_customer_id', $customer_id); //which row want to upgrade  
        $this->db->where('notice_sent_date >= ', date('Y/m/d', strtotime('-2 week')));
        $this->db->where('seen', "0"); //which row want to upgrade  
        $query = $this->db->get(); 
        $result = $query->result_array()[0];
        return $result;
    }

    function countNewsNotices($customer_id) {

        $this->db->select('*'); 
        $this->db->from('pm_notice_log'); 
        $this->db->join('pm_notice_property_log', 'pm_notice_log.id = pm_notice_property_log.fk_notice_id');
        $this->db->where('fk_customer_id', $customer_id); //which row want to upgrade  
        $this->db->where('notice_sent_date >= ', date('Y/m/d', strtotime('-2 week')));
        $this->db->where('seen', "0"); //which row want to upgrade  
        $result = $this->db->count_all_results();
        return $result;
    }

}
