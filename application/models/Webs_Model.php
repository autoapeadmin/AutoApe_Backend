<?php

defined('BASEPATH') OR exit('No direct script access allowed');

    /**
     * PropertiMax
	 * @author Edward An
	 */
	 
class Webs_Model extends CI_Model {

    function __construct() {
        //parent::__construct();
        $this->load->database();
    }


    /**
     * CRUD - Select
     */

    // getting the chat list of the current user
    function selectAgentListLimit($limit) {
        $this->db->select('*');
        $this->db->from('pm_agent');
        $this->db->order_by('pm_agent.agent_id', 'RANDOM');
        $this->db->limit($limit);
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }

    function selectPropertyRecentListLimit($limit) {
        $this->db->select('*');
        $this->db->from('pm_property');
        $this->db->join('pm_property_type', 'pm_property.fk_property_type_id = pm_property_type.property_type_id', 'left outer');
        $this->db->join('pm_location', 'pm_property.fk_location_id = pm_location.location_id');
        $this->db->where('pm_property.delete_flag', 0);
        $this->db->order_by('pm_property.property_indate', 'DESC');
        $this->db->limit($limit);
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }

    function selectSearchAgentList($key_word) {
        $this->db->select('
            pm_agent.agent_id, pm_agent.agent_pic, pm_agent.agent_first_name, 
            pm_agent.agent_last_name, pm_agent.agent_mobile, pm_agent.agent_phone, pm_agent.agent_office, 
            pm_agency.*
        ');
        $this->db->from('pm_agent');
        $this->db->join('pm_agency', 'pm_agency.agency_id = pm_agent.fk_agency_id');
        $this->db->where('pm_agent.delete_flag', 0);
        $this->db->like('pm_agent.agent_first_name', $key_word, 'both');
        $this->db->or_like('pm_agent.agent_last_name', $key_word, 'both');
        $this->db->or_like('pm_agent.agent_mobile', $key_word, 'both');
        $this->db->or_like('pm_agent.agent_phone', $key_word, 'both');
        $this->db->or_like('pm_agent.agent_office', $key_word, 'both');

        $this->db->order_by('pm_agent.agent_id', 'DESC');

        $query = $this->db->get();
        $result = $query->result_array();
        
        return $result;
    }

}
