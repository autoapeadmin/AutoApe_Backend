<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * PropertiMax
 * @author Edward An
 */
class Common_Model extends CI_Model {

    function __construct() {
        //parent::__construct();
        $this->load->database();
    }

    /**
     * CRUD - Select
     */
    function selectAgencyList() {
        $this->db->select('*');
        $this->db->from('pm_agency');
        $this->db->where("agency_id NOT IN ('0')");
        $this->db->order_by("agency_id", "asc");
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    function selectRegionList() {
        $this->db->select('*');
        $this->db->from('pm_region');
        $this->db->order_by('region_id', 'asc');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    function selectCityList($region_id) {
        $this->db->select('*');
        $this->db->from('pm_city');
        $this->db->where('fk_region_id', 0);
        $this->db->or_where('fk_region_id', $region_id);
        $this->db->order_by('city_id', 'asc');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    function selectSuburbList($city_id) {
        $this->db->select('*');
        $this->db->from('pm_suburb');
        $this->db->where('fk_city_id', 0);
        $this->db->or_where('fk_city_id', $city_id);
        $this->db->order_by('suburb_id', 'asc');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    function selectSuburbListAll() {
        $this->db->select('*');
        $this->db->from('pm_suburb');
        $this->db->join('pm_city', 'pm_suburb.fk_city_id = pm_city.city_id', 'left outer');
        $this->db->order_by('suburb_id', 'asc');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    function selectCityListAll() {
        $this->db->select('*');
        $this->db->from('pm_city');
        $this->db->order_by('city_id', 'asc');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    function selectNewsList() {
        $this->db->select('*');
        $this->db->from('pm_news');
        $this->db->order_by("news_id", "desc");
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    function selectLanguage() {
        $this->db->select('*');
        $this->db->from('pm_language');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    function selectTargetNews($news_id) {
        $this->db->select('*');
        $this->db->from('pm_news');
        $this->db->where('news_id', $news_id);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    function selectOpenHomeVisitor($open_home_id) {
        $this->db->select('pm_customer.customer_name, pm_customer.customer_email, pm_customer.customer_mobile, pm_visitor_log.visitor_indate');
        $this->db->from('pm_visitor_log');
        $this->db->join('pm_customer', 'pm_visitor_log.fk_customer_id = pm_customer.customer_id', 'left outer');
        $this->db->where('fk_open_home_id', $open_home_id);
        $query = $this->db->get();
//        $result = $query->result_array();
        return $query;
    }

    function selectAjaxOpenHomeVisitor($open_home_id) {
        $this->db->select('pm_customer.customer_name, pm_customer.customer_email, pm_customer.customer_mobile, pm_visitor_log.visitor_indate');
        $this->db->from('pm_visitor_log');
        $this->db->join('pm_customer', 'pm_visitor_log.fk_customer_id = pm_customer.customer_id', 'left outer');
        $this->db->where('fk_open_home_id', $open_home_id);
        $query = $this->db->get();
        $result = $query->result_array();
        return $query;
    }

    function selectCntVisitor($customer_id, $open_home_id) {
        $this->db->select('*');
        $this->db->from('pm_visitor_log');
        $this->db->where('fk_customer_id', $customer_id);
        $this->db->where('fk_open_home_id', $open_home_id);
        $result = $this->db->count_all_results();
        return $result;
    }

    // thilan 7-05-2018

    function get_suburb_from_id($subid) {
        $query = $this->db->get_where("pm_suburb", array("suburb_id" => $subid));
        $result = $query->result();
        return $result[0];
    }

    function get_city_by_id($cid) {
        $query = $this->db->get_where("pm_city", array("city_id" => $cid));
        $result = $query->result();
        return $result[0];
    }

    function get_region_by_id($rid) {
        $query = $this->db->get_where("pm_region", array("region_id" => $rid));
        $result = $query->result();
        return $result[0];
    }

    function getNoticeList() {
        $this->db->select('pm_notice.*,pm_customer.customer_fb_token,pm_customer.social_token,pm_customer.notification_token');
        $this->db->from('pm_notice');
        $this->db->join('pm_customer', 'pm_customer.customer_id = pm_notice.fk_customer_id', 'left outer');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    function sendAuction3DayNoticeList() {
        $this->db->select('pm_property_pic.property_pic,pm_customer.customer_id,pm_customer.notification_token,pm_property.*,pm_location.*,COUNT(pm_max_list.fk_property_id) AS property ');
        $this->db->from('pm_max_list');
        $this->db->join('pm_customer', 'pm_customer.customer_id = pm_max_list.fk_customer_id', 'left outer');
        $this->db->join('pm_property', 'pm_property.property_id = pm_max_list.fk_property_id', 'left outer');
        $this->db->join('pm_location', 'pm_location.location_id = pm_property.fk_location_id', 'left outer');
        $this->db->join('pm_property_pic', 'pm_property_pic.fk_property_id = pm_property.property_id', 'left outer');
        $this->db->where('pm_customer.notifi_auction_3days', 1);
        $this->db->where('pm_property.property_auction', 1);
        $this->db->where('pm_property_pic.property_first_pic', 1);
        //$this->db->where('DATEDIFF(DATE_FORMAT(pm_property.property_auction_date,"%Y-%m-%d"),DATE_FORMAT(ADDTIME(now(),"12:00:00"),"%Y-%m-%d")) = 3');
        //$this->db->where('DATEDIFF(pm_property.property_auction_date,ADDTIME(now(),"12:00:00")) = 3');
        $this->db->where('DATEDIFF(DATE_FORMAT(pm_property.property_auction_date,"%Y-%m-%d"),DATE_FORMAT(now(),"%Y-%m-%d")) = 3');
        $this->db->group_by('pm_customer.customer_id,pm_customer.notification_token');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    function getAuction3DayNoticeList($customerId, $sentDate) {
        $this->db->select('pm_property_pic.property_pic,b.customer_id,b.notification_token,c.*,d.*,e.* ');
        $this->db->from('pm_max_list a');
        $this->db->join('pm_customer b', 'b.customer_id = a.fk_customer_id', 'left outer');
        $this->db->join('pm_property c', 'c.property_id = a.fk_property_id', 'left outer');
        $this->db->join('pm_location d', 'd.location_id = c.fk_location_id', 'left outer');
        $this->db->join('pm_property_pic', 'pm_property_pic.fk_property_id = c.property_id', 'left outer');
        $this->db->join('pm_suburb e', 'e.suburb_id = d.fk_suburb_id', 'left outer');
        $this->db->where('b.notifi_auction_3days', 1);
        $this->db->where('c.property_auction', 1);
        $this->db->where('pm_property_pic.property_first_pic', 1);
        if ($sentDate != "") {
            $this->db->where('DATEDIFF(c.property_auction_date,"' . $sentDate . '") = 3');
        } else {
            $this->db->where('DATEDIFF(DATE_FORMAT(c.property_auction_date,"%Y-%m-%d"),DATE_FORMAT(now(),"%Y-%m-%d")) = 3');
            //$this->db->where('DATEDIFF(c.property_auction_date,ADDTIME(now(),"12:00:00")) = 3');
        }
        if ($customerId != "") {
            $this->db->where('a.fk_customer_id', $customerId);
        }
        //$this->db->group_by('pm_customer.customer_id,pm_customer.notification_token');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    function sendAuctionDayNoticeList() {
        $this->db->select('pm_property_pic.property_pic,pm_customer.customer_id,pm_customer.notification_token,pm_property.*,pm_location.*,COUNT(pm_max_list.fk_property_id) AS property ');
        $this->db->from('pm_max_list');
        $this->db->join('pm_customer', 'pm_customer.customer_id = pm_max_list.fk_customer_id', 'left outer');
        $this->db->join('pm_property', 'pm_property.property_id = pm_max_list.fk_property_id', 'left outer');
        $this->db->join('pm_location', 'pm_location.location_id = pm_property.fk_location_id', 'left outer');
        $this->db->join('pm_property_pic', 'pm_property_pic.fk_property_id = pm_property.property_id', 'left outer');
        $this->db->where('pm_customer.notifi_auction_day', 1);
        $this->db->where('pm_property.property_auction', 1);
        $this->db->where('pm_property_pic.property_first_pic', 1);
      //  $this->db->where('DATEDIFF(DATE_FORMAT(pm_property.property_auction_date,"%Y-%m-%d"),DATE_FORMAT(now(),"%Y-%m-%d")) = 0');
        $this->db->where('DATE_FORMAT(pm_property.property_auction_date,"%Y-%m-%d")=DATE_FORMAT(now(),"%Y-%m-%d")');
        $this->db->group_by('pm_customer.customer_id,pm_customer.notification_token');
        $query = $this->db->get();
        $result = $query->result_array();
       // print_r($this->db->last_query());
        return $result;
    }
    
    
    function sendAvailableDayNoticeList() {
        $this->db->select('pm_customer.customer_id,pm_customer.notification_token,pm_property.*,pm_location.*,COUNT(pm_max_list.fk_property_id) AS property ');
        $this->db->from('pm_max_list');
        $this->db->join('pm_customer', 'pm_customer.customer_id = pm_max_list.fk_customer_id', 'left outer');
        $this->db->join('pm_property', 'pm_property.property_id = pm_max_list.fk_property_id', 'left outer');
        $this->db->join('pm_location', 'pm_location.location_id = pm_property.fk_location_id', 'left outer');
        $this->db->where('pm_customer.notifi_available_day', 1);
        $this->db->where('pm_property.property_sale_flag', 1);
      //  $this->db->where('DATEDIFF(DATE_FORMAT(pm_property.property_auction_date,"%Y-%m-%d"),DATE_FORMAT(now(),"%Y-%m-%d")) = 0');
        $this->db->where('DATE_FORMAT(pm_property.property_available_date,"%Y-%m-%d")=DATE_FORMAT(now(),"%Y-%m-%d")');
        $this->db->group_by('pm_customer.customer_id,pm_customer.notification_token');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    function sendNewListingNoticeList($agentid) {
        $this->db->select('*');
        $this->db->from('pm_agent_list');
        $this->db->join('pm_customer', 'pm_customer.customer_id = pm_agent_list.fk_customer_id', 'left outer');
        $this->db->join('pm_agent', 'pm_agent.agent_id = pm_agent_list.fk_agent_id', 'left outer');
        $this->db->where('pm_agent_list.fk_agent_id', $agentid);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }
    
    

    function getAuctionDayNoticeList($customerId, $sentDate) {
        $this->db->select('pm_property_pic.property_pic,pm_customer.customer_id,pm_customer.notification_token,pm_property.*,pm_location.*,pm_suburb.* ');
        $this->db->from('pm_max_list');
        $this->db->join('pm_customer', 'pm_customer.customer_id = pm_max_list.fk_customer_id', 'left outer');
        $this->db->join('pm_property', 'pm_property.property_id = pm_max_list.fk_property_id', 'left outer');
        $this->db->join('pm_location', 'pm_location.location_id = pm_property.fk_location_id', 'left outer');
        $this->db->join('pm_property_pic', 'pm_property_pic.fk_property_id = pm_property.property_id', 'left outer');
        $this->db->join('pm_suburb', 'pm_suburb.suburb_id = pm_location.fk_suburb_id', 'left outer');
        $this->db->where('pm_customer.notifi_auction_day', 1);
        $this->db->where('pm_property.property_auction', 1);
        $this->db->where('pm_property_pic.property_first_pic', 1);
        if ($sentDate != "") {
            $this->db->where('DATEDIFF(pm_property.property_auction_date,"' . $sentDate . '") = 0');
        } else {
            //$this->db->where('DATEDIFF(DATE_FORMAT(pm_property.property_auction_date,"%Y-%m-%d"),DATE_FORMAT(ADDTIME(now(),"12:00:00"),"%Y-%m-%d")) = 0');
        $this->db->where('DATE_FORMAT(pm_property.property_auction_date,"%Y-%m-%d")=DATE_FORMAT(now(),"%Y-%m-%d")');
            
        }
        if ($customerId != "") {
            $this->db->where('pm_max_list.fk_customer_id', $customerId);
        }
        //$this->db->group_by('pm_customer.customer_id,pm_customer.notification_token');
        $query = $this->db->get();
        print_r($this->db->last_query());
//        exit();
        
        $result = $query->result_array();

        return $result;
    }

    function getNewListNotice($idproperty) {
        $this->db->select('pm_property_pic.*,pm_property.*,pm_location.*,pm_suburb.* ');
        $this->db->from('pm_property');
        $this->db->join('pm_location', 'pm_location.location_id = pm_property.fk_location_id', 'left outer');
        $this->db->join('pm_property_pic', 'pm_property_pic.fk_property_id = pm_property.property_id', 'left outer');
        $this->db->join('pm_suburb', 'pm_suburb.suburb_id = pm_location.fk_suburb_id', 'left outer');
        //$this->db->where('pm_customer.notifi_auction_day', 1);
        $this->db->where('property_id', $idproperty);
        $this->db->where('property_first_pic', "1");
        
        //$this->db->group_by('pm_customer.customer_id,pm_customer.notification_token');
        $query = $this->db->get();
//        print_r($this->db->last_query());
//        exit();
        
        $result = $query->result_array();
        return $result;
    }

    
        function getAvailableDayNoticeList($customerId, $sentDate) {
        $this->db->select('pm_property_pic.property_pic,pm_customer.customer_id,pm_customer.notification_token,pm_property.*,pm_location.*,pm_suburb.* ');
        $this->db->from('pm_max_list');
        $this->db->join('pm_customer', 'pm_customer.customer_id = pm_max_list.fk_customer_id', 'left outer');
        $this->db->join('pm_property', 'pm_property.property_id = pm_max_list.fk_property_id', 'left outer');
        $this->db->join('pm_location', 'pm_location.location_id = pm_property.fk_location_id', 'left outer');
        $this->db->join('pm_property_pic', 'pm_property_pic.fk_property_id = pm_property.property_id', 'left outer');
        $this->db->join('pm_suburb', 'pm_suburb.suburb_id = pm_location.fk_suburb_id', 'left outer');
        //$this->db->where('pm_customer.notifi_auction_day', 1);
        $this->db->where('pm_property.property_sale_flag', 1);
        $this->db->where('pm_property_pic.property_first_pic', 1);
        if ($sentDate != "") {
            $this->db->where('DATEDIFF(pm_property.property_available_date,"' . $sentDate . '") = 0');
        } else {
            //$this->db->where('DATEDIFF(DATE_FORMAT(pm_property.property_auction_date,"%Y-%m-%d"),DATE_FORMAT(ADDTIME(now(),"12:00:00"),"%Y-%m-%d")) = 0');
        $this->db->where('DATE_FORMAT(pm_property.property_available_date,"%Y-%m-%d")=DATE_FORMAT(now(),"%Y-%m-%d")');
            
        }
        if ($customerId != "") {
            $this->db->where('pm_max_list.fk_customer_id', $customerId);
        }
        //$this->db->group_by('pm_customer.customer_id,pm_customer.notification_token');
        $query = $this->db->get();
//        print_r($this->db->last_query());
//        exit();
        
        $result = $query->result_array();
        return $result;
    }

    function sendUpdateNoticeList() {
        $this->db->select('pm_customer.customer_id,pm_customer.notification_token,pm_property.*,pm_location.*,COUNT(pm_max_list.fk_property_id) AS property ');
        $this->db->from('pm_max_list');
        $this->db->join('pm_customer', 'pm_customer.customer_id = pm_max_list.fk_customer_id', 'left outer');
        $this->db->join('pm_property', 'pm_property.property_id = pm_max_list.fk_property_id', 'left outer');
        $this->db->join('pm_location', 'pm_location.location_id = pm_property.fk_location_id', 'left outer');
        $this->db->where('pm_customer.notifi_agent_updates', 1);
        //$this->db->where('pm_property.property_auction', 1);
        $this->db->where('DATEDIFF(DATE_FORMAT(ADDTIME(pm_property.property_update,"12:00:00"),"%Y-%m-%d"),DATE_FORMAT(ADDTIME(now(),"12:00:00"),"%Y-%m-%d")) = -1');

        $this->db->group_by('pm_customer.customer_id,pm_customer.notification_token');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    function getUpdateNoticeList($customerId, $sentDate) {
        $this->db->select('pm_customer.customer_id,pm_customer.notification_token,pm_property.*,pm_location.* ');
        $this->db->from('pm_max_list');
        $this->db->join('pm_customer', 'pm_customer.customer_id = pm_max_list.fk_customer_id', 'left outer');
        $this->db->join('pm_property', 'pm_property.property_id = pm_max_list.fk_property_id', 'left outer');
        $this->db->join('pm_location', 'pm_location.location_id = pm_property.fk_location_id', 'left outer');
        $this->db->where('pm_customer.notifi_agent_updates', 1);
        $this->db->where('pm_property.notifi_available_day', 1);
        if ($sentDate != "") {
            $this->db->where('DATEDIFF(ADDTIME(pm_property.property_update,"12:00:00"),"' . $sentDate . '") = -1');
        } else {
            $this->db->where('DATEDIFF(DATE_FORMAT(ADDTIME(pm_property.property_update,"12:00:00"),"%Y-%m-%d"),DATE_FORMAT(ADDTIME(now(),"12:00:00"),"%Y-%m-%d")) = -1');
        }
        if ($customerId != "") {
            $this->db->where('pm_max_list.fk_customer_id', $customerId);
        }
        //$this->db->group_by('pm_customer.customer_id,pm_customer.notification_token');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    function getSavedSearchListW($customerId, $searchId) {

        $this->db->select('pm_customer.customer_id,pm_customer.notification_token,pm_location.fk_suburb_id,pm_location.fk_city_id,pm_location.fk_region_id,pm_search.* ');
        $this->db->from('pm_search_list');
        $this->db->join('pm_customer', 'pm_customer.customer_id = pm_search_list.fk_customer_id', 'left outer');
        $this->db->join('pm_search', 'pm_search.search_id = pm_search_list.fk_search_id', 'left outer');
        $this->db->join('pm_location', 'pm_location.location_id = pm_search.fk_location_id', 'left outer');
        $this->db->where('pm_customer.notifi_saved_search', 1);
        $this->db->where('pm_customer.nofifi_frequency', 0);
        $this->db->where('pm_customer.delete_flag', 0);
        $this->db->where('pm_customer.verify_flag', 0);
        if ($customerId != "") {
            $this->db->where('pm_search_list.fk_customer_id', $customerId);
        }
        if ($searchId != "") {
            $this->db->where('pm_search_list.fk_search_id', $searchId);
        }

        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    function getSavedSearchListF($customerId, $searchId) {

        $this->db->select('pm_customer.customer_id,pm_customer.notification_token,pm_location.fk_suburb_id,pm_location.fk_city_id,pm_location.fk_region_id,pm_search.* ');
        $this->db->from('pm_search_list');
        $this->db->join('pm_customer', 'pm_customer.customer_id = pm_search_list.fk_customer_id', 'left outer');
        $this->db->join('pm_search', 'pm_search.search_id = pm_search_list.fk_search_id', 'left outer');
        $this->db->join('pm_location', 'pm_location.location_id = pm_search.fk_location_id', 'left outer');
        $this->db->where('pm_customer.notifi_saved_search', 1);
        $this->db->where('pm_customer.nofifi_frequency', 1);
        $this->db->where('pm_customer.delete_flag', 0);
        $this->db->where('pm_customer.verify_flag', 0);
        if ($customerId != "") {
            $this->db->where('pm_search_list.fk_customer_id', $customerId);
        }
        if ($searchId != "") {
            $this->db->where('pm_search_list.fk_search_id', $searchId);
        }
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    function getSavedSearchListD($customerId, $searchId) {

        $this->db->select('pm_customer.customer_id,pm_customer.notification_token,pm_location.fk_suburb_id,pm_location.fk_city_id,pm_location.fk_region_id,pm_search.* ');
        $this->db->from('pm_search_list');
        $this->db->join('pm_customer', 'pm_customer.customer_id = pm_search_list.fk_customer_id', 'left outer');
        $this->db->join('pm_search', 'pm_search.search_id = pm_search_list.fk_search_id', 'left outer');
        $this->db->join('pm_location', 'pm_location.location_id = pm_search.fk_location_id', 'left outer');
        $this->db->where('pm_customer.notifi_saved_search', 1);
        $this->db->where('pm_customer.nofifi_frequency', 2);
        $this->db->where('pm_customer.delete_flag', 0);
        $this->db->where('pm_customer.verify_flag', 0);
        if ($customerId != "") {
            $this->db->where('pm_search_list.fk_customer_id', $customerId);
        }
        if ($searchId != "") {
            $this->db->where('pm_search_list.fk_search_id', $searchId);
        }
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    function getSavedSearchForW($saleFlag, $priceFrom, $priceTo, $bedroomFrom, $bedroomTo, $bathroomFrom, $bathroomTo, $pet, $openNow, $available, $locationId, $cityId, $regionId, $searchId, $sentDate) {

        $this->db->select('*');
        $this->db->from('pm_property');
        $this->db->join('pm_location', 'pm_location.location_id = pm_property.fk_location_id', 'left outer');
        $this->db->where('property_sale_flag', $saleFlag);
        $this->db->where("property_hidden_price between '$priceFrom' and '$priceTo'");
        $this->db->where("property_bedroom between '$bedroomFrom' and '$bedroomTo'");
        $this->db->where("property_bathroom between '$bathroomFrom' and '$bathroomTo'");
        if ($pet != "") {
            $this->db->where('property_pet', $pet);
        }
        //$this->db->where('DATEDIFF(property_indate,CURDATE()) >= -6');
        if ($sentDate != "") {
            $this->db->where('DATEDIFF(ADDTIME(property_indate,"12:00:00"),"' . $sentDate . '") >= -6');
        } else {
            $this->db->where('DATEDIFF(DATE_FORMAT(ADDTIME(property_indate,"12:00:00"),"%Y-%m-%d"),DATE_FORMAT(ADDTIME(now(),"12:00:00"),"%Y-%m-%d")) >= -6');
        }
        //$this->db->where('notifi_frequency', 0);
        if ($cityId != null && $cityId != 0) {
            $this->db->where('pm_location.fk_city_id', $cityId);
            $this->db->where('pm_location.fk_region_id', $regionId);
            $this->db->where('pm_location.fk_suburb_id in (select fk_proprty_subrub_id from pm_subrub_save_list where fk_search_id = "' . $searchId . '")');
        }
        $this->db->where('pm_property.delete_flag', 0);
        //$this->db->group_by('property_id,property_title');
        //$this->db->where('property_pet', $openNow);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    function insertPropertyLog($insertId, $propertyId) {
        $data = array(
            'fk_notice_id' => $insertId,
            'fk_property_id' => $propertyId
        );
        return $this->db->insert('pm_notice_property_log', $data);
    }

    function insertLanguageAgent($insertId, $agentId) {
        $data = array(
            'fk_id_agent' => $insertId,
            'fk_id_language' => $agentId
        );
        return $this->db->insert('pm_agent_language', $data);
    }


    function getNoticePropertyLogList($noticeId) {
        $this->db->select('pm_property.*,pm_location.*,pm_property_type.*, pm_notice_property_log.*, pm_notice_property_log.* ');
        $this->db->from('pm_notice_property_log');
        $this->db->join('pm_property', 'pm_property.property_id = pm_notice_property_log.fk_property_id', 'left outer');
        $this->db->join('pm_location', 'pm_location.location_id = pm_property.fk_location_id', 'left outer');
        $this->db->join('pm_property_type', 'pm_property.fk_property_type_id = pm_property_type.property_type_id', 'left outer');
        $this->db->where('pm_notice_property_log.fk_notice_id', $noticeId);
        $query = $this->db->get();
        $result = $query->result_array();
        return $query;
    }

    function getSavedSearchForF($saleFlag, $priceFrom, $priceTo, $bedroomFrom, $bedroomTo, $bathroomFrom, $bathroomTo, $pet, $openNow, $available, $locationId, $cityId, $regionId, $searchId, $sentDate) {
        $this->db->select('*');
        $this->db->from('pm_property');
        $this->db->join('pm_location', 'pm_location.location_id = pm_property.fk_location_id', 'left outer');
        $this->db->where('property_sale_flag', $saleFlag);
        $this->db->where("property_hidden_price between '$priceFrom' and '$priceTo'");
        $this->db->where("property_bedroom between '$bedroomFrom' and '$bedroomTo'");
        $this->db->where("property_bathroom between '$bathroomFrom' and '$bathroomTo'");
        if ($pet != "") {
            $this->db->where("property_pet = $pet");
        }
        //$this->db->where('DATEDIFF(property_indate,CURDATE()) >= -13');
        if ($sentDate != "") {
            $this->db->where('DATEDIFF(ADDTIME(property_indate,"12:00:00"),"' . $sentDate . '") >= -13');
        } else {
            $this->db->where('DATEDIFF(DATE_FORMAT(ADDTIME(property_indate,"12:00:00"),"%Y-%m-%d"),DATE_FORMAT(ADDTIME(now(),"12:00:00"),"%Y-%m-%d")) >= -13');
        }
        //$this->db->where('notifi_frequency', 0);
        if ($cityId != null && $cityId != 0) {
            $this->db->where('pm_location.fk_city_id', $cityId);
            $this->db->where('pm_location.fk_region_id', $regionId);
            $this->db->where('pm_location.fk_suburb_id in (select fk_proprty_subrub_id from pm_subrub_save_list where fk_search_id = "' . $searchId . '")');
        }
        $this->db->where('pm_property.delete_flag', 0);
        //$this->db->where('property_pet', $openNow);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    function getSavedSearchForD($aa, $saleFlag, $priceFrom, $priceTo, $bedroomFrom, $bedroomTo, $bathroomFrom, $bathroomTo, $pet, $openNow, $available, $locationId, $cityId, $regionId, $searchId, $sentDate) {
        $this->db->select('*,' . $aa);
        $this->db->from('pm_property a');
        $this->db->join('pm_location b', 'b.location_id = a.fk_location_id', 'left outer');
        $this->db->where('a.property_sale_flag', $saleFlag);
        $this->db->where("a.property_hidden_price >= $priceFrom");
        $this->db->where("a.property_hidden_price <= $priceTo");
        //$this->db->where("a.property_bedroom between '$bedroomFrom' and '$bedroomTo'");
        $this->db->where("a.property_bedroom >= $bedroomFrom");
        $this->db->where("a.property_bedroom <= $bedroomTo");
        $this->db->where("a.property_bathroom >= $bathroomFrom");
        $this->db->where("a.property_bathroom <= $bathroomTo");
        if ($pet != "") {
            $this->db->where("a.property_pet = $pet");
        }
        //$this->db->where('DATEDIFF(property_indate,CURDATE()) = -1');
        if ($sentDate != "") {
            $this->db->where('DATEDIFF(ADDTIME(a.property_indate,"12:00:00"),"' . $sentDate . '") >= -1');
        } else {
            $this->db->where('DATEDIFF(DATE_FORMAT(ADDTIME(a.property_indate,"12:00:00"),"%Y-%m-%d"),DATE_FORMAT(ADDTIME(now(),"12:00:00"),"%Y-%m-%d")) >= -1');
        }
        //$this->db->where('notifi_frequency', 0);
        if ($cityId != null && $cityId != 0) {
            $this->db->where("b.fk_city_id = $cityId");
            $this->db->where("b.fk_region_id = $regionId");
            $this->db->where('b.fk_suburb_id in (select fk_proprty_subrub_id from pm_subrub_save_list where fk_search_id = "' . $searchId . '")');
        }
        $this->db->where('a.delete_flag', 0);
        //$this->db->where('property_pet', $openNow);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    function getTokenNum($customerId) {
        $this->db->select('*');
        $this->db->from('pm_customer');
        $this->db->where('customer_id', $customerId);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    function getNoticeLogList($noticeId) {

        $this->db->select('*');
        $this->db->from('pm_notice_log');
        //$this->db->where('fk_customer_id', $customerId);
        $this->db->where('id', $noticeId);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    function getPushNoticList($customerId) {

        $this->db->select('*');
        $this->db->from('pm_notice_log');
        $this->db->where('fk_customer_id', $customerId);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    function getConfig() {
        $this->db->select('*');
        $this->db->from('pm_config');
        $this->db->where('config_id', 1);
        $query = $this->db->get();
        return $query;
    }

    function getPersonalListings($customerid) {
        $this->db->select('*');
        $this->db->from('pm_property');
        $this->db->join('pm_property_pic', 'pm_property_pic.fk_property_id = pm_property.property_id');
        $this->db->join('pm_location', 'pm_location.location_id = pm_property.fk_location_id');
        if (isset($_SESSION["SESS_CUSTOMER_AGENT"])) {
            if ($_SESSION["SESS_CUSTOMER_AGENT"] == 0) {
                $this->db->join('pm_order', 'pm_order.propertyid = pm_property.property_id');
            }
        }

        //$this->db->where('pm_property_pic.property_first_pic', 1);
        $this->db->where('pm_property.property_disable', 0);
        // $this->db->where('pm_property.delete_flag', 0);
        $this->db->where_in('pm_property.delete_flag', array(0, 2));
        $this->db->where('pm_property.fk_customer_id', $customerid);
        $this->db->order_by("pm_property.property_id", "desc");
        $query = $this->db->get();
        //echo $this->db->last_query();
        //exit();
        return $query->result();
    }

    function chkPropertyType($property_type_id) {
        $this->db->select('
            pm_property_type.property_type_id,
            count(pm_property_type.property_type_id) as chk_property_type,
            pm_property_type.property_type_name
        ');
        $this->db->from('pm_property_type');
        $this->db->where('pm_property_type.property_type_id', $property_type_id);
        $query = $this->db->get();
        $result = $query->result();
        return $result[0];
    }

    function getBrand() {
        $this->db->select('*');
        $this->db->from('pm_brand');
        $this->db->where('pm_brand.delete_flag', 0);
        $query = $this->db->get();
        return $query;
    }
    
    function getSubContact($customer_id) {
        $this->db->select('
            pm_sub_contact.*
        ');
        $this->db->from('pm_sub_contact');
        $this->db->where('pm_sub_contact.fk_customer_id', $customer_id);
        $this->db->order_by("pm_sub_contact.sub_contact_id", "desc");
        $query = $this->db->get();
        return $query;
    }


    /**
     * CRUD - Insert
     */
    function insertLocation($data) {
        $this->db->insert('pm_location', $data);
        return $this->db->insert_id();
    }

    function insertNews($data) {
        return $this->db->insert('pm_news', $data);
    }

    function insertVisitor($data) {
        return $this->db->insert('pm_visitor_log', $data);
    }

    function insertLog($data) {
        return $this->db->insert('pm_log', $data);
    }

    function insertTokenLog($data) {
        return $this->db->insert('pm_token_log', $data);
    }



    function insertNotice($data) {
        return $this->db->insert('pm_notice', $data);
    }

    function insertNoticeLog($data) {
        $this->db->insert('pm_notice_log', $data);
        return $this->db->insert_id();
    }

    function insertBrand($data) {
        return $this->db->insert('pm_brand', $data);
    }

    function insertSubContact($data) {
        return $this->db->insert('pm_sub_contact', $data);
    }

    function insertUserEmail($email) {
        return $this->db->insert('pm_email_landing', $email);
    }


    /**
     * CRUD - Update
     */
    function updateConfig($data) {
        $this->db->where('config_id', 1);
        return $this->db->update('pm_config', $data);
    }

    function updateLocation($data, $location_id) {
        $this->db->where('location_id', $location_id);
        return $this->db->update('pm_location', $data);
    }

    function updateAgent($data, $agent_id) {
        $this->db->where('agent_id', $agent_id);
        return $this->db->update('pm_agent', $data);
    }

    function updateNews($data, $news_id) {
        $this->db->where('news_id', $news_id);
        return $this->db->update('pm_news', $data);
    }

    function updateProperty($data, $property_id) {
        $this->db->where('property_id', $property_id);
        return $this->db->update('pm_property', $data);
    }

    function updateCronTabExpireList($sales_listing_term, $rental_listing_term) {
        $sql1 = "
			UPDATE pm_property LEFT JOIN pm_order ON 
			pm_property.property_id = pm_order.propertyid
			SET pm_property.delete_flag = 2, pm_order.expired_flag = 1
			WHERE pm_property.delete_flag = 0
			AND pm_property.property_sale_flag = 0
			AND pm_order.expired_flag = 0
			AND pm_order.start_date < SUBDATE(NOW(), INTERVAL ? WEEK);
        ";
        $query1 = $this->db->query($sql1, array($sales_listing_term));

        $sql2 = "
			UPDATE pm_property LEFT JOIN pm_order ON 
			pm_property.property_id = pm_order.propertyid
			SET pm_property.delete_flag = 2, pm_order.expired_flag = 1
			WHERE pm_property.delete_flag = 0
			AND pm_property.property_sale_flag = 1
			AND pm_order.expired_flag = 0
			AND pm_order.start_date < SUBDATE(NOW(), INTERVAL ? WEEK);
        ";
        $query2 = $this->db->query($sql2, array($rental_listing_term));

        $result = $query1 + $query2;
        return $result;
    }

    function chkCronTabExpireCoupon() {
        $this->db->select('*');
        $this->db->from('pm_coupon');
        $this->db->where('co_active', 0);
        $this->db->where('pm_coupon.co_expdate < NOW()');
        $query = $this->db->get();
        return $query;
    }

    function updateCronTabExpireCoupon() {
        $data = array();
        $data['co_active'] = 1;
        $this->db->where('co_active', 0);
        $this->db->where('pm_coupon.co_expdate < NOW()');
        return $this->db->update('pm_coupon', $data);
    }

    function updateSubContact($data, $sub_contact_id) {
        $this->db->where('pm_sub_contact.sub_contact_id', $sub_contact_id);
        return $this->db->update('pm_sub_contact', $data);
    }


    /**
     * CRUD - Delete
     */
    function deleteLocation($location_id) {
        $this->db->where('location_id', $location_id);
        return $this->db->delete('pm_location');
    }


    function deleteLanguagesAgent($agent_id) {
        $this->db->where('fk_id_agent', $agent_id);
        return $this->db->delete('pm_agent_language');
    }

    function deleteImage($image_id) {
        $this->db->where('property_pic_id', $image_id);
        return $this->db->delete('pm_property_pic');
    }

    function deleteAgent($agent_id) {
        $this->db->where('agent_id', $agent_id);
        return $this->db->delete('pm_agent');
    }

    function deleteNews($news_id) {
        $this->db->where('news_id', $news_id);
        return $this->db->delete('pm_news');
    }

    function deleteNotice($notice_id) {
        $this->db->where('notice_id', $notice_id);
        return $this->db->delete('pm_notice');
    }

}
